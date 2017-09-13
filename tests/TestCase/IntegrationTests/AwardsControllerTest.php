<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AwardsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class AwardsControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Awards', 'Users'];
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
        $classes = ['Awards', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test award add page view & use
     *
     * @return void
     */
    public function testAddAnAward()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/awards/add');
        $this->assertResponseOk();
        $award = [
            'user_id' => $user->name,
            'name' => 'Test Awards',
            'awarded_on' => date('Y-m-d'),
            'awarded_by' => 'This Test',
            'description' => 'Testing 123'
        ];

        $this->post('/awards/add', $award);
        $this->assertResponseOk();

        $award = $this->Awards->find()
            ->where(['name' => $award['name']])
            ->firstOrFail();

        if ($award) {
            $this->assertResponseOk();

            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test award edit page
     *
     * @return void
     */
    public function testEditingAwards()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $award = $this->Awards->find()
            ->where(['name' => 'Test Awards'])
            ->firstOrFail();

        $this->get("/awards/edit/$award->id");
        $this->assertResponseOk();

        $newAward = [
            'user_id' => $user->name,
            'name' => 'Test Awards Part Three',
            'awarded_on' => date('Y-m-d'),
            'awarded_by' => 'That Test',
            'description' => 'Testing 321'
        ];

        $this->post("/awards/edit/$award->id", $newAward);
        $this->assertResponseOk();

        $award = $this->Awards->find()
            ->where(['name' => $newAward['name']])
            ->firstOrFail();

        if ($award) {
            $this->assertResponseOk();

            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting awards
     *
     * @return void
     */
    public function testDeletingAwards()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $award = $this->Awards->find()
            ->where(['name' => 'Test Awards Part Three'])
            ->firstOrFail();

        $this->get("/awards/delete/$award->id");
        $this->assertResponseSuccess();
    }
}
