<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
// App::import('Vendor', 'Instagram');
// App::import('Vendor', 'Instagram/auth');
// App::import('Vendor', 'Instagram/Instagram');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class InstagramsController extends AppController {

/**
 * Components
 *
 * @var array
 */
    // public  $components = array('Datains','Paginator', 'Curl','Instagram', 'Instagram2','Instagramdata');





/**
 * index method
 *
 * @return void
 */
  public function index(){    
    $this->set_return($this->request->here);    
    $this->get_access();
    $d            = $this->Session->read('accessToken');
    $instagram    = new Instagram\Instagram($d);
    $current_user = $instagram->getCurrentUser();
    debug($current_user);
    die();
    
    $full_name = $current_user->full_name;
    $username = $current_user->username;
    $bio = $current_user->bio;
    debug($current_user->counts->media);
    die();

    
  }



/**
 *  method
 *
 * @return void
 */
  public function inits(){
    $d = $this->Session->read('accessToken');
    $instagram = new Instagram\Instagram($d);
    return $instagram;    
  }


/**
 * set_return method
 *
 * @return void
 */
  public function set_return($request_url){
      $request_url = FULL_BASE_URL . $request_url ;
      $this->Session->write('return', $request_url);
      return;    
  }




/**
 * get_access method
 *
 * @return void
 */
  public function get_access(){
    $d = $this->Session->read('accessToken');
    if (!isset($d)) {
      $auth_config = array(
                'client_id'         => '8d1dd48e8afb42dd8f85596b1de1a34d',
                'client_secret'     => 'b1783130b7a246ba89fb9858c26a2ac9',
                'redirect_uri'      => 'http://www.boghokarna.ir/instagram/Instagrams/callback',
                'scope'             => array( 'likes', 'comments', 'relationships' )
            );

      $auth = new Instagram\Auth( $auth_config );
      $auth->authorize();      
    }
    
  }





/**
 * callback method
 *
 * @return void
 */
  public function callback(){
    $code = $this->request->query['code'];
    $auth_config = array(
        'client_id'         => '8d1dd48e8afb42dd8f85596b1de1a34d',
        'client_secret'     => 'b1783130b7a246ba89fb9858c26a2ac9',
        'redirect_uri'      => 'http://www.boghokarna.ir/instagram/Instagrams/callback',
        'scope'             => array( 'likes', 'comments', 'relationships' )
    );

    $auth = new Instagram\Auth( $auth_config );
    $accessToken = $auth->getAccessToken($code);
    $this->Session->write('accessToken', $accessToken);
    $instagram = new Instagram;
    $instagram->setAccessToken( $accessToken );
    $this->redirect($this->Session->read('return'));    
  }




/**
 * images method
 *
 * @return void
 */
  public function first_images($username){
      $this->set_return($this->request->here);    
      $this->get_access();
      $instagram = $this->inits();
      $user = $instagram->getUserByUsername($username);
      $counts = $user->counts->media;
      $images = $user->getMedia(
          array( 'count' => 30 )
      );
      foreach ($images as $key => $image) {
        $images2[] = $image->id;
      }
      return $images2;    
  }




/**
 * like method
 *
 * @return void
 */
  public function like_username($username){
    $images = $this->first_images($username);
    foreach ($images as $key => $image) {
      $this->like($image);
    }
    
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
