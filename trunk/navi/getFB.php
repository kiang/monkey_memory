<?php

$debug = 1 ;

/*
	Usage:
		fID=593742735
		action=[home|likes]
		offset=[0|100|200|...]
*/

$facebook = $GLOBAL['facebook'] ;

$fID = $_REQUEST['fID'] ;
$action = $_REQUEST['action'] ;
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

	if ( $debug )
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
