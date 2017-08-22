<?php

class M_pensiones extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getAllSedes($sedes = null) {
        $sql = "SELECT s.nid_sede, 
                       s.desc_sede
                  FROM public.sede s
                 WHERE (( ? IS NULL AND 1 = 1) OR ( ? IS NOT NULL AND s.nid_sede NOT IN(?) ))
              ORDER BY desc_sede";
        $result = $this->db->query($sql,array($sedes,$sedes,$sedes));
        return $result->result();
    }
    
    function existeCondicion($id_sede, $year,$tipoCrono) {
        $sql = "SELECT Count(1) count
                  FROM pagos.sede_monto
                 WHERE _id_sede            = ?
        		   AND year                = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql, array($id_sede, $year,$tipoCrono));
        return $result->row()->count;
    }
    
    function getIdCondicion($id_sede, $idNivel, $year,$tipoCronograma) {
        $sql = "SELECT id_condicion
                  FROM pagos.condicion
                 WHERE _id_sede            = ?
        		   AND _id_nivel           = ?
        		   AND _id_grado           = 0
        		   AND year_condicion      = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql, array($id_sede, $idNivel, $year,$tipoCronograma));
        if($result->num_rows() > 0){
        	return $result->row()->id_condicion;
        } else {
        	return null;
        }
    }
    
    function getIdCondicionSedeNivelGrado($id_sede, $idNivel, $idGrado, $year,$tipoCrono) {
        $sql = "SELECT id_condicion
                  FROM pagos.condicion
                 WHERE _id_sede            = ?
        		   AND _id_nivel           = ?
        		   AND _id_grado           = ?
        		   AND year_condicion      = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql, array($id_sede, $idNivel, $idGrado, $year,$tipoCrono));
        if($result->num_rows() > 0){
            return $result->row()->id_condicion;
        } else {
            return null;
        }
    }
    
    function getMontosByNivelOrByGrado($id_condicion) {
    	$sql = "SELECT monto_matricula,
    	               --monto_matricula_prom monto_matricula,
    	               monto_pension,
    	               monto_cuota_ingreso,
    	               descuento_nivel,
    	               monto_matricula_prom
                  FROM pagos.condicion
                 WHERE id_condicion = ?";
    	$result = $this->db->query($sql, array($id_condicion));
    	return $result->row_array();
    }
    
    function getIdNivel($id_condicion) {
        $sql = "SELECT _id_nivel
                  FROM pagos.condicion
                 WHERE id_condicion = ?";
        $result = $this->db->query($sql, array($id_condicion));
        return $result->row_array();
    }
    
    function getIdGrado($id_condicion) {
    	$sql = "SELECT _id_grado
                  FROM pagos.condicion
                 WHERE id_condicion = ?";
    	$result = $this->db->query($sql, array($id_condicion));
    	return $result->row_array();
    }
    
    function getsede($id_sede) {
        $sql = "SELECT desc_sede
				  FROM sede
                 WHERE nid_sede = ?";
        $result = $this->db->query($sql, $id_sede);
        return $result->row()->desc_sede;
    }
    
    function getExiste($id_sede) {
    	$sql="SELECT exists(SELECT 1 FROM pagos.sede_monto WHERE _id_sede = ?) as existe";
    	$result = $this->db->query($sql, array($id_sede));
    	return $result->row_array();
    }
    
    function getPreviousYear($id_sede, $pensiones_year) {
    	$sql = "SELECT COUNT(1) as count
                  FROM pagos.sede_monto s1
                 WHERE s1._id_sede = ?
                   AND s1.year     = ?";
    	$result = $this->db->query($sql, array($id_sede, $pensiones_year));
    	return $result->row()->count;
    }
    
    function getAllMontos($id_sede, $pensiones_year,$tipoCrono) {
        $sql = "SELECT CASE WHEN (s1.cuota_ingreso IS NOT NULL)
                    	    THEN s1.cuota_ingreso::text
                    	    ELSE '-'
                    	END AS cuota_ingreso,
                    	CASE WHEN (s1.monto_matricula IS NOT NULL)
                    	    THEN s1.monto_matricula::text
                    	    ELSE '-'
                    	END AS monto_matricula,
                    	CASE WHEN (s1.monto_pension IS NOT NULL)
                    	    THEN s1.monto_pension::text
                    	    ELSE '-'
                    	END AS monto_pension,
                    	CASE WHEN (s1.descuento_sede IS NOT NULL)
                    	    THEN s1.descuento_sede::text
                    	    ELSE '-'
                    	END AS descuento_sede,
                    	CASE WHEN (s1.flg_cerrado IS NOT NULL)
                    	    THEN s1.flg_cerrado::text
                    	    ELSE '-'
                    	END AS flg_cerrado
                  FROM pagos.sede_monto s1
                 WHERE s1._id_sede            = ?
                   AND s1.year                = ?
                   AND s1._id_tipo_cronograma = ?";
        $result = $this->db->query($sql, array($id_sede, $pensiones_year,$tipoCrono));
        if($result->num_rows() > 0){
            return $result->row_array();
        } else{
            $result = null;
            $result['cuota_ingreso']   = '-';
            $result['monto_matricula'] = '-';
            $result['monto_pension']   = '-';
            $result['descuento_sede']  = '-';
            $result['flg_cerrado']     = '-';
            return $result;
        }
    }
    
    function getAllMontos1($id_sede, $year,$tipoCrono) {
    	$sql = "SELECT cuota_ingreso,
    	               monto_matricula,
    	               monto_pension,
    	               descuento_sede,
    	               monto_matricula_prom,
    	               to_char(fecha_fin_promo,'DD/MM/YYYY') fecha_fin_promo,
    	               flg_promo
                  FROM pagos.sede_monto
                 WHERE _id_sede            = ?
                   AND year                = ?
    	           AND _id_tipo_cronograma = ?";
    	$result = $this->db->query($sql, array($id_sede, $year,$tipoCrono));
    	if($result->num_rows() > 0) {
    		return $result->row_array();
    	} else {
    		$result = null;
    		$result['cuota_ingreso']   = null;
    		$result['monto_matricula'] = null;
    		$result['monto_pension']   = null;
    		$result['descuento_sede']  = null;
    		return $result;
    	}
    }
    
    function getFlgCerrado($id_sede, $year,$tipoCronograma) {
        $sql = "SELECT flg_cerrado        
		          FROM pagos.sede_monto
		         WHERE _id_sede            = ?
		           AND  year               = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql, array($id_sede, $year,$tipoCronograma));
        return $result->row()->flg_cerrado;
    }
    
    function getNivelesBySedes($idSede){
       $sql = "SELECT n.nid_nivel,
                      INITCAP(n.desc_nivel) desc_nivel
                 FROM nivel_x_sede ns,
                      nivel n
                WHERE n.nid_nivel = ns.id_nivel
                  AND ns.id_sede  = ?
             ORDER BY n.nid_nivel";
        $result = $this->db->query($sql, array($idSede));
        return $result->result();
     }
    
     function getYear($tipoCrono) {
        $sql = "SELECT DISTINCT year
                  FROM pagos.sede_monto
                 WHERE _id_tipo_cronograma = ? 
              ORDER BY year";
        $result = $this->db->query($sql,array($tipoCrono));
        if($result->num_rows() == 0) {
        	return null;
        } else {
        	return $result->result();
        }
    }
    
    function getGradosByNiveles($id_sede, $niveles) {
        $sql = "SELECT DISTINCT a.nid_grado,
                       g.desc_grado
		          FROM aula a,
		               grado g
                 WHERE a.nid_sede  = ?
	               AND a.nid_nivel = ?
	               AND a.nid_grado <> 0
	               AND g.nid_grado = a.nid_grado
	          ORDER BY a.nid_grado";
        $result = $this->db->query($sql, array($id_sede, $niveles));
        return $result->result();
    }
    
    function registrarPensionesBySedes($arrayInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->insert('pagos.sede_monto', $arrayInsert);
            if($this->db->affected_rows() != 1 ||$this->db->trans_status() == FALSE){
                throw new Exception("Vuelva a Intentar");
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
//             $this->db->trans_commit();
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function registrarCodiciones($arrayInsert, $accion, $arrayUptSede, $flg_final = 0) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            if($accion == INSERTA) {
                $this->db->insert_batch('pagos.condicion', $arrayInsert);
                $this->db->affected_rows();
                if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($arrayInsert)) { //
                    throw new Exception('No se pudo registrar');
                }
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = MSJ_INS;
                if($flg_final == 1){
                    $this->db->trans_commit();
                }
            }
            if($accion == ACTUALIZA) {
                $this->db->where('_id_sede', $arrayUptSede['_id_sede']);
                $this->db->where('_id_paquete', $arrayUptSede['_id_paquete']);
                $this->db->update('pagos.condicion', $arrayUptSede);
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = MSJ_INS;
                if($flg_final == 1){
                    $this->db->trans_commit();
                }
            }
        }catch (Exception $e){
        	$data['msj']   = $e->getMessage();
        	$this->db->trans_rollback();
        }
        return $data;
    }
    
    function actualizarCodiciones($arrayUpdate,$flg_final = 0) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->update_batch('pagos.condicion', $arrayUpdate, 'id_condicion');
            $this->db->affected_rows();
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($arrayUpdate)) {
                throw new Exception('No se pudo actualizar los registros12');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            if($flg_final == 1){
                $this->db->trans_commit();
            }
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function actualizarPensionesBySedesbyNivelOrGrado($id_condicion, $arrayUpdate,$flg_final = 0) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->where('id_condicion', $id_condicion);
            $this->db->update('pagos.condicion', $arrayUpdate);
            if($this->db->affected_rows() != 1 ||$this->db->trans_status() == FALSE){
                throw new Exception("Vuelva a Intentar");
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            if($flg_final == 1){
                $this->db->trans_commit();
            }
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function actualizarPensionesBySedes($_id_sede, $year, $tipoCrono, $arrayUpdate,$flg_fin = 0) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->where('_id_sede'            , $_id_sede);
            $this->db->where('year'                , $year);
            $this->db->where('_id_tipo_cronograma' , $tipoCrono);
            $this->db->update('pagos.sede_monto', $arrayUpdate);
            if($this->db->affected_rows() != 1 ||$this->db->trans_status() === FALSE){
                throw new Exception("Vuelva a Intentar");
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            if($flg_fin == 1){
                $this->db->trans_commit();
            }
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
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
    
    function getCalendarioBySede($idSede, $pensiones_year,$tipo_crono) {
    	$sql = "SELECT id_cronograma,
    	               flg_cerrado
                  FROM pagos.cronograma
                 WHERE _id_sede            = ?
                   AND year                = ?
    	           AND _id_tipo_cronograma = ?";
    	$result = $this->db->query($sql,array($idSede, $pensiones_year,$tipo_crono));
    	return $result->row_array();
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
    
    function verificaCuotaIngreso($id_persona) {
        $sql = "SELECT COUNT(1)
                  FROM pagos.movimiento
                 WHERE _id_concepto = ?
                   AND _id_persona  = ?";
        $result = $this->db->query($sql,array(CUOTA_INGRESO,$id_persona));
        return $result->row()->count;
    }
    
    function getCountCondicionAsignada($condicion,$persona) {
        $sql = "SELECT COUNT(1)
                  FROM pagos.condicion_x_persona
                 WHERE _id_condicion = ?
                   AND _id_persona   = ?
                   AND fecha_asignacion = current_date";
        $result = $this->db->query($sql,array($condicion,$persona));
        return $result->row()->count;
    }
    
    function checkIfExistsConfigCI($sede,$year){
        $sql = "SELECT COUNT(1) count
                  FROM pagos.config_cuota_ingreso
                 WHERE _id_sede = ?
                   AND year     = ?";
        $result = $this->db->query($sql,array($sede,$year));
        return $result->row()->count;
    }
    
    function updateInsertConfigCI($arrayUpdInsert,$arrayNivel,$arrayGrado,$arrayUptSede){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            if(count($arrayNivel) == 0 || $arrayGrado == 0){
                throw new Exception('Registra las pensiones');
            }
            //BEGIN UPDATE CONFIG CUOTA INGRESO
            if($arrayUpdInsert['accion'] == INSERTA){
                unset($arrayUpdInsert['accion']);
                $this->db->insert('pagos.config_cuota_ingreso', $arrayUpdInsert);
            } else {
                $this->db->where('_id_sede' , $arrayUpdInsert['_id_sede']);
                $this->db->where('year'     , $arrayUpdInsert['year']);
                unset($arrayUpdInsert['accion']);
                unset($arrayUpdInsert['_id_sede']);
                unset($arrayUpdInsert['year']);
                $this->db->update('pagos.config_cuota_ingreso' , $arrayUpdInsert);
            }
            //END UPDATE
            //BEGIN UPDATE SEDE MONTO
            $this->db->where('_id_sede' , $arrayUptSede['_id_sede']);
            $this->db->where('year'     , $arrayUptSede['year']);
            unset($arrayUptSede['_id_sede']);
            unset($arrayUptSede['year']);
            $this->db->update('pagos.sede_monto',$arrayUptSede);
            if($this->db->affected_rows() == 0 ||$this->db->trans_status() == FALSE){
                throw new Exception("Hubo un error");
            }
            //END UPDATE
            //BEGIN UPDATE CONDICION
            $this->db->update_batch('pagos.condicion', $arrayNivel, 'id_condicion');
            $this->db->affected_rows();
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($arrayNivel)) {//
                throw new Exception('No se pudo actualizar los registros13');
            }   
            $this->db->update_batch('pagos.condicion', $arrayGrado, 'id_condicion');
            $this->db->affected_rows();
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($arrayGrado)) {
                throw new Exception('No se pudo actualizar los registros14');
            }
            //END UPDATE
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            $this->db->trans_commit();
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getConfigCI($sede, $year){
        $sql = "SELECT CASE WHEN(estado = '".ESTADO_ACTIVO."') 
                            THEN 'checked'
                            ELSE ''
                       END AS check,
                       ct.valor combo
                  FROM pagos.config_cuota_ingreso cci,
                       combo_tipo                  ct
                 WHERE ct.grupo = ".COMBO_TIPO_CINGRESO."
                   AND ct.valor = cci.flg_afecta::CHARACTER VARYING
                   AND cci._id_sede = ?
                   AND cci.year     = ?";
        $result = $this->db->query($sql,array($sede,$year));
        if($this->db->affected_rows() == 1){
            return $result->row_array();
        } else{
            $data['check'] = null;
            $data['combo'] = null;
            return $data;
        }
    }
    
    function getMontosLastYear($year,$sede){
        $sql = "SELECT sm.monto_matricula,
                       sm.monto_pension
                  FROM pagos.sede_monto sm
                 WHERE sm.year     = ?
                   AND sm._id_sede = ?";
        $result = $this->db->query($sql,array(($year-1),$sede));
        if($result->num_rows() == 1){
            return $result->row_array();
        } else{
            $data['monto_matricula'] = 0;
            $data['monto_pension']   = 0;
            return $data;
        }
    }
    
    function getMontosNivelGradoLastYear($year,$sede,$nivel,$grado){
        $sql = "SELECT c.monto_matricula
                  FROM pagos.condicion c
                 WHERE c.year_condicion = ?
                   AND c._id_sede       = ?
                   AND c._id_nivel      = ?
                   AND c._id_grado      = ?";
        $result = $this->db->query($sql,array(($year-1),$sede,$nivel,$grado));
        if($result->num_rows() == 1){
            return $result->row_array();
        } else{
            $data['monto_matricula'] = 0;
            return $data;
        }
    }
    
    function getFlgPromo($id_sede,$year,$tipo_crono){
        $sql = "SELECT flg_promo
                  FROM pagos.sede_monto
                 WHERE _id_sede            = ?
                   AND year                = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql,array($id_sede,$year,$tipo_crono));
        return $result->row()->flg_promo;
    }
    
    function getMontosPaquetes($tipoCrono){
        $sql = "SELECT p.id_paquete,
                       p.desc_paquete,
                       pm.monto_pension,
                       pm.monto_descuento
                  FROM pagos.paquete p
                       LEFT JOIN pagos.paquete_monto pm ON(p.id_paquete = pm._id_paquete AND pm.year = 2017 AND pm._id_tipo_cronograma = ?)
                 WHERE p.flg_tipo = ? ";
        $result = $this->db->query($sql,array($tipoCrono,$tipoCrono));
        return $result->result();
    }
    
    function getMontosPaquetesBySede($sede, $paquete){
        $sql = "SELECT CASE WHEN (monto_matricula IS NOT NULL) 
                            THEN monto_matricula:: character varying 
                            ELSE '-'
                        END AS monto_matricula,
                       CASE WHEN (descuento_nivel IS NOT NULL)
                            THEN descuento_nivel:: character varying 
                            ELSE '-'
                        END AS descuento_nivel
                  FROM pagos.condicion
                 WHERE _id_sede = ? 
                   AND _id_paquete = ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($sede,$paquete));
        return $result->row_array();
    }
    
    function getExisteMontosPaquetesBySede($sede, $paquete){
        $sql = "SELECT count(1)
                  FROM (SELECT CASE WHEN (monto_pension IS NOT NULL)
                                    THEN monto_pension:: character varying
                                    ELSE '-'
                                END AS monto_pension,
                               CASE WHEN (descuento_nivel IS NOT NULL)
                                    THEN descuento_nivel:: character varying
                                    ELSE '-'
                                END AS descuento_nivel
                          FROM pagos.condicion
                         WHERE _id_sede = ?
                           AND _id_paquete = ?
                         LIMIT 1) AS tab";
        $result = $this->db->query($sql,array($sede,$paquete));
        return $result->row_array();
    }
}