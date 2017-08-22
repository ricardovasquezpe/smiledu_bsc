<?php
//LAST-CODE: MU-002
class M_alumno extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function getAlumnosByNombre($nombre, $letra, $sede, $offset, $limit = null, $year, $nivel, $grado,$idaula){
	    $sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers)) AS apellidos,
	                   INITCAP(p.nom_persona) AS nombres,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
				       CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
	                        ELSE '-' END AS desc_aula,
		               CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
	                        ELSE '-' END AS desc_sede,
	                   CASE WHEN n.desc_nivel IS NOT NULL THEN n.desc_nivel
	                        ELSE '-' END AS desc_nivel,
	                   CASE WHEN g.desc_grado IS NOT NULL THEN g.desc_grado
	                        ELSE '-' END AS desc_grado,
				       CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
	                        ELSE '-' END AS desc_grado_nivel,
		               CASE WHEN d.cod_familia IS NOT NULL THEN d.cod_familia
	                        ELSE '-' END AS cod_familia,
		               CASE WHEN d.cod_alumno IS NOT NULL THEN d.cod_alumno
	                        ELSE '-' END AS cod_alumno,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   d.estado,
	                   a.nid_aula,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar   = f.id_familiar
	                       AND ff.cod_familiar  = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT f.telf_celular
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar   = f.id_familiar
	                       AND ff.cod_familiar  = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS telefonoresponsable,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('POR PAGAR'), UPPER('PAGADO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as pagado,
    			       (SELECT COUNT(1)
    			          FROM pagos.movimiento
    			         WHERE estado IN ('".ESTADO_VENCIDO."')
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as por_pagar,
	                   p.flg_acti,
	                   d.pais,
	    		       d.cod_alumno_temp,
    			       d.year_ingreso,
    			       rank() OVER (ORDER BY CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona) ASC) AS rnk
				  FROM persona p
			 LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona and (pa.year_academico = (SELECT pa2.year_academico
												  FROM persona_x_aula pa2
												 WHERE pa2.__id_persona = p.nid_persona
											      ORDER BY pa2.year_academico DESC
												 LIMIT 1))
		     LEFT JOIN aula a
				    ON a.nid_aula    = pa. __id_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
	         LEFT JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
				 WHERE d.cod_alumno IS NOT NULL
				   AND (UNACCENT(UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona))) LIKE UNACCENT(UPPER(?)) OR
				        UPPER(p.nro_documento) LIKE ? OR
				        UPPER(d.cod_alumno)    LIKE ?)
	               AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(p.ape_pate_pers)) LIKE ?
	                          ELSE 1 = 1 END)
	               AND (CASE WHEN ? IS NOT NULL THEN d.year_ingreso = ?
	                          ELSE 1 = 1 END)
    			   AND (CASE WHEN (? IS NOT NULL AND d.estado != '".ALUMNO_PREREGISTRO."') 
	                         THEN s.nid_sede = ?
	                         ELSE (CASE WHEN (? IS NOT NULL AND d.estado = '".ALUMNO_PREREGISTRO."')
            				            THEN ? = d.id_sede_ingreso
            				            ELSE 1 = 1 END ) END)
	               AND (CASE WHEN ? IS NOT NULL THEN n.nid_nivel = ?
	                          ELSE 1 = 1 END)
	               AND (CASE WHEN ? IS NOT NULL THEN g.nid_grado = ?
	                          ELSE 1 = 1 END)
	               AND (CASE WHEN ? IS NOT NULL THEN a.nid_aula = ?
	                          ELSE 1 = 1 END)
              GROUP BY p.nid_persona, a.desc_aula, s.desc_sede, n.desc_nivel, g.desc_grado, g.abvr, n.abvr, d.cod_familia, d.cod_alumno, d.estado, a.nid_aula, d.nid_persona, pa.year_academico
		        OFFSET ? LIMIT ?";
	    $result = $this->db->query($sql, array("%".$nombre."%", "%".$nombre."%", "%".$nombre."%", $letra, $letra."%",$year,$year,$sede,$sede,$sede, $sede,$nivel,$nivel,$grado,$grado,$idaula,$idaula, $offset, $limit));
	    return $result->result();
	}
	
	function getAlumnosByCombos($aula, $letra){
	    $sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers)) AS apellidos,
	                   INITCAP(p.nom_persona) AS nombres,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
				       CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
	                        ELSE '-' END AS desc_aula,
		               CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
	                        ELSE '-' END AS desc_sede,
	                   CASE WHEN n.desc_nivel IS NOT NULL THEN n.desc_nivel
	                        ELSE '-' END AS desc_nivel,
	                   CASE WHEN g.desc_grado IS NOT NULL THEN g.desc_grado
	                        ELSE '-' END AS desc_grado,
				       CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
	                        ELSE '-' END AS desc_grado,
		               CASE WHEN d.cod_familia IS NOT NULL THEN d.cod_familia
	                        ELSE '-' END AS cod_familia,
		               CASE WHEN d.cod_alumno IS NOT NULL THEN d.cod_alumno
	                        ELSE '-' END AS cod_alumno,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   d.estado,
	                   a.nid_aula,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT f.telf_celular
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS telefonoresponsable,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('POR PAGAR'), UPPER('PAGADO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as pagado,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('VENCIDO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as por_pagar,
	                   p.flg_acti,
	                   d.pais,
	    		       d.cod_alumno_temp,
    			       d.year_ingreso
				  FROM persona p
			 LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona and (pa.year_academico = (SELECT pa2.year_academico
												  FROM persona_x_aula pa2
												 WHERE pa2.__id_persona = p.nid_persona
											      ORDER BY pa2.year_academico DESC
												 LIMIT 1))
		     LEFT JOIN aula a
				    ON a.nid_aula = pa. __id_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
	        LEFT JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
				 WHERE d.cod_alumno IS NOT NULL
	               AND a.nid_aula = ?
				   AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(p.ape_pate_pers)) LIKE ?
	                          ELSE 1 = 1 END)
			ORDER BY apellidos";
	    $result = $this->db->query($sql, array($aula, $letra, $letra."%"));
	    return $result->result();
	}
	
	function getAlumnosByNombreAula($nombre, $aula, $letra) {
	    $sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',split_part( p.nom_persona, ' ' , 1 ))) AS nombrecompleto,
	                   INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers)) AS apellidos,
	                   INITCAP(p.nom_persona) AS nombres,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
				       CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
	                        ELSE '-' END AS desc_aula,
		               CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
	                        ELSE '-' END AS desc_sede,
	                   CASE WHEN n.desc_nivel IS NOT NULL THEN n.desc_nivel
	                        ELSE '-' END AS desc_nivel,
	                   CASE WHEN g.desc_grado IS NOT NULL THEN g.desc_grado
	                        ELSE '-' END AS desc_grado,
				       CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
	                        ELSE '-' END AS desc_grado,
		               CASE WHEN d.cod_familia IS NOT NULL THEN d.cod_familia
	                        ELSE '-' END AS cod_familia,
		               CASE WHEN d.cod_alumno IS NOT NULL THEN d.cod_alumno
	                        ELSE '-' END AS cod_alumno,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   d.estado,
	                   a.nid_aula,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT f.telf_celular
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar = f.id_familiar
	                       AND ff.cod_familiar = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS telefonoresponsable,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('POR PAGAR'), UPPER('PAGADO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as pagado,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('VENCIDO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as por_pagar,
	                   p.flg_acti,
	    		       d.pais,
	    		       d.cod_alumno_temp
				  FROM persona p
			 LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona
		     LEFT JOIN aula a
				    ON a.nid_aula    = pa. __id_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
	         LEFT JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
				 WHERE d.cod_alumno IS NOT NULL
				   AND a.nid_aula = ?
	               AND (UPPER(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona)) LIKE UPPER(?) OR
				        UPPER(p.nro_documento) LIKE UPPER(?) OR
				        UPPER(d.cod_alumno) LIKE UPPER(?))
	               AND (CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(p.ape_pate_pers)) LIKE ?
	                          ELSE 1 = 1 END)
				   ORDER BY nombrecompleto";
	    $result = $this->db->query($sql, array($aula, "%".$nombre."%", "%".$nombre."%", "%".$nombre."%", $letra, $letra."%"));
	    return $result->result();
	}
	
	function getAlumno($id_alumno){
	    $sql = "SELECT (p.nom_persona)   AS nom_persona,
	                   (p.ape_pate_pers) AS ape_pate_pers,
	                   (p.ape_mate_pers) AS ape_mate_pers,
	                   p.fec_naci,
	                   p.nro_documento,
	                   p.telf_pers,
	                   p.correo_pers,
	                   p.sexo,
	                   d.cod_familia,
	                   d.codigo_ugel,
	                   d.cod_banco,
	                   d.total_hermano,
	                   d.nro_hermano,
	                   d.colegio_procedencia,
	                   d.ubigeo,
	                   d.religion,
	                   d.observacion,
	                   d.year_ingreso,
	                   d.id_grado_ingreso,
	                   d.id_nivel_ingreso,
	                   d.id_sede_ingreso,
	                   p.estado_civil,
	                   d.pais,
	                   d.ubigeo,
	                   d.estado,
	                   d.cod_familia,
	                   p.tipo_documento,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona
	              FROM persona p
	         LEFT JOIN sima.detalle_alumno d
	                ON p.nid_persona = d.nid_persona
	             WHERE p.nid_persona = ?";
	    $result = $this->db->query($sql,array($id_alumno));
	    return $result->row_array();
	}
	
	function getFamiliaByCodFam($codFam){
	    $sql = "SELECT INITCAP(CONCAT(ape_paterno,' ',ape_materno)) AS apellidos,
	                   INITCAP(nombres) AS nombres,
	                   f.id_familiar,
	                   f.tipo_doc_identidad,
	                   CASE WHEN nro_doc_identidad <> '' THEN nro_doc_identidad
	                        ELSE '-' END AS nro_doc_identidad,
	                   INITCAP((SELECT c.desc_combo 
        	                      FROM combo_tipo c
        	                     WHERE c.valor = f.tipo_doc_identidad::CHARACTER VARYING
        	                       AND c.grupo = ".COMBO_TIPO_DOC." )) as tipo_doc,
	                   CASE WHEN email1 IS NOT NULL AND email1 <> '' THEN email1
        	                WHEN email2 IS NOT NULL AND email2 <> '' THEN email2
	                        ELSE '-' END AS email,
	                   CASE WHEN ff.flg_apoderado = '1' THEN 'S&iacute;'
	                        WHEN ff.flg_apoderado = '2' THEN 'No'
	                        ELSE '-' END AS apoderado,
	                   ff.flg_apoderado,
	                   CASE WHEN ff.flg_resp_economico = '1' THEN 'S&iacute;'
	                        WHEN ff.flg_resp_economico = '2' THEN 'No'
	                        ELSE '-' END AS resp_economico,
	                   ff.flg_resp_economico,
	                   (SELECT INITCAP(c.desc_combo) 
	                      FROM combo_tipo c
	                     WHERE c.valor = ff.parentesco::CHARACTER VARYING
	                       AND c.grupo = ".COMBO_PARENTEZCO." ) as parentesco,
	                    CASE WHEN f.foto_persona IS NOT NULL THEN f.foto_persona
	                         ELSE 'nouser.svg' END AS foto_persona,
	                   CASE WHEN f.telf_celular IS NOT NULL AND f.telf_celular <> '' THEN f.telf_celular
	                        ELSE '-' END AS telf_celular,
	                   ff.flg_apoderado,
	                   CASE WHEN f.usuario IS NOT NULL THEN 1
	                        ELSE 0 END AS  flg_usuario,
	                   ff.usuario_edusys
				  FROM familiar f,
	                   sima.familiar_x_familia ff
				 WHERE ff.id_familiar  = f.id_familiar
	               AND ff.cod_familiar = ?
	          ORDER BY apellidos";
	    $result = $this->db->query($sql, array($codFam));
	    return $result->result();
	}
	
	function getDocumentosByAlumno($idAlumno){
	    $sql = "SELECT INITCAP(c.desc_combo) AS desc_combo,
                       c.valor,
                       da.fec_recibio,
                       da.fec_registro,
                       CASE WHEN da.flg_recibio = 1 THEN 'checked'
                            ELSE '' END AS flg_recibio
                  FROM sima.documentos_x_alumno da RIGHT JOIN combo_tipo c
                    ON (c.valor = da.id_documento::CHARACTER VARYING AND da.id_alumno = ?)
                 wHERE c.grupo = ".COMBO_DOCUMENTOS."
                   AND c.valor <> '0'";
	    
	    $result = $this->db->query($sql, array($idAlumno));
	    return $result->result();
	}
	
	function getAllSedesWithoutSedePersona($idPersona){
	    $sql = "SELECT nid_sede,
                       UPPER(desc_sede) as desc_sede
                  FROM sede
	             WHERE nid_sede NOT IN (SELECT a.nid_sede
	                                  FROM aula a,
	                                       persona_x_aula pa
	                                 WHERE a.nid_aula        = pa.__id_aula
	                                   AND pa.year_academico = Extract(year from now())
	                                   AND pa.__id_persona   = ?)
	          ORDER BY desc_sede ASC";
	    $result = $this->db->query($sql, array($idPersona));
	    return $result->result();
	}
	
	function insertAlumno($arrayInsert, $arrayInsert2){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert("persona", $arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-001)');
	        }
	
	        $idAlumno = $this->db->insert_id();
	        $array = array(
	            "nid_rol"     => ID_ROL_ESTUDIANTE,
	            "nid_persona" => $idAlumno,
	            "flg_acti"    => FLG_ACTIVO
	        );
	
	        $arrayInsert2['nid_persona'] = $idAlumno;
	        $this->db->insert("sima.detalle_alumno", $arrayInsert2);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-002)');
	        }
	
	        $rpt['idAlumno'] = $idAlumno;
	        $rpt['error'] = EXIT_SUCCESS;
	        $rpt['msj']   = MSJ_INS;
	
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	
	    return $rpt;
	}
	
	function updateCampoDetalleAlumno($arrayUpdate, $idAlumno, $bd) {
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        if($bd == 1) {//SIMA
	            $this->db->where("nid_persona", $idAlumno);
	            $this->db->update("sima.detalle_alumno", $arrayUpdate);
	        } else if($bd == 2) {//SCHOOWL
	            $this->db->where("nid_persona", $idAlumno);
	            $this->db->update("persona", $arrayUpdate);
	        }
	        
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-003)');
	        }
	        
	        $detaAlumno = $this->getCamposMinimosAlumno($idAlumno);
	        if($detaAlumno['nom_persona'] != null && $detaAlumno['ape_pate_pers'] != null && $detaAlumno['ape_mate_pers'] != null && $detaAlumno['fec_naci'] != null &&
	           $detaAlumno['sexo'] != null && $detaAlumno['nro_documento'] != null && $detaAlumno['estado'] == ALUMNO_DATOS_INCOMPLETOS && $detaAlumno['count_familia'] >= 1
	            && $detaAlumno['id_grado_ingreso'] != null && $detaAlumno['id_nivel_ingreso'] != null && $detaAlumno['id_sede_ingreso'] != null && $detaAlumno['year_ingreso'] != null) {
	            $alumnoUpdate = array("estado" => ALUMNO_PREREGISTRO);
	            $rpta = $this->updateCampoDetalleAlumno($alumnoUpdate, $idAlumno, 1);
	            $rpta['estado'] = "pre-registro";
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getCamposMinimosAlumno($idAlumno) {
	    $sql = "SELECT p.nom_persona,
	                   p.ape_pate_pers,
	                   p.ape_mate_pers,
            	       p.fec_naci,
            	       p.sexo,
            	       p.nro_documento,
	                   da.estado,
	    			   da.id_grado_ingreso,
	    			   da.id_nivel_ingreso,
	    			   da.id_sede_ingreso,
	    		       da.year_ingreso,
	                   (SELECT COUNT(1)
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE f.id_familiar    = ff.id_familiar
	                       AND ff.cod_familiar  = da.cod_familia
	                       AND ff.flg_apoderado = '1'
	                       AND f.usuario IS NOT NULL) AS count_familia
	           FROM persona p,
	               sima.detalle_alumno da
	           WHERE p.nid_persona = ?
	        AND p.nid_persona = da.nid_persona";
	    
	    $result = $this->db->query($sql, array($idAlumno));
	    return $result->row_array();
	}
	
	function editarFamiliar($arrayUpdate, $arrayUpdate_1, $idFamiliar, $codFamilia, $actClave = null){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        if($actClave == null) {
	            $this->db->where("id_familiar", $idFamiliar);
	            $this->db->update("familiar", $arrayUpdate);
	            if($this->db->affected_rows() != 1){
	                throw new Exception('(MA-004)');
	            }
	        }
	        if($codFamilia != null){
	            $this->db->where("id_familiar", $idFamiliar);
	            $this->db->where("cod_familiar", $codFamilia);
	            $this->db->update("sima.familiar_x_familia", $arrayUpdate_1);
	            if($this->db->affected_rows() != 1){
	                throw new Exception('(MA-005)');
	            }
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e)
	    {
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function verificarDOC_Repetido($nro_documento, $idPersona){
	    $sql = "SELECT COUNT(1) cant
	              FROM persona o
	             WHERE nro_documento = ?
	               AND o.nid_persona <>  ?";
	    $result = $this->db->query($sql, array($nro_documento, $idPersona));
	    return $result->row()->cant;
	}
	
	function validateCorreoRepetido($persona, $correo){
	    $sql = "SELECT COUNT(1) AS count
	              FROM persona
	             WHERE (CASE WHEN ? IS NOT NULL THEN nid_persona <> ?
	                         ELSE 1 = 1 END)
	               AND UPPER(correo_pers)       = UPPER(?)";
	    $result = $this->db->query($sql, array($persona, $persona, $correo));
	    return $result->row()->count;
	}
	
	function insertColegio($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	    
	        $this->db->insert("sima.colegios", $arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-006)');
	        }
	        	
	        $rpt['error'] = EXIT_SUCCESS;
	        $rpt['msj']   = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	     
	    return $rpt;
	}

	function getColegiosByName($colegio){
	    $sql = 'SELECT desc_colegio
	              FROM sima.colegios
	             WHERE UPPER(desc_colegio) LIKE UPPER(?)';
	    
	    $result = $this->db->query($sql, array("%".$colegio."%"));
	    return $result->result();
	}
	
	function getCountFamiliaresResponsableEconomico($codFam, $idFamiliar = null){
	    $sql = "SELECT COUNT(1) AS count
	              FROM familiar f,
	                   sima.familiar_x_familia ff
	             WHERE f.id_familiar         = ff.id_familiar
	               AND ff.cod_familiar       = ?
	               AND ff.flg_resp_economico = '1'
	               AND (CASE WHEN  ? IS NOT NULL THEN f.id_familiar <> ?
	                         ELSE 1 = 1 END)";
	    
	    $result = $this->db->query($sql, array($codFam, $idFamiliar, $idFamiliar));
	    return $result->row()->count;
	}
	
	function getCountFamiliaresApoderado($codFam, $idFamiliar = null){
	    $sql = "SELECT COUNT(1) AS count
	              FROM familiar f,
	                   sima.familiar_x_familia ff
	             WHERE f.id_familiar        = ff.id_familiar
	               AND ff.cod_familiar      = ?
	               AND ff.flg_apoderado     = '1'
	               AND (CASE WHEN  ? IS NOT NULL THEN f.id_familiar <> ?
	                         ELSE 1 = 1 END)";
	     
	    $result = $this->db->query($sql, array($codFam, $idFamiliar, $idFamiliar));
	    return $result->row()->count;
	}
	
	function asignarODesagsinarFamiliar($arrayUpdate, $idAlumno){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
            $this->db->where("nid_persona", $idAlumno);
            $this->db->update("sima.detalle_alumno", $arrayUpdate);
        
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-007)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    
	    return $rpt;
	}
	
	function desagsinarFamiliar($idFamiliar, $codFamilia){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where('id_familiar', $idFamiliar);
	        $this->db->where('cod_familiar', $codFamilia);
	        $this->db->delete('sima.familiar_x_familia');
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-008)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_DEL;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function buscarFamilia($nombre, $codFamilia){//busca por nombre del pariente
	    $sql = "SELECT (SELECT INITCAP(CONCAT(p1.ape_pate_pers, ' ', p1.ape_mate_pers))
					      FROM persona p1,
						       sima.detalle_alumno da1
						 WHERE p1.nid_persona = da1.nid_persona
						   AND da1.cod_familia = da.cod_familia LIMIT 1) AS apellidoscompleto,
	                   da.cod_familia
            	  FROM persona p,
            	       sima.detalle_alumno da,
            	       sima.familiar_x_familia fxf,
            	       familiar f
                 WHERE p.nid_persona = da.nid_persona
		   		   AND da.cod_familia = fxf.cod_familiar
		           AND f.id_familiar  = fxf.id_familiar
	               AND (UPPER(CONCAT(f.ape_paterno,' ',f.ape_materno)) LIKE UPPER(?)
	                    OR f.nro_doc_identidad = ?)
	               AND (CASE WHEN ? IS NOT NULL THEN da.cod_familia != ?
	                    ELSE 1 = 1 END)
                   AND da.cod_familia IS NOT NULL
              GROUP BY da.cod_familia, apellidoscompleto
              ORDER BY da.cod_familia";
	    $result = $this->db->query($sql, array("%".$nombre."%", $nombre, $codFamilia, $codFamilia));
	    return $result->result();
	}
	
	function getFamiliarByTipoDoc($nroDoc, $tipoDoc, $idFamiliar = null){
	    $sql = "SELECT INITCAP(CONCAT(f.ape_paterno,' ',f.ape_materno,', ',f.nombres)) AS nombrecompleto,
	                   f.tipo_doc_identidad,
	                   f.nro_doc_identidad,
	                   (SELECT string_agg(ff.cod_familiar,',')
                          FROM sima.familiar_x_familia ff
                         WHERE f.id_familiar = ff.id_familiar) as codfams,
	                   f.id_familiar
				  FROM familiar f LEFT JOIN sima.familiar_x_familia ff
	                   ON f.id_familiar = ff.id_familiar
	             WHERE f.tipo_doc_identidad = ?
	               AND f.nro_doc_identidad  = ? 
	               AND (CASE WHEN  ? IS NOT NULL THEN ff.cod_familiar <> ?
	                         ELSE 1 = 1 END) 
	             LIMIT 1";
	    $result = $this->db->query($sql, array($tipoDoc, $nroDoc, $idFamiliar, $idFamiliar));
	    return $result->row_array();
	}
	
	function getUbicacionAlumno($idPersona){
	    $sql = "SELECT a.nid_sede,
	                   a.nid_aula,
	                   a.nid_grado,
	                   a.nid_nivel
                  FROM aula a,
                       persona_x_aula pa
                 WHERE a.nid_aula        = pa.__id_aula
                   AND pa.year_academico >= Extract(year from now())
                   AND pa.__id_persona   = ?";
	    $result = $this->db->query($sql, array($idPersona));
	    return $result->row_array();
	}
	
	function validateCorreoRepetidoFamiliar($familiar, $correo){
	    $sql = "SELECT COUNT(1) AS count
	              FROM familiar
	             WHERE (CASE WHEN ? IS NOT NULL THEN id_familiar <> ?
	                         ELSE 1 = 1 END)
	               AND (UPPER(email1)       = UPPER(?) OR
	                    UPPER(email2)       = UPPER(?))";
	    $result = $this->db->query($sql, array($familiar, $familiar, $correo, $correo));
	    return $result->row()->count;
	}
	
	function getContinuosCodFam($year){
// 	    $sql = "SELECT CASE WHEN MAX(SUBSTRING(cod_familiar, 2, 4))::INTEGER = ("._YEAR_.")::INTEGER THEN (MAX(SUBSTRING(cod_familiar, 2, LENGTH(cod_familiar)))::integer + 1)::text
//                        ELSE CONCAT(("._YEAR_."), '', '0001') END AS codfam
//                   FROM sima.familiar_x_familia";
	    $sql = "SELECT fun_generar_codigo_familia as codfam FROM fun_generar_codigo_familia(?)";
	    $result = $this->db->query($sql, array($year));
	    return $result->row()->codfam;
	}
	
	function getContinuosCodAlu(){
	    $sql = "SELECT CASE WHEN MAX(SUBSTRING(cod_alumno, 2, 4))::INTEGER = ("._YEAR_.")::INTEGER THEN (MAX(SUBSTRING(cod_alumno, 2, LENGTH(cod_alumno)))::integer + 1)::text
                       ELSE CONCAT(("._YEAR_."), '', '0001') END AS codalu
                  FROM sima.detalle_alumno";
	    $result = $this->db->query($sql);
	    return $result->row()->codalu;
	}
	
	function validateUsuarioRepetido($usuario){
	    $sql = "SELECT COUNT(1) AS count 
	              FROM persona
	             WHERE usuario = ?";
	    $result = $this->db->query($sql, array($usuario));
	    return $result->row()->count;  
	}
	
	function insertFamiliar($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{

    	    $this->db->insert("familiar", $arrayInsert);
    	    if($this->db->affected_rows() != 1) {
    	        throw new Exception('(MA-010)');
    	    }
    	    
    	    $rpt['idFamiliar'] = $this->db->insert_id();
    	    $rpt['error'] = EXIT_SUCCESS;
    	    $rpt['msj']   = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    
	    return $rpt;
	}
	
	function updateCodFamAlumno($arrayUpdate, $idAlumno){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where("nid_persona", $idAlumno);
	        $this->db->update("sima.detalle_alumno", $arrayUpdate);
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-011)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e)
	    {
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function getCountFamiliaresVivas($codFam, $idFamiliar = null){
	    $sql = "SELECT COUNT(1) AS count
	              FROM familiar f,
	                   sima.familiar_x_familia ff
	             WHERE f.id_familiar        = ff.id_familiar
	               AND ff.cod_familiar      = ?
	               AND f.flg_vive           = '1'
	               AND (CASE WHEN  ? IS NOT NULL THEN f.id_familiar <> ?
	                         ELSE 1 = 1 END)";
	
	    $result = $this->db->query($sql, array($codFam, $idFamiliar, $idFamiliar));
	    
	    return $result->row()->count;
	}
	
	function asignarFamiliarAFamilia($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert("sima.familiar_x_familia", $arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-012)');
	        }
	        	
	        $rpt['error'] = EXIT_SUCCESS;
	        $rpt['msj']   = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	     
	    return $rpt;
	}
	
	function buscarFamiliar($nombre, $codfam){
	    $sql = "SELECT INITCAP(CONCAT(f.ape_paterno,' ',f.ape_materno,', ',f.nombres)) AS nombrecompleto,
	                   f.id_familiar
				  FROM familiar f LEFT JOIN sima.familiar_x_familia ff 
	                   ON ff.id_familiar = f.id_familiar
				 WHERE UPPER(CONCAT(f.ape_paterno,' ',f.ape_materno,' ',f.nombres)) LIKE UPPER(?)
	               /*AND (CASE WHEN ff.cod_familiar IS NOT NULL THEN ff.cod_familiar <> ?
	                         ELSE 1 = 1 END)*/
	               AND (CASE WHEN ff.id_familiar IS NOT NULL THEN ff.id_familiar NOT IN (SELECT ff1.id_familiar
	                                                                                      FROM sima.familiar_x_familia ff1
	                                                                                     WHERE ff1.cod_familiar = ?)
	                         ELSE 1 = 1 END)
	              GROUP BY f.id_familiar";
	    $result = $this->db->query($sql, array("%".$nombre."%", $codfam, $codfam));
	    return $result->result();
	}
	
    function insertFamilia($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert("persona", $arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-013)');
	        }
	
	        $rpt['error'] = EXIT_SUCCESS;
	        $rpt['msj']   = MSJ_INS;
	
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	
	    return $rpt;
	}
	
	function getFamiliarById($idFamiliar){
	    $sql = "SELECT f.ape_paterno, 
                       f.ape_materno,
                       f.nombres, 
                       f.email1,
                       f.email2,
                       f.flg_vive, 
                       f.fec_naci,
                       f.nacionalidad,
                       f.tipo_doc_identidad,
                       f.nro_doc_identidad,
                       f.estado_civil,
                       f.idioma, 
                       f.nivel_instruccion, 
                       f.flg_ex_alumno, 
                       f.colegio_egreso,
                       f.religion, 
                       f.ocupacion, 
                       f.centro_trabajo, 
                       f.direccion_trabajo,
                       f.situacion_laboral,
                       f.sueldo,
                       f.cargo,
                       ff.flg_resp_economico, 
                       f.year_egreso, 
                       f.direccion_hogar, 
                       f.refer_domicilio, 
                       ff.flg_apoderado,
                       f.telf_fijo,
                       f.telf_celular,
                       f.telf_trabajo,
                       f.ubigeo_hogar,
                       f.ubigeo_trabajo, 
                       f.id_familiar,
	                   ff.parentesco
	              FROM familiar f,
	                   sima.familiar_x_familia ff
	             WHERE f.id_familiar = ?
	               AND f.id_familiar = ff.id_familiar";
	    
	    $result = $this->db->query($sql, array($idFamiliar));
	    return $result->row_array();
	}
	
	function insertDocumentoAlumno($arrayInsert){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	    
	        $this->db->insert("sima.documentos_x_alumno", $arrayInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MA-014)');
	        }
	        
	        $rpt['error'] = EXIT_SUCCESS;
	        $rpt['msj']   = MSJ_INS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	     
	    return $rpt;
	}
	
	function deleteDocumentoAlumno($idDocumento, $idAlumno){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->where('id_documento', $idDocumento);
	        $this->db->where('id_alumno', $idAlumno);
	        $this->db->delete('sima.documentos_x_alumno');
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-015)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_DEL;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
    function updateDocumentoAlumno($arrayUpdate, $idDocumento, $idAlumno){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
            $this->db->where("id_documento", $idDocumento);
            $this->db->where("id_alumno", $idAlumno);
            $this->db->update("sima.documentos_x_alumno", $arrayUpdate);
        
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-016)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    
	    return $rpt;
	}
	
	function getDetalleApoderado($idFamiliar) {
	    $sql = "SELECT INITCAP(CONCAT(f.ape_paterno,' ',f.ape_materno,', ',f.nombres)) AS nombrecompleto,
	                   CONCAT(INITCAP(SPLIT_PART(nombres, ' ', 1)),' ',ape_paterno,' ',SUBSTRING(ape_materno,1, 1),'.' ) AS nombre_abvr,
	                   INITCAP(SPLIT_PART(nombres, ' ', 1)) AS nombre_solo,
	                   f.email1,
	                   f.email2,
	                   CASE WHEN email1 IS NOT NULL AND email1 <> '' THEN email1
        	                WHEN email2 IS NOT NULL AND email2 <> '' THEN email2
	                        ELSE NULL END AS email_destino,
	                   f.id_familiar,
	                   ff.cod_familiar,
	                   ff.usuario_edusys,
	                   ff.clave_edusys,
	                   ff.cod_familiar
	              FROM familiar f,
	                   sima.familiar_x_familia ff
	             WHERE f.id_familiar    = ff.id_familiar
	               AND f.id_familiar    = ?
	               AND ff.flg_apoderado = '1'
	           LIMIT 1";
	    $result = $this->db->query($sql, array($idFamiliar));
	    return $result->row_array();
	}
    
    function getCountGeneroCuotasIniciales($idEstudiante){
        $sql = "SELECT COUNT(1) cant
                  FROM pagos.movimiento
                 WHERE _id_persona = ?
                   AND _id_concepto = ".CUOTA_INGRESO."";
        $result = $this->db->query($sql,array($idEstudiante));
        return $result->row()->cant;
    }
    
    function SaveCompromisosMovimientos($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            if(count($datos) > 1){
                $data['n_total_mov'] = $this->db->insert_batch("pagos.movimiento",$datos);
            } else {
                $this->db->insert("pagos.movimiento",$datos[1]);
                $data['n_total_mov'] = 1;
            }
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datos)) {
                throw new Exception('No se guardaron los compromisos');
            }
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_INS;
            $data['id_movimiento'] = $this->db->insert_id();
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getCompromisosEstudiante($id_persona, $year, $sede, $nivel, $grado, $arrayCompromisos){
        $sql_beca = "SELECT porcentaje_beca
                       FROM pagos.condicion c
                 INNER JOIN pagos.condicion_x_persona cp ON cp._id_persona = ?
                        AND cp.estado = 'ACTIVO'
                        AND cp._id_condicion = c.id_condicion
                        AND cp.year_uso = ?
                      WHERE c.tipo_condicion = '0'";
        $porcentaje   = $this->db->query($sql_beca,array($id_persona, $year));
        if($porcentaje->num_rows() > 0) {
            $descuento = $porcentaje->row()->porcentaje_beca;
        } else {
            $descuento = null;
        }
        $sql =      "SELECT det.id_detalle_cronograma,
                            cro._id_tipo_cronograma,
                            (INITCAP(det.desc_detalle_crono)) as detalle,
                            det.fecha_vencimiento as fecha_v,
                            det.fecha_descuento as fecha_d,
                            det.flg_beca,
                            CASE WHEN(current_date > det.fecha_descuento::timestamp::date) 
                                 THEN(current_date - det.fecha_descuento::timestamp::date)*det.cantidad_mora
                                 ELSE 0
                            END AS mora,
                            CASE WHEN (det.flg_beca = '1')
                                THEN 'BECA'
                                ELSE ''
                            END AS descuento,
                            CASE WHEN ( det.flg_tipo IN ('1','2'))
                                 THEN monto_matricula
                                 ELSE CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '1')
                                           THEN CASE WHEN(current_date < det.fecha_descuento )
													 THEN round((((cond.monto_pension - cond.descuento_nivel) * (to_number('100', '9999D99') - ?))/100),2)
													 ELSE round((((cond.monto_pension - cond.descuento_nivel)* (to_number('100', '9999D99') - ?))/100),2) + cond.descuento_nivel
												END
                                           ELSE CASE WHEN(current_date < det.fecha_descuento )
                                                     THEN cond.monto_pension - cond.descuento_nivel
                                                     ELSE cond.monto_pension
                                                END
                                      END
                            END AS monto,
                            CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '1')
                                           THEN round((((cond.monto_pension - cond.descuento_nivel) * (to_number('100', '9999D99') - ?))/100),2) + cond.descuento_nivel
                                           ELSE cond.monto_pension
                            END AS monto_base,
                            INITCAP(CASE WHEN( det.flg_tipo = '1') THEN 'MATRÍCULA' 
                    					 WHEN( det.flg_tipo = '2') THEN 'RATIFICACIÓN'
                    					 WHEN( det.flg_tipo = '3') THEN 'CUOTA'
            			            END) AS concepto,
                            det.flg_tipo,
                            cond.monto_matricula_prom,
                            CASE WHEN (m.estado IS NOT NULL) THEN  m.estado
                                 ELSE 'Compromiso a generar' END AS estado,
            		        m.fecha_pago,
                            CASE WHEN (det.flg_tipo = '".FLG_CUOTA."' AND current_date < det.fecha_descuento) THEN cond.descuento_nivel
                                  ELSE 0 END AS descuento_nivel
                       FROM pagos.cronograma cro
                 INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND 
                                                             (CASE WHEN (? IS NOT NULL) THEN det.flg_tipo        IN ?
                                                                                        ELSE 1 != 1 END))
                  LEFT JOIN pagos.movimiento m             ON(m._id_detalle_cronograma  = det.id_detalle_cronograma AND
							                                 m._id_persona = ? )
                 INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND
                                                              cond._id_nivel      = ?                 AND
                                                              cond._id_grado      = ?                 AND
                                                              cond.year_condicion = ?                 AND
                                                              cond._id_tipo_cronograma = cro._id_tipo_cronograma)
                  LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND
                                                              cope.estado         = 'ACTIVO'          AND
                                                              cope._id_condicion  = cond.id_condicion)
                      WHERE cro._id_sede = ?
                        AND cro.year     = ?
                        AND cro.estado   = 'ACTIVO'
				   GROUP BY cro._id_tipo_cronograma,det.id_detalle_cronograma,cond.monto_matricula,cond.monto_pension,cond.monto_matricula_prom,m.estado,m.fecha_pago, monto_base,descuento_nivel
                   ORDER BY cro._id_tipo_cronograma,  det.fecha_vencimiento";
        $result = $this->db->query($sql, array($descuento,$descuento,$descuento, $descuento, $descuento, $arrayCompromisos, $arrayCompromisos,$id_persona, $sede,$nivel,$grado,$year,$id_persona,$sede,$year));
        return $result->result();
    }
    
    function getCuotaIngresoBySedeNivelGrado($sede,$nivel,$grado,$year) {
        $sql = "SELECT monto_cuota_ingreso
                  FROM pagos.condicion
                 WHERE _id_sede       = ?
                   AND _id_nivel      = ?
                   AND _id_grado      = ?
                   AND year_condicion = ?
                   AND monto_cuota_ingreso != 0";
        $result = $this->db->query($sql,array($sede,$nivel,$grado,$year));
        if($result->num_rows() > 0){
            return $result->row()->monto_cuota_ingreso;
        } else {
            return 0;
        }
    }
    
    function countByTipoDocFamiliar($nro, $tipoDoc, $idFamiliar = null){
        $sql = "SELECT COUNT(1) cant
                  FROM familiar
                 WHERE nro_doc_identidad  = ?
                   AND tipo_doc_identidad = ?
                   AND CASE WHEN ? IS NOT NULL THEN id_familiar <> ?
                       ELSE 1 = 1 END";
        $result = $this->db->query($sql,array($nro, $tipoDoc, $idFamiliar, $idFamiliar));
        return $result->row()->cant;
    }
    
    function deleteAlumnoById($idAlumno){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('nid_persona', $idAlumno);
            $this->db->delete('sima.detalle_alumno');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-017)');
            }
            $this->db->where('nid_persona', $idAlumno);
            $this->db->delete('persona');
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-018)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getFamiliaByCodFamAbrev($codFam){
        $sql = "SELECT INITCAP(CONCAT(ape_paterno,' ',ape_materno, ', ', nombres)) AS nombrecompleto,
	                   (SELECT INITCAP(c.desc_combo)
	                      FROM combo_tipo c
	                     WHERE c.valor = ff.parentesco::CHARACTER VARYING
	                       AND c.grupo = ".COMBO_PARENTEZCO." ) as parentesco,
	                    CASE WHEN f.foto_persona IS NOT NULL THEN f.foto_persona
	                         ELSE 'nouser.svg' END AS foto_persona,
	                   ff.cod_familiar
				  FROM familiar f,
	                   sima.familiar_x_familia ff
				 WHERE ff.id_familiar  = f.id_familiar
	               AND ff.cod_familiar = ?
	          ORDER BY nombrecompleto";
        $result = $this->db->query($sql, array($codFam));
        return $result->result();
    }
    
    function getConfig($year, $sede){
        $sql = "SELECT estado,
                       flg_afecta
                  FROM pagos.config_cuota_ingreso
                 WHERE year = ?
                   AND _id_sede = ?";
        $result = $this->db->query($sql, array($year, $sede));
	    return $result->row_array();
    }
    
    function getCountMatricula($idpersona, $year, $sede){
        $sql = "SELECT COUNT(*) cant
                  FROM pagos.movimiento m
            INNER JOIN pagos.detalle_cronograma dc
                    ON dc.id_detalle_cronograma = m._id_detalle_cronograma
            INNER JOIN pagos.cronograma c
                    ON c.id_cronograma = dc._id_cronograma
                 WHERE m._id_concepto = ".CONCEPTO_SERV_ESCOLAR."
                   AND dc.flg_tipo = '1'
                   AND _id_persona = ?
                   AND c.year = ?
                   AND c._id_sede = ?";
	    $result = $this->db->query($sql,array($idpersona, $year, $sede));
	    return $result->row()->cant;
    }
    
    function getArrayAlumnoById($idAlumno){
        $sql = "SELECT p.nid_persona,
				       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers)) AS apellidos,
	                   INITCAP(p.nom_persona) AS nombres,
					   CASE WHEN p.nro_documento IS NOT NULL THEN p.nro_documento
	                        ELSE '-' END AS nro_documento,
				       CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
	                        ELSE '-' END AS desc_aula,
		               CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
	                        ELSE '-' END AS desc_sede,
	                   CASE WHEN n.desc_nivel IS NOT NULL THEN n.desc_nivel
	                        ELSE '-' END AS desc_nivel,
	                   CASE WHEN g.desc_grado IS NOT NULL THEN g.desc_grado
	                        ELSE '-' END AS desc_grado,
				       CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
	                        ELSE '-' END AS desc_grado_nivel,
		               CASE WHEN d.cod_familia IS NOT NULL THEN d.cod_familia
	                        ELSE '-' END AS cod_familia,
		               CASE WHEN d.cod_alumno IS NOT NULL THEN d.cod_alumno
	                        ELSE '-' END AS cod_alumno,
	                   CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                        ELSE 'nouser.svg' END AS foto_persona,
	                   d.estado,
	                   a.nid_aula,
	                   (SELECT INITCAP(CONCAT(f.ape_paterno,', ',split_part( f.nombres, ' ' , 1 )))
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar   = f.id_familiar
	                       AND ff.cod_familiar  = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS nombrecompletoresponsable,
	                   (SELECT f.telf_celular
	                      FROM familiar f,
	                           sima.familiar_x_familia ff
	                     WHERE ff.id_familiar   = f.id_familiar
	                       AND ff.cod_familiar  = d.cod_familia
	                       AND ff.flg_apoderado = '1'
	                     LIMIT 1) AS telefonoresponsable,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('POR PAGAR'), UPPER('PAGADO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as pagado,
    			       (SELECT COUNT(*)
    			          FROM pagos.movimiento
    			         WHERE UPPER(estado) IN (UPPER('VENCIDO'))
    			           AND d.nid_persona = _id_persona
	                       AND _id_concepto IN (1,3)) as por_pagar,
	                   p.flg_acti,
	                   d.pais,
        		       d.cod_alumno_temp,
    			       d.year_ingreso
				  FROM persona p
			 LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona and (pa.year_academico = (SELECT pa2.year_academico
												  FROM persona_x_aula pa2
												 WHERE pa2.__id_persona = p.nid_persona
											      ORDER BY pa2.year_academico DESC
												 LIMIT 1))
		     LEFT JOIN aula a
				    ON a.nid_aula    = pa. __id_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
	         LEFT JOIN sima.detalle_alumno d
	                ON d.nid_persona = p.nid_persona
				 WHERE p.nid_persona = ?";
        $result = $this->db->query($sql, array($idAlumno));
        return $result->result();
    }
    
    function getCantidadEtudianteActivos(){
        $sql = "SELECT COUNT(1) AS count
	              FROM persona p,
                       sima.detalle_alumno da
	             WHERE p.nid_persona = da.nid_persona
                   AND p.flg_acti = '".FLG_ACTIVO."'";
	     
	    $result = $this->db->query($sql);
	    return $result->row()->count;
    }
    
    function verificarEstadoEstudiante($idAlumno){
        $rpta['entro'] = 0;
        $detaAlumno = $this->getCamposMinimosAlumno($idAlumno);
        if($detaAlumno['nom_persona'] != null && $detaAlumno['ape_pate_pers'] != null && $detaAlumno['ape_mate_pers'] != null && $detaAlumno['fec_naci'] != null &&
        $detaAlumno['sexo'] != null && $detaAlumno['nro_documento'] != null && $detaAlumno['estado'] == ALUMNO_DATOS_INCOMPLETOS && $detaAlumno['count_familia'] >= 1
        && $detaAlumno['id_grado_ingreso'] != null && $detaAlumno['id_nivel_ingreso'] != null && $detaAlumno['id_sede_ingreso'] != null && $detaAlumno['year_ingreso'] != null) {
            $alumnoUpdate = array("estado" => ALUMNO_PREREGISTRO);
            $this->updateCampoDetalleAlumno($alumnoUpdate, $idAlumno, 1);
            $rpta['entro'] = 1;
        }
        return $rpta;
    }
    
    function updatePersona_x_aul($arrayUpdate, $idAlumno, $year){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("__id_persona", $idAlumno);
            $this->db->where("year_academico", $year);
            $this->db->update("persona_x_aula", $arrayUpdate);
    
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-019)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
    
    function getCountFamiliares($idpersona){
    	$sql = "SELECT COUNT(1) cant
				  FROM sima.familiar_x_familia
				 WHERE cod_familiar = (SELECT cod_familia
				                         FROM sima.detalle_alumno
				                        WHERE nid_persona = ?)";
	    $result = $this->db->query($sql, array($idpersona));
	    return $result->row()->cant;
    }
    
    function updateConfirmaDeclaracion($arrayUpdate, $idAlumno, $year){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_estudiante", $idAlumno);
            $this->db->where("tipo", 'R');
            $this->db->where("year_confirmacion", $year);
            $this->db->update("sima.confirmacion_datos", $arrayUpdate);
    
            if($this->db->affected_rows() != 1){
                throw new Exception('(MA-020)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
         
        return $rpt;
    }
	
	function updateDatosRatificacionAlumno($arrayUpdate, $idAlumno) {
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
            $this->db->where("nid_persona", $idAlumno);
            $this->db->update("sima.detalle_alumno", $arrayUpdate);
	        
	        if($this->db->affected_rows() != 1){
	            throw new Exception('(MA-021)');
	        }
	        
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_UPT;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}
	
	function camposAdmision($id_alumno){
	    $sql = "SELECT d.year_ingreso,
	                   d.observacion,
	                   INITCAP(CONCAT(s.desc_sede,' ',g.abvr,' ',n.desc_nivel)) sedeGradoNivel
	              FROM sima.detalle_alumno d
	        INNER JOIN sede s
	                ON s.nid_sede = d.id_sede_ingreso
	        INNER JOIN nivel n
	                ON n.nid_nivel = d.id_nivel_ingreso
	        INNER JOIN grado g
	                ON g.nid_grado = d.id_grado_ingreso
	             WHERE d.nid_persona = ?";
	    $result = $this->db->query($sql,array($id_alumno));
	    return $result->row_array();
	}
	
	function getUsuario($idfamiliar, $nombres, $ape_pate, $ape_mate){
		$sql = "SELECT fun_generar_usuario_familiar(?,?,?,?) as usuario";
	    $result = $this->db->query($sql,array($idfamiliar, $nombres, $ape_pate, $ape_mate));
	    return $result->row()->usuario;
	}
}