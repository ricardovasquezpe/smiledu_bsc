<?php
class M_boleta extends  CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    function sedeTrabajo($idPersona) {
    	$sql = "SELECT p.id_sede_trabajo
	              FROM persona p
	             WHERE p.nid_persona = ?";
    	$result = $this->db->query($sql, $idPersona);
    	return $result->row()->id_sede_trabajo;
    }
    
    function serieSede($sede) {
    	$sql = "SELECT nro_serie
                  FROM pagos.serie_x_sede
                 WHERE _id_sede  = ?";
    	$result = $this->db->query($sql, $sede);
    	if($result->num_rows() > 0){
    	    return $result->row()->nro_serie;
    	} else{
    	    return null;
    	}
    }
    
    function lastBoleta($serie) {
    	$sql = "SELECT min(nro_documento) as nro_documento
			      FROM pagos.documento
			     WHERE nro_serie = ?
			       AND estado    = '".ESTADO_CREADO."'
			        OR flg_anulado <> '1'";
    	$result = $this->db->query($sql, $serie);
    	return $result->row()->nro_documento;
    }
    
    function getBoletas($serie, $_id_sede) {
    	$sql = "(SELECT d.nro_documento, 
    			       to_char(m.fecha_pago, 'DD/MM/YYYY') as fecha_pago,
    			       to_char(d.fecha_registro, 'DD/MM/YYYY') as fecha_emision, 
		    		   dc.desc_detalle_crono, 
		    		   d.estado,
		    		   m._id_persona,
    	               CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto
				  FROM pagos.documento d,
				       pagos.movimiento m,
				       pagos.detalle_cronograma dc,
    	               public.persona p
				 WHERE d.tipo_documento                    = '".DOC_BOLETA."'
				   AND m.id_movimiento                     = d._id_movimiento
				   AND m._id_detalle_cronograma            = dc.id_detalle_cronograma
				   AND d.estado                            <> 'IMPRESO'
				   AND d.nro_serie                         = ?
				   AND d._id_sede                          = ?
				   AND m._id_persona                       = p.nid_persona)
				 UNION 
			   (SELECT d.nro_documento, 
    			       to_char(m.fecha_pago, 'DD/MM/YYYY') as fecha_pago,
    			       to_char(d.fecha_registro, 'DD/MM/YYYY') as fecha_emision, 
		    		   c.desc_concepto as desc_detalle_crono, 
		    		   d.estado,
		    		   m._id_persona,
    	               CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto
				  FROM pagos.documento d,
				       pagos.movimiento m,
				       pagos.concepto c,
    	               public.persona p
				 WHERE d.tipo_documento                    = '".DOC_BOLETA."'
				   AND m.id_movimiento                     = d._id_movimiento
				   AND m._id_concepto                      = 3
				   AND m._id_concepto                      = c.id_concepto
				   AND d.estado                            <> 'IMPRESO'
				   AND d.nro_serie                         = ?
				   AND d._id_sede                          = ?
				   AND m._id_persona                       = p.nid_persona)
		      ORDER BY nro_documento
				 LIMIT 100";
    	$result = $this->db->query($sql, array($serie, $_id_sede,$serie, $_id_sede));
    	return $result->result();
    } 
    
    function getBoletasCuotasIngreso($serie, $_id_sede) {
    	$sql = "SELECT d.nro_documento, 
    			       to_char(m.fecha_pago, 'DD/MM/YYYY') as fecha_pago,
    			       to_char(d.fecha_registro, 'DD/MM/YYYY') as fecha_emision, 
		    		   c.desc_concepto as desc_detalle_crono, 
		    		   d.estado,
		    		   m._id_persona,
    	               CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto
				  FROM pagos.documento d,
				       pagos.movimiento m,
				       pagos.concepto c,
    	               public.persona p
				 WHERE d.tipo_documento                    = '".DOC_BOLETA."'
				   AND m.id_movimiento                     = d._id_movimiento
				   AND m._id_concepto                      = 3
				   AND m._id_concepto                      = c.id_concepto
				   AND d.estado                            <> 'IMPRESO'
				   --AND (SELECT EXTRACT (MONTH FROM now())) = (SELECT EXTRACT (MONTH FROM d.fecha_registro))
				   AND d.nro_serie                         = ?
				   AND d._id_sede                          = ?
				   AND m._id_persona                       = p.nid_persona
    		  ORDER BY d.nro_documento";
    	$result = $this->db->query($sql, array($serie, $_id_sede));
    	return $result->result();
    }
    
    function getCompromisos($sede,$idCuota,$flg_boleta,$fechaInicio,$fechaFin) {
    	$sql="  SELECT m.id_movimiento, 
                	   m.monto, 
                	   m.estado, 
                	   to_char(m.fecha_pago,'DD/MM/YYYY') fecha_pago, 
                	   m._id_persona, 
                	   dc.desc_detalle_crono, 
                	   d.tipo_documento, 
                	   d._id_sede,
    	               CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto,
    	               CASE WHEN(p.foto_persona IS NULL) THEN 'nouser.svg'
                                                         ELSE p.foto_persona
    	               END AS foto_persona
				  FROM pagos.detalle_cronograma dc,
				       pagos.cronograma          c,
    	               public.persona            p,
				       pagos.movimiento          m
				       LEFT JOIN pagos.documento d ON(m.id_movimiento = d._id_movimiento AND d.tipo_documento        <> 'BOLETA')
				 WHERE m.tipo_movimiento        = 'INGRESO'
				   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
				   AND (CASE WHEN (m.estado = 'PAGADO')
				              THEN (m.estado = 'PAGADO' 
    	                          AND ((
                                       (SELECT COUNT(1) 
                                          FROM pagos.documento d2 
                                         WHERE d2._id_movimiento = m.id_movimiento
                                           AND d2.flg_anulado        <> '1'
                                           AND d2.tipo_documento = 'BOLETA') = 1 
                                  AND d.tipo_documento NOT IN('BOLETA','RECIBO')) OR ((SELECT COUNT(1) 
                                                                        								 FROM pagos.documento d2 
                                                                        								WHERE d2._id_movimiento = m.id_movimiento
                                                                        								  AND d2.flg_anulado        <> '1'
                                                                        								  AND d2.tipo_documento = 'BOLETA') <> 1)
                                  AND m.fecha_pago::date >= ? AND m.fecha_pago::date <= ? 
    	                          AND flg_boleta = '0'
    	                          AND '0' = '".$flg_boleta."'         			 
                                      )
				              )
				              ELSE/*((to_char(dc.fecha_vencimiento,'MM') = CASE WHEN(current_date > to_date(CONCAT(to_char(current_date,'YYYY-MM'),'-15'), 'YYYY-MM-DD'))
																			  THEN to_char(current_date,'MM')
																			  ELSE to_char((to_date(to_char(current_date,'MM'),'MM')-1),'MM')
																		 END) AND ( (SELECT COUNT(1) 
                                												       FROM pagos.documento d2 
                                												      WHERE d2._id_movimiento = m.id_movimiento
                                												        AND d2.flg_anulado        <> '1'
                                												        AND d2.tipo_documento = 'BOLETA') <> 1) )*/ 
                                                                        	  (dc.id_detalle_cronograma = ?
                                                                          AND (SELECT COUNT(1) 
                                                                        	     FROM pagos.documento d2 
                                                                        		WHERE d2._id_movimiento = m.id_movimiento
                                                                        		  AND d2.flg_anulado        <> '1'
                                                                        		  AND d2.tipo_documento = 'BOLETA') <> 1 AND '1' = '".$flg_boleta."')
				         END)
				    AND dc._id_cronograma       = c.id_cronograma
				    AND c._id_sede              = ?
                    --AND c.year                  = (SELECT EXTRACT(YEAR FROM now()))
    			    AND m._id_persona           = p.nid_persona
                    AND m.estado               <> 'ANULADO'
			   GROUP BY m.id_movimiento, dc.desc_detalle_crono, d.tipo_documento, d._id_sede, p.ape_pate_pers, p.ape_mate_pers, p.nom_persona, p.foto_persona
                    UNION 

                    (
                    (SELECT m.id_movimiento, 
                	 m.monto, 
                	 m.estado, 
                	 to_char(m.fecha_pago,'DD/MM/YYYY') fecha_pago, 
                	 m._id_persona,
                	 c.desc_concepto as desc_detalle_crono, 
                	 d.tipo_documento, 
                	 d._id_sede,
    	             CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto,
    	             CASE WHEN(p.foto_persona IS NULL) 
    	                  THEN 'nouser.svg'
                          ELSE p.foto_persona
    	             END AS foto_persona
			    FROM public.persona p,
                     sima.detalle_alumno da,
				     pagos.concepto c,
				     pagos.movimiento m
		   LEFT JOIN pagos.documento d ON(m.id_movimiento = d._id_movimiento )
			   WHERE m.tipo_movimiento = 'INGRESO'
				 AND m._id_concepto = 3
				 AND m._id_concepto = c.id_concepto
				 AND (CASE WHEN (m.estado = 'PAGADO')
                           THEN (m.estado = 'PAGADO' 
                               AND ((
                    	       (SELECT COUNT(1) 
                    	          FROM pagos.documento d2 
                    	         WHERE d2._id_movimiento = m.id_movimiento
                    	           AND d2.flg_anulado    <> '1'
                    	           AND d2.tipo_documento = 'BOLETA') = 1 
                    	           AND d.tipo_documento NOT IN('BOLETA','RECIBO')) 
                    	   OR ((SELECT COUNT(1) 
                    		      FROM pagos.documento d2 
                    		     WHERE d2._id_movimiento = m.id_movimiento
                    		       AND d2.flg_anulado   <> '1'
                    		       AND d2.tipo_documento = 'BOLETA') <> 1))
                          AND m.fecha_pago::date >= ? AND m.fecha_pago::date <= ?
                          AND '0' = '".$flg_boleta."'
                          AND flg_boleta = '0'
    	                   ) 
            			   ELSE((to_char(m.fecha_vencimiento_aux,'MM') = CASE WHEN(current_date::date > to_date(CONCAT(to_char(current_date::date,'YYYY-MM'),'-15'), 'YYYY-MM-DD'))
                                            								  THEN to_char(current_date,'MM')
                                            								  ELSE to_char((to_date(to_char(current_date::date,'MM'),'MM')-1),'MM')
            							                                 END) AND (SELECT COUNT(1) 
                            													     FROM pagos.documento d2 
                            												        WHERE d2._id_movimiento = m.id_movimiento
                            													      AND d2.flg_anulado        <> '1'
                            													      AND d2.tipo_documento = 'BOLETA') <> 1 AND '1' = '".$flg_boleta."')
            		     END)
        	     AND da.id_sede_ingreso = ?
    	         AND da.nid_persona     = p.nid_persona
    			 AND m._id_persona = p.nid_persona
			GROUP BY m.id_movimiento, d.tipo_documento, d._id_sede, p.ape_pate_pers, p.ape_mate_pers, p.nom_persona, c.desc_concepto,foto_persona) )
			   ORDER BY fecha_pago,nombrecompleto";
    	$result = $this->db->query($sql, array($fechaInicio,$fechaFin,$idCuota,$sede,$fechaInicio,$fechaFin,$sede));
    	return $result->result();
    }
    
    function getCuotaIngreso($sede,$idCuota) {
    	$sql="SELECT m.id_movimiento, 
                	 m.monto, 
                	 m.estado, 
                	 to_char(m.fecha_pago,'DD/MM/YYYY') fecha_pago, 
                	 m._id_persona,
    	             CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto,
    	             c.desc_concepto as desc_detalle_crono, 
                	 d.tipo_documento, 
                	 d._id_sede,
    	             CASE WHEN(p.foto_persona IS NULL) 
    	                  THEN 'nouser.svg'
                          ELSE p.foto_persona
    	             END AS foto_persona
			    FROM public.persona p,
                     sima.detalle_alumno da,
				     pagos.concepto c,
				     pagos.movimiento m
		   LEFT JOIN pagos.documento d ON(m.id_movimiento = d._id_movimiento )
			   WHERE m.tipo_movimiento = 'INGRESO'
				 AND m._id_concepto = 3
				 AND m._id_concepto = c.id_concepto
				 AND (CASE WHEN (m.estado = 'PAGADO')
                           THEN (m.estado = 'PAGADO' 
                               AND ((
                    	       (SELECT COUNT(1) 
                    	          FROM pagos.documento d2 
                    	         WHERE d2._id_movimiento = m.id_movimiento
                    	           AND d2.flg_anulado    <> '1'
                    	           AND d2.tipo_documento = 'BOLETA') = 1 
                    	           AND d.tipo_documento NOT IN('BOLETA','RECIBO')) 
                    	   OR ((SELECT COUNT(1) 
                    		      FROM pagos.documento d2 
                    		     WHERE d2._id_movimiento = m.id_movimiento
                    		       AND d2.flg_anulado   <> '1'
                    		       AND d2.tipo_documento = 'BOLETA') <> 1))
                          AND CONCAT((SELECT EXTRACT(MONTH FROM m.fecha_pago)),'-',(SELECT EXTRACT(YEAR FROM m.fecha_pago))) = (SELECT CONCAT((SELECT EXTRACT(MONTH FROM fecha_vencimiento)),'-',(SELECT EXTRACT(YEAR FROM fecha_vencimiento)) )
                                                        																	      FROM pagos.detalle_cronograma
                                                        																	     WHERE id_detalle_cronograma = ?)         			  
    	                   ) 
            			   ELSE((to_char(m.fecha_vencimiento_aux,'MM') = CASE WHEN(current_date::date > to_date(CONCAT(to_char(current_date::date,'YYYY-MM'),'-15'), 'YYYY-MM-DD'))
                                            								  THEN to_char(current_date,'MM')
                                            								  ELSE to_char((to_date(to_char(current_date::date,'MM'),'MM')-1),'MM')
            							                                 END) AND (SELECT COUNT(1) 
                            													     FROM pagos.documento d2 
                            												        WHERE d2._id_movimiento = m.id_movimiento
                            													      AND d2.flg_anulado        <> '1'
                            													      AND d2.tipo_documento = 'BOLETA') <> 1 )
            		     END)
        	     AND da.id_sede_ingreso = ?
    	         AND da.nid_persona     = p.nid_persona
    			 AND m._id_persona = p.nid_persona
			GROUP BY m.id_movimiento, d.tipo_documento, d._id_sede, p.ape_pate_pers, p.ape_mate_pers, p.nom_persona, c.desc_concepto,foto_persona
		    ORDER BY m.id_movimiento, m.estado";
    	$result = $this->db->query($sql, array($idCuota,$sede));
    	return $result->result();
    }
    
    function getBoletasPrint($boleta, $serie) {
    	$sql = "((SELECT d._id_movimiento, 
    	               d.tipo_documento,
                       CONCAT(d.nro_serie,'-',d.nro_documento) as nro_documento,
    	               CONCAT(to_char(m.fecha_pago,'DD-MM-YY'),' ',(SELECT usuario
                                								      FROM persona 
                                								     WHERE nid_persona = (SELECT id_pers_regi
                                								                            FROM pagos.audi_movimiento
                                								                           WHERE _id_movimiento = 1522
                                								                             AND accion = 'PAGAR'
                                								                        ORDER BY audi_fec_regi DESC 
                                								                       LIMIT 1))) as info_pago,
                       d.nro_documento as documento,
                       d.nro_serie,
                       d.estado,
    	               CASE WHEN(m.fecha_pago::timestamp::date <= dc.fecha_descuento) 
                            THEN '1'
                            ELSE '0'
                       END AS flg_descuento,
                       (SELECT COUNT(1) + 1 as cuenta
            			  FROM pagos.audi_documento
            			 WHERE _id_movimiento = m.id_movimiento
            			   AND tipo_documento = 'BOLETA') num_corre,
                       d.fecha_registro as fecha_emision, 
                       dc.desc_detalle_crono, 
                       m._id_persona, 
                       m.descuento_acumulado,
                       m.monto,
                       m.mora_acumulada,
                       m.monto_final,
                       to_char(d.fecha_registro, 'YYYY') as year,
                       d._id_sede,
                       (CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona))) as nombrecompleto,
                       (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado, ' ', a.desc_aula)) as ubicacion
                  FROM pagos.documento           d,
                       pagos.movimiento          m,
                       persona                   p,
                       pagos.detalle_cronograma dc,
                       persona_x_aula           pa,
                       aula                      a,
                       grado                     g,
                       nivel                     n,
                       sede                      s,
    	               sima.detalle_alumno      da
                 WHERE d.tipo_documento                    = '".DOC_BOLETA."'
                   AND d.nro_serie                         = ?
                   AND d.nro_documento                     IN ?
                   AND d.estado                            <> 'IMPRESO'
                   AND da.estado                           = 'MATRICULADO'
                   AND pa.year_academico                   = (SELECT year_academico 
                                						        FROM persona_x_aula
                                						       WHERE __id_persona = p.nid_persona
                                						    ORDER BY year_academico DESC 
                                						       LIMIT 1)
                   AND p.nid_persona                       = m._id_persona
                   AND pa.__id_persona                     = p.nid_persona
                   AND pa.__id_aula                        = a.nid_aula
                   AND a.nid_grado                         = g.nid_grado
                   AND a.nid_nivel                         = n.nid_nivel
                   AND a.nid_sede                          = s.nid_sede
                   AND m.id_movimiento                     = d._id_movimiento
                   AND m._id_detalle_cronograma            = dc.id_detalle_cronograma
                   AND da.nid_persona                      = p.nid_persona)
                  UNION
               (SELECT d._id_movimiento, 
                       d.tipo_documento,
                       CONCAT(d.nro_serie,'-',d.nro_documento) as nro_documento,
                       CONCAT(to_char(m.fecha_pago,'DD-MM-YY'),' ',(SELECT usuario
                                								      FROM persona 
                                								     WHERE nid_persona = (SELECT id_pers_regi
                                								                            FROM pagos.audi_movimiento
                                								                           WHERE _id_movimiento = 1522
                                								                             AND accion = 'PAGAR'
                                								                        ORDER BY audi_fec_regi DESC 
                                								                           LIMIT 1))) as info_pago,
                       d.nro_documento as documento,
                       d.nro_serie,
                       d.estado,
                       CASE WHEN(m.fecha_pago::timestamp::date <= dc.fecha_descuento) 
                            THEN '1'
                            ELSE '0'
                       END AS flg_descuento,
                       (SELECT COUNT(1) + 1 as cuenta
            			  FROM pagos.audi_documento
            			 WHERE _id_movimiento = m.id_movimiento
            			   AND tipo_documento = 'BOLETA') num_corre,
                       d.fecha_registro as fecha_emision, 
                       dc.desc_detalle_crono, 
                       m._id_persona, 
                       m.descuento_acumulado,
                       m.monto,
                       m.mora_acumulada,
                       m.monto_final,
                       to_char(d.fecha_registro, 'YYYY') as year,
                       d._id_sede,
                       (CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona))) as nombrecompleto,
                       (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado, ' ', '')) as ubicacion
                 FROM pagos.documento      d, 
                      public.persona       p,
                      sima.detalle_alumno da,
                      public.grado         g,
                      public.nivel         n,
                      public.sede          s,
                      pagos.concepto       c,
                      pagos.movimiento     m
                      LEFT JOIN pagos.detalle_cronograma dc ON(m._id_detalle_cronograma = dc.id_detalle_cronograma)
                WHERE d.tipo_documento                        = 'BOLETA'
                  AND c.id_concepto                           = m._id_concepto
                  AND p.nid_persona                           = m._id_persona
                  AND da.id_grado_ingreso                     = g.nid_grado
                  AND da.id_nivel_ingreso                     = n.nid_nivel
                  AND da.id_sede_ingreso                      = s.nid_sede
                  AND m.id_movimiento                         = d._id_movimiento
                  AND d.estado                                <> 'IMPRESO'
                  AND da.nid_persona                          = p.nid_persona
                  AND da.estado                               IN ('REGISTRADO','PROM_PREREGISTRO','PROM_REGISTRO','PREREGISTRO','MATRICULABLE','RETIRADO')
                  AND d.nro_serie                             = ?
                  AND d.nro_documento                         IN ?))
                UNION 
                  (SELECT d._id_movimiento,
    	               d.tipo_documento,
    			       CONCAT(d.nro_serie,'-',d.nro_documento) as nro_documento,
    			       d.nro_documento as documento,
    				   d.nro_serie,
    			       d.estado,
    			       (SELECT COUNT(1) + 1 as cuenta
            			  FROM pagos.audi_documento
            			 WHERE _id_movimiento = m.id_movimiento
            			   AND tipo_documento = 'BOLETA') num_corre,
		    		   d.fecha_registro as fecha_emision,
		    		   c.desc_concepto as desc_detalle_crono,
		    		   m._id_persona,
		    		   m.descuento_acumulado,
    				   m.monto,
    			       m.mora_acumulada,
    			       m.monto_final,
    		           to_char(d.fecha_registro, 'YYYY') as year,
	  		           d._id_sede,
    	               (CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona))) as nombrecompleto,
				       (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado, ' ', a.desc_aula)) as ubicacion
    	          FROM pagos.documento      d,
				       pagos.movimiento     m,
    	               persona              p,
				       pagos.concepto       c,
    	               persona_x_aula      pa,
	                   aula                 a,
	                   grado                g,
	                   nivel                n,
	                   sede                 s,
    	               sima.detalle_alumno da
				 WHERE d.tipo_documento                    = '".DOC_BOLETA."'
				   AND p.nid_persona                       = m._id_persona
				   AND da.estado                           = 'MATRICULADO'
	               AND pa.__id_persona                     = p.nid_persona
	               AND pa.__id_aula                        = a.nid_aula
				   AND pa.year_academico                   = (SELECT year_academico 
                                						        FROM persona_x_aula
                                						       WHERE __id_persona = p.nid_persona
                                						    ORDER BY year_academico DESC 
                                						       LIMIT 1)
			       AND a.year                              = cast(to_char(d.fecha_registro, 'YYYY') as int)
	               AND a.nid_grado                         = g.nid_grado
	               AND a.nid_nivel                         = n.nid_nivel
	               AND a.nid_sede                          = s.nid_sede
			       AND m.id_movimiento                     = d._id_movimiento
			       AND m._id_concepto                      = 3
				   AND m._id_concepto                      = c.id_concepto
				   AND da.nid_persona                      = p.nid_persona
    			   AND d.estado                            <> '".DOC_IMPRESO."'
    		       AND d.nro_serie                         = ?
    		       AND (SELECT EXTRACT (MONTH FROM now())) = (SELECT EXTRACT (MONTH FROM d.fecha_registro))
    		       AND d.nro_documento                     IN ?
    		     UNION (
    			SELECT d._id_movimiento, 
                       d.tipo_documento,
                       CONCAT(d.nro_serie,'-',d.nro_documento) as nro_documento,
                       d.nro_documento as documento,
                       d.nro_serie,
                       d.estado,
                       (SELECT COUNT(1) + 1 as cuenta
            			  FROM pagos.audi_documento
            			 WHERE _id_movimiento = m.id_movimiento
            			   AND tipo_documento = 'BOLETA') num_corre,
                       d.fecha_registro as fecha_emision, 
                       c.desc_concepto,
                       m._id_persona, 
                       m.descuento_acumulado,
                       m.monto,
                       m.mora_acumulada,
                       m.monto_final,
                       to_char(d.fecha_registro, 'YYYY') as year,
                       d._id_sede,
                       (CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona))) as nombrecompleto,
                       (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado, ' ', '')) as ubicacion
                  FROM pagos.documento      d, 
                       public.persona       p,
                       sima.detalle_alumno da,
                       public.grado         g,
                       public.nivel         n,
                       public.sede          s,
                       pagos.concepto       c,
                       pagos.movimiento     m
                 WHERE d.tipo_documento                        = 'BOLETA'
                   AND c.id_concepto                           = m._id_concepto
                   AND p.nid_persona                           = m._id_persona
                   AND da.id_grado_ingreso                     = g.nid_grado
                   AND da.id_nivel_ingreso                     = n.nid_nivel
                   AND da.id_sede_ingreso                      = s.nid_sede
                   AND m.id_movimiento                         = d._id_movimiento
                   AND m._id_concepto                          = 3
                   AND d.estado                                <> 'IMPRESO'
                   AND da.nid_persona                          = p.nid_persona
                   AND da.estado                               IN ('REGISTRADO','PROM_PREREGISTRO','PROM_REGISTRO','PREREGISTRO','MATRICULABLE','RETIRADO')
                   AND d.nro_serie                             = ?
                   AND d.nro_documento                         IN ?))
              ORDER BY nro_documento";
    	$result = $this->db->query($sql, array($serie, $boleta,$serie, $boleta,$serie, $boleta,$serie, $boleta));
    	return $result->result();
    }
    
    function getBoletasCuotasIngresoPrint($serie, $boletas) {
    	$sql = "";
    	$result = $this->db->query($sql, array($serie, $boletas,$serie, $boletas));
    	return $result->result();
    }
    
    function actualizarBoletas($updateBoleta, $nro_serie, $insertAudiDoc) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$this->db->trans_begin();
    	try{
    		$this->db->where('nro_serie',$nro_serie);
    		$this->db->update_batch('pagos.documento', $updateBoleta, 'nro_documento');
    		$this->db->affected_rows();
    		if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($updateBoleta)) { //
    			throw new Exception('No se pudo actualizar los registros');
    		}
    		$this->db->insert_batch('pagos.audi_documento', $insertAudiDoc);
    		$this->db->affected_rows();
    		if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($insertAudiDoc)) { //
    			throw new Exception('No se pudo registrar');
    		}
    		$data['error'] = EXIT_SUCCESS;
    		$data['msj']   = "Actualizacion Exitosa";
    		$this->db->trans_commit();
    	}catch (Exception $e){
    		$data['msj']   = $e->getMessage();
    		$this->db->trans_rollback();
    	}
    	return $data;
    }
    
    function getPersona($idPersona) {
    	$sql = "SELECT CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona)) as nombrecompleto
	              FROM persona p
	             WHERE p.nid_persona = ?";
    	$result = $this->db->query($sql, $idPersona);
    	return $result->row()->nombrecompleto;
    }
    
    function getPersonaUbicacion($idPersona, $year) {
    	$sql = "SELECT (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado, ' ', a.desc_aula)) as ubicacion
	              FROM persona p,
	                   persona_x_aula pa,
	                   aula a,
	                   grado g,
	                   nivel n,
	                   sede s
	             WHERE p.nid_persona   = ?
	               AND pa.__id_persona = p.nid_persona
	               AND pa.__id_aula    = a.nid_aula
	               AND a.year          = ?
	               AND a.nid_grado     = g.nid_grado
	               AND a.nid_nivel     = n.nid_nivel
	               AND a.nid_sede      = s.nid_sede";
    	$result = $this->db->query($sql, array($idPersona, $year));
    	return $result->row()->ubicacion;
    }
    
    function registrarBoletasByCompromisos($array,$arrayUpdateCorrelativo,$arrayUpdateMovi) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$this->db->trans_begin();
    	try {
    	    $val = 0;
    	    foreach($array as $row){
    	        $this->db->insert('pagos.documento',$row);
    	        if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) { //
    	            throw new Exception('No se pudo registrar');
    	        }
    	        $val++;
    	    }
    	    if ($this->db->trans_status() === FALSE  || $val != count($array)) { //
    	        throw new Exception('No se pudo registrar');
    	    }
    		if($arrayUpdateCorrelativo['accion'] == INSERTA){
    			unset($arrayUpdateCorrelativo['accion']);
    			$this->db->insert('pagos.correlativo',$arrayUpdateCorrelativo);
    		} else{
    			$this->db->where('tipo_movimiento' , $arrayUpdateCorrelativo['tipo_movimiento']);
    			$this->db->where('_id_sede'        , $arrayUpdateCorrelativo['_id_sede']);
    			$this->db->where('tipo_documento'  , $arrayUpdateCorrelativo['tipo_documento']);
    			$this->db->where('nro_serie'  , $arrayUpdateCorrelativo['nro_serie']);
    			unset($arrayUpdateCorrelativo['accion']);
    			unset($arrayUpdateCorrelativo['nro_serie']);
    			unset($arrayUpdateCorrelativo['_id_sede']);
    			unset($arrayUpdateCorrelativo['tipo_documento']);
    			unset($arrayUpdateCorrelativo['tipo_movimiento']);
    			$this->db->update('pagos.correlativo',$arrayUpdateCorrelativo);
    		}
    		$val = 0;
    		foreach($arrayUpdateMovi as $row){
    		    $this->db->where('id_movimiento',$row['id_movimiento']);
    		    $this->db->update('pagos.movimiento',array('flg_boleta' => '1'));
    		    if ($this->db->trans_status() === FALSE ){
    		        throw new Exception(ANP);
    		    }
    		    $val++;
    		}
    		if ($this->db->trans_status() === FALSE  || $val != count($arrayUpdateMovi)) { //
    		    throw new Exception('No se pudo registrar');
    		}
    		$data['error'] = EXIT_SUCCESS;
    		$data['msj']   = 'Se genero todos las boletas';
    		$this->db->trans_commit();
    	} catch(Exception $e){
    		$data['msj'] = $e->getMessage();
    		$this->db->trans_rollback();
    	}
    	return $data;
    }
    
    function getHistorialDocumentos($idSede,$fechaInicio,$fechaFin){
        $sql = "SELECT row_number() OVER() rownum,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) nombre_completo,
                       to_char(d.fecha_registro,'DD/MM/YYYY') fecha_registro,
                       d.flg_anulado,
                       d.estado,
                       CONCAT(d.nro_serie,'-',d.nro_documento) correlativo,
                       to_char(m.fecha_pago,'DD/MM/YYYY') fecha_pago,
                       CASE WHEN m._id_concepto = ".CUOTA_INGRESO."  
                            THEN 'Cuota Ingreso'
                            ELSE dc.desc_detalle_crono
                       END AS detalle,
                       p.foto_persona
                  FROM pagos.documento  d,
                       persona          p,
                       pagos.movimiento m
                       LEFT JOIN pagos.detalle_cronograma dc ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
                 WHERE d._id_sede       = ?
                   AND m.id_movimiento  = d._id_movimiento
                   AND d.tipo_documento = '".DOC_BOLETA."'
                   AND p.nid_persona    = m._id_persona
                   AND d.fecha_registro <= COALESCE(?,d.fecha_registro)
                   AND d.fecha_registro >= COALESCE(?,d.fecha_registro)
              ORDER BY correlativo desc";
        $result = $this->db->query($sql,array($idSede,$fechaFin,$fechaInicio));
        return $result->result();
    }
    
    function getHistoricoCorrelativos($idSede){
        $sql = "SELECT tipo_documento,
                       CASE WHEN(tipo_movimiento = '".MOV_EGRESO."'  AND tipo_documento = '".DOC_RECIBO."') THEN CONCAT(tipo_documento, ' (Tickets)')
                            ELSE tipo_documento
                       END AS tipo_documento,
                       numero_correlativo
                  FROM pagos.correlativo 
                 WHERE _id_sede = ?";
        $result = $this->db->query($sql,array($idSede));
        return $result->result();
    }
}