<?php
//LAST-CODE: MU-002
/**
 * 
 * @author czavalacas
 *
 */
class M_certi_ingles_alumno extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getAlumnosCertificados($idAula){
		$sql = "SELECT ac.nid_alumno_certificacion,
                       pa.__id_persona, 
                       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona )) AS nombrecompleto,
                       pa.__id_aula, 
                       ac.estado 
                  FROM persona p,
                       persona_x_aula pa LEFT JOIN  alumno_certificacion ac ON (ac.__id_alumno = pa.__id_persona AND ac.year = (Select extract (year from now())))		
                 WHERE pa.__id_aula   = ?
                   AND p.nid_persona = pa.__id_persona
                 ORDER BY nombrecompleto ASC";
		$result = $this->db->query($sql, array($idAula));
		return $result->result();
	}
	
	function updateDataCertiInglesAlumnos($arrayInsert,$arrayDelete,$arrayUpdate){
		$data['error']    = EXIT_ERROR;
		$data['msj']      = null;
		$data['cabecera'] = CABE_ERROR;
		$this->db->trans_begin();
		try {
			if(count($arrayUpdate) != 0){
				$cont = 0;
				$this->db->update_batch('alumno_certificacion',$arrayUpdate, 'nid_alumno_certificacion');
				$cont = $cont + $this->db->affected_rows();
				if($cont != count($arrayUpdate) ) {
					$this->db->trans_rollback();
					throw new Exception('(MS-002)');
				}
			}
			if(count($arrayInsert) != 0){
				$cont2 = 0;
				$this->db->insert_batch('alumno_certificacion',$arrayInsert);
				$cont2 = $cont2 + $this->db->affected_rows();
				if($cont2 != count($arrayInsert) ) {
					$this->db->trans_rollback();
					throw new Exception('(MS-003)');
				}
			}
			if(count($arrayDelete) != 0){
				$cont3 = 0;
				$this->db->where_in('nid_alumno_certificacion', $arrayDelete);
				$this->db->delete('alumno_certificacion');
				$cont3 = $cont3 + $this->db->affected_rows();
				if($cont3 != count($arrayDelete) ) {
					$this->db->trans_rollback();
					throw new Exception('(MS-003)');
				}
			}			
			if ($this->db->trans_status() === FALSE) {
				$data['msj'] = '(MS-001)';
				$this->db->trans_rollback();
			}else {
				$data['error']    = EXIT_SUCCESS;
				$data['msj']      = MSJ_INS;
				$data['cabecera'] = CABE_INS;
				$this->db->trans_commit();
			}
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
			$this->db->trans_rollback();
		}
		return $data;
	}
}