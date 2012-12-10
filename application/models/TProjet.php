<?php
class TProjet extends Zend_Db_Table_Abstract
{
	protected $_name = 'projet';
	protected $_primary = 'idProjet';

	protected $_referenceMap = array(
		'user' => array(
			'columns'=> 'idUser',
			'refTableClass' => 'TUser'
			)
		);
}