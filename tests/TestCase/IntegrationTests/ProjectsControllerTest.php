<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProjectsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class ProjectsControllerTest extends IntegrationTestCase
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

        $this->UsersProjects = TableRegistry::get("UsersProjects");
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
        unset($this->UsersProjects);
        parent::tearDown();
    }

    /**
     * Test project add page view & use
     *
     * @return void
     */
    public function testAddProject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/projects/add');
        $this->assertResponseOk();

        $project = [
            'name' => 'Project Win',
            'organization' => 'American Placeholder Association',
            'fund_id' => 1,
            'description' => 'Here is some text'
        ];

        $this->post('/projects/add', $project);
        $this->assertResponseSuccess();

        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/projects/add');
        $this->assertRedirect();

        $project = $this->Projects->find()
            ->where(['name' => $project['name']])
            ->firstOrFail();

        if ($project) {
            $this->assertResponseSuccess();
            return;
        }
    }

    /**
     * Test project edit page
     *
     * @return void
     */
    public function testEditingProjects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $project = $this->Projects->find()
            ->where(['name' => 'Project Win'])
            ->firstOrFail();

        $this->get("/projects/edit/$project->id");
        $this->assertResponseOk();

        $newProject = [
            'name' => 'Test Project',
            'organization' => 'American Testing Association',
            'fund_id' => 1,
            'description' => 'Here is some TEST'
        ];

        $this->post("/projects/edit/$project->id", $newProject);
        $this->assertResponseSuccess();

        $id = $this->Users->getIdFromEmail('mblum@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get("/projects/edit/$project->id");
        $this->assertRedirect();

        $project = $this->Projects->find()
            ->where(['name' => $newProject['name']])
            ->firstOrFail();

        if ($project) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test project edit page and add users to it
     *
     * @return void
     */
    public function testAddingUsersToProject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $project = $this->Projects->find()
            ->where(['name' => 'Test Project'])
            ->firstOrFail();

        $newProject = [
            'name' => 'Test Project',
            'organization' => 'American Testing Association',
            'fund_id' => 1,
            'description' => 'Here is some TEST',
            'users' => [
                0 => [
                    '_joinData' => [
                        'user_id' => $id,
                        'role' => 'Place Holder',
                        'delete' => 0
                    ]
                ]
            ]
        ];

        $this->post("/projects/edit/$project->id", $newProject);
        $this->assertResponseSuccess();

        $project = $this->UsersProjects->find()
            ->where(['user_id' => $id])
            ->firstOrFail();
    }

    /**
     * Test project edit page and remove users from it
     *
     * @return void
     */
    public function testRemovingUsersFromProject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $project = $this->Projects->find()
            ->where(['name' => 'Test Project'])
            ->firstOrFail();

        $newProject = [
            'name' => 'Test Project',
            'organization' => 'American Testing Association',
            'fund_id' => 1,
            'description' => 'Here is some TEST',
            'users' => [
                0 => [
                    '_joinData' => [
                        'user_id' => $id,
                        'role' => 'Place Holder',
                        'delete' => 0
                    ],
                    'delete' => 1
                ]
            ]
        ];

        $this->post("/projects/edit/$project->id", $newProject);
        $this->assertResponseSuccess();

        $project = $this->UsersProjects->find()
            ->where(['user_id' => $id])
            ->first();

        if (!isset($project->id)) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting projects
     *
     * @return void
     */
    public function testDeletingProjects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $project = $this->Projects->find()
            ->where(['name' => 'Test Project'])
            ->firstOrFail();

        $this->get("/projects/delete/$project->id");
        $this->assertResponseSuccess();
    }
}
