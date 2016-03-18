<?php
App::uses('AppModel', 'Model');
/**
 * UserMedia Model
 *
 * @property User $User
 * @property Comment $Comment
 * @property Image $Image
 * @property Like $Like
 */
class UserMedia extends AppModel {


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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'user_media_id',
		),
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'user_media_id',
		),
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'user_media_id',
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
		'Like' => array(
			'className' => 'Like',
			'foreignKey' => 'user_media_id',
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
 * min method
 *
 * @return void
 */
	public function min($user_id){
		$min = array(
	          'conditions'  => array(
	            'user_id' => $user_id ,
	          ),
	          'fields'    => array(
	            'id',
	            'created_time',
	          ),          
	          'order'    => array(
	            'created_time' => 'desc',
	          ),
        );
    	$this->recursive = -1;
    	$min = $this->find('first', $min);
    	if ($min) {
    		$min_id = $min['UserMedia']['id'];
    		return $min_id;
    	}else{
    		return false;
    	}		
	}

/**
 * max method
 *
 * @return void
 */
	public function max($user_id){
		$max = array(
            'conditions'  => array(
              'user_id' => $user_id ,
            ),
            'fields'    => array(
              'id',
              'created_time',
            ),          
            'order'    => array(
              'created_time' => 'asc',
            ),
          );
          $this->recursive = -1;
          $max = $this->find('first', $max);
          if ($max) {
          	$max_id = $max['UserMedia']['id'];
          	return $max_id;
          }else{
          	return false;          	
          }
		
	}


/**
 * save_images method
 *
 * @return void
 */
    public function save_images($images){

        if ($this->saveMany($images)) {
            debug('all saved');
            die();
        }
        
    }

/**
 * get_last_image($user_id,$count)ethod
 *
 * @return void
 */
    public function get_last_image($users,$count = 5){
        $conditions = array(
            'conditions'  => array(
                'user_id IN' => $users,
            ),            
            'order'      => array(
                'created desc',
            ),
            'fields'=>array(
                'link',
                'id',
                'user_id',
                'created_time'
            ),
            'limit'=>$count
        );
        $this->recursive = -1;
        $data = $this->find('all',$conditions);
        foreach ($data as $key => $item) {
            $item = $item['UserMedia']['id'];
            $media_id = substr($item, 0,strpos($item, '_'));
            $result[]  = $media_id;
        }        
        $return = array(
            'data'   => $data,
            'result' => $result,
        );        
        return $return;        
    }




}
