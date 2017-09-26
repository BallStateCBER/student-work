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
use Cake\Database\Type;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
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
        //Automaticaly Login.
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
        $this->loadComponent('Cookie');
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

        $activeUser = $this->request->session()->read('Auth.User');
        $this->set(compact('activeUser'));

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

    /**
     * Determines if user is authorized.
     *
     * @return bool $user['role']
     */
    public function isAuthorized()
    {
        $user = $this->request->session()->read('Auth.User');

        return $user['admin'];
    }
}
