<?php
class formUser extends Zend_Form
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

		$eLogin = new Zend_Form_Element_Text('login');
		$eLogin->setLabel('Login');
		$eLogin->setDecorators($decorators);

		$eNom = new Zend_Form_Element_Text('nom');
		$eNom->setLabel('Nom');
		$eNom->setDecorators($decorators);

		$ePrenom = new Zend_Form_Element_Text('prenom');
		$ePrenom->setLabel('Prenom');
		$ePrenom->setDecorators($decorators);

		$eEmail = new Zend_Form_Element_Text('email');
		$eEmail->setLabel('Email');
		$eEmail->addValidator('EmailAddress');
		$eEmail->setDecorators($decorators);

		$eMdp = new Zend_Form_Element_Text('motdepasse');
		$eMdp->setLabel('Mot de passe');
		$eMdp->setValue($this->Genere_Password());
		$eMdp->setDecorators($decorators);

		$eSubmit = new Zend_Form_Element_Submit('créer');


		$eCancel = new Zend_Form_Element_Reset('annuler');
		$eCancel->setAttrib('class','close');

		$this->addElement($eLogin);
		$this->addElement($eNom);
		$this->addElement($ePrenom);
		$this->addElement($eEmail);
		$this->addElement($eMdp);
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

	
	//Genere un mot de passe aleatoire ( mot de passe de 8 caractere majuscule)
	function Genere_Password()
	{
	    // on créer un variable vide
	    $password ='';
	    // Initialisation des caractères utilisables
	    $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

	    // pour i = 0 tant que i est inferieur a 8 on fait i++
	    for($i=0;$i<8;$i++)
	    {
	        //la variable  = un caractere aleatoire majuscule
	        $password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
	    }

	    // on retourne la variable
	    return $password;
	} 

}
