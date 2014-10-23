<?php
use \FunctionalTester;


class AuthCest
{
    public function _after(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->seeCurrentUrlEquals('/');
        $I->seeAuthentication();
        $I->logout();
        $I->dontSeeAuthentication();
    }

    public function _before(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
    }

    /**
     * @group m_auth
     */
    public function loginUsingUserRecord(FunctionalTester $I)
    {
        $authModel = \Config::get('auth.model');
        $I->amLoggedAs(with(new $authModel)->firstOrNew([]));
    }

    /**
     * @group m_auth
     */
    public function loginUsingCredentials(FunctionalTester $I)
    {
        $I->amLoggedAs(['email' => 'xlink@cybershade.org', 'password' => 'password']);
    }
}
