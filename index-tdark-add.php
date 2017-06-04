<?php


function queue_url($url){

	global $db;

	// Get the queue collection
	$c_queue = $db->queue;

	// Insert this new document into the queue collection
	$c_queue->save(array( 'url' => $url, 'done' => 0 ));

}



error_reporting( E_ALL );
ini_set('display_errors', 1);

require_once('ganon.php');
require_once('t-db.php');

if (isset($_POST['url'])) {
	echo 'sitemap:' .$_POST['url'].'<br/>';
	//TODO: sanitize 
	$sitemap = trim($_POST['url']);

	$xml = file_get_dom($sitemap);
	
	 foreach($xml('url > loc') as $loc) {
	  queue_url($loc->getPlainText());
	   echo $loc->getPlainText().' queued.<br/>';
	 }
	 
}

?>
<form type method="post">
	<input name='url'  width = '100' />
	<button type='submit' >submit</button>
</form>
