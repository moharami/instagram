<?php
App::uses('AppModel', 'Model');
/**
 * Relationship Model
 *
 * @property RelationshipType $RelationshipType
 * @property User $User
 * @property Field $Field
 */
class Relationship extends AppModel {
	 public $actsAs = array('Containable');


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'RelationshipType' => array(
			'className' => 'RelationshipType',
			'foreignKey' => 'relationship_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'counterCache' => true,
			'fields' => '',
			'order' => ''
		),
		'Field' => array(
			'className' => 'Field',
			'foreignKey' => 'field_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


/**
 * set_unfollow method
 *
 * @return void
 */
	public function set_unfollow($user_id){
		$this->id = $user_id;
		if ($this->saveField('unfollow', true) && $this->saveField('unfollow_time', date('Y-m-d H:i:s')) ) {
			return true;
		}else{
			return false;
		}
		
		
		
	}

}
