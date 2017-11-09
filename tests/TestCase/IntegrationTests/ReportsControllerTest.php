<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class ReportsControllerTest extends ApplicationTest
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * testReportsIndex method
     * tests all the indexes for the reports
     *
     * @return void
     */
    public function testReportsIndexes()
    {
        $this->session($this->currentEmployee);

        // all reports
        $this->get('/reports');
        $this->assertResponseOk();

        // current reports
        $this->get('/reports/current');
        $this->assertResponseOk();
        $this->assertResponseContains('Melatonin');

        // all reports
        $this->get('/reports/past');
        $this->assertResponseOk();
        $this->assertResponseContains('Stillbirth');

        // reports by name
        foreach ($this->reports as $report) {
            $this->get("/reports/project/" . $report['project_id']);
            $project = $this->Projects->get($report['project_id']);
            $this->assertResponseContains($project['name']);
            $this->assertResponseOk();
        }

        $roles = ['student', 'supervisor'];
        foreach ($roles as $role) {
            // reports by $role
            foreach ($this->reports as $report) {
                $this->get("/reports/" . $role . "/" . $report[$role . "_id"]);
                $this->assertResponseOk();
                $person = $this->Users->get($report[$role . "_id"]);
                $this->assertResponseContains($person['name']);
            }
        }
    }

    /**
     * testReportsViews method
     *
     * @return void
     */
    public function testReportsViews()
    {
        $this->session($this->currentEmployee);
        foreach ($this->reports as $report) {
            $project = $this->Projects->get($report['project_id']);
            $this->get("/reports/view/" . $report['id']);
            #$this->assertResponseOk();
            $this->assertResponseContains($project['name']);
        }
    }

    /**
     * testReportAdding method
     *
     * @return void
     */
    public function testReportAdding()
    {
        $this->session($this->currentEmployee);
        $this->get("/reports/add");
        $this->assertResponseOk();
        $this->assertResponseContains('Add a Report');

        $formData = [
            'supervisor_id' => $this->admin['Auth']['User']['id'],
            'student_id' => $this->currentEmployee['Auth']['User']['id'],
            'project_name' => 4,
            'start_date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'work_performed' => 'Wrote the song and the bass line.',
            'learned' => 'How to do those things! :D',
            'routine' => 2
        ];

        // uh oh, this report already exists for this project!
        $this->post('/reports/add', $formData);
        $this->assertResponseContains('Report #4 has been created for the project Melatonin.');
        $this->assertResponseOk();

        $formData['project_name'] = 5;

        $this->post('/reports/add', $formData);
        $this->assertResponseContains('The report has been saved.');
        $this->assertResponseOk();

        $report = $this->Reports->find()->where(['learned' => $formData['learned']])->firstOrFail();
        $this->assertEquals($report['work_performed'], $formData['work_performed']);
    }

    /**
     * testReportEditing method
     *
     * @return void
     */
    public function testReportEditing()
    {
        // can't edit reports that aren't yours
        $this->session($this->currentEmployee);
        $this->get("/reports/edit/1");
        $this->assertRedirect();

        // can't edit reports if you're a former employee
        $this->session($this->formerEmployee);
        $this->get("/reports/edit/1");
        $this->assertRedirect();

        // admins can do whatever
        $this->session($this->admin);
        $this->get("/reports/edit/1");
        $this->assertResponseOk();

        // current owners can do whatever too
        $this->session($this->currentEmployee);
        $this->get("/reports/edit/4");
        $this->assertResponseOk();

        $formData = [
            'supervisor_id' => $this->admin['Auth']['User']['id'],
            'student_id' => $this->currentEmployee['Auth']['User']['id'],
            'project_name' => 4,
            'start_date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'work_performed' => 'Oh no everything is different now!',
            'learned' => 'Wowza!',
            'routine' => 2
        ];

        $this->post('/reports/edit/4', $formData);
        $this->assertResponseContains('The report has been saved.');
        $this->assertResponseOk();

        $report = $this->Reports->find()->where(['learned' => $formData['learned']])->firstOrFail();
        $this->assertEquals($report['work_performed'], $formData['work_performed']);
    }

    /**
     * testReportDeleting method
     *
     * @return void
     */
    public function testReportDeleting()
    {
        // can't delete reports that aren't yours
        $this->session($this->currentEmployee);
        $this->get("/reports/delete/1");
        $this->assertRedirect();

        // can't delete reports if you're a former employee
        $this->session($this->formerEmployee);
        $this->get("/reports/delete/1");
        $this->assertRedirect();

        // admins can do whatever
        $this->session($this->admin);
        $this->get("/reports/delete/1");
        $this->assertResponseSuccess();
        $report = $this->Reports->find()->where(['id' => 1])->first();
        $this->assertNull($report);

        // current owners can do whatever too
        $this->session($this->currentEmployee);
        $this->get("/reports/delete/4");
        $this->assertResponseSuccess();
        $report = $this->Reports->find()->where(['id' => 4])->first();
        $this->assertNull($report);
    }
}
