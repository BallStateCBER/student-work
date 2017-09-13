<?php
namespace App\Test\TestCase\Controller;

use App\Controller\DegreesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class DegreesTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Degrees', 'Users'];
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
        $classes = ['Degrees', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test degree add page
     *
     * @return void
     */
    public function testAddDegreePage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/degrees/add');
        $this->assertResponseOk();
        $this->assertResponseContains('Associate of Applied Arts');
    }

    /**
     * Test degree list method
     *
     * @return void
     */
    public function testGetAllTheDegrees()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);
        $degrees = $this->Degrees->getDegreeTypes();

        foreach ($degrees as $degree) {
            $this->get('/degrees/add');
            $this->assertResponseContains($degree);
        }
    }
}
