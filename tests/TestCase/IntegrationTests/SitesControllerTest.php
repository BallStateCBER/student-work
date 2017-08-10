<?php
namespace App\Test\TestCase\Controller;

use App\Controller\SitesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class SitesControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Sites', 'Users'];
        foreach ($classes as $class) {
            $config = TableRegistry::exists("$class") ? [] : ['className' => 'App\Model\Table\\'.$class.'Table'];
            $this->$class = TableRegistry::get("$class", $config);
        }

        $this->UsersSites = TableRegistry::get("UsersSites");
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $classes = ['Sites', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        unset($this->UsersSites);
        parent::tearDown();
    }

    /**
     * Test site add page view & use
     *
     * @return void
     */
    public function testAddSite()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/sites/add');
        $this->assertResponseOk();

        $site = [
            'site_name' => 'Site Win',
            'url' => 'americanplaceholderassociation.com',
            'grant_id' => 1,
            'description' => 'Here is some text',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'in_progress' => 1
        ];

        $this->post('/sites/add', $site);
        $this->assertResponseSuccess();

        $site = $this->Sites->find()
            ->where(['site_name' => $site['site_name']])
            ->firstOrFail();

        if ($site) {
            $this->assertResponseSuccess();
            return;
        }
    }

    /**
     * Test site edit page
     *
     * @return void
     */
    public function testEditingSites()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $site = $this->Sites->find()
            ->where(['site_name' => 'Site Win'])
            ->firstOrFail();

        $this->get("/sites/edit/$site->id");
        $this->assertResponseOk();

        $newSite = [
            'site_name' => 'Test Site',
            'url' => 'americantestingassociation.com',
            'grant_id' => 1,
            'description' => 'Here is some TEST',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'in_progress' => 1
        ];

        $this->post("/sites/edit/$site->id", $newSite);
        $this->assertResponseSuccess();

        $site = $this->Sites->find()
            ->where(['site_name' => $newSite['site_name']])
            ->firstOrFail();

        if ($site) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test site edit page and add users to it
     *
     * @return void
     */
    public function testAddingUsersToSite()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $site = $this->Sites->find()
            ->where(['site_name' => 'Test Site'])
            ->firstOrFail();

        $newSite = [
            'site_name' => 'Test Site',
            'url' => 'americantestingassociation.com',
            'grant_id' => 1,
            'description' => 'Here is some TEST',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'in_progress' => 0,
            'users' => [
                0 => [
                    '_joinData' => [
                        'user_id' => $id,
                        'employee_role' => 'Place Holder',
                        'delete' => 0
                    ]
                ]
            ]
        ];

        $this->post("/sites/edit/$site->id", $newSite);
        $this->assertResponseSuccess();

        $site = $this->UsersSites->find()
            ->where(['user_id' => $id])
            ->firstOrFail();
    }

    /**
     * Test site edit page and remove users from it
     *
     * @return void
     */
    public function testRemovingUsersFromSite()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $site = $this->Sites->find()
            ->where(['site_name' => 'Test Site'])
            ->firstOrFail();

        $newSite = [
            'site_name' => 'Test Site',
            'url' => 'americantestingassociation.com',
            'grant_id' => 1,
            'description' => 'Here is some TEST',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'in_progress' => 0,
            'users' => [
                0 => [
                    '_joinData' => [
                        'user_id' => $id,
                        'employee_role' => 'Place Holder',
                        'delete' => 1
                    ],
                    'delete' => 1
                ]
            ]
        ];

        $this->post("/sites/edit/$site->id", $newSite);
        $this->assertResponseSuccess();

        $site = $this->UsersSites->find()
            ->where(['user_id' => $id])
            ->andWhere(['site_id' => $site->id])
            ->first();

        if (!isset($site->id)) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting sites
     *
     * @return void
     */
    public function testDeletingSites()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $site = $this->Sites->find()
            ->where(['site_name' => 'Test Site'])
            ->firstOrFail();

        $this->get("/sites/delete/$site->id");
        $this->assertResponseSuccess();
    }
}
