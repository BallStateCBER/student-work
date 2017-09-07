<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProjectsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ProjectsTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Projects', 'Users'];
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
        $classes = ['Projects', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test Project add page
     *
     * @return void
     */
    public function testAddProjectsPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/projects/add');
        $this->assertResponseOk();
    }

    /**
     * Test Project index page
     *
     * @return void
     */
    public function testIndexProjects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/projects');
        $this->assertResponseOk();

        $projects = $this->Projects->find('list')->toArray();

        if ($projects > 0) {
            foreach ($projects as $project) {
                $this->assertResponseContains($project);
            }
            return;
        }

        $this->assertResponseContains("This probably suggests a problem.");
    }
}
