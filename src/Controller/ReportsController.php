<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 *
 * @method \App\Model\Entity\Report[] paginate($object = null, array $settings = [])
 */
class ReportsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->deny([
            'add', 'delete', 'edit'
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate;
        $reports = $this->paginate($this->Reports);

        foreach ($reports as $report) {
            $student = $this->Reports->Users->find()
                ->where(['id' => $report->student_id])
                ->first();

            $report->student_id = $student->name;


            $supervisor = $this->Reports->Users->find()
                    ->where(['id' => $report->supervisor_id])
                    ->first();

            $report->supervisor_id = $supervisor->name;
        }

        $this->set(compact('reports'));
        $this->set('_serialize', ['reports']);
    }

    /**
     * View method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $report = $this->Reports->get($id);

        $student = $this->Reports->Users->find()
            ->where(['id' => $report->student_id])
            ->first();

        $report->student_id = $student->name;

        $supervisor = $this->Reports->Users->find()
                ->where(['id' => $report->supervisor_id])
                ->first();

        $report->supervisor_id = $supervisor->name;

        $this->set('report', $report);
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Report: $report->project_name"]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $projectNames = [];
        $locals = $this->Reports->Localprojects->find()
            ->select('name');
        foreach ($locals as $local) {
            $projectNames += [$local->name => $local->name];
        }
        $pubs = $this->Reports->Publications->find()
            ->select('title');
        foreach ($pubs as $pub) {
            $projectNames += [$pub->title => $pub->title];
        }
        $sites = $this->Reports->Sites->find()
            ->select('site_name');
        foreach ($sites as $site) {
            $projectNames += [$site->site_name => $site->site_name];
        }
        $supervisors = $this->Reports->Users->find('list');
        $projectTypes = [
            'Localprojects' => 'Community project',
            'Publications' => 'Publication',
            'Sites' => 'Website'
        ];

        $report = $this->Reports->newEntity();

        $this->set(compact('projectNames', 'projectTypes', 'report', 'routine', 'supervisors'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => 'Add a Report']);

        if ($this->request->is('post')) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $type = $report->project_type;
            if ($type == 'Localprojects') {
                $selector = 'name';
            }
            if ($type == 'Publications') {
                $selector = 'title';
            }
            if ($type == 'Sites') {
                $selector = 'site_name';
            }
            $project = $this->Reports->$type->find()
                ->select('id')
                ->where([$selector => $this->request->data['project_name']])
                ->first();
            if ($project == null) {
                $this->Flash->error(__('That project was not found. Please enter a new project to make a report about it.'));
                return $this->redirect(['action' => 'index']);
            }
            $report->project_name = $this->request->data['project_name'];
            $report->student_id = Router::getRequest()->session()->read('Auth.User.id');
            if ($this->Reports->save($report)) {
                return $this->Flash->success(__('The report has been saved.'));
            }
            return $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => []
        ]);

        $projectNames = [];
        $locals = $this->Reports->Localprojects->find()
            ->select('name');
        foreach ($locals as $local) {
            $projectNames += [$local->name => $local->name];
        }
        $pubs = $this->Reports->Publications->find()
            ->select('title');
        foreach ($pubs as $pub) {
            $projectNames += [$pub->title => $pub->title];
        }
        $sites = $this->Reports->Sites->find()
            ->select('site_name');
        foreach ($sites as $site) {
            $projectNames += [$site->site_name => $site->site_name];
        }
        $supervisors = $this->Reports->Users->find('list');
        $projectTypes = [
            'Localprojects' => 'Community project',
            'Publications' => 'Publication',
            'Sites' => 'Website'
        ];
        $this->set(compact('projectNames', 'projectTypes', 'report', 'routine', 'supervisors'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Edit Report: $report->project_name"]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $type = $report->project_type;
            if ($type == 'Localprojects') {
                $selector = 'name';
            }
            if ($type == 'Publications') {
                $selector = 'title';
            }
            if ($type == 'Sites') {
                $selector = 'site_name';
            }
            $project = $this->Reports->$type->find()
                ->select('id')
                ->where([$selector => $this->request->data['project_name']])
                ->first();
            if ($project == null) {
                $this->Flash->error(__('That project was not found. Please enter a new project to make a report about it.'));
                return $this->redirect(['action' => 'index']);
            }
            $report->project_name = $this->request->data['project_name'];
            $report->student_id = Router::getRequest()->session()->read('Auth.User.id');
            if ($this->Reports->save($report)) {
                return $this->Flash->success(__('The report has been saved.'));
            }
            return $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $report = $this->Reports->get($id);
        if ($this->Reports->delete($report)) {
            $this->Flash->success(__('The report has been deleted.'));
        } else {
            $this->Flash->error(__('The report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
