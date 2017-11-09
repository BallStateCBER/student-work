<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProjectsFixture
 *
 */
class ProjectsFixture extends TestFixture
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
                'name' => 'Without Love',
                'description' => 'Got to be without love got to be without love got to be without love.',
                'organization' => 'Alice Glass',
                'fund_id' => 1,
                'image' => null,
                'funding_details' => 'Alice Glass first solo EP since her departure from Crystal Castles in 2014.',
                'grant_name' => 'Alice Glass'
            ],
            [
                'id' => 2,
                'name' => 'Natural Selection',
                'description' => 'Are you safer now than when you\'re in the dark? Maybe we\'re safer here.',
                'organization' => 'Alice Glass',
                'fund_id' => 1,
                'image' => null,
                'funding_details' => 'Alice Glass first solo EP since her departure from Crystal Castles in 2014.',
                'grant_name' => 'Alice Glass'
            ],
            [
                'id' => 3,
                'name' => 'Stillbirth',
                'description' => 'I wanna start again I wanna start again I wanna start again I wanna start again.',
                'organization' => 'Alice Glass',
                'fund_id' => 1,
                'image' => null,
                'funding_details' => 'All proceeds from the STILLBIRTH track went to RAINN.',
                'grant_name' => 'Alice Glass'
            ],
            [
                'id' => 4,
                'name' => 'Melatonin',
                'description' => 'I have fallen asleep the angels won\'t let me be.',
                'organization' => 'September Girls',
                'fund_id' => 2,
                'image' => null,
                'funding_details' => 'Some band from Dublin.',
                'grant_name' => 'Veneer'
            ],
            [
                'id' => 5,
                'name' => 'Blue Monday',
                'description' => 'How does it feel to treat me like you do.',
                'organization' => 'New Order',
                'fund_id' => 2,
                'image' => null,
                'funding_details' => 'Most revenue generated from the cover versions of it.',
                'grant_name' => 'New Order'
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
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'organization' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fund_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'image' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'funding_details' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'grant_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
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
