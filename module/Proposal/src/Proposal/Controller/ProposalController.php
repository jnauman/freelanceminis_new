<?php
namespace Proposal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Proposal\Model\Proposal;          
use Proposal\Form\ProposalForm;       

class ProposalController extends AbstractActionController
{

    protected $proposalTable;

    public function indexAction()
    {
        $userID = $this->zfcUserAuthentication()->getIdentity()->getid();
        //change to permission check later
        if($this->zfcUserAuthentication()->getIdentity()->getRoleID() == 1){
            return new ViewModel(array(
                'proposals' => $this->getProposalTable()->fetchAll(),
            ));
        }else if($this->zfcUserAuthentication()->getIdentity()->getRoleID() == 3){
            return new ViewModel(array(
                'proposals' => $this->getProposalTable()->fetchByUserID($userID),
            ));
        } else {
             return $this->redirect()->toRoute('project');
        }
    }

    public function listAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('proposal', array(
                'action' => 'index'
            ));
        }
        return new ViewModel(array(
                'proposals' => $this->getProposalTable()->fetchByProjectID($projectID),
            ));
    }

    public function addAction()
    {   
        $project_id = (int) $this->params()->fromRoute('id', 0);
        $form = new ProposalForm();
        $form->get('project_id')->setValue($project_id);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $proposal = new Proposal();
            $form->setInputFilter($proposal->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $data['user_id'] = $this->zfcUserAuthentication()->getIdentity()->getid();
                $proposal->exchangeArray($data);
                $this->getProposalTable()->saveProposal($proposal);

                // Redirect to list of proposals
                return $this->redirect()->toRoute('proposal');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('proposal', array(
                'action' => 'add'
            ));
        }
        $proposal = $this->getProposalTable()->getProposal($id);

        $form  = new ProposalForm();
        $form->bind($proposal);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($proposal->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getProposalTable()->saveProposal($form->getData());

                // Redirect to list of proposals
                return $this->redirect()->toRoute('proposal');
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
            return $this->redirect()->toRoute('proposal');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProposalTable()->deleteProposal($id);
            }

            // Redirect to list of proposals
            return $this->redirect()->toRoute('proposal');
        }

        return array(
            'id'    => $id,
            'proposal' => $this->getProposalTable()->getProposal($id)
        );
    }

    public function getProposalTable()
    {
        if (!$this->proposalTable) {
            $sm = $this->getServiceLocator();
            $this->proposalTable = $sm->get('Proposal\Model\ProposalTable');
        }
        return $this->proposalTable;
    }
}