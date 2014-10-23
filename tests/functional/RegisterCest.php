<?php
use \FunctionalTester;

/**
 * @group m_auth
 */
class RegisterCest
{
    public function _before(FunctionalTester $I)
    {
        $I->am('guest');
        $I->amOnPage('/');
        $I->click('Register');
        $I->seeCurrentUrlEquals('/register');
    }

    // tests
    public function test_register_form_validation(FunctionalTester $I)
    {
        $I->wantTo('see if the form errors out with invalid data');

        // should fail with a validation error
        \PHPUnit_Framework_Assert::assertTrue(
            $I->seeExceptionThrown('Cysha\Modules\Core\Helpers\Forms\FormValidationException', function () use ($I) {
                $I->fillField('username', 'Test');
                $I->fillField('email', 'Tester@example.com');
                $I->fillField('password', 'testt');
                $I->fillField('password_confirmation', 'testt');
                $I->fillField('tnc', '1');
                $I->click('[type=submit]');
            })
        );
    }

    // tests
    public function test_user_can_register(FunctionalTester $I)
    {
        $I->wantTo('make sure i can register successfully');

        // run the form tester properly
        $I->fillField('username', 'Tester');
        $I->fillField('email', 'Tester@example.com');
        $I->fillField('password', 'testt');
        $I->fillField('password_confirmation', 'testt');
        $I->fillField('tnc', '1');
        $I->click('[type=submit]');

        $I->seeCurrentUrlEquals('');
        $I->see('You have successfully registered, Welcome');
        $I->seeRecord('users', [
            'username' => 'Tester',
        ]);
    }

    // tests
    // public function test_user_can_register(\AcceptanceTester $I)
    // {
    // }
}
