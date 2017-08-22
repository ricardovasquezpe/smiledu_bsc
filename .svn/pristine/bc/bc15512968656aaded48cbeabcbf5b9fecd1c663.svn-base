<?php
class m_utils extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAulasByGrado($idSede, $idNivel, $idGrado){
        $sql = "SELECT nid_aula,
                       a.desc_aula 
                  FROM aula a
                 WHERE a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_sede  = ?
                   AND a.nid_nivel = ?
                   AND a.nid_grado = ? 
                   ORDER BY a.desc_aula";
        $result = $this->db->query($sql,array($idSede, $idNivel,$idGrado));
        
        return $result->result();
    }
    
    function getSedes(){
        $sql = "SELECT nid_sede,
                       desc_sede
                  FROM sede 
                ORDER BY desc_sede ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getNivelesBySede($idSede){
       $sql = "SELECT a.nid_nivel,
                      UPPER(n.desc_nivel) desc_nivel
                 FROM aula a,
                      nivel n
                WHERE flg_acti    = ".FLG_ACTIVO." 
                  AND nid_sede    = ?
                  AND a.nid_nivel = n.nid_nivel
             GROUP BY a.nid_nivel,
                      n.nid_nivel,
                      desc_nivel
             ORDER BY a.nid_nivel";
        $result = $this->db->query($sql, array($idSede));
        return $result->result();
     }
     
     function getGradosByNivel($idNivel, $idSede) {
         $sql = "SELECT a.nid_grado,
                       CONCAT(g.abvr,' ',n.abvr) desc_grado
                  FROM grado g,
                       nivel n,
                       aula  a
                 WHERE g.id_nivel = ?
                   AND n.nid_nivel = g.id_nivel
                   AND a.nid_grado = g.nid_grado
                   AND a.nid_nivel = g.id_nivel
                   AND a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_sede  = ?
                GROUP BY a.nid_grado,CONCAT(g.abvr,' ',n.abvr)
                ORDER BY a.nid_grado";
         $result = $this->db->query($sql, array($idNivel, $idSede));
         return $result->result();
     }
     
     function getDisciplinas(){
         $sql = "SELECT d.id_disciplina,
                        UPPER(d.desc_disciplina) desc_disciplina
                  FROM disciplina d
                ORDER BY d.id_disciplina";
         $result = $this->db->query($sql);
         return $result->result();
     }
     
     function getNivelesEducativos(){
         $sql = "SELECT p.nid_nivel,
	    			    p.desc_nivel
				  FROM nivel p";
         $result = $this->db->query($sql);
         return $result->result();
     }
     
     function getAreasAcad(){
         $sql = "SELECT nid_area_academica,
                       desc_area_academica
                  FROM area_acad 
                  ORDER BY desc_area_academica ASC";
         $result = $this->db->query($sql);
         return $result->result();
     }
     
     function getGradosBySede($idSede){
         $sql = "SELECT a.nid_grado,
                       CONCAT(g.abvr,' ','SEC') desc_grado
                  FROM aula a,
                       grado g
                 WHERE a.nid_sede = ?
                   AND a.flg_acti = 1
                   AND a.nid_grado IN (13, 14, 15)
                   AND a.nid_grado = g.nid_grado
                 GROUP BY a.nid_grado, g.abvr
                 ORDER BY a.nid_grado ";
         $result = $this->db->query($sql, array($idSede));
         return $result->result();
     }
     
     function getOneSitema($idSistema){
         $sql = "SELECT url_sistema
                   FROM sistema
                  WHERE nid_sistema = ? LIMIT 1";
         $result = $this->db->query($sql, $idSistema);
         if($result->num_rows() == 1){  
            return ($result->row()->url_sistema);            
         }else {
            return null;
         }
     }
     
     function getCategoriaByObjetivo($idObjetivo){
         $sql = "SELECT c.id_categoria,
                        c.desc_categoria
                 FROM bsc.categoria c
                 WHERE c.__id_objetivo = ?";
         
         $result = $this->db->query($sql, array($idObjetivo));
         return $result->result();
     }
     
     function getIndicadoresByCategoria($idCategoria){
         $sql = "SELECT i._id_indicador,
                        i.desc_indicador
                 FROM bsc.indicador i,
                      bsc.categoria_x_indicador ci
                 WHERE ci.__id_indicador = i._id_indicador
                  AND ci.__id_categoria  = ?";
          
         $result = $this->db->query($sql, array($idCategoria));
         return $result->result();
     }
     
     function getAreasEspecificas($idAreaGeneral){
         $sql = "SELECT id_area,
                        desc_area
                 FROM   area
                 WHERE  flg_general = 0
                 AND    id_area_general = ?";
         
         $result = $this->db->query($sql, array($idAreaGeneral));
         return $result->result();
     }
}