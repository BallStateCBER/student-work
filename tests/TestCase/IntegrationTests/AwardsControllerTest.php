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
        $config = TableRegistry::exists('Awards') ? [] : ['className' => 'App\Model\Table\AwardsTable'];
        $this->Awards = TableRegistry::get('Awards', $config);
        $this->Users = TableRegistry::get('Users');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Awards);
        unset($this->Users);
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
        $this->session(['Auth.User.id' => $id]);

        $this->get('/awards/add');
        $this->assertResponseOk();

        $award = [
            'user_id' => $id,
            'name' => 'Test Awards',
            'awarded_on' => date('Y-m-d'),
            'awarded_by' => 'This Test',
            'description' => 'Testing 123'
        ];

        $this->post('/awards/add', $award);
        $this->assertResponseOk();

        $award = $this->Awards->find()
            ->where(['name' => $award['name']])
            ->first();

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
        $this->session(['Auth.User.id' => $id]);

        $award = $this->Awards->find()
            ->where(['name' => 'Test Awards'])
            ->first();

        $this->get("/awards/edit/$award->id");
        $this->assertResponseOk();

        $newAward = [
            'user_id' => $id,
            'name' => 'Test Awards Part Three',
            'awarded_on' => date('Y-m-d'),
            'awarded_by' => 'That Test',
            'description' => 'Testing 321'
        ];

        $this->post("/awards/edit/$award->id", $newAward);
        $this->assertResponseOk();

        $award = $this->Awards->find()
            ->where(['name' => $newAward['name']])
            ->first();

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
        $this->session(['Auth.User.id' => $id]);

        $award = $this->Awards->find()
            ->where(['name' => 'Test Awards Part Three'])
            ->first();

        $this->get("/awards/delete/$award->id");
        $this->assertResponseSuccess();
    }
}
