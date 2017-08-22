<?php
class M_selec_taller extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
  
    function getHijosByFamilia($idPersona) {
        $sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers, ', ', p.nom_persona)) AS nombrecompleto,
                       p.nid_persona,
                       CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
                       desc_aula,
                       (SELECT abvr
                          FROM grado
                         WHERE nid_grado = a.nid_grado) abrev_grado,
                       (SELECT abvr
                          FROM nivel
                         WHERE nid_nivel = a.nid_nivel) abrev_nivel,
                       (SELECT COUNT(1) 
                          FROM (SELECT COUNT(1)
                                  FROM grupo_x_alumno gal,
                                       main m
                                 WHERE gal.__id_main   = m.nid_main
                                   AND gal.estado      = '".ESTADO_GRUPO_REGISTRADO."'
                                   AND gal.__id_alumno = p.nid_persona 
                                 GROUP BY m.__id_taller) AS a) cant_talleres,
                       (SELECT COUNT(1)
                          FROM pagos.movimiento
                         WHERE _id_persona = p.nid_persona
                           AND estado = 'VENCIDO') AS count_vencidos
                  FROM persona p,
                       sima.detalle_alumno da,
                       persona_x_aula pa,
                       aula a
                 WHERE p.nid_persona   = da.nid_persona
                   AND pa.__id_persona = p.nid_persona
                   AND a.nid_aula      = pa.__id_aula
                   AND da.cod_familia IN (SELECT cod_familiar
                                           FROM sima.familiar_x_familia ff
                                          WHERE ff.id_familiar = ?)
              ORDER BY a.nid_nivel, a.nid_grado";
        $result = $this->db->query($sql, array($idPersona));
        return $result->result();
    }
    
    function getTalleresByEstudiante($idEstudiante){
        $sql = "SELECT INITCAP(t.desc_taller) AS desc_taller,
                       t.id_taller,
                       INITCAP((SELECT INITCAP(a.desc_area) AS desc_area
                                  FROM area a 
                                 WHERE t.__id_area = a.id_area)) AS area,
                        CASE WHEN (SELECT __id_main 
                                     FROM grupo_x_alumno gal,
                                          main m1
                                    WHERE t.id_taller = m1.__id_taller
                                      AND gal.__id_main   = m1.nid_main
                                      AND gal.__id_alumno = ?
                                      AND gal.estado = '".ESTADO_GRUPO_REGISTRADO."') IS NOT NULL THEN (SELECT m2.nombre_grupo
                                                                                   FROM grupo_x_alumno gal,
                                                                                        main m2
                                                                                  WHERE t.id_taller     = m2.__id_taller
                                                                                    AND gal.__id_main   = m2.nid_main
                                                                                    AND gal.__id_alumno = ?
                                                                                    AND gal.estado = '".ESTADO_GRUPO_REGISTRADO."')
                       ELSE '0' END AS in,
                       CASE WHEN (SELECT __id_main 
                                     FROM grupo_x_alumno gal,
                                          main m1
                                    WHERE t.id_taller = m1.__id_taller
                                      AND gal.__id_main   = m1.nid_main
                                      AND gal.__id_alumno = ?
                                      AND gal.estado = '".ESTADO_GRUPO_REGISTRADO."') IS NOT NULL THEN (SELECT gal.audi_fec_modi
                                                                                   FROM grupo_x_alumno gal,
                                                                                        main m2
                                                                                  WHERE t.id_taller     = m2.__id_taller
                                                                                    AND gal.__id_main   = m2.nid_main
                                                                                    AND gal.__id_alumno = ?
                                                                                    AND gal.estado = '".ESTADO_GRUPO_REGISTRADO."')
                       ELSE null END AS fecha,
                       CASE WHEN (SELECT __id_main 
                                     FROM grupo_x_alumno gal,
                                          main m1
                                    WHERE t.id_taller = m1.__id_taller
                                      AND gal.__id_main   = m1.nid_main
                                      AND gal.__id_alumno = ?
                                      AND gal.__id_main_solicitud IS NOT null) IS NOT NULL THEN 1
                       ELSE 0 END AS count_solicitudes,                                                      
                       (SELECT COUNT(1)
                          FROM main m2,
                               grupo_aula ga2
                         WHERE m2.__id_taller = t.id_taller
                           AND ga2.__id_main  = m2.nid_main
                           AND ga2.__id_grado = (SELECT nid_grado
                                                   FROM aula a,
                                                        persona_x_aula pa
                                                  WHERE a.nid_aula      = pa.__id_aula
                                                    AND pa.__id_persona = ?)
                           AND m2.limite_alumno > (SELECT COUNT(1)
                                                     FROM grupo_x_alumno ga2
                                                    WHERE ga2.__id_main = m2.nid_main)) AS cant_grupos_vacios
                  FROM taller t,
                       main m,
                       grupo_aula ga
                 WHERE t.id_taller    = m.__id_taller
                   AND m.nid_main     = ga.__id_main
                   AND ga.__id_grado  = (SELECT nid_grado
                                        FROM aula a,
                                             persona_x_aula pa
                                       WHERE a.nid_aula      = pa.__id_aula
                                         AND pa.__id_persona = ?)
              GROUP BY t.id_taller, t.id_taller";
        $result = $this->db->query($sql, array($idEstudiante, $idEstudiante, $idEstudiante, $idEstudiante, $idEstudiante, $idEstudiante, $idEstudiante));
        return $result->result();
    }
    
    function getGruposByTaller($idEstudiante, $idTaller){
        $sql = "SELECT m.nombre_grupo,
                       m.nid_main,
                       m.limite_alumno,
                       (SELECT desc_aula_ext
                          FROM aula_externa
                         WHERE id_aula_ext = ga.__id_aula_ext) AS nom_aula,
                       (SELECT COUNT(__id_main) 
                          FROM grupo_x_alumno gal
                         WHERE gal.__id_main = m.nid_main) AS count_alumn
                  FROM taller t,
                       main m,
                       grupo_aula ga
                 WHERE t.id_taller    = m.__id_taller
                   AND m.nid_main     = ga.__id_main
                   AND ga.__id_grado  = (SELECT nid_grado
                                           FROM aula a,
                                                persona_x_aula pa
                                          WHERE a.nid_aula      = pa.__id_aula
                                            AND pa.__id_persona = ?)
                   AND t.id_taller = ?";
        $result = $this->db->query($sql, array($idEstudiante, $idTaller));
        return $result->result();
    }
    
    function insertarEstudianteGrupo($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("grupo_x_alumno", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCT-001)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getTalleresByEstudianteCombo($idEstudiante, $idTaller){
        $sql = "SELECT INITCAP(t.desc_taller) AS desc_taller,
                       t.id_taller,
                       (SELECT COUNT(1)
                          FROM main m2
                         WHERE m2.__id_taller = t.id_taller
                           AND m2.limite_alumno = (SELECT COUNT(1)
                                                     FROM grupo_x_alumno ga2
                                                    WHERE ga2.__id_main = m2.nid_main)) AS cant_grupos_llenos,
                       (SELECT COUNT(1)
                          FROM main m2
                         WHERE m2.__id_taller = t.id_taller) AS cant_grupos
                  FROM taller t,
                       main m,
                       grupo_aula ga
                 WHERE t.id_taller    = m.__id_taller
                   AND m.nid_main     = ga.__id_main
                   AND ga.__id_grado  = (SELECT nid_grado
                                        FROM aula a,
                                             persona_x_aula pa
                                       WHERE a.nid_aula      = pa.__id_aula
                                         AND pa.__id_persona = ?)
              GROUP BY t.id_taller, t.id_taller";
        $result = $this->db->query($sql, array($idEstudiante));
        return $result->result();
    }
    
    function getGrupoByHijoTaller($idHijo, $idTaller){
        $sql = "SELECT nid_main AS grupo
                  FROM main m,
                       grupo_x_alumno ga
                 WHERE m.nid_main     = ga.__id_main
                   AND __id_taller    = ?
                   AND ga.__id_alumno = ?
                   AND ga.estado      = '".ESTADO_GRUPO_REGISTRADO."'";
        $result = $this->db->query($sql, array($idTaller, $idHijo));
        if($result->row() != null){
            return $result->row()->grupo;
        }else{
            return null;
        }
    }
    
    function eliminarEstudianteGrupo($idGrupo, $idAlumno){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('__id_main', $idGrupo);
            $this->db->where('__id_alumno', $idAlumno);
            $this->db->delete('grupo_x_alumno');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MCT-002)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function verificarHijoTalleroGrupo($idHijo, $idTaller){
        $sql = "SELECT COUNT(1) AS count
                  FROM main m,
                       grupo_x_alumno ga
                 WHERE m.nid_main      = ga.__id_main
                   AND __id_taller     = ?
                   AND ga.__id_alumno  = ?
                   AND (ga.estado      = '".ESTADO_GRUPO_REGISTRADO."' OR 
                        ga.estado      = '".ESTADO_GRUPO_SOLICITADO."')";
        $result = $this->db->query($sql, array($idTaller, $idHijo));
        return $result->row()->count;
    }
    
    function updateGrupoHijo($arrayUpdate, $idPersona, $idGrupo){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("__id_alumno", $idPersona);
            $this->db->where("__id_main", $idGrupo);
            $this->db->update("grupo_x_alumno", $arrayUpdate);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCT-003)');
            }
        
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getCantidadTalleresByHijo($idPersona){
        $sql = "SELECT COUNT(1) AS count
                   FROM (SELECT COUNT(1)
                           FROM grupo_x_alumno gal,
                                main m
                          WHERE gal.__id_main   = m.nid_main
                            AND gal.estado      = '".ESTADO_GRUPO_REGISTRADO."' 
                            AND gal.__id_alumno = ?
                          GROUP BY m.__id_taller) AS a";
        $result = $this->db->query($sql, array($idPersona));
        return $result->row()->count;
    }
    
    function getCantidadTalleresSameAreaByHijo($idPersona, $idTaller){
        $sql = "SELECT COUNT(1) AS count
                   FROM (SELECT COUNT(1)
                           FROM grupo_x_alumno gal,
                                main m,
                                taller t
                          WHERE gal.__id_main   = m.nid_main
                            AND t.id_taller     = m.__id_taller
                            AND gal.estado      = '".ESTADO_GRUPO_REGISTRADO."'
                            AND gal.__id_alumno = ?
                            AND t.__id_area     = (SELECT __id_area
                                                     FROM taller t1
                                                    WHERE t1.id_taller = ?)
                          GROUP BY m.__id_taller) AS a";
        $result = $this->db->query($sql, array($idPersona, $idTaller));
        return $result->row()->count;
    }
}