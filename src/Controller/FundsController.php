<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Funds Controller
 *
 * @property \App\Model\Table\FundsTable $Funds
 *
 * @method \App\Model\Entity\Fund[] paginate($object = null, array $settings = [])
 */
class FundsController extends AppController
{
    /**
     * beforeFilter
     * @param  Event  $event beforeFilter
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

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
        if (!$user['admin']) {
            $this->Flash->error('Only admins can access funding details.');

            return $this->redirect(['controller' => 'Reports', 'action' => 'index']);
        }

        return true;
    }

    /**
     * index funds
     * @return void
     */
    public function index()
    {
        $funds = $this->paginate($this->Funds);

        $fundList = $this->Funds->find('list')
            ->toArray();

        $count = count($fundList);

        // this determines if $count is an odd or even number
        // and sets the dividing point for the paginator
        $halfCount = $count % 2 == 0 ? $count / 2 : ($count + 1) / 2;

        $this->set(compact('count', 'funds', 'halfCount'));
        $this->set('_serialize', ['funds']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $fund = $this->Funds->newEntity();

        $this->set(compact('fund'));
        $this->set('_serialize', ['fund']);
        $this->set(['titleForLayout' => 'Add a Fund']);

        if ($this->request->is('post')) {
            $fund = $this->Funds->patchEntity($fund, $this->request->getData());
            if ($this->Funds->save($fund)) {
                return $this->Flash->success(__('The fund has been saved.'));
            }
            $this->Flash->error(__('The fund could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Fund id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fund = $this->Funds->get($id, [
            'contain' => []
        ]);

        $this->set(compact('fund'));
        $this->set('_serialize', ['fund']);
        $this->set(['titleForLayout' => "Edit Fund: $fund->name"]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $fund = $this->Funds->patchEntity($fund, $this->request->getData());
            if ($this->Funds->save($fund)) {
                return $this->Flash->success(__('The fund has been saved.'));
            }
            $this->Flash->error(__('The fund could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Fund id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $fund = $this->Funds->get($id);
        if ($this->Funds->delete($fund)) {
            $this->Flash->success(__('The fund has been deleted.'));

            return $this->redirect(['action' => 'index']);
        }

        return $this->Flash->error(__('The fund could not be deleted. Please, try again.'));
    }
}
