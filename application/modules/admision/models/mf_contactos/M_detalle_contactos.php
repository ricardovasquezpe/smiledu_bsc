<?php
//LAST CODE 001
class M_detalle_contactos extends  CI_Model{
    function __construct(){
        parent::__construct();
    }

    function updateCampoDetalleContacto($arrayUpdate, $idContacto){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_contacto", $idContacto);
            $this->db->update("admision.contacto", $arrayUpdate);
    
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getFamiliaresByPostulante($idPostulante){
        $sql="SELECT CONCAT(ape_paterno,' ',ape_materno,', ',nombres) as nombrecompleto,
                     id_contacto,
                     nullornotnull(correo) as correo,
                     nullornotnull(telefono_celular) as telefono_celular,
                     tipo_documento,
                     parentesco,
                     nullornotnull(nro_documento) as nro_documento
                FROM admision.contacto
               WHERE cod_grupo = (SELECT cod_grupo
                                    FROM admision.contacto
                                   WHERE id_contacto = ?)
                 AND flg_estudiante = ".FLG_FAMILIAR."
            ORDER BY nombrecompleto";
        $result = $this->db->query($sql, array($idPostulante));
        return $result->result();
    }
    
    function getDetalleContacto($idContacto){
        $sql="SELECT nombres,
                     ape_paterno,
                     ape_materno,
                     id_contacto,
                     correo,
                     telefono_celular,
                     parentesco,
                     cod_grupo,
                     sexo,
                     ubigeo,
                     telefono_fijo,
                     flg_apoderado,
                     referencia_domicilio,
                     tipo_documento,
                     nro_documento,
                     operador_telefonico,
                     canal_comunicacion,
                     colegio_procedencia
                FROM admision.contacto
               WHERE id_contacto = ?";
        $result = $this->db->query($sql, array($idContacto));
        return $result->row_array();
    }
    
    function updateContacto($arrayUpdate, $idContacto){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_contacto", $idContacto);
            $this->db->update("admision.contacto", $arrayUpdate);
        
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getHistorialPostulante($idContacto){
        $sql = "SELECT e.id_evento,
                       e.desc_evento,
            	       e.fecha_realizar,
            	       i.asistencia,
                       i.opcion,
                       (SELECT INITCAP(desc_combo) as desc
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_EVENTO."
                           AND valor = e.tipo_evento::CHARACTER VARYING) AS tipo_evento
            	  FROM admision.invitados i
             LEFT JOIN admision.evento e
                    ON i.id_evento = e.id_evento
                 WHERE i.id_contacto = ?";
        $result = $this->db->query($sql,array($idContacto));
        return $result->result();
    }
    
    function insertContacto($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.contacto", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-001)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function deleteContacto($idContacto){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_contacto', $idContacto);
            $this->db->delete('admision.contacto');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDE-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function countEventosByIdContacto($idContacto){
        $sql = "SELECT COUNT(*) count
			       FROM admision.invitados i,
                        admision.evento e
			      WHERE i.id_evento = e.id_evento
                    AND i.id_contacto IN (SELECT id_contacto
			                                FROM admision.contacto
			                               WHERE id_contacto = ?)";
        $result = $this->db->query($sql,array($idContacto));
        return $result->row()->count;
    }
    
    function getRazonInasistencia($idevento,$idcontacto){
        $sql = "SELECT razon_inasistencia
                  FROM admision.invitados
                 WHERE id_evento = ?
                   AND id_contacto = ?";
        $result = $this->db->query($sql,array($idevento,$idcontacto));
        return $result->row()->razon_inasistencia;
    }
    
    function getFlgCampoObligatoriosFamilia($idcontacto){
        $sql = "SELECT flg_campos_obligatorios
			      FROM admision.contacto 
			     WHERE cod_grupo IN (SELECT cod_grupo 
					                   FROM admision.contacto 
					                  WHERE id_contacto = ?)
              GROUP BY flg_campos_obligatorios";
        $result = $this->db->query($sql,array($idcontacto));
        return $result->result();
    }
    
  
    
    
    
}