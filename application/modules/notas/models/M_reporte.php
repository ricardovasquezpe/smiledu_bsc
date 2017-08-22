<?php
class M_reporte extends  CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    function getPadres($idAlumno) {
        $sql = "SELECT INITCAP(CONCAT(f.ape_paterno, ' ', f.ape_materno, ', ',f.nombres)) as nombre_padre,
                       ff.cod_familiar,
                       CASE WHEN email2 IS NULL THEN email1
                            ELSE CONCAT(email1,',',email2) END AS email	 
                  FROM familiar 	           f, 
                       sima.familiar_x_familia ff 
                 WHERE f.id_familiar = ff.id_familiar
                   AND ff.cod_familiar IN(SELECT da.cod_familia 
                			    FROM sima.detalle_alumno da
                			   WHERE da.nid_persona = ? )
                ORDER BY f.ape_paterno asc";
        $result = $this->db->query($sql, array($idAlumno));
        return $result->result_array();
    }   
}