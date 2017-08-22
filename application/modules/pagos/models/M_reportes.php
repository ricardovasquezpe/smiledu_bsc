<?php

class M_reportes extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
	function getPencionesPagadas($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula) {
		$sql = "SELECT m.estado,
		               d.fecha_vencimiento,
		               d.fecha_descuento, m.id_movimiento,
					   CASE WHEN (m.fecha_pago < d.fecha_descuento) THEN 'PRONTO PAGO'
			                WHEN (m.fecha_pago BETWEEN d.fecha_descuento AND d.fecha_vencimiento) THEN 'PAGO NORMAL'
			                WHEN (m.fecha_pago > d.fecha_vencimiento) THEN 'PAGO MOROSO'
			                ELSE 'other'
			       	   END AS estado,
			           m.monto,
			       	   m.descuento_acumulado,
			       	   m.mora_acumulada,
				       m._id_persona
			      FROM pagos.movimiento m,
			           pagos.detalle_cronograma d
                 WHERE m._id_detalle_cronograma = d.id_detalle_cronograma
                   AND m.tipo_movimiento        = '".TIPO_INGRESO."'
			       AND (d.fecha_vencimiento >= ? AND d.fecha_vencimiento <= ?)
			       AND d.flg_tipo               = '".FLG_CUOTA."'
				   AND m._id_persona IN (SELECT p.nid_persona
                                		   FROM persona         p,
                                		        persona_x_aula pa,
                                		        aula            a,
                                		        sede            s,
                                		        grado           g,
                                		        nivel           n
                                		  WHERE a.year = (SELECT EXTRACT(YEAR FROM now()))
                                		    AND s.nid_sede  = COALESCE(?, s.nid_sede)
                                		    AND n.nid_nivel = COALESCE(?, n.nid_nivel)
                                		    AND g.nid_grado = COALESCE(?, g.nid_grado)
                                		    AND a.nid_aula  = COALESCE(?, a.nid_aula)
                                		    --AND p.flg_acti = '1'
                                		    AND pa.__id_persona = p.nid_persona
                                		    AND a.nid_aula      = pa.__id_aula
                                		    AND a.nid_sede      = s.nid_sede
                                		    AND a.nid_grado     = g.nid_grado
                                		    AND a.nid_nivel     = n.nid_nivel
                                       ORDER BY nid_persona)";
		$result = $this->db->query($sql, array($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula));
	    return $result->result();
	}
	
	function getPensionesVencidas($fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $idAula) {
		$sql = "(SELECT SUM(m.monto_final) as monto_final_total,
                        SUM(m.monto) as monto_total,
                        SUM(m.mora_acumulada) as mora_acumulada_total,
                        m._id_persona,
                        CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers), ', ',INITCAP(p.nom_persona)) nombre_estudiante,
                        da.cod_alumno,
                        CONCAT(s.desc_sede,' / ',n.desc_nivel,' / ',g.desc_grado,' / ',a.desc_aula) niveles,
                        da.estado
                   FROM pagos.movimiento         m,
                        pagos.detalle_cronograma d,
                        aula                     a,
                        sede                     s,
                        grado                    g,
                        nivel                    n,
                        sima.detalle_alumno     da,
                        public.persona           p,
                        persona_x_aula          pa
                  WHERE m._id_detalle_cronograma = d.id_detalle_cronograma
                    AND m._id_persona   = p.nid_persona
                    AND da.nid_persona  = p.nid_persona
                    AND pa.__id_persona = p.nid_persona
                    AND m.estado        = '".ESTADO_VENCIDO."'
                    AND a.nid_aula      = pa.__id_aula
                    AND a.nid_sede      = s.nid_sede
                    AND a.nid_grado     = g.nid_grado
                    AND a.nid_nivel     = n.nid_nivel
                    AND da.estado IN('".ALUMNO_MATRICULADO."')
                    AND pa.year_academico = (SELECT MAX(year_academico) 
                                               FROM persona_x_aula pa2
                                              WHERE pa2.__id_persona = p.nid_persona)
                    AND s.nid_sede  = COALESCE (?, s.nid_sede)
                    AND n.nid_nivel = COALESCE (?, n.nid_nivel)
                    AND g.nid_grado = COALESCE (?, g.nid_grado)
                    AND a.nid_aula  = COALESCE (?, a.nid_aula)
                    AND (to_char(d.fecha_vencimiento, 'DD/MM/YYYY') >= ? AND to_char(d.fecha_vencimiento, 'DD/MM/YYYY') <= ?)
               GROUP BY m._id_persona, nombre_estudiante, da.cod_alumno, niveles,da.estado)
                  UNION
                  (SELECT SUM(m.monto_final) as monto_final_total,
                        SUM(m.monto) as monto_total,
                        SUM(m.mora_acumulada) as mora_acumulada_total,
                        m._id_persona,
                        CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers), ', ',INITCAP(p.nom_persona)) nombre_estudiante,
                        da.cod_alumno,
                        CONCAT(s.desc_sede,' / ',n.desc_nivel,' / ',g.desc_grado,' /  -') niveles,
                        da.estado
                   FROM pagos.movimiento         m,
                        pagos.detalle_cronograma d,
                        sede                     s,
                        grado                    g,
                        nivel                    n,
                        sima.detalle_alumno     da,
                        public.persona           p
                  WHERE m._id_detalle_cronograma = d.id_detalle_cronograma
                    AND m._id_persona       = p.nid_persona
                    AND da.nid_persona      = p.nid_persona
                    AND m.estado            = '".ESTADO_VENCIDO."'
                    AND da.id_sede_ingreso  = s.nid_sede
                    AND da.id_grado_ingreso = g.nid_grado
                    AND da.id_nivel_ingreso = n.nid_nivel
                    AND da.estado IN('".ALUMNO_PREREGISTRO."','".ALUMNO_RETIRADO."','".ALUMNO_MATRICULABLE."','".ALUMNO_DATOS_INCOMPLETOS."', '".ALUMNO_REGISTRADO."', '".ALUMNO_PROM_PREREGISTRO."', '".ALUMNO_PROM_REGISTRO."')
                    AND s.nid_sede  = COALESCE (?, s.nid_sede)
                    AND n.nid_nivel = COALESCE (?, n.nid_nivel)
                    AND g.nid_grado = COALESCE (?, g.nid_grado)
                    AND (to_char(d.fecha_vencimiento, 'DD/MM/YYYY') >= ? AND to_char(d.fecha_vencimiento, 'DD/MM/YYYY') <= ?)
               GROUP BY m._id_persona, nombre_estudiante, da.cod_alumno, niveles,da.estado)
                   ORDER BY nombre_estudiante";
// 		$arrayFiltro = (count($personas) > 0) ? array($idSede, $idSede, $idNivel, $idNivel, $idGrado, $idGrado, $idAula, $idAula, $fecInicio, $fecFin, $personas) : array($fecInicio, $fecFin);
		$result = $this->db->query($sql, array($idSede, $idNivel, $idGrado, $idAula, $fecInicio, $fecFin, $idSede, $idNivel, $idGrado, $fecInicio, $fecFin));
		return $result->result();
	}

	function getAlumnosByFiltro($idSede, $idNivel, $idGrado, $idAula) {
		$sql = "SELECT p.nid_persona
                  FROM persona         p,
                       persona_x_aula pa,
                       aula            a,
                       sede            s,
                       grado           g,
                       nivel           n
                 WHERE a.year = "._YEAR_."
                   AND CASE WHEN( ? IS NOT NULL) THEN s.nid_sede  = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN n.nid_nivel = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN g.nid_grado = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN a.nid_aula  = ? ELSE 1 = 1 END
                   --AND p.flg_acti = '".FLG_ACTIVO."'
                   AND pa.__id_persona = p.nid_persona
                   AND a.nid_aula      = pa.__id_aula
                   AND a.nid_sede      = s.nid_sede
                   AND a.nid_grado     = g.nid_grado
                   AND a.nid_nivel     = n.nid_nivel
              ORDER BY nid_persona";
		$result = $this->db->query($sql,array($idSede, $idSede, $idNivel, $idNivel, $idGrado, $idGrado, $idAula, $idAula));
		return $result->result();
	}
	
	function filtrarAlumnos($idAlumno, $fecInicio, $fecFin, $tipo) {
		$sql = "SELECT m._id_persona,
		               d.desc_detalle_crono,
		               to_char(m.fecha_pago , 'DD/MM/YYYY') AS fecha_pago,
		               m.monto_adelanto
 			      FROM pagos.movimiento m,
 			           pagos.detalle_cronograma d
 			     WHERE m.estado                 = '".ESTADO_PAGADO."'
			       AND m._id_detalle_cronograma = d.id_detalle_cronograma
 			       AND d.fecha_vencimiento BETWEEN ? AND ?
			       AND d.flg_tipo               = '".FLG_CUOTA."'
 			       AND CASE WHEN( ? = 'descuento') THEN  (m.fecha_pago < d.fecha_descuento )
			                ELSE 1 = 1 END
 			       AND CASE WHEN( ? = 'normal')    THEN  (m.fecha_pago >= d.fecha_descuento AND m.fecha_pago <= d.fecha_vencimiento) 
			                ELSE 1 = 1 END
 			       AND CASE WHEN( ? = 'mora')      THEN  (m.fecha_pago > d.fecha_vencimiento) 
			                ELSE 1 = 1 END
		           AND m._id_persona IN ?";
		$result = $this->db->query($sql, array($fecInicio, $fecFin,$tipo,$tipo,$tipo, $idAlumno));
		return $result->result();
	}
		
	function getDatosAlumnos ($idAlumno) {
		$sql = "SELECT p.nid_persona, 
				       (CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona))) as nombre_alumno, 
					   da.cod_alumno ,
				       CASE WHEN(f.ape_paterno IS NOT NULL) THEN (CONCAT(UPPER(f.ape_paterno),' ',UPPER(f.ape_materno),', ', INITCAP(f.nombres)))
                                                            ELSE null 
                       END AS nombre_apoderado,
				       f.telf_celular
				  FROM public.persona p,
		               sima.detalle_alumno da
				       LEFT JOIN sima.familiar_x_familia ff ON(da.cod_familia = ff.cod_familiar)
	                   LEFT JOIN public.familiar f          ON(ff.flg_resp_economico = '".FLG_RESPONSABLE."' AND f.id_familiar = ff.id_familiar)
			     WHERE p.nid_persona = ?
	               AND da.nid_persona = p.nid_persona";
		$result = $this->db->query($sql,array($idAlumno));
		return $result->row_array();
	}
	
	function getDetallePadre($idPersona) {
		$sql = "SELECT p.nid_persona,
				       (CONCAT(UPPER(f.ape_paterno),' ',UPPER(f.ape_materno),', ', INITCAP(f.nombres))) as nombre_apoderado,
				       f.email1
				  FROM public.persona           p,
				       sima.familiar_x_familia ff,
				       public.combo_tipo       ct,
		               sima.detalle_alumno     da,
				       public.familiar          f
				       LEFT JOIN sima.ubigeo u ON(f.ubigeo_hogar = u.cod_ubigeo)
			     WHERE p.nid_persona  = ?
		           AND p.nid_persona  = da.nid_persona
			       AND da.cod_familia = ff.cod_familiar
			       AND f.id_familiar  = ff.id_familiar
			       AND ct.grupo::integer       = 5
			       AND ct.valor       = ff.parentesco::CHARACTER VARYING
				   AND ff.flg_resp_economico = '1'";
		$result = $this->db->query($sql,array($idPersona));
		return $result->row_array();
	}
	
	function getDetallePadres($idPersona) {
		$sql = "SELECT f.id_familiar,
                       (CONCAT(UPPER(f.ape_paterno),' ',UPPER(f.ape_materno),', ', INITCAP(f.nombres))) as nombre_apoderado,
                       f.email1,
                       ct.desc_combo,
                       CASE WHEN (f.direccion_hogar IS NOT NULL) THEN f.direccion_hogar
                	        ELSE '-'
                        END as direccion_hogar,
                       CASE WHEN (f.telf_celular IS NOT NULL) THEN f.telf_celular
                	        WHEN (f.telf_fijo IS NOT NULL) THEN f.telf_fijo
                	        WHEN (f.telf_trabajo IS NOT NULL) THEN f.telf_trabajo
                	        ELSE '-'
                        END as telefono,
                       CASE WHEN (u.distrito IS NOT NULL) THEN CONCAT(u.departamento,' ', u.provincia,' ', u.distrito)
                	        ELSE '-'
                        END as ubigeo,
		               CASE WHEN (p.foto_persona IS  NULL) THEN 'nouser.svg'
                	        ELSE p.foto_persona
                        END as foto_persona
                  FROM public.persona           p,
                       sima.familiar_x_familia ff,
                       public.combo_tipo       ct,
                       sima.detalle_alumno     da,
                       public.familiar          f
             LEFT JOIN sima.ubigeo u ON(f.ubigeo_hogar = u.cod_ubigeo)
                 WHERE p.nid_persona = ?
                   AND p.nid_persona = da.nid_persona
                   AND da.cod_familia = ff.cod_familiar
                   AND f.id_familiar = ff.id_familiar
                   AND ct.grupo::integer      = 5
                   AND ct.valor      = ff.parentesco::CHARACTER VARYING
                   AND f.id_familiar IN ((SELECT f2.id_familiar 
                                            FROM familiar f2,
	                                         sima.familiar_x_familia fxf
                                           WHERE f2.id_familiar = fxf.id_familiar
                                             AND fxf.cod_familiar = ff.cod_familiar
                                             AND fxf.flg_resp_economico = '".FLG_RESPONSABLE."'
                                           LIMIT 1)
                                           UNION
                                         (SELECT f3.id_familiar 
                                            FROM familiar  f3,
                                        	 sima.familiar_x_familia fxf
                                           WHERE f3.id_familiar = fxf.id_familiar
                                             AND fxf.cod_familiar = ff.cod_familiar
                                             AND fxf.flg_resp_economico <> '".FLG_RESPONSABLE."'
                                           LIMIT 1))";
		$result = $this->db->query($sql,array($idPersona));
		return $result->result();
	}
	
	function getDetalleAlumno($id_persona) {
		$sql = "SELECT (CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona))) as nombre_alumno,
                       da.cod_alumno,
                       CONCAT(s.desc_sede,' / ',  g.abvr,' ', n.abvr,' / ',INITCAP(a.desc_aula) ) as ubicacion
                  FROM persona 		    p,
                       persona_x_aula  pa,
                       aula 		    a,
                       grado	 	    g,
                       nivel 		    n,
                       sima.detalle_alumno da,
                       sede 		    s
                 WHERE p.nid_persona     = ?
		           AND p.nid_persona     = pa.__id_persona
                   AND pa.year_academico = (SELECT EXTRACT(YEAR FROM now()))
                   AND pa.__id_aula      = a.nid_aula
                   AND a.nid_grado       = g.nid_grado
                   AND a.nid_nivel       = n.nid_nivel
                   AND a.nid_sede        = s.nid_sede
                   AND a.nid_grado       = da.id_grado_ingreso
                   AND a.nid_nivel       = da.id_nivel_ingreso
                   AND a.nid_sede        = da.id_sede_ingreso
                   AND p.nid_persona     = da.nid_persona";
		$result = $this->db->query($sql,array($id_persona));
		return $result->row_array();
	}
	
	function getDetallePensiones($idPersona, $fecInicio, $fecFin) {
		$sql = "SELECT m.monto,
		               m.mora_acumulada,
		               (m.monto_final + m.mora_acumulada) monto_final,
		               CONCAT(dc.desc_detalle_crono,' ',c.year) as desc_detalle_crono,
				       CASE WHEN(dc.flg_tipo = '".FLG_CUOTA."') THEN current_date - dc.fecha_vencimiento
				            ELSE 0
				       END as dias_mora
				  FROM pagos.movimiento m,
				       pagos.detalle_cronograma dc,
				       pagos.cronograma c
				 WHERE m.tipo_movimiento        = '".MOV_INGRESO."'
				   AND m.estado                 = '".ESTADO_VENCIDO."'
				   AND m._id_persona            = ?
				   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
				   AND dc.fecha_vencimiento BETWEEN ? AND ?
				   AND dc._id_cronograma        = c.id_cronograma";
		$result = $this->db->query($sql, array($idPersona, $fecInicio, $fecFin));
		return $result->result();
	}
	
	function getSedeByCronograma($idCronograma) {
		$sql = "SELECT _id_sede
			      FROM pagos.cronograma
			     WHERE id_cronograma = ?";
		$result = $this->db->query($sql, array($idCronograma));
		return $result->row()->_id_sede;
	}
	
	function getCuotasByCronograma($idCronograma) {
		$sql = "SELECT id_detalle_cronograma,
		               desc_detalle_crono
			      FROM pagos.detalle_cronograma
			     WHERE _id_cronograma = ?
		 	  ORDER BY fecha_vencimiento";
		$result = $this->db->query($sql, array($idCronograma));
		return $result->result();
	}
	
	function getPagosPuntuales($idCuota, $sede, $nivel, $grado, $aula) {
	    $sql = "SELECT to_char(m.fecha_pago , 'DD/MM/YYYY') AS fecha_pago,
                       m.monto,
                       m.descuento_acumulado,
                       m.mora_acumulada,
                       m._id_persona,
                       (CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona))) as nombre_alumno,
                       da.cod_alumno,
                       CONCAT(s.desc_sede,' / ',  g.abvr,' ', n.abvr,' / ',INITCAP(a.desc_aula) ) as ubicacion,
                       (SELECT (CONCAT(UPPER(f.ape_paterno),' ',UPPER(f.ape_materno),', ', INITCAP(f.nombres))) as nombre_apoderado
                          FROM sima.familiar_x_familia ff,
                	           public.combo_tipo       ct,
                	           sima.detalle_alumno     da,
                	           public.familiar          f
                         WHERE p.nid_persona  = _id_persona
                    	   AND p.nid_persona  = da.nid_persona
                    	   AND da.cod_familia = ff.cod_familiar
                    	   AND f.id_familiar  = ff.id_familiar
                    	   AND ct.grupo       = 5
                    	   AND ct.valor       = ff.parentesco::CHARACTER VARYING
                    	   AND ff.flg_resp_economico = '1'
                    	 LIMIT 1),
                       (SELECT f.email1
                          FROM sima.familiar_x_familia ff,
                    	       public.combo_tipo       ct,
                    	       sima.detalle_alumno     da,
                    	       public.familiar          f
                         WHERE p.nid_persona  = _id_persona
                    	   AND p.nid_persona  = da.nid_persona
                    	   AND da.cod_familia = ff.cod_familiar
                    	   AND f.id_familiar  = ff.id_familiar
                    	   AND ct.grupo       = 5
                    	   AND ct.valor       = ff.parentesco::CHARACTER VARYING
                    	   AND ff.flg_resp_economico = '1'
                    	 LIMIT 1),
                       CASE WHEN (m.fecha_pago < d.fecha_descuento) THEN 'PRONTO PAGO'
                            WHEN (m.fecha_pago BETWEEN d.fecha_descuento AND d.fecha_vencimiento) THEN 'PAGO NORMAL'
                            WHEN (m.fecha_pago > d.fecha_vencimiento) THEN 'PAGO MOROSO'
                            ELSE 'other'
                        END AS estado,
                       d.desc_detalle_crono
                  FROM pagos.movimiento          m,
                       pagos.detalle_cronograma  d,
                       persona_x_aula           pa,
                       aula                      a,
                       persona                   p,
                       grado	                 g,
                       nivel 		             n,
                       sima.detalle_alumno      da,
                       sede 		             s,
                       sima.familiar_x_familia  ff,
            	       public.familiar          f
                 WHERE m.tipo_movimiento        = '".TIPO_INGRESO."'
                   AND m._id_detalle_cronograma = ?
                   AND m._id_detalle_cronograma = d.id_detalle_cronograma
                   AND m._id_persona            = pa.__id_persona
                   AND a.nid_aula               = pa.__id_aula
                   AND p.nid_persona            = m._id_persona
                   AND p.nid_persona            = pa.__id_persona
                   AND pa.__id_aula             = a.nid_aula
                   AND a.nid_grado              = g.nid_grado
                   AND a.nid_nivel              = n.nid_nivel
                   AND a.nid_sede               = s.nid_sede
                   AND a.nid_grado              = da.id_grado_ingreso
                   AND a.nid_nivel              = da.id_nivel_ingreso
                   AND a.nid_sede               = da.id_sede_ingreso
                   AND p.nid_persona            = da.nid_persona
                   AND da.cod_familia           = ff.cod_familiar
                   AND f.id_familiar            = ff.id_familiar
                   AND (m.fecha_pago <= d.fecha_descuento)
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = a.nid_sede  ELSE 1 = 1 END 
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = a.nid_nivel ELSE 1 = 1 END 
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = a.nid_grado ELSE 1 = 1 END 
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = a.nid_aula  ELSE 1 = 1 END
                UNION
                SELECT to_char(m.fecha_pago , 'DD/MM/YYYY') AS fecha_pago,
                       m.monto,
                       m.descuento_acumulado,
                       m.mora_acumulada,
                       m._id_persona,
                       (CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona))) as nombre_alumno,
                       da.cod_alumno,
                       CONCAT(s.desc_sede,' / ',  g.abvr,' ', n.abvr,' / ',INITCAP(a.desc_aula) ) as ubicacion,
                       (SELECT (CONCAT(UPPER(f.ape_paterno),' ',UPPER(f.ape_materno),', ', INITCAP(f.nombres))) as nombre_apoderado
                          FROM sima.familiar_x_familia ff,
                    	       public.combo_tipo       ct,
                    	       sima.detalle_alumno     da,
                    	       public.familiar          f
                         WHERE p.nid_persona  = _id_persona
                    	   AND p.nid_persona  = da.nid_persona
                    	   AND da.cod_familia = ff.cod_familiar
                    	   AND f.id_familiar  = ff.id_familiar
                    	   AND ct.grupo       = 5
                    	   AND ct.valor       = ff.parentesco::CHARACTER VARYING
                    	   AND ff.flg_resp_economico = '1'
                    	 LIMIT 1),
                       (SELECT f.email1
                          FROM sima.familiar_x_familia ff,
                    	       public.combo_tipo       ct,
                    	       sima.detalle_alumno     da,
                    	       public.familiar          f
                         WHERE p.nid_persona  = _id_persona
                    	   AND p.nid_persona  = da.nid_persona
                    	   AND da.cod_familia = ff.cod_familiar
                    	   AND f.id_familiar  = ff.id_familiar
                    	   AND ct.grupo       = 5
                    	   AND ct.valor       = ff.parentesco::CHARACTER VARYING
                    	   AND ff.flg_resp_economico = '1'
                    	 LIMIT 1),
                       CASE WHEN (m.fecha_pago < d.fecha_descuento) THEN 'PRONTO PAGO'
                            WHEN (m.fecha_pago BETWEEN d.fecha_descuento AND d.fecha_vencimiento) THEN 'PAGO NORMAL'
                            WHEN (m.fecha_pago > d.fecha_vencimiento) THEN 'PAGO MOROSO'
                            ELSE 'other'
                        END AS estado,
                       d.desc_detalle_crono
                  FROM pagos.movimiento          m,
                       pagos.detalle_cronograma  d,
                       sima.detalle_alumno      da,
                       persona_x_aula           pa,
                       persona                   p,
                       aula 		             a,
                       grado	 	             g,
                       nivel 		             n,
                       sede 		             s,
                       sima.familiar_x_familia  ff,
            	       public.familiar          f
                 WHERE m.tipo_movimiento        = '".TIPO_INGRESO."'
                   AND m._id_detalle_cronograma = ?
                   AND m._id_detalle_cronograma = d.id_detalle_cronograma
                   AND m._id_persona            = da.nid_persona
                   AND da.nid_persona           = p.nid_persona
                   AND p.nid_persona            = pa.__id_persona
                   AND pa.__id_aula             = a.nid_aula
                   AND a.nid_grado              = g.nid_grado
                   AND a.nid_nivel              = n.nid_nivel
                   AND a.nid_sede               = s.nid_sede
                   AND a.nid_grado              = da.id_grado_ingreso
                   AND a.nid_nivel              = da.id_nivel_ingreso
                   AND a.nid_sede               = da.id_sede_ingreso
                   AND p.nid_persona            = da.nid_persona
                   AND da.cod_familia           = ff.cod_familiar
                   AND f.id_familiar            = ff.id_familiar
                   AND (m.fecha_pago <= d.fecha_descuento)
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = da.id_sede_ingreso  ELSE 1 = 1 END 
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = da.id_nivel_ingreso ELSE 1 = 1 END 
                   AND CASE WHEN ( ? IS NOT NULL) THEN ? = da.id_grado_ingreso ELSE 1 = 1 END";
		$result = $this->db->query($sql,array($idCuota,$sede,$sede,$nivel,$nivel,$grado,$grado,$aula,$aula,$idCuota,$sede,$sede,$nivel,$nivel,$grado,$grado));
		return $result->result();
	}
	
	function getDatosGrafico($personas,$fecInicio, $fecFin) {
	    $sql = " SELECT *
	               FROM (SELECT SUM(m.monto) pronto_pago,--PRONTO PAGO
                                COUNT(1) count_pronto_pago
                           FROM pagos.movimiento m,
                                pagos.detalle_cronograma dc
                          WHERE m.tipo_movimiento        = '".TIPO_INGRESO."'
                            AND m.fecha_pago::timestamp::date < dc.fecha_descuento
	                        AND dc.fecha_vencimiento >= ?
        	                AND dc.fecha_vencimiento <= ?         
                            AND m._id_persona IN ?
                            AND m._id_detalle_cronograma =  dc.id_detalle_cronograma
                         ) as moroso,
                        (SELECT SUM(m.monto) moroso,--MOROSOS
                                COUNT(1) count_moroso
                           FROM pagos.movimiento m,
                                pagos.detalle_cronograma dc
                          WHERE m.tipo_movimiento        = '".TIPO_INGRESO."'
        	                AND dc.fecha_vencimiento >= ?
        	                AND dc.fecha_vencimiento <= ?
        	                AND m._id_persona IN ?
                            AND m.fecha_pago > dc.fecha_vencimiento  
                            AND m._id_detalle_cronograma =  dc.id_detalle_cronograma
                         ) as monto1,
                        (SELECT SUM(m.monto) normal,--PAGO NORMAL
                                COUNT(1) count_normal
                           FROM pagos.movimiento m,
                                pagos.detalle_cronograma dc
                          WHERE m.tipo_movimiento        = '".TIPO_INGRESO."'
        	                AND dc.fecha_vencimiento >= ?
        	                AND dc.fecha_vencimiento <= ?
        	                AND m._id_persona IN ?
                            AND m._id_detalle_cronograma =  dc.id_detalle_cronograma
                            AND (m.fecha_pago BETWEEN dc.fecha_descuento AND dc.fecha_vencimiento)
                         ) as monto2";
	    $result = $this->db->query($sql, array($fecInicio,$fecFin,$personas, $fecInicio,$fecFin,$personas,$fecInicio,$fecFin,$personas));
	    return $result->row_array();
	}
	
	function getPersonasBySedeYear($idSede,$year) {
	    $idSede = (!is_numeric($idSede)) ? 0 : $idSede;
	    $sql = "SELECT CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(p.nom_persona)) nombre_completo,
                       s.desc_sede,
                       n.desc_nivel,
	                   da.cod_alumno,
                       g.desc_grado,
                       a.desc_aula,
                       (SELECT string_agg((CONCAT(trim(to_char(m._id_detalle_cronograma , '9999')) , '|' , trim(to_char(m.monto, '99999999.99' )), '|' , CASE WHEN (  m.mora_acumulada = 0.00 AND m.estado <> 'PAGADO') THEN '' ELSE trim(to_char(m.mora_acumulada, '99990.99')) END, '|' , CASE WHEN (m.monto_adelanto = 0.00 AND m.estado <> 'PAGADO') THEN '' ELSE trim(to_char(m.monto_adelanto, '999999990.99')) END  , '|' , to_char(m.fecha_pago, 'DD/MM/YYYY') , '|', (CASE WHEN m.fecha_pago > dc.fecha_vencimiento THEN 'warning' WHEN m.fecha_pago IS NULL THEN 'mora' ELSE ''  END)  )), ',') pagos
                          FROM pagos.movimiento m ,
                               pagos.detalle_cronograma dc,
                               pagos.cronograma c
                         WHERE c._id_sede               = ?
	                       AND m._id_persona            = p.nid_persona
                           AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                           AND c.id_cronograma          = dc._id_cronograma
	                       AND c.year                   = ? ),
	                   (SELECT string_agg(CONCAT(dc.id_detalle_cronograma,'|',dc.desc_detalle_crono,'|',to_char(dc.fecha_vencimiento,'DD/MM/YYYY')),',' order by c._id_tipo_cronograma,dc.fecha_vencimiento)
                          FROM pagos.detalle_cronograma dc,
                               pagos.cronograma c
                         WHERE c.id_cronograma          = dc._id_cronograma
                           AND c._id_sede               = ?
	                       AND c.year                   = ?) cuotas
                  FROM persona_x_aula      pa,
                       aula                 a,
                       sede                 s,
                       nivel                n,
                       grado                g,
                       persona              p,
	                   sima.detalle_alumno da
                 WHERE /*p.flg_acti     = '".FLG_ACTIVO."'
                   AND pa.flg_acti    = '".FLG_ACTIVO."'
                   AND*/ a.nid_sede   = ?
                   AND year_academico = ?
                   AND a.nid_sede     = s.nid_sede
                   AND a.nid_nivel    = n.nid_nivel
                   AND a.nid_grado    = g.nid_grado
                   AND p.nid_persona  = pa.__id_persona
                   AND a.nid_aula     = pa.__id_aula
                   AND da.nid_persona = p.nid_persona
              ORDER BY s.nid_sede,n.nid_nivel,g.nid_grado,a.nid_aula,p.ape_pate_pers";
	    $result     = $this->db->query($sql,array($idSede,$year,$idSede,$year,$idSede,$year));
	    return $result->result_array();
	}
	
	function getPensionesVencidasGrafico($fechaInicio,$fechaFin, $idSede, $idNivel, $idGrado, $idAula) {
	    $sql = "SELECT SUM(s.sum),
                       s.desc_sede,
                       SUM(s.count) as count
                  FROM (SELECT SUM(monto_final),
                     	       s.desc_sede,
                	           Count(1)
                          FROM pagos.movimiento         m,
                               pagos.detalle_cronograma d,
                               aula                     a,
                               sede                     s,
                               grado                    g,
                               nivel                    n,
                               sima.detalle_alumno     da,
                               public.persona           p,
                               persona_x_aula          pa
                         WHERE m._id_detalle_cronograma = d.id_detalle_cronograma
                           AND m._id_persona   = p.nid_persona
                           AND da.nid_persona  = p.nid_persona
                           AND pa.__id_persona = p.nid_persona
                           AND m.estado        = '".ESTADO_VENCIDO."'
                           AND a.nid_aula      = pa.__id_aula
                           AND a.nid_sede      = s.nid_sede
                           AND a.nid_grado     = g.nid_grado
                           AND a.nid_nivel     = n.nid_nivel
                           AND da.estado IN('".ALUMNO_MATRICULADO."')
                           AND pa.year_academico = (SELECT MAX(year_academico) 
                                                      FROM persona_x_aula pa2
                                                     WHERE pa2.__id_persona = p.nid_persona)
                           AND s.nid_sede  = COALESCE (?, s.nid_sede)
                           AND n.nid_nivel = COALESCE (?, n.nid_nivel)
                           AND g.nid_grado = COALESCE (?, g.nid_grado)
                           AND a.nid_aula  = COALESCE (?, a.nid_aula)
                           AND (to_char(d.fecha_vencimiento, 'DD/MM/YYYY')::date >= ? AND to_char(d.fecha_vencimiento, 'DD/MM/YYYY')::date <= ?)
                      GROUP BY s.desc_sede,s.nid_sede
                     UNION
                        SELECT SUM(monto_final),
                	           s.desc_sede,
                	           Count(1)
                          FROM pagos.movimiento         m,
                               pagos.detalle_cronograma d,
                               sede                     s,
                               grado                    g,
                               nivel                    n,
                               sima.detalle_alumno     da,
                               public.persona           p
                         WHERE m._id_detalle_cronograma = d.id_detalle_cronograma
                           AND m._id_persona       = p.nid_persona
                           AND da.nid_persona      = p.nid_persona
                           AND m.estado            = '".ESTADO_VENCIDO."'
                           AND da.id_sede_ingreso  = s.nid_sede
                           AND da.id_grado_ingreso = g.nid_grado
                           AND da.id_nivel_ingreso = n.nid_nivel
                           AND da.estado IN('".ALUMNO_PREREGISTRO."','".ALUMNO_RETIRADO."','".ALUMNO_MATRICULABLE."','".ALUMNO_DATOS_INCOMPLETOS."', '".ALUMNO_REGISTRADO."', '".ALUMNO_PROM_PREREGISTRO."', '".ALUMNO_PROM_REGISTRO."')
                           AND s.nid_sede  = COALESCE (?, s.nid_sede)
                           AND n.nid_nivel = COALESCE (?, n.nid_nivel)
                           AND g.nid_grado = COALESCE (?, g.nid_grado)
                           AND (to_char(d.fecha_vencimiento, 'DD/MM/YYYY')::date >= ? AND to_char(d.fecha_vencimiento, 'DD/MM/YYYY')::date <= ?)
                      GROUP BY s.desc_sede,s.nid_sede) s
               GROUP BY s.desc_sede";
	    $result = $this->db->query($sql,array($idSede, $idNivel, $idGrado, $idAula, $fechaInicio, $fechaFin, $idSede, $idNivel, $idGrado, $fechaInicio,$fechaFin));
	    return $result->result();
	}
	
	function getPensionesPuntualesGrafico($idSede, $idNivel, $idGrado, $idAula) {
	    $sql="SELECT SUM(CASE WHEN (au.audi_fec_regi < d.fecha_descuento) THEN 1
			                 ELSE 0
			       	     END) as pronto_pago_count,
			       	 SUM(CASE WHEN (au.audi_fec_regi < d.fecha_descuento) THEN m.monto
			                  ELSE 0
			       	     END) as pronto_pago_monto,
			       	 SUM(CASE WHEN (au.audi_fec_regi BETWEEN d.fecha_descuento AND d.fecha_vencimiento) THEN 1
			                  ELSE 0
			       	     END) as pago_normal_count,
			       	 SUM(CASE WHEN (au.audi_fec_regi BETWEEN d.fecha_descuento AND d.fecha_vencimiento) THEN m.monto
			                  ELSE 0
			       	     END) as pago_normal_monto,
			       	 s.desc_sede
			    FROM public.sede s
		   LEFT JOIN pagos.cronograma c ON c._id_sede = s.nid_sede
		   LEFT JOIN pagos.detalle_cronograma d ON d._id_cronograma = c.id_cronograma
		   LEFT JOIN pagos.movimiento m ON m._id_detalle_cronograma = d.id_detalle_cronograma
		   LEFT JOIN pagos.audi_movimiento au ON au._id_movimiento = m.id_movimiento AND au.accion = 'PAGAR'
	           WHERE s.nid_sede = ?
            GROUP BY s.desc_sede";
	    $result     = $this->db->query($sql, array($idSede));
	    return $result->result_array();
	}
	
	function getDatosGraficoMontoMoraTotal1($fecInicio,$fecFin) {
	    $fecInicio = ($fecInicio == null) ? _getYear().'-01-01' : $fecInicio;
	    $fecFin    = ($fecFin == null)    ? _getYear().'-12-31' : $fecFin;
	    $sql = "SELECT max(s.desc_sede) desc_sede,
                       CASE WHEN(SUM(m.monto_adelanto) IS NOT NULL) THEN SUM(m.monto_adelanto) 
                                                                    ELSE 0.00 
                       END AS monto_pagado,
                       CASE WHEN(SUM(m.mora_acumulada) IS NOT NULL) THEN SUM(m.mora_acumulada)
                                                                    ELSE 0.00 
                       END AS mora,
                       CASE WHEN(SUM(m.monto)          IS NOT NULL) THEN (CASE WHEN max(dc.fecha_descuento) > current_date THEN SUM(m.monto)-max(m.descuento_acumulado) 
											                                   ELSE sum(m.monto) 
			                                                  END)
                                                                    ELSE 0.00 
                       END AS monto_total,
                       s.nid_sede,
	                   CASE WHEN(MAX(dc.fecha_descuento) > current_date) THEN CASE WHEN(SUM(m.monto) - SUM(m.monto_adelanto) - MAX(m.descuento_acumulado) IS NULL) 
                                                                                   THEN 0.00 
                                                                                   ELSE (SUM(m.monto) - SUM(m.monto_adelanto) - MAX(m.descuento_acumulado)) 
                                                                         END
                                                                         ELSE CASE WHEN(SUM(m.monto) - SUM(m.monto_adelanto) IS NULL) 
                                                                                   THEN 0.00
                                                                                   ELSE (SUM(m.monto) - SUM(m.monto_adelanto)) 
                                                                         END
                       END AS monto_restante
                  FROM public.sede s
                       LEFT JOIN pagos.cronograma c          ON(c._id_sede = s.nid_sede)
                       LEFT JOIN pagos.detalle_cronograma dc ON(c.id_cronograma = dc._id_cronograma
	                                                            AND dc.fecha_vencimiento <= ? 
                                                                AND dc.fecha_vencimiento >= ?)
                       LEFT JOIN pagos.movimiento m          ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
	             WHERE s.nid_sede IN(SELECT nid_sede
                                       FROM sede
                                      WHERE flg_extra IS NULL)
              GROUP BY s.nid_sede";
	    $result     = $this->db->query($sql,array($fecFin,$fecInicio));
	    return $result->result();
	}
	
	function getDatosGraficoMesesMontos2($fecInicio,$fecFin) {
	    $fecInicio = ($fecInicio == null) ? _getYear().'-01-01' : $fecInicio;
	    $fecFin    = ($fecFin == null)    ? _getYear().'-12-31' : $fecFin;
	    $sql = "SELECT CONCAT((SELECT EXTRACT (MONTH FROM dc.fecha_vencimiento)),'-',(SELECT EXTRACT (YEAR FROM dc.fecha_vencimiento))) detalle,
                       SUM(m.monto) monto_pendiente,
                       (SELECT SUM(m2.monto_adelanto)
                          FROM pagos.movimiento m2,
                               pagos.detalle_cronograma dc2
                         WHERE m2.estado = 'PAGADO'
                           AND (SELECT EXTRACT (MONTH FROM dc2.fecha_vencimiento)) = (SELECT EXTRACT (MONTH FROM dc.fecha_vencimiento))
                           AND m2._id_detalle_cronograma = dc2.id_detalle_cronograma) monto_pagado,
                       CASE WHEN m.estado = 'PAGADO' THEN 'PAGADO' 
                				     ELSE 'PENDIENTE' 
                       END AS estado_aux
                       
                  FROM pagos.cronograma c,
                       pagos.detalle_cronograma dc ,
                       pagos.movimiento m
                 WHERE c._id_sede IN(SELECT nid_sede
                                       FROM sede
                                      WHERE flg_extra IS NULL)
                   AND c.id_cronograma = dc._id_cronograma
                   AND dc.id_detalle_cronograma = m._id_detalle_cronograma 
                   AND m.estado                   <> 'PAGADO'
	               AND dc.fecha_vencimiento       <= ?
                   AND dc.fecha_vencimiento       >= ?
                GROUP BY CONCAT((SELECT EXTRACT (MONTH FROM dc.fecha_vencimiento)),'-',(SELECT EXTRACT (YEAR FROM dc.fecha_vencimiento))),estado_aux,dc.fecha_vencimiento
                ORDER BY (SELECT EXTRACT (YEAR FROM dc.fecha_vencimiento)), (SELECT EXTRACT (MONTH FROM dc.fecha_vencimiento))";
	    $result = $this->db->query($sql,array($fecFin,$fecInicio));
	    return $result->result();
	}
	
	function getDatosGraficoComparacion3($fecInicio,$fecFin) {
	    $fecInicio = ($fecInicio == null) ? _getYear().'-01-01' : $fecInicio;
	    $fecFin    = ($fecFin == null)    ? _getYear().'-12-31' : $fecFin;
	    $sql = "SELECT desc_sede,
                       CASE WHEN (SUM(m.monto_adelanto) IS NULL ) THEN 0.00
                                                                  ELSE (-SUM(m.monto_adelanto))
                       END AS pagado,
                       CASE WHEN (SUM(m.monto_final) IS NULL ) THEN 0.00
                                                               ELSE SUM(m.monto_final)
                       END AS pendiente
                  FROM sede s
                       LEFT JOIN pagos.cronograma c          ON(s.nid_sede = c._id_sede AND s.nid_sede IN(SELECT nid_sede
                                                                                                            FROM sede
                                                                                                           WHERE flg_extra IS NULL))
                       LEFT JOIN pagos.detalle_cronograma dc ON(c.id_cronograma = dc._id_cronograma
	                                                            AND dc.fecha_vencimiento <= ? 
                                                                AND dc.fecha_vencimiento >= ?)
                       LEFT JOIN pagos.movimiento m          ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
                 GROUP BY desc_sede";
	    $result = $this->db->query($sql,array($fecFin,$fecInicio));
	    return $result->result();
	}
	
	function getDatosPolarGrafico($fecInicio,$fecFin) {
	    $fecInicio = ($fecInicio == null) ? _getYear().'-01-01' : $fecInicio;
	    $fecFin    = ($fecFin    == null) ? _getYear().'-12-31' : $fecFin;
	    $sql = "SELECT (SELECT COUNT(1)
                          FROM pagos.movimiento m,
                               pagos.detalle_cronograma dc,
                               pagos.cronograma c
                         WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
                           AND dc._id_cronograma        = c.id_cronograma
                           AND c._id_sede               = s.nid_sede
                           AND m.estado                 = 'PAGADO'
                           AND m.fecha_pago             > dc.fecha_vencimiento
	                       AND dc.fecha_vencimiento     <= ?
	                       AND dc.fecha_vencimiento     >= ?) AS vencido,
                       (SELECT COUNT(1)
                          FROM pagos.movimiento m,
                               pagos.detalle_cronograma dc,
                               pagos.cronograma c
                         WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
                           AND dc._id_cronograma        = c.id_cronograma
                           AND c._id_sede               = s.nid_sede
                           AND m.estado                 = 'PAGADO'
                           AND m.fecha_pago             <= dc.fecha_descuento
	                       AND dc.fecha_vencimiento     <= ?
	                       AND dc.fecha_vencimiento     >= ?) AS puntual,
                       (SELECT COUNT(1)
                          FROM pagos.movimiento m,
                               pagos.detalle_cronograma dc,
                               pagos.cronograma c
                         WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
                           AND dc._id_cronograma        = c.id_cronograma
                           AND c._id_sede               = s.nid_sede
                           AND m.estado                 = 'PAGADO'
                           AND m.fecha_pago             <= dc.fecha_vencimiento
                           AND m.fecha_pago             > dc.fecha_descuento
	                       AND dc.fecha_vencimiento     <= ?
	                       AND dc.fecha_vencimiento     >= ?) AS normal,
                        s.desc_sede
                  FROM sede s";
	    $result = $this->db->query($sql,array($fecFin,$fecInicio,$fecFin,$fecInicio,$fecFin,$fecInicio));
	    return $result->result();
	}
	
	function getDatosFinalesExcel($sede,$year) {
	    $sede = (!is_numeric($sede)) ? 0 : $sede;
	    $sql = "SELECT SUM(m.monto)                                                                                                  AS total_mes,--total mes cuota 
                       --acumulado total mes cuota 
                       SUM(m.monto_adelanto)                                                                                         AS total_cuota_cobrado,--verde total cuota cobrado
                       --acumulado total cuota cobrado
                       SUM(m.mora_acumulada)                                                                                         AS mora_acumulada,
                       (SELECT SUM(m2.monto_adelanto)
                          FROM pagos.movimiento m2
                         WHERE (SELECT EXTRACT(MONTH FROM m2.fecha_pago)) = (SELECT EXTRACT(MONTH FROM dc.fecha_vencimiento))
                           AND m2._id_detalle_cronograma                  = dc.id_detalle_cronograma)                                AS monto_cobranza,
                       ROUND((SELECT CASE WHEN (SUM(m2.monto_adelanto) IS NULL ) THEN 0.00 
                                                                                 ELSE SUM(m2.monto_adelanto)
                                     END
                                FROM pagos.movimiento m2
                               WHERE (SELECT EXTRACT(MONTH FROM m2.fecha_pago)) = (SELECT EXTRACT(MONTH FROM dc.fecha_vencimiento))
                                 AND m2._id_detalle_cronograma                  = dc.id_detalle_cronograma) * (100) / (SUM(m.monto)),2)    AS porce_cobranza,       
                             (SELECT CASE WHEN (SUM(m2.monto_adelanto) IS NULL ) THEN 0.00 
                                                                                 ELSE SUM(m2.monto_adelanto)
                                     END
                                FROM pagos.movimiento m2
                               WHERE (SELECT EXTRACT(MONTH FROM m2.fecha_pago)) <> (SELECT EXTRACT(MONTH FROM dc.fecha_vencimiento))
                                 AND m2._id_detalle_cronograma                   = dc.id_detalle_cronograma)                               AS monto_morosidad,
                       ROUND((SELECT CASE WHEN (SUM(m2.monto_adelanto) IS NULL ) THEN 0.00 
                                                                                 ELSE SUM(m2.monto_adelanto)
                                     END
                                FROM pagos.movimiento m2
                               WHERE (SELECT EXTRACT(MONTH FROM m2.fecha_pago)) <> (SELECT EXTRACT(MONTH FROM dc.fecha_vencimiento))
                                 AND m2._id_detalle_cronograma                   = dc.id_detalle_cronograma) * (100) / (SUM(m.monto)) , 2) AS porce_morosidad,
                              ((SUM(m.monto)+SUM(m.mora_acumulada))  - SUM(m.monto_adelanto))                                               AS monto_por_cobrar,
                       ROUND(((SUM(m.monto)+SUM(m.mora_acumulada)) - SUM(m.monto_adelanto)) * (100) / SUM(m.monto),2)                     AS porce_monto_por_cobrar,
                       dc.id_detalle_cronograma
                  FROM pagos.cronograma          c,
                       pagos.detalle_cronograma dc,
                       pagos.movimiento          m
                 WHERE c._id_sede               = ?
	               AND c.year                   = ?
                   AND c.id_cronograma          = dc._id_cronograma
                   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                GROUP BY dc.id_detalle_cronograma
                ORDER BY dc.fecha_vencimiento";
	    $result = $this->db->query($sql,array($sede,$year));
	    return $result->result();
	}
	
	function getAudiConta($fecInicio = null, $fecFin = null) {
		$sql = "SELECT e.id_empresa, 
    				   CASE WHEN (max(ac.audi_fec_regi) IS NOT NULL) 
    				        THEN  to_char(max(ac.audi_fec_regi),'DD/MM/YYYY')
    				   ELSE '-'
    				   END  as last_fecha, 
    				   (SELECT COUNT(1) FROM pagos.audi_contabilidad a WHERE e.id_empresa = a._id_empresa) as count, 
    				   e.desc_empresa, 
    				   CASE WHEN ((SELECT au.audi_pers_regi FROM pagos.audi_contabilidad au WHERE e.id_empresa = au._id_empresa ORDER  BY au.audi_fec_regi LIMIT 1) IS NOT NULL) 
    				        THEN  (SELECT au.audi_pers_regi FROM pagos.audi_contabilidad au WHERE e.id_empresa = au._id_empresa ORDER  BY au.audi_fec_regi LIMIT 1)
    				   ELSE '-'
    				   END as  persona,
    				   CASE WHEN (p.telf_pers IS NOT NULL) 
    				        THEN  p.telf_pers
    				   ELSE '-'
    				   END  as telf_pers,
    				   CASE WHEN (p.correo_inst IS NOT NULL) 
    				        THEN  p.correo_inst
    				   ELSE (CASE WHEN (p.correo_pers IS NOT NULL) 
    					          THEN  p.correo_pers
    					          ELSE '-'
    					    END)
    				   END  as correo
			      FROM public.empresa e 
			 LEFT JOIN pagos.audi_contabilidad ac  ON (e.id_empresa = ac._id_empresa)
			 LEFT JOIN persona p ON (p.nid_persona = ac.id_pers_regi)
                 WHERE CASE WHEN ((ac.audi_fec_regi) IS NOT NULL)
                		    THEN (audi_fec_regi::date >= ? AND audi_fec_regi::date <= ? )
                	        ELSE 1 = 1 
                	   END
			  GROUP BY e.id_empresa, e.desc_empresa, p.telf_pers, p.correo_pers, p.correo_inst";
		$result = $this->db->query($sql, array($fecInicio, $fecFin));
		return $result->result();
	}
	
	function getAudiMov($fecInicio, $fecFin) {
		$sql = "SELECT am.id_pers_regi, 
				       am.audi_nomb_regi, 
				       to_char(max(am.audi_fec_regi),'DD/MM/YYYY') as last_fecha,
				       (SELECT monto_pagado FROM pagos.audi_movimiento aum WHERE aum.id_pers_regi = am.id_pers_regi AND aum.audi_fec_regi = max(am.audi_fec_regi) ORDER BY aum.audi_fec_regi LIMIT 1),
				       (SELECT aum.accion FROM pagos.audi_movimiento aum WHERE aum.id_pers_regi   = am.id_pers_regi AND aum.audi_fec_regi = max(am.audi_fec_regi) ORDER BY aum.audi_fec_regi LIMIT 1),
				       s.desc_sede, b.desc_banco
				  FROM public.sede s, 
				       pagos.audi_movimiento am
				       LEFT JOIN pagos.banco b ON(am._id_banco = b.id_banco)
				 WHERE am.accion   <> 'REGISTRAR'
				   AND am._id_sede = s.nid_sede
                   AND CASE WHEN (? IS NOT NULL AND ? IS NOT NULL) 
		                    THEN (TO_CHAR(audi_fec_regi, 'DD/MM/YYYY')::date >= ? AND TO_CHAR(audi_fec_regi, 'DD/MM/YYYY')::date <= ?)
		                    ELSE 1 = 1
		                END
		      GROUP BY am.id_pers_regi, am.audi_nomb_regi, s.desc_sede, b.desc_banco";
		$result = $this->db->query($sql, array($fecInicio, $fecFin, $fecInicio, $fecFin));
		return $result->result();
	}
	
	function getHistorialByEmpresa($idEmpresa, $fechaInicio, $fechaFin) {
		$sql = "SELECT _id_empresa, id_pers_regi, audi_pers_regi,
		               to_char(audi_fec_regi,'DD/MM/YYYY') as audi_fec_regi,
					   CASE WHEN (p.telf_pers IS NOT NULL) 
							THEN  p.telf_pers
							ELSE '-'
					   END  as telf_pers, 
					   CASE WHEN (p.correo IS NOT NULL) 
							THEN  p.correo
							ELSE '-'
					   END  as correo,
					   e.desc_empresa
				  FROM pagos.audi_contabilidad a,
					   public.persona p,
					   public.empresa e
				 WHERE _id_empresa = ?
				   AND e.id_empresa = a._id_empresa
				   AND p.nid_persona = a.id_pers_regi
                   AND (audi_fec_regi::date >= ? AND audi_fec_regi::date <= ? )
              ORDER BY audi_fec_regi DESC";
		$result = $this->db->query($sql, array($idEmpresa, $fechaInicio, $fechaFin));
		return $result->result();
	}
	
	function getHistorialByPersona($idPersona, $fechaInicio, $fechaFin) {
		$sql = "SELECT am.audi_nomb_regi, 
				       to_char(am.audi_fec_regi,'DD/MM/YYYY') as audi_fec_regi, 
				       am.monto_pagado, 
				       am.flg_visa, 
			           CASE WHEN (am.flg_visa = '".FLG_VISA."') THEN 'credit_card'
					        ELSE 'toll'
					   END AS icon_mod_pago,
				       dc.desc_detalle_crono, 
				       c.desc_concepto, 
				       c.id_concepto, 
				       am.accion,
				       CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(p.nom_persona)) nombre_completo
				  FROM pagos.audi_movimiento am,
				       public.persona p,
				       pagos.movimiento m
		     LEFT JOIN pagos.detalle_cronograma dc ON (dc.id_detalle_cronograma = m._id_detalle_cronograma)
			 LEFT JOIN pagos.concepto c ON (c.id_concepto = m._id_concepto)
			     WHERE am.accion       <> '".REGISTRAR."'
		           AND m.id_movimiento = am._id_movimiento
				   AND p.nid_persona   = m._id_persona
				   AND id_pers_regi    = ?
                   AND (audi_fec_regi::date >= ? AND audi_fec_regi::date <= ? )
		      ORDER BY am.audi_fec_regi DESC";
		$result = $this->db->query($sql, array($idPersona, $fechaInicio, $fechaFin));
		return $result->result();
	}
	
    function getHistorialByBanco($idBanco, $idEmpresa, $fecInicio, $fecFin) {
    	$sql = "SELECT accion, 
      			       to_char(fecha_migracion,'DD/MM/YYYY') as fecha_migracion, 
      			       id_pers_regi, 
      			       audi_pers_regi,
      			       b.desc_banco, 
    			       CASE WHEN (p.telf_pers IS NOT NULL) THEN  p.telf_pers
    					    ELSE '-'
    				    END  as telf_pers, 
    			       CASE WHEN (p.correo_inst IS NOT NULL) 
    				        THEN  p.correo_inst
    				        ELSE (CASE WHEN (p.correo_pers IS NOT NULL) 
    					               THEN  p.correo_pers
    					               ELSE '-'
				                  END)
    				    END  as correo
    			  FROM pagos.audi_banco ab,
    			       pagos.banco       b,
    			       public.persona    p
    			 WHERE _id_empresa     = ?
    			   AND ab._id_banco    = ?
    			   AND ab._id_banco  = b.id_banco
    			   AND p.nid_persona = ab.id_pers_regi
                   AND (TO_CHAR(fecha_migracion, 'DD/MM/YYYY')::date >= ? AND TO_CHAR(fecha_migracion, 'DD/MM/YYYY')::date <= ? )
      		  ORDER BY fecha_migracion";
      	$result = $this->db->query($sql, array($idEmpresa, $idBanco, $fecInicio, $fecFin));
      	return $result->result();
    }
      
    function getCantidades(){
        $sql = "SELECT --string_agg(
                         CONCAT(
                           (trim(
                               to_char(
                                    (SELECT COUNT(1)
                                       FROM pagos.movimiento m
                                      WHERE m.fecha_pago             <=  dc.fecha_vencimiento
                                        AND dc.fecha_vencimiento     IS NOT NULL
                                        AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                                        AND m.estado IN('PAGADO')
                                    )
                                   ,'99999')
                                ) 
                           ),'|',
                           (trim(
                               to_char(
                                    (SELECT COUNT(1)
                                       FROM pagos.movimiento m
                                      WHERE m.fecha_pago            <=  dc.fecha_descuento
                                        AND dc.fecha_descuento       IS NOT NULL
                                        AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                                        AND m.estado IN('PAGADO')
                                    )
                                   ,'99999')
                                )
                           ),'|',
                           (trim(
                               to_char(
                                    (SELECT COUNT(1)
                                       FROM pagos.movimiento m
                                      WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
                                        AND m.fecha_pago IS NULL
                                        AND m.estado IN('VENCIDO','POR PAGAR')
                                    )
                                   ,'99999')
                               )
                           ),'|',
                           (trim(
                               to_char(
                                    (SELECT COUNT(1)
                                       FROM pagos.movimiento m
                                      WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
                                        AND dc.fecha_vencimiento     < m.fecha_pago
                                        AND dc.fecha_vencimiento  IS NOT NULL
                                        AND m.estado IN('PAGADO')
                                    )
                                   ,'99999')
                               )
                           )
                       --),'|'
                       ),
                       dc.id_detalle_cronograma,
                       dc.fecha_vencimiento,
                       dc.fecha_descuento
                  FROM pagos.cronograma c,
                       pagos.detalle_cronograma dc
                 WHERE c.id_cronograma       = dc._id_cronograma
                   AND c._id_tipo_cronograma = 2
              GROUP BY dc._id_cronograma,dc.id_detalle_cronograma,dc.fecha_vencimiento
              ORDER BY dc._id_cronograma,dc.fecha_vencimiento";
        $result = $this->db->query($sql);
        $data = $result->result();
        $suma = 0;
        foreach($data as $row){
            $numeros = explode('|', $row->concat);
            $suma += $numeros[0] + $numeros[1] + $numeros[2] + $numeros[3];
        }
    }
    
    function getDataConceptosGrafico(){
        $sql = "SELECT SUM(m.monto) monto,
                       MAX(CONCAT(c.desc_concepto)) concepto
                  FROM pagos.concepto   c,
                       pagos.movimiento m
                 WHERE c.id_concepto NOT IN(1,2,3)
                   AND m._id_concepto = c.id_concepto
                   AND monto > 0
                 GROUP BY c.id_concepto";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAudiMovBancos($banco, $fecInicio, $fecFin){
        $sql = "SELECT CONCAT(INITCAP(p.nom_persona), ' ',INITCAP(p.ape_pate_pers), ' ', INITCAP(p.ape_mate_pers)) AS nombre_completo,
                       p.nid_persona,
                       b.id_banco,
                       m.desc_banco_pago,
                       s.desc_sede,
                       m.monto,
                       dc.desc_detalle_crono,
                       m._id_detalle_cronograma,
                       to_char(m.fecha_pago,'DD/MM/YYYY') as fecha_pago
                  FROM pagos.movimiento         m,
                       pagos.banco              b,
                       public.persona           p,
                       public.sede              s,
                       pagos.detalle_cronograma dc
                 WHERE m._id_banco_pago         = COALESCE (? ,m._id_banco_pago)
                   AND m._id_banco_pago         = b.id_banco
                   AND m._id_persona            = p.nid_persona
                   AND s.nid_sede               = (SELECT c._id_sede
                                    			     FROM pagos.cronograma       c,
                                    			          sede                   s,
                                    			          pagos.tipo_cronograma tc
                                    			    WHERE id_cronograma  = (SELECT _id_cronograma
                                                    					      FROM pagos.detalle_cronograma
                                                    					     WHERE id_detalle_cronograma = m._id_detalle_cronograma)
                                    			      AND s.nid_sede            = c._id_sede
                                    			      AND c._id_tipo_cronograma = tc.id_tipo_cronograma)
                   AND dc._id_cronograma        = (SELECT _id_cronograma
                            			             FROM pagos.detalle_cronograma
                            			            WHERE id_detalle_cronograma = m._id_detalle_cronograma)
                   AND dc.id_detalle_cronograma = m._id_detalle_cronograma 
                   AND m.flg_lugar_pago         = '".FLG_BANCO."'
                   AND m.estado                 = '".ESTADO_PAGADO."'
                   AND (to_char(m.fecha_pago, 'DD/MM/YY')::date >= ? AND to_char(m.fecha_pago, 'DD/MM/YY')::date <= ?)
                GROUP BY m.desc_banco_pago, s.desc_sede, p.nid_persona, m.fecha_pago, m._id_detalle_cronograma, m.monto, p.nid_persona, dc.desc_detalle_crono, b.id_banco
                ORDER BY p.nid_persona, fecha_pago";
        $result = $this->db->query($sql, array($banco, $fecInicio, $fecFin));
        return $result->result();
    }
    
    function getTiposBySedeYear($idSede,$year){
        $sql = "SELECT INITCAP(tc.desc_tipo_cronograma) desc_tipo_cronograma,
                       c.id_cronograma
                  FROM pagos.cronograma       c,
                       pagos.tipo_cronograma tc
                 WHERE c._id_sede             = 2
                   AND c.year                 = 2017
                   AND c._id_tipo_cronograma  = tc.id_tipo_cronograma
                   AND c._id_tipo_cronograma IN(".CRONO_SPORT_SUMMER.",".CRONO_CREATIVE_SUMMER.")";
        $result = $this->db->query($sql, array($idSede,$year));
        return $result->result();
    }
    
    function getEstudiantesByTaller($taller){
        $sql = "SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
                       CASE WHEN p.google_foto IS NOT NULL 
                            THEN 1
                	    ELSE 0 
                       END AS foto_select,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',INITCAP(p.nom_persona)) nombre_completo,
                       s.desc_sede,
                       n.desc_nivel,
                       g.desc_grado    
                  FROM pagos.movimiento     m,
                       persona              p,
                       sima.detalle_alumno da,
                       nivel                n,
                       grado                g,
                       sede                 s
                 WHERE m._id_detalle_cronograma = ?
                   AND m.estado                <> '".ESTADO_ANULADO."'
                   AND m._id_persona            = p.nid_persona
                   AND da.nid_persona           = p.nid_persona
                   AND da.id_sede_ingreso       = s.nid_sede
                   AND da.id_nivel_ingreso      = n.nid_nivel
                   AND da.id_grado_ingreso      = g.nid_grado
              ORDER BY nombre_completo";
        $result = $this->db->query($sql, array($taller));
        return $result->result();
    }
}
