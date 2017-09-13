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

    /**
     * array of every type of degree someone can earn
     * @return [array] $degreeTypes
     */
    public function getDegreeTypes()
    {
        $degreeTypes = [
             'Associate of Applied Arts' => 'Associate of Applied Arts',
             'Associate of Applied Science' => 'Associate of Applied Science',
             'Associate of Arts' => 'Associate of Arts',
             'Associate of Engineering' => 'Associate of Engineering',
             'Associate of General Studies' => 'Associate of General Studies',
             'Associate of Political Science' => 'Associate of Political Science',
             'Associate of Science' => 'Associate of Science',
             'Bachelor of Applied Science' => 'Bachelor of Applied Science',
             'Bachelor of Architecture' => 'Bachelor of Architecture',
             'Bachelor of Arts' => 'Bachelor of Arts',
             'Bachelor of Business Administration' => 'Bachelor of Business Administration',
             'Bachelor of Engineering' => 'Bachelor of Engineering',
             'Bachelor of Fine Arts' => 'Bachelor of Fine Arts',
             'Bachelor of General Studies' => 'Bachelor of General Studies',
             'Bachelor of Science' => 'Bachelor of Science',
             'Doctor of Dental Surgery' => 'Doctor of Dental Surgery',
             'Doctor of Education' => 'Doctor of Education',
             'Doctor of Medicine' => 'Doctor of Medicine',
             'Doctor of Pharmacy' => 'Doctor of Pharmacy',
             'Doctor of Philosophy' => 'Doctor of Philosophy',
             'General Education Development' => 'General Education Development',
             'High School Diploma' => 'High School Diploma',
             'High School Equivalency Diploma' => 'High School Equivalency Diploma',
             'Juris Doctor' => 'Juris Doctor',
             'Master of Arts' => 'Master of Arts',
             'Master of Business Administration' => 'Master of Business Administration',
             'Master of Education' => 'Master of Education',
             'Master of Fine Arts' => 'Master of Fine Arts',
             'Master of Laws' => 'Master of Laws',
             'Master of Philosophy' => 'Master of Philosophy',
             'Master of Research' => 'Master of Research',
             'Master of Science' => 'Master of Science'
         ];

        return $degreeTypes;
    }

    /**
     * get all the degrees of user $userId
     * @param int|null $userId User ID
     * @return $degrees
     */
    public function getDegrees($userId = null)
    {
        $degrees = $this->find('all');
        $degrees
            ->select()
            ->where(['user_id' => $userId])
            ->order(['date' => 'DESC'])
            ->toArray();

        if (!iterator_count($degrees)) {
            $degrees = null;
        }

        return $degrees;
    }
}
