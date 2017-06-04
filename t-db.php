<?php

	//MongoDB Configuration
	$dbhost = 'localhost';
	$dbname = 'tdark';

	// Connect to  database
	$m = new MongoClient("mongodb://$dbhost");
	$db = $m->$dbname;

	// access collection
	$collection = $db->queue;