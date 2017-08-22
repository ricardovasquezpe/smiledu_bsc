<?php

class M_reportes extends CI_Model
{
    function __construct(){
		parent::__construct();
	}
	
	function reporteGetTutores($year, $idSede, $idNivel, $idGrado){
	    $sql = "SELECT CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
	                   a.nid_aula,
	                   CASE WHEN p.nom_persona IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) 
	                        ELSE '-' END AS nombrecompleto,
	                   nullornotnull(nombre_letra) AS nombre_letra,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   CASE WHEN a.capa_max IS NOT NULL THEN a.capa_max
	                        ELSE 0 END AS capa_max,
	                   (SELECT desc_combo
	                      FROM combo_tipo
	                     WHERE grupo = '".COMBO_TIPO_CICLO."'
	                       AND valor = a.tipo_ciclo::CHARACTER VARYING) AS tipo_ciclo,
	                   CASE WHEN foto_persona IS NOT NULL THEN foto_persona
                            ELSE 'nouser.svg' END AS foto_persona
                  FROM aula a LEFT JOIN persona   p
	                       ON p.nid_persona = a.id_tutor
                   WHERE a.year    = ?  
                   AND nid_sede  = ?
                   AND nid_nivel = ? 
                   AND nid_grado = ?";
	    $result = $this->db->query($sql, array($year, $idSede, $idNivel, $idGrado));
	    return $result->result();
	}

	function reporteGetAlumnosAulaBirthday($idAula, $mes){
	    $sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
                       nullornotnull(p.nro_documento) AS nro_documento,
	                   nullornotnull(da.cod_alumno) AS cod_alumno,
	                   nullornotnull(da.cod_familia) AS cod_familia,
	                   CASE (EXTRACT(MONTH FROM p.fec_naci))
	                        WHEN 1 THEN 'Enero'
	                        WHEN 2 THEN 'Febrero'
                	        WHEN 3 THEN 'Marzo'
                	        WHEN 4 THEN 'Abril'
                	        WHEN 5 THEN 'Mayo'
                	        WHEN 6 THEN 'Junio'
                	        WHEN 7 THEN 'Julio'
                	        WHEN 8 THEN 'Agosto'
                	        WHEN 9 THEN 'Setiembre'
                	        WHEN 10 THEN 'Octubre'
                	        WHEN 11 THEN 'Noviembre'
                	        ELSE 'Diciembre' END AS mes,
	                   p.fec_naci,
	                   CASE WHEN foto_persona IS NOT NULL THEN foto_persona
                            ELSE 'nouser.svg' END AS foto_persona
                  FROM persona        p LEFT JOIN sima.detalle_alumno da ON p.nid_persona = da.nid_persona,
                       persona_x_aula pa
                 WHERE p.nid_persona     = pa.__id_persona
                   AND pa.__id_aula      = ?
	               AND (CASE WHEN ? IS NOT NULL THEN EXTRACT(MONTH FROM p.fec_naci) = ?
	                         ELSE  1 = 1 END)
	               ORDER BY EXTRACT(MONTH FROM p.fec_naci) ASC,
	                        EXTRACT(DAY FROM p.fec_naci) ASC";
	    $result = $this->db->query($sql, array($idAula, $mes, $mes));
	    return $result->result();
	}
	
	function reporteGetAlumnosAula($idAula){
	    $sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
	                   nullornotnull(p.nro_documento) AS nro_documento,
	                   nullornotnull(da.cod_alumno) AS cod_alumno,
	                   nullornotnull(da.cod_familia) AS cod_familia,
	                   nullornotnull(p.telf_pers) AS telf_pers,
	                   CASE WHEN da.colegio_procedencia IS NOT NULL THEN (SELECT desc_colegio 
	                      FROM sima.colegios
	                     WHERE id_colegio = da.colegio_procedencia) 
	                        ELSE '-' END AS colegio_procedencia,
	                   (SELECT desc_combo 
	                      FROM combo_tipo
	                     WHERE grupo = ".COMBO_SEXO."
	                       AND valor = p.sexo::CHARACTER VARYING) AS sexo,
	                   nullornotnull(da.estado) AS estado,
                       CASE (da.estado)
	                        WHEN 'DATOS INCOMPLETOS'       THEN 'datos-incompletos'
	                        WHEN 'EGRESADO'                THEN 'egresado'
                	        WHEN 'MATRICULABLE'            THEN 'matriculable'
                	        WHEN 'MATRICULADO'             THEN 'matriculado'
                	        WHEN 'NO PROMOVIDO'            THEN 'no-promovido'
                	        WHEN 'NO PROMOVIDO NIVELACION' THEN 'nivelacion'
                	        WHEN 'PREREGISTRO'             THEN 'pre-registro'
                	        WHEN 'PROMOVIDO'               THEN 'promovido'
                	        WHEN 'REGISTRADO'              THEN 'registrado'
                	        WHEN 'RETIRADO'                THEN 'retirado'
                	        WHEN 'VERANO'                  THEN 'verano'
                	        ELSE 'default' END AS label,
	                   p.fec_naci,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,' ',f.ape_materno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = da.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT string_agg(INITCAP(CONCAT(f.ape_paterno,' ',f.ape_materno,', ',split_part( f.nombres, ' ' , 1 ))),'/')
                          FROM familiar f,
                           sima.familiar_x_familia ff
                          WHERE ff.id_familiar = f.id_familiar
                            AND ff.cod_familiar = da.cod_familia
                            AND ff.flg_apoderado = '1') as nombrecompletoresponsables,
	                   (SELECT string_agg(f.nro_doc_identidad,'/')
                          FROM familiar f,
                           sima.familiar_x_familia ff
                          WHERE ff.id_familiar = f.id_familiar
                            AND ff.cod_familiar = da.cod_familia
                            AND ff.flg_apoderado = '1') as nrodocresponsables,
	                   CASE WHEN foto_persona IS NOT NULL THEN foto_persona
                            ELSE 'nouser.svg' END AS foto_persona
                  FROM persona_x_aula pa,
	                   persona        p LEFT JOIN sima.detalle_alumno da
	                   ON p.nid_persona = da.nid_persona
                 WHERE p.nid_persona     = pa.__id_persona
                   AND pa.__id_aula      = ?
	               ORDER BY CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona) ASC";
	    $result = $this->db->query($sql, array($idAula));
	    return $result->result();
	}
	
	function reporteGetAlumnosAulaEstado($idSede, $idNivel, $idGrado, $estado){
	    $sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
                       nullornotnull(p.nro_documento) AS nro_documento,
	                   nullornotnull(da.cod_alumno) AS cod_alumno,
	                   nullornotnull(da.estado) AS estado,
                       CASE (da.estado)
	                        WHEN 'DATOS INCOMPLETOS'       THEN 'datos-incompletos'
	                        WHEN 'EGRESADO'                THEN 'egresado'
                	        WHEN 'MATRICULABLE'            THEN 'matriculable'
                	        WHEN 'MATRICULADO'             THEN 'matriculado'
                	        WHEN 'NO PROMOVIDO'            THEN 'no-promovido'
                	        WHEN 'NO PROMOVIDO NIVELACION' THEN 'nivelacion'
                	        WHEN 'PREREGISTRO'             THEN 'pre-registro'
                	        WHEN 'PROMOVIDO'               THEN 'promovido'
                	        WHEN 'REGISTRADO'              THEN 'registrado'
                	        WHEN 'RETIRADO'                THEN 'retirado'
                	        WHEN 'VERANO'                  THEN 'verano'
                	        ELSE 'default' END AS label
                  FROM persona p LEFT JOIN sima.detalle_alumno da ON p.nid_persona = da.nid_persona
	             WHERE (CASE WHEN ? IS NOT NULL THEN UPPER(da.estado) = UPPER(?)
	                         ELSE  1 = 1 END)
	               AND da.id_sede_ingreso  = ?
	               AND da.id_nivel_ingreso = ?
	               AND da.id_grado_ingreso = ?
	               ORDER BY nombrecompleto ASC";
	    $result = $this->db->query($sql, array($estado, $estado, $idSede, $idNivel, $idGrado));
	    return $result->result();
	}
	
	function reporteGetCantAlumnos($year, $idSede, $idNivel, $idGrado){
	    $sql = "SELECT a.nid_aula,
	                   INITCAP(nullornotnull(a.desc_aula)) AS desc_aula,
	                   s.desc_sede,
	                   n.desc_nivel,
	                   g.desc_grado,
	                   CASE WHEN a.capa_max IS NOT NULL THEN a.capa_max
	                        ELSE 0 END as capa_max,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   CASE WHEN a.nombre_letra IS NOT NULL THEN a.nombre_letra
	                        ELSE '-' END as nombre_letra,
	                   a.tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '".SEXO_MASCULINO."'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) varones,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '".SEXO_FEMENINO."'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
	              FROM aula  a,
	                   sede  s,
	                   nivel n,
	                   grado g
	             WHERE a.nid_sede  = s.nid_sede
	               AND a.nid_nivel = n.nid_nivel
	               AND a.nid_grado = g.nid_grado
	               AND a.year      = ?
	               AND a.nid_sede  = ?
	               AND CASE WHEN ? IS NOT NULL THEN a.nid_nivel = ?
	                   ELSE 1 = 1 END
	               AND CASE WHEN ? IS NOT NULL THEN a.nid_grado = ?
	                   ELSE 1 = 1 END
	               ORDER BY n.nid_nivel DESC, g.nid_grado ASC, a.desc_aula DESC";
	    $result = $this->db->query($sql, array($year, $idSede, $idNivel, $idNivel, $idGrado, $idGrado));
	    return $result->result();
	}
	
	function reporteGetDocentes($idCursos, $year, $idSede, $idGrado, $idNivel){
	    $sql="SELECT INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) AS nombrecompleto,
    	               c.desc_curso,
             CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
                  ELSE '-' END AS nro_documento,
             CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
                  ELSE '-' END AS desc_aula
    		      FROM main     m
    	     LEFT JOIN persona p
    			    ON m.nid_persona = p.nid_persona
    	     LEFT JOIN cursos c
    			    ON m.nid_curso   = c.id_curso
    	     LEFT JOIN persona_x_aula pa
    			    ON p.nid_persona = pa.__id_persona
    	     LEFT JOIN aula a
    			    ON a.nid_aula    = m.nid_aula
    	         WHERE a.year = ?
	               AND a.nid_sede = ?
	               AND a.nid_grado = ?
	               AND a.nid_nivel = ?
	               AND c.id_curso IN ?
    	      ORDER BY nombrecompleto";
	    $result = $this->db->query($sql, array($year, $idSede, $idGrado, $idNivel, $idCursos));
	    return $result->result();
	}
	
	function reporteGetTraslado($year, $idSede, $cb){
	    $sql = "	SELECT n.desc_nivel,
                	       (SELECT COUNT(*)
                		      FROM sima.traslado_alumno ta1,
                		           aula a1
                		     WHERE a1.year     = ?
                	           AND a1.nid_sede = ?
	                           AND ta1.estado  = ?
	                           AND ta1.id_aula_origen = a1.nid_aula
                		       AND a1.nid_nivel  = n.nid_nivel
                		       AND ta1.tipo_traslado = 'INTRASEDE') intrasede,
                		   (SELECT COUNT(*)
                		      FROM sima.traslado_alumno ta2,
                		           aula a2
                		     WHERE a2.year     = ?
                	           AND a2.nid_sede = ?
	                           AND ta2.estado  = ?
	                           AND ta2.id_aula_origen = a2.nid_aula
                		       AND a2.nid_nivel  = n.nid_nivel
                		       AND ta2.tipo_traslado = 'INTERSEDES') intersedes
                	  FROM sima.traslado_alumno ta,
                	       aula a,
                	       nivel n
                     WHERE a.year     = ?
                	   AND a.nid_sede = ?
	                   AND ta.estado  = ?
                	   AND ta.id_aula_origen = a.nid_aula
                	   AND a.nid_nivel  = n.nid_nivel
                  GROUP BY n.nid_nivel";
	    $result = $this->db->query($sql, array($year, $idSede, $cb, $year, $idSede, $cb, $year, $idSede, $cb));
	    return $result->result();
	}
	
	function getAlumnosAulaBySexo($idAula, $sexo){
	    $sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
		               nullornotnull(p.nro_documento) AS nro_documento,
	                   p.nid_persona,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'foto_perfil_default.png' END AS foto_persona
			      FROM persona        p,
	                   persona_x_aula pa
	             WHERE p.nid_persona = pa.__id_persona
	               AND pa.__id_aula = ?
	               AND p.sexo       = ?
		      ORDER BY CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)";
	    $result = $this->db->query($sql, array($idAula, $sexo));
	    return $result->result();
	}

	function reporteGetFamiliaresByAula($idAula, $idParentezcos){
	    $sql = "SELECT INITCAP(CONCAT(f.ape_paterno, ' ',f.ape_materno, ', ' ,f.nombres)) AS nombrecompletofamiliar,
                       nullornotnull(f.telf_fijo) AS telf_fijo,
                       nullornotnull(f.telf_celular) AS telf_celular,
                       nullornotnull(f.direccion_hogar) AS direccion_hogar,
                       INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompletoalumno,
	                   f.id_familiar,
	                   f.email1 AS correo,
	                   (SELECT desc_combo
	                      FROM combo_tipo c
	                     WHERE c.grupo = ".COMBO_PARENTEZCO."
	                       AND c.valor = ff.parentesco::CHARACTER VARYING) AS parentesco
                  FROM persona_x_aula pa,
                       persona p INNER JOIN sima.detalle_alumno da
	                             ON p.nid_persona = da.nid_persona
	                   INNER JOIN sima.familiar_x_familia ff
                                 ON da.cod_familia = ff.cod_familiar,
                       familiar f
                 WHERE p.nid_persona = pa.__id_persona
                   AND pa.__id_aula  = ?
	               AND ff.parentesco IN  ?
                   AND f.id_familiar = ff.id_familiar
                   AND p.nom_persona <> 'FAMILIA'
	              ORDER BY nombrecompletoalumno";
	    $result = $this->db->query($sql, array($idAula, $idParentezcos));
	    return $result->result();
	}
	
	function reporteGetFamiliaresByDistrito($ubigeo, $idParentezcos){
	    $sql = "SELECT INITCAP(CONCAT(f.ape_paterno, ' ',f.ape_materno, ', ' ,f.nombres)) AS nombrecompleto,
	                   nullornotnull(f.telf_fijo) AS telf_fijo,
	                   nullornotnull(f.telf_celular) AS telf_celular,
	                   nullornotnull(f.direccion_hogar) AS direccion_hogar,
	                   CASE WHEN f.foto_persona IS NOT NULL THEN f.foto_persona
	                        ELSE 'foto_perfil_default.png' END AS foto_persona,
	                   (SELECT desc_combo
	                      FROM combo_tipo c
	                     WHERE c.grupo = ".COMBO_PARENTEZCO."
	                       AND c.valor = ff.parentesco::CHARACTER VARYING) AS parentesco
	             FROM familiar f,
	                  sima.familiar_x_familia ff
	            WHERE ff.id_familiar  = f.id_familiar
	              AND ff.parentesco IN ?
	              AND f.ubigeo_hogar = ?";
	    $result = $this->db->query($sql, array($idParentezcos, $ubigeo));
	    return $result->result();
	}
	
	function getGraficosReporteBirthday($idAula, $mes){
	    $sql = "SELECT COUNT(1) as cant,
	                   CASE (EXTRACT(MONTH FROM p.fec_naci))
	                        WHEN 1 THEN 'ENERO'
	                        WHEN 2 THEN 'FEBRERO'
                	        WHEN 3 THEN 'MARZO'
                	        WHEN 4 THEN 'ABRIL'
                	        WHEN 5 THEN 'MAYO'
                	        WHEN 6 THEN 'JUNIO'
                	        WHEN 7 THEN 'JULIO'
                	        WHEN 8 THEN 'AGOSTO'
                	        WHEN 9 THEN 'SETIEMBRE'
                	        WHEN 10 THEN 'OCTUBRE'
                	        WHEN 11 THEN 'NOVIEMBRE'
                	        ELSE 'DICIEMBRE' END AS mes
                  FROM persona        p,
                       persona_x_aula pa
                 WHERE p.nid_persona     = pa.__id_persona
                   AND pa.__id_aula      = ?
	               AND (CASE WHEN ? IS NOT NULL THEN EXTRACT(MONTH FROM p.fec_naci) = ?
	                         ELSE  1 = 1 END)
	          GROUP BY (EXTRACT(MONTH FROM p.fec_naci)), mes
	          ORDER BY EXTRACT(MONTH FROM p.fec_naci) ASC";
	    $result = $this->db->query($sql, array($idAula, $mes, $mes));
	    return $result->result();
	     
	}
	
	function getProfesoresCursosByAula($idAula){
	    $sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
		               c.desc_curso
			      FROM main     m
		     LEFT JOIN persona p
				    ON m.nid_persona = p.nid_persona
		     LEFT JOIN cursos c
				    ON m.nid_curso   = c.id_curso
		         WHERE m.nid_aula    = ?
		      ORDER BY CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)";
	
	    $result = $this->db->query($sql, array($idAula));
	    return $result->result();
	}

	function getGraficoReporteDocentes($idCursos, $year, $idSede, $idGrado, $idNivel){
	    $sql="SELECT CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
                                ELSE '-' END AS desc_aula,
	                   COUNT(1) cant
    		      FROM main     m
    	     LEFT JOIN persona p
    			    ON m.nid_persona = p.nid_persona
    	     LEFT JOIN cursos c
    			    ON m.nid_curso   = c.id_curso
    	     LEFT JOIN persona_x_aula pa
    			    ON p.nid_persona = pa.__id_persona
    	     LEFT JOIN aula a
    			    ON a.nid_aula    = m.nid_aula
    	         WHERE a.year = ?
	               AND a.nid_sede = ?
	               AND a.nid_grado = ?
	               AND a.nid_nivel = ?
	               AND c.id_curso IN ?
	          GROUP BY desc_aula
	          ORDER BY desc_aula";
	    $result = $this->db->query($sql, array($year, $idSede, $idGrado, $idNivel, $idCursos));
	    return $result->result();
	}
	
	function getGraficoReporteFamiliarDistrito($ubigeo, $idParentescos){
	    $sql = "SELECT COUNT (1) cant,
	                   (SELECT desc_combo
	                      FROM combo_tipo c
	                     WHERE c.grupo = ".COMBO_PARENTEZCO."
	                       AND c.valor = ff.parentesco::CHARACTER VARYING) AS parentesco
	             FROM familiar f,
	                  sima.familiar_x_familia ff
	            WHERE ff.id_familiar  = f.id_familiar
	              AND ff.parentesco IN ?
	              AND f.ubigeo_hogar = ?
	         GROUP BY parentesco";
	    $result = $this->db->query($sql, array($idParentescos, $ubigeo));
	    return $result->result();
	}
	
	function getGraficoReporteFamiliarParentescos($idAula, $idParentescos){
	    $sql = "SELECT COUNT (1) as cant,
	                   (SELECT desc_combo
	                      FROM combo_tipo c
	                     WHERE c.grupo = ".COMBO_PARENTEZCO."
	                       AND c.valor = ff.parentesco::CHARACTER VARYING) AS parentesco
                  FROM persona_x_aula pa,
                       persona p INNER JOIN sima.familiar_x_familia ff
                                 ON p.cod_familia = ff.cod_familiar
                 WHERE p.nid_persona = pa.__id_persona
                   AND pa.__id_aula  = ?
	               AND ff.parentesco IN  ?
                   AND p.nom_persona <> 'FAMILIA'
              GROUP BY parentesco";
	    $result = $this->db->query($sql, array($idAula, $idParentescos));
	    return $result->result();
	}

	function getHijosByFamiliar($idfamiliar){
	    $sql = "    SELECT p.nid_persona,
	                       INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
            	           to_char(p.fec_naci, 'dd-mm-yyyy') AS fec_naci,
                           nullornotnull(p.nro_documento) AS dni,
	                       CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                            ELSE 'foto_perfil_default.png' END AS foto_persona
                      FROM familiar f,
                           sima.familiar_x_familia ff,
                           persona P
                 LEFT JOIN persona_x_rol pr
                        ON p.nid_persona   = pr.nid_persona,
	                       sima.detalle_alumno da
                     WHERE f.id_familiar   = ?
                       AND pr.nid_rol      = 5
	                   AND da.nid_persona  = p.nid_persona
                       AND f.id_familiar   = ff.id_familiar
                       AND ff.cod_familiar = da.cod_familia
                  ORDER BY nombrecompleto";
	    $result = $this->db->query($sql, array($idfamiliar));
	    return $result->result();
	}

	function reporteGetRatificacion2($year, $idsede, $idgrado, $idnivel, $estado, $idaula){
		$sql = "SELECT p.nid_persona,
					   CASE WHEN p.nom_persona IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) 
	                    ELSE '-' END AS nombrecompleto,
	                   INITCAP(nullornotnull(a.desc_aula)) AS desc_aula,
		       		   s.desc_sede,
		               n.desc_nivel,
		               g.desc_grado,
		               d.estado estado_alumno,
		               m.estado estado_ratif
                  FROM pagos.movimiento m
    	    INNER JOIN pagos.detalle_cronograma dc
		               ON m._id_detalle_cronograma = dc.id_detalle_cronograma
    	    INNER JOIN pagos.cronograma c
		               ON c.id_cronograma = dc._id_cronograma
	        INNER JOIN persona p
	                ON p.nid_persona = m._id_persona
	        INNER JOIN persona_x_aula pxa
	                ON (pxa.__id_persona = p.nid_persona AND pxa.year_academico = ?)
	        INNER JOIN aula a
	                ON pxa.__id_aula = a.nid_aula
	        INNER JOIN sede s
	                ON s.nid_sede = a.nid_sede
	        INNER JOIN nivel n
	                ON n.nid_nivel = a.nid_nivel
	        INNER JOIN grado g
	                ON g.nid_grado = a.nid_grado
	        INNER JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
		         WHERE dc.flg_tipo = '2'
		           AND c.year = ?
		           AND s.nid_sede = ?
		           AND (CASE WHEN ? IS NOT NULL THEN (g.nid_grado = ? AND n.nid_nivel = ?)
	                        ELSE 1 = 1 END)
		           AND (CASE WHEN ? IS NOT NULL THEN m.estado LIKE ?
	                        ELSE 1 = 1 END)
		           AND (CASE WHEN ? IS NOT NULL THEN a.nid_aula = ?
	                        ELSE 1 = 1 END)
				  ORDER BY g.nid_grado, desc_aula, nombrecompleto";
	    $result = $this->db->query($sql, array($year, $year+1,$idsede,$idnivel, $idgrado, $idnivel,$estado,"%".$estado."%", $idaula,$idaula));
	    return $result->result();
	}

	function reporteGetRatificacion1($year, $idsede, $idgrado, $idnivel, $idaula, $flgrecibido ){
		$sql ="		SELECT p.nid_persona,
		                   CASE WHEN p.nom_persona IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) 
		                        ELSE '-' END AS nombrecompleto,
		       			   INITCAP(nullornotnull(a.desc_aula)) AS desc_aula,
		       			   s.desc_sede,
		       			   n.desc_nivel,
		       			   g.desc_grado,
		       			   d.estado estado_alumno		  
		  			  FROM sima.confirmacion_datos cd
                INNER JOIN persona p
		 			    ON p.nid_persona = cd.id_estudiante
				INNER JOIN persona_x_aula pxa
		                ON (pxa.__id_persona = p.nid_persona AND pxa.year_academico = ?)
	            INNER JOIN aula a
		                ON pxa.__id_aula = a.nid_aula
	                 INNER JOIN sede s
		                ON s.nid_sede = a.nid_sede
	            INNER JOIN nivel n
					    ON n.nid_nivel = a.nid_nivel
	            INNER JOIN grado g
						ON g.nid_grado = a.nid_grado
				INNER JOIN sima.detalle_alumno d
						ON d.nid_persona = p.nid_persona
	 				 WHERE UPPER(cd.tipo) LIKE upper('R')
	   				   AND cd.year_confirmacion = ?
    				   AND s.nid_sede = ?
	   				   AND (CASE WHEN ? IS NOT NULL THEN cd.flg_recibido = '1'
		     				     ELSE cd.flg_recibido IS NULL END)
			           AND (CASE WHEN ? IS NOT NULL THEN (g.nid_grado = ? AND n.nid_nivel = ?)
		                        ELSE 1 = 1 END)
			           AND (CASE WHEN ? IS NOT NULL THEN a.nid_aula = ?
		                        ELSE 1 = 1 END)
				  ORDER BY g.nid_grado, desc_aula, nombrecompleto";
	    $result = $this->db->query($sql, array($year, $year+1,$idsede, $flgrecibido, $idnivel, $idgrado, $idnivel, $idaula, $idaula));
	    return $result->result();
	}

	function reporteGetRatificacion0($year, $idsede, $idgrado, $idnivel, $idaula){
		$sql ="		SELECT p.nid_persona,
		                   CASE WHEN p.nom_persona IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) 
		                        ELSE '-' END AS nombrecompleto,
		       			   INITCAP(nullornotnull(a.desc_aula)) AS desc_aula,
		       			   s.desc_sede,
		       			   n.desc_nivel,
		       			   g.desc_grado,
		       			   d.estado estado_alumno
		  			  FROM persona p
				INNER JOIN persona_x_aula pxa
		                ON (pxa.__id_persona = p.nid_persona AND pxa.year_academico = ?)
	            INNER JOIN aula a
		                ON pxa.__id_aula = a.nid_aula
	            INNER JOIN sede s
		                ON s.nid_sede = a.nid_sede
	            INNER JOIN nivel n
					    ON n.nid_nivel = a.nid_nivel
	            INNER JOIN grado g
						ON g.nid_grado = a.nid_grado
				INNER JOIN sima.detalle_alumno d
						ON d.nid_persona = p.nid_persona
	 				 WHERE s.nid_sede = ?
			           AND (CASE WHEN ? IS NOT NULL THEN (g.nid_grado = ? AND n.nid_nivel = ?)
		                        ELSE 1 = 1 END)
			           AND (CASE WHEN ? IS NOT NULL THEN a.nid_aula = ?
		                        ELSE 1 = 1 END)
		               AND p.nid_persona NOT IN (SELECT id_estudiante 
		                                           FROM sima.confirmacion_datos 
		                                          WHERE flg_recibido = '1' 
		                                            AND UPPER(tipo) LIKE upper('R'))
				  ORDER BY g.nid_grado, desc_aula, nombrecompleto";
	    $result = $this->db->query($sql, array($year, $idsede, $idnivel, $idgrado, $idnivel, $idaula, $idaula));
	    return $result->result();
	}
	
	
	/*
	
	//GRAFICOS
	
	function getGraficosReporteCumpleaños($idAula, $mes){
	    $sql = "SELECT COUNT(1) as cant,
	                   CASE (EXTRACT(MONTH FROM p.fec_naci))
	                        WHEN 1 THEN 'ENERO'
	                        WHEN 2 THEN 'FEBRERO'
                	        WHEN 3 THEN 'MARZO'
                	        WHEN 4 THEN 'ABRIL'
                	        WHEN 5 THEN 'MAYO'
                	        WHEN 6 THEN 'JUNIO'
                	        WHEN 7 THEN 'JULIO'
                	        WHEN 8 THEN 'AGOSTO'
                	        WHEN 9 THEN 'SETIEMBRE'
                	        WHEN 10 THEN 'OCTUBRE'
                	        WHEN 11 THEN 'NOVIEMBRE'
                	        ELSE 'DICIEMBRE' END AS mes
                  FROM persona        p,
                       persona_x_aula pa
                 WHERE p.nid_persona     = pa.__id_persona
                   AND pa.__id_aula      = ?
	               AND (CASE WHEN ? IS NOT NULL THEN EXTRACT(MONTH FROM p.fec_naci) = ?
	                         ELSE  1 = 1 END)
	          GROUP BY (EXTRACT(MONTH FROM p.fec_naci)), mes
	          ORDER BY EXTRACT(MONTH FROM p.fec_naci) ASC";
	    $result = $this->db->query($sql, array($idAula, $mes, $mes));
	    return $result->result();
	}
	
	
	*/
}