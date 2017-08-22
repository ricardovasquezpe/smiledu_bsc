<?php
//LAST CODE 001
class M_contactos extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getGrupo($limit = null, $idrol, $sede, $idpersona, $cod_grupo = 0){
        $sql="SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                     CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                     nombres as nombres,
                     id_contacto,
                     correo,
                     telefono_celular,
                     parentesco,
                     (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = id_persona_registro) persona_registro,
                     (SELECT COUNT(*) 
                        FROM admision.invitados i 
                  INNER JOIN admision.evento e
                          ON i.id_evento = e.id_evento
			           WHERE i.id_contacto = c.id_contacto
                         AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."') as invitados,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                     flg_estudiante,
                     cod_grupo,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     tipo_proceso
                FROM admision.contacto c
               WHERE cod_grupo IN (SELECT cod_grupo
                                     FROM admision.contacto c1
                                    WHERE CASE WHEN (? = ".ID_ROL_SECRETARIA." OR ? = ".ID_ROL_SUBDIRECTOR.") THEN (c1.id_persona_registro = ? OR c1.sede_interes = ?)
                                          ELSE 1 = 1 END
                                      AND cod_grupo > ?
                                 GROUP BY cod_grupo
                                 ORDER BY cod_grupo
                                    LIMIT ?)
            ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($idrol, $idrol, $idpersona, $sede, $cod_grupo, $limit));
        return $result->result();
    }
    
    function getGrupoByBusqueda($txt, $limit = null, $cod_grupo = 0, $idRol, $idSede, $idPersona){
        $sql="SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                     CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                     nombres as nombres,
                     id_contacto,
                     correo,
                     telefono_celular,
                     parentesco,
                     (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = id_persona_registro) persona_registro,
                     (SELECT COUNT(*) 
                        FROM admision.invitados i 
                  INNER JOIN admision.evento e
                          ON i.id_evento = e.id_evento
			           WHERE i.id_contacto = c.id_contacto
                         AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."') as invitados,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                     flg_estudiante,
                     cod_grupo,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     tipo_proceso
                FROM admision.contacto c
               WHERE cod_grupo IN (SELECT c1.cod_grupo 
        	         		         FROM admision.contacto c1
        		 	                WHERE (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
                                                 ELSE 1 = 1 END)
                                      AND (CASE WHEN ( c1.flg_estudiante = ".FLG_ESTUDIANTE." AND (? = ".ID_ROL_SECRETARIA." OR ? = ".ID_ROL_SUBDIRECTOR.")) THEN (c1.id_persona_registro = ? OR c1.sede_interes = ?)
                                               WHEN ( c1.flg_estudiante = ".FLG_FAMILIAR." AND (? = ".ID_ROL_SECRETARIA." OR ? = ".ID_ROL_SUBDIRECTOR.")) THEN (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
                                                                                                                                                                     ELSE 1 = 1 END)
                                               ELSE 1 = 1 END)
                                      AND c1.cod_grupo > ?
                                 GROUP BY c1.cod_grupo
                                 ORDER BY c1.cod_grupo
                                    LIMIT ?)
            ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql,array($txt, '%'.$txt.'%', $idRol, $idRol, $idPersona, $idSede, $idRol, $idRol,$txt, '%'.$txt.'%', $cod_grupo, $limit));
        return $result->result();
    }
    
    function getCodGrupoByBusqueda($txt){
        $sql = "SELECT cod_grupo
                  FROM admision.contacto
                 WHERE (CASE WHEN ? IS NOT NULL THEN UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres)) LIKE UPPER(?)
                             ELSE 1 = 1 END)
              GROUP BY cod_grupo
              ORDER BY cod_grupo";
        $result = $this->db->query($sql,array($txt,'%'.$txt.'%'));
        return $result->result();
    }
    
    function getCountPersonas($idrol,$sede,$idpersona){
        $sql="SELECT COUNT(1) cant
                FROM admision.contacto
               WHERE cod_grupo IN (SELECT cod_grupo 
                    		         FROM admision.contacto c1
                                    WHERE CASE WHEN (? = ".ID_ROL_SECRETARIA." OR ? = ".ID_ROL_SUBDIRECTOR.") THEN (c1.id_persona_registro = ? OR c1.sede_interes = ?)
                                               ELSE 1 = 1 END
                                 GROUP BY cod_grupo
                                 ORDER BY cod_grupo)";
        $result = $this->db->query($sql,array($idrol, $idrol, $idpersona, $sede));
        return $result->row()->cant;
    }
    
    function getFamiliasByEstadoYear($estado, $year, $sedeInteres, $nivel, $grado, $limit=null,$cod_grupo = 0){
        $sql="SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                     CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                     nombres as nombres,
            	     id_contacto,
            	     correo,
            	     telefono_celular,
            	     parentesco,
                     (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = id_persona_registro) persona_registro,
                     (SELECT COUNT(*) 
                        FROM admision.invitados i 
                  INNER JOIN admision.evento e
                          ON i.id_evento = e.id_evento
			           WHERE i.id_contacto = c.id_contacto
                         AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."') as invitados,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     flg_estudiante,
                     cod_grupo,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     tipo_proceso
                FROM admision.contacto c
               WHERE cod_grupo IN (SELECT cod_grupo 
            	         		     FROM admision.contacto 
            		 	            WHERE (CASE WHEN ? IS NOT NULL THEN estado = ?
                                                ELSE 1 = 1 END)
                     		          AND cod_grupo > ?
                                      AND flg_estudiante = ".FLG_ESTUDIANTE."
                                      AND (CASE WHEN ? IS NOT NULL THEN sede_interes = ?
                                                ELSE 1 = 1 END)
                                      AND (CASE WHEN ? IS NOT NULL THEN nivel_ingreso = ?
                                                ELSE 1 = 1 END)
                                      AND (CASE WHEN ? IS NOT NULL THEN grado_ingreso = ?
                                                ELSE 1 = 1 END)
                                      AND (CASE WHEN ? IS NOT NULL THEN (EXTRACT (year FROM fecha_registro)) = ?
                                                ELSE 1 = 1 END)
            		 	         GROUP BY cod_grupo
                                 ORDER BY cod_grupo
                                    LIMIT ?)
            	 AND id_contacto NOT IN (SELECT id_contacto 
            			                   FROM admision.contacto 
            			                  WHERE (CASE WHEN ? IS NOT NULL THEN estado != ? 
            			                              ELSE 1 != 1 END))
            	 AND id_contacto NOT IN (SELECT id_contacto 
            			                   FROM admision.contacto 
            			                  WHERE flg_estudiante = ".FLG_ESTUDIANTE."
            					            AND (CASE WHEN ? IS NOT NULL THEN nivel_ingreso != ?
                									  ELSE 1 != 1 END)
    						                OR flg_estudiante = ".FLG_ESTUDIANTE."
    						               AND (CASE WHEN ? IS NOT NULL THEN grado_ingreso != ?
                                                      ELSE 1 != 1 END))
                 AND id_contacto NOT IN (SELECT id_contacto 
            			                   FROM admision.contacto 
            			                  WHERE flg_estudiante = ".FLG_ESTUDIANTE."
            					            AND (CASE WHEN ? IS NOT NULL THEN sede_interes != ?
            							              ELSE 1 != 1 END))
                 
            ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
        $result = $this->db->query($sql, array($estado,$estado,$cod_grupo,$sedeInteres,$sedeInteres,$nivel,$nivel,$grado,$grado,$year,$year,$limit,$estado,$estado,$nivel,$nivel,$grado,$grado,$sedeInteres,$sedeInteres));
        return $result->result();
    }
    
    function getLlamadas($idcontacto){
        $sql = "SELECT CONCAT(initcap(UPPER(sp.ape_pate_pers)),' ',initcap(UPPER(sp.ape_mate_pers)),', ',initcap(INITCAP(sp.nom_persona))) nombrecompleto,
                       (SELECT INITCAP(desc_combo) as desc
                          FROM combo_tipo
                         WHERE grupo = ".COMBO_SEGUIMIENTO."
                           AND valor = l.tipo_llamada::CHARACTER VARYING) desc_tipo_llamada,
                       e.desc_evento,
                       l.observacion,
                       l.fecha_registro
                  FROM admision.evento e,
                       admision.log_llamada l
            INNER JOIN persona sp
                    ON l.id_persona_registro = nid_persona
                 WHERE e.id_evento = l.id_evento
                   AND l.id_contacto = ?
              ORDER BY l.fecha_registro DESC";
        $result = $this->db->query($sql,array($idcontacto));
        return $result->result();
    }
    
    function insertarLlamada($data){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.log_llamada", $data);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MC-001)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_INSERT_SUCCESS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getSedeInteresByYear($year){
        $sql = "SELECT array_to_string(array_agg(s.sede_interes), ',') as sedes
			      FROM (SELECT sede_interes
        				 FROM admision.contacto
        			    WHERE id_contacto IN (SELECT id_contacto
        							            FROM admision.contacto
        							           WHERE sede_interes IS NOT NULL
        							             AND EXTRACT(year FROM fecha_registro) = ?)
        							        GROUP BY sede_interes
        							        ORDER BY sede_interes) s";
        $result = $this->db->query($sql,array($year));
        return $result->row()->sedes;
    }
    
    function getSedeByIdSede($sede){
        $sql= "SELECT nid_sede, desc_sede
                 FROM sede
                WHERE nid_sede IN ?
             ORDER BY desc_sede";
        $result = $this->db->query($sql,array($sede));
        return $result->result();
    }
     
     function getEventosFuturos(){
         $sql = "SELECT id_evento,
            		    desc_evento,
                        fecha_realizar 
        		   FROM admision.evento
        		  WHERE (fecha_realizar >= now()::date OR fecha_realizar IS NULL)
                    AND estado <> '".EVENTO_ANULADO."'
        	   ORDER BY fecha_Realizar";
         $result = $this->db->query($sql);
         return $result->result();
     }
     
     function getGradoNivelByYear($year,$sedeInteres){
         $sql = "SELECT array_to_string(array_agg(s.id_grado_nivel), ',') as id_grado_nivel
                   FROM (SELECT CONCAT(grado_ingreso,'_',nivel_ingreso) id_grado_nivel 
            			   FROM admision.contacto 
            			  WHERE flg_estudiante = ".FLG_ESTUDIANTE."
            			    AND EXTRACT (year FROM fecha_registro) = ?
            			    AND (CASE WHEN ? IS NOT NULL THEN sede_interes = ?
                                                         ELSE 1 = 1 END)
                       GROUP BY nivel_ingreso, grado_ingreso
                       ORDER BY nivel_ingreso,grado_ingreso) s";
         $result = $this->db->query($sql,array($year,$sedeInteres,$sedeInteres));
         return $result->row()->id_grado_nivel;
     }
     
     function getGradoNivelByIDs($gradoNivel){
         $sql = "SELECT CONCAT(a.nid_grado,'_',a.nid_nivel) id_grado_nivel,
			            CONCAT(g.abvr,' ',n.abvr) descrip
                   FROM aula a
              LEFT JOIN nivel n
                     ON a.nid_nivel = n.nid_nivel
              LEFT JOIN grado  g
                     ON a.nid_grado = g.nid_grado
                  WHERE CONCAT(a.nid_grado,'_',a.nid_nivel) IN ?
               GROUP BY id_grado_nivel, descrip,n.nid_nivel,a.nid_grado
               ORDER BY n.nid_nivel,a.nid_grado";
         $result = $this->db->query($sql,array($gradoNivel));
         return $result->result();
     }
     
     function getFamiliasByEstadoBusqueda($txt,$estado, $limit = null, $idrol, $sede, $idpersona, $cod_grupo = 0, $tipoproceso = NULL){
         $sql="SELECT   CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                        CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                        nombres as nombres,
            	        id_contacto,
            	        correo,
            	        telefono_celular,
            	        parentesco,
                     (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = id_persona_registro) persona_registro,
                         (SELECT COUNT(*) 
                            FROM admision.invitados i 
                      INNER JOIN admision.evento e
                              ON i.id_evento = e.id_evento
    			           WHERE i.id_contacto = c.id_contacto
                             AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."') as invitados,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                             AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                         (SELECT INITCAP(desc_combo) as desc
                            FROM combo_tipo
                           WHERE grupo = ".COMBO_PARENTEZCO."
                             AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                         flg_estudiante,
                         cod_grupo,
                         CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
    			              ELSE (SELECT s.desc_sede
                                      FROM sede s
                                     WHERE s.nid_sede = sede_interes) END AS desc_sede,
                         CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                              ELSE (SELECT n.desc_nivel
    				                  FROM nivel n
    			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                         CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                              ELSE (SELECT g.abvr
    				                  FROM grado g
    			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                        tipo_proceso
                   FROM admision.contacto c
                  WHERE (cod_grupo IN (SELECT cod_grupo
        	            	             FROM admision.contacto c1
        		 	                    WHERE ((CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
					   			                        ELSE 1 = 1 END)
                         		          AND cod_grupo > ?
					   			          AND CASE WHEN ((? = ".ID_ROL_SECRETARIA." OR ? = ".ID_ROL_SUBDIRECTOR.") AND ? IS NULL) THEN (c1.id_persona_registro = ? OR c1.sede_interes = ?)
					   			                   ELSE 1 = 1 END)
					                      AND CASE WHEN flg_estudiante = ".FLG_ESTUDIANTE."
					                      	 	   THEN ((CASE WHEN ? = 2 THEN estado IN (2,10)
					                      		                          ELSE estado = ? END)
												         AND (CASE WHEN ? IS NOT NULL THEN ? = ANY(tipo_proceso::int[])
																 ELSE 1 = 1 END))
					                      	 	   ELSE (cod_grupo IN (SELECT cod_grupo 
				                                                         FROM (SELECT count(1), cod_grupo 
				                                                                 FROM admision.contacto 
				                                                                WHERE (CASE WHEN ? = 2 THEN estado IN (2,10)
					                      		                          			        ELSE estado = ? END)
				                                                                  AND flg_estudiante = ".FLG_ESTUDIANTE."
		                                                                  	   	  AND (CASE WHEN ? IS NOT NULL THEN ? = ANY(tipo_proceso::int[])
																 											   ELSE 1 = 1 END)
				                                                             GROUP BY cod_grupo
					   			                                             ORDER BY cod_grupo) AS C 
				                                                        WHERE count>0)) END
					   		         GROUP BY cod_grupo
					   		         ORDER BY cod_grupo
				                        LIMIT ?)
            	    AND id_contacto NOT IN (SELECT id_contacto
            		   	                      FROM admision.contacto
            		   	                     WHERE CASE WHEN ? IS NULL 
                                                        THEN (CASE WHEN ? = 2 THEN estado NOT IN (2,10)
					                      		                          ELSE estado != ? END)
											       		ELSE (tipo_proceso IS NULL
														   OR ? != ALL(tipo_proceso::int[])) END
					   	                       AND flg_estudiante = ".FLG_ESTUDIANTE.") )
               ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
         $result = $this->db->query($sql, array($txt,'%'.$txt.'%',$cod_grupo,$idrol, $idrol, $tipoproceso,$idpersona, $sede,$estado,$estado,$tipoproceso,$tipoproceso,$estado,$estado,$tipoproceso,$tipoproceso,$limit,$tipoproceso,$estado,$estado,$tipoproceso));
         return $result->result();
     }
     
     function insertInvitados($data){
         $rpt['error']    = EXIT_ERROR;
         $rpt['msj']      = MSJ_ERROR;
         try{
             $this->db->insert('admision.invitados', $data);
             if($this->db->affected_rows() != 1) {
                 throw new Exception('(MA-001)');
             }
             $rpt['error']  = EXIT_SUCCESS;
             $rpt['msj']    = MSJ_INSERT_SUCCESS;
         } catch(Exception $e){
             $this->db->trans_rollback();
             $rpt['msj'] = $e->getMessage();
         }
         return $rpt;
     }
     
     function getDetalleContacto($idcontacto, $idEvento = 0) {
         $sql = "SELECT c.nombres,
                        c.ape_paterno,
                        c.ape_materno,
                        c.sexo,
                        c.sede_interes,
                        CASE WHEN c.grado_ingreso IS NOT NULL THEN CONCAT(c.grado_ingreso,'_',c.nivel_ingreso)
                             ELSE '' END AS gradonivel,
                        c.colegio_procedencia,
                        c.fecha_nacimiento,
                        c.tipo_documento,
                        c.nro_documento,
                        c.obser_solicitud,
                        i.estado_eval,
                        i.id_entrevistador
                   FROM admision.contacto c LEFT JOIN admision.invitados i ON (c.id_contacto = i.id_contacto AND i.id_evento = ?)
                  WHERE c.id_contacto = ?";
         $result = $this->db->query($sql,array($idEvento, $idcontacto));
         return $result->row_array();
     }
     
     function insertarHermano($data){
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
     
     function getDetalleContactoCard($idcontacto){
         $sql = "SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                        CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
                             ELSE (SELECT s.desc_sede
                                     FROM sede s
                                    WHERE s.nid_sede = sede_interes) END AS desc_sede,
                        CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                             ELSE (SELECT n.desc_nivel
                                     FROM nivel n
                                    WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                        CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                             ELSE (SELECT g.abvr
    				                  FROM grado g
    			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado
                   FROM admision.contacto
                  WHERE id_contacto = ?";
         $result = $this->db->query($sql,array($idcontacto));
         return $result->row_array();
     }
     
     function getCorreoFamiliares($txt,$estado, $limit = null){
         $sql="SELECT correo
                   FROM admision.contacto
                  WHERE (cod_grupo in (SELECT cod_grupo
        	         		             FROM admision.contacto
        		 	                    WHERE ((CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
								                     ELSE 1 = 1 END)
								          AND estado = ?
								          AND flg_estudiante = ".FLG_ESTUDIANTE.")
							         GROUP BY cod_grupo
								     ORDER BY cod_grupo)
            	    AND id_contacto NOT IN (SELECT id_contacto
            			                      FROM admision.contacto
            			                     WHERE estado != ?))
		             OR (cod_grupo IN (SELECT COD_GRUPO
			                            FROM admision.contacto
				                       WHERE cod_grupo IN (SELECT cod_grupo
			                                                 FROM admision.contacto
				                                            WHERE flg_estudiante = ".FLG_FAMILIAR."
				                                              AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
                                                                        ELSE 1 = 1 END)
				                                              AND cod_grupo IN (SELECT cod_grupo
				                                                                  FROM (SELECT count(1), cod_grupo
				                                                                          FROM admision.contacto
				                                                                         WHERE estado = ?
				                                                                           AND flg_estudiante = ".FLG_ESTUDIANTE."
				                                                                      GROUP BY cod_grupo
								                                                      ORDER BY cod_grupo) AS C
				                                                                 WHERE COUNT>0))
									GROUP BY cod_grupo
								    ORDER BY cod_grupo)
                                	     AND cod_grupo NOT IN (SELECT cod_grupo
                    				                             FROM admision.contacto
                    				                            WHERE estado != ?
                    				                              AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
                    	                                                    ELSE 1 = 1 END))
                    		             AND id_contacto NOT IN (SELECT id_contacto
                                			                       FROM admision.contacto
                                			                      WHERE estado != ?))
               ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
         $result = $this->db->query($sql, array($txt,'%'.$txt.'%',$estado,$estado,$txt,'%'.$txt.'%',$estado,$estado,$txt,'%'.$txt.'%',$estado));
         return $result->result();
     }
     
     function getCorreoFamiliasByEstadoBusqueda($txt,$estado, $limit = null){
         $sql="SELECT correo
                   FROM admision.contacto
                  WHERE (cod_grupo in (SELECT cod_grupo
        	            	             FROM admision.contacto
        		 	                    WHERE ((CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
								                     ELSE 1 = 1 END)
								          AND estado = ?
								          AND flg_estudiante = ".FLG_ESTUDIANTE.")
							         GROUP BY cod_grupo
								     ORDER BY cod_grupo
								        LIMIT ?)
            	    AND id_contacto NOT IN (SELECT id_contacto
            			                      FROM admision.contacto
            			                     WHERE estado != ?))
		             OR (cod_grupo IN (SELECT COD_GRUPO
			                             FROM admision.contacto
				                        WHERE cod_grupo IN (SELECT cod_grupo
			                                                  FROM admision.contacto
				                                             WHERE flg_estudiante = ".FLG_FAMILIAR."
				                                               AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
                                                                         ELSE 1 = 1 END)
				                                               AND cod_grupo IN (SELECT cod_grupo
				                                                                   FROM (SELECT count(1), cod_grupo
				                                                                           FROM admision.contacto
				                                                                          WHERE estado = ?
				                                                                            AND flg_estudiante = ".FLG_ESTUDIANTE."
				                                                                       GROUP BY cod_grupo
								                                                       ORDER BY cod_grupo) AS C
				                                                                  WHERE COUNT>0))
									 GROUP BY cod_grupo
								     ORDER BY cod_grupo
                                        LIMIT ?)
                                	      AND cod_grupo NOT IN (SELECT cod_grupo
                    				                              FROM admision.contacto
                    				                             WHERE estado != ?
                    				                               AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(CONCAT(ape_paterno,' ',ape_materno,', ',nombres))) LIKE UNACCENT(UPPER(?))
                    	                                                     ELSE 1 = 1 END))
                    		              AND id_contacto NOT IN (SELECT id_contacto
                                                                    FROM admision.contacto
                                		 	                       WHERE estado != ?))
               ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
         $result = $this->db->query($sql, array($txt,'%'.$txt.'%',$estado,$limit,$estado,$txt,'%'.$txt.'%',$estado,$limit,$estado,$txt,'%'.$txt.'%',$estado));
         return $result->result();
     }
     
     function getCorreosFamiliasByEstadoYear($estado, $year, $sedeInteres, $nivel, $grado, $limit=null){
         $sql="SELECT correo
                FROM admision.contacto
               WHERE cod_grupo IN (SELECT cod_grupo
            	         		     FROM admision.contacto
            		 	            WHERE estado = ?
                                      AND flg_estudiante = ".FLG_ESTUDIANTE."
                                      AND (CASE WHEN ? IS NOT NULL THEN sede_interes = ?
                                                ELSE 1 = 1 END)
                                      AND (CASE WHEN ? IS NOT NULL THEN nivel_ingreso = ?
                                                ELSE 1 = 1 END)
                                      AND (CASE WHEN ? IS NOT NULL THEN grado_ingreso = ?
                                                ELSE 1 = 1 END)
            		 	         GROUP BY cod_grupo
                                 ORDER BY cod_grupo
                                    LIMIT ?)
            	 AND id_contacto NOT IN (SELECT id_contacto
            			                   FROM admision.contacto
            			                  WHERE estado != ?)
            	 AND id_contacto NOT IN (SELECT id_contacto
            			                   FROM admision.contacto
            			                  WHERE flg_estudiante = ".FLG_ESTUDIANTE."
            					            AND (CASE WHEN ? IS NOT NULL THEN nivel_ingreso != ?
                									  ELSE 1 != 1 END)
    						                AND (CASE WHEN ? IS NOT NULL THEN grado_ingreso != ?
                                                      ELSE 1 != 1 END))
                 AND id_contacto NOT IN (SELECT id_contacto
            			                   FROM admision.contacto
            			                  WHERE flg_estudiante = ".FLG_ESTUDIANTE."
            					            AND (CASE WHEN ? IS NOT NULL THEN sede_interes != ?
            							              ELSE 1 != 1 END))
                 AND (CASE WHEN ? IS NOT NULL THEN (EXTRACT (year FROM fecha_registro)) = ?
                                                ELSE 1 = 1 END)
            	ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
         $result = $this->db->query($sql, array($estado,$sedeInteres,$sedeInteres,$nivel,$nivel,$grado,$grado,$limit,$estado,$nivel,$nivel,$grado,$grado,$sedeInteres,$sedeInteres,$year,$year));
         return $result->result();
     }
     
     function getFamiliaInvitar($idContacto, $idEvento){
         $sql ="SELECT c.id_contacto,
                       CONCAT(UPPER(ape_paterno),' ',UPPER(ape_materno),', ',INITCAP(SPLIT_PART( nombres, ' ' , 1 ))) nombrecompleto,
                       c.telefono_celular,
                       c.correo,
                       c.flg_estudiante,
                       CASE WHEN flg_estudiante = ".FLG_ESTUDIANTE." THEN 'POSTULANTE'
                            ELSE (SELECT UPPER(desc_combo)
                                    FROM combo_tipo
                                   WHERE grupo = ".COMBO_PARENTEZCO."
                                     AND valor = parentesco::CHARACTER VARYING ) END AS parentesco,
                       i.id_hora_cita,
                       i.opcion,
                       i.razon_inasistencia
                  FROM admision.contacto c LEFT JOIN admision.invitados i
                    ON (c.id_contacto   = i.id_contacto 
                        AND i.id_evento = ?)
                 WHERE c.cod_grupo = (SELECT cod_grupo 
            	                        FROM admision.contacto
            	                       WHERE id_contacto = ?)
              ORDER BY c.flg_estudiante, parentesco, id_contacto";
         $result = $this->db->query($sql,array($idEvento,$idContacto));
         return $result->result();
     }
     
     function getFamiliasByFechas($fechaInicio, $fechaFin){
         $sql="SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                     id_contacto,
                     correo,
                     telefono_celular,
                     parentesco,
                     (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = id_persona_registro) persona_registro,
                     (SELECT COUNT(*) 
                        FROM admision.invitados i 
                  INNER JOIN admision.evento e
                          ON i.id_evento = e.id_evento
			           WHERE i.id_contacto = c.id_contacto
                         AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."') as invitados,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     flg_estudiante,
                     cod_grupo,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     tipo_proceso
                FROM admision.contacto c
               WHERE cod_grupo IN (SELECT cod_grupo
        	         		         FROM admision.contacto
        		 	                WHERE fecha_registro BETWEEN ? AND ?
                                 GROUP BY cod_grupo
                                 ORDER BY cod_grupo)
            ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
         $result = $this->db->query($sql,array($fechaInicio, $fechaFin));
         return $result->result();
     }
     
     function getFamiliasByCanalCom($canalCom, $fechaInicio, $fechaFin){
         $sql="SELECT CONCAT(initcap(ape_paterno),' ',initcap(ape_materno),', ',initcap(nombres)) as nombrecompleto,
                      CONCAT(ape_paterno,' ',ape_materno) as apellidos,
                     nombres as nombres,
                     id_contacto,
                     correo,
                     telefono_celular,
                     parentesco,
                     (SELECT INITCAP(CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona,' ',1)))
                          FROM persona p
                         WHERE p.nid_persona = id_persona_registro) persona_registro,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_PARENTEZCO."
                         AND valor = parentesco::CHARACTER VARYING) AS desc_parentesco,
                     (SELECT INITCAP(desc_combo) as desc
                        FROM combo_tipo
                       WHERE grupo = ".COMBO_CANAL_COMUNICACION."
                         AND valor = canal_comunicacion::CHARACTER VARYING) AS canal_comunicacion,
                     (SELECT COUNT(*) 
                        FROM admision.invitados i 
                  INNER JOIN admision.evento e
                          ON i.id_evento = e.id_evento
			           WHERE i.id_contacto = id_contacto
                         AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."') as invitados,
                     flg_estudiante,
                     cod_grupo,
                     CASE WHEN (sede_interes = ".SEDE_POR_DEFINIR." OR sede_interes IS NULL) THEN 'Por definir'
			              ELSE (SELECT s.desc_sede
                                  FROM sede s
                                 WHERE s.nid_sede = sede_interes) END AS desc_sede,
                     CASE WHEN (nivel_ingreso = ".SEDE_POR_DEFINIR." OR nivel_ingreso IS NULL) THEN '-'
                          ELSE (SELECT n.desc_nivel
				                  FROM nivel n
			                     WHERE n.nid_nivel = nivel_ingreso) END AS desc_nivel,
                     CASE WHEN (grado_ingreso = ".SEDE_POR_DEFINIR." OR grado_ingreso IS NULL) THEN '-'
                          ELSE (SELECT g.abvr
				                  FROM grado g
			                     WHERE g.nid_grado = grado_ingreso) END AS abvr_grado,
                     tipo_proceso
                FROM admision.contacto
               WHERE CASE WHEN ? IS NOT NULL THEN cod_grupo IN (SELECT cod_grupo
                                    	         		          FROM admision.contacto
                                    		 	                 WHERE canal_comunicacion = ?
                                                              GROUP BY cod_grupo
                                                              ORDER BY cod_grupo)
                     ELSE 1 = 1 END    
                         
                AND CASE WHEN (? IS NOT NULL OR ? IS NOT NULL) THEN cod_grupo IN (SELECT cod_grupo
                                                        	         		          FROM admision.contacto
                                                        		 	                 WHERE fecha_registro BETWEEN COALESCE(?, now()) AND COALESCE(?, now())
                                                                                  GROUP BY cod_grupo
                                                                                  ORDER BY cod_grupo)         
                      ELSE  1 = 1 END   
            ORDER BY cod_grupo, flg_estudiante, parentesco, nombres";
         $result = $this->db->query($sql,array($canalCom, $canalCom, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin));
         return $result->result();
     }
     
     function countInvitadosEventoByGrupo($codgrupo){
         $sql = "SELECT COUNT(*) count
			       FROM admision.invitados i 
             INNER JOIN admision.evento e
                     ON i.id_evento = e.id_evento
			      WHERE i.id_contacto IN (SELECT id_contacto
			                                FROM admision.contacto
			                               WHERE cod_grupo = ?)
                    AND e.fecha_realizar >= now()::date
                         AND e.estado = '".EVENTO_PENDIENTE."'";
         $result = $this->db->query($sql,array($codgrupo));
         return $result->row()->count;  
     }
     
     function getEventosFuturosGrupoInvitado($codGrupo){
         $sql = "SELECT e.id_evento,
            		    e.desc_evento,
                        e.fecha_realizar,
                        CASE WHEN (SELECT COUNT (1)
                                     FROM admision.contacto  c,
                                          admision.invitados i
                                    WHERE c.id_contacto = i.id_contacto
                                      AND i.id_evento   = e.id_evento
                                      AND c.cod_grupo   = ?) > 0 THEN '(X)'
                             ELSE '' END AS invitado
        		   FROM admision.evento e
        		  WHERE fecha_realizar >= now()::date
                    AND e.estado <> '".EVENTO_ANULADO."'
        	   ORDER BY fecha_Realizar";
         $result = $this->db->query($sql, array($codGrupo));
         return $result->result();
     }
     
     function countInvitadosEventoByGrupoValidate($codgrupo){
         $sql = "SELECT COUNT(*) count
			       FROM admision.invitados i,
                        admision.evento e
			      WHERE i.id_evento = e.id_evento 
                    AND i.id_contacto IN (SELECT id_contacto
			                                FROM admision.contacto
			                               WHERE cod_grupo = ?)";
         $result = $this->db->query($sql,array($codgrupo));
         return $result->row()->count;
     }
     
     function countSeguimientoByGrupoValidate($codgrupo){
         $sql = "SELECT COUNT(*) count
			       FROM admision.contacto c,
                        admision.log_llamada l
			      WHERE l.id_contacto = c.id_contacto
                    AND c.id_contacto IN (SELECT id_contacto
			                                FROM admision.contacto
			                               WHERE cod_grupo = ?)";
         $result = $this->db->query($sql,array($codgrupo));
         return $result->row()->count;
     }
     
     function deleteFamiliaByCodGrupo($codgrupo){
         $rpt['error']    = EXIT_ERROR;
         $rpt['msj']      = MSJ_ERROR;
         try{
             $this->db->where('cod_grupo', $codgrupo);
             $this->db->delete('admision.contacto');
             $rpt['error']    = EXIT_SUCCESS;
             $rpt['msj']      = MSJ_DEL;
         }catch(Exception $e){
             $rpt['msj'] = $e->getMessage();
         }
         return $rpt;
     }
     
     function validateDatosCompletos($idContacto){
         $sql = "SELECT nombres,
                        ape_paterno,
                        ape_materno,
                        nivel_ingreso,
                        grado_ingreso
                   FROM admision.contacto
                  WHERE id_contacto = ?";
         $result = $this->db->query($sql,array($idContacto));
         return $result->row_array();
     }
     
     function updateContacto($arrayUpdate, $idContacto){
         $rpt['error']    = EXIT_ERROR;
         $rpt['msj']      = MSJ_ERROR;
         try{
             $this->db->where("id_contacto", $idContacto);
             $this->db->update("admision.contacto", $arrayUpdate);
     
             if($this->db->affected_rows() != 1){
                 throw new Exception('(MC-001)');
             }
             $rpt['error']    = EXIT_SUCCESS;
             $rpt['msj']      = MSJ_UPT;
         }catch(Exception $e){
             $rpt['msj'] = $e->getMessage();
         }
         return $rpt;
     }
    
    function procesoMatriculaVerano($idContacto, $codGrupo, $year){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->trans_begin();
            $sql = "SELECT * FROM admision.migrar_admision_matricula_verano(?, ?, ?) resultado";
            $result = $this->db->query($sql, array($codGrupo, $idContacto, $year));
            $resultado = explode('|', $result->row()->resultado);
            $this->db->trans_commit();
//             _log("PRIMERO: ".print_r($resultado, true));
            if($resultado[0] == "OK") {
            	$this->db->trans_commit();
                $rpt['error'] = EXIT_SUCCESS;
                $rpt['msj']   = MSJ_UPT;
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
}