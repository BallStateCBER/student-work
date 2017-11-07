<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class DegreesControllerTest extends ApplicationTest
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }
    /**
     * Test add method for degrees controller
     *
     * @return void
     */
    public function testAddDegrees()
    {
        $this->session($this->currentEmployee);
        $this->get('/degrees/add');
        $this->assertResponseContains('Add Educational Experience');
        $this->assertResponseOk();

        $formData = [
            'name' => 'Placeholder Degrees',
            'location' => 'Galesburg, Ill.',
            'type' => 'Bachelor of Science',
            'user_id' => $this->currentEmployee['Auth']['User']['name'],
            'date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'major' => 'Holding Places',
            'completed' => 1
        ];
        $this->post('/degrees/add', $formData);
        $this->assertResponseOk();
    }
    /**
     * Test edit method for degrees controller
     *
     * @return void
     */
    public function testEditDegrees()
    {
        $this->session($this->currentEmployee);
        $this->get('/degrees/edit/1');
        $this->assertRedirect('/employees');

        $this->session($this->admin);
        $this->get('/degrees/edit/1');
        $this->assertResponseContains('Edit');
        $this->assertResponseOk();

        $formData = [
            'name' => 'Placeholder Degrees',
            'location' => 'Galesburg, Ill.',
            'type' => 'Bachelor of Science',
            'user_id' => $this->currentEmployee['Auth']['User']['name'],
            'date' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'major' => 'Holding Places',
            'completed' => 1
        ];
        $this->post('/degrees/edit/1', $formData);
        $this->assertResponseOk();

        $degree = $this->Degrees->get(1);
        $this->assertEquals($degree->user_id, $this->currentEmployee['Auth']['User']['id']);
    }

    /**
     * Test delete method for degrees controller
     *
     * @return void
     */
    public function testDeleteDegrees()
    {
        $this->session(['Auth.User.id' => 1]);
        $this->get('/degrees/delete/1');
        $this->assertRedirect('/employees');

        $this->get('/degrees/delete/3');
        $this->assertResponseSuccess();

        $degree = $this->Degrees->find()->where(['id' => 3])->toArray();
        $this->assertEquals($degree, []);
    }
}
