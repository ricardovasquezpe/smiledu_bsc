<?php
class M_asig_alum_curso extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getGruposCursos($idCurso, $idGrado, $year, $idArea, $idSede) {
        $sql = "SELECT m.nid_main,
                       m.nombre_grupo,
                       m.limite_alumno,
                       a.desc_aula,
                       p.nom_persona,
                       (SELECT COUNT(1) as cant_alumno
        			      FROM grupo_x_alumno
        			     WHERE __id_main = m.nid_main),
                       (SELECT string_agg(CONCAT(g.abvr, n.abvr),' - ') FROM grupo_aula ga,
										   grado g,
										   nivel n
									WHERE ga.__id_grado = g.nid_grado
									  AND n.nid_nivel   = g.id_nivel
									  AND ga.__id_main  = m.nid_main) as grados,
                       (SELECT flg_pen_cambio 
        				  FROM grupo_x_alumno 
        				 WHERE flg_pen_cambio = ".FLG_CAMBIO_PENDIENTE."
        				   AND  m.nid_main = __id_main
                          GROUP BY flg_pen_cambio),			  
		               m.nid_curso
                  FROM main             m,
                       aula	            a,
                       grupo_aula      ga,
                       persona          p,
        			   sede             s,     
                       notas.fun_get_cursos_area(?) cg,
                       grupo_x_docente              gd
                 WHERE ga.__id_aula   = a.nid_aula
                   AND ga.__id_main   = m.nid_main
        		   AND a.nid_sede     = s.nid_sede
                   AND p.nid_persona  = gd.__id_docente
                   AND m.nid_main     = gd.__id_main
                   AND cg.id_curso    = m.nid_curso
                   AND ga.__id_grado  = ?
                   AND cg.id_curso    = ?
                   AND a.year	      = ?
        		   AND a.nid_sede     = ?                           
              GROUP BY m.nid_main, m.nombre_grupo, a.desc_aula, p.nom_persona, ga.__id_aula
             ORDER BY m.nid_main DESC";
        $result = $this->db->query($sql, array($idArea, $idGrado, $idCurso, $year, $idSede));
        if($result->num_rows() >= 1) {
            return $result->result_array();
        }
        return null;
    }
    
    function cantidadAlumGrupo($idMain) {
        $sql = "SELECT (SELECT COUNT(1) as cant_alumno
					     FROM grupo_x_alumno
					    WHERE __id_main = ?),
                       m.limite_alumno
			      FROM main m
			     WHERE m.nid_main = ?";
        $result = $this->db->query($sql, array($idMain, $idMain));

        if($result->num_rows() == 1) {
            return $result->row();
        } else {
            return null;
        }
    }
    
    function getAulas($idGrado, $year, $sede) {
        $sql = "SELECT nid_aula,
                       desc_aula,
                       capa_max,
                       CONCAT('(',s.abvr,')') AS sede,
                       (SELECT COUNT(1) as cant_alumno 
                	      FROM persona_x_aula pa,
                	           persona 	       p
                	     WHERE pa.__id_aula    = nid_aula
                	       AND pa.__id_persona = p.nid_persona) 
                  FROM aula a,
                       sede s
                 WHERE a.nid_sede  = s.nid_sede
                   AND a.nid_grado = ?
                   AND year        = ?
                   AND a.nid_sede  = ?";
        $result = $this->db->query($sql, array($idGrado, $year, $sede));   
        return $result->result_array();   
    }
    
    function getEstudiantes($idAula, $idAnio, $idCurso, $idMain, $busqueda) {
        $sql = "SELECT p.nid_persona,
                       a.nid_aula,
                       pa.promedio_final,
                       CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers,', ' ,SPLIT_PART(INITCAP(p.nom_persona),' ',1)) AS nombre_corto,
                       CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
            	       (SELECT count(1) AS grupo 
            			  FROM grupo_x_alumno ga,
            			       main            m	  
            			 WHERE ga.__id_main = m.nid_main
            			   AND __id_alumno  = p.nid_persona
            			   AND m.nid_curso  = ?
            			   AND ga.estado    = '".ESTADO_GRUPO_REGISTRADO."'),   
            		   (SELECT flg_pen_cambio
            			  FROM grupo_x_alumno
            			 WHERE __id_alumno = p.nid_persona)	        
                  FROM persona_x_aula pa,
                       persona         p,
                       aula            a
                 WHERE a.year            = pa.year_academico
                   AND pa.__id_persona   = p.nid_persona
                   AND a.nid_aula        = pa.__id_aula
                   AND pa.__id_aula      = ?
                   AND pa.year_academico = ?
            	   AND UPPER(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) LIKE UPPER(?)
            	   AND pa.__id_persona   NOT IN (SELECT ga.__id_alumno
												   FROM grupo_x_alumno ga,
													    main            m,
													    aula            a,
													    grupo_aula     gl,
													    persona         p	  
												  WHERE ga.__id_main   = m.nid_main
												    AND gl.__id_main   = m.nid_main
												    AND gl.__id_aula   = a.nid_aula
												    AND ga.__id_alumno = p.nid_persona
            			                            AND flg_pen_cambio = '".FLG_ASIGNADO_GRUPO."' 
												    AND ga.estado      = '".ESTADO_GRUPO_REGISTRADO."'
												    AND a.year	       = ?
												    AND m.nid_curso    = ?       
												   GROUP BY ga.__id_alumno) 	        
            ORDER BY ape_pate_pers, ape_mate_pers, nom_persona";
        $result = $this->db->query($sql, array($idCurso, $idAula, $idAnio,'%'.$busqueda.'%', $idAnio, $idCurso));
        return $result->result_array();
    }
    
    function flgPendCambio($idCurso, $idAlumno, $idMain) {
        $sql = "SELECT ga.flg_pen_cambio
    			  FROM grupo_x_alumno ga,
    			       main            m	  
    			 WHERE ga.__id_main   = m.nid_main
    			   AND m.nid_curso    = ?
                   AND ga.__id_alumno = ?
                   AND ga.__id_main   = ?
    			   AND ga.estado      = '".ESTADO_GRUPO_REGISTRADO."'";
        $result = $this->db->query($sql, array($idCurso, $idAlumno, $idMain));
        return $result->row_array();
    }
    
    function asignarAlumno($arrayAlumno, $idMain) {
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->trans_begin();
            foreach ($arrayAlumno as $row) {
                $idAlumno = _decodeCI($row['__id_alumno']);
                $arrayInsert = array("__id_main"       => $idMain,
                                     "__id_alumno"     => $idAlumno,
                                     "flg_activo"      => FLG_ACTIVO,
                                     "audi_usua_modi"  => $this->_idUserSess,
                                     "audi_fec_modi"   => date('Y-m-d H:i:s'),
                                     "estado"          => ESTADO_GRUPO_REGISTRADO,
                                      "flg_pen_cambio" => FLG_ASIGNADO_GRUPO 
                );
                $this->db->insert('grupo_x_alumno', $arrayInsert);
                if($this->db->affected_rows() != 1) {
                    throw new Exception('(MCT-001)');
                }
                
                $sql = "DELETE FROM grupo_x_alumno
                         WHERE flg_pen_cambio = ".FLG_CAMBIO_PENDIENTE."
                           AND __id_main IN (SELECT __id_main FROM grupo_x_alumno)
                           AND __id_alumno = ?";
                $this->db->query($sql, array($idAlumno));
            }
                        
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
            $this->db->trans_commit();
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $rpt;
    }
        
    function getAlumnosGrupos($year, $idMain, $idCurso, $busqueda) {
        $sql = "SELECT CONCAT(p.ape_pate_pers,' ', p.ape_mate_pers,', ' ,SPLIT_PART(INITCAP(p.nom_persona),' ',1)) AS nombre_corto,
                       CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona, 
                       a.desc_aula,
                       ga.__id_alumno,
                       ga.flg_pen_cambio,
                       (SELECT a.desc_aula 
                    	  FROM persona_x_aula pa,
                    	       aula            a 
                    	 WHERE __id_persona = ga.__id_alumno
                    	   AND pa.__id_aula = a.nid_aula
                           AND a.year       = ?)
                  FROM grupo_x_alumno ga,
                       main            m,
                       aula            a,
                       grupo_aula     gl,
                       persona         p	  
                 WHERE ga.__id_main   = m.nid_main
                   AND gl.__id_main   = m.nid_main
                   AND gl.__id_aula   = a.nid_aula
                   AND ga.__id_alumno = p.nid_persona
                   AND ga.estado      = '".ESTADO_GRUPO_REGISTRADO."'
                   AND a.year	      = ?
                   AND ga.__id_main   = ? 
                   AND m.nid_curso    = ?   
                   AND UPPER(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)) LIKE UPPER(?)     
                GROUP BY a.desc_aula, ga.__id_alumno, p.ape_pate_pers, p.ape_mate_pers, p.nom_persona, p.foto_persona, ga.flg_pen_cambio
                ORDER BY p.ape_pate_pers ASC ";
        $result = $this->db->query($sql, array($year, $year, $idMain, $idCurso,'%'.$busqueda.'%'));
        return $result->result_array();
    }
    
    function cambioAlumnoGrupo($idAlumno, $idMain, $arrayDato) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->where ('__id_main'     , $idMain);
            $this->db->where ('__id_alumno'   , $idAlumno);
            $this->db->update('grupo_x_alumno', $arrayDato);
    
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-001)');
            }

            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function flg_cambio($idAlumno, $idMain) {
        $sql = "SELECT flg_pen_cambio
                  FROM grupo_x_alumno
                 WHERE __id_main   = ?
                   AND __id_alumno = ?";
        $result = $this->db->query($sql, array($idMain, $idAlumno));
        return $result->row()->flg_pen_cambio;
    }
}