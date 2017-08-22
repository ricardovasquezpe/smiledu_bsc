<?php

class M_aula extends CI_Model
{
    function __construct(){
		parent::__construct();
	}
	
	function getAulasByBusqueda($txtBusqueda,$idSedeRol,$limit=null,$offset=null){
	    $sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
		               INITCAP(s.desc_sede) AS desc_sede,
		               INITCAP(n.desc_nivel) AS desc_nivel,
		               INITCAP(CONCAT(g.abvr,' ',g.desc_grado)) AS desc_grado,
	                   CONCAT(g.abvr,' ',n.abvr) AS desc_gradonivel,
	                   a.nombre_letra,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN CONCAT(p.ape_pate_pers,', ',p.nom_persona)
	                        ELSE 'Tutor no asignado' END AS nombretutor,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'foto_perfil_default.png' END AS foto_persona,
		               CASE WHEN a.capa_max IS NOT NULL THEN a.capa_max
	                        ELSE 0 END AS capa_max,
	                   a.year,
	                   (SELECT 	desc_combo
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_CICLO."
                           AND valor = a.tipo_ciclo::character varying) tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '1'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) hombres,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '2'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
                   AND a.year        = EXTRACT (year from NOW())
                   AND n.nid_nivel   = a.nid_nivel
                   AND (CASE WHEN ? IS NOT NULL THEN s.nid_sede = ?
	                          ELSE 1 = 1 END)
	               AND (CASE WHEN ? IS NOT NULL THEN UPPER(a.desc_aula) LIKE UPPER(?)
	                          ELSE 1 = 1 END)
		    order by desc_aula
                      LIMIT ".(($limit != null) ? $limit : 100000000).
	                      " OFFSET ".(($offset != null) ? $offset : 0);
	    $result = $this->db->query($sql, array($idSedeRol,$idSedeRol,$txtBusqueda, '%'.$txtBusqueda.'%'));
	    return $result->result();
	}
	
	function getAllAulasByBusquedaTipoCicloSede($txt,$tipociclo,$idSedeRol){
	    $sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
		               INITCAP(s.desc_sede) AS desc_sede,
		               INITCAP(n.desc_nivel) AS desc_nivel,
		               INITCAP(CONCAT(g.abvr,' ',g.desc_grado)) AS desc_grado,
	                   CONCAT(g.abvr,' ',n.abvr) AS desc_gradonivel,
	                   UPPER(a.nombre_letra) AS nombre_letra,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers,', ',p.nom_persona))
	                        ELSE 'Tutor no asignado' END AS nombretutor,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'foto_perfil_default.png' END AS foto_persona,
		               CASE WHEN a.capa_max IS NOT NULL THEN a.capa_max
	                        ELSE 0 END AS capa_max,
	                   a.year,
	                   (SELECT desc_combo
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_CICLO."
                           AND valor = a.tipo_ciclo::character varying) tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '".SEXO_MASCULINO."'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) hombres,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '".SEXO_FEMENINO."'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
		           AND n.nid_nivel   = a.nid_nivel
	               AND (CASE WHEN ? IS NOT NULL THEN s.nid_sede = ?
	                          ELSE 1 = 1 END)
                   AND a.year        = EXTRACT (year from NOW())
	               AND a.nid_aula    IN (SELECT a.nid_aula
    								   FROM sede s,
    								        grado g,
    								        nivel n,
    								        aula a
    						      LEFT JOIN persona p
    					                 ON p.nid_persona = a.id_tutor
    							  	  WHERE s.nid_sede    = a.nid_sede
    								    AND g.nid_grado   = a.nid_grado
    								    AND n.nid_nivel   = a.nid_nivel
    							        AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(a.desc_aula)) LIKE UNACCENT(UPPER(?))
	                                              ELSE 1 = 1 END))
	               AND (CASE WHEN ? IS NOT NULL THEN a.tipo_ciclo = ?
        									    ELSE 1 = 1 END)
		      ORDER BY desc_aula";
	    $result = $this->db->query($sql, array($idSedeRol, $idSedeRol, $txt, '%'.$txt.'%', $tipociclo, $tipociclo));
	    return $result->result();
	}
	
	function getAllAulasByGradoYear($year, $idSede, $idNivel, $idGrado, $tipociclo){
	    $sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
		               INITCAP(s.desc_sede) AS desc_sede,
		               INITCAP(n.desc_nivel) AS desc_nivel,
		               INITCAP(CONCAT(g.abvr,' ',g.desc_grado)) AS desc_grado,
	                   CONCAT(g.abvr,' ',n.abvr) AS desc_gradonivel,
	                   a.nombre_letra,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers,', ',p.nom_persona))
	                        ELSE 'Tutor no asignado' END AS nombretutor,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'foto_perfil_default.png' END AS foto_persona,
	                   CASE WHEN a.capa_max IS NOT NULL THEN a.capa_max
	                        ELSE 0  END AS capa_max,
	                   a.year,
	                   (SELECT 	desc_combo
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_CICLO."
                           AND valor = a.tipo_ciclo::character varying) tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '1'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) hombres,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '2'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
		           AND n.nid_nivel   = a.nid_nivel
	               AND a.year        = ?
	               AND a.nid_sede    = ?
	               AND n.nid_nivel   = ?
	               AND g.nid_grado   = ?
	               AND (CASE WHEN ? IS NOT NULL THEN a.tipo_ciclo = ?
	                          ELSE 1 = 1 END)
		    order by desc_aula";
	    $result = $this->db->query($sql, array($year, $idSede, $idNivel, $idGrado, $tipociclo, $tipociclo));
	    return $result->result();
	}
	
	function getAulasPendientes(){
	    $sql="SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
		               INITCAP(s.desc_sede) AS desc_sede,
		               INITCAP(n.desc_nivel) AS desc_nivel,
		               INITCAP(CONCAT(g.abvr,' ',g.desc_grado)) AS desc_grado,
	                   CONCAT(g.abvr,' ',n.abvr) AS desc_gradonivel,
	                   UPPER(a.nombre_letra) AS nombre_letra,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN INITCAP(CONCAT(p.ape_pate_pers,', ',p.nom_persona))
	                        ELSE 'Tutor no asignado' END AS nombretutor,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'foto_perfil_default.png' END AS foto_persona,
		               CASE WHEN a.capa_max IS NOT NULL THEN a.capa_max
	                        ELSE 0 END AS capa_max,
	                   a.year,
	                   (SELECT desc_combo
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_CICLO."
                           AND valor = a.tipo_ciclo::character varying) tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '".SEXO_MASCULINO."'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) hombres,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '".SEXO_FEMENINO."'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
				  FROM aula a
	         LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE a.flg_acti    = 0
                   AND (a.year >= EXTRACT (year from NOW()) OR a.year IS NULL)
		      ORDER BY desc_aula";
	    $result = $this->db->query($sql, array());
	    return $result->result();
	}
	
	function getDetalleAulas($idAula){
		$sql = "SELECT desc_aula,
					   nid_sede,
				       nid_grado,
				       nid_nivel,
			           capa_max,
				       id_tutor,
				       id_tipo_nota,
				       UPPER(nombre_letra) nombre_letra,
				       year,
		               nid_aula,
		               observacion,
		               tipo_ciclo,
		               flg_acti
  				  FROM aula
 				 WHERE nid_aula = ?";
		$result = $this->db->query($sql,array($idAula));
		return $result->row_array();
	}
	
	function getAulasNoActivas($idsede){
        $sql = "(SELECT UPPER(a.desc_aula) AS desc_a
                   FROM aula a
                  WHERE a.year     <> "._getYear()."
                    AND a.flg_acti <> ".FLG_ACTIVO.")
                EXCEPT
                (SELECT UPPER(a1.desc_aula) AS desc_a
			       FROM aula a1
        		  WHERE a1.year     = "._getYear()."
        		    AND a1.flg_acti = ".FLG_ACTIVO."
        			AND CASE WHEN nid_sede IS NOT NULL THEN nid_sede = ?
        		        ELSE 1 = 1 END)
        		ORDER BY desc_a";
	    $result = $this->db->query($sql, array($idsede));
	    return $result->result();
	}

	function getProfesoresCursosByAula($idAula){
	    $sql = "SELECT c.desc_curso,
                       string_agg(p.nid_persona::character varying, ',') as personas,
                       string_agg(INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ')), ';') as apellidos,
                       string_agg(INITCAP(CONCAT(p.nom_persona)), ',') as nombres,
                       string_agg(gd.flg_activo::character varying, ',') as activos,
                       string_agg(gd.flg_titular::character varying, ',') as titulares,
                       string_agg(CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                                       WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                       ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END, ',') as fotos
                  FROM main            m,
                       grupo_x_docente gd,
                       persona         p,
                       cursos          c
                 WHERE m.nid_aula      = ?
                   AND m.nid_persona   = p.nid_persona
                   AND gd.__id_main    = m.nid_main
                   AND gd.__id_docente = p.nid_persona
                   AND m.nid_curso     = c.id_curso
                   AND gd.flg_activo   IN ('".FLG_DOCENTE_ASIGNADO."', '".FLG_DOCENTE_DESACTIVADO."')
              GROUP BY c.id_curso";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
	}
	
	function getCapaActualAula($idaula){
	    $sql = "SELECT COUNT(1) capa_actual
	              FROM persona_x_aula pa
	             WHERE pa.__id_aula = ?";
	    $result = $this->db->query($sql,array($idaula));
	    return $result->row()->capa_actual;
	}
	
	function getCountAulasByDescripcion($sede, $nivel, $grado, $desc_aula){
	    $sql = "SELECT COUNT(1) cant
	              FROM aula
	             WHERE flg_acti = ".FLG_ACTIVO."
	               AND nid_sede  = ?
	               AND UNACCENT(LOWER(desc_aula)) LIKE UNACCENT(LOWER(?))";
	    $result = $this->db->query($sql,array($sede, $desc_aula));
	    return $result->row()->cant;
	}
	
    function updateCampoDetalleAula($arrayUpdate, $idAula){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where("nid_aula", $idAula);
            $this->db->update("aula", $arrayUpdate);
	        
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
	
	function insertAula($data){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert('aula',$data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $rpt['idAula'] = $this->db->insert_id();
	        $rpt['error']  = EXIT_SUCCESS;
	        $rpt['msj']    = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getVacantesDisponibles($idaula){
	    $sql = "SELECT a.nid_aula,(a.capa_max - (SELECT COUNT(1)
            	                                   FROM persona_x_aula pa
            	                                  WHERE pa.__id_aula = ?) )disponible
                  FROM aula a
                 WHERE a.nid_aula = ?
              GROUP BY a.nid_aula";
	    $result = $this->db->query($sql,array($idaula,$idaula));
	    return $result->row()->disponible;
	}
	
	function getCountCicloRegular($idpersona,$year){
	    $sql = "   SELECT COUNT(1) cant
                     FROM aula a
                LEFT JOIN persona_x_aula pxa
                       ON (pxa.__id_aula = a.nid_aula)
                    WHERE pxa.__id_persona = ?
                      AND a.tipo_ciclo = ".TIPO_CICLO_REGULAR."
                      AND pxa.year_academico = ?";
	    $result = $this->db->query($sql,array($idpersona,$year));
	    return $result->row()->cant;
	}
	
	function deleteAula($idaula){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where('nid_aula',$idaula);
	        $delete = $this->db->delete('main');
	        if($delete != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $this->db->where('nid_aula', $idaula);
	        $this->db->delete('aula');
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	        $this->db->trans_commit();
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_DEL;
	    }catch(Exception $e){
	        $this->db->trans_rollback();
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getYearBySedeRol($sedeRol){
	    $sql = "SELECT year
	              FROM aula
	             WHERE year > 0
	               AND nid_sede = ?
	          GROUP BY year
	          ORDER BY year";
	    $result = $this->db->query($sql, array($sedeRol));
	    return $result->result();
	}
	
	function getAllAulasByGradoYearCapMax($year, $idSede, $idNivel, $idGrado, $idAula){
	    $sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN UPPER(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN UPPER(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
	                   CONCAT((SELECT COUNT(1)
	                                    FROM persona_x_aula pa
	                                   WHERE pa.__id_aula = a.nid_aula), '/', a.capa_max) AS capacidad
				  FROM aula a
	             WHERE a.year        = ?
	               AND a.nid_sede    = ?
	               AND a.nid_nivel   = ?
	               AND a.nid_grado   = ?
	               AND a.capa_max > (SELECT COUNT(1)
	                                    FROM persona_x_aula pa
	                                   WHERE pa.__id_aula = a.nid_aula)
	               AND a.nid_aula <> ?
		      ORDER BY desc_aula";
	    $result = $this->db->query($sql, array($year, $idSede, $idNivel, $idGrado, $idAula));
	    return $result->result();
	}
	
	function getEstructuraAula($idAula){
	    $sql = "SELECT nid_sede,
	                   nid_grado,
	                   nid_nivel
	              FROM aula
	             WHERE nid_aula = ?";
	    $result = $this->db->query($sql,array($idAula));
	    return $result->row_array();
	}
	
	function getAulasCantidadByCombo($year, $idSede, $idNivel, $idGrado){
	    $sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN UPPER(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN UPPER(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
	                   CONCAT((SELECT COUNT(1)
	                                    FROM persona_x_aula pa
	                                   WHERE pa.__id_aula = a.nid_aula), '/', a.capa_max) AS capacidad
				  FROM aula a
	             WHERE a.year        = ?
	               AND a.nid_sede    = ?
	               AND a.nid_nivel   = ?
	               AND a.nid_grado   = ?
	               AND a.capa_max > (SELECT COUNT(1)
	                                    FROM persona_x_aula pa
	                                   WHERE pa.__id_aula = a.nid_aula)
		      ORDER BY desc_aula";
	    $result = $this->db->query($sql, array($year, $idSede, $idNivel, $idGrado));
	    return $result->result();
	}
	
	function getCursos($year, $idGrado){
	    $sql = "SELECT *
                  FROM notas.fun_get_cursos_grado_year(?, ?)";
	    $result = $this->db->query($sql, array($year, $idGrado));
	    return $result->result();
	}
	
	function countUgelByGrado($sede, $nivel, $grado, $valor){
	    $sql = "SELECT COUNT(*) cant
        		  FROM aula
        		 WHERE nid_sede = ?
        		   AND nid_nivel = ?
        		   AND nid_grado = ?
        		   AND UPPER(nombre_letra) like UPPER(?)";
	    $result = $this->db->query($sql,array($sede, $nivel, $grado, $valor));
	    return $result->row()->cant;
	}
    
    function getTutoresNoAsignados($idtutor) {
        $sql = "SELECT p.nid_persona,
                       INITCAP(CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ',p.nom_persona)) AS nombre_completo
                  FROM persona p 
            INNER JOIN aula a 
                    ON a.id_tutor = p.nid_persona 
            INNER JOIN persona_x_rol pr
                    ON p.nid_persona = pr.nid_persona
                 WHERE pr.nid_rol IN (".ID_ROL_EVALUADOR.",
                                   ".ID_ROL_DOCENTE.",
                                   ".ID_ROL_COORDINADOR_ACADADEMICO.",
                                   ".ID_ROL_TUTOR.",
                                   ".ID_ROL_PROFESORA_ASISTENTE.",
                                   ".ID_ROL_PSICOPEDAGOGO_SEDE.",
                                   ".ID_ROL_ENFERMERA.",
                                   ".ID_ROL_BIBLIOTECARIO.",
                                   ".ID_ROL_SUBDIRECTOR.")
                   AND pr.nid_persona NOT IN (SELECT pr1.nid_persona
                                                FROM persona_x_rol pr1
                                               WHERE pr1.nid_rol = ".ID_ROL_TUTOR."
                                                 AND CASE WHEN ? IS NOT NULL THEN pr1.nid_persona != ?
	                                                 ELSE 1 = 1 END)
              GROUP BY p.nid_persona, nombre_completo
              ORDER BY nombre_completo";
        $result = $this->db->query($sql, array($idtutor, $idtutor));
        return $result->result();
    }
	
    function asignarTutor($idTutor, $idTutorRolPer) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
    
            if($idTutorRolPer != null) {
                $this->db->delete('persona_x_rol', array('nid_persona' => $idTutorRolPer, 'nid_rol' => ID_ROL_TUTOR));
            }
    
            $dataRolxPersona = array(
                "nid_persona" => $idTutor,
                "nid_rol"     => ID_ROL_TUTOR,
                "flg_acti"    => FLG_ACTIVO
            );
            $this->db->insert("persona_x_rol", $dataRolxPersona);
    
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al asignar el Tutor');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

	function getProfesoresByCursoAula($idAula,$idCurso){
	    $sql = "SELECT c.desc_curso,
                       string_agg(p.nid_persona::character varying, ',') as personas,
                       string_agg(INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ')), ';') as apellidos,
                       string_agg(INITCAP(CONCAT(p.nom_persona)), ',') as nombres,
                       string_agg(gd.flg_activo::character varying, ',') as activos,
                       string_agg(gd.flg_titular::character varying, ',') as titulares,
                       string_agg(CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                                       WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                       ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END, ',') as fotos
                  FROM main            m,
                       grupo_x_docente gd,
                       persona         p,
                       cursos          c
                 WHERE m.nid_aula      = ?
                   AND (CASE WHEN ? IS NOT NULL THEN c.id_curso = ?
	                          ELSE 1 = 1 END)
                   AND m.nid_persona   = p.nid_persona
                   AND gd.__id_main    = m.nid_main
                   AND gd.__id_docente = p.nid_persona
                   AND m.nid_curso     = c.id_curso
                   AND gd.flg_activo   IN ('".FLG_DOCENTE_ASIGNADO."', '".FLG_DOCENTE_DESACTIVADO."')
              GROUP BY c.id_curso";
        $result = $this->db->query($sql,array($idAula, $idCurso, $idCurso));
        return $result->result();
	}

	function getYear(){
	    $sql = "SELECT year
	              FROM aula
	             WHERE year > 0
	          GROUP BY year
	          ORDER BY year";
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	/*
	function getAllAulas(){
		$sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
		               upper(s.desc_sede) AS desc_sede,
		               upper(n.desc_nivel) AS desc_nivel,
		               upper(g.desc_grado) AS desc_grado,
	                   CONCAT(g.abvr,' ',n.abvr) AS desc_gradonivel,
	                   a.nombre_letra,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN CONCAT(p.ape_pate_pers,', ',p.nom_persona)
	                        ELSE 'Tutor no asignado' END AS nombretutor,
	                   p.foto_persona,
		               a.capa_max,
	                   a.year,
	                   (SELECT 	desc_combo 
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_CICLO."
                           AND valor = a.tipo_ciclo) tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '1'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) hombres,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '2'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
		           AND n.nid_nivel   = a.nid_nivel
		    order by desc_aula";
		$result = $this->db->query($sql);
		return $result->result();
	}
	
	function getAllAulasByYear($year){
	    $sql = "SELECT a.nid_aula,
				       upper(a.desc_aula) AS desc_aula,
		               upper(s.desc_sede) AS desc_sede,
		               upper(g.desc_grado) AS desc_grado,
		               upper(n.desc_nivel) AS desc_nivel,
	                   a.year,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)
	                        ELSE '' END AS nombrecompleto,
		               a.capa_max,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE a.year        = ?
	               AND s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
		           AND n.nid_nivel   = a.nid_nivel
		      order by a.desc_aula";
	    $result = $this->db->query($sql, array($year));
	    return $result->result();
	}
		
	function getAllAulasByTxt($txt,$letra,$idSedeRol){
	    $sql = "SELECT a.nid_aula,
	                   CASE WHEN desc_aula IS NOT NULL THEN INITCAP(desc_aula)
	                        WHEN nombre_letra IS NOT NULL THEN INITCAP(nombre_letra)
	                        ELSE '-'  END AS desc_aula,
		               upper(s.desc_sede) AS desc_sede,
		               upper(n.desc_nivel) AS desc_nivel,
		               upper(g.desc_grado) AS desc_grado,
	                   CONCAT(g.abvr,' ',n.abvr) AS desc_gradonivel,
	                   a.nombre_letra,
		               CASE WHEN p.ape_pate_pers IS NOT NULL THEN CONCAT(p.ape_pate_pers,', ',p.nom_persona)
	                        ELSE 'Tutor no asignado' END AS nombretutor,
	                   p.foto_persona,
		               a.capa_max,
	                   a.year,
	                   (SELECT desc_combo
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_TIPO_CICLO."
                           AND valor = a.tipo_ciclo) tipo_ciclo,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '1'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) hombres,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa,
	                           persona p
	                     WHERE sexo = '2'
	                       AND pa.__id_aula = a.nid_aula
	                       AND p.nid_persona = pa.__id_persona) mujeres
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
		           AND n.nid_nivel   = a.nid_nivel
	               AND (CASE WHEN ? IS NOT NULL THEN s.nid_sede = ?
	                          ELSE 1 = 1 END)
	               AND a.nid_aula    IN (SELECT a.nid_aula
    								   FROM sede s,
    								        grado g,
    								        nivel n,
    								        aula a
    						      LEFT JOIN persona p
    					                 ON p.nid_persona = a.id_tutor
    							  	  WHERE s.nid_sede    = a.nid_sede
    								    AND g.nid_grado   = a.nid_grado
    								    AND n.nid_nivel   = a.nid_nivel
    							        AND (CASE WHEN ? IS NOT NULL THEN UPPER(a.desc_aula) LIKE UPPER( ? )
    									    ELSE 1 = 1 END))
	               AND (CASE WHEN ? IS NOT NULL THEN UPPER(a.desc_aula) LIKE UPPER( ? )
        									    ELSE 1 = 1 END)
		              order by desc_aula";
	    $result = $this->db->query($sql, array($idSedeRol,$idSedeRol,$txt,$txt.'%',$letra,$letra.'%'));
	    return $result->result();
	}
	
	function getAllAulasByGradoProfesor($idSede, $idNivel, $idGrado){
	    $sql = "SELECT a.nid_aula,
				       upper(a.desc_aula) AS desc_aula,
		               CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombrecompleto,
		               a.capa_max,
	                   (SELECT COUNT(1)
	                      FROM persona_x_aula pa
	                     WHERE pa.__id_aula = a.nid_aula) capa_actual,
	                   (SELECT COUNT(1)
	                      FROM sima.profesor_aula_curso pac
	                     WHERE pac._id_aula = a.nid_aula) num_profesores
				  FROM aula a
		     LEFT JOIN persona p
		            ON p.nid_persona = a.id_tutor
		         WHERE a.nid_sede    = ?
	               AND a.nid_nivel   = ?
	               AND a.nid_grado   = ?
		    order by desc_aula";
	    $result = $this->db->query($sql, array($idSede, $idNivel, $idGrado));
	    return $result->result();
	}
		
	//RESULT() TE TRAE FILAS
	//ROW_ARRAY() TE TRAE 1 FILA
	
	function editAula($data,$idaula){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where('nid_aula',$idaula);
	        $this->db->update('aula',$data);
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-002)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPDATE_SUCCESS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getAbvrAula($year, $idSede, $idNivel, $idGrado){
	    $sql = "SELECT CONCAT ('Aulas ',a.year,' (',s.abvr,' / ',g.abvr,' ',n.abvr,')' ) as abvr_aula
				  FROM sede s,
		               grado g,
		               nivel n,
		               aula a
		         WHERE a.year        = ?
	               AND a.nid_sede    = ?
	               AND n.nid_nivel   = ?
	               AND g.nid_grado   = ?
	               AND s.nid_sede    = a.nid_sede
		           AND g.nid_grado   = a.nid_grado
		           AND n.nid_nivel   = a.nid_nivel";
	    $result = $this->db->query($sql, array($year, $idSede, $idNivel, $idGrado));
	    return $result->row()->abvr_aula;
	}
	
	function getCountCicloVerano($idpersona,$year){
	    $sql = "   SELECT COUNT(1) cant
                     FROM aula a
                LEFT JOIN persona_x_aula pxa
                       ON (pxa.__id_aula = a.nid_aula)
                    WHERE pxa.__id_persona = ?
                      AND a.tipo_ciclo = ".TIPO_CICLO_VERANO."
                      AND pxa.year_academico = ?";
	    $result = $this->db->query($sql,array($idpersona,$year));
	    return $result->row()->cant;
	}
	*/
}