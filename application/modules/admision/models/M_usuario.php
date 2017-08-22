<?php

class M_usuario extends  CI_Model{
    function __construct(){
        parent::__construct();
    }

    function getDatosPersona($idPersona) {
        $sql = "SELECT foto_persona,
                       google_foto,
                       CONCAT(ape_pate_pers,' ',ape_mate_pers,', ',INITCAP(nom_persona)) nombres,
                       nom_persona nombre,
                       usuario,
                       correo_inst,
                       id_sede_trabajo
                  FROM persona
                 WHERE nid_persona = ? 
                 LIMIT 1";
        $result = $this->db->query($sql, $idPersona);
        return ($result->row_array());
    }
    
    function getHijosByNodoSistemaRol($idRol) {
        $sql = "SELECT sp.desc_permiso,
                       sp.id_obj_html,
                       sp.id_menu_padre,
                       sp.css_icon
                FROM rol_x_sist_permiso rsp,
                     sist_permiso sp
                WHERE rsp.nid_sistema  = ".ID_SISTEMA_ADMISION."
                  AND rsp.nid_rol      = ?
                  AND rsp.flg_acti     = '".FLG_ACTIVO."'
                  AND sp.flg_nodo      = '0'
                  AND sp.nid_sistema   = rsp.nid_sistema
                  AND sp.nid_permiso   = rsp.nid_permiso
                ORDER BY sp.num_orden";
        $result = $this->db->query($sql, array($idRol));
        return $result->result();
    }
    
    function getNodosPadreBySistemaAndRol($idSistema, $roles) {
        $rolesIds = null;
        $mIds_size = count($roles);
        $i = 1;
        foreach($roles as $row) {
            if($i == $mIds_size) {
                $rolesIds .= $row;
            } else {
                $rolesIds .= $row.', ';
            }
            $i++;
        }
        $this->db->escape($rolesIds);
        $sql = "SELECT sp.desc_permiso,
                       sp.flg_has_hijos,
                       sp.nid_permiso
                  FROM rol_x_sist_permiso rsp,
                       sist_permiso sp
                 WHERE rsp.nid_sistema = $idSistema
                   AND rsp.nid_rol     IN ($rolesIds)
                   AND rsp.flg_acti    = '".FLG_ACTIVO."'
                   AND sp.flg_nodo     = '0'
                   AND sp.nid_sistema  = rsp.nid_sistema
                   AND sp.nid_permiso  = rsp.nid_permiso
                 ORDER BY sp.num_orden";
        $result = $this->db->query($sql, array( $rolesIds ));
        return $result->result();
    }
    
    function getRolesByUsuario($idPersona,$rolActual){
        $sql = "SELECT r.nid_rol,
	                   r.desc_rol,
	                   CASE WHEN r.nid_rol = ? THEN 1 ELSE 0 END AS check 
	              FROM rol r,
            	       persona_x_rol pr,
            	       persona p,
	                   rol_x_sistema rs
                 WHERE p.nid_persona  = ?
                   AND rs.nid_sistema = ".ID_SISTEMA_ADMISION."
                   AND rs.flg_acti    = '".FLG_ACTIVO."'
		           AND p.flg_acti     = '".FLG_ACTIVO."'
	               AND pr.flg_acti    = '".FLG_ACTIVO."'
		           AND r.nid_rol      = rs.nid_rol
                   AND r.nid_rol      = pr.nid_rol
                   AND p.nid_persona  = pr.nid_persona";
        $result = $this->db->query($sql,array($rolActual,$idPersona));
        return $result->result();
    }
    
    function getRolesOnlySistem($isUser, $idSistem){
        $sql = "SELECT r.nid_rol,
	                   r.desc_rol
	              FROM rol r,
            	       persona_x_rol pr,
            	       persona p,
	                   rol_x_sistema rs
                 WHERE p.nid_persona  = ?
                   AND rs.nid_sistema = ?
                   AND rs.flg_acti    = '".FLG_ACTIVO."'
		           AND p.flg_acti     = '".FLG_ACTIVO."'
	               AND pr.flg_acti    = '".FLG_ACTIVO."'
		           AND r.nid_rol      = rs.nid_rol
                   AND r.nid_rol      = pr.nid_rol
                   AND p.nid_persona  = pr.nid_persona";
        $result = $this->db->query($sql,array($isUser, $idSistem));
        return $result->result();
    }
    
    function realizoEncuesta($idPersona, $idRol){
        $sql = "SELECT flg_encuesta
                  FROM persona_x_rol
                 WHERE nid_persona = ?
                   AND nid_rol     = ?";
        $result = $this->db->query($sql,array($idPersona, $idRol));
        return $result->row()->flg_encuesta;
    }
}