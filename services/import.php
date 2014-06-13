<?php
try {
	set_time_limit(1000);
	 
	require_once( "../application/configs/config.php" );
	$application->bootstrap();
	
	$imp = new Gen_Import();
	$imp->addDoc($_REQUEST);
	
}catch (Zend_Exception $e) {
	echo "Récupère exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
}
