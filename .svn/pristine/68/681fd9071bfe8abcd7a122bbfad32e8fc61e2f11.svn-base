<?php
class M_calendario extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    function getDiasNoLaborables() {
        $sql = "SELECT pk_fecha id,
                       descripcion AS title,
                       CONCAT((CAST(EXTRACT(epoch FROM pk_fecha at time zone 'utc') AS INTEGER)), '000') AS start,
                       flg_laborable laborable
                  FROM calendario
                 WHERE flg_laborable = 0
                   AND EXTRACT(YEAR FROM pk_fecha) = "._YEAR_."
                 ORDER BY pk_fecha";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function crearDiaNoLaborable($fecha, $descr){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            //validar
            $cnt = $this->esDiaNoLaborable($fecha);
            if($cnt != 0) {
                throw new Exception('El día ya está marcado como no laborable');
            }
            $this->db->where('pk_fecha', $fecha);
            $this->db->update('calendario' , array("flg_laborable" => 0, "descripcion" => $descr));
            if($this->db->affected_rows() == 1) {
                $data['id'] = $fecha;
                $data['title'] = $descr;
                $data['start'] = $fecha;
                $data['editable'] = true;
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = CABE_INS;
                $data['cabecera'] = CABE_INS;
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function esDiaNoLaborable($fecha) {
        $sql = "SELECT COUNT(1) cnt
                  FROM calendario
                 WHERE pk_fecha = ?
                   AND flg_laborable = 0";
        $result = $this->db->query($sql, $fecha);
        return $result->row()->cnt;
    }
    
    function editarBorrarDiaNoLaborable($fecha, $borrarLaborable, $descripcion) {
        try{
            $arrayUpdate = array();
            if($borrarLaborable != 'true' && $borrarLaborable != 'false') {
                throw new Exception(ANP);
            }
            if($borrarLaborable == 'true') {
                $arrayUpdate = array("flg_laborable" => 1, "descripcion" => null);
            } else {
                $arrayUpdate = array("descripcion" => $descripcion);
            }
            $this->db->where('pk_fecha', $fecha);
            $this->db->update('calendario', $arrayUpdate);
            if($borrarLaborable == 'false') {
                $data['id']    = $fecha;
                $data['title'] = $descripcion;
                $data['start'] = $fecha;
                $data['editable'] = true;
                $data['borrar']   = 0;
            } else {
                $data['borrar'] = 1;
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = CABE_INS;
            $data['cabecera'] = CABE_UPT;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}