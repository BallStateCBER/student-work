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
     * Test report add page view & use
     *
     * @return void
     */
    public function testAddReport()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/reports/add');
        $this->assertResponseOk();

        $report = [
            'student_id' => $id,
            'supervisor_id' => $id,
            'project_name' => 'The Raven Who Refused to Sing',
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
        $this->assertResponseContains('Anything');

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
        $this->session(['Auth.User.id' => $id]);

        $report = $this->Reports->find()
            ->where(['learned' => 'Cos I deserve to occupy this space without feeling like I don\'t belong.'])
            ->firstOrFail();

        $this->get("/reports/edit/$report->id");
        $this->assertResponseOk();

        $newReport = [
            'student_id' => $id,
            'supervisor_id' => $id,
            'project_name' => 'The Raven Who Refused to Sing',
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
        $this->session(['Auth.User.id' => $id]);

        $report = $this->Reports->find()
            ->where(['learned' => "I'm sorry I'm sorry I'm sorry is gone"])
            ->firstOrFail();

        $this->get("/reports/delete/$report->id");
        $this->assertResponseSuccess();
    }
}
