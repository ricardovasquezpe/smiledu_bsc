<?php

class M_config extends  CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function guardarConfig($val1, $val2, $val3, $val4) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->where('id_config', CONFIG_MIN_MAX_SUB);
            $this->db->update('sped.sped_config', array("valor_num_1" => $val1, "valor_num_2" => $val2));
            $this->db->where('id_config', CONFIG_MIN_MAX_COR);
            $this->db->update('sped.sped_config', array("valor_num_1" => $val3, "valor_num_2" => $val4));
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getValoresInit() {
        $sql = "SELECT (SELECT valor_num_1 sub_val_num_1
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_SUB."),
               (SELECT valor_num_2 sub_val_num_2
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_SUB."),
               (SELECT val_min sub_val_min
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_SUB."),
               (SELECT val_max sub_val_max
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_SUB."),
               (SELECT valor_num_1 cor_valor_num_1
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_COR."),
               (SELECT valor_num_2 cor_valor_num_2
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_COR."),
               (SELECT val_min cor_val_min
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_COR."),
               (SELECT val_max cor_val_max
                  FROM sped.sped_config
                 WHERE id_config = ".CONFIG_MIN_MAX_COR.")";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
}