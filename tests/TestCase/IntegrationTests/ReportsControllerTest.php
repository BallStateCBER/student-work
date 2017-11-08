<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class ReportsControllerTest extends ApplicationTest
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
     * testReportsIndex method
     *
     * @return void
     */
    public function testReportsIndex()
    {
        $this->session($this->currentEmployee);
        $this->get('/reports');
        $this->assertResponseOk();
    }
}
