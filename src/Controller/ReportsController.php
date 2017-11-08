<?php
namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\Event;

/**
 * Reports Controller
 *
 * @property \App\Model\Table\ReportsTable $Reports
 *
 * @method \App\Model\Entity\Report[]
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
    }

    /**
     * isAuthorized
     *
     * @param User|null $user User entity
     * @return \Cake\Http\Response|bool
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
     * populating the forms
     *
     * @return void
     */
    private function formPopulation()
    {
        $projectNames = [];
        $projects = $this->Reports->Projects->find()
            ->select(['id', 'name'])
            ->contain(['Users']);
        foreach ($projects as $project) {
            if ($this->Auth->user('admin') == 0) {
                foreach ($project->users as $user) {
                    if ($user->id == $this->Auth->user('id')) {
                        $projectNames += [$project->id => $project->name];
                    }
                }
                continue;
            }
            $projectNames += [$project->id => $project->name];
        }
        $supervisors = $this->Users->find('list')->toArray();
        foreach ($supervisors as $id => $name) {
            if (empty($name)) {
                $supervisors[$id] = "Employee #$id";
            }
        }

        $this->set(compact('projects', 'projectNames', 'supervisors'));
    }

    /**
     * indexing reports
     *
     * @param \Cake\ORM\ResultSet $reports This is a set of Report entities
     * @return array $allReports
     */
    private function reportIndexing($reports)
    {
        foreach ($reports as $report) {
            $studentId = $report->student_id;
            $report->student_id = $this->Users->getUserNameFromId($studentId);
            if ($report->student_id == null) {
                $report->student_id = "Employee #" . $studentId;
            }

            $supervisorId = $report->supervisor_id;
            $report->supervisor_id = $this->Users->getUserNameFromId($supervisorId);;
            if ($report->supervisor_id == null) {
                $report->supervisor_id = "Employee #" . $supervisorId;
            }
        }

        $allReports = $this->Reports->find()->contain(['Projects'])->toArray();

        return $allReports;
    }

    /**
     * setting ids & names of students
     *
     * @param array $allReports This is a set of Report entities
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
     * @param array $allReports This is a set of Report entities
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
     * @param \Cake\ORM\ResultSet $reports This is a set of Report entities
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

        $studentId = $report->student_id;
        $report->student_id = $this->Users->getUserNameFromId($studentId);
        if ($report->student_id == null) {
            $report->student_id = "Employee #" . $studentId;
        }

        $supervisorId = $report->supervisor_id;
        $report->supervisor_id = $this->Users->getUserNameFromId($supervisorId);;
        if ($report->supervisor_id == null) {
            $report->supervisor_id = "Employee #" . $supervisorId;
        }

        $title = $report->project['name'];
        $this->set('report', $report);
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Report: $title"]);
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $this->formPopulation();

        $report = $this->Reports->newEntity();

        $this->set(compact('report'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => 'Add a Report']);

        if ($this->request->session()->read('Auth.User.admin') == 0) {
            $id = $this->Auth->user('id');
            $reports = $this->Reports->getStudentCurrentReports($id);

            foreach ($reports as $report) {
                if (!isset($report->end_date)) {
                    $this->Flash->error(
                        "Hey! Before you continue, make sure you're not duplicating work reports.
                        Your report for $report->project_name has no end date."
                    );
                }
                if (isset($report->end_date)) {
                    $this->Flash->error(
                        "Hey! Before you continue, make sure you're not duplicating work reports.
                        Your report for $report->project_name ends on $report->end_date."
                    );
                }
            }
        }

        if ($this->request->is('post')) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $project = $this->Reports->Projects->get($this->request->getData('project_name'));

            if ($project == null) {
                $this->Flash->error(__(
                    'That project was not found.
                    Please enter a new project to make a report about it.'
                ));

                return;
            }

            $report->project_id = $this->request->getData('project_name');
            $student = $this->Users->findByName($this->request->getData('student_id'))->first();
            $report->student_id = $student->id;

            if ($this->request->session()->read('Auth.User.admin') != 1) {
                $id = $this->Auth->user('id');
                $project = $this->Projects->get($this->request->getData('project_name'));
                $reports = $this->Reports->getStudentCurrentReportsByProject($id, $project->id);
                if (!empty($reports)) {
                    foreach ($reports as $report) {
                        $this->Flash->error(
                            "Sorry, you cannot create this report.
                            Report #$report->id has been created for the project $project->name."
                        );
                    }

                    return;
                }
            }
            if ($this->Reports->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return;
            }

            $this->Flash->error(__('The report could not be saved. Please, try again.'));

            return;
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Report id.
     * @return void
     */
    public function edit($id = null)
    {
        $report = $this->Reports->get($id, [
            'contain' => []
        ]);

        $this->formPopulation();

        $this->set(compact('report'));
        $this->set('_serialize', ['report']);
        $this->set(['titleForLayout' => "Edit Report: " . $report['project_name']]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->getData());
            $project = $this->Reports->Projects->get($this->request->getData('project_name'));
            if ($project == null) {
                $this->Flash->error(__(
                    'That project was not found.
                    Please enter a new project to make a report about it.'
                ));
                $this->redirect(['action' => 'index']);

                return;
            }
            $report['project_name'] = $this->request->getData('project_name');
            if ($this->Reports->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return;
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));

            return;
        };
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
