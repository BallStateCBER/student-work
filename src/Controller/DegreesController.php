<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Degrees Controller
 *
 * @property \App\Model\Table\DegreesTable $Degrees
 *
 * @method \App\Model\Entity\Degree[] paginate($object = null, array $settings = [])
 */
class DegreesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadModel('Users');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $degreeTypes = $this->Degrees->getDegreeTypes();

        $degree = $this->Degrees->newEntity();
        $this->set(['titleForLayout' => 'Add Educational Experience']);
        $this->set(compact('degree', 'degreeTypes'));
        $this->set('_serialize', ['degree']);

        if ($this->request->is('post')) {
            $degree = $this->Degrees->patchEntity($degree, $this->request->getData());
            $grad = $this->Users->getUserByName($this->request->data['user_id']);
            $degree->user_id = $grad->id;
            if ($this->Degrees->save($degree)) {
                return $this->Flash->success(__('The degree has been saved.'));
            }
            $this->Flash->error(__('The degree could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Degree id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $degreeTypes = $this->Degrees->getDegreeTypes();

        $degree = $this->Degrees->get($id, [
            'contain' => []
        ]);

        $this->set(['titleForLayout' => 'Edit this Degree']);
        $this->set(compact('degree', 'degreeTypes'));
        $this->set('_serialize', ['degree']);

        if ($degree['user_id'] != $this->Auth->user('id')) {
            if (!$this->isAuthorized()) {
                return $this->Flash->error('Sorry, you are not authorized to edit this degree.');
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $degree = $this->Degrees->patchEntity($degree, $this->request->getData());
            $grad = $this->Users->getUserByName($this->request->data['user_id']);
            $degree->user_id = $grad->id;
            if ($this->Degrees->save($degree)) {
                return $this->Flash->success(__('The degree has been saved.'));
            }
            $this->Flash->error(__('The degree could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Degree id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $degree = $this->Degrees->get($id);
        if ($this->Degrees->delete($degree)) {
            $this->Flash->success(__('The degree has been deleted.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'account']);
        } else {
            $this->Flash->error(__('The degree could not be deleted. Please, try again.'));
        }
    }
}
