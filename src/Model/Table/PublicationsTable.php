<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Publications Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Publication get($primaryKey, $options = [])
 * @method \App\Model\Entity\Publication newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Publication[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Publication|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Publication patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Publication[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Publication findOrCreate($search, callable $callback = null, $options = [])
 */
class PublicationsTable extends Table
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

        $this->setTable('publications');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'cover' => [
                'nameCallback' => function (array $data, array $settings) {
                    $ext = pathinfo($data['title'], PATHINFO_EXTENSION);
                    $salt = Configure::read('profile_salt');
                    $newFilename = md5($data['title'].$salt);
                    return $newFilename.'.'.$ext;
                },
                'path' => 'webroot'.DS.'img'.DS.'publications'
            ]
        ]);

        $this->belongsToMany('Users', [
            'foreignKey' => 'publication_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_publications'
        ]);

        $this->hasMany('Grants', [
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
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->allowEmpty('abstract');

        $validator
            ->allowEmpty('cover');

        $validator
            ->allowEmpty('url');

        $validator
            ->allowEmpty('sponsor');

        return $validator;
    }
}
