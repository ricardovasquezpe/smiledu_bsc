<?php
//LAST CODE 001
class M_confirm_evento extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function updatePersonaRecurso($arrayUpdate, $idPersona, $idRecursoEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_persona", $idPersona);
            $this->db->where("id_recurso_x_evento", $idRecursoEvento);
            $this->db->update("admision.persona_x_recurso", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MCD-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function updateRecursoMaterial($arrayUpdate, $idRecursoEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_recurso_x_evento", $idRecursoEvento);
            $this->db->update("admision.recurso_x_evento", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(MCD-002)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
}