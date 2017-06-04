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

//require_once('flush.php');
require_once('ganon.php');
require_once('t-db.php');

if (isset($_POST['code']) || isset($_POST['multiple-code'])) {

	$summary = array();
		
	if(isset($_POST['multiple-code']))
		$codes = $_POST['multiple-code'];
	else
		$codes = $_POST['code'];
	
	$codes_array = explode(PHP_EOL, trim($codes));		
	
	foreach	 ($codes_array as $code) {
		
		//TODO: sanitize 
		$code = trim($code);	
		if (ctype_digit($code)) {
			$code = 'AJAV0'.$code;
		}	
		else {
			$parts = explode("@",$code);
			$code = $parts['0'];
			$parts = explode(".",$code);
			$code = $parts['0'];
		}
		
		// execute query
		// retrieve all documents
		$cursor = $collection->findOne(array("code" => $code));
		
		//var_dump($cursor);

		// format DVD code
		$dvd = $cursor['dvd'];
		$code_temp = strtoupper($dvd);
		$code_temp_numbers = filter_var($code_temp, FILTER_SANITIZE_NUMBER_INT);
		$dvd_nice = str_replace($code_temp_numbers, '-' . $code_temp_numbers, $code_temp);
		
		$summary[$code] = $dvd_nice;

		echo 'code:' .$code.':<br/>';
		echo '<img src="'.$cursor['cover'].'" />';
		echo '<h3>'.$dvd.'</h3>';
		echo '<h2>'.$dvd_nice.'</h2><hr/><br/>';
	}
	
	echo '<hr/><br/>';
	foreach ($summary as $c => $d) {
		echo $c . ':<br/>  <strong>'. $d . '</strong><br/>';
	}

}

?>
<form type method="post">
	<input name='code'  width = '20' />
	<textarea name='multiple-code'  columns = '25' rows='15' ></textarea>
	<button type='submit' >search</button>
</form>
