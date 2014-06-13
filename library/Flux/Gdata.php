<?php
/**
 * Classe qui gère les flux Gdata
 *
 * @copyright  2011 Samuel Szoniecky
 * @license    "New" BSD License
 * 
 */
class Flux_Gdata extends Flux_Site{

	var $client;
	var $docs;		
	var $feed;
 	
	public function __construct($login=null, $pwd=null, $idBase=false)
    {
    	parent::__construct($idBase);
    	
    	$this->login = $login;
    	$this->pwd = $pwd;
    }
	
	function getSpreadsheets(){
		$c = str_replace("::", "_", __METHOD__)."_".md5($this->login); 
	   	$this->feed = $this->cache->load($c);
        if(!$this->feed){
			$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
		    $this->client = Zend_Gdata_ClientLogin::getHttpClient($this->login, $this->pwd, $service);
		    $spreadsheetService = new Zend_Gdata_Spreadsheets($this->client);
			$this->feed = $spreadsheetService->getSpreadsheetFeed();
			$this->cache->save($this->feed, $c);
        }	
	}

	function getSpreadsheetsContents(){
		$c = str_replace("::", "_", __METHOD__)."_".md5($this->login); 
	   	$arr = $this->cache->load($c);
        if(!$arr){
			$this->getSpreadsheets();
			$i = 0;
			foreach ($this->feed->entry as $ss){
				if($i<10000){					
					$wss = $ss->getWorksheets();
					$ssName = $ss->getTitleValue();
					//TODO récupérer l'url du classeur
					$ssUrl = "";
					$arrWs = array();
					foreach ($wss as $ws){
						$wsName = $ws->getTitleValue();
						//TODO récupérer l'url de la feuille
						$wsUrl = "";
						$arrWs[] = array('titre'=>$wsName,'url'=>$wsUrl,'values'=>$ws->getContentsAsRows());		
					}
					$arr[] = array('titre'=>$ssName,'url'=>$ssUrl,'feuilles'=>$arrWs); 
				}
				$i++;
			}
			$this->cache->save($arr, $c);
        }	
		return $arr;
	}
	
	function saveSpreadsheetsDico($docTronc=-1){
		
		$arr = $this->getSpreadsheetsContents();
		if($arr){
			if(!$this->dbD)$this->dbD = new Model_DbTable_Dicos($this->db);
			if(!$this->dbC)$this->dbC = new Model_DbTable_Concepts($this->db);
			if(!$this->dbG)$this->dbG = new Model_DbTable_Generateurs($this->db);
			if(!$this->dbCG)$this->dbCG = new Model_DbTable_ConceptsGenerateurs($this->db);
			
			$this->getUser(array("login"=>$this->login,"flux"=>"gdata"));
			
			$date = new Zend_Date();
			
			foreach ($arr as $nSS=>$vSS){
				//ajouter un dico correspondant au classeur
				//$idD = $this->dbD->ajouter(array("url"=>$vSS['url'],"titre"=>$vSS['titre'],"tronc"=>$docTronc,"pubDate"=>$date->get("c")));
				
				$i = 0;
				$nomDico="";
				$nomConcept="";				
				foreach ($vSS['feuilles'] as $nWS=>$vWS) {
					$j = 0;
					//pour chaque ligne de la feuille
					foreach ($vWS['values'] as $v) {
						if($nomDico!=$v['dictionnaire']){
							//création du dictionnaire
							$idD = $this->dbD->ajouterDico('aucune', $v['dictionnaire'], "concepts","","",$v['langue']);								
							$nomDico=$v['dictionnaire'];
						}
						if($nomConcept!=$v['concept']){
							//création du concept
							$idC = $this->dbC->ajouterConcept($idD, $v['concept'], "dis");								
							$nomConcept=$v['concept'];				
						}
						//création du générateur
						$idG = $this->dbG->ajouterGenerateur($idD, $v['proverbe']);
						//lien avec le concept
						$idG = $this->dbCG->ajouterConceptGenerateur($idC, $idG);
						
					}
					$i += $j;					
				}
			}
	
			
		}		
		
		
	}
	
}