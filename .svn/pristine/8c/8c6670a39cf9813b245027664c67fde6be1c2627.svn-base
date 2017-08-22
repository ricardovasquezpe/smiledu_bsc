<?php
class M_simulacro extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    function getAlumnosPostulantesByAula($idUniv, $id_aula, $nroSimulacro) {
        $nroSimulacro = $nroSimulacro == false ? null : $nroSimulacro;
        $sql = "SELECT pa.__id_persona,
                       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) AS nombres,
                       us.id_simulacro,
                       us.flg_apto,
                       us.puntaje
                  FROM persona p,
        	           persona_x_aula pa LEFT JOIN univ_simulacro us ON (pa.__id_persona = us.id_alumno AND us.__id_universidad = ? AND us.nro_simulacro = ?)
                 WHERE pa.__id_aula  = ?
                   AND p.nid_persona = pa.__id_persona
                 ORDER BY p.nom_persona ASC";
        $result = $this->db->query($sql, array($idUniv, $nroSimulacro, $id_aula));
        return $result->result();
    }
    
    function insertUpdateSimulacro($arrayDatos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                if($dato['id_simulacro'] == null) {//insert
                    unset($dato['id_simulacro']);
                    $this->db->insert('univ_simulacro', $dato);
                    $cont = $cont + $this->db->affected_rows();
                } else if($dato['id_simulacro'] != null && $dato['checkParticipo'] == true) {//update

                    $this->db->where('id_simulacro', $dato['id_simulacro']);
                    unset($dato['id_simulacro']);
                    unset($dato['checkParticipo']);
                    $this->db->update('univ_simulacro', $dato);
                    $cont = $cont + $this->db->affected_rows();
                } else if($dato['id_simulacro'] != null && $dato['checkParticipo'] == false){                 
                    $this->db->where("id_simulacro", $dato['id_simulacro']);       
                    unset($dato['checkParticipo']);
                    $this->db->delete("univ_simulacro");
                    $cont = $cont + $this->db->affected_rows();
                }
            }
            
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MS-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MS-001)';
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
    
    function getNrosSimulacros($idSede, $idGrado, $idUniv) {
        $sql = "SELECT nro_simulacro
                  FROM univ_simulacro
                 WHERE id_sede          = ?
                   AND id_grado         = ?
                   AND year_academico   = (SELECT EXTRACT(YEAR FROM now()))
                   AND __id_universidad = ?
                GROUP BY nro_simulacro";
        $result = $this->db->query($sql, array($idSede, $idGrado, $idUniv));
        return $result->result();
    }
    
    function getNextNroSimulacro($idSede, $idGrado, $idUniv) {
        $sql = "SELECT COALESCE(MAX(nro_simulacro), 0) + 1 next_simu
                  FROM univ_simulacro
                 WHERE id_sede          = ?
                   AND id_grado         = ?
                   AND year_academico   = (SELECT EXTRACT(YEAR FROM now()))
                   AND __id_universidad = ? LIMIT 1";
        $result = $this->db->query($sql, array($idSede, $idGrado, $idUniv));
        return ($result->num_rows() == 1) ? $result->row()->next_simu : null;
    }
    
    function getCountAlumnosSimulacro($idSede, $idGrado, $idUniv) {
        $sql = "SELECT COUNT(1) cant
                  FROM univ_simulacro
                 WHERE id_sede          = ?
                   AND id_grado         = ?
                   AND year_academico   = (SELECT EXTRACT(YEAR FROM now()))
                   AND __id_universidad = ?";
        $result = $this->db->query($sql, array($idSede, $idGrado, $idUniv));
        return ($result->num_rows() == 1) ? $result->row()->cant : null;
    }
}