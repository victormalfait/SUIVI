<?php
class AdminController extends Zend_Controller_Action
{
	public function indexAction(){
		$tableUser = new TUser;
		$this->view->tableUser = $tableUser;

		$user = $tableUser->fetchAll();

		$pageUser = Zend_Paginator::factory($user);

		$pageUser->setCurrentPageNumber($this->_getParam('page'));
		$pageUser->setItemCountPerPage(2);

		
		$this->view->paginator = $pageUser;
	}

	public function ajouterAction(){
		$form = new formUser;
		$this->view->form = $form;

		if ($this->_request->isPost()){
            $formData = $this->_request->getPost();

            if($form->isValid($formData)){
                
                $tableUser = new TUser;

                $row = $tableUser->createRow();
                 
                $row->login_user     	= $form->getValue('login');
                $row->nom_user       	= $form->getValue('nom');
                $row->prenom_user       = $form->getValue('prenom');
                $row->email_user    	= $form->getValue('email');
                $row->motdepasse       	= md5($form->getValue('motdepasse'));
                $row->role 				= "Chef de projet";

                $login 					= $form->getValue('login');
                $nom 					= $form->getValue('nom').' '.$form->getValue('prenom');
                $email 					= $form->getValue('email');
                $mdp 					= $form->getValue('motdepasse');
                $text 					= "Bonjour ".$nom.",/r
                							Votre login est : ".$login."/r
                							Votre mot de passe : ".$mdp.".";

                $row->save();
                $form->reset();

                $mail = new Zend_Mail();
                $mail->setFrom('kainve@yahoo.fr','Administrateur Suivie de projet');
                $mail->addTo($email,'Cher '.$nom);
                $mail->setSubject('Validation d incription');
                $mail->setBodyText($text);
                $mail->send();
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('admin/index');
            } 
        }
	}

	public function supprimerAction(){
		$id = $this->_getParam('id');
        $tableUser = new TUser;
        $user = $tableUser->find($id)->current();
        
        $tableProjet = new TProjet;
        $projetRequest = $tableProjet->select()->from(array('projet'))
        									   ->where("idUser = ?", $id);
        $projetRequest->setIntegrityCheck(false);
        $projet = $tableProjet->fetchAll($projetRequest);
    	foreach ($projet as $value) {

    		$tableTache = new TTache;
    		$tacheRequest = $tableTache->select()->from(array('tache'))
    											 ->where("tache_idProjet = ?", $value->idProjet);
    		$tacheRequest->setIntegrityCheck(false);
        	$tache = $tableTache->fetchAll($tacheRequest);
    		//$tableTache->delete($tache);
    	}
    	if(count($projet)>0)
    		$tableProjet->delete($projet);
    	$user->delete();
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl('admin/index');
	}
}