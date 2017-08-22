<?php

class M_traslado extends CI_Model {
    function __construct(){
		parent::__construct();
	}
	
	function getAllTraslados() {
	    $sql = " SELECT p.nid_persona,
        	            CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombre_completo,
                        p.nro_documento,
	                    tr.tipo_traslado,
	                    tr.fecha_hora_traslado,
	                    tr.estado,
	                    tr.nombres_usuario_traslado,
	                    tr.motivo_traslado,
	                    tr.motivo_rechazo,
	                    tr.nombres_usuario_confirma,
	                    tr.fecha_hora_confirmacion,
	                    (SELECT CONCAT(s.abvr,' ',g.abvr,' ',n.abvr,' / ',a.desc_aula) AS sede_grado_nivel
                	       FROM aula           a,
                	            sede           s,
                	            nivel          n,
                	            grado          g
	                      WHERE tr.id_aula_origen  = a.nid_aula
                            AND a.nid_sede         = s.nid_sede
                            AND a.nid_nivel        = n.nid_nivel
                            AND a.nid_grado        = g.nid_grado) AS sede_grado_nivel_aula_origen,
	                   (SELECT CONCAT(s.abvr,' ',g.abvr,' ',n.abvr,' / ',a.desc_aula) AS sede_grado_nivel
                	       FROM aula           a,
                	            sede           s,
                	            nivel          n,
                	            grado          g
	                      WHERE tr.id_aula_destino  = a.nid_aula
                            AND a.nid_sede          = s.nid_sede
                            AND a.nid_nivel         = n.nid_nivel
                            AND a.nid_grado         = g.nid_grado) AS sede_grado_nivel_aula_destino
            	   FROM persona              p,
                        sima.traslado_alumno tr
                  WHERE p.nid_persona = tr.id_alumno
            	 ORDER BY fecha_hora_traslado DESC";
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	function getAllTrasladosByPersonaSede($idPersona, $idSede) {
	    $sql = " SELECT p.nid_persona,
        	            INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)) AS nombre_completo,
                        p.nro_documento,
	                    tr.id_traslado,
	    				INITCAP(tr.tipo_traslado) tipo_traslado,
	                    tr.fecha_hora_traslado,
	                    tr.estado,
	                    INITCAP(tr.nombres_usuario_traslado) AS nombres_usuario_traslado,
	                    tr.motivo_traslado,
	                    tr.motivo_rechazo,
	                    CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                         ELSE 'nouser.svg' 
	                    END AS foto_persona,
	                    INITCAP(tr.nombres_usuario_confirma) AS nombres_usuario_confirma,
	                    tr.fecha_hora_confirmacion,
	                    (SELECT CONCAT(s.abvr,' / ',g.abvr,' ',n.abvr,' / ',INITCAP(a.desc_aula)) AS sede_grado_nivel
                	       FROM aula           a,
                	            sede           s,
                	            nivel          n,
                	            grado          g
	                      WHERE tr.id_aula_origen  = a.nid_aula
                            AND a.nid_sede         = s.nid_sede
                            AND a.nid_nivel        = n.nid_nivel
                            AND a.nid_grado        = g.nid_grado) AS sede_grado_nivel_aula_origen,
	                   CASE WHEN tr.id_aula_destino IS NOT NULL THEN (SELECT CONCAT(s.abvr,' / ',g.abvr,' ',n.abvr,' / ',INITCAP(a.desc_aula)) AS sede_grado_nivel
                                                            	       FROM aula           a,
                                                            	            sede           s,
                                                            	            nivel          n,
                                                            	            grado          g
                                            	                      WHERE tr.id_aula_destino  = a.nid_aula
                                                                        AND a.nid_sede          = s.nid_sede
                                                                        AND a.nid_nivel         = n.nid_nivel
                                                                        AND a.nid_grado         = g.nid_grado)
	                        ELSE '-' END AS sede_grado_nivel_aula_destino
            	   FROM persona              p,
                        sima.traslado_alumno tr
                  WHERE p.nid_persona = tr.id_alumno
	                AND (tr.id_usuario_traslado = ? OR 
	                       (CASE WHEN ? IS NOT NULL THEN tr.id_sede_destino = ?
	                            ELSE 1 = 1 END) OR 
	                       (CASE WHEN ? IS NOT NULL THEN tr.id_sede_origen = ?
	                            ELSE 1 = 1 END))
            	 ORDER BY fecha_hora_traslado DESC";
	    $result = $this->db->query($sql, array($idPersona, $idSede, $idSede, $idSede, $idSede));
	    return $result->result();
	}
	
	function buscarAlumnoATrasladar($textBusqueda, $idSede) {
	    $sql = " SELECT p.nid_persona,
            	            CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombre_completo,
            	            a.nid_aula,
            	            a.desc_aula AS aula,
            	            CONCAT(s.abvr,' ',g.abvr,' ',n.abvr) AS sede_grado_nivel,
                            p.nro_documento,
	                        CASE WHEN tr.estado IS NOT NULL THEN tr.estado
	                             ELSE 'NINGUNA' END AS estado
                	   FROM persona_x_aula pa,
                	        aula           a,
                	        sede           s,
                	        nivel          n,
                	        grado          g,
	                        persona p LEFT JOIN sima.traslado_alumno tr
	                                  ON p.nid_persona = tr.id_alumno
                	  WHERE pa.flg_acti     = '1'
                	    AND a.nid_sede      = ?
                	    AND pa.__id_persona = p.nid_persona
                	    AND pa.__id_aula    = a.nid_aula
                        AND a.nid_sede      = s.nid_sede
                        AND a.nid_nivel     = n.nid_nivel
                        AND a.nid_grado     = g.nid_grado
	                    AND UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona)) LIKE UPPER(?)
                	 ORDER BY p.ape_pate_pers, p.ape_mate_pers, p.nom_persona ";
	    $result = $this->db->query($sql, array($idSede, '%'.$textBusqueda.'%'));
	    return $result->result();
	}
	
	function getDetalleAlumnoTrasladoIntersedes($idAlumno) {
	    $sql = "SELECT a.nid_grado,
                       a.nid_nivel 
                  FROM persona_x_aula pxa,
                       aula a
                 WHERE __id_persona = ?
                   AND __id_aula = nid_aula";
	    $result = $this->db->query($sql, array($idAlumno));
	    return $result->row_array();
	}
	
	function getDetalleAlumnoTrasladoIntrasede($idAlumno) {
	    $sql = "SELECT a.nid_grado,
                       a.nid_nivel,
	                   a.nid_aula
                  FROM persona_x_aula pxa,
                       aula a
                 WHERE __id_persona = ?
	               AND __id_aula = nid_aula";
	    $result = $this->db->query($sql, array($idAlumno));
	    return $result->row_array();
	}
	
	function insertSolicitudTraslado($data){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert('sima.traslado_alumno',$data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = 'Se ha enviado correctamente la solicitud de traslado';
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getCountSolicitudesAlumno($idAlumno){
        $sql = "SELECT COUNT(1) cant
                  FROM sima.traslado_alumno
                 WHERE id_alumno = ?
                   AND estado = 'SOLICITADA'";
        $result = $this->db->query($sql,array($idAlumno));
        return $result->row()->cant;
	}
	
	function getAllSolicitudes() {
	    $sql = "SELECT  CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombre_completo,
        	            tr.desc_origen AS origen,
	                    tr.desc_destino AS destino,
                        p.nro_documento,
	                    tr.tipo_traslado,
	                    tr.nombres_usuario_traslado,
	                    tr.fecha_hora_traslado,
	                    tr.nombres_usuario_confirma,
	                    tr.fecha_hora_confirmacion,
	                    tr.id_traslado,
                        tr.estado
            	   FROM persona              p,
	                    sima.traslado_alumno tr
            	  WHERE p.nid_persona   = tr.id_alumno
            	 ORDER BY tr.fecha_hora_traslado DESC";
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	function getDetalleTraslado($idTraslado){
	    $sql = "SELECT  tr.desc_origen AS origen,
	                    tr.desc_destino AS destino,
	                    tr.tipo_traslado,
	                    tr.nombres_usuario_traslado,
	                    tr.fecha_hora_traslado,
	                    tr.nombres_usuario_confirma,
	                    tr.fecha_hora_confirmacion,
	                    tr.nombres_usuario_confirma,
	                    tr.motivo_traslado,
	                    tr.motivo_rechazo,
                        tr.estado
            	   FROM sima.traslado_alumno tr
            	  WHERE tr.id_traslado = ?";
	    $result = $this->db->query($sql, array($idTraslado));
	    return $result->row_array();
	}
	
	function getSedeAlumno($idalumno){
	    $sql = "SELECT UPPER(s.desc_sede) desc_sede
	              FROM sede s,
	                   aula a,
	                   persona_x_aula pxa
	             WHERE pxa.__id_persona = ?
	               AND s.nid_sede      = a.nid_sede
	               AND pxa.__id_aula   = a.nid_aula   ";
	    $result = $this->db->query($sql, array($idalumno));
	    return $result->row()->desc_sede;
	}
	
	function updateTraslado($idTraslado, $arrayUpdate){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where("id_traslado", $idTraslado);
	        $this->db->update("sima.traslado_alumno", $arrayUpdate);
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MT-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	     
	    return $rpt;
	}
	
	function getEstructuraTraslado($idTraslado){
	    $sql = "SELECT a.nid_aula,
	                   s.nid_sede,
	                   n.nid_nivel,
	                   g.nid_grado,
	                   a.year
    	          FROM sima.traslado_alumno tr,
	                   aula           a,
    	               sede           s,
    	               nivel          n,
    	               grado          g
                 WHERE tr.id_traslado    = ?
	               AND tr.id_aula_origen = a.nid_aula
                   AND a.nid_sede        = s.nid_sede
                   AND a.nid_nivel       = n.nid_nivel
                   AND a.nid_grado       = g.nid_grado";
	    $result = $this->db->query($sql, array($idTraslado));
	    return $result->row_array();
	}
}