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
     * indexing reports
     *
     * @param ResultSet $reports This is a set of Report entities
     * @return array $allReports
     */
    private function reportIndexing($reports)
    {
        foreach ($reports as $report) {
            $report->student_id = $this->Users->getUserNameFromId($report->student_id);
            $report->supervisor_id = $this->Users->getUserNameFromId($report->supervisor_id);
        }

        $allReports = $this->Reports->find()->contain(['Projects'])->toArray();

        return $allReports;
    }

    /**
     * setting ids & names of students
     *
     * @param ResultSet $allReports This is a set of Report entities
     * @return array $students
     */
    private function students($allReports)
    {
        $ids = [];
        $names = [];
        foreach ($allReports as $report) {
            $student = $this->Users->get($report->student_id);

            $ids[] = $report->student_id;
            $names[] = $student->name;
        }
        $students = array_combine($ids, $names);
        $students = array_unique($students);

        return $students;
    }

    /**
     * setting ids & names of supervisors
     *
     * @param ResultSet $allReports This is a set of Report entities
     * @return array $supervisors
     */
    private function supervisors($allReports)
    {
        $ids = [];
        $names = [];
        foreach ($allReports as $report) {
            $supervisor = $this->Users->get($report->supervisor_id);

            $ids[] = $report->supervisor_id;
            $names[] = $supervisor->name;
        }
        $supervisors = array_combine($ids, $names);
        $supervisors = array_unique($supervisors);

        return $supervisors;
    }

    /**
     * setting the vars for the index
     *
     * @param ResultSet $reports This is a set of Report entities
     * @return void
     */
    private function setIndexVars($reports)
    {
        $allReports = $this->reportIndexing($reports);

        $ids = [];
        $projects = [];
        foreach ($allReports as $report) {
            $ids[] = $report->project['id'];
            $projects[] = $report->project['name'];
        }
        $projects = array_combine($ids, $projects);
        $projects = array_unique($projects);

        $students = $this->students($allReports);
        $supervisors = $this->supervisors($allReports);

        $this->set(compact('allReports', 'projects', 'reports', 'students', 'supervisors'));
    }

    /**
     * Current method
     *
     * @return void
     */
    public function current()
    {
        $reports = $this->Reports->find()
            ->where(['end_date >=' => date('Y-m-d')])
            ->orWhere(['end_date IS' => null])
            ->contain(['Projects']);

        $reports = $this->paginate($reports);
        $this->setIndexVars($reports);

        $this->set('_serialize', ['reports']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $reports = $this->paginate($this->Reports->find()->contain(['Projects']));

        $this->setIndexVars($reports);

        $this->set('_serialize', ['reports']);
    }

    /**
     * Past method
     *
     * @return void
     */
    public function past()
    {
        $reports = $this->Reports->find()
            ->where(['end_date <' => date('Y-m-d')])
            ->andWhere(['end_date IS NOT' => null])
            ->andWhere(['end_date !=' => '0000-00-00 00:00:00'])
            ->contain(['Projects']);

        $reports = $this->paginate($reports);
        $this->setIndexVars($reports);
        $this->set('_serialize', ['reports']);
    }

    /**
     * Project method
     *
     * @param string|null $id Project id.
     * @return void
     */
    public function project($id = null)
    {
        $project = $this->Reports->Projects->get($id);

        $reports = $this->Reports->find()
            ->where(['project_id' => $id])
            ->contain(['Projects']);

        $reports = $this->paginate($reports);
        $this->setIndexVars($reports);

        $this->set('_serialize', ['reports']);
    }

    /**
     * Student method
     *
     * @param string|null $id Student id.
     * @return void
     */
    public function student($id = null)
    {
        $reports = $this->Reports->find()
            ->where(['student_id' => $id])
            ->contain(['Projects']);

        $reports = $this->paginate($reports);
        $this->setIndexVars($reports);

        $this->set('_serialize', ['reports']);
    }

    /**
     * Supervisor method
     *
     * @param string|null $id Supervisor id.
     * @return void
     */
    public function supervisor($id = null)
    {
        $reports = $this->Reports->find()
            ->where(['supervisor_id' => $id])
            ->contain(['Projects']);

        $reports = $this->paginate($reports);
        $this->setIndexVars($reports);

        $this->set('_serialize', ['reports']);
    }

    /**
     * View method
     *
     * @param string|null $id Report id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @return void
     */
    public function view($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => 'Projects'
        ]);

        $report->student_id = $this->Users->getUserNameFromId($report->student_id);
        $report->supervisor_id = $this->Users->getUserNameFromId($report->supervisor_id);

        $title = $report->project['name'];
        $this->set('report', $report);
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Report: $title"]);
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
        $supervisors = $this->Users->find('list');

        $report = $this->Reports->newEntity();

        $this->set(compact('projectNames', 'report', 'routine', 'supervisors'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => 'Add a Report']);

        if ($this->request->session()->read('Auth.User.admin') == 0) {
            $id = $this->Auth->user('id');
            $reports = $this->Reports->getStudentCurrentReports($id);

            foreach ($reports as $report) {
                if (!isset($report->end_date)) {
                    $this->Flash->duplicates(
                        "Hey! Before you continue, make sure you're not duplicating work reports.
                        Your report for $report->project_name has no end date."
                    );
                }
                if (isset($report->end_date)) {
                    $this->Flash->duplicates(
                        "Hey! Before you continue, make sure you're not duplicating work reports.
                        Your report for $report->project_name ends on $report->end_date."
                    );
                }
            }
        }

        if ($this->request->is('post')) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $project = $this->Reports->Projects->getProjectByName($this->request->data['project_name']);

            if ($project == null) {
                $this->Flash->error(__(
                    'That project was not found.
                    Please enter a new project to make a report about it.'
                ));

                return $this->redirect(['action' => 'index']);
            }

            $report->project_id = $this->request->data['project_name'];
            $student = $this->Users->findByName($this->request->data['student_id'])->first();
            $report->student_id = $student->id;

            if ($this->request->session()->read('Auth.User.admin') != 1) {
                $id = $this->Auth->user('id');
                $project = $this->Projects->get($this->request->data['project_name']);
                $reports = $this->Reports->getStudentCurrentReportsByProject($id, $project->id);
                if (!empty($reports)) {
                    foreach ($reports as $report) {
                        $this->Flash->duplicates(
                            "Sorry, you cannot create this report.
                            You've already got a current report for the project $project->name."
                        );
                    }

                    return $this->redirect(['action' => 'index']);
                }
            }
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
            ->select();
        foreach ($projects as $project) {
            $projectNames += [$project->id => $project->name];
        }
        $supervisors = $this->Users->find('list');

        $this->set(compact('projectNames', 'report', 'routine', 'supervisors'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Edit Report: $report->project_name"]);

        if ($report->student_id != $this->Auth->user('id')) {
            if ($report->supervisor_id != $this->Auth->user('id')) {
                if ($this->request->session()->read('Auth.User.admin') != 1) {
                    return $this->Flash->error(__('You are not authorized to edit this.'));
                }
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $project = $this->Reports->Projects->getProjectByName($this->request->data['project_name']);
            if ($project == null) {
                $this->Flash->error(__(
                    'That project was not found.
                    Please enter a new project to make a report about it.'
                ));

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
        $activeUser = $this->Auth->user('id');
        $admin = $this->request->session()->read('Auth.User.admin');
        if ($admin != 1 || ($report->student_id != $activeUser || $report->supervisor_id != $activeUser)) {
            $this->Flash->error('You are not authorized to delete this.');

            return $this->redirect(['action' => 'index']);
        }
        if ($this->Reports->delete($report)) {
            $this->Flash->success(__('The report has been deleted.'));
        } else {
            $this->Flash->error(__('The report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
