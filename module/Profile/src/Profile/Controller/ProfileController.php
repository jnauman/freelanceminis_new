<?php
namespace Profile\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Profile\Model\Profile;          
use Profile\Form\ProfileForm;       

class ProfileController extends AbstractActionController
{

    protected $profileTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'profiles' => $this->getProfileTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new ProfileForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $profile = new Profile();
            $form->setInputFilter($profile->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $profile->exchangeArray($form->getData());
                $this->getProfileTable()->saveProfile($profile);

                // Redirect to list of profiles
                return $this->redirect()->toRoute('profile');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('profile', array(
                'action' => 'add'
            ));
        }
        $profile = $this->getProfileTable()->getProfile($id);

        $form  = new ProfileForm();
        $form->bind($profile);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($profile->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getProfileTable()->saveProfile($form->getData());

                // Redirect to list of profiles
                return $this->redirect()->toRoute('profile');
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
            return $this->redirect()->toRoute('profile');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProfileTable()->deleteProfile($id);
            }

            // Redirect to list of profiles
            return $this->redirect()->toRoute('profile');
        }

        return array(
            'id'    => $id,
            'profile' => $this->getProfileTable()->getProfile($id)
        );
    }

    public function getProfileTable()
    {
        if (!$this->profileTable) {
            $sm = $this->getServiceLocator();
            $this->profileTable = $sm->get('Profile\Model\ProfileTable');
        }
        return $this->profileTable;
    }
}