<?php defined('BASEPATH') OR exit('No direct script access allowed');
//LAST-CODE: MU-026
class M_caja extends  CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    function getCurrentCaja($idSede,$pers,$fechaAux = NULL) {
        $sql = "SELECT c.id_caja,
                       c.monto_inicio,
                       c.estado_caja,
                       c.fecha_inicio,
                       c.fecha_cierre
                  FROM pagos.caja c
                 WHERE (( ? IS NULL     AND c.fecha_inicio::timestamp::date = current_date) 
                      OR( ? IS NOT NULL AND c.fecha_inicio::timestamp::date = ?) )
                   AND c._id_sede     = ?
                   AND c.id_pers_caja = ?";
        $result = $this->db->query($sql,array($fechaAux,$fechaAux,$fechaAux,$idSede,$pers));
        if($result->num_rows() > 0){
            return $result->row_array();
        } else{
            $data['id_caja']      = null;
            $data['monto_inicio'] = 0;
            $data['estado_caja']  = null;
            $data['fecha_inicio'] = $fechaAux;
            $data['fecha_cierre'] = $fechaAux;
            return $data;
        }
    }
    
    function getPersona($idPersona) {
    	$sql = "SELECT p.nid_persona,
    	               CONCAT(p.nom_persona,' ', p.ape_pate_pers,' ', p.ape_mate_pers) as nombre_completo,
    			       pd.id_sede_control,
    	               p.foto_persona
				  FROM persona p,
    	               rrhh.personal_detalle pd
				 WHERE p.nid_persona = ?
    	           AND pd.id_persona = p.nid_persona";
    	$result = $this->db->query($sql,array($idPersona));
    	return $result->row_array();
    }
    
    function getIdPersona($id_movimiento) {
    	$sql = "SELECT _id_persona
                  FROM pagos.movimiento
                 WHERE id_movimiento = ?";
    	$result = $this->db->query($sql,array($id_movimiento));
    	return $result->row()->_id_persona;
    }
    
    function getIngresosEgresosByCaja2($fechaInicio,$fechaFin,$tipoMov,$pers) {
        $sql = "SELECT SUM(am.monto_pagado) as monto_pagado, 
                       MAX(am.accion),
                       (count(1))
		          FROM pagos.caja c,
                       pagos.audi_movimiento am,
                       pagos.movimiento m
                 WHERE c.id_pers_caja                     = ?
                   AND am.audi_fec_regi::timestamp::date >= ?
                   AND am.audi_fec_regi::timestamp::date <= ?
                   AND am.accion         IN  ?
                   AND (( ARRAY[?]::character varying[] = ARRAY[?]::character varying[] AND flg_visa <> '1' AND m.flg_lugar_pago <> '1'))
                   AND am._id_caja       = c.id_caja
                   AND am._id_movimiento = m.id_movimiento
                --GROUP BY am.accion";
        $result = $this->db->query($sql,array($pers,$fechaInicio,$fechaFin,$tipoMov,$tipoMov,$tipoMov));
        return $result->row_array();
    }
    
    function getIngresosEgresosByCaja($tipoMov,$fechaInicio,$fechaFin,$pers) {
        $sql = "SELECT CASE WHEN(m._id_concepto = 1) THEN dc.desc_detalle_crono
                                                     ELSE co.desc_concepto
                       END AS desc_cuota,
                       am.monto_pagado,
                       am.flg_visa ,
                       to_char(am.audi_fec_regi,'DD/MM/YYYY HH24:MI:SS') audi_fec_regi,
                       substring(am.observacion from 1 for 20) observacion,
                       m._id_persona,
                       CASE WHEN (am.flg_visa = '".FLG_VISA."') THEN 'credit_card'
                                                                ELSE 'toll'
                       END AS icon_mod_pago,
                       CASE WHEN (am.flg_visa = '".FLG_VISA."') THEN 'Cr&eacute;dito'
                                                                ELSE 'Efectivo'
                       END AS tooltip,
                       m.flg_lugar_pago,
                       CASE WHEN(m.flg_regi_movi = 2) 
                            THEN (SELECT responsable
                                    FROM pagos.proveedor
                                   WHERE id_proveedor = m._id_persona)
                            ELSE (CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(p.nom_persona)))
                       END nom_persona,
                       (SELECT nro_documento
                          FROM pagos.documento
                         WHERE _id_movimiento = m.id_movimiento
                           AND tipo_documento = 'RECIBO' 
                         LIMIT 1) as nro_doc
                  FROM pagos.caja c,
                       pagos.concepto co,
                       persona p,
                       pagos.audi_movimiento am,
                       pagos.movimiento m
                       LEFT JOIN pagos.detalle_cronograma dc ON(dc.id_detalle_cronograma = m._id_detalle_cronograma)
                 WHERE c.id_pers_caja                     = ?
                   AND m._id_persona                      = p.nid_persona
                   AND am.id_pers_regi                    = c.id_pers_caja
                   AND am.audi_fec_regi::timestamp::date >= ?
                   AND am.audi_fec_regi::timestamp::date <= ?
                   --AND c.estado_caja   = '".APERTURADA."'
                   AND m.flg_lugar_pago  = '0'
                   AND am.accion         IN ?
                   AND am._id_movimiento = m.id_movimiento
                   AND co.id_concepto    = m._id_concepto
                GROUP BY m.id_movimiento,desc_cuota,am.monto_pagado,am.flg_visa,am.audi_fec_regi,am.observacion,m._id_persona, p.ape_pate_pers, p.ape_mate_pers, p.nom_persona,m.flg_lugar_pago,m.flg_regi_movi
                ORDER BY nro_doc ASC";
        $result = $this->db->query($sql,array($pers,$fechaInicio,$fechaFin,$tipoMov));
        return $result->result();
    }
    
    function getDataByCaja($sede,$secretaria) {
        $sql = "SELECT id_caja,
                       monto_inicio,
                       to_char(fecha_inicio,'DD/MM/YYYY HH24:MI:SS') fecha_inicio,
                       to_char(fecha_cierre,'DD/MM/YYYY HH24:MI:SS') fecha_cierre,
                       monto_fin
                  FROM pagos.caja
                 WHERE fecha_inicio::timestamp::date = current_date
                   AND _id_sede     = ?
                   AND id_pers_caja = ?";
        $result = $this->db->query($sql,array($sede,$secretaria));
        return $result->row_array();
    }
    
    function getDataCierreCaja($idCaja=10) {
        $sql = "SELECT c.monto_inicio,
                       am.monto_pagado,
                       am.accion,
                       am.flg_visa
                  FROM pagos.audi_movimiento am,
                       pagos.movimiento m,
                       pagos.caja c
                 WHERE c.id_caja   = ?
                   AND estado_caja = '".APERTURADA."' 
                   AND c.id_caja   = am._id_caja
                   AND m.id_movimiento = am._id_movimiento
                   AND m.flg_lugar_pago <> '".FLG_BANCO."'
                   AND am.accion   <> '".ANULAR."'
                   AND am.audi_fec_regi::timestamp::date = current_date";
        $result = $this->db->query($sql,array($idCaja));
        if($result->num_rows() > 0) {
            return $result->result();
        } else{
            return array();
        }
    }
    
    function cerrarCaja($arrayUpdate,$arrayInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            //CERRAR CAJA
            $this->db->where('id_caja',$arrayUpdate['id_caja']);
            unset($arrayUpdate['id_caja']);
            $this->db->update('pagos.caja',$arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-001');
            }
            //REGISTRAR AUDITORIA
            $this->db->insert('pagos.audi_caja',$arrayInsert);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-002');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = 'Se cerró la caja';
            $this->db->trans_commit();
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage(); 
        }
        return $data;
    }
    
    function saveIncidencia($arrayInsertMovi,$arrayInserAudiMovi){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            //REGISTRAR INCIDENCIA
            $this->db->insert('pagos.movimiento',$arrayInsertMovi);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-003');
            }
            $idMovi = $this->db->insert_id();
            //REGISTRAR AUDITORIA
            $arrayInserAudiMovi['_id_movimiento'] = $idMovi;
            $this->db->insert('pagos.audi_movimiento',$arrayInserAudiMovi);
            if($this->db->affected_rows() != 1){
                throw new Exception('MU-004');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e){
            $this->db->trans_rollback();
            $data['error'] = $e->getMessage();
        }
        return $data;
    }
    
    function getNextCorrelativoByAudiDoc($idCaja){ 
        $sql = "SELECT COUNT(1) + 1 as cuenta
                  FROM pagos.audi_caja
                 WHERE _id_caja = ?";
        $result = $this->db->query($sql,array($idCaja));
        return $result->row()->cuenta;
    }
    
    function getDetalleColaborador($idPersona,$idSede) {
        $sql = " SELECT m.id_movimiento,
                		m.tipo_movimiento, 
                		m.monto_final, 
                		m.estado, 
                		m._id_persona, 
                		m._id_concepto, 
                		to_char(m.fecha_registro,'DD/MM/YYYY') fecha_registro, 
                		c.desc_concepto, 
                		a.accion,
                        CASE WHEN (p2.foto_persona IS NOT NULL) 
                		     THEN p2.foto_persona
                		     ELSE 'nouser.svg'
                		END AS foto_pers_regi,
                		CASE WHEN p.foto_persona IS NULL 
                             THEN 'nouser.svg'
                             ELSE p.foto_persona
                		END AS foto_pers_retiro,
                		(SELECT COUNT(1) FROM pagos.audi_movimiento am WHERE am.id_devolucion = m.id_movimiento) AS devolucion,
                		CONCAT(p.nom_persona,' ', p.ape_pate_pers,' ', p.ape_mate_pers) as nombre_completo_retiro,
                		CONCAT(p2.nom_persona,' ', p2.ape_pate_pers,' ', p2.ape_mate_pers) as nombre_completo_regi,
                        CASE WHEN (a2.monto_pagado IS NOT NULL) 
                             THEN to_char(a2.monto_pagado,'9999999D99') 
                             ELSE '-' 
                        END AS devuelto
                   FROM pagos.movimiento      m,
                		pagos.concepto        c,
                		pagos.documento       d,
                		persona               p,
                		persona              p2,
                		pagos.audi_movimiento a
                		LEFT JOIN pagos.audi_movimiento a2 ON(a2.id_devolucion = a._id_movimiento)
                  WHERE m.tipo_movimiento = '".TIPO_EGRESO."'
                	AND m.fecha_registro::timestamp::date > (current_date - 15)
                	AND m.estado        <> '".ESTADO_ANULADO."'
                	AND m._id_concepto  = c.id_concepto
                	AND m.id_movimiento = d._id_movimiento
                	AND p2.nid_persona  = ?
                	AND a._id_sede      = ?
                	AND p2.nid_persona  = a.id_pers_regi
                	AND p.nid_persona   = m._id_persona
                	AND m.id_movimiento = a._id_movimiento
               ORDER BY m.fecha_registro";
        $result = $this->db->query($sql,array($idPersona,$idSede));
        return $result->result();
    }
    
    function getColaborador($idPersona) {
    	$sql = "SELECT CONCAT(nom_persona,' ', ape_pate_pers,' ', ape_mate_pers) as nombre_completo
				  FROM persona
				  WHERE nid_persona = ?";
    	$result = $this->db->query($sql,array($idPersona));
    	return $result->row()->nombre_completo;
    }
    
    function insertDevolver($arrayInsertMov, $arrayInsertAud) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$this->db->trans_begin();
    	try{
    		$this->db->insert('pagos.movimiento', $arrayInsertMov);
    		if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
    			throw new Exception("Vuelva a Intentar");
    		}
    		$lastID = $this->db->insert_id();
    		$arrayInsertAud['_id_movimiento'] = $lastID;
    		$this->db->insert('pagos.audi_movimiento', $arrayInsertAud);
    		if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
    			throw new Exception("Vuelva a Intentar");
    		}
    		$data['error'] = EXIT_SUCCESS;
    		$data['msj']   = "Devolucion Completa";
    		$this->db->trans_commit();
    	}catch (Exception $e){
    		$data['msj']   = $e->getMessage();
    		$this->db->trans_rollback();
    	}
    	return $data;
    }
    
    function getLastCajaBySede($idSede,$pers) {
        $sql = "SELECT id_caja, 
                       CASE WHEN monto_fin IS NULL
                            THEN 0.00
                            ELSE monto_fin
                       END monto_fin,
                       fecha_inicio::timestamp::date fecha_inicio,
                       current_date as actual,
                       estado_caja
                  FROM pagos.caja
                 WHERE _id_sede     = ?
                   AND id_pers_caja = COALESCE(?,id_pers_caja)
              ORDER BY fecha_inicio DESC
                 LIMIT 1";
        $result = $this->db->query($sql,array($idSede,$pers));
        if($result->num_rows() > 0){
            return $result->row_array();
        } else{
            $result = null;
            $result['monto_fin']    = 0;
            $result['fecha_inicio'] = null;
            $result['actual']       = null;
            $result['estado_caja']  = null;
            $result['id_caja']      = null;
            return $result;
        }
    }
    
    function accionCaja($array,$accion) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            if($accion == INSERTA){
                $this->db->insert('pagos.caja',$array);
            } else{
                $this->db->where('_id_caja ', $array['_id_caja']);
                unset($array['_id_caja']);
                $this->db->update('pagos.caja'    , $array);
            }
            if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
                throw new Exception(MSJ_ERROR);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getCajaCerrada($idSede,$pers) {
        $sql = "SELECT COUNT(1)
                  FROM pagos.caja c
                 WHERE c._id_sede    = ?
                   AND c.fecha_inicio::timestamp::date = current_date
                   AND c.estado_caja  IN ('".CERRADA."','".CERRADA_EMERGENCIA."')
                   AND c.id_pers_caja = ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($idSede,$pers));
        return $result->row()->count;
    }
    
    function getSecretariasReemplazo($personaSession){
        $sql = "SELECT * 
                  FROM(
                       SELECT p.nid_persona,
                              CASE WHEN p.foto_persona IS NULL 
                                   THEN 'nouser.svg'
                                   ELSE p.foto_persona
                              END AS foto_persona,
                              usuario,
                              CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ',INITCAP(p.nom_persona)) nombre_completo,
                              (SELECT COUNT(1)
                                 FROM pagos.caja c
                                WHERE (c.id_pers_caja = p.nid_persona OR c.id_pers_reemplazo = p.nid_persona)
                                  AND c.estado_caja IN('APERTURADA','CERRADA_EMERGENCIA')
                                  AND c.fecha_inicio::date = current_date )
                         FROM persona                p,
                              persona_x_rol         pr,
                              rrhh.personal_detalle pd
                        WHERE pr.nid_rol         = ".ID_ROL_SECRETARIA."
                          AND p.nid_persona      <> ?
                          AND pd.id_persona      = p.nid_persona
                          AND pr.nid_persona     = p.nid_persona) tab
                        WHERE count = 0";
        $result = $this->db->query($sql,array($personaSession));
        return $result->result();
    }
    
    function getSecretariasConCaja() {
        $sql = "SELECT p.nid_persona,
                       CONCAT(UPPER(p.ape_pate_pers), ' ', UPPER(p.ape_mate_pers)) AS apellidos,
                       INITCAP(p.nom_persona) as nombre,
                       CASE WHEN p.foto_persona IS NULL 
                            THEN 'nouser.svg'
                            ELSE p.foto_persona
                       END AS foto_persona,
                       p.telf_pers,
                       p.correo_pers,
                       s.desc_sede,
                       r.desc_rol
                  FROM persona        p,
                       persona_x_rol pr,
                       pagos.caja     c,
                       public.sede    s,
                       public.rol     r
                 WHERE PR.NID_ROL     = ".ID_ROL_SECRETARIA."
                   AND pr.flg_acti    = '".FLG_ACTIVO."'
                   AND p.flg_acti     = '".FLG_ACTIVO."'
                   AND pr.nid_persona = p.nid_persona
                   AND c.id_pers_caja = p.nid_persona
                   AND s.nid_sede     = c._id_sede
                   AND r.nid_rol      = pr.nid_rol
                   --AND to_char(c.fecha_inicio, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY')
              GROUP BY p.nid_persona, apellidos, nombre, foto_persona, telf_pers, correo_pers, desc_sede, desc_rol
              ORDER BY nid_persona ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getSedeBySecretaria($secretaria) {
        $sql = "SELECT _id_sede AS sede
                  FROM pagos.caja
                 WHERE id_pers_caja = ?
              GROUP BY _id_sede
               LIMIT 1";
        $result = $this->db->query($sql,array($secretaria));
        return $result->row()->sede;
    }
    
    function getDetalleIncidencia($secretaria, $sede) {
        $sql = "SELECT to_char(am.audi_fec_regi, 'DD/MM/YYYY') as fecha,
                       CASE WHEN(am.observacion IS NOT NULL)
                            THEN am.observacion
                            ELSE '-'
                       END AS observacion,
                       am.monto_pagado
                  FROM pagos.audi_movimiento am,
                       pagos.movimiento       m
                 WHERE am.id_pers_regi   = ?
                   AND (am.audi_nomb_regi IS NOT NULL)
                   AND am._id_sede       = ?
                   AND m._id_concepto    IN('".PERDIDA."','".REPOSICION."')
                   AND am._id_movimiento = m.id_movimiento";
        $result = $this->db->query($sql,array($secretaria, $sede));
        return $result->result();
    }
    
    function getCajasAsignadas($secretaria){
        $sql = "SELECT c.id_caja,
                       CASE WHEN p.foto_persona IS NULL     AND p.google_foto IS NOT NULL THEN p.google_foto
                            WHEN p.foto_persona IS NOT NULL AND p.google_foto IS NULL     THEN p.foto_persona
                            ELSE 'nouser.svg'
                       END AS foto_persona,
                       CONCAT(p.ape_pate_pers, ' ' , p.ape_mate_pers) apellidos,
                       CASE WHEN p.foto_persona IS NULL     AND p.google_foto IS NOT NULL THEN 'google'
                            ELSE 'persona'
                       END AS flg_foto,
                       p.nom_persona nombres,
                       c.monto_inicio,
                       c.monto_fin,
                       s.desc_sede,
                       p.nid_persona,
                       c.flg_acepta
                  FROM pagos.caja c,
                       persona p,
                       sede s,
                       rrhh.personal_detalle pd
                 WHERE c.id_pers_reemplazo  = ?
                   AND c.estado_caja        = 'CERRADA_EMERGENCIA'
                   AND c.fecha_inicio::date = current_date 
                   AND c.id_pers_caja       = p.nid_persona
                   AND p.nid_persona  = pd.id_persona
                   AND s.nid_sede           = pd.id_sede_control";
        $result = $this->db->query($sql,array($secretaria));
        return $result->result();
    }
    
    function updateCaja($idCaja, $arrayUpdate){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('id_caja',$idCaja);
            $this->db->update('pagos.caja',$arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo actualizar');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = 'Aceptaste la caja :)';
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    
    
    
}