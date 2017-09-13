<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Awards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Award get($primaryKey, $options = [])
 * @method \App\Model\Entity\Award newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Award[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Award|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Award
 *  patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Award[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Award findOrCreate($search, callable $callback = null, $options = [])
 */
class AwardsTable extends Table
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

        $this->setTable('awards');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->date('awarded_on')
            ->requirePresence('awarded_on', 'create')
            ->notEmpty('awarded_on');

        $validator
            ->requirePresence('awarded_by', 'create')
            ->notEmpty('awarded_by');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * get all awards of user $userId
     * @param  int $userId
     * @return $awards
     */
    public function getAwards($userId)
    {
        $awards = $this->find('all');
        $awards
            ->select()
            ->where(['user_id' => $userId])
            ->order(['awarded_on' => 'ASC'])
            ->toArray();

        if (!iterator_count($awards)) {
            $awards = null;
        }

        return $awards;
    }
}
