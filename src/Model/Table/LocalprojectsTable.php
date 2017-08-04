<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Localprojects Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Localproject get($primaryKey, $options = [])
 * @method \App\Model\Entity\Localproject newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Localproject[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Localproject|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Localproject patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Localproject[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Localproject findOrCreate($search, callable $callback = null, $options = [])
 */
class LocalprojectsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('localprojects');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Users', [
            'foreignKey' => 'localproject_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_localprojects'
        ]);

        $this->belongsTo('Grants');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('organization', 'create')
            ->notEmpty('organization');

        return $validator;
    }
}
