<?php
namespace App\Test\TestCase\Controller;

use App\Controller\DegreesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class DegreesControllerTest extends IntegrationTestCase
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
        $classes = ['Degrees', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        parent::tearDown();
    }

    /**
     * Test degree add page view & use
     *
     * @return void
     */
    public function testAddDegree()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $this->get('/degrees/add');
        $this->assertResponseOk();

        $degree = [
            'user_id' => $user->name,
            'type' => 'Doctor of Pharmacy',
            'name' => 'Test Degree',
            'location' => 'Muncie, Indiana',
            'major' => 'Pharmacy',
            'completed' => 1,
            'date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ]
        ];

        $this->post('/degrees/add', $degree);
        $this->assertResponseOk();

        $degree = $this->Degrees->find()
            ->where(['name' => $degree['name']])
            ->firstOrFail();

        if ($degree) {
            $this->assertResponseOk();
            return;
        }
    }

    /**
     * Test degree edit page
     *
     * @return void
     */
    public function testEditingDegrees()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $degree = $this->Degrees->find()
            ->where(['name' => 'Test Degree'])
            ->firstOrFail();

        $this->get("/degrees/edit/$degree->id");
        $this->assertResponseOk();

        $newDegree = [
            'user_id' => $user->name,
            'type' => 'Doctor of Pharmacy',
            'name' => 'Test Degree',
            'location' => 'Madison, Wisconsin',
            'major' => 'Biochemistry',
            'completed' => 1,
            'date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ]
        ];

        $this->post("/degrees/edit/$degree->id", $newDegree);
        $this->assertResponseOk();

        $degree = $this->Degrees->find()
            ->where(['name' => $newDegree['name']])
            ->firstOrFail();

        if ($degree) {
            $this->assertResponseOk();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting degrees
     *
     * @return void
     */
    public function testDeletingDegrees()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $user = $this->Users->get($id);
        $this->session(['Auth.User' => $user]);

        $degree = $this->Degrees->find()
            ->where(['name' => 'Test Degree'])
            ->firstOrFail();

        $this->get("/degrees/delete/$degree->id");
        $this->assertResponseSuccess();
    }
}
