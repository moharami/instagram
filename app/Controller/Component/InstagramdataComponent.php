<?php

App::uses('Component', 'Controller');
App::import('Vendor', 'simple_html_dom', array('file'=>'simple_html_dom.php'));

class InstagramdataComponent extends Component {



/**
 * search_user method
*   username => 'moharamiamir'
*   profile_picture => 'http://photos-d.ak.instagram.com/hphotos-ak-xfp1/t51.2885-19/10299697_303154483195603_900956499_a.jpg'
*   id => '1391798568'
*   full_name => 'Amir Moharami'
 *
 * @return void
 */
    public function user($instagram , $user_name, $field){
        $user = $instagram->searchUser($user_name);
        $user = json_decode($user); 
        $user = $user->data[0];
        return $user->$field;        
    }



    /**
     * follower method
     *
     * @return void
     */
        public function follower($instagram,$user_id){
            $follower = $instagram->getuserfollower();
            $follower = json_decode($follower);
            debug($follower);
            die();
            
        }









/**
 * like method
 *
 * @return void
 */
    public function like($media_id, $access_token){
        $url = "https://instagram.com/web/likes/". $media_id . "/like/";
        $fields = array(
            'access_token'       =>      $access_token,
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        debug($response);
        die();
        curl_close($ch);

        echo $response; 

        
    }






}