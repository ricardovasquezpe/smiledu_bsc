<?php
//LAST CODE 001
class M_detalle_evaluacion extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getDiagnosticosByEstudiante($idestudiante, $idevento){
        $sql = "SELECT id_diagnostico,
                       ce.id_config_eval,
            	       diagnostico_final,
            	       taller_verano,
            	       obser_diagnostico,
                       tipo_diagnostico,
                       nullornotnull((SELECT CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona)
                                        FROM persona p
                                       WHERE p.nid_persona = id_evaluador)) nombre_evaluador,
                       nullornotnull((SELECT CONCAT(INITCAP(SPLIT_PART(p.nom_persona, ' ', 1)),' ', p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' )
                                        FROM persona p
                                       WHERE p.nid_persona = id_evaluador)) nombre_evaluador_abreviado,
                       fecha_registro,
                       INITCAP(ce.descripcion) AS descripcion,
                       CASE WHEN id_diagnostico IS NOT NULL THEN (SELECT string_agg(de.ruta, ',')
                                                                    FROM admision.documento_evaluacion de
                                                                   WHERE de.id_diagnostico = d.id_diagnostico) 
                            ELSE null END AS documentos,
                       CASE WHEN titulo_observacion IS NOT NULL THEN INITCAP(titulo_observacion) 
                            ELSE '--Falta configurar--' END AS titulo_observacion, 
                       opciones_eval,
                       (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                                    WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                    ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = id_evaluador) foto_persona,
                       id_evaluador
            	  FROM admision.config_eval ce LEFT JOIN admision.diagnostico d
                       ON (ce.id_config_eval = d.id_config_eval AND id_estudiante = ? AND id_evento = ? AND d.tipo_diagnostico = ".DIAGNOSTICO_CURSO.")
            	 WHERE ce._id_grado = (SELECT grado_ingreso
                                         FROM admision.contacto c
                                        WHERE c.id_contacto = ?)
                   AND ce.flg_activo = ".FLG_ACTIVO;
        $result = $this->db->query($sql,array($idestudiante, $idevento, $idestudiante));
        return $result->result();
    }
    
    function updateDiagnosticoEstudiante($arrayUpdate, $idDiagnostico){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_diagnostico", $idDiagnostico);
            $this->db->update("admision.diagnostico", $arrayUpdate);
        
            if($this->db->affected_rows() != 1){
                throw new Exception('(MDA-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function insertDiagnosticoEstudiante($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.diagnostico", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDA-001)');
            }
            $rpt['diag'] = _simple_encrypt($this->db->insert_id());
            $rpt['error']      = EXIT_SUCCESS;
            $rpt['msj']        = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getDocumentosByTipo($idDiagnostico){
        $sql = "SELECT string_agg(de.ruta, ',') as rutas
                  FROM admision.documento_evaluacion de
                 WHERE de.id_diagnostico = ?";
        $result = $this->db->query($sql,array($idDiagnostico));
        return $result->row()->rutas;
    }
    
    function insertDocumentoPostulante($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.documento_evaluacion", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDA-003)');
            }
            $rpt['error']      = EXIT_SUCCESS;
            $rpt['msj']        = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function editarDiagnosticoEstudiante($arrayUpdate, $idDiagnostico){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_diagnostico", $idDiagnostico);
            $this->db->update("admision.diagnostico", $arrayUpdate);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDA-004)');
            }
            $rpt['error']      = EXIT_SUCCESS;
            $rpt['msj']        = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getDiagnosticoSubidrector($idestudiante, $idevento){
        $sql = "SELECT id_diagnostico,
            	       diagnostico_final,
            	       taller_verano,
            	       obser_diagnostico,
                       nullornotnull((SELECT CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona)
                                        FROM persona p
                                       WHERE p.nid_persona = id_evaluador)) nombre_evaluador,
                       fecha_registro,
                       CASE WHEN id_diagnostico IS NOT NULL THEN (SELECT STRING_AGG(de.ruta, ',')
                                                                    FROM admision.documento_evaluacion de
                                                                   WHERE de.id_diagnostico = d.id_diagnostico)
                            ELSE NULL END AS documentos,
                       (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                                    WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                    ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = id_evaluador) foto_persona,
                        id_evaluador
            	  FROM admision.diagnostico d
                 WHERE d.tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA."
                   AND id_estudiante = ?
                   AND id_evento     = ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($idestudiante, $idevento));
        return $result->row_array();
    }
    
    function getIDDiagnosticoEvaluacionEventoEstudiante($idestudiante, $idevento){
        $sql = "SELECT id_diagnostico
            	  FROM admision.diagnostico d
                 WHERE d.tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA."
                   AND id_estudiante = ?
                   AND id_evento     = ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($idestudiante, $idevento));
        return $result->row()->id_diagnostico;
    }
    
    function verifySameDiagnostico($idPostulante, $idDag, $idEvento){
        $sql = "SELECT COUNT(1) count
                  FROM admision.diagnostico
                 WHERE id_estudiante  = ?
                   AND id_config_eval = ?
                   AND id_evento      = ?";
        $result = $this->db->query($sql, array($idPostulante, $idDag, $idEvento));
        return $result->row()->count;
    }
    
    function verifySameEntrevista($idPostulante, $idEvento){
        $sql = "SELECT COUNT(1) count
                  FROM admision.diagnostico
                 WHERE id_estudiante    = ?
                   AND tipo_diagnostico = ".DIAGNOSTICO_ENTREVISTA."
                   AND id_evento        = ?";
        $result = $this->db->query($sql, array($idPostulante, $idEvento));
        return $result->row()->count;
    }
    
    function countDiagRealizados($idPostulante, $idEvento){
        $sql = "SELECT (
                      SELECT COUNT(1)
                        FROM admision.config_eval ce 
                       WHERE ce._id_grado = (SELECT grado_ingreso
			                                   FROM admision.contacto c
			                                  WHERE c.id_contacto = ?)
                                                AND ce.flg_activo = ".FLG_ACTIVO." ) as cant_cursos,
                    (SELECT COUNT(1)
                       FROM admision.diagnostico
                      WHERE id_estudiante    = ?
                        AND id_evento        = ?
                        AND tipo_diagnostico = ".DIAGNOSTICO_CURSO.") as cant_realiz";
        $result = $this->db->query($sql,array($idPostulante, $idPostulante, $idEvento));
        return $result->row_array();
    }
    
    function getNombreEva($idPersona) {
        $sql = "SELECT CONCAT(INITCAP(p.ape_pate_pers), ', ', INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 ))) nombre_evaluador
                  FROM persona p
                 WHERE p.nid_persona = ?";
        $result = $this->db->query($sql, array($idPersona));
        return $result->row()->nombre_evaluador;
    }

    function getNombreContactoAbrev($id){
        $sql="SELECT INITCAP(CONCAT(ape_paterno,' ',INITCAP(SPLIT_PART( nombres, ' ' , 1 )))) nombre
               FROM admision.contacto
              WHERE id_contacto = ?";
        $result = $this->db->query($sql, array($id));
        if($result->num_rows()){
            return $result->row()->nombre;
        } else {
            return null;
        }
    }
    
    function updateAgendados($arrayUpdate, $idContacto, $idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_contacto", $idContacto);
            $this->db->where("id_evento", $idEvento);
            $this->db->update("admision.agendar", $arrayUpdate);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDA-006)');
            }
            $rpt['error']      = EXIT_SUCCESS;
            $rpt['msj']        = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function validateContactoAgendado($idEvento, $idContacto){
        $sql = "SELECT COUNT(1) AS count
                  FROM admision.agendar c
                 WHERE id_contacto = ?
                   AND id_evento   = ?";
        $result = $this->db->query($sql, array($idContacto, $idEvento));
        return $result->row()->count;
    }
    
    function agendarContacto($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.agendar", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MER-001)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function fechaAgendado($idContacto, $idEvento){
        $sql = "SELECT fecha as fecha
                  FROM admision.agendar c
                 WHERE id_contacto = ?
                   AND id_evento   = ?";
        $result = $this->db->query($sql, array($idContacto, $idEvento));
        return $result->row()->fecha;
    }
}