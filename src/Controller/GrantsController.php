<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Grants Controller
 *
 * @property \App\Model\Table\GrantsTable $Grants
 *
 * @method \App\Model\Entity\Grant[] paginate($object = null, array $settings = [])
 */
class GrantsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny([
            'add', 'delete', 'edit'
        ]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $grant = $this->Grants->newEntity();
        if ($this->request->is('post')) {
            $grant = $this->Grants->patchEntity($grant, $this->request->getData());
            if ($this->Grants->save($grant)) {
                return $this->Flash->success(__('The grant has been saved.'));
            }
            $this->Flash->error(__('The grant could not be saved. Please, try again.'));
        }
        $this->set(compact('grant'));
        $this->set('_serialize', ['grant']);
        $this->set(['titleForLayout' => 'Add a Grant']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Grant id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $grant = $this->Grants->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $grant = $this->Grants->patchEntity($grant, $this->request->getData());
            if ($this->Grants->save($grant)) {
                return $this->Flash->success(__('The grant has been saved.'));
            }
            $this->Flash->error(__('The grant could not be saved. Please, try again.'));
        }
        $this->set(compact('grant'));
        $this->set('_serialize', ['grant']);
        $this->set(['titleForLayout' => "Edit Grant: $grant->name"]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Grant id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $grant = $this->Grants->get($id);
        if ($this->Grants->delete($grant)) {
            $this->Flash->success(__('The grant has been deleted.'));
        } else {
            $this->Flash->error(__('The grant could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
