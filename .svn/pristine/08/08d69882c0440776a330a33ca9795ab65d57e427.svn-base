<?php
class M_pesos_asistencia extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function selectTableAsist() {
        $sql = "SELECT *
                  FROM asistencia_config
                ORDER BY id_asist_config asc";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function selectTableAsistCalif() {
        $sql = "SELECT *
                  FROM asistencia_calificacion_config
                ORDER BY id_asist_calif_config asc";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function agregarPeso($dato, $idAsistencia) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->where ('id_asist_config', $idAsistencia);
            $this->db->update('asistencia_config', $dato);
            if($this->db->affected_rows() != 1) {
                throw new Exception();
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function agregarCalif($dato, $idAsistenciaCalif) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->where ('id_asist_calif_config', $idAsistenciaCalif);
            $this->db->update('asistencia_calificacion_config', $dato);
            if($this->db->affected_rows() != 1) {
                throw new Exception();
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}