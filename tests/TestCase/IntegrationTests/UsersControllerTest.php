<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class UsersControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Users'];
        foreach ($classes as $class) {
            $config = TableRegistry::exists("$class") ? [] : ['className' => 'App\Model\Table\\'.$class.'Table'];
            $this->$class = TableRegistry::get("$class", $config);
        }
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $classes = ['Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test registration
     *
     * @return void
     */
    public function testRegistrationFormCorrectly()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);
        $this->get('/register');

        $this->assertResponseOk();

        $data = [
            'id' => 123456789,
            'email' => 'mblum@bsu.edu',
            'password' => 'letstopcheatingoneachother'
        ];

        $this->post('/register', $data);

        $this->assertResponseSuccess();

        $this->session(['Auth.User.id' => 123456789]);
        $this->get('/account');

        $moreData = [
            'name' => 'Mal Blum',
            'email' => 'mblum@bsu.edu',
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
            'role' => 'Site Admin',
            'has_publications' => 0,
            'has_sites' => 1,
            'is_current' => 1
        ];

        $this->post('/account', $moreData);

        $this->assertResponseSuccess();

        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');
        $this->assertSession($id, 'Auth.User.id');
    }

    /**
     * Test login method
     *
     * @return void
     */
    public function testLoggingIn()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->get('/login');
        $this->assertResponseOk();

        $data = [
            'email' => 'mblum@bsu.edu',
            'password' => 'i am such a great password'
        ];

        $this->post('/login', $data);

        $this->assertResponseContains('We could not log you in.');

        $this->get('/login');
        $this->assertResponseOk();

        $data = [
            'email' => 'mblum@bsu.edu',
            'password' => 'letstopcheatingoneachother'
        ];

        $this->post('/login', $data);

        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');
        $this->assertSession($id, 'Auth.User.id');
    }

    /**
     * Test editing account info
     *
     * @return void
     */
    public function testAccountInfoForUsers()
    {
        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/account');

        $userInfo = [
            'name' => 'Mal Blum',
            'email' => 'mblum@bsu.edu',
            'position' => 'Former Placeholder Specialist',
            'start_date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'end_date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'birth_date' => [
                'year' => date('Y', strtotime('1992')),
                'month' => date('m', strtotime('January')),
                'day' => date('d', strtotime('28'))
            ],
            'bio' => "I'm a placeholder. I just quit my job!!",
            'role' => 'Student',
            'has_publications' => 0,
            'has_sites' => 1,
            'is_current' => 0
        ];

        $user = $this->Users->get($id);
        $user = $this->Users->patchEntity($user, $userInfo);
        if ($this->Users->save($user)) {
            $this->assertResponseOk();
        }
    }

    /**
     * Test editing account info
     * plus file uploading
     *
     * @return void
     */
    /*public function testPhotoUploadingForUsers()
    {
        $this->session(['Auth.User.id' => $id]);

        $salt = Configure::read('profile_salt');
        $newFilename = md5('placeholder.jpg'.$salt);

        $this->get('/account');
        $userInfo = [
            'name' => 'Placeholder',
            'email' => 'mblum@bsu.edu',
            'bio' => "I'm the BEST placeholder!",
            'photo' => [
                'name' => 'placeholder.jpg',
                'type' => 'image/jpeg',
                'tmp_name' => WWW_ROOT . DS . 'img' . DS . 'users' . $newFilename,
                'error' => 4,
                'size' => 845941,
            ]
        ];
        $user = $this->Users->get($id);
        $user = $this->Users->patchEntity($user, $userInfo);
        if ($this->Users->save($user)) {
            $this->assertResponseOk();
            if ($user->photo == $newFilename) {
                return $this->assertResponseOk();
            }

            // file upload unit testing not done yet!
            $this->markTestIncomplete();
        }
    } */

    /**
     * Test logout
     *
     * @return void
     */
    public function testLoggingOut()
    {
        $this->session(['Auth.User.id' => 1]);

        $this->get('/logout');
        $this->assertSession(null, 'Auth.User.id');
    }

    /**
     * Test sending password reset email
     *
     * @return void
     */
    public function testSendingPasswordReset()
    {
        $this->get("users/forgot-password");
        $this->assertResponseOk();

        $data = [
            'email' => 'mblum@bsu.edu'
        ];

        $this->post('users/forgot-password', $data);

        $this->markTestIncomplete();
        #$this->assertResponseContains('Message sent.');
        #$this->assertResponseOk();
    }

    /**
     * Test actually resetting the password
     *
     * @return void
     */
    public function testResettingThePassword()
    {
        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');
        // what if someone's trying to fabricate a password-resetting code?
        $this->get("users/reset-password/$id/abcdefg");
        $this->assertRedirect('/');

        // get password reset hash
        $hash = $this->Users->getResetPasswordHash($id, 'mblum@bsu.edu');

        // now, this is the REAL URL for password resetting
        $resetUrl = "users/reset-password/$id/$hash";
        $this->get($resetUrl);
        $this->assertResponseOk();

        $passwords = [
            'new_password' => 'Placeholder!',
            'new_confirm_password' => 'Placeholder!'
        ];

        $this->post($resetUrl, $passwords);
        $this->assertResponseContains('Password changed.');
        $this->assertResponseOk();
    }

    /**
     * Test delete action for users
     *
     * @return void
     */
    public function testDeletingUsers()
    {
        $this->session(['Auth.User.id' => 1]);

        // delete the new user
        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');

        $this->get("employee/delete/$id");
        $this->assertResponseSuccess();
    }
}
