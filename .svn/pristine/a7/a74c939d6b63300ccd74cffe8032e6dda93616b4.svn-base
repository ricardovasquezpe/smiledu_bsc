<?php
class M_combo extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getAllCombos(){
	    $sql = "SELECT c.desc_combo,
	                   c.grupo,
	                   CASE WHEN c.flg_estado = 1 THEN 'checked'
	                        ELSE '' END AS estado
	              FROM combo_tipo c 
	             WHERE c.valor = '0'
	          ORDER BY c.desc_combo";
	    
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	function insertCombo($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_INSERT_ERROR;
	    try {
	        $this->db->insert('combo_tipo',$arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_INSERT_SUCCESS;
	    }catch (Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    
	    return $rpt;
	}
	
	function lastGrupoCombo(){
	    $sql = "SELECT MAX(grupo) as grupo
	              FROM combo_tipo";
	    
	    $result = $this->db->query($sql);
	    return $result->row()->grupo;
	}
	
	function validateTitutloRepetido($titulo){
	    $sql = "SELECT COUNT(1) as cant
	              FROM combo_tipo
	             WHERE UPPER(desc_combo) = UPPER(?)
	               AND valor = '0'";
	    
	    $result = $this->db->query($sql, array($titulo));
	    return $result->row()->cant;
	}
	
	function getOpcionesByGrupo($grupo){
	    $sql = "SELECT c.desc_combo,
	                   c.grupo,
	                   c.valor
	              FROM combo_tipo c
	             WHERE c.grupo = ?";
	    
	    $result = $this->db->query($sql, array($grupo));
	    return $result->result();
	}
	
	function deleteOpcion($grupo, $valor){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_INSERT_ERROR;
	    try{
	        $this->db->where('grupo', $grupo);
	        $this->db->where('valor', $valor);
	        $this->db->delete('combo_tipo');
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = 'Se elimino correctamente';
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function insertOpcion($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_INSERT_ERROR;
	    try{
	        $this->db->insert('combo_tipo', $arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_INSERT_SUCCESS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getLastOpcionByGrupo($grupo){
	    $sql = "SELECT MAX(valor) as valor
	              FROM combo_tipo
	              WHERE grupo = ?";
	    
	    $result = $this->db->query($sql, array($grupo));
	    $valor = 1;
        if($result->row() != null){
            $valor = $result->row()->valor;
        }
	    return $valor;
	}
	
	function updateOpcionByGrupoValor($data, $grupo, $valor){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = "error";
	    try{
	        $this->db->where('grupo',$grupo);
	        $this->db->where('valor',$valor);
	        $this->db->update('combo_tipo',$data);
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-002)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = "Se actualizó correctamente";
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
}