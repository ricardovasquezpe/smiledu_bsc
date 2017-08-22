<?php
class M_config_valor_graf extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllLineasEstrategicas(){
        $sql = "SELECT le._id_linea_estrategica,
                       le.desc_linea_estrategica,
                       le.flg_amarillo,
                       le.flg_verde
                  FROM bsc.linea_estrategica le
                  ORDER BY _id_linea_estrategica";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllObetivosByLinea($idLineaEstrategica){
        $sql = "SELECT o._id_objetivo,
                       o.desc_objetivo,
                       o.flg_amarillo,
                       o.flg_verde
                  FROM bsc.objetivo o
                 WHERE o.__id_linea_estrategica = ?
              ORDER BY o._id_objetivo";
        $result = $this->db->query($sql, array($idLineaEstrategica));
        return $result->result();
    }
    
    function updateValoresLinea($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                $this->db->where('_id_linea_estrategica', $dato['_id_linea_estrategica']);
                 
                unset($dato['_id_linea_estrategica']);
                $this->db->update('bsc.linea_estrategica', $dato);
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MA-001)';
                $this->db->trans_rollback();
    
            }else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = MSJ_UPT;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function updateValoresObjetivos($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                $this->db->where('_id_objetivo', $dato['_id_objetivo']);
                 
                unset($dato['_id_objetivo']);
                $this->db->update('bsc.objetivo', $dato);
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MA-001)';
                $this->db->trans_rollback();
    
            }else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = MSJ_UPT;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getGrupoEducativo(){
        $sql = "SELECT c.id_config,
                       c.desc_config,
                       c.valor_numerico1,
                       c.valor_numerico2
                  FROM bsc.config c
                 WHERE c.id_config = 1";
        
        $result= $this->db->query($sql);
        $data   = $result->row_array();
        return $data;
    }
    
    function updateValoresGrupoEduc($array){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_config', $array['id_config']);
            unset($array['id_config']);
            $this->db->update('bsc.config', $array);
            if($this->db->affected_rows() != 1){
                throw new Exception('(ME-002)');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}