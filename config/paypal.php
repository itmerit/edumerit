<?php
 
return [
 
   'client_id' => '',
 
   'secret' => '',
 
   'settings' => array(
 
       'mode' => env('PAYPAL_MODE','sandbox'),
 
       'http.ConnectionTimeOut' => 30,
 
       'log.LogEnabled' => true,
 
       'log.FileName' => storage_path() . '/logs/paypal.log',
 
       'log.LogLevel' => 'ERROR'
 
   ),
 
];