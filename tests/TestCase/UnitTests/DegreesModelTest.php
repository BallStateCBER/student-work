<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class DegreesModelTest extends ApplicationTest
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
     * test degrees getter
     *
     * @return void
     */
    public function testGetDegrees()
    {
        $degrees = $this->Degrees->getDegrees(333666999);
        $testCase = $this->Degrees->find()->where(['user_id' => 333666999])->first();
        foreach ($degrees as $degree) {
            $this->assertEquals($degree->id, $testCase->id);
        }
    }

    /**
     * test degrees types
     *
     * @return void
     */
    public function testDegreeTypes()
    {
        $degrees = $this->Degrees->getDegreeTypes();
        $this->assertEquals($degrees['Associate of Applied Arts'], 'Associate of Applied Arts');
    }
}
