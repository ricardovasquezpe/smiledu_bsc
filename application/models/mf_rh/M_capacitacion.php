<?php
class M_capacitacion extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
function getCapacitaciones() {
        $sql = "SELECT id_capacitacion id,
                       desc_capacitacion title,
                       fec_programada::date AS start,
                       estado,
                       CASE estado WHEN 'REALIZADA' THEN 'true' ELSE 'false' END AS realizado,
                       observaciones,
                       CASE estado  WHEN 'REALIZADA' THEN '#43AC6D' ELSE '#004062' END AS color,
                       to_char(fec_realizada, 'DD MM YYYY') fec_reali
                  FROM capacitacion
                ORDER BY fec_programada";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getCombosByCapacitacion($idCap){
        $sql = "SELECT id_area,
                       id_sede,
                       id_area_especifica
                  FROM capacitacion
                 WHERE id_capacitacion = ?";
        
        $result = $this->db->query($sql, $idCap);
        return $result->row_array();
    }
    
    
    function getFreshData($id) {
        $sql = "SELECT desc_capacitacion title,
                       observaciones,
                       fec_programada,
                       to_char(fec_realizada, 'DD MM YYYY') fec_reali
                  FROM capacitacion
                 WHERE id_capacitacion = ?";
        $result = $this->db->query($sql, $id);
        return $result->row_array();
    }
    
    function crearCapacitacion($array) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('capacitacion' , $array);
            $idGenerado = $this->db->insert_id();
            if($this->db->affected_rows() == 1) {
                $data['id']            = $idGenerado;
                $data['title']         = $array['desc_capacitacion'];
                $data['start']         = $array['fec_programada'];
                $data['observaciones'] = $array['observaciones'];
                $data['editable']      = true;
                $data['error']         = EXIT_SUCCESS;
                $data['msj']           = MSJ_INS;
                $data['cabecera']      = CABE_INS;
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function editarCapacitacion($array, $eventId) {
        try{
            $this->db->where('id_capacitacion', $eventId);
            $this->db->update('capacitacion', $array);
            
            $datos = $this->getFreshData($eventId);
            $data['id']            = $eventId;
            $data['title']         = $datos['title'];
            $data['observaciones'] = $datos['observaciones'];
            $data['start']         = $datos['fec_programada'];
            $data['fec_reali']     = $datos['fec_reali'];
            $data['editable']      = true;
            $data['error']         = EXIT_SUCCESS;
            $data['msj']           = MSJ_UPT;
            
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}