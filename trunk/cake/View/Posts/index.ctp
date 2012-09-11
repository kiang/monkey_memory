<?php

$debug = 0 ;

/*
	Usage:
		fID=593742735
		action=[home|likes]
		offset=[0|100|200|...]
*/

function filter_home( $fdatas, $fid )
{
	global $debug ;
	$r = array()  ;

	foreach ( $fdatas['data'] as $data )
	{
		$link = NULL ;
		$imgUrl = NULL ;

		if ( ! isset ( $data['from']['id'] ) || $data['from']['id'] != $fid )
			continue ;

		$time = substr($data['created_time'], 0, 10) ;
		if ( isset($data['message']) )
		{
			$message = $data['message'] ;
		}else if ( isset( $data['story'] ) )
		{
			$message = $data['story'] ;
		}else
		{
			continue ;
		}

echo <<<EOF
	<div class="item">
		<div class="shadow"></div>
		<div class="date">
			<div class="time">$time</div>
			<a target="_blank" href="$link"><img src="$imgUrl"></a>
			$message
		</div>
	</div>
EOF;
/*
		$record = array(
			'fromID' => $data['from']['id'],
			'time' => substr($data['created_time'], 0, 10),
		) ;
		if ( isset($data['message']) )
		{
			$record['message'] = $data['message'] ;
		}else if ( isset( $data['story'] ) )
		{
			$record['message'] = $data['story'] ;
		}else
		{
			if ( $debug ) print_r( $data ) ;
		}
		print_r( $data ) ;
		array_push( $r, $record ) ;
*/
	}

//	return array( 'post' => $r ) ;
}

/*
	<div class="item">
		<div class="shadow"></div>
		<div class="date">
			<div class="time">$time</div>
			<a target="_blank" href="$link"><img src="$imgUrl"></a>
			$message
		</div>
	</div>
*/

function filter_likes( $fdata )
{
	$r = array() ;
	global $facebook ;

	foreach( $fdata['data'] as $data )
	{
		$record = array(
			'message' => $data['name'],
			'id' => $data['id'],
			'created_time' => $data['created_time'],
		);

		$like = ( $facebook->api('/' . $data['id'] ) ) ;

		$record['link'] = $like['link'] ;

		array_push( $r, $record ) ;
	}

	return array( 'likes' => $r ) ;
}

$facebook = $GLOBALS['facebook'] ;

$fID = $fbid ;
//$action = $_REQUEST['action'] ;
$action = 'home' ;
$offset =  ( isset ( $_REQUEST['offset'] ) )? $_REQUEST['offset'] : 0  ;
$limit = 100 ;

if ( $debug )
{
	echo "<h2> Friend ID: $fID</h2>\n" ;
	echo "<h2> Action: $action</h2>\n" ;
	echo "<h2> Offset: $offset</h2>\n" ;
	echo "<h2> Limit: $limit</h2>\n" ;
}


$cmds = array( $action ) ;

$r = NULL ;

foreach ( $cmds as $cmd )
{
	$fbCmd = "/$fID/$cmd" ;

	if ( $cmd == 'home' )
	{
		$fbCmd = '/me/home' ;
	}

	if ( $debug )
		echo "<h2> $fbCmd </h2>\n" ;

	$ftmp = $facebook->api("$fbCmd?offset=$offset&limit=$limit") ;

	$tmpR = NULL ;
	switch ( $cmd )
	{
		case 'home':
			$r = filter_home($ftmp, $fID) ;
			break ;
		case 'likes':
			$r = filter_likes($ftmp) ;
			break ;
		case 'locations':
		case 'photos':
	}

}

if ( $debug )
	print_r( $r ) ;


echo json_encode( $r ) ;

