<?php
App::uses('AppModel', 'Model');
/**
 * Comment Model
 *
 * @property UserMedia $UserMedia
 * @property User $User
 */
class Comment extends AppModel {


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




/**
 * beforeSave method
 *
 * @return void
 */
	public function beforeSave($options = array()){
		// $d = $this->data['Comment'];
		// unset($d['User']);
		// $exist = $this->User->exist($this->data['Comment']['User']);
		// $this->data = $d;
  //   	return true;		
	}



/**
 * save_comment method
 *
 * @return void
 */
	public function save_comment($data){
		$exist = $this->findById($data['Comment']['id']);
		if (empty($exist)) {
			$this->create();
			$this->save($data);
		}else{

		}
		
	}


}
