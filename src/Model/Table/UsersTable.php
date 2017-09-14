<?php
namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Awards
 * @property \Cake\ORM\Association\HasMany $Jobs
 * @property \Cake\ORM\Association\BelongsToMany $Projects
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
                'nameCallback' => function (array $data, array $settings) {
                    $ext = pathinfo($data['name'], PATHINFO_EXTENSION);
                    $salt = Configure::read('profile_salt');
                    $newFilename = md5($data['name'] . $salt);

                    return $newFilename . '.' . $ext;
                },
                'path' => 'webroot' . DS . 'img' . DS . 'users'
            ]
        ]);

        $this->hasMany('Awards', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Projects', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'project_id',
            'joinTable' => 'users_projects'
        ]);

        # $this->addBehavior('Search.Search');

        /* $this->searchManager()
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->add('filter', 'Search.Like', [
                'before' => true,
                'after' => true,
                'fieldMode' => 'OR',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => ['name']
            ])
            ->add('foo', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    // Modify $query as required
                }
            ]); */
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
            ->requirePresence('id', 'create')
            ->notEmpty('id');

        $validator
            ->add('email', 'validFormat', [
                'rule' => ["email", false, '/^.+@bsu\.edu/i'],
                'message' => 'You must enter your @bsu.edu email address.'
            ])
            ->email('email');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->allowEmpty('image');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['id']));

        return $rules;
    }

    /**
     * get email from id $userId
     * @param  int|null $userId User ID
     * @return string $email
     */
    public function getEmailFromId($userId = null)
    {
        $query = TableRegistry::get('Users')->find()->select(['email'])->where(['id' => $userId]);
        $result = $query->all();
        $email = $result->toArray();
        $email = implode($email);
        $email = trim($email, '{}');
        $email = str_replace('"email": ', '', $email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }

    /**
     * get user->id from $email
     * @param string|null $email User email
     * @return object property $result->id or bool
     */
    public function getIdFromEmail($email = null)
    {
        $result = $this->find()
            ->select(['id'])
            ->where(['email' => $email])
            ->first();
        if ($result) {
            return $result->id;
        }

        return false;
    }

    /**
     * get reset password hash
     * @param int|null $userId User ID
     * @param string|null $email User email
     * @return string hash
     */
    public function getResetPasswordHash($userId = null, $email = null)
    {
        $salt = Configure::read('password_reset_salt');
        $month = date('my');

        return md5($userId . $email . $salt . $month);
    }

    /**
     * get user-> name from id $userId
     * @param int|null $userId User ID
     * @return object property $user->name
     */
    public function getUserNameFromId($userId = null)
    {
        $user = $this->get($userId);

        return $user->name;
    }

    /**
     * send password reset email
     * @param int|null $userId User ID
     * @param string|null $email User email
     * @return $resetEmail
     */
    public function sendPasswordResetEmail($userId = null, $email = null)
    {
        $resetPasswordHash = $this->getResetPasswordHash($userId, $email);
        $resetEmail = new Email('default');
        $resetUrl = Router::url([
            'controller' => 'users',
            'action' => 'resetPassword',
            $userId,
            $resetPasswordHash
        ], true);
        $resetEmail
            ->setTo($email)
            ->setSubject('Muncie Events: Reset Password')
            ->setTemplate('forgot_password')
            ->setEmailFormat('both')
            ->setHelpers(['Html', 'Text'])
            ->setViewVars(compact(
                'email',
                'resetUrl'
            ));

        return $resetEmail->send();
    }
}
