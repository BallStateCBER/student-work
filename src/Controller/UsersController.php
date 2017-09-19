<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /*public $components = [
        'Search.Prg'
    ];*/

    /**
     * initialize controller
     *
     * @return Redirect
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Awards');
        $this->loadModel('Degrees');
        if (!$this->isAuthorized()) {
            if ($this->request->getParam('action') == 'edit') {
                $this->Flash->error('Only admins can edit accounts.');

                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
            if ($this->request->getParam('action') == 'register') {
                $this->Flash->error('Only admins can create accounts.');

                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
            if ($this->request->getParam('action') == 'delete') {
                $this->Flash->error('Only admins can delete accounts.');

                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
        }
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
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
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
                return $this->Flash->error('This user ID is already in use?');
            }
            $user->email = strtolower(trim($user->email));
            if ($this->Users->save($user)) {
                $this->Flash->success(__("You have successfully registered user #$user->id."));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Sorry, we could not register you. Please try again.'));
        }
    }

    /**
     * Account method
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
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
                return $this->Flash->success(__('Your information has been saved!'));
            }

            return $this->Flash->error(__('Your information could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Projects']
        ]);
        $this->getUserVars($id);

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        $this->set(['titleForLayout' => "Edit user: $user->name"]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                return $this->Flash->success(__('Your information has been saved!'));
            }

            return $this->Flash->error(__('Your information could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['get']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * user login
     * @return redirect
     */
    public function login()
    {
        $this->set('titleForLayout', 'Log In');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                // Remember login information
                if ($this->request->data('remember_me')) {
                    $this->Cookie->configKey('CookieAuth', [
                        'expires' => '+1 year',
                        'httpOnly' => true
                    ]);
                    $this->Cookie->write('CookieAuth', [
                        'email' => $this->request->data('email'),
                        'password' => $this->request->data('password')
                    ]);
                }

                return $this->redirect($this->Auth->redirectUrl());
            }
            if (!$user) {
                $this->Flash->error(__('We could not log you in. Please check your email & password.'));
            }
        }
    }

    /**
     * user logout
     * @return redirect
     */
    public function logout()
    {
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
     * forgotPassword method
     * @return email
     */
    public function forgotPassword()
    {
        $this->set([
            'titleForLayout' => 'Forgot Password'
        ]);

        if ($this->request->is('post')) {
            $adminEmail = Configure::read('admin_email');
            $email = strtolower(trim($this->request->data['email']));
            $userId = $this->Users->getIdFromEmail($email);
            if ($userId) {
                if ($this->Users->sendPasswordResetEmail($userId, $email)) {
                    return $this->Flash->success(
                        'Message sent. You should be shortly receiving an email with a link to reset your password.'
                    );
                }
                $this->Flash->error(
                    'Whoops. There was an error sending your password-resetting email out.
                    Please try again, and if it continues to not work,
                    email <a href="mailto:' . $adminEmail . '">' . $adminEmail . '</a> for assistance.'
                );
            }
            if (!$userId) {
                $this->Flash->error('We couldn\'t find an account registered with the email address ' . $email . '.');
            }

            if (!isset($email)) {
                $this->Flash->error('Please enter the email address you registered with to have your password reset.');
            }
        }
    }

    /**
     * resetting a password after getting the hash
     *
     * @param int|null $userId User id
     * @param string|null $resetPasswordHash hash to reset the password
     * @return \Cake\Http\Response|null Redirect
     */
    public function resetPassword($userId = null, $resetPasswordHash = null)
    {
        $user = $this->Users->get($userId);
        $email = $user->email;

        $this->set([
            'titleForLayout' => 'Reset Password',
            'user_id' => $userId,
            'email' => $email,
            'reset_password_hash' => $resetPasswordHash
        ]);

        $expectedHash = $this->Users->getResetPasswordHash($userId, $email);

        if ($resetPasswordHash != $expectedHash) {
            $this->Flash->error(
                'Invalid password-resetting code. Make sure that you entered the correct address
                and that the link emailed to you hasn\'t expired.'
            );
            $this->redirect('/');
        }

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, [
                'password' => $this->request->data['new_password'],
                'confirm_password' => $this->request->data['new_confirm_password']
            ]);

            if ($this->Users->save($user)) {
                $data = $user->toArray();

                $this->Auth->setUser($data);

                return $this->Flash->success('Password changed. You are now logged in.');
            }
            $this->Flash->error(
                'There was an error changing your password.
                Please check to make sure they\'ve been entered correctly.'
            );

            return $this->redirect('/');
        }
    }
}
