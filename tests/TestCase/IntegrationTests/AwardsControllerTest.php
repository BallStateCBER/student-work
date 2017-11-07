<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class AwardsControllerTest extends ApplicationTest
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
     * Test add method for awards controller
     *
     * @return void
     */
    public function testAddAwards()
    {
        $this->session($this->currentEmployee);
        $this->get('/awards/add');
        $this->assertResponseContains('Add an Award');
        $this->assertResponseOk();

        $formData = [
            'name' => 'Placeholder Awards',
            'awarded_by' => 'American Placeholder Association',
            'user_id' => $this->currentEmployee['Auth']['User']['name'],
            'awarded_on' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'description' => 'Best in show for holding places!'
        ];
        $this->post('/awards/add', $formData);
        $this->assertResponseOk();
    }
    /**
     * Test edit method for awards controller
     *
     * @return void
     */
    public function testEditAwards()
    {
        $this->session($this->currentEmployee);
        $this->get('/awards/edit/1');
        $this->assertRedirect('/employees');

        $this->session($this->admin);
        $this->get('/awards/edit/1');
        $this->assertResponseContains('Edit');
        $this->assertResponseOk();

        $formData = [
            'name' => 'Placeholder Awards',
            'awarded_by' => 'American Placeholder Association',
            'user_id' => $this->currentEmployee['Auth']['User']['name'],
            'awarded_on' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'description' => 'Best in show for holding places!'
        ];
        $this->post('/awards/edit/1', $formData);
        $this->assertResponseOk();

        $award = $this->Awards->get(1);
        $this->assertEquals($award->user_id, $this->currentEmployee['Auth']['User']['id']);
    }

    /**
     * Test delete method for awards controller
     *
     * @return void
     */
    public function testDeleteAwards()
    {
        $this->session($this->currentEmployee);
        $this->get('/awards/delete/1');
        $this->assertRedirect('/employees');

        $this->get('/awards/delete/2');
        $this->assertResponseSuccess();

        $award = $this->Awards->find()->where(['id' => 2])->toArray();
        $this->assertEquals($award, []);
    }
}
