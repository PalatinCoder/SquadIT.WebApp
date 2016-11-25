<?php
namespace SquadIT\WebApp\Tests\Behavior\Features\Bootstrap;

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use TYPO3\Flow\Security\AccountRepository;
use TYPO3\Flow\Security\AccountFactory;

/**
 * A trait containing commonly used step definitions
 */
trait CommonFeaturesTrait
{

    /**
     * @Given /^a squad "([^"]*)" exists$/
     */
    public function aSquadExists($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given /^there are users:$/
     */
    public function thereAreUsers(TableNode $table)
    {
        /** @var AccountRepository $accountRepository */
        $accountRepository = $this->objectManager->get(AccountRepository::class);
        /** @var AccountFactory $accountFactory */
        $accountFactory = $this->objectManager->get(AccountFactory::class);

        $hash = $table->getHash();
        foreach ($hash as $row) {
            $account = $accountFactory->createAccountWithPassword($row['username'], $row['password']);
            $accountRepository->add($account);
        }

        $this->getSubcontext('flow')->persistAll();
    }

    /**
     * @Given /^I am authenticated as "([^"]*)" with password "([^"]*)"$/
     */
    public function iAmAuthenticatedAsWithPassword($username, $password)
    {
        $this->visit('/authentication/login');
        $this->fillField('Email address', $username);
        $this->fillField('Password', $password);
        $this->pressButton('Login');
    }
}