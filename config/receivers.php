<?php
$receivers = array(
	// single user per alias
    'your.name@example.com' => array(
	    array(
	    	'name' => 'Your Name',
	    	'email' => 'your.name@gmail.com'
	    )
    ),
    // multiple users per alias
    'your.admin@example.com' => array(
	    array(
	    	'name' => 'Your Name',
	    	'email' => 'your.name@gmail.com'
	    ),
	    array(
	    	'name' => 'Your Admin',
	    	'email' => 'your.admin@gmail.com'
	    )
    ),
    // Catch-all (REQUIRED)
    '*' => array(
	    array(
	    	'name' => 'My Catch-all mailbox',
	    	'email' => 'catch.all@gmail.com'
	    )
    )
);
