<?php
//LAST CODE 004
class M_espera extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getInfoEventoHoy(){
        $sql = "SELECT id_evento,
                       desc_evento
                  FROM admision.evento
                 WHERE fecha_realizar = now()::date
                   AND tipo_evento    = ".TIPO_EVENTO_EVALUACION."
                 LIMIT 1";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
    
    function getPostulantesSuTurno($idEvento){
        $sql = "SELECT CONCAT(ape_paterno,' ',ape_materno,', ',split_part(nombres,' ',1)) as nombrecompleto,
                       c.id_contacto,
                       (SELECT CONCAT(p.ape_pate_pers,' ', p.nom_persona)
                          FROM persona p
                         WHERE p.nid_persona = i.id_entrevistador) evaluador
                  FROM admision.contacto c,
                       admision.invitados i
                 WHERE c.id_contacto = i.id_contacto 
                   AND i.id_evento   = ?
                   AND i.estado_eval = '".ESTADO_SU_TURNO_CONTACTO."'";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function getPostulantesPerdioTurno($idEvento){
        $sql = "SELECT CONCAT(ape_paterno,' ',ape_materno,', ',split_part(nombres,' ',1)) as nombrecompleto,
                       c.id_contacto,
                       (SELECT CONCAT(p.ape_pate_pers,' ', p.nom_persona)
                          FROM persona p
                         WHERE p.nid_persona = i.id_entrevistador) evaluador
                  FROM admision.contacto c,
                       admision.invitados i
                 WHERE c.id_contacto = i.id_contacto
                   AND i.id_evento   = ?
                   AND i.estado_eval = '".ESTADO_PERDIO_TURNO_CONTACTO."'";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function getPostulantesEnEspera($idEvento){
        $sql = "SELECT INITCAP(CONCAT(ape_paterno,' ',ape_materno,', ',split_part(nombres,' ',1))) as nombrecompleto,
                       c.id_contacto,
                       (SELECT MAX(fecha_registro)
                          FROM admision.diagnostico
                         WHERE id_estudiante = c.id_contacto
                           AND id_evento     = ?) AS hora
                  FROM admision.contacto c,
                       admision.invitados i
                 WHERE c.id_contacto = i.id_contacto
                   AND i.id_evento   = ?
                   AND (SELECT COUNT(1)
					      FROM admision.diagnostico d
					     WHERE d.id_estudiante = c.id_contacto
					       AND d.id_evento     = ?
                           AND d.tipo_diagnostico = ".DIAGNOSTICO_CURSO.") = (SELECT COUNT(1)
									                                            FROM admision.config_eval ce
									                                           WHERE ce._id_grado = c.grado_ingreso)
                   AND (SELECT COUNT(1)
					      FROM admision.diagnostico d
					     WHERE d.id_estudiante = c.id_contacto
					       AND d.id_evento     = ?
                           AND d.tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA.") <> 1
                    AND i.estado_eval IS NULL
                    AND flg_estudiante = ".FLG_ESTUDIANTE."
                  ORDER BY hora DESC";
        $result = $this->db->query($sql, array($idEvento, $idEvento, $idEvento, $idEvento));
        return $result->result();
    }
    
    function checkLlamadaAInvitado($idEvento, $idContacto) {
        $sql = "SELECT COUNT(1) AS cnt
                  FROM admision.invitados
                 WHERE id_evento   = ?
                   AND id_contacto = ?
                   AND (estado_eval IS NULL OR estado_eval IN ('".ESTADO_PERDIO_TURNO_CONTACTO."', '".ESTADO_CANCELADA."') )";
        $result = $this->db->query($sql, array($idEvento, $idContacto));
        $count = $result->row()->cnt;
        if($count == 1) {
            return true;
        }
        $sql = "SELECT id_entrevistador,
                       estado_eval
                  FROM admision.invitados
                 WHERE id_evento   = ?
                   AND id_contacto = ?";
        $result = $this->db->query($sql, array($idEvento, $idContacto));
        return $result->row_array();
    }
    
    function checkLlamadaLlamadaPerdida_o_pasar_a_Entrevista($idEvento, $idContacto, $idEntrevistador) {
        $sql = "SELECT 1 AS ok_lost_call
                  FROM admision.invitados
                 WHERE id_evento        = ?
                   AND id_contacto      = ?
                   AND id_entrevistador = ?
                   AND estado_eval      = ?";
        $result = $this->db->query($sql, array($idEvento, $idContacto, $idEntrevistador, ESTADO_SU_TURNO_CONTACTO));
        if($result->num_rows() != 1) {
            return null;
        }
        return $result->row()->ok_lost_call;
    }
    
    function checkIfEsMiEntrevista($idEvento, $idContacto, $idEntrevistador) {
        $sql = "SELECT 1 AS en_entrevista
                  FROM admision.invitados
                 WHERE id_evento        = ?
                   AND id_contacto      = ?
                   AND id_entrevistador = ?
                   AND estado_eval      = ?";
        $result = $this->db->query($sql, array($idEvento, $idContacto, $idEntrevistador, ESTADO_EN_ENTREVISTA));
        if($result->num_rows() != 1) {
            return null;
        }
        return $result->row()->en_entrevista;
    }
    
    function registrarLlamada($idEvento, $idContacto, $arryUpdate) {
        $this->db->where('id_evento'  , $idEvento);
        $this->db->where('id_contacto', $idContacto);
        if($arryUpdate['estado_eval'] == ESTADO_SU_TURNO_CONTACTO) {
            //$this->db->where('(id_entrevistador IS NULL OR id_entrevistador = '.$arryUpdate['id_entrevistador'].' )', null, false);
        } else {
            $this->db->where('id_entrevistador', _getSesion('nid_persona'));
        }
        $this->db->update('admision.invitados', $arryUpdate);
        if($this->db->affected_rows() != 1) {
            throw new Exception('No se pudo hacer el cambio en la entrevista');
        }
        return array('error' => EXIT_SUCCESS);
    }
    
    function getPostulantesRestantes($idEvento, $postulantes){
        $sql = "SELECT CONCAT(ape_paterno,' ',ape_materno,', ',split_part(nombres,' ',1)) as nombrecompleto,
                       c.id_contacto,
                       (SELECT CONCAT(p.ape_pate_pers,' ', p.nom_persona)
                          FROM persona p
                         WHERE p.nid_persona = i.id_entrevistador) evaluador,
                       (SELECT MAX(fecha_registro)
                          FROM admision.diagnostico
                         WHERE id_estudiante = c.id_contacto
                           AND id_evento     = ?) AS hora
                  FROM admision.contacto c,
                       admision.invitados i
                 WHERE c.id_contacto  = i.id_contacto
                   AND i.id_evento    = ?
                   AND c.id_contacto IN ?
              ORDER BY hora DESC";
        $result = $this->db->query($sql, array($idEvento, $idEvento, $postulantes));
        return $result->result();
    }
}