<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SitesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class SitesTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Sites', 'Users'];
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
        $classes = ['Sites', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test Site add page
     *
     * @return void
     */
    public function testAddSitesPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/sites/add');
        $this->assertResponseOk();
    }

    /**
     * Test Site index page
     *
     * @return void
     */
    public function testIndexSites()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/sites');
        $this->assertResponseOk();

        $sites = $this->Sites->find('list')->toArray();

        if ($sites > 0) {
            foreach ($sites as $site) {
                $this->assertResponseContains("$site");
            }
            return;
        }

        $this->assertResponseContains("This probably suggests a problem.");
    }
}
