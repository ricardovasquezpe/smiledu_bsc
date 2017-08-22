<?php
class M_config_medida_nota extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getConfigMedidaRashNota($config) {
	    $sql = " SELECT id_config,
	                    desc_config,
	                    valor_numerico,
	                    CASE WHEN tipo_ece = 'ECE' THEN 22
	                         WHEN tipo_ece = 'EAI' THEN 24
	                         WHEN tipo_ece = '23'  THEN 23 END AS grupo,
	                    id_nota
                   FROM config
                  WHERE tipo_ece = ?
                    AND year_config = (SELECT EXTRACT(YEAR FROM now()))
               ORDER BY desc_config";
	    $result = $this->db->query($sql, array($config));
        return $result->result();
	}
	
	function editPuntajeConfig($pk, $campo, $valor, $tipoEce){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj']   = null;
	    $this->db->trans_begin();
	    try{
	        $data = array($campo => (($valor == null) ? null : $valor));
	        $this->db->where('id_config', $pk);
	        $this->db->update('config', $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MDI-001)');
	        }
	        if($tipoEce == EAI_EVALUACION) {
	            //Actualizar puntaje de todos los alumnos
	            $sql = "UPDATE persona_x_aula
                           SET ind_logro_eai_comu = (CASE WHEN medida_rash_eai_comu <= config.inicio THEN 1
                                                          WHEN medida_rash_eai_comu > config.inicio AND medida_rash_eai_comu <= config.proceso THEN 2
                                                          WHEN medida_rash_eai_comu > config.proceso THEN 3 END ),
                               ind_logro_eai_mate = (CASE WHEN medida_rash_eai_mate <= config.inicio THEN 1
                                                          WHEN medida_rash_eai_mate > config.inicio AND medida_rash_eai_mate <= config.proceso THEN 2
                                                          WHEN medida_rash_eai_mate > config.proceso THEN 3 END ),
                               ind_logro_eai_ciencia = (CASE WHEN medida_rash_eai_ciencia <= config.inicio THEN 1
                                                             WHEN medida_rash_eai_ciencia > config.inicio AND medida_rash_eai_ciencia <= config.proceso THEN 2
                                                             WHEN medida_rash_eai_ciencia > config.proceso THEN 3 END ),
                               ind_logro_eai_infor = (CASE WHEN medida_rash_eai_infor <= config.inicio THEN 1
                                                           WHEN medida_rash_eai_infor > config.inicio AND medida_rash_eai_infor <= config.proceso THEN 2
                                                           WHEN medida_rash_eai_infor > config.proceso THEN 3 END )
                          FROM (SELECT *
                        	  FROM (SELECT valor_numerico inicio
                        		  FROM config
                        		 WHERE year_config = "._YEAR_."
                        		   AND tipo_ece    = '".EAI_EVALUACION."'
                        		   AND id_nota     = 1) inicio,
                        		(SELECT valor_numerico proceso
                        		  FROM config
                        		 WHERE year_config = "._YEAR_."
                        		   AND tipo_ece    = '".EAI_EVALUACION."'
                        		   AND id_nota     = 2) proceso) config
                         WHERE year_academico = "._YEAR_."
                           AND flg_acti       = '1' ";
	            $result = $this->db->query($sql);
	        } else if($tipoEce == ECE_EVALUACION) {
	            $sql = "UPDATE persona_x_aula
                           SET nivel_logro_lectora = (CASE WHEN medida_rash_lectura <= config.inicio THEN '".ECE_INICIO."'
                                                          WHEN medida_rash_lectura > config.inicio AND medida_rash_lectura <= config.proceso THEN '".ECE_PROCESO."'
                                                          WHEN medida_rash_lectura > config.proceso THEN '".ECE_SATISF."'
                                                          ELSE NULL END ),
                               ind_logro_lectura = (CASE WHEN medida_rash_lectura <= config.inicio THEN 1
                                                         WHEN medida_rash_lectura > config.inicio AND medida_rash_lectura <= config.proceso THEN 2
                                                         WHEN medida_rash_lectura > config.proceso THEN 3 
                                                         ELSE NULL END ),
                               nivel_logro_matematica = (CASE WHEN medida_rash_matematica <= config.inicio THEN '".ECE_INICIO."'
                                                              WHEN medida_rash_matematica > config.inicio AND medida_rash_matematica <= config.proceso THEN '".ECE_PROCESO."'
                                                              WHEN medida_rash_matematica > config.proceso THEN '".ECE_SATISF."'
                                                              ELSE NULL END ),
                               ind_logro_matematica = (CASE WHEN medida_rash_matematica <= config.inicio THEN 1
                                                            WHEN medida_rash_matematica > config.inicio AND medida_rash_matematica <= config.proceso THEN 2
                                                            WHEN medida_rash_matematica > config.proceso THEN 3 
                                                            ELSE NULL END )
                          FROM (SELECT *
                        	  FROM (SELECT valor_numerico inicio
                        		  FROM config
                        		 WHERE year_config = "._YEAR_."
                        		   AND tipo_ece    = '".ECE_EVALUACION."'
                        		   AND id_nota     = 1) inicio,
                        		(SELECT valor_numerico proceso
                        		  FROM config
                        		 WHERE year_config = "._YEAR_."
                        		   AND tipo_ece    = '".ECE_EVALUACION."'
                        		   AND id_nota     = 2) proceso) config
                         WHERE year_academico = "._YEAR_."
                           AND flg_acti       = '1' ";
	            $result = $this->db->query($sql);
	        }
	        $this->db->trans_commit();
	        $rpta['error'] = EXIT_SUCCESS;
	        $rpta['msj']   = 'Se ha modificado';
	    }catch(Exception $e){
	        $rpta['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $rpta;
	}
	//Model POPUP
	function getCountConfig($configpopup,$idconfigPromMedida){
	    $sql ="SELECT COUNT(1) cant
        	     FROM config
        	    WHERE desc_config LIKE '%$configpopup%'
        	      AND id_nota = ?
        	      AND year_config = (SELECT EXTRACT(YEAR FROM now())) LIMIT 1";
	    $result = $this->db->query($sql, array($idconfigPromMedida));
	    return ($result->row()->cant);
	}
	//Busca el puntaje de medida rash de inicio para luego verificar que se mayor que la de proceso
	function getConfigPtjeMedidaRashInicio($siglaConfig){
	    $sql ="SELECT valor_numerico
                 FROM  config
                WHERE desc_config LIKE '%$siglaConfig%'
                  AND year_config = (SELECT EXTRACT(YEAR FROM now()))";
	    $result = $this->db->query($sql);
	    return ($result->row()->valor_numerico);
	}
		
	function getExisteMedidaRashInicio($siglaConfig){
	    $sql ="SELECT COUNT(1) cant
        	     FROM  config
        	    WHERE desc_config LIKE '%$siglaConfig%'
        	      AND year_config = (SELECT EXTRACT(YEAR FROM now())) LIMIT 1";
	    $result = $this->db->query($sql);
	    return ($result->row()->cant);
	}
	
	function insertaConfigPopup($arrayDatos) {
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
	
	function getExisteMedidaRash($tipoMedidaRash, $idNota){
	    $sql ="SELECT valor_numerico
                 FROM config
                WHERE year_config = "._YEAR_."
                  AND tipo_ece    = ?
                  AND id_nota     = ?";
	    $result = $this->db->query($sql, array($tipoMedidaRash, $idNota));
	    if($result->num_rows() == 0) {
	        return null;
	    } else {
	        return ($result->row()->valor_numerico);
	    }
	}
}