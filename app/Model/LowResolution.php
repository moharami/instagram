<?php
App::uses('AppModel', 'Model');
/**
 * LowResolution Model
 *
 * @property Image $Image
 * @property Image $Image
 */
class LowResolution extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed



/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'low_resolution_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/**
 * save_standard method
 *
 * @return void
 */
	public function save_low($data){
		$exist = $this->findByUrl($data['LowResolution']['url']);
		if (!empty($exist)) {
			return $exist['LowResolution']['id'];
		}else{
			$this->create();
			$this->save($data);
			$id = $this->id;
			return $id;
		}
		
	}












}
