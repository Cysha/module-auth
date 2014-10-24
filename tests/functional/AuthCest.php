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

    public function login_using_user_record(FunctionalTester $I)
    {
        $I->wantTo('try and login with a user record');

        $authModel = \Config::get('auth.model');
        $I->amLoggedAs(with(new $authModel)->firstOrNew([]));
    }

    public function login_using_provided_details(FunctionalTester $I)
    {
        $I->wantTo('try and login with credentials');
        $I->amLoggedAs(['email' => 'xlink@cybershade.org', 'password' => 'password']);
    }
}
