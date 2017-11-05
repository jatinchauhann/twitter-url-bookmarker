<?php
require_once 'vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
$config = require_once 'config.php';

$oauth_token = $_GET['oauth_token'];
$oauth_token_secret = $_GET['oauth_token_secret'];

$twitter = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $oauth_token,
    $oauth_token_secret
);
//Database connectivity
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "projects";

$conn = mysqli_connect($servername, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$credentials = $twitter->get('account/verify_credentials');

define('TWEET_LIMIT', 400);
$name = $credentials->name;
$username=$credentials->screen_name;
$location=$credentials->location;
$about=$credentials->description;
$profileimage=$credentials->profile_image_url_https;
$followers=$credentials->followers_count;
$following=$credentials->friends_count;
$since_id = "";
$max_id = "";
$count = 0;

//$selectSQL = "SELECT * FROM `users` WHERE `users`.`user` = '$username' GROUP BY `tweetid` ORDER BY `tweetid` DESC";
$selectSQL = "SELECT * FROM `users` WHERE `users`.`user` = '$username' GROUP BY `timestamp`";

if( !( $selectRes = mysqli_query( $conn , $selectSQL ) ) ){
    echo 'Retrieval of data from Database Failed';
  }else{
$tweets = $twitter->get('statuses/home_timeline', array('screen_name' => $username, 'exclude_replies' => 'true', 'include_rts' => 'true', 'count' => TWEET_LIMIT));
while($count < 3){
	$bool=true;
	if(!empty($tweets)) {
	    foreach($tweets as $tweet) {
	    	# Access as an object
	        $tweetText = $tweet->text;
	        $uname=$tweet->user->screen_name;
	        $time=$tweet->created_at;
	        $tweetid=$tweet->id;
	        if($bool){
	    		$since_id=$tweetid;
	    		$bool=false;
	    	}
	        preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $tweetText, $match);
	        if(isset($match[0])){
	        $sql = "INSERT INTO `users` (`id`, `user`,  `username`, `url`, `timestamp`, `tweetid`) VALUES (NULL, '$username', '$uname', '$match[0]', '$time', '$tweetid')";
	        mysqli_query($conn, $sql);
	        $max_id=$tweetid;
	    }
	  }
	}
	$tweets = $twitter->get('statuses/home_timeline', array('screen_name' => $username, 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => TWEET_LIMIT, 'max_id' => $max_id));


	$count++;
	}
}
$redir = "http://localhost:666/booklocal/dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&oauth_token=".$token['oauth_token']."&oauth_token_secret=".$token['oauth_token_secret']."";
header('Location: '. $redir);
?>