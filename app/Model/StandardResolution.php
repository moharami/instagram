<?php
App::uses('AppModel', 'Model');
/**
 * StandardResolution Model
 *
 * @property Image $Image
 * @property Image $Image
 */
class StandardResolution extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed





/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'standard_resolution_id',
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
	public function save_standard($data){
		$exist = $this->findByUrl($data['StandardResolution']['url']);
		if (!empty($exist)) {
			return $exist['StandardResolution']['id'];
		}else{
			$this->create();
			$this->save($data);
			$id = $this->id;
			return $id;
		}
		
	}













}
