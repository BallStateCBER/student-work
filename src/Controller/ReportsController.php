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
    }

    private function __reportIndexing($reports)
    {
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

        $allReports = $this->Reports->find()->toArray();
        return $allReports;
    }

    private function __students($allReports)
    {
        $ids = [];
        $names = [];
        foreach ($allReports as $report) {
            $student = $this->Reports->Users->find()
                ->where(['id' => $report->student_id])
                ->first();

            $ids[] = $report->student_id;
            $names[] = $student->name;
        }
        $students = array_combine($ids, $names);
        $students = array_unique($students);

        return $students;
    }

    private function __supervisors($allReports)
    {
        $ids = [];
        $names = [];
        foreach ($allReports as $report) {
            $supervisor = $this->Reports->Users->find()
                ->where(['id' => $report->supervisor_id])
                ->first();

            $ids[] = $report->supervisor_id;
            $names[] = $supervisor->name;
        }
        $supervisors = array_combine($ids, $names);
        $supervisors = array_unique($supervisors);

        return $supervisors;
    }

    /**
     * Current method
     *
     * @return \Cake\Http\Response|null
     */
    public function current()
    {
        $this->paginate;
        $reports = $this->Reports->find()
            ->where(['end_date >=' => date('Y-m-d')])
            ->orWhere(['end_date IS' => null]);

        $reports = $this->paginate($reports);

        $allReports = $this->__reportIndexing($reports);
        $projects = $this->Reports->Projects->find('list');
        $students = $this->__students($allReports);
        $supervisors = $this->__supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
        $this->set('_serialize', ['reports']);
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

        $allReports = $this->__reportIndexing($reports);
        $projects = $this->Reports->Projects->find('list');
        $students = $this->__students($allReports);
        $supervisors = $this->__supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
        $this->set('_serialize', ['reports']);
    }

    /**
     * Past method
     *
     * @return \Cake\Http\Response|null
     */
    public function past()
    {
        $this->paginate;
        $reports = $this->Reports->find()
            ->where(['end_date <' => date('Y-m-d')])
            ->andWhere(['end_date IS NOT' => null])
            ->andWhere(['end_date !=' => '0000-00-00 00:00:00']);

        $reports = $this->paginate($reports);

        $allReports = $this->__reportIndexing($reports);
        $projects = $this->Reports->Projects->find('list');
        $students = $this->__students($allReports);
        $supervisors = $this->__supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
        $this->set('_serialize', ['reports']);
    }

    /**
     * Project method
     *
     * @return \Cake\Http\Response|null
     */
    public function project($id = null)
    {
        $project = $this->Reports->Projects->find()
            ->where(['id' => $id])
            ->first();

        $this->paginate;

        $reports = $this->Reports->find()
            ->where(['project_name' => $project->name]);

        $reports = $this->paginate($reports);

        $allReports = $this->__reportIndexing($reports);
        $projects = $this->Reports->Projects->find('list');
        $students = $this->__students($allReports);
        $supervisors = $this->__supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
        $this->set('_serialize', ['reports']);
    }

    /**
     * Student method
     *
     * @return \Cake\Http\Response|null
     */
    public function student($id = null)
    {
        $this->paginate;
        $reports = $this->Reports->find()
            ->where(['student_id' => $id]);

        $reports = $this->paginate($reports);

        $allReports = $this->__reportIndexing($reports);
        $projects = $this->Reports->Projects->find('list');
        $students = $this->__students($allReports);
        $supervisors = $this->__supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
        $this->set('_serialize', ['reports']);
    }

    /**
     * Supervisor method
     *
     * @return \Cake\Http\Response|null
     */
    public function supervisor($id = null)
    {
        $this->paginate;
        $reports = $this->Reports->find()
            ->where(['supervisor_id' => $id]);

        $reports = $this->paginate($reports);

        $allReports = $this->__reportIndexing($reports);
        $projects = $this->Reports->Projects->find('list');
        $students = $this->__students($allReports);
        $supervisors = $this->__supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
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

        $projects = $this->Reports->Projects->find()
            ->select('name');
        foreach ($projects as $project) {
            $projectNames += [$project->name => $project->name];
        }

        $supervisors = $this->Reports->Users->find('list');

        $report = $this->Reports->newEntity();

        $this->set(compact('projectNames', 'report', 'routine', 'supervisors'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => 'Add a Report']);

        if ($this->request->is('post')) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $project = $this->Reports->Projects->find()
                ->select('id')
                ->where(['name' => $this->request->data['project_name']])
                ->first();
            if ($project == null) {
                $this->Flash->error(__('That project was not found. Please enter a new project to make a report about it.'));
                return $this->redirect(['action' => 'index']);
            }
            $report->project_name = $this->request->data['project_name'];
            $studentId = $this->Reports->Users->find()
                ->where(['name' => $this->request->data['student_id']])
                ->first();
            $report->student_id = $studentId->id;
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
        $projects = $this->Reports->Projects->find()
            ->select('name');
        foreach ($projects as $project) {
            $projectNames += [$project->name => $project->name];
        }
        $supervisors = $this->Reports->Users->find('list');

        $this->set(compact('projectNames', 'report', 'routine', 'supervisors'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Edit Report: $report->project_name"]);

        if ($report->student_id != $this->request->session()->read('Auth.User.id')) {
            if ($report->supervisor_id != $this->request->session()->read('Auth.User.id')) {
                if ($this->request->session()->read('Auth.User.role') != 'Site Admin') {
                    return $this->Flash->error(__('You are not authorized to edit this.'));
                }
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $project = $this->Reports->Projects->find()
                ->select('id')
                ->where(['name' => $this->request->data['project_name']])
                ->first();
            if ($project == null) {
                $this->Flash->error(__('That project was not found. Please enter a new project to make a report about it.'));
                return $this->redirect(['action' => 'index']);
            }
            $report->project_name = $this->request->data['project_name'];
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
