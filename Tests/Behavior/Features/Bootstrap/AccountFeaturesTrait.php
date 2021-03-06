<?php
namespace SquadIT\WebApp\Tests\Behavior\Features\Bootstrap;

use Behat\Gherkin\Node\TableNode;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\AccountFactory;
use PHPUnit\Framework\Assert as Assert;

/**
 * A trait containing step definitions regarding accounts
 */
trait AccountFeaturesTrait
{

    /**
     * @Given /^there are accounts:$/
     */
    public function thereAreAccounts(TableNode $table)
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

    /**
     * @Then /^the account "([^"]*)" should exist$/
     */
    public function theAccountShouldExist($accountId)
    {
        /** @var AccountRepository $AccountRepository */
        $accountRepository = $this->objectManager->get(AccountRepository::class);
        $account = $accountRepository->findOneByAccountIdentifier($accountId);
        Assert::assertNotNull($account);
    }
}
