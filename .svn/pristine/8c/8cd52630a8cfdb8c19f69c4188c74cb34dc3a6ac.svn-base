<?php
class M_config_eval extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getGradosNiveles(){
        $sql = "SELECT g.nid_grado,
                       g.desc_grado,
                       g.abvr AS abrev_grado,
                       n.desc_nivel,
                       n.abvr AS abrev_nivel,
                       (SELECT COUNT(1)
                          FROM admision.config_eval ce
                         WHERE ce._id_grado = g.nid_grado   
                           AND ce.flg_activo = ".FLG_ACTIVO.") AS cant_cursos
                  FROM grado g,
                       nivel n
                 WHERE g.id_nivel = n.nid_nivel
              ORDER BY n.nid_nivel, g.nid_grado";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getCursosByGrado($idGrado){
        $sql = "SELECT id_config_eval,
                       INITCAP(descripcion) AS descripcion,
                       fecha_modi,
                       flg_activo,
                       ruta_doc,
                       (SELECT INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers, ', ', p.nom_persona))
                          FROM persona p
                         WHERE p.nid_persona = _id_usua_modi) nombrecompletousuariocambio,
                       (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = _id_usua_modi) nombrecompletousuariocambio_1,
                      (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                                 ELSE 'nouser.svg' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = _id_usua_modi) foto_persona,
                       INITCAP(titulo_observacion) AS titulo_observacion
                  FROM admision.config_eval ce
                 WHERE ce._id_grado = ?
              ORDER BY fecha_modi";
        $result = $this->db->query($sql, array($idGrado));
        return $result->result();
    }
    
    function validateSameDescripcionCursoGrado($idGrado, $descripcion){
        $sql = "SELECT COUNT(1) AS count
                  FROM admision.config_eval
                 WHERE _id_grado = ?
                   AND UNACCENT(UPPER(descripcion)) = UNACCENT(UPPER(?))";
        $result = $this->db->query($sql, array($idGrado, $descripcion));
        return $result->row()->count;
    }
    
    function insertCursoGrado($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.config_eval", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCE-001)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function deleteCursoGrado($idCurso){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_config_eval', $idCurso);
            $this->db->delete('admision.config_eval');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MCE-002)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function updateCursoGrado($arrayUpdate, $idCurso){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_config_eval', $idCurso);
            $this->db->update('admision.config_eval', $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MCE-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function validateSameDescIndicadores($desc, $idCurso){
        $sql = "SELECT COUNT(1) AS count
		          FROM (SELECT jsonb_array_elements((indicadores)::jsonb) AS data
		                  FROM admision.config_eval
		                 WHERE id_config_eval = ?) AS tab
		          WHERE UPPER(tab.data->>'descripcion') = UPPER(?)";
        $result = $this->db->query($sql, array($idCurso, $desc));
        return $result->row()->count;
    }
    
    function validateSameDescOpciones($desc, $idCurso){
        $sql = "SELECT COUNT(1) AS count
		          FROM (SELECT jsonb_array_elements((opciones_eval)::jsonb) AS data
		                  FROM admision.config_eval
		                 WHERE id_config_eval = ?) AS tab
		          WHERE UPPER(tab.data->>'descripcion') = UPPER(?)";
        $result = $this->db->query($sql, array($idCurso, $desc));
        return $result->row()->count;
    }
}