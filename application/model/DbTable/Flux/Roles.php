<?php
/**
 * Ce fichier contient la classe flux_roles.
 *
 * @copyright  2013 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Flux_Roles extends Zend_Db_Table_Abstract
{
    
    /**
     * Nom de la table.
     */
    protected $_name = 'flux_roles';
    
    /**
     * Clef primaire de la table.
     */
    protected $_primary = 'id_role';

    
    /**
     * Vérifie si une entrée flux_roles existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('id_role'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_role; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée flux_roles.
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
     * Recherche une entrée flux_roles avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
   	
    	$this->update($data, 'flux_roles.id_role = ' . $id);
    }
    
    /**
     * Recherche une entrée flux_roles avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
    	$this->delete('flux_roles.id_role = ' . $id);
    }
    
    /**
     * Récupère toutes les entrées flux_roles avec certains critères
     * de tri, intervalles
     */
    public function getAll($order=null, $limit=0, $from=0)
    {
   	
    	$query = $this->select()
                    ->from( array("flux_roles" => "flux_roles") );
                    
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
     * Recherche une entrée flux_roles avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $id_role
     *
     * @return array
     */
    public function findById_role($id_role)
    {
        $query = $this->select()
                    ->from( array("g" => "flux_roles") )                           
                    ->where( "g.id_role = ?", $id_role );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée flux_roles avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $lib
     *
     * @return array
     */
    public function findByLib($lib)
    {
        $query = $this->select()
                    ->from( array("g" => "flux_roles") )                           
                    ->where( "g.lib = ?", $lib );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée flux_roles avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $inherit
     *
     * @return array
     */
    public function findByInherit($inherit)
    {
        $query = $this->select()
                    ->from( array("g" => "flux_roles") )                           
                    ->where( "g.inherit = ?", $inherit );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée flux_roles avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param text $params
     *
     * @return array
     */
    public function findByParams($params)
    {
        $query = $this->select()
                    ->from( array("g" => "flux_roles") )                           
                    ->where( "g.params = ?", $params );

        return $this->fetchAll($query)->toArray(); 
    }
    
    
}
