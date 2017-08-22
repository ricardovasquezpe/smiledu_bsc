<?php
//LAST CODE 001
class M_confirm_datos extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getNombrePadres($codGrupo){
        $sql="SELECT CONCAT(initcap(ape_paterno),', ',initcap(nombres)) as nombreabrev,
                     id_contacto
                FROM admision.contacto
               WHERE cod_grupo      = ?
                 AND flg_estudiante = ".FLG_FAMILIAR."
            ORDER BY flg_apoderado";
        $result = $this->db->query($sql, array($codGrupo));
        return $result->result();
    }
    
    function getDatosFamiliar($idContacto){
        $sql="SELECT id_contacto,
                     nombres,
                     ape_paterno,
                     ape_materno,
                     parentesco,
                     sexo,
                     telefono_celular,
                     correo,
                     telefono_fijo,
                     tipo_documento,
                     nro_documento,
                     ubigeo,
                     referencia_domicilio,
                     fecha_nacimiento
                FROM admision.contacto
               WHERE id_contacto = ?
               LIMIT 1";
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
                throw new Exception('(MCD-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getNombrePostulantes($codGrupo){
        $sql="SELECT CONCAT(initcap(ape_paterno),', ',initcap(nombres)) as nombreabrev,
                     id_contacto
                FROM admision.contacto
               WHERE cod_grupo      = ?
                 AND flg_estudiante = ".FLG_ESTUDIANTE;
        $result = $this->db->query($sql, array($codGrupo));
        return $result->result();
    }
    
    function getDatosPostulante($idContacto){
        $sql="SELECT id_contacto,
                     nombres,
                     ape_paterno,
                     ape_materno,
                     tipo_documento,
                     nro_documento,
                     sexo,
                     fecha_nacimiento,
                     nivel_ingreso,
                     grado_ingreso
                FROM admision.contacto
               WHERE id_contacto = ?
               LIMIT 1";
        $result = $this->db->query($sql, array($idContacto));
        return $result->row_array();
    }
    
    function buscarFamilia($busqueda){
        $sql = "SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                       cod_grupo
                 FROM admision.contacto
                WHERE UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))";
        $result = $this->db->query($sql, array('%'.$busqueda.'%'));
        return $result->result();
    }
    
    function getPreguntasByNivel($idNivel, $idContacto){
        if($idNivel == ID_SECUNDARIA){
            $idNivel = ID_PRIMARIA;
        }
        $sql = "SELECT id_pregunta,
                       tipo_pregunta,
                       alternativas,
                       descripcion,
                       flg_obligatorio,
                       categoria,
                       resp.data->>'valor' AS respuesta
                  FROM admision.preguntas preg LEFT JOIN (SELECT jsonb_array_elements((resp_ficha)::jsonb) AS data
                				            FROM admision.contacto 
                				           WHERE id_contacto = ?) resp
                		               ON resp.data->>'pregunta' = preg.id_pregunta::text
                 WHERE _id_nivel = ?
                ORDER BY orden, categoria";
        $result = $this->db->query($sql, array($idContacto, $idNivel));
        return $result->result();
    }
    
    function insertarContacto($data){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert('admision.contacto', $data);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MA-001)');
            }
            $rpt['idContacto'] = $this->db->insert_id();
            $rpt['error']  = EXIT_SUCCESS;
            $rpt['msj']    = MSJ_INSERT_SUCCESS;
        } catch(Exception $e){
            $this->db->trans_rollback();
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
}