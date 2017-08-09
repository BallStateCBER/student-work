<?php
namespace App\Test\TestCase\Controller;

use App\Controller\PublicationsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class PublicationsTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Publications', 'Users'];
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
        $classes = ['Publications', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test Publication add page
     *
     * @return void
     */
    public function testAddPublicationsPage()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/publications/add');
        $this->assertResponseOk();
    }

    /**
     * Test Publication index page
     *
     * @return void
     */
    public function testIndexPublications()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/publications');
        $this->assertResponseOk();

        $publications = $this->Publications->find('list')->toArray();

        if ($publications > 0) {
            foreach ($publications as $publication) {
                $this->assertResponseContains("$publication");
            }
            return;
        }

        $this->assertResponseContains("This probably suggests a problem.");
    }
}
