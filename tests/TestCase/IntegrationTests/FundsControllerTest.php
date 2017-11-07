<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class FundsControllerTest extends ApplicationTest
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
     * Test indexing method for funds controller
     *
     * @return void
     */
    public function testFundsIndexAndRedirect()
    {
        $this->session($this->currentEmployee);
        $this->get('/funds');
        $this->assertRedirect('/reports');

        $this->session($this->admin);
        $this->get('/funds');
        $this->assertResponseOk();
        $this->assertResponseContains('New Funding Source');
    }
    /**
     * Test add method for funds controller
     *
     * @return void
     */
    public function testAddFunds()
    {
        $this->session($this->admin);
        $this->get('/funds/add');
        $this->assertResponseContains('Add a Fund');
        $this->assertResponseOk();

        $formData = [
            'name' => '2',
            'organization' => 'Galesburg, Ill.',
            'amount' => '$500',
            'funding_detail' => '$500 for the entire city of Galesburg wow!'
        ];
        $this->post('/funds/add', $formData);
        $this->assertResponseOk();
    }
    /**
     * Test edit method for funds controller
     *
     * @return void
     */
    public function testEditFunds()
    {
        $this->session($this->admin);
        $this->get('/funds/edit/1');
        $this->assertResponseContains('Edit');
        $this->assertResponseOk();

        $formData = [
            'name' => '1',
            'organization' => 'Galesburg, Ill.',
            'amount' => '$500',
            'funding_detail' => '$500 for the entire city of Galesburg wow!'
        ];
        $this->post('/funds/edit/1', $formData);
        $this->assertResponseOk();

        $fund = $this->Funds->get(1);
        $this->assertEquals($fund->organization, $formData['organization']);
    }

    /**
     * Test delete method for funds controller
     *
     * @return void
     */
    public function testDeleteFunds()
    {
        $this->session($this->admin);
        $this->get('/funds/delete/1');
        $this->assertResponseSuccess();

        $fund = $this->Funds->find()->where(['id' => 1])->toArray();
        $this->assertEquals($fund, []);
    }
}
