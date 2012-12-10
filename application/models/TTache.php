<?php
class TTache extends Zend_Db_Table_Abstract
{
	protected $_name = 'tache';
	protected $_primary = 'tache_id';

	protected $_referenceMap = array(
		'projet' => array(
			'columns'=> 'idProjet',
			'refTableClass' => 'TProjet'
			)
		);
}