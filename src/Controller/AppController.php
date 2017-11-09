<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property \App\Model\Table\AwardsTable $Awards
 * @property \App\Model\Table\DegreesTable $Degrees
 * @property \App\Model\Table\FundsTable $Funds
 * @property \App\Model\Table\ProjectsTable $Projects
 * @property \App\Model\Table\UsersTable $Users
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $helpers = [
        'AkkaCKEditor.CKEditor' => [
            'version' => '4.7.3',
            'distribution' => 'basic'
            // Default Option / Other options => 'basic', 'standard', 'standard-all', 'full-all'
        ],
        'CakeJs.Js',
        'Flash',
        'Form',
        'Html'
    ];

    /**
     * beforeFilter
     *
     * @param  Event  $event beforeFilter
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        // Automatically login.
        if (!$this->Auth->user() && $this->Cookie->read('CookieAuth')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
            } else {
                $this->Cookie->delete('CookieAuth');
            }
        }
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie', [
            'encryption' => 'aes',
            'key' => Configure::read('cookie_key')
        ]);
        $this->loadComponent(
            'Auth',
            [
                'loginAction' => [
                    'controller' => 'Users',
                    'action' => 'login'
                ],
                'loginRedirect' => [
                    'prefix' => false,
                    'controller' => 'Users',
                    'action' => 'index'
                ],
                'logoutRedirect' => [
                    'prefix' => false,
                    'controller' => 'Pages',
                    'action' => 'home'
                ],
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                            ]
                        ],
                    'Xety/Cake3CookieAuth.Cookie'
                ],
                'authorize' => ['Controller']
            ]
        );


        $this->loadModel('Awards');
        $this->loadModel('Degrees');
        $this->loadModel('Projects');
        $this->loadModel('Reports');
        $this->loadModel('UsersProjects');
        $this->loadModel('Users');

        $activeUser = $this->request->session()->read('Auth.User');
        $name = explode(' ', trim($activeUser['name']));
        $name = $name[0];
        $name = $name != '' ? $name . ' :' : '';

        $this->set(compact('activeUser', 'name'));

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
}
