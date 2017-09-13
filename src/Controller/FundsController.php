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
     * controller beforeFilter
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * initialize controller and set auth
     */
    public function initialize()
    {
        parent::initialize();
        if (!$this->isAuthorized()) {
            $this->Flash->error('Only admins can access funding details.');

            return $this->redirect(['controller' => 'Reports', 'action' => 'index']);
        }
    }

    /**
     * Index method
     *
     */
    public function index()
    {
        $this->paginate;
        $funds = $this->paginate($this->Funds);

        $this->set(compact('funds'));
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

    /**
     * viewing fund details
     * in individual pages
     */
    public function view($id = null)
    {
        $fund = $this->Funds->get($id);

        $this->set('fund', $fund);
        $this->set('_serialize', ['fund']);
    }
}
