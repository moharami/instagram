<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::import('Vendor', 'Instagram');


// 3 part 
  // 1- Colecct image   we do this by update_new_images function
  // 2 - Put User In User Table and  ready for follow  (update_users function)
  // 3- Follow User


// **************** Guide ************************

  // 1 - we save new image for each Field
  
  // 2 -  we collect new users for each field
        // 1 - in actor table we collect best page for collect image related to each field
        // 2 - we add new new image for ech page to user media
        // 3 - we select last image added in user media and then collect commnet for each media and save in user table with created time

  
  // 3 - we follow users
        // 1- we follow last user in user table and like x photo of them


// **************** Guide ************************
















// App::import('Vendor', 'Instagram/auth');
// App::import('Vendor', 'Instagram/Instagram');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public  $components = array('Datains','Paginator', 'Curl','Instagram', 'Instagram2','Instagramdata');


  public $added_image = 0;
  public $count = 0;

/**
 * add method
 *
 * @return void
 */
    public function add($username = 'moharamiamir', $field_id) {    
      ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1'); 

      $result   = $this->save_user_data($username);
      debug($result);
      
      $user_id  = $result['user_id'];
      $username = $result['username'];
      $data     = $result['data']['User'];
        
      $this->User->save($data);        
        
      return true;      
    }





/**
 * update_all method
 *
 * @return void
 */
  public function update_new_images($field_id = 2){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $all = $this->User->Actor->list_actor_update_false($field_id); 
    if (empty($all)) {
      $this->User->Actor->reset($field_id);
      $this->redirect($this->referer());
    }
    foreach ($all as $key => $username) {
      debug($username);      
      $result = $this->update($username,$field_id);
      
      if($result){          
        $this->User->Actor->update($username, $field_id); 
      }
    } 

    return true;  
  }



/**
 * update method
 *
 * @return void
 */
  public function update($username, $field_id = 1){ 

    $user_id = $this->User->get_user_id($username);

    
    if ($user_id) {
      $min = $this->min($user_id, $username, $field_id);
      $max = $this->max($user_id, $username,$field_id);
      
      if ($min || $max) {
        return true;
      }elseif (!$min && !$max) {
        $result = $this->add($username,$field_id);              
        $this->redirect(array('controller' => 'Users', 'action' => 'update'),$username, $field_id);
      }
    }else{
      $result = $this->add($username,$field_id);
      // $this->redirect(array('controller' => 'Users', 'action' => 'update'),$username, $field_id);      
    }
     return $result;
  }






/**
 * min method
 *
 * @return void
 */
  public function min($user_id, $username,$field_id){
    $min_id  = $this->User->UserMedia->min($user_id, $username); 
    if ($min_id == false) {
      $this->update_media('', $user_id, $username,$field_id);      
    }
    
    while ($min_id != null) {
      $min_id = $this->update_media($min_id, $user_id, $username, $field_id);
    }   
    return true; 
  }



/**
 * max method
 *
 * @return void
 */
  public function max($user_id, $username,$field_id){
    $max_id = $this->User->UserMedia->max($user_id); 
    if ($max_id == false) {
      return false;
    }         
    while ($max_id != null) {
      $max_id = $this->load($max_id, $user_id, $username,$field_id); 
    }
    return true; 
  }



// ********************* save User data **********************
  /**
   * save_user_data method
   *
   * @return void
   */
    public function save_user_data($username){
        $server = $_SERVER['SERVER_NAME'];
        if ($server == 'localhost') {
          $data      = $this->Datains->get_user_data($username);          
           $user     = $data->entry_data->UserProfile[0]->user;
           $user     =  $this->Datains->user($user);
        }else{          
          $user      = $this->Datains->get_user_data3($username);
          if ($user === false) {
            $this->User->Actor->delete_actor($username);            
          }
        }
          // **************** User ***************
           // $count    = $user['User']['Count'];
           // $this->User->Count->create();
           // $count_id = $this->User->Count->save_count($count);
           // unset($user['User']['Count']);
           // $user['User']['count_id']= $count_id;
           $this->User->create();
           if ($this->User->save($user)) {
             $user_id  = $this->User->id;
           }
        // **************** User ***************
          $result = array(
            'data'    => $user,
            'user_id' => $user_id,
            'username' => $username,
          );
           return $result;
      
    }
// ********************* save User data **********************




// ********************* update_media ***********************
  /**
   * load method
   *
   * @return void
   */
    public function update_media($min_id, $user_id, $username,$field_id){
      
      
      $url    = 'http://instagram.com/'. $username . '/media/?min_id=' . $min_id;      
      $html   = file_get_contents($url);      
      $decode = json_decode($html);

      
      if ($decode->status  == 'ok') {
          $items = $decode->items;          
        $user_media_id =  $this->userMedia($items, $user_id, $username,$field_id);
        if ($user_media_id != null) {
          return $user_media_id;                    
        }else{
          return null;
        }        
      }else{
        return null; 
      }
      
    }
// ********************* update_media ***********************



// ********************* Load ***********************
  /**
   * load method
   *
   * @return void
   */
    public function load($max_id, $user_id, $username,$field_id){
      $url    = 'http://instagram.com/'. $username . '/media/?max_id=' . $max_id;
      $html   = file_get_contents($url);
      $decode = json_decode($html);
      if ($decode->status  == 'ok') {
          $items = $decode->items;
          $user_media_id =  $this->userMedia($items, $user_id, $username, $field_id);        
          if ($user_media_id != null) {
            return $user_media_id;                    
          }else{
            return null;
          } 
      }else{
        return null;     
      }
      
    }
// ********************* Load ***********************





// ********************* UserMedia ****************************
      /**
       * userMedia method
       *
       * @return void
       */
          public function userMedia($data, $user_id, $username, $field_id){              
              foreach ($data as $key => $item) { 
                  $created_time  = $item->created_time;
                 
                  if ($created_time < '1434578551') {
                    continue;
                  }                    
                  

                  if (isset($item->videos) && is_object($item->videos) ) {
                    $videos = $item->videos;
                  }else{
                    $videos = null;
                  }
                  
                  $media = $this->media($item, $user_id); 

                  if (!isset($media) || $media == null) {
                      continue;
                  }
                  // $this->images($item->images, $media['id'],$username, $item->caption,$field_id,$videos);
                  debug($media);                  
                  if ($this->User->UserMedia->save($media)) {    
                    $this->images($item->images, $media['id'],$username, $item->caption,$field_id,$videos);                    
                    //$this->likes($item->likes, $media['id']);                       
                    //$this->comment($item->comments, $media['id']);
                    $media_id = substr($media['id'], 0,strpos($media['id'], '_')); 
                    // if ($field_id == 1) {                
                    //   $this->new_like($media_id);                       
                    //   $this->new_comment($media_id);
                    // }                      
                  }
              }
              if (isset($media)) {
                return $media['id'];                                
              }else{
                return null;
              }
          }






// ****************** Comments *************************
      /**
       * comment method
       *
       * @return void
       */
        public function comment($data,$user_media_id){
            if (!empty($data->data)) {
                foreach ($data->data as $key => $comment) {
                    $d = array(
                        'Comment' => array(
                                'id'            => $comment->id,
                                'created_time'  => $comment->created_time,
                                'user_media_id' => $user_media_id,
                                'text'         => $comment->text,
                                'user_id'       => $comment->from->id,
                                'User'         => array(
                                    'username'        => $comment->from->username,
                                    'profile_picture' => $comment->from->profile_picture,
                                    'id'              => $comment->from->id,
                                    'full_name'       => $comment->from->full_name,
                                ),
                        ),
                    );
                    $this->User->Comment->save_comment($d);
                }
            }
            
        }
// ****************** Comments *************************





// ********************** Likes *************************
       /**
        * likes method
        *
        * @return void
        */
        public function likes($likes, $user_media_id){
            $likes = $likes->data;
            foreach ($likes as $key => $like) {
                    $data = array(
                        'Like'=>array(
                            'user_id'       => $like->id,
                            'user_media_id' => $user_media_id,
                            'User'=>array(
                                'id'              => $like->id,
                                'username'        => $like->username,
                                'profile_picture' => $like->profile_picture,
                                'full_name'       => $like->full_name,
                            )
                        )
                    );
                    $this->User->Like->create();
                    $this->User->Like->save($data);
                }
                
            }
// ********************** Likes *************************


/** in this action we want to add more like of user --- function likes - just add 20 like
 * new_like method
 *
 * @return void
 */
  public function new_like($user_media_id=null){
    $this->get_access();
    $instagram = $this->inits();
    $media = $instagram->getMedia($user_media_id);
    $likes = $media->getLikes();
    foreach ($likes as $key => $like) {
      $user = array(
                                'id'              => $like->id,
                                'username'        => $like->username,
                                'profile_picture' => $like->profile_picture,
                                'full_name'       => $like->full_name,
                                'created'         => date('Y-m-d H:i:s'),
                                'modified'        => date('Y-m-d H:i:s')
      );

      
      $this->User->save_user($user);
    }
    
  }

/**
 * new_comment method
 *
 * @return void
 */
  public function new_comment($user_media_id  = null){
    $this->get_access();
    $instagram = $this->inits();
    $media     = $instagram->getMedia($user_media_id);
    $comments  = $media->getComments();
    foreach ($comments as $key => $like) {
      $like = $like->from;
      $user = array(
                                'id'              => $like->id,
                                'username'        => $like->username,
                                'profile_picture' => $like->profile_picture,
                                'full_name'       => $like->full_name,
                                'created'         => date('Y-m-d H:i:s'),
                                'modified'        => date('Y-m-d H:i:s')
      );
      $this->User->save_user($user);
    }
    
  }








// **************************** Image ************************
        /**
         * images method
         *
         * @return void
         */
            public function images($data, $user_media_id, $username, $caption= null, $field_id, $videos = null){
                    $standard_id  = $this->standard($data);
                    $low_id       = $this->low($data);
                    $thumbnail_id = $this->thumbnail($data);
                    $d = array(
                        'Image'=>array(
                            'user_media_id'          => $user_media_id,
                            'standard_resolution_id' => $standard_id,
                            'low_resolution_id'      => $low_id,
                            'thumbnail_id'           => $thumbnail_id,
                        ),
                    );

                    // $caption = $this->caption($caption);
                    $this->User->UserMedia->Image->create();
                    $image_data = $this->User->UserMedia->Image->save_image($d); 

            // $this->copy_image($image_data, $username);
            $this->copy_new_image($image_data, $username,$caption,$field_id,$videos);
          }


          /**
           * caption method
           *
           * @return void
           */
            public function caption($caption){
              if ($caption != null) {
                  $text = $caption->text;
                  $username = $caption->from->username;
                  $result = $text;
                  return $result;
              }
              
            }



          /**
           * copy_image method
           *
           * @return void
           */
            public function copy_image($data, $username){
                $instagram_dir = WWW_ROOT . 'instagram';
                
                $d = array(
                      0 => array(
                        'Url'  => $data['StandardResolution']['url'],
                        'Path' => $instagram_dir  . DS .  'standard' . DS .  $username . DS ,
                      ),
                      // 1 => array(
                      //   'Url'  => $data['LowResolution']['url'],
                      //   'Path' => $instagram_dir  . DS .  'low' . DS .  $username . DS ,
                      // ),
                      // 2 => array(
                      //   'Url'  => $data['Thumbnail']['url'],
                      //   'Path' => $instagram_dir  . DS .  'thumbnail' . DS .  $username . DS ,
                      // ),
                );

              foreach ($d as $key => $item) {
                $name = pathinfo($item['Url'], PATHINFO_FILENAME);
                $ext = pathinfo($item['Url']);
                $ext = $ext['extension'];
                $name_ext = $name . '.' . $ext;
                debug($name_ext);
                
                if(!file_exists($item['Path'])){
                  mkdir($item['Path'], 0777, true);
                }
                $url = str_replace( "&amp;", "&", urldecode(trim($item['Url'])) );

                $content = file_get_contents($item['Url']);
                file_put_contents($item['Path'] . $name_ext, $content);
              }
              
            }


        /**
         * copy_new_image method
         *
         * @return void
         */
          public function copy_new_image($data, $username, $caption,$field_id,$videos){ 
            if ($field_id == 1) {
              $name_folders = 'honarmandir';              
            }elseif ($field_id == 2) {
              $name_folders = 'tabiatejahan';                            
            }elseif ($field_id == 3) {
              $name_folders = 'nails';                            
            }elseif ($field_id == 4) {
              $name_folders = 'selfie';                            
            }



            $instagram_dir = WWW_ROOT . 'instagram' . DS . $name_folders. DS;
            $text_file     = WWW_ROOT . 'instagram' . DS . $field_id . DS .'new.txt';
            if ($videos == null) {
              $d = array(
                    0 => array(
                      'Url'  => $data['StandardResolution']['url'],
                      'Path' => $instagram_dir,
                    ),                  
              );              
            }else{
              $d = array(
                    0 => array(
                      'Url'  => $videos->standard_resolution->url,
                      'Path' => $instagram_dir,
                    ),                  
              );
            }

          foreach ($d as $key => $item) {

            $name1 = pathinfo($item['Url'], PATHINFO_FILENAME);
              $name = $username .  $name1 ;            

              // if (isset($caption)) {
              //   $n =   $name .' > ' . $caption; 
              // }else{             
              //   $n =  $name ;
              // }
              // $n = $n . "\r\n";
              // // $file = fopen($text_file,"a");
              // // fwrite($file,$n);
              // // fclose($file); 

              $ext      = pathinfo($item['Url']);
              $ext      = $ext['extension'];
              $name_ext = $name . '.' . $ext;
          




              $url = str_replace( "&amp;", "&", urldecode(trim($item['Url'])) );

              $content = file_get_contents($item['Url']);
              if (file_put_contents($item['Path'] . $name_ext, $content)) {

              };
            
          }            
          }

        /**
         * standard method
         *
         * @return void
         */
            public function standard($data){
                    $standard_resolution = $data->standard_resolution;
                    $st = array(
                        'StandardResolution' => array(
                                'url'    => $standard_resolution->url,
                                'width'  => $standard_resolution->width,
                                'height' => $standard_resolution->height,
                        ),                  
                        );
                        $id = $this->User->UserMedia->Image->StandardResolution->save_standard($st);                    
                        return $id;
                    
          }


             /**
             * low method
             *
             * @return void
             */
                public function low($data){
                    $standard_resolution = $data->low_resolution;
                    $st = array(
                        'LowResolution' => array(
                            'url'    => $standard_resolution->url,
                            'width'  => $standard_resolution->width,
                            'height' => $standard_resolution->height,
                        ),                  
                    );
                    $id = $this->User->UserMedia->Image->LowResolution->save_low($st);                  
                    return $id;
                    
                }


            /**
             * thumbnail method
             *
             * @return void
             */
                public function thumbnail($data){
                    $standard_resolution = $data->thumbnail;
                    $st = array(
                        'Thumbnail' => array(
                            'url'    => $standard_resolution->url,
                            'width'  => $standard_resolution->width,
                            'height' => $standard_resolution->height,
                        ),                  
                    );
                    $id = $this->User->UserMedia->Image->Thumbnail->save_thumbnail($st);                    
                    return $id;
                    
                }
// **************************** Image ************************




        /**
         * media method
         *
         * @return void
         */
            public function media($item, $user_id){
              // if (!empty($item->location) && isset($item->location)) {
              //     $this->User->UserMedia->Location->save_location($item->location);    
              // }else{
              //   $location = null;
              // }

              $location = null;

                $user_media = array(
                    'id'                  => $item->id,
                    'user_id'             => $user_id,
                    'code'                => $item->code,
                    'type'                => $item->type,
                    'link'                => $item->link,
                    // 'caption'             => $item->caption,
                    'alt_media_url'       => $item->alt_media_url,
                    // 'location'            => $location,
                    'can_view_comments'   => $item->can_view_comments,
                    'can_delete_comments' => $item->can_delete_comments,
                    'created_time'        => $item->created_time
                );
                $exist = $this->User->UserMedia->findById($item->id);
                if (!empty($exist)) {
                  return false;
                }else{
                  return $user_media;
                }
                
            }
// ********************* UserMedia ****************************




function get_fcontent( $url,  $javascript_loop = 0, $timeout = 5 ) {
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    if ($response['http_code'] == 301 || $response['http_code'] == 302) {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) ) {
            foreach( $headers as $value ) {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
        return get_url( $value[1], $javascript_loop+1 );
    } else {
        return array( $content, $response );
    }
}



/**
 * zip method
 *
 * @return void
 */
  public function zip(){
    $images =  WWW_ROOT . 'instagram' . DS . 'new-image'. DS ;
    //this folder must be writeable by the server
    $backup =  WWW_ROOT . 'instagram' . DS . 'zip';
    $zip_file = $backup. DS . 'backup.zip';

    if ($handle = opendir($images))  
      {
          $zip = new ZipArchive();

          if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) 
          {
              exit("cannot open <$filename>\n");
          }

          while (false !== ($file = readdir($handle))) 
          {
            debug($file);
            
              $zip->addFile($images.$file);
              
          }
          closedir($handle);
          echo "numfiles: " . $zip->numFiles . "\n";
          echo "status:" . $zip->status . "\n";
          $zip->close();
          echo 'Zip File:'.$zip_file . "\n";
      }
    
  }



/**
 * create_zip method
 *
 * @return void
 */
  public function create_zip($rootPath){
    // Get real path for our folder
    
    //this folder must be writeable by the server
    $backup   =  WWW_ROOT . 'instagram' . DS . 'zip';
    $zip_file = $backup. DS . date('Y-m-d-H-i-s') . '.zip';

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
    return $zip_file;
    
  }


/**
 * delete_file method
 *
 * @return void
 */
  public function delete_file($files){
      foreach (new DirectoryIterator($files) as $fileInfo) {
          if(!$fileInfo->isDot()) {
              unlink($fileInfo->getPathname());
          }
      }    
  }































/**
 * liker method
 *
 * @return void
 */
  public function liker(){
    $this->add('alifatehi');
    $this->User->UserMedia->recursive = -1;
    $conditions = array(
      'conditions'  => array(
        'like' => false,
      ),
      'fields'    => array(
        'id',
      ),   
      'limit' => 5,       
    );
    $media     = $this->User->UserMedia->find('all',$conditions);

    foreach ($media as $key => $item) {
      $media_id = $item['UserMedia']['id'];
      $this->like($media_id);
    }



    // $username = $user['User']['username'];
    // // $username = 'moharamiamir';
    // $images   = $this->first_images('alifatehi');

    // $this->User->UserMedia->save_images($images);    
}


/**
 * sleep($field_id) method
 *
 * @return void
 */
  public function sleep($field_id){
    if ($field_id == 1) {
      $rand = rand(1,5);
    }elseif ($field_id == 4) {      
      $rand = rand(34,40);
    }else{
      $rand = rand(7,11);
    }
    sleep($rand);    
  }

/**
 * persuasion method
 *
 * @return void
 */
  public function targhib($field_id = 1,$count_user = 2,$count_like = 2){
    $this->sleep($field_id);
    date_default_timezone_set('Asia/Tehran');
    $conditions = array(
      'conditions'=>array(
        'relationship_count'=>0,
        'private'=> false
      ),   
      'fields'    => array(
        'id',
        'username',
        'created_time',
        'relationship_count',
      ),      
      'order'=>'created_time desc',
      'limit'=> $count_user,      
    );
    $this->User->recursive = 1;
    $users = $this->User->find('all',$conditions);
    debug($users);
    
    foreach ($users as $key => $user) { 
      $user_id      = $user['User']['id'];
      $username     = $user['User']['username'];
      debug($user_id);
      debug($username);
      
      $user_rel = $this->user_rel($user_id, $field_id,null);
      debug($user_rel);
      
      $private  = $user_rel->target_user_is_private; 
      
      // 1 - follow

      $this->do_follow($user_id,$username,$field_id);
      $this->do_like($user_id,$username,$field_id,$count_like,$private);
      sleep(23);
    }
    
  }

/**
 * do_like method
 *
 * @return void
 */
  public function do_like($user_id,$username,$field_id,$count_like,$private){                   
        if (!$private) {
          $result = $this->recent_user_media($user_id,$count_like);
          if (!empty($result)) {
            foreach ($result as $key => $item) {
              $result  = $this->like($item,$field_id);
              if ($result) {
                $relationship = array(
                  'Relationship' => array(
                    'relationship_type_id' => 3,
                    'user_id'              => $user_id,
                    'field_id'             => $field_id,
                    'relation_time'        => date('Y-m-d H:i:s'),
                    'description'          => null,
                    // 'outgoing'             => $outgoing
                  )
                );
                $this->User->Relationship->create();
                $this->User->Relationship->save($relationship);
                sleep(3);
              }
          }
          }else{
            $relationship = array(
            'Relationship' => array(
                'relationship_type_id' => 3,
                'user_id'              => $user_id,
                'field_id'             => $field_id,
                'relation_time'        => date('Y-m-d H:i:s'),
                'description'          => null,
                'outgoing'             => 'no image to like'
              )
            );
            $this->User->Relationship->create();
            $this->User->Relationship->save($relationship);
          }          
        }else{
          $this->User->id = $user_id;
          $this->User->saveField('private', true);          
        }                 
  }


/**
 * do_follow method
 *
 * @return void
 */
  public function do_follow($user_id, $username,$field_id = 1){
          
          
          $result = $this->follow($user_id,$field_id);
          debug($result);          
          if ($result === true) {                        
            $this->write_temp_file($field_id,true,$username);

            $outgoing = $this->user_rel($user_id, $field_id, 'outgoing_status');
            $relationship = array(
              'Relationship' => array(
                'relationship_type_id' => '1',
                'user_id'              => $user_id,
                'field_id'             => $field_id,
                'relation_time'        => date('Y-m-d H:i:s'),
                'description'          => null,
                'outgoing'             => $outgoing
              )
            );     
            $this->User->Relationship->create();
            $this->User->Relationship->save($relationship);
            $this->Session->setFlash(__d('Behandam', 'username => ' . $username . '  followed successfull'),'default',array('class'=>'success'));                               
          }else{
            $this->write_temp_file($field_id,false, $username);
          }        

  }


/**
 * write_temp_file method
 *
 * @return void
 */
  public function write_temp_file($field_id, $status = 1, $username, $unfollow = false){
    $tmp_file = $this->temp_file($field_id, $unfollow);
    
    if ($status) {
      $status_word = 'success =>';      
    }else{
      $status_word = 'error => ';      
    }

    CakeLog::write($tmp_file,  $status_word . ' ' . $username); 
    
  }



/**
 * temp_file method
 *
 * @return void
 */
  public function temp_file($field_id, $unfollow){
    if (!$unfollow) {
      if ($field_id == 1) {
        $tmp_file = 'honarmandir_follow_instagram';            
      }elseif ($field_id == 2) {
        $tmp_file = 'tabiate_jahan_follow_instagram';                  
      }elseif ($field_id == 4) {
        $tmp_file = 'akse3lfi_follow_instagram';                  
      }elseif ($field_id == 5) {
        $tmp_file = 'ninijan_follow_instagram';                  
      }
      
    }else{
      if ($field_id == 1) {
        $tmp_file = 'honarmandanir_unfollow_instagram';            
      }elseif ($field_id == 2) {
        $tmp_file = 'tabiate_jahan_unfollow_instagram';                  
      }elseif ($field_id == 4) {
        $tmp_file = 'akse3lfi_unfollow_instagram';                  
      }elseif ($field_id == 5) {
        $tmp_file = 'ninijan_unfollow_instagram';                  
      }
    }
    

    return $tmp_file;
    
  }




/**
 * unfollow method
 *
 * @return void
 */
  public function unfollow2($user_id){
    $access_token = '2131245424.8d1dd48.faefeb91e49c4256b710ffd7ec5a8e16';
    $url = 'https://api.instagram.com/v1/users/'.$user_id.'/friendships?access_token='.$access_token . '&ACTION=unfollow';
    $answer = $this->get_data($url);
    $answer = json_decode($answer);
    debug($answer); 
    die();   
    $code = $answer->meta->code;
    if ($code == 200) {
      return true;
    }else{
      return false;      
    }
    
  }



/**
 * follow method
 *
 * @return void
 */
  public function follow($id,$field_id = 1){
            $url = 'https://instagram.com/web/friendships/' . $id . '/follow/';

            
            $CSRFToken = $this->scrftoken($field_id);
            $cookie = $this->cookie($field_id);


            $headers = array(
                'Host: instagram.com', 
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 
                'Accept: */*', 
                'Accept-Language: en-US,en;q=0.5', 
                'Accept-Encoding: gzip, deflate', 
                'X-Instagram-AJAX: 1', 
                'X-CSRFToken:'.$CSRFToken, 
                'X-Requested-With: XMLHttpRequest', 
                'Pragma: no-cache', 
                'Cache-Control: no-cache', 
                'Referer: https://instagram.com/p/2Yv3Q0E9MX/', 
                'Cookie:'.$cookie, 

                'Connection: keep-alive', 
                'Content-Length: 0', 
            );
            $body = 'q=%0A++++++ig_shortcode(2Y7W1Po7Ey)+%7B%0A++++++++id%2C%0A++++++++code%2C%0A++++++++owner+%7B%0A++++++++++id%2C%0A++++++++++username%2C%0A++++++++++is_private%2C%0A++++++++++profile_pic_url%2C%0A++++++++++followed_by_viewer%2C%0A++++++++++requested_by_viewer%0A++++++++%7D%2C%0A++++++++is_video%2C%0A++++++++video_url%2C%0A++++++++shared_by_author%2C%0A++++++++date%2C%0A++++++++display_src%2C%0A++++++++location+%7B%0A++++++++++id%2C%0A++++++++++has_public_page%2C%0A++++++++++name%0A++++++++%7D%2C%0A++++++++caption%2C%0A++++++++caption_is_edited%2C%0A++++++++usertags+%7B%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%0A++++++++++++%7D%2C%0A++++++++++++position%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++likes+%7B%0A++++++++++count%2C%0A++++++++++viewer_has_liked%2C%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%2C%0A++++++++++++++followed_by_viewer%2C%0A++++++++++++++requested_by_viewer%0A++++++++++++%7D%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++comments.last(20)+%7B%0A++++++++++nodes+%7B%0A++++++++++++id%2C%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%0A++++++++++++%7D%2C%0A++++++++++++text%2C%0A++++++++++++viewer_can_delete%0A++++++++++%7D%0A++++++++%7D%0A++++++%7D%0A++++';

            $result = $this->get_data_header($url,$headers, $body);
            return $result;          
}



/**
 * follow_tabiate_jahan method
 *
 * @return void
 */
  public function follow_tabiate_jahan($id){
    $url = 'https://instagram.com/web/friendships/' . $id . '/follow/';
            $headers = array(
                'Host: instagram.com', 
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 
                'Accept: */*', 
                'Accept-Language: en-US,en;q=0.5', 
                'Accept-Encoding: gzip, deflate', 
                'X-Instagram-AJAX: 1', 
                'X-CSRFToken:fb762d34a19f56740c24adf585748367', 
                'X-Requested-With: XMLHttpRequest', 
                'Pragma: no-cache', 
                'Cache-Control: no-cache', 
                'Referer: https://instagram.com/p/2Yv3Q0E9MX/', 
                'Cookie: mid=VU36FwAEAAFVbmE_tw0j--q9jM2C; __utma=227057989.733228142.1431173769.1433055606.1433831784.3; __utmz=227057989.1431173769.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); sessionid=IGSC9ac4bef0c3b0b341789328cc0ee59f637aca9df889aa2a5e66a9f4d135498eba%3A4hl0VMo13jisrQvEVkZ88v5SEueHbZva%3A%7B%22_token%22%3A%222080951062%3A4veuv3qxGLI8kXqOd9KA9oXYo23A1KT7%3A9217cdcd4db4abf89d6fccdac0bc24af8ac9a831f6a4092e59107890b5fdf221%22%2C%22last_refreshed%22%3A1433831791.048309%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_id%22%3A2080951062%2C%22_platform%22%3A4%7D; __utma=1.227445856.1432217945.1432710222.1433831743.5; __utmz=1.1432217945.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmb=1.4.10.1433831743; __utmt=1; __utmc=1; csrftoken=fb762d34a19f56740c24adf585748367; __utmb=227057989.1.10.1433831784; __utmc=227057989; ds_user_id=2080951062', 

                'Connection: keep-alive', 
                'Content-Length: 0', 
            );
            $body = 'q=%0A++++++ig_shortcode(2Y7W1Po7Ey)+%7B%0A++++++++id%2C%0A++++++++code%2C%0A++++++++owner+%7B%0A++++++++++id%2C%0A++++++++++username%2C%0A++++++++++is_private%2C%0A++++++++++profile_pic_url%2C%0A++++++++++followed_by_viewer%2C%0A++++++++++requested_by_viewer%0A++++++++%7D%2C%0A++++++++is_video%2C%0A++++++++video_url%2C%0A++++++++shared_by_author%2C%0A++++++++date%2C%0A++++++++display_src%2C%0A++++++++location+%7B%0A++++++++++id%2C%0A++++++++++has_public_page%2C%0A++++++++++name%0A++++++++%7D%2C%0A++++++++caption%2C%0A++++++++caption_is_edited%2C%0A++++++++usertags+%7B%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%0A++++++++++++%7D%2C%0A++++++++++++position%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++likes+%7B%0A++++++++++count%2C%0A++++++++++viewer_has_liked%2C%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%2C%0A++++++++++++++followed_by_viewer%2C%0A++++++++++++++requested_by_viewer%0A++++++++++++%7D%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++comments.last(20)+%7B%0A++++++++++nodes+%7B%0A++++++++++++id%2C%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%0A++++++++++++%7D%2C%0A++++++++++++text%2C%0A++++++++++++viewer_can_delete%0A++++++++++%7D%0A++++++++%7D%0A++++++%7D%0A++++';

            $result = $this->get_data_header($url,$headers, $body);
            return $result;    
  }





/**
 * follow method
 *
 * @return void
 */
  public function unfollow($id, $field_id){

            $url = 'https://instagram.com/web/friendships/' . $id . '/unfollow/';

            $CSRFToken = $this->scrftoken($field_id);
            $cookie = $this->cookie($field_id);

            
            $headers = array(
                'Host: instagram.com', 
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 
                'Accept: */*', 
                'Accept-Language: en-US,en;q=0.5', 
                'Accept-Encoding: gzip, deflate', 
                'X-Instagram-AJAX: 1', 
                'X-CSRFToken:'.$CSRFToken,  
                'X-Requested-With: XMLHttpRequest', 
                'Pragma: no-cache', 
                'Cache-Control: no-cache', 
                'Referer: https://instagram.com/p/2Yv3Q0E9MX/', 
                'Cookie:'.$cookie,

                'Connection: keep-alive', 
                'Content-Length: 0', 
            );
            $body = 'q=%0A++++++ig_shortcode(2Y7W1Po7Ey)+%7B%0A++++++++id%2C%0A++++++++code%2C%0A++++++++owner+%7B%0A++++++++++id%2C%0A++++++++++username%2C%0A++++++++++is_private%2C%0A++++++++++profile_pic_url%2C%0A++++++++++followed_by_viewer%2C%0A++++++++++requested_by_viewer%0A++++++++%7D%2C%0A++++++++is_video%2C%0A++++++++video_url%2C%0A++++++++shared_by_author%2C%0A++++++++date%2C%0A++++++++display_src%2C%0A++++++++location+%7B%0A++++++++++id%2C%0A++++++++++has_public_page%2C%0A++++++++++name%0A++++++++%7D%2C%0A++++++++caption%2C%0A++++++++caption_is_edited%2C%0A++++++++usertags+%7B%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%0A++++++++++++%7D%2C%0A++++++++++++position%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++likes+%7B%0A++++++++++count%2C%0A++++++++++viewer_has_liked%2C%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%2C%0A++++++++++++++followed_by_viewer%2C%0A++++++++++++++requested_by_viewer%0A++++++++++++%7D%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++comments.last(20)+%7B%0A++++++++++nodes+%7B%0A++++++++++++id%2C%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%0A++++++++++++%7D%2C%0A++++++++++++text%2C%0A++++++++++++viewer_can_delete%0A++++++++++%7D%0A++++++++%7D%0A++++++%7D%0A++++';

            $result = $this->get_data_header($url,$headers, $body);
            return $result;
          
          }


/**
 * add_actor method
 *
 * @return void
 */
  public function add_actor(){
    $fields = $this->User->Actor->Field->find('list');    
    $this->set(compact('fields'));
    if (!empty($this->request->data)) {
      $field_id  = intval($this->request->data['User']['field_id']);
      $username  = $this->request->data['user_name'];
      $user_data = $this->Datains->get_user_data3($username);
      $user_id   = $user_data['User']['id'];
      $result    = $this->User->Actor->add($username, $field_id, $user_id);
      $this->Session->setFlash(__d('Behandam', $result),'default',array('class'=>'success'));
    }
    
    
  }




/**
 * index method
 *
 * @return void
 */
  public function index(){    
    // $this->set_return($this->request->here);    
    // $this->get_access();
    // $d = $this->Session->read('accessToken');
    // $instagram = new Instagram\Instagram($d);
    // $current_user = $instagram->getCurrentUser();
    
    // $full_name = $current_user->full_name;
    // $username = $current_user->username;
    // $bio = $current_user->bio;
    // debug($current_user->counts->media);
    // die();

    
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
  public function get_access($field_id = 1){

    // $accessToken = Configure::read('Instagram.access_token');
    // $this->Session->write('accessToken', $accessToken);

    // $d = $this->Session->read('accessToken');
    if (!isset($d)) {
      $auth_config  = $this->auth_config($field_id);
      $auth = new Instagram\Auth( $auth_config );
      $auth->authorize();      
    }
    
  }


/**
 * auth_config method
 *
 * @return void
 */
  public function auth_config($field_id){
    debug($field_id);
    
    if ($field_id == 1) {
      $auth_config = array(
                    'client_id'         => Configure::read('Instagram.golandam.honarmandan.client_id'),
                    'client_secret'     => Configure::read('Instagram.golandam.honarmandan.client_secret'),
                    'redirect_uri'      => Configure::read('Instagram.golandam.honarmandan.callback_url'),
                    'scope'             => array( 'likes', 'comments', 'relationships' )
                );
    }elseif ($field_id == 2) {
      $auth_config = array(
                    'client_id'         => Configure::read('Instagram.tabiatejahan.client_id'),
                    'client_secret'     => Configure::read('Instagram.tabiatejahan.client_secret'),
                    'redirect_uri'      => Configure::read('Instagram.tabiatejahan.callback_url'),
                    'scope'             => array( 'likes', 'comments', 'relationships' )
                );
      
    }elseif ($field_id == 4) {
      $auth_config = array(
                    'client_id'         => Configure::read('Instagram.akse3lfi.client_id'),
                    'client_secret'     => Configure::read('Instagram.akse3lfi.client_secret'),
                    'redirect_uri'      => Configure::read('Instagram.akse3lfi.callback_url'),
                    'scope'             => array( 'likes', 'comments', 'relationships' )
                );
      
    }
    return $auth_config;
  }



/**
 * get_access_honarmandanir method
 *
 * @return void
 */
  public function get_access_honarmandanir(){
        // $accessToken = Configure::read('Instagram.access_token');
        // $this->Session->write('accessToken', $accessToken);
        // $d = $this->Session->read('accessToken');
        if (!isset($d)) {
          $auth_config = array(
                    'client_id'         => Configure::read('Instagram.honarmandan.client_id'),
                    'client_secret'     => Configure::read('Instagram.honarmandan.client_secret'),
                    'redirect_uri'      => Configure::read('Instagram.honarmandan.callback_url'),
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
  public function callback($field_id){
    $code = $this->request->query['code'];
    $auth_config  = $this->auth_config($field_id);
    

    $auth = new Instagram\Auth( $auth_config );
    $accessToken = $auth->getAccessToken($code);
    debug($accessToken);
    die();

    $this->Session->write('accessToken', $accessToken);
    $instagram = new Instagram;
    $instagram->setAccessToken( $accessToken );
    $this->redirect($this->Session->read('return'));    
  }






/**
 * get_access_field_id_2 method
 *
 * @return void
 */
  public function get_access_tabiate_jahan(){
    $access_token_tabiate_jahan = Configure::read('Instagram.access_token_tabiate_jahan');    
    $this->Session->write('access_token_tabiate_jahan', $access_token_tabiate_jahan);
    // if (!isset($d)) {
    //   $auth_config = array(
    //             'client_id'         => 'c21a0d5449cc43339859a558045a5a6d',
    //             'client_secret'     => '1ddcf89f519d4009a02f837eb2ffddda',
    //             'redirect_uri'      => 'http://www.boghokarna.ir/instagram/Users/call_back_tabiate_jahan',
    //             'scope'             => array('likes', 'comments', 'relationships')
    //         );

    //   $auth = new Instagram\Auth( $auth_config );
    //   $auth->authorize();      
    // }
    
  }





/**
 * call_back_field_id_1 method
 *
 * @return void
 */
  public function call_back_tabiate_jahan(){
    $code = $this->request->query['code'];
    $auth_config = array(
        'client_id'         => 'c21a0d5449cc43339859a558045a5a6d',
        'client_secret'     => '1ddcf89f519d4009a02f837eb2ffddda',
        'redirect_uri'      => 'http://www.boghokarna.ir/instagram/Users/call_back_tabiate_jahan',
        'scope'             => array( 'likes', 'comments', 'relationships' )
    );

    $auth = new Instagram\Auth( $auth_config );
    $accessToken_tabiate_jahan = $auth->getAccessToken($code);
    echo $accessToken_tabiate_jahan;
    $this->Session->write('accessToken_tabiate_jahan', $accessToken_tabiate_jahan);
    // $instagram = new Instagram;
    // $instagram->setAccessToken($accessToken_tabiate_jahan);
    // $this->redirect($this->Session->read('return'));  
    
  }




/**
 * images method
 *
 * @return void
 */
  public function first_images($username){
    // $username = 'moharamiamir';
      $this->set_return($this->request->here);    
      $this->get_access();
      $instagram = $this->inits();
      $user = $instagram->getUserByUsername($username);
      $private = $instagram->isUserPrivate($user->id);
      if (!$private) {
        $images2 = $user->getMedia(
          array( 'count' => 30 )
      );
        foreach ($images2 as $key => $image) {
          $img_id[$key]['id']           = $image->id;
          $img_id[$key]['user_id']      = $user->id;
          $img_id[$key]['type']         = $image->type;
          $img_id[$key]['created_time'] = $image->created_time;
          $img_id[$key]['like']         = false;
        }

      return $img_id; 
      }
         
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




function like($id, $field_id){
            $url = 'https://instagram.com/web/likes/' . $id . '/like/';
            $CSRFToken = $this->scrftoken($field_id);
            $cookie = $this->cookie($field_id);
            $headers = array(
                'Host: instagram.com', 
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 
                'Accept: */*', 
                'Accept-Language: en-US,en;q=0.5', 
                'Accept-Encoding: gzip, deflate', 
                'X-Instagram-AJAX: 1',                 
                'X-CSRFToken:'.$CSRFToken, 
                'X-Requested-With: XMLHttpRequest', 
                'Pragma: no-cache', 
                'Cache-Control: no-cache', 
                'Referer: https://instagram.com/p/2Yv3Q0E9MX/', 
                'Cookie:'.$cookie,
                'Connection: keep-alive', 
                'Content-Length: 0', 
            );
            $body = 'q=%0A++++++ig_shortcode(2Y7W1Po7Ey)+%7B%0A++++++++id%2C%0A++++++++code%2C%0A++++++++owner+%7B%0A++++++++++id%2C%0A++++++++++username%2C%0A++++++++++is_private%2C%0A++++++++++profile_pic_url%2C%0A++++++++++followed_by_viewer%2C%0A++++++++++requested_by_viewer%0A++++++++%7D%2C%0A++++++++is_video%2C%0A++++++++video_url%2C%0A++++++++shared_by_author%2C%0A++++++++date%2C%0A++++++++display_src%2C%0A++++++++location+%7B%0A++++++++++id%2C%0A++++++++++has_public_page%2C%0A++++++++++name%0A++++++++%7D%2C%0A++++++++caption%2C%0A++++++++caption_is_edited%2C%0A++++++++usertags+%7B%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%0A++++++++++++%7D%2C%0A++++++++++++position%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++likes+%7B%0A++++++++++count%2C%0A++++++++++viewer_has_liked%2C%0A++++++++++nodes+%7B%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%2C%0A++++++++++++++followed_by_viewer%2C%0A++++++++++++++requested_by_viewer%0A++++++++++++%7D%0A++++++++++%7D%0A++++++++%7D%2C%0A++++++++comments.last(20)+%7B%0A++++++++++nodes+%7B%0A++++++++++++id%2C%0A++++++++++++user+%7B%0A++++++++++++++username%2C%0A++++++++++++++profile_pic_url%0A++++++++++++%7D%2C%0A++++++++++++text%2C%0A++++++++++++viewer_can_delete%0A++++++++++%7D%0A++++++++%7D%0A++++++%7D%0A++++';

                      
            $result = $this->get_data_header($url,$headers, $body);
            return $result; 

        }


/**
 * $scrftoker method
 *
 * @return void
 */
  public function scrftoken($field_id){
    if ($field_id == 1) {
      $CSRFToken = '6eba641d33e3ab1f713ce5746055f4a6';
    }elseif ($field_id ==2) {
      $CSRFToken = 'fb762d34a19f56740c24adf585748367';
    }elseif ($field_id ==4) {
      $CSRFToken = '2dea3714a52df70879c6cb33b8a041fa';
    }
    return $CSRFToken;    
  }

/**
 * cookie method
 *
 * @return void
 */
  public function cookie($field_id){
    if ($field_id == 1) {
      $cookie = 'mid=VU36FwAEAAFVbmE_tw0j--q9jM2C; __utma=227057989.733228142.1431173769.1434441221.1434532885.5; __utmz=227057989.1431173769.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); sessionid=IGSC05bf351a72c9aabdaca27ffa74df6ef2acb0cdb8b49062133821d761a31a9db4%3AMjDZmQxvlLZVDFz3RqQ3EmAir5A9gmg5%3A%7B%22_token_ver%22%3A1%2C%22_auth_user_id%22%3A2124577533%2C%22_token%22%3A%222124577533%3Auc9v6XpYGAdJcCMsEe2td0oBRRmwTpgH%3A5e74cb59fbf16d8cc677ac71951ad98a548bd07b6e78e83b73c74905d0fe1162%22%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22last_refreshed%22%3A1435047589.025321%2C%22_platform%22%3A4%7D; __utma=1.227445856.1432217945.1432710222.1433831743.5; __utmz=1.1432217945.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); csrftoken=6eba641d33e3ab1f713ce5746055f4a6; ds_user_id=2124577533; ig_pr=1; ig_vw=1360';
    }elseif ($field_id ==2) {
      $cookie = 'mid=VU36FwAEAAFVbmE_tw0j--q9jM2C; __utma=227057989.733228142.1431173769.1433055606.1433831784.3; __utmz=227057989.1431173769.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); sessionid=IGSC9ac4bef0c3b0b341789328cc0ee59f637aca9df889aa2a5e66a9f4d135498eba%3A4hl0VMo13jisrQvEVkZ88v5SEueHbZva%3A%7B%22_token%22%3A%222080951062%3A4veuv3qxGLI8kXqOd9KA9oXYo23A1KT7%3A9217cdcd4db4abf89d6fccdac0bc24af8ac9a831f6a4092e59107890b5fdf221%22%2C%22last_refreshed%22%3A1433831791.048309%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_id%22%3A2080951062%2C%22_platform%22%3A4%7D; __utma=1.227445856.1432217945.1432710222.1433831743.5; __utmz=1.1432217945.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmb=1.4.10.1433831743; __utmt=1; __utmc=1; csrftoken=fb762d34a19f56740c24adf585748367; __utmb=227057989.1.10.1433831784; __utmc=227057989; ds_user_id=2080951062';
    }elseif ($field_id == 4) {
      $cookie = 'mid=U3T87wAEAAFzm2Q-ppNwoSj6yXfo; fbm_124024574287414=base_domain=.instagram.com; __utma=1.245062998.1400175855.1433698476.1433708336.19; __utmz=1.1418558263.8.3.utmcsr=avatech.ir|utmccn=(referral)|utmcmd=referral|utmcct=/en/get-started/; sessionid=IGSC7207b3b96490aa972400834eb562a62cd4180936abbb50fd7f09af99efb73e13%3AYYVJg65zJ931nOxF9SoevUDC9ZHp2ESE%3A%7B%22_token_ver%22%3A1%2C%22_auth_user_id%22%3A2138589585%2C%22_token%22%3A%222138589585%3AHV6QMswiG9YfVjOPpf1czTW2EsdPINiH%3Adbb4e0d6a7eed212d805597b034662860243304090cb6c364ea2c487343a6e72%22%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22last_refreshed%22%3A1434752521.665922%2C%22_platform%22%3A4%7D; fbsr_124024574287414=fDRYw3TMa4sXi2JjUHx_7soiz2pV-0VuUA4z_ILu36o.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUURWYk9lRTJJTkk2S3VoX3RmY2JaekNvVk5xU243YTM3RTJPampyUkVrbkFsZkg2SGFrZkJTYlpHRWtidnVLbXMxUHJEUl9uM01veUdfY0dmcjUxaFRKNmthRFd5VjJ5WmdrSlU4a3VXOUlEU1BzUlR3bnlsZzh2My0zdXB0Ujl0RjFWX1R6bEM0SjEyNlYyeTdOVHN3aF85OV8xTWVTdlZaQmtLSGk2ZG5EN2ltcTB4enJ4M3lIOFR3UmxZaTVlV2hEVE5WNnBUcTBlUUxuU05vWWdTSWEwQXg5MjRqU2VpNEZyODhlYXV4dFJlSVNsd2FSdjJVMnBSOFFlV0RLOVBEZlg4bzNwaUNrSnJHNEwzVVZHQVYza0dhOWpDZklRY2hnMWZqcFlhMDBud3o1ZG1hZjdRdEEzMG5rZnR0emgzN2d1UnM0eC1HNEY3dGsySUk1T3pLLVphWFV5Mm1lQm1TMWpualBCMl9jU0EiLCJpc3N1ZWRfYXQiOjE0MzQ3NTc2OTIsInVzZXJfaWQiOiI2NDA4Nzk3NTMifQ; __utma=227057989.711052211.1416673913.1434751764.1434758010.10; __utmb=227057989.3.10.1434758010; __utmc=227057989; __utmz=227057989.1434301225.2.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); ig_pr=1; ig_vw=1920; csrftoken=2dea3714a52df70879c6cb33b8a041fa; ds_user_id=2138589585';
    }

    return $cookie;    
  }



/**
 * do_comment_actor method
 *
 * @return void
 */
  public function do_comment_actor2(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $all = $this->User->Actor->list_actor(); 
    foreach ($all as $key => $username) {
        $conditions = array(
          'conditions'=>array(
            'username'=>$username,
          ),
          'contain'=>array(
            'UserMedia'=>array(
              'conditions'=>array(
                  'comment'=> false,
                  ),
                'limit'=> 5
                ),
            
          ),
        );

        $data = $this->User->find('first',$conditions);
        foreach ($data['UserMedia'] as $key => $item) {
          $id = $item['id'];
          $link = $item['link'];

          
          $user_media_id = substr($id, 0,strpos($id, '_'));
          $result = $this->comment_image($user_media_id,$link); 

          if ($result = '') {
            $this->User->UserMedia->id= $id;
            $this->User->UserMedia->saveField('comment', true);
        
          }
          sleep(4);

        }


    }
    
  }












/**
 * comment_actor method
 *
 * @return void
 */
  public function comment_image($user_media_id,$link){
    $url = 'https://instagram.com/web/comments/' . $user_media_id . '/add/';

        $comment_text = array(
          '1',
          '2',
          '3',          
          '4',
        );
        
        $rand = array_rand($comment_text,1);
        
        $text = $comment_text[$rand];
        $len = strlen($text) + 13;        
            $headers = array(
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 
                'Accept: */*', 
                'Accept-Language: en-US,en;q=0.5', 
                'Accept-Encoding: gzip, deflate', 
                'X-Instagram-AJAX: 1', 
                'X-CSRFToken:637923ba13773ba37fad3ea006e59fdd', 
                'X-Requested-With: XMLHttpRequest', 
                'Pragma: no-cache', 
                'Cache-Control: no-cache', 
                'Referer:'.$link, 
                'Cookie: mid=U3T87wAEAAFzm2Q-ppNwoSj6yXfo; __utma=1.997969661.1431024844.1431024844.1431024844.1; __utmz=1.1431024844.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); fbm_124024574287414=; csrftoken=637923ba13773ba37fad3ea006e59fdd; __utma=227057989.569022379.1431024803.1431024803.1431024803.1; __utmb=227057989.1.10.1431024803; __utmc=227057989; __utmz=227057989.1431024803.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); sessionid=IGSC4b77d276fc1af936dda243905db2a9433b18e4b3be1aa54d859f03090dc61656%3AQH7CrzCTArKFnTLrPMITLc2MRumTbVGJ%3A%7B%22_token%22%3A%222131245424%3AahYD9mjMbOrFgEnbB7Glc89tHOiBiUht%3Ab69c2df380c160b0be4f691f4dc364bb8601c2c48ee49f1e75735d4ad7de1632%22%2C%22last_refreshed%22%3A1431024828.380776%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_id%22%3A2131245424%2C%22_platform%22%3A4%7D; ds_user_id=2131245424; __utmb=1.2.10.1431024844; __utmc=1', 

                'Connection: keep-alive', 
                'Content-Length:'.$len, 
            );
            $body = 'comment_text='.$text;
            
            $d = $this->get_data_header($url,$headers, $body);
            return $d;
    
  }



    function get_data_header($url, $headers=null, $body=null) {
        $ch      = curl_init();
        $timeout = 7;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);  
        debug($data);        

        curl_close($ch);
        if ($data == '{"status":"ok"}') {
          return true;          
        }else{
          return $data;
        }
        
    }


    function get_data($url) {
      $ch      = curl_init();
      $timeout = 7;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
        
    }




/**
 * follower method
 *
 * @return void
 */
  public function follower(){
    $this->get_access();
    $this->get_access_tabiate_jahan();

    // *********** Bogho karna **************
    $honarmand             = $this->Session->read('accessToken');
    $instagram              = new Instagram\Instagram($honarmand);
    $CurrentUser            = $instagram->getCurrentUser();
    // $this->User->Actor->Field->Follower->save_follower($CurrentUser,1);
    $followed_by_honarmand = $CurrentUser->counts->followed_by;
    $follows_honarmand     = $CurrentUser->counts->follows;
    $media_honarmand       = $CurrentUser->counts->media;
    // *********** Bogho karna **************


    // ************ tabiate.jahan****************
    $tabiate_jahan             = $this->Session->read('access_token_tabiate_jahan');
    $instagram                 = new Instagram\Instagram($tabiate_jahan);
    $CurrentUser               = $instagram->getCurrentUser();    
    $followed_by_tabiate_jahan = $CurrentUser->counts->followed_by;
    $follows_tabiate_jahan     = $CurrentUser->counts->follows;
    $media_tabiate_jahan       = $CurrentUser->counts->media;
    $data = array(
      'boghokarna'=>array(
        'followed_by' => $followed_by_boghokarna,
        'follows'     => $follows_boghokarna,
        'media'       => $media_boghokarna,
      ),
      'tabiate_jahan'=>array(
        'followed_by' => $followed_by_tabiate_jahan,
        'follows'     => $follows_tabiate_jahan,
        'media'       => $media_tabiate_jahan,
      ),
    );
    $this->set(compact('data'));
    
    
    // ************ tabiate.jahan****************
    




  }


/**
 * track_follower method
 *
 * @return void
 */
  public function track_follower(){
    $list = $this->User->Actor->Field->list_alias();
    foreach ($list as $key => $item) {
      $user_id = $this->User->get_user_id($item);
      
      if ($user_id == null) {
        
        $this->add($item,$key);
        $user_id = $this->User->get_user_id($item);
      }
      $user_info = $this->Datains->get_user_data4($user_id); 
      
      $this->User->Actor->Field->Follower->save_follower($user_info,$key);     

    }
    
  }



/**
 * unfollow method
 *
 * @return void
 */
  public function set_incoming_outgoing2(){
    $this->get_access();
    $d            = $this->Session->read('accessToken');
    $instagram    = new Instagram\Instagram($d);
    $CurrentUser  = $instagram->getCurrentUser();
    $conditions = array(
      'conditions'  => array(
        'request_send' => 1,
        'incoming_status' => '',
        'exist' => 1,
      ),
      'limit'=>1000,
    );
    $this->User->recursive = -1;
    $all = $this->User->find('all',$conditions);
    foreach ($all as $key => $item) {
      $user_id = $item['User']['id'];
      $CurrentUser->unFollow($user_id);
      die();
      $username = $item['User']['username'];
      // $d = $instagram->getUserByUsername($username);
      // $ins_id = $d->id;
      // if ($ins_id != $user_id) {
      //   $this->update_user($d,$user_id);

      //   continue;      
      // }     
        
      // if ($d) {
      debug($user_id);
          // $relationship = $CurrentUser->getRelationship($user_id);
          $relationship = $this->relationship($user_id);
      
          // *****************
          $outgoing_status        = $relationship->outgoing_status;
          $target_user_is_private = $relationship->target_user_is_private;
          $incoming_status        = $relationship->incoming_status;      
          // *****************
          $this->User->id = $user_id;
          $this->User->saveField('outgoing_status', $outgoing_status);
          $this->User->saveField('target_user_is_private', $target_user_is_private);
          $this->User->saveField('incoming_status', $incoming_status); 
          debug('$this->request->data');
                 die();       
      // }else{
      //   $this->User->id = $user_id;
      //   $this->User->saveField('exist', false);
        
      // }
      

    }
    debug('done');
    // follow_back_id
  }



/**
 * unfollow method
 *
 * @return void
 */
  public function set_incoming_outgoing_url(){
    // $this->get_access();
    $conditions = array(
      'conditions'  => array(
        'incoming_outgoing_set' => false,        
        'outgoing_status' => 'follows',        
        'request_send !=' => 2,        
      ),
      'limit'=>500,
    );

    $this->User->recursive = -1;
    $all = $this->User->find('all',$conditions);
    foreach ($all as $key => $item) {
      $user_id      = $item['User']['id'];
      $username     = $item['User']['username'];
      $request_send = $item['User']['request_send'];
      $relationship = $this->relationship($user_id);
      if (is_object($relationship)) {
         // *****************
          $outgoing_status        = $relationship->outgoing_status;          
          $target_user_is_private = $relationship->target_user_is_private;
          $incoming_status        = $relationship->incoming_status;      
          // *****************
          $this->User->id = $user_id;
          $this->User->saveField('outgoing_status', $outgoing_status);
          if ($outgoing_status == 'none') {
              $this->User->saveField('request_send', false);            
          }elseif ($outgoing_status == 'follows' && $request_send = false) {
             $this->User->saveField('request_send', true);
             $date = date("Y-m-d H:i:s",strtotime("2 day ago"));
             $this->User->saveField('request_time', $date);
          }
          $this->User->saveField('target_user_is_private', $target_user_is_private);
          $this->User->saveField('incoming_status', $incoming_status); 
          $this->User->saveField('incoming_outgoing_set', true); 
          
          CakeLog::write('incoming_outgoing_set',  'Success => ' . $username); 
          

      }elseif ($relationship == 'you cannot view this resource' || $relationship == 'this user does not exist') {
        $this->User->id = $user_id;
        $this->User->delete($user_id);
      }else{
        debug($relationship);
        die();
      }
      
    }

  }



/**
 * update_outgoin method
 *
 * @return void
 */
  public function update_outgoin(){
    $this->get_access();
    $conditions = array(
      'conditions'  => array(
        'incoming_outgoing_set' => false,        
      ),
      'limit'=>500,
    );

    $this->User->recursive = -1;
    $all = $this->User->find('all',$conditions);
    foreach ($all as $key => $item) {
      $user_id      = $item['User']['id'];
      $username     = $item['User']['username'];
      $request_send = $item['User']['request_send'];
      $relationship = $this->relationship($user_id);
      $this->User->set_income_outgoing($user_id,true);     
    }
  }


/**
 * unfollow_follow_by method
 *
 * @return void
 */
  public function unfollow_follow_by(){
    date_default_timezone_set('Asia/Tehran');    
    $conditions = array(
      'conditions'  => array(
        'outgoing_status'              => 'follows',
        'incoming_status'              => 'followed_by',
        // 'incoming_outgoing_set'              => true,
      ),
      'order'=>'request_time ASC',
      'limit'=>2
    );
    $this->User->recursive = -1;
    $all = $this->User->find('all',$conditions);
    return $all;
  }


/**
 * unfollow_dont_follow_back method
 *
 * @return void
 */
  public function unfollow_dont_follow_back(){
    date_default_timezone_set('Asia/Tehran');
    $date = date("Y-m-d H:i:s",strtotime("1 day ago"));
    $conditions = array(
      'conditions'  => array(
        // 'outgoing_status'              => array('follows','requested'),
        'request_send'              => 1,
        // 'target_user_is_private'              => true,
        'incoming_outgoing_set'              => true,
        'request_time           <=' => $date,                
      ),
      'order'=>'request_time ASC',
      'limit'=>40
    );
    $this->User->recursive = -1;
    $all = $this->User->find('all',$conditions);
    return $all;
    
  }



/**
 * find_user_id method
 *
 * @return void
 */
  public function find_user_id($username,$field_id){
    $username = strtolower($username); // sanitization
    $access_token = $this->access_token($field_id);
    $url = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".$access_token;
    $get = file_get_contents($url);
    $json = json_decode($get);

    foreach($json->data as $user)
    {
        if($user->username == $username)
        {
            return $user->id;
        }
    }    
  }

/**
 * access_token method
 *
 * @return void
 */
  public function access_token($field_id){
    if ($field_id == 1) {
      $access_token = Configure::read('Instagram.golandam.honarmandan.access_token');
    }elseif ($field_id == 2) {
      $access_token = Configure::read('Instagram.tabiatejahan.access_token');      
    }elseif ($field_id == 4) {
      $access_token = Configure::read('Instagram.akse3lfi.access_token');            
    }
    return $access_token;
  }


/**
 * do_unfollow method
 *
 * @return void
 */
  public function do_unfollow(){    
    $all = $this->unfollow_dont_follow_back();    
    if (count($all) ==  0) {
      $all = $this->unfollow_follow_by();      
    }
    $count_unfollow = 0;
    foreach ($all as $key => $user) { 
      if ($count_unfollow > 2) {
        die();
      }
      debug($user);      
      sleep(5);
      $user_id      = $user['User']['id'];
      $username     = $user['User']['username']; 
      debug($username);
                
      $relationship = $this->relationship($user_id);    

      
      $request_send = $this->User->request_send($user_id);
      debug($request_send);
      
      if (
            is_object($relationship) && 
            ($relationship->outgoing_status == 'follows' || $relationship->outgoing_status == 'requested') && 
            ($relationship->incoming_status == 'none' || $relationship->incoming_status == 'followed_by') 
            // && ($request_send == 1 || $request_send == 2)
          ) { 
        debug($relationship);
          $result = $this->unfollow($user_id);
          $count_unfollow++;  
          debug($result);
                  
        if ($result) {
          debug('unfolow');          
          CakeLog::write('un_follow_instagram',  ' Success => ' . $username);
          $this->User->id = $user_id;
          $this->User->saveField('outgoing_status', 'none');        
          $this->User->saveField('request_send', 4);     //5== not return after 2 day   // means that we send request(1) and after 2 day or more we found he dont follow back and we unfollow that user and set request_send  = 2 
        }        
      }elseif(
            $relationship == 'this user does not exist' || $relationship == 'you cannot view this resource'
            
        ) {
        // delete user
        CakeLog::write('un_follow_instagram',  ' error => ' . 'deleted ' . 'username => '.  $username);
        $this->User->id = $user_id;
        $this->User->delete($user_id);
      }
    }
       
  }





/**
 * unfollow_users method
 *
 * @return void
 */
  public function unfollow_users($field_id, $count = 3){
    date_default_timezone_set('Asia/Tehran');
    $twodayago = date('Y-m-d H:i:s', strtotime('-2 day'));
    $conditions = array(
      'conditions'  => array(
        'Relationship.relationship_type_id' => 1,
        'Relationship.field_id' => $field_id,
        'Relationship.outgoing' => 'requested',
        'Relationship.unFollow' => false,
        'Relationship.unFollow' => false,
        'Relationship.relation_time <' => $twodayago,
      ),         
      'order'    => array(
        'relation_time' =>'asc',
      ),
      'limit'=> $count,
    );
    $all = $this->User->Relationship->find('all', $conditions);
    debug($all);
    die();
    foreach ($all as $key => $user) {
      debug($user);      
      $user_id = $user['User']['id'];
      $username = $user['User']['username'];
      $relationship_id = $user['Relationship']['id'];
      $relation_time = $user['Relationship']['relation_time'];
      $result = $this->unfollow($user_id, $field_id);

      $this->write_temp_file($field_id,$result,$username,true);
      if ($result) {
        $unfollow = $this->User->Relationship->set_unfollow($relationship_id);
        debug($unfollow);
        sleep(rand(3,7));
      }elseif ($result == '') {
        $this->User->delete($user_id);
        $this->User->Relationship->deleteAll(array('Relationship.user_id' => $user_id), false);
      }

    }
    debug('done');
    die();
    
  }






/**
 * test method
 *
 * @return void
 */
  public function relationship($user_id,$field_id = 1){
    $field_id = intval($field_id);
    if ($field_id == 1) {
      $access_token = '2131245424.8d1dd48.faefeb91e49c4256b710ffd7ec5a8e16';      
    }elseif ($field_id == 2) {
      $access_token = Configure::read('Instagram.access_token_tabiate_jahan'); 
    }

    $url = 'https://api.instagram.com/v1/users/'.$user_id.'/relationship?access_token='.$access_token;
    $answer = $this->get_data($url);
    $answer = json_decode($answer); 
    $code = $answer->meta->code;
    if ($code == 400) {
      $error_message = $answer->meta->error_message;
      return $error_message;
    }elseif ($code == 200) {
      $data = $answer->data;
      $this->save_rel($user_id,$data,$field_id);
      return $data;
    }    
  }

/**
 * save_rel method
 *
 * @return void
 */
  public function save_rel($user_id, $data,$field_id=1){
    $this->User->id         = $user_id;
    
    $this->User->recursive = -1;
    $user = $this->User->findById($user_id);
    if ($field_id == 1) {
      $r = $user['User']['request_send'];      
    }elseif ($field_id == 2) {
      $r = $user['User']['request_send_tabiate_jahan'];      
    }
    $outgoing_status        = $data->outgoing_status;
    $target_user_is_private = $data->target_user_is_private;
    $incoming_status        = $data->incoming_status;

    
    if ($outgoing_status == 'none' && $incoming_status == 'none' && ($r == '0'  || $r == '2' ) ) {
      $request_send = 0;
    }elseif ( ($outgoing_status == 'follows'  || $outgoing_status == 'requested')  && $incoming_status == 'none') {
      $request_send = 1;      
    }elseif ($outgoing_status == 'follows' && $incoming_status == 'followed_by') {
      $request_send = 2;      
    }elseif ( ($outgoing_status == 'none' || $outgoing_status == 'requested' ) && $incoming_status == 'followed_by') {
      $request_send = 3;      
    }elseif ($outgoing_status == 'none' && $incoming_status == 'none' && $r == '3' || $r == '1') {
      $request_send = 4;      
    }
    if (!isset($request_send)) {
      debug($data);
      die();
    }

    if ($field_id == 1) {
      $user = array(
      'outgoing_status'        => $outgoing_status,
      'target_user_is_private' => $target_user_is_private,
      'incoming_status'        => $incoming_status,
      'request_send'           => $request_send,
      'incoming_outgoing_set'  => true,
      );  
    }elseif ($field_id == 2) {
      $user = array(
        'outgoing_status_tabiate_jahan'        => $outgoing_status,
        'target_user_is_private' => $target_user_is_private,
        'incoming_status_tabiate_jahan'        => $incoming_status,
        'request_send_tabiate_jahan'           => $request_send,
        'incoming_outgoing_set_tabiate_jahan'  => true,
        );
    }
    $this->User->save($user);    
  }


/**
 * update_user method
 *
 * @return void
 */
  public function update_user($d, $user_id){
    $this->User->id  = $user_id;
    if ($this->User->delete()) {
      echo $user_id . ' delted ' .' </br>';
    }
    $data = array(
      'id'              => $d->id,
      'username'        => $d->username ,
      'profile_picture' => $d->profile_picture,
      'full_name'       => $d->full_name ,
      'request_send'    =>false,
    );
    if ($this->User->save($data)) {
      echo 'saved';
    }    
  }


/**
 * cron method
 *
 * @return void
 */
  public function cron(){

    if (!defined('CRON_DISPATCHER')){ 
        $this->redirect('/'); exit(); 
    }
    $d = $this->User->find('first');
    $this->redirect(array('controller' => 'Users', 'action' => 'do_follow'));
    
  }


/**
 * my_follower method
 *
 * @return void
 */
  public function my_follower($un){
    $user_id = $this->find_user_id($un);
    $client_id = Configure::read('Instagram.honarmandan.client_id');
    // $this->get_access();
    // $d           = $this->Session->read('accessToken');
    $url         = 'https://api.instagram.com/v1/users/'.$user_id . '/followed-by/?client_id='.$client_id;
    do {      
      $follow_info = $this->get_data($url);
      $follow_info = @json_decode($follow_info, true);
      debug($follow_info);
      die();
      foreach ($follow_info['data'] as $key => $user) {
        $fl[] = $user;
        // $relationship = $this->relationship($user['id']);          
        // debug($relationship);
        // die();
        
      }
      if (isset($follow_info['pagination']['next_url'])) {        
          $url =   $follow_info['pagination']['next_url'];
      }
                
    } while (!empty($url));
    debug($fl);
    die();
  }



  /**
 * my_follower method
 *
 * @return void
 */
  public function unfollow_field($un = 'honarmand.ir', $field_id = 1){
    
    $user_id = $this->find_user_id($un, $field_id);
    $client_id = Configure::read('Instagram.honarmandan.client_id');                                  
    
    // $this->get_access();
    // $d           = $this->Session->read('accessToken');
    $url         = 'https://api.instagram.com/v1/users/'.$user_id . '/follows/?client_id='.$client_id;
    do {      
      $follow_info = $this->get_data($url);
      $follow_info = @json_decode($follow_info, true);
      foreach ($follow_info['data'] as $key => $user) {   
        $user_id = $user['id'];
        $result = $this->unfollow($user_id,1);
        sleep(26);
        if ($key > 1) {
          die();
        }



      }
      if (isset($follow_info['pagination']['next_url'])) {        
          $url =   $follow_info['pagination']['next_url'];
      }
                
    } while (!empty($url));
    debug($list);
    die();
  }








/**
 * Update_users method
 *
 * @return void
 */
  public function update_users($field_id = 1, $count = 10){
    $all      = $this->User->Actor->list_all_actor_user_id($field_id);    
    $images   = $this->User->UserMedia->get_last_image($all,$count);       
    $comments = $this->Instagram->comments($images);
    debug($comments);    
    foreach ( $comments as $key => $user) {
      $this->User->save_user($user);
    }
    debug('done');
    die();

  }



/**
 * outgoing_status method
 *
 * @return void
 */
  public function user_rel($user_id, $field_id = 1 , $field = null){
    $field_id = intval($field_id);
    if ($field_id == 1) {
      $access_token = Configure::read('Instagram.golandam.honarmandan.access_token');      
    }elseif ($field_id == 2) {
      $access_token = Configure::read('Instagram.access_token_tabiate_jahan'); 
    }elseif ($field_id == 4) {
      $access_token = Configure::read('Instagram.akse3lfi.access_token'); 
    }


    $url = 'https://api.instagram.com/v1/users/'.$user_id.'/relationship?access_token='.$access_token;
    $answer = $this->get_data($url);
    $answer = json_decode($answer); 

    $code = $answer->meta->code;
    if ($code == 400) {
      $error_message = $answer->meta->error_message;
      return $error_message;
    }elseif ($code == 200) {
      $data = $answer->data;
      if (isset($field)) {
        $out  = $data->$field;        
      }else{
        $out  = $data;                
      }
      return $out;
    }    
    
  }

/**
 * recent_user_media method
 *
 * @return void
 */         
  public function recent_user_media($user_id,$count = 3){
    $client_id = Configure::read('Instagram.honarmandan.client_id');    
    $html      = file_get_contents('https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?client_id=' . $client_id);
    $decode    = json_decode($html);
    if ($decode->meta->code  == 200) {
      if (!empty($decode->data)) {
        foreach ($decode->data as $key => $item) {
          if ($key >= $count) {
            break;
          }

          $ids[]  = substr($item->id, 0,strpos($item->id, '_')); 
        }

        return $ids; 
      }else{
        return null;
      }

       
    }else{

    }
}




/**
 * update_count method
 *
 * @return void
 */
  public function update_count(){
    $this->User->Relationship->recursive = 1;
    $conditions = array(
      'contain'  => array(
        'User'
      ),
      
    );
    $all = $this->User->Relationship->find('all',$conditions);
    debug($all);
    die();
    foreach ($all as $key => $user) {      
      $field_id = $user['Relationship']['field_id'];
      $id = $user['Relationship']['id'];
      $this->User->Relationship->id = $id;
      $this->User->Relationship->saveField('field_id', $field_id);      
    }

    
  }

/**
 * list method
 *
 * @return void
 */
  public function lists(){
    $conditions = array(
      'limit'=>100,
      'order'=>'created_time desc',
    );
    $list = $this->User->find('all',$conditions);
    debug($list);
    die();
    
  }



}
