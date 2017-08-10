<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ReportsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ReportsTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Reports', 'Users'];
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
        $classes = ['Reports', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test Report add page
     *
     * @return void
     */
    public function testAddReportsPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/reports/add');
        $this->assertResponseOk();
    }

    /**
     * Test Report index page
     *
     * @return void
     */
    public function testIndexReports()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/reports');
        $this->assertResponseOk();

        $reports = $this->Reports->find('list')->toArray();

        if ($reports > 0) {
            foreach ($reports as $report) {
                $this->assertResponseContains("$report");
            }
            return;
        }
    }
}
