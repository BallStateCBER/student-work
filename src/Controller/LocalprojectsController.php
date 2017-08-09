<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Localprojects Controller
 *
 * @property \App\Model\Table\LocalprojectsTable $Localprojects
 *
 * @method \App\Model\Entity\Localproject[] paginate($object = null, array $settings = [])
 */
class LocalprojectsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('UsersLocalprojects');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny([
            'add', 'delete', 'edit'
        ]);
    }

    private function uponFormSubmissionPr($localproject)
    {
        if ($this->request->getParam('action') == 'edit') {
            if (!empty($this->request->data['users'])) {
                foreach ($this->request->data['users'] as $user) {
                    // skip over the placeholders
                    if ($user['_joinData']['user_id'] != null) {
                        // get form data & user
                        $userId = $user['_joinData']['user_id'];
                        $employeeRole = $user['_joinData']['role'];
                        $delete = isset($user['delete']) ? $user['delete'] : 0;
                        $user = $this->Localprojects->Users->get($userId);

                        // are we deleting this field?
                        if ($delete == 1) {
                            $this->Localprojects->Users->unlink($localproject, [$user]);
                        }

                        // nope? good to go
                        if ($delete == 0) {
                            // get the user and set joinData
                            $user->_joinData = $this->UsersLocalprojects->newEntity();
                            $user->_joinData->user_id = $userId;
                            $user->_joinData->localproject_id = $localproject->id;
                            $user->_joinData->role = $employeeRole;

                            // does this exact field already exist?
                            $prevField = $this->UsersLocalprojects->find();
                            $prevField
                                ->where(['user_id' => $userId])
                                ->andWhere(['localproject_id' => $localproject->id])
                                ->andWhere(['role' => $employeeRole]);
                            $prevCount = $prevField->count();

                            // if not, link the two
                            if ($prevCount == 0) {
                                $this->Localprojects->Users->link($localproject, [$user]);
                            }
                        }
                    }
                }
            }
        }

        $localproject = $this->Localprojects->patchEntity($localproject, $this->request->getData(), [
            'fieldList' => ['name', 'description', 'grant_id', 'organization']
        ]);

        if ($this->Localprojects->save($localproject)) {
            $this->Flash->success(__('The project has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The project could not be saved. Please, try again.'));
    }

    public function index()
    {
        $localprojects = $this->Localprojects->find('all', [
            'contain' => ['Users'],
            'order' => ['name' => 'ASC']
        ]);

        $this->set(compact('localprojects'));
        $this->set('_serialize', ['localprojects']);
    }

    public function view($id = null)
    {
        $localproject = $this->Localprojects->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('localproject', $localproject);
        $this->set('_serialize', ['localproject']);
    }

    public function add()
    {
        $localproject = $this->Localprojects->newEntity();

        $users = $this->Localprojects->Users->find('list');
        $grants = $this->Localprojects->Grants->find('list');
        $this->set(compact('grants', 'localproject', 'users'));
        $this->set('_serialize', ['localproject']);
        $this->set(['titleForLayout' => 'Add a Project']);

        if ($this->request->is('post')) {
            $this->uponFormSubmissionPr($localproject);
        }
    }

    public function edit($id = null)
    {
        $localproject = $this->Localprojects->get($id, [
            'contain' => ['Users']
        ]);

        $users = $this->Localprojects->Users->find('list');
        $this->set(compact('localproject', 'users'));
        $this->set('_serialize', ['localproject']);
        $this->set(['titleForLayout' => 'Edit Project: '.$localproject->title]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->uponFormSubmissionPr($localproject);
        }
    }

    public function delete($id = null)
    {
        $localproject = $this->Localprojects->get($id);
        if ($this->Localprojects->delete($localproject)) {
            $this->Flash->success(__('The project has been deleted.'));
        } else {
            $this->Flash->error(__('The project could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
