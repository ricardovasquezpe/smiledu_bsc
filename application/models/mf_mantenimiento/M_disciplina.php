<?php
//LAST-CODE: MU-002
/**
 * 
 * @author czavalacas
 *
 */
class M_disciplina extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getDisciplinas(){
	    $sql = "SELECT    dd.id_detalle_disciplina,
	    				  d.tipo_disciplina, 
						  d.desc_disciplina, 
						  n.desc_nivel, 
						  dd.nivel_competitivo, 
						  INITCAP(dd.organizador) organizador, 
					      dd.fecha, 
						  dd.nro_copas, 
						  INITCAP(p.ape_pate_pers) ape_pate_pers, 
						  INITCAP(p.ape_mate_pers) ape_mate_pers,
	    				  INITCAP(p.nom_persona) nom_persona 
				FROM 	  disciplina_detalle dd, 
						  disciplina d, 
						  nivel n, 
						  persona p				
				WHERE 	  dd.__id_disciplina	=	d.id_disciplina
				AND 	  n.nid_nivel			=	dd.__id_nivel
				AND 	  p.nid_persona			=	dd.__id_docente
	    		ORDER BY  fecha DESC";
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	function getDisciplinasByTipoDisciplina($tipo){
		$sql = "SELECT id_disciplina, 
					   desc_disciplina
				  FROM disciplina
		         WHERE tipo_disciplina	=	?
	    	  ORDER BY desc_disciplina ASC";
		$result = $this->db->query($sql,array($tipo));
		return $result->result();		
	}
	
	function insertDisciplinaDetalle($data){
		$rpta['error'] = EXIT_ERROR;
		$rpta['msj']   = null;
		try {
			$this->db->insert('disciplina_detalle',$data);
			if ($this->db->trans_status() === FALSE) {
				throw new Exception(ANP);
			}
				$rpta['error']     = EXIT_SUCCESS;
				$rpta['msj']       = CABE_INS;
				$rpta['cabecera']  = CABE_INS;			
		}catch(Exception $e){
			$rpta['msj'] = $e->getMessage();
		}
		return $rpta;
	}
	
	function deleteTablaByCampo($tabla,$campo_id,$pk){
		$rpta['error'] = EXIT_ERROR;
		$rpta['msj']   = null;
		try {
			$this->db->where($campo_id, $pk);
			$this->db->delete($tabla);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				throw new Exception(ANP);
			}
			$rpta['error']     = EXIT_SUCCESS;
			$rpta['msj']       = MSJ_DEL;
			$rpta['cabecera']  = CABE_DEL;
		}catch(Exception $e){
			$rpta['msj'] = $e->getMessage();
		}
		return $rpta;
	}
	
	function existeNivel($idNivel) {
		$sql = "SELECT COUNT(1)
                  FROM nivel
                 WHERE nid_nivel	=	?";
		$result = $this->db->query($sql,array($idNivel));
		return ($result->row()->count);
	}
	
	function existeDocente($idPersona) {
		$sql = 'SELECT COUNT(1)
                  FROM persona p , 
    				   persona_x_rol pr
                 WHERE p.nid_persona = pr.nid_persona
                   AND pr.nid_rol	 = '.ID_ROL_DOCENTE.'
                   AND p.nid_persona = ?';
		$result = $this->db->query($sql,array($idPersona));
		return ($result->row()->count);
	}
	
	function existeDisciplina($idDisciplina) {
		$sql = "SELECT COUNT(1)
                  FROM disciplina
                 WHERE id_disciplina	=	?";
		$result = $this->db->query($sql,array($idDisciplina));
		return ($result->row()->count);
	}

}