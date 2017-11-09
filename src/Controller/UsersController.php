<?php
namespace App\Controller;

use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @method \App\Model\Entity\User[]
 */
class UsersController extends AppController
{
    /*public $components = [
        'Search.Prg'
    ];*/

    /**
     * initialize controller
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * isAuthorized
     *
     * @param User|null $user User entity
     * @return bool
     */
    public function isAuthorized($user = null)
    {
        if (php_sapi_name() == 'cli') {
            $user = $this->request->session()->read(['Auth']);
            $user = $user['User'];
        }
        if (!$user['is_admin']) {
            if ($this->request->getParam('action') == 'edit') {
                $this->Flash->error('Only admins can edit accounts.');
                $this->redirect(['controller' => 'Users', 'action' => 'index']);

                return false;
            }
            if ($this->request->getParam('action') == 'register') {
                $this->Flash->error('Only admins can create accounts.');
                $this->redirect(['controller' => 'Users', 'action' => 'index']);

                return false;
            }
            if ($this->request->getParam('action') == 'delete') {
                $this->Flash->error('Only admins can delete accounts.');
                $this->redirect(['controller' => 'Users', 'action' => 'index']);

                return false;
            }
        }

        return true;
    }

    /**
     * beforeFilter
     *
     * @param  Event  $event beforeFilter
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'forgotPassword', 'login', 'resetPassword'
        ]);
    }

    /**
     * get degrees and awards of user
     * @param  int $id null
     * @return void
     */
    private function getUserVars($id = null)
    {
        $awards = $this->Awards->getAwards($id);

        $degrees = $this->Degrees->getDegrees($id);

        $this->set(compact('awards', 'degrees'));
    }

    /**
     * Index method
     * @return void
     */
    public function index()
    {
        $users = $this->Users->find('all', [
            'order' => ['start_date' => 'ASC']
        ]);
        $userCount = $users->count();

        $this->set(compact('users', 'userCount'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @return void
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [
                'Projects'
            ]
        ]);
        $this->getUserVars($id);
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void
     */
    public function register()
    {
        $this->set(['titleForLayout' => 'Register']);
        $user = $this->Users->newEntity();
        $this->set(compact('user', 'projects'));
        $this->set('_serialize', ['user']);

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!$user->isNew()) {
                $this->Flash->error('This user ID is already in use?');

                return;
            }
            $user->email = strtolower(trim($user->email));
            if ($this->Users->save($user)) {
                $this->Flash->success(__("You have successfully registered user #$user->id."));
                $this->redirect(['action' => 'index']);

                return;
            }
            $this->Flash->error(__('Sorry, we could not register you. Please try again.'));
        }
    }

    /**
     * Account method
     *
     * @return void
     */
    public function account()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Projects']
        ]);
        $this->getUserVars($id);

        $resetPasswordHash = $this->Users->getResetPasswordHash($user->id, $user->email);
        $this->set(compact('resetPasswordHash', 'user'));
        $this->set('_serialize', ['user']);
        $this->set(['titleForLayout' => 'Your Account Info']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your information has been saved!'));

                return;
            }
            $this->Flash->error(__('Your information could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Projects']
        ]);
        $this->getUserVars($id);

        $resetPasswordHash = $this->Users->getResetPasswordHash($user->id, $user->email);
        $this->set(compact('resetPasswordHash', 'user'));
        $this->set('_serialize', ['user']);
        $this->set(['titleForLayout' => "Edit user: $user->name"]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your information has been saved!'));

                return;
            }
            $this->Flash->error(__('Your information could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Degree id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The degree has been deleted.'));
        } else {
            $this->Flash->error(__('The degree could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * login for users
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        $this->set('titleForLayout', 'Log In');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                // do they have an old sha1 password?
                if ($this->Auth->authenticationProvider()->needsPasswordRehash()) {
                    $user = $this->Users->get($this->Auth->user('id'));
                    $user['password'] = $this->request->getData('password');
                    $this->Users->save($user);
                }

                // Remember login information
                if ($this->request->getData('remember_me')) {
                    $this->Cookie->configKey('User', [
                        'expires' => '+1 year',
                        'httpOnly' => true
                    ]);
                    $this->Cookie->write('User', [
                        'email' => $this->request->getData('email'),
                        'password' => $this->request->getData('password')
                    ]);
                }

                return $this->redirect($this->Auth->redirectUrl());
            }
            if (!$user) {
                $this->Flash->error(__('We could not log you in. Please check your email & password.'));
            }
        }

        return null;
    }

    /**
     * log out users
     *
     * @return \Cake\Http\Response
     */
    public function logout()
    {
        $this->Flash->success('Thanks for stopping by!');

        return $this->redirect($this->Auth->logout());
    }

/*    public function search()
    {
        $filter= $this->request->query;

        $users = $this->Events->find('search', [
            'search' => $filter,
            'contain' => ['Projects']])
            ->toArray();

        if ($users) {
            $this->indexEvents($users);
        }

        $this->set([
            'titleForLayout' => 'Search Results'
        ]);
    } */

    /**
     * for when the user forgets their password
     *
     * @return null
     */
    public function forgotPassword()
    {
        $this->set([
            'titleForLayout' => 'Forgot Password'
        ]);

        if ($this->request->is('post')) {
            $adminEmail = Configure::read('admin_email');
            $email = strtolower(trim($this->request->getData('email')));
            $userId = $this->Users->getIdFromEmail($email);
            if ($userId) {
                if ($this->Users->sendPasswordResetEmail($userId, $email)) {
                    $this->Flash->success('Message sent. You should be shortly receiving an email with a link to reset your password.');

                    return null;
                }
                $this->Flash->error("Whoops. There was an error sending your password-resetting email out. Please try again, and if it continues to not work, email $adminEmail for more assistance.");
            }
            if (!$userId) {
                $this->Flash->error("We couldn't find an account registered with the email address $email.");
            }

            if (!isset($email)) {
                $this->Flash->error('Please enter the email address you registered with to have your password reset.');
            }
        }

        return null;
    }

    /**
     * reset password of user
     *
     * @param int $userId of the user to reset
     * @param string $resetPasswordHash for the user to reset
     * @return null
     */
    public function resetPassword($userId, $resetPasswordHash)
    {
        $user = $this->Users->get($userId);
        $email = $user['email'];

        $this->set([
            'titleForLayout' => 'Reset Password',
            'user_id' => $userId,
            'email' => $email,
            'reset_password_hash' => $resetPasswordHash
        ]);

        $expectedHash = $this->Users->getResetPasswordHash($userId, $email);

        if ($resetPasswordHash != $expectedHash) {
            $this->Flash->error('Invalid password-resetting code. Make sure that you entered the correct address and that the link emailed to you hasn\'t expired.');
            $this->redirect('/');
        }

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, [
                'password' => $this->request->getData('new_password'),
                'confirm_password' => $this->request->getData('new_confirm_password')
            ]);

            if ($this->Users->save($user)) {
                $data = $user->toArray();
                $this->Auth->setUser($data);
                $this->Flash->success('Password changed. You are now logged in.');

                return null;
            }
            $this->Flash->error('There was an error changing your password. Please check to make sure they\'ve been entered correctly.');

            return $this->redirect('/');
        }

        return null;
    }
}
