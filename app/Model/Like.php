<?php
App::uses('AppModel', 'Model');
/**
 * Like Model
 *
 * @property UserMedia $UserMedia
 * @property User $User
 */
class Like extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'UserMedia' => array(
			'className' => 'UserMedia',
			'foreignKey' => 'user_media_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);



public function beforeSave($options = array()) {
    $exist = $this->User->exist($this->data['Like']['User']);
    return true;
}


}
