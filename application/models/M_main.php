<?php

class M_main extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getSistemasByRol($arrayRoles,$search = null){
	    $sql = "SELECT r.nid_sistema,
                       s.desc_sist,
                       s.url_sistema,
                       s.logo_sistema,
	                   s.logo_sistema_c,
	                   s.flg_realizado,
	                   s.orden
                  FROM rol_x_sistema r,
                       sistema       s
                 WHERE r.nid_rol     IN ?
	               AND LOWER(s.desc_sist) LIKE LOWER(?)
                   AND r.flg_acti    = '1'
	               AND r.nid_sistema <> ".ID_SISTEMA_MATENIMIENTO."
                   AND r.nid_sistema = s.nid_sistema
	            GROUP BY r.nid_sistema,
	                     s.desc_sist,
                         s.url_sistema,
                         s.logo_sistema,
	                     s.logo_sistema_c,
	                     s.orden,
	                     s.flg_realizado
                ORDER BY s.orden asc";
	    $result = $this->db->query($sql, array($arrayRoles,'%'.$search.'%'));
// 	    _logLastQuery();
	    return ($result->result());  
    }
    
    function getOneSitema($idSistema){
        $sql = "SELECT url_sistema
                  FROM sistema
                 WHERE nid_sistema = ?";
        $result = $this->db->query($sql,$idSistema);
        return ($result->row()->url_sistema);
    }
    
    function getDataUser($idUser){
        $sql = "SELECT CASE WHEN foto_persona IS NOT NULL THEN foto_persona
                            WHEN foto_persona IS NULL AND google_foto IS NOT NULL THEN google_foto ELSE NULL END,
                       concat(ape_pate_pers,' ',nom_persona) AS nombre
                  FROM persona
                 WHERE nid_persona = ?";
        $result = $this->db->query($sql,array($idUser));
        $data = array();
        $data = $result->row_array();
        return $data;
    }
    
    /*SISTEMAS DE ENCUESTA*/
    function validateEncuestaPersonaRol($idPersona, $idRol){
        $sql = "SELECT COUNT(1) as count
                  FROM persona_x_rol
                 WHERE nid_persona = ?
                   AND nid_rol     = ?
                   AND flg_encuesta IS NOT NULL";
        $result = $this->db->query($sql,array($idPersona, $idRol));
        return $result->row()->count;
    }
    
    function validateEncuestaByTiepoEncuesta($idTipoEncuesta){
        $sql = "SELECT COUNT(1) as count
                  FROM senc.encuesta
                 WHERE _id_tipo_encuesta = ?
                   AND flg_estado        = 'APERTURADA'";
        $result = $this->db->query($sql,array($idTipoEncuesta));
        return $result->row()->count;
    }
    
    function getCountHijosAntiguos($idfamiliar){
    	$sql = "SELECT (SELECT COUNT(*)
                          FROM public.persona_x_aula pea 
                         WHERE pea.__id_persona = da.nid_persona) cant
			      FROM sima.detalle_alumno da
	        INNER JOIN sima.familiar_x_familia fxf
			        ON da.cod_familia = fxf.cod_familiar
			     WHERE fxf.id_familiar = ?";
        $result = $this->db->query($sql,array($idfamiliar));
        return $result->row_array();
    }
    
    function getCountHijosDeben($codfamilia, $estado, $simbolo){
    	$sql = "SELECT COUNT(*) cant,
    			       (SELECT COUNT(*) cant
				         FROM sima.detalle_alumno da
				        WHERE da.cod_familia = ?) total,
    	               (SELECT COUNT(*)
    	                  FROM sima.confirmacion_datos
    	                 WHERE year_confirmacion = da.year_ingreso
    	                   AND id_estudiante IN (SELECT da1.nid_persona
    	                                           FROM sima.detalle_alumno da1
                                                  WHERE da1.cod_familia = da.cod_familia)) confirmacion
                  FROM persona p
            INNER JOIN sima.detalle_alumno da
                    ON p.nid_persona = da.nid_persona
            INNER JOIN pagos.movimiento m
                    ON p.nid_persona = m._id_persona
            INNER JOIN pagos.detalle_cronograma dc
                    ON dc.id_detalle_cronograma = m._id_detalle_cronograma
            INNER JOIN pagos.cronograma c
                    ON (c.id_cronograma = dc._id_cronograma AND
        		        c.year = da.year_ingreso)
                 WHERE da.cod_familia = ?
                   AND m.estado ".$simbolo." '".ESTADO_PAGADO."'
                   AND m._id_concepto = ".CONCEPTO_SERV_ESCOLAR."
                   AND (dc.flg_tipo = '1' OR dc.flg_tipo = '2')
                   AND da.estado IN ('".$estado."')
    	      GROUP BY da.year_ingreso, da.nid_persona";
    	$result = $this->db->query($sql,array($codfamilia,$codfamilia));
    	return $result->row_array();
    }
}