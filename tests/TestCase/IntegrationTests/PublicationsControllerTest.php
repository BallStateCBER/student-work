<?php
namespace App\Test\TestCase\Controller;

use App\Controller\PublicationsController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

class PublicationsControllerTest extends IntegrationTestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $classes = ['Publications', 'Users'];
        foreach ($classes as $class) {
            $config = TableRegistry::exists("$class") ? [] : ['className' => 'App\Model\Table\\'.$class.'Table'];
            $this->$class = TableRegistry::get("$class", $config);
        }

        $this->UsersPublications = TableRegistry::get("UsersPublications");
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        $classes = ['Publications', 'Users'];
        foreach ($classes as $class) {
            unset($this->$class);
        }
        unset($this->UsersPublications);
        parent::tearDown();
    }

    /**
     * Test publication add page view & use
     *
     * @return void
     */
    public function testAddPublication()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $this->get('/publications/add');
        $this->assertResponseOk();

        $publication = [
            'title' => 'Publication Win',
            'url' => 'americanplaceholderassociation.com',
            'grant_id' => 1,
            'abstract' => 'Here is some text',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ]
        ];

        $this->post('/publications/add', $publication);
        $this->assertResponseSuccess();

        $publication = $this->Publications->find()
            ->where(['title' => $publication['title']])
            ->firstOrFail();

        if ($publication) {
            $this->assertResponseSuccess();
            return;
        }
    }

    /**
     * Test publication edit page
     *
     * @return void
     */
    public function testEditingPublications()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $publication = $this->Publications->find()
            ->where(['title' => 'Publication Win'])
            ->firstOrFail();

        $this->get("/publications/edit/$publication->id");
        $this->assertResponseOk();

        $newPublication = [
            'title' => 'Test Publication',
            'url' => 'americantestingassociation.com',
            'grant_id' => 1,
            'abstract' => 'Here is some TEST',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ]
        ];

        $this->post("/publications/edit/$publication->id", $newPublication);
        $this->assertResponseSuccess();

        $publication = $this->Publications->find()
            ->where(['title' => $newPublication['title']])
            ->firstOrFail();

        if ($publication) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test publication edit page and add users to it
     *
     * @return void
     */
    public function testAddingUsersToPublication()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $publication = $this->Publications->find()
            ->where(['title' => 'Test Publication'])
            ->firstOrFail();

        $newPublication = [
            'title' => 'Test Publication',
            'url' => 'americantestingassociation.com',
            'grant_id' => 1,
            'abstract' => 'Here is some TEST',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'users' => [
                0 => [
                    '_joinData' => [
                        'user_id' => $id,
                        'role' => 'Place Holder',
                        'delete' => 0
                    ]
                ]
            ]
        ];

        $this->post("/publications/edit/$publication->id", $newPublication);
        $this->assertResponseSuccess();

        $publication = $this->UsersPublications->find()
            ->where(['user_id' => $id])
            ->firstOrFail();
    }

    /**
     * Test publication edit page and remove users from it
     *
     * @return void
     */
    public function testRemovingUsersFromPublication()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $publication = $this->Publications->find()
            ->where(['title' => 'Test Publication'])
            ->firstOrFail();

        $newPublication = [
            'title' => 'Test Publication',
            'url' => 'americantestingassociation.com',
            'grant_id' => 1,
            'abstract' => 'Here is some TEST',
            'date_published' => [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ],
            'users' => [
                0 => [
                    '_joinData' => [
                        'user_id' => $id,
                        'role' => 'Place Holder',
                        'delete' => 0
                    ],
                    'delete' => 1
                ]
            ]
        ];

        $this->post("/publications/edit/$publication->id", $newPublication);
        $this->assertResponseSuccess();

        $publication = $this->UsersPublications->find()
            ->where(['user_id' => $id])
            ->first();

        if (!isset($publication->id)) {
            $this->assertResponseSuccess();
            return;
        }

        $this->assertResponseError();
    }

    /**
     * Test deleting publications
     *
     * @return void
     */
    public function testDeletingPublications()
    {
        $id = $this->Users->getIdFromEmail('edfox@bsu.edu');
        $this->session(['Auth.User.id' => $id]);

        $publication = $this->Publications->find()
            ->where(['title' => 'Test Publication'])
            ->firstOrFail();

        $this->get("/publications/delete/$publication->id");
        $this->assertResponseSuccess();
    }
}
