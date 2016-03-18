<?php
App::uses('AppModel', 'Model');
/**
 * Follower Model
 *
 * @property Field $Field
 */
class Follower extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		)
	);

/**
 * save_follower method
 *
 * @return void
 */
	public function save_follower($obj, $field_id){
		date_default_timezone_set('Asia/Tehran');   
		$last = $this->last($field_id);
		
		$data = array(
			'field_id'       => $field_id,
			'follower'       => $obj->data->counts->followed_by,
			'following'      => $obj->data->counts->follows ,
			'media'          => $obj->data->counts->media,
			'follower_diff'  => $obj->data->counts->followed_by - $last['follower'] ,
			'following_diff' => $obj->data->counts->follows - $last['following'] ,
			'hour'           => date('H'),
			'minute'         => date('i'),
		);
		$this->create();
		if ($this->save($data)) {
		}
	}



/**
 * last method
 *
 * @return void
 */
	public function last($field_id){
		$conditions = array(
			'conditions'  => array(
				'field_id' => $field_id,
			),								
			'order'	  => array(
				'created desc',
			),
		);

		$this->recursive = -1;
		$last = $this->find('first',$conditions);

		if (empty($last)) {
			$data = array(
				'follower'  => 0,
				'following' => 0,
				'media'     => 0,
			);
		}else{
			$data = array(
				'follower'  => $last['Follower']['follower'],
				'following' => $last['Follower']['following'],
				'media'     => $last['Follower']['media'],
			);

		}

		return $data;
		
	}


}
