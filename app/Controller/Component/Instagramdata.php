<?php

App::uses('Component', 'Controller');
App::import('Vendor', 'simple_html_dom', array('file'=>'simple_html_dom.php'));

class InstagramdataComponent extends Component {



/**
 * search_user method
 *
 * @return void
 */
    public function user($instagram , $user_name, $field){
        $user = $instagram->searchUser($user_name);
        $user = json_decode($user);
        debug($user);
        die();
        
    }


}