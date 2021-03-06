<?php
class M_utils extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getById($tabla, $campo, $id, $idVal) {
        $sql = 'SELECT o.'.$campo.' campo
                 FROM '.$tabla.' o
                WHERE o.'.$id.' = ? LIMIT 1';
        
        $result = $this->db->query($sql,array($idVal));
        if($result->num_rows() > 0) {
            return ($result->row()->campo);
        } else {
            return null;
        }
    }
    
    function getCamposById($tabla, $campos, $id, $idVal){
        $select = null;
        foreach($campos as $campo) {
            $select .= 'o.'.$campo.',';
        }
        $select = substr($select, 0,strlen($select)-1);
        $sql = 'SELECT '.$select.'
                 FROM '.$tabla.' o
                WHERE o.'.$id.' = ? LIMIT 1';
        $result = $this->db->query($sql,array($idVal));
        if($result->num_rows() > 0) {
            return ($result->row_array());
        } else {
            return null;
        }
    }
    
    function checkClaveActual($clave, $idPers) {
        $sql = "SELECT encrypt(?,?,'aes') AS encry";
        $result = $this->db->query($sql,array($clave, $clave));
        if ($result->num_rows() == 1) {
            $clave = $result->row()->encry;
            $claveBD = $this->getById('persona', 'clave', 'nid_persona', $idPers);
            if($clave == $claveBD) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    
    function existeByCampoModelById($campo, $valor, $tabla, $nid_persona) {
        $sql = "SELECT COUNT(1) cant
	              FROM $tabla o
	             WHERE lower(o.$campo) = lower(?)
	               AND o.nid_persona <> ?";
        $result = $this->db->query($sql,array($valor, $nid_persona));
        return $result->row()->cant;
    }
    
    function getNivelesEducativos() {
    	$sql = "SELECT nid_nivel,
	    			   INITCAP(desc_nivel) desc_nivel
				  FROM nivel";
    	$result = $this->db->query($sql);
    	return $result->result();    	
    }
    
    function getUniversidades() {
        $sql = "SELECT id_universidad,
                       desc_univ
                  FROM universidad 
               GROUP BY id_universidad";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getSedes($sedes = null){
        $sql = "SELECT nid_sede,
                       INITCAP(desc_sede) desc_sede
                  FROM sede
                 WHERE ( ( ? IS NULL AND nid_sede NOT IN(?) ) OR ( ? IS NOT NULL AND 1 = 1) )
                ORDER BY desc_sede ASC";
        $result = $this->db->query($sql,array($sedes,SEDES_NOT_IN,$sedes));
        return $result->result();
    }
    
    function getSedesEcologica($sedes = null){
        $sql = "SELECT nid_sede,
                       INITCAP(desc_sede) desc_sede
                  FROM sede
                 WHERE nid_sede = 2";
        $result = $this->db->query($sql,array($sedes,SEDES_NOT_IN,$sedes));
        return $result->result();
    }
    
    function getRoles(){
        $sql = "SELECT nid_rol,
                       desc_rol
                  FROM rol
                ORDER BY desc_rol ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAreasAcad(){
        $sql = "SELECT nid_area_academica,
                       desc_area_academica 
                  FROM area_acad
                ORDER BY desc_area_academica ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAreasGenerales(){
        $sql = "SELECT id_area,
                       desc_area
                  FROM area
                 WHERE flg_general = 1
               ORDER BY desc_area";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getPPU(){
        $sql = "SELECT id_ppu,
                       desc_ppu
                  FROM ppu 
                ORDER BY desc_ppu ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
        
    function getNivelesBySede($idSede){
       $sql = "SELECT a.nid_nivel,
                      UPPER(n.desc_nivel) desc_nivel
                 FROM aula a,
                      nivel n
                WHERE flg_acti    = ".FLG_ACTIVO."
                  AND nid_sede    = ?
                  AND a.nid_nivel = n.nid_nivel
             GROUP BY a.nid_nivel,
                      n.nid_nivel
             ORDER BY a.nid_nivel";
        $result = $this->db->query($sql, array($idSede));
        return $result->result();
    }
     
    function getNivelesBySedeYear($idSede, $year) {
        $sql = "SELECT a.nid_nivel,
                      UPPER(n.desc_nivel) desc_nivel
                 FROM aula a,
                      nivel n
                WHERE a.nid_sede  = ?
                  AND a.year      = ?
                  AND a.nid_nivel = n.nid_nivel
             GROUP BY a.nid_nivel,
                      n.nid_nivel
             ORDER BY a.nid_nivel";
         $result = $this->db->query($sql, array($idSede, $year));
         return $result->result();
    }
    
    function getNiveleSecundariasBySede($idSede){
        $sql = "SELECT a.nid_nivel,
                       UPPER(n.desc_nivel) desc_nivel
                  FROM aula a,
                       nivel n
                 WHERE flg_acti    = ".FLG_ACTIVO."
                   AND nid_sede    = ?
                   AND n.nid_nivel = ".ID_SECUNDARIA."
                   AND a.nid_nivel = n.nid_nivel
              GROUP BY a.nid_nivel,
                       n.nid_nivel
              ORDER BY a.nid_nivel";
        $result = $this->db->query($sql, array($idSede));
        return $result->result();
     }

    function getGradosBySede($idSede){
        $sql = "SELECT a.nid_grado,
                       CONCAT(g.abvr,' ','SEC') desc_grado
                  FROM aula a,
                       grado g
                 WHERE a.nid_sede = ?
                   AND a.flg_acti = ".FLG_ACTIVO."
                   AND a.nid_grado IN (".GRADOS_SECUNDARIA.")
                   AND a.nid_grado = g.nid_grado
                 GROUP BY a.nid_grado, g.abvr
                 ORDER BY a.nid_grado ";
        $result = $this->db->query($sql, array($idSede));
        return $result->result();
    }
    
    function getAulasByGrado($idGrado,$id_sede){
        $year = date('Y');
        $sql = "SELECT nid_aula,
                       a.desc_aula 
                  FROM aula a
                 WHERE a.nid_sede  = ?
                   AND a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_grado = ?
                   AND a.year      = ?
                ORDER BY a.desc_aula";
        $result = $this->db->query($sql, array($id_sede, $idGrado, $year));
        return $result->result();
    }
    
    function getAulasByGradoSedeYear($idGrado, $id_sede, $year){
    	$sql = "SELECT nid_aula, 
				       desc_aula 
				  FROM aula 
				 WHERE nid_sede  = ?
				   AND flg_acti  = ".FLG_ACTIVO."
				   AND year      = COALESCE(?, year)
				   AND nid_grado = COALESCE(?, nid_grado) ";
    	$result = $this->db->query($sql, array($id_sede, $year, $idGrado));
    	return $result->result();
    }
    
    function getAulasTutorBySede($idSede) {
        $sql = " SELECT a.nid_aula,
                        CONCAT(a.desc_aula,' ',g.abvr,' ',n.abvr,' ',s.abvr) AS aula,
                        CASE WHEN p.nid_persona IS NOT NULL THEN
                                  CONCAT(INITCAP(SPLIT_PART(p.nom_persona, ' ', 1)),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' )
                             ELSE NULL END AS tutor
                   FROM aula a LEFT JOIN persona p ON (a.id_tutor = p.nid_persona),
                        sede  s,
                        nivel n,
                        grado g
                  WHERE a.nid_sede = ?
                    AND a.flg_acti = ".FLG_ACTIVO."
                    AND a.nid_sede  = s.nid_sede
                    AND a.nid_nivel = n.nid_nivel
                    AND a.nid_grado = g.nid_grado 
                 ORDER BY a.nid_nivel, a.nid_grado";
        $result = $this->db->query($sql, array($idSede));
        return $result->result_array();
    }
    
    function getAulasByGradoSinSede($idGrado){
        $sql = "SELECT nid_aula,
                       a.desc_aula
                  FROM aula a
                 WHERE a.flg_acti = ".FLG_ACTIVO."
                   AND a.nid_grado = ?
                ORDER BY a.desc_aula";
        $result = $this->db->query($sql, array($idGrado));
        return $result->result();
    }
    
    function getPersonalByRol($idRol) {
         $sql = "SELECT p.nid_persona,
    				    CONCAT(INITCAP(SPLIT_PART(p.nom_persona, ' ', 1)),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' ) nombre_docente
    			  FROM persona p ,
    				   persona_x_rol pr
                 WHERE p.flg_acti    = '".FLG_ACTIVO."'
                   AND pr.flg_acti   = '".FLG_ACTIVO."'
                   AND pr.nid_rol	 = $idRol
                   AND p.nid_persona = pr.nid_persona
              ORDER BY p.ape_pate_pers"	;
         $result = $this->db->query($sql);
         return $result->result();
     }
    
    function getGradosBySedeAll($idSede){
    	$sql = "SELECT a.nid_grado,
                       CONCAT(g.abvr,' ',n.desc_nivel) desc_grado
                  FROM aula a,
                       grado g,
                       nivel n
                 WHERE a.nid_sede  = ?
                   AND a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_grado = g.nid_grado
                   AND n.nid_nivel = a.nid_nivel
              GROUP BY a.nid_grado, n.nid_nivel, g.abvr
              ORDER BY a.nid_grado ";
    	$result = $this->db->query($sql, array($idSede));
    	return $result->result();
    }
    
    /**
     * Trae a los grados dependiendo del nivel enviado
     * @author cesar 17.09.2015
     * @param integer $idNivel
     */
    function getGradosByNivel($idNivel, $idSede) {
        $sql = "SELECT a.nid_grado, 
                       CONCAT(g.abvr,' ',n.abvr) desc_grado
                  FROM grado g,
                       nivel n,
                       aula  a
                 WHERE g.id_nivel = ?
                   AND n.nid_nivel = g.id_nivel
                   AND a.nid_grado = g.nid_grado
                   AND a.nid_nivel = g.id_nivel
                   AND a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_sede  = ?
                GROUP BY a.nid_grado,CONCAT(g.abvr,' ',n.abvr)
                ORDER BY a.nid_grado";
        $result = $this->db->query($sql, array($idNivel, $idSede));
        return $result->result();
    }
    
    /**
     * Trae a los grados dependiendo del nivel enviado (variacion year)
     * @author dfloresgonz 09.10.2016
     * @param integer $idNivel
     */
    function getGradosByNivelYear($idNivel, $idSede, $year) {
        $sql = "SELECT a.nid_grado,
                       CONCAT(g.abvr,' ',n.abvr) desc_grado
                  FROM grado g,
                       nivel n,
                       aula  a
                 WHERE g.id_nivel  = ?
                   AND a.year      = ?
                   AND a.nid_sede  = ?
                   AND n.nid_nivel = g.id_nivel
                   AND a.nid_grado = g.nid_grado
                   AND a.nid_nivel = g.id_nivel
                GROUP BY a.nid_grado,CONCAT(g.abvr,' ',n.abvr)
                ORDER BY a.nid_grado";
        $result = $this->db->query($sql, array($idNivel, $year, $idSede));
        return $result->result();
    }
    
    function getGradoYNivel_FromAula($idAula) {
        $sql = "SELECT nid_grado,
                       nid_nivel
                  FROM aula
                 WHERE nid_aula = ? ";
        $result = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    function getConfigByMediaRashPromedio($config) {
        $sql = "SELECT id_nota,
                       desc_config
                  FROM config
                 WHERE desc_config LIKE '%$config%'
               GROUP BY id_nota,
                        desc_config
               ORDER BY id_nota";
       $result = $this->db->query($sql);
       return $result->result();
    }
    
    function updatePassword($newClave, $idPersona) {
        $array = array("clave"                 => $newClave, 
                       "fec_hora_cambio_clave" => null);
        $this->db->where('nid_persona', $idPersona);
        $this->db->update('persona', $array);
        if($this->db->affected_rows() != 1) {
            throw new Exception('Hubo un error al cambiar la clave');
        }
        return array('error' => EXIT_SUCCESS, 'msj' => 'La clave fue actualizada correctamente');
    }
    
    function getCursos() {
        $sql = "SELECT c.nid_curso id_curso,
                CONCAT(c.desc_curso,' / ',a.desc_area) curso
                  FROM curso     c,
                       area a
                 WHERE flg_acti = ".FLG_ACTIVO."
                   AND c.nid_area_academica = a.id_area";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllAulas() {
        $sql = "SELECT a.nid_aula,
                       CONCAT(a.desc_aula,' / ',g.abvr,' ',n.abvr,' / ',s.abvr) aula
                  FROM aula a,
                       sede s,
                       nivel n,
                       grado g
                 WHERE a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_sede  = s.nid_sede
                   AND a.nid_grado = g.nid_grado
                   AND a.nid_nivel = n.nid_nivel
                 ORDER BY s.nid_sede,
                          n.nid_nivel,
                          g.nid_grado";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAulas($idCurso, $idArea=null) {
        $sql = "SELECT nid_aula, 
                       desc_aula 
                  FROM aula 
                 WHERE nid_grado IN (SELECT id_grado 
                                       FROM notas.fun_get_cursos_area(?) 
                                      WHERE id_curso = ?) 
                   AND flg_acti = ".FLG_ACTIVO."  
                UNION ALL
                SELECT id_aula_ext, 
            	       desc_aula_ext 
                  FROM aula_externa
                 WHERE COALESCE(null, (SELECT nid_aula
                        	             FROM aula 
                        	            WHERE nid_grado IN (SELECT id_grado 
                        				                      FROM notas.fun_get_cursos_area(?) 
                        				                     WHERE id_curso = ?) LIMIT 1)) IS NULL" ;
        $result = $this->db->query($sql, array($idArea, $idCurso, $idArea, $idCurso));
        return $result->result();
    }
    
    function getSedeNivelGradoFromAula($idAula) {
        $sql = "SELECT nid_sede,
                       nid_nivel,
                       nid_grado
                  FROM aula
                 WHERE nid_aula = ? " ;
        $result = $this->db->query($sql, array($idAula));
        if($result->num_rows() == 1) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getGrupoMigracionCombo($tipo) {
        $sql = "SELECT grupo_migracion
                  FROM log_migracion
                 WHERE tipo_migracion = ?
                GROUP BY grupo_migracion
                ORDER BY grupo_migracion";
        $result = $this->db->query($sql, array($tipo) );
        return $result->result();
    }
    
    function getPuestoById($id){
        $sql = "SELECT desc_combo campo
                  FROM combo_tipo
                 WHERE grupo = ".GRUPO_PUESTOS."
                   AND valor = ? LIMIT 1";
        $result = $this->db->query($sql,array($id));
        if($result->num_rows() > 0) {
            return ($result->row()->campo);
        } else {
            return null;
        }
    }
    
    function updateTabla($tabla, $whereClause, $whereClauseValor, $campo, $valorNuevo) {
        $this->db->where($whereClause, $whereClauseValor);
        $this->db->update($tabla, array($campo => $valorNuevo));
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al actualizar');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);
    }
    
    function updateTabla_2($tabla, $whereClause, $whereClauseValor, $arrayUpdate) {
        $this->db->where($whereClause, $whereClauseValor);
        $this->db->update($tabla, $arrayUpdate);
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al actualizar');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);
    }
    
    function validarPersonaPermiso($idPersona, $idPermiso) {
        $sql = "SELECT COUNT(1) AS count
                  FROM permiso_x_persona
                 WHERE _id_permiso = ?
                   AND _id_persona = ?";
        $result = $this->db->query($sql,array($idPermiso, $idPersona));
        $tof = false;
        if($result->row()->count == 1) {
            $tof = true;
        }
		return $tof;
    }
    
    /**
     * Ejecuta el VACUUM a las tablas
     * @author dfloresgonz 04.05.2016
     * @param array $arrayTables array("tabla1", "tabla2")
     * @param string $bd nombre de la conexion a BD, NULL = schoowl
     */
    function vacuumTablesMigracion($arrayTables, $bd = null) {
        if($bd != null && $bd != 'smiledu') {
            $database = $this->load->database($bd, TRUE);
        }
        foreach ($arrayTables as $tab) {
            $sqlVaccuum = "VACUUM FULL ".$tab;
            if($bd != null && $bd != 'smiledu') {
                $database->query($sqlVaccuum);
            } else if($bd == null || $bd == 'smiledu') {
                $this->db->query($sqlVaccuum);
            }
        }
    }
    
    function getGradosNivelBySede($idSede) {
        $sql = "SELECT CONCAT(a.nid_grado,'_',a.nid_nivel) id_grado_nivel,
                        CONCAT(g.abvr,' ',n.abvr) grado_nivel
                  FROM aula a,
                       sede s,
                       nivel n,
                       grado g
                 WHERE a.flg_acti  = ".FLG_ACTIVO."
                   AND a.nid_sede  = ?
                   AND s.nid_sede  = a.nid_sede
                   AND n.nid_nivel = a.nid_nivel
                   AND g.nid_grado = a.nid_grado
                 GROUP BY a.nid_grado, a.nid_nivel, g.abvr, n.abvr
                 ORDER BY a.nid_nivel, a.nid_grado";
        $result = $this->db->query($sql, array($idSede));
        return $result->result();
    }
    
    function getCorreosPadresByAula($idSede, $idNivel, $idGrado, $idAula) {
        $sql ="SELECT *
                 FROM (
            	  SELECT CASE WHEN tab.correo ~* '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$' = TRUE THEN LOWER(BTRIM(tab.correo, '-')) ELSE NULL END AS email
            	    FROM (
            			   SELECT CASE WHEN email1 IS NOT NULL THEN email1 ELSE email2 END AS correo
            			     FROM sima.familiar_x_familia ff,
		                          familiar f
            			    WHERE ff.id_familiar = f.id_familiar
                              AND ff.cod_familiar IN (SELECT da.cod_familia
                            					        FROM persona_x_aula pa,
                            								 persona        p,
                            								 aula           a,
                                                             sima.detalle_alumno da
                            						   WHERE ( (? IS NOT NULL AND ? IS NULL AND ? IS NULL AND ? IS NULL AND a.nid_sede  = ?)
                            						             OR (? IS NOT NULL AND ? IS NOT NULL AND ? IS NOT NULL AND ? IS NULL AND a.nid_sede  = ? AND a.nid_nivel = ? AND a.nid_grado = ?)
                            						             OR (? IS NOT NULL AND ? IS NOT NULL AND ? IS NOT NULL AND ? IS NOT NULL AND a.nid_sede  = ? AND a.nid_nivel = ? AND a.nid_grado = ? AND a.nid_aula = ?) )
                            					                AND pa.flg_acti = '1'
                            							 AND p.nid_persona = pa.__id_persona
                            						     AND pa.__id_aula  = a.nid_aula
                                                         AND p.nid_persona = da.nid_persona )
            		     ) tab
            	   WHERE (tab.correo ~* '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$') = TRUE
            	 ) tab2
            	GROUP BY tab2.email
                ORDER BY tab2.email";
        $result = $this->db->query($sql,array($idSede, $idNivel, $idGrado, $idAula, $idSede,
                                              $idSede, $idNivel, $idGrado, $idAula, $idSede, $idNivel, $idGrado,
                                              $idSede, $idNivel, $idGrado, $idAula, $idSede, $idNivel, $idGrado, $idAula));
        return $result->result();
    }
    
    function getComboTipoByGrupo($grupo, $order = 'desc_combo') {
        $sql = "SELECT valor,
                       INITCAP(desc_combo) desc_combo
                  FROM combo_tipo
                 WHERE grupo = ?
                   AND valor <> '0'
              ORDER BY (CASE WHEN ".$order." IS NOT NULL THEN ".$order." END)";
        $result = $this->db->query($sql, array($grupo));
        return $result->result();  
    }
    
    function getValoresArrayTipoByGrupo($grupo) {
        $sql = "SELECT valor
                  FROM combo_tipo
                 WHERE grupo = ?
                   AND valor <> '0'";
        $result = $this->db->query($sql, array($grupo));
        $arryIds = array();
        foreach ($result->result() as $val) {
            array_push($arryIds, $val->valor);
        }
        return $arryIds;
    }
    
    ///////////////////////////////// NUEVAS FUNCIONES FIJAS DE SMILEDU /////////////////////////////////////////
    
    function getSistemasByRol($idSistemaActual, $idPersona) {
        $result = null;
        //PERMISOS FAMILIAR
        if(_getSesion('cod_familiar') != null){
            $sql = "SELECT r.nid_sistema,
                           s.desc_sist,
                           s.url_sistema,
                           s.logo_sistema,
    	                   s.orden,
                           s.logo_sistema_c,
                           s.flg_realizado
                      FROM rol_x_sistema r,
                           sistema       s
                     WHERE r.flg_acti     = '".FLG_ACTIVO."'
    	               AND r.nid_sistema  NOT IN (".ID_SISTEMA_MATENIMIENTO.", $idSistemaActual)
                       AND r.nid_sistema  = s.nid_sistema
    	               AND r.nid_rol     = '".ID_ROL_FAMILIA."'
    	            GROUP BY r.nid_sistema,
    	                     s.desc_sist,
                             s.url_sistema,
                             s.logo_sistema,
    	                     s.orden,
    	                     s.logo_sistema_c,
    	                     s.flg_realizado";
            $result = $this->db->query($sql);
        } else{//PERMISOS PERSONAL Y ALUMNOS
            $sql = "SELECT r.nid_sistema,
                       s.desc_sist,
                       s.url_sistema,
                       s.logo_sistema,
	                   s.orden,
                       s.logo_sistema_c,
                       s.flg_realizado
                  FROM rol_x_sistema r,
                       sistema       s,
                       persona_x_rol pr
                 WHERE r.flg_acti     = '".FLG_ACTIVO."'
	               AND r.nid_sistema  NOT IN (".ID_SISTEMA_MATENIMIENTO.", $idSistemaActual)
            	               AND r.nid_sistema  = s.nid_sistema
            	               AND pr.nid_rol     = r.nid_rol
            	               AND pr.flg_acti    = '".FLG_ACTIVO."'
            	               AND pr.nid_persona = $idPersona
            	               GROUP BY r.nid_sistema,
            	               s.desc_sist,
            	               s.url_sistema,
            	               s.logo_sistema,
            	               s.orden,
            	               s.logo_sistema_c,
            	               s.flg_realizado";
            $result = $this->db->query($sql);
        }
        return $result->result();
    }
    
    function checkIfRolHasPermiso($idRol, $idModulo, $idPermiso) {
        $sql = "SELECT 1 AS has_permiso
                   FROM rol_x_sist_permiso
                  WHERE nid_sistema = ?
                    AND nid_rol     = ?
                    AND nid_permiso = ?
                    AND flg_acti    = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idModulo, $idRol, $idPermiso) );
        if($result->num_rows() == 0 ) {
            return false;
        }
        if($result->row()->has_permiso == 1) {
            return true;
        }
        return false;
    }
    
    function checkIfUserHasRol($idPersona, $idRol) {
        $sql = "SELECT TRUE AS rpta
                  FROM persona_x_rol
                 WHERE nid_persona = ?
                   AND nid_rol     = ?
                   AND flg_acti    = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idPersona, $idRol) );
        if($result->num_rows() == 0 ) {
            return false;
        }
        return $result->row()->rpta;
    }
    
    function checkIfUserHasRol_Aux($idPersona, $idRol) {
        $sql = "SELECT TRUE AS rpta
                  FROM persona_x_rol
                 WHERE nid_persona = ?
                   AND nid_rol     = ?";
        $result = $this->db->query($sql, array($idPersona, $idRol) );
        if($result->num_rows() == 0 ) {
            return false;
        }
        return $result->row()->rpta;
    }
    
    function getAllColegios(){
        $sql = 'SELECT id_colegio,
	                   INITCAP(desc_colegio) desc_colegio
	              FROM sima.colegios
              ORDER BY desc_colegio';
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getGradoNivel() {
        $sql = "SELECT CONCAT(g.nid_grado,'_',n.nid_nivel) id_grado_nivel,
                        CONCAT(g.abvr,' ',n.abvr) grado_nivel
                  FROM nivel n,
                       grado g
                 WHERE g.id_nivel = n.nid_nivel
                 GROUP BY g.nid_grado, n.nid_nivel, g.abvr, n.abvr
                 ORDER BY n.nid_nivel, g.nid_grado";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllDepartamentos(){
        $sql = "SELECT cod_dept     AS cod,
	                   INITCAP(departamento) AS desc
	              FROM sima.ubigeo
                 WHERE cod_prov = '00'
                   AND cod_dist = '00'
              ORDER BY departamento";
        $result = $this->db->query($sql);
        return $result->result();
    }
     
    function getAllProvinciaByDepartamento($idDepartamentos){
        $sql = "SELECT cod_prov  AS cod,
	                   INITCAP(provincia) AS desc
	              FROM sima.ubigeo
                 WHERE cod_dept = ?
                   AND cod_dist = '00'
	               AND cod_prov <> '00'
              ORDER BY provincia";
        $result = $this->db->query($sql, array($idDepartamentos));
        return $result->result();
    }
     
    function getAllDistritoByProvincia($idDepartamento, $idProvincia){
        $sql = "SELECT cod_dist AS cod,
	                   INITCAP(distrito) AS desc
	              FROM sima.ubigeo
                 WHERE cod_dept = ?
	               AND cod_prov = ?
	               AND cod_dist <> '00'
              ORDER BY distrito";
        $result = $this->db->query($sql, array($idDepartamento, $idProvincia));
        return $result->result();
    }
    
    function getDescComboTipoByGrupoValor($grupo, $valor) {
        $sql = "SELECT INITCAP(desc_combo) as desc
                   FROM combo_tipo
                  WHERE grupo = ?
                    AND valor = ?
                   AND valor <> '0'";
        $result = $this->db->query($sql,array($grupo, $valor));
        return $result->row()->desc;
    }
    
    function getAllAreasEspecificas(){
        $sql = "SELECT id_area,
                       UPPER(desc_area) desc_area
                  FROM area
                 WHERE flg_general = '".FLG_GENERAL."'
              ORDER BY desc_area ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllAreasEspecificasByGeneral($idAreaGeneral) {
        $sql = "SELECT id_area,
                       desc_area
                  FROM area
                 WHERE flg_general     = '".FLG_GENERAL."'
                   AND id_area_general = ?
              ORDER BY desc_area ASC";
        $result = $this->db->query($sql, array($idAreaGeneral) );
        return $result->result_array();
    }
    
    function getAllTipoNotas(){
    	$sql = "SELECT id_tipo_nota ,
    			       desc_tipo_nota
 			      FROM tipo_nota";
    	$result = $this->db->query($sql);
    	return $result->result();
    }
    
    function getAllConceptosByTipo($tipoConcepto){
        $sql = "SELECT c.id_concepto,
	                   c.desc_concepto,
	                   c.monto_referencia
	              FROM pagos.concepto c
	             WHERE c.tipo_movimiento = ?
	               AND c.id_concepto NOT IN ?
	               AND c.estado = '".FLG_ESTADO_ACTIVO."'";
        $result = $this->db->query($sql,array($tipoConcepto,json_decode(ARRAY_CONCEPTOS)));
        return $result->result();
    }
    
    function getYearsAcademicos() {
        $sql = "SELECT year
                   FROM aula
                 GROUP BY year
                 ORDER BY year";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getGradosNivel_All() {
        $sql = "SELECT a.nid_grado,
                        CONCAT(g.abvr,' ',n.abvr) grado_nivel
                  FROM aula a,
                       sede s,
                       nivel n,
                       grado g
                 WHERE a.flg_acti  = ".FLG_ACTIVO."
                   AND s.nid_sede  = a.nid_sede
                   AND n.nid_nivel = a.nid_nivel
                   AND g.nid_grado = a.nid_grado
                 GROUP BY a.nid_grado, a.nid_nivel, g.abvr, n.abvr
                 ORDER BY a.nid_nivel, a.nid_grado";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getMaxSedeByAlumno($idPersona){
        $sql = "SELECT a.nid_sede
                  FROM public.aula a,
                       public.persona_x_aula pa
                 WHERE pa.__id_persona = ?
                   AND pa.__id_aula    = a.nid_aula
              ORDER BY year_academico DESC
                 LIMIT 1";
        $result = $this->db->query($sql, array($idPersona));
        if($result->num_rows() > 0){
            return $result->row()->nid_sede;
        } else{
            return null;
        }
    }
    
    //@Pendiente cambiar cuando se migre de rrhh
    function getSedeTrabajoByColaborador($idPersona){
        $sql = "SELECT pd.id_sede_control
                   FROM rrhh.personal_detalle pd
                  WHERE pd.id_persona = ?";
        $result = $this->db->query($sql, array($idPersona));
        if($result->num_rows() > 0) {
            return $result->row()->id_sede_control;
        } else{
            return null;
        }
    }
    
    function getBimestresPosibles() {
        $sql = "SELECT id_ciclo,
                        desc_ciclo_acad
                   FROM ciclo_academico
                  WHERE tipo_ciclo = ".ID_TIPO_BIMESTRE."
                    AND (SELECT now())::date < fec_fin
                 ORDER BY orden";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getBimestres() {
        $sql = "SELECT id_ciclo,
                        desc_ciclo_acad
                   FROM ciclo_academico
                  WHERE tipo_ciclo = ".ID_TIPO_BIMESTRE."
                 ORDER BY orden";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    //@TODO repetidos
    function getNombrePersona($id){
        $sql="SELECT INITCAP(CONCAT(ape_pate_pers,' ',ape_mate_pers,', ',nom_persona)) nom_persona
               FROM public.persona
              WHERE nid_persona=?";
        $result = $this->db->query($sql, array($id));
        if($result->num_rows()){
            return $result->row()->nom_persona;
        } else {
            return null;
        }
    }
    
    function getRolByPersona($idPersona){
        $sql = "SELECT r.desc_rol
                   FROM public.rol r,
                        public.persona_x_rol pr
                  WHERE pr.nid_persona = ?
                    AND pr.nid_rol     = r.nid_rol
                  LIMIT 1";
        $result = $this->db->query($sql, array($idPersona));
        if($result->num_rows() > 0) {
            return $result->row()->desc_rol;
        } else {
            return null;
        }
    }
    
    function getSedesByYear($year){
        $sql = "SELECT s.nid_sede,
                       INITCAP(s.desc_sede) desc_sede
                  FROM sede s,
                       aula a
                 WHERE year = ?
                   AND a.nid_sede = s.nid_sede
              GROUP BY a.nid_sede,
                       s.nid_sede,
                       desc_sede
              ORDER BY desc_sede ASC";
        $result = $this->db->query($sql, array($year));
        return $result->result();
    }
    
    function getGradoNivelBySedeYear($idSede,$year){
        $sql = "SELECT CONCAT(a.nid_grado,'_',a.nid_nivel) id_grado_nivel,
                       CONCAT(g.abvr,' ',n.abvr) descrip
                  FROM aula a,
                       nivel n,
                       grado g
                 WHERE a.nid_sede  = ?
                   AND a.year      = ?
                   AND a.nid_nivel = n.nid_nivel
                   AND a.nid_grado = g.nid_grado
              GROUP BY a.nid_grado, a.nid_nivel, g.abvr, n.abvr
              ORDER BY a.nid_nivel, a.nid_grado";
        $result = $this->db->query($sql, array($idSede,$year));
        return $result->result();
    }
    
    function getAllPaises(){
        $sql = 'SELECT id_pais,
	                   INITCAP(desc_pais) desc_pais
	              FROM sima.paises
              ORDER BY desc_pais';
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function existeByCampoModel($campo, $valor, $tabla){
        $sql = "SELECT COUNT(1) cant
              FROM ".$tabla." o
             WHERE lower(o.".$campo.") = lower(?)
             LIMIT 1";
        $result = $this->db->query($sql,array($valor));
        return $result->row()->cant;
    }
    
    function countByTipoDoc($nro, $tipoDoc,$idContacto = null){
        $sql = "SELECT COUNT(1) cant
                  FROM admision.contacto
                 WHERE nro_documento = ?
                   AND tipo_documento = ?
                   AND CASE WHEN ? IS NOT NULL THEN id_contacto <> ?
                       ELSE 1 = 1 END";
        $result = $this->db->query($sql,array($nro, $tipoDoc,$idContacto, $idContacto));
        return $result->row()->cant;
    }
    
    function countByTipoDocPersona($nro, $tipoDoc, $idContacto = null){
    	$sql = "SELECT COUNT(1) cant
                  FROM persona
                 WHERE nro_documento = ?
                   AND tipo_documento = ?
                   AND CASE WHEN ? IS NOT NULL THEN nid_persona <> ?
                       ELSE 1 = 1 END";
    	$result = $this->db->query($sql,array($nro, $tipoDoc,$idContacto, $idContacto));
        return $result->row()->cant;
    }
    
    function getLastOpcionByGrupo($grupo){
        $sql = "SELECT MAX(valor::integer) as valor
	              FROM combo_tipo
	              WHERE grupo = ?";
        $result = $this->db->query($sql, array($grupo));
        $valor = 1;
        if($result->row() != null){
            $valor = $result->row()->valor;
        }
        return $valor;
    }
    
    function getGradosByNivel_sinAula($idNivel) {
        $sql = "SELECT g.nid_grado,
                       INITCAP(CONCAT(g.abvr,' ',n.desc_nivel)) desc_grado
                  FROM grado g,
                       nivel n
                 WHERE g.id_nivel  = ?
                   AND n.nid_nivel = g.id_nivel
                ORDER BY g.nid_grado";
        $result = $this->db->query($sql, array($idNivel));
        return $result->result();
    }
    
    function getAllYearByCompromisos() {
        $sql = "SELECT c.year
                  FROM pagos.cronograma c
            INNER JOIN pagos.detalle_cronograma dc
                    ON dc._id_cronograma = c.id_cronograma
        	     WHERE c.estado = '".FLG_ESTADO_ACTIVO."'
        	       AND c.flg_cerrado_mat = '1'
              GROUP BY c.year
              ORDER BY c.year";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllSedesByCompromisos($year) {
        $sql = "SELECT s.nid_sede,
                       UPPER(s.desc_sede) desc_sede
                  FROM pagos.cronograma cr
            INNER JOIN sede s
        		    ON s.nid_sede = cr._id_sede
        		 WHERE cr.estado = '".FLG_ESTADO_ACTIVO."'
        		   AND year = ?
        	       AND cr.flg_cerrado_mat = '1'
        	  GROUP BY s.nid_sede
              ORDER BY s.desc_sede";
        $result = $this->db->query($sql, array($year));
        return $result->result();
    }
    
    function getNivelesBySedeCondicion($idSede,$year){
       $sql = "SELECT n.nid_nivel,
                      UPPER(n.desc_nivel) desc_nivel
                 FROM nivel n
       	   INNER JOIN pagos.condicion c
       		       ON (c._id_nivel = n.nid_nivel)
       	   INNER JOIN pagos.cronograma cro
       		       ON c._id_tipo_cronograma = cro._id_tipo_cronograma
           INNER JOIN pagos.detalle_cronograma det
       		       ON det._id_cronograma  = cro.id_cronograma
        	      AND cro.flg_cerrado_mat = '1'
       		      AND c.year_condicion = ?
       		      AND c._id_sede       = ?
       		      AND c._id_grado      = 0
             GROUP BY n.nid_nivel
       	     ORDER BY n.nid_nivel";
        $result = $this->db->query($sql, array($year, $idSede));
        return $result->result();
    }
    
    function getGradosByNivelCondicion($idNivel,$idSede,$year){
       $sql = "SELECT g.nid_grado,
                      UPPER(g.desc_grado) desc_grado
                 FROM grado g
       	   INNER JOIN pagos.condicion c
       		       ON (c._id_grado = g.nid_grado)
       	   INNER JOIN pagos.cronograma cro
       		       ON c._id_tipo_cronograma = cro._id_tipo_cronograma
           INNER JOIN pagos.detalle_cronograma det
       		       ON det._id_cronograma  = cro.id_cronograma
        	      AND cro.flg_cerrado_mat = '1'
       		      AND c.year_condicion = ?
       		      AND c._id_sede       = ?
       		      AND c._id_nivel      = ?
       		      AND c._id_grado     != 0
             GROUP BY g.nid_grado
       	     ORDER BY g.nid_grado";
        $result = $this->db->query($sql, array($year, $idSede, $idNivel));
        return $result->result();
    }
    
    function getDatosResumenAula($idAula) {
        $sql = "SELECT CONCAT(a.nid_sede,' - ',a.desc_aula,' ',g.abvr,' ',n.abvr,' ',s.abvr) AS descrip,
                       CONCAT(INITCAP(a.desc_aula),' ',g.abvr,' ',n.abvr,' ',s.abvr) AS descrip_aux
                  FROM aula  a,
                       grado g,
                       nivel n,
                       sede  s
                 WHERE a.nid_aula = ?
                   AND a.nid_sede  = s.nid_sede
                   AND a.nid_nivel = n.nid_nivel
                   AND a.nid_grado = g.nid_grado";
        $result = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    function getDatosIDs_Aula($idAula) {
        $sql = "SELECT a.nid_sede,
                       a.nid_nivel,
                       a.nid_grado,
                       a.flg_acti
                  FROM aula  a,
                       grado g,
                       nivel n,
                       sede  s
                 WHERE a.nid_aula = ?
                   AND a.nid_sede  = s.nid_sede
                   AND a.nid_nivel = n.nid_nivel
                   AND a.nid_grado = g.nid_grado";
        $result = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    function getTutorByAula($idAula) {
        $sql = "SELECT CONCAT(UPPER(p.ape_pate_pers), ' ', UPPER(p.ape_mate_pers), ', ', INITCAP(p.nom_persona)) AS tutor
                  FROM persona p,
                       aula    a
                 WHERE a.nid_aula = ?
                   AND a.id_tutor = p.nid_persona";
        $result = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    function insertarEnviarCorreo($arrayInsert) {
        $arrayInsert['estado_correo'] = CORREO_PENDIENTE;
        $this->db->insert("envio_correo", $arrayInsert);
        if($this->db->affected_rows() != 1) {
            throw new Exception('(MU-010)');
        }
        return array('error' => EXIT_SUCCESS);
    }
    
    function getAreasByIdAreaGeneral($idAreaGeneral) {
        $sql = "SELECT id_area,
                       desc_area
                  FROM area
                 WHERE flg_general     = '".FLG_GENERAL."'
                   AND id_area_general = ?
              ORDER BY desc_area ASC";
        $result = $this->db->query($sql, array($idAreaGeneral) );
        return $result->result_array();
    }
    
    function getDisciplinas(){
        $sql = "SELECT d.id_disciplina,
                        UPPER(d.desc_disciplina) desc_disciplina
                  FROM disciplina d
                ORDER BY d.id_disciplina";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getCorreoByPersona($idPersona){
        $sql = "SELECT CASE WHEN correo_inst IS NOT NULL THEN correo_inst
                            WHEN correo_admi IS NOT NULL THEN correo_admi
                            ELSE correo_pers END AS correo
	              FROM persona
	              WHERE nid_persona = ?";
        $result = $this->db->query($sql, array($idPersona));
        return $result->row()->correo;
    }

    function getComboTipoByGrupos($grupo) {
        $sql = "SELECT grupo,
                       array_to_string(array_agg(CONCAT(valor,'_',INITCAP(desc_combo)) order by desc_combo),',') valor_desc
                  FROM combo_tipo
                 WHERE grupo IN ?
                   AND valor <> '0'
              GROUP BY grupo";
        $result = $this->db->query($sql, array($grupo));
        return $result->result();
    }
    
    function getSedesRatificacion($idnivel, $idgrado){
        $sql = "SELECT s.nid_sede,
                       INITCAP(s.desc_sede) desc_sede
                  FROM sede s,
                       aula a
                 WHERE a.nid_nivel = ?
                   AND a.nid_grado = ?
                   AND a.nid_sede = s.nid_sede
              GROUP BY a.nid_sede,
                       s.nid_sede,
                       desc_sede
              ORDER BY desc_sede ASC";
        $result = $this->db->query($sql, array($idnivel, $idgrado));
        return $result->result();
    }
    
    function getYearsFromNowByCompromisos() {
        $sql = "SELECT c.year
                  FROM pagos.cronograma c
            INNER JOIN pagos.detalle_cronograma dc
                    ON dc._id_cronograma = c.id_cronograma
        	     WHERE c.estado          = '".FLG_ESTADO_ACTIVO."'
        	       AND c.flg_cerrado_mat = '1'
                   AND c.year            >= (SELECT EXTRACT (YEAR FROM now()))
              GROUP BY c.year
              ORDER BY c.year";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllTiposCronograma($opc = null) {
        $sql = "SELECT id_tipo_cronograma,
                       INITCAP(desc_tipo_cronograma) desc_tipo_cronograma
                  FROM pagos.tipo_cronograma
                 WHERE CASE WHEN ? IS NOT NULL THEN id_tipo_cronograma != 4
                                               ELSE 1 = 1 
                       END";
        $result = $this->db->query($sql,array($opc));
        return $result->result();
    }
    
    function buscarPersonal($busqueda) {
        $sql = "SELECT nid_persona,
                       CONCAT(SPLIT_PART(INITCAP(p.nom_persona),' ',1),' ', UPPER(p.ape_pate_pers), ' ', UPPER(p.ape_mate_pers)) nombres,
                	   CASE WHEN p.correo_inst IS NOT NULL THEN p.correo_inst
                			WHEN p.correo_admi IS NOT NULL THEN p.correo_admi
                			WHEN p.correo_pers IS NOT NULL THEN p.correo_pers
                		    ELSE NULL END AS correo,
                	   CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                			WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                			ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
                	   ROW_NUMBER() OVER () AS rnum
                  FROM persona p,
                       rrhh.personal_detalle pd
                 WHERE p.flg_acti    = '".FLG_ACTIVO."'
                   AND p.nid_persona = pd.id_persona
                   AND UPPER(CONCAT(SPLIT_PART(INITCAP(p.nom_persona),' ',1),' ',p.ape_pate_pers,' ',p.ape_mate_pers)) LIKE UPPER(?)";
        $result = $this->db->query($sql, array('%'.$busqueda.'%'));
        return $result->result_array();
    }
    
    function getAllBancosActivos() {
        $sql = "SELECT id_banco,
                       desc_banco,
                       UPPER(abvr) abvr
                  FROM pagos.banco
                 WHERE estado = '".FLG_ESTADO."'
              ORDER BY desc_banco";
        $result = $this->db->query($sql);
        return $result->result();
    }
}