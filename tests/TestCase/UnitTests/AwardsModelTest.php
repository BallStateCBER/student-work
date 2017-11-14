<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestCase\ApplicationTest;

class AwardsModelTest extends ApplicationTest
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
     * test awards getter
     *
     * @return void
     */
    public function testGetAwards()
    {
        $awards = $this->Awards->getAwards(333666999);
        $testCase = $this->Awards->find()->where(['user_id' => 333666999])->first();
        foreach ($awards as $award) {
            $this->assertEquals($award->id, $testCase->id);
        }
    }
}
