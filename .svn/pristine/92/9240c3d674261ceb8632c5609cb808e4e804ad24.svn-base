<?php
class M_detalle_alumno extends  CI_Model {
    function __construct(){
        parent::__construct();
    }

    function getNotasCursoBimestre($idAlumno, $year, $idAula, $id_bimestre, $idCurso) {
        $sql = "SELECT id_curso, desc_curso,  
                       CASE WHEN (AVG(ixe.nota_numerica) IS NOT NULL) THEN Round(AVG(ixe.nota_numerica), 3)::character varying
                            ELSE '-' END AS promedio 
                  FROM notas.fun_get_cursos_area(null) na LEFT JOIN notas.matriz_x_instrumento mxi ON(mxi._id_curso          = na.id_curso 
                                                                                                      AND mxi._id_ciclo_acad = COALESCE(?, mxi._id_ciclo_acad ))
                  LEFT JOIN notas.instrumento_x_estudiante ixe ON (ixe._id_instrumento = mxi._id_instrumento
                                						       AND ixe._year_acad      = ? 
                                						       AND ixe._id_estudiante  = ? 
									                           AND ixe._id_curso       = COALESCE(?, ixe._id_curso)   
                                						       AND ixe._id_grado       = mxi._id_grado   
                                						       AND ixe._id_curso       = id_curso
                                						       AND ixe._year_acad      = mxi._year_acad
                                						       AND ixe._id_competencia = mxi._id_competencia
                                						       AND ixe._id_capacidad   = mxi._id_capacidad
                                						       AND ixe._id_indicador   = mxi._id_indicador
                                						       AND ixe._id_main        = mxi._id_main
                                						       AND ixe.correlativo     = mxi.correlativo
                                						       AND ixe._id_main IN (SELECT nid_main 
                                            										  FROM main 
                                            										 WHERE nid_aula = ?))
               WHERE id_curso = COALESCE(?, id_curso)      
                GROUP BY id_curso, desc_curso ";
        $result = $this->db->query($sql, array($id_bimestre, $year, $idAlumno, $idCurso, $idAula, $idCurso));
        $this->db->last_query();
        return $result->result_array();
    }
}