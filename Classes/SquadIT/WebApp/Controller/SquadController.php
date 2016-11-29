<?php
namespace SquadIT\WebApp\Controller;

/*
 * This file is part of the SquadIT.WebApp package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use SquadIT\WebApp\Domain\Model\Squad;

class SquadController extends ActionController
{

    /**
     * @Flow\Inject
     * @var \SquadIT\WebApp\Domain\Repository\SquadRepository
     */
    protected $squadRepository;

    /**
     * @Flow\Inject
     * @var \SquadIT\WebApp\Domain\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Context
     */
    protected $securityContext;

    /**
     * @var \SquadIT\WebApp\Domain\Model\User
     */
    protected $user;

    /**
     * @param \TYPO3\Flow\Mvc\View\ViewInterface $view
     * @return void
     */
    public function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view)
    {
        $this->user = $this->userRepository->findOneByAccount($this->securityContext->getAccount());
        if ($this->user === null) {
            return;
        }
        $view->assign('user_firstname', $this->user->getFirstname());
        $view->assign('user_initials', substr($this->user->getFirstname(), 0, 1) . substr($this->user->getLastname(), 0, 1));
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('squads', $this->squadRepository->findAll());
    }

    /**
     * @param \SquadIT\WebApp\Domain\Model\Squad $squad
     * @return void
     */
    public function showAction(Squad $squad)
    {
        $this->view->assign('squad', $squad);
    }

    /**
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * @param \SquadIT\WebApp\Domain\Model\Squad $newSquad
     * @return void
     */
    public function createAction(Squad $newSquad)
    {
        $this->squadRepository->add($newSquad);
        $this->addFlashMessage('Created a new squad.');
        $this->redirect('index');
    }

    /**
     * @param \SquadIT\WebApp\Domain\Model\Squad $squad
     * @return void
     */
    public function editAction(Squad $squad)
    {
        $this->view->assign('squad', $squad);
    }

    /**
     * @param \SquadIT\WebApp\Domain\Model\Squad $squad
     * @return void
     */
    public function updateAction(Squad $squad)
    {
        $this->squadRepository->update($squad);
        $this->addFlashMessage('Updated the squad.');
        $this->redirect('index');
    }

    /**
     * @param \SquadIT\WebApp\Domain\Model\Squad $squad
     * @return void
     */
    public function deleteAction(Squad $squad)
    {
        $this->squadRepository->remove($squad);
        $this->addFlashMessage('Deleted a squad.');
        $this->redirect('index');
    }
}
