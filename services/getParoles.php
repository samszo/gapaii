<?php
	$parole = urlencode($_GET["parole"]);
	$nbCaract = strlen($parole);
	
	// Save the MP3 file in this folder with the .mp3 extension 
	$file = md5($parole).".mp3";
 
	
	// If the MP3 file exists, do not create a new request
	if (!file_exists($file)) {
    	$mp3 = file_get_contents('http://translate.google.com/translate_tts?ie=UTF-8&q='.$parole.'&tl=fr&textlen='.$nbCaract.'&idx=0&total=1');
		file_put_contents($file, $mp3);
	}
	echo $file;
	