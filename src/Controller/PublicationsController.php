<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Publications Controller
 *
 * @property \App\Model\Table\PublicationsTable $Publications
 *
 * @method \App\Model\Entity\Publication[] paginate($object = null, array $settings = [])
 */
class PublicationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('UsersPublications');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny([
            'add', 'delete', 'edit'
        ]);
    }

    private function uponFormSubmissionPr($publication)
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
                        $user = $this->Publications->Users->get($userId);

                        // are we deleting this field?
                        if ($delete == 1) {
                            $this->Publications->Users->unlink($publication, [$user]);
                        }

                        // nope? good to go
                        if ($delete == 0) {
                            // get the user and set joinData
                            $user->_joinData = $this->UsersPublications->newEntity();
                            $user->_joinData->user_id = $userId;
                            $user->_joinData->publication_id = $publication->id;
                            $user->_joinData->role = $employeeRole;

                            // does this exact field already exist?
                            $prevField = $this->UsersPublications->find();
                            $prevField
                                ->where(['user_id' => $userId])
                                ->andWhere(['publication_id' => $publication->id])
                                ->andWhere(['role' => $employeeRole]);
                            $prevCount = $prevField->count();

                            // if not, link the two
                            if ($prevCount == 0) {
                                $this->Publications->Users->link($publication, [$user]);
                            }
                        }
                    }
                }
            }
        }

        $publication = $this->Publications->patchEntity($publication, $this->request->getData(), [
            'fieldList' => ['title', 'url', 'sponsor', 'date_published', 'cover', 'abstract', 'grant_id']
        ]);

        if ($this->Publications->save($publication)) {
            $this->Flash->success(__('The publication has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The publication could not be saved. Please, try again.'));
    }

    public function index()
    {
        $publications = $this->Publications->find('all', [
            'order' => ['date_published' => 'ASC']
        ]);

        $this->set(compact('publications'));
        $this->set('_serialize', ['publications']);
    }

    public function view($id = null)
    {
        $publication = $this->Publications->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('publication', $publication);
        $this->set('_serialize', ['publication']);
    }

    public function add()
    {
        $publication = $this->Publications->newEntity();

        if ($this->request->is('post')) {
            $this->uponFormSubmissionPr($publication);
        }
        $users = $this->Publications->Users->find('list');
        $grants = $this->Publications->Grants->find('list');
        $this->set(compact('grants', 'publication', 'users'));
        $this->set('_serialize', ['publication']);
        $this->set(['titleForLayout' => 'Add a Publication']);
    }

    public function edit($id = null)
    {
        $publication = $this->Publications->get($id, [
            'contain' => ['Users']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->uponFormSubmissionPr($publication);
        }
        $users = $this->Publications->Users->find('list');
        $this->set(compact('publication', 'users'));
        $this->set('_serialize', ['publication']);
        $this->set(['titleForLayout' => 'Edit Publication: '.$publication->title]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['get']);
        $publication = $this->Publications->get($id);
        if ($this->Publications->delete($publication)) {
            $this->Flash->success(__('The publication has been deleted.'));
        } else {
            $this->Flash->error(__('The publication could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
