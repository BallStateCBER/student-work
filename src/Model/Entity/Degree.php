<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Degree Entity
 *
 * @property int $id
 * @property int $idemployees
 * @property string $type
 * @property string $name
 * @property string $location
 * @property string $major
 * @property int $completed
 * @property \Cake\I18n\FrozenTime $date
 */
class Degree extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $accessible = [
        '*' => true,
        'id' => false
    ];
}
