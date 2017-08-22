<?php
class M_horario extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    function getCountHorario($idDocente, $idCurso, $idAula) {
        $sql = "SELECT COUNT(1) cnt
                  FROM horario
                 WHERE flg_acti   = 1
                   AND id_docente = ?
                   AND id_curso   = ?
                   AND id_aula    = ?";
        $result = $this->db->query($sql, array($idDocente, $idCurso, $idAula));
        return $result->row()-> cnt;
    }
    
    function registrarHorario($idProfe, $idAula, $idCurso) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $idAreaAcad = $this->m_utils->getById("curso", "nid_area_academica", "nid_curso", $idCurso);
            $aulaData   = $this->m_utils->getSedeNivelGradoFromAula($idAula);
            $datosInsert = array("id_docente"   => $idProfe,
                                 "id_curso"     => $idCurso,
                                 "id_area_aux"  => $idAreaAcad,
                                 "id_aula"      => $idAula,
                                 "id_sede_aux"  => $aulaData['nid_sede'],
                                 "id_nivel_aux" => $aulaData['nid_nivel'],
                                 "id_grado_aux" => $aulaData['nid_grado'],
                                 "flg_acti"     => FLG_ACTIVO);
            $this->db->insert('horario', $datosInsert);
            $insertedID = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE) {
                $data['msj'] = '(MH-001)';
                $this->db->trans_rollback();
            } else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = CABE_INS;
                $data['inserted_id'] = $insertedID;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function borrarHorario($idHorario) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->where("id_horario", $idHorario);
            $this->db->delete('horario');
            if($this->db->affected_rows() != 1) {
                $data['msj'] = 'No se pudo eliminar el horario';
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = CABE_DEL;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getHorarios($flgIdHorario, $idHorario) {
        $sql = "SELECT h.id_horario,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) as docente,
                       CONCAT(c.desc_curso,' / ',ac.desc_area_academica) curso,
                       CONCAT(a.desc_aula,' / ',g.abvr,' ',n.abvr,' / ',s.abvr) aula
                  FROM horario h,
                       persona p,
                       curso   c,
                       aula    a,
                       area_acad ac,
                       sede s,
                       nivel n,
                       grado g
                 WHERE h.flg_acti           = 1
                   AND h.id_docente         = p.nid_persona
                   AND h.id_curso           = c.nid_curso
                   AND h.id_aula            = a.nid_aula
                   AND c.nid_area_academica = ac.nid_area_academica
                   AND a.nid_sede           = s.nid_sede
                   AND a.nid_grado          = g.nid_grado
                   AND a.nid_nivel          = n.nid_nivel
                   AND ( (? = 'BY_ID' AND h.id_horario = ?) OR (? = 'TODO' AND 1 = 1) )";
        $result = $this->db->query($sql, array($flgIdHorario, $idHorario, $flgIdHorario));
        return $result->result();
    }
    
    function getCountHorarioInEvaluacion($idHorario) {
        $sql = "SELECT COUNT(1) cnt
                  FROM evaluacion
                 WHERE id_horario = ? ";
        $dbSped = $this->load->database('sped', TRUE);
        $result = $dbSped->query($sql, array($idHorario));
        return $result->row()-> cnt;
    }
}