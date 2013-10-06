<?

/* 
===============================================

Wittgenstein 
 	settings.php 

@author: Samuel Acuna 
@date: 10/2013 

Settings configuration for data requests with Twitter 
and Instagram PHP Apis. 

Twitter PHP Api: http://github.com/j7mbo/twitter-api-php
Instagram PHP Api: https://github.com/macuenca/Instagram-PHP-API

===============================================
*/

require_once('TwitterAPIExchange.php'); 
require_once('Instagram.php'); 


// Connect to database
// *******************************
$dbhost = ''; 
$dbuser = ''; 
$dbpass = ''; 
$dbname = ''; 

$dblink = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname); 

session_start(); 


// Connect to Twitter
// *******************************
$settings = array(
	'oauth_access_token' => '',
	'oauth_access_token_secret' => '', 
	'consumer_key' => '', 
	'consumer_secret' => ''
);

$url = 'https://api.twitter.com/1.1/search/tweets.json'; 
$requestMethod = 'GET'; 
$rpp = '100'; 
$lang = 'en'; 

$twitter = new TwitterAPIExchange($settings); 

// Connect to Instagram 
// *******************************
$config = array(
	'client_id' => '', 
	'client_secret' => '', 
	'grant_type' => '', 
	'redirect_uri' => '' 
); 

if(isset($_SESSION['InstagramAccessToken']) && !empty($_SESSION['InstagramAccessToken'])) {
	header('Location: callback.php'); 
	die(); 
}

$instagram = new Instagram($config); 

?>