<?php
//LAST CODE 004
class M_detalle_evento extends  CI_Model{
    function __construct(){
        parent::__construct();
    }

    function getFamiliasByOpcion($idEvento, $opcion){
        $sql="SELECT CONCAT(ape_paterno,' ',ape_materno,', ',nombres) AS nombrecompleto,
                     CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                     nombres as nombres,
            	     id_contacto,
            	     correo,
            	     telefono_celular,
            	     parentesco,
            	     flg_estudiante,
            	     cod_grupo,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
            	     estado,
            	     CASE WHEN (SELECT opcion
            	                  FROM admision.invitados
            	                 WHERE id_evento = ?
            	                   AND id_contacto = c.id_contacto) = ?
            	                 THEN '".ASISTENCIA_CONTACTO."'
            	          ELSE '".INASISTENCIA_CONTACTO."' END AS opcion
                FROM admision.contacto c
               WHERE cod_grupo IN (SELECT cod_grupo
            	         		     FROM admision.contacto c1,
                                          admision.invitados i
                                    WHERE c1.id_contacto    = i.id_contacto
                                      AND i.id_evento       = ?
                                      AND i.opcion          = ?
            		 	         GROUP BY cod_grupo)
            	ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($idEvento, $opcion,$idEvento, $opcion));
        return $result->result();
    }
    
    function getFamiliasPorLlamadas($idEvento, $limit, $offSet){
        $sql="SELECT CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                     c.id_contacto,
                     c.correo,
                     c.telefono_celular,
                     c.parentesco,
                    (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     c.flg_estudiante,
                     c.cod_grupo,
                     (SELECT COUNT (1)
	                    FROM admision.log_llamada l
	                   WHERE l.id_evento = ?
		                 AND l.id_contacto IN (SELECT c1.id_contacto
			                                     FROM admision.contacto c1
					                            WHERE c1.cod_grupo = c.cod_grupo)) as llamadas
                FROM admision.contacto c
                WHERE cod_grupo IN (SELECT c3.cod_grupo
                                     FROM admision.contacto c3
                                 GROUP BY c3.cod_grupo
                                 ORDER BY (SELECT COUNT (1)
	                                         FROM admision.log_llamada l
	                                        WHERE l.id_evento = ?
		                                      AND l.id_contacto IN (SELECT c1.id_contacto
			                                                          FROM admision.contacto c1
					                                                 WHERE c1.cod_grupo = c3.cod_grupo)), c3.cod_grupo
                                    LIMIT ".$limit."
                                   OFFSET ".$offSet.")
                  AND cod_grupo NOT IN (SELECT c1.cod_grupo
                                            FROM admision.contacto c1,
                                                 admision.invitados i
                                           WHERE c1.id_contacto    = i.id_contacto
                                             AND c1.cod_grupo      = c.cod_grupo
                                             AND i.id_evento       = ?)
                ORDER BY llamadas, cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($idEvento, $idEvento, $idEvento));
        return $result->result();
    }
    
    function createRecursoMaterial($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("combo_tipo", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function validateRecursoRepetido($desc){
        $sql = "SELECT COUNT(1) cnt
                  FROM combo_tipo
                 WHERE UPPER(desc_combo) = UPPER(?)
                   AND grupo = ".COMBO_RECURSOS_MATERIALES;
        $result = $this->db->query($sql, $desc);
        return $result->row()->cnt;
    }
    
    function getRecursosMaterialesByEvento($idEvento) {
        $sql="SELECT id_recurso_x_evento,
                     id_recurso,
                     cantidad,
                     observacion_pedido,
                     observacion_cumplimiento,
                     observacion_resp,
                     id_responsable,
                     fecha_registro,
                     flg_confirmacion,
                     fecha_confirmacion,
                     nombre_responsable,
                     CONCAT(SPLIT_PART(nombre_responsable,' ',1), ',', SPLIT_PART(SPLIT_PART(nombre_responsable,', ',2),' ',1)) nombreabreviado,
                     CASE WHEN flg_cumplimiento = 1 THEN 'true'
                          ELSE 'false' END AS checked,
                     (SELECT INITCAP(desc_combo) as desc
                           FROM combo_tipo
                          WHERE grupo = ".COMBO_RECURSOS_MATERIALES."
                            AND valor = id_recurso::CHARACTER VARYING) as recurso_desc,
                     CASE WHEN flg_confirmacion = 1 THEN 'thumb_up'
                          WHEN flg_confirmacion = 0 THEN 'thumb_down'
                          ELSE 'thumbs_up_down' END AS pulgar,
                     CASE WHEN flg_confirmacion = 1 THEN '#2196F3'
                          WHEN flg_confirmacion = 0 THEN 'red'
                          ELSE '#757575' END AS color_pulgar,
                     flg_confirmacion,
                    (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona ELSE 'nouser.svg' END AS foto_persona FROM persona p WHERE p.nid_persona = id_responsable) foto_persona,
                    (SELECT google_foto FROM persona p WHERE p.nid_persona = id_responsable) foto_persona_google
                FROM admision.recurso_x_evento
               WHERE tipo_recurso = ".TIPO_RECURSO_MATERIAL."
                 AND id_evento    = ?
            ORDER BY fecha_registro DESC";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function deleteRecursoEvento($idRecursoEvento){
        $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where('id_recurso_x_evento', $idRecursoEvento);
	        $this->db->delete('admision.recurso_x_evento');
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MDE-003)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_DEL;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
    }
    
    function asignarRecursoEvento($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.recurso_x_evento", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDE-004)');
            }
            $rpt['idRecEvento'] = $this->db->insert_id();
            $rpt['error']       = EXIT_SUCCESS;
            $rpt['msj']         = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt; 
    }
    
    function getAllEncargados(){
        $sql = "SELECT CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombrecompleto,
                       p.nid_persona
                  FROM persona p,
                       persona_x_rol pr
                 WHERE p.nid_persona = pr.nid_persona
                   AND (   pr.nid_rol = ".ID_ROL_DOCENTE."
                        OR pr.nid_rol = ".ID_ROL_MARKETING."
                        OR pr.nid_rol = ".ID_ROL_PROFESORA_ASISTENTE."
                        OR pr.nid_rol = ".ID_ROL_PSICOPEDAGOGO_SEDE."
                        OR pr.nid_rol = ".ID_ROL_ENFERMERA."
                        OR pr.nid_rol = ".ID_ROL_NUTRICIONISTA."
                        OR pr.nid_rol = ".ID_ROL_SECRETARIA."
                        OR pr.nid_rol = ".ID_ROL_DIRECTOR_TI."
                        OR pr.nid_rol = ".ID_ROL_OPERADOR_TICE."
                        OR pr.nid_rol = ".ID_ROL_RESPONSABLE_MOBILIDAD."
                        OR pr.nid_rol = ".ID_ROL_CHOFER."
                        OR pr.nid_rol = ".ID_ROL_ELECTRICISTA."
                        OR pr.nid_rol = ".ID_ROL_JEFE_SEGURIDAD."
                        OR pr.nid_rol = ".ID_ROL_TUTOR.")
               GROUP BY p.nid_persona
               ORDER BY CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 )))";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function updateRecursoEvento($arrayUpdate, $idRecursoEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_recurso_x_evento", $idRecursoEvento);
            $this->db->update("admision.recurso_x_evento", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
    
    function getSedesRutaEvento($idEvento){
        $sql="SELECT s.nid_sede,
                     desc_sede,
                     hora_inicio,
                     hora_fin,
                    (SELECT  CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers),', ',INITCAP(p.nom_persona))
                        FROM persona_x_rol pxr 
                   LEFT JOIN persona p
                          ON pxr.nid_persona = p.nid_persona,
                             rrhh.personal_detalle pd
                       WHERE pd.id_persona = p.nid_persona
                          AND      nid_rol = ".ID_ROL_SUBDIRECTOR."
                         AND id_sede_control = s.nid_sede) as nombrecompleto,
                     (SELECT  CONCAT(INITCAP(p.ape_pate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 )))
                        FROM persona_x_rol pxr 
                   LEFT JOIN persona p
                          ON pxr.nid_persona = p.nid_persona,
                             rrhh.personal_detalle pd
                       WHERE pd.id_persona = p.nid_persona
                          AND      nid_rol = ".ID_ROL_SUBDIRECTOR."
                         AND id_sede_control = s.nid_sede) as nombreabreviado,
                     (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END as foto
                        FROM persona_x_rol pxr 
                   LEFT JOIN persona p
                          ON pxr.nid_persona = p.nid_persona,
                             rrhh.personal_detalle pd
                       WHERE pd.id_persona = p.nid_persona
                          AND      nid_rol = ".ID_ROL_SUBDIRECTOR."
                         AND id_sede_control = s.nid_sede) as foto_persona,
                     CASE WHEN rt.id_sede IS NOT NULL THEN 'checked'
                          ELSE '' END AS checked,
                     orden,
                     (SELECT COUNT(1)
                        FROM admision.ruta_tour
                       WHERE id_evento = ?) AS cant_ruta_sede
                FROM sede s LEFT JOIN admision.ruta_tour rt
                     ON (s.nid_sede = rt.id_sede AND rt.id_evento = ?)
                 WHERE s.nid_sede <> 7
            ORDER BY rt.orden";
        $result = $this->db->query($sql, array($idEvento, $idEvento));
        return $result->result();
    }
    
    function verifySedeRutaEvento($idEvento, $idSede){
        $sql = "SELECT COUNT(1) cnt
                  FROM admision.ruta_tour
                 WHERE id_evento = ?
                   AND id_sede   = ?";
        $result = $this->db->query($sql, array($idEvento, $idSede));
        return $result->row()->cnt;
    }
    
    function insertRutaTour($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.ruta_tour", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDE-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function deleteRutaTour($idEvento, $idSede, $orden){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_evento', $idEvento);
            $this->db->where('id_sede', $idSede);
            $this->db->delete('admision.ruta_tour');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDE-003)');
            }
            
            $sql="UPDATE admision.ruta_tour
                     SET orden = orden - 1
                   WHERE orden     > ?
                     AND id_evento = ?";
            $result = $this->db->query($sql, array($orden, $idEvento));
            
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function cambiarOrdenSedeRuta($arrayUpdate1, $arrayUpdate2, $idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_sede", $arrayUpdate1['id_sede']);
            $idSede = $arrayUpdate1['id_sede'];
            unset($arrayUpdate1['id_sede']);
            $this->db->where("id_evento", $idEvento);
            $this->db->update("admision.ruta_tour", $arrayUpdate1);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            
            $this->db->where("orden", $arrayUpdate2['orden_cambio']);
            $this->db->where("id_sede !=", $idSede);
            unset($arrayUpdate2['orden_cambio']);
            $this->db->where("id_evento", $idEvento);
            $this->db->update("admision.ruta_tour", $arrayUpdate2);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
    
    function getLastOrdenSedeRuta($idEvento){
        $sql = "SELECT COUNT(1) + 1 orden
                  FROM admision.ruta_tour
                 WHERE id_evento = ?";
        $result = $this->db->query($sql, array($idEvento));
        return $result->row()->orden;
    }
    
    function getOrdenBySedeEvento($idEvento, $idSede){
        $sql = "SELECT orden
                  FROM admision.ruta_tour
                 WHERE id_evento = ?
                   AND id_sede   = ?";
        $result = $this->db->query($sql, array($idEvento, $idSede));
        return $result->row()->orden;
    }
    
    function getApoyoAdministrativoByEvento($idEvento){
        $sql="SELECT re.id_recurso_x_evento,
                     re.id_recurso,
                     re.cantidad,
                     CASE WHEN re.observacion_pedido IS NOT NULL THEN re.observacion_pedido 
                          ELSE '-' end AS observacion_pedido,
                     CASE WHEN re.observacion_cumplimiento IS NOT NULL THEN re.observacion_cumplimiento
                          ELSE '-' end AS observacion_cumplimiento,
                     re.fecha_registro,
                     re.flg_confirmacion,
                     re.fecha_confirmacion,
                     re.id_sede,
                     CASE WHEN flg_toma_asistencia = 1 THEN '(Toma Asistencia)'
                          ELSE '' END AS toma_asistencia,
                     (SELECT COUNT(1) 
                        FROM admision.persona_x_recurso pr
                       WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento) cant_personas,
                     nullornotnull(s.desc_sede) as desc_sede
                FROM admision.recurso_x_evento re
                     LEFT JOIN sede s ON (re.id_sede = s.nid_sede)
               WHERE re.id_evento    = ?
                 AND re.tipo_recurso = ".TIPO_RECURSO_HUMANO."
            ORDER BY re.fecha_registro";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function getApoyoAdministrativoRecursoEvento($idRecursoEvento){
        $sql="SELECT pr.id_persona,
                     CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombrecompleto
                FROM admision.persona_x_recurso pr,
                     persona p
               WHERE id_recurso_x_evento = ?
                 AND pr.id_persona = p.nid_persona";
        $result = $this->db->query($sql, array($idRecursoEvento));
        return $result->result();
    }
    
    function deleteRecursoApoyoAdministrativo($idRecursoEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_recurso_x_evento', $idRecursoEvento);
            $this->db->delete('admision.persona_x_recurso');
            
            $this->db->where('id_recurso_x_evento', $idRecursoEvento);
            $this->db->delete('admision.recurso_x_evento');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDE-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getAllApoyoAdministrativo(){
        $sql="SELECT desc_rol,
                     nid_rol
                FROM rol
               WHERE (     nid_rol = ".ID_ROL_DOCENTE."
                        OR nid_rol = ".ID_ROL_MARKETING."
                        OR nid_rol = ".ID_ROL_PROFESORA_ASISTENTE."
                        OR nid_rol = ".ID_ROL_PSICOPEDAGOGO_SEDE."
                        OR nid_rol = ".ID_ROL_ENFERMERA."
                        OR nid_rol = ".ID_ROL_NUTRICIONISTA."
                        OR nid_rol = ".ID_ROL_SECRETARIA."
                        OR nid_rol = ".ID_ROL_DIRECTOR_TI."
                        OR nid_rol = ".ID_ROL_OPERADOR_TICE."
                        OR nid_rol = ".ID_ROL_SUBDIRECTOR."
                        OR nid_rol = ".ID_ROL_RESPONSABLE_MOBILIDAD."
                        OR nid_rol = ".ID_ROL_CHOFER."
                        OR nid_rol = ".ID_ROL_ELECTRICISTA."
                        OR nid_rol = ".ID_ROL_JEFE_SEGURIDAD."
                        OR nid_rol = ".ID_ROL_AGENTE_SEGURIDAD."
                        OR nid_rol = ".ID_ROL_TUTOR."
                        OR nid_rol = ".ID_ROL_DIRECTOR."
                        OR nid_rol = ".ID_ROL_NUTRICIONISTA.")";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getPersonasEncargadasApoyoAdministrativo($idRecurdoEvento) {
        $sql="SELECT p.nid_persona,
                     CASE WHEN asistencia = 1 THEN 'checked'
                          ELSE '-' END AS check,
                     CASE WHEN hora_llegada IS NOT NULL THEN hora_llegada
		                  ELSE null END AS hora_llegada,
                     CASE WHEN correo_inst IS NOT NULL THEN correo_inst
						  WHEN correo_admi IS NOT NULL THEN correo_admi
						  WHEN correo_pers IS NOT NULL THEN correo_pers
						  ELSE '-' END AS correo_admi,
                     CASE WHEN  p.telf_pers IS NOT NULL THEN  p.telf_pers :: character varying 
		                  ELSE '-' END AS telf_pers,
                     CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers),', ',INITCAP( p.nom_persona)) nombrecompleto,
                     CONCAT(INITCAP(p.ape_pate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombreabreviado,
                     CASE WHEN flg_confirmacion = 1 THEN 'thumb_up'
                          WHEN flg_confirmacion = 0 THEN 'thumb_down'
                          ELSE 'thumbs_up_down' END AS pulgar,
                     CASE WHEN flg_confirmacion = 1 THEN '#2196F3'
                          WHEN flg_confirmacion = 0 THEN 'red'
                          ELSE '#757575' END AS color_pulgar,
                     (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona ELSE 'nouser.svg' END AS foto_persona FROM persona p WHERE p.nid_persona = pr.id_persona) foto_persona,
                     (SELECT google_foto FROM persona p WHERE p.nid_persona = pr.id_persona) foto_persona_google,
                     pr.observacion_resp,
                     pr.observacion_ped
                FROM admision.persona_x_recurso pr,
                     persona p
               WHERE id_recurso_x_evento = ?
                 AND pr.id_persona = p.nid_persona";
        $result = $this->db->query($sql, array($idRecurdoEvento));
        return $result->result();
    }
    
    function asistenciaApoyoAdministrativo($idRecursoEvento, $idPersona, $arrayUpdate){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_recurso_x_evento", $idRecursoEvento);
            $this->db->where("id_persona", $idPersona);
            $this->db->update("admision.persona_x_recurso", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
    
    function getParticipantesEvento($idEvento){
        $sql="SELECT * FROM (
                SELECT CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombrecompleto,
                       asistencia
                  FROM admision.recurso_x_evento re,
                       admision.persona_x_recurso pr,
                       persona p
                 WHERE re.id_evento = ?
                   AND re.id_recurso_x_evento = pr.id_recurso_x_evento
                   AND pr.id_persona = p.nid_persona
            
                 UNION
            
                SELECT CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombrecompleto,
                       1 as asistencia
                  FROM admision.evento e,
                       persona p
                 WHERE e.id_evento = ?
                   AND e.id_persona_encargada = p.nid_persona
                
                 UNION
                
                SELECT CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombrecompleto,
                       1 as asistencia
                  FROM admision.recurso_x_evento re,
                       persona p
                 WHERE re.id_evento = ?
                   AND re.id_responsable = p.nid_persona) a";
        $result = $this->db->query($sql, array($idEvento, $idEvento, $idEvento));
        return $result->result();
    }
    
    function validExisteContactoInvitacionEvento($idContacto, $idEvento){
        $sql = "SELECT COUNT(1) cnt
                  FROM admision.invitados
                 WHERE id_evento   = ?
                   AND id_contacto = ?";
        $result = $this->db->query($sql, array($idEvento, $idContacto));
        return $result->row()->cnt;
    }
    
    function insertInvitacionEvento($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.invitados", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function updateInvitacionEvento($arrayUpdate, $idEvento, $idContacto){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_evento", $idEvento);
            $this->db->where("id_contacto", $idContacto);
            $this->db->update("admision.invitados", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
    
    function validExisteFamiliaInvitacionEvento($codGrupo, $idEvento){
        $sql = "SELECT COUNT(1) cnt
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento     = ?
                   AND i.id_contacto = c.id_contacto
                   AND c.cod_grupo   = ?";
        $result = $this->db->query($sql, array($idEvento, $codGrupo));
        return $result->row()->cnt;
    }
    
    function insertFamiliaresInvitacionEvento($codGrupo, $idEvento, $opcion){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $sql = "INSERT INTO admision.invitados (id_evento, id_contacto, opcion, asistencia, flg_asistencia_directa)
                    SELECT ?, id_contacto, ?, ".INASISTENCIA_CONTACTO.", ".ASISTENCIA_INVITACION_CONTACTO."
                      FROM admision.contacto
                     WHERE cod_grupo = ?
                       AND flg_estudiante <> ".FLG_ESTUDIANTE;
            $this->db->query($sql, array($idEvento, $opcion, $codGrupo));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getApoyoAdministrativoByEventoSede($idEvento){
        $sql="SELECT re.id_recurso_x_evento,
                     re.id_recurso,
                     re.cantidad,
                     re.observacion_pedido,
                     re.observacion_cumplimiento,
                     re.fecha_registro,
                     re.flg_confirmacion,
                     re.fecha_confirmacion,
                     re.id_sede,
                     (SELECT COUNT(1)
                        FROM admision.persona_x_recurso pr
                       WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento) cant_personas,
                     s.desc_sede
                FROM admision.recurso_x_evento re,
                     sede s
               WHERE re.id_evento    = ?
                 AND re.tipo_recurso = ".TIPO_RECURSO_HUMANO."
                 AND re.id_sede      = s.nid_sede";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    //ARREGLAR
    function busquedaAuxiliares($busqueda, $idRol, $idRecursoEvento){
        $sql="SELECT CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers),', ',INITCAP( p.nom_persona)) nombrecompleto,
                     CONCAT(INITCAP(p.ape_pate_pers),', ',SPLIT_PART(INITCAP( p.nom_persona),' ',1)) nombreabreviado,
                     p.nid_persona,
                     pr.id_persona,
                     CASE WHEN correo_inst IS NOT NULL THEN correo_inst
                          WHEN correo_admi IS NOT NULL THEN correo_admi
                          WHEN correo_pers IS NOT NULL THEN correo_admi
                          ELSE '' END AS correo_admi,
					 CASE WHEN  p.telf_pers IS NOT NULL THEN  p.telf_pers 
					      ELSE '-' END AS telf_pers,
                     CASE WHEN re.id_recurso_x_evento = ? THEN '1' 
                          ELSE '0' END AS flg,
                    CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona ELSE 'nouser.svg' END AS foto_persona,
                    p.google_foto AS foto_persona_google
               FROM admision.recurso_x_evento  re RIGHT JOIN 
                    ((persona p INNER JOIN persona_x_rol pro
                 ON (pro.nid_persona = p.nid_persona AND pro.nid_rol = ?))
                     LEFT JOIN admision.persona_x_recurso   pr
                 ON pr.id_persona = p.nid_persona)
                 ON (re.id_recurso_x_evento = pr.id_recurso_x_evento)
              WHERE UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) LIKE UPPER(?)
           GROUP BY nombrecompleto, p.nid_persona, id_persona, correo_admi, telf_pers, flg
           ORDER BY nid_persona";
        $result = $this->db->query($sql, array($idRecursoEvento, $idRol, '%'.$busqueda.'%'));
        return $result->result();
    }
    
    function insertPersonaRecursoApoyoAdm($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.persona_x_recurso", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getCountApoyoAdmRecursoEvento($idRecursoEvento){
        $sql="SELECT CONCAT((SELECT COUNT(1)
                               FROM admision.persona_x_recurso pr
                              WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento),'/', re.cantidad) as cant
                FROM admision.recurso_x_evento re
               WHERE re.id_recurso_x_evento = ?";
        $result = $this->db->query($sql, array($idRecursoEvento));
        return $result->row()->cant;
    }
    
    function getFamiliaCompletaInvitarEvento($idContacto, $idEvento, $opcion){
        $sql = "SELECT c.id_contacto,
                       CONCAT(UPPER(ape_paterno),' ',UPPER(ape_materno),', ',INITCAP(SPLIT_PART( nombres, ' ' , 1 ))) nombrecompleto,
                       c.telefono_celular,
                       c.correo,
                       c.flg_estudiante,
                       CASE WHEN flg_estudiante = 1 THEN 'POSTULANTE'
                            ELSE (SELECT UPPER(desc_combo)
                                    FROM combo_tipo
                                   WHERE grupo = ".COMBO_PARENTEZCO."
                                     AND valor = parentesco::CHARACTER VARYING ) END AS parentesco,
                       i.id_hora_cita,
                       CASE WHEN i.id_evento IS NOT NULL THEN 'checked'
                            ELSE '' END AS check,
                       CASE WHEN i.id_hora_cita IS NOT NULL THEN ''
                            ELSE 'disabled' END AS hora
                  FROM admision.contacto c LEFT JOIN admision.invitados i
                    ON (c.id_contacto   = i.id_contacto
                        AND i.id_evento = ?)
                        
                 WHERE c.cod_grupo = (SELECT cod_grupo 
            	                        FROM admision.contacto
            	                       WHERE id_contacto = ?)
                   AND (i.opcion = ? or i.opcion IS NULL)";
        
        $result = $this->db->query($sql, array($idEvento, $idContacto, $opcion));
        return $result->result();
    }
    
    function getHorasCitaEvento($idEvento){
        $sql = "SELECT hora_cita,
                       desc_hora_cita,
                       correlativo,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto    = c.id_contacto
                           AND i.id_evento      = ?
                           AND i.id_hora_cita   = correlativo
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE."
                           AND i.opcion       IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.")) AS cantidad
                  FROM admision.horario_evaluacion
                 WHERE id_evento = ?";
        $result = $this->db->query($sql, array($idEvento, $idEvento));
        return $result->result();
    }
    
    function insertHorarioEvaluacion($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.horario_evaluacion", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function validateHorarioEvaluacion($hora, $idEvento){
        $sql = "SELECT COUNT(1) cnt
                  FROM admision.horario_evaluacion
                 WHERE hora_cita = ?
                   AND id_evento = ?";
        $result = $this->db->query($sql, array($hora, $idEvento));
        return $result->row()->cnt;
    }
    
    function deleteHorarioEvaluacion($correlativo, $idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_evento', $idEvento);
            $this->db->where('correlativo', $correlativo);
            $this->db->delete('admision.horario_evaluacion');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDE-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getHorarioByFamiliaOpcion($idcontacto, $opcion, $idEvento){
        $sql = "SELECT i.id_contacto,
                       i.id_hora_cita,
                       CONCAT(c.ape_paterno,' ',c.ape_materno,', ',c.nombres) AS nombrecompleto,
                       CASE WHEN flg_estudiante = ".FLG_ESTUDIANTE." THEN 'POSTULANTE'
                            ELSE (SELECT UPPER(desc_combo)
                                    FROM combo_tipo
                                   WHERE grupo = ".COMBO_PARENTEZCO."
                                     AND valor = parentesco::CHARACTER VARYING ) END AS parentesco
                  FROM admision.contacto  c,
                       admision.invitados i
                 WHERE i.id_contacto = c.id_contacto
                   AND i.id_evento = ?
                   AND i.opcion    = ?
                   AND c.cod_grupo IN (SELECT c1.cod_grupo
                                          FROM admision.contacto c1
                                         WHERE c1.id_contacto = ?)
                   ORDER BY i.id_hora_cita";
        $result = $this->db->query($sql, array($idEvento, $opcion, $idcontacto));
        return $result->result();
    }
    
    function deletePersonaRecursoEvento($recursoEvento, $idPersona){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_recurso_x_evento', $recursoEvento);
            $this->db->where('id_persona', $idPersona);
            $this->db->delete('admision.persona_x_recurso');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDE-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function deleteInvitacionEvento($idEvento, $idContacto){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_evento', $idEvento);
            $this->db->where('id_contacto', $idContacto);
            $this->db->delete('admision.invitados');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDE-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function countContactosEventoTipo($idEvento, $opc, $idPersona, $flgEstu){
        $sql = "SELECT COUNT (1) AS cnt
                  FROM admision.invitados i
                 WHERE i.id_evento = ?
                   and i.opcion = ?
                   AND i.id_contacto IN (SELECT id_contacto
                                         FROM admision.contacto c1
                                        WHERE c1.cod_grupo = (SELECT cod_grupo
                                                                FROM admision.contacto c2
                                                               WHERE c2.id_contacto = ?)
                                           and c1.flg_estudiante = ?)";
        $result = $this->db->query($sql, array($idEvento, $opc, $idPersona, $flgEstu));
        return $result->row()->cnt;
    }
    
    function countContactosEventoTipo_1($idEvento, $opc){
        $sql = "SELECT COUNT (1) AS cnt
                  FROM admision.invitados i
                 WHERE i.id_evento = ?
                   and i.opcion = ?
                   AND i.id_contacto IN (SELECT id_contacto
                                         FROM admision.contacto c1)";
        $result = $this->db->query($sql, array($idEvento, $opc));
        return $result->row()->cnt;
    }
    
    function validateHorarioReservado($correlativo){
        $sql = "SELECT COUNT (1) AS cnt
                  FROM admision.invitados i
                 WHERE i.id_hora_cita = ?";
        $result = $this->db->query($sql, array($correlativo));
        return $result->row()->cnt;
    }
    
    function updateRecursoEventoPersona($arrayUpdate, $idRecursoEvento, $idPersona){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_recurso_x_evento", $idRecursoEvento);
            $this->db->where("id_persona", $idPersona);
            $this->db->update("admision.persona_x_recurso", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
    
    function getConfirmacionAsistenciaRecursoEventoPersona($idRecursoEvento, $idPersona){
        $sql = "SELECT flg_confirmacion AS confirm
                  FROM admision.persona_x_recurso i
                 WHERE i.id_recurso_x_evento = ?
                   AND i.id_persona          = ?";
        $result = $this->db->query($sql, array($idRecursoEvento, $idPersona));
        return $result->row()->confirm;
    }
    
    function getPostulantesByEvento($idEvento){
        $sql = "SELECT id_hora_cita,
    	               (SELECT COUNT(*)
       	                  FROM admision.invitados i
        		    INNER JOIN admision.contacto c
        			        ON i.id_contacto = c.id_contacto
        		         WHERE c.flg_estudiante = 1
        			      AND i.id_evento = 44
        			      AND (i.opcion = 1 OR i.opcion = 2)
        			      AND c.nivel_ingreso = 1)as INICIAL,
        			   (SELECT COUNT(*)
           	              FROM admision.invitados i
        		    INNER JOIN admision.contacto c
        			        ON i.id_contacto = c.id_contacto
        		       WHERE c.flg_estudiante = 1
        			 AND i.id_evento = 44
        			 AND (i.opcion = 1 OR i.opcion = 2)
        			 AND c.nivel_ingreso = 2)as PRIMARIA,
        			 (SELECT COUNT(*)
           	            FROM admision.invitados i
        		  INNER JOIN admision.contacto c
        			      ON i.id_contacto = c.id_contacto
        		       WHERE c.flg_estudiante = 1
        			 AND i.id_evento = 44
        			 AND (i.opcion = 1 OR i.opcion = 2)
        			 AND c.nivel_ingreso = 3)as SECUNDARIA
    	        FROM admision.invitados i
    	  INNER JOIN admision.contacto c
    	          ON i.id_contacto = c.id_contacto
    	       WHERE c.flg_estudiante = 1
    	         AND i.id_evento = 44
    	         AND (i.opcion = 1 OR i.opcion = 2)
    	       GROUP BY id_hora_cita,flg_estudiante";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function getNombreCompletoPersona($idPersona){
        $sql = "SELECT CONCAT(ape_pate_pers,' ',ape_mate_pers,', ',nom_persona) AS nombrecompleto,
                       nom_persona
                  FROM persona
                 WHERE nid_persona = ?";
        $result = $this->db->query($sql, array($idPersona));
        return $result->row()->nom_persona;
    }
    
    function getAllSubdirectores(){
        $sql = "SELECT CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(p.nom_persona)) nombrecompleto,
                       p.nid_persona,
                       s.desc_sede,
                       s.nid_sede
                  FROM persona p INNER JOIN rrhh.personal_detalle pd ON id_persona = p.nid_persona,
                       persona_x_rol pr,
                       sede s
                 WHERE p.nid_persona = pr.nid_persona
                   AND s.nid_sede = pd.id_sede_control
                   AND pr.nid_rol = ".ID_ROL_SUBDIRECTOR;
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getContactosByHorario($idEvento, $horario){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       COALESCE((SELECT desc_grado
                                   FROM grado
                                  WHERE nid_grado = grado_ingreso), 'SIN DEFINIR') AS desc_grado,
                       COALESCE((SELECT n.abvr
                                  FROM grado g,
                                       nivel n
                                 WHERE nid_grado  = grado_ingreso
                                   AND g.id_nivel = n.nid_nivel), 'SIN DEFINIR') AS desc_nivel
                  FROM admision.invitados i,
                       admision.contacto  c
                 WHERE i.id_evento      = ?
                   AND i.opcion        IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.")
                   AND i.id_contacto    = c.id_contacto
                   AND c.flg_estudiante = ".FLG_ESTUDIANTE."
                   AND i.id_hora_cita   = ?
                ORDER BY grado_ingreso";
        $result = $this->db->query($sql, array($idEvento, $horario));
        return $result->result();
    }
    
    function getNombrePersonaCorreo($idPersona){
        $sql = "SELECT CONCAT(nom_persona,' ',ape_pate_pers) AS nombrecompleto,
                       nom_persona
                  FROM persona
                 WHERE nid_persona = ?";
        $result = $this->db->query($sql, array($idPersona));
        return $result->row()->nom_persona;
    }
    
    function validateApoyoAdministrativoAux($idRecursoEvento, $idPersona){
        $sql = "SELECT COUNT(1) AS cuenta
                  FROM admision.persona_x_recurso
                 WHERE id_recurso_x_evento = ?
                   AND id_persona          = ?";
        $result = $this->db->query($sql, array($idRecursoEvento, $idPersona));
        return $result->row()->cuenta;
    }
    
    function buscarPersonaApoyoAdmSede($nombre, $idEvento){
        $sql = "SELECT CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers),', ',INITCAP( p.nom_persona)) nombrecompleto,
                       CONCAT(SPLIT_PART(INITCAP( p.nom_persona),' ',1),' ',INITCAP(p.ape_pate_pers)) nombreabreviado,
                       nid_persona,
                       CASE WHEN correo_inst IS NOT NULL THEN correo_inst
						    WHEN correo_admi IS NOT NULL THEN correo_admi
						    WHEN correo_pers IS NOT NULL THEN correo_pers
						  ELSE '-' END AS correo,
                       CASE WHEN  p.telf_pers IS NOT NULL THEN  p.telf_pers::character varying 
		                  ELSE '-' END AS telf_pers,
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                			WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                			ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona
                  FROM persona p,
                       rrhh.personal_detalle pd
                 WHERE UPPER(CONCAT(SPLIT_PART(INITCAP( p.nom_persona),' ',1), ' ',p.ape_pate_pers,' ',p.ape_mate_pers)) LIKE UPPER(?)
                   AND p.nid_persona = pd.id_persona
                   AND p.nid_persona NOT IN (SELECT id_persona
                                               FROM admision.persona_x_recurso pr,
                                                    admision.recurso_x_evento re
                                              WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento
                                                AND re.id_evento           = ?)";
        $result = $this->db->query($sql, array('%'.$nombre.'%', $idEvento));
        return $result->result();
    }
    
    function crearRecursoEventoFictisio($idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $arrayInsert = array("id_evento"    => $idEvento,
                                 "tipo_recurso" => TIPO_RECURSO_FICTISIO);
            $this->db->insert("admision.recurso_x_evento", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-005)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
            $rpt['id']    = $this->db->insert_id();
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getPersonasApoyoAdmSede($idEvento){
        $sql = "SELECT CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers),', ',INITCAP( p.nom_persona)) nombrecompleto,
                       CONCAT(SPLIT_PART(INITCAP( p.nom_persona),' ',1),' ', INITCAP(p.ape_pate_pers)) nombreabreviado,
                       nid_persona,
                       CASE WHEN correo_inst IS NOT NULL THEN correo_inst
						    WHEN correo_admi IS NOT NULL THEN correo_admi
						    WHEN correo_pers IS NOT NULL THEN correo_pers
						  ELSE '-' END AS correo,
                       CASE WHEN  p.telf_pers IS NOT NULL THEN  p.telf_pers :: character varying
		                  ELSE '-' END AS telf_pers,
                       CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona ELSE 'nouser.svg' END AS foto_persona,
                       p.google_foto AS foto_persona_google,
                       (SELECT pr.fecha_registro
                          FROM admision.persona_x_recurso pr,
                               admision.recurso_x_evento re
                         WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento
                           AND re.id_evento           = ?
                           AND id_persona             = p.nid_persona) AS fecha_reg,
                       (SELECT CONCAT(SPLIT_PART(INITCAP( p1.nom_persona),' ',1),' ', INITCAP(p1.ape_pate_pers))
                          FROM admision.persona_x_recurso pr,
                               admision.recurso_x_evento re,
                               persona p1
                         WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento
                           AND re.id_evento           = ?
                           AND id_persona             = p.nid_persona
                           AND id_persona_registro    = p1.nid_persona) AS persona_reg
                  FROM persona p
                 WHERE p.nid_persona IN (SELECT id_persona
                                           FROM admision.persona_x_recurso pr,
                                                admision.recurso_x_evento re
                                          WHERE pr.id_recurso_x_evento = re.id_recurso_x_evento
                                            AND re.id_evento           = ?)";
        $result = $this->db->query($sql, array($idEvento, $idEvento, $idEvento));
        return $result->result();
    }
}