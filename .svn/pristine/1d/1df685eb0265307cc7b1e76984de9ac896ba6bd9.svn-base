<?php

class M_utils_pagos extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getFlgCerrados($sede,$year,$tipo, $nombreTabla){
        $sql = "SELECT flg_cerrado,
                       flg_cerrado_mat
                  FROM pagos.".$nombreTabla."
                 WHERE _id_sede            = ?
                   AND year                = ?
                   AND _id_tipo_cronograma = ?";
        $result = $this->db->query($sql,array($sede,$year,$tipo));
        if($result->num_rows() == 0) {
            $result = null;
            $result['flg_cerrado'] = 0;
            $result['flg_cerrado_mat'] = 0;
            return $result;
        }
        return $result->row_array();
    }
    
    function getPaquetesByTipo($tipoCrono,$idCrono){
        $sql = "SELECT id_paquete,
                       desc_paquete
                  FROM pagos.paquete
                 WHERE flg_tipo = ?
                   AND flg_acti = '1'
                   AND CASE WHEN ? IS NOT NULL
                            THEN id_paquete NOT IN(SELECT _id_paquete
                			                         FROM pagos.detalle_cronograma dc
                                                    WHERE _id_cronograma = ? )
                            ELSE 1 = 1
                       END ";
        $result = $this->db->query($sql, array($tipoCrono,$idCrono,$idCrono));
        return $result->result();
    }
}