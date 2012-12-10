<?php
class TacheController extends Zend_Controller_Action
{
    function find_first_day_of_week($timestamp) {
        $target_week=$timestamp;
        $day=date('D',$target_week);
        switch(strtoupper($day)) {
            case "MON":$day_left=0;
                break;
            case "TUE":$day_left=1;
                break;
            case "WED":$day_left=2;
                break;
            case "THU":$day_left=3;
                break;
            case "FRI":$day_left=4;
                break;
            case "SAT":$day_left=5;
                break;
            case "SUN":$day_left=6;
                break;
            default:$day_left=0;
                break;
        }
        $one_day=24*60*60;
        $first_day = $target_week - ($one_day * $day_left);
        return $first_day;
    }

    function find_first_day_of_month($timestamp) {
        $target_month=$timestamp;    
        $day=date('d',$target_month);
        for($i=1;$i<32;$i++){
            if($day==$i){
                $day_left=$i-1;}
        }
        $one_day=24*60*60;
        $first_day = $target_month - ($one_day * $day_left);
        return $first_day;
    }

    function find_nbr_day($timestamp){
        $target = $timestamp;
        $month = date('n',$target);
        switch ($month) {
            case '1':$nbrDay=31;
                break;
            case '2':$nbrDay=28;
                break;
            case '3':$nbrDay=31;
                break;
            case '4':$nbrDay=30;
                break;
            case '5':$nbrDay=31;
                break;
            case '6':$nbrDay=30;
                break;
            case '7':$nbrDay=31;
                break;
            case '8':$nbrDay=31;
                break;
            case '9':$nbrDay=30;
                break;
            case '10':$nbrDay=31;
                break;
            case '11':$nbrDay=30;
                break;
            case '12':$nbrDay=31;
                break;                    
            default:$nbrDay=31;
                break;
        }
        return $nbrDay;
    }
    function margin($target) {
         $day=date('D',$target);
        switch(strtoupper($day)) {
            case "MON":$day_left=0;
                break;
            case "TUE":$day_left=1;
                break;
            case "WED":$day_left=2;
                break;
            case "THU":$day_left=3;
                break;
            case "FRI":$day_left=4;
                break;
            case "SAT":$day_left=5;
                break;
            case "SUN":$day_left=6;
                break;
            default:$day_left=0;
                break;
        }
        return $day_left;
    }

    public function indexAction() {

        //on recupere l'ID du projet
    	$idProjetParam = $this->_getparam('id');
        $this->view->param = $idProjetParam;

        $type = $this->_getparam('type');
        if($this->_getparam('type'))
            $this->view->type = $type;

        //appel a la table tache
		$tableTache = new TTache;

        //requete pour recuperer toute les taches
		$tacheRequest = $tableTache->select()->from(array('p'=>'tache'),array('p.tache_id','p.tache_contenu','p.tache_debut','p.tache_fin','p.tache_idProjet','p.etat_tache'));
		$tacheRequest->setIntegrityCheck(false);
		$tacheRequest->where('p.tache_idProjet = ?',$idProjetParam);
        $tacheRequest->order('p.tache_debut');
		$tache = $tableTache->fetchAll($tacheRequest);

        //on définie la date sur laquelle on veut centrer le calendrier
        $sept_jours=(7*24*60*60)-1;

        //si on a un jour en parametre
        if($this->_getParam('d')) {
            $jourJ=date("d",$this->_getparam('d'));
            $moisM=date("m",$this->_getparam('d'));
            $anA=date("Y",$this->_getparam('d'));
        }
        //sinon on centre sur la date du jour
        else {
            $moisM = date("m", time());
            $jourJ = date("d", time());
            $anA = date("Y", time());
        }

        //on transforme la date en timestamp
        $jour_debut_semaine = mktime(0,0,0, $moisM, $jourJ, $anA);

        if($type == ""){

            //on recupere le premier jour de la semaine
            $semaine_debut_reel = $this->find_first_day_of_week($jour_debut_semaine);

            //On place le debut de la semaine 3 semaine avant pour centrer le calendrier        
            $semaine_debut = $semaine_debut_reel - (3*7*24*60*60);

            //on initialise la fin de semaine 7 jours apres
            $semaine_fin = $semaine_debut + $sept_jours;

            //on ajoute différentes variables à la vue
            $this->view->semaine_debut=$semaine_debut;
            $this->view->semaine_fin=$semaine_fin;
            $this->view->tache = $tache;
        }else{
            $mois_debut_reel = $this->find_first_day_of_month($jour_debut_semaine);

            echo "<br/>".$mois_debut_reel."<br/>";

            echo "<br/>".date('d-m-y',$mois_debut_reel)."<br/>";

            $nbr_jour_mois_prec = $this->find_nbr_day($mois_debut_reel-1);
            $nbr_jour_mois = $this->find_nbr_day($mois_debut_reel);
            $nbr_jour_mois_suiv = $this->find_nbr_day($mois_debut_reel+$nbr_jour_mois*24*60*60+1);
            $nbr_jour_mois_suiv_suiv = $this->find_nbr_day($mois_debut_reel+$nbr_jour_mois_suiv*24*60*60+1);
            echo $nbr_jour_mois.'</br>';
            echo $nbr_jour_mois_suiv.'</br>';
            echo $nbr_jour_mois_suiv_suiv.'</br>';
            // $mois_suiv = 


            $mois_debut = $mois_debut_reel - ($nbr_jour_mois_prec*24*60*60);

            $this->view->mois_prec = $mois_debut;
            $this->view->mois = $mois_debut_reel;
            $this->view->mois_plus_un = $mois_suiv;
            $this->view->mois_plus_deux = $mois_suiv_suiv;

            echo "<br/>".date('d-m-y',$mois_debut)."<br/>";
        }

        //on crée un tableau pour stocker les différentes taches
        $table_content=array();


        for($i=0;$i<7;$i++) {
            $semaine=$semaine_debut+($i*$sept_jours);
            $fin_jour=$semaine+((7*24*60*60)-1);

            $count=0;
            $total_size_used=0;

           foreach($tache as $event) {
                if(($event->tache_debut >= $semaine)&&($event->tache_fin <= $fin_jour)) {

        /* je stocke le nombre d'heure */
                    $duree=$event->tache_fin - $event->tache_debut;
                    $table_content[$i]['duree']=$duree;
                    $size=$duree/2400;
        /*je stocke la taille en pixel de l'Ã©vÃ©nement */
                    $table_content[$i]['events'][$count]['size']=ceil($size);

        /*je recupere le quart d'heure de la journÃ©e correspondant
         * au dÃ©part de l'evenement */
                    $quart_heure_journee=$this->margin($event->tache_debut);
                    $table_content[$i]['events'][$count]['qhstart']=$quart_heure_journee*36;

                    $table_content[$i]['events'][$count]['etat'] = $event->etat_tache;
                    $table_content[$i]['events'][$count]['id'] = $event->tache_id;
        /* j'incrÃ©mente mes compteurs*/
                    $count+=1;
                    $total_size_used+=$size;
                }
                $table_content[$i]["nb"]=$count;
                $table_content[$i]["tsu"]=$total_size_used;
            }

        $this->view->table_content=$table_content;

    	}
    }

    public function ajouterAction(){

        //on recupere les parametre envoyer
        $idTache = $this->_request->getParam('idTache');
        $idProjet = $this->_request->getParam('idProjet');

        $this->view->idTache = $idTache;

        //on crée le formulaire
        $formTache = new formTache;

        //On envoie les valeurs d'ID dans le formulaire
        $formTache->setIdTache($idTache);
        $formTache->setIdProjet($idProjet);

        //on initialise le formulaire avec les id envoyés précédement
        $formTache->init();

        //on envoie dans la vue le formulaire
        $this->view->form = $formTache;

        //on verifie si on a un POST
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();


            if($formTache->isValid($formData)){
                $date_debut = $_POST['datepickerdeb'.$idTache];
                $date_fin = $_POST['datepickerfin'.$idTache];
                 //On explose le format envoyé par les datepicker
                list($jourD, $moisD, $anneeD) = explode("-", $date_debut);
                list($jourF, $moisF, $anneeF) = explode("-", $date_fin);

                //on passe au format timestamp les dates
                $date_debut = mktime(0, 0, 0,  $moisD, $jourD, $anneeD);
                $date_fin = mktime(23, 59, 59, $moisF, $jourF, $anneeF);

                $tableTache = new TTache;

                if(isset($idTache) && $idTache!=""){
                    $row = $tableTache->find($idTache)->current();  
                }else{
                    $row = $tableTache->createRow();

                }
                 
                $row->tache_contenu     = $formTache->getValue('tache_contenu');
                $row->tache_debut       = $date_debut;
                $row->tache_fin         = $date_fin;
                $row->tache_idProjet    = $formTache->getValue('id');
                $row->etat_tache        = $formTache->getValue('etat');

                $idProjet = $formTache->getValue('id');
                $row->save();
                $formTache->reset();
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoUrl('tache/index/id/'.$idProjet.'/d/'.$date_debut);
            }   
        }
    }

    public function supprimerAction()
    {
        $id = $_POST['idTache'];
        $tableTache = new TTache;
        $tache = $tableTache->delete(array("tache_id = ?" => $id));
        $redirector = $this->_helper->getHelper('Redirector');
        $redirector->gotoUrl('tache/index/id/'.$_POST['idProjet']);
    } 
        
}
