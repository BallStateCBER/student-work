<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\Entity;

/**
 * Sites Controller
 *
 * @property \App\Model\Table\SitesTable $Sites
 *
 * @method \App\Model\Entity\Site[] paginate($object = null, array $settings = [])
 */
class SitesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('UsersSites');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny([
            'add', 'delete', 'edit'
        ]);
    }

    private function uponFormSubmissionPr($site)
    {
        if ($this->request->getParam('action') == 'edit') {
            foreach ($this->request->data['users'] as $user) {
                // skip over the placeholders
                if ($user['_joinData']['user_id'] != null) {
                    // get form data & user
                    $userId = $user['_joinData']['user_id'];
                    $employeeRole = $user['_joinData']['employee_role'];
                    $delete = isset($user['delete']) ? $user['delete'] : 0;
                    $user = $this->Sites->Users->get($userId);

                    // are we deleting this field?
                    if ($delete == 1) {
                        $this->Sites->Users->unlink($site, [$user]);
                    }

                    // nope? good to go
                    if ($delete == 0) {
                        // get the user and set joinData
                        $user->_joinData = $this->UsersSites->newEntity();
                        $user->_joinData->user_id = $userId;
                        $user->_joinData->site_id = $site->id;
                        $user->_joinData->employee_role = $employeeRole;

                        // does this exact field already exist?
                        $prevField = $this->UsersSites->find();
                        $prevField
                            ->where(['user_id' => $userId])
                            ->andWhere(['site_id' => $site->id])
                            ->andWhere(['employee_role' => $employeeRole]);
                        $prevCount = $prevField->count();

                        // if not, link the two
                        if ($prevCount == 0) {
                            $this->Sites->Users->link($site, [$user]);
                        }
                    }
                }
            }
        }

        $site = $this->Sites->patchEntity($site, $this->request->getData(), [
            'fieldList' => ['site_name', 'url', 'sponsor', 'date_live', 'image', 'description', 'in_progress']
        ]);

        if ($this->Sites->save($site)) {
            $this->Flash->success(__('The site has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The site could not be saved. Please, try again.'));
    }

    public function index()
    {
        $sites = $this->Sites->find('all', [
            'order' => ['date_live' => 'ASC']
        ]);

        $this->set(compact('sites'));
        $this->set('_serialize', ['sites']);
    }

    public function view($id = null)
    {
        $site = $this->Sites->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('site', $site);
        $this->set('_serialize', ['site']);
    }

    public function add()
    {
        $site = $this->Sites->newEntity();

        if ($this->request->is('post')) {
            $this->uponFormSubmissionPr($site);
        }
        $users = $this->Sites->Users->find('list');
        $grants = $this->Sites->Grants->find('list');
        $this->set(compact('grants', 'site', 'users'));
        $this->set('_serialize', ['site']);
        $this->set(['titleForLayout' => 'Add Site']);
    }

    public function edit($id = null)
    {
        $site = $this->Sites->get($id, [
            'contain' => ['Users']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->uponFormSubmissionPr($site);
        }
        $users = $this->Sites->Users->find('list');
        $grants = $this->Sites->Grants->find('list');
        $this->set(compact('grants', 'site', 'users'));
        $this->set('_serialize', ['site']);
        $this->set(['titleForLayout' => 'Edit Site: '.$site->site_name]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['get']);
        $site = $this->Sites->get($id);
        if ($this->Sites->delete($site)) {
            $this->Flash->success(__('The site has been deleted.'));
        } else {
            $this->Flash->error(__('The site could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
