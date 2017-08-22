<?php
class M_graficos_new extends  CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getEvaluadoresCantidadEvas() {
        $sql = 'SELECT id_evaluador, 
                       COUNT(1) AS cantidad,
                       CONCAT(SPLIT_PART(p.nom_persona,\' \', 1),\' \',UPPER(p.ape_pate_pers),\' \',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluador
                  FROM sped.evaluacion e,
                       persona         p
                 WHERE e.estado_evaluacion = \''.EJECUTADO.'\'
                   AND e.id_evaluador      = p.nid_persona
                GROUP BY id_evaluador, CONCAT(SPLIT_PART(p.nom_persona,\' \', 1),\' \',UPPER(p.ape_pate_pers),\' \',SUBSTRING(p.ape_mate_pers,1, 1))';
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getCantidadEvasByFechas() {
        $sql = "SELECT tt.fecha,
                       (SELECT STRING_AGG(tab.cnt, ',')
                	      FROM (SELECT e.id_evaluador, COALESCE(tab.count, 0)::text AS cnt
                            	  FROM sped.evaluacion e LEFT JOIN (SELECT COUNT(1), ex.id_evaluador
                                            					      FROM sped.evaluacion ex
                                            					      WHERE ex.estado_evaluacion = tt.estado_evaluacion
                                            						    AND TO_CHAR(ex.fecha_evaluacion, 'yyyy-mm-dd') = tt.fecha
                                            					    GROUP BY ex.id_evaluador) AS tab ON e.id_evaluador = tab.id_evaluador
                            	 WHERE e.estado_evaluacion = tt.estado_evaluacion
                            	 GROUP by e.id_evaluador, tab.count
                            	 ORDER BY e.id_evaluador) AS tab
                                ) AS cants
                  FROM (SELECT TO_CHAR(ee.fecha_evaluacion, 'yyyy-mm-dd') AS fecha,
                               ee.estado_evaluacion
                    	  FROM sped.evaluacion ee
                    	 WHERE ee.estado_evaluacion = '".EJECUTADO."'
                    	GROUP BY TO_CHAR(ee.fecha_evaluacion, 'yyyy-mm-dd'), ee.estado_evaluacion
                    	ORDER BY TO_CHAR(ee.fecha_evaluacion, 'yyyy-mm-dd')::DATE )AS tt";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getEvaluadores() {
        $sql = "SELECT id_evaluador,
                       CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluador
                  FROM sped.evaluacion e,
                       persona         p
                 WHERE e.estado_evaluacion = '".EJECUTADO."'
                   AND e.id_evaluador      = p.nid_persona
              GROUP by e.id_evaluador, CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1))
              ORDER BY e.id_evaluador";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getCantDocentes($id_sede_evaluador) {
        $sql = "SELECT id_evaluado, 
                       COUNT(1) AS cantidad,
                       CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluado
                  FROM sped.evaluacion e,
                       persona         p
                 WHERE e.estado_evaluacion = '".EJECUTADO."'
	               AND id_sede_evaluador = ?
                   AND e.id_evaluador      = p.nid_persona
              GROUP BY id_evaluado, CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1))";
        $result = $this->db->query($sql, array($id_sede_evaluador));
        return $result->result_array();
    }
    
    function getCantLowDocentes($id_sede_evaluador) {
        $sql = "SELECT id_evaluado,
                       COUNT(1) AS cantidad,
                       CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluado
                  FROM sped.evaluacion e,
                       persona         p
                 WHERE e.estado_evaluacion = '".EJECUTADO."'
	               AND id_sede_evaluador = ?
                   AND e.id_evaluador      = p.nid_persona
                GROUP BY id_evaluado, CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1))";
        $result = $this->db->query($sql, array($id_sede_evaluador));
        return $result->result_array();
    }
    
    function getEstadoEvaluacionesCant() {
        $sql = "SELECT e.id_evaluador,
                       CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluador,
                       (SELECT STRING_AGG(tt.cnt::text, ',')
                	      FROM (SELECT tab.estado,
                        		       (SELECT COUNT(1) AS cnt
                            			  FROM sped.evaluacion ex
                            			 WHERE ex.id_evaluador = e.id_evaluador
                            			   AND ex.estado_evaluacion = tab.estado
                        		       )
                        		  FROM (SELECT '".PENDIENTE."' AS estado
                            			UNION ALL
                            			SELECT '".EJECUTADO."'
                            			UNION ALL
                            			SELECT '".NO_EJECUTADO."'
                            			UNION ALL
                            			SELECT '".INJUSTIFICADO."'
                            			UNION ALL
                            			SELECT '".POR_JUSTIFICAR."'
                            			UNION ALL
                            			SELECT '".JUSTIFICADO."') AS tab) AS tt) AS cants
                  FROM sped.evaluacion e,
                       persona         p
                 WHERE e.id_evaluador = p.nid_persona
                GROUP by e.id_evaluador, CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',UPPER(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1))
                ORDER BY e.id_evaluador";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getTipoVisitaEvaluaciones($idSede) {
        $sql = "SELECT id_evaluador,
                    (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1, 1),'.') FROM persona WHERE nid_persona = id_evaluador) AS evaluador,
                    (SELECT STRING_AGG(tab.nota_promedio::text, ',') AS nota_vigesimal
                    FROM (SELECT COALESCE(tab.avg, '0') AS nota_promedio
                    FROM (SELECT '".VISITA_OPINADA."' AS tipo_visita
                    UNION
                    SELECT '".VISITA_SEMI_OPINADA."'
                        UNION
                        SELECT '".VISITA_NO_OPINADA."') AS lft LEFT JOIN (SELECT AVG(nota_vigesimal),
                        e2.tipo_visita
                        FROM sped.evaluacion e2
                        WHERE e2.estado_evaluacion = '".EJECUTADO."'
                            AND e2.id_evaluador      = e.id_evaluador
                            GROUP BY e2.tipo_visita) AS tab
                            ON lft.tipo_visita = tab.tipo_visita
                    ) AS tab )
                    FROM sped.evaluacion e,
                    persona p
                    WHERE e.estado_evaluacion = '".EJECUTADO."'
                        AND ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1))
                        AND e.id_evaluador     = p.nid_persona
                        GROUP BY e.id_evaluador,p.nom_persona, p.ape_pate_pers, p.ape_mate_pers
                        ORDER BY e.id_evaluador ASC";
        $result = $this->db->query($sql, array($idSede, $idSede, $idSede));
        return $result->result_array();
    }
    
    function getTop_Low_SubFactores($tipo, $idSede, $top = 5) {////
        $sql = "SELECT *
                  FROM (SELECT ROUND(AVG(tab.valor), 1) AS val,
                	       tab.desc_indicador,
                           tab.id_subfactor,
                	       DENSE_RANK() OVER(ORDER BY AVG(tab.valor) DESC)
                	  FROM (SELECT (tab.data->>'valor_vigesimal')::NUMERIC AS valor,
                    		       (tab.data->>'valor_indi')::NUMERIC AS valor_indi,
                    		       (tab.data->>'id_criterio')::INTEGER AS id_criterio,
                    		       (tab.data->>'id_indicador')::INTEGER AS id_subfactor,
                    		       (tab.data->>'desc_indicador')::TEXT AS desc_indicador
                    		  FROM (SELECT jsonb_array_elements((respuestas_jsonb->>'respuestas')::jsonb) AS data
                    			      FROM sped.evaluacion
                    			     WHERE estado_evaluacion = '".EJECUTADO."'
                    			       AND ( (? <> 0 AND id_sede_evaluador = ?) OR (? = 0 AND 1 = 1) ) 
                    			   ) AS tab) AS tab
                    	GROUP BY tab.desc_indicador,  tab.id_subfactor
                    	ORDER BY AVG(tab.valor) $tipo ) AS tab
                 WHERE tab.dense_rank <= ?";
        $result = $this->db->query($sql, array($idSede, $idSede, $idSede, $top));
        return $result->result_array();
    }
    
    function getTop_Low_Docentes($idSede, $top = 5) {
        $sql = "SELECT tab.*
                  FROM (SELECT ROUND(AVG(e.nota_vigesimal), 2) AS promedio,
                			   CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',INITCAP(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluado,
                			   DENSE_RANK() OVER(ORDER BY AVG(e.nota_vigesimal) DESC),
                			   e.id_evaluado
                		  FROM sped.evaluacion e,
                			   persona         p
                		 WHERE estado_evaluacion = '".EJECUTADO."'
                		   AND ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1) )
                		   AND e.id_evaluado     = p.nid_persona
                		GROUP BY p.nom_persona, p.ape_pate_pers, p.ape_mate_pers,e.id_evaluado
                		ORDER BY AVG(e.nota_vigesimal) ASC) AS tab
                 WHERE tab.dense_rank <= ?";
        $result = $this->db->query($sql, array($idSede, $idSede, $idSede, $top));
        return $result->result_array();
    }
    
    function getTopDocentes($idSede, $top = 5) {
        $sql = "SELECT tab.*
                  FROM (SELECT ROUND(AVG(e.nota_vigesimal), 2) AS promedio,
                			   CONCAT(SPLIT_PART(p.nom_persona,' ', 1),' ',INITCAP(p.ape_pate_pers),' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS evaluado,
                			   DENSE_RANK() OVER(ORDER BY AVG(e.nota_vigesimal) DESC),e.id_evaluado
                		  FROM sped.evaluacion e,
                			   persona         p
                		 WHERE estado_evaluacion = '".EJECUTADO."'
                		   AND ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1) )
                		   AND e.id_evaluado     = p.nid_persona
                		GROUP BY p.nom_persona, p.ape_pate_pers, p.ape_mate_pers,e.id_evaluado
                		ORDER BY AVG(e.nota_vigesimal) DESC) AS tab
                 WHERE tab.dense_rank <= ?";
        $result = $this->db->query($sql, array($idSede, $idSede, $idSede, $top));
        return $result->result_array();
    }
    
    function getEvaDocentesFechaLow($idSede, $idSubF, $orden) {
        $sql = "SELECT * FROM (SELECT 
        		       row_number() OVER(ORDER BY fecha_evaluacion) AS orden,
        		       (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1, 1),'.') FROM persona WHERE nid_persona = id_evaluado) AS docente,
        		       (select desc_sede from sede where nid_sede = id_sede) as sede,
        		       (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1, 1),'.') FROM persona WHERE nid_persona = id_evaluador) AS evaluador,
        		       INITCAP(tipo_visita) as tipo_visita,
        		       CONCAT(TO_CHAR(fecha_inicio,'hh12:mi AM'),' - ',TO_CHAR(fecha_fin,'hh12:mi AM')) AS horario,
        		       tb.fecha_evaluacion
        		  FROM (SELECT (tab.unwind->>'id_indicador')::integer AS id_subfactor,
        			       (tab.unwind->>'valor_vigesimal')::numeric AS nota_vigesimal,
        			       tab.fecha_evaluacion,
        			       tab.id_evaluado,
        			       tab.id_sede,
        			       tab.id_evaluador,
        			       tab.tipo_visita,
        			       fecha_inicio,
        			       fecha_fin
        			  FROM (SELECT jsonb_array_elements(respuestas_jsonb->'respuestas') AS unwind,
        				       fecha_evaluacion,
        				       id_evaluado,
        				       id_sede,
        				       id_evaluador,
        				       tipo_visita,
        				       fecha_inicio,
        				       fecha_fin
        				  FROM sped.evaluacion e
        				 WHERE estado_evaluacion = '".EJECUTADO."'
        				 AND ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1))
        				   ) AS tab ) AS tb
		 WHERE tb.id_subfactor = ?) a
		 WHERE a.orden = ?";
        $result = $this->db->query($sql, array($idSede,$idSede,$idSede, $idSubF, $orden));
        return $result->result_array();
    }
    
    function getDetaEvaDocentes1($idSede, $idSubF) {
        $sql = "SELECT 
                   row_number() OVER(ORDER BY fecha_evaluacion) AS orden,
                   (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1, 1),'.') FROM persona WHERE nid_persona = id_evaluador) AS evaluador,
                   (select desc_sede from sede where nid_sede = id_sede) as sede,
                   INITCAP(tipo_visita) as tipo_visita,
                   nota_vigesimal as nota_vigesimal,
                   CONCAT(TO_CHAR(fecha_inicio,'hh12:mi AM'),' - ',TO_CHAR(fecha_fin,'hh12:mi AM')) as horario
              FROM (SELECT (tab.unwind->>'id_indicador')::integer AS id_subfactor,
            	       (tab.unwind->>'id_docente')::integer AS id_docente,
            	       tab.fecha_inicio,
            	       tab.fecha_fin,
            	       tab.fecha_evaluacion,
            	       tab.id_evaluador,
            	       tab.id_sede,
            	       tab.tipo_visita,
            	       tab.nota_vigesimal
            	  FROM (SELECT jsonb_array_elements(respuestas_jsonb->'respuestas') AS unwind,
            		       fecha_inicio,
            		       fecha_fin,
            		       fecha_evaluacion,
            		       id_evaluador,
            		       id_sede,
            		       estado_evaluacion,
            		       tipo_visita,
            		       nota_vigesimal
            		  FROM sped.evaluacion e
            		 WHERE estado_evaluacion = '".EJECUTADO."'
            		 AND ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1))
            		   ) AS tab order by id_subfactor) AS tb
            WHERE tb.id_docente = ?
            GROUP BY evaluador, sede, INITCAP(tipo_visita),nota_vigesimal, fecha_inicio, fecha_fin, fecha_evaluacion";
        $result = $this->db->query($sql, array($idSede,$idSede,$idSede, $idSubF));
        return $result->result_array();
    }
    
    function getDetaEvaTipoVisita($tipoVisita, $idEvaluador ) {
        $sql = "SELECT 		
                	TO_CHAR(fecha_evaluacion,'dd/mm/yyyy hh12:mi AM') AS fecha_evaluacion,
                    (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1, 1),'.') FROM persona WHERE nid_persona = id_evaluado) AS evaluado,
                    nota_vigesimal as nota_vigesimal,
                    CONCAT(TO_CHAR(fecha_inicio,'hh12:mi AM'),' - ',TO_CHAR(fecha_fin,'hh12:mi AM')) as horario
               FROM sped.evaluacion e
              WHERE estado_evaluacion = '".EJECUTADO."'
                AND tipo_visita = ?
                AND id_evaluador = ?
           GROUP BY evaluado,nota_vigesimal, fecha_inicio, fecha_fin, fecha_evaluacion
           ORDER BY fecha_evaluacion::date DESC, fecha_evaluacion DESC";
        $result = $this->db->query($sql, array($tipoVisita,$idEvaluador ));
        return $result->result_array();
    }
    
    function getDetaEvaHechasXHacer($idEvaluador ) {
        $sql = "SELECT 
            	(SELECT count(TO_CHAR(fecha_inicio, 'dd/mm/yyyy')) * (SELECT valor_num_1 
            							FROM sped.sped_config 
            						       WHERE id_config=1)
                                      FROM sped.evaluacion
                                     WHERE (fecha_inicio > '2016/10/23' AND fecha_inicio < current_date)
                            	   AND id_evaluador = ?
                                       AND to_char(current_date, 'D') <> '1'
                                       AND to_char(current_date, 'D') <> '7') AS total,
                                   (SELECT count(TO_CHAR(fecha_inicio, 'dd/mm/yyyy'))* (SELECT valor_num_1 
            									      FROM sped.sped_config 
            								             WHERE id_config=1)
                                      FROM sped.evaluacion
                                     WHERE (fecha_inicio > '2016/10/23' AND fecha_inicio < current_date)
                                       AND id_evaluador = ?
                                       AND to_char(current_date, 'D') <> '1'
                                       AND to_char(current_date, 'D') <> '7'
                                       AND estado_evaluacion like '".EJECUTADO."') AS Ejecutadas";
        $result = $this->db->query($sql, array($idEvaluador,$idEvaluador ));
        return $result->result_array();
    }
        
    function getGaugesPromediosSedeGrupoEduc($idSede) {
        $sql = "SELECT ROUND(AVG(nota_vigesimal), 1) AS nota,
                       'Grupo Educ.'                 AS label
                  FROM sped.evaluacion
                 WHERE estado_evaluacion = '".EJECUTADO."'
                UNION ALL
                SELECT ROUND(AVG(e.nota_vigesimal), 1),
                       s.desc_sede
                  FROM sped.evaluacion e,
                       main m,
                       aula a,
                       sede s
                 WHERE e.estado_evaluacion = '".EJECUTADO."'
                   AND a.nid_sede          = $idSede
                   AND m.nid_main          = e.id_horario
                   AND m.nid_aula          = a.nid_aula
                   AND a.nid_sede          = s.nid_sede
                GROUP BY s.desc_sede";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getBarrasEvasByArea($idSede) {
        $sql = "SELECT tab.*
                  FROM (SELECT CASE WHEN e.id_area IS NOT NULL THEN COUNT(1) ELSE 0 END AS cant_evas,
                	       e.id_area,
                	       a.desc_area
                	  FROM area a LEFT JOIN sped.evaluacion e ON ( ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1) ) 
                                                                   AND a.id_area         = e.id_area 
                                                                   AND estado_evaluacion = '".EJECUTADO."')
                	 WHERE a.id_area_general = ?
                  GROUP BY e.id_area, a.desc_area) AS tab
                  ORDER BY tab.cant_evas DESC";
        $result = $this->db->query($sql, array($idSede, $idSede, $idSede, ID_AREA_ACADEMICA));
        return $result->result_array();
    }
    
    function getDocentes_y_CantEvasByArea($idSede, $idArea) {
        $sql = "SELECT *
                  FROM (SELECT tab_a.id_persona,
                               CASE WHEN tab_b.cant_evas IS NOT NULL THEN tab_b.cant_evas ELSE 0 END AS cant_evas,
                               (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1 ,1)) FROM persona WHERE nid_persona = tab_a.id_persona) AS docente
                          FROM (SELECT id_persona
                        		  FROM rrhh.personal_detalle
                        		 WHERE id_sede_control    = ?
                        		   AND id_area_general    = ".ID_AREA_ACADEMICA."
                        		   AND id_area_especifica = ?
                        		UNION
                        		SELECT id_evaluado
                        		  FROM sped.evaluacion
                        		 WHERE estado_evaluacion = '".EJECUTADO."'
                        		   AND id_area           = ?
                        		   AND id_sede_evaluador = ?) AS tab_a LEFT JOIN (SELECT id_evaluado, COUNT(1) AS cant_evas
                        														    FROM sped.evaluacion
                        														   WHERE estado_evaluacion = '".EJECUTADO."'
                        														     AND id_area           = ?
                        														     AND id_sede_evaluador = ?
                                                                                 GROUP BY id_evaluado) AS tab_b ON (tab_a.id_persona = tab_b.id_evaluado)
                         ) AS tab
                ORDER BY tab.cant_evas DESC";
        $result = $this->db->query($sql, array($idSede, $idArea, $idArea, $idSede, $idArea, $idSede));
        return $result->result_array();
    }
    
    function getPromSubFLow($idSede, $idSubF) {
        $sql = "SELECT tb.nota_vigesimal AS nota_vigesimal,
        		       AVG(tb.nota_vigesimal) OVER (PARTITION BY 2) AS promedio,
        		       row_number() OVER(ORDER BY fecha_evaluacion) AS orden
        		  FROM (SELECT (tab.unwind->>'id_indicador')::integer AS id_subfactor,
        			       (tab.unwind->>'valor_vigesimal')::numeric AS nota_vigesimal,
        			       tab.fecha_evaluacion
        			  FROM (SELECT jsonb_array_elements(respuestas_jsonb->'respuestas') AS unwind,
        				       fecha_evaluacion
        				  FROM sped.evaluacion e
        				 WHERE estado_evaluacion = '".EJECUTADO."'
        				 AND ( (? <> 0 AND e.id_sede_evaluador = ?) OR (? = 0 AND 1 = 1) )
        				   ) AS tab ) AS tb
		 WHERE tb.id_subfactor = ?";
        $result = $this->db->query($sql, array($idSede, $idSede, $idSede, $idSubF));
        return $result->result_array();
    }
    
    function getDocentes_y_CantEvasByArea_Directivos($idArea) {
        $sql = "SELECT id_evaluado AS id_persona, 
                       COUNT(1) AS cant_evas,
                       (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1 ,1)) FROM persona WHERE nid_persona = id_evaluado) AS docente
    		      FROM sped.evaluacion
    		     WHERE estado_evaluacion = '".EJECUTADO."'
    		       AND id_area           = ?
                 GROUP BY id_evaluado
    		     ORDER BY cant_evas DESC";
        $result = $this->db->query($sql, array($idArea));
        return $result->result_array();
    }
    
    function getSubFactores_vs_docentes($idSede, $docentesIDS, $subFactoresIDS, $fecI, $fecF) {
        $this->db->escape($docentesIDS);
        $this->db->escape($subFactoresIDS);
        $sql = "SELECT tt.id_evaluado,
                       (SELECT CONCAT(SPLIT_PART(nom_persona,' ', 1),' ', TRIM(ape_pate_pers),' ', SUBSTRING(ape_mate_pers,1 ,1)) FROM persona WHERE nid_persona = tt.id_evaluado) AS docente,
                       (SELECT STRING_AGG(tab.valor_subf, ',')
                          FROM (SELECT tab_first.*
                                  FROM (SELECT ROUND(AVG(tab.valor), 2)::text as valor_subf--,tab.id_indicador
                            			  FROM (SELECT (tab.unwind->>'valor_vigesimal')::NUMERIC AS valor,
                                				       (tab.unwind->>'valor_indi')::NUMERIC      AS valor_indi,
                                				       (tab.unwind->>'id_criterio')::INTEGER     AS id_criterio,
                                				       (tab.unwind->>'id_indicador')::INTEGER    AS id_indicador,
                                				       (tab.unwind->>'desc_indicador')::TEXT     AS desc_indicador
                                				  FROM (SELECT jsonb_array_elements((respuestas_jsonb->>'respuestas')::jsonb) AS unwind
                                    					  FROM sped.evaluacion
                                    					 WHERE estado_evaluacion = '".EJECUTADO."'
                                    					   AND id_evaluado       = tt.id_evaluado
                                    					   AND ( ($idSede <> 0 AND id_sede_evaluador = $idSede) OR ($idSede = 0 AND 1 = 1) )
                                    					   AND fecha_evaluacion::date BETWEEN ? AND ?
                                				        ) AS tab
                                			    ) AS tab
                            			 WHERE tab.id_indicador IN ($subFactoresIDS)
                            			 GROUP BY tab.id_indicador
                            			 ORDER BY tab.id_indicador
                		        ) AS tab_first
                			UNION ALL
                			SELECT ROUND(AVG(tab.valor_subf::numeric), 2)::text
                			  FROM (SELECT ROUND(AVG(tab.valor), 2)::text as valor_subf,tab.id_indicador
                				      FROM (SELECT (tab.unwind->>'valor_vigesimal')::NUMERIC AS valor,
                        					       (tab.unwind->>'valor_indi')::NUMERIC      AS valor_indi,
                        					       (tab.unwind->>'id_criterio')::INTEGER     AS id_criterio,
                        					       (tab.unwind->>'id_indicador')::INTEGER    AS id_indicador,
                        					       (tab.unwind->>'desc_indicador')::TEXT     AS desc_indicador
                        					  FROM (SELECT jsonb_array_elements((respuestas_jsonb->>'respuestas')::jsonb) AS unwind
                            						  FROM sped.evaluacion
                            						 WHERE estado_evaluacion = '".EJECUTADO."'
                            						   AND id_evaluado       = tt.id_evaluado
                            						   AND ( ($idSede <> 0 AND id_sede_evaluador = $idSede) OR ($idSede = 0 AND 1 = 1) )
                            						   AND fecha_evaluacion::date BETWEEN ? AND ?
                        					        ) AS tab
                        					) AS tab
                    				WHERE tab.id_indicador IN ($subFactoresIDS)
                    				GROUP BY tab.id_indicador
                    				ORDER BY tab.id_indicador
                			       ) AS tab
                			) AS tab
                		) AS cants
                  FROM (SELECT id_evaluado
                	      FROM sped.evaluacion ee
                         WHERE ee.estado_evaluacion = '".EJECUTADO."'
                	       AND ( ($idSede <> 0 AND ee.id_sede_evaluador = $idSede) OR ($idSede = 0 AND 1 = 1) )
                	       AND ee.id_evaluado IN ($docentesIDS)
                	       AND fecha_evaluacion::date BETWEEN ? AND ?
                	    GROUP BY ee.id_evaluado 
                       ) AS tt";
        $result = $this->db->query($sql, array($fecI, $fecF, $fecI, $fecF, $fecI, $fecF));
        return $result->result_array();
    }
}