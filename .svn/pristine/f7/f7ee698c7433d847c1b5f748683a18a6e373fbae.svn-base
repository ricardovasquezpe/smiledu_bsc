<?php
class M_docente extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
        
    function registrarGrupoTaller($idTaller, $descGrupo, $capacidad, $idDocente, $arrayGradoGrupo, $idAulaEx) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
            $dataMain = array(
                "__id_taller"   => $idTaller,
                "nombre_grupo"  => $descGrupo,
                "limite_alumno" => $capacidad
            );
            $this->db->insert("main", $dataMain);
            $idMain = $this->db->insert_id();
            
            foreach($arrayGradoGrupo as $rowGrado) {
                $idGrado = _decodeCI($rowGrado['__id_grado']);
                $dataGrupoGrado = array(
                    "__id_aula_ext" => $idAulaEx,
                    "__id_grado"    => $idGrado,
                    "__id_main"     => $idMain
                );
                $this->db->insert('grupo_aula', $dataGrupoGrado);
            }
                                  
            $dataGrupoDocente = array(
                "__id_docente" => $idDocente,
                "flg_activo"   => FLG_ACTIVO,
                "__id_main"    => $idMain,
                "fec_in"       => date('Y-m-d H:i:s')     
            );
            $this->db->insert("grupo_x_docente", $dataGrupoDocente);
            
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function registrarGrupoCursos($idCurso, $descGrupo, $capacidad, $idDocente, $arrayGradoGrupo, $idAula) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
            $dataMain = array(
                "nid_curso"     => $idCurso,
                "nombre_grupo"  => $descGrupo,
                "limite_alumno" => $capacidad
            );
            $this->db->insert("main", $dataMain);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-001)');
            }
            $idMain = $this->db->insert_id();
    
            foreach($arrayGradoGrupo as $rowGrado) {
                $idGrado = _decodeCI($rowGrado['__id_grado']);
                $dataGrupoGrado = array(
                    "__id_aula"   => $idAula,
                    "__id_grado"  => $idGrado,
                    "__id_main"   => $idMain
                );
                $this->db->insert('grupo_aula', $dataGrupoGrado);
                if($this->db->affected_rows() != 1) {
                    throw new Exception('(MC-002)');
                }
            }
    
            $dataGrupoDocente = array(
                "__id_docente" => $idDocente,
                "flg_activo"   => FLG_ACTIVO,
                "__id_main"    => $idMain,
                "fec_in"       => date('Y-m-d H:i:s')
            );
            $this->db->insert("grupo_x_docente", $dataGrupoDocente);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-003)');
            }
    
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;        
    }
    
    function registrarGrupoDocente($arryInsert) {
        $this->db->insert("grupo_x_docente", $arryInsert);
        if($this->db->affected_rows() != 1) {
            throw new Exception('(MC-004)');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_INS);
    }
    
    function getAulas($idGrado, $year) {
        $sql = "SELECT nid_aula,
                       nid_grado,
                       id_tutor,
                       a.desc_aula,
                       CONCAT('(',s.abvr,')') AS sede
                  FROM aula a,
                       sede s
                 WHERE a.nid_grado = ?
                   AND a.year      = ?
                   AND a.nid_sede  = s.nid_sede
              ORDER BY nid_aula";
    $result = $this->db->query($sql, array($idGrado, $year));
    return $result->result();
    }
    
    function getCursos($idGrado, $idAnio) {
        $sql = "SELECT CASE WHEN id_curso IN (SELECT id_curso 
                            			        FROM cursos 
                            			       WHERE _id_area_especifica IN (".ID_AREA_TALLER_ARTISTICO.",".ID_AREA_TALLER_DEPORTIVO.")) THEN 1
                            ELSE null END AS curso_taller, 
                       id_curso,
                       desc_curso
                  FROM notas.fun_get_cursos_grado_year(?, ?)";
        $result = $this->db->query($sql, array($idAnio, $idGrado));
        return $result->result_array();      
    }
    
    function countCursos($idGrado, $idAnio, $idAula) {
        $sql = "SELECT todo.count_cursos,
                       todo.total 
                  FROM (
                	SELECT
                	    (SELECT count(1) 
                	       FROM (SELECT m.nid_curso
                		       FROM main             m, 
                			    grupo_x_docente gd
                		      WHERE m.nid_main = gd.__id_main 
                			AND m.nid_aula    = ?
                			AND gd.flg_activo = '".FLG_ACTIVO."'     
                		      GROUP BY m.nid_curso) as count_cursos) as count_cursos,
                	    (SELECT COUNT(1) total
                	       FROM notas.fun_get_cursos_grado_year(?, ?))
                       ) todo";
        $result = $this->db->query($sql, array($idAula, $idAnio, $idGrado));
        return $result->row_array();
    }
    
    /*
    function countCursosDocente($idAula, $idGrado, $idAnio, $idGrado, $idAnio) {
        $sql = "SELECT m.nid_curso   
                  FROM main m, 
                       aula a
                 WHERE m.nid_aula  = ?
                   AND m.nid_aula  = a.nid_aula
                   AND m.nid_curso IN (SELECT ce._id_curso_equiv       
                                         FROM curso_equivalencia ce,
                                              curso_equivalente  ceq
                                        WHERE ce._id_grado       = ?
                                          AND ce._year_acad      = ?
                                          AND ce._id_curso_equiv = ceq.id_curso_equiv
                                       UNION ALL
                                       SELECT cug._id_curso_ugel
                                         FROM curso_ugel_x_grado cug,
                                              cursos             c
                                        WHERE cug._id_grado      = ?
                                          AND cug.year_acad      = ?
                                          AND c.id_curso         = cug._id_curso_ugel) 
                    	              GROUP BY m.nid_curso";
        $result = $this->db->query($sql, array($idAula, $idGrado, $idAnio, $idGrado, $idAnio));
        return $result->num_rows();
    }
    */
    
    function getDocentesParaAsignar($busqueda, $idAula, $idCurso) {
        $sql = "SELECT p.nid_persona,
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                       INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombre_completo,
                       CONCAT(SPLIT_PART( INITCAP(LOWER(p.nom_persona)), ' ', 1),' ',p.ape_pate_pers, ' ', p.ape_mate_pers) AS nombre_corto
                  FROM persona       p,
                       rol           r,
                       persona_x_rol pr
                 WHERE p.flg_acti    = '".FLG_ACTIVO."'
                   AND pr.flg_acti   = '".FLG_ACTIVO."'
                   AND pr.nid_rol    = ".ID_ROL_DOCENTE."
                   AND r.nid_rol     = pr.nid_rol
                   AND p.nid_persona = pr.nid_persona
                   AND UPPER(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) LIKE UPPER(?)
                   AND p.nid_persona NOT IN (SELECT gd.__id_docente
                                               FROM grupo_x_docente gd,
                                                    main  		     m       
                                              WHERE gd.__id_main   =  m.nid_main
                                                AND gd.flg_activo NOT IN ('".FLG_DOCENTE_DESASIGNADO."') 
                                                AND m.nid_aula     = ?
                                                AND m.estado       = '".FLG_ACTIVO."'
                                                AND m.nid_curso    = ?
                                                 OR __id_taller    = ?    
                            			     GROUP BY gd.__id_docente)       			            
                 ORDER BY CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona) ASC";
        $result = $this->db->query($sql,array('%'.$busqueda.'%', $idAula, $idCurso, $idCurso));
        return $result->result_array();
    }
    
    function asignarDocenteMain($arrayGeneral) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->insert('main', $arrayGeneral);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MAC-001)');
            }
            $data['error']  = EXIT_SUCCESS;
            $data['msj']    = MSJ_INS;
            $data['idmain'] =  $this->db->insert_id();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function asignarDocenteGxD($arrayAsigDoc) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	try {
    		$this->db->insert('grupo_x_docente', $arrayAsigDoc);
    		if($this->db->affected_rows() != 1) {
    			throw new Exception('(MAC-001)');
    		}
    		$data['error']  = EXIT_SUCCESS;
    		$data['msj']    = MSJ_INS;
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	return $data;
    }
    
    function getDocentesByAulaCurso($idAula, $idCurso) {
        $sql = "SELECT p.nid_persona,
                       m.nid_main,
                       gd.flg_titular,
                       gd.flg_activo,
                       CONCAT(SPLIT_PART( INITCAP(LOWER(p.nom_persona)), ' ', 1),' ',p.ape_pate_pers) AS nombre_corto,
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona
			      FROM main            m,
                       grupo_x_docente gd,
                       persona         p
			     WHERE m.nid_aula      = ?
			       AND m.nid_curso     = ?
			       AND m.estado        = '".FLG_DOCENTE_ASIGNADO."'
			       AND gd.__id_docente = p.nid_persona
			       AND gd.__id_main    = m.nid_main
			       AND gd.__id_docente = p.nid_persona
			       AND gd.flg_activo   IN ('".FLG_DOCENTE_ASIGNADO."', '".FLG_DOCENTE_DESACTIVADO."')";
        $result = $this->db->query($sql, array($idAula, $idCurso));
        return $result->result_array();
    }
    
    function desasDesactDocenteAsignado($idPersonaDocente, $idAula, $idCurso, $fechaActual, $optionRadio) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
        	$sql = "UPDATE grupo_x_docente
        			   SET flg_activo = ?,
        			       fec_fin	  = ?	
    				      WHERE __id_docente = ?
        	                AND flg_activo <> '".FLG_DOCENTE_DESASIGNADO."'
    						AND __id_main  IN (SELECT nid_main
    										     FROM main
    									    	WHERE nid_aula  = ?
    										      AND nid_curso = ? )";	
        	$this->db->query($sql, array($optionRadio, $fechaActual, $idPersonaDocente, $idAula, $idCurso));_logLastQuery();
        	   
        	    $sql2 = "UPDATE grupo_x_docente
                            SET flg_titular = '".DOCENTE_SUPLENTE."'
                          WHERE __id_main   = (SELECT MIN(__id_main)
                                		         FROM grupo_x_docente
                                		        WHERE flg_activo = '".FLG_DOCENTE_DESASIGNADO."'
                                		          AND flg_titular = '".DOCENTE_TITULAR."'
                                		          AND __id_main  IN (SELECT nid_main
                                			                           FROM main
                                				                      WHERE nid_aula  = ?
                                					                    AND nid_curso = ? ))";
        	    $this->db->query($sql2,array($idAula, $idCurso));
        	     
        	    $sql3 = "UPDATE grupo_x_docente
                            SET flg_titular = '".DOCENTE_TITULAR."'
                          WHERE __id_main   = (SELECT MIN(__id_main)
                        	 	                 FROM grupo_x_docente
                        		                WHERE flg_activo IN ('".FLG_DOCENTE_ASIGNADO."', '".FLG_DOCENTE_DESACTIVADO."')
                        		                  AND flg_titular IS NOT NULL
                        		                  AND __id_main  IN (SELECT nid_main
                        			                                   FROM main
                        				                              WHERE nid_aula  = ?
                        					                            AND nid_curso = ? ))";
        	    $this->db->query($sql3, array($idAula, $idCurso));
        	
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function comparacionIdMain($idMain) {
        $sql = "SELECT COUNT(1) AS cout 
                  FROM asistencia a, 
                       main       m
                 WHERE a.__nid_main = m.nid_main
                   AND m.nid_main   = ?";
        $result = $this->db->query($sql, $idMain);
        return $result->row()->cout;
    }
        
    function getTutorParaAsignar($buscarTutor, $idAula) {
        $sql = "SELECT p.nid_persona, 
                       a.desc_aula,   
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                            CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ',INITCAP(p.nom_persona)) AS nombre_completo
                  FROM persona p 
             LEFT JOIN aula a 
                    ON a.id_tutor = p.nid_persona 
                  JOIN persona_x_rol pr
                    ON  p.nid_persona = pr.nid_persona  
                 WHERE nid_rol IN (".ID_ROL_EVALUADOR.",
                                   ".ID_ROL_DOCENTE.",
                                   ".ID_ROL_COORDINADOR_ACADADEMICO.",
                                   ".ID_ROL_TUTOR.",
                                   ".ID_ROL_PROFESORA_ASISTENTE.",
                                   ".ID_ROL_PSICOPEDAGOGO_SEDE.",
                                   ".ID_ROL_ENFERMERA.",
                                   ".ID_ROL_BIBLIOTECARIO.",
                                   ".ID_ROL_SUBDIRECTOR.")                    
                   AND UPPER(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) LIKE UPPER(?)
               GROUP BY p.nid_persona, nombre_completo, a.desc_aula";
        $result = $this->db->query($sql, array('%'.$buscarTutor.'%'));
        return $result->result_array();
    }
    
    function asignarTutor($idTutor, $idAula, $arrayIdTutor, $idTutorRolPer) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;      
        try {
            $this->db->trans_begin();
            $this->db->where ('nid_aula', $idAula);
            $this->db->update('aula', $arrayIdTutor);  
            
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-005)');
            }

            if($idTutorRolPer != null) {
                /*$this->db->delete('persona_x_rol', array('nid_persona' => $idTutorRolPer, 'nid_rol' => ID_ROL_TUTOR));
                if($this->db->affected_rows() != 1) {
                    throw new Exception('(MC-008)');
                }*/
            } 
                    
            $dataRolxPersona = array(
                "nid_persona" => $idTutor, 
                "nid_rol"     => ID_ROL_TUTOR,
                "flg_acti"    => FLG_ACTIVO
            );
            $this->db->insert("persona_x_rol", $dataRolxPersona);
            
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-006)');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) { 
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function contarArrayCotutor($idAula) {
        $sql = "SELECT count(id_cotutores)+1 AS count 
                  FROM aula a,persona p 
                 WHERE p.nid_persona = ANY(id_cotutores) 
                   AND nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        if($result->num_rows() == 1) {
            return $result->row()->count;
        } else {
            throw new Exception('(MC-007)');
        }  
    }
    
    function asignarCoTutor($idCoTutor, $idAula, $indxNuevo) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $sql = "UPDATE aula SET id_cotutores[".$indxNuevo."] = ? 
                     WHERE nid_aula = ?";
            $this->db->query($sql, array($idCoTutor, $idAula));
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al asignar el Tutor');
        } else {
            return $data;
        } 
    }
    
    function checkIfCotutorExiste($idAula, $idCotutorAdd) {
        $sql = "SELECT 1 AS existe
                  FROM aula 
                 WHERE nid_aula = ?
                   AND ? = ANY(id_cotutores)";
        $result = $this->db->query($sql, array($idAula, $idCotutorAdd));
        if($result->num_rows() == 1) {
            return $result->row()->existe;
        }
        return null;
    }
          
    function countTutorAsignado($idPersona) {
       $sql = "SELECT COUNT(1) AS count 
                FROM aula where id_tutor = ?";
       $result = $this->db->query($sql,array($idPersona));
       return $result->row()->count;
    }
    
    function selectTutorAula($idAula) {
        $sql = "SELECT id_tutor  
                  FROM aula 
                 WHERE nid_aula = ?";
        $result = $this->db->query($sql,array($idAula));
        if($result->num_rows() == 1) {
            return $result->row()->id_tutor;
        }
        return null;
    }
    
    function getFotoTutor($idAula) {
        $sql = "SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                       CONCAT(SPLIT_PART( INITCAP(LOWER(p.nom_persona)), ' ', 1),' ',p.ape_pate_pers) AS nombre_corto                      
                  FROM persona p, 
                       aula a          
           	     WHERE p.nid_persona = a.id_tutor
                   AND a.nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    function getTutores($idAula) {
        $sql = "SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                       CONCAT(SPLIT_PART( INITCAP(p.nom_persona), ' ', 1),' ',p.ape_pate_pers, ' ',SUBSTRING(p.ape_mate_pers, 1, 1),'.') AS nombre_corto,
                       p.nid_persona         
                  FROM persona p,
                       aula a
           	     WHERE p.nid_persona = a.id_tutor
                   AND a.nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        return $result->result_array();
    }
    
    function getCotutores($idAula) {
        $sql = "SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                       CONCAT(SPLIT_PART( INITCAP(p.nom_persona), ' ', 1),' ',p.ape_pate_pers, ' ',SUBSTRING(p.ape_mate_pers, 1, 1),'.') AS nombre_corto,
                       p.nid_persona         
                  FROM persona p,
                       aula a
           	     WHERE p.nid_persona = ANY(a.id_cotutores)
                   AND a.nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        return $result->result_array();
    }
    
    function ubicacionArrayCotutor($idAula, $idCotutor) {
        $sql = "SELECT array_search(?, (SELECT id_cotutores 
                        		          FROM aula 
                        		         WHERE nid_aula = ?)) AS position";
        $result = $this->db->query($sql, array($idCotutor, $idAula));
        return $result->row_array();
    }
    
    function reasignarCotutor($idAula, $i, $newIdCotutor) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $sql = "UPDATE aula SET id_cotutores[".$i."] = ? 
                     WHERE nid_aula = ?";
             $this->db->query($sql, array($newIdCotutor, $idAula));
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al reasignar el Cotutor');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function maxCotutor($idAula) {
        $sql = "SELECT COALESCE(MAX(nid_persona), 0) cotutor_max
                  FROM aula    a, 
                       persona p 
                 WHERE nid_persona = ANY(id_cotutores) 
                   AND nid_aula = ?";
        $result     = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    function desasignarCoTutorFromAula($idAula, $idCoTutor) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $sql = "UPDATE aula
                       SET id_cotutores = array_remove(id_cotutores::INT[],ARRAY[$idCoTutor]::INT[])
                     WHERE nid_aula = ?";
            $this->db->query($sql, array($idAula));

            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        if($this->db->affected_rows() != 1) {
            throw new Exception('(MC-009)');
        } else {
            return $data;
        }
    }
    
    function desasignarTutorFromAula($idAula, $idTutor) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
            $this->db->where('nid_aula', $idAula);
            $this->db->update('aula', array("id_tutor" => null));
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al desasignar al Tutor');
            }

            $this->db->delete('persona_x_rol', array('nid_persona' => $idTutor, 'nid_rol' => ID_ROL_TUTOR) );
            
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
             
        if($this->db->affected_rows() != 1) {
            throw new Exception('(MC-010)');
        } else {
            return $data;
        }
    }
    
    function getCantidadCoTutores($idAula) {
        $sql = "SELECT COALESCE(array_length(id_cotutores, 1), 0) AS cant_cotutores
                  FROM aula
                 WHERE nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        if($result->num_rows() == 1) {
            return $result->row()->cant_cotutores;
        }
        return null;//NO EXISTE EL REGISTRO
    }
    
    function countTitular($idAula, $idCurso) {
        $sql = "SELECT CASE WHEN COUNT(1) >= 1 THEN null 
                	        ELSE COUNT(1) END AS count
                  FROM grupo_x_docente 
                 WHERE flg_titular  = '".DOCENTE_TITULAR."'
                   AND __id_main  IN (SELECT nid_main
                			FROM main
                		       WHERE nid_aula  = ?
                			     AND nid_curso = ? )";
        $result = $this->db->query($sql, array($idAula, $idCurso));
        if($result->num_rows() == 1) {
            return $result->row()->count;
        }
        return null;
    }
    
    function getGrupos($idGrado, $idTaller) { 
        $sql = " SELECT m.nid_main,
                        CONCAT(t.desc_taller,'.',m.nombre_grupo) AS nombre_grupo,
                        m.limite_alumno,
                        t.id_taller,
                        t.desc_taller,
                        ae.desc_aula_ext,
                        p.nom_persona,    
                        (SELECT string_agg(CONCAT(g.abvr, n.abvr),' - ') FROM grupo_aula ga, 
                										   grado g,
                										   nivel n 
                								  WHERE ga.__id_grado = g.nid_grado
                								    AND n.nid_nivel   = g.id_nivel
                								    AND ga.__id_main  = m.nid_main) as grados						    
                  FROM main             m,        
                       taller           t,
                       aula_externa    ae,
                       grupo_aula      ga,
                       persona          p,
                       grupo_x_docente  gd  
                 WHERE t.id_taller    = m.__id_taller
                   AND ae.id_aula_ext = ga.__id_aula_ext
                   AND ga.__id_main   = m.nid_main
                   AND p.nid_persona  = gd.__id_docente
                   AND m.nid_main     = gd.__id_main 
                   AND t.id_taller    = COALESCE(?, t.id_taller)
                   AND ga.__id_grado  = COALESCE(?, ga.__id_grado)
              GROUP BY m.nid_main, t.desc_taller, m.nombre_grupo, t.id_taller, t.desc_taller, ae.desc_aula_ext, p.nom_persona
             ORDER BY m.nid_main DESC";
        $result = $this->db->query($sql, array($idTaller, $idGrado));
        if($result->num_rows() >= 1) {
            return $result->result_array();
        }
        return array();               
    }
    
    function countGrupos($idCurso, $idArea, $idGrado, $idAnio) {
        $sql = "SELECT count(1) AS count_cursos,
                       (SELECT count(1) AS count_taller FROM(SELECT id_curso FROM notas.fun_get_cursos_area(".ID_AREA_TALLER_ARTISTICO.")
                                            				  WHERE id_curso = ?
                                            				 UNION ALL
                                            				 SELECT id_curso FROM notas.fun_get_cursos_area(".ID_AREA_TALLER_DEPORTIVO.")
                                            				  WHERE id_curso = ?) a) 
            	  FROM main            m,
                       aula	            a,
                       grupo_aula      ga,
                       persona          p,
        			   sede             s,     
                       notas.fun_get_cursos_area(?) cg,
                       grupo_x_docente              gd
                 WHERE ga.__id_aula   = a.nid_aula
                   AND ga.__id_main   = m.nid_main
        		   AND a.nid_sede     = s.nid_sede
                   AND p.nid_persona  = gd.__id_docente
                   AND m.nid_main     = gd.__id_main
                   AND cg.id_curso    = m.nid_curso
                   AND ga.__id_grado  = ?
                   AND cg.id_curso    = ?
                   AND a.year	      = ?";
        $result = $this->db->query($sql, array($idCurso, $idCurso, $idArea, $idGrado, $idCurso, $idAnio));
        if($result->num_rows() == 1) {
            return $result->row();
        }
        return null;
        }

        function getGruposCursosAulas($idCurso, $idGrado, $year, $idArea) {
            $sql = "(SELECT m.nid_main,
                       CONCAT(t.desc_taller,'.',m.nombre_grupo) AS nombre_grupo,
                       m.limite_alumno,
                       ae.desc_aula_ext,
                       p.nom_persona,    
                       (SELECT string_agg(CONCAT(g.abvr, n.abvr),' - ') FROM grupo_aula ga, 
										   grado g,
										   nivel n 
									WHERE ga.__id_grado = g.nid_grado
									  AND n.nid_nivel   = g.id_nivel
									  AND ga.__id_main  = m.nid_main) as grados,
                    (SELECT id_curso  FROM( 
					 SELECT id_curso FROM notas.fun_get_cursos_area(".ID_AREA_TALLER_ARTISTICO.")
					  WHERE id_curso = ?
					 UNION ALL
					 SELECT id_curso FROM notas.fun_get_cursos_area(".ID_AREA_TALLER_DEPORTIVO.")
					  WHERE id_curso = ?) a) 									   							    							    
                      FROM main             m,        
                           taller           t,
                           aula_externa    ae,
                           grupo_aula      ga,
                           persona          p,
                           grupo_x_docente  gd  
                     WHERE t.id_taller    = m.__id_taller
                       AND ae.id_aula_ext = ga.__id_aula_ext
                       AND ga.__id_main   = m.nid_main
                       AND p.nid_persona  = gd.__id_docente
                       AND m.nid_main     = gd.__id_main 
                       AND ga.__id_grado  = COALESCE(?, ga.__id_grado)
                  GROUP BY m.nid_main, t.desc_taller, m.nombre_grupo, t.id_taller, t.desc_taller, ae.desc_aula_ext, p.nom_persona
                 ORDER BY m.nid_main DESC)      
                  UNION ALL
                   (SELECT m.nid_main,
                           m.nombre_grupo,
                           m.limite_alumno,
                           a.desc_aula,
                           p.nom_persona,
                           (SELECT string_agg(CONCAT(g.abvr, n.abvr),' - ') FROM grupo_aula ga,
    										   grado g,
    										   nivel n
    									WHERE ga.__id_grado = g.nid_grado
    									  AND n.nid_nivel   = g.id_nivel
    									  AND ga.__id_main  = m.nid_main) as grados,		  
    		               m.nid_curso
                      FROM main             m,
                           aula	            a,
                           grupo_aula      ga,
                           persona          p,
            			   sede             s,     
                           notas.fun_get_cursos_area(?) cg,
                           grupo_x_docente              gd
                     WHERE ga.__id_aula   = a.nid_aula
                       AND ga.__id_main   = m.nid_main
            		   AND a.nid_sede     = s.nid_sede
                       AND p.nid_persona  = gd.__id_docente
                       AND m.nid_main     = gd.__id_main
                       AND cg.id_curso    = m.nid_curso
                       AND ga.__id_grado  = ?
                       AND cg.id_curso    = ?
                       AND a.year	      = ?                      
                  GROUP BY m.nid_main, m.nombre_grupo, a.desc_aula, p.nom_persona, ga.__id_aula
                 ORDER BY m.nid_main DESC)";
            $result = $this->db->query($sql, array($idCurso, $idCurso, $idGrado, $idArea, $idGrado, $idCurso, $year));
            if($result->num_rows() >= 1) {
                return $result->result_array();
            }
            return null;
            }
}
