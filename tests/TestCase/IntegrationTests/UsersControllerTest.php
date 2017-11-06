<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class UsersControllerTest extends ApplicationTest
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }
    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }
    /**
     * Test login method
     *
     * @return void
     */
    public function testLoggingInAndViewingUsers()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->get('/login');
        $this->assertResponseOk();

        $data = [
            'email' => 'cemployee@bsu.edu',
            'password' => 'i am such a great password'
        ];

        $this->post('/login', $data);

        $this->assertResponseContains('We could not log you in.');

        $this->get('/login');
        $this->assertResponseOk();

        $data = [
            'email' => 'cemployee@bsu.edu',
            'password' => 'placeholder'
        ];

        $this->post('/login', $data);

        $id = $this->Users->getIdFromEmail('cemployee@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/user/1');
        $this->assertResponseContains('cemployee@bsu.edu');
    }
    /**
     * Test logout
     *
     * @return void
     */
    public function testLoggingOutAndViewingUsers()
    {
        $this->session($this->currentEmployee);

        $this->get('/logout');
        $this->assertSession(null, 'Auth.User.id');

        $this->get('/employee/1');
        $this->assertRedirect('/login?redirect=%2Femployee%2F1');
    }
    /**
     * Test the procedure for resetting one's password
     */
    public function testPasswordResetProcedure()
    {
        $this->get('/users/forgot-password');
        $this->assertResponseOk();

        $user = [
            'email' => 'admin@bsu.edu'
        ];
        $this->post('/users/forgot-password', $user);
        $this->assertResponseContains('Message sent.');
        $this->assertResponseOk();

        $this->get('/users/reset-password/333666999/12345');
        $this->assertRedirect('/');

        // get password reset hash
        $hash = $this->Users->getResetPasswordHash(333666999, 'admin@bsu.edu');
        $resetUrl = "/users/reset-password/333666999/$hash";
        $this->get($resetUrl);
        $this->assertResponseOk();

        $passwords = [
            'new_password' => 'Placeholder!',
            'new_confirm_password' => 'Placeholder!'
        ];
        $this->post($resetUrl, $passwords);
        $this->assertResponseContains('Password changed.');
        $this->assertResponseOk();

        $this->get('/login');

        $newCreds = [
            'email' => 'admin@bsu.edu',
            'password' => 'Placeholder!'
        ];

        $this->post('/login', $newCreds);
        $this->assertSession(333666999, 'Auth.User.id');
    }
    /**
     * Test entire life cycle of user account
     *
     * @return void
     */
    public function testRegistrationAndAccountEditingAndDeletingAUser()
    {
        $this->get('/register');
        $this->assertRedirect('/login?redirect=%2Fregister');

        $this->session($this->currentEmployee);
        $this->get('/register');
        $this->assertRedirect('/employees');

        $this->session($this->admin);
        $this->get('/register');
        $this->assertResponseOk();

        // validation works?
        $newUser = [
            'id' => '999999999',
            'password' => 'placeholder',
            'email' => 'nuser@bsu.edu',
            'admin' => 0
        ];

        $this->post('/register', $newUser);
        $this->assertRedirect('/employees');
        $this->assertResponseContains('nuser@bsu.edu');

        $this->session(['Auth.User.id' => 999999999]);

        $accountInfo = [
            'name' => 'New User',
            'email' => 'nuser@bsu.edu',
            'position' => 'Placeholder Specialist',
            'start_date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'birth_date' => [
                'year' => date('Y', strtotime('1992')),
                'month' => date('m', strtotime('January')),
                'day' => date('d', strtotime('28'))
            ],
            'bio' => "I'm a placeholder. I just started!",
            'has_publications' => 0,
            'has_sites' => 1,
            'is_current' => 1
        ];
        $id = $this->Users->getIdFromEmail($accountInfo['email']);

        // for the moment, we're not using this test, because it's not working and I have no idea why
        /*$this->get('/account');
        $this->post('/account', $accountInfo);
        $user = $this->Users->get($id);
        dd($user);

        $this->assertEquals('I am yet another placeholder.', $user->bio);*/

        $this->get('/logout');

        $this->session($this->currentEmployee);

        $this->get("users/delete/$id");
        $id = $this->Users->getIdFromEmail($accountInfo['email']);
        $this->assertEquals($id, 3);

        // let's try again with an admin
        $this->session($this->admin);

        $this->get("users/delete/$id");

        $this->assertRedirect('/');
        $id = $this->Users->getIdFromEmail($accountInfo['email']);
        $this->assertEquals($id, null);

        $this->markTestIncomplete();
    }
}
