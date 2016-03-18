<?php
App::uses('AppModel', 'Model');
/**
 * Caption Model
 *
 * @property UserMedia $UserMedia
 */
class Caption extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


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
		)
	);




/**
 * save_location method
 *
 * @return void
 */
	public function save_location($input){
		debug($input);
		
		$latitude  = $input->latitude;
		$longitude = $input->longitude;

		if (isset($input->name)) {
			$name      = $input->name;						
		}else{
			$name = null;
		}

		if (isset($input->id)) {
			$id      = $input->id;			
		}else{
			$id = null;
		}

		$data = array(
			'id'        => $id,
			'name'      => $name,
			'latitude'  => $latitude,
			'longitude' => $longitude,
		);
		debug($data);
		

		$this->save($data);


		
	}








}
