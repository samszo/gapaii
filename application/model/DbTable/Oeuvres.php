<?php
/**
 * Ce fichier contient la classe Gen_oeuvres.
 *
 * @copyright  2008 Gabriel Malkas
 * @copyright  2010 Samuel Szoniecky
 * @license    "New" BSD License
*/


/**
 * Classe ORM qui représente la table 'gen_oeuvres'.
 *
 * @copyright  2010 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Oeuvres extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'gen_oeuvres';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'id_oeu';

    
    /**
     * Vérifie si une entrée Gen_oeuvres existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('id_oeu'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_oeu; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée Gen_oeuvres.
     *
     * @param array $data
     * @param boolean $existe
     *  
     * @return integer
     */
    public function ajouter($data, $existe=false)
    {
    	$id=false;
    	if($existe)$id = $this->existe($data);    	
    	if(!$id){
    		$d = array(
	            'lib' => $data["lib"],
	            'maj' => new Zend_Db_Expr('NOW()')
	        );    		
    	 	$id = $this->insert($d);
    	}
    	return $id;
    } 
           
    /**
     * Recherche une entrée Gen_oeuvres avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
    	$d = array(
	    	'lib' => $data["lib"],
	    	'maj' => new Zend_Db_Expr('NOW()')
	    );    		
    	$this->update($d, 'gen_oeuvres.id_oeu = ' . $id);
    }
    
    /**
     * Recherche une entrée Gen_oeuvres avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
        $this->delete('gen_oeuvres.id_oeu = ' . $id);
    }
    
    /**
     * Récupère toutes les entrées Gen_oeuvres avec certains critères
     * de tri, intervalle
     * 
     * @param string $order
     * @param integer $limit
     * @param integer $from
     *
     * @return array 
     */
    public function getAll($order=null, $limit=0, $from=0)
    {
        $query = $this->select()
                    ->from( array("gen_oeuvres" => "gen_oeuvres") );
                    
        if($order != null)
        {
            $query->order($order);
        }

        if($limit != 0)
        {
            $query->limit($limit, $from);
        }

        return $this->fetchAll($query)->toArray();
    }

    /**
     * Récupère les spécifications des colonnes Gen_oeuvres 
     * 
     *
     * @return array
     * 
     */
    public function getCols(){

    	$arr = array("cols"=>array(
    	   	array("titre"=>"id_oeu","champ"=>"id_oeu","visible"=>true),
    	array("titre"=>"lib","champ"=>"lib","visible"=>true),
    	array("titre"=>"maj","champ"=>"maj","visible"=>true),
        	
    		));    	
    	return $arr;
		
    }     
    
    /**
     * Recherche une entrée Gen_oeuvres avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $id_oeu
     *
     * @return array
     */
    public function findByIdOeu($id_oeu)
    {
        $query = $this->select()
                    ->from( array("g" => "gen_oeuvres") )                           
                    ->where( "g.id_oeu = ?", $id_oeu );

        return $this->fetchAll($query)->toArray(); 
    }
    /**
     * Recherche une entrée Gen_oeuvres avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $lib
     *
     * @return array
     */
    public function findByLib($lib)
    {
        $query = $this->select()
                    ->from( array("g" => "gen_oeuvres") )                           
                    ->where( "g.lib = ?", $lib );

        return $this->fetchAll($query)->toArray(); 
    }
    /**
     * Recherche une entrée Gen_oeuvres avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param datetime $maj
     *
     * @return array
     */
    public function findByMaj($maj)
    {
        $query = $this->select()
                    ->from( array("g" => "gen_oeuvres") )                           
                    ->where( "g.maj = ?", $maj );

        return $this->fetchAll($query)->toArray(); 
    }
    
    
}
