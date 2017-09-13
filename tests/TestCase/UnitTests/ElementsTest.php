<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AwardsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ElementsTest extends IntegrationTestCase
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
            $config = TableRegistry::exists("$class") ? [] : ['className' => 'App\Model\Table\\' . $class . 'Table'];
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
     * Test the main page
     *
     * @return void
     */
    public function testDisplay()
    {
        $this->get('/');
        $this->assertResponseContains('Log in');
        $this->assertResponseContains('<i>You</i>');
    }

    /**
     * Test the header
     *
     * @return void
     */
    public function testHeader()
    {
        $this->get('/');
        $this->assertResponseContains('Log in');

        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/');
        $this->assertResponseContains('Log out');
    }
}
