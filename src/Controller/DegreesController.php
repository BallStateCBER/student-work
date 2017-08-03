<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Degrees Controller
 *
 * @property \App\Model\Table\DegreesTable $Degrees
 *
 * @method \App\Model\Entity\Degree[] paginate($object = null, array $settings = [])
 */
class DegreesController extends AppController
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
        $degreeTypes = $this->getDegreeTypesPr();
        $degree = $this->Degrees->newEntity();
        if ($this->request->is('post')) {
            $degree = $this->Degrees->patchEntity($degree, $this->request->getData());
            $degree->user_id = $this->request->session()->read('Auth.User.id');
            if ($this->Degrees->save($degree)) {
                $this->Flash->success(__('The degree has been saved.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'account']);
            }
            $this->Flash->error(__('The degree could not be saved. Please, try again.'));
        }
        $this->set(['titleForLayout' => 'Add Educational Experience']);
        $this->set(compact('degree'));
        $this->set('_serialize', ['degree']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Degree id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $degreeTypes = $this->getDegreeTypesPr();
        $degree = $this->Degrees->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $degree = $this->Degrees->patchEntity($degree, $this->request->getData());
            if ($this->Degrees->save($degree)) {
                $this->Flash->success(__('The degree has been saved.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'account']);
            }
            $this->Flash->error(__('The degree could not be saved. Please, try again.'));
        }
        $this->set(['titleForLayout' => 'Edit this Degree']);
        $this->set(compact('degree'));
        $this->set('_serialize', ['degree']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Degree id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $degree = $this->Degrees->get($id);
        if ($this->Degrees->delete($degree)) {
            $this->Flash->success(__('The degree has been deleted.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'account']);
        } else {
            $this->Flash->error(__('The degree could not be deleted. Please, try again.'));
        }
    }

    private function getDegreeTypesPr()
    {
        $this->set(['degreeTypes' => [
            'Associate of Applied Arts' => 'Associate of Applied Arts',
            'Associate of Applied Science' => 'Associate of Applied Science',
            'Associate of Arts' => 'Associate of Arts',
            'Associate of Engineering' => 'Associate of Engineering',
            'Associate of General Studies' => 'Associate of General Studies',
            'Associate of Political Science' => 'Associate of Political Science',
            'Associate of Science' => 'Associate of Science',
            'Bachelor of Applied Science' => 'Bachelor of Applied Science',
            'Bachelor of Architecture' => 'Bachelor of Architecture',
            'Bachelor of Arts' => 'Bachelor of Arts',
            'Bachelor of Business Administration' => 'Bachelor of Business Administration',
            'Bachelor of Engineering' => 'Bachelor of Engineering',
            'Bachelor of Fine Arts' => 'Bachelor of Fine Arts',
            'Bachelor of General Studies' => 'Bachelor of General Studies',
            'Bachelor of Science' => 'Bachelor of Science',
            'Doctor of Dental Surgery' => 'Doctor of Dental Surgery',
            'Doctor of Education' => 'Doctor of Education',
            'Doctor of Medicine' => 'Doctor of Medicine',
            'Doctor of Pharmacy' => 'Doctor of Pharmacy',
            'Doctor of Philosophy' => 'Doctor of Philosophy',
            'General Education Development' => 'General Education Development',
            'High School Diploma' => 'High School Diploma',
            'High School Equivalency Diploma' => 'High School Equivalency Diploma',
            'Juris Doctor' => 'Juris Doctor',
            'Master of Arts' => 'Master of Arts',
            'Master of Business Administration' => 'Master of Business Administration',
            'Master of Education' => 'Master of Education',
            'Master of Fine Arts' => 'Master of Fine Arts',
            'Master of Laws' => 'Master of Laws',
            'Master of Philosophy' => 'Master of Philosophy',
            'Master of Research' => 'Master of Research',
            'Master of Science' => 'Master of Science'
        ]]);
    }
}
