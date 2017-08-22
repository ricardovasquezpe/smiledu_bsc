<?php
class M_solicitud_grupo extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getSolicitudes() {
        $sql = " SELECT ga.__id_main,
                        ga.__id_main_solicitud,
                        CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                             WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                             ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona, 
                        INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,',',p.nom_persona)) as nombre_alumno,
                        CONCAT(m.nombre_grupo,'.',t.desc_taller) as nombre_grupo,
                        t.desc_taller,
                        ga.motivo_cambio,         
                        ga.audi_fec_modi as fecha
                   FROM grupo_x_alumno ga,
                        main            m,
                        taller          t,
                        persona	        p
                  WHERE t.id_taller    = m.__id_taller
                    AND m.nid_main     = ga.__id_main
                    AND ga.__id_alumno = p.nid_persona
                    AND ga.estado      = '".ESTADO_GRUPO_SOLICITADO."'             
                    AND ga.__id_main_solicitud IS NULL";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function rechazarSolicitud($idMain) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
            $this->db->where('__id_main_solicitud', $idMain);
            $this->db->update('grupo_x_alumno', array("__id_main_solicitud" => null));
            
            $this->db->where('__id_main', $idMain);
            $this->db->update('grupo_x_alumno', array("estado" => ESTADO_GRUPO_RECHAZADO));
            
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;   
    }
    
    function getLimiteGrupoByCantAlum($idMain) {
        $sql = "SELECT limite_alumno, 
                       (SELECT count(1) AS cant_alumnos 
                    	  FROM main           m,
                    	       grupo_x_alumno ga
                    	 WHERE ga.__id_main = m.nid_main
                    	   AND ga.__id_main = ?),
                    	  CASE WHEN limite_alumno = (SELECT count(1) AS cant_alumnos 
                    				                   FROM main           m,
                    				                        grupo_x_alumno ga
                    				                  WHERE ga.__id_main = m.nid_main
                    				                    AND ga.__id_main = ?) THEN null
                                                       ELSE 1 END AS comparacion 
                  FROM main           
                 WHERE nid_main = ?";
        $result = $this->db->query($sql, array($idMain, $idMain, $idMain));
        if($result->num_rows() == 1) {
            return $result->row_array();
        }
        return;
    }
    
    function aceptarSolicitud($idMain) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
            $this->db->where('__id_main', $idMain);
            $this->db->update('grupo_x_alumno', array("estado" => ESTADO_GRUPO_REGISTRADO));
            

            $this->db->where('__id_main_solicitud', $idMain);
            $this->db->update('grupo_x_alumno', array("estado" => ESTADO_GRUPO_ACEPTADO));
            
            $this->db->where('__id_main_solicitud', $idMain);
            $this->db->update('grupo_x_alumno', array("__id_main_solicitud" => null));
            
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-001)');
            }
            
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;   
    }
}