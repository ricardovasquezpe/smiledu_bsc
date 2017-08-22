<?php
//LAST CODE 001
class M_formulario extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getNextCodGrupo(){
        $sql = "SELECT (max(cod_grupo)+1) cod_grupo
                  FROM admision.contacto";
        $result = $this->db->query($sql);
        return $result->row()->cod_grupo;
    }
    
    function insertFamilia($data){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        $idFamilia = array();
        try{
            $this->db->trans_begin();
            foreach ($data as $dat){
                $this->db->insert('admision.contacto', $dat);
                if($this->db->affected_rows() != 1) {
                    throw new Exception('(MA-001)');
                }
                array_push($idFamilia, $this->db->insert_id());
            }
            $this->db->trans_commit();
            $rpt['arrayIds']=$idFamilia;
            $rpt['error']  = EXIT_SUCCESS;
            $rpt['msj']    = MSJ_INSERT_SUCCESS;
        }catch(Exception $e){
	        $this->db->trans_rollback();
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getEventoRegistro(){
        $sql ="SELECT id_evento,
                      desc_evento,
                      fecha_realizar,
                      cast(hora_inicio as time) AS hora_inicio,
                      observacion
                 FROM admision.evento 
                WHERE fecha_realizar = (SELECT min(fecha_realizar) 
                                          FROM admision.evento
                                         WHERE (fecha_realizar >= now()::date OR fecha_realizar IS NULL))
                  AND UPPER(estado) like UPPER('".EVENTO_PENDIENTE."')
                  ORDER BY id_evento";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
    
    function inscribirFamEvento($data){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        $idFamilia = array();
        try{
            $this->db->trans_begin();
            foreach ($data as $dat){
                $this->db->insert('admision.invitados', $dat);
                if($this->db->affected_rows() != 1) {
                    throw new Exception('(MA-001)');
                }
            }
            $this->db->trans_commit();
            $rpt['error']  = EXIT_SUCCESS;
            $rpt['msj']    = MSJ_INSERT_SUCCESS;
        }catch(Exception $e){
            $this->db->trans_rollback();
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function insertColegio($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("sima.colegios", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MA-001)');
            }
    
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INSERT_SUCCESS;
            $rpt['id']    = $this->db->insert_id();
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
    
        return $rpt;
    }
    
    function validateColegioRepetido($descColegio){
        $sql = "SELECT COUNT(1) cant
                  FROM sima.colegios
                 WHERE UPPER(unaccent(desc_colegio)) = UPPER(unaccent(?))";
        $result = $this->db->query($sql,array($descColegio));
        return $result->row()->cant;
    }
    
    function getDescripcionByEvento($idevento){
        $sql ="SELECT id_evento,
                      desc_evento,
                      CASE WHEN tipo_evento = ".TIPO_EVENTO_EVALUACION_VERANO."
                           THEN NULL
                           ELSE fecha_realizar END as fecha_realizar,
                      CASE WHEN tipo_evento = ".TIPO_EVENTO_EVALUACION_VERANO."
                           THEN NULL
                           ELSE hora_inicio END as hora_inicio,
                      observacion,
                      tipo_evento
                 FROM admision.evento
                WHERE id_evento = ?";
        $result = $this->db->query($sql,array($idevento));
        return $result->row_array();
    }
    
    function getParientesFromMatricula($codfamilia){
        $sql = "SELECT parentesco,
				       ape_paterno apellidoPaterno,
				       ape_materno apellidoMaterno,
				       nombres nombre,
				       tipo_doc_identidad tipoDocumento,
				       nro_doc_identidad nroDocumento,
				       sexo,
				       ubigeo_hogar,
				       direccion_hogar referencia,
				       telf_fijo telfijo,
				       telf_celular celular,
	                   CASE WHEN email1 IS NOT NULL AND email1 <> '' THEN email1
        	                WHEN email2 IS NOT NULL AND email2 <> '' THEN email2
	                        ELSE NULL END AS correo,
	                   f.id_familiar
	              FROM familiar f,
	                   sima.familiar_x_familia fxf
	             WHERE f.id_familiar = fxf.id_familiar
	               AND fxf.cod_familiar = ?";
        $result = $this->db->query($sql,array($codfamilia));
        return $result->result();
    }
    
    function agendarFamEvento($data){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        $idFamilia = array();
        try{
            $this->db->trans_begin();
            foreach ($data as $dat){
                $this->db->insert('admision.agendar', $dat);
                if($this->db->affected_rows() != 1) {
                    throw new Exception('(MA-001)');
                }
            }
            $this->db->trans_commit();
            $rpt['error']  = EXIT_SUCCESS;
            $rpt['msj']    = MSJ_INSERT_SUCCESS;
        }catch(Exception $e){
            $this->db->trans_rollback();
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
}