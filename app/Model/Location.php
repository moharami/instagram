<?php
App::uses('AppModel', 'Model');
/**
 * Location Model
 *
 * @property UserMedia $UserMedia
 */
class Location extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'location';

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
		

		if (isset($input->latitude)) {			
			$latitude  = $input->latitude;
		}else{
			$latitude = null;
		}
		
		if (isset($input->longitude)) {			
			$longitude  = $input->longitude;
		}else{
			$longitude = null;
		}

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
		$this->save($data);

	}
}
