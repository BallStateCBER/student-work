<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AwardsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class AwardsTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Awards', 'Users'];
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
        $classes = ['Awards', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test award add page
     *
     * @return void
     */
    public function testAddAwardsPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/awards/add');
        $this->assertResponseOk();
    }
}
