<?php
//LAST-CODE: MU-000
class M_ece_alumnos extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    //Buscar datos de la hoja de excel
    function getNidAulaByLetra($data_value, $idSede, $idGrado, $year) {
        $sql ="SELECT nid_aula 
                 FROM aula 
                WHERE year         = ?
                  AND nid_sede     = ? 
                  AND nid_grado    = ?
                  AND nombre_letra = ? LIMIT 1";
        $result = $this->db->query($sql, array($year, $idSede, $idGrado, $data_value));
        if($result->num_rows() == 1) {
            return $result->row()->nid_aula;
        }
        return null;
    }
    
    function getAlumnosByAula($data_value, $idAula){       
        $sql = "SELECT p.nid_persona
                  FROM persona_x_aula pa,
                       persona p
                 WHERE pa.__id_aula  = ? 
                   AND p.nid_persona = pa.__id_persona
                   AND UNACCENT(UPPER(CONCAT(TRIM(p.ape_pate_pers), ' ',TRIM(p.ape_mate_pers), ' ',TRIM(SPLIT_PART(p.nom_persona, ' ', 1))))) = UNACCENT(UPPER(?)) ";
        $result = $this->db->query($sql, array($idAula, $data_value));
        if($result->num_rows() == 1) {
            return $result->row()->nid_persona;
        }
        return null;
    }
   
  function updateCargaExcel($arrayDatos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                    $this->db->where('__id_aula'     , $dato['__id_aula']);
                    $this->db->where('__id_persona'  , $dato['__id_persona']);
                    $this->db->where('year_academico', $dato['year_academico']);   
                    unset($dato['__id_aula']);
                    unset($dato['__id_persona']);
                    unset($dato['year_academico']);
                    $this->db->update('persona_x_aula', $dato);
                    $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_INS;
            $data['cabecera'] = CABE_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getAllAlumnosEceExcel($idSede, $idGrado, $year) {
        $sql = "SELECT a.nombre_letra,
                       INITCAP(UPPER(CONCAT( p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona))) AS nom_persona,
                       pa.nivel_logro_lectora,
                       pa.medida_rash_lectura,
                       pa.nivel_logro_matematica,
                       pa.medida_rash_matematica
                  FROM persona        p,
                       aula           a,
                       grado          g,
                       sede           s,
                       persona_x_aula pa
                 WHERE /*pa.flg_acti  = '".FLG_ACTIVO."'          
                   AND*/ pa.__id_persona = p.nid_persona
                   AND pa.__id_aula = a.nid_aula
                   AND a.nid_grado  = ?
                   AND a.nid_sede   = ?
                   AND a.year       = ?
              GROUP BY nombre_letra,p.ape_pate_pers,p.ape_mate_pers,p.nom_persona,pa.nivel_logro_lectora,pa.medida_rash_lectura,
                       pa.nivel_logro_matematica,pa.medida_rash_matematica 
              ORDER BY nombre_letra ASC";
        $result = $this->db->query($sql, array($idGrado, $idSede, $year));
        return $result->result();
    }
    
    function getAllAulasEcE($idSede, $idGrado, $year){
        $sql = "SELECT  a.nid_aula,
                    	a.desc_aula,
                    	a.nombre_letra
                  FROM  persona_x_aula pa,
                        aula a
                WHERE nid_sede    = ?
                  AND a.nid_grado = ?
                  AND a.year      = ?
                  AND pa.__id_aula = a.nid_aula
             GROUP BY a.nid_aula,a.desc_aula,a.nombre_letra
             ORDER BY a.desc_aula ASC";
        $result = $this->db->query($sql, array($idSede, $idGrado, $year));
        return $result->result();
    }
    
    function UpdateLetras($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                $this->db->where('nid_aula'       , $dato['nid_aula']);
                unset($dato['nid_aula']);
                $this->db->update('aula', $dato);
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MA-001)';
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