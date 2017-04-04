<?php
namespace SquadIT\WebApp\Controller;

/*
 * This file is part of the SquadIT.WebApp package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\AccountFactory;
use TYPO3\Flow\Security\Cryptography\HashService;
use SquadIT\WebApp\Domain\Model\User;
use SquadIT\WebApp\Utility\CircleImageFilter;

class UserController extends AbstractUserAwareActionController
{
    /**
     * @Flow\Inject
     * @var AccountFactory
     */
    protected $accountFactory;


    /**
     * @Flow\Inject
     * @var HashService
     */
    protected $hashService;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Utility\Environment
     */
    protected $environment;

    /**
     * @var \Imagine\Image\ImagineInterface
     * @Flow\Inject(lazy = false)
     */
    protected $imagineService;

    /**
     * @var \TYPO3\Flow\Resource\ResourceManager
     * @Flow\Inject
     */
    protected $resourceManager;

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('account', $this->securityContext->getAccount());
    }

    /**
     * Shows a registration form
     *
     * @return void
     */
    public function registerAction()
    {
    }

    /**
     * Create a User and an account for login
     *
     * @param string $firstname
     * @Flow\Validate(argumentName="firstname", type="NotEmpty")
     * @param string $lastname
     * @Flow\Validate(argumentName="firstname", type="NotEmpty")
     * @param string $email
     * @Flow\Validate(argumentName="firstname", type="NotEmpty")
     * @Flow\Validate(argumentName="email", type="EmailAddress")
     * @param string $password
     * @Flow\Validate(argumentName="firstname", type="NotEmpty")
     * @param string $passwordRepeat
     * @Flow\Validate(argumentName="firstname", type="NotEmpty")
     * @return void
     */
    public function createAction($firstname, $lastname, $email, $password, $passwordRepeat)
    {
        if ($password !== $passwordRepeat) {
            $this->addFlashMessage('The entered passwords are not identical', 'Error', Message::SEVERITY_ERROR);
            $this->redirect('register');
        }

        /** @var Account $account */
        $account = $this->accountFactory->createAccountWithPassword($email, $password);
        $this->accountRepository->add($account);

        $user = new User($firstname, $lastname, $account);
        $this->userRepository->add($user);

        /*
         * Perform login for the created account
         */

        /** @var \TYPO3\Flow\Security\Authentication\Token\UsernamePassword */
        $authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
        if (count($authenticationTokens) === 1) {
            $authenticationTokens[0]->setAccount($account);
            $authenticationTokens[0]->setAuthenticationStatus(\TYPO3\Flow\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
        }

        $this->addFlashMessage('Registration successful');
        $this->redirect('index', 'standard');
    }

    /**
     * Update the user
     *
     * @param User $user
     * @return void
     */
    public function updateAction($user)
    {
        $this->userRepository->update($user);

        $resourceUri = $user->getProfilepicture()->createTemporaryLocalCopy();
        $resultingFileExtension = $user->getProfilepicture()->getFileExtension();
        $transformedImageTemporaryPath = $this->environment->getPathToTemporaryDirectory() . uniqid('ProcessedImage-') . '.' . $resultingFileExtension;

        if (!file_exists($resourceUri)) {
            throw new \TYPO3\Flow\Exception(sprintf('An error occurred while transforming an image: the resource data of the original image does not exist (%s, %s).', $originalResource->getSha1(), $resourceUri), 1374848224);
        }

        $imagineImage = $this->imagineService->open($resourceUri);
        $circleFilter = new CircleImageFilter($this->imagineService, new \Imagine\Image\Box(200,200));
        $circleFilter->apply($imagineImage)->save($transformedImageTemporaryPath);

        $resource = $this->resourceManager->importResource($transformedImageTemporaryPath, $user->getProfilepicture()->getCollectionName());
        unlink($transformedImageTemporaryPath);

        $user->setProfilepicture($resource);
        $this->userRepository->update($user);

        $this->addFlashMessage('Updated your profile');
        $this->forward('index');
    }

    /**
     * Changes the password of the account
     *
     * @param Account $account
     * @param string $password
     * @param string $passwordRepeat
     *
     * @return void
     */
    public function changePasswordAction($account, $password, $passwordRepeat)
    {
        if ($password != $passwordRepeat) {
            $this->addFlashMessage('The entered passwords are not identical', '', Message::SEVERITY_ERROR);
            $referrer = $this->request->getInternalArgument('__referrer');
            $this->redirect($referrer['@action'], $referrer['@controller']);
        }
        //$account = $this->accountRepository->find($account);
        $account->setCredentialsSource($this->hashService->hashPassword($password, 'default'));
        $this->accountRepository->update($account);

        $this->addFlashMessage('Successfully changed your password');

        $referrer = $this->request->getInternalArgument('__referrer');
        $this->redirect($referrer['@action'], $referrer['@controller']);
    }

    /**
     * Deletes an account and the corresponding user
     *
     * @param boolean $confirm
     * @return void
     */
    public function deleteAction($confirm)
    {
        if (!$confirm) {
            $this->redirect('index');
        }

        $this->resourceManager->deleteResource($this->user->getProfilepicture());
        $this->userRepository->remove($this->user);
        $this->accountRepository->remove($this->securityContext->getAccount());
        $this->addFlashMessage('Your account has been deleted');
        $this->redirect('logout', 'Authentication');
    }
}
