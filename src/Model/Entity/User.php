<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $start_date
 * @property \Cake\I18n\FrozenTime $end_date
 * @property \Cake\I18n\FrozenTime $birth_date
 * @property string $image
 * @property string $bio
 * @property int $has_publications
 * @property int $has_sites
 * @property string $email
 * @property string $password
 * @property int $is_current
 *
 * @property \App\Model\Entity\Award[] $awards
 * @property \App\Model\Entity\Job[] $jobs
 * @property \App\Model\Entity\Project[] $projects
 * @property \App\Model\Entity\Publication[] $publications
 * @property \App\Model\Entity\Site[] $sites
 */
class User extends Entity
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
        '*' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * sets the password when creating a new user
     * @param string $password to be hashed
     */
    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
