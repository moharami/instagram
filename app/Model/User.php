<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Count $Count
 * @property Comment $Comment
 * @property Count $Count
 * @property Like $Like
 * @property UserMedia $UserMedia
 */
class User extends AppModel {
 public $actsAs = array('Containable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed


/**
 * exist method
 *
 * @return void
 */
	public function exist($data){
		$exist = $this->findById($data['id']);
		if (empty($exist)) {
			$this->save($data);
		}else{
			return true;
		}
		
	}






/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		// 'Comment' => array(
		// 	'className' => 'Comment',
		// 	'foreignKey' => 'user_id',
		// 	'dependent' => false,
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => '',
		// 	'limit' => '',
		// 	'offset' => '',
		// 	'exclusive' => '',
		// 	'finderQuery' => '',
		// 	'counterQuery' => ''
		// ),
		// 'Count' => array(
		// 	'className' => 'Count',
		// 	'foreignKey' => 'user_id',
		// 	'dependent' => false,
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => '',
		// 	'limit' => '',
		// 	'offset' => '',
		// 	'exclusive' => '',
		// 	'finderQuery' => '',
		// 	'counterQuery' => ''
		// ),
		// 'Like' => array(
		// 	'className' => 'Like',
		// 	'foreignKey' => 'user_id',
		// 	'dependent' => false,
		// 	'conditions' => '',
		// 	'fields' => '',
		// 	'order' => '',
		// 	'limit' => '',
		// 	'offset' => '',
		// 	'exclusive' => '',
		// 	'finderQuery' => '',
		// 	'counterQuery' => ''
		// ),
		'UserMedia' => array(
			'className' => 'UserMedia',
			'foreignKey' => 'user_id',
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
		'Actor' => array(
			'className' => 'Actor',
			'foreignKey' => 'user_id',
		),
		'Relationship' => array(
			'className' => 'Relationship',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);




/**
 * get_user_id method
 *
 * @return void
 */
	public function get_user_id($username){
		$conditions = array(
          'conditions'=>array(
              'username'=> $username,
            ), 
      );
      $this->recursive = -1;
      $d                     = $this->find('first', $conditions);
      if ($d) {
      	$user_id               = $d['User']['id'];
      }else{
      	$user_id = null;
      }
      return $user_id;
		
	}








/**
 * save_user method
 *
 * @return void
 */
	public function save_user($user){		
		$result = $this->exist_user($user);
		if ($result === true) {
			$this->create();
			$this->save($user);
			return true;
		}else{
			$this->id = $result['User']['id'];
			$this->saveField('created_time', $result['User']['created_time']);			
		}
		
	}


/**
 * exist_user method
 *
 * @return void
 */
	public function exist_user($user){
		
		$username  = $user['User']['username'];
		$this->recursive = -1;
		$result = $this->findByUsername($username);		
		
		if (empty($result)) {
			$result = true;
		}
		return $result;
		
	}



/**
 * set_request_send method
 *
 * @return void
 */
	public function set_request_send($user_id, $var){
		$this->id = $user_id;
		$this->saveField('request_send', $var);
		
		
	}



/**
 * request_send method
 *
 * @return void
 */
	public function request_send($user_id,$field_id = 1){
		$this->id = $user_id;
		$this->recursive = -1;
		$user = $this->findById($user_id);
		if ($field_id == 1) {
			$request_send = $user['User']['request_send'];			
		}else{
			$request_send = $user['User']['request_send_tabiate_jahan'];
		}
		return $request_send;
		
	}


/**
 * set_income_outgoing method
 *
 * @return void
 */
	public function set_income_outgoing($user_id,$val){
		
		
		$this->id = intval($user_id);
		$this->saveField('incoming_outgoing_set', $val);
		
	}


}
