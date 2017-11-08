<?php
namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\Event;

/**
 * Degrees Controller
 *
 * @method \App\Model\Entity\Degree[]
 */
class DegreesController extends AppController
{
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
            if ($this->request->getParam('action') == 'edit' || $this->request->getParam('action') == 'delete') {
                $entityId = $this->request->getParam('pass')[0];
                $entity = $this->Degrees->get($entityId);

                return $entity->user_id === $user['id'];
            }
        }

        return true;
    }

    /**
     * beforeFilter
     *
     * @param Event $event beforeFilter
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Add method
     *
     * @return void
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
                    $this->Flash->error('You cannot make a degree for someone else.');

                    return;
                }
            }

            $degree->user_id = $gradId;
            if ($this->Degrees->save($degree)) {
                $this->Flash->success(__('The degree has been saved.'));

                return;
            }
            $this->Flash->error(__('The degree could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Degree id.
     * @return void
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
                    $this->Flash->error('You cannot make a degree for someone else.');

                    return;
                }
            }

            $degree->user_id = $gradId;
            if ($this->Degrees->save($degree)) {
                $this->Flash->success(__('The degree has been saved.'));

                return;
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

        return null;
    }
}
