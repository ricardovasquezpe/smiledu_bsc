<?php
class M_alumno_eai extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllAlumnosEai($idAula){
        $sql = " SELECT __id_persona, __id_aula, year_academico, p.nid_persona,
                        INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) AS nombrecompleto,
                        medida_rash_eai_mate,
                        medida_rash_eai_comu,
                        medida_rash_eai_ciencia,
                        medida_rash_eai_infor
                   FROM persona        p,
                        aula           a,
                        persona_x_aula pa
                  WHERE pa.flg_acti     = '".FLG_ACTIVO."'
                    AND year_academico  = "._YEAR_."
                    AND pa.__id_aula    = ?
                    AND pa.__id_persona = p.nid_persona
                    AND pa.__id_aula    = a.nid_aula
                    ORDER BY p.ape_pate_pers ASC";
        $result = $this->db->query($sql, array($idAula));
        return $result->result();
    }
    
    function editCamposEai($arrayDatos){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try{                       
                $this->db->where('__id_aula', $arrayDatos['__id_aula']);
                $this->db->where('__id_persona', $arrayDatos['__id_persona']);
                $this->db->where('year_academico', $arrayDatos['year_academico']);
                unset($arrayDatos['__id_aula']);
                unset($arrayDatos['__id_persona']);
                unset($arrayDatos['year_academico']);
                
                $this->db->update('persona_x_aula', $arrayDatos);
            
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDI-001)');
            }
            $rpta['error'] = EXIT_SUCCESS;
            $rpta['msj']   = CABE_UPT;
        }catch(Exception $e){
            $rpta['msj']   = $e->getMessage();
        }
        return $rpta;
    }
}