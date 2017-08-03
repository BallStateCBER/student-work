<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class AwardsTest extends IntegrationTestCase
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
     * Test award add page
     *
     * @return void
     */
    public function testAddPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/awards/add');
        $this->assertResponseOk();
    }
}
