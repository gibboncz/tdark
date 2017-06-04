<?php
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

error_reporting( E_ALL );
ini_set('display_errors', 1);

// parser library
require_once('ganon.php');
require_once('flush.php');
disable_ob();
ob_implicit_flush(true);

//MongoDB Configuration
$dbhost = 'localhost';
$dbname = 'tdark';

// Connect to  database
$m = new MongoClient("mongodb://$dbhost");
$db = $m->$dbname;

// access collection
$collection = $db->queue;

// execute query
// retrieve all documents
$cursor = $collection->find(array("done" => 0))->limit( 2 );

// iterate through the result set
// print each document
echo $cursor->count() . ' document(s) found. <br/>';  
foreach($cursor as $obj){
	
	// load file
	$html = file_get_dom($obj['url']);

	// find the AJAV code
	$block = $html('div.postContent > p', 1)->getPlainText();
	$words = preg_match('/AJAV\w+/', $block, $matches);
	$ajav = str_replace('Description', '', $matches[0]);

	// Publisher ID : SRXV342 , XV353 Category :
	
	unset($matches);
	$block = $html('div.postContent > p', 2)->getPlainText();
	$words = preg_match('/(?<=Publisher ID :)(.*\n?)(?=Category)/', $block, $matches);
	$dvd = trim(str_replace('Category', '', $matches[0]));
	
	$cover = $html('div.postContent > p img', 0)->src;
//	$words = preg_match('/(?<=Publisher ID :)(.*\n?)(?=Category)/', $block, $matches);
	//$dvd = trim(str_replace('Category', '', $matches[0]));
		
	$obj['code'] = $ajav;
	$obj['dvd'] = $dvd;
	$obj['cover'] = $cover;
	$obj['done'] = 1;
	$collection->save($obj);
	
	echo $ajav.' = '.$obj['url'].' | '.   'dvd:'. $dvd.' | ' . 'image:'.  $cover.' <br/>';
	
	//echo $obj['url'].'<br/>';
	
}