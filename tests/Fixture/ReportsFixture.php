<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ReportsFixture
 *
 */
class ReportsFixture extends TestFixture
{
    /**
     * initialize fixture method
     */
    public function init()
    {
        parent::init();
        $this->records = [
            [
                'id' => 1,
                'student_id' => 987654321,
                'supervisor_id' => 333666999,
                'project_id' => 1,
                'start_date' => date('Y-m-d', strtotime('-6 Weeks Ago')),
                'end_date' => date('Y-m-d', strtotime('-5 Weeks Ago')),
                'work_performed' => 'I did a super good job, everything was correct, I got A+ on the class this was relevant for, hey!',
                'routine' => 0,
                'learned' => 'I learned how to make an entire website from start-to-finish and have it turned into a startup and make millions off of it!'
            ],
            [
                'id' => 2,
                'student_id' => 987654321,
                'supervisor_id' => 333666999,
                'project_id' => 2,
                'start_date' => date('Y-m-d', strtotime('-4 Weeks Ago')),
                'end_date' => date('Y-m-d', strtotime('-3 Weeks Ago')),
                'work_performed' => 'I did an OK job, I did what I am usually supposed to.',
                'routine' => 1,
                'learned' => 'I learned how to begin using these fixtures as a thinly-veiled allegory for my own downward spiral with mental health.'
            ],
            [
                'id' => 3,
                'student_id' => 987654321,
                'supervisor_id' => 333666999,
                'project_id' => 3,
                'start_date' => date('Y-m-d', strtotime('-2 Weeks Ago')),
                'end_date' => date('Y-m-d', strtotime('-1 Week Ago')),
                'work_performed' => 'Wow, I just really screwed all of this up totally relentlessly.',
                'routine' => 1,
                'learned' => 'I learned I really need to get it together or I will probably be fired oh no!'
            ],
            [
                'id' => 4,
                'student_id' => 123456789,
                'supervisor_id' => 333666999,
                'project_id' => 4,
                'start_date' => date('Y-m-d', strtotime('Today')),
                'end_date' => null,
                'work_performed' => 'I am taking over the job for Former Employee! New starts are here!',
                'routine' => 0,
                'learned' => 'I learned that, eventually, we are all replaceable :('
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
        'student_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'supervisor_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'project_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'start_date' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'end_date' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'work_performed' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'routine' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'learned' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
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
