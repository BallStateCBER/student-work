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
        $classes = ['Projects', 'Reports', 'Users'];
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
        $classes = ['Projects', 'Reports', 'Users'];
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
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

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
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

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

    /**
     * Test current Report index page
     *
     * @return void
     */
    public function testIndexCurrentReports()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/reports/current');
        $this->assertResponseOk();
    }

    /**
     * Test past Report index page
     *
     * @return void
     */
    public function testIndexPastReports()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/reports/past');
        $this->assertResponseOk();
    }

    /**
     * Test project Report index page
     *
     * @return void
     */
    public function testIndexReportsByProjects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $project = $this->Projects->find()
            ->first();
        $this->get("/reports/project/$project->id");
        $this->assertResponseOk();
    }

    /**
     * Test student Report index page
     *
     * @return void
     */
    public function testIndexReportsByStudents()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get("/reports/student/$id");
        $this->assertResponseOk();
    }

    /**
     * Test supervisor Report index page
     *
     * @return void
     */
    public function testIndexReportsBySupervisors()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get("/reports/supervisor/$id");
        $this->assertResponseOk();
    }

    /**
     * Test getStudentCurrentReports method
     *
     * @return void
     */
    public function testGetStudentCurrentReports()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $reports = $this->Reports->getStudentCurrentReports($id);
        $this->assertEquals([], $reports);
    }

    /**
     * Test getStudentCurrentReportsByProject method
     *
     * @return void
     */
    public function testGetStudentCurrentReportsByProject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $project = $this->Projects->find()->first();
        $reports = $this->Reports->getStudentCurrentReportsByProject($id, $project->id);
        $this->assertEquals([], $reports);
    }
}
