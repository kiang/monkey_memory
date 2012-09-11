<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'api/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '271136786338217',
  'secret' => '9f7f3c597dd54220470c6aed5f821fa1',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
	$q = $_GET['q'];
    $user_profile = $facebook->api('/me');
	$home_info = $facebook->api('/me/home?q=' . $q . '&type=post');
	//$home_info = $facebook->api('/me/home?q=facebook&type=post');
	$home_data = $home_info['data'];
	$result_post = array();
	foreach ($home_data as $entry) {
		$result_post[] = array('message' => $entry['message'], 'picture' => $entry['picture'], 'link' => $entry['actions'][0]['link'], 'time' => $entry['created_time']);
	}
	
	
	
	$oauth_token = $facebook->getAccessToken();
	$query = "SELECT+user_id,object_id,post_id,object_type+FROM+like+WHERE+user_id=me()";
	
	$url = 'https://graph.facebook.com/' . '/fql?q=' . $query . '&access_token=' . $oauth_token;
	$obj = json_decode(file_get_contents($url));
	$data = $obj->data;
	foreach($data as $entry) {
		if ($entry->object_type == 'list') {
//			var_dump($entry);
		}
	}
	


//	echo '<pre>';
	header('Content-type: application/json');
	echo(json_encode(array('post' => $result_post, 'pictures'=> array(), 'link'=>array(), 'checkins' => array())));
	exit();
//	echo '</pre>';


  } catch (FacebookApiException $e) {
    var_dump($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
	$params = array('scope' => 'read_stream');
  $loginUrl = $facebook->getLoginUrl($params);
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Monkey Memory</title>
    <meta charset="utf-8">
    <!-- metadata -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="css/bootstrap-responsive.min.css">-->
    <style>
      body{padding: 41px 0 0 0;overflow: hidden;}
      .navbar .avatar{padding: 6px 0 0 !important;}
      .navbar .avatar img{width: 30px;height: 30px;}
      .box{height:1000px;background:url(img/bg.jpg);-moz-background-size:100% 100%;background-size:100%;background-repeat:no-repeat;}
    </style>
    </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Monkey Memory</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Total Recall</a></li>
              <li><a href="#about">Super Friends</a></li>
            </ul>
            <ul class="nav pull-right">
              <?php if ($user): ?>
              <li>
                <a href="#" class="avatar"><img src="https://graph.facebook.com/<?php echo $user; ?>/picture"></a>
              </li>
              <li>
                <a href="<?php echo $logoutUrl; ?>">Logout</a>
              </li>
              <?php else: ?>
              <li>
                <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
              </li>
              <?php endif ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="box">
      
    </div>

    <script src="js/jquery-1.8.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
