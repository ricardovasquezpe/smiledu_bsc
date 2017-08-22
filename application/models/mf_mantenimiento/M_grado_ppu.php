<?php
class M_grado_ppu extends CI_Model{
    function __construct(){
        parent::__construct();
    }
  
    function getAllGrados_PPU($idNivel,$idSede,$idPPu){
        $sql = "SELECT g.nid_grado,
                   gppu.puesto,
                   CONCAT(g.abvr,' ',n.abvr) desc_grado
                 FROM nivel n, 
                       aula  a,
                      grado g
                  LEFT JOIN (SELECT gp.puesto,
                			        gp.__id_sede,
                			        gp.__id_grado 
                		       FROM grado_ppu gp, 
                			        ppu        p
                        	  WHERE gp.__id_ppu = p.id_ppu
                        	    AND p.id_ppu          = ?
                        		AND gp.year_academico = (SELECT EXTRACT(YEAR FROM now())) ) gppu ON (g.nid_grado = gppu.__id_grado AND gppu.__id_sede = ?)
                      WHERE g.id_nivel = ?
                        AND a.flg_acti  = '".FLG_ACTIVO."'
                        AND a.nid_sede  = ?
                        AND g.nid_grado IN ?
                        AND n.nid_nivel = g.id_nivel
                        AND a.nid_grado = g.nid_grado
                        AND a.nid_nivel = g.id_nivel
                   GROUP BY g.nid_grado,CONCAT(g.abvr,' ',n.abvr),gppu.puesto
                   ORDER BY g.nid_grado";
        $gradosPPU = json_decode(GRADOS_PPU);
        $result = $this->db->query($sql, array($idPPu, $idSede, $idNivel, $idSede, $gradosPPU));
        return $result->result();
    }
    
    function getAulaAlumnoAll($idAula, $idPPU){
        $sql = "SELECT pa.__id_persona,
                       CASE WHEN ? = 1 THEN ppu_puesto_numerico
                            WHEN ? = 2 THEN ppu_puesto_ciencia
                            WHEN ? = 3 THEN ppu_puesto_lectura ELSE NULL END AS puesto_alumno,
                       CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ',' ,p.nom_persona) AS nombrecompleto 
                 FROM persona p,
		              aula a, 
                      persona_x_aula pa 
                 WHERE pa.flg_acti     = '".FLG_ACTIVO."'
                   AND pa.__id_aula    = ?
                   AND pa.__id_persona = p.nid_persona
                   AND pa.__id_aula    = a.nid_aula
              ORDER BY p.nom_persona ASC";
        $result = $this->db->query($sql, array($idPPU, $idPPU, $idPPU, $idAula));
        //log_message('error', print_r($result));
        return $result->result();
    }

    function getCantPuestoSedeAreaGrado($idSede, $idGrado, $idPPu){
        $sql ="SELECT COUNT(1) cant
                 FROM grado_ppu
                WHERE __id_sede      = ?
                  AND __id_grado     = ?
                  AND year_academico = (SELECT EXTRACT(YEAR FROM now()))
                  AND __id_ppu       = ? LIMIT 1";
        $result = $this->db->query($sql, array($idSede, $idGrado, $idPPu));
        return ($result->row()->cant);
    }
    
    function getPuestoSedeAreaGrado($idSede, $idGrado, $idPPu){
        $sql ="SELECT puesto
                 FROM grado_ppu
                WHERE __id_sede      = ?
                  AND __id_grado     = ?
                  AND year_academico = (SELECT EXTRACT(YEAR FROM now()))
                  AND __id_ppu       = ? LIMIT 1";
        $result = $this->db->query($sql, array($idSede, $idGrado, $idPPu));
        if($result->num_rows() == 1) {
            return ($result->row()->puesto);
        } else {
            return null;
        }
    }

    function InsertUpdateGradoPPU($arrayDatos, $idSede, $idPPu, $puestoSede){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                if($dato['ACCION'] == 'I' ) {//insert
                    unset($dato['ACCION']);
                    $this->db->insert('grado_ppu', $dato);
                    $cont = $cont + $this->db->affected_rows();
                } else if($dato['ACCION'] == 'U'){
                    unset($dato['ACCION']);
                    $this->db->where('__id_sede' , $dato['__id_sede']);
                    $this->db->where('__id_grado', $dato['__id_grado']);
                    $this->db->where('__id_ppu'  , $dato['__id_ppu']);
                    $this->db->where('year_academico'  , date('Y'));
                    
                    unset($dato['__id_grado']);
                    unset($dato['__id_sede']);
                    unset($dato['__id_ppu']);
                    $this->db->update('grado_ppu', $dato);
                    $cont = $cont + $this->db->affected_rows();
                }
                else if($dato['ACCION'] == 'D'){
                    unset($dato['ACCION']);
                    $this->db->where('__id_sede' , $dato['__id_sede']);
                    $this->db->where('__id_grado', $dato['__id_grado']);
                    $this->db->where('__id_ppu'  , $dato['__id_ppu']);
                    $this->db->where('year_academico'  , date('Y'));
                
                    $this->db->delete('grado_ppu', $dato);
                    $cont = $cont + $this->db->affected_rows();
                }
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            $cantPPU = $this->getCantPuestoSedeAreaGrado($idSede, 0, $idPPu);
            if($cantPPU == 0) { //Insertar Puesto de la Sede
                $arrayDatos = array("__id_grado"     => 0,
                                    "__id_sede"      => $idSede,
                                    "__id_ppu"       => $idPPu,
                                    "puesto"         => $puestoSede,
                                    "year_academico" => date('Y'));
                $this->db->insert('grado_ppu', $arrayDatos);
            } else {// Actualizar el puesto de la sede
                $arryUpt = array("puesto" => $puestoSede);
                $this->db->where('__id_sede'      , $idSede);
                $this->db->where('__id_grado'     , 0);
                $this->db->where('__id_ppu'       , $idPPu);
                $this->db->where('year_academico' , date('Y'));

                $this->db->update('grado_ppu', $arryUpt);
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MA-001)';
                $this->db->trans_rollback();
            }else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = CABE_UPT;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function updatedeleteaulaalum($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
               if($dato['ACCION'] == 'U'){
                    unset($dato['ACCION']);
                    $this->db->where('__id_persona', $dato['__id_persona']);
                    $this->db->where('__id_aula', $dato['__id_aula']);
   
                    unset($dato['__id_persona']);
                    unset($dato['__id_aula']);
                    $this->db->update('persona_x_aula', $dato);
                    $cont = $cont + $this->db->affected_rows();
                }
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
    
    function updateInsertPuestoPpuSede($idSede, $idPPu,$puesto){
        try{
            $cantPPU = $this->getCantPuestoSedeAreaGrado($idSede, 0, $idPPu);
            if($cantPPU == 0) { //Insertar Puesto de la Sede
                $arrayDatos = array("__id_grado"     => 0,
                                    "__id_sede"      => $idSede,
                                    "__id_ppu"       => $idPPu,
                                    "puesto"         => $puesto,
                                    "year_academico" => date('Y'));
                $this->db->insert('grado_ppu', $arrayDatos);
            } else {// Actualizar el puesto de la sede
                $arryUpt = array("puesto" => $puesto);
                $this->db->where('__id_sede'      , $idSede);
                $this->db->where('__id_grado'     , 0);
                $this->db->where('__id_ppu'       , $idPPu);
                $this->db->where('year_academico' , date('Y'));
            
                $this->db->update('grado_ppu', $arryUpt);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
    }
    
}