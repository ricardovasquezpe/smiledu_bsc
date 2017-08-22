<?php
class M_detalle_curso extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    function getMisCursos_Docente($idDocente) {
        $sql = "SELECT ca.desc_curso,
                        s.desc_sede,
                        n.desc_nivel,
                        g.desc_grado,
                        INITCAP(a.desc_aula) AS desc_aula,
                        a.year,
                        a.nid_aula,
                        a.capa_max,
                        m.nid_main,
                        ca.id_curso,
                        (SELECT COUNT(1)
                    	   FROM persona_x_aula pa
                    	  WHERE pa.__id_aula      = m.nid_aula
                    	    AND pa.flg_acti       = '".FLG_ACTIVO."'
                    	    AND pa.year_academico = a.year) AS cant_estu,
                        (SELECT COUNT(1)
                    	   FROM persona p
                    	  WHERE p.sexo = '".SEXO_FEMENINO."'
                            AND p.nid_persona IN (SELECT pa.__id_persona
                                                    FROM persona_x_aula pa
                                                   WHERE pa.__id_aula      = m.nid_aula
                                                     AND pa.flg_acti       = '".FLG_ACTIVO."'
                    	                             AND pa.year_academico = a.year) ) AS cant_mujeres,
                        (SELECT COUNT(1)
                    	   FROM persona p
                    	  WHERE p.sexo = '".SEXO_MASCULINO."'
                            AND p.nid_persona IN (SELECT pa.__id_persona
                                                    FROM persona_x_aula pa
                                                   WHERE pa.__id_aula      = m.nid_aula
                                                     AND pa.flg_acti       = '".FLG_ACTIVO."'
                    	                             AND pa.year_academico = a.year) ) AS cant_varones
                   FROM main  m,
                        grupo_x_docente gd,                             		
                        aula  a,
                        sede  s,
                        nivel n,
                        grado g,
                        notas.fun_get_cursos_area(null) ca
                  WHERE gd.__id_docente = ?
                    AND m.estado      = '".FLG_ACTIVO."'
                    AND a.year        = "._YEAR_."
                    AND gd.__id_main  = m.nid_main
                    AND gd.flg_activo IN (".FLG_DOCENTE_ASIGNADO.", ".FLG_DOCENTE_DESACTIVADO.")
                    AND m.nid_aula      = a.nid_aula
                    AND m.nid_curso     = ca.id_curso
                    AND a.nid_sede      = s.nid_sede
                    AND a.nid_nivel     = n.nid_nivel
                    AND a.nid_grado     = g.nid_grado
			GROUP BY ca.id_curso, 
			       ca.desc_curso, 
					s.desc_sede,
					n.desc_nivel,
					g.desc_grado,
					desc_aula,
					a.year,
					a.nid_aula,
					a.capa_max,
					m.nid_main,
					ca.id_curso ";
        $result = $this->db->query($sql, array($idDocente) );
        return $result->result_array();
    }
     
    function checkIfHayAsistenciaHoy($idMain) {
        $sql = "SELECT COUNT(1) AS cant_asist
                   FROM asistencia
                  WHERE __nid_main       = ?
                    AND fecha_asistencia = (SELECT now())::date";
        $result = $this->db->query($sql, array($idMain) );
        return $result->row()->cant_asist;
    }
     
    function insertarAsistenciasPorDefecto($idAula, $idMain) {
        $sql = "INSERT INTO asistencia
                    SELECT p.nid_persona,
                           pa.__id_aula,
                           (SELECT EXTRACT(YEAR FROM now())),
                           (SELECT now()::date),
                           '".ASISTENCIA_PRESENTE."',
                               NULL,
                               $idMain,
                               NULL
                               FROM persona    p,
                               persona_x_aula pa
                               WHERE pa.flg_acti   = '".FLG_ACTIVO."'
                       AND p.flg_acti    = '".FLG_ACTIVO."'
                       AND pa.__id_aula  = ?
                       AND p.nid_persona = pa.__id_persona";
        $result = $this->db->query($sql, array($idAula) );
        if($this->db->affected_rows() == 0) {
            throw new Exception('Error al registrar asistencia inicial');
        }
        return array("error" => EXIT_SUCCESS, "msj" => 'Se marc&oacute; la asistencia');
    }
     
    function puedeDictarHoyDiaEspecial($idMain) {
        $sql = "SELECT TRUE AS puede
                   FROM notas.main_detalle
                  WHERE _id_main    = ?
                    AND fecha_clase = (SELECT now()::date) ";
        $result = $this->db->query($sql, array($idMain) );
        if($result->num_rows() == 1) {
            return $result->row()->puede;
        }
        return false;
    }
     
    function getEstudiantesByCurso($idMain) {
        $sql = "SELECT p.nid_persona,
                	    INITCAP(p.ape_pate_pers) AS ape_pate_pers,
                	    INITCAP(p.ape_mate_pers) AS ape_mate_pers,
                	    INITCAP(p.nom_persona)   AS nom_persona,
                	    CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."',p.foto_persona)
                		     WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                		     ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                	    m.nid_aula,
                	    m.nid_main,
                		a.*
                   FROM persona        p,
                	    persona_x_aula pa,
                	    main           m,
                	    asistencia     a
                  WHERE m.nid_main         = ?
                    AND pa.flg_acti        = '".FLG_ACTIVO."'
                    AND pa.year_academico  = (SELECT year FROM aula WHERE nid_aula = m.nid_aula)
                    AND pa.__id_aula       = m.nid_aula
                    AND p.nid_persona      = pa.__id_persona
                    AND p.nid_persona      = a.__id_alumno
                    AND m.nid_main         = a.__nid_main
                    AND a.fecha_asistencia = (SELECT now()::date)
                 ORDER BY p.ape_pate_pers, p.ape_mate_pers, p.nom_persona";
        $result = $this->db->query($sql, array($idMain) );
        return $result->result_array();
    }
    
    function getEstudiantesByCursoAll($idMain) {
        $sql = "SELECT p.nid_persona,
                	    INITCAP(p.ape_pate_pers) AS ape_pate_pers,
                	    INITCAP(p.ape_mate_pers) AS ape_mate_pers,
                	    INITCAP(p.nom_persona)   AS nom_persona,
                	    CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."',p.foto_persona)
                		     WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                		     ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona
                   FROM persona        p,
                	    persona_x_aula pa,
                	    main           m
                  WHERE m.nid_main         = ?
                    AND pa.flg_acti        = '".FLG_ACTIVO."'
                    AND pa.year_academico  = (SELECT year FROM aula WHERE nid_aula = m.nid_aula)
                    AND pa.__id_aula       = m.nid_aula
                    AND p.nid_persona      = pa.__id_persona
                 ORDER BY p.ape_pate_pers, p.ape_mate_pers, p.nom_persona";
        $result = $this->db->query($sql, array($idMain) );
        return $result->result_array();
    }
     
    function getDataAulaByMain($idMain) {
        $sql = "SELECT nid_aula,
                        year,
                        nid_grado,
                        (SELECT nid_curso FROM main WHERE nid_main = ?) AS nid_curso
                   FROM aula
                  WHERE nid_aula = (SELECT nid_aula
                                      FROM main
                                     WHERE nid_main = ?)";
        $result = $this->db->query($sql, array($idMain, $idMain) );
        return $result->row_array();
    }
     
    function insertarAsistencia($arryInsert) {
        $this->db->insert('asistencia', $arryInsert);
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al registrar la asistencia');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_INS);
    }
     
    function actualizarAsistencia($arryUpdate, $idAlumno, $idAula, $fecha, $dMain) {
        $this->db->where('__id_alumno', $idAlumno);
        $this->db->where('__id_aula'  , $idAula);
        $this->db->where('fecha_asistencia', $fecha);
        $this->db->where('__nid_main', $dMain);
         
        $this->db->update('asistencia', $arryUpdate);
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al actualizar la asistencia');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);
    }
     
    function checkIfAsistenciaExiste($idAlumno, $idAula, $fecha, $dMain) {
        $sql = "SELECT true AS rpta
                   FROM asistencia
                  WHERE __id_alumno      = ?
                    AND __id_aula        = ?
                    AND fecha_asistencia = ?
                    AND __nid_main       = ?";
        $result = $this->db->query($sql, array($idAlumno, $idAula, $fecha, $dMain) );
        if($result->num_rows() == 0) {
            return false;
        }
        return $result->row()->rpta;
    }
     
    function getListaEventosAsistencia($idMain, $idAula, $year) {
        $sql = "SELECT __id_aula,
                        __year_academico,
                        __nid_main,
                        TO_CHAR(fecha_asistencia, 'DD/MM/YYYY') AS fec_normal,
                        CAST(EXTRACT(epoch FROM fecha_asistencia at time zone 'utc') AS INTEGER) AS fecha_asistencia
                   FROM asistencia
                  WHERE __id_aula        = ?
                    AND __year_academico = ?
                    AND __nid_main       = ?
                 GROUP BY __id_aula, __year_academico, __nid_main, fecha_asistencia";
        $result = $this->db->query($sql, array($idAula, $year, $idMain) );
        return $result->result_array();
    }
     
    function getAsistenciaByFecha($fecha, $idAula, $idMain) {
        $sql = "SELECT p.nid_persona,
                        INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers, ' ',p.nom_persona)) AS nombre_estudiante,
                        CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."',p.foto_persona)
                             WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                             ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                        a.estado,
                        a.flg_justificado
                   FROM persona_x_aula pa,
                        persona        p LEFT JOIN asistencia a ON (    p.nid_persona   = a.__id_alumno
                                    			                    AND a.fecha_asistencia = ?
                                                                    AND a.__id_aula        = ?
                                                                    AND a.__nid_main       = ?)
                  WHERE pa.__id_aula      = ?
                    AND pa.flg_acti       = '".FLG_ACTIVO."'
                    AND pa.year_academico = (SELECT year FROM aula WHERE nid_aula = pa.__id_aula)
                    AND p.nid_persona     = pa.__id_persona
                 ORDER BY p.ape_pate_pers, p.ape_mate_pers, p.nom_persona";
        $result = $this->db->query($sql, array($fecha, $idAula, $idMain, $idAula) );
        return $result->result_array();
    }
    
    function getPieForAsistByMain($idMain, $fecIni, $fecFin) {
        $sql = "SELECT b.*
                   FROM (SELECT *
                           FROM (SELECT COUNT(1) * (SELECT COUNT(1)
                            					      FROM (SELECT TO_CHAR(a.fecha_asistencia, 'DD/MM/YYYY') fecha_asistencia
                            						          FROM asistencia a
                            						         WHERE a.__nid_main       = ?
                            						           AND a.fecha_asistencia BETWEEN ? AND ?
                            						        GROUP BY a.fecha_asistencia
                            						        ORDER BY a.fecha_asistencia ) AS t) AS cnt_estu
                            	   FROM persona_x_aula pa
                            	  WHERE pa.__id_aula = (SELECT m.nid_aula FROM main m WHERE nid_main = ?)
                            	    AND pa.flg_acti  = '".FLG_ACTIVO."') AS cnt_estu LEFT JOIN (SELECT a.estado,
                                                    								      COUNT(1) cant_asist
                                                    								 FROM asistencia a
                                                    							    WHERE a.__nid_main = ?
                                                    								  AND a.fecha_asistencia BETWEEN ? AND ?
                                                    							    GROUP BY a.estado) AS estad ON (1 = 1)
                         ORDER BY estado
                  ) AS b";
        $result = $this->db->query($sql, array($idMain, $fecIni, $fecFin, $idMain, $idMain, $fecIni, $fecFin) );
        return $result->result_array();
    }
     
    function getAsistLineaGraph($idMain, $fecIni, $fecFin) {
        $sql = " SELECT tab.estado,
                    	 STRING_AGG(tab.cant_asist, ',') AS cant_asist
                    FROM ( SELECT statuses.estado_asist AS estado,
                                  COALESCE(states.cant_asist, '0') AS cant_asist
                             FROM (SELECT *
                    			     FROM (SELECT estado_asist
                    				         FROM ( VALUES ('".ASISTENCIA_FALTA."'), ('".ASISTENCIA_FALTA_JUSTIF."'), ('".ASISTENCIA_PRESENTE."'), ('".ASISTENCIA_TARDE."'), ('".ASISTENCIA_TARDE_JUSTIF."') ) s(estado_asist) ) AS t1,
                    			          (SELECT *
                                             FROM (SELECT TO_CHAR(fec::date, 'DD/MM/YYYY') AS fech
                                            	     FROM generate_series(?, ?, '1 day'::interval) AS fec
                                            	    WHERE EXTRACT(DOW FROM fec) NOT IN (0,6)
                                            	    UNION ALL
                                            	    SELECT TO_CHAR(fecha_clase::date, 'DD/MM/YYYY')
                                            	      FROM notas.main_detalle
                                                     WHERE _id_main = ?
                                            	       AND fecha_clase BETWEEN ? AND ? ) tab
                                        ORDER BY tab.fech ) AS t2 ) AS statuses
                                          LEFT JOIN ( SELECT a.estado,
                            				                 TO_CHAR(a.fecha_asistencia, 'DD/MM/YYYY') fecha_asistencia,
                            				                 COUNT(1)::text cant_asist
                                        				FROM asistencia a
                                        			   WHERE a.__nid_main       = ?
                                        				 AND a.fecha_asistencia BETWEEN ? AND ?
                                        			   GROUP BY a.estado, a.fecha_asistencia
                                        			   ORDER BY a.fecha_asistencia, a.estado ) states ON (statuses.estado_asist = states.estado AND statuses.fech = states.fecha_asistencia)
                    	 ) AS tab
                      GROUP BY tab.estado";
        $result = $this->db->query($sql, array($fecIni, $fecFin, $idMain, $fecIni, $fecFin, $idMain, $fecIni, $fecFin) );
        return $result->result_array();
    }
     
    function getCategoriasGrafLinea($idMain, $fecIni, $fecFin) {
        $sql = "SELECT *
                   FROM (SELECT TO_CHAR(fec::date, 'DD/MM/YYYY') AS fecha_asistencia
                    	   FROM generate_series(?, ?, '1 day'::interval) AS fec
                    	  WHERE EXTRACT(DOW FROM fec) NOT IN (0,6)
                    	 UNION ALL
                    	 SELECT TO_CHAR(fecha_clase::date, 'DD/MM/YYYY')
                    	   FROM notas.main_detalle
                          WHERE _id_main = ?
                    	    AND fecha_clase BETWEEN ? AND ? ) tab
                ORDER BY tab.fecha_asistencia";
        $result = $this->db->query($sql, array($fecIni, $fecFin, $idMain, $fecIni, $fecFin) );
        return $result->result_array();
    }
     
    //////////////////////////////// GRAFICO BARRAS POR SEXO /////////////////////////////////
     
    function getDataForGrafBarrasBySexo($nidMain, $fecIni, $fecFin) {
        $varones = SEXO_MASCULINO;
        $mujeres = SEXO_FEMENINO;
        $sql = "SELECT 'VARONES' AS sexo,
                       '#03A9F4' AS color,
                       (SELECT COUNT(1)
                          FROM asistencia a
                         WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                           AND a.__nid_main = $nidMain
                           AND a.estado = '".ASISTENCIA_FALTA."'
                           AND $varones = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )) AS falta,
                       (SELECT COUNT(1)
                          FROM asistencia a
                         WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                           AND a.__nid_main = $nidMain
                           AND a.estado = '".ASISTENCIA_FALTA_JUSTIF."'
                           AND $varones = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )) AS falta_just,
                       (SELECT COUNT(1)
                          FROM asistencia a
                         WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                           AND a.__nid_main = $nidMain
                           AND a.estado = '".ASISTENCIA_PRESENTE."'
                           AND $varones = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )) AS presente,
                       (SELECT COUNT(1)
                          FROM asistencia a
                         WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                           AND a.__nid_main = $nidMain
                           AND a.estado = '".ASISTENCIA_TARDE."'
                           AND $varones = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )) AS tardanza,
                       (SELECT COUNT(1)
                          FROM asistencia a
                         WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                           AND a.__nid_main = $nidMain
                           AND a.estado = '".ASISTENCIA_TARDE_JUSTIF."'
                           AND $varones = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )) AS tardanza_justif
                 UNION ALL
                 SELECT 'MUJERES',
                        '#F48FB1',
                        (SELECT COUNT(1)
                           FROM asistencia a
                          WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                            AND a.__nid_main = $nidMain
                            AND a.estado = '".ASISTENCIA_FALTA."'
                            AND $mujeres = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )),
                        (SELECT COUNT(1)
                           FROM asistencia a
                          WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                            AND a.__nid_main = $nidMain
                            AND a.estado = '".ASISTENCIA_FALTA_JUSTIF."'
                            AND $mujeres = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )),
                        (SELECT COUNT(1)
                           FROM asistencia a
                          WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                            AND a.__nid_main = $nidMain
                            AND a.estado = '".ASISTENCIA_PRESENTE."'
                            AND $mujeres = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )),
                        (SELECT COUNT(1)
                           FROM asistencia a
                          WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                            AND a.__nid_main = $nidMain
                            AND a.estado = '".ASISTENCIA_TARDE."'
                            AND $mujeres = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno )),
                        (SELECT COUNT(1)
                           FROM asistencia a
                          WHERE a.fecha_asistencia BETWEEN '$fecIni' AND '$fecFin'
                            AND a.__nid_main = $nidMain
                            AND a.estado = '".ASISTENCIA_TARDE_JUSTIF."'
                            AND $mujeres = (SELECT sexo::integer FROM persona WHERE nid_persona = a.__id_alumno ))";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
     
    /////////////////////////////////////// HEAT MAP ASISTENCIA ////////////////////////////////////////
     
    function getFechasHeatMapAsistencia($idMain) {
        $sql = "SELECT * FROM get_fechas_graf_heat_map(?) AS fecha";
        $result = $this->db->query($sql, array($idMain));
        return $result->result_array();
    }
     
    function queryAuxHeatMap($nidMain, $estado, $fecha1, $fecha2, $fecha3, $fecha4, $fecha5) {
        return "SELECT '$estado' estado,
                (SELECT COUNT(1)
                   FROM asistencia a
                  WHERE a.fecha_asistencia = '$fecha1'
                    AND a.__nid_main       = $nidMain
                    AND a.estado = '$estado') AS falta_dia1,
                (SELECT COUNT(1)
                   FROM asistencia a
                  WHERE a.fecha_asistencia = '$fecha2'
                    AND a.__nid_main       = $nidMain
                    AND a.estado = '$estado') AS falta_dia2,
                (SELECT COUNT(1)
                   FROM asistencia a
                  WHERE a.fecha_asistencia = '$fecha3'
                    AND a.__nid_main       = $nidMain
                    AND a.estado = '$estado') AS falta_dia3,
                (SELECT COUNT(1)
                   FROM asistencia a
                  WHERE a.fecha_asistencia = '$fecha4'
                    AND a.__nid_main       = $nidMain
                    AND a.estado = '$estado') AS falta_dia4,
                (SELECT COUNT(1)
                   FROM asistencia a
                  WHERE a.fecha_asistencia = '$fecha5'
                    AND a.__nid_main       = $nidMain
                    AND a.estado = '$estado') AS falta_dia5 ";
    }
     
    function getDataHeatMapAsistencia($nidMain, $fechas) {
        $sql = $this->queryAuxHeatMap($nidMain, ASISTENCIA_FALTA, $fechas[0]['fecha'], $fechas[1]['fecha'], $fechas[2]['fecha'], $fechas[3]['fecha'], $fechas[4]['fecha']);
        $sql .= ' UNION ALL '.$this->queryAuxHeatMap($nidMain, ASISTENCIA_FALTA_JUSTIF  , $fechas[0]['fecha'], $fechas[1]['fecha'], $fechas[2]['fecha'], $fechas[3]['fecha'], $fechas[4]['fecha']);
        $sql .= ' UNION ALL '.$this->queryAuxHeatMap($nidMain, ASISTENCIA_PRESENTE      , $fechas[0]['fecha'], $fechas[1]['fecha'], $fechas[2]['fecha'], $fechas[3]['fecha'], $fechas[4]['fecha']);
        $sql .= ' UNION ALL '.$this->queryAuxHeatMap($nidMain, ASISTENCIA_TARDE         , $fechas[0]['fecha'], $fechas[1]['fecha'], $fechas[2]['fecha'], $fechas[3]['fecha'], $fechas[4]['fecha']);
        $sql .= ' UNION ALL '.$this->queryAuxHeatMap($nidMain, ASISTENCIA_TARDE_JUSTIF  , $fechas[0]['fecha'], $fechas[1]['fecha'], $fechas[2]['fecha'], $fechas[3]['fecha'], $fechas[4]['fecha']);
        $result = $this->db->query($sql);
        return $result->result_array();
    }
     
    function getAlumno($id_alumno) {
        $sql = "SELECT UPPER(p.nom_persona)   AS nom_persona,
	                   UPPER(p.ape_pate_pers) AS ape_pate_pers,
	                   UPPER(p.ape_mate_pers) AS ape_mate_pers,
	                   TO_CHAR(p.fec_naci, 'DD/MM/YYYY') fec_naci,
	                   p.nro_documento,
	                   p.telf_pers,
	                   p.correo_pers,
                       (SELECT desc_combo FROM combo_tipo WHERE grupo = ".COMBO_SEXO." AND valor = p.sexo::CHARACTER VARYING) AS sexo,
	                   d.cod_familia,
	                   d.codigo_ugel,
	                   d.cod_banco,
	                   d.total_hermano,
	                   d.nro_hermano,
	                   d.colegio_procedencia,
	                   d.ubigeo,
	                   (SELECT desc_combo FROM combo_tipo WHERE grupo = ".COMBO_RELIGION." AND valor = d.religion::CHARACTER VARYING) AS religion,
	                   d.observacion,
	                   d.year_ingreso,
	                   d.id_grado_ingreso,
	                   d.id_nivel_ingreso,
	                   d.id_sede_ingreso,
	                   (SELECT desc_combo FROM combo_tipo WHERE grupo = ".COMBO_ESTADO_CIVIL." AND valor = p.estado_civil::CHARACTER VARYING) AS estado_civil,
	                   (SELECT desc_pais FROM sima.paises WHERE id_pais = d.pais) AS pais,
	                   (SELECT CONCAT(INITCAP(LOWER(departamento)), ' / ', INITCAP(LOWER(provincia)),' / ', INITCAP(LOWER(distrito))) ubicacion
                          FROM sima.ubigeo
                         WHERE cod_ubigeo = d.ubigeo),
	                   d.estado,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."',p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona
	               FROM persona p,
                        sima.detalle_alumno d
	              WHERE p.nid_persona = ?
                    AND p.nid_persona = d.nid_persona";
        $result = $this->db->query($sql, array($id_alumno) );
        return $result->row_array();
    }
     
    function getHistorialEstudiante($idEstudiante) {
        $sql = "SELECT CONCAT(s.desc_sede,' ',g.abvr,' ',n.abvr) AS sede,
                       INITCAP(LOWER(a.desc_aula)) AS desc_aula,
                       pa.promedio_final,
                       pa.year_academico
                  FROM persona_x_aula pa,
                       aula           a,
                       sede           s,
                       nivel          n,
                       grado          g
                 WHERE pa.__id_persona = ?
                   AND pa.__id_aula    = a.nid_aula
                   AND a.nid_sede      = s.nid_sede
                   AND a.nid_nivel     = n.nid_nivel
                   AND a.nid_grado     = g.nid_grado
                ORDER BY pa.year_academico DESC";
        $result = $this->db->query($sql, array($idEstudiante) );
        return $result->result_array();
    }
     
    function getFamiliaresByCodFam($codFam) {
        $sql = "SELECT INITCAP( LOWER(CONCAT(split_part( nombres, ' ' , 1 ),' ',ape_paterno)) ) AS nombre_completo,
	                   f.id_familiar,
	                   CASE WHEN nro_doc_identidad <> '' THEN nro_doc_identidad ELSE '-' END AS nro_doc_identidad,
	                   INITCAP((SELECT c.desc_combo FROM combo_tipo c WHERE c.valor = f.tipo_doc_identidad::CHARACTER VARYING AND c.grupo = ".COMBO_TIPO_DOC." )) AS tipo_doc,
	                   CASE WHEN email1 <> '' THEN email1 ELSE '-' END AS email,
	                   CASE WHEN ff.flg_apoderado = '1' THEN 'SÍ'
	                        WHEN ff.flg_apoderado = '2' THEN 'NO'
	                        ELSE '-' END AS apoderado,
	                   CASE WHEN ff.flg_resp_economico = '1' THEN 'SÍ'
	                        WHEN ff.flg_resp_economico = '2' THEN 'NO'
	                        ELSE '-' END AS resp_economico,
	                   (SELECT c.desc_combo FROM combo_tipo c WHERE c.valor = ff.parentesco::CHARACTER VARYING AND c.grupo = ".COMBO_PARENTEZCO." ) AS parentesco
				  FROM familiar f,
	                   sima.familiar_x_familia ff
				 WHERE ff.id_familiar  = f.id_familiar
	               AND ff.cod_familiar = ?";
        $result = $this->db->query($sql, array($codFam));
        return $result->result_array();
    }
     
    function getCantPresenteByEstu($idEstudiante, $idMain, $yearAcad) {
        $sql = "SELECT CAST(EXTRACT(epoch FROM a.fecha_asistencia/* at time zone 'utc'*/) AS INTEGER) AS fecha_asistencia,
                       ( SELECT COUNT(1)
                           FROM asistencia aa
                          WHERE aa.estado      = '".ASISTENCIA_PRESENTE."'
                            AND aa.fecha_asistencia BETWEEN (SELECT MIN(a2.fecha_asistencia)
                                                               FROM asistencia a2
                                    					      WHERE a2.__id_alumno      = a.__id_alumno
                                    					        AND a2.__nid_main       = a.__nid_main
                                    					        AND a2.__year_academico = a.__year_academico ) AND a.fecha_asistencia
                            AND aa.__id_alumno      = a.__id_alumno
                            AND aa.__nid_main       = a.__nid_main
                            AND aa.__year_academico = a.__year_academico ) AS cnt
                  FROM asistencia a
                 WHERE a.__id_alumno      = ?
                   AND a.__nid_main       = ?
                   AND a.__year_academico = ?
                ORDER BY a.fecha_asistencia";
        $result = $this->db->query($sql, array($idEstudiante, $idMain, $yearAcad));
        return $result->result_array();
    }
     
    function getDataRadar($idEstudiante, $idMain) {
        $sql = " SELECT t1.estado_asist,
                        COALESCE(states.count, 0) AS cant
                   FROM (SELECT estado_asist
                		   FROM ( VALUES ('".ASISTENCIA_PRESENTE."'), ('".ASISTENCIA_TARDE_JUSTIF."'), ('".ASISTENCIA_FALTA_JUSTIF."'), ('".ASISTENCIA_FALTA."'), ('".ASISTENCIA_TARDE."') ) s(estado_asist) ) AS t1
                             LEFT JOIN (   SELECT a.estado,
                        		                  COUNT(1)
                        			         FROM asistencia a
                        			        WHERE a.__nid_main  = ?
                        			          AND a.__id_alumno = ?
                        			       GROUP BY a.estado ) states ON (t1.estado_asist = states.estado)";
        $result = $this->db->query($sql, array($idMain, $idEstudiante));
        return $result->result_array();
    }
     
    function getListadoEstuCantAsistencias($idMain) {
        $sql = "SELECT * FROM (
                    SELECT INITCAP(LOWER(CONCAT(split_part( p.nom_persona, ' ' , 1 ) ,' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers, 1, 1),'.'))) AS estudiante,
                           CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."', p.foto_persona)
                                WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                                    p.nid_persona,
                                    (SELECT COUNT(1) FROM asistencia a WHERE a.__nid_main = $idMain AND a.__id_alumno = p.nid_persona AND a.estado = '".ASISTENCIA_PRESENTE."' )     cant_temprano,
                                    (SELECT COUNT(1) FROM asistencia a WHERE a.__nid_main = $idMain AND a.__id_alumno = p.nid_persona AND a.estado = '".ASISTENCIA_FALTA."' )        cant_falta,
                                    (SELECT COUNT(1) FROM asistencia a WHERE a.__nid_main = $idMain AND a.__id_alumno = p.nid_persona AND a.estado = '".ASISTENCIA_TARDE."' )        cant_tarde,
                                    (SELECT COUNT(1) FROM asistencia a WHERE a.__nid_main = $idMain AND a.__id_alumno = p.nid_persona AND a.estado = '".ASISTENCIA_TARDE_JUSTIF."' ) cant_tarde_justif,
                                    (SELECT COUNT(1) FROM asistencia a WHERE a.__nid_main = $idMain AND a.__id_alumno = p.nid_persona AND a.estado = '".ASISTENCIA_FALTA_JUSTIF."' ) cant_falta_justif
                                    FROM persona        p,
                                    persona_x_aula pa
                                    WHERE p.nid_persona = pa.__id_persona
                                    AND pa.__id_aula  = (SELECT nid_aula FROM main WHERE nid_main = $idMain)
                                    AND pa.flg_acti   = '".FLG_ACTIVO."'
                       AND p.flg_acti    = '".FLG_ACTIVO."'
                 ) AS tot
                    ORDER BY tot.cant_temprano DESC,
                             tot.cant_falta ASC,
                             tot.cant_tarde ASC,
                             tot.cant_tarde_justif ASC,
                             tot.cant_falta_justif ASC";
        $result = $this->db->query($sql, array($idMain));
        return $result->result_array();
    }
     
    function getCursosByEstudiante($idGrado, $yearAcad, $idEstudiante) {
        $sql = "SELECT INITCAP(cursos.desc_curso) AS desc_curso,
                       (SELECT INITCAP(CONCAT(SPLIT_PART( p.nom_persona, ' ', 1),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' ) )
                          FROM persona p
                         WHERE p.nid_persona = m.nid_persona) AS docente_nombres,
                       (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."', p.foto_persona)
                                    WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                    ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END
                          FROM persona p
                         WHERE p.nid_persona = m.nid_persona) AS foto_docente,
                        m.nid_persona
                  FROM (SELECT ce._id_curso_equiv AS id_curso,
                    	       ceq.desc_curso_equiv AS desc_curso
                    	  FROM curso_equivalencia ce,
                    	       curso_equivalente  ceq
                    	 WHERE ce._id_grado       = ?
                    	   AND ce._year_acad      = ?
                    	   AND ce._id_curso_equiv = ceq.id_curso_equiv
                    	UNION ALL
                    	SELECT cug._id_curso_ugel,
                    	       c.desc_curso
                    	  FROM curso_ugel_x_grado cug,
                    	       cursos             c
                    	 WHERE cug._id_grado      = ?
                    	   AND cug.year_acad      = ?
                    	   AND c.id_curso         = cug._id_curso_ugel
                    	   AND cug._id_curso_ugel NOT IN (SELECT _id_curso_ugel
                                                            FROM curso_equivalencia
                                                		   WHERE _id_grado  = cug._id_grado
                                                			 AND _year_acad = cug.year_acad
                                                		   GROUP BY _id_curso_ugel) ) AS cursos LEFT JOIN main m ON (cursos.id_curso = m.nid_curso
                                                                                                                      AND m.nid_aula = (SELECT pa.__id_aula
                                                                    															          FROM persona_x_aula pa
                                                                    															         WHERE pa.__id_persona   = ?
                                                                    															           AND pa.year_academico = ?
                                                                    															           AND pa.flg_acti       = '".FLG_ACTIVO."')) ";
        $result = $this->db->query($sql, array($idGrado, $yearAcad, $idGrado, $yearAcad, $idEstudiante, $yearAcad));
        return $result->result_array();
    }
     
    function getInstrumentosBusqueda($busqueda) {
        $sql = "SELECT i.id_instrumento,
                       i.nombre_instrumento,
                       (SELECT CONCAT(INITCAP(SPLIT_PART(p.nom_persona, ' ', 1)),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' )
                          FROM persona p
                         WHERE p.nid_persona = i.id_creador )                   AS autor,
                       CONCAT(i.nro_usos,' / ',i.nro_visitas,' / ',i.nro_likes) AS rank
                  FROM instru.instrumento i
                 WHERE UPPER(i.nombre_instrumento) LIKE UPPER(?)";
        $result = $this->db->query($sql, array('%'.$busqueda.'%'));
        return $result->result_array();
    }
     
    function asignarInstrumento($arryInsert) {
        $this->db->trans_begin();
        $this->db->insert('notas.matriz_x_instrumento', $arryInsert);
        if($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            throw new Exception('No se puedo asignar el instrumento');
        }
        $nroUsosInstru = $this->m_utils->getById('instru.instrumento', 'nro_usos', 'id_instrumento', $arryInsert['_id_instrumento']);
        $nroUsosInstru = ($nroUsosInstru == null) ? 0 : $nroUsosInstru;
        $this->db->where('id_instrumento', $arryInsert['_id_instrumento']);
        $this->db->update('instru.instrumento', array("nro_usos" => ($nroUsosInstru + 1) ));
        if($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            throw new Exception('No se pudo actualizar el n&uacute;mero de usos');
        }
        $this->db->trans_commit();
        return array("error" => EXIT_SUCCESS, "msj" => 'Se asign&oacute; el instrumento');
    }
     
    function getNextCorrelativoInstrumento($idGrado    , $idCurso    , $idCompetencia,
        $idCapacidad, $idIndicador, $idMain       , $idInstrumento) {
        $sql = "SELECT correlativo + 1 correlativo
                  FROM notas.matriz_x_instrumento
                 WHERE _id_grado       = ?
                   AND _id_curso       = ?
                   AND _year_acad      = "._YEAR_."
                   AND _id_competencia = ?
                   AND _id_capacidad   = ?
                   AND _id_indicador   = ?
                   AND _id_main        = ?
                   AND _id_instrumento = ?";
        $result = $this->db->query($sql, array($idGrado    , $idCurso    , $idCompetencia,
            $idCapacidad, $idIndicador, $idMain       , $idInstrumento));
        if($result->num_rows() == 1) {
            return $result->row()->correlativo;
        }
        return 1;
    }
     
    function getInstrumentoToEvaluar($idEstudiante, $idMain, $idInstrumento, $idIndicador, $correlativo) {
        $sql = "SELECT tab.*,
                       (SELECT o.desc_opcion
                          FROM instru.opcion o,
                               instru.instrumento_x_opcion io
                         WHERE io._id_opcion      = o.id_opcion
                           AND tab._id_instrumento = io._id_instrumento
                           AND o.id_opcion        = tab.id_opcion
                           AND io.valor           = tab.valor)
                   FROM ( SELECT ia._id_instrumento,
                                 ia._id_aspecto,
                                 a.desc_aspecto,
                                 ia.orden,
                                 ia.flg_obligatorio,
                                 (SELECT (elem->>'valor')::numeric
                                    FROM json_array_elements(ie.result_json_instru->'resultados') AS elem
                                   WHERE (elem->>'_id_aspecto')::integer = ia._id_aspecto) AS valor,
                                 (SELECT (elem->>'_id_opcion')::numeric
                                    FROM json_array_elements(ie.result_json_instru->'resultados') AS elem
                                   WHERE (elem->>'_id_aspecto')::integer = ia._id_aspecto) AS id_opcion
                            FROM instru.instrumento_x_aspecto ia LEFT JOIN notas.instrumento_x_estudiante ie
                                                                    ON (    ia._id_instrumento = ie._id_instrumento
                                                                        AND ie._id_estudiante  = ?
                                                                        AND ie._id_main        = ?
                                                                        AND ie._id_indicador   = ?
                                                                        AND ie.correlativo     = ?
                                                                        AND ia._id_aspecto IN (SELECT (json_array_elements(ie.result_json_instru->'resultados')->>'_id_aspecto')::integer))
                                 ,instru.aspecto a
                           WHERE ia._id_instrumento = ?
                             AND ia._id_aspecto     = a.id_aspecto
                          ORDER BY ia.orden ) AS tab";
        $result = $this->db->query($sql, array($idEstudiante, $idMain, $idIndicador, $correlativo, $idInstrumento));
        return $result->result_array();
    }
     
    function getOpcioneByInstrumento($idInstrumento) {
        $sql = "SELECT o.id_opcion,
                       o.desc_opcion,
                       o.abvr_opcion,
                       io.valor
                  FROM instru.instrumento_x_opcion io,
                       instru.opcion               o
                 WHERE io._id_instrumento = ?
                   AND io._id_opcion      = o.id_opcion
                ORDER BY orden";
        $result = $this->db->query($sql, array($idInstrumento));
        return $result->result_array();
    }
     
    function getRptasJSONByEstu($idEstudiante, $idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $sql = "SELECT json_array_elements(ie.result_json_instru->'resultados')
                  FROM notas.instrumento_x_estudiante ie
                 WHERE ie._id_estudiante  = ?
                   AND ie._id_main        = ?
                   AND ie._id_instrumento = $idInstrumento
                   AND ie.correlativo     = $correlativo
                   AND ie._id_competencia = $idCompetencia
                   AND ie._id_capacidad   = $idCapacidad
                   AND ie._id_indicador   = $idIndicador ";
        $result = $this->db->query($sql, array($idEstudiante, $idMain));
        return $result->result_array();
    }
     
    function getRptasJSON_AS_String_ByEstu($idEstudiante, $idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $sql = "SELECT ie.result_json_instru::TEXT as texto_json
                  FROM notas.instrumento_x_estudiante ie
                 WHERE ie._id_estudiante  = ?
                   AND ie._id_main        = ?
                   AND ie._id_instrumento = $idInstrumento
                   AND ie.correlativo     = $correlativo
                   AND ie._id_competencia = $idCompetencia
                   AND ie._id_capacidad   = $idCapacidad
                   AND ie._id_indicador   = $idIndicador";
        $result = $this->db->query($sql, array($idEstudiante, $idMain));
        if($result->num_rows() == 1) {
            return $result->row()->texto_json;
        }
        return null;
    }
     
    function updateJSON_StringRptas($idEstudiante, $idMain, $jsonString, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $this->db->where('_id_estudiante', $idEstudiante);
        $this->db->where('_id_main', $idMain);
        $this->db->where('_id_instrumento', $idInstrumento);
        $this->db->where('correlativo', $correlativo);
        $this->db->where('_id_competencia', $idCompetencia);
        $this->db->where('_id_capacidad', $idCapacidad);
        $this->db->where('_id_indicador', $idIndicador);
        $this->db->update('notas.instrumento_x_estudiante', array('result_json_instru' => $jsonString));
        if($this->db->affected_rows() != 1) {
            throw new Exception('Hubo un error al grabar la respuesta');
        }
    }
     
    function updateNota_Rptas($idEstudiante, $idMain, $nota, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $this->db->where('_id_estudiante', $idEstudiante);
        $this->db->where('_id_main', $idMain);
        $this->db->where('_id_instrumento', $idInstrumento);
        $this->db->where('correlativo', $correlativo);
        $this->db->where('_id_competencia', $idCompetencia);
        $this->db->where('_id_capacidad', $idCapacidad);
        $this->db->where('_id_indicador', $idIndicador);
        $this->db->update('notas.instrumento_x_estudiante', array('nota_numerica' => $nota));
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al calcular la nota');
        }
    }
     
    function replaceJSON_StringRpta($idEstudiante, $idMain, $idAspecto, $oldVal, $newVal, $idOpcionOld, $idOpcionNew, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $sql = "UPDATE notas.instrumento_x_estudiante
                   SET result_json_instru = (regexp_replace(result_json_instru::text,
                                                            '\"_id_aspecto\":$idAspecto, \"valor\":\"$oldVal\", \"_id_opcion\" : $idOpcionOld ',
                                                            '\"_id_aspecto\":$idAspecto, \"valor\":\"$newVal\", \"_id_opcion\" : $idOpcionNew '))::json
                 WHERE _id_estudiante  = $idEstudiante
                   AND _id_main        = $idMain
                   AND _id_instrumento = $idInstrumento
                   AND correlativo     = $correlativo
                   AND _id_competencia = $idCompetencia
                   AND _id_capacidad   = $idCapacidad
                   AND _id_indicador   = $idIndicador ";
        $result = $this->db->query($sql);
        if($result != 1) {
            throw new Exception('Hubo un error al grabar');
        }
    }
     
    function calcularNotaInstrumento($idEstudiante, $idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $sql = "SELECT ROUND((main.nota * 20 / ( tab2.valor * tab3.cnt_aspectos ) ), 4) AS nota
                  FROM (SELECT SUM(tab.valor) AS nota
                          FROM (SELECT (json_array_elements(ie.result_json_instru->'resultados')->>'valor')::numeric AS valor
                                  FROM notas.instrumento_x_estudiante ie
                                 WHERE ie._id_estudiante  = ?
                                   AND ie._id_main        = ?
                                   AND ie._id_instrumento = $idInstrumento
                                   AND ie.correlativo     = $correlativo
                                   AND ie._id_competencia = $idCompetencia
                                   AND ie._id_capacidad   = $idCapacidad
                                   AND ie._id_indicador   = $idIndicador ) AS tab ) AS main,
                (SELECT MAX(valor) AS valor
                   FROM instru.instrumento_x_opcion
                  WHERE _id_instrumento = $idInstrumento ) AS tab2,
                (SELECT COUNT(1) cnt_aspectos
                   FROM instru.instrumento_x_aspecto
                  WHERE _id_instrumento = $idInstrumento)  AS tab3";
        $result = $this->db->query($sql, array($idEstudiante, $idMain));
        if($result->num_rows() == 1) {
            return $result->row()->nota;
        }
        return 0;
    }
     
    function getPromedioValorByInstruByEstu($idEstudiante, $idMain, $idInstrumento, $correlativo, $idCompetencia, $idCapacidad, $idIndicador) {
        $sql = "SELECT tab.sum_vals / tab2.cnt_aspectos AS promedio
                  FROM (SELECT SUM(tab.valor) AS sum_vals
                          FROM (SELECT (json_array_elements(ie.result_json_instru->'resultados')->>'valor')::numeric AS valor
                                  FROM notas.instrumento_x_estudiante ie
                                 WHERE ie._id_estudiante  = ?
                                   AND ie._id_main        = ?
                                   AND ie._id_instrumento = $idInstrumento
                                   AND ie.correlativo     = $correlativo
                                   AND ie._id_competencia = $idCompetencia
                                   AND ie._id_capacidad   = $idCapacidad
                                   AND ie._id_indicador   = $idIndicador ) AS tab ) AS tab,
                (SELECT COUNT(1) cnt_aspectos
                   FROM instru.instrumento_x_aspecto
                  WHERE _id_instrumento = $idInstrumento)  AS tab2 ";
        $result = $this->db->query($sql, array($idEstudiante, $idMain));
        if($result->num_rows() == 1 && $result->row()->promedio != null) {
            $sql = "SELECT o.abvr_opcion,
                           o.desc_opcion,
                           io.valor,
                           (valor - ?)^2 AS d
                      FROM instru.instrumento_x_opcion io,
                           instru.opcion               o
                     WHERE io._id_instrumento = ?
                       AND io._id_opcion      = o.id_opcion
                    ORDER BY d LIMIT 1";
            $result = $this->db->query($sql, array($result->row()->promedio, $idInstrumento));
            if($result->num_rows() == 1) {
                return $result->row()->abvr_opcion;
            }
         }
         return null;
    }
     
    function checkIfExisteEvaluacionEstu($idMain, $idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo, $idEstudiante) {
        $sql = "SELECT COUNT(1) AS exist_eva
                  FROM notas.instrumento_x_estudiante
                 WHERE _id_main        = ?
                   AND _id_instrumento = ?
                   AND _id_competencia = ?
                   AND _id_capacidad   = ?
                   AND _id_indicador   = ?
                   AND correlativo     = ?
                   AND _id_estudiante  = ?";
         $result = $this->db->query($sql, array($idMain, $idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo, $idEstudiante));
         if($result->num_rows() == 1) {
             return $result->row()->exist_eva;
         }
         return 0;
    }
              
    function registrarEvaluacionEstudianteInstru($arryInsert) {
        $this->db->insert('notas.instrumento_x_estudiante', $arryInsert);
        if($this->db->affected_rows() != 1) {
            throw new Exception('Hubo un error al grabar');
        }
    }
              
    function getNotaByEstuInstru($idMain, $idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo, $idEstudiante) {
        $sql = " SELECT ROUND(nota_numerica, 1) AS nota_numerica
                   FROM notas.instrumento_x_estudiante
                  WHERE _id_main        = ?
                    AND _id_instrumento = ?
                    AND _id_competencia = ?
                    AND _id_capacidad   = ?
                    AND _id_indicador   = ?
                    AND correlativo     = ?
                    AND _id_estudiante  = ?";
        $result = $this->db->query($sql, array($idMain, $idInstrumento, $idCompetencia, $idCapacidad, $idIndicador, $correlativo, $idEstudiante));
        if($result->num_rows() == 1) {
            return $result->row()->nota_numerica;
        }
        return null;
    }
          
    function getComboBimestres() {
        $sql = "SELECT id_ciclo,
                       desc_ciclo_acad
                  FROM ciclo_academico
                 WHERE tipo_ciclo = ".ID_TIPO_BIMESTRE."
                   AND (SELECT now())::date < fec_fin
                 ORDER BY orden";
                 $result = $this->db->query($sql);
        return $result->result_array();
     }
   
     function getDatosDocenteEmailAsig($idAula, $anioLectivo, $idGrado, $idCurso) {
         $sql = "SELECT *
                   FROM (SELECT a.desc_aula,
                                (SELECT s.desc_sede FROM sede s WHERE s.nid_sede = a.nid_sede)
                           FROM aula a
                          WHERE nid_aula = ?) AS tab,
                         (SELECT id_curso,
                	             desc_curso
                    	    FROM notas.fun_get_cursos_grado_year(?, ?)
                    	   WHERE id_curso = ?) AS tab2 ";
         $result = $this->db->query($sql, array($idAula, $anioLectivo, $idGrado, $idCurso));
         return $result->row_array();
     }
              
     /////////////////////////////////////// AWARDS /////////////////////////////////////////////////
              
     function getAwards($tipoAward) {
         $sql = "SELECT id_award,
                        desc_award,
                        ruta_icono
                   FROM notas.award
                  WHERE flg_positivo = ? ";
         $result = $this->db->query($sql, array($tipoAward));
         return $result->result_array();
     }
   
     function checkHasAwardsEstudiante($idMain, $idEstudiante) {
         $sql = "SELECT COUNT(1) AS cnt
                   FROM notas.main_x_estudiante
                  WHERE _id_main       = ?
                    AND _id_estudiante = ? ";
         $result = $this->db->query($sql, array($idMain, $idEstudiante));
         if($result->row()->cnt == 0) {
             return true;
         }
         return false;
     }
   
     function getAwardsJSON_AS_String_ByEstu($idMain, $idEstudiante) {
         $sql = "SELECT awards_estudiante_json::TEXT AS awards_json
                   FROM notas.main_x_estudiante
                  WHERE _id_main       = ?
                    AND _id_estudiante = ?";
         $result = $this->db->query($sql, array($idMain, $idEstudiante));
         if($result->num_rows() == 1) {
             return $result->row()->awards_json;
         }
         return null;
     }
   
     function updateJSON_String_Awards($idMain, $idEstudiante, $jsonString) {
         $this->db->where('_id_estudiante', $idEstudiante);
         $this->db->where('_id_main', $idMain);
         $this->db->update('notas.main_x_estudiante', array('awards_estudiante_json' => $jsonString));
         if($this->db->affected_rows() != 1) {
            throw new Exception('Hubo un error al grabar el premio');
         }
         return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);
     }
                      
     function registrarAward_Estudiante($arryInsert) {
         $this->db->insert('notas.main_x_estudiante', $arryInsert);
         if($this->db->affected_rows() != 1) {
            throw new Exception('Hubo un error al grabar');
         }
         return array("error" => EXIT_SUCCESS, "msj" => MSJ_INS);
     }
              
     function getStudentAwardsByMain($idMain, $idEstudiante) {
         $sql = "SELECT awards.*,
                        a.desc_award,
                        a.ruta_icono
                   FROM (SELECT (json_array_elements(awards_estudiante_json->'awards')->>'id_award')::integer AS id_award,
                    	        (json_array_elements(awards_estudiante_json->'awards')->>'fec_registro')::timestamp without time zone AS fec_registro,
                    	        (json_array_elements(awards_estudiante_json->'awards')->>'id_main')::integer   AS id_main,
                    	        (json_array_elements(awards_estudiante_json->'awards')->>'id_estudiante')::integer AS id_estu,
                    	        (json_array_elements(awards_estudiante_json->'awards')->>'audi_usua_regi')::integer AS id_pers_regi,
                    	        (json_array_elements(awards_estudiante_json->'awards')->>'audi_pers_regi')::text    AS pers_regi
                    	   FROM notas.main_x_estudiante me
                    	  WHERE me._id_main       = ?
                    	    AND me._id_estudiante = ? )
                        AS awards,
                           notas.award a
                  WHERE awards.id_award = a.id_award
                  ORDER BY awards.fec_registro DESC";
         $result = $this->db->query($sql, array($idMain, $idEstudiante));
         return $result->result_array();
     }
   
     function getCountAwardsPositivos($idMain, $idEstudiante) {
             $sql = "SELECT COUNT(1) cnt
                       FROM (SELECT (json_array_elements(awards_estudiante_json->'awards')->>'id_award')::integer AS id_award
                               FROM notas.main_x_estudiante me
                              WHERE me._id_main       = ?
                            	AND me._id_estudiante = ? )AS awards,
                            notas.award a
                      WHERE awards.id_award = a.id_award
                        AND a.flg_positivo  = '".AWARD_POSITIVO."' ";
         $result = $this->db->query($sql, array($idMain, $idEstudiante));
         return $result->row()->cnt;
     }
}