<?php
class M_comparativa extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllComparativas(){
        $sql = "SELECT c._id_comparativa,
                       CONCAT( (CASE WHEN tipo_comparativa = 'HISTORICO' THEN CONCAT('(',c.year_indicador,') ') ELSE '' END) , c.desc_comparativa) AS desc_comparativa,
                       c.year,
                       INITCAP(c.tipo_comparativa) AS tipo_comparativa,
                       c.id_indicador,
                       c.valor_comparativa
                  FROM bsc.comparativa c
              ORDER BY c.year DESC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllIndicadores(){
        $sql = " SELECT ind.__id_indicador,
	                    CONCAT('(',ind.year,') ',ind.desc_registro) as descripcion,
                        ind.valor_actual_ultimo,
                        ind.year,
                        i.cod_indi
                   FROM bsc.indicador_detalle ind,
                        bsc.indicador i
                  WHERE ind.tipo_regi = 'INDI'
                        and i._id_indicador = ind.__id_indicador
               ORDER BY ind.__id_indicador , ind.year ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getValorActualUltimoByIndicador($idIndicador){
        $sql = "SELECT ind.valor_actual_ultimo,
                       ind.desc_registro,
                       ind.year
                  FROM bsc.indicador_detalle ind
                 WHERE ind.__id_indicador = ?
                   AND ind.tipo_regi      = 'INDI'";
        $result = $this->db->query($sql,array($idIndicador));
        $data   = $result->row_array();
        return $data;
    }
    
    function insertNuevaComparativa($arrayData){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $this->db->insert('bsc.comparativa' , $arrayData);
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_INS;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getComparativasByIndicador($idIndicador){
        $sql = "    SELECT c._id_comparativa,
                           CONCAT( (CASE WHEN tipo_comparativa = 'HISTORICO' THEN CONCAT('(',c.year_indicador,') ') ELSE '' END) , c.desc_comparativa) AS desc_comparativa,
                           ic.flg_acti,ic.__id_indicador,
                           c.valor_comparativa
                      FROM bsc.comparativa c LEFT JOIN bsc.indicador_x_comparativa ic ON(c._id_comparativa = ic.__id_comparativa AND ic.__id_indicador = ?)
                     WHERE ( (c.tipo_comparativa = 'HISTORICO' AND c.id_indicador = ?) OR (UPPER(c.tipo_comparativa) = 'OTRO' AND 1 = 1) )
                    ORDER BY c._id_comparativa";
        
        $result = $this->db->query($sql, array($idIndicador,$idIndicador));
        return $result->result();
    }
    
    function evaluaInsertUpdateComparativaXIndicador($idIndicador, $idComparativa){
        $sql = "SELECT COUNT(__id_indicador) as cuenta
                  FROM bsc.indicador_x_comparativa 
                 WHERE  __id_indicador  = ?
                   AND __id_comparativa = ?
                   AND year_comparativa = (SELECT EXTRACT(YEAR FROM now()))";
        $result = $this->db->query($sql,array($idIndicador,$idComparativa));
        $data   = $result->row_array();
        return $data;
    }
    
function updateInsertComparativasXIndicador($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                if($dato['condicion'] == 0 ) {//insert
                    unset($dato['condicion']);
                    $this->db->insert('bsc.indicador_x_comparativa', $dato);
                    $cont = $cont + $this->db->affected_rows();
                    $data['error']    = EXIT_SUCCESS;
                    $data['msj']      = MSJ_INS;
                } else if($dato['condicion'] == 1){//update
                    unset($dato['condicion']);
                    $this->db->where('__id_indicador'   , $dato['__id_indicador']);
                    $this->db->where('__id_comparativa' , $dato['__id_comparativa']);
                    $this->db->where('year_comparativa' , $dato['year_comparativa']);
                    unset($dato['__id_indicador']);
                    unset($dato['__id_comparativa']);
                    unset($dato['year_comparativa']);
                    $this->db->update('bsc.indicador_x_comparativa', $dato);
                    $data['error']    = EXIT_SUCCESS;
                    $data['msj']      = MSJ_UPT;
                    $cont = $cont + $this->db->affected_rows();
                }
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['error']     = EXIT_ERROR;
                $data['msj']   = '(MA-001)';
                $this->db->trans_rollback();
        
            }else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = MSJ_UPT;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function existeComparativaById($campo, $valor){
        $sql=null;
        if($campo == 'comparativa'){
            $sql = "    SELECT COUNT(desc_comparativa) as cuenta
                          FROM bsc.comparativa
                         WHERE desc_comparativa = ? 
                           AND year = (SELECT EXTRACT (YEAR FROM now()))";
            $result = $this->db->query($sql,array($valor));            
        } else if($campo == 'indicador'){
            $sql = "SELECT COUNT(desc_comparativa) as cuenta
                      FROM bsc.comparativa
                     WHERE id_indicador   = ?
                       AND year = (SELECT EXTRACT (YEAR FROM now()))
                       AND year_indicador = (SELECT year FROM bsc.indicador_detalle where __id_indicador = ? AND tipo_regi = 'INDI')";
            $result = $this->db->query($sql,array($valor,$valor));
        }
        return $result->row()->cuenta;
    }
    
    function getAllComparativasByIndicador($idIndicador,$yearIndi){
        $sql = "    SELECT c._id_comparativa,
                           CONCAT( (CASE WHEN tipo_comparativa = 'HISTORICO' THEN CONCAT('(',c.year_indicador,') ') ELSE '' END) , c.desc_comparativa) AS desc_comparativa,
                           ic.flg_acti,
                           c.tipo_comparativa,
                           ic.__id_indicador,
                           c.valor_comparativa
                      FROM bsc.comparativa c,
                           bsc.indicador_x_comparativa ic,
                           bsc.indicador_detalle id
	                 WHERE ic.__id_indicador = ?
	                   AND id.year = ?
	                   AND id.tipo_regi = 'INDI'
	                   AND id.__id_indicador = ic.__id_indicador
	                   AND ic.__id_comparativa = c._id_comparativa
	              ORDER BY ic.__id_comparativa, ic.year_comparativa";
        $result = $this->db->query($sql,array($idIndicador,$yearIndi));
        return $result->result();
    }
}