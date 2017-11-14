<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;
use Cake\Core\Configure;

class ReportsModelTest extends ApplicationTest
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;
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
     * Test getStudentCurrentReports method
     *
     * @return void
     */
    public function testGetStudentCurrentReports()
    {
        $reports = $this->Reports->getStudentCurrentReports(123456789);
        foreach ($reports as $report) {
            $this->assertEquals($report['supervisor_id'], 333666999);
        }
    }
    /**
     * Test getStudentCurrentReportsByProject method
     *
     * @return void
     */
    public function testGetStudentCurrentReportsByProject()
    {
        $reports = $this->Reports->getStudentCurrentReportsByProject(123456789, 4);
        foreach ($reports as $report) {
            $this->assertEquals($report['supervisor_id'], 333666999);
        }
    }
}
