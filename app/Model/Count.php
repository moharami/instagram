<?php
App::uses('AppModel', 'Model');
/**
 * Count Model
 *
 * @property User $User
 * @property User $User
 */
class Count extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


/**
 * save_count method
 *
 * @return void
 */
	public function save_count($count){
		$id = $this->exist($count);
		if (isset($id)) {
			$this->id = $id;
		}
		if (isset($count)) {
			$this->save($count);
			return $this->id;
		}
		
	}


	/**
	 * exist method
	 *
	 * @return void
	 */
		public function exist($count){
			$user_id = $count['user_id'];
			$conditions = array(
				'conditions'  => array(
					'user_id' => $user_id,
				),	
				'fields'=>array(
					'id'
				),			
			);
			$this->recursive = -1;
			$exist = $this->find('first',$conditions);
			if (!empty($exist)) {
				$id = $exist['Count']['id'];
				return $id ;
			}else{
				return null;
			}
			
		}








/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'count_id',
		)
	);






}
