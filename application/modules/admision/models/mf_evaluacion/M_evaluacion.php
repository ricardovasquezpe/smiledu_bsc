<?php
//LAST CODE 001
class M_evaluacion extends  CI_Model{
    function __construct(){
        parent::__construct();
    }

    function getContactosAEvaluarEvaluados($evento, $nombre = null, $nivel = null, $grado = null, $curso = null){
        $sql = "SELECT   CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                         CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                         nombres as nombres,
                         c.id_contacto,
                         c.correo,
                         c.telefono_celular,
                         c.parentesco,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_PARENTEZCO."
                             AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                         CASE WHEN (sede_interes = 0 OR sede_interes IS NULL) THEN 'Por definir'
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
                         c.flg_estudiante,
                         c.cod_grupo,
                         (SELECT COUNT(1)
            	            FROM admision.diagnostico d
            	           WHERE d.id_estudiante = c.id_contacto 
            	             AND d.id_evento     = ?) AS cant_eval,
            	         (SELECT COUNT(1)
            	             FROM admision.config_eval ce
            	            WHERE ce._id_grado = c.grado_ingreso) cant_eval_grado,              
                         CASE WHEN  c.flg_form_completo = ".FLG_FORMULARIO_COMPLETO." THEN 'error'
                              ELSE 'error_outline' END AS form_completo  
                    FROM admision.contacto  c,
                         admision.invitados i
                   WHERE c.id_contacto = i.id_contacto
                     AND i.asistencia = ".ASISTENCIA_CONTACTO."
                     AND i.id_evento  = ?
                     AND CASE WHEN c.flg_estudiante = 1 THEN  (SELECT COUNT(1)
                                    						      FROM admision.diagnostico d
                                    						     WHERE d.id_estudiante = c.id_contacto 
                                    						       AND d.id_evento     = ?
                                                                   AND d.tipo_diagnostico = ".DIAGNOSTICO_CURSO.") < (SELECT COUNT(1)
                                    										                                            FROM admision.config_eval ce
                                    										                                           WHERE ce._id_grado = c.grado_ingreso)
                          ELSE 1 = 1 END
                      AND CASE WHEN ? IS NOT NULL THEN (UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,' ',nombres))) LIKE UNACCENT(UPPER(?)) AND flg_estudiante = ".FLG_ESTUDIANTE.")
                               ELSE 1 = 1 END
                      AND CASE WHEN ? IS NOT NULL THEN (nivel_ingreso = ? AND flg_estudiante = ".FLG_ESTUDIANTE.")
                               ELSE 1 = 1 END
                      AND CASE WHEN ? IS NOT NULL THEN (grado_ingreso = ? AND flg_estudiante = ".FLG_ESTUDIANTE.")
                               ELSE 1 = 1 END
                      AND CASE WHEN ? IS NOT NULL THEN (SELECT COUNT(1) FROM admision.diagnostico WHERE id_estudiante = c.id_contacto AND id_config_eval = ? AND id_evento = ?) <> 1
		                       ELSE 1 = 1 END
                    ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($evento, $evento, $evento, $nombre, '%'.$nombre.'%', $nivel, $nivel, $grado, $grado, $curso, $curso, $evento));
        return $result->result();
    }
    
    function getEvaluados($evento){
        $sql = "SELECT   CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                         CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                         nombres as nombres,
                         c.id_contacto,
                         c.correo,
                         c.telefono_celular,
                         c.parentesco,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_PARENTEZCO."
                             AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                         CASE WHEN (sede_interes = 0 OR sede_interes IS NULL) THEN 'Por definir'
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
                         c.flg_estudiante,
                         c.cod_grupo,
                         (SELECT COUNT(1)
            	            FROM admision.diagnostico d
            	           WHERE d.id_estudiante = c.id_contacto
            	             AND d.id_evento     = ?) AS cant_eval,
            	         (SELECT COUNT(1)
            	             FROM admision.config_eval ce
            	            WHERE ce._id_grado = c.grado_ingreso) cant_eval_grado,
                         CASE WHEN  c.flg_form_completo = ".FLG_FORMULARIO_COMPLETO." THEN 'error'
                              ELSE 'error_outline' END AS form_completo
                    FROM admision.contacto  c,
                         admision.invitados i
                   WHERE  c.id_contacto       = i.id_contacto
                      AND i.asistencia        = ".ASISTENCIA_CONTACTO."
                      AND i.id_evento         = ?
                      AND CASE WHEN c.flg_estudiante = 1 THEN  (SELECT COUNT(1)
                                    						      FROM admision.diagnostico d
                                    						     WHERE d.id_estudiante = c.id_contacto
                                    						       AND d.id_evento     = ?
                                                                   AND d.tipo_diagnostico = ".DIAGNOSTICO_CURSO.") = (SELECT COUNT(1)
                                    										                                            FROM admision.config_eval ce
                                    										                                           WHERE ce._id_grado = c.grado_ingreso)
                          ELSE 1 = 1 END
                      AND CASE WHEN c.flg_estudiante = 1 THEN  (SELECT COUNT(1)
                                    						      FROM admision.diagnostico d
                                    						     WHERE d.id_estudiante = c.id_contacto
                                    						       AND d.id_evento     = ?
                                                                   AND d.tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA.") <> 1
                          ELSE 1 = 1 END
                    ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($evento, $evento, $evento, $evento));
        return $result->result();
    }
    
    function getContactosEntrevistados($evento){
        $sql = "SELECT   CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                         CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                         nombres as nombres,
                         c.id_contacto,
                         c.correo,
                         c.telefono_celular,
                         c.parentesco,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_PARENTEZCO."
                             AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                         CASE WHEN (sede_interes = 0 OR sede_interes IS NULL) THEN 'Por definir'
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
                         c.flg_estudiante,
                         c.cod_grupo,
                         (SELECT COUNT(1)
            	            FROM admision.diagnostico d
            	           WHERE d.id_estudiante = c.id_contacto
            	             AND d.id_evento     = ?) AS cant_eval,
            	         (SELECT COUNT(1)
            	             FROM admision.config_eval ce
            	            WHERE ce._id_grado = c.grado_ingreso) cant_eval_grado,
                         CASE WHEN  c.flg_form_completo = ".FLG_FORMULARIO_COMPLETO." THEN 'error'
                              ELSE 'error_outline' END AS form_completo
                    FROM admision.contacto  c,
                         admision.invitados i
                   WHERE  c.id_contacto       = i.id_contacto
                      AND i.asistencia        = ".ASISTENCIA_CONTACTO."
                      AND i.id_evento         = ?
                      AND CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN  (SELECT COUNT(1)
                                                    						      FROM admision.diagnostico d
                                                    						     WHERE d.id_estudiante = c.id_contacto
                                                    						       AND d.id_evento     = ?
                                                                                   AND d.tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA.") = 1
                          ELSE 1 = 1 END
                       AND CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN c.estado <> 7
                           ELSE 1 = 1 END
                    ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($evento, $evento, $evento));
        return $result->result();
    }
    
    function getContactosMatricula($evento){
        $sql = "SELECT   CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                         CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                         nombres as nombres,
                         c.id_contacto,
                         c.correo,
                         c.telefono_celular,
                         c.parentesco,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_PARENTEZCO."
                             AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                         CASE WHEN (sede_interes = 0 OR sede_interes IS NULL) THEN 'Por definir'
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
                         c.flg_estudiante,
                         c.cod_grupo,
                         (SELECT COUNT(1)
            	            FROM admision.diagnostico d
            	           WHERE d.id_estudiante = c.id_contacto
            	             AND d.id_evento     = ?) AS cant_eval,
            	         (SELECT COUNT(1)
            	             FROM admision.config_eval ce
            	            WHERE ce._id_grado = c.grado_ingreso) cant_eval_grado,
                         CASE WHEN  c.flg_form_completo = ".FLG_FORMULARIO_COMPLETO." THEN 'error'
                              ELSE 'error_outline' END AS form_completo
                    FROM admision.contacto  c,
                         admision.invitados i
                   WHERE  c.id_contacto       = i.id_contacto
                      AND i.asistencia        = ".ASISTENCIA_CONTACTO."
                      AND i.id_evento         = ?
                      AND CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN c.estado = 7
                          ELSE 1 = 1 END
                    ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($evento, $evento));
        return $result->result();
    }
    
    function getContactosRestantes($evento, $postulantes){
        $sql = "SELECT   CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                         CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                         nombres as nombres,
                         c.id_contacto,
                         c.correo,
                         c.telefono_celular,
                         c.parentesco,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_PARENTEZCO."
                             AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                         CASE WHEN (sede_interes = 0 OR sede_interes IS NULL) THEN 'Por definir'
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
                         c.flg_estudiante,
                         c.cod_grupo,
                         (SELECT COUNT(1)
            	            FROM admision.diagnostico d
            	           WHERE d.id_estudiante = c.id_contacto
            	             AND d.id_evento     = ?) AS cant_eval,
            	         (SELECT COUNT(1)
            	             FROM admision.config_eval ce
            	            WHERE ce._id_grado = c.grado_ingreso) cant_eval_grado,
                         CASE WHEN  c.flg_form_completo = ".FLG_FORMULARIO_COMPLETO." THEN 'error'
                              ELSE 'error_outline' END AS form_completo
                    FROM admision.contacto  c,
                         admision.invitados i
                   WHERE cod_grupo IN (SELECT c3.cod_grupo
                                         FROM admision.contacto c3
                                        WHERE c3.id_contacto      IN ?
                                     GROUP BY c3.cod_grupo
                                     ORDER BY c3.cod_grupo)
                      AND c.id_contacto       = i.id_contacto
                      AND i.asistencia        = ".ASISTENCIA_CONTACTO."
                      AND i.id_evento         = ?
                      AND ( SELECT COUNT (1)
                            FROM admision.contacto  c,
                                 admision.invitados i
                           WHERE cod_grupo IN (SELECT c3.cod_grupo
                                                 FROM admision.contacto c3
                                                WHERE c3.id_contacto      IN ?
                                             GROUP BY c3.cod_grupo
                                             ORDER BY c3.cod_grupo)
                              AND c.id_contacto       = i.id_contacto
                              AND i.asistencia        = ".ASISTENCIA_CONTACTO."
                              AND i.id_evento         = ?
                              AND flg_estudiante      = ".FLG_FAMILIAR.") > 0
                    ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($evento, $postulantes, $evento, $postulantes, $evento));
        return $result->result();
    }
    
    function getDiagnosticosResumen($idContacto, $idEvento){
        $sql = "SELECT NULLORNOTNULL((SELECT INITCAP(CONCAT(ape_pate_pers,', ', split_part(nom_persona,' ',1))) AS nombrecompleto
                                        FROM persona
                                       WHERE nid_persona = d.id_evaluador)) evaluador,
                       fecha_registro AS fecha_registro,
                       NULLORNOTNULL(d.diagnostico_final) AS diagnostico_final,
                       INITCAP(ce.descripcion) AS descripcion,
                       (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                                 ELSE 'nouser.svg' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = d.id_evaluador) foto_persona,
                        (SELECT google_foto
                          FROM persona p
                         WHERE p.nid_persona = d.id_evaluador) foto_persona_google
                  FROM admision.config_eval ce LEFT JOIN admision.diagnostico d
                       ON (ce.id_config_eval = d.id_config_eval AND id_evento = ? AND id_estudiante = ?)
                 WHERE ce._id_grado = (SELECT grado_ingreso
                                         FROM admision.contacto
                                        WHERE id_contacto = ?)
              ORDER BY fecha_registro DESC";
        $result = $this->db->query($sql, array($idEvento, $idContacto, $idContacto));
        return $result->result();
    }
    
    function getDiagnosticoSubdirector($idContacto, $idEvento){
        $sql = "SELECT NULLORNOTNULL((SELECT INITCAP(CONCAT(ape_pate_pers,' ', ape_mate_pers,', ', nom_persona)) AS nombrecompleto
                                        FROM persona
                                       WHERE nid_persona = id_evaluador)) evaluador,
                       fecha_registro AS fecha_registro,
                	   (SELECT INITCAP(desc_combo) as desc
                	      FROM combo_tipo
                	     WHERE grupo = ".COMBO_RESULTADO_DIAGNOSTICO."
                	       AND valor = diagnostico_final::CHARACTER VARYING) AS diagnostico,
                	   obser_diagnostico,
                       (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                                 ELSE 'nouser.svg' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = id_evaluador) foto_persona,
                        (SELECT google_foto
                          FROM persona p
                         WHERE p.nid_persona = id_evaluador) foto_persona_google
                        
                  FROM admision.diagnostico
                 WHERE tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA."
                   AND id_estudiante    = ?
                   AND id_evento        = ?
                 LIMIT 1";
        $result = $this->db->query($sql, array($idContacto, $idEvento));
		return $result->row_array();
    }
    
    function procesoMatricula($idContacto, $codGrupo, $idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->trans_begin();
            $sql = "SELECT * FROM admision.migrar_admision_matricula(?, ?, ?) resultado";
            $result = $this->db->query($sql, array($codGrupo, $idContacto, $idEvento));
            $resultado = explode('|', $result->row()->resultado);
            _log("PRIMERO: ".print_r($resultado, true));
            if($resultado[0] == "OK") {
                $this->db->trans_commit();
                /////////////////////////
                $this->db->trans_begin();
                $sql = "SELECT * FROM admision.migrar_admision_matricula_edusys(?, ?) resultado";
                $result = $this->db->query($sql, array($idContacto, $result->row()->resultado));
                $resultado = explode('|', $result->row()->resultado);
                _log("SEGUNDO: ".print_r($resultado, true));
                if($resultado[0] == "OK") {
                    $this->db->trans_commit();
                    $rpt['error'] = EXIT_SUCCESS;
                    $rpt['msj']   = MSJ_UPT;
                } else {//ROLLBACK
                    //pendiente
                }
            } else if(isset($resultado[1])) {
                $rpt['msj'] = $resultado[1];
            } else {
                $rpt['msj'] = 'Hubo un error al procesar la matrícula';
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getCursosByGradoNivel($nivel, $grado){
        $sql = "SELECT descripcion,
                       id_config_eval
                  FROM admision.config_eval
                 WHERE flg_activo = ".FLG_ACTIVO."
                   AND _id_nivel  = ?
                   AND _id_grado  = ?";
        $result = $this->db->query($sql, array($nivel, $grado));
        return $result->result();
    }
}