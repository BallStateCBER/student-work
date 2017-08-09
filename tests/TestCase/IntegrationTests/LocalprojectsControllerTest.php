<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LocalprojectsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class LocalprojectsControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Localprojects', 'Users'];
        foreach ($classes as $class) {
            $config = TableRegistry::exists("$class") ? [] : ['className' => 'App\Model\Table\\'.$class.'Table'];
            $this->$class = TableRegistry::get("$class", $config);
        }

        $this->UsersLocalprojects = TableRegistry::get("UsersLocalprojects");
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $classes = ['Localprojects', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        unset($this->UsersLocalprojects);
        parent::tearDown();
    }

    /**
     * Test localproject add page view & use
     *
     * @return void
     */
    public function testAddLocalproject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/localprojects/add');
        $this->assertResponseOk();

        $localproject = [
            'name' => 'Localproject Win',
            'organization' => 'American Placeholder Association',
            'grant_id' => 1,
            'description' => 'Here is some text'
        ];

        $this->post('/localprojects/add', $localproject);
        $this->assertResponseSuccess();

        $localproject = $this->Localprojects->find()
            ->where(['name' => $localproject['name']])
            ->firstOrFail();

        if ($localproject) {
            $this->assertResponseSuccess();
            return;
        }
    }

    /**
     * Test localproject edit page
     *
     * @return void
     */
    public function testEditingLocalprojects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $localproject = $this->Localprojects->find()
            ->where(['name' => 'Localproject Win'])
            ->firstOrFail();

        $this->get("/localprojects/edit/$localproject->id");
        $this->assertResponseOk();

        $newLocalproject = [
            'name' => 'Test Localproject',
            'organization' => 'American Testing Association',
            'grant_id' => 1,
            'description' => 'Here is some TEST'
        ];

        $this->post("/localprojects/edit/$localproject->id", $newLocalproject);
        $this->assertResponseSuccess();

        $localproject = $this->Localprojects->find()
            ->where(['name' => $newLocalproject['name']])
            ->firstOrFail();

        if ($localproject) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test localproject edit page and add users to it
     *
     * @return void
     */
    public function testAddingUsersToLocalproject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $localproject = $this->Localprojects->find()
            ->where(['name' => 'Test Localproject'])
            ->firstOrFail();

        $newLocalproject = [
            'name' => 'Test Localproject',
            'organization' => 'American Testing Association',
            'grant_id' => 1,
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

        $this->post("/localprojects/edit/$localproject->id", $newLocalproject);
        $this->assertResponseSuccess();

        $localproject = $this->UsersLocalprojects->find()
            ->where(['user_id' => $id])
            ->firstOrFail();
    }

    /**
     * Test localproject edit page and remove users from it
     *
     * @return void
     */
    public function testRemovingUsersFromLocalproject()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $localproject = $this->Localprojects->find()
            ->where(['name' => 'Test Localproject'])
            ->firstOrFail();

        $newLocalproject = [
            'name' => 'Test Localproject',
            'organization' => 'American Testing Association',
            'grant_id' => 1,
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

        $this->post("/localprojects/edit/$localproject->id", $newLocalproject);
        $this->assertResponseSuccess();

        $localproject = $this->UsersLocalprojects->find()
            ->where(['user_id' => $id])
            ->first();

        if (!isset($localproject->id)) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting localprojects
     *
     * @return void
     */
    public function testDeletingLocalprojects()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $localproject = $this->Localprojects->find()
            ->where(['name' => 'Test Localproject'])
            ->firstOrFail();

        $this->get("/localprojects/delete/$localproject->id");
        $this->assertResponseSuccess();
    }
}
