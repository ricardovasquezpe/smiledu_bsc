<?php
//LAST-CODE: MU-000
class M_persona_rol extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllPersonasByRol($idRol){
        $sql = "SELECT p.nid_persona,
                	   r.nid_rol,
    	               INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
    	               (SELECT string_agg(r2.desc_rol,',') FROM rol r2, persona_x_rol pr2 WHERE r2.nid_rol = pr2.nid_rol AND pr2.nid_persona = p.nid_persona AND pr2.flg_acti = '1') roles
                  FROM persona p,
                   rol r,
                	   persona_x_rol pr
                 WHERE p.nid_persona = pr.nid_persona
                   AND p.flg_acti  = '".FLG_ACTIVO."'
                   AND pr.flg_acti = '".FLG_ACTIVO."'
                   AND r.nid_rol   = pr.nid_rol
                   AND pr.nid_rol  = ?";
        
        $result = $this->db->query($sql,array($idRol));
        return $result->result();
    }
    
    function getPersonasRolAll($idPersona){
        $sql = "SELECT r.nid_rol,
                       r.desc_rol,
                       pr.nid_persona,
                       pr.flg_acti
                  FROM rol r LEFT JOIN persona_x_rol pr on (r.nid_rol = pr.nid_rol AND pr.nid_persona = ?)
              ORDER BY r.nid_rol ";
        $result = $this->db->query($sql,array($idPersona));
        return $result->result();
    }
    
    function updateInsertRolesPersona($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                if($dato['condicion'] == 0 ) {//insert
                    unset($dato['condicion']);
                    $this->db->insert('persona_x_rol', $dato);
                    $cont = $cont + $this->db->affected_rows();
                } else if($dato['condicion'] == 1){
                    unset($dato['condicion']);
                    $this->db->where('nid_persona', $dato['nid_persona']);
                    $this->db->where('nid_rol', $dato['nid_rol']);
                    unset($dato['nid_persona']);
                    unset($dato['nid_rol']);
                    $this->db->update('persona_x_rol', $dato);
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
                $data['msj']       = MSJ_INS;
                $data['cabecera']  = CABE_INS;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
    }
    
    function evaluaInsertUpdate($idPersona, $idRol){
        $sql = "SELECT COUNT(nid_persona) as cuenta
                  FROM persona_x_rol 
                 WHERE nid_persona = ? 
                   AND nid_rol = ?";
        $result = $this->db->query($sql, array($idPersona, $idRol));
        $data = array();
        $data = $result->row();
        return $data->cuenta;
    }
}