<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class PagesControllerTest extends ApplicationTest
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
     * Test help page
     *
     * @return void
     */
    public function testHelpPage()
    {
        $this->session($this->currentEmployee);
        $this->get('/help');
        $this->assertResponseContains('Students');

        $this->session($this->admin);
        $this->get('/help');
        $this->assertResponseContains('Site Admins');
    }

    /**
     * Test home page
     *
     * @return void
     */
    public function testHomePage()
    {
        $this->get('/');
        $this->assertResponseContains('Log in');

        $this->session($this->admin);
        $this->get('/');
        $this->assertResponseContains('Log out');
    }
}
