<?php
namespace App\Test\Fixture;

use FriendsOfCake\Fixturize\TestSuite\Fixture\ChecksumTestFixture as TestFixture;

/**
 * UsersProjectsFixture
 *
 */
class UsersProjectsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'project_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'role' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'id_UNIQUE' => ['type' => 'unique', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'user_id' => 333666999,
            'project_id' => 1,
            'role' => 'Supervisor'
        ],
        [
            'id' => 2,
            'user_id' => 333666999,
            'project_id' => 2,
            'role' => 'Supervisor'
        ],
        [
            'id' => 3,
            'user_id' => 333666999,
            'project_id' => 3,
            'role' => 'Supervisor'
        ],
        [
            'id' => 4,
            'user_id' => 333666999,
            'project_id' => 4,
            'role' => 'Supervisor'
        ],
        [
            'id' => 5,
            'user_id' => 987654321,
            'project_id' => 1,
            'role' => 'Student'
        ],
        [
            'id' => 6,
            'user_id' => 987654321,
            'project_id' => 2,
            'role' => 'Student'
        ],
        [
            'id' => 7,
            'user_id' => 987654321,
            'project_id' => 3,
            'role' => 'Student'
        ],
        [
            'id' => 8,
            'user_id' => 123456789,
            'project_id' => 4,
            'role' => 'Student'
        ]
    ];
}
