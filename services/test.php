<?php
header('Content-Type: text/html; charset=utf-8');
echo "<br/>DEBUT du TEST<br/>";

require_once( "../application/configs/config.php" );
try {
	
	$application->bootstrap();

	/*
	$idDico = 134;
	$idConj = 44;
    $oDico = new Gen_Dico();
    $oDico->importCSV($idDico, array("path_source"=>"http://localhost/generateur/data/dicos/52cedaeb4a510.csv"));
    $oDico->GetMacToXml($idDico);
    $oDico->SaveBdd($idDico, $idConj);
	*/
	
	
	$txts = array("[0|caract2] [010000000|v_dire 4] [990000000|v_avoir] [0|m_peur] [40#] [126#]");	
	$m = new Gen_Moteur();
	//$m->coupures =  array(10, 30);
	$arrVerifDico = $m->getDicosOeuvre(41);
	//$var = $m->Verifier($txts[0], $arrVerifDico);
	$trace = false;
	$var = $m->Tester($txts, $arrVerifDico,$trace);
	echo "<br>".$m->texte;
	echo "<br>".$m->detail;


	/*
	$row = 1;
	if (($handle = fopen("https://docs.google.com/spreadsheet/pub?key=0AsJMmUNA97M2dGxwUVltUWNQazVwMGZ0b3V6enVaS0E&single=true&gid=0&output=csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $num = count($data);
	        echo "<p> $num champs à la ligne $row: <br /></p>\n";
	        $row++;
	        for ($c=0; $c < $num; $c++) {
	            echo $data[$c] . "<br />\n";
	        }
	    }
	    fclose($handle);
	}
	$oSite = new Flux_Site();
	$csv = $oSite->getUrlBodyContent("");
	$arr = str_getcsv($csv);
	$toto = "";
	*/
	//$dbU = new Model_DbTable_flux_Uti();
	//$arr = $dbU->getAll();
	
	/*
	$dbDico = new Model_DbTable_Gen_dicos();
$gen_dicos = array(
  array('id_dico' => '4','nom' => 'gen franÃ§ais syntagmes'),
  array('id_dico' => '13','nom' => 'gen franÃ§ais pronom complÃ©ment'),
  array('id_dico' => '14','nom' => 'gen franÃ§ais pronom sujet'),
  array('id_dico' => '34','nom' => 'gen franÃ§ais gÃ©nÃ©ral'),
  array('id_dico' => '44','nom' => 'gen franÃ§ais conjugaison'),
  array('id_dico' => '45','nom' => 'gen ?'),
  array('id_dico' => '46','nom' => 'gen franÃ§ais dÃ©terminants'),
  array('id_dico' => '51','nom' => 'gen ?'),
  array('id_dico' => '72','nom' => 'gen ?'),
  array('id_dico' => '73','nom' => 'gen espagnol gÃ©nÃ©ral'),
  array('id_dico' => '82','nom' => 'capture critiques'),
  array('id_dico' => '67','nom' => 'gen espagnol conjugaisons'),
  array('id_dico' => '68','nom' => 'gen espagnol syntagme'),
  array('id_dico' => '69','nom' => 'gen espagnol dÃ©terminant'),
  array('id_dico' => '70','nom' => 'gen espagnol pronom complÃ©ment'),
  array('id_dico' => '84','nom' => 'gen ?'),
  array('id_dico' => '89','nom' => 'gen ?'),
  array('id_dico' => '90','nom' => 'gen ?'),
  array('id_dico' => '91','nom' => 'gen ?'),
  array('id_dico' => '92','nom' => 'gen ?'),
  array('id_dico' => '93','nom' => 'capture bios'),
  array('id_dico' => '94','nom' => 'capture chansons'),
  array('id_dico' => '96','nom' => 'capture twitter'),
  array('id_dico' => '98','nom' => 'gen ?'),
  array('id_dico' => '99','nom' => 'thÃ©Ã¢tre'),
  array('id_dico' => '101','nom' => 'vies'),
  array('id_dico' => '102','nom' => 'Proverbes web'),
  array('id_dico' => '104','nom' => 'Dico ?'),
  array('id_dico' => '109','nom' => 'test'),
  array('id_dico' => '110','nom' => 'mon dico'),
  array('id_dico' => '111','nom' => 'monostiches'),
  array('id_dico' => '112','nom' => 'DS_Monostiches'),
  array('id_dico' => '117','nom' => 'mon dico'),
  array('id_dico' => '114','nom' => 'mon dictionnaire'),
  array('id_dico' => '115','nom' => 'mon mono'),
  array('id_dico' => '116','nom' => 'fredv'),
  array('id_dico' => '118','nom' => 'DS_Climats'),
  array('id_dico' => '119','nom' => 'mon dico'),
  array('id_dico' => '120','nom' => 'mon dico'),
  array('id_dico' => '121','nom' => 'DS-Corneille'),
  array('id_dico' => '122','nom' => 'DS-Corneille'),
  array('id_dico' => '123','nom' => 'DS_Tests-Ressources'),
  array('id_dico' => '127','nom' => 'Eric'),
  array('id_dico' => '128','nom' => 'test herbier sam'),
  array('id_dico' => '129','nom' => 'DS-PoÃ©tiques'),
  array('id_dico' => '130','nom' => 'DS-Trajectoires')
  );
	$nbTot = 0;
	foreach ($gen_dicos as $dico){
		echo '<br/>'.$dico['id_dico'].' - '.$dico['nom'].'<br/>';
		$nb = $dbDico->remove($dico['id_dico']);		
		echo $nb.'<br/>';
	}	
	echo $nbTot.'<br/>';
	*/
	
	
	/*
	$a = new Auth_LoginManager();
	$user = new Auth_LoginVO();
	$user->username='samszo';
	$user->password='samszo';
	$r = $a->verifyUser($user);
	print_r($r);
	*/	
	/*
	$dbC = new Model_DbTable_Gen_concepts();
	$ac = $dbC->findAllByDicos(array("concepts"=>"102, 34","pronoms"=>"13, 14","syntagmes"=>"4","déterminants"=>"46","negations"=>"16"));
	$ac = $dbC->findByIdDico("102.34",true);
	print_r($ac);
	$dbC->ajouter(array("id_dico"=>102,"lib"=>"bidule","type"=>"dis"));	
	$dbC->utilise(157625, "a_testouill");
	$arr = $dbC->remove(157632);
	*/
	
	/*
	$dbN = new Model_DbTable_Gen_negations();
	$arr = $dbN->utilise(16, 1);
	*/
	
	/*
	$dbA = new Model_DbTable_flux_acti();
	//$xml = $dbA->getFullPath(9);
	$xml = $dbA->getActiForOeuvre(6);
	$idActi = $dbA->ajoutForUtis("dictionnaire syntagme : modifier : à -> àbb (ref:4_429)","1,2",6,4);
	*/
	
	/*
	$dbT = new Model_DbTable_Gen_conjugaisons();
	$arrTrmChange[]=array("id_trm"=>"24712","lib"=>"ous");	
	$dbT->editTerms($arrTrmChange);
	$arr = $dbT->findVerbeByIdConj('824');
	$arr = $dbT->findTermByIdConj(680);
	*/
	
	
	/*
	$arrVerifDico = array("concepts"=>"120, 118, 34, 130"	
		,"conjugaisons"=>"44"	
		,"déterminants"=>"46"	
		,"negations"=>"16"	
		,"pronoms"=>"13, 14, 108"	
		,"pronoms_complement"=>"13"	
		,"pronoms_sujet"=>"14"	
		,"pronoms_sujet_indefini"=>"108"	
		,"syntagmes"=>"4");	

	$txts = array("1[thl-rivière-01] 2([thl-jardin-01] [s_prépo]) 3[thl-rivière-01]. 4[thl-rivière-01][s_ponctuer] 5[thl-Philos][s_ponctuer]  6[thl-champ-01][s_ponctuer] 7[thl-champ-01][s_ponctuer] 8[thl-champ-01][s_ponctuer] 9[thl-champ-01][s_ponctuer] 10[thl-champ-01][s_ponctuer] 11[thl-champ-01][[s_ponctuer] 12[thl-rivière-01][s_ponctuer] 13[thl-forêt-01][s_ponctuer] 14[thl-champ-01][s_ponctuer] 15[thl-village-01] 16([thl-jardin-01])[s_ponctuer] 17[thl-champ-01][s_ponctuer] 18[thl-champ-01][s_ponctuer] 19[thl-champ-01][s_ponctuer] 20[thl-champ-01][s_ponctuer] 21[thl-champ-01][s_ponctuer] 22[thl-champ-01][s_ponctuer] 23[thl-champ-01][s_ponctuer] 24[thl-champ-01][s_ponctuer] 25[thl-champ-01][s_ponctuer] 26[thl-champ-01] 27[s_prépo] 28[thl-forêt-01]. 29[thl-cielBleu-01] 30[s_prépo] 31[thl-Philos].");	
	$m = new Gen_Moteur();
	$arrVerifDico = $m->getDicosOeuvre(38);
	//$var = $m->Verifier($txts[0], $arrVerifDico);
	$var = $m->Tester($txts, $arrVerifDico);
	//echo $var[0];
	echo "<br>".$m->detail;
	//$m->getArbreGen("[010000000|v_sembler 1] [090000000|v_accepter 1] ",$arrVerifDico);
	//$arr = $m->arrClass;
	*/
	
	/*
	$dbS = new Model_DbTable_Gen_syntagmes();
	$dbS->utiliseCpt(157638, "s_brouc");
	$dbS->ajouter(array("id_dico"=>4,"lib"=>"test"));
	$arr = $dbS->findByIdConcept(56203, "4");
	*/
		
	/* 
	$dbODU = new Model_DbTable_Gen_oeuvresxdicosxutis();
	$dbODU->remove(55);
	$dbODU->findByIdOeu(6);
	*/
	
	/*
	$dbV = new Model_DbTable_Gen_verbes();
	$arr = $dbV->findByIdConcept('60298');
	$dbV->ajouter(array("id_dico"=>102,"lib"=>"bidouiller","type"=>"v"),array("elision"=>"0","id_conj"=>"771","id_dico"=>102,"prefix"=>"bidouille"));	
	*/
	
	/*
	$gdata = new Flux_Gdata("projetgapaii@gmail.com","HakNasSam");
	$gdata->saveSpreadsheetsDico();
	*/
	/*
	$dbGen = new Model_DbTable_Gen_generateurs();
	$dbGen->remove(192667, 157623);
	*/
	
	/*
	$dbA = new Model_DbTable_Gen_adjectifs();
	$dbA->editMulti(array(array("id_adj"=>"19887"
		,"val"=>array("f_p"=>"es", "f_s"=>"e", "m_p"=>"s", "prefix"=>"cramoisi")
		)));
	*/
		
	
}catch (Zend_Exception $e) {
  echo "<h1>Erreur d'exécution</h1>
  <h2>".$this->message."</h2>
  <h3>Exception information:</h3>
  <p><b>Message:</b>".$this->exception->getMessage()."</p>
  <h3>Stack trace:</h3>
  <pre>".$this->exception->getTraceAsString()."</pre>
  <h3>Request Parameters:</h3>
  <pre>".var_export($this->request->getParams(), true)."</pre>";
}
echo "<br/>FIN du TEST<br/>";
