<?php
class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
        //on recupere la date du jour
    	$jour=date("d",time());
        $mois=date("m",time());
        $an=date("Y",time());

        //on convertie la date en timestamp
        $timestamp=mktime(0, 0, 0, $mois, $jour, $an);

        //on recupere les infos des projets dans la BDD
    	$tableProjet = new TProjet;
    	$projetRequest = $tableProjet->select()->from(array('p'=>'projet'),array('p.idProjet','p.nomprojet','p.description_projet','p.date_debut','p.date_fin','p.idUser'));;
    	$projetRequest->setIntegrityCheck(false);

        //on recupere les projets qui sont en cours à la date du jour
    	$projetRequest->where('p.date_debut <='.$timestamp.' and p.date_fin >='.$timestamp);

        //on join la table user pour recuperer le nom du chef de projet
    	$projetRequest->join(array('u'=>'user'),'p.idUser=u.idUser',array('u.login_user'));

        //on tri les résultat par date de fin
    	$projetRequest->order('p.date_fin');			  
		$projet = $tableProjet->fetchAll($projetRequest);

        //on envoi les résultats sur la vue
		$this->view->result = $projet;
    }

    public function loginAction()
    {	
        //on crée le formulaire de login
        $form = new formLogin;

        //on affiche le formulaire          
        $this->view->login = $form;
        
        //on verifie si il y a un POST
        if ($this->_request->isPost ()) {
            //sinon on recupere les infos envoyées
            $formData = $this->_request->getPost ();

            //on verifie que les informations sont valides
            if ($form->isValid ( $formData )) {

                //on recupere les valeurs dans login et mdp
                $email = $form->getValue ( 'login' );
                $password = $form->getValue ( 'mdp' );

                //on crée un Zend Auth
                $authAdapter = new Zend_Auth_Adapter_DbTable ( Zend_Db_Table::getDefaultAdapter () );
                $authAdapter->setTableName ('user')//table ou l'on doit vérifier
                            ->setIdentityColumn ('login_user')//colonne à vérifier
                            ->setCredentialColumn ('motdepasse')//colonne  crypter à vérifer
                            ->setIdentity ($email)
                            ->setCredential ($password);

                //on execute la verif
                $authAuthenticate = $authAdapter->authenticate ();
                print_r($authAuthenticate);

                //on verifie si elle est valide
                if ($authAuthenticate->isValid ()) {

                    //on crée un espace de stackage
                    $storage = Zend_Auth::getInstance ()->getStorage ();

                    //on enregistre les infos récupérer dans l'AUTH sauf le MDP
                    $storage->write ( $authAdapter->getResultRowObject ( null, 'motdepasse' ) );

                    //on recupere les infos de l'utilisateur logué
                    $membre = Zend_Auth::getInstance()->getIdentity();

                    //on verifie son role pour le rediriger vers la page correspondante
                    if($membre->role == 'administrateur'){
                        $redirector = $this->_helper->getHelper('Redirector');
                        $redirector->gotoUrl("admin/index");
                    }else{

                        $moisM = date("m", time());
                        $jourJ = date("d", time());
                        $anA = date("Y", time());
                        $jour_debut_semaine = mktime(0,0,0, $moisM, $jourJ, $anA);

                        $tableUser = new TUser;

                        $row = $tableUser->find($membre->idUser)->current(); 

                        $row->date_derniere_connexion = $jour_debut_semaine;
                        $row->save();

                        $redirector = $this->_helper->getHelper('Redirector');
                        $redirector->gotoUrl("suivi/index"); 
                    }
                } 

                //si l'authentification n'est pas valide on affiche un message d'erreur
                else {
                    $form->addError ( 'Il n\'existe pas d\'utilisateur avec ce mot de passe ou ce login' );
                }
            }
        }
        //permet d'additionner l'action à une autre
        $this->_helper->viewRenderer->setResponseSegment('login');
    }

    public function logoutAction() {

        //on vide les infos stocker dans zend auth
        Zend_Auth::getInstance ()->clearIdentity ();

        //on redirige vers la page d'accueil
        $this->_helper->redirector ( 'index', 'index' );
    }
}

