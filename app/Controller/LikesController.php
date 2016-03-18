<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::import('Vendor', 'Instagram');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class LikesController extends AppController {

/**
 * Components
 *
 * @var array
 */
      public  $components = array('Datains','Paginator', 'Curl');


/**
 * add method
 *
 * @return void
 */
  public function index(){
   // require_once 'Instagram.php';
  	App::import('Vendor', 'Instagram');

  	$config = array(
		'client_id'     => '8d1dd48e8afb42dd8f85596b1de1a34d',
		'client_secret' => 'b1783130b7a246ba89fb9858c26a2ac9',
		'grant_type'    => 'authorization_code',
		'redirect_uri'  => 'http://boghokarna.ir/instagram/Likes/callback',
     );

	session_start();
	if (isset($_SESSION['InstagramAccessToken']) && !empty($_SESSION['InstagramAccessToken'])) {
	    header('Location:'. $config['redirect_uri']);
	    die();
	}

	// Instantiate the API handler object
	$instagram = new Instagram($config);
	$instagram->openAuthorizationUrl();


	}





	/**
	 * callback method
	 *
	 * @return void
	 */
		public function callback(){
		  	$config = array(
				'client_id'     => '8d1dd48e8afb42dd8f85596b1de1a34d',
				'client_secret' => 'b1783130b7a246ba89fb9858c26a2ac9',
				'grant_type'    => 'authorization_code',
				'redirect_uri'  => 'http://boghokarna.ir/instagram/Likes/callback',
		     );

			// Instantiate the API handler object
			$instagram = new Instagram($config);
			$accessToken = $instagram->getAccessToken();
			$_SESSION['InstagramAccessToken'] = $accessToken;
			$instagram->setAccessToken($_SESSION['InstagramAccessToken']);
			$user_name = 'afsaneh97807';
			$user_id = $this->get_user_id($instagram,$user_name);

			
			$this->like_all_media($instagram,$user_id);

			// $follower = $instagram->modifyUserRelationship('331891493','unfollow');
			
			// $this->readable($follower);	
			// die();		
			// $next = $this->get_data('https://api.instagram.com/v1/users/331891493/followed-by?access_token=2131245424.8d1dd48.faefeb91e49c4256b710ffd7ec5a8e16&cursor=1431036535858');
			// $this->readable($next);
			
			
		}

		/**
		 * get_user_id method
		 *
		 * @return void
		 */
			public function get_user_id($instagram,$user_name){
				$instagram->setAccessToken($_SESSION['InstagramAccessToken']);
				$user_data = $instagram->searchUser($user_name);
				$user_data = $this->readable($user_data);
				$user_id = $user_data->data[0]->id;
				return $user_id;				
			}


		/**
		 * readable method
		 *
		 * @return void
		 */
			public function readable($data){
				$data = json_decode($data);
				return $data;
			}



		function get_data($url, $proxy=null) {
			$ch      = curl_init();
			$timeout = 7;
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    // if (isset($proxy)) {
		    // 	curl_setopt($ch, CURLOPT_PROXY, $proxy);    	
		    // }
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		    $data = curl_exec($ch);
		    curl_close($ch);
			return $data;
		    
		}





/**
 * test method
 *
 * @return void
 */
	public function test(){
		
	}


/**
 * like_all_media method
 *
 * @return void
 */
	public function like_all_media($instagram,$user_id){
		// $data = $instagram->getUserRecent($user_id);
		// $data = $this->readable($data);
		// $images = $this->image_id($data);
		$images = array(			 
			 15 => '980190335898520634',
			 16 => '980197547233832806',
			 17 => '980196115366322138',
			 18 => '980183013206566443',
			 19 => '979810488909074596',
			 20 => '979893763375870890',
			 21 => '979851402065680721',
			 22 => '979828547953383110',
	);
		foreach ($images as $key => $image) {
			$this->like($image);
		}
		debug('done all ');
		die();

		
	}





	/**
	 * image_id method
	 *
	 * @return void
	 */
		public function image_id($data){
			$d = $data->data;
			foreach ($d as $key => $item) {
				$images[] = $item->id;
			}
			foreach ($images as $key => $image) {
				$pos = strpos($image, '_');
				$img = substr($image, 0,$pos);
				$imgs[] = $img;
			}
			return $imgs;
			
		}









 function like($id){
            $url = 'https://instagram.com/web/likes/' . $id . '/like/';
            $headers = array(
                'Host: instagram.com', 
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 
                'Accept: */*', 
                'Accept-Language: en-US,en;q=0.5', 
                'Accept-Encoding: gzip, deflate', 
                'X-Instagram-AJAX: 1', 
                'X-CSRFToken:637923ba13773ba37fad3ea006e59fdd', 
                'X-Requested-With: XMLHttpRequest', 
                'Pragma: no-cache', 
                'Cache-Control: no-cache', 
                'Referer: https://instagram.com/p/2Yv3Q0E9MX/', 
                'Cookie: mid=U3T87wAEAAFzm2Q-ppNwoSj6yXfo; __utma=1.997969661.1431024844.1431024844.1431024844.1; __utmz=1.1431024844.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); fbm_124024574287414=; csrftoken=637923ba13773ba37fad3ea006e59fdd; __utma=227057989.569022379.1431024803.1431024803.1431024803.1; __utmb=227057989.1.10.1431024803; __utmc=227057989; __utmz=227057989.1431024803.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); sessionid=IGSC4b77d276fc1af936dda243905db2a9433b18e4b3be1aa54d859f03090dc61656%3AQH7CrzCTArKFnTLrPMITLc2MRumTbVGJ%3A%7B%22_token%22%3A%222131245424%3AahYD9mjMbOrFgEnbB7Glc89tHOiBiUht%3Ab69c2df380c160b0be4f691f4dc364bb8601c2c48ee49f1e75735d4ad7de1632%22%2C%22last_refreshed%22%3A1431024828.380776%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_id%22%3A2131245424%2C%22_platform%22%3A4%7D; ds_user_id=2131245424; __utmb=1.2.10.1431024844; __utmc=1', 

                'Connection: keep-alive', 
                'Content-Length: 0', 
            );
            $body = 'q=%0A++++++ig_shortcode(2Y7W1Po7Ey)+%7B%0A++++++++id%2C%0A++++++++code%2C%0A++++++++owner+%7B%0A++++++++++id%2C%0A++++++++++username%2C%0A++++++++++is_private%2C%0A++++++++++profile_pic_url%2C%0A++++++++++followed_by_viewer%2C%0A++++++++++requested_by_viewer%0A++++++++%7D%2C%0A++++++++is_video%2C%0A++++++++video_url%2C%0A++++++++shared_by_author%2C%0A++++++++date%2C%0A++++++++display_src%2C%0A++++++++location+%7B%0A++++++++++id%2C%0A++++++++++has_public_page%2C%0A++++++++++name%0A++++++++%7D%2C%0A++++++++caption%2C%0A++++++++caption_is_edited%2C%0A++++++++usertags+%7B%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%0A++++++++++++%7D%2C%0A++++++++++++position%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++likes+%7B%0A++++++++++count%2C%0A++++++++++viewer_has_liked%2C%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%2C%0A++++++++++++++followed_by_viewer%2C%0A++++++++++++++requested_by_viewer%0A++++++++++++%7D%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++comments.last(20)+%7B%0A++++++++++nodes+%7B%0A++++++++++++id%2C%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%0A++++++++++++%7D%2C%0A++++++++++++text%2C%0A++++++++++++viewer_can_delete%0A++++++++++%7D%0A++++++++%7D%0A++++++%7D%0A++++';

            $this->get_data_header($url,$headers, $body);

        }

    function get_data_header($url, $headers=null, $body) {
        $ch      = curl_init();
        $timeout = 7;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        print_r($data);
        curl_close($ch);
        return $data;
        
    }





}
