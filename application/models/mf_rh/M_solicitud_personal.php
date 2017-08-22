<?php
//LAST-CODE: MU-001
class M_solicitud_personal extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    // 1 = AREAS | 2 = PUESTOS
    function getPuestos(){
        $sql = "SELECT g.valor,
                       g.desc_combo
                  FROM combo_tipo g
                 WHERE g.grupo = 2
              ORDER BY desc_combo";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getAreasGenerales(){
        $sql = "SELECT a.id_area,
                       a.desc_area
                  FROM area a
                 WHERE a.flg_general = 1
              ORDER BY desc_area";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllVacantes($idUsuario){
        $filtroUsuario = ($idUsuario != null) ? "AND v.id_solicitante = ".$idUsuario : null;        
        $sql = "SELECT v.id_vacante,
                       INITCAP(c.desc_combo) puesto,
                       a.desc_area AS desc_general,
                       a1.desc_area AS desc_especifica,
                       s.desc_sede,
                       v.observacion,
                       v.nombres_solicitante,
                       to_char(v.fec_regi,'DD/MM/YYYY HH12:MI AM') fec_regi,
                       v.estado,
                       CASE WHEN v.estado = '".SOLICITUD_SOLICITADO."' THEN 'bg-warning' 
                                                             ELSE 
		                    CASE WHEN v.estado = '".SOLICITUD_CONTRATADO."' THEN 'bg-success' 
							                                      ELSE  
			                     CASE WHEN v.estado = '".SOLICITUD_PENDIENTE."' THEN 'bg-warning' 
								                                      ELSE ''
				                 END
		                    END 
		               END AS color,
			           CASE WHEN v.estado = '".SOLICITUD_SOLICITADO."' THEN 'add_alert' 
                                                             ELSE 
		                    CASE WHEN v.estado = '".SOLICITUD_CONTRATADO."' THEN 'account_box' 
							                                      ELSE
		                        CASE WHEN v.estado = '".SOLICITUD_ANULADO."' THEN 'remove_circle' 
							                                      ELSE 
			                     CASE WHEN v.estado = '".SOLICITUD_PENDIENTE."' THEN 'supervisor_account' 
								                                      ELSE ''
				                 END
			                   END
		                    END 
		               END AS icono,              
                       v.nombres_usua_atencion,
                       to_char(v.fec_fin,'DD/MM/YYYY HH12:MI AM') fec_fin
                  FROM vacante v,
                       sede s,
                       combo_tipo c,
                       area       a,
                       area	  a1
                 WHERE s.nid_sede       = v.id_sede
                   AND c.grupo          = ".GRUPO_PUESTOS."
                   AND c.valor          = v.id_puesto::CHARACTER VARYING
                   AND a.id_area        = v.id_area
                   AND a1.id_area       = v.id_area_especifica
                   ".$filtroUsuario."
              ORDER BY v.estado desc , v.fec_regi";
        $result = $this->db->query($sql);//log_message('error', $this->db->last_query());
        return $result->result();
    }
    
    function insertSolicitudesPersonal($arrayInsert){
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $cont = 0;
            $tamaño = count($arrayInsert);
            if($tamaño <= 0){
                throw new Exception('La cantidad debe ser menor que 0');
            }
            $this->db->insert_batch('vacante',$arrayInsert);
            $cont = $cont + $this->db->affected_rows();
            if($cont != $tamaño){
                $this->db->trans_rollback();
                throw new Exception('(MS-001)');
            }
            $data['msj']   = MSJ_INS;
            $data['error'] = EXIT_SUCCESS;
        }catch (Exception $e){
            $data['error'] = EXIT_ERROR;
            $data['msj']   = $e->getMessage();
			$this->db->trans_rollback();
        }
        return $data;
    }
    
    function actualizaEstadoSolicitud($idVacante,$arrayUpdate){
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $this->db->where('id_vacante', $idVacante);
            $this->db->update('vacante',$arrayUpdate);
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = CABE_UPT;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}