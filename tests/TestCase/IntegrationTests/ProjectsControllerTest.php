<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class ProjectsControllerTest extends ApplicationTest
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
     * Test indexing method for projects controller
     *
     * @return void
     */
    public function testProjectsIndex()
    {
        $this->session($this->currentEmployee);
        $this->get('/projects');
        $this->assertResponseOk();

        $this->session($this->admin);
        $this->get('/projects');
        $this->assertResponseOk();
        $this->assertResponseContains('Without Love');
        $this->assertResponseContains('Stillbirth');
    }

    /*
     * test viewing projects
     *
     * @return void
     */
    public function testViewingProjects()
    {
        $projects = $this->projects;
        foreach ($projects as $project) {
            $this->session($this->currentEmployee);
            $this->get("/projects/" . $project['id']);
            $this->assertResponseContains($project['name']);

            $this->session($this->admin);
            $this->get("/projects/" . $project['id']);
            $this->assertResponseContains($project['name']);
        }
    }

    /**
     * test adding and editing projects and deleting them also
     *
     * @return void
     */
    public function testAddingAndEditingAndDeletingProjects()
    {
        $this->session($this->currentEmployee);
        $this->get("/projects/add");
        $this->assertRedirect();

        // doesn't work, need an admin
        $this->session($this->admin);
        $this->get("/projects/add");
        $this->assertResponseOk();

        $formData = [
            'name' => 'Blood Oath',
            'description' => 'You cannot take it back.',
            'organization' => 'Alice Glass',
            'fund_id' => 1,
            'image' => null,
            'funding_details' => 'Alice Glass first solo EP since her departure from Crystal Castles in 2014.',
            'grant_name' => 'Alice Glass'
        ];

        $this->post("/projects/add", $formData);
        $this->assertResponseSuccess();

        $project = $this->Projects->find()->where(['name' => $formData['name']])->first();

        $this->get("/projects");
        $this->assertResponseContains($project['name']);

        $this->get("/projects/edit/" . $project->id);
        $this->assertResponseSuccess();

        $formData['name'] = 'Blood Oath Different Name';
        $formData['users'][0]['_joinData']['user_id'] = $this->currentEmployee['Auth']['User']['id'];
        $formData['users'][0]['_joinData']['role'] = 'Testing block!';

        $this->post("/projects/edit/" . $project->id, $formData);
        $this->assertResponseSuccess();

        $project = $this->Projects->find()->where(['name' => $formData['name']])->first();
        $this->assertEquals($project['name'], $formData['name']);

        $this->get("/projects");
        $this->assertResponseContains($formData['name']);
        $this->assertResponseContains('Testing block!');

        $this->get("/projects/delete/$project->id");
        $this->assertResponseSuccess();

        $project = $this->Projects->find()->where(['id' => $project->id])->first();
        $this->assertNull($project);
    }

    /**
     * test deleting projects with reports
     *
     * @return void
     */
    public function testDeletingProjectsWithReports()
    {
        $this->session($this->admin);
        $project = $this->projects[0];
        $this->get("/projects/delete/" . $project['id']);
        $there = $this->Projects->get($project['id']);
        $this->assertEquals($there['id'], $project['id']);

        $report = $this->reports[0];
        $this->get("/reports/delete/" . $report['id']);
        $this->assertResponseSuccess();

        $this->get("/projects/delete/" . $project['id']);
        $this->assertResponseSuccess();

        $project = $this->Projects->find()->where(['id' => $project['id']])->first();
        $this->assertNull($project);
    }
}
