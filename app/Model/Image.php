<?php
App::uses('AppModel', 'Model');
/**
 * Image Model
 *
 * @property UserMedia $UserMedia
 * @property StandardResolution $StandardResolution
 * @property LowResolution $LowResolution
 * @property Thumbnail $Thumbnail
 * @property LowResolution $LowResolution
 * @property StandardResolution $StandardResolution
 * @property Thumbnail $Thumbnail
 */
class Image extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed




/**
 * afterSave method
 *
 * @return void
 */
	public function afterSave($created, $options = array()){
		return $this->data['Image']['id'];		
	}






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
		'StandardResolution' => array(
			'className' => 'StandardResolution',
			'foreignKey' => 'standard_resolution_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'LowResolution' => array(
			'className' => 'LowResolution',
			'foreignKey' => 'low_resolution_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Thumbnail' => array(
			'className' => 'Thumbnail',
			'foreignKey' => 'thumbnail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);





/**
 * save_image method
 *
 * @return void
 */
	public function save_image($data){
		$exist = $this->findByUserMediaId($data['Image']['user_media_id']);
		if(!empty($exist)){
			$id = $exist['Image']['id'];
		}else{
			$this->save($data);
			$id = $this->id;
		}

		return $this->findById($id);
		
	}







}
