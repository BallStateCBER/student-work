<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LocalprojectsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class LocalprojectsTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Localprojects', 'Users'];
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
        $classes = ['Localprojects', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test Localproject add page
     *
     * @return void
     */
    public function testAddLocalprojectsPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/localprojects/add');
        $this->assertResponseOk();
    }

    /**
     * Test Localproject index page
     *
     * @return void
     */
    public function testIndexLocalprojects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/localprojects');
        $this->assertResponseOk();

        $localprojects = $this->Localprojects->find('list')->toArray();

        if ($localprojects > 0) {
            foreach ($localprojects as $localproject) {
                $this->assertResponseContains("$localproject");
            }
            return;
        }

        $this->assertResponseContains("This probably suggests a problem.");
    }
}
