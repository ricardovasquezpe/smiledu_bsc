<?php
//LAST-CODE: MU-002
/**
 * 
 * @author czavalacas
 *
 */
class M_admision extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getAlumnosPostulantesByAula($idUniv, $id_aula){
	    $sql = "SELECT pa.__id_persona,
	                   CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombres,
                       ad.id_admision,
                       ad.flg_ingreso,
                       ad.puntaje
                  FROM persona p,
        	           persona_x_aula pa LEFT JOIN univ_admision ad ON (pa.__id_persona = ad .id_alumno AND ad .__id_universidad = ?)
                 WHERE pa.__id_aula::text = COALESCE(empty2null(?::text),pa.__id_aula::text) 
                   AND p.nid_persona      = pa.__id_persona
                 ORDER BY p.ape_pate_pers ASC";
	    $result = $this->db->query($sql, array($idUniv, $id_aula));
	    return $result->result();
	}
	
	function insertUpdateAdmision($arrayDatos) {
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    $this->db->trans_begin();
	    try {
	        $cont = 0;
	        foreach ($arrayDatos as $dato) {
	            if($dato['id_admision'] == null) {//insert
	                unset($dato['id_admision']);
	                $this->db->insert('univ_admision', $dato);
	                $cont = $cont + $this->db->affected_rows();
	            } 
	             else if($dato['id_admision'] != null && $dato['checkParticipo'] == true) {//update
	                $this->db->where('id_admision', $dato['id_admision']);
	                unset($dato['id_admision']);
	                unset($dato['checkParticipo']);
	                $this->db->update('univ_admision', $dato);
	                $cont = $cont + $this->db->affected_rows();
	            } 
	            else if($dato['id_admision'] != null && $dato['checkParticipo'] == false){
	                $this->db->where("id_admision", $dato['id_admision']);
	                unset($dato['checkParticipo']);
	                $this->db->delete("univ_admision");
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
	            $data['msj']       = MSJ_INS;
	            $data['cabecera']  = CABE_INS;
	            $this->db->trans_commit();
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

}