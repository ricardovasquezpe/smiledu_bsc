<?php
class M_utils_notas extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getCompetencias($idGrado, $idCurso, $idYear) {
        $sql = "SELECT mc._id_competencia,
                        LOWER(c.desc_competencia) AS desc_competencia
                   FROM notas.matriz_x_competencia mc,
                        notas.competencia          c
                  WHERE mc._id_grado      = ?
                    AND mc._id_curso      = ?
                    AND mc._year_acad     = ?
                    AND c.id_competencia = mc._id_competencia";
        $result = $this->db->query($sql, array($idGrado, $idCurso, $idYear) );
        return $result->result_array();
    }
     
    function getCapacidades($idCompetencia, $idGrado, $idCurso, $idYear) {
        $sql = "SELECT mc._id_capacidad,
                        LOWER(c.desc_capacidad) AS desc_capacidad
                   FROM notas.matriz_x_capacidad mc,
                        notas.capacidad          c
                  WHERE mc._id_grado       = ?
                    AND mc._id_curso       = ?
                    AND mc._year_acad      = ?
                    AND mc._id_competencia = ?
                    AND c.id_capacidad     = mc._id_capacidad";
        $result = $this->db->query($sql, array($idGrado, $idCurso, $idYear, $idCompetencia) );
        return $result->result_array();
    }
    
    function getAreasAcademicas() {
        $sql = "SELECT id_area,
                       desc_area
                  FROM area
                 WHERE id_area IN (".ID_AREA_INGLES.",
                                   ".ID_AREA_TALLER_ARTISTICO.",
                                   ".ID_AREA_TALLER_DEPORTIVO.",
                                   ".ID_AREA_MATEMATICA.",
                                   ".ID_AREA_COMUNICACION.",
                                   ".ID_AREA_CIENCIA.",
                                   ".ID_AREA_INFORMATICA.",
                                   ".ID_AREA_SOCIALES.",
                                   ".ID_AREA_INICIAL.")
               ORDER BY desc_area ASC";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
     
    function getIndicadores($idCompetencia, $idCapacidad, $idGrado, $idCurso, $idYear) {
        $sql = "SELECT mi._id_indicador,
                        LOWER(i.desc_indicador) AS desc_indicador
                   FROM notas.matriz_x_indicador mi,
                        notas.indicador          i
                  WHERE mi._id_grado       = ?
                    AND mi._id_curso       = ?
                    AND mi._year_acad      = ?
                    AND mi._id_competencia = ?
                    AND mi._id_capacidad   = ?
                    AND i.id_indicador     = mi._id_indicador";
        $result = $this->db->query($sql, array($idGrado, $idCurso, $idYear, $idCompetencia, $idCapacidad) );
        return $result->result_array();
    }
     
    function getInstrumentosConceptos($idGrado, $idCurso, $idCompetencia, $idCapacidad, $idIndicador, $idMain) {
        $sql = "SELECT CONCAT(mi._id_instrumento,';',mi.correlativo) AS id,
                       CONCAT(mi.concepto_evaluar,' Instrumento: (',ins.nombre_instrumento,')') AS concepto_instru
                  FROM notas.matriz_x_instrumento mi,
                       instru.instrumento         ins
                 WHERE mi._id_grado        = ?
                   AND mi._id_curso        = ?
                   AND mi._year_acad       = "._YEAR_."
                   AND mi._id_competencia  = ?
                   AND mi._id_capacidad    = ?
                   AND mi._id_indicador    = ?
                   AND mi._id_main         = ?
                   AND mi._id_instrumento  = ins.id_instrumento";
        $result = $this->db->query($sql, array($idGrado, $idCurso, $idCompetencia, $idCapacidad, $idIndicador, $idMain) );
        return $result->result_array();
    }

    function getTalleres() {
        $sql = "SELECT id_taller, 
                	   desc_taller
                  FROM taller";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getAulaExt() {
        $sql = "SELECT id_aula_ext,
                       desc_aula_ext
                  FROM aula_externa";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getComboCursosUgelEquiv($idArea) {
        $sql = "SELECT id_curso,
                       desc_curso
                  FROM notas.fun_get_cursos_area(?)
              GROUP BY id_curso, desc_curso";
        $result = $this->db->query($sql, array($idArea));
        return $result->result_array();
    }
    
    function getAulasTutor($idTutor) {
        $sql = "SELECT a.nid_aula,
                        a.desc_aula
                   FROM persona_x_aula pa,
                        persona         p,
                        aula            a
                  WHERE a.id_tutor      = ?
                    AND a.year          = pa.year_academico
                    AND pa.__id_persona = p.nid_persona
                    AND a.nid_aula      = pa.__id_aula
                GROUP BY a.nid_aula";
        $result = $this->db->query($sql, array($idTutor));
        return $result->result();
    }
}