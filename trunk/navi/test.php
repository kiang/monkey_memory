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

//$fID = '1093780135' ; // lock




// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '422119347844224',
  'secret' => '0fbc991db950c4bc27a3f40e887a6caf',
));
/* mm
$facebook = new Facebook(array(
  'appId'  => '153310081475699',
  'secret' => 'cf24b60cf2d6c37c72da2bab07bb3ec2',
));
*/

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

/*
if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
*/

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array( 'scope' => array( 'friends_checkins', 'friends_likes', 'friends_location', 'friends_photos', 'friends_status', 'read_stream' ) ) );
}

// This call will always work since we are fetching public data.
//$naitik = $facebook->api('/naitik');

$fID = $_REQUEST['fID'] ;
$action = $_REQUEST['action'] ;
$offset =  ( isset ( $_REQUEST['offset'] ) )? $_REQUEST['offset'] : 0  ;
$limit = 5 ;
echo "<h2> Friend ID: $fID</h2>\n" ;
echo "<h2> Action: $action</h2>\n" ;
echo "<h2> Offset: $offset</h2>\n" ;
echo "<h2> Limit: $limit</h2>\n" ;

//$accessToken = $facebook->getAccessToken() ;

//echo "<h2> Access Token</h2>" ;
//echo "$accessToken<br>\n" ;

//$fbUrl = 'https://graph.facebook.com' ;
//$accessToken = "access_token=$accessToken" ;

?>
              <?php if ($user): ?>
                <a href="#" class="avatar"><img src="https://graph.facebook.com/<?php echo $user; ?>/picture"></a>
                <a href="<?php echo $logoutUrl; ?>">Logout</a>
              <?php else: ?>
                <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
              <?php endif ?>

<?php

if ( ! $user )
	exit ;
/*
	$userFriends = $facebook->api('/me/friends') ;
	foreach( $userFriends['data'] as $friend )
	{
		echo $friend['name'] . '=>' . $friend['id'] . "<br>\n" ;  
	}
*/


$cmds = array(
	'home',
	'likes',
	'locations',
	'photos',
) ;

foreach ( $cmds as $cmd )
{
	$fbCmd = "/$fID/$cmd" ;

	if ( $cmd == 'home' )
	{
		$fbCmd = '/me/home' ;
	}
	echo "<h2> $fbCmd </h2>\n" ;

	$ftmp = $facebook->api("$fbCmd?offset=$offset&limit=$limit") ;

	$r = NULL ;
	switch ( $cmd )
	{
		case 'home':
			$r = filter_home($ftmp, $fID) ;
		case 'likes':
			$r = filter_likes($ftmp) ;
		case 'locations':
		case 'photos':
	}

	print_r( $ftmp['data'][0] ) ;

	print_r( $r ) ;
}


function filter_home( $fdatas, $fid )
{
	$r = array()  ;

	foreach ( $fdatas['data'] as $data )
	{
		$record = array(
			'message' => $data['message'],
			'fromID' => $data['from']['id'],
			'time' => $data['created_time'],
		) ;
		array_push( $r, $record ) ;
	}

	return array( 'post' => $r ) ;
}

function filter_likes( $fdata )
{
	$r = array() ;


	return array( 'likes' => $r ) ;
}
