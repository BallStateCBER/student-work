<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Degrees Model
 *
 * @method \App\Model\Entity\Degree get($primaryKey, $options = [])
 * @method \App\Model\Entity\Degree newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Degree[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Degree|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Degree patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Degree[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Degree findOrCreate($search, callable $callback = null, $options = [])
 */
class DegreesTable extends Table
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

        $this->setTable('degrees');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('location', 'create')
            ->notEmpty('location');

        $validator
            ->requirePresence('major', 'create')
            ->notEmpty('major');

        $validator
            ->integer('completed')
            ->requirePresence('completed', 'create')
            ->notEmpty('completed');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

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
         $rules->add($rules->existsIn(['user_id'], 'Users'));

         return $rules;
     }
}
