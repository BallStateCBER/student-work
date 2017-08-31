<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\I18n\Time;

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

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Awards');
        $this->loadModel('Degrees');
        /*$this->loadComponent('Search.Prg', [
            'actions' => ['search']
        ]);*/
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'forgotPassword', 'login', 'register', 'resetPassword'
        ]);
    }

    // gets all the employee's related work, education, etc. experience
    private function getUserVarsPr($id = null)
    {
        $awards = $this->Awards->find('all');
        $awards
            ->select()
            ->where(['user_id' => $id])
            ->order(['awarded_on' => 'ASC'])
            ->toArray();

        if (!iterator_count($awards)) {
            $awards = null;
        }

        $degrees = $this->Degrees->find('all');
        $degrees
            ->select()
            ->where(['user_id' => $id])
            ->order(['date' => 'DESC'])
            ->toArray();

        if (!iterator_count($degrees)) {
            $degrees = null;
        }

        $this->set(compact('awards', 'degrees'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
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
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [
                'Projects'
            ]
        ]);
        $this->getUserVarsPr($id);
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
            $user->email = strtolower(trim($user->email));
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Thanks for registering with us!'));
                $this->Auth->setUser($user);
                return $this->redirect(['action' => 'account']);
            }
            $this->Flash->error(__('Sorry, we could not register you. Please try again.'));
        }
    }

    /**
     * Account method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function account($id = null)
    {
        $id = Router::getRequest()->session()->read('Auth.User.id');
        $user = $this->Users->get($id, [
            'contain' => ['Projects']
        ]);
        $this->getUserVarsPr($id);

        $this->set(compact('user'));
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
        $this->getUserVarsPr($id);

        $this->set(compact('user'));
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
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $role = Router::getRequest()->session()->read('Auth.User.id');
        if ($role == 'Site Admin') {
            $this->request->allowMethod(['get']);
            $user = $this->Users->get($id);
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error('Sorry, you are not authorized to delete users.');
        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $this->set('titleForLayout', 'Log In');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                // Remember login information
                if ($this->request->data('auto_login')) {
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
                    return $this->Flash->success('Message sent. You should be shortly receiving an email with a link to reset your password.');
                }
                $this->Flash->error('Whoops. There was an error sending your password-resetting email out. Please try again, and if it continues to not work, email <a href="mailto:'.$adminEmail.'">'.$adminEmail.'</a> for assistance.');
            }
            if (!$userId) {
                $this->Flash->error('We couldn\'t find an account registered with the email address '.$email.'.');
            }

            if (!isset($email)) {
                $this->Flash->error('Please enter the email address you registered with to have your password reset.');
            }
        }
    }

    public function resetPassword($userId, $resetPasswordHash)
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
            $this->Flash->error('Invalid password-resetting code. Make sure that you entered the correct address and that the link emailed to you hasn\'t expired.');
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
            $this->Flash->error('There was an error changing your password. Please check to make sure they\'ve been entered correctly.');
            return $this->redirect('/');
        }
    }
}
