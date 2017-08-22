<?php

class M_matricula extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function getAlumnosByAula($idAula){
	    $sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',split_part( p.nom_persona, ' ' , 1 ))) AS nombrecompleto,
		               INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ')) AS apellidos,
		               INITCAP(CONCAT(split_part( p.nom_persona, ' ' , 1 ))) AS nombres,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
		               CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
	                        ELSE '-' END AS desc_sede,
				       CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
	                        ELSE '-' END AS desc_grado,
		               CASE WHEN d.cod_alumno IS NOT NULL THEN d.cod_alumno
	                        ELSE '-' END AS cod_alumno,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   d.estado,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT f.telf_celular
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS telefonoresponsable,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('POR PAGAR'), UPPER('PAGADO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as pagado,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('VENCIDO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as por_pagar,
	    		       d.pais,
	    			   d.cod_alumno_temp
				  FROM persona p
			LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona
		    LEFT JOIN aula a
				    ON (a.nid_aula = pa. __id_aula AND a.nid_aula = ?)
		    LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		    LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		    LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
	        LEFT JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
				 WHERE d.cod_alumno IS NOT NULL
			  ORDER BY nombrecompleto";
	    $resultado=$this->db->query($sql, array($idAula));
	    return $resultado->result();
	}
	
	function getAulasByGradoYear($idNivel, $idSede, $idGrado, $year, $idaula = null) {
	    $sql = "	SELECT 	nid_aula,
		                    INITCAP(desc_aula) desc_aula
					FROM 	aula a
					WHERE 	nid_sede  = ?
		            AND 	nid_nivel = ?
                    AND	    nid_grado = ?
	                AND     a.year    = ?
	                AND     (CASE WHEN ? IS NOT NULL THEN a.nid_aula != ?
	                        ELSE 1 = 1 END)";
	    $result = $this->db->query($sql, array($idSede, $idNivel, $idGrado, $year, $idaula, $idaula));
	    return $result->result();
	}
	
	function getAulasByGradoYearCapcidad($idNivel, $idSede, $idGrado, $year, $idaula = null) {
	    $sql = "	SELECT 	nid_aula,
		                    desc_aula,
	                        (SELECT COUNT(1)
	                           FROM persona_x_aula pa
	                          WHERE a.nid_aula = pa.__id_aula) count_est,
	                        capa_max
					FROM 	aula a
					WHERE 	nid_sede  = ?
		            AND 	nid_nivel = ?
                    AND	    nid_grado = ?
	                AND     a.year    = ?
	                AND     a.flg_acti  = ".FLG_ACTIVO."
	                AND     (CASE WHEN ? IS NOT NULL THEN a.nid_aula != ?
	                        ELSE 1 = 1 END)";
	    $result = $this->db->query($sql, array($idSede, $idNivel, $idGrado, $year, $idaula, $idaula));
	    return $result->result();
	}
	
	function getAlumnosToCicloRegular($nombre, $idAu, $year, $sede, $nivel, $grado){
	    $sql = "	SELECT p.nid_persona,
                	       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) AS nombrecompleto,
                	       da.estado,
                	       p.nro_documento,
		                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
		                        ELSE 'nouser.svg' END AS foto_persona
                	  FROM persona             p
                INNER JOIN sima.detalle_alumno da
	    		        ON da.nid_persona = p.nid_persona
                	 WHERE da.cod_alumno IS NOT NULL
                	   AND UPPER(da.estado)  IN ( UPPER('".ALUMNO_MATRICULABLE."'))
            	       AND p.nid_persona NOT IN (SELECT pxa.__id_persona
                                                   FROM aula a,
                                                        persona_x_aula pxa
                                                  WHERE a.nid_aula = pxa.__id_aula
                	                                AND a.nid_aula = ?)
                	   AND UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona)) LIKE UPPER(?)
                	   AND da.year_ingreso = ?
                	   AND da.id_sede_ingreso = ?
                	   AND da.id_nivel_ingreso = ?
                	   AND da.id_grado_ingreso = ?
                  ORDER BY nombrecompleto";
	    $result = $this->db->query($sql, array($idAu,"%".$nombre."%", $year, $sede, $nivel, $grado));
	    return $result->result();
	}
	
	function asignarAlumnoEnAula($data){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert("persona_x_aula", $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function updateDetalleAlumno($idAlumno, $arrayUpdate){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where("nid_persona", $idAlumno);
	        $this->db->update("sima.detalle_alumno", $arrayUpdate);
	
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
	
	function insertIncidenciaMatricula($data){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert("sima.incidencia_matricula", $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}

	function eliminarAlumnoDeAula($data){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->delete("persona_x_aula", $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MM-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_DEL;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getDetallePariente($idfamiliar){
	    $sql = "SELECT fxf.parentesco parentesco,
            	       fxf.flg_resp_economico,
            	       fxf.flg_apoderado,
	                   fxf.cod_familiar,
            	       INITCAP(f.ape_paterno) ape_paterno,
            	       INITCAP(f.ape_materno) ape_materno,
            	       INITCAP(f.nombres) nombres,
            	       f.email1 email1,
            	       f.flg_vive,
	                   f.sexo,
            	       f.fec_naci,
            	       f.nacionalidad,
            	       f.tipo_doc_identidad,
            	       f.nro_doc_identidad,
            	       f.estado_civil,
            	       f.idioma,
            	       f.nivel_instruccion,
            	       f.flg_ex_alumno,
            	       f.colegio_egreso,
            	       f.religion,
            	       INITCAP(f.ocupacion) ocupacion,
            	       INITCAP(f.centro_trabajo) centro_trabajo,
            	       INITCAP(f.direccion_trabajo) direccion_trabajo,
            	       f.situacion_laboral,
            	       INITCAP(f.cargo) cargo,
            	       f.year_egreso,
            	       INITCAP(f.direccion_hogar) direccion_hogar,
            	       INITCAP(f.refer_domicilio) refer_domicilio,
            	       f.telf_fijo,
            	       f.telf_celular,
            	       f.ubigeo_hogar,
	                   f.flg_dominio_ingles,
	                   f.flg_nivel_dom_ingles,
	                   f.movil_datos,
	                   f.so_movil
	              FROM sima.familiar_x_familia fxf
             LEFT JOIN familiar f
                    ON fxf.id_familiar = f.id_familiar
	             WHERE fxf.id_familiar = ?
              ORDER BY cod_familiar desc
	             LIMIT 1";
	    $result = $this->db->query($sql,array($idfamiliar));
	    return $result->row_array();
	}

	function updateCampoFamiliar($arrayUpdate,$idFamiliar,$table){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try {
	        $this->db->where("id_familiar", $idFamiliar);
	        $this->db->update($table, $arrayUpdate);
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    } catch (Exception $e) {
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getAlumnosToCicloVerano($nombre, $idAu, $year, $sede, $nivel, $grado){
	    $sql = "	SELECT p.nid_persona,
                	       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) AS nombrecompleto,
                	       da.estado,
                	       p.nro_documento,
		                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
		                        ELSE 'nouser.svg' END AS foto_persona
                	  FROM persona             p
                INNER JOIN sima.detalle_alumno da
	    		        ON da.nid_persona = p.nid_persona
                	 WHERE da.cod_alumno IS NOT NULL
                	   AND UPPER(da.estado)  IN ( '".ALUMNO_NOPROMOVIDO_NIVELACION."','".ALUMNO_PROMOVIDO."','".ALUMNO_VERANO."')
                	   AND p.nid_persona NOT IN (SELECT pxa.__id_persona
                                                   FROM aula a,
                                                        persona_x_aula pxa
                                                  WHERE a.nid_aula = pxa.__id_aula
                	                                AND a.nid_aula = ?)
                	   AND UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona)) LIKE UPPER(?)
                	   AND da.year_ingreso = ?
                	   AND da.id_sede_ingreso = ?
                	   AND da.id_nivel_ingreso = ?
                	   AND da.id_grado_ingreso = ?
                     ORDER BY da.estado";
	    $result = $this->db->query($sql, array($idAu,"%".$nombre."%", $year, $sede, $nivel, $grado));
	    return $result->result();
	}
    
    function insertColegio($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("sima.colegios", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MA-001)');
            }
    
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
            $rpt['id']    = $this->db->insert_id();
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
    
        return $rpt;
    }
    
    function validateColegioRepetido($descColegio){
        $sql = "SELECT COUNT(1) cant
                  FROM sima.colegios
                 WHERE UPPER(unaccent(desc_colegio)) = UPPER(unaccent(?))";
        $result = $this->db->query($sql,array($descColegio));
        return $result->row()->cant;
    }
    
function getDetallePostulante($idpostulante){
        $sql = "SELECT (INITCAP(p.nom_persona))   AS nom_persona,
	                   (INITCAP(p.ape_pate_pers)) AS ape_pate_pers,
	                   (INITCAP(p.ape_mate_pers)) AS ape_mate_pers,
	                   p.sexo,
	                   p.tipo_documento,
	                   p.nro_documento,
	                   d.total_hermano,
	                   d.nro_hermano,
                       d.lengua_materna,
                       d.flg_padres_juntos,
                       INITCAP(d.convivencia) convivencia,
                       INITCAP(d.familiar_frecuente) familiar_frecuente,
	                   d.religion,
	                   d.pais,
	                   d.ubigeo,
                       CASE WHEN d.id_grado_ingreso IS NOT NULL THEN CONCAT(d.id_grado_ingreso,'_',d.id_nivel_ingreso)
                            ELSE '' END AS gradonivel,
	                   d.colegio_procedencia,
	                   p.fec_naci,
	                   d.flg_nac_registrado,
                       d.nac_complicaciones,
	                   p.tipo_sangre,
        			   d.tipo_discapacidad,
        			   d.peso,
        			   d.talla,
        		 	   INITCAP(d.alergia) alergia,
        			   d.flg_alergia,
        		       d.flg_permiso_datos,
        		       d.flg_permiso_fotos,
        			   INITCAP(d.evacuacion_contacto) evacuacion_contacto,
                       INITCAP(d.encargado_contacto) encargado_contacto,
        		       (SELECT COUNT(1)
        		          FROM pagos.movimiento
        		         WHERE _id_detalle_cronograma > 0
        		           AND _id_persona    = ?) as count_compromisos,
        		       d.year_ingreso
	              FROM persona p
	         LEFT JOIN sima.detalle_alumno d
	                ON p.nid_persona = d.nid_persona
	             WHERE p.nid_persona = ?";
        $result = $this->db->query($sql,array($idpostulante,$idpostulante));
        return $result->row_array();
    }
    
    function countByTipoDocMatricula($nroDoc ,$tipoDoc, $idContacto, $famiOPost){
        $sql ="SELECT (count1 + count2) cant 
                 FROM (SELECT  COUNT(*) count1
		                 FROM  familiar f
	                    WHERE  (f.tipo_doc_identidad = ?
	                      AND  f.nro_doc_identidad  = ?	
	                      AND  (CASE WHEN ? IS NOT NULL AND 0 = ? THEN id_familiar <> ?
	                                 ELSE 1 = 1 END))) c1,
			          (SELECT  COUNT(*) count2
                         FROM  persona p
                        WHERE  (p.tipo_documento = ?
	                      AND  p.nro_documento  = ?	
	                      AND  (CASE WHEN ? IS NOT NULL AND 1 = ? THEN nid_persona <> ?
	                          ELSE 1 = 1 END)) ) c2";
        $result = $this->db->query($sql,array($tipoDoc, $nroDoc, $idContacto, $famiOPost,$idContacto, $tipoDoc, $nroDoc, $idContacto, $famiOPost, $idContacto));
        return $result->row()->cant;
    }
    
    function getHijosByFamiliaPagoCuotaIngreso($codfamilia){
        $sql = "SELECT p.nid_persona,
	                   CONCAT(INITCAP(p.nom_persona),' ',UPPER(substring(p.ape_pate_pers from 1 for 1)),'.') nombres
                  FROM persona p
            INNER JOIN sima.detalle_alumno da
                    ON p.nid_persona = da.nid_persona
            INNER JOIN pagos.movimiento m
                    ON p.nid_persona = m._id_persona
                 WHERE da.cod_familia = ?
                   AND m.estado = '".ESTADO_PAGADO."'
                   AND m._id_concepto = ".CUOTA_INGRESO."
              ORDER BY p.nid_persona";
        $result = $this->db->query($sql,array($codfamilia));
        return $result->result();
    }
    
	function updateCampoPostulante($arrayUpdate,$idpostulante,$table){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try {
	        $this->db->where("nid_persona", $idpostulante);
	        $this->db->update($table, $arrayUpdate);
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    } catch (Exception $e) {
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
    
    function getParientesByFamilia($codfamilia){
        $sql = "SELECT f.id_familiar,
	                   CONCAT(INITCAP(f.nombres),' ',UPPER(substring(f.ape_paterno from 1 for 1)),'.') nombres,
                       CASE WHEN f.foto_persona IS NOT NULL THEN f.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona
		          FROM familiar f
		    INNER JOIN sima.FAMILIAR_X_FAMILIA fxf
		            ON f.id_familiar = fxf.id_familiar
		         WHERE cod_familiar = ?
              ORDER BY f.id_familiar";
        $result = $this->db->query($sql,array($codfamilia));
        return $result->result();
    }
	
    function ValidarCronoAluCompromisos($sede,$nivel,$grado,$year,$id_persona, $tipo = null) {
        $id_det_cronograma = array();
        $sql_mov = "SELECT _id_detalle_cronograma
                      FROM pagos.movimiento
                     WHERE _id_persona = ?
                       AND (estado = '".ESTADO_PAGADO."' OR estado = '".ESTADO_POR_PAGAR."' OR estado = '".ESTADO_VENCIDO."')
                  ORDER BY _id_detalle_cronograma";
        $result = $this->db->query($sql_mov, array($id_persona));
        $id_det_crono = $result->result();
        if($result->num_rows() == 0){ $id_det_cronograma[] = 0;}
        else{
            foreach ($id_det_crono as $item){
                $id_det_cronograma[] = $item->_id_detalle_cronograma == null ? 0 : $item->_id_detalle_cronograma;
            }
        }
        $sql_beca = "SELECT porcentaje_beca
                       FROM pagos.condicion c
                 INNER JOIN pagos.condicion_x_persona cp ON cp._id_persona = ?
                        AND cp.estado = 'ACTIVO'
                        AND cp._id_condicion = c.id_condicion
                      WHERE c.tipo_condicion = '0'";
        $porcentaje   = $this->db->query($sql_beca,array($id_persona));
        
        if($porcentaje->num_rows() > 0) {
            $descuento = $porcentaje->row()->porcentaje_beca;
        } else {
            $descuento = null;
        }
        $sql_prom = "SELECT pea.year_academico, da.cod_alumno
                       FROM sima.detalle_alumno AS da
                  LEFT JOIN public.persona_x_aula pea ON pea.__id_persona = ? AND pea.year_academico <> ?
                      WHERE da.nid_persona = ?";
        $promovido = $this->db->query($sql_prom,array($id_persona,$year,$id_persona));
        $join = ($tipo == null) ? 'LEFT' : 'INNER';
//         if($promovido->num_rows() > 0) {
        	$sql = null;
        	if($promovido->row()->year_academico != NULL){
        		//RATIFICACION
        		$sql =  "SELECT det.id_detalle_cronograma,
            		        m.fecha_pago,
                            cro._id_tipo_cronograma,
                            INITCAP(det.desc_detalle_crono) as detalle,
                            det.fecha_vencimiento as fecha_v,
                            det.fecha_descuento as fecha_d,
                            det.flg_beca,
                            CASE WHEN(current_date > det.fecha_vencimiento::timestamp::date)
                                 THEN(current_date - det.fecha_vencimiento::timestamp::date)*det.cantidad_mora
                                 ELSE 0
                            END AS mora,
                            CASE WHEN (det.flg_beca = '1')
                                THEN  'BECA'
                                 ELSE ''
                            END AS descuento,
                            CASE WHEN( det.flg_tipo = '2')
                                 THEN monto_matricula
                                 ELSE CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '1')
                                           THEN round(((cond.monto_pension * (to_number('100', '9999D99') - ?))/100),2)
                                           ELSE CASE WHEN(current_date < det.fecha_descuento )
                                                     THEN cond.monto_pension - cond.descuento_nivel
                                                     ELSE cond.monto_pension
                                                END
                                      END
                            END AS monto,
                            CASE WHEN( det.flg_tipo = '2')
                                THEN 'RATIFICACIÓN'
                                ELSE CASE WHEN( det.flg_tipo = '3')
                                          THEN 'CUOTA'
                                          ELSE 'CUOTA'
                                     END
                            END AS concepto,
                            CASE WHEN (Det.id_detalle_cronograma IN ?)
                    			THEN 'TIENE COMPROMISO'
                    			ELSE 'NO TIENE COMPROMISO'
                			END AS compromiso,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?) as count_compromisos,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?
            		           AND flg_tipo IN ('3')) as count_compromisos_x,
                            det.flg_tipo,
                            CASE WHEN (m.estado IS NOT NULL) THEN  m.estado
                                 ELSE 'Compromiso por aceptar' END AS estado,
                            cond.monto_matricula_prom
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND
                                                             (det.flg_tipo        IN ('2','3')))
                 ".$join." JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma AND
						      								  m._id_persona = ?)
                 INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                              cond._id_nivel      = ?                 AND
                                                              cond._id_grado      = ?                 AND
                                                              cond.year_condicion = ?                 AND
                                                              cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND
                                                              cope.estado         = 'ACTIVO'          AND
                                                              cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     = ?
                        AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
                   GROUP BY id_detalle_cronograma, cro._id_tipo_cronograma, cond.monto_matricula, cond.monto_pension, cond.descuento_nivel, m.estado, m.fecha_pago, cond.monto_matricula_prom
                   ORDER BY det.id_detalle_cronograma,cro._id_tipo_cronograma,  det.fecha_vencimiento";
//         	}
        }else{
            //MATRICULA
            $sql = "SELECT det.id_detalle_cronograma,
            		        m.fecha_pago,
                            cro._id_tipo_cronograma,
                            (INITCAP(det.desc_detalle_crono)) as detalle,
                            det.fecha_vencimiento as fecha_v,
                            det.fecha_descuento as fecha_d,
                            det.flg_beca,
                            CASE WHEN(current_date > det.fecha_descuento::timestamp::date)
                                 THEN(current_date - det.fecha_descuento::timestamp::date)*det.cantidad_mora
                                 ELSE 0
                            END AS mora,
                            CASE WHEN (det.flg_beca = '1')
                                THEN 'BECA'
                                ELSE ''
                            END AS descuento,
                            CASE WHEN( det.flg_tipo = '1')
                                 THEN monto_matricula
                                 ELSE CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '1')
                                           THEN round(((cond.monto_pension * (to_number('100', '9999D99') - ?))/100),2)
                                           ELSE CASE WHEN(current_date < det.fecha_descuento )
                                                     THEN cond.monto_pension - cond.descuento_nivel
                                                     ELSE cond.monto_pension
                                                END
                                      END
                            END AS monto,
                            INITCAP(CASE WHEN( det.flg_tipo = '1')
                                THEN 'MATRICULA'
                                ELSE CASE WHEN( det.flg_tipo = '3')
                                        THEN 'CUOTA'
                                        ELSE 'CUOTA'
                                    END
                            END) AS concepto,
                            CASE WHEN (Det.id_detalle_cronograma IN ?)
                    			THEN 'TIENE COMPROMISO'
                    			ELSE 'NO TIENE COMPROMISO'
                			END AS compromiso,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?) as count_compromisos,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento mx
            		         WHERE mx._id_detalle_cronograma > 0
            		           AND mx._id_persona    = ?
            		           AND flg_tipo IN ('3')) as count_compromisos_x,
                            det.flg_tipo,
                            CASE WHEN (m.estado IS NOT NULL) THEN  m.estado
                                 ELSE 'Compromiso por aceptar' END AS estado,
                            cond.monto_matricula_prom
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND
                                                             (det.flg_tipo        IN ('1','3')))
                  ".$join." JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma AND
						      								  m._id_persona = ?)
                 INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                              cond._id_nivel      = ?                 AND
                                                              cond._id_grado      = ?                 AND
                                                              cond.year_condicion = ?                 AND
                                                              cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND
                                                              cope.estado         = 'ACTIVO'          AND
                                                              cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     = ?
                        AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
                   GROUP BY id_detalle_cronograma, cro._id_tipo_cronograma, cond.monto_matricula, cond.monto_pension, cond.descuento_nivel, cond.monto_cuota_ingreso, m.estado, m.fecha_pago, cond.monto_matricula_prom
                   ORDER BY det.flg_tipo, det.fecha_vencimiento, cro._id_tipo_cronograma,  det.id_detalle_cronograma";
        }
        $result = $this->db->query($sql, array($descuento,$descuento,$id_det_cronograma,$id_persona,$id_persona,$id_persona,$sede,$nivel,$grado,$year,$id_persona,$sede,$year));
        return array("result" => $result->result(),"descuento" => $descuento,'codigo' => $promovido->row()->cod_alumno);
    }
    
    function getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year) {
        $sql = "SELECT monto_cuota_ingreso
                  FROM pagos.condicion
                 WHERE _id_sede       = ?
                   AND _id_nivel      = ?
                   AND _id_grado      = ?
                   AND year_condicion = ?";
        $result = $this->db->query($sql,array($sede,$nivel,$grado,$year));
        if($result->num_rows() > 0){
            return $result->row()->monto_cuota_ingreso;
        } else{
            return 0;
        }
    }
    
    function evaluateCuotaIngresoByPersona($idPersona) {
        $sql = "SELECT COUNT(1) count
                  FROM persona p,
                       pagos.movimiento m,
                       sima.detalle_alumno da
                 WHERE da.nid_persona = ?
        		   AND p.nid_persona   = m._id_persona
                   AND m._id_persona   = da.nid_persona
                   AND m._id_concepto  = 3";
        $result = $this->db->query($sql,array($idPersona));
        if($result->num_rows() == 0){
            return 0;
        } else {
            return $result->row()->count;
        }
    }
    
    function datosIngresoPostulante($idpostulante){
        $sql = "SELECT da.id_grado_ingreso, 
                       da.id_nivel_ingreso, 
                       da.id_sede_ingreso,
                       da.year_ingreso,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('VENCIDO'))
    			           AND da.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as deuda,
        			   da.cod_alumno_temp,
                       da.estado,
                       da.cod_familia,
                       da.id_grado_ratificacion,
                       (SELECT desc_grado FROM grado WHERE nid_grado = da.id_grado_ratificacion) desc_grado,
                       da.id_nivel_ratificacion,
                       da.id_sede_ratificacion,
                       da.year_ratificacion,
		        	   (SELECT COUNT (1)
					      FROM persona_x_aula pxa1
					     WHERE pxa1.__id_persona = da.nid_persona 
					       AND CASE WHEN year_ratificacion > 0 THEN pxa1.year_Academico = year_ratificacion - 1
					                                               ELSE 1 = 1 END) countaulas
                  FROM sima.detalle_alumno da
                 WHERE da.nid_persona = ?";
        $result = $this->db->query($sql,array($idpostulante));
	    return $result->row_array();
    }
    
    function getCompromisosEstudiante($id_persona, $year, $sede, $nivel, $grado, $opcion = null){
        $sql_beca = "SELECT porcentaje_beca
                       FROM pagos.condicion c
                 INNER JOIN pagos.condicion_x_persona cp ON cp._id_persona = ?
                        AND cp.estado = 'ACTIVO'
                        AND cp._id_condicion = c.id_condicion
                      WHERE c.tipo_condicion = '0'";
        $porcentaje   = $this->db->query($sql_beca,array($id_persona));
        if($porcentaje->num_rows() > 0) {
            $descuento = $porcentaje->row()->porcentaje_beca;
        }
        else{
            $descuento = null;
        }
        //MATRICULA
        $flg_tipo = 1;
        $monto_columna= 'monto_matricula';
        $fecha = 'fecha_descuento';
        if($opcion != null){
            //RATIFICACION
            $flg_tipo = 2;
            $monto_columna= 'monto_matricula';
            $fecha = 'fecha_vencimiento';
        }

        $sql =      "SELECT det.id_detalle_cronograma,
                            CASE WHEN( det.flg_tipo = '".$flg_tipo."')
                                 THEN ".$monto_columna."
                                 ELSE CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '1')
                                           THEN round(((cond.monto_pension * (to_number('100', '9999D99') - ?))/100),2)
                                           ELSE CASE WHEN(current_date < det.fecha_descuento )
                                                     THEN cond.monto_pension - cond.descuento_nivel
                                                     ELSE cond.monto_pension
                                                END
                                      END
                            END AS monto,
                            (SELECT COUNT(1)
            		          FROM pagos.movimiento m
		                INNER JOIN pagos.detalle_cronograma det   ON (det.id_detalle_cronograma  = m._id_detalle_cronograma
		      		                                             AND (det.flg_tipo        IN('3')))
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?) as count_compromisos
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON (det._id_cronograma  = cro.id_cronograma      
                                                          AND (det.flg_tipo        IN('3')))
                 INNER JOIN pagos.condicion cond           ON (cond._id_sede       = ?                 AND
                                                               cond._id_nivel      = ?                 AND
                                                               cond._id_grado      = ?                 AND
                                                               cond.year_condicion = ?                 AND
                                                               cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON (cope._id_persona    = ?                 AND
                                                               cope.estado         = 'ACTIVO'          AND
                                                               cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     = ?
                        AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
                   GROUP BY det.id_detalle_cronograma, count_compromisos, monto
                   ORDER BY det.flg_tipo, det.fecha_vencimiento";
        $result = $this->db->query($sql, array($descuento,$descuento,$id_persona, $sede,$nivel,$grado,$year,$id_persona,$sede,$year));
        return $result->result();
    }
    
    function getMontoByGradoNivelSede($grado,$nivel,$sede) {
        $sql = "SELECT monto_pension
                  FROM pagos.condicion
                 WHERE _id_sede  = ?
                   AND _id_grado = ?
                   AND _id_nivel = ?";
        $result = $this->db->query($sql,array($sede,$grado,$nivel));
        return $result->row()->monto_pension;
    }
    
    function getId_condicionAlumno($sede,$nivel,$grado,$year,$idtipocronograma) {
        $sql = "SELECT id_condicion
                FROM pagos.condicion
                WHERE _id_sede            = ?
                  AND _id_nivel           = ?
                  AND _id_grado           = ?
                  AND year_condicion      = ?
        		  AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql, array($sede,$nivel,$grado,$year,$idtipocronograma));
        if($result->num_rows() > 0) {
            return $result->row()->id_condicion;
        } else{
            return 0;
        }
    }
    
    function getBecaByPersona($idPersona) {
        $sql = "SELECT porcentaje_beca
                  FROM pagos.condicion_x_persona cp,
                       pagos.condicion c
                 WHERE c.id_condicion = cp._id_condicion
                   AND cp._id_persona = ?";
        $result = $this->db->query($sql,array($idPersona));
        if($result->num_rows() > 0) {
            return $result->row()->porcentaje_beca;
        } else{
            return 0;
        }
    }

    function getMoraByDetalle($idDetalle,$porcentaje,$sede,$nivel,$grado,$year) {
        $sql = "SELECT CASE WHEN(dc.fecha_vencimiento::timestamp::date < current_date) THEN 'VENCIDO'
                								                                       ELSE 'POR PAGAR'
                       END AS estado
                  FROM pagos.detalle_cronograma dc,
                       pagos.condicion c
                 WHERE dc.id_detalle_cronograma = ?
                   AND c._id_sede  = ?
                   AND c._id_nivel = ?
                   AND c._id_grado = ?
                   AND c.year_condicion = ?";
        $result = $this->db->query($sql,array($idDetalle,$sede,$nivel,$grado,$year));
        return $result->row_array();
    }
    
    function SaveCompromisosMovimientos($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $this->db->trans_begin();
        try{
            if(count($datos) > 1){
                $data['n_total_mov'] = $this->db->insert_batch("pagos.movimiento",array_values($datos));
            } else {
                $this->db->insert("pagos.movimiento",$datos[1]);
                $data['n_total_mov'] = 1;
            }
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datos)) {
                throw new Exception('No se guardaron los compromisos');
            }
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_INS;
            $data['id_movimiento'] = $this->db->insert_id();
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getNextCorrelativo($idCompromiso) {
        $sql = "SELECT COUNT(1) + 1 as cuenta
                  FROM pagos.audi_movimiento
                 WHERE _id_movimiento = ?";
        $result = $this->db->query($sql,array($idCompromiso));
        return $result->row()->cuenta;
    }
    
    function getCountCondicionAsignada($condicion,$persona, $year) {
        $sql = "SELECT COUNT(1)
                  FROM pagos.condicion_x_persona
                 WHERE _id_condicion = ?
                   AND _id_persona   = ?
                   AND year_uso = ?
                   AND flg_beca != ".FLG_BECA."";
        $result = $this->db->query($sql,array($condicion,$persona, $year));
        return $result->row()->count;
    }
    
    function SaveCompromisosAudiMovimientos($datos,$condicion) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $this->db->trans_begin();
        try{
            if($this->m_matricula->getCountCondicionAsignada($condicion['_id_condicion'],$condicion['_id_persona'], $condicion['year_uso']) == 0){
                $this->db->insert("pagos.condicion_x_persona",$condicion);
                if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) {
                    throw new Exception('No se guardaron los datos');
                }
            }
            if(count($datos) > 1){
                $this->db->insert_batch("pagos.audi_movimiento",$datos);
            } else{
                $this->db->insert("pagos.audi_movimiento",$datos[1]);
            }
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datos)) {
                throw new Exception('No se guardaron los compromisos');
            }
            $data['error']     = EXIT_SUCCESS;
            $data['cabecera']  = MSJ_INS;
            $data['msj']       = 'Los compromisos se generaron correctamente';
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function insertConfirmacion($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("sima.confirmacion_datos", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MA-001)');
            }
    
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
    
        return $rpt;
    }
    
    function countConfirmacionDatos($year, $idpostulante, $tipo, $opc = null){
    	$sql = " SELECT * FROM (SELECT COUNT(1) recibido
                                  FROM sima.confirmacion_datos
                                 WHERE year_confirmacion = ?
                    			   AND id_estudiante = ?
                    	           AND tipo = '".$tipo."'
                    	           AND CASE WHEN ? IS NOT NULL THEN (flg_recibido = '1')
                    	                    ELSE 1 = 1 END) recibido,
    	                       (SELECT COUNT(1) existe
                                  FROM sima.confirmacion_datos
                                 WHERE year_confirmacion = ?
                    			   AND id_estudiante = ?
                    	           AND tipo = '".$tipo."') existe";
    	$result = $this->db->query($sql,array($year, $idpostulante, $opc, $year, $idpostulante));
	    return $result->row_array();
    }
	
    function getHijosByFamiliaPagoMatricula($codfamilia){
        $sql = "SELECT p.nid_persona,
	                   CONCAT(INITCAP(p.nom_persona),' ',UPPER(substring(p.ape_pate_pers from 1 for 1)),'.') nombres,
                       CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   (SELECT COUNT(1) cant
                          FROM sima.confirmacion_datos
                         WHERE year_confirmacion = da.year_ingreso
        		   AND id_estudiante = da.nid_persona
                           AND tipo = 'P') confirmo_datos
                  FROM persona p
            INNER JOIN sima.detalle_alumno da
                    ON p.nid_persona = da.nid_persona
                 WHERE da.cod_familia = ?
                   AND CASE WHEN (SELECT COUNT (1)
                			        FROM persona_x_aula pxa1
                			       WHERE pxa1.__id_persona = da.nid_persona
                			         AND CASE WHEN year_ratificacion > 0 
                                              THEN pxa1.year_Academico = year_ratificacion - 1
								              ELSE da.estado NOT IN ('REGISTRADO','PREREGISTRO') END) != 0
		                    THEN '1' = (SELECT flg_recibido
                    				      FROM sima.confirmacion_datos
                    				     WHERE id_estudiante = da.nid_persona
                    				       AND year_confirmacion = year_ratificacion)
		                    ELSE 1 = 1 END
                   AND da.estado IN ('".ALUMNO_REGISTRADO."', '".ALUMNO_MATRICULADO."', '".ALUMNO_MATRICULABLE."', '".ALUMNO_PROM_REGISTRO."','".ALUMNO_PREREGISTRO."')
              GROUP BY da.nid_persona, da.year_ingreso, p.nid_persona
              ORDER BY p.nid_persona";
        $result = $this->db->query($sql,array($codfamilia));
        return $result->result();
    }
    
    function getAlumnosByFamilia($codfamiliar){
    	$sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers)) AS apellidos,
	                   INITCAP(p.nom_persona) AS nombres,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
				       CASE WHEN a.desc_aula IS NOT NULL AND pa.year_academico = d.year_ingreso THEN a.desc_aula
	                        ELSE '-' END AS desc_aula,
		               CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
	                        ELSE '-' END AS desc_sede,
	                   CASE WHEN n.desc_nivel IS NOT NULL THEN n.desc_nivel
	                        ELSE '-' END AS desc_nivel,
	                   CASE WHEN g.desc_grado IS NOT NULL THEN g.desc_grado
	                        ELSE '-' END AS desc_grado,
				       CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
	                        ELSE '-' END AS desc_grado_nivel,
		               CASE WHEN d.cod_familia IS NOT NULL THEN d.cod_familia
	                        ELSE '-' END AS cod_familia,
		               CASE WHEN d.cod_alumno IS NOT NULL THEN d.cod_alumno
	                        ELSE '-' END AS cod_alumno,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   d.estado,
	                   a.nid_aula,
    	               p.flg_acti,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar   = f.id_familiar
	                       AND ff.cod_familiar  = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT f.telf_celular
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar   = f.id_familiar
	                       AND ff.cod_familiar  = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS telefonoresponsable,
    			 	   (SELECT COUNT(1)
    				   	  FROM persona_x_aula pxa1
    			         WHERE pxa1.__id_persona = d.nid_persona) countaulas,
    			       d.year_ingreso,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('POR PAGAR'), UPPER('PAGADO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as pagado,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('VENCIDO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as por_pagar,
	                   d.pais,
    				   d.cod_alumno_temp
				  FROM persona p
			 LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona and (pa.year_academico = (SELECT pa2.year_academico
                                												  FROM persona_x_aula pa2
                                												 WHERE pa2.__id_persona = p.nid_persona
                                											      ORDER BY pa2.year_academico DESC
                                												 LIMIT 1))
		     LEFT JOIN aula a
				    ON a.nid_aula    = pa. __id_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
			 LEFT JOIN sima.detalle_alumno d
    			    ON p.nid_persona = d.nid_persona
				 WHERE d.cod_alumno IS NOT NULL
				   AND d.cod_familia = ?
    		  ORDER BY apellidos, nombres";
    	$result = $this->db->query($sql, array($codfamiliar));
    	return $result->result();
    }
    
    function countCuotas($tipo,$idestudiante,$year){
    	$sql = "SELECT COUNT(1) cant
                  FROM pagos.movimiento m
    	    INNER JOIN pagos.detalle_cronograma dc
    			    ON m._id_detalle_cronograma = dc.id_detalle_cronograma
    	    INNER JOIN pagos.cronograma c
    			    ON c.id_cronograma = dc._id_cronograma
    			 WHERE dc.flg_tipo = ?::character varying
    			   AND m._id_persona = ?
    			   AND c.year = ?";
    	$result = $this->db->query($sql,array($tipo, $idestudiante,$year));
    	return $result->row()->cant;
    }
    
    function getCountCompromisosEstudiante($id_persona, $year, $sede, $nivel, $grado){
    	$sql_prom = "SELECT pea.year_academico, da.cod_alumno
                       FROM sima.detalle_alumno AS da
                  LEFT JOIN public.persona_x_aula pea ON pea.__id_persona = ? AND pea.year_academico <> ?
                      WHERE da.nid_persona = ?";
    	$promovido = $this->db->query($sql_prom,array($id_persona,$year,$id_persona));
    	//MATRICULA
    	$flg_tipo = 2;
    	$monto_columna= 'monto_cuota_ingreso';
    	$fecha = 'fecha_descuento';
    	if($promovido->row()->year_academico != NULL) {
    		//RATIFICACION
    		$flg_tipo = 1;
    		$monto_columna= 'monto_matricula';
    		$fecha = 'fecha_vencimiento';
    	}
    
    	$sql =      "SELECT det.id_detalle_cronograma,
                            (SELECT COUNT(1)
            		          FROM pagos.movimiento m
		                INNER JOIN pagos.detalle_cronograma det   ON (det.id_detalle_cronograma  = m._id_detalle_cronograma
		      		                                             AND (det.flg_tipo        IN('3')))
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?) as count_compromisos
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON (det._id_cronograma  = cro.id_cronograma
                                                          AND (det.flg_tipo        IN('3')))
                 INNER JOIN pagos.condicion cond           ON (cond._id_sede       = ?                 AND
                                                               cond._id_nivel      = ?                 AND
                                                               cond._id_grado      = ?                 AND
                                                               cond.year_condicion = ?                 AND
                                                               cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON (cope._id_persona    = ?                 AND
                                                               cope.estado         = 'ACTIVO'          AND
                                                               cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     = ?
                        AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
                   GROUP BY det.id_detalle_cronograma, count_compromisos
                   ORDER BY det.flg_tipo, det.fecha_vencimiento";
    	$result = $this->db->query($sql, array($id_persona, $sede,$nivel,$grado,$year,$id_persona,$sede,$year));
    	return $result->result();
    }
    
    function getDetalleCuotaIngreso($idpostulante){
    	$sql = "SELECT monto, 
    			       estado,
    			       fecha_pago
    			  FROM pagos.movimiento
    			 WHERE _id_persona = ?
    			   AND _id_concepto = 3";
    	$result = $this->db->query($sql, array($idpostulante));
    	return $result->row_array();
    }
    
    function getCountCuotasConfiguradas($sede,$nivel,$grado,$year, $idpersona){
    	$sql = "SELECT COUNT(1) cant,
    			       (SELECT COUNT(1) cant
        		          FROM pagos.movimiento m,
        		               pagos.detalle_cronograma det,
        		               pagos.cronograma cro
        		         WHERE m._id_detalle_cronograma = det.id_detalle_cronograma
        		           AND det._id_cronograma  = cro.id_cronograma
        		           AND det.flg_tipo = '".FLG_MATRICULA."'
        		           AND m._id_persona = ?
        		           AND cro.year = cond.year_condicion) matricula,
        		       (SELECT COUNT(1) cant
        		          FROM pagos.movimiento m,
        		               pagos.detalle_cronograma det,
        		               pagos.cronograma cro
        		         WHERE m._id_detalle_cronograma = det.id_detalle_cronograma
        		           AND det._id_cronograma  = cro.id_cronograma
        		           AND det.flg_tipo = '".FLG_RATIFICACION."'
        		           AND m._id_persona = ?
        		           AND cro.year = cond.year_condicion) ratificacion,
        		       (SELECT COUNT(1) cant
        		          FROM pagos.movimiento m,
        		               pagos.detalle_cronograma det,
        		               pagos.cronograma cro
        		         WHERE m._id_detalle_cronograma = det.id_detalle_cronograma
        		           AND det._id_cronograma  = cro.id_cronograma
        		           AND det.flg_tipo = '".FLG_CUOTA."'
        		           AND m._id_persona = ?
        		           AND cro.year = cond.year_condicion) pensiones
            	  FROM pagos.cronograma cro
            INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND
                                                        (det.flg_tipo        IN ('".FLG_MATRICULA."','".FLG_RATIFICACION."','".FLG_CUOTA."')))
            INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                         cond._id_nivel      = ?                 AND
                                                         cond._id_grado      = ?                 AND
                                                         cond.year_condicion = ?                 AND
                                                         cond._id_tipo_cronograma = cro._id_tipo_cronograma)
    		 WHERE cro.flg_cerrado = '1'
    		   AND cro.year = ?
    		   AND cro._id_sede   = ?
          GROUP BY cond.year_condicion";
    	$result = $this->db->query($sql, array($idpersona,$idpersona, $idpersona, $sede,$nivel,$grado,$year, $year, $sede));
        return $result->row_array();
    }
    
    function getFechasReferenciaByTipo($tipo){
        $sql = "SELECT fec_inicio,
    			       fec_fin,
                       id_config
    			  FROM sima.config
                 WHERE tipo = ?
    			 LIMIT 1";
        $result = $this->db->query($sql, array($tipo));
        return $result->row_array();
    }
    
    function insertFechasReferencia($dataInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("sima.config", $dataInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MA-010)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_INS;
            $rpt['id']       = $this->db->insert_id();
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function updateFechasReferencia($dataupdate, $idConfig){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try {
            $this->db->where("id_config", $idConfig);
            $this->db->update("sima.config", $dataupdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-011)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        } catch (Exception $e) {
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
	
	function getDeudasByEstudiantes($nid_cod_alumno){
	    $sql = "SELECT desc_sede, 
	                   desc_cuota, 
	                   fec_vencimiento, 
	                   monto_pagar
                  FROM pagos.fun_get_deudas_by_estudiante(?)
	             WHERE fec_vencimiento < current_date";
	    $result = $this->db->query($sql, array($nid_cod_alumno));
	    return $result->result();
	}
	
	function getCountDeudasByEstudiantes($nid_cod_alumno){
	    $sql = "SELECT COUNT(1) cant
                  FROM pagos.fun_get_deudas_by_estudiante(?)
	             WHERE fec_vencimiento < current_date";
	    $result = $this->db->query($sql, array($nid_cod_alumno));
	    return $result->row()->cant;
	}
	
	function getGradoNivelRatificacion($idalumno){
	    $sql = "SELECT INITCAP(g.abvr) desc_grado, 
	                   INITCAP(desc_nivel) desc_nivel,
	                   n.nid_nivel,
	                   g.nid_grado
                  FROM grado g
                 INNER JOIN nivel n
                  ON n.nid_nivel = g.id_nivel
                 WHERE nid_grado = (SELECT id_grado_ingreso
                            	      FROM sima.detalle_alumno
                            	     WHERE nid_persona = ? LIMIT 1) +1";
	    $result = $this->db->query($sql, array($idalumno));
	    return $result->row_array();
	}
	
	function getConfigPromocion ($sede, $year) {
	    $sql = "SELECT flg_promo, 
	                   fecha_fin_promo
	              FROM pagos.sede_monto 
	             WHERE _id_sede = ? 
	               AND year = ?
	               AND _id_tipo_cronograma = 2
	             LIMIT 1";
	    $result = $this->db->query($sql, array($sede, $year));
	    return $result->row_array();
	}
    
	function getFlagConfirmacion($idVal, $yearconfirmacion) {
	    $sql = 'SELECT flg_recibido campo
                  FROM sima.confirmacion_datos
                WHERE id_estudiante = ? 
	              AND year_confirmacion = ? LIMIT 1';
	    $result = $this->db->query($sql,array($idVal, $yearconfirmacion));
	    if($result->num_rows() > 0) {
	        return ($result->row()->campo);
	    } else {
	        return null;
	    }
	}
    
    function getEstadoCuota($tipo,$idestudiante,$year){
    	$sql = "SELECT m.estado estado
                  FROM pagos.movimiento m
    	    INNER JOIN pagos.detalle_cronograma dc
    			    ON m._id_detalle_cronograma = dc.id_detalle_cronograma
    	    INNER JOIN pagos.cronograma c
    			    ON c.id_cronograma = dc._id_cronograma
    			 WHERE dc.flg_tipo = ?::character varying
    			   AND m._id_persona = ?
    			   AND c.year = ?";
    	$result = $this->db->query($sql,array($tipo,$idestudiante,$year));
    	return $result->row();
    }
    
    function countByTipoDocMatriculaFamiliares($nroDoc ,$tipoDoc, $idContacto, $famiOPost){
        $sql ="SELECT (count1) cant
                 FROM (SELECT  COUNT(1) count1
		                 FROM  familiar f
	                    WHERE  (f.tipo_doc_identidad = ?
	                      AND  f.nro_doc_identidad  = ?
	                      AND  (CASE WHEN ? IS NOT NULL AND 0 = ? THEN id_familiar <> ?
	                                 ELSE 1 = 1 END))) c1";
        $result = $this->db->query($sql,array($tipoDoc, $nroDoc, $idContacto, $famiOPost,$idContacto));
        return $result->row()->cant;
    }
    
    function allCompromisos($sede,$nivel,$grado,$year,$id_persona, $arrayCuotas) {
        $id_det_cronograma = array();
        $sql_mov = "SELECT _id_detalle_cronograma
                      FROM pagos.movimiento
                     WHERE _id_persona = ?
                       AND (estado = '".ESTADO_PAGADO."' OR estado = '".ESTADO_POR_PAGAR."' OR estado = '".ESTADO_VENCIDO."')
                  ORDER BY _id_detalle_cronograma";
        $result = $this->db->query($sql_mov, array($id_persona));
        $id_det_crono = $result->result();
        if($result->num_rows() == 0){ $id_det_cronograma[] = 0;}
        else{
            foreach ($id_det_crono as $item){
                $id_det_cronograma[] = $item->_id_detalle_cronograma == null ? 0 : $item->_id_detalle_cronograma;
            }
        }
        $sql_prom = "SELECT pea.year_academico, da.cod_alumno
                       FROM sima.detalle_alumno AS da
                  LEFT JOIN public.persona_x_aula pea ON pea.__id_persona = ? AND pea.year_academico <> ?
                      WHERE da.nid_persona = ?";
        $promovido = $this->db->query($sql_prom,array($id_persona,$year,$id_persona));
		$sql =  "SELECT det.id_detalle_cronograma,
    		        m.fecha_pago,
                    cro._id_tipo_cronograma,
                    INITCAP(det.desc_detalle_crono) as detalle,
                    det.fecha_vencimiento as fecha_v,
                    det.fecha_descuento as fecha_d,
                    det.flg_beca,
                    CASE WHEN(current_date > det.fecha_vencimiento::timestamp::date)
                         THEN(current_date - det.fecha_vencimiento::timestamp::date)*det.cantidad_mora
                         ELSE 0
                    END AS mora,
                    CASE WHEN (det.flg_beca = '1')
                        THEN  'BECA'
                         ELSE ''
                    END AS descuento,
                    CASE WHEN( det.flg_tipo IN ('1','2'))
                         THEN monto_matricula
                         ELSE cond.monto_pension
                    END AS monto,
                    CASE WHEN( det.flg_tipo = '1')
                         THEN 'MATRÍCULA'
		                 WHEN( det.flg_tipo = '2')
                         THEN 'RATIFICACIÓN'
                         ELSE CASE WHEN( det.flg_tipo = '3')
                                   THEN 'CUOTA'
                                   ELSE 'CUOTA'
                             END
                    END AS concepto,
    		        (SELECT COUNT(1)
    		          FROM pagos.movimiento
    		         WHERE _id_detalle_cronograma > 0
    		           AND _id_persona    = ?) as count_compromisos,
    		        (SELECT COUNT(1)
    		          FROM pagos.movimiento
    		         WHERE _id_detalle_cronograma > 0
    		           AND _id_persona    = ?
    		           AND flg_tipo IN ('3')) as count_compromisos_x,
                    det.flg_tipo,
                    CASE WHEN (m.estado IS NOT NULL) THEN  m.estado
                         ELSE 'Compromiso por aceptar' END AS estado,
                    cond.monto_matricula_prom
               FROM pagos.cronograma cro
         INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND
                                                     (det.flg_tipo        IN ?))
          LEFT JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma AND
				      								  m._id_persona = ?)
         INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                      cond._id_nivel      = ?                 AND
                                                      cond._id_grado      = ?                 AND
                                                      cond.year_condicion = ?                 AND
                                                      cond._id_tipo_cronograma = cro._id_tipo_cronograma)
          LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND
                                                      cope.estado         = 'ACTIVO'          AND
                                                      cope._id_condicion  = cond.id_condicion)
              WHERE cro._id_sede = ?
                AND cro.year     = ?
                AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
           GROUP BY id_detalle_cronograma, cro._id_tipo_cronograma, cond.monto_matricula, cond.monto_pension, cond.descuento_nivel, m.estado, m.fecha_pago, cond.monto_matricula_prom
           ORDER BY det.fecha_vencimiento, det.id_detalle_cronograma,cro._id_tipo_cronograma";
        $result = $this->db->query($sql, array($id_persona,$id_persona,$arrayCuotas, $id_persona,$sede,$nivel,$grado,$year,$id_persona,$sede,$year));
        return $result->result();
    }
    
    function getCuotasGeneradas($sede,$nivel,$grado,$year,$id_persona, $tipo = null) {
        $id_det_cronograma = array();
        $sql_mov = "SELECT _id_detalle_cronograma
                      FROM pagos.movimiento
                     WHERE _id_persona = ?
                       AND (estado = '".ESTADO_PAGADO."' OR estado = '".ESTADO_POR_PAGAR."' OR estado = '".ESTADO_VENCIDO."')
                  ORDER BY _id_detalle_cronograma";
        $result = $this->db->query($sql_mov, array($id_persona));
        $id_det_crono = $result->result();
        if($result->num_rows() == 0){ $id_det_cronograma[] = 0;}
        else{
            foreach ($id_det_crono as $item){
                $id_det_cronograma[] = $item->_id_detalle_cronograma == null ? 0 : $item->_id_detalle_cronograma;
            }
        }
        $descuento = null;
        $sql_prom = "SELECT pea.year_academico, da.cod_alumno
                       FROM sima.detalle_alumno AS da
                  LEFT JOIN public.persona_x_aula pea ON pea.__id_persona = ? AND pea.year_academico <> ?
                      WHERE da.nid_persona = ?";
        $promovido = $this->db->query($sql_prom,array($id_persona,$year,$id_persona));
        $join = ($tipo == null) ? 'LEFT' : 'INNER';
        //         if($promovido->num_rows() > 0) {
        $sql = null;
        if($promovido->row()->year_academico != NULL){
            //RATIFICACION
            $sql =  "SELECT det.id_detalle_cronograma,
            		        m.fecha_pago,
                            cro._id_tipo_cronograma,
                            INITCAP(det.desc_detalle_crono) as detalle,
                            det.fecha_vencimiento as fecha_v,
                            det.fecha_descuento as fecha_d,
                            det.flg_beca,
                            CASE WHEN(current_date > det.fecha_vencimiento::timestamp::date)
                                 THEN(current_date - det.fecha_vencimiento::timestamp::date)*det.cantidad_mora
                                 ELSE 0
                            END AS mora,
                            CASE WHEN (det.flg_beca = '1')
                                THEN  'BECA'
                                 ELSE ''
                            END AS descuento,
                            m.monto,
                            CASE WHEN( det.flg_tipo = '2')
                                THEN 'RATIFICACIÓN'
                                ELSE CASE WHEN( det.flg_tipo = '3')
                                          THEN 'CUOTA'
                                          ELSE 'CUOTA'
                                     END
                            END AS concepto,
                            CASE WHEN (Det.id_detalle_cronograma IN ?)
                    			THEN 'TIENE COMPROMISO'
                    			ELSE 'NO TIENE COMPROMISO'
                			END AS compromiso,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?) as count_compromisos,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?
            		           AND flg_tipo IN ('3')) as count_compromisos_x,
                            det.flg_tipo,
                            CASE WHEN (m.estado IS NOT NULL) THEN  m.estado
                                 ELSE 'Compromiso por aceptar' END AS estado
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND
                                                             (det.flg_tipo        IN ('2','3')))
                 ".$join." JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma AND
						      								  m._id_persona = ?)
                 INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                              cond._id_nivel      = ?                 AND
                                                              cond._id_grado      = ?                 AND
                                                              cond.year_condicion IN (?,?-1)                 AND
                                                              cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND
                                                              cope.estado         = 'ACTIVO'          AND
                                                              cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     IN (?,?-1)
                        AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
                   GROUP BY id_detalle_cronograma, m.fecha_pago, cro._id_tipo_cronograma, m.monto, m.estado
                   ORDER BY det.fecha_vencimiento, det.id_detalle_cronograma,cro._id_tipo_cronograma";
            //         	}
        }else{
            //MATRICULA
            $sql = "SELECT det.id_detalle_cronograma,
            		        m.fecha_pago,
                            cro._id_tipo_cronograma,
                            (INITCAP(det.desc_detalle_crono)) as detalle,
                            det.fecha_vencimiento as fecha_v,
                            det.fecha_descuento as fecha_d,
                            det.flg_beca,
                            CASE WHEN(current_date > det.fecha_descuento::timestamp::date)
                                 THEN(current_date - det.fecha_descuento::timestamp::date)*det.cantidad_mora
                                 ELSE 0
                            END AS mora,
                            CASE WHEN (det.flg_beca = '1')
                                THEN 'BECA'
                                ELSE ''
                            END AS descuento,
                            m.monto,
                            INITCAP(CASE WHEN( det.flg_tipo = '1')
                                THEN 'MATRICULA'
                                ELSE CASE WHEN( det.flg_tipo = '3')
                                        THEN 'CUOTA'
                                        ELSE 'CUOTA'
                                    END
                            END) AS concepto,
                            CASE WHEN (Det.id_detalle_cronograma IN ?)
                    			THEN 'TIENE COMPROMISO'
                    			ELSE 'NO TIENE COMPROMISO'
                			END AS compromiso,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento
            		         WHERE _id_detalle_cronograma > 0
            		           AND _id_persona    = ?) as count_compromisos,
            		        (SELECT COUNT(1)
            		          FROM pagos.movimiento mx
            		         WHERE mx._id_detalle_cronograma > 0
            		           AND mx._id_persona    = ?
            		           AND flg_tipo IN ('3')) as count_compromisos_x,
                            det.flg_tipo,
                            CASE WHEN (m.estado IS NOT NULL) THEN  m.estado
                                 ELSE 'Compromiso por aceptar' END AS estado
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND
                                                             (det.flg_tipo        IN ('1','3')))
                  ".$join." JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma AND
						      								  m._id_persona = ?)
                 INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                              cond._id_nivel      = ?                 AND
                                                              cond._id_grado      = ?                 AND
                                                              cond.year_condicion IN (?,?-1)                 AND
                                                              cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND
                                                              cope.estado         = 'ACTIVO'          AND
                                                              cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     IN (?,?-1)
                        AND cro.estado   = '".FLG_ESTADO_ACTIVO."'
                   GROUP BY id_detalle_cronograma, m.fecha_pago, cro._id_tipo_cronograma, m.monto, m.estado
                   ORDER BY det.fecha_vencimiento, det.flg_tipo, cro._id_tipo_cronograma,  det.id_detalle_cronograma";
        }
        $result = $this->db->query($sql, array($id_det_cronograma,$id_persona,$id_persona,$id_persona,$sede,$nivel,$grado,$year,$year,$id_persona,$sede,$year,$year));
        return array("result" => $result->result(),"descuento" => $descuento,'codigo' => $promovido->row()->cod_alumno);
    }
    
    function getAlumnosByAulaLista($idAula){
        $sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',split_part( p.nom_persona, ' ' , 1 ))) AS nombrecompleto,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
                       INITCAP(p.ape_pate_pers) AS ape_pate_pers,
                       INITCAP(p.ape_mate_pers) AS ape_mate_pers
				  FROM persona p
			 LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona
		     LEFT JOIN aula a
				    ON a.nid_aula = pa. __id_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
	        LEFT JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
				 WHERE d.cod_alumno IS NOT NULL
	               AND a.nid_aula = ?
			  ORDER BY nombrecompleto";
        $resultado=$this->db->query($sql, array($idAula));
        return $resultado->result();
    }
    
    function getFechasReferencia($tipo){
        $sql = "SELECT fec_inicio,
    			       fec_fin,
                       id_config,
                       tipo
    			  FROM sima.config
                 WHERE tipo IN ?
                 ORDER BY id_config";
        $result = $this->db->query($sql, array($tipo));
        return $result->result();
    }
    
    function countCuotasByPersona($idestudiante,$year,$arrayCuotas){
    	$sql = "SELECT COUNT(1) cant
                  FROM pagos.movimiento m
    	    INNER JOIN pagos.detalle_cronograma dc
    			    ON m._id_detalle_cronograma = dc.id_detalle_cronograma
    	    INNER JOIN pagos.cronograma c
    			    ON c.id_cronograma = dc._id_cronograma
    			 WHERE dc.flg_tipo IN ?
    			   AND m._id_persona = ?
    			   AND c.year = ?";
    	$result = $this->db->query($sql,array($arrayCuotas,$idestudiante,$year));
    	return $result->row()->cant;
    }
    
    function evaluateCuotaIngresoByFamilia($codFamilia) {
        $sql = "SELECT COUNT(1) count
                  FROM persona p,
                       pagos.movimiento m,
                       sima.detalle_alumno da
                 WHERE da.cod_familia = ?
        		   AND p.nid_persona   = m._id_persona
                   AND m._id_persona   = da.nid_persona
                   AND m._id_concepto  = 3";
        $result = $this->db->query($sql,array($codFamilia));
        if($result->num_rows() == 0){
            return 0;
        } else {
            return $result->row()->count;
        }
    }
    
    function getDocDeclaracion($id_postulante){
        $sql = "SELECT declaracion_jurada 
                  FROM sede 
                 WHERE nid_Sede = (SELECT id_sede_ingreso 
			                         FROM sima.detalle_alumno 
		                             WHERE nid_persona = ?)";
        $result = $this->db->query($sql,array($id_postulante));
        return $result->row()->declaracion_jurada;
    }
    
    function getFechaMatricula($sede,$nivel,$grado,$year){
    	$sql = "SELECT det.fecha_vencimiento - 1 as fecha_v
                  FROM pagos.cronograma cro
			INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND 
														(det.flg_tipo        IN ('".FLG_MATRICULA."')))
			INNER JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma)
			INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
												 	 	 cond._id_nivel      = ?                 AND
														 cond._id_grado      = ?                 AND
														 cond.year_condicion = ?                 AND
														 cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                 WHERE cro._id_sede = ?
                   AND cro.year     = ?
                   AND cro.estado   = 'ACTIVO'
		   	  GROUP BY fecha_v";
    	$result = $this->db->query($sql,array($sede,$nivel,$grado,$year,$sede,$year));
        if($result->num_rows() == 0){
            return 0;
        } else {
            return $result->row()->fecha_v;
        }
    }
    
	/*
	function getAulasByNombre($descAula){
	    $sql = "	SELECT 	a.nid_aula,
		                    UPPER(a.desc_aula) AS desc_aula,
		                    UPPER(s.desc_sede) AS desc_sede,
		                    UPPER(n.desc_nivel) AS desc_nivel,
		                    UPPER(g.desc_grado) AS desc_grado
					FROM 	aula a,
		                    sede s,
		                    nivel n,
		                    grado g
					WHERE 	UPPER(desc_aula) LIKE UPPER(?)
		            AND 	a.nid_sede  = s.nid_sede
                    AND	    a.nid_nivel = n.nid_nivel
                    AND	    a.nid_grado = g.nid_grado";
	    $resultado=$this->db->query($sql, array("%".$descAula."%"));
	    return $resultado->result();
	}
	
	function getAlumnosWithoutAula($nombre){
	    $sql = "      SELECT   p.nid_persona,
	                           CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombrecompleto,
	                           p.dni
                        FROM   persona p,
	                           persona_x_rol pr
                       WHERE   p.nid_persona NOT IN(
	                           SELECT      pa.__id_persona
	                           FROM        persona_x_aula pa
	                           WHERE       pa.year_academico = ".date("Y").")
	                     AND   p.nid_persona = pr.nid_persona
	                     AND   pr.nid_rol    = ".ID_ROL_ESTUDIANTE."
	                     AND   UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona)) LIKE UPPER(?)
	                ORDER BY   nombrecompleto";
	    $resultado=$this->db->query($sql, array("%".$nombre."%"));
	    return $resultado->result();
	}
	
	function getAulasByGrado($idNivel, $idSede, $idGrado) {
	    $sql = "	SELECT 	nid_aula,
		                    desc_aula
					FROM 	aula a
					WHERE 	nid_sede  = ?
		            AND 	nid_nivel = ?
                    AND	    nid_grado = ?";
	    $result = $this->db->query($sql, array($idSede, $idNivel, $idGrado));
	    return $result->result();
	}
	*/
}