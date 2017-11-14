<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class ProjectsModelTest extends ApplicationTest
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
     * test get project by name
     *
     * @return void
     */
    public function testGetProjectByName()
    {
        $project = $this->Projects->getProjectByName('Without Love');
        $this->assertEquals($project->organization, 'Alice Glass');
    }
}
