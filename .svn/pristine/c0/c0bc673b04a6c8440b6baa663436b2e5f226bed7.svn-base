<?php
class M_tutoria extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
   
    function getEstudiantes($idAula, $year, $idTutor) {
        $sql = "select * from notas.fun_get_orden_merito(?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, array($year, $idSede=null, $idGrado=null, $idAula, $idCiclo=null, $idTutor, $idCurso=null));
        return $result->result_array();
    }
    
    function alumnoPromedioOrdenMerito($year, $idSede, $idGrado=null, $idAula=null, $idCiclo=null, $idTutor=null, $idCurso=null) {
        $sql = "select * from notas.fun_get_orden_merito(?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql, array($year, $idSede, $idGrado, $idAula, $idCiclo, $idTutor, $idCurso));
        return $result->result_array();
    }
    
    function getCapacidad($idTutor) {
        $sql = "SELECT (SELECT CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',SPLIT_PART(INITCAP(p.nom_persona),' ',1)) AS nombre_corto
                          FROM persona p,
                               aula    aul
                	     WHERE a.id_tutor = ?
                	       AND a.nid_aula = nid_aula 
                	       AND p.nid_persona = a.id_tutor),
                       (SELECT s.abvr FROM SEDE s, aula  WHERE a.nid_aula = nid_aula
                                                           AND s.nid_sede = a.nid_sede) as sede,
                       count(1) as cant_estu,
                                   a.year,
                                   a.capa_max,
                                   a.nid_aula,
                                   a.desc_aula
                  FROM persona_x_aula pa,
                       persona         p,
                       aula            a
                 WHERE a.id_tutor      = ?
                   AND a.year          = pa.year_academico
                   AND pa.__id_persona = p.nid_persona
                   AND a.nid_aula      = pa.__id_aula
                GROUP BY a.capa_max, a.year, a.nid_aula";
        $result = $this->db->query($sql, array($idTutor, $idTutor));
        return $result->result_array();
    }
    
    function getIdGrado($idAula) {
        $sql = "SELECT nid_grado
                  FROM aula 
                 WHERE nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        return $result->row()->nid_grado;
    }
    
    function getCursos($year, $idAula) {
        $sql = "SELECT desc_curso, 
                       id_curso,
	        		   (SELECT nid_grado
	                      FROM aula 
	                     WHERE nid_aula = ?) id_grado 
                  FROM notas.fun_get_cursos_grado_year(?, (SELECT nid_grado
											                 FROM aula 
											                WHERE nid_aula = ?))";
        $result = $this->db->query($sql, array($idAula, $year, $idAula));
        return $result->result_array();
    }
    
    function getDocentesxCurso($year, $idGrado, $idAula) {
        $sql = "SELECT * FROM notas.fun_get_docentes(?, ?, ?, ?)";
        $result = $this->db->query($sql, array($year, $idSede=null, $idGrado, $idAula));
        return $result->result_array();
    }
    
    function getDetalleNotas($idEstudiante, $idCurso, $idGrado, $idAnio, $idCicloAcad, $idAula) {
        $sql = " SELECT mxi._id_ciclo_acad, 
                        mxi.concepto_evaluar,
		                ixe.nota_numerica, 
                        ixe._id_estudiante
                   FROM notas.instrumento_x_estudiante ixe, 
                        notas.matriz_x_instrumento     mxi  
                  WHERE ixe._id_estudiante  = ?
                    AND ixe._id_curso 	    = ?
                    AND ixe._id_grado       = ?
                    AND ixe._year_acad	    = ?
                    AND mxi._id_ciclo_acad  = ?
                    AND ixe._id_main        IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                    AND ixe._id_grado       = mxi._id_grado
                    AND ixe._year_acad      = mxi._year_acad 
                    AND ixe._id_curso       = mxi._id_curso
                    AND ixe._id_instrumento = mxi._id_instrumento
                    AND ixe._id_main        = mxi._id_main
                    AND ixe.correlativo     = mxi.correlativo
                   GROUP BY ixe.correlativo,
    	                    mxi._id_ciclo_acad, 
                            mxi.concepto_evaluar,
                            ixe.nota_numerica, 
    	                    ixe._id_estudiante";
        $result = $this->db->query($sql, array($idEstudiante, $idCurso, $idGrado, $idAnio, $idCicloAcad, $idAula));  
        if($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }    
    }
    
    function getPdfDatos($idAula, $idAlumno) {
        $sql = " SELECT *
                   FROM (SELECT p.nid_persona,
                                pa.promedio_final,
                                CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers,' ',SPLIT_PART(INITCAP(p.nom_persona),' ',1)) AS nombre_corto,
                                CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers),', ',INITCAP(p.nom_persona)) AS nombre_completo,
                                INITCAP(p.ape_pate_pers) AS ape_pate_pers,
                                INITCAP(p.ape_mate_pers) AS ape_mate_pers,
                                INITCAP(p.nom_persona)   AS nom_persona,              
                                m.nid_aula,
                                m.nid_main,
                                pa.year_academico                  
                           FROM persona        p,
                                persona_x_aula pa,
                                main           m
                          WHERE m.nid_main        IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                            AND p.nid_persona     = ? 
                            AND pa.flg_acti       = '".FLG_ACTIVO."'
                            AND pa.year_academico = (SELECT year FROM aula WHERE nid_aula = m.nid_aula)
                            AND pa.__id_aula      = m.nid_aula
                            AND p.nid_persona     = pa.__id_persona
                          ) AS estu
                                LEFT JOIN asistencia a ON (    estu.nid_persona   = a.__id_alumno
               				   				               AND estu.nid_main      = a.__nid_main
               								               AND a.fecha_asistencia = (SELECT now()::date) )
                ORDER BY estu.ape_pate_pers, estu.ape_mate_pers, estu.nom_persona";
        $result = $this->db->query($sql, array($idAula, $idAlumno));
        if($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getAbrGradoAula($idGrado, $idAula) {
        $sql = " SELECT g.abvr, 
                        g.desc_grado, 
                        a.desc_aula 
                   FROM grado g, 
                        aula  a 
                  WHERE g.nid_grado = ? 
                    AND nid_aula    = ? 
              AND  g.nid_grado = a.nid_grado";  
        $result = $this->db->query($sql, array($idGrado, $idAula));
        return $result->row_array();
    }
        
    function getCursosLibreta($idGrado, $anio) {
            $sql = "SELECT c.desc_curso,
            			   c.id_curso,
            			   (SELECT string_agg(CONCAT(ce.desc_curso_equiv,'|',ce.id_curso_equiv),',') FROM curso_equivalente ce,
            									    curso_equivalencia ceq 
            								      WHERE ceq._id_curso_ugel  = c.id_curso
            									    AND ceq._id_curso_equiv = id_curso_equiv),
        		           (SELECT string_agg(CONCAT(nc.desc_competencia),'|') AS competencia_curso 
                              FROM notas.matriz_x_competencia             mxc,
        			               notas.competencia                       nc
            			     WHERE mxc._id_grado       = ?
                               AND mxc._year_acad      = ?
            			       AND mxc._id_curso       = c.id_curso  
            			       AND mxc._id_competencia = id_competencia
            			       AND mxc._id_curso       = c.id_curso)
        		      FROM cursos             c, 
                           curso_ugel_x_grado cg 
        		     WHERE  c.id_curso  = cg._id_curso_ugel 
        		       AND cg._id_grado = ?";
        $result = $this->db->query($sql, array($idGrado, $anio, $idGrado));
        return $result->result_array();
    }
    
    function getCountAsistencia($idAlumno, $idAula) {
        $sql = "SELECT (SELECT COUNT(1) AS tarde_justif
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ? 
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_TARDE_JUSTIF."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                       (SELECT COUNT(1) AS tarde
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ? 
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_TARDE."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                       (SELECT COUNT(1) AS falta
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ? 
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_FALTA."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                       (SELECT COUNT(1) AS falta_justif
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ? 
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_FALTA_JUSTIF."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin), 
                           desc_ciclo_acad
                  FROM ciclo_academico ca
               order by ca.fec_inicio";
        $result = $this->db->query($sql, array($idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula));
        if($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    
    function getCountAsistenciaAula($idAula, $year) {
        $sql = "SELECT (SELECT COUNT(1) falta 
                          FROM asistencia 
                         WHERE __id_aula        = ?
        		           AND __year_academico = ?
                           AND estado = '".ASISTENCIA_FALTA."'),
	                   (SELECT COUNT(1) falta_justif 
                          FROM asistencia 
                         WHERE __id_aula        = ? 
		                   AND __year_academico = ?
                           AND estado = '".ASISTENCIA_FALTA_JUSTIF."'),
                       (SELECT COUNT(1) tarde 
                          FROM asistencia 
                         WHERE __id_aula        = ?
	                       AND __year_academico = ?
                           AND estado = '".ASISTENCIA_TARDE."'),
                       (SELECT COUNT(1) tarde_justif 
                          FROM asistencia 
                         WHERE __id_aula        = ?
		                   AND __year_academico = ?
                           AND estado = '".ASISTENCIA_TARDE_JUSTIF."')";
        $result = $this->db->query($sql, array($idAula, $year, $idAula, $year, $idAula, $year, $idAula, $year));
        return $result->row_array();
    }
    
    function getCountAsistenciaGrafic($idAlumno, $idAula, $bimestre) {
        $sql = "SELECT (SELECT COUNT(1) AS tarde_justif
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ?
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_TARDE_JUSTIF."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                       (SELECT COUNT(1) AS tarde
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ?
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_TARDE."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                       (SELECT COUNT(1) AS falta
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ?
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_FALTA."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                       (SELECT COUNT(1) AS falta_justif
                          FROM asistencia a
                         WHERE a.__id_alumno = ?
                           AND a.__id_aula   = ?
                           AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                           AND a.estado      = '".ASISTENCIA_FALTA_JUSTIF."'
                           AND a.fecha_asistencia BETWEEN ca.fec_inicio AND ca.fec_fin),
                           desc_ciclo_acad,
                           id_ciclo    
                  FROM ciclo_academico ca
                 WHERE id_ciclo = ?
               order by ca.fec_inicio";
        $result = $this->db->query($sql, array($idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $bimestre));
        if($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }
        
    function promedioCursoxBimestre($idEstudiante, $idAnio, $idCicloAcad, $idCurso, $idMain) {
        $sql = " SELECT CASE WHEN ixe._id_curso = ixe._id_curso THEN Round(AVG(ixe.nota_numerica), 4) END AS promedio
                                  FROM notas.instrumento_x_estudiante ixe, notas.matriz_x_instrumento mxi
                                 WHERE ixe._id_estudiante   = ?
                                   AND ixe._id_main         = ?
                                   AND mxi._id_ciclo_acad   = ?
                                   AND mxi._id_curso        = ?
                                   AND ixe._id_main         = mxi._id_main
                                   AND ixe._id_instrumento  = mxi._id_instrumento
                                 GROUP BY ixe._id_curso, ixe._id_estudiante ";
        $result = $this->db->query($sql, array($idEstudiante, $idMain, $idCicloAcad, $idCurso));
        if($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function agregarComentario($comen, $idAula, $idAlumno, $Anio) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
            try {
                $this->db->where ('__id_persona'  , $idAlumno);
                $this->db->where ('__id_aula'     , $idAula);
                $this->db->where ('year_academico', $Anio);
                $this->db->update('persona_x_aula', $comen);
                if($this->db->affected_rows() != 1) {
                    throw new Exception();
                }
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = MSJ_INS;
            } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            }
            return $data;
    }
    
    function getComentario($idAlumno, $idAula, $Anio, $bimestre) {
        $sql = "SELECT CASE WHEN ca.id_ciclo = ".BIMESTRE_I  ." THEN comentario_bim_i
		                    WHEN ca.id_ciclo = ".BIMESTRE_II ." THEN comentario_bim_ii
		                    WHEN ca.id_ciclo = ".BIMESTRE_III." THEN comentario_bim_iii
		                    WHEN ca.id_ciclo = ".BIMESTRE_IV ." THEN comentario_bim_iv END AS comentario
                  FROM persona_x_aula pxa, 
                       ciclo_academico ca 
                 WHERE pxa.__id_persona   = ?
                   AND pxa.__id_aula      = ?
                   AND pxa.year_academico = ?
                   AND ca.id_ciclo        = ?  
                   AND pxa.year_academico = ca.year_acad";
        $result = $this->db->query($sql, array($idAlumno, $idAula, $Anio, $bimestre));
        if($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }
         
    function getPromByEstudiante($idEstudiante, $idAula, $year, $indic, $idCurso, $bim) {
        $sql = "SELECT ca.id_ciclo,
                       ca.desc_ciclo_acad,
                       CASE WHEN (AVG(ixe.nota_numerica) IS NOT NULL) THEN Round(AVG(ixe.nota_numerica), 3)::character varying
                						      ELSE '-' 
                       END AS promedio
                  FROM ciclo_academico       ca 
                       LEFT JOIN notas.matriz_x_instrumento     mxi ON(mxi._id_ciclo_acad = ca.id_ciclo)
                       LEFT JOIN notas.instrumento_x_estudiante ixe ON(ixe._id_instrumento     = mxi._id_instrumento
                                        						       AND ixe._year_acad      = ? 
                                        						       AND ixe._id_estudiante  = ? 
                                                                       AND ixe._id_curso       = COALESCE(?, ixe._id_curso)   
                                        						       AND ixe._id_grado       = mxi._id_grado
                                        						       AND ixe._id_curso       = mxi._id_curso
                                        						       AND ixe._year_acad      = mxi._year_acad
                                        						       AND ixe._id_competencia = mxi._id_competencia
                                        						       AND ixe._id_capacidad   = mxi._id_capacidad
                                        						       AND ixe._id_indicador   = mxi._id_indicador
                                        						       AND ixe._id_main        = mxi._id_main
                                        						       AND ixe.correlativo     = mxi.correlativo
                                        						       AND ixe._id_main IN (SELECT nid_main 
                                                    										  FROM main 
                                                    										 WHERE nid_aula = ?))
                 WHERE ca.year_acad = ?
                   AND ca.id_ciclo  = COALESCE(?, ca.id_ciclo)
                GROUP BY ca.id_ciclo
                ORDER BY ca.fec_inicio";
        
        $result = $this->db->query($sql, array($year, $idEstudiante, $idCurso, $idAula, $year, $bim));
        if($indic != null) {
            return $result->result_array();
        } else {
            return $result->row_array();
        }
    }
    
    function getFechaIniFin($bimestre) {
        $sql = 'SELECT fec_inicio, 
                          fec_fin 
                  FROM ciclo_academico 
                 WHERE id_ciclo = ? ';
        $result = $this->db->query($sql,array($bimestre));
        return $result->row_array();
    }
    
    function getStudentAwardsByMain($idAula, $idEstudiante,$fecha_inic, $fecha_fin, $flg_positivo) {
        $sql = "SELECT CASE WHEN awards.id_award = awards.id_award THEN COUNT(1) 
        	                ELSE COUNT(1) END AS cantidad, 
        	           a.desc_award,
        	           a.ruta_icono
        	      FROM (SELECT (json_array_elements(awards_estudiante_json->'awards')->>'id_award')::integer AS id_award,
                            	         (json_array_elements(awards_estudiante_json->'awards')->>'fec_registro')::date AS fec_registro,
                            	         (json_array_elements(awards_estudiante_json->'awards')->>'id_main')::integer   AS id_main,
                            	         (json_array_elements(awards_estudiante_json->'awards')->>'id_estudiante')::integer AS id_estu,
                            	         (json_array_elements(awards_estudiante_json->'awards')->>'audi_usua_regi')::integer AS id_pers_regi,
                            	         (json_array_elements(awards_estudiante_json->'awards')->>'audi_pers_regi')::text    AS pers_regi
                            	    FROM notas.main_x_estudiante me
                            	   WHERE me._id_main       IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                            	     AND me._id_estudiante = ? ) 
                                 AS awards,
                                   notas.award a
                           WHERE awards.id_award = a.id_award
                             AND flg_positivo    = ?
                             AND awards.fec_registro BETWEEN ? AND ?
                           GROUP BY awards.id_award,  a.desc_award,  a.ruta_icono 
                           ORDER BY cantidad desc";
        $result = $this->db->query($sql, array($idAula, $idEstudiante, $flg_positivo, $fecha_inic, $fecha_fin));
        return $result->result_array();
    }
    
    function promedioCursos($idAnio, $idGrado, $idEstudiante, $idCicloAcad, $idAula) {
        $sql = "SELECT CASE WHEN (Round(AVG(ixe.nota_numerica),4) IS NULL) THEN 0.00
     	                                                                   ELSE Round(AVG(ixe.nota_numerica),4) 
     	               END AS promedio, 
                       mxi._id_ciclo_acad, 
                       cg.desc_curso 
                  FROM notas.fun_get_cursos_grado_year(?, ?) cg  
                       LEFT JOIN notas.instrumento_x_estudiante ixe ON(cg.id_curso        = ixe._id_curso 
                                                                   AND ixe._id_estudiante = ? 
                                                                   AND ixe._id_grado      = ? 
                                                                   AND ixe._year_acad	  = ?
                                                                   AND ixe._id_main       IN (SELECT nid_main FROM main WHERE nid_aula = ?))
                       LEFT JOIN notas.matriz_x_instrumento     mxi ON(ixe._id_grado      = mxi._id_grado 
                                                                   AND ixe._year_acad     = mxi._year_acad 
                                                                   AND ixe._id_curso      = mxi._id_curso 
                                                                   AND ixe._id_main       = mxi._id_main
                                                                   AND mxi._id_ciclo_acad = ?)
              GROUP BY mxi._id_ciclo_acad, cg.desc_curso;";
        $result = $this->db->query($sql, array($idAnio, $idGrado, $idEstudiante, $idGrado, $idAnio, $idAula ,$idCicloAcad));
        return $result->result();
    }
    
    function getNotasAula($idAnio, $idAula) {
        $sql = "SELECT pa.promedio_final,
                       CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers) AS nombre_corto 
                  FROM persona_x_aula pa, persona p 
                 WHERE __id_aula         = ?
                   AND pa.year_academico = ?
                   AND p.nid_persona = pa.__id_persona";
        $result = $this->db->query($sql, array($idAula, $idAnio));
        return $result->result();
    }
    
    function notaAsistencia($idAlumno, $idAula) {
        $sql = "SELECT rango_limite, 
                       nota_alfabetica,
                       nota_numerica,	
                       ABS((SELECT (SELECT (peso * (SELECT COUNT(1) AS tarde
                                                      FROM asistencia a
                                                     WHERE a.__id_alumno = ?
                                                       AND a.__id_aula   = ? 
                                                       AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                                                       AND a.estado      = '".ASISTENCIA_TARDE."'
                                                       AND a.fecha_asistencia BETWEEN ca.fec_inicio AND (SELECT fec_fin + CAST('7 days' AS INTERVAL) 
                                                                        							       FROM ciclo_academico 
                                                                        								  WHERE NOW() BETWEEN fec_inicio AND fec_fin)))
                        	  FROM asistencia_config
                        	 WHERE id_asist_config = '".ID_ASISTENCIA_TARDE."') +
                        	(SELECT (peso * (SELECT COUNT(1) AS falta
                                                  FROM asistencia a
                                                 WHERE a.__id_alumno = ?
                                                   AND a.__id_aula   = ? 
                                                   AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                                                   AND a.estado      = '".ASISTENCIA_FALTA."'
                                                   AND a.fecha_asistencia BETWEEN ca.fec_inicio AND (SELECT fec_fin + CAST('7 days' AS INTERVAL) 
                                                            									       FROM ciclo_academico 
                                                            									      WHERE NOW() BETWEEN fec_inicio AND fec_fin)))
                        	  FROM asistencia_config
                        	 WHERE id_asist_config = '".ID_ASISTENCIA_FALTA."') +
                        	 (SELECT (peso * (SELECT COUNT(1) AS falta
                                                  FROM asistencia a
                                                 WHERE a.__id_alumno = ?
                                                   AND a.__id_aula   = ? 
                                                   AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                                                   AND a.estado      = '".ASISTENCIA_TARDE_JUSTIF."'
                                                   AND a.fecha_asistencia BETWEEN ca.fec_inicio AND (SELECT fec_fin + CAST('7 days' AS INTERVAL) 
                                                            									       FROM ciclo_academico 
                                                            									      WHERE NOW() BETWEEN fec_inicio AND fec_fin)))
                        	  FROM asistencia_config
                        	 WHERE id_asist_config = ".ID_ASISTENCIA_TARDE_JUSTIF.") +
                        	(SELECT (peso * (SELECT COUNT(1) AS falta
                                                  FROM asistencia a
                                                 WHERE a.__id_alumno = ?
                                                   AND a.__id_aula   = ? 
                                                   AND a.__nid_main  IN (SELECT nid_main FROM main WHERE nid_aula = ?)
                                                   AND a.estado      = '".ASISTENCIA_FALTA_JUSTIF."'
                                                   AND a.fecha_asistencia BETWEEN ca.fec_inicio AND (SELECT fec_fin + CAST('7 days' AS INTERVAL) 
                                                            									       FROM ciclo_academico 
                                                            									      WHERE NOW() BETWEEN fec_inicio AND fec_fin)))
                        	  FROM asistencia_config
                        	 WHERE id_asist_config = ".ID_ASISTENCIA_FALTA_JUSTIF.")SUMA
                          FROM ciclo_academico ca
                         WHERE NOW() BETWEEN ca.fec_inicio AND ca.fec_fin
                        	ORDER BY SUMA DESC LIMIT 1) - rango_limite ) diferencia
                    FROM asistencia_calificacion_config
                    ORDER BY diferencia LIMIT 1";
        $result = $this->db->query($sql, array($idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula, $idAlumno, $idAula, $idAula));
        return $result->row_array();
    }
      
    function getProfesores($idAula, $idSede, $idGrado, $year) {
        $sql = "SELECT * FROM notas.fun_get_docentes(?, ?, ?, ?)";
        $result = $this->db->query($sql, array($year, $idSede, $idGrado, $idAula));
        return $result->result_array();
    }
    
}