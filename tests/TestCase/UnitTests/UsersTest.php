<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTest extends IntegrationTestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test getEmailFromId method
     *
     * @return void
     */
    public function testGetEmailFromId()
    {
        $user = $this->Users->find()
            ->where(['name' => 'Erica Dee Fox'])
            ->first();

        $email = $this->Users->getEmailFromId($user->id);

        $this->assertEquals($user->email, $email);
    }

    /**
     * Test getIdFromEmail method
     *
     * @return void
     */
    public function testGetIdFromEmail()
    {
        $user = $this->Users->find()
            ->where(['name' => 'Erica Dee Fox'])
            ->first();

        $id = $this->Users->getIdFromEmail($user->email);

        $this->assertEquals($user->id, $id);
    }

    /**
     * Test getResetPasswordHash method
     *
     * @return void
     */
    public function testGetResetPasswordHash()
    {
        $user = $this->Users->find()
            ->where(['name' => 'Erica Dee Fox'])
            ->first();

        $hash = $this->Users->getResetPasswordHash($user->id, $user->email);

        $this->assertEquals(md5($user->id.$user->email.Configure::read('password_reset_salt').date('my')), $hash);
    }

    /**
     * Test sendPasswordResetEmail
     *
     * @return void
     */
    public function testSendPasswordResetEmail()
    {
        $user = $this->Users->find()
            ->where(['name' => 'Erica Dee Fox'])
            ->first();

        // unfortunately, everything email-related must stay a test until the proper email settings can be configured
        $this->markTestIncomplete();

    #    $email = $this->Users->sendPasswordResetEmail($user->id, $user->email);
    #    $email = implode($email);

#        $resetPasswordHash = $this->Users->getResetPasswordHash($user->id, $user->email);

#        $this->assertTextContains($resetPasswordHash, $email);
    }

    /**
     * Test viewing an employee
     *
     * @return void
     */
    public function testViewingAnEmployee()
    {
        $user = $this->Users->find()
            ->where(['name' => 'Erica Dee Fox'])
            ->first();

        $id = $this->Users->getIdFromEmail($user->email);

        $this->get("/employee/$id");
        $this->assertResponseContains($user->email);
        $this->assertResponseContains($user->position);
        $this->assertResponseContains($user->bio);
    }

    /**
     * Test viewing the employee index
     *
     * @return void
     */
    public function testViewingEmployeeIndex()
    {
        $user = $this->Users->find()
            ->where(['name' => 'Erica Dee Fox'])
            ->first();

        $id = $this->Users->getIdFromEmail($user->email);

        $this->get("/employees");
        $this->assertResponseContains($user->email);
        $this->assertResponseContains($user->position);
        $this->assertResponseContains($user->name);
    }
}
