<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ReportsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ReportsControllerTest extends IntegrationTestCase
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
        $classes = ['Projects', 'Reports', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test report add page view & use
     *
     * @return void
     */
    public function testAddReport()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/reports/add');
        $this->assertResponseOk();

        $project = $this->Projects->getProjectByName('The Raven Who Refused to Sing');

        $report = [
            'student_id' => $user->name,
            'supervisor_id' => $user->name,
            'project_name' => $project->id,
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
            'work_performed' => 'Chained to a little house outside.',
            'routine' => 0,
            'learned' => 'Cos I deserve to occupy this space without feeling like I don\'t belong.'
        ];

        $this->post('/reports/add', $report);
        $this->assertResponseOk();
        $this->assertResponseContains("The report has been saved.");

        $report = $this->Reports->find()
            ->where(['learned' => $report['learned']])
            ->firstOrFail();

        if ($report) {
            $this->assertResponseOk();

            return;
        }
    }

    /**
     * Test report edit page
     *
     * @return void
     */
    public function testEditingReports()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $project = $this->Projects->getProjectByName('The Raven Who Refused to Sing');
        $report = $this->Reports->find()
            ->where(['learned' => 'Cos I deserve to occupy this space without feeling like I don\'t belong.'])
            ->firstOrFail();

        $this->get("/reports/edit/$report->id");
        $this->assertResponseOk();

        $newReport = [
            'student_id' => $id,
            'supervisor_id' => $id,
            'project_name' => $project->id,
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
            'work_performed' => 'Chained to a little house outside.',
            'routine' => 0,
            'learned' => "I'm sorry I'm sorry I'm sorry is gone"
        ];

        $this->post("/reports/edit/$report->id", $newReport);
        $this->assertResponseOk();

        $report = $this->Reports->find()
            ->where(['learned' => $newReport['learned']])
            ->firstOrFail();

        if ($report) {
            $this->assertResponseOk();

            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting reports
     *
     * @return void
     */
    public function testDeletingReports()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $report = $this->Reports->find()
            ->where(['learned' => "I'm sorry I'm sorry I'm sorry is gone"])
            ->firstOrFail();

        $this->get("/reports/delete/$report->id");
        $this->assertResponseSuccess();
    }
}
