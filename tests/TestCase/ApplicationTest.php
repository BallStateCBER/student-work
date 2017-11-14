<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase;

use App\Application;
use App\Test\Fixture\UsersFixture;
use App\Test\Fixture\UsersProjectsFixture;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\TableRegistry;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ApplicationTest Test Case
 *
 * @property \App\Model\Table\AwardsTable $Awards
 * @property \App\Model\Table\DegreesTable $Degrees
 * @property \App\Model\Table\FundsTable $Funds
 * @property \App\Model\Table\ProjectsTable $Projects
 * @property \App\Model\Table\ReportsTable $Reports
 * @property \App\Model\Table\UsersTable $Users
 * @property \Cake\ORM\Association\BelongsToMany $UsersProjects
 */
class ApplicationTest extends IntegrationTestCase
{
    // fixtures
    public $awards;
    public $degrees;
    public $funds;
    public $projects;
    public $reports;

    // users fixtures
    public $admin;
    public $currentEmployee;
    public $formerEmployee;

    // users-projects jointable fixtures
    public $usersProjects;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.awards',
        'app.degrees',
        'app.funds',
        'app.projects',
        'app.reports',
        'app.users',
        'app.users_projects'
    ];

    public $classes = [
        'Awards',
        'Degrees',
        'Funds',
        'Projects',
        'Reports',
        'Users',
        'Users_Projects'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        foreach ($this->classes as $object) {
            $this->$object = TableRegistry::get($object);
        }

        $nonUserClasses = ['Awards', 'Degrees', 'Funds', 'Projects', 'Reports'];
        foreach ($nonUserClasses as $class) {
            $fixture = "App\Test\Fixture\\" . $class . "Fixture";
            $object = new $fixture();
            $class = strtolower($class);
            $this->$class = [];
            foreach ($object->records as $fix) {
                $this->$class[] = $fix;
            }
        }

        // set up the users fixtures
        $usersFixture = new UsersFixture();

        $this->currentEmployee = [
            'Auth' => [
                'User' => $usersFixture->records[0]
            ]
        ];

        $this->formerEmployee = [
            'Auth' => [
                'User' => $usersFixture->records[1]
            ]
        ];

        $this->admin = [
            'Auth' => [
                'User' => $usersFixture->records[2]
            ]
        ];

        // finally, set up users-projects jointable fixtures
        $usersProjectsFixture = new UsersProjectsFixture();
        $this->usersProjects = [];

        foreach ($usersProjectsFixture->records as $userProject) {
            $this->usersProjects[] = $userProject;
        }
    }

    /**
     * testMiddleware
     *
     * @return void
     */
    public function testMiddleware()
    {
        $app = new Application(dirname(dirname(__DIR__)) . '/config');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->get(0));
        $this->assertInstanceOf(AssetMiddleware::class, $middleware->get(1));
        $this->assertInstanceOf(RoutingMiddleware::class, $middleware->get(2));
    }
}
