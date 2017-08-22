<?php defined('BASEPATH') OR exit('No direct script access allowed');
//LAST-CODE: MU-028
class M_movimientos extends CI_Model {
    /////////////////FILTRO
    function getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$searchMagic,$offSet = 0) {
        $sql = "SELECT INITCAP(CONCAT(p.nom_persona,' ',p.ape_pate_pers,' ', p.ape_mate_pers)) as nombrecompleto,
                       INITCAP(p.nom_persona) as nombres,
                       UPPER(CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers, ',')) as apellidos,
                       da.cod_alumno_temp cod_alumno,
                       da.cod_familia,
                       p.foto_persona,
                       s.desc_sede,
                       g.desc_grado,
                       a.desc_aula,
                       n.desc_nivel,
                       p.nid_persona,
                       da.estado,
                       CASE WHEN p.foto_persona IS NULL 
                            THEN 'nouser.svg'
                            ELSE foto_persona
                       END AS foto_persona,
                       (SELECT COUNT(1)
        		          FROM pagos.movimiento          m,
        			           pagos.detalle_cronograma dc
        		         WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
        			       AND m._id_persona = p.nid_persona
        			       AND m.fecha_pago  IS NOT NULL
        			       AND m.fecha_pago  <= dc.fecha_descuento
        		      GROUP BY dc.fecha_vencimiento
        		      ORDER BY dc.fecha_vencimiento DESC
        		         LIMIT 1) adelanto,
            	       (SELECT COUNT(1) cuenta
    			          FROM pagos.movimiento m,
            			       pagos.detalle_cronograma dc
            			 WHERE dc.fecha_vencimiento < now()
            			   AND m.estado = 'VENCIDO'
            			   AND m._id_persona = p.nid_persona
            			   AND dc.id_detalle_cronograma = m._id_detalle_cronograma) cuotas_deuda
                  FROM persona         p,
                       persona_x_aula pa,
                       aula            a,
                       sede            s,
                       grado           g,
                       nivel           n,
                       sima.detalle_alumno   da
                 WHERE UNACCENT(LOWER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona))) LIKE UNACCENT(LOWER(?))
                   AND da.estado       = 'MATRICULADO'
                   AND CASE WHEN( ? IS NOT NULL) THEN s.nid_sede  = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN n.nid_nivel = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN g.nid_grado = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN a.nid_aula  = ? ELSE 1 = 1 END
                   --AND p.flg_acti = '".FLG_ACTIVO."'
                   AND pa.__id_persona   = p.nid_persona
                   AND a.nid_aula        = pa.__id_aula
                   AND a.nid_sede        = s.nid_sede
                   AND pa.year_academico = (SELECT year_academico
                                              FROM persona_x_aula
                                             WHERE __id_persona = p.nid_persona
                                          ORDER BY year_academico DESC
                                            LIMIT 1)
                   AND a.nid_grado     = g.nid_grado
                   AND a.nid_nivel     = n.nid_nivel
                   AND da.nid_persona  = p.nid_persona
                 UNION 
               SELECT INITCAP(CONCAT(p.nom_persona,' ',p.ape_pate_pers,' ', p.ape_mate_pers)) as nombrecompleto,
                      INITCAP(p.nom_persona) as nombres,
                      UPPER(CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers, ',')) as apellidos,
                      da.cod_alumno_temp cod_alumno,
                      da.cod_familia,
                      p.foto_persona,
                      s.desc_sede,
                      g.desc_grado,
                      '-' as desc_aula,
                      n.desc_nivel,
                      p.nid_persona,
                      da.estado,
                      CASE WHEN p.foto_persona IS NULL 
                           THEN 'nouser.svg'
                           ELSE foto_persona
                      END AS foto_persona,
                      (SELECT COUNT(1)
        		         FROM pagos.movimiento          m,
        			          pagos.detalle_cronograma dc
        		        WHERE m._id_detalle_cronograma = dc.id_detalle_cronograma
        			      AND m._id_persona = p.nid_persona
        			      AND m.fecha_pago  IS NOT NULL
        			      AND m.fecha_pago  <= dc.fecha_descuento
        		     GROUP BY dc.fecha_vencimiento
        		     ORDER BY dc.fecha_vencimiento DESC
        		        LIMIT 1) adelanto,
            	      (SELECT COUNT(1) cuenta
    			         FROM pagos.movimiento m,
            			      pagos.detalle_cronograma dc
            			WHERE dc.fecha_vencimiento < now()
            			  AND m.estado = 'VENCIDO'
            			  AND m._id_persona = p.nid_persona
            			  AND dc.id_detalle_cronograma = m._id_detalle_cronograma) cuotas_deuda
                 FROM persona              p,
                      sede                 s,
                      grado           	   g,
                      nivel                n,
                      sima.detalle_alumno da
                WHERE da.estado IN ('".ALUMNO_PREREGISTRO."','".ALUMNO_REGISTRADO."','".ALUMNO_MATRICULABLE."','".ALUMNO_PROM_PREREGISTRO."','".ALUMNO_PROM_REGISTRO."','".ALUMNO_RETIRADO."','".ALUMNO_VERANO."')
                  AND UNACCENT(LOWER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona))) LIKE UNACCENT(LOWER(?))
                  AND CASE WHEN( ? IS NOT NULL) THEN da.id_sede_ingreso  = ? ELSE 1 = 1 END
                  AND CASE WHEN( ? IS NOT NULL) THEN da.id_nivel_ingreso = ? ELSE 1 = 1 END
                  AND CASE WHEN( ? IS NOT NULL) THEN da.id_grado_ingreso = ? ELSE 1 = 1 END
                  AND da.nid_persona  	   = p.nid_persona
                  AND da.id_sede_ingreso  = s.nid_sede
                  AND da.id_nivel_ingreso = n.nid_nivel
                  AND da.id_grado_ingreso = g.nid_grado
             ORDER BY estado DESC, apellidos
                OFFSET ".$offSet." LIMIT 10";
        $result = $this->db->query($sql,array("%".$searchMagic."%",$idSede,$idSede,$idNivel,$idNivel,$idGrado,$idGrado,$idAula,$idAula,"%".$searchMagic."%",$idSede,$idSede,$idNivel,$idNivel,$idGrado,$idGrado));
        return $result->result();
    }
    
    function getNombresParentescoByPersona($idPersona) {
        $sql = "SELECT p.nid_persona,
                       p.nom_persona,
                       da.cod_familia,
                       p.foto_persona,
                       CASE WHEN p.foto_persona IS NULL 
                            THEN 'nouser.svg'
                            ELSE foto_persona
                       END AS foto_persona,
                       (CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona))) as nombrecompleto,
                       CASE WHEN p.nid_persona = ? THEN 'true'
                                                       ELSE ''
                       END AS principal
                  FROM persona p,
                       sima.detalle_alumno   da
                 WHERE CASE WHEN (da.cod_familia IS NOT NULL) 
                            THEN  da.cod_familia = (SELECT cod_familia
                		                      FROM sima.detalle_alumno
                			             WHERE nid_persona = ?)
                	        ELSE da.nid_persona = ?
                   END
                   AND da.nid_persona  = p.nid_persona
                ORDER BY principal desc";
        $result = $this->db->query($sql,array($idPersona,$idPersona,$idPersona));
        return $result->result();
    }
    
    //BEGIN DETALLE PERSONA
    function getAllCompromisosByAlumno($idAlumno) {
        $sql = "SELECT *,
                       to_char(todo.fec_vencimiento,'DD/MM/YYYY') fec_vencimiento
                  FROM (
                SELECT m.id_movimiento,
                       m.monto monto,
                       CASE WHEN (m.mora_acumulada IS NULL OR m.mora_acumulada = 0) THEN '-' 
                                ELSE to_char(m.mora_acumulada, '99990D99')
                       END AS mora_acumulada,
                       m.monto_final,
                       CASE WHEN(dc.fecha_descuento IS NOT NULL AND m._id_concepto = '".CONCEPTO_SERV_ESCOLAR."') THEN to_char(dc.fecha_descuento, 'DD/MM/YYYY') 
                				ELSE '-' 
                       END AS fecha_descuento,
                       m.estado estado,
                       CASE WHEN (m.monto_adelanto IS NULL OR m.monto_adelanto = 0) THEN '-' 
                                ELSE to_char(m.monto_adelanto, '99999999D99')
                       END AS monto_adelanto,
                       CASE WHEN(m.fecha_pago IS NOT NULL) THEN to_char(m.fecha_pago, 'DD/MM/YYYY') 
                				ELSE '-' 
                       END AS fecha_pago,
                       CASE WHEN (m.descuento_acumulado IS NULL OR m.descuento_acumulado = 0) THEN '-' 
                                ELSE to_char(m.descuento_acumulado, '99999999D99')
                       END AS descuento_acumulado,
                       CASE WHEN(m._id_concepto = ".CONCEPTO_SERV_ESCOLAR.") THEN dc.desc_detalle_crono
                				ELSE c.desc_concepto 
                       END AS desc_cuota,
                       /*CASE WHEN(m._id_concepto = 1) 
                            THEN dc.fecha_vencimiento
                	        ELSE  m.fecha_vencimiento_aux
                       END AS fec_vencimiento,*/
                       m.fecha_vencimiento_aux fec_vencimiento,
                       CASE WHEN cro._id_tipo_cronograma <> 2 
                            THEN 0
                            ELSE m._id_concepto
                       END AS _id_concepto,
                       CASE WHEN(m.estado = '".ESTADO_PAGADO."')    THEN 'default' 
                                WHEN(m.estado = '".ESTADO_POR_PAGAR."') THEN 'success'
                                WHEN(m.estado = '".ESTADO_VENCIDO."')   THEN 'danger'
                                WHEN(m.estado = '".ESTADO_CANCELADO."') THEN 'info'
                	            ELSE ''		             
                       END AS class,
                       CASE WHEN ((SELECT COUNT(1) FROM pagos.documento d2 WHERE d2._id_movimiento = m.id_movimiento AND d2.tipo_documento = '".DOC_BOLETA."' AND m.estado = '".ESTADO_PAGADO."'  AND d2.flg_anulado <> '1' AND (m._id_concepto = '".CONCEPTO_SERV_ESCOLAR."' OR m._id_concepto = '".CUOTA_INGRESO."') ) = 1) 
                                 THEN '<i class=\"mdi mdi-content_copy\" style=\"color: #fff;font-size: 11px;padding-right: 6px;\"></i>'
                                 ELSE ''
                       END AS boleta_icon,
                       CASE WHEN (m.estado <> '".ESTADO_PAGADO."' OR ( m._id_concepto <> '".CONCEPTO_SERV_ESCOLAR."' AND  m._id_concepto <> '".CUOTA_INGRESO."') OR (SELECT COUNT(1) 
                                                                                                                             FROM pagos.documento d2 
                                                                                                                            WHERE d2._id_movimiento = m.id_movimiento
                                                                                                                              AND d2.estado <> '".ESTADO_ANULADO."'
                                                                                                                              AND d2.tipo_documento = '".DOC_BOLETA."') = 1) THEN 'disabled' 
                                                                                                                     ELSE '' 
                       END AS flg_disabled_boleta,
                       CASE WHEN ((SELECT COUNT(1)
                                         FROM pagos.documento
                                        WHERE _id_movimiento = m.id_movimiento
                                          AND estado <> '".ESTADO_ANULADO."') = 0) THEN 'disabled'
                                                                                   ELSE ''
                       END AS flg_disabled_docs,
                       CASE WHEN ((m.estado IN('VENCIDO','POR PAGAR') AND ((SELECT COUNT(1)
                                                                              FROM pagos.audi_movimiento am2
                                                                             WHERE am2._id_movimiento       = m.id_movimiento
                                                                               AND am2.accion               = 'PAGAR'
                                                                               AND am2.audi_fec_regi::date >= current_date) = 0 )) ) 
                                                          THEN 'no-pagado'
                            WHEN (m.estado = 'PAGADO')    THEN ''  
                            WHEN (m.estado = 'POR PAGAR') THEN 'disabled'
                       END AS flg_disabled_anular,
                       CASE WHEN(m.fecha_registro IS NOT NULL) THEN to_char(m.fecha_registro, 'DD/MM/YYYY') 
                				ELSE '-' 
                       END AS fecha_registro,
                       dc._id_cronograma,
                       m.fecha_registro::date AS fec_regi_aux,
                       CASE WHEN ((SELECT id_movimiento 
                                     FROM pagos.movimiento
                                    WHERE estado IN('POR PAGAR','VENCIDO')
                                      AND _id_persona = m._id_persona
                                 ORDER BY fecha_vencimiento_aux ASC
                                    LIMIT 1) = m.id_movimiento)
                            THEN 'begin'
                            ELSE null
                       END AS flg_inicio,
                       CASE WHEN (m.estado = '".ESTADO_PAGADO."' AND m.flg_lugar_pago = '".FLG_BANCO."') THEN CONCAT('<i class=\"mdi mdi-account_balance\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"',m.desc_banco_pago,'\"></i>')
                            WHEN (m.estado = '".ESTADO_PAGADO."' AND m.flg_lugar_pago = '".FLG_COLEGIO."') THEN '<i class=\"mdi mdi-school\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Colegio\"></i>'
                            ELSE '-'
                       END AS lugar_pago
                  FROM pagos.concepto c,
                       pagos.movimiento m
                       LEFT JOIN pagos.documento d ON(d._id_movimiento = m.id_movimiento 
                                                  AND d.estado         <> 'ANULADO' 
                                                  AND d.nro_documento  = (SELECT nro_documento
                                                                            FROM pagos.documento
                                                                           WHERE _id_movimiento = m.id_movimiento
                                                                           LIMIT 1))
                       LEFT JOIN pagos.detalle_cronograma dc ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
                       LEFT JOIN pagos.cronograma        cro ON(dc._id_cronograma        = cro.id_cronograma) 
                 WHERE m._id_persona = ?
                   AND m._id_concepto = c.id_concepto
                   AND m.estado <> '".ESTADO_ANULADO."') todo
              ORDER BY todo.fec_vencimiento DESC,todo.fecha_registro DESC";
        $result = $this->db->query($sql,array($idAlumno));
        return $result->result();
    }
    
    function verificaDeudaByAlumno($idAlumno) {
        $sql = "SELECT COUNT(1) cuenta
                  FROM pagos.movimiento m,
                       pagos.detalle_cronograma dc
                 WHERE dc.fecha_vencimiento < current_date
                   AND m.estado = '".ESTADO_VENCIDO."'
                   AND m._id_persona = ?
                   AND dc.id_detalle_cronograma = m._id_detalle_cronograma";
        $result = $this->db->query($sql,array($idAlumno));
        return $result->row()->cuenta;
    }
    
    /**
     * Query que retorna las deudas de cronograma vencidas y por pagar de un estudiante,
     * se usa en los cards del modulo de matricula para visualizar el detalle
     * de deudas pendientes
     * @param integer $idEstudiante
     * @author dfloresgonz
     * @since 02.12.2016
     * @return array con las deudas del estudiante
     */
    function getDeudasByEstudiante($idEstudiante) {
        $sql = "SELECT ROW_NUMBER() OVER (ORDER BY fecha_vencimiento_aux) as row_num,
                       dc.desc_detalle_crono AS desc_pago,
                       m.monto,
                       ROUND(m.mora_acumulada, 2) AS mora_acumulada,
                       m.monto_final,
                       TO_CHAR(m.fecha_vencimiento_aux, 'DD/MM/YYYY') AS fec_venc,
                       m.estado,
                       CASE m.estado
                            WHEN 'VENCIDO'   THEN 'danger'
                            WHEN 'POR PAGAR' THEN 'success'
                            ELSE NULL END AS clase_css
                  FROM pagos.movimiento m,
                       pagos.detalle_cronograma dc
                 WHERE m.estado IN ('".ESTADO_VENCIDO."', '".ESTADO_POR_PAGAR."')
                   AND m._id_persona            = ?
                   AND dc.id_detalle_cronograma = m._id_detalle_cronograma
                ORDER BY fecha_vencimiento_aux";
        $result = $this->db->query($sql, array($idEstudiante));
        return $result->result_array();
    }
    
    //END DETALLE PERSONA
    
    function getNextCorrelativo($idCompromiso) {
        $sql = "SELECT COUNT(1) + 1 as cuenta
                  FROM pagos.audi_movimiento
                 WHERE _id_movimiento = ?";
        $result = $this->db->query($sql,array($idCompromiso));
        return $result->row()->cuenta;
    }
    
    function getNextCorrelativoArray($idCompromiso) {
        $sql = "SELECT COUNT(1) + 1 as cuenta,_id_movimiento
                  FROM pagos.audi_movimiento
                 WHERE _id_movimiento IN ?
              GROUP BY _id_movimiento
              ORDER BY _id_movimiento";
        $result = $this->db->query($sql,array($idCompromiso));
        return $result->result();
    }
    
    //REGISTRAR PAGO COMPROMISO
    function pagarMovimientos($arrayEditar,$arrayAuditoria,$arrayRecibo,$arrayUpdateCorre,$arrayUpdateDeta) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $this->db->trans_begin();
        try{
            if(count($arrayAuditoria) != count($arrayEditar) || count($arrayRecibo) != count($arrayAuditoria) || !isset($arrayUpdateCorre['numero_correlativo'])){
                throw new Exception('MU-001');
            }
            //CAMBIAR ESTADO A PAGADO
            $cont = 0;
            foreach($arrayEditar as $row){
                $this->db->where('id_movimiento',$row['id_movimiento']);
                unset($row['id_movimiento']);
                $this->db->update('pagos.movimiento',$row);
                if($this->db->trans_status() === FALSE){
                    throw new Exception('MU-002');
                }
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayEditar)){
                throw new Exception('MU-003');
            }
            //END
            //REGISTRAR EN LA TABLA DE AUDITORIA
            $cont = 0;
            foreach($arrayAuditoria as $row){
                $this->db->insert('pagos.audi_movimiento',$row);
                if($this->db->trans_status() === FALSE){
                    throw new Exception('MU-004');    
                }
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayAuditoria)){
                throw new Exception('MU-005');
            }
            //END
            //REGISTRAR EN LA TABLA DOCUMENTO
            $cont = 0;
            foreach($arrayRecibo as $row){
                if($row['accion'] == INSERTA){
                    unset($row['accion']);
                    $this->db->insert('pagos.documento',$row);
                } else{
                    $this->db->where('_id_movimiento' , $row['_id_movimiento']);
                    $this->db->where('tipo_documento' , $row['tipo_documento']);
                    unset($row['tipo_documento']);
                    unset($row['_id_movimiento']);
                    unset($row['accion']);
                    unset($row['fecha_registro']);
                    $this->db->update('pagos.documento',$row);
                }
                if($this->db->trans_status() === FALSE){
                    throw new Exception('MU-006');
                }
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayRecibo)){
                throw new Exception('MU-007');
            }
            //END
            //ACTUALIZAR TABLA CORRELATIVO
            if($arrayUpdateCorre['accion'] == ACTUALIZA){
                $this->db->where('_id_sede'        , $arrayUpdateCorre['_id_sede']);
                $this->db->where('tipo_documento'  , $arrayUpdateCorre['tipo_documento']);
                $this->db->where('tipo_movimiento' , $arrayUpdateCorre['tipo_movimiento']);
                unset($arrayUpdateCorre['_id_sede']);
                unset($arrayUpdateCorre['tipo_documento']);
                unset($arrayUpdateCorre['accion']);
                unset($arrayUpdateCorre['tipo_movimiento']);
                $this->db->update('pagos.correlativo'    , $arrayUpdateCorre);
            } else{
                unset($arrayUpdateCorre['accion']);
                $this->db->insert('pagos.correlativo'    , $arrayUpdateCorre);
            }
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-008');
            }
            if($arrayUpdateDeta['flg_actualiza'] != 0){
                $this->db->where('nid_persona' , $arrayUpdateDeta['nid_persona']);
                unset($arrayUpdateDeta['nid_persona']);
                unset($arrayUpdateDeta['flg_actualiza']);
                $this->db->update('sima.detalle_alumno' , $arrayUpdateDeta);
            }
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-009');
            }
            //END
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getCurrentCorrelativo($idSede,$tipo_doc,$tipo_mov,$nro_serie = null){
        $sql = "SELECT c.numero_correlativo 
                  FROM pagos.correlativo c
                 WHERE c._id_sede        = ?
                   AND c.tipo_documento  = ?
                   AND c.tipo_movimiento = ?
                   AND CASE WHEN (? IS NOT NULL) THEN  c.nro_serie = ? ELSE 1 =1  END";
        $result = $this->db->query($sql,array($idSede,$tipo_doc,$tipo_mov,$nro_serie,$nro_serie));
        if($result->num_rows() > 0) {
            return $result->row()->numero_correlativo;
        } else{
            return null;
        }
    }
    
    function getCurrentCorrelativoALumno($sede,$tipo_doc,$tipo_mov,$nro_serie = null) {
    	$sql = "SELECT c.numero_correlativo
                  FROM pagos.correlativo c
                 WHERE c._id_sede        = ?
                   AND c.tipo_documento  = ?
                   AND c.tipo_movimiento = ?
                   AND CASE WHEN (? IS NOT NULL) THEN  c.nro_serie = ? ELSE 1 =1  END";
    	$result = $this->db->query($sql,array($sede,$tipo_doc,$tipo_mov,$nro_serie,$nro_serie));
    	if($result->num_rows() > 0) {
    		return $result->row()->numero_correlativo;
    	} else{
    		return null;
    	}
    }
    
    function getEstadoConceptoByCompromiso($idCompromiso) {
        $sql = "SELECT m._id_concepto,
                       m.estado,
                       CASE WHEN (dc.fecha_vencimiento < current_date) 
                            THEN '".ESTADO_VENCIDO."' 
                            ELSE '".ESTADO_POR_PAGAR."'
                       END AS new_estado,
                       (SELECT COUNT(1)
                          FROM pagos.audi_movimiento
                         WHERE _id_movimiento = ?
                           AND audi_fec_regi >= current_date)
                  FROM pagos.concepto   c,
                       pagos.movimiento m
                       LEFT JOIN pagos.detalle_cronograma dc ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
                 WHERE m.id_movimiento = ?
                   AND m._id_concepto  = c.id_concepto";
        $result = $this->db->query($sql,array($idCompromiso,$idCompromiso));
        return $result->row_array();
    }
    
    //ANULAR COMPROMISO Y ANULAR SUS DOCUMENTOS AMARRADOS
    function anularMovimientoByPersona($arrayUpdateCompro,$arrayInsertAudi,$arrayInsertAudiBoleta) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $this->db->trans_begin();
        try{
            $this->db->where('id_movimiento',$arrayUpdateCompro['id_movimiento']);
            $idMovimiento = $arrayUpdateCompro['id_movimiento'];
            unset($arrayUpdateCompro['id_movimiento']);
            $this->db->update('pagos.movimiento',$arrayUpdateCompro);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-009');
            }
//             $this->db->insert('pagos.audi_movimiento',$arrayInsertAudi);
//             if($this->db->affected_rows() != 1){
//                 throw new Exception('MU-010');
//             }
            //UPDATE DOCUMENTO
            $count = $this->countDocumentosByMovimientoByDay($arrayInsertAudi['_id_movimiento']);
            $this->db->where('_id_movimiento' , $arrayInsertAudi['_id_movimiento']);
            $this->db->where('estado !='      , ESTADO_ANULADO);
            $this->db->where('fecha_registro >=' , date('Y-m-d'));
            unset($arrayInsertAudi['_id_movimiento']);
            $this->db->update('pagos.documento',array('estado' => ESTADO_ANULADO));
            if($this->db->affected_rows() != $count){
                throw new Exception('MU-011');
            }
            //END
            //INSERT AUDI DOCUMENTO
            $count = 0; 
            foreach($arrayInsertAudiBoleta as $row){
                $correlativoByMov = $this->m_movimientos->getNextCorrelativoByAudiDoc($row['_id_movimiento'],$row['tipo_documento']);
                $row['correlativo'] = $correlativoByMov;
                $this->db->insert('pagos.audi_documento',$row);
                if($this->db->trans_status() === FALSE){
                    throw new Exception('MU-012');
                }
                $count = $count + $this->db->affected_rows();
            }
            if($count != count($arrayInsertAudiBoleta)){
                throw new Exception('MU-013');
            }
            $this->db->where('audi_fec_regi >='  , date('Y-m-d'));
            $this->db->where('_id_movimiento' , $idMovimiento);
            $this->db->update('pagos.audi_movimiento',array('accion' => ANULAR));
            //END
            $data['msj']   = MSJ_ANL;
            $data['error'] = EXIT_SUCCESS;
            $this->db->trans_commit();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function countDocumentosByMovimiento($idCompromiso,$all = 'NADA') {
        $sql = "SELECT COUNT(1) as cuenta
                  FROM pagos.documento
                 WHERE _id_movimiento = ?
                   AND (('NADA' = ? ) OR ('NADA' <> ? AND tipo_documento = ?))
                   AND (('NADA' = ? AND estado <> '".ESTADO_ANULADO."') OR ('NADA' <> ? AND estado <> 'ANULADO'))";
        $result = $this->db->query($sql,array($idCompromiso,$all,$all,$all,$all,$all));
        return $result->row()->cuenta;
    }
    
    function countDocumentosByMovimientoByDay($idCompromiso,$all = 'NADA') {
        $sql = "SELECT COUNT(1) as cuenta
                  FROM pagos.documento
                 WHERE _id_movimiento = ?
                   AND (('NADA' = ? ) OR ('NADA' <> ? AND tipo_documento = ?))
                   AND (('NADA' = ? AND estado <> '".ESTADO_ANULADO."') OR ('NADA' <> ?))
                   AND fecha_registro::timestamp::date = current_date";
        $result = $this->db->query($sql,array($idCompromiso,$all,$all,$all,$all,$all));
        return $result->row()->cuenta;
    }
    
    function getAllDocumentosByMovimiento($idMov) {
        $sql = "SELECT tipo_documento,
                       nro_documento
                  FROM pagos.documento
                 WHERE _id_movimiento = ?
                   AND estado         <> '".ESTADO_ANULADO."'";
        $result = $this->db->query($sql,array($idMov));
        return $result->result();
    }
    
    function getNextCorrelativoByAudiDoc($idCompromiso,$tipo_documento) {
        $sql = "SELECT COUNT(1) + 1 as cuenta
                  FROM pagos.audi_documento
                 WHERE _id_movimiento = ?
                   AND tipo_documento = ?";
        $result = $this->db->query($sql,array($idCompromiso,$tipo_documento));
        return $result->row()->cuenta;
    }
    
    function verifyDocumentExist($idCompromiso,$tipo_documento) {
        $sql = "SELECT COUNT(1) cuenta
                  FROM pagos.documento
                 WHERE _id_movimiento = ?
                   AND tipo_documento = ?";
        $result = $this->db->query($sql,array($idCompromiso,$tipo_documento));
        return $result->row()->cuenta;
    }
    
    function registrarBoletaByCompromiso($array,$arrayUpdateCorrelativo,$arrayUpdateMovi = array()) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            if($array['accion'] == INSERTA){
                unset($array['accion']);
                $this->db->insert('pagos.documento',$array);
            } else{
                $this->db->where('_id_movimiento' , $array['_id_movimiento']);
                $this->db->where('tipo_documento' , $array['tipo_documento']);
                unset($array['tipo_documento']);
                unset($array['_id_movimiento']);
                unset($array['accion']);
                unset($array['fecha_registro']);
                $this->db->update('pagos.documento',$array);
            }
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-012');
            }   
            if($this->db->trans_status() === FALSE){
                throw new Exception('MU-013');
            }
            if($arrayUpdateCorrelativo['accion'] == INSERTA){
                unset($arrayUpdateCorrelativo['accion']);
                $this->db->insert('pagos.correlativo',$arrayUpdateCorrelativo);
            } else{
                $this->db->where('tipo_movimiento' , $arrayUpdateCorrelativo['tipo_movimiento']);
                $this->db->where('_id_sede'        , $arrayUpdateCorrelativo['_id_sede']);
                $this->db->where('tipo_documento'  , $arrayUpdateCorrelativo['tipo_documento']);
                unset($arrayUpdateCorrelativo['accion']);
                unset($arrayUpdateCorrelativo['_id_sede']);
                unset($arrayUpdateCorrelativo['tipo_documento']);
                unset($arrayUpdateCorrelativo['tipo_movimiento']);
                $this->db->update('pagos.correlativo',$arrayUpdateCorrelativo);
            }
            if(count($arrayUpdateMovi) > 0){
                $this->db->where('id_movimiento' ,$arrayUpdateMovi['id_movimiento']);
                unset($arrayUpdateMovi['id_movimiento']);
                $this->db->update('pagos.movimiento',$arrayUpdateMovi);
                if($this->db->affected_rows() != 1){
                    throw new Exception('MU-027');
                }
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_GEN;
            $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getDataDocumentos($idCompromiso) {
        $sql = "SELECT INITCAP(d.tipo_documento) as tipo_documento,
                       d.nro_serie,
                       d.nro_documento,
                       d.flg_impreso,
                       d.estado,
                       d._id_movimiento,
                       CASE WHEN d.tipo_documento = 'RECIBO' AND fecha_registro::timestamp::date < current_date 
                            THEN 'disabled' 
                            ELSE '' 
                       END AS disabled,
                       CONCAT(nro_serie,'-',nro_documento) nro_documento, 
                       (SELECT CASE WHEN(d.tipo_documento = '".DOC_BOLETA."')
                                    THEN CONCAT(nro_serie,'-',nro_documento)
                                    ELSE nro_documento
                               END
                          FROM pagos.documento 
                         WHERE _id_movimiento = ?
                           AND tipo_documento = d.tipo_documento
                           AND estado = '".ESTADO_ANULADO."' 
                      ORDER BY nro_documento DESC
                         LIMIT 1) last_document
                  FROM pagos.documento d
                 WHERE d._id_movimiento = ?
                   AND d.flg_anulado    <> '1'";
        $result = $this->db->query($sql,array($idCompromiso,$idCompromiso));
        return $result->result();
    }
    
    function deleteDocumentoByTipo($idComp,$tipoDoc){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $this->db->where('_id_movimiento'  , $idComp);
            $this->db->where('tipo_documento'  , $tipoDoc);
            $this->db->update('pagos.documento', array('estado' => ESTADO_ANULADO));
        } catch(Exception $e){
            
        }
        return $data;
    }
    
    function insertCompromiso($array) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->insert('pagos.movimiento',$array);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-014');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getColaboradoresByFiltro($nombre, $apellidos, $dni, $idArea, $searchMagic, $offSet = 0) {
        $sql = "SELECT UPPER(CONCAT(p.ape_pate_pers , ' ', p.ape_mate_pers)) apellidos,
                       max(INITCAP(p.nom_persona))as nombres,
                       CASE WHEN (p.nro_documento IS NOT NULL)
                            THEN p.nro_documento
                            ELSE '-'
                       END AS nro_documento,
                       CASE WHEN (max(p.telf_pers) IS NOT NULL)
                            THEN max(p.telf_pers)
                            ELSE '-'
                       END AS telf_pers,
                       CASE WHEN (max(s.desc_sede) IS NOT NULL)
                            THEN max(s.desc_sede)
                            ELSE '-' 
                       END AS desc_sede,
                       CASE WHEN (max(r.desc_rol) IS NOT NULL)
                            THEN max(r.desc_rol)
                            ELSE '-'
                       END AS desc_rol,
                       CASE WHEN (max(n.desc_nivel) IS NOT NULL)
                            THEN max(n.desc_nivel)
                            ELSE '-'
                       END AS desc_nivel,
                       CASE WHEN (max(a.desc_area) IS NOT NULL)
                            THEN max(a.desc_area)
                            ELSE '-' 
                       END AS desc_area,
                       max(p.nid_persona) nid_persona,
                       max(a.id_area)
                  FROM persona_x_rol           pr,
                       rol                      r,
                       rrhh.personal_detalle   pd
                       LEFT JOIN sede           s ON(pd.id_sede_control  = s.nid_sede)
                       LEFT JOIN nivel          n ON(pd.id_nivel_control = n.nid_nivel)
                       LEFT JOIN area           a ON(pd.id_area_especifica = a.id_area),
                       persona                  p
                 WHERE r.nid_rol NOT IN ('".ID_ROL_ESTUDIANTE."','".ID_ROL_FAMILIA."')
                   AND pd.id_persona  = p.nid_persona 
                   AND LOWER(CONCAT(p.nom_persona,' ', p.ape_pate_pers,' ',p.ape_mate_pers)) LIKE LOWER(?)
                   AND ((p.nro_documento IS NOT NULL AND p.nro_documento LIKE ?) OR (p.nro_documento IS NULL AND 1 = 1))
                   AND CASE WHEN(? IS NOT NULL) THEN a.id_area = ? ELSE 1 = 1 END
                   AND pr.nid_persona = p.nid_persona
                   AND r.nid_rol      = pr.nid_rol
                   AND p.nid_persona  = pr.nid_persona
              GROUP BY p.nid_persona
              ORDER BY apellidos            
                 LIMIT 12 OFFSET ".$offSet;
        $result = $this->db->query($sql,array("%".$searchMagic."%","%".$dni."%",$idArea,$idArea));
        return $result->result();
    }
    
    function getAllEgresosByPersona($idPersona,$flg_regi) {
        $sql = "SELECT m.id_movimiento,
                       c.desc_concepto,
                       to_char(m.fecha_registro, 'DD/MM/YYYY') fecha_registro,
                       m.fecha_registro as hora,
                       m.monto,
                       m.estado,
                       m._id_persona
                  FROM pagos.movimiento m,
                       pagos.concepto c
                 WHERE m._id_persona = ?
                   AND m.estado <> '".ESTADO_ANULADO."'
                   AND m.flg_regi_movi = ?
                   AND m._id_concepto = c.id_concepto
              ORDER BY m.fecha_registro DESC";
        $result = $this->db->query($sql,array($idPersona,$flg_regi));
        return $result->result();
    }
    
    function registrarEgresoByPersona($arrayInsertMovi,$arrayInsertAudi,$arrayInsertDocumento,$arrayUpdateCorrelativo) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            //INSERTAR EN MOVIMIENTO
            $this->db->insert('pagos.movimiento',$arrayInsertMovi);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-015');
            }
            //CAPTURAR Y A�ADIR SUS CAMPOS
            $idMovi      = $this->db->insert_id();
            $correlativo = $this->getNextCorrelativo($idMovi);
            $arrayInsertAudi['_id_movimiento'] = $idMovi;
            $arrayInsertAudi['correlativo']    = $correlativo;
            $arrayInsertDocumento['_id_movimiento'] = $idMovi;
            //REGISTRAR DOCUMENTO
            $this->db->insert('pagos.documento',$arrayInsertDocumento);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-016');
            }
            //REGISTRAR EN AUDITORIA
            $this->db->insert('pagos.audi_movimiento',$arrayInsertAudi);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-017');
            }
            //ACTUALIZAR EL CORRELATIVO ACTUAL
            $ipoDoc = _encodeCI($arrayUpdateCorrelativo['tipo_documento']);
            if($arrayUpdateCorrelativo['accion'] == ACTUALIZA){
                $this->db->where('_id_sede'        , $arrayUpdateCorrelativo['_id_sede']);
                $this->db->where('tipo_documento'  , $arrayUpdateCorrelativo['tipo_documento']);
                $this->db->where('tipo_movimiento' , $arrayUpdateCorrelativo['tipo_movimiento']);
                unset($arrayUpdateCorrelativo['_id_sede']);
                unset($arrayUpdateCorrelativo['tipo_documento']);
                unset($arrayUpdateCorrelativo['accion']);
                unset($arrayUpdateCorrelativo['tipo_movimiento']);
                $this->db->update('pagos.correlativo'    , $arrayUpdateCorrelativo);
                unset($arrayUpdateCorrelativo['tipo_movimiento']);
            } else{
                unset($arrayUpdateCorrelativo['accion']);
                $this->db->insert('pagos.correlativo'    , $arrayUpdateCorrelativo);
            }
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-018');
            }
            //////////////////////////////////
            $data['compromiso'] = _encodeCI($idMovi);
            $data['tipo_doc']   = _encodeCI($ipoDoc);
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function registraConceptoAndEgreso($arrayInsertConcepto,$arrayInsertEgreso,$arrayInsertDocumento,$arrayUpdateCorre,$arrayInsertAuditoria) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            //INSERT CONCEPTO
            $this->db->insert('pagos.concepto',$arrayInsertConcepto);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-019');
            }
            $idConcepto = $this->db->insert_id();
            //INSERTA EGRESO
            $arrayInsertEgreso['_id_concepto'] = $idConcepto;
            $this->db->insert('pagos.movimiento',$arrayInsertEgreso);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-020');
            }
            $idMovimiento = $this->db->insert_id();
            //REGISTRAR EN AUDITORIA
            $correlativo = $this->getNextCorrelativo($idMovimiento);
            $arrayInsertAuditoria['_id_movimiento'] = $idMovimiento;
            $arrayInsertAuditoria['correlativo']    = $correlativo;
            $this->db->insert('pagos.audi_movimiento',$arrayInsertAuditoria);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-021');
            }
            //INSERTA DOCUMENTO
            $arrayInsertDocumento['_id_movimiento'] = $idMovimiento; 
            $this->db->insert('pagos.documento',$arrayInsertDocumento);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-022');
            }
            //ACTUALIZAR EL CORRELATIVO ACTUAL
            if($arrayUpdateCorre['accion'] == ACTUALIZA){
                $this->db->where('_id_sede'        , $arrayUpdateCorre['_id_sede']);
                $this->db->where('tipo_documento'  , $arrayUpdateCorre['tipo_documento']);
                $this->db->where('tipo_movimiento' , $arrayUpdateCorre['tipo_movimiento']);
                unset($arrayUpdateCorre['_id_sede']);
                unset($arrayUpdateCorre['tipo_documento']);
                unset($arrayUpdateCorre['accion']);
                unset($arrayUpdateCorre['tipo_movimiento']);
                $this->db->update('pagos.correlativo'    , $arrayUpdateCorre);
            } else{
                unset($arrayUpdateCorre['accion']);
                $this->db->insert('pagos.correlativo'    , $arrayUpdateCorre);
            }
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-023');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            $this->db->trans_commit();
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getSerieActivaBySede($idSede) {
        $sql = "SELECT ss.nro_serie
                  FROM pagos.serie_x_sede ss
                 WHERE ss._id_sede = ?
                   AND ss.estado = '".FLG_ESTADO."'";
        $result = $this->db->query($sql,array($idSede));
        if($result->num_rows() > 0) {
            return $result->row()->nro_serie;
        } else{
            return null;
        }
    }
    
    function getDataCreateRecibo($idCompromiso) {
        $sql = "SELECT to_char(am.audi_fec_regi,'DD/MM/YYYY') fecha,
                       to_char(am.audi_fec_regi,'HH:SS:MM PM') hora,
                       m.monto_final,
                       c.desc_concepto,
                       d.nro_documento,
                       d._id_sede,
                       m._id_persona,
                       am.id_pers_regi
                  FROM pagos.audi_movimiento am,
                       pagos.movimiento m,
                       pagos.concepto c,
                       pagos.documento d
                 WHERE am._id_movimiento = ?
                   AND m.id_movimiento   = am._id_movimiento
                   AND c.id_concepto     = m._id_concepto
                   AND d._id_movimiento  = m.id_movimiento
                   AND d.tipo_documento  = '".DOC_RECIBO."'";
        $result = $this->db->query($sql,array($idCompromiso));
        return $result->row_array();
    }
    
    function getNewMontoFinalAnular($idCompromiso) {
        $sql = "SELECT SUM(am.monto_pagado) current_pago,
                       max(m.monto_adelanto) monto_adelanto,
                       max(m.monto_final) monto_final
                  FROM pagos.audi_movimiento am,
                       pagos.movimiento m
                 WHERE m.id_movimiento  = ?
                   AND m.id_movimiento  = am._id_movimiento
                   AND am.accion        <> '".ANULAR."'
                   AND am.audi_fec_regi >= current_date ";
        $result = $this->db->query($sql,array($idCompromiso));
        return $result->row_array();
    }
    
    function getNroDocumentoByEgreso($idEgreso) {
        $sql = "SELECT nro_documento
                  FROM pagos.documento
                 WHERE _id_movimiento = ?
                   AND tipo_documento = '".DOC_RECIBO."'";
        $result = $this->db->query($sql,array($idEgreso));
        return $result->row()->nro_documento;
    }
    
    function getFechaRegistroPagoCompromiso($nroDocumento,$tipoDoc) {
        $sql = "SELECT m.fecha_pago::date
                  FROM pagos.documento  d,
                       pagos.movimiento m
                 WHERE d.tipo_documento  LIKE ?
                   AND d.estado          <>   'ANULADO'
                   AND m.tipo_movimiento LIKE '".TIPO_INGRESO."'
                   AND d._id_movimiento = m.id_movimiento
                   AND CONCAT(d.nro_serie,'-', d.nro_documento)  = ?";
        $result = $this->db->query($sql, array($tipoDoc,$nroDocumento));
        if($result->num_rows() > 0){
            return $result->row()->fecha_pago;
        } else {
            return null;
        }
    }
    
    function anularDocumentByTipoByCompromiso($arrayUpdateDoc,$arrayInsertAudiDoc,$arrayInsertDoc,$arrayUpdateCorrelativo, $fechaPagoDocumento) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            //ANULA EL DOCUMENTO
            $arrayUpdate = array('estado'      => ESTADO_ANULADO, 
                                 'flg_anulado' => 1
                                );
            if($arrayUpdateDoc['tipoDocumento'] == DOC_BOLETA){
                $estado = $this->getActualState($arrayUpdateDoc['nroDoc'],$arrayUpdateDoc['compromiso']);
                if($estado == DOC_IMPRESO){
                    unset($arrayUpdate['estado']);
                }
            }
            $this->db->where('_id_movimiento'  , $arrayUpdateDoc['compromiso']);
            $this->db->where('nro_documento'   , $arrayUpdateDoc['nroDoc']);
            $this->db->where('tipo_documento'  , $arrayUpdateDoc['tipoDocumento']);
            $this->db->update('pagos.documento', $arrayUpdate);
//             throw new Exception('AUX');
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-024');
            }
            //INSERTA AUDITORIA DEL DOCUMENTO
            $this->db->insert('pagos.audi_documento', $arrayInsertAudiDoc);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-025');
            }
            //INSERTA NUEVO DOCUMENTO
            unset($arrayInsertDoc['accion']);
            $this->db->insert('pagos.documento',$arrayInsertDoc);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-026');
            }
            //ACTUALIZA CORRELATIVO
            if($arrayUpdateCorrelativo['accion'] == INSERTA){
                unset($arrayUpdateCorrelativo['accion']);
                $this->db->insert('pagos.correlativo',$arrayUpdateCorrelativo);
            } else{
                $this->db->where('tipo_movimiento' , $arrayUpdateCorrelativo['tipo_movimiento']);
                $this->db->where('_id_sede'        , $arrayUpdateCorrelativo['_id_sede']);
                $this->db->where('tipo_documento'  , $arrayUpdateCorrelativo['tipo_documento']);
                unset($arrayUpdateCorrelativo['accion']);
                unset($arrayUpdateCorrelativo['_id_sede']);
                unset($arrayUpdateCorrelativo['tipo_documento']);
                unset($arrayUpdateCorrelativo['tipo_movimiento']);
                $this->db->update('pagos.correlativo',$arrayUpdateCorrelativo);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getFirstCompromisosByPersona($idPersona,$compromisos) {
        $sql = "SELECT m.id_movimiento,m._id_detalle_cronograma
                  FROM pagos.movimiento m
                 WHERE m._id_persona  = ?
                   AND m._id_concepto = ".CONCEPTO_SERV_ESCOLAR."
                   AND m.estado       <> '".ESTADO_PAGADO."'
                ORDER BY fecha_vencimiento_aux
                 LIMIT (SELECT count(1) 
                          FROM pagos.movimiento m,
                               pagos.detalle_cronograma dc,
                               pagos.cronograma          c
                         WHERE dc.id_detalle_cronograma = m._id_detalle_cronograma
                           AND c.id_cronograma          = dc._id_cronograma
                           AND c._id_tipo_cronograma    = 2 
                           AND m.id_movimiento          IN ? )";
        $result = $this->db->query($sql,array($idPersona,$compromisos));
        return $result->result();
    }
    
    function getCountCompromisosPorPagarVencidosByPersona($idPersona) {
        $sql = "SELECT COUNT(1) cuenta
                  FROM pagos.movimiento m
                 WHERE m._id_persona = ?
                   AND m.estado      <> '".ESTADO_PAGADO."'";
        $result = $this->db->query($sql,array($idPersona));
        return $result->row()->cuenta;
    }
    
    function getDataByDocumento($idMov) {
        $sql = "SELECT m._id_persona,
                       d.nro_documento,
                       to_char(am.audi_fec_regi,'DD/MM/YYYY') fecha_pago,
                       CASE WHEN(m._id_concepto = 1) THEN dc.desc_detalle_crono
                                                     ELSE c.desc_concepto 
                       END AS concepto,
                       am.monto_pagado as importe,
                       am.id_pers_regi,
                       '00565' codigo
                  FROM pagos.audi_movimiento am,
                       pagos.documento d,
                       pagos.concepto c,
                       pagos.movimiento m
                       LEFT JOIN pagos.detalle_cronograma dc ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
                 WHERE m.id_movimiento  IN  ?
                   AND d.tipo_documento = '".DOC_RECIBO."'
                   AND d.estado         = '".ESTADO_CREADO."'
                   AND am.accion        = '".PAGAR."'
                   AND m.id_movimiento  = am._id_movimiento
                   AND m.id_movimiento  = d._id_movimiento
                   AND d._id_movimiento = am._id_movimiento
                   AND ((m._id_concepto = '".CONCEPTO_SERV_ESCOLAR."' AND c.id_concepto = m._id_concepto) 
                       OR (m._id_concepto <> '".CONCEPTO_SERV_ESCOLAR."' AND m._id_concepto = c.id_concepto))";
        $resultConcept = $this->db->query($sql,array($idMov))->result();
        if(isset($resultConcept[0])){
            $sql = "(SELECT CASE WHEN da.cod_alumno_temp IS NOT NULL 
                                 THEN da.cod_alumno
                                 ELSE '00000000'
                           END AS cod_alumno,
                           CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers), ', ',INITCAP(p.nom_persona)) estudiante,
                           INITCAP(n.desc_nivel) desc_nivel,
                           INITCAP(g.desc_grado) desc_grado,
                           INITCAP(a.desc_aula) desc_aula,
                           p2.usuario
                      FROM persona p,
                           nivel n,
                           grado g,
                           aula a,
                           persona_x_aula pa,
                           persona p2,
                           sima.detalle_alumno da
                     WHERE p.nid_persona   = ?
                       AND pa.__id_persona = p.nid_persona
                       AND pa.__id_aula    = a.nid_aula
                       AND n.nid_nivel     = a.nid_nivel
                       AND g.nid_grado     = a.nid_grado
                       AND da.nid_persona  = p.nid_persona
                       AND p2.nid_persona  = ?
                  ORDER BY year_academico DESC
                     LIMIT 1)
                    UNION
                    SELECT da.cod_alumno,
                    	   CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers), ', ',INITCAP(p.nom_persona)) estudiante,
                    	   n.desc_nivel,
                    	   g.desc_grado,
                    	   '' as desc_aula,
                    	   p2.usuario
                      FROM persona p,
                    	   nivel n,
                    	   grado g,
                    	   persona p2,
                    	   sima.detalle_alumno da
                         WHERE p.nid_persona   = ?
                           AND n.nid_nivel     = da.id_nivel_ingreso
                           AND g.nid_grado     = da.id_grado_ingreso
                           AND da.nid_persona  = p.nid_persona
                           AND da.estado       IN('PREREGISTRO','REGISTRO','REGISTRO_PROM','PREREGISTRO_PROM','RETIRADO')
                           AND p2.nid_persona  = ? ";
            $resultPers = $this->db->query($sql,array($resultConcept[0]->_id_persona,$resultConcept[0]->id_pers_regi,$resultConcept[0]->_id_persona,$resultConcept[0]->id_pers_regi))->row_array();
        }
        foreach ($resultConcept as $row){
            $row->concepto   = utf8_encode($row->concepto);
            $row->cod_alumno = $resultPers['cod_alumno'];
            $row->estudiante = utf8_encode($resultPers['estudiante']);
            $row->desc_nivel = utf8_encode($resultPers['desc_nivel']);
            $row->desc_grado = utf8_encode($resultPers['desc_grado']);
            $row->desc_aula  = utf8_encode($resultPers['desc_aula']);
            $row->usuario    = utf8_encode($resultPers['usuario']);
            unset($row->_id_persona);
            unset($row->id_pers_regi);
        }
        return $resultConcept;
    }
    
    function getBoletasPrint($nroCorre,$persona,$arrayCompromisos) {
        $this->db->where("CONCAT(nro_serie,'-',nro_documento)" , $nroCorre);
        $this->db->where("_id_movimiento"                      , $arrayCompromisos[0]);
        $this->db->where("tipo_documento"                      , DOC_BOLETA);
        $this->db->update("pagos.documento"                    , array('estado' => DOC_IMPRESO));_logLastQuery();
        $sql = "(SELECT d._id_movimiento, d.tipo_documento,
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
    			       d.num_corre,
		    		   to_char(d.fecha_registro,'DD-MM-YY') as fecha,
                       CASE WHEN(m.fecha_pago::timestamp::date <= dc.fecha_descuento) 
                            THEN '1'
                            ELSE '0'
                       END AS flg_descuento,
		    		   CASE WHEN m._id_detalle_cronograma IS NOT NULL 
                            THEN dc.desc_detalle_crono
                            ELSE c.desc_concepto  
                       END AS cuota,
		    		   m._id_persona,
		    		   m.descuento_acumulado descuento,
    				   m.monto,
    			       m.mora_acumulada mora,
    			       m.monto_adelanto,
    				   to_char(d.fecha_registro, 'YYYY') as year,
	  			       d._id_sede,
    	               (CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona))) as nombrecompleto,
				       (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado, ' ', a.desc_aula)) as ubicacion
    	          FROM pagos.documento d,
    	               public.persona p,
    	               public.persona_x_aula pa,
                       sima.detalle_alumno da,
	                   public.aula a,
	                   public.grado g,
	                   public.nivel n,
	                   public.sede s,
                       pagos.concepto c,
                       pagos.movimiento     m
    			       LEFT JOIN pagos.detalle_cronograma dc ON(m._id_detalle_cronograma = dc.id_detalle_cronograma)
                 WHERE d.tipo_documento                        = '".DOC_BOLETA."'
                   AND da.estado                               = '".ALUMNO_MATRICULADO."'
                   AND CONCAT(d.nro_serie,'-',d.nro_documento) = ?
                   AND m.id_movimiento                        IN ?
                   AND c.id_concepto                           = m._id_concepto
			       AND p.nid_persona                           = m._id_persona
	               AND pa.__id_persona                         = p.nid_persona
	               AND pa.__id_aula                            = a.nid_aula
				   AND a.year                                  = cast(to_char(d.fecha_registro, 'YYYY') as int)
	               AND a.nid_grado                             = g.nid_grado
	               AND a.nid_nivel                             = n.nid_nivel
	               AND a.nid_sede                              = s.nid_sede
				   AND m.id_movimiento                         = d._id_movimiento
    			   --AND d.estado                                <> '".DOC_IMPRESO."'
    			   AND p.nid_persona                           = ?
    			   AND (SELECT EXTRACT (MONTH FROM now()))     = (SELECT EXTRACT (MONTH FROM d.fecha_registro))
    			   AND da.nid_persona = p.nid_persona
    		  ORDER BY d.estado, d.nro_documento)
    			 UNION 
    			(SELECT d._id_movimiento, d.tipo_documento,
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
    			       d.num_corre,
		    		   to_char(d.fecha_registro,'DD-MM-YY') as fecha,
                       CASE WHEN(m.fecha_pago::timestamp::date <= dc.fecha_descuento) 
                            THEN '1'
                            ELSE '0'
                       END AS flg_descuento,
		    		   CASE WHEN m._id_detalle_cronograma IS NOT NULL 
                            THEN dc.desc_detalle_crono
                            ELSE c.desc_concepto  
                       END AS cuota,
		    		   m._id_persona,
		    		   m.descuento_acumulado descuento,
    				   m.monto,
    			       m.mora_acumulada mora,
    			       m.monto_adelanto,
    				   to_char(d.fecha_registro, 'YYYY') as year,
	  			       d._id_sede,
    	               (CONCAT(UPPER(p.ape_pate_pers),' ', UPPER(p.ape_mate_pers), ', ' , INITCAP(p.nom_persona))) as nombrecompleto,
				       (CONCAT(s.desc_sede, ' ', n.desc_nivel, ' ', g.desc_grado)) as ubicacion
                   FROM pagos.documento      d, 
                        public.persona       p,
                        sima.detalle_alumno da,
                        public.grado         g,
                        public.nivel         n,
                        public.sede          s,
                        pagos.concepto       c,
                        pagos.movimiento     m
                        LEFT JOIN pagos.detalle_cronograma dc ON(m._id_detalle_cronograma = dc.id_detalle_cronograma)
                  WHERE d.tipo_documento                        = '".DOC_BOLETA."'
                    AND c.id_concepto                           = m._id_concepto
                    AND m.id_movimiento                        IN ?
                    AND p.nid_persona                           = m._id_persona
                    AND da.id_grado_ingreso                     = g.nid_grado
                    AND da.id_nivel_ingreso                     = n.nid_nivel
                    AND da.id_sede_ingreso                      = s.nid_sede
                    AND m.id_movimiento                         = d._id_movimiento
                    --AND d.estado                                <> '".DOC_IMPRESO."'
                    AND da.nid_persona                          = p.nid_persona
                    AND da.estado                               IN ('REGISTRADO','PROM_PREREGISTRO','PROM_REGISTRO','PREREGISTRO','MATRICULABLE','RETIRADO')
                    AND CONCAT(d.nro_serie,'-',d.nro_documento) = ?
                    AND p.nid_persona                           IN (?) )";
        $result = $this->db->query($sql, array($nroCorre,$arrayCompromisos,$persona,$arrayCompromisos,$nroCorre,$persona));
        if($result->num_rows() > 0){
            $arrayData = array();
            foreach($result->result() as $row){
                $arrayAux = array('nombrecompleto' => utf8_encode($row->nombrecompleto),
                                   'ubicacion'      => utf8_encode($row->ubicacion),
                                   'nro_documento'  => $row->nro_documento,
                                   'fecha'          => $row->fecha,
                                   'cuota'          => utf8_encode($row->cuota),
                                   'monto'          => $row->monto,
                                   'descuento'      => $row->descuento,
                                   'mora'           => $row->mora,
                                   'total'          => $row->monto_adelanto,
                                   'flg_descuento'  => $row->flg_descuento,
                                   'info_pago'      => $row->info_pago
                );
                array_push($arrayData, $arrayAux);
            }
            return $arrayData;
        } else{
            return null;
        }
    }
    
    function flgUpdateDetalleAlumno($idPersona,$arrayComp){
        $sql = "SELECT COUNT(1) count,
                       MAX(da.estado) estado,
                       MAX(dc.flg_tipo) flg_tipo
                  FROM sima.detalle_alumno da,
                       pagos.movimiento     m,
                       pagos.detalle_cronograma dc
                 WHERE da.nid_persona           = ?
                   AND da.estado                IN('PREREGISTRO','PROM_PREREGISTRO')
                   AND dc.flg_tipo              IN('1','2')
                   AND dc.id_detalle_cronograma IN (SELECT _id_detalle_cronograma
                                                      FROM pagos.movimiento 
                                                     WHERE id_movimiento IN ?
                                                       AND _id_concepto  = ".CONCEPTO_SERV_ESCOLAR.")
                   AND m.id_movimiento IN ? 				     
                   AND m._id_detalle_cronograma = dc.id_detalle_cronograma";
        $result = $this->db->query($sql, array($idPersona,$arrayComp,$arrayComp));
        return $result->row_array();
    }
    
    function getDataAulas($descAula, $year,$sede, $nivel, $grado, $aula, $offset = 0){
        $sql = "SELECT INITCAP(a.desc_aula) AS desc_aula,
                       a.capa_actual,
                       (SELECT COUNT(1) 
                          FROM (
            			    SELECT 1
            			      FROM pagos.movimiento m,
            				       persona          p,
            				       persona_x_aula  pa
            				 WHERE p.nid_persona = pa.__id_persona
            				   AND m._id_persona = p.nid_persona
            				   AND pa.__id_aula  = a.nid_aula
            				   AND m.estado      = '".ESTADO_VENCIDO."'
            				GROUP BY p.nid_persona) ta) cant_vencidos,
                       (SELECT COUNT(1)
                          FROM persona_x_aula            pa,
                               pagos.condicion_x_persona cp,
                               pagos.condicion            c
                         WHERE pa.__id_persona  = cp._id_persona
                           AND cp.estado        = '".ESTADO_ACTIVO."'
                           AND c.id_condicion   = cp._id_condicion
                           AND c.tipo_condicion = '0'
                           AND pa.__id_aula     = a.nid_aula) cant_becas,
                       (SELECT COUNT(1)
                          FROM persona_x_aula pa,
                               persona         p
                         WHERE pa.__id_persona = p.nid_persona
                           AND a.nid_aula      = pa.__id_aula) matriculados,
                       g.desc_grado,
                       n.desc_nivel,
                       a.nombre_letra,
                       s.desc_sede,
                       a.nid_aula,
                       a.year
                  FROM aula             a,
                       sede             s,
                       nivel            n,
                       grado            g
                 WHERE (LOWER(a.desc_aula) LIKE LOWER(?) OR LOWER(n.desc_nivel) LIKE LOWER(?) OR LOWER(g.desc_grado) LIKE LOWER(?))
                   AND a.nid_sede         = s.nid_sede
                   AND a.year             = COALESCE(?, a.year)
                   AND a.nid_sede         = COALESCE(?, a.nid_sede)
                   AND a.nid_nivel        = COALESCE(?, a.nid_nivel)
                   AND a.nid_grado        = COALESCE(?, a.nid_grado)
				   AND a.nid_aula         = COALESCE(?, a.nid_aula)
				   AND a.nid_sede         = s.nid_sede
				   AND a.nid_nivel        = n.nid_nivel
				   AND a.nid_grado        = g.nid_grado
              ORDER BY year DESC
                LIMIT 10  OFFSET $offset";
        $result = $this->db->query($sql, array('%'.$descAula.'%', '%'.$descAula.'%', '%'.$descAula.'%', $year, $sede, $nivel, $grado, $aula));
        return $result->result();
    }
    
    function getDataHistoricoByAlumno($idPersona){
        $sql = "SELECT monto_adelanto,
                       estado,
                       CONCAT(to_char(m.fecha_pago,'DD/MM/YYYY'),' - ',dc.desc_detalle_crono) as detalle
                  FROM pagos.movimiento          m,
                       pagos.detalle_cronograma dc
                 WHERE _id_persona     = ?
                   AND tipo_movimiento = 'INGRESO'
                   AND m.estado        = 'PAGADO'
                   AND dc.id_detalle_cronograma = m._id_detalle_cronograma
                ORDER BY m.fecha_pago";
        $result = $this->db->query($sql,array($idPersona));
        return $result->result();
    }
    
    function getAlumnosByAula($idAula){
        $sql = "SELECT row_number() OVER() as row_num,
                       da.cod_alumno_temp cod_alumno,
                       CASE WHEN p.foto_persona IS NULL 
                            THEN 'nouser.svg'
                            ELSE foto_persona
                       END AS foto_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',INITCAP(p.nom_persona)) estudiante,
                       CONCAT(INITCAP(p.ape_pate_pers),', ',split_part(p.nom_persona, ' ', 1)) nombreabreviado,
                       (
                        SELECT CONCAT(
                                          COUNT(1),
                                          '|',
                                          SUM(m.monto_final)
                                     )
                          FROM pagos.movimiento m
                         WHERE m._id_persona = p.nid_persona
                           AND m.estado = '".ESTADO_VENCIDO."'
                       ) vencido,
                       (
                        SELECT CONCAT(to_char(m.fecha_pago,'DD/MM/YYYY'),'|',m.monto_adelanto)
                          FROM pagos.movimiento m
                         WHERE m._id_persona = p.nid_persona
                           AND m.estado = '".ESTADO_PAGADO."'
                      ORDER BY m.fecha_pago DESC
                         LIMIT 1
                       ) ultimo_pago,
                       p.nid_persona
                  FROM persona              p,
                       persona_x_aula      pa,
                       sima.detalle_alumno da
                 WHERE pa.__id_aula    = ?
                   --AND pa.flg_acti     = '".FLG_ACTIVO."'
                   AND da.nid_persona  = p.nid_persona
                   AND pa.__id_persona = p.nid_persona
                   AND da.nid_persona  = pa.__id_persona";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
    }
    
    function getDatosAlumnoCorreos($id_compromiso) {
        $sql = "SELECT CONCAT(UPPER(p.nom_persona), ' ', UPPER(p.ape_pate_pers), ' ', UPPER(p.ape_mate_pers)) AS estudiante,
                       p.correo_pers,
                       dc.desc_detalle_crono,
                       dc.fecha_descuento,
                       m.monto,
                       m.mora_acumulada,
                       m.monto_adelanto,
                       CASE WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 1) THEN 'Enero'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 2) THEN 'Febrero'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 3) THEN 'Marzo'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 4) THEN 'Abril'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 5) THEN 'Mayo'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 6) THEN 'Junio'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 7) THEN 'Julio'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 8) THEN 'Agosto'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 9) THEN 'Setiembre'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 10) THEN 'Octubre'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 11) THEN 'Noviembre'
                            WHEN ((EXTRACT (month from dc.fecha_vencimiento)) = 12) THEN 'Diciembre'
                       END AS fecha_vencimiento,
                       c.year
                  FROM pagos.movimiento          m,
                       pagos.detalle_cronograma dc,
                       pagos.cronograma          c,
                       public.persona            p
                 WHERE p.nid_persona            = m._id_persona
                   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                   AND c.id_cronograma          = dc._id_cronograma
                   AND m.id_movimiento          IN ?";
        $result = $this->db->query($sql,array($id_compromiso));
        return $result->result();
    }
    
    function getTotalPagado($id_compromiso) {
        $sql = "SELECT SUM(monto_adelanto) AS total
                  FROM (SELECT CONCAT(UPPER(p.nom_persona), ' ', UPPER(p.ape_pate_pers), ' ', INITCAP(p.ape_mate_pers)) AS estudiante,
                               p.correo_pers,
                               dc.desc_detalle_crono,
                               dc.fecha_descuento,
                               m.monto,
                               m.mora_acumulada,
                               m.monto_adelanto
                          FROM pagos.movimiento          m,
                               pagos.detalle_cronograma dc,
                               public.persona            p
                         WHERE p.nid_persona            = m._id_persona
                           AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                           --AND m._id_concepto           = 1
                           AND m.id_movimiento          IN ?) s ";
        $result = $this->db->query($sql,array($id_compromiso));
        return $result->row()->total;
    }
    
    function getSedeByMovimiento($idMovimiento){
        $sql = "SELECT c._id_sede
                  FROM pagos.cronograma          c,
                       pagos.detalle_cronograma dc,
                       pagos.movimiento          m
                 WHERE m.id_movimiento          = ?
                   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                   AND dc._id_cronograma        = c.id_cronograma";
        $result = $this->db->query($sql,array($idMovimiento));
        return $result->row()->_id_sede;
    }
    
    function getActualState($nroDoc,$idMov){
        $sql = "SELECT estado
                  FROM pagos.documento
                 WHERE nro_documento  = ?
                   AND _id_movimiento = ?
                   AND tipo_documento = '".DOC_BOLETA."'";
        $result = $this->db->query($sql,array($nroDoc,$idMov));
        return $result->row()->estado;
    }
    
    function getAllProveedores($nombre){
        $sql = "SELECT id_proveedor,
                       nombre_proveedor,
                       responsable
                  FROM pagos.proveedor
                 WHERE UPPER(nombre_proveedor) LIKE UPPER( ? )";
        $result = $this->db->query($sql,array('%'.$nombre.'%'));
        return $result->result();
    }
    
    function getPromocionesActivas(){
        $sql = "SELECT cant_cuotas,
                       porcentaje_descuento
                  FROM pagos.promociones
                 WHERE flg_acti = '".FLG_ACTIVO."'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function updateMovimientoById($idMovimiento,$arrayUpdate){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('id_movimiento'     , $idMovimiento);
            $this->db->update('pagos.movimiento' , $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-028');
            }
            $data['msj']   = MSJ_UPT;
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        return $data;
    }
    
    function validateCuotasCompromisos($arrayMovimientos){
        $sql = "SELECT COUNT(1) pagos,
                       (SELECT SUM(monto_final)
                          FROM pagos.movimiento 
                         WHERE id_movimiento IN ? ) monto
                  FROM pagos.detalle_cronograma dc,
                       pagos.cronograma          c
                 WHERE c._id_tipo_cronograma     = ".ANIO_LECTIVO."
                   AND dc.flg_tipo               = '".FLG_CUOTA."'
                   AND c.year                    = (SELECT EXTRACT(YEAR FROM now())) + 1
                   AND dc._id_cronograma         = c.id_cronograma
                   AND dc.id_detalle_cronograma IN(SELECT _id_detalle_cronograma
                                                     FROM pagos.movimiento 
                                                    WHERE id_movimiento IN ? )";
        $result = $this->db->query($sql,array($arrayMovimientos,$arrayMovimientos));
        return $result->row_array();
    }
}
