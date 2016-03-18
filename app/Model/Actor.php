<?php
App::uses('AppModel', 'Model');
/**
 * Actor Model
 *
 * @property Field $Field
 * @property User $User
 */
class Actor extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

public $displayField = 'username';


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Field' => array(
			'className' => 'Field',
			'foreignKey' => 'field_id',
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
 * reset method
 *
 * @return void
 */
	public function reset($id =1){
		$this->updateAll(array('update'=>false),array('Actor.field_id' => $id));		
	}


/**
 * update method
 *
 * @return void
 */
	public function update($username){
		$this->recursive = -1;
		$result = $this->findByUsername($username);
		$this->id = $result['Actor']['id'];
		$this->saveField('update', true);		
	}


	/**
	 * list_actor method
	 *
	 * @return void
	 */
		public function list_actor_update_false($id = 1){

		    $conditions = array(
		      'conditions'  => array(
				'field_id'     => $id,				
				'update'       => false,
		      ),      
		    );
		    $this->User->Actor->recursive = -1;
    		$all = $this->User->Actor->find('list', $conditions);
    		debug($all);
    		die();
    		return $all;			
		}

/**
 * list_all_actor method
 *
 * @return void
 */
	public function list_all_actor_user_id($id){
		$conditions = array(
		      'conditions'  => array(		        
		        'field_id' => $id,
		    ),
		      'fields'=>array('user_id'),
		);

		$this->recursive = -1;
		$all = $this->find('list', $conditions);
		return $all;
		
	}


/**
 * add method
 *
 * @return void
 */
	public function add($username,$field_id,$user_id){
		$conditions = array(
			'conditions'  => array(
				'username' => $username,
				'field_id' => $field_id,
			),			
		);

		$this->recursive = -1;
		$data  = $this->find('first',$conditions);
		
		if (empty($data)) {
			$out = array(
				'username' => $username,
				'update'   => false,
				'field_id' => $field_id,
				'user_id'  => $user_id
			);
			if ($this->save($out) ) {
				return 'saved';
			}
		}else{
			return 'already exist';
		}
		
	}


/**
 * dele method
 *
 * @return void
 */
	public function delete_actor($username){
		$deleted = $this->deleteAll(array('Actor.username' => $username), false);
		debug($deleted);
		die();
		
	}






/**
 * actors method
 *
 * @return void
 */
	public function actors(){
			
    		$all = $this->find('all');
    		return $all;
		
	}


/**
 * collect_user method
 *
 * @return void
 */
	public function collect_user(){
		$conditions = array(
			'conditions'  => array(
				'collect_user' => true,
			),			
		);
		$result = $this->find('list',$conditions);
		return $result ; 
		
	}



}
