<?php

class M_colaborador extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllColaboradores($offset, $limit){
        $sql = "SELECT nid_persona,
                       CONCAT(UPPER(ape_pate_pers),' ', UPPER(ape_mate_pers)) AS apellidocompleto,
                       nom_persona,
                       correo_pers,
                       telf_pers,
                       foto_persona
                  FROM persona
                 OFFSET ? LIMIT ?";
        $result = $this->db->query($sql, array($offset, $limit));
        return $result->result();
    }
    
    function insertColaborador($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("rrhh.persona", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-001)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getRolesByPersona($idPersona){
        $sql = "SELECT r.nid_rol,
                       r.desc_rol,
                       CASE WHEN pr.nid_persona IS NOT NULL THEN 'checked'
                            ELSE '' END AS check
                  FROM rol r LEFT JOIN persona_x_rol pr
                       ON (r.nid_rol = pr.nid_rol AND pr.nid_persona = ?)";
        $result = $this->db->query($sql, array($idPersona));
        return $result->result();
    }
    
    function buscarColaborador($texto){
        $sql = "SELECT nid_persona,
                       CONCAT(UPPER(ape_pate_pers),' ', UPPER(ape_mate_pers)) AS apellidocompleto,
                       nom_persona,
                       correo_pers,
                       telf_pers,
                       foto_persona
                  FROM rrhh.persona
                 WHERE CASE WHEN ? IS NOT NULL THEN UPPER(CONCAT(ape_pate_pers,' ', ape_mate_pers, ' ',nom_persona)) LIKE UPPER(?)
                       ELSE 1 = 1 END";
        $result = $this->db->query($sql, array($texto, "%".$texto."%"));
        return $result->result();
    }
    
    function updateRolesPersona($arrayRoles, $idPersona){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->query("DELETE FROM persona_x_rol WHERE nid_persona = ?", array($idPersona));
            $this->db->insert_batch('persona_x_rol', $arrayRoles);
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
}