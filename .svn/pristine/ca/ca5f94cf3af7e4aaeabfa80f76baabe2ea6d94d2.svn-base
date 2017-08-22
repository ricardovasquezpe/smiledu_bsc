<?php
class M_cronograma extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getSedes() {
    	$sql = "SELECT nid_sede,
    	               desc_sede
				  FROM sede";
    	$result = $this->db->query($sql);
    	return $result->result();
    }
    
    function getYearCronograma($id) {
        $sql = "SELECT year,_id_sede
                  FROM pagos.cronograma
                WHERE id_cronograma = ?";
        $result = $this->db->query($sql,array($id));
        return $result->result();
    }
    
    function getYearCronoBySede($idSede) {
        $sql = "SELECT year
                  FROM pagos.cronograma
                 WHERE _id_sede = ?
                   AND (cast(to_char(now(),'YYYY') as int) = year OR cast(to_char(now(),'YYYY') as int) + 1 = year)
              GROUP BY year
              ORDER BY year";
        $result = $this->db->query($sql,array($idSede));
        return $result->result();
    }
    
    function getCronogramaByFiltro($idSede) {
        $sql = "SELECT c.id_cronograma,
                       CONCAT(c.desc_cronograma) desc_cronograma,
                       c.year,
                       c.flg_cerrado
                  FROM pagos.cronograma c,
                       pagos.tipo_cronograma tc
                 WHERE _id_sede              = ?
                   AND tc.id_tipo_cronograma = c._id_tipo_cronograma 
              ORDER BY year DESC";
        $result = $this->db->query($sql,array($idSede));
        return $result->result();
    }
    
    function getComboCronograma() {
    	$sql = "SELECT c.id_cronograma,
                       c.desc_cronograma
                  FROM pagos.cronograma c,
    	               pagos.tipo_cronograma tc
    	         WHERE tc.id_tipo_cronograma = c._id_tipo_cronograma
              ORDER BY _id_sede ASC, year DESC";
    	$result = $this->db->query($sql);
    	return $result->result();
    }
    
    function getSedeByCronograma($idCronograma) {
    	$sql = "SELECT c._id_sede,
    	               c.year,
    	               c.flg_cerrado,
    	               c.flg_cerrado_mat,
    	               s.desc_sede,
    	               tc.desc_tipo_cronograma,
    	               tc.id_tipo_cronograma
                  FROM pagos.cronograma       c,
    	               sede                   s,
    	               pagos.tipo_cronograma tc
                 WHERE id_cronograma          = ?
    	           AND s.nid_sede             = c._id_sede
    	           AND c._id_tipo_cronograma = tc.id_tipo_cronograma";
    	$result = $this->db->query($sql, $idCronograma);
    	return $result->row_array();
    }
    
    function getDetalleCronograma($idCronograma) {
        $sql = "SELECT id_detalle_cronograma,
                       desc_detalle_crono,
                       cantidad_mora,
                       fecha_vencimiento,
                       fecha_descuento,
                       flg_beca,
                       flg_tipo,
                       (SELECT COUNT(1) FROM pagos.movimiento m WHERE m._id_detalle_cronograma = id_detalle_cronograma)
                  FROM pagos.detalle_cronograma
                 WHERE _id_cronograma = ? 
              ORDER BY fecha_vencimiento, desc_detalle_crono";
        $result = $this->db->query($sql,$idCronograma);
        return $result->result();
    }
    
    function getItemCronograma($idItemCronograma) {
        $sql = "SELECT id_detalle_cronograma,
                       desc_detalle_crono,
                       cantidad_mora,
                       fecha_vencimiento,
                       fecha_descuento
                  FROM pagos.detalle_cronograma 
                 WHERE _id_cronograma = ?"; 
        $result = $this->db->query($sql,array($idItemCronograma));
        return $result->result_array();
    }
    
    function getBlockConcDeleteCrono() {
        $sql = "SELECT _id_detalle_cronograma,
                       fecha_pago
                  FROM pagos.movimiento
                 WHERE estado='PAGADO' 
                   AND tipo_movimiento = 'INGRESO' 
                   AND _id_detalle_cronograma is not null
              ORDER BY fecha_pago DESC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getItemCronoCalendarEdit($idsede,$year) {
        $sql = "SELECT mes,
                       cant_cuotas,
                       numero_mes
                  FROM pagos.cuota_x_mes
                 WHERE _id_sede=? and year=?
              ORDER BY numero_mes ASC";
        $result = $this->db->query($sql,array($idsede,$year));
        return $result->result_array();
    }
    
    function getItemCronoCalendarCuotas($id) {
        $sql = "SELECT desc_detalle_crono,
                       to_char(fecha_vencimiento, 'DD/MM/YYYY') fec_vencimiento,
                       CAST(to_char(fecha_vencimiento,'mm') as int) as n_mes
                  FROM pagos.detalle_cronograma
                 WHERE _id_cronograma = ?";
        $result = $this->db->query($sql,array($id));
        return $result->result();
    }
    
    function getItemConceptoCronograma($sede,$idItemCronograma) {
        $sql = "SELECT det.desc_detalle_crono as desc,
                       det.cantidad_mora as mora,
                       det.fecha_descuento as fdesc,
                       det.fecha_vencimiento as fvenc,
                       det.flg_tipo as condicional,
                       cu.cant_cuotas as n_cuotas
                  FROM pagos.detalle_cronograma as det
             LEFT JOIN pagos.cuota_x_mes cu ON cu.numero_mes = cast(to_char(det.fecha_vencimiento,'mm') as int) 
                                           AND cu.year       = cast(to_char(det.fecha_vencimiento,'yyyy') as int) 
                                           AND cu._id_sede   = ?
                 WHERE det.id_detalle_cronograma = ?";
        $result = $this->db->query($sql,array($sede,$idItemCronograma));
        return $result->result();
    }
    
    function getItemConceptoCronograma2($idItemCronograma) {
        $sql = "SELECT desc_detalle_crono as descrip,
                       cantidad_mora as mora,
                       fecha_descuento as fdesc,
                       fecha_vencimiento as fvenc,
                       flg_tipo as condicional
                  FROM pagos.detalle_cronograma
                 WHERE id_detalle_cronograma = ?";
        $result = $this->db->query($sql,array($idItemCronograma));
        
        $data['descrip']     = $result->row()->descrip;
        $data['mora']        = $result->row()->mora;
        $data['fdesc']       = $result->row()->fdesc;
        $data['fvenc']       = $result->row()->fvenc;
        $data['condicional'] = $result->row()->condicional;
        return $data;
    }

    function getCronogramas($id_sede = null) {
        $sql = "SELECT id_cronograma,
                       desc_cronograma
                  FROM pagos.cronograma
                 WHERE _id_sede = COALESCE(?,_id_sede) 
              ORDER BY _id_sede ASC ,year DESC";
        $result = $this->db->query($sql,array($id_sede));
        return $result->result();
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
    
    function crearCronograma($datos,$datoscuotas = null) {
        $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    $this->db->trans_begin();
        try{
            $this->db->insert("pagos.cronograma",$datos);
            $this->db->affected_rows();    
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) {
                throw new Exception('No se pudo guardar el cronograma');
            }
            if(count($datoscuotas) > 0){
                $this->db->insert_batch("pagos.cuota_x_mes",$datoscuotas);
                if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datoscuotas)) {
                    throw new Exception('No se pudo guardar las cuotas por sede');
                }
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = MSJ_INS;
            $data['cabecera']  = CABE_INS;
            $data['insert_id'] = $this->db->insert_id();
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function selectPlantillaCronograma($id) {
        $sql = "SELECT desc_detalle_crono,
                       cantidad_mora,
                       fecha_vencimiento,
                       fecha_descuento
                  FROM pagos.detalle_cronograma 
                 WHERE _id_cronograma=?";
        $result = $this->db->query($sql,array($id));
        if($result->num_rows() > 0) {
            return $result->result_array();
        }else {
            return null;
        }
    }
    
    function getListaCalendarCronograma($sedeCrono,$yearCrono) {
        $sql = "SELECT mes,
                       year,
                       _id_sede,
                       cant_cuotas,
                       numero_mes
                  FROM pagos.cuota_x_mes
                 WHERE _id_sede = ?
                   AND year     = ?
              ORDER BY numero_mes";
        $result = $this->db->query($sql,array($sedeCrono,$yearCrono));
        return $result->result();
    }
    
    function crearPlantillaCronograma($id,$yearCrono,$crear_cronograma,$datoscuotas = null) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            $sql = "SELECT desc_detalle_crono,
                           cantidad_mora,
                           fecha_vencimiento,
                           fecha_descuento,
                           flg_tipo,
                           flg_beca,
                           _id_paquete
                      FROM pagos.detalle_cronograma
                     WHERE _id_cronograma = ?";
            $result = $this->db->query($sql,array($id));
            
            if($result->num_rows() > 0) {
               $data['lista_conceptos']  = $result->result_array();
            }else {
               $data['lista_conceptos']  = null;
            }
            if(count($datoscuotas) > 0){
                $this->db->insert_batch("pagos.cuota_x_mes",$datoscuotas);
                if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 12) {
                    throw new Exception(MSJ_ERROR);
                }   
            }
            
            $this->db->insert("pagos.cronograma",$crear_cronograma);
            $this->db->affected_rows();
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) {
                throw new Exception(MSJ_ERROR);
            }
            $data['id_cronograma']  = $this->db->insert_id();
            $data['error']          = EXIT_SUCCESS;
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function validarCuotasCronograma($idCronograma) {
        $sql = "SELECT COUNT(to_char(fecha_vencimiento,'mm')) n_cuota,
                       cast(to_char(fecha_vencimiento,'mm') as int) mes
                  FROM pagos.detalle_cronograma
                 WHERE _id_cronograma = ?
              GROUP BY mes
              ORDER BY mes";
        $result = $this->db->query($sql,array($idCronograma));
        if($result->num_rows() > 0) {
            return $result->result();
        }else {
            return null;
        }
    }
    
    function crearPlantillaConceptosCronograma($lista_new_conceptos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        try{
            if(0<count($lista_new_conceptos)){
                $this->db->insert_batch("pagos.detalle_cronograma",$lista_new_conceptos);
                if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($lista_new_conceptos)) {
                    throw new Exception('No se pudo guardar la plantilla para el cronograma');
                }
                $data['id_cronograma_plantilla']  =$this->db->insert_id();
            }
            $data['error']     = EXIT_SUCCESS; 
            $data['msj']       = MSJ_INS;
            $data['cabecera']  = CABE_INS;
            $data['insert_id'] = $this->db->insert_id();
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function validarDescConceptoCronograma($descripcion,$idCronograma) {
        $sql = "SELECT id_detalle_cronograma
                  FROM pagos.detalle_cronograma
                 WHERE lower(desc_detalle_crono) = lower(?)
                   AND _id_cronograma            = ?";
        $result = $this->db->query($sql,array($descripcion,$idCronograma));
        if($result->num_rows() > 0) {
            return ($result->row()->id_detalle_cronograma);
        }else {
            return null;
        }
    }
    
    function validar_ConceptoMesCrono($mes,$idCronograma,$sede,$year) {
        $sql = "SELECT cant_cuotas, 
                       count(cro.fecha_vencimiento) n_cuotas
                  FROM pagos.cuota_x_mes
             LEFT JOIN pagos.detalle_cronograma as cro on cro._id_cronograma = ? and cast(to_char(cro.fecha_vencimiento,'mm') as int) = ?
                 WHERE numero_mes = ?
                   AND _id_sede   = ?
                   AND year       = ?
              GROUP BY cant_cuotas";
        $result = $this->db->query($sql,array($idCronograma,$mes,$mes,$sede,$year));
        if($result->num_rows() > 0) {
            return ($result->result_array());
        }else {
            return null;
        }   
    }
    
    function saveBecaCronograma($id,$beca, $id_persona, $name_persona) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            $this->db->where('id_detalle_cronograma',$id);
            $this->db->update('pagos.detalle_cronograma',array('flg_beca' =>$beca, 'id_pers_registro'=>$id_persona ,'nombre_pers_registro'=>$name_persona));
            if($this->db->trans_status() === FALSE){
                throw new Exception('No se puedo guardar la beca');
            }
            $data['msj']      = MSJ_UPT;
            $data['error']    = EXIT_SUCCESS;
            $data['cabecera'] = CABE_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function year_cronograma($idCronograma) {
        $sql = "SELECT year
                  FROM pagos.cronograma
                 WHERE id_cronograma = ?";
        $result = $this->db->query($sql,array($idCronograma));
        return $result->row()->year;
    }
    
    function validar_year_cronograma($sede,$year,$tipoCrono) {
        $sql = "SELECT count(id_cronograma) as n_year
                  FROM pagos.cronograma
                 WHERE _id_sede            = ? 
                   AND year                = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql,array($sede,$year,$tipoCrono));
        return $result->row()->n_year;
    }
    
    function crearConceptosCronograma($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            $this->db->insert("pagos.detalle_cronograma",$datos);
            if ($this->db->trans_status() === FALSE || $this->db->affected_rows() != 1) {
                throw new Exception('No se puedo guardar el concepto del cronograma');
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = MSJ_INS;
            $data['cabecera']  = CABE_INS;
            $data['insert_id'] = $this->db->insert_id();
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function update_cuota_x_mes($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            foreach($datos as $item){
                $id = array('mes'      => $item['mes'],
                            'year'     => $item['year'],
                            '_id_sede' => $item['_id_sede']);
                
                $this->db->set('cant_cuotas',$item['cant_cuotas']);
                $this->db->where($id);
                $this->db->update('pagos.cuota_x_mes');
                if ($this->db->trans_status() === FALSE || $this->db->affected_rows() != 1) {
                    throw new Exception(MSJ_ERROR);
                }
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = MSJ_UPT;
            $data['cabecera']  = CABE_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function editarConceptosCronograma($id,$datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            $this->db->where('id_detalle_cronograma', $id);
            $this->db->update('pagos.detalle_cronograma', $datos);
            if ($this->db->trans_status() === FALSE || $this->db->affected_rows() != 1) {
                throw new Exception(MSJ_ERROR);
            }
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_UPT;
            $data['cabecera'] = CABE_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getNombreSedes($idSede) {
        $sql = "SELECT desc_sede
                  FROM sede where nid_sede=?";
        $result = $this->db->query($sql,array($idSede));
        return $result->row()->desc_sede;
    }
    
    function eliminarConceptosCronograma($idConcepto) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            $sql    = "DELETE 
                         FROM pagos.detalle_cronograma
                        WHERE id_detalle_cronograma = ?";
            $result = $this->db->query($sql,array($idConcepto));
               
            if ($this->db->trans_status() === FALSE || $this->db->affected_rows() != 1) {
                throw new Exception(MSJ_ERROR);
            }
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_DEL;
            $data['cabecera'] = CABE_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function cerrarCronograma($idCronograma, $update) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$this->db->trans_begin();
        try{
            $this->db->where('id_cronograma', $idCronograma);
            $this->db->update('pagos.cronograma', $update);
            if($this->db->affected_rows() != 1 ||$this->db->trans_status() == FALSE){
                throw new Exception("Vuelva a Intentar");
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            $this->db->trans_commit();
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
    	return $data;
    }
    
    function getLastCrono($idSede, $year) {
    	$sql="SELECT count(1) as count
			    FROM pagos.cronograma
			   WHERE _id_sede    = ?
			     AND  year       = ?
			     AND flg_cerrado = '1'";
    	$result = $this->db->query($sql,array($idSede, $year));
    	return $result->row()->count;
    }
    
    function getExisteCrono($id_sede) {
    	$sql="SELECT exists(SELECT 1 FROM pagos.cronograma WHERE _id_sede = ?) as existe";
    	$result = $this->db->query($sql, array($id_sede));
    	return $result->row_array();
    }
    
    function checkIfExistsCuotaXMesBySedeYear($sede,$year) {
        $sql = "SELECT COUNT(1) count
                  FROM pagos.cuota_x_mes
                 WHERE _id_sede = ?
                   AND year     = ?";
        $result = $this->db->query($sql,array($sede,$year));
        return $result->row()->count;
    }
    
    function getTipoCuotasByCronograma($idCronograma) {
        $sql = "SELECT tc.id_tipo_cuota,
                       tc.desc_tipo_cuota
                  FROM pagos.tipo_cuota tc,
                       pagos.tipo_cronograma_x_tipo_cuota tcxtc,
                       pagos.cronograma c
                 WHERE tcxtc._id_tipo_cronograma = c._id_tipo_cronograma
                   AND tcxtc._id_tipo_cuota      = tc.id_tipo_cuota
                   AND c.id_cronograma           = ?";
        $result = $this->db->query($sql,array($idCronograma));
        return $result->result();
    }
   
    function countCompromisoSinMov($idCronograma){
        $sql = "SELECT COUNT(1)
                  FROM pagos.detalle_cronograma dc,
                       pagos.movimiento          m,
                       pagos.cronograma          c
                 WHERE c.id_cronograma          = dc._id_cronograma
                   AND dc.id_detalle_cronograma = m._id_detalle_cronograma
                   AND c.id_cronograma          = ?";
        $result = $this->db->query($sql, array($idCronograma))->row()->count;
        return $result;
    }
    
    function eliminaCronograma($idCronograma) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $result = $this->countCompromisoSinMov($idCronograma);
        $this->db->trans_begin();
        try{
            if($result == 0) {
            $this->db->where('_id_cronograma',$idCronograma);
            $this->db->delete('pagos.detalle_cronograma');
            
            $this->db->where('id_cronograma',$idCronograma);
            $this->db->delete('pagos.cronograma');
            if($this->db->affected_rows() != 1) {
                throw new Exception('No se pudo eliminar cronograma');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = "Se elimin&oacute; el cronograma";
            $this->db->trans_commit();
            }
        }catch (Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
}