<?php
App::uses('AppModel', 'Model');
/**
 * Thumbnail Model
 *
 * @property Image $Image
 * @property Image $Image
 */
class Thumbnail extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed



/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'thumbnail_id',
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
	public function save_thumbnail($data){
		$exist = $this->findByUrl($data['Thumbnail']['url']);
		if (!empty($exist)) {
			return $exist['Thumbnail']['id'];
		}else{
			$this->create();
			$this->save($data);
			$id = $this->id;
			return $id;
		}
		
	}






}
