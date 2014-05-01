<?php
namespace Project\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Project\Model\Project;          
use Project\Form\ProjectForm;       

class ProjectController extends AbstractActionController
{

    protected $projectTable;

    public function indexAction()
    {
        $userID = $this->zfcUserAuthentication()->getIdentity()->getid();
        //change to permission check later
        if($this->zfcUserAuthentication()->getIdentity()->getRoleID() == 1){
            return new ViewModel(array(
                'projects' => $this->getProjectTable()->fetchAll(),
            ));  
        } else {
            return new ViewModel(array(
                'projects' => $this->getProjectTable()->fetchByUser($userID),
            ));  
        }
        
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $project = $this->getProjectTable()->getProject($id);
        return array('project' => $project);
    }

    public function addAction()
    {
        $form = new ProjectForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $project = new Project();
            $form->setInputFilter($project->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $data['user_id'] = $this->zfcUserAuthentication()->getIdentity()->getid();
                
                $project->exchangeArray($data);


                $this->getProjectTable()->saveProject($project);

                // Redirect to list of projects
                return $this->redirect()->toRoute('project');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('project', array(
                'action' => 'add'
            ));
        }
        $project = $this->getProjectTable()->getProject($id);

        $form  = new ProjectForm();
        $form->bind($project);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($project->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getProjectTable()->saveProject($form->getData());

                // Redirect to list of projects
                return $this->redirect()->toRoute('project');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('project');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProjectTable()->deleteProject($id);
            }

            // Redirect to list of projects
            return $this->redirect()->toRoute('project');
        }

        return array(
            'id'    => $id,
            'project' => $this->getProjectTable()->getProject($id)
        );
    }

    public function getProjectTable()
    {
        if (!$this->projectTable) {
            $sm = $this->getServiceLocator();
            $this->projectTable = $sm->get('Project\Model\ProjectTable');
        }
        return $this->projectTable;
    }
}