<?php
namespace App\Test\TestCase\Controller;

use App\Controller\FundsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class FundsControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Funds', 'Users'];
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
        $classes = ['Funds', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test fund add page view & use
     *
     * @return void
     */
    public function testAddFund()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/funds/add');
        $this->assertResponseOk();

        $fund = [
            'name' => 'Fund Win',
            'organization' => 'American Placeholder Association',
            'amount' => '$500',
            'description' => 'Here is some text'
        ];

        $this->post('/funds/add', $fund);
        $this->assertResponseOk();

        $fund = $this->Funds->find()
            ->where(['name' => $fund['name']])
            ->firstOrFail();

        if ($fund) {
            $this->assertResponseOk();
            return;
        }
    }

    /**
     * Test fund edit page
     *
     * @return void
     */
    public function testEditingFunds()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $fund = $this->Funds->find()
            ->where(['name' => 'Fund Win'])
            ->firstOrFail();

        $this->get("/funds/edit/$fund->id");
        $this->assertResponseOk();

        $newFund = [
            'name' => 'Test Fund',
            'organization' => 'American Testing Association',
            'amount' => '$590',
            'description' => 'Here is some TEST'
        ];

        $this->post("/funds/edit/$fund->id", $newFund);
        $this->assertResponseOk();

        $fund = $this->Funds->find()
            ->where(['name' => $newFund['name']])
            ->firstOrFail();

        if ($fund) {
            $this->assertResponseOk();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting funds
     *
     * @return void
     */
    public function testDeletingFunds()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $fund = $this->Funds->find()
            ->where(['name' => 'Test Fund'])
            ->firstOrFail();

        $this->get("/funds/delete/$fund->id");
        $this->assertResponseSuccess();
    }
}
