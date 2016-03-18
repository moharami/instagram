<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));





// ************ Instagram ****************
    Configure::write(
        'Instagram',
            array(
                'Url'=> 'https://www.Instagram.com/',
                'Script' =>'body'
            )

    );
// ************ Instagram ****************

Configure::write('Instagram.honarmandan.client_id', '911b1caf2642418f80dd6a3b3c9c4a39');
Configure::write('Instagram.honarmandan.client_secret', 'ecaa47167c9446f1bb2a3416952c19fa'); 
Configure::write('Instagram.honarmandan.redirect_uri', 'ecaa47167c9446f1bb2a3416952c19fa'); 
Configure::write('Instagram.honarmandan.callback_url', 'http://www.boghokarna.ir/instagram/Users/callback');
Configure::write('Instagram.honarmandan.access_token', '2124577533.911b1ca.15b9a02b980a45f3b517140309bd399b');



Configure::write('Instagram.golandam.honarmandan.client_id', 'aa336b83912543aa9f5335ffc880d461');
Configure::write('Instagram.golandam.honarmandan.client_secret', '41421f5df34d4a1095be3c82d5891df2'); 
Configure::write('Instagram.golandam.honarmandan.redirect_uri', 'ecaa47167c9446f1bb2a3416952c19fa'); 
Configure::write('Instagram.golandam.honarmandan.callback_url', 'http://www.golandam.com/instagram/Users/callback/1/');
Configure::write('Instagram.golandam.honarmandan.access_token', '2124577533.aa336b8.bf98fbac9ba546ca8f82e3a47791d770');













Configure::write('Instagram.access_token', '2124577533.911b1ca.15b9a02b980a45f3b517140309bd399b');
Configure::write('Instagram.access_token_tabiate_jahan', '2080951062.c21a0d5.de186e09452e4f28816d47516f616536');


// *********** tabiate.jahan *************
Configure::write('Instagram.tabiatejahan.client_id','c21a0d5449cc43339859a558045a5a6d');
Configure::write('Instagram.tabiatejahan.client_secret','1ddcf89f519d4009a02f837eb2ffddda');
Configure::write('Instagram.tabiatejahan.redirect_uri', 'http://www.boghokarna.ir/instagram/Users/callback'); 
Configure::write('Instagram.tabiatejahan.callback_url', 'http://www.boghokarna.ir/instagram/Users/callback/2');
Configure::write('Instagram.tabiatejahan.access_token', '2080951062.c21a0d5.de186e09452e4f28816d47516f616536');
// *********** tabiate.jahan *************




// *********** akse3lfi *************
Configure::write('Instagram.akse3lfi.client_id', '46cf9b0484ca4cf7826e8e370c22be65');
Configure::write('Instagram.akse3lfi.client_secret', '17df707ff71a49fea69c8f95ef5a989c'); 
Configure::write('Instagram.akse3lfi.redirect_uri', 'http://www.boghokarna.ir/instagram/Users/callback'); 
Configure::write('Instagram.akse3lfi.callback_url', 'http://www.boghokarna.ir/instagram/Users/callback');
Configure::write('Instagram.akse3lfi.access_token', '2138589585.46cf9b0.effe69c7f72d45caa6d80018a3882d31');

// *********** akse3lfi *************



// *********** nini.jan *************
Configure::write('Instagram.ninijan.client_id', 'd3364556a23e4d4cab1225aa1c07e789');
Configure::write('Instagram.ninijan.client_secret', '4a87afdbd2d54ad49cf313841368e267'); 
Configure::write('Instagram.ninijan.redirect_uri', 'http://www.boghokarna.ir/instagram/Users/callback'); 
Configure::write('Instagram.ninijan.callback_url', 'http://www.boghokarna.ir/instagram/Users/callback/2');
// Configure::write('Instagram.ninijan.access_token', '2138589585.46cf9b0.effe69c7f72d45caa6d80018a3882d31');



// *********** nini.jan *************



spl_autoload_register(function($class) {
    foreach(App::path('Vendor') as $base) {
        $path = $base . str_replace('\\', DS, $class) . '.php';
        if (file_exists($path)) {
            return include $path;
        }
    }
});




// ************* log ***********************
    CakeLog::config('honarmandanir_follow_instagram', array('engine' => 'FileLog'));
    CakeLog::config('honarmandanir_unfollow_instagram', array('engine' => 'FileLog'));

    CakeLog::config('tabiate_jahan_follow_instagram', array('engine' => 'FileLog'));
    CakeLog::config('tabiate_jahan_unfollow_instagram', array('engine' => 'FileLog'));


    CakeLog::config('akse3lfi_follow_instagram', array('engine' => 'FileLog'));
    CakeLog::config('akse3lfi_unfollow_instagram', array('engine' => 'FileLog'));


    CakeLog::config('ninijan_follow_instagram', array('engine' => 'FileLog'));
    CakeLog::config('ninijan_unfollow_instagram', array('engine' => 'FileLog'));






    CakeLog::config('incoming_outgoing_set', array('engine' => 'FileLog'));
// ************* log ***********************