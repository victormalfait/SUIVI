<?php
class formProjet extends Zend_Form
{
	public function init(){

		$this->setMethod('post');
		$this->setAction('');
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

		$eLog = new Zend_Form_Element_Text('titre');
		$eLog->setLabel('Titre du projet');
		$eLog->setRequired(true);
		$eLog->setDecorators($decorators);

		$eMdp = new Zend_Form_Element_Textarea('description');
		$eMdp->setLabel('Description du projet');
		$eMdp->setAttribs(array('cols' => 50, 'rows'=> 6 ));
		$eMdp->setRequired(true);
		$eMdp->setDecorators($decorators);
		$eMdp->addValidator('StringLength', false, array('max'=> '200'));

		$eDatedeb = new Zend_Form_Element_Text('datepickerdeb');
		$eDatedeb->setLabel('Date de début');
		$eDatedeb->setRequired(true);
		$eDatedeb->setDecorators($decorators);

		$eDatefin = new Zend_Form_Element_Text('datepickerfin');
		$eDatefin->setLabel('Date de fin');
		$eDatefin->setRequired(true);
		$eDatefin->setDecorators($decorators);

		$eHidden = new Zend_Form_Element_Hidden('id');

		$eSubmit = new Zend_Form_Element_Submit('Nouveau');

		$this->addElement($eLog);
		$this->addElement($eMdp);
		$this->addElement($eDatedeb);
		$this->addElement($eDatefin);
		$this->addElement($eHidden);
		$this->addElement($eSubmit);

		$this->setDecorators(
		    array(
		        'FormElements',
		        array('HtmlTag', array('tag' => 'ul', 'class'=>'ajoutprojet')),
		        'Form'
		    )
		);

		
	}
}