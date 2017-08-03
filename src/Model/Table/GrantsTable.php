<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Grants Model
 *
 * @property \Cake\ORM\Association\HasMany $Localprojects
 * @property \Cake\ORM\Association\HasMany $Publications
 * @property \Cake\ORM\Association\HasMany $Sites
 *
 * @method \App\Model\Entity\Grant get($primaryKey, $options = [])
 * @method \App\Model\Entity\Grant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Grant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Grant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Grant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Grant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Grant findOrCreate($search, callable $callback = null, $options = [])
 */
class GrantsTable extends Table
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

        $this->setTable('grants');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Localprojects', [
            'foreignKey' => 'grant_id'
        ]);
        $this->hasMany('Publications', [
            'foreignKey' => 'grant_id'
        ]);
        $this->hasMany('Sites', [
            'foreignKey' => 'grant_id'
        ]);
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
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('organization', 'create')
            ->notEmpty('organization');

        $validator
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->allowEmpty('description');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }
}
