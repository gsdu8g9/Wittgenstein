<?
/* 
===============================================

Wittgenstein 
 	request.php

@author: Samuel Acuna 
@date: 10/2013 

Script to perform the requests. 

===============================================
*/

require_once("settings.php"); 

$hashtags = explode("|",$_POST['tags']); 

main($dblink,$twitter,$instagram,$hashtags); 

function main($dblink,$twitter,$instagram,$hashtags) {
	$t = 0; 
	$i = 0; 

	foreach($hashtags as $tag) {
		// Requests data
		$t += getTweets($dblink,$twitter,$tag); 
		$i += getInstagrams($dblink,$instagram,$tag); 
	}

	echo strval($t).'.'.strval($i); 
}

function getTweets($dblink,$twitter,$tag) {
	$getfield = '?q='.$tag.'&count=100&lang=en'; 
	$url = 'https://api.twitter.com/1.1/search/tweets.json'; 
	$twitter_feed= $twitter->setGetField($getfield)
						   ->buildOauth($url,'GET')
						   ->performRequest(); 
	$tweets = json_decode("".$twitter_feed); 
	$pre_total = mysqli_num_rows(mysqli_query($dblink,"SELECT id FROM data3")); 
	if(isset($tweets->errors)) {
		echo "ERROR 200"; 
		return 0; 
	} else {
		foreach($tweets as $tweet) {
			for($i=0; $i<count($tweet)-1; $i++) { 
				$media = "twitter"; 
				$tag = $tag; 
				$post = json_encode($tweet[$i]); 
				$datetime = now(); 
				try {
					mysqli_query($dblink,"INSERT INTO data3 (media,tag,post,datetime) VALUES ('$media','$tag','$post','$datetime')"); 
				} catch (mysqli_sql_exception $e) {
					echo "ERROR"; 
				}
			}
		}
	}
	$post_total = mysqli_num_rows(mysqli_query($dblink,"SELECT id FROM data3")); 

	return $post_total-$pre_total; 
}

function getInstagrams($dblink,$instagram,$tag) {
	$instagram_feed = $instagram->getRecentTags($tag); 
	$instagrams = json_decode("".$instagram_feed); 
	$pre_total = mysqli_num_rows(mysqli_query($dblink,"SELECT id FROM data3")); 
	foreach($instagrams->data as $post) { 
		$media = "instagram"; 
		$tag = $tag; 
		$post = json_encode($post); 
		$datetime = now(); 
		try {
			mysqli_query($dblink,"INSERT INTO data3 (media,tag,post,datetime) VALUES ('$media','$tag','$post','$datetime')"); 
		} catch (mysqli_sql_exception $e) {
			echo "ERROR"; 
		}
	}
	$post_total = mysqli_num_rows(mysqli_query($dblink,"SELECT id FROM data3")); 

	return $post_total-$pre_total; 
}

function now() { return date('Y\/m\/d H\:i\:s'); }

?>