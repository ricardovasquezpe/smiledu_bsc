<?php
//LAST-CODE: MU-002

class M_cert_ing_doc extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllDocentes() {
        $sql = "SELECT p.nid_persona,
                       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) AS nombrecompleto,
                       p.nro_documento,
                       p.flg_certi_efce,
                       p.flg_ingles_nativo
                  FROM persona p, 
                       persona_x_rol pr
                 WHERE pr.nid_rol     = ".ID_ROL_DOCENTE."
                   AND pr.flg_acti    = '".FLG_ACTIVO."'
                   AND pr.nid_persona = p.nid_persona 
              ORDER BY p.ape_pate_pers ASC ";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllDocentesCombo() {
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) as nombrecompleto
                  FROM persona p,
                       persona_x_rol pr
                 WHERE pr.nid_rol     = ".ID_ROL_DOCENTE."
                   AND pr.flg_acti    = '".FLG_ACTIVO."'
                   AND pr.nid_persona = p.nid_persona
              ORDER BY p.ape_pate_pers ASC ";
        $result = $this->db->query($sql);//_logLastQuery('q:');
        return $result->result();
    }
    
    function updateCertificacionDoc($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            $this->db->update_batch('persona',$arrayDatos, 'nid_persona');
            $cont = $cont + $this->db->affected_rows();
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MS-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MS-001)';
                $this->db->trans_rollback();
            }else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = MSJ_INS;
                $data['cabecera']  = CABE_INS;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
}