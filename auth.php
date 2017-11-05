<?php
require_once 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
try{ 
session_start();
 
$config = require_once 'config.php';

$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

if (empty($oauth_verifier) ||
    empty($_SESSION['oauth_token']) ||
    empty($_SESSION['oauth_token_secret'])
) {
    // something's missing, go and login again
    header('Location: ' . $config['url_login']);
}
// connect with application token
$connection = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);

// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);


$twitter = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $token['oauth_token'],
    $token['oauth_token_secret']
);
}catch(\Abraham\TwitterOAuth\TwitterOAuthException $e){
    echo "Unable to Connect <a href=\"http://urlbook.ml\">Go Back</a>";
}

$redir = "http://localhost:666/booklocal/dbref.php?&oauth_token=".$token['oauth_token']."&oauth_token_secret=".$token['oauth_token_secret']."";
header('Location: '. $redir);

?>