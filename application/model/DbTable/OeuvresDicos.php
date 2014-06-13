<?php
/**
 * Ce fichier contient la classe Gen_oeuvres_dicos.
 *
 * @copyright  2010 Samuel Szoniecky
 * @license    "New" BSD License
*/


/**
 * Classe ORM qui représente la table 'gen_oeuvres_dicos'.
 *
 * @copyright  2010 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_OeuvresDicos extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'gen_oeuvres_dicos';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'id_oeu';

    
    /**
     * Vérifie si une entrée Gen_oeuvres_dicos existe.
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
     * Ajoute une entrée Gen_oeuvres_dicos.
     *
     * @param array $data
     * @param boolean $existe
     *  
     * @return integer
     */
    public function ajouter($data, $existe=true)
    {
    	$id=false;
    	if($existe)$id = $this->existe($data);
    	if(!$id){
    	 	$id = $this->insert($data);
    	}
    	return $id;
    } 
           
    /**
     * Recherche une entrée Gen_oeuvres_dicos avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
        $this->update($data, 'gen_oeuvres_dicos.id_oeu = ' . $id);
    }
    
    /**
     * Recherche une entrée Gen_oeuvres_dicos avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
        $this->delete('gen_oeuvres_dicos.id_oeu = ' . $id);
    }
    
    /**
     * Récupère toutes les entrées Gen_oeuvres_dicos avec certains critères
     * de tri, intervalles
     */
    public function getAll($order=null, $limit=0, $from=0)
    {
        $query = $this->select()
                    ->from( array("gen_oeuvres_dicos" => "gen_oeuvres_dicos") );
                    
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
    
    /*
     * Recherche une entrée Gen_oeuvres_dicos avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $id_oeu
     *
     * @return array
     */
    public function findByIdOeuvre($id_oeu)
    {
		$str = "select d.id_dico, d.type, d.nom
			from gen_dicos d
			inner join gen_oeuvres_dicos od on od.id_dico = d.id_dico and od.id_oeu = ".$id_oeu;
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$stmt = $db->query($str);
    	$stmt->setFetchMode(Zend_Db::FETCH_NUM);

        return $stmt->fetchAll()->toArray();    	
    }
    /*
     * Recherche une entrée Gen_oeuvres_dicos avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $id_dico
     *
     * @return array
     */
    public function findByIdDico($id_dico)
    {

		$str = "select d.id_dico, d.type, d.nom
			from gen_dicos d
			inner join gen_oeuvres_dicos od on od.id_dico = d.id_dico and od.id_dico = ".$id_dico;
    	$db = Zend_Db_Table::getDefaultAdapter();
    	$stmt = $db->query($str);
    	$stmt->setFetchMode(Zend_Db::FETCH_NUM);

        return $stmt->fetchAll()->toArray();    	
    }
    
    
}
