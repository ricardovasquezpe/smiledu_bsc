<?php
class M_correos extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    
    function getNextCorrelativoByYear() {
        $sql = "SELECT to_number(MAX(correlativo),'9999999999')+1  cuenta
                  FROM pagos.correo_x_calendario
                 WHERE year = "._YEAR_."";
        $result = $this->db->query($sql);
        return $result->row()->cuenta;
    }
    
    function insertFechasEnvioCorreo($arrayInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert_batch('pagos.correo_x_calendario',$arrayInsert);
            if($this->db->trans_status() === FALSE || $this->db->affected_rows() != count($arrayInsert)){
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = "Se registr&oacute;";
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getCountByDate($fecha_envio,$tipoCorreo) {
        $sql = "SELECT COUNT(1) cuenta 
                  FROM pagos.correo_x_calendario 
                 WHERE fecha_envio = ?
                   AND flg_tipo_correo = ?";
        $result = $this->db->query($sql,array($fecha_envio,$tipoCorreo));
        return $result->row()->cuenta;
    }
    
    function getAllEventosActivos() {
        $sql = "SELECT EXTRACT(EPOCH FROM fecha_envio::timestamp without time zone + interval '24 hour')*1000 as start,
                       EXTRACT(EPOCH FROM fecha_envio::timestamp without time zone + interval '24 hour')*1000 as end,
                       CASE WHEN(flg_tipo_correo = '".CUOTA_VENCIDA."')   THEN CONCAT('Cuota Vencida'    , ' ', to_char(fecha_envio,'DD/MM/YYYY'))
                            WHEN(flg_tipo_correo = '".PRONTO_PAGO."')     THEN CONCAT('Pronto Pago'      , ' ', to_char(fecha_envio,'DD/MM/YYYY'))
                            WHEN(flg_tipo_correo = '".REC_VENCIMIENTO."') THEN CONCAT('Rec. Vencimiento' , ' ', to_char(fecha_envio,'DD/MM/YYYY'))
                       END AS title,
                       CASE WHEN(flg_tipo_correo = '".CUOTA_VENCIDA."')   THEN 'event-important'
                            WHEN(flg_tipo_correo = '".PRONTO_PAGO."')     THEN 'event-success'
                            WHEN(flg_tipo_correo = '".REC_VENCIMIENTO."') THEN 'event-warning'
                       END AS class
                  FROM pagos.correo_x_calendario";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function updateCorreo($arrayUpdate, $correlatico, $year) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$this->db->trans_begin();
    	try{
    		$this->db->where('year', $year);
    		$this->db->where('correlativo', $correlatico);
    		$this->db->update('pagos.correo_x_calendario', $arrayUpdate);
    		if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
    			throw new Exception("Vuelva a Intentar");
    		}
    		$data['error'] = EXIT_SUCCESS;
    		$data['msj']   = "Correo Actualizado";
    		$this->db->trans_commit();
    	}catch (Exception $e){
    		$data['msj']   = $e->getMessage();
    		$this->db->trans_rollback();
    	}
    	return $data;
    }
    
    function deleteCorreo($arrayFechas, $day) {
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$this->db->trans_begin();
    	try{
    		$sql="SELECT COUNT(1) as count
				    FROM pagos.correo_x_calendario
				   WHERE year = "._YEAR_."
				     AND (EXTRACT(DAY FROM fecha_envio)) = ".$day."
				     AND (SELECT EXTRACT(MONTH FROM now())) <= (EXTRACT(MONTH FROM fecha_envio))";
    		$result = $this->db->query($sql, array($day));
    		$count = $result->row()->count;
    		
    		$this->db->where_in('fecha_envio', $arrayFechas);
    		$this->db->delete('pagos.correo_x_calendario');
    		if($this->db->affected_rows() != $count || $this->db->trans_status() == FALSE){
    			throw new Exception("Vuelva a Intentar");
    		}
    		$data['error'] = EXIT_SUCCESS;
    		$data['msj']   = "Correos Eliminados";
    		$this->db->trans_commit();
    	}catch (Exception $e){
    		$data['msj']   = $e->getMessage();
    		$this->db->trans_rollback();
    	}
    	return $data;
    }
    
    function getEstudiantesPensionesVencidas() {
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) nombre_completo,
                       string_agg(CONCAT(
                                         dc.desc_detalle_crono                        , '|' ,
                                         m.monto                                      , '|' ,
                                         m.mora_acumulada                             , '|' ,
                                         m.monto_final                                , '|' ,
                                         to_char(dc.fecha_vencimiento, 'dd/mm/yyyy')
                                        ) , ',' ORDER BY dc.fecha_vencimiento
                                 ) as cuotas,
                       da.cod_familia,
                       (SELECT string_agg(
                                          CONCAT(
                                                 f.email1      , '|'  ,
                                                 UPPER(f.ape_paterno) , ' '  ,
                                                 UPPER(f.ape_materno) , ', ' ,
                                                 INITCAP(f.nombres)
                                                )    , ','  
                                         )
                          FROM familiar                  f,
                               sima.familiar_x_familia fxf
                         WHERE fxf.flg_resp_economico = '".FLG_RESPONSABLE."'
                           AND f.flg_vive             = '1'
                           AND fxf.cod_familiar       = da.cod_familia
                           AND f.email1               ~ '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$'
                           AND f.id_familiar          = fxf.id_familiar
                      GROUP BY f.id_familiar
                         LIMIT 1) as apoderado
                  FROM pagos.movimiento           m,
                       persona                    p,
                       sima.detalle_alumno       da,
                       pagos.detalle_cronograma  dc
                 WHERE m.estado                 = '".ESTADO_VENCIDO."'
                   AND p.nid_persona            = m._id_persona
                   AND m._id_detalle_cronograma = dc.id_detalle_cronograma
                   AND da.nid_persona           = p.nid_persona
                 GROUP BY p.nid_persona,da.cod_familia
                 ORDER BY cod_familia";
        $result = $this->db->query($sql,array());
        return $result->result();
    }
    
    function getEstudiantesPagoPuntual() {
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) nombre_completo,
                       da.cod_familia,
                       (SELECT string_agg(
                                          CONCAT(
                                                 f.email1      , '|'  ,
                                                 f.ape_paterno , ' '  ,
                                                 f.ape_materno , ', ' ,
                                                 f.nombres
                                                )    , ','
                                         )
                          FROM familiar                  f,
                               sima.familiar_x_familia fxf
                         WHERE fxf.flg_resp_economico = '".FLG_RESPONSABLE."'
                           AND f.flg_vive             = '1'
                           AND fxf.cod_familiar       = da.cod_familia
                           AND f.email1               ~ '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$'
                           AND f.id_familiar          = fxf.id_familiar
                      GROUP BY f.id_familiar
                         LIMIT 1
                       ) as apoderado
                  FROM pagos.movimiento           m,
                       persona                    p,
                       pagos.detalle_cronograma  dc,
                       sima.detalle_alumno       da
                 WHERE m.estado                 = 'PAGADO'
                   AND (EXTRACT (MONTH FROM dc.fecha_descuento)) = (SELECT EXTRACT(MONTH FROM current_date))
                   AND m.fecha_pago::timestamp::date             <= dc.fecha_descuento
                   AND p.nid_persona                             = m._id_persona
                   AND m._id_detalle_cronograma                  = dc.id_detalle_cronograma
                   AND da.nid_persona                            = p.nid_persona
                 GROUP BY p.nid_persona,da.cod_familia
                 ORDER BY cod_familia";
        $result = $this->db->query($sql,array());
        return $result->result();
    }
    
    function getEstudiantesRecVencimiento() {
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) nombre_completo,
                       da.cod_familia,
                       string_agg(CONCAT(
                                         dc.desc_detalle_crono                        , '|' ,
                                         m.monto                                      , '|' ,
                                         '0'                                          , '|' ,                                            
                                         m.monto_final                                , '|' ,
                                         to_char(dc.fecha_vencimiento, 'dd/mm/yyyy')
                                        ) , ',' 
                                 ) as cuotas,
                       (SELECT string_agg(
                                          CONCAT(
                                                 f.email1      , '|'  ,
                                                 f.ape_paterno , ' '  ,
                                                 f.ape_materno , ', ' ,
                                                 f.nombres
                                                )    , ','  
                                         )
                          FROM familiar                  f,
                               sima.familiar_x_familia fxf
                         WHERE fxf.flg_resp_economico = '".FLG_RESPONSABLE."'
                           AND f.flg_vive             = '1'
                           AND fxf.cod_familiar       = da.cod_familia
                           AND f.email1               ~ '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$'
                           AND f.id_familiar          = fxf.id_familiar
                      GROUP BY f.id_familiar
                         LIMIT 1) as apoderado
                  FROM pagos.movimiento           m,
                       persona                    p,
                       sima.detalle_alumno       da,
                       pagos.detalle_cronograma  dc
                 WHERE m.estado                                  = 'POR PAGAR'
                   AND (EXTRACT (MONTH FROM dc.fecha_descuento)) = (SELECT EXTRACT(MONTH FROM current_date))
                   AND p.nid_persona                             = m._id_persona
                   AND m._id_detalle_cronograma                  = dc.id_detalle_cronograma
                   AND da.nid_persona           = p.nid_persona
                 GROUP BY p.nid_persona,da.cod_familia
                 ORDER BY cod_familia";
        $result = $this->db->query($sql,array());
        return $result->result();
    }
}