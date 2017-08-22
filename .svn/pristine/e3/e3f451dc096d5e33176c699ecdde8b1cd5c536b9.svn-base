<?php
//LAST-CODE: MU-002
class M_usuario extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getUsuarioLogin($s_usr, $s_pwd) {
	    $sql = "SELECT p.*,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
	                   CASE WHEN p.google_foto IS NOT NULL THEN 1
                            ELSE 0 END AS foto_select,
	                   CONCAT(INITCAP(SPLIT_PART(nom_persona, ' ', 1)),' ',ape_pate_pers,' ',SUBSTRING(ape_mate_pers,1, 1),'.' ) AS nombre_abvr,
                       pd.id_sede_control,
                       CASE WHEN (p.nro_documento = ?) 
                            THEN 1 
                            ELSE 0 
                       END AS redirect_encuesta
	              FROM persona p LEFT JOIN rrhh.personal_detalle pd ON pd.id_persona = p.nid_persona 
	             WHERE ( LOWER(p.usuario) = LOWER(?)     OR 
                         LOWER(p.correo_inst) = LOWER(?) OR 
                         LOWER(p.correo_admi) = LOWER(?) )
	               AND p.clave            = (SELECT encrypt(?, ?, 'aes'))
	               AND p.flg_acti         = '".FLG_ACTIVO."' LIMIT 1";
	    $result = $this->db->query($sql, array($s_usr, $s_usr, $s_usr, $s_usr, $s_pwd, $s_pwd));
	    return $result->row_array();
	}
	
	function getTestLoginForCambioClave($idPersona, $clave) {
	    $sql = "SELECT CASE WHEN clave = (SELECT encrypt(?, ?, 'aes')) THEN 'OK'
                            ELSE NULL END AS result
                  FROM persona
                 WHERE nid_persona = ?
	               AND flg_acti    = '".FLG_ACTIVO."'";
	    $result = $this->db->query($sql, array($clave, $clave, $idPersona));
	    if($result->num_rows() == 0) {
	        return null;
	    } else {
	        return $result->row()->result;
	    }
	}
	
	function getUsuarioLoginGoogle($correo) {
	    $sql = "SELECT p.*,
	                    pd.id_sede_control,
                       CASE WHEN (p.nro_documento = p.usuario) THEN 1 
                            ELSE 0 END AS redirect_encuesta,
	                   CASE WHEN foto_persona IS NOT NULL THEN foto_persona
                            WHEN google_foto  IS NOT NULL THEN google_foto ELSE NULL END AS foto_final
	              FROM persona p LEFT JOIN rrhh.personal_detalle pd ON pd.id_persona = p.nid_persona 
	             WHERE (LOWER(p.correo_inst) = LOWER(?) OR
	                    LOWER(p.correo_admi) = LOWER(?) )
	               AND p.flg_acti            = '".FLG_ACTIVO."' 
	             LIMIT 1";
	    $result = $this->db->query($sql, array($correo, $correo));
	    return $result->row_array();
	}
	
	function loginPadresFamilia($user, $pass, $sede) {
	    $sql = "SELECT *
                  FROM (SELECT f.id_familiar,
                    	       ff.cod_familia_temp,
                    	       ff.cod_familiar AS cod_familia,
                    	       ff.usuario_edusys,
                    	       CONCAT(INITCAP(SPLIT_PART(nombres, ' ', 1)),' ', ape_paterno,' ',SUBSTRING(ape_materno,1, 1),'.' ) AS nombre_abvr,
                    	       CASE WHEN foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', foto_persona)
                    		        ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
                    		   (SELECT CONCAT('Fam: ',p.ape_pate_pers,' ',p.ape_mate_pers)
                    	          FROM persona p,
                    	               sima.detalle_alumno da
                    	         WHERE p.nid_persona = da.nid_persona
                    	           AND da.cod_familia_temp = ff.cod_familia_temp LIMIT 1) AS familia_name
                    	  FROM familiar                f,
                    	       sima.familiar_x_familia ff
                    	 WHERE f.id_familiar = ff.id_familiar
                    	   AND LOWER(ff.usuario_edusys) = LOWER(?)
                    	   AND ff.clave_edusys          = ? /*LIMIT 1*/) AS ff
                 WHERE CASE WHEN (SELECT COUNT(1) 
    				                FROM admision.contacto 
    				               WHERE id_familiar = id_contacto_matricula 
    				                 AND flg_estudiante != ".FLG_ESTUDIANTE.") != 0 
            		        THEN ? IN (SELECT id_sede_ingreso 
        		                         FROM sima.detalle_alumno
            		                    WHERE cod_familia_temp = ff.cod_familia_temp)
		                    ELSE ( ? IN (SELECT nid_sede
                            	           FROM aula
                            	          WHERE nid_aula IN (SELECT __id_aula
                            	 		                       FROM persona_x_aula
                            	 		                      WHERE flg_acti = '1'
                            	 	                            AND __id_persona IN (SELECT nid_persona
                            	 		 				                               FROM sima.detalle_alumno 
                            	 		 				                              WHERE cod_familia_temp = ff.cod_familia_temp)
                            	  	                         ) LIMIT 1 
                                        )
                    		     ) END LIMIT 1";
	    $result = $this->db->query($sql, array($user, $pass, $sede,$sede)); //_logLastQuery('lo');
	    return $result->row_array();
	}
	
	function loginPadresByEmail($email) {
	    $sql = "SELECT f.id_familiar,
                	   ff.cod_familia_temp,
                	   ff.cod_familiar AS cod_familia,
                	   ff.usuario_edusys,
	                   CASE WHEN f.foto_persona     IS NOT NULL THEN f.foto_persona
	                        WHEN f.facebook_picture IS NOT NULL THEN f.facebook_picture
	                        WHEN f.google_picture   IS NOT NULL THEN f.google_picture
	                        ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona
                  FROM familiar f,
                       sima.familiar_x_familia ff
                 WHERE f.id_familiar = ff.id_familiar
                   AND (TRIM(LOWER(f.email1)) = LOWER(?) OR 
	                    TRIM(LOWER(f.email2)) = LOWER(?))
                   AND (SELECT COUNT(1)
                          FROM sima.detalle_alumno da,
                               persona_x_aula      pa
                         WHERE pa.flg_acti         = '".FLG_ACTIVO."'
                           AND da.cod_familia_temp = ff.cod_familia_temp
                           AND da.nid_persona      = pa.__id_persona) > 0";
	    $result = $this->db->query($sql, array($email, $email));
	    if($result->num_rows() == 1) {
	        return $result->row_array();
	    }
	    return null; 
	}
	
	function getRolesByuser($idUser){
	    $sql = "SELECT DISTINCT r.nid_rol,
	                   r.desc_rol 
	              FROM rol r, 
	                   persona_x_rol pr,
            	       persona p,
            	       rol_x_sistema rs
                 WHERE p.nid_persona = ?
		           AND p.flg_acti    = '".FLG_ACTIVO."' 
	               AND pr.flg_acti   = '".FLG_ACTIVO."' 
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
                   AND rs.flg_acti    = '".FLG_ACTIVO."'
		           AND p.flg_acti     = '".FLG_ACTIVO."'
	               AND pr.flg_acti    = '".FLG_ACTIVO."'
		           AND r.nid_rol      = rs.nid_rol
                   AND r.nid_rol      = pr.nid_rol
                   AND p.nid_persona  = pr.nid_persona";
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
                   AND rs.flg_acti    = '".FLG_ACTIVO."' 
                   AND pr.flg_acti    = '".FLG_ACTIVO."' 
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
	 
	 function getDatosPersona($nidPersona) {
	     $sql = "SELECT p.foto_persona,
	                    p.nom_persona,
	                    p.ape_pate_pers,
	                    p.ape_mate_pers,
	                    p.correo_inst,
	                    p.correo_pers,
	                    p.fec_naci, 
	                    p.nro_documento,
	                    p.telf_pers,
	                    p.tipo_sangre,
	                    p.hobby,
	                    p.usuario,
	                    CASE WHEN p.correo_inst IS NOT NULL THEN p.correo_inst
	                         WHEN p.correo_pers IS NOT NULL THEN p.correo_pers
	                         ELSE p.correo_admi END AS correo_envio,
	                    (SELECT desc_combo
	                       FROM combo_tipo
	                      WHERE grupo = ".COMBO_SEXO."
	                        AND valor = p.sexo::CHARACTER VARYING) AS desc_sexo,
	                    (SELECT desc_sede
	                       FROM sede
	                      WHERE nid_sede = pd.id_sede_control) desc_sede,
	                    (SELECT COUNT(nro_likes) FROM publicacion WHERE audi_usua_regi = ?) nro_corazones,
	                    CONCAT(ape_pate_pers,' ',ape_mate_pers,', ',INITCAP(nom_persona)) nombres,
	                    CONCAT(INITCAP(SPLIT_PART(nom_persona, ' ', 1)),' ',ape_pate_pers,' ',SUBSTRING(ape_mate_pers,1, 1),'.' ) AS nombre_abvr,
	                    CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                             WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                             ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona
                   FROM persona p LEFT JOIN rrhh.personal_detalle pd ON pd.id_persona = p.nid_persona 
                  WHERE nid_persona = ?";
	     $result = $this->db->query($sql, array($nidPersona, $nidPersona));
	     return $result->row_array();
	 }
	 
	 function getDatosFamiliar($idFamiliar, $codFamiliar) {
	     $sql = "SELECT p.foto_persona,
                		p.nombres AS nom_persona,
                		p.ape_paterno AS ape_pate_pers,
                		p.ape_materno AS ape_mate_pers,
                		p.email1 AS correo_inst,
                		p.email2 AS correo_pers,
                		p.fec_naci, 
                		p.nro_doc_identidad AS nro_documento,
                		p.telf_celular AS telf_pers,
                		NULL AS tipo_sangre,
                		NULL AS hobby,
                		ff.usuario_edusys AS usuario,
                		CASE WHEN p.email1 IS NOT NULL THEN p.email1
                			 WHEN p.email2 IS NOT NULL THEN p.email2
                			 ELSE NULL END AS correo_envio,
                		(SELECT desc_combo
                		   FROM combo_tipo
                		  WHERE grupo = ".COMBO_SEXO."
                			AND valor = p.sexo::CHARACTER VARYING) AS desc_sexo,
                		NULL AS desc_sede,
                		(SELECT COUNT(nro_likes) FROM publicacion WHERE audi_usua_regi = ?) nro_corazones,
                		CONCAT(ape_paterno,' ',ape_materno,', ',nombres) nombres
                   FROM familiar p,
                		sima.familiar_x_familia ff
                  WHERE p.id_familiar   = ?
                    AND ff.cod_familiar = ?
                    AND p.id_familiar   = ff.id_familiar";
	     $result = $this->db->query($sql, array($idFamiliar, $idFamiliar, $codFamiliar));
	     return $result->row_array();
	 }
	 
	 function updateDatosPersona($datos, $nid_persona) {
	     $rpt['error']    = EXIT_ERROR;
	     $rpt['msj']      = MSJ_ERROR;
	     try{
	         $this->db->where('nid_persona', $nid_persona);
	         $this->db->update('persona' , $datos);
	         if($this->db->affected_rows() != 1) {
	             throw new Exception('(MU-001)');
	         }
	         $rpt['error']    = EXIT_SUCCESS;
             $rpt['msj']      = MSJ_UPT;
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

	 function getHijosByNodoSistemaRol($idSistema, $roles, $idPadreNodo) {
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
	     $this->db->escape($roles);
	     $sql = "SELECT sp.desc_permiso,
                        sp.id_obj_html
                   FROM rol_x_sist_permiso rsp,
                        sist_permiso sp
                  WHERE rsp.nid_sistema  = $idSistema
                    AND rsp.nid_rol      IN ($rolesIds)
                    AND rsp.flg_acti     = '".FLG_ACTIVO."'
                    AND sp.flg_nodo      = '1'
                    AND sp.id_menu_padre = $idPadreNodo
                    AND sp.nid_sistema   = rsp.nid_sistema
                    AND sp.nid_permiso   = rsp.nid_permiso
                 ORDER BY sp.num_orden";
	     $result = $this->db->query($sql, array($rolesIds));
	     return $result->result();
	 }
	 
	 function getPermisosBySistemaRol($idRol, $idSistema) {
	     $sql = "SELECT sp.desc_permiso,
                        sp.id_obj_html,
                        sp.id_menu_padre,
                        sp.css_icon
                   FROM rol_x_sist_permiso rsp,
                        sist_permiso sp
                  WHERE rsp.nid_sistema  = ?
                    AND rsp.nid_rol      = ?
                    AND rsp.flg_acti     = '".FLG_ACTIVO."'
                    AND sp.flg_nodo      = '0'
                    AND sp.nid_sistema   = rsp.nid_sistema
                    AND sp.nid_permiso   = rsp.nid_permiso
                 ORDER BY sp.num_orden";
	     $result = $this->db->query($sql, array($idSistema, $idRol));
	     return $result->result();
	 }

	 function getPermisoBaseByPersona($idPersona) {
	     $sql = "SELECT sp.desc_permiso,
	                    sp.id_obj_html,
                        sp.id_menu_padre,
                        sp.css_icon
	               FROM permiso_x_persona pp,
	                    sist_permiso sp
	              WHERE pp._id_permiso = sp.nid_permiso
	                AND sp.nid_sistema = ?
	                AND pp._id_persona = ?
	              ORDER BY sp.desc_permiso";
	     $result = $this->db->query($sql, array(ID_SISTEMA_MATENIMIENTO, $idPersona));
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
 	 
 	 function getCorreoByUsuario($campo, $valor) {
 	     $sql = "SELECT p.correo_inst,
 	                    p.correo_admi,
 	                    CONCAT(SPLIT_PART(p.nom_persona,' ', 1), ' ', p.ape_pate_pers, ' ', p.ape_mate_pers) AS persona,
	    				p.ape_mate_pers,
 	                    p.ape_pate_pers,
	    				p.nom_persona,
 	                    SPLIT_PART(p.nom_persona,' ', 1) AS nombre_solo,
	    				p.usuario,
	    				p.nid_persona
	               FROM persona p
	         	  WHERE UPPER(p.".$campo.") = UPPER(?)
	         	    AND p.flg_acti          = '".FLG_ACTIVO."' ";
 	     $result = $this->db->query($sql,array($valor));
 	     return $result->row_array();
 	 }
 	 
 	 function getCorreoByUsuarioByNid($valor) {
 	     $sql = "SELECT p.correo_inst,
 	                    p.correo_admi,
 	                    CONCAT(SPLIT_PART(p.nom_persona,' ', 1), ' ', p.ape_pate_pers, ' ', p.ape_mate_pers) AS persona,
	    				p.ape_mate_pers,
 	                    p.ape_pate_pers,
	    				p.nom_persona,
 	                    SPLIT_PART(p.nom_persona,' ', 1) AS nombre_solo,
	    				p.usuario,
	    				p.nid_persona
	               FROM persona p
	         	  WHERE p.nid_persona = ?
	         	  AND p.flg_acti      = '".FLG_ACTIVO."' ";
 	     $result = $this->db->query($sql, array($valor));
 	     return $result->row_array();
 	 }
 	 
 	 function getAllPersonas() {
 	     $sql = "SELECT CONCAT(ape_pate_pers, ' ' ,ape_mate_pers) as apellidos,
 	                    nom_persona,
 	                    nro_documento,
 	                    nid_persona
 	               FROM persona";
 	     $result = $this->db->query($sql);
 	     return $result->result();
 	 }
 	 
 	 function getAllPersonasByNombre($nombre){
 	     $sql = "SELECT CONCAT(ape_pate_pers, ' ' ,ape_mate_pers) as apellidos,
 	                    CONCAT(ape_pate_pers, ' ',ape_mate_pers, ', ' ,nom_persona) as nombrecompleto,
 	                    nom_persona,
 	                    CASE WHEN nro_documento IS NULL THEN '--' 
 	                         ELSE nro_documento END AS nro_documento,
 	                    nid_persona,
 	                    CASE WHEN foto_persona IS NULL THEN 'public/files/images/profile/nouser.svg' 
 	                    ELSE foto_persona END AS foto_persona,
 	                    fec_naci
 	               FROM persona
 	              WHERE UPPER(CONCAT(ape_pate_pers, ' ',ape_mate_pers, ', ' ,nom_persona)) like UPPER(?)";
 	     $result = $this->db->query($sql,array('%'.$nombre.'%'));
 	     return $result->result();
 	 }
 	 
 	 //@TODO repetidos 
 	 function getNombreCompletoPersona($idPersona){
 	     $sql = "SELECT CONCAT(ape_pate_pers, ' ' ,ape_mate_pers, ', ' , nom_persona) as nombres
 	               FROM persona
 	              WHERE nid_persona = ?";
 	    $result = $this->db->query($sql, array($idPersona));
        return $result->row()->nombres;
 	 }
 	 
 	 function getNombrePersona($id){
 	     $sql="SELECT (CONCAT(UPPER(ape_pate_pers),' ',UPPER(ape_mate_pers),', ',INITCAP(nom_persona))) nom_persona
                FROM public.persona
               WHERE nid_persona = ?";
 	     $result = $this->db->query($sql, array($id));
 	     if($result->num_rows()){
 	         return $result->row()->nom_persona;
 	     } else{
 	         return null;
 	     }
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
                       p.google_foto,
                       CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers,', ',p.nom_persona) as nombrecompleto,
                       to_char(p.fec_naci::timestamp::date,'DD/MM/YYYY') as fec_naci,
                       CASE WHEN (SELECT EXTRACT(DAY FROM p.fec_naci)) = (SELECT EXTRACT(DAY FROM now())) THEN '1' ELSE '0' END as color
                  FROM persona p,
                       persona_x_rol pr
                 WHERE (SELECT EXTRACT(MONTH FROM p.fec_naci)) = (SELECT EXTRACT(MONTH FROM now()))
                   AND pr.nid_rol     <> 5
                   AND p.nid_persona = pr.nid_persona
                GROUP BY p.foto_persona, p.google_foto, nombrecompleto,fec_naci";
        $result = $this->db->query($sql);
        return $result->result();
 	 }
 	 
 	 function updatearDatosGooglePersona($idPersona, $idGoogle, $fotoGoogle) {
 	     $this->db->where('nid_persona', $idPersona);
 	     $this->db->update('persona', array("id_google" => $idGoogle, "google_foto" => $fotoGoogle));
 	     return true;
 	 }
 	 //
 	 function getBusquedaPersonaRol($idRol, $busquedaPersona) {
 	     $sql = " SELECT p.nid_persona,
                    	 INITCAP(CONCAT(p.nom_persona, ' ',p.ape_pate_pers, ' ' ,p.ape_mate_pers)) AS nombres
                    FROM persona       p LEFT JOIN persona_x_rol pr ON (p.nid_persona = pr.nid_persona)
                   WHERE pr.nid_rol NOT IN (?,".ID_ROL_FAMILIA.")
                     AND pr.flg_acti = '1'
                     AND p.flg_acti  = '1'
                     AND p.cod_alumno IS NULL
                     AND p.nid_persona NOT IN (SELECT p2.nid_persona
                                				FROM persona p2, 
 	                                                 persona_x_rol pr2
                                			   WHERE p2.nid_persona = pr2.nid_persona
                                				 AND pr2.flg_acti = '1'
                                				 AND p2.flg_acti  = '1'
                                				 AND pr2.nid_rol  = ?)
                        AND UPPER(CONCAT(p.nom_persona, ' ',p.ape_pate_pers, ' ' ,p.ape_mate_pers)) LIKE UPPER(?)
                         GROUP BY p.nid_persona
                     ORDER BY CONCAT(p.nom_persona, ' ',p.ape_pate_pers, ' ' ,p.ape_mate_pers)";
 	     $result = $this->db->query($sql, array($idRol, $idRol, '%'.$busquedaPersona.'%'));
 	     return $result->result();
 	 }
 	 
 	 function insertarPersonasRol($arryPersGlobal, $rolSelected) {
 	     $data['error'] = EXIT_ERROR;
 	     $data['msj']   = null;
 	     try {
 	         $this->db->trans_begin();
 	         foreach ($arryPersGlobal as $row) {
 	             $idPers = $this->encrypt->decode($row);
 	             if($idPers == null) {
 	                 throw new Exception(ANP);
 	             }
 	             $existe = $this->m_usuario->checkExistePersXRol($idPers, $rolSelected);
 	             if($existe) {
 	                 $this->db->where('nid_persona', $idPers);
 	                 $this->db->where('nid_rol', $rolSelected);
 	                 $this->db->update('persona_x_rol', array("flg_acti" => FLG_ACTIVO) );
 	             } else {
 	                 $this->db->insert('persona_x_rol', array("nid_persona" => $idPers, "nid_rol" => $rolSelected, "flg_acti" => FLG_ACTIVO));
 	             }
 	         }
 	         $this->db->trans_commit();
 	         $data['error'] = EXIT_SUCCESS;
 	     } catch(Exception $e) {
 	         $data['msj'] = $e->getMessage();
 	         $this->db->trans_rollback();
 	     }
 	     return $data;
 	 }
 	 
 	 function checkExistePersXRol($idPers, $rolSelected) {
 	     $sql = "SELECT 1 AS existe
 	               FROM persona_x_rol
 	              WHERE nid_persona = ?
 	                AND nid_rol     = ?";
 	     $result = $this->db->query($sql, array($idPers, $rolSelected));
 	     if($result->num_rows() == 1) {
 	         return true;
 	     } else {
 	         return false;
 	     }
 	 }

 	 function realizoEncuesta($idPersona,$tipo){
 	     $sql = null;
 	     if($tipo == TIPO_ENCUESTA_PADREFAM){
 	         $sql = "SELECT flg_encuesta
                       FROM persona_x_aula
                      WHERE __id_persona   = ?
                        AND year_academico = "._YEAR_;
 	     } else{
 	         $sql = "SELECT flg_encuesta
                       FROM persona
                      WHERE nid_persona   = ?";
 	     }
 	     $result = $this->db->query($sql,array($idPersona));
 	     if($result->num_rows() == 0){
 	         return 0;
 	     } else{
 	         return $result->row()->flg_encuesta;
 	     } 	     
 	 }
    
    function getIngreso($s_usr, $s_pwd){
        $sql = "SELECT CASE WHEN (SELECT COUNT(*)
					                FROM persona p LEFT JOIN rrhh.personal_detalle pd ON pd.id_persona = p.nid_persona 
					               WHERE ( LOWER(p.usuario) = LOWER(?)     OR 
						                   LOWER(p.correo_inst) = LOWER(?) OR 
                                           LOWER(p.correo_admi) = LOWER(?) )
					                 AND p.clave            = (SELECT encrypt(?, ?, 'aes'))
					                 AND p.flg_acti         = '1' LIMIT 1) > 0 THEN '1'
		                    ELSE '0' END AS personal,
			           CASE WHEN (SELECT COUNT(*)
					                FROM familiar f
					               WHERE LOWER(f.usuario) = LOWER(?)
					                 AND f.clave          = (SELECT encrypt(?, ?, 'aes'))) > 0 THEN 1
			                ELSE '0' END AS familiar";
        $result = $this->db->query($sql, array($s_usr, $s_usr, $s_usr, $s_pwd, $s_pwd, $s_usr, $s_pwd, $s_pwd));
        return $result->row_array();
    }

	function getUsuarioLoginFamiliar($s_usr, $s_pwd) {
	    $sql = "SELECT f.*,
	                   CASE WHEN foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', foto_persona)
                            ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
	                   CONCAT(INITCAP(SPLIT_PART(nombres, ' ', 1)),' ',ape_paterno,' ',SUBSTRING(ape_materno,1, 1),'.' ) AS nombre_abvr,
	                   fxf.cod_familiar
			      FROM familiar f
		    INNER JOIN sima.familiar_x_familia fxf
			        ON f.id_familiar = fxf.id_familiar
		         WHERE LOWER(f.usuario) = LOWER(?)
			       AND f.clave          = (SELECT encrypt(?, ?, 'aes'))
	               AND flg_acti         = '1' LIMIT 1";
	    $result = $this->db->query($sql, array($s_usr, $s_pwd, $s_pwd));
	    return $result->row_array();
	}
}