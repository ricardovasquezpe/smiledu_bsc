<?php

class M_pagos extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getFamilia($idPersona) {
        $sql="SELECT cod_familia
                FROM persona
               WHERE nid_persona = ?";
        $result = $this->db->query($sql, array($idPersona));
        return $result->row()->cod_familia;
    } 
    
    function getHijos($idFamilia, $idPersona) {
        $sql="SELECT nom_persona,
                     ape_pate_pers,
                     ape_mate_pers,
                     cod_alumno
                FROM persona
               WHERE cod_familia = ?
                 AND nid_persona <> ?";
        $result = $this->db->query($sql, array($idFamilia, $idPersona));
        return $result->result();
    }
    
    function getNombresParentescoByPersona($cod_familiar, $idPersona) {
        $sql = "(SELECT p.nid_persona,
                        p.nom_persona,
                        da.cod_familia,
                        p.foto_persona,
                        INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ', p.nom_persona)) as nombrecompleto
                   FROM persona              p,
                        aula                 a,
                        persona_x_aula      pa,
                        sima.detalle_alumno da
                  WHERE da.cod_familia  = COALESCE(?,(SELECT cod_familiar
                     					     FROM sima.familiar_x_familia
                    					    WHERE id_familiar = ?))
                    AND a.nid_aula      = pa.__id_aula
                    AND da.nid_persona  = p.nid_persona
                    AND pa.__id_persona = p.nid_persona)
                  UNION 
                 SELECT p.nid_persona,
                        p.nom_persona,
                        da.cod_familia,
                        p.foto_persona,
                        INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ', p.nom_persona)) as nombrecompleto
                   FROM persona              p,
                        sima.detalle_alumno da
                  WHERE da.cod_familia  = COALESCE(?,(SELECT cod_familiar
                       				         FROM sima.familiar_x_familia
                      				        WHERE id_familiar = ?))
                    AND da.nid_persona  = p.nid_persona
                    AND da.estado       IN ('".ALUMNO_PREREGISTRO."','".ALUMNO_REGISTRADO."','".ALUMNO_MATRICULABLE."')";
        $result = $this->db->query($sql,array($cod_familiar,$idPersona,$cod_familiar, $idPersona));
        return $result->result();
    }
    
    function getAllCompromisosByAlumno($idAlumno,$year) {
        $sql = "SELECT m.id_movimiento,
                       m.monto,
                       m.estado,
        		       m._id_concepto,
                       CASE WHEN(m._id_concepto = 1) 
        		            THEN dc.desc_detalle_crono
                			ELSE c.desc_concepto 
                       END AS desc_cuota,
                       CASE WHEN(m._id_detalle_cronograma IS NOT NULL) 
        		            THEN to_char(dc.fecha_vencimiento, 'DD/MM/YYYY') 
                			ELSE '-' 
                       END AS fec_vencimiento,
                       CASE WHEN(m.fecha_pago IS NOT NULL OR m._id_concepto ='".CUOTA_INGRESO."') 
                            THEN to_char(m.fecha_pago, 'DD/MM/YYYY') 
                            ELSE '-' 
                       END AS fecha_pago,
                       CASE WHEN(m.estado = '".ESTADO_PAGADO."')    THEN 'default' 
                            WHEN(m.estado = '".ESTADO_POR_PAGAR."') THEN 'success'
                            WHEN(m.estado = '".ESTADO_VENCIDO."')   THEN 'danger'
                	        ELSE ''		             
                       END AS class,
                       m.monto_final,
			           CASE WHEN(m.estado = '".ESTADO_PAGADO."')    THEN m.monto_final
                            WHEN(m.estado = '".ESTADO_POR_PAGAR."') THEN CASE WHEN(m.monto_adelanto = 0) THEN (m.monto_adelanto)
                                                                              WHEN(m.monto_adelanto <> 0) THEN (m.monto_final-monto_adelanto)
                                                                              ELSE 0
                                                                         END
                             WHEN(m.estado = '".ESTADO_VENCIDO."')   THEN 0.00
                	         ELSE 0		             
                       END AS monto_adelanto,
			           CASE WHEN(m.estado = '".ESTADO_PAGADO."')    THEN 0.00
                            WHEN(m.estado = '".ESTADO_POR_PAGAR."') THEN (m.monto_final-monto_adelanto)
                            WHEN(m.estado = '".ESTADO_VENCIDO."')   THEN m.monto_final
                	        ELSE 0		             
                       END AS monto_pendiente,
                       m.mora_acumulada
                  FROM pagos.detalle_cronograma dc,
                       pagos.concepto c,
                       pagos.movimiento m,
                       pagos.cronograma cr
                 WHERE _id_persona = ?
                   AND CASE WHEN (m._id_detalle_cronograma IS NOT NULL AND m._id_concepto = ".CONCEPTO_SERV_ESCOLAR.")  
                	        THEN dc.id_detalle_cronograma = m._id_detalle_cronograma AND m._id_concepto = c.id_concepto
                	        ELSE c.id_concepto = m._id_concepto 
                       END
                   AND m.estado <> '".ESTADO_ANULADO."'
                   AND cr.id_cronograma = dc._id_cronograma
                   AND cr.year          = ?
                   AND CASE WHEN(m._id_concepto = '".CUOTA_INGRESO."' AND ((SELECT COUNT(1)
                                                                              FROM persona_x_aula 
                                                                             WHERE __id_persona = ?) > 0) ) THEN (SELECT year_academico
	                                                                                                                FROM persona_x_aula
	                                                                                                               WHERE __id_persona = ?
	                                                                                                            ORDER BY year_academico DESC
	                                                                                                               LIMIT 1) = ? 
    	                    WHEN(m._id_concepto = '".CUOTA_INGRESO."' AND ((SELECT COUNT(1)
                                                                              FROM persona_x_aula 
                                                                             WHERE __id_persona = ?) = 0)) THEN ((SELECT year_ingreso
    										                                                                            FROM sima.detalle_alumno
    									                                                                               WHERE nid_persona = ?
    										                                                                             AND estado IN('REGISTRO','PREREGISTRO','MATRICULABLE')) = ?)
    	                    ELSE 1 = 1
    		       END 
	               AND cr.estado = '".FLG_ESTADO_ACTIVO."'
	               AND c.id_concepto IN(".CONCEPTO_SERV_ESCOLAR.",".CUOTA_INGRESO.")
              GROUP BY m.id_movimiento,m.monto,m.estado,m.fecha_pago,desc_cuota,m._id_concepto,fec_vencimiento, fecha_pago
              ORDER BY id_movimiento";
        $result = $this->db->query($sql,array($idAlumno,$year,$idAlumno,$idAlumno,$year,$idAlumno,$idAlumno,$year));
        return $result->result();
    }
    
    function getAllFechasVencimiento($idAlumno) {
    	$sql = "SELECT DISTINCT DC.fecha_vencimiento,
    	               dc.desc_detalle_crono,
    	               cr.estado,
    	               cr.id_cronograma
			      FROM pagos.detalle_cronograma dc,
					   pagos.concepto c,
					   pagos.movimiento m,
					   pagos.cronograma cr
			     WHERE m._id_persona            = ?
			       AND m._id_concepto           = ".CONCEPTO_SERV_ESCOLAR."
			       AND dc.id_detalle_cronograma = m._id_detalle_cronograma 
			       AND m.estado                 <> '".ESTADO_ANULADO."'
			       AND dc.fecha_vencimiento     IS NOT NULL
			       AND cr.id_cronograma         = dc._id_cronograma
			       AND cr.estado                = '".FLG_ESTADO_ACTIVO."'
			    ORDER BY DC.fecha_vencimiento";
    	$result = $this->db->query($sql,array($idAlumno));
    	return $result->result();
    }
    
    function getAllFechasPagos($idAlumno) {
    	$sql = "SELECT m.fecha_pago,
    	               dc.desc_detalle_crono
				  FROM pagos.movimiento m,
				       pagos.detalle_cronograma dc,
    			       pagos.cronograma cr 
				 WHERE _id_persona              = ?
				   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
				   AND m.estado                 = '".ESTADO_PAGADO."' 
				   AND m.fecha_pago             IS NOT NULL
				   AND cr.id_cronograma         = dc._id_cronograma
			       AND cr.estado                = '".FLG_ESTADO_ACTIVO."'
		      ORDER BY m.fecha_pago";
    	$result = $this->db->query($sql,array($idAlumno));
    	return $result->result();
    }
    
    function getAllFechasDescuento($idAlumno) {
    	$sql = "SELECT DISTINCT dc.fecha_descuento,
    	               dc.desc_detalle_crono,
    	               concat(to_char(dc.fecha_descuento, 'YYYY-MM'),'-01') as fechaInicio
			      FROM pagos.detalle_cronograma dc,
			           pagos.concepto c,
			           pagos.movimiento m,
			           pagos.cronograma cr
			     WHERE _id_persona              = ?
			       AND m._id_concepto           = ".CONCEPTO_SERV_ESCOLAR."
			       AND dc.id_detalle_cronograma = m._id_detalle_cronograma 
			       AND m.estado                 <> '".ESTADO_ANULADO."'
			       AND dc.fecha_descuento       IS NOT NULL
			       AND cr.id_cronograma         = dc._id_cronograma
			       AND cr.estado                = '".FLG_ESTADO_ACTIVO."'
			  ORDER BY dc.fecha_descuento";
    	$result = $this->db->query($sql,array($idAlumno));
    	return $result->result();
    }
}