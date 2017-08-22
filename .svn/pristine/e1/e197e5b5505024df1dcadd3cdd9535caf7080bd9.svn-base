<?php
//LAST-CODE: MU-002
class M_roles_permisos_sistemas extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getAllSistemas($idRol) {
        $sql = "SELECT s.nid_sistema,
                       rs.nid_rol,
                       rs.flg_acti,
                       s.desc_sist
                  FROM sistema s LEFT JOIN rol_x_sistema rs ON ( s.nid_sistema = rs.nid_sistema AND rs.nid_rol= ?)";      
        	    $result = $this->db->query($sql, array($idRol));
	    return $result->result();
    }

    function getAllPermisos($idRol,$idSist){
        $sql = "SELECT sp.nid_sistema,
                       sp.nid_permiso,
                       sp.desc_permiso,
                       perm_rol_sist.nid_rol
                  FROM sist_permiso sp LEFT JOIN (SELECT rs.nid_sistema,
                				         rs.nid_rol,
                				         rsp.nid_permiso
                	 FROM rol_x_sistema rs,
                          rol_x_sist_permiso rsp
                    WHERE rs.flg_acti    = '".FLG_ACTIVO."'
                      AND rsp.flg_acti   = '".FLG_ACTIVO."'
                      AND rs.nid_rol     = ?
                	  AND rsp.nid_rol    = ?
                	  AND rs.nid_rol     = rsp.nid_rol
                	  AND rs.nid_sistema = ?
                	  AND rs.nid_sistema = rsp.nid_sistema ) perm_rol_sist         				         				    			   
                	ON (sp.nid_sistema = perm_rol_sist.nid_sistema AND sp.nid_permiso = perm_rol_sist.nid_permiso)
                 WHERE sp.nid_sistema = ?";  
        $result = $this->db->query($sql, array($idRol,$idRol,$idSist,$idSist));
        return $result->result();
    }
    
    function getflgActiRolSist($idRol,$idSist){
        $sql ="SELECT flg_acti 
                 FROM rol_x_sistema 
                WHERE nid_rol=? 
                  AND nid_sistema=?";
        $result = $this->db->query($sql, array($idRol,$idSist));
        if($result->num_rows() > 0) {
            return ($result->row()->flg_acti);
        }else {
            return null;
        }
    }
    
    
    function InsertupdateRolSistPerm($arrayDatos){
    	$data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    $this->db->trans_begin();
	    try {
	        $cont = 0;
	        foreach ($arrayDatos as $dato) {	      
	            if($dato['ACCION'] == 'I' ) {//insert
	                unset($dato['ACCION']);
	                $this->db->insert('rol_x_sistema', $dato);
	                $cont = $cont + $this->db->affected_rows();	              
	            } else if($dato['ACCION'] == 'U'){	            
	                unset($dato['ACCION']);
	                $this->db->where('nid_rol', $dato['nid_rol']);
	                $this->db->where('nid_sistema', $dato['nid_sistema']);	  
	                unset($dato['nid_rol']);
	                unset($dato['nid_sistema']);	                
	                $this->db->update('rol_x_sistema', $dato);
	                $cont = $cont + $this->db->affected_rows();
	            }        
	        }
	        if($cont != count($arrayDatos) ) {
	            $this->db->trans_rollback();
	            throw new Exception('(MA-002)');
	        }
	        if ($this->db->trans_status() === FALSE) {
	            $data['msj']   = '(MA-001)';
	            $this->db->trans_rollback();
	            
	        }else {
	            $data['error']     = EXIT_SUCCESS;
	            $data['msj']       = CABE_UPT;
	            $this->db->trans_commit();
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getflgActiSistPerm($idRol,$idSist,$idPerm){
	    $sql ="SELECT flg_acti
                 FROM rol_x_sist_permiso
                WHERE nid_rol=?
                  AND nid_sistema=?
	              AND nid_permiso=?";
	    $result = $this->db->query($sql, array($idRol,$idSist,$idPerm));
	    if($result->num_rows() > 0) {
	        return ($result->row()->flg_acti);
	    }else {
	        return null;
	    }
	}

	function InsertupdateRolSistPermv2($arrayDatos){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    $this->db->trans_begin();
	    try {
	        $cont = 0;
	        foreach ($arrayDatos as $dato) {
	            if($dato['ACCION'] == 'I' ) {//insert
	                unset($dato['ACCION']);
	                $this->db->insert('rol_x_sist_permiso', $dato);
	                $cont = $cont + $this->db->affected_rows();
	            } else if($dato['ACCION'] == 'U'){
	                unset($dato['ACCION']);
	                $this->db->where('nid_sistema', $dato['nid_sistema']);
	                $this->db->where('nid_rol', $dato['nid_rol']);
	                $this->db->where('nid_permiso', $dato['nid_permiso']);
	                unset($dato['nid_sistema']);
	                unset($dato['nid_rol']);
	                unset($dato['nid_permiso']);
	                $this->db->update('rol_x_sist_permiso', $dato);
	                $cont = $cont + $this->db->affected_rows();
	            }
	        }
	        if($cont != count($arrayDatos) ) {
	            $this->db->trans_rollback();
	            throw new Exception('(MA-002)');
	        }
	        if ($this->db->trans_status() === FALSE) {
	            $data['msj']   = '(MA-001)';
	            $this->db->trans_rollback();
	             
	        }else {
	            $data['error']     = EXIT_SUCCESS;
	            $data['msj']       = CABE_UPT;
	            $this->db->trans_commit();
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}