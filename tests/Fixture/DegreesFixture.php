<?php
namespace App\Test\Fixture;

use FriendsOfCake\Fixturize\TestSuite\Fixture\ChecksumTestFixture as TestFixture;

/**
 * DegreesFixture
 *
 */
class DegreesFixture extends TestFixture
{
    /**
     * initialize fixture method
     */
    public function init()
    {
        parent::init();
        $this->records = [
            [
                'user_id' => 333666999,
                'type' => 'Master of Business Administration',
                'name' => 'Ball State University',
                'location' => 'Muncie, Indiana',
                'major' => 'Economics',
                'completed' => 1,
                'date' => date('Y-m-d', strtotime('05-15-2008'))
            ],
            [
                'user_id' => 987654321,
                'type' => 'Bachelor of General Studies',
                'name' => 'Ball State University',
                'location' => 'Muncie, Indiana',
                'major' => 'General Studies',
                'completed' => 1,
                'date' => date('Y-m-d', strtotime('05-15-2011'))
            ],
            [
                'user_id' => 123456789,
                'type' => 'Associate of General Studies',
                'name' => 'Ball State University',
                'location' => 'Muncie, Indiana',
                'major' => 'General Studies',
                'completed' => 0,
                'date' => date('Y-m-d', strtotime('Today'))
            ]
        ];
    }
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'type' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'location' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'major' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'completed' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'date' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'id_UNIQUE' => ['type' => 'unique', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
}
