<?php
namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\Event;

/**
 * Awards Controller
 *
 * @method \App\Model\Entity\Award[]
 */
class AwardsController extends AppController
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
                $entity = $this->Awards->get($entityId);

                return $entity->user_id === $user['id'];
            }
        }

        return true;
    }

    /**
     * beforeFilter
     *
     * @param  Event  $event beforeFilter
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
        $award = $this->Awards->newEntity();

        $users = $this->Awards->Users->find('list', ['limit' => 200]);
        $this->set(compact('award', 'users'));
        $this->set('_serialize', ['award']);
        $this->set(['titleForLayout' => 'Add an Award']);

        if ($this->request->is('post')) {
            $award = $this->Awards->patchEntity($award, $this->request->getData());
            $awardee = $this->Users->findByName($this->request->getData('user_id'))->first();

            // nothing found? the user has not set their name
            $awardeeId = $awardee != null ? $awardee->id : $this->request->getData('user_id');

            if (!$this->Auth->user('admin')) {
                if ($this->Auth->user('id') != $awardeeId) {
                    $this->Flash->error('You cannot make an award for someone else.');

                    return;
                }
            }

            $award->user_id = $awardeeId;
            if ($this->Awards->save($award)) {
                $this->Flash->success(__('The award has been saved.'));

                return;
            }
            $this->Flash->error(__('The award could not be saved. Please, try again.'));

            return;
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Award id.
     * @return void
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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $award = $this->Awards->patchEntity($award, $this->request->getData());
            $awardee = $this->Users->findByName($this->request->getData('user_id'))->first();

            // nothing found? the user has not set their name
            $awardeeId = $awardee != null ? $awardee->id : $this->request->getData('user_id');

            if (!$this->Auth->user('admin')) {
                if ($this->Auth->user('id') != $awardeeId) {
                    $this->Flash->error('You cannot make an award for someone else.');

                    return;
                }
            }

            $award->user_id = $awardeeId;
            if ($this->Awards->save($award)) {
                $this->Flash->success(__('The award has been saved.'));

                return;
            }
            $this->Flash->error(__('The award could not be saved. Please, try again.'));

            return;
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

        return null;
    }
}
