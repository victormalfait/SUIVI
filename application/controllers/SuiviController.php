<?php

class SuiviController extends Zend_Controller_Action
{
    public function indexAction()
    {
        //on recupere l'id de la personne authentifié
        $membre = Zend_Auth::getInstance ()->getIdentity();
        $idClient = $membre->idUser;

        //on recupere la date du jour
    	$jour=date("d",time());
        $mois=date("m",time());
        $an=date("Y",time());

        //on convertie en timestamp
        $timestamp=mktime(0, 0, 0, $mois, $jour, $an);

        //on recupere les projets qui concerne le chef de projet concerné
    	$tableProjet = new TProjet;
    	$projetRequest = $tableProjet->select()->from(array('p'=>'projet'),array('p.idProjet','p.nomprojet','p.description_projet','p.date_debut','p.date_fin','p.idUser'));;
    	$projetRequest->setIntegrityCheck(false);
    	$projetRequest->where('p.idUser ='.$idClient);
    	$projetRequest->join(array('u'=>'user'),'p.idUser=u.idUser',array('u.nom_user'));
    	$projetRequest->order('p.date_fin');			  
		$projet = $tableProjet->fetchAll($projetRequest);
		$this->view->result = $projet;

        //on crée un formulaire et on l'envoi sur la vue
        $formProjet = new formProjet;
        $this->view->form = $formProjet;
        $this->view->time = $timestamp;
        
    }

    public function enregistrementAction()
    {
        $membre = Zend_Auth::getInstance ()->getIdentity();
        $idClient = $membre->idUser;
        if ($this->getRequest()->isPost()){
            $formData = $this->_request->getPost ();
            if($form->isValid($formData)){
                 //On explose le format envoyé par les datepicker
                list($jourD, $moisD, $anneeD) = explode("-", $formTache->getValue('datepickerdeb'));
                list($jourF, $moisF, $anneeF) = explode("-", $formTache->getValue('datepickerfin'));

                //on passe au format timestamp les dates
                $date_debut = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);
                $date_fin = mktime(23, 59, 59, $moisF, $jourF, $anneeF);

                $tableProjet = new TProjet;

            }

           
            $data = array(
                'nomProjet'=> $_POST['titre'],
                'description_projet'=> $_POST['description'],
                'date_debut' => $date_debut,
                'date_fin'=> $date_fin,
                'idUser' => $idClient
                );
            $tableProjet->insert($data);
            echo 'Projet bien enregistré';
        }   
    } 

    public function supprimerAction()
    {
        $id = $_POST['idduprojet'];
        $tableProjet = new TProjet;
        $projet = $tableProjet->find($_POST['idduprojet'])->current();
        $projet->delete();
        $tableTache = new TTache;
        $tache = $tableTache->delete(array("tache_idProjet = ?" => $id));
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl('suivi/index');
    } 
}