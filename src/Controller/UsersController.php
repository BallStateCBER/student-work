<?php
namespace App\Controller;

use App\Controller\AppController;
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
        $this->loadModel('Jobs');
        /*$this->loadComponent('Search.Prg', [
            'actions' => ['search']
        ]);*/
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny([
            'account', 'delete'
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

        $jobs = $this->Jobs->find('all');
        $jobs
            ->select()
            ->where(['user_id' => $id])
            ->order(['job_title' => 'ASC'])
            ->toArray();

        if (!iterator_count($jobs)) {
            $jobs = null;
        }

        $this->set(compact('awards', 'degrees', 'jobs'));
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
                'Localprojects',
                'Publications',
                'Sites'
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
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->email = strtolower(trim($user->email));
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Thanks for registering with us!'));
                $this->Auth->setUser($user);
                return $this->redirect(['action' => 'account', $user->id]);
            }
            $this->Flash->error(__('Sorry, we could not register you. Please try again.'));
        }
        $this->set(compact('user', 'localprojects', 'publications', 'sites'));
        $this->set('_serialize', ['user']);
        $this->set(['titleForLayout' => 'Register']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function account($id = null)
    {
        $id = Router::getRequest()->session()->read('Auth.User.id');
        $user = $this->Users->get($id, [
            'contain' => ['Localprojects', 'Publications', 'Sites']
        ]);
        $this->getUserVarsPr($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Your information has been saved!'));
            } else {
                $this->Flash->error(__('Your information could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        $this->set(['titleForLayout' => 'Your Account Info']);
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
            'contain' => ['Localprojects', 'Publications', 'Sites']])
            ->toArray();

        if ($users) {
            $this->indexEvents($users);
        }

        $this->set([
            'titleForLayout' => 'Search Results'
        ]);
    } */
}
