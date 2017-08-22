<?php
//LAST-CODE: MU-002
class M_usuario extends CI_Model{
	function __construct(){
		parent::__construct();
	}
/*
	function getUsuarioLogin($s_usr, $s_pwd){
	    $sql = "SELECT * 
	              FROM persona p
	             WHERE LOWER(p.usuario) = LOWER(?)
	               AND p.clave          = (SELECT encrypt(?,?,'aes'))
	               AND p.flg_acti       = '1' LIMIT 1";
	    $result = $this->db->query($sql, array($s_usr, $s_pwd, $s_pwd));
	    return $result->row_array();
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
                   AND rs.nid_sistema = ".ID_SISTEMA_MATRICULA."
                   AND rs.flg_acti    = '1'
		           AND p.flg_acti     = '1'
	               AND pr.flg_acti    = '1'
		           AND r.nid_rol      = rs.nid_rol
                   AND r.nid_rol      = pr.nid_rol
                   AND p.nid_persona  = pr.nid_persona";
	    $result = $this->db->query($sql,array($rolActual,$idPersona));
	    return $result->result();
	}
	
	function getRolesByuser($idUser){
	    $sql = "SELECT DISTINCT r.nid_rol,
	                   r.desc_rol 
	              FROM rol r, 
	                   persona_x_rol pr,
            	       persona p,
            	       rol_x_sistema rs
                 WHERE p.nid_persona = ?
		           AND p.flg_acti    = '1'
	               AND pr.flg_acti   = '1'
		           AND r.nid_rol     = rs.nid_rol
                   AND r.nid_rol     = pr.nid_rol
                   AND p.nid_persona = pr.nid_persona";
	    $result = $this->db->query($sql,array($idUser));
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
                   AND rs.flg_acti    = '1'
		           AND p.flg_acti     = '1'
	               AND pr.flg_acti    = '1'
		           AND r.nid_rol      = rs.nid_rol
                   AND r.nid_rol      = pr.nid_rol
                   AND p.nid_persona = pr.nid_persona";
	    $result = $this->db->query($sql,array($isUser, $idSistem));
	    return $result->result();
	}
	
	function getAllSistem(){
	    $sql = "SELECT desc_sist,
	                   nid_sistema,
	                   url_sistema,
	                   logo_sistema,
	                   logo_sistema_c,
	                   orden
	              FROM sistema 
	           ORDER BY orden";
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	function getSistemUser($user){
	    $sql = "SELECT nid_persona
	              FROM persona
	             WHERE LOWER(usuario) = LOWER(?)";
	    $resUser = $this->db->query($sql,array($user));
        $idUser  = (!empty($resUser->row()->nid_persona) ? $resUser->row()->nid_persona : null);
	    return $idUser;
	}
	
	function getSistemsUser($nidUser){
	    $sql = "SELECT DISTINCT rs.nid_sistema, 
                       s.desc_sist
                  FROM rol_x_sistema rs, 
                       persona_x_rol pr, 
                       sistema s
                 WHERE pr.nid_persona = ?
                   AND rs.flg_acti    = '1'
                   AND pr.flg_acti    = '1'
                   AND pr.nid_rol     = rs.nid_rol
                   AND rs.nid_sistema = s.nid_sistema
               ORDER BY nid_sistema";
	    $result = $this->db->query($sql,array($nidUser));
	    return $result->result();
	}
	
	 function sistemByUser($idSistema){
	     $sql = "SELECT * FROM sistema
	               WHERE nid_sistema = ?";
	     $result = $this->db->query($sql,array($idSistema));
	     return $result->result();
	 }
	 
    function getDatosPersona($idPersona) {
        $sql = "SELECT foto_persona,
                       CONCAT(ape_pate_pers,' ',ape_mate_pers,', ',nom_persona) nombres,
                       nom_persona nombre,
                       usuario,
                       id_sede_trabajo
                  FROM persona
                 WHERE nid_persona = ? LIMIT 1";
        $result = $this->db->query($sql, $idPersona);
        return ($result->row_array());
    }
	 
	 function updateDatosPersona($datos, $nid_persona) {
	     $rpt['error']    = EXIT_ERROR;
	     $rpt['msj']      = null;
	     $rpt['cabecera'] = CABE_ERROR;
	     try{
	         $this->db->where('nid_persona', $nid_persona);
	         $this->db->update('persona' , $datos);
	         if($this->db->affected_rows() != 1) {
	             throw new Exception('(MU-001)');
	         }
	         $rpt['error']    = EXIT_SUCCESS;
             $rpt['msj']      = MSJ_INS;
             $rpt['cabecera'] = CABE_INS;
	     } catch(Exception $e) {
	         $rpt['msj'] = $e->getMessage();
         }
	     return $rpt;
	 }
	 
	 function updatePassword($clave, $nid_persona){
	     $rpt['error']     = EXIT_ERROR;
	     $rpt['msj']       = null;
	     $data['cabecera'] = CABE_ERROR;
	     try {
	         $this->db->where('nid_persona', $nid_persona);
	         $this->db->update('persona', $clave);
	         if($this->db->affected_rows() != 1) {
	             throw new Exception('(MU-001)');
	         }
	         $rpt['error']     = EXIT_SUCCESS;
	         $data['msj']      = MSJ_INS;
	         $data['cabecera'] = CABE_INS;
	     } catch(Exception $e) {
	         $rpt['msj'] = $e->getMessage();
	     }
	     return $rpt;
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
                   AND rsp.nid_rol    IN ($rolesIds)
                   AND rsp.flg_acti    = '".FLG_ACTIVO."'
                   AND sp.flg_nodo     = '0'
                   AND sp.nid_sistema  = rsp.nid_sistema
                   AND sp.nid_permiso  = rsp.nid_permiso
                   GROUP BY  sp.nid_permiso
                   ORDER BY sp.num_orden";
	     $result = $this->db->query($sql, array( $rolesIds ));
	     return $result->result();
	 }
	 
	 function getHijosByNodoSistemaRol_1($idRol) {
	     $sql = "SELECT sp.desc_permiso,
                       sp.id_obj_html,
                       sp.id_menu_padre,
                       sp.css_icon
                FROM rol_x_sist_permiso rsp,
                     sist_permiso sp
                WHERE rsp.nid_sistema  = ".ID_SISTEMA_MATRICULA."
                  AND rsp.nid_rol      = ?
                  AND rsp.flg_acti     = '1'
                  AND sp.flg_nodo      = '0'
                  AND sp.nid_sistema   = rsp.nid_sistema
                  AND sp.nid_permiso   = rsp.nid_permiso
                ORDER BY sp.num_orden";
	     $result = $this->db->query($sql, array($idRol));
	     return $result->result();
	 }

	 function getPermisoByPersona($idSistema, $idPersona){
	     $sql = "SELECT sp.desc_permiso,
	                    sp.id_obj_html
	               FROM permiso_x_persona pp,
	                    sist_permiso sp
	              WHERE pp._id_permiso = sp.nid_permiso
	                AND sp.nid_sistema = ?
	                AND pp._id_persona = ?
	              ORDER BY sp.desc_permiso";
	     $result = $this->db->query($sql, array($idSistema, $idPersona));
	     return $result->result();
	 }

	 //19 es el permiso Mantenimiento
	 function getListaPermisosMantByPersona($idPersona){
	     $sql = "SELECT sp.desc_permiso,
                        sp.nid_permiso,
                        CASE WHEN pp._id_persona IS NOT NULL THEN 'checked' ELSE NULL END AS flg_acti
                   FROM sist_permiso sp LEFT JOIN permiso_x_persona pp ON pp._id_permiso = sp.nid_permiso AND pp._id_persona = ?
	              WHERE sp.nid_sistema = ".ID_SISTEMA_MATENIMIENTO."
	                AND sp.nid_permiso <> 19";
	     $result = $this->db->query($sql, array($idPersona));
	     return $result->result();
	 }
	 
	 function getUrlImagePersona($nid_persona){
	     $sql = "SELECT p.foto_persona as img 
	               FROM persona p 
	              WHERE p.nid_persona = ?";
         $result = $this->db->query($sql,array($nid_persona));
         return ($result->row()->img);
 	 }
 	 
 	 function getDescripcionBySistema($orden){
 	     $sql = "SELECT descripcion 
 	               FROM sistema
                  WHERE orden = ?";
 	     $result = $this->db->query($sql, array($orden));
 	     if($result->num_rows() > 0) {
 	         return ($result->row()->descripcion);
 	     } else {
 	         return null;
 	     }
 	 }
 	 
 	 function getCorreoByUsuario($campo,$valor){
 	     $sql = "SELECT p.correo,
	    				p.ape_mate_pers,
 	                    p.ape_pate_pers,
	    				p.nom_persona,
	    				p.usuario,
	    				p.nid_persona
	            FROM    persona p
	         	WHERE   upper(p.".$campo.") = upper(?)";
 	     $result = $this->db->query($sql,array($valor));
 	     if($result->num_rows() == 1) {
 	         return $result->row_array();
 	     } else {
 	         return '0';//no existe correo
 	     }
 	 }
 	 
 	 function getAllPersonas(){
 	     $sql = "SELECT CONCAT(ape_pate_pers, ' ' ,ape_mate_pers) as apellidos,
 	                    nom_persona,
 	                    dni,
 	                    nid_persona
 	               FROM persona";
 	     $result = $this->db->query($sql);
 	     return $result->result();
 	 }
 	 
 	 function getAllPersonasByNombre($nombre){
 	     $sql = "SELECT CONCAT(ape_pate_pers, ' ' ,ape_mate_pers) as apellidos,
 	                    CONCAT(ape_pate_pers, ' ',ape_mate_pers, ', ' ,nom_persona) as nombrecompleto,
 	                    nom_persona,
 	                    CASE WHEN dni IS NULL THEN '--' 
 	                    ELSE dni END AS dni,
 	                    nid_persona,
 	                    CASE WHEN foto_persona IS NULL THEN 'public/files/images/profile/nouser.svg' 
 	                    ELSE foto_persona END AS foto_persona,
 	                    fec_naci
 	               FROM persona
 	              WHERE UPPER(CONCAT(ape_pate_pers, ' ',ape_mate_pers, ', ' ,nom_persona)) like UPPER(?)";
 	     $result = $this->db->query($sql,array('%'.$nombre.'%'));
 	     return $result->result();
 	 }
 	 
 	 function getNombreCompletoPersona($idPersona){
 	     $sql = "SELECT CONCAT(ape_pate_pers, ' ' ,ape_mate_pers, ', ' , nom_persona) as nombres
 	               FROM persona
 	              WHERE nid_persona = ?";
 	    $result = $this->db->query($sql, array($idPersona));
        return $result->row()->nombres;
 	 }
 	 
 	 function evaluaInsertUpdatePersPermMant($idPersona, $idPermiso){
 	     $sql = "SELECT COUNT(1) as cuenta
                   FROM permiso_x_persona
                  WHERE _id_persona = ?
                    AND _id_permiso = ?";
 	     $result = $this->db->query($sql, array($idPersona, $idPermiso));
 	     return $result->row()->cuenta;
 	 }
 	 
 	 function updateInsertPermisosPersonaMant($arrayDatos){
 	     $data['error']    = EXIT_ERROR;
 	     $data['msj']      = null;
 	     $data['cabecera'] = CABE_ERROR;
 	     $this->db->trans_begin();
 	 
 	     try {
 	         $cont = 0;
 	         foreach ($arrayDatos as $dato) {
 	             if($dato['condicion'] == 0 ) {//insert
 	                 unset($dato['condicion']);
 	                 $this->db->insert('permiso_x_persona', $dato);
 	                 $cont = $cont + $this->db->affected_rows();
 	             } else if($dato['condicion'] == 1){
 	                 $this->db->where('_id_persona', $dato['_id_persona']);
 	                 $this->db->where('_id_permiso', $dato['_id_permiso']);
 	                 $this->db->delete('permiso_x_persona');
 	                 $cont = $cont + $this->db->affected_rows();
 	             }
 	         }
 	         if($cont != count($arrayDatos) ) {
 	             $this->db->trans_rollback();
 	             throw new Exception('(MA-002)');
 	         }
 	         if ($this->db->trans_status() === FALSE) {
 	             $data['error']     = EXIT_ERROR;
 	             $data['msj']   = '(MA-001)';
 	             $this->db->trans_rollback();
 	 
 	         }else {
 	             $data['error']     = EXIT_SUCCESS;
 	             $data['msj']       = MSJ_INS;
 	             $data['cabecera']  = CABE_INS;
 	             $this->db->trans_commit();
 	         }
 	     } catch (Exception $e) {
 	         $data['error'] = EXIT_ERROR;
 	         $data['msj'] = $e->getMessage();
 	         $this->db->trans_rollback();
 	     }
 	 }
 	 
 	 function updateFontSize($idPersona, $fontSize){
 	     $rpt['error'] = 1;
 	     $rpt['msj']   = null;
 	     $rpt['cabecera'] = CABE_ERROR;
 	     try{
 	         $this->db->where('nid_persona', $idPersona);
 	         $this->db->update('persona' , $fontSize);
 	         if($this->db->affected_rows() == 1){
 	             $rpt['error'] = 0;
 	             $data['msj']       = MSJ_INS;
 	             $data['cabecera']  = CABE_INS;
 	         }
 	         else{
 	             throw new Exception('(MU-005)');
 	         }
 	     }catch(Exception $e){
 	         $rpt['msj'] = $e->getMessage();
 	     }
 	     
 	     return $rpt;
 	 }
 	 
 	 function getPersonasCumpleañosMes(){
        $sql = "SELECT p.foto_persona,
                       CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers,', ',p.nom_persona) as nombrecompleto,
                       to_char(p.fec_naci::timestamp::date,'DD/MM/YYYY') as fec_naci,
                       CASE WHEN (SELECT EXTRACT(DAY FROM p.fec_naci)) = (SELECT EXTRACT(DAY FROM now())) THEN '1' ELSE '0' END as color
                  FROM persona p,
                       persona_x_rol pr
                 WHERE (SELECT EXTRACT(MONTH FROM p.fec_naci)) = (SELECT EXTRACT(MONTH FROM now()))
                   AND pr.nid_rol     <> 5
                   AND p.nid_persona = pr.nid_persona
                GROUP BY p.foto_persona, nombrecompleto,fec_naci";
        $result = $this->db->query($sql);
        return $result->result();
 	 }
 	 
    function getHijosByNodoSistemaRol($idRol) {
        $sql = "SELECT sp.desc_permiso,
                       sp.id_obj_html,
                       sp.id_menu_padre,
                       sp.css_icon
                FROM rol_x_sist_permiso rsp,
                     sist_permiso sp
                WHERE rsp.nid_sistema  = ".ID_SISTEMA_SIMA."
                  AND rsp.nid_rol      = ?
                  AND rsp.flg_acti     = '".FLG_ACTIVO."' 
                  AND sp.flg_nodo      = '0'
                  AND sp.nid_sistema   = rsp.nid_sistema
                  AND sp.nid_permiso   = rsp.nid_permiso
                ORDER BY sp.num_orden";
        $result = $this->db->query($sql, array($idRol));
        return $result->result();
    }*/
}