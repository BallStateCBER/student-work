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
    /**
     * beforeFilter
     *
     * @param  Event  $event beforeFilter
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadModel('Users');
    }

    /**
     * isAuthorized
     *
     * return bool
     */
    public function isAuthorized($user)
    {
        if (php_sapi_name() == 'cli') {
            $user = $this->request->session()->read(['Auth']);
            $user = $user['User'];
        }
        if (!$user['admin']) {
            if ($this->request->getParam('action') == 'edit' || $this->request->getParam('action') == 'delete') {
                $entityId = $this->request->getParam('pass')[0];
                $entity = $this->Degrees->get($entityId);

                return $entity->user_id === $user['id'];
            }
        }
        return true;
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
            $grad = $this->Users->findByName($this->request->getData('user_id'))->first();

            // nothing found? the user has not set their name
            $gradId = $grad != null ? $grad->id : $this->request->getData('user_id');

            if (!$this->Auth->user('admin')) {
                if ($this->Auth->user('id') != $gradId) {
                    return $this->Flash->error('You cannot make a degree for someone else.');
                }
            }

            $degree->user_id = $gradId;
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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $degree = $this->Degrees->patchEntity($degree, $this->request->getData());
            $grad = $this->Users->findByName($this->request->getData('user_id'))->first();

            // nothing found? the user has not set their name
            $gradId = $grad != null ? $grad->id : $this->request->getData('user_id');

            if (!$this->Auth->user('admin')) {
                if ($this->Auth->user('id') != $gradId) {
                    return $this->Flash->error('You cannot make a degree for someone else.');
                }
            }

            $degree->user_id = $gradId;
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
