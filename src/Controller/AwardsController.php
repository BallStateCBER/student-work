<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Awards Controller
 *
 * @property \App\Model\Table\AwardsTable $Awards
 *
 * @method \App\Model\Entity\Award[] paginate($object = null, array $settings = [])
 */
class AwardsController extends AppController
{
    /**
     * initialize controller and load models
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    /**
     * controller beforeFilter
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $award = $this->Awards->newEntity();

        $users = $this->Awards->Users->find('list', ['limit' => 200]);
        $this->set(compact('award', 'users'));
        $this->set('_serialize', ['award']);
        $this->set(['titleForLayout' => 'Add an Award']);

        if ($this->request->is('post')) {
            $award = $this->Awards->patchEntity($award, $this->request->getData());
            $awardee = $this->Users->getUserByName($this->request->data['user_id']);
            $award->user_id = $awardee->id;
            if ($this->Awards->save($award)) {
                return $this->Flash->success(__('The award has been saved.'));
            }
            $this->Flash->error(__('The award could not be saved. Please, try again.'));

            return $this->redirect('/users');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Award id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $award = $this->Awards->get($id, [
            'contain' => []
        ]);

        $users = $this->Awards->Users->find('list', ['limit' => 200]);
        $this->set(compact('award', 'users'));
        $this->set('_serialize', ['award']);
        $this->set(['titleForLayout' => 'Edit Award: ' . $award->name]);

        if ($award['user_id'] != $this->Auth->user('id')) {
            if (!$this->isAuthorized()) {
                return $this->Flash->error('Sorry, you are not authorized to edit this award.');
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $award = $this->Awards->patchEntity($award, $this->request->getData());
            $awardee = $this->Users->getUserByName($this->request->data['user_id']);
            $award->user_id = $awardee->id;
            if ($this->Awards->save($award)) {
                return $this->Flash->success(__('The award has been saved.'));
            }
            $this->Flash->error(__('The award could not be saved. Please, try again.'));

            return $this->redirect('/users');
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Award id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $award = $this->Awards->get($id);
        if ($this->Awards->delete($award)) {
            $this->Flash->success(__('The award has been deleted.'));

            return $this->redirect(['controller' => 'Users', 'action' => 'account']);
        } else {
            $this->Flash->error(__('The award could not be deleted. Please, try again.'));
        }
    }
}
