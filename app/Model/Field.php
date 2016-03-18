<?php
App::uses('AppModel', 'Model');
/**
 * Field Model
 *
 * @property Actor $Actor
 * @property Relationship $Relationship
 */
class Field extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Actor' => array(
			'className' => 'Actor',
			'foreignKey' => 'field_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Relationship' => array(
			'className' => 'Relationship',
			'foreignKey' => 'field_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Follower' =>array(
			'className' => 'Follower',
			'foreignKey' => 'field_id',
			'dependent' => false,
		),
	);


/**
 * list_alias method
 *
 * @return void
 */
	public function list_alias(){
		$conditions = array(			
			'fields'	  => array(
				'alias',
			),
			'conditions'=>array(
				'live'=>1
			),							
		);
		$list = $this->find('list',$conditions);
		return $list;		
	}


}
