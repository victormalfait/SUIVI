<?php
class formTache extends Zend_Form
{
	private $idTacheParam;
	private $idProjetParam;

	public function init(){

		 // on recupere la valeur de l'id utilisateur
        $idTache = $this->getIdTache();
        $idProjet = $this->getIdProjet();

		$this->setMethod('post');
		$this->setAction('/tache/ajouter/idTache/'.$idTache);
		$this->clearDecorators();

		$decorators = array(
		    'ViewHelper',
		    'Errors',
		    array('Description', array('tag' => 'p', 'class' => 'description')),
		    array('HtmlTag', array('tag' => 'li')),
		    array('Label', array('tag' => 'li')),
		);

		$decorators = new Projet_Form_Decorator_Projet;
		$decorators = array($decorators);

		$eMdp = new Zend_Form_Element_Textarea('tache_contenu');
		$eMdp->setLabel('Description du tache');
		$eMdp->setAttribs(array('cols' => 50, 'rows'=> 6 ));
		$eMdp->setRequired(true);
		$eMdp->addValidator('StringLength', false, array('max'=> '200'));
		$eMdp->setDecorators($decorators);

		

		$eDatedeb = new Zend_Form_Element_Text('datepickerdeb');
		$eDatedeb->setLabel('Date de début');
		$eDatedeb->setAttrib('class','datepickerdeb'.$idTache);
		$eDatedeb->setDecorators($decorators);

		$eDatefin = new Zend_Form_Element_Text('datepickerfin');
		$eDatefin->setLabel('Date de fin');
		$eDatefin->setAttrib('class','datepickerfin'.$idTache);
		$eDatefin->setDecorators($decorators);


		$eSelect = new Zend_Form_Element_Select('etat');
		$eSelect->addMultiOption('Pas commencé', 'Pas commencé');
		$eSelect->setDecorators($decorators);

		$eHidden = new Zend_Form_Element_Hidden('id');
		$eHidden->setValue($idProjet);

		$eSubmit = new Zend_Form_Element_Submit('enregistrer');


		$eCancel = new Zend_Form_Element_Reset('annuler');
		$eCancel->setAttrib('class','close');

		$this->addElement($eMdp);
		$this->addElement($eDatedeb);
		$this->addElement($eDatefin);
		$this->addElement($eSelect);


			

        // si on a une valeur ...
        if (isset ( $idTache ) && $idTache != "") {
        	$eDatedeb->setName('datepickerdeb'.$idTache);
			$eDatefin->setName('datepickerfin'.$idTache);

            // ... on charde le model de base de donnée Client,
            $tableTache = new TTache ( );
            // on envoi la requete pour recupere les informations de l'utilisateur
            $tache = $tableTache  ->find($idTache)
                                  ->current();
           	$datefin = date("d-m-Y",$tache->tache_fin);
           	$datedebut = date("d-m-Y",$tache->tache_debut);
            // si on a un retour
            if ($tache != null) {
                // on peuple le formulaire avec les information demandé
                $tache = array(
                	'tache_contenu' => $tache->tache_contenu
                	);

                $this->populate ( $tache );
                $eDatefin->setValue($datefin);
                $eDatedeb->setValue($datedebut);

            }
            $eSelect->clearMultiOptions();
            $etatTab=array('Pas commencé'=>'Pas commencé', 'En cours'=>'En cours', 'Fait'=>'Fait', 'En retard'=>'En retard');
            $eSelect->setMultiOptions($etatTab);
           
            // on change le label du bouton
            $eSubmit->setLabel ( 'Modifier' );
        }


		$this->addElement($eHidden);
		$this->addElement($eSubmit);
		$this->addElement($eCancel);

		
		$this->setDecorators(
		    array(
		        'FormElements',
		        array('HtmlTag', array('tag' => 'ul', 'class'=>'ajoutprojet')),
		        'Form'
		    )
		);

		
	}

	public function setIdTache($id){
		$this->idTacheParam = $id;
	}

	public function getIdTache(){
		return $this->idTacheParam;
	}

	public function setIdProjet($id){
		$this->idProjetParam = $id;
	}

	public function getIdProjet(){
		return $this->idProjetParam;
	}
}