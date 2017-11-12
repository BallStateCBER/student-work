<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{
    /**
     * initialize fixture method
     */
    public function init()
    {
        parent::init();

        // password is "placeholder" for all these users
        $this->records = [
            [
                'id' => 123456789,
                'name' => 'Current Employee',
                'start date' => date('Y-m-d', strtotime('Today')),
                'end_date' => null,
                'birth_date' => date('Y-m-d', strtotime('01-28-1992')),
                'image' => null,
                'bio' => 'Hey, I totally started working here at CBER today, I love my new job, this is awesome!',
                'has_publications' => 0,
                'has_sites' => 0,
                'email' => 'cemployee@bsu.edu',
                'password' => '$2y$10$e6OYcHtHCjcEJrMqg/W7..SdbI09oGlRfokPWj8rzITEWUH56iP4i',
                'is_current' => 1,
                'is_admin' => 0,
                'ice_name' => 'Stevie Placeholder',
                'ice_phone' => '(765) 555-1234',
                'ice_relationship' => 'Roommate',
                'alt_email' => 'cemployee@gmail.com',
                'cell' => '(765) 555-7985',
                'position' => 'Student GIS Specialist'
            ],
            [
                'id' => 987654321,
                'name' => 'Former Employee',
                'start date' => date('Y-m-d', strtotime('02-27-2017')),
                'end_date' => date('Y-m-d', strtotime('-1 day')),
                'birth_date' => date('Y-m-d', strtotime('05-01-1989')),
                'image' => null,
                'bio' => 'Uh oh, today I am Fired! Looking for a new job ASAP! Front-end designer with a big portfolio!',
                'has_publications' => 1,
                'has_sites' => 1,
                'email' => 'femployee@bsu.edu',
                'password' => '$2y$10$HM.S6M8BQ.UUXSi7q9nz9eS88Ld1pND1B4fO4GhkREY14dajJAyYK',
                'is_current' => 0,
                'is_admin' => 0,
                'ice_name' => 'Frankie User',
                'ice_phone' => '(765) 555-8888',
                'ice_relationship' => 'Spouse',
                'alt_email' => 'femployee@gmail.com',
                'cell' => '(765) 555-4746',
                'position' => 'Student Designer'
            ],
            [
                'id' => 333666999,
                'name' => 'Addy Admin',
                'start date' => date('Y-m-d', strtotime('01-15-2008')),
                'end_date' => null,
                'birth_date' => date('Y-m-d', strtotime('09-21-1979')),
                'image' => null,
                'bio' => 'I am the Head Honcho here at CBER! No one is more important than me! FEAR!',
                'has_publications' => 1,
                'has_sites' => 1,
                'email' => 'admin@bsu.edu',
                'password' => '$2y$10$9MelK6sbrC.hNMgi08cR/ep2LcrhMX.wzlqU1j75BAHzg8DD0eGZG',
                'is_current' => 1,
                'is_admin' => 1,
                'ice_name' => 'Georgie Generic',
                'ice_phone' => '(765) 555-4741',
                'ice_relationship' => 'Parent',
                'alt_email' => 'admin@gmail.com',
                'cell' => '(765) 555-4746',
                'position' => 'Web Publications Manager'
            ]
        ];
    }
    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'start_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'end_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'birth_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'image' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'bio' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'has_publications' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'has_sites' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'is_current' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'is_admin' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'comment' => '', 'precision' => null],
        'ice_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'ice_phone' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'ice_relationship' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'alt_email' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cell' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'position' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'id_UNIQUE' => ['type' => 'unique', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
}
