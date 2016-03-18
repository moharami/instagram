<?php

App::uses('Component', 'Controller');


class CurlComponent extends Component {

    /**
     * scrape method
     *
     * @return void
     */
        public function scrape($url, $selector_content, $selector_title=null){
            

            $url      = curl_init($url);            

            // set options for the call
            curl_setopt($url,CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($url,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($url,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
            
            //we can also initialize curl and set the url as follows
            //$url = curl_init();
            //curl_setopt($url,CURLOPT_URL,"http://www.imdb.com/chart/top");
            
            $html = curl_exec($url);
            
            $error = curl_error($url);
            
            curl_close($url);
            
            if(empty($error)){        
                // continue with scrape, no error occurred
                // create DOM object
                $DOM = new DOMDocument();

                // load our html
                // @$DOM->loadHTML($html);
                @$DOM->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                // create a new xapth object
                $xpath = new DOMXpath($DOM);

                // run an xpath query to   pull a list of the movies from imdb
                $contents = $xpath->query($selector_content);

                foreach ($contents as $key => $content) {
                    $content_page = $content->nodeValue;                    
                }
                $result = array(
                    'content'=>$content_page,
                );
                return $result;
            }else{
                
                // show error and kill
                die($error);
            }
                
            }













// ************************** Load more ******************************
        /**
         * loadmore method
         *
         * @return void
         */
            public function loadmore($url){
                $url      = curl_init('http://www.instagram.com/moharamiamir/media/?max_id=851278972149286779_1391798568');            

                $html = file_get_contents('http://www.instagram.com/moharamiamir');
                debug($html);
                die();

                // set options for the call
                // curl_setopt($url,CURLOPT_FOLLOWLOCATION,true);
                curl_setopt($url,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($url,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
                
                $html = curl_exec($url);
                
                $error = curl_error($url);
                debug($error);
                die();
                
                curl_close($url);

                debug($html);
                die();
                
            }
// ************************** Load more ******************************













}