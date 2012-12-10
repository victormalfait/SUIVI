<?php
class formLogin extends Zend_Form
{
	public function init(){

		$this->setMethod('post');
		$this->setAction('');

		$decorators = array(
		    'ViewHelper',
		    'Errors',
		    array('Description', array('tag' => 'p', 'class' => 'description')),
		    array('HtmlTag', array('tag' => 'li')),
		    array('Label', array('tag' => 'li', 'requiredSuffix' => ' :')),
		    
		);


		$eLog = new Zend_Form_Element_Text('login');
		$eLog->setLabel('Login');
		$eLog->setAttrib('required', 'required');
		$eLog->setRequired(true);
		$eLog->setDecorators($decorators);

		$eMdp = new Zend_Form_Element_Password('mdp');
		$eMdp->setLabel('Mot de passe');
		$eMdp->setAttrib('required', 'required');
		$eMdp->setRequired(true);
		$eMdp->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('identifier');
		$eSubmit->setDecorators($decorators);
		$eSubmit->setLabel('Identifier');

		$this->addElement($eLog);
		$this->addElement($eMdp);
		$this->addElement($eSubmit);

		$this->setDecorators(
		    array(
		        'FormElements',
		        array('HtmlTag', array('tag' => 'ul', 'class'=>'login')),
		        'Form'
		    )
		);

	}
}
