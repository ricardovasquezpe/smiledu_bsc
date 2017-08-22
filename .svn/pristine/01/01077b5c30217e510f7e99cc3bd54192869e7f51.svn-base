<?php
class M_config_ptje extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getConfigPtje(){
	    $sql = " SELECT id_config,
	                    desc_config,
	                    id_univ,
	                    valor_numerico,
	                    year_config
                   FROM config
                  WHERE tipo_examen = '".SIMULACRO."'
	                AND year_config = (SELECT EXTRACT(YEAR FROM now()))
	               ORDER BY desc_config";
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	function UpdatePuntajeConfigPtje($arrayDatos){
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
	    try {
	        $cont = 0;
	        foreach ($arrayDatos as $dato) {
	         $this->db->where('id_univ', $dato['id_univ']);
             $this->db->where('year_config', $dato['year_config']);	 
             $this->db->where('tipo_examen', $dato['tipo_examen']);
             unset($dato['id_univ']);
             unset($dato['year_config']);	                
             $this->db->update('config', $dato);
             $cont = $cont + $this->db->affected_rows();
	        }
	        if($cont != count($arrayDatos) ) {
	            $this->db->trans_rollback();
	            throw new Exception('(MA-002)');
	        }if($this->db->trans_status() === FALSE) {
	            $data['error']     = EXIT_ERROR;
	            $data['msj']   = '(MA-001)';
	            $this->db->trans_rollback();
	        
	        }else {
	            $data['error']     = EXIT_SUCCESS;
	            $data['msj']       = MSJ_INS;
	            $data['cabecera']  = CABE_INS;
	            $this->db->trans_commit();
	        }
	    } catch (Exception $e) {
	        $data['error'] = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function insertaPuntajeConfigPtjePopup($arrayDatos) {
	    $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    try {
            $this->db->insert('config', $arrayDatos);
            if($this->db->affected_rows() != 1) {
                throw new Exception("MCONFIG-001");
            }
            $data['error']    = EXIT_SUCCESS;  
            $data['msj']       = MSJ_INS;
            $data['cabecera']  = CABE_INS;
	    } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	//Model POPUP
	function getCountUnivPtjeConfig($idUniv, $year){
	    $sql ="SELECT COUNT(1) cant
                 FROM config
                WHERE id_univ     = ?
                  AND tipo_examen = '".SIMULACRO."'
                  AND year_config = ? LIMIT 1";
	    $result = $this->db->query($sql, array($idUniv, $year));
	    return ($result->row()->cant);
	}
	
	function editPuntajeConfig($pk, $campo, $valor){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj']   = null;
	    try{
	        $data = array($campo => (($valor == null) ? null : $valor));
	        $this->db->where('id_config', $pk);
	        $this->db->update('config', $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MDI-001)');
	        }
	        $rpta['error'] = EXIT_SUCCESS;
	        $rpta['msj']   = 'Se ha modificado';
	    }catch(Exception $e){
	        $rpta['msj']   = $e->getMessage();
	    }
	    return $rpta;
	}
}