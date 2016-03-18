<?php
App::uses('HttpSocket', 'Network/Http');
/**   
* Instagram Component
* @author Joris Blaak <joris@label305.com>
* @require CakePHP 2.x
* @license THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT 
* WARRANTY OF ANY KIND. ONLY OUR CLIENTS FOR CUSTOM SOFTWARE 
* ARE ENTITLED TO A LIMITED WARRANTY UP TO SIX WEEKS AFTER 
* COMPLETION OR DEPLOYMENT. SEE OUR ARTICLE 5 OF OUR GENERAL  
* TERMS AND CONDITIONS FOR MORE INFORMATION ON OUR WARRANTY. 
*/
class InstagramComponent extends Component {

	/**
	 * Base url of the instagram api
	 * @var string
	 */
	public $apiBase = 'https://api.instagram.com/v1';

	/**
	 * Meta data of the last request, for information purposes
	 * @var array
	 */
	public $meta;

	/**
	 * Cake component initialize
	 * @param  Controller $controller 
	 */
	public function initialize(Controller $controller) {
        $this->controller = $controller;
    }
	
	/**
	 * List subscriptions
	 * @return array
	 */
	public function subscriptions() {
		return $this->get('/subscriptions/');
	}

	/**
	 * Subscribe to the realtime api
	 *
	 * Available objects:
	 *
	 * + Users
	 * + Tags
	 * + Locations
	 * + Geographies
	 *
	 * Available aspects:
	 *
	 * + Media
	 *
	 * Extra parameters:
	 *
	 * + object_id (in case of, for example, tags you can pass the tag here)
	 * + ...
	 * 
	 * @param  string $object    object you'd like to subscribe to
	 * @param  string $aspect    aspect of the object you'd like to subscribe to
	 * @param  array  $options   the different params available for the objects
	 * @return array             the subscription
	 */
	public function subscribe($object, $aspect, $options=array()) {
		$pass = array_merge(array(
			'object' => $object,
			'aspect' => $aspect
		), $options);
		return $this->post('/subscriptions/', $pass);
	}

	/**
	 * Unsubscribe from a subscription id
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function unsubscribe($id) {
		return $this->delete('/subscriptions', array('id' => $id));
	}

	/**
	 * Verifies the request, also will handle the 'hub_challenge' in an uber ugly, but working, way
	 * @return mixed    boolean on error, otherwise input data
	 */
	public function verify() {
		if(isset($this->controller->request->query['hub_challenge'])) {
			echo $this->controller->request->query['hub_challenge'];
			die();//This is the ugly bit, we just 
		} else {
			$input = file_get_contents("php://input");
			if(hash_hmac('sha1', $input, Configure::read('Instagram.secret')) == $this->controller->request->header('X-Hub-Signature')) {
				return $this->process($input);	
			}
		}
		return false;
	}

	/**
	 * Make a get request to the api
	 * @param  string $url  relative to the base
	 * @param  array $data 
	 * @return array
	 */
	public function get($url, $data = array()) {
		$http = new HttpSocket();
		$url = $this->apiBase.$url;
		return $this->process($http->get($url, $this->_params($data)));
	}

	/**
	 * Make a post request to the api
	 * @param  string $url  relative to the base
	 * @param  array $data 
	 * @return array
	 */
	public function post($url, $data = array()) {
		$http = new HttpSocket();
		$url = $this->apiBase.$url;
		debug($url);
		debug($this->_params($data));
		
		return $this->process($http->post($url, $this->_params($data)));
	}

	/**
	 * Make a delete request to the api
	 * @param  string $url  relative to the base
	 * @param  array $data 
	 * @return array
	 */
	public function delete($url, $data = array()) {
		$http = new HttpSocket();
		return $this->process($http->delete($this->apiBase.$url.'?'.http_build_query($this->_params($data))));
	}

	/**
	 * Wrapper for json_decode to array supressing php errors with invalid formatting
	 * @param  string $raw 	json input
	 * @return mixed      	array with result or true on success otherwise false
	 */
	private function process($raw) {
		$result = false;
		$data = @json_decode($raw, true);		
		if(is_array($data)) {
			if(isset($data['data'], $data['meta']['code']) && $data['meta']['code'] == 200) {
				$result = $data['data'];
			} elseif(isset($data['meta']['code']) && $data['meta']['code'] == 200) {
				$result = true;
			}
			if(isset($data['meta'])) {
				$this->meta = $data['meta'];
			}
		}
		return $result;
	}

	/**
	 * Concatenates input data with required api data
	 * @param  array $data 
	 * @return array
	 */
	private function _params($data) {
		return 
			 $data;
	}





/**
 * followby method
 *
 * @return void
 */
	public function followedby($user_id){
		
	}





// write by Amir Moharami
// ***************** Scope User ********************************
	/**
	 * get_user_info method
	 *
	 * @return void
	 */
		public function get_user_info($user_id){
			$result = $this->get('/users/' . $user_id );
		 	return $result;			
		}


	/**
	 * recent_media method
	 *
	 * @return void
	 */
		public function recent_media($user_id, $min_id = null, $max_id = null, $count = null){
			if (!isset($min_id) && !isset($max_id)) {				
				$result = $this->get('/users/' . $user_id .'/media/recent', array('count'=> $count, 'min_id'=> $min_id, 'max_id'=> $max_id ));
		 		return $result;
				
			}
			
		}
// ***************** Scope User ********************************




// ***************** Scope Relationship ********************************
		
		/** Get the list of users this user follows.
		 * follows method
		 *
		 * @return void
		 */
			public function follows($user_id){
				$result = $this->get('/users/' . $user_id . '/followed-by');
		 		return $result;				
			}





	/**
	 * relationship method
	 *
	 * @return void
	 */
		public function do_follow($user_id){
			$result = $this->post('/users/' . $user_id . '/relationship',array('action'=>'follow'));
			return $result;
			
		}
// ***************** Scope Relationship ********************************




// ***************** Scope Like ********************************
	/**
	 * do_like method
	 *
	 * @return void
	 */
		public function do_like($media_id,$access_token){
			debug(http_build_query( array('access_token'=> $access_token) ));
			die();
			$result = $this->post2('/media/' . $media_id . '/like',array('access_token'=> $access_token));
			return $result;
			
		}
// ***************** Scope Like ********************************







    public function post2( $url, array $data = null ) {
    	$url = curl_init();
        curl_setopt( $url, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $url, CURLOPT_URL, $url );
        curl_setopt( $url, CURLOPT_POSTFIELDS, http_build_query( $data ) );
        $jsonData = curl_exec($url);
        debug($jsonData);
        die();
    }


/**
 * comments method
 *
 * @return void
 */
    public function comments($images){
    	$img = $images['data'];
    	
    	foreach ($img as $key => $image) {
			$image_link = $image['UserMedia']['link'];
			$id         = $image['UserMedia']['id'];
			$media_id = substr($id, 0,strpos($id, '_'));
	    	$recent_comment = $this->recent_comment($media_id);
    		$comments[] =$recent_comment;    		
    	}

    	$comments = $this->sort_comment_date($comments);
    	return $comments;
    }


/**
 * sort_comment_date method
 *
 * @return void
 */
	public function sort_comment_date($comments){
		foreach ($comments as $key => $comment) {
			foreach ($comment as $key => $cm) {
				$all_cm[] = $cm;
			}
		}




		foreach ($all_cm as $key => $comment) {	        	
			$user         = $comment->from;
			$created_time = $comment->created_time;        	
			$users[]      = $this->user($user, $created_time);
        }
        
        usort($users, function($a, $b) {
		    return $a['User']['created_time'] - $b['User']['created_time'];
		});
        return $users;		
	}


    /**
     * comment method
     *
     * @return void
     */
    	public function recent_comment($image){
    		$client_id = Configure::read('Instagram.tabiatejahan.client_id');
    		https://api.instagram.com/v1/media/{media-id}}/comments?access_token=ACCESS-TOKEN
	        $html = file_get_contents('https://api.instagram.com/v1/media/' . $image . '/comments?client_id=' . $client_id);
	        $decode = json_decode($html);
	        $comments = $decode->data;

	        
	        // foreach ($comments as $key => $comment) {	        	
	        // 	$user = $comment->from;
	        // 	$users[] = $this->user($user);
	        // }

	        return $comments;    		
    	}



/**
     * user method
     *
     * @return void
     */
        public function user($obj, $created_time){
        	
            // $counts = $this->count($obj->counts, $obj->id);
            $User = array(
                'User'=>array(
					'id'              => $obj->id,
					'username'        => $obj->username,
					'bio'             => isset($obj->bio) ? $obj->bio : null,
					'website'         => isset($obj->website) ? $obj->website : null,
					'website'         => isset($obj->profile_picture) ? $obj->profile_picture : null,
					'profile_picture' => isset($obj->profile_picture) ? $obj->profile_picture : null,
					'isVerified'      => isset($obj->isVerified) ? $obj->isVerified : null,
					'created_time'    => $created_time,
                    // 'Count'           => $counts,
                ),
            );
            return $User;
        }



}