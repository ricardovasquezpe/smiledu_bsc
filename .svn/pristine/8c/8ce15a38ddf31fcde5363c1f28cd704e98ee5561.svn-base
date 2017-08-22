<?php
class M_utils_admision extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getYearContactos(){
         $sql = "SELECT EXTRACT(YEAR FROM fecha_registro) as year
                   FROM admision.contacto
               GROUP BY year
               ORDER BY year";
         $result = $this->db->query($sql);
         return $result->result();
     }
     
     function getSedesAdmisionByNivel($nivel){
         $sql="SELECT s.nid_sede,
                      INITCAP(s.desc_sede) AS desc_sede
                 FROM nivel_x_sede n
           INNER JOIN sede s
                   ON n.id_sede = s.nid_sede
                WHERE (CASE WHEN ? IS NOT NULL THEN n.id_nivel = ?
                            ELSE 1 = 1 END)
             GROUP BY nid_sede, desc_sede
             ORDER BY nid_sede";
         $result = $this->db->query($sql,array($nivel,$nivel));
         return $result->result();
     }
     
}