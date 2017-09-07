<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 *
 * @method \App\Model\Entity\Project[] paginate($object = null, array $settings = [])
 */
class ProjectsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('UsersProjects');
        if ($this->request->getParam('action') != 'index' and $this->request->getParam('action') != 'view') {
            if ($this->request->session()->read('Auth.User.role') != 'Site Admin') {
                $this->Flash->error('Only admins can access project details.');
                return $this->redirect(['controller' => 'Projects', 'action' => 'index']);
            }
        }
    }

    private function uponFormSubmissionPr($project)
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
                        $user = $this->Projects->Users->get($userId);

                        // are we deleting this field?
                        if ($delete == 1) {
                            $this->Projects->Users->unlink($project, [$user]);
                        }

                        // nope? good to go
                        if ($delete == 0) {
                            // get the user and set joinData
                            $user->_joinData = $this->UsersProjects->newEntity();
                            $user->_joinData->user_id = $userId;
                            $user->_joinData->project_id = $project->id;
                            $user->_joinData->role = $employeeRole;

                            // does this exact field already exist?
                            $prevField = $this->UsersProjects->find();
                            $prevField
                                ->where(['user_id' => $userId])
                                ->andWhere(['project_id' => $project->id])
                                ->andWhere(['role' => $employeeRole]);
                            $prevCount = $prevField->count();

                            // if not, link the two
                            if ($prevCount == 0) {
                                $this->Projects->Users->link($project, [$user]);
                            }
                        }
                    }
                }
            }
        }

        $project = $this->Projects->patchEntity($project, $this->request->getData(), [
            'fieldList' => ['name', 'description', 'fund_id', 'organization', 'image', 'funding_details']
        ]);

        if ($this->Projects->save($project)) {
            $this->Flash->success(__('The project has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The project could not be saved. Please, try again.'));
    }

    public function index()
    {
        $projects = $this->Projects->find('all', [
            'contain' => ['Users'],
            'order' => ['name' => 'ASC']
        ]);

        $this->set(compact('projects'));
        $this->set('_serialize', ['projects']);
    }

    public function view($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Users']
        ]);

        $fund = $this->Projects->Funds->get($project->fund_id);
        $this->set(compact('project'));
        $this->set('fundNumber', $fund->name);
        $this->set('_serialize', ['project']);
    }

    public function add()
    {
        $project = $this->Projects->newEntity();

        $users = $this->Projects->Users->find('list');
        $funds = $this->Projects->Funds->find('list');
        $this->set(compact('funds', 'project', 'users'));
        $this->set('_serialize', ['project']);
        $this->set(['titleForLayout' => 'Add a Project']);

        if ($this->request->is('post')) {
            $this->uponFormSubmissionPr($project);
        }
    }

    public function edit($id = null)
    {
        $project = $this->Projects->get($id, [
            'contain' => ['Users']
        ]);

        $users = $this->Projects->Users->find('list');
        $funds = $this->Projects->Funds->find('list');
        $this->set(compact('funds', 'project', 'users'));
        $this->set('_serialize', ['project']);
        $this->set(['titleForLayout' => 'Edit Project: '.$project->title]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->uponFormSubmissionPr($project);
        }
    }

    public function delete($id = null)
    {
        $project = $this->Projects->find()
            ->where(['id' => $id])
            ->contain(['Users'])
            ->first();
        $role = $this->request->session()->read('Auth.User.role');
        $activeUser = $this->request->session()->read('Auth.User.id');
        if ($role != 'Site Admin') {
            $ok = 0;
            foreach ($project->users as $user) {
                if ($user->id == $activeUser) {
                    $ok = 1;
                }
            }
            if (!$ok) {
                $this->Flash->error('You are not authorized to delete this.');
                return $this->redirect(['action' => 'index']);
            }
        }
        if ($this->Projects->delete($project)) {
            $this->Flash->success(__('The project has been deleted.'));
        } else {
            $this->Flash->error(__('The project could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
