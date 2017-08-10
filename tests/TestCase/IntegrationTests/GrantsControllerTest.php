<?php
namespace App\Test\TestCase\Controller;

use App\Controller\GrantsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class GrantsControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Grants', 'Users'];
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
        $classes = ['Grants', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test grant add page view & use
     *
     * @return void
     */
    public function testAddGrant()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/grants/add');
        $this->assertResponseOk();

        $grant = [
            'name' => 'Grant Win',
            'organization' => 'American Placeholder Association',
            'amount' => '$500',
            'description' => 'Here is some text'
        ];

        $this->post('/grants/add', $grant);
        $this->assertResponseOk();

        $grant = $this->Grants->find()
            ->where(['name' => $grant['name']])
            ->firstOrFail();

        if ($grant) {
            $this->assertResponseOk();
            return;
        }
    }

    /**
     * Test grant edit page
     *
     * @return void
     */
    public function testEditingGrants()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $grant = $this->Grants->find()
            ->where(['name' => 'Grant Win'])
            ->firstOrFail();

        $this->get("/grants/edit/$grant->id");
        $this->assertResponseOk();

        $newGrant = [
            'name' => 'Test Grant',
            'organization' => 'American Testing Association',
            'amount' => '$590',
            'description' => 'Here is some TEST'
        ];

        $this->post("/grants/edit/$grant->id", $newGrant);
        $this->assertResponseOk();

        $grant = $this->Grants->find()
            ->where(['name' => $newGrant['name']])
            ->firstOrFail();

        if ($grant) {
            $this->assertResponseOk();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting grants
     *
     * @return void
     */
    public function testDeletingGrants()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $grant = $this->Grants->find()
            ->where(['name' => 'Test Grant'])
            ->firstOrFail();

        $this->get("/grants/delete/$grant->id");
        $this->assertResponseSuccess();
    }
}
