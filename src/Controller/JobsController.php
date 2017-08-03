<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Jobs Controller
 *
 * @property \App\Model\Table\JobsTable $Jobs
 *
 * @method \App\Model\Entity\Job[] paginate($object = null, array $settings = [])
 */
class JobsController extends AppController
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
        $job = $this->Jobs->newEntity();
        if ($this->request->is('post')) {
            $job = $this->Jobs->patchEntity($job, $this->request->getData());
            $job->user_id = $this->request->session()->read('Auth.User.id');
            if ($this->Jobs->save($job)) {
                $this->Flash->success(__('The job has been saved.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'account']);
            }
            $this->Flash->error(__('The job could not be saved. Please, try again.'));
        }
        $users = $this->Jobs->Users->find('list', ['limit' => 200]);
        $this->set(compact('job', 'users'));
        $this->set('_serialize', ['job']);
        $this->set(['titleForLayout' => 'Add Work Experience']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Job id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $job = $this->Jobs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $job = $this->Jobs->patchEntity($job, $this->request->getData());
            if ($this->Jobs->save($job)) {
                $this->Flash->success(__('The job has been saved.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'account']);
            }
            $this->Flash->error(__('The job could not be saved. Please, try again.'));
        }
        $users = $this->Jobs->Users->find('list', ['limit' => 200]);
        $this->set(compact('job', 'users'));
        $this->set('_serialize', ['job']);
        $this->set(['titleForLayout' => 'Edit Work Experience: '.$job->job_title]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Job id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $job = $this->Jobs->get($id);
        if ($this->Jobs->delete($job)) {
            $this->Flash->success(__('The job has been deleted.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'account']);
        } else {
            $this->Flash->error(__('The job could not be deleted. Please, try again.'));
        }
    }
}
