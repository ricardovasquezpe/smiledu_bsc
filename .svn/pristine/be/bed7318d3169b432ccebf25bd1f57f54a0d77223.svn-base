<?php
class M_incidencia extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    function getAllIncidencias(){
        $sql = "SELECT il.desc_incidencia,
                       il.id_incidencia,
                       il.fecha_incidencia,
                       CONCAT(il.desc_sede, ' - ',il.desc_area, ' - ', il.desc_area_especifica) AS lugar,
                       INITCAP(il.nombres_personal) nombres_personal,
                       il.flg_checkbox,
                       INITCAP(il.audi_pers_regi) audi_pers_regi,
                       il.audi_fec_regi,
                       ct.desc_combo,
                       ct.valor,
            CASE WHEN (ct.valor = ".INC_CLIMA_LABORAL."::CHARACTER VARYING AND il.flg_checkbox   = '1') THEN 'bg-success'
                 WHEN (ct.valor = ".INC_CLIMA_LABORAL."::CHARACTER VARYING AND il.flg_checkbox   = '0') THEN 'bg-warning'
                 WHEN (ct.valor = ".INC_DESCANSO_MEDICO."::CHARACTER VARYING AND il.flg_checkbox = '1') THEN 'bg-success'
                 WHEN (ct.valor = ".INC_DESCANSO_MEDICO."::CHARACTER VARYING AND il.flg_checkbox = '0') THEN 'bg-warning'
                 ELSE '' END AS color
                FROM incidencia_laboral il,
                     combo_tipo ct
                WHERE il.tipo_incidencia::CHARACTER VARYING = ct.valor
                  AND ct.grupo = 3
                ORDER BY fecha_incidencia DESC";
        
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllAreasEmpresa(){
        $sql = "SELECT a.id_area,
                       a.desc_area
                FROM   area a
                 WHERE a.flg_general = 1";
        
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllAreasEspecificasEmpresa($idArea){
        $sql = "SELECT a.id_area,
                       a.desc_area
                  FROM area a
                 WHERE a.flg_general = 0
                   AND a.id_area_general = ? 
              ORDER BY desc_area";
    
        $result = $this->db->query($sql,array($idArea));
        return $result->result();
    }
    
    function getAllTipoIncidencias(){
        $sql = "SELECT ct.valor,
                       ct.desc_combo
                FROM   combo_tipo ct
                 WHERE ct.grupo = 3";
        
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function insertIncidencia($array){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try {
            $this->db->insert('incidencia_laboral',$array);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception(ANP);
            }
            $rpta['error']     = EXIT_SUCCESS;
            $rpta['msj']       = MSJ_INS;
            $rpta['cabecera']  = CABE_INS;
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    
    function cambiarEstado($array, $idIncidencia){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try {
            $this->db->where('id_incidencia', $idIncidencia);
            $this->db->update('incidencia_laboral', $array); 
            if ($this->db->trans_status() === FALSE) {
                throw new Exception(ANP);
            }
            $rpta['error']     = EXIT_SUCCESS;
            $rpta['msj']       = MSJ_INS;
            $rpta['cabecera']  = CABE_INS;
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    
    function getDescComboTipo($campo, $valor){
        $sql = "SELECT desc_combo
                 FROM  combo_tipo
                 WHERE grupo = ?
                 AND   valor = ?";
        $result = $this->db->query($sql, array($campo,$valor));
        $data = array();
        $data = $result->row();
        return $data->desc_combo;
    }
}