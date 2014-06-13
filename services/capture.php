<?php
set_time_limit(10000);
ini_set("memory_limit",'1600M');

$temps_debut = microtime(true);


require_once('../application/configs/config.php');
require_once('../library/odtphp/odf.php');

if(isset($_GET['nb']))
	$nb = $_GET['nb'];
else
	$nb = 1;

if(isset($_GET['gen']))
	$gen = $_GET['gen'];
else
	$gen = 'chansons';
	
if($gen=="bios"){
	$id = 150144;
	$dico = 93;
}
if($gen=="chansons"){
	$id = 150248;
	$dico = 94;
}
if($gen=="critiques"){
	$id = 120613;
	$dico = 82;
}	
if($gen=="twitts"){
	$id = 150342;
	$dico = 96;
}	


$getHtml = true;	
if(isset($_GET['odf'])){
	$getOdf = true;
	$getHtml = false;
}else
	$getOdf = false;
if(isset($_GET['xml'])){
	$getXml = true;
	$getHtml = false;
}else
	$getXml = false;
	
if(isset($_GET['err']))
	$err = true;
else
	$err = false;

if(isset($_GET['dicos']))
	$dicos = $_GET['dicos'].",".$dico;
else
	$dicos = "34,".$dico;

if(isset($_GET['force']))
	$force = true;
else
	$force = false;
	
	
if($getHtml)header ('Content-type: text/html; charset=utf-8');
if($getXml)header ('Content-type: text/xml; charset=utf-8');


	
try {

	$application->bootstrap();


	//récupère le nombre de fichier
	$nbFic = count_files(ROOT_PATH.'/data/capture/','txt','');
	
	//vérifie si on fait un fichier
	if($getOdf){
		$pathModel = ROOT_PATH."/data/models/capture.odt";
		$odf = new odf($pathModel);
	}
	
	$arrDicos = array(
		"concepts"=>$dicos
		,"syntagmes"=>4
		,"pronoms_complement"=>13
		,"conjugaisons"=>44
		,"pronoms"=>"13,14"
		,"déterminants"=>46
		,"negations"=>16);
		
	//récupère la définition des gènes
	$dbConcepts = new Model_DbTable_Concepts();
	$Rowset = $dbConcepts->find($id);

	$parent = $Rowset->current();
	//ajout des généreteurs
	$defGenes = $parent->findManyToManyRowset('Model_DbTable_Generateurs','Model_DbTable_ConceptsGenerateurs');
	$arrGenes = $defGenes->toArray();
	
	//nombre de chansons possible
	$nbGenes = count($arrGenes)-1;
	
	if($getOdf)$chansons = $odf->setSegment('chansons');
	if($getXml)$xml = "<Directory>";
	for ($itr = 0; $itr < $nb; $itr++) {
		try {
			$num = mt_rand(0, $nbGenes);
			$gene = $arrGenes[$num];
			
			if($getHtml && $err){
				echo "<br/>".($itr+1)." sur ".$nb;
				echo " texte ".$num." sur ".$nbGenes."<br/>";
			}
			
			if($getOdf)$chansons->setVars('chansons_titre', getipv6());

			//calcul une génération
			$moteur = new Gen_Moteur("",$force);
			$moteur->arrDicos = $arrDicos;	
			//$moteur->finLigne = "\r\n";
			$moteur->showErr = $err;
			if($getHtml && $err)echo $gene['valeur']."<br/><br/>";

			$moteur->Generation($gene['valeur']);	
			
			if($gen=="chansons" || $gen=="bios"  || $gen=="critiques" ){
				$finTitre = strpos($moteur->texte,"<br/>");
				$titre = ucfirst(substr($moteur->texte,0,$finTitre));
				$texte = substr($moteur->texte,$finTitre);
			}else{
				$texte = $moteur->texte;				
			}
			
			if($getHtml){
				if($titre) echo "<H1>".$titre."</H1>";
				echo $texte."<br/>";
			}
			if($getXml){
				if($gen=="chansons")
					$xml .= "<chanson><paroles><![CDATA[".$texte."]]></paroles><titre1>".getipv6()."</titre1><titre2><![CDATA[".$titre."]]></titre2></chanson>";
				if($gen=="bios")
					$xml .= "<bio><codeIP>".getipv6()."</codeIP><titre><![CDATA[".$titre."]]></titre><description><![CDATA[".$texte."]]></description></bio>";
				if($gen=="critiques")
					$xml .= "<critique><codeIP>".getipv6()."</codeIP><titre><![CDATA[".$titre."]]></titre><description><![CDATA[".$texte."]]></description></critique>";
			}
			
			if($getOdf){
				//ajoute le texte au doc
				$chansons->setVars('chansons_texte', str_replace("<br/>","\n",$moteur->texte));
				//enregistre l'item dans le document
				$chansons->merge();
			}
						
			
			if($getHtml && $err){
				//calcul le temps d'execution
				$temps_fin = microtime(true);
				echo '<br/>Temps d\'execution : '.round($temps_fin - $temps_debut, 4)." s. ";
				$nomFic = "texte_".$gen."_".$id."_".($nbFic+$itr);
				SaveFile(ROOT_PATH.'/data/capture/'.$nomFic.".txt",str_replace("<br/>","\n",$moteur->texte));
				echo " <a href='".WEB_ROOT.'/data/capture/'.$nomFic.".txt'>".$nomFic."</a>";
				SaveFile(ROOT_PATH.'/data/capture/'.$nomFic."_detail.html",$moteur->detail);
				echo " <a href='".WEB_ROOT.'/data/capture/'.$nomFic."_detail.html'> detail</a><br/>";
			}
		}catch (Exception $e) {
			echo "Récupère exception: " . get_class($e) . "<br/>";
		    echo "Message: " . $e->getMessage() . "<br/>";
		}
			
	}
	//finalise le document
	if($getOdf){
		$odf->mergeSegment($chansons);
		$odf->exportAsAttachedFile();
	}
	if($getHtml && $err){
		echo "<br/><br/>FIN DE GENERATION ".$itr." sur ".$nb;
	}
	if($getXml){
		$xml .= "</Directory>";
		echo $xml;
	}
/*
	*/
	
		
}catch (Exception $e) {
    echo "Message: " . $e->getMessage() . "\n";
}
	
function SaveFile($path,$texte){

	$fic = fopen($path, "w");
	if($fic){
		fwrite($fic, $texte);		
    	fclose($fic);
	}

}

function getipv6(){
	$listAlpha = 'abcde0123456789012345678901234567890';
	return $listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		." : "
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		.$listAlpha[mt_rand(0, 35)]
		;
	//2001:0db8:0000:85a3:0000:0000:ac1f:8001
}

function count_files($folder, $ext, $subfolders)
{
	//merci à http://www.phpsources.org/scripts51-PHP.htm
	
     // on rajoute le / à la fin du nom du dossier s'il ne l'est pas
     if(substr($folder, -1) != '/')
        $folder .= '/';
     
     // $ext est un tableau?
     $array = 0;
     if(is_array($ext))
        $array = 1;

     // ouverture du répertoire
     $rep = @opendir($folder);
     if(!$rep)
        return -1;
        
     $nb_files = 0;
     // tant qu'il y a des fichiers
     while($file = readdir($rep))
     {
        // répertoires . et ..
        if($file == '.' || $file == '..')
         continue;
        
        // si c'est un répertoire et qu'on peut le lister
        if(is_dir($folder . $file) && $subfolders)
            // on appelle la fonction
         $nb_files += count_files($folder . $file, $ext, 1);
        // vérification de l'extension avec $array = 0
        else if(!$array && substr($file, -strlen($ext))== $ext)
         $nb_files++;
        // vérification de l'extension avec $array = 1   
        else if($array==1 && in_array(strtolower(substr(strrchr($file,"."),1)), $ext))
         $nb_files++;
     }
     
     // fermeture du rep
     closedir($rep);
     return $nb_files;
} 

?>	