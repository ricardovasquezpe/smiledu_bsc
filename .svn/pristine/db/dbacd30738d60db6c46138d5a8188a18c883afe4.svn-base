<?php
class M_crear_encuesta extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllPreguntasByCateEnc($idEncuesta, $idCategoria){
        $sql = "SELECT p.id_pregunta,
                       x._id_tipo_pregunta,
                       e._id_tipo_encuesta,
                       p.desc_pregunta,
                       x.tipo_encuestado,
                       te.desc_tipo_enc,
                       CASE WHEN flg_obligatorio = '".FLG_OBLIGATORIO."' THEN 'checked'
                                                       ELSE ''
                       END AS checkObli,
                       'checked' as checked
                  FROM senc.preguntas p,
                       senc.encuesta e,
                       senc.pregunta_x_enc_cate x
                       LEFT JOIN senc.tipo_encuestado te ON (x.tipo_encuestado = te.abvr_tipo_enc)
                 WHERE x._id_encuesta = ?
                   AND x._id_categoria = ?
                   AND x._id_encuesta = e.id_encuesta
                   AND x._id_pregunta  = p.id_pregunta
              ORDER BY x.orden";
        $result = $this->db->query($sql,array($idEncuesta,$idCategoria));
        return $result->result();
    }
    
    function getAllPreguntasNoAsignadasByEncuesta($idEncuesta){
        $sql = "SELECT *
                  FROM senc.preguntas p
                 WHERE p.id_pregunta NOT IN (SELECT x._id_pregunta
                                               FROM senc.pregunta_x_enc_cate x
                                              WHERE x._id_encuesta = ?)";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->result();
    }
    
    function getLastOrdenPregByEncuestaCategoria($idEncuesta,$idCategoria){
        $sql = "SELECT (COUNT(1)+1) next_orden
                  FROM senc.pregunta_x_enc_cate x
                 WHERE x._id_encuesta  = ?
                   AND x._id_categoria = ?";
        $result = $this->db->query($sql,array($idEncuesta,$idCategoria));
        return $result->row()->next_orden;
    }
    
    function getLastOrdenCateByEncuesta($idEncuesta){
        $sql = "SELECT (COUNT(1)+1) next_orden
                  FROM senc.categoria_x_encuesta x
                 WHERE x._id_encuesta  = ?";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->row()->next_orden;
    }
    
    function getOrdenCategoriaByEncuesta($idEnc,$idCate){
        $sql = "SELECT orden
                  FROM senc.categoria_x_encuesta
                 WHERE _id_encuesta  = ?
                   AND _id_categoria = ?";
        $result = $this->db->query($sql,array($idEnc,$idCate));
        return $result->row()->orden;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////                SEGUIMIENTO DE ENCUESTA EFQM        //////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function getSedesAvanceByEncuestaEFQM() {
        $sql = "SELECT tab.nid_sede,
                       tab.desc_sede,
                       tab.cant_estu,
                       tab.cant_hecho,
                       ROUND(( (tab.cant_hecho * 100)::numeric / (CASE WHEN tab.cant_estu = 0 THEN 1 ELSE tab.cant_estu END)), 1)::numeric AS porct
                  FROM (SELECT s.nid_sede,
                	       s.desc_sede,
                	       (SELECT COUNT(1)
                    		  FROM persona_x_aula pa,
                    		       aula           a
                    		 WHERE pa.flg_acti       = '".FLG_ACTIVO."'
                    		   AND pa.year_academico = "._YEAR_."
                    		   --AND a.nid_grado       >= 9
                    		   AND a.nid_sede        = s.nid_sede
                    		   AND pa.__id_aula      = a.nid_aula ) AS cant_estu,
                	       (SELECT COUNT(1)
                    		  FROM persona_x_aula pa,
                    		       aula           a
                    		 WHERE pa.flg_acti       = '".FLG_ACTIVO."'
                    		   AND pa.flg_encuesta   = 1
                    		   --AND a.nid_grado       >= 9
                    		   AND pa.year_academico = "._YEAR_."
                    		   AND a.nid_sede        = s.nid_sede
                    		   AND pa.__id_aula      = a.nid_aula ) AS cant_hecho
                	  FROM sede s
                	 WHERE nid_sede NOT IN (".SEDES_NOT_IN.",".ID_SEDE_AVANTGARD.")
                	ORDER BY desc_sede) AS tab";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getSedesCantEstu() {
        $sql = "SELECT s.nid_sede,
            	       s.desc_sede,
                       row_number() OVER() AS rownum,
            	       (SELECT COUNT(1)
                		  FROM persona_x_aula pa,
                		       aula           a
                		 WHERE pa.flg_acti       = '".FLG_ACTIVO."'
                		   AND pa.year_academico = "._YEAR_."
                		   AND a.year            = "._YEAR_."
                		   AND a.flg_acti        = ".FLG_ACTIVO."
                		   AND a.nid_grado       >= 9
                		   AND a.nid_sede        = s.nid_sede
                		   AND pa.__id_aula      = a.nid_aula ) AS cant_estu
                 FROM sede s
                WHERE s.nid_sede NOT IN (".SEDES_NOT_IN.")
               ORDER BY s.desc_sede";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function getSedesAvanceByEncuestaDocentePersAdmEFQM($areaGeneralDocente) {
        $sql = "SELECT row_number() OVER() AS rownum,
                       tab.nid_sede,
                       tab.desc_sede,
                       tab.cant_pers,
                       tab.cant_hecho,
                       CASE WHEN tab.cant_pers > 0 THEN ROUND(( (tab.cant_hecho * 100)::numeric / tab.cant_pers), 1)::numeric
                            ELSE 0 END as porct
                  FROM (SELECT s.nid_sede,
                    	       s.desc_sede,
                    	       (SELECT COUNT(1)
                        		  FROM persona p,
                                       rrhh.personal_detalle pd
                        		 WHERE p.flg_acti         = '".FLG_ACTIVO."'
                        		   AND pd.id_cargo           NOT IN (4)
		                           AND pd.id_area_especifica NOT IN (58)
                        		   AND p.nid_persona      = pd.id_persona
                        		   AND pd.id_sede_control = s.nid_sede
                        		   AND ( CASE WHEN ? = ".ID_AREA_ACADEMICA." THEN pd.id_area_general = ".ID_AREA_ACADEMICA."
                        		              ELSE pd.id_area_general <> ".ID_AREA_ACADEMICA." END ) ) AS cant_pers,
                    	       (SELECT COUNT(1)
                        		  FROM persona p,
                                       rrhh.personal_detalle pd
                        		 WHERE p.flg_acti         = '".FLG_ACTIVO."'
                        		   AND p.flg_encuesta     = 1
                        		   AND pd.id_cargo           NOT IN (4)
		                           AND pd.id_area_especifica NOT IN (58)
                        		   AND p.nid_persona      = pd.id_persona
                        		   AND pd.id_sede_control = s.nid_sede
                        		   AND ( CASE WHEN ? = ".ID_AREA_ACADEMICA." THEN pd.id_area_general = ".ID_AREA_ACADEMICA."
                        		              ELSE pd.id_area_general <> ".ID_AREA_ACADEMICA." END ) ) AS cant_hecho
                	  FROM sede s
                	 WHERE nid_sede NOT IN (".ID_SEDE_AVANTGARD.")
                	ORDER BY desc_sede) AS tab";
        $result = $this->db->query($sql, array($areaGeneralDocente, $areaGeneralDocente));
        return $result->result_array();
    }
    
    function getAvanceAulasBySede($idSede) {
        $sql = "SELECT tab.nid_aula,
                       tab.desc_aula,
                       CASE WHEN tab.nid_nivel = 1 THEN 'bg-success'
                            WHEN tab.nid_nivel = 2 THEN 'bg-info'
                            WHEN tab.nid_nivel = 3 THEN 'bg-warning' END AS color_nivel,
                       tab.grado,
                       tab.cant_estu,
                       tab.cant_hecho,
                       ROUND(( (tab.cant_hecho * 100)::numeric / CASE WHEN tab.cant_estu = 0 THEN 1 ELSE tab.cant_estu END), 1)::numeric AS porct
                  FROM (SELECT a.nid_aula,
                    	       a.desc_aula,
                               a.nid_nivel,
                    	       CONCAT(g.abvr,' ',n.abvr) AS grado,
                    	       (SELECT COUNT(1)
                        		  FROM persona_x_aula pa
                        		 WHERE pa.flg_acti  = '".FLG_ACTIVO."'
                        		   AND pa.__id_aula = a.nid_aula ) AS cant_estu,
                    	       (SELECT COUNT(1)
                        		  FROM persona_x_aula pa
                        		 WHERE pa.flg_acti     = '".FLG_ACTIVO."'
                        		   AND pa.flg_encuesta = 1
                        		   AND pa.__id_aula = a.nid_aula ) AS cant_hecho 
                    	  FROM aula  a,
                    	       nivel n,
                    	       grado g
                    	 WHERE a.nid_sede  = ?
                    	  -- AND a.flg_acti  = ".FLG_ACTIVO."
                    	   AND a.year      = "._YEAR_."
                    	   AND a.nid_nivel = n.nid_nivel
                    	   AND a.nid_grado = g.nid_grado
                    	ORDER BY a.nid_nivel, a.nid_grado ) AS tab";
        $result = $this->db->query($sql, array($idSede));
        return $result->result_array();
    }
    
    function getAvanceAulasBySedeOnlyHecho($idSede) {//encuesta efqm estudiantes
        $sql = "SELECT tab.nid_aula,
                       tab.desc_aula,
                       tab.nid_sede,
                       tab.candado,
                       CASE WHEN tab.nid_nivel = 1 THEN 'bg-success'
                            WHEN tab.nid_nivel = 2 THEN 'bg-info'
                            WHEN tab.nid_nivel = 3 THEN 'bg-warning' END AS color_nivel,
                       tab.grado,
                       tab.cant_estu
                  FROM (SELECT a.nid_aula,
                    	       a.desc_aula,
                               a.nid_nivel,
                               a.nid_sede,
                               a.flg_encuesta_efqm_estu  AS candado,
                    	       CONCAT(g.abvr,' ',n.abvr) AS grado,
                    	       (SELECT COUNT(1)
                        		  FROM persona_x_aula pa
                        		 WHERE pa.flg_acti  = '".FLG_ACTIVO."'
                        		   AND pa.__id_aula = a.nid_aula ) AS cant_estu
                    	  FROM aula  a,
                    	       nivel n,
                    	       grado g
                    	 WHERE a.nid_sede  = ?
                    	   AND a.flg_acti  = ".FLG_ACTIVO."
                    	   AND a.year      = "._YEAR_."
                    	   AND a.nid_grado >= 9
                    	   AND a.nid_nivel = n.nid_nivel
                    	   AND a.nid_grado = g.nid_grado
                    	ORDER BY a.nid_nivel, a.nid_grado ) AS tab";
        $result = $this->db->query($sql, array($idSede));
        return $result->result_array();
    }
    
    function getAvanceAreasPersAdm($idSede) {
        $sql = "SELECT a.id_area,
                       a.desc_area,
                       (SELECT COUNT(1)
                    	  FROM persona p,
                    	       rrhh.personal_detalle pd
                    	 WHERE p.flg_acti         = '".FLG_ACTIVO."'
                    	   AND pd.id_cargo           NOT IN (".ID_ROL_SUBDIRECTOR.")
                    	   AND pd.id_area_especifica NOT IN (".ID_AREA_CONTABLE.")
                    	   AND pd.id_area_general = a.id_area
                    	   AND p.nid_persona      = pd.id_persona
                    	   AND pd.id_sede_control = ?) AS cant_pers,
                       (SELECT COUNT(1)
                    	  FROM persona p,
                    	       rrhh.personal_detalle pd
                    	 WHERE p.flg_acti         = '".FLG_ACTIVO."'
                    	   AND p.flg_encuesta     = 1
                    	   AND pd.id_cargo           NOT IN (".ID_ROL_SUBDIRECTOR.")
                    	   AND pd.id_area_especifica NOT IN (".ID_AREA_CONTABLE.")
                    	   AND pd.id_area_general = a.id_area
                    	   AND p.nid_persona      = pd.id_persona
                    	   AND pd.id_sede_control = ?) AS cant_hecho
                  FROM area a
                 WHERE a.flg_general = 1
                   AND a.id_area <> ".ID_AREA_ACADEMICA;
        $result = $this->db->query($sql, array($idSede, $idSede));
        return $result->result_array();
    }
    
    function getAvanceAreasDocente($idSede) {
        $sql = "SELECT a.id_area,
                       a.desc_area,
                       (SELECT COUNT(1)
                    	  FROM persona p,
                    	       rrhh.personal_detalle pd
                    	 WHERE p.flg_acti         = '".FLG_ACTIVO."'
                    	   AND pd.id_cargo           NOT IN (".ID_ROL_SUBDIRECTOR.")
                    	   AND pd.id_area_especifica NOT IN (".ID_AREA_CONTABLE.")
                    	   AND pd.id_area_especifica = a.id_area
                    	   AND p.nid_persona      = pd.id_persona
                    	   AND pd.id_sede_control = ?) AS cant_pers,
                       (SELECT COUNT(1)
                    	  FROM persona p,
                    	       rrhh.personal_detalle pd
                    	 WHERE p.flg_acti         = '".FLG_ACTIVO."'
                    	   AND p.flg_encuesta     = 1
                    	   AND pd.id_cargo           NOT IN (".ID_ROL_SUBDIRECTOR.")
                    	   AND pd.id_area_especifica NOT IN (".ID_AREA_CONTABLE.")
                    	   AND pd.id_area_especifica = a.id_area
                    	   AND p.nid_persona      = pd.id_persona
                    	   AND pd.id_sede_control = ?) AS cant_hecho
                  FROM area a
                 WHERE a.flg_general = 0
                   AND a.id_area_general = ".ID_AREA_ACADEMICA;
        $result = $this->db->query($sql, array($idSede, $idSede));
        return $result->result_array();
    }
    
    function getPersonalEncuestado($tipoEncuesta, $idArea, $idSede) {
        $sql = "SELECT row_number() OVER() AS rownum,
                       CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona)) AS persona,
                       CASE WHEN p.flg_encuesta IS NULL THEN null ELSE '1' END AS realizo,
                       (SELECT desc_rol FROM rol WHERE nid_rol = pd.id_cargo) AS cargo,
                       p.usuario
                  FROM persona               p,
                       rrhh.personal_detalle pd
                 WHERE p.nid_persona = pd.id_persona
                   AND p.flg_acti         = '".FLG_ACTIVO."'
                   AND pd.id_cargo           NOT IN (".ID_ROL_SUBDIRECTOR.")
                   AND pd.id_area_especifica NOT IN (".ID_AREA_CONTABLE.")
                   AND pd.id_sede_control = $idSede
                   AND ( CASE WHEN '$tipoEncuesta' = ".TIPO_ENCUESTA_PERSADM." THEN pd.id_area_general = ?
                	          ELSE pd.id_area_especifica = ? END )";
        $result = $this->db->query($sql, array($idArea, $idArea));
        return $result->result_array();
    }
    
    function getPersonalEncuestadoBySede($idSede, $tipoEncuesta) {
        $sql = "SELECT row_number() OVER() AS rownum,
                       CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),', ', INITCAP(p.nom_persona)) AS persona,
                       CASE WHEN p.flg_encuesta IS NULL THEN null ELSE 'Sí' END AS realizo,
                       (SELECT desc_rol FROM rol WHERE nid_rol = pd.id_cargo) AS cargo,
                       p.usuario
                  FROM persona               p,
                       rrhh.personal_detalle pd
                 WHERE p.nid_persona = pd.id_persona
                   AND p.flg_acti         = '".FLG_ACTIVO."'
                   AND pd.id_cargo           NOT IN (".ID_ROL_SUBDIRECTOR.")
                   AND pd.id_area_especifica NOT IN (".ID_AREA_CONTABLE.")
                       AND pd.id_sede_control = $idSede
                       AND ( CASE WHEN '$tipoEncuesta' = ".TIPO_ENCUESTA_PERSADM." THEN pd.id_area_general <> 18
                	              ELSE pd.id_area_general = ".ID_AREA_ACADEMICA." END )";
        $result = $this->db->query($sql, array($tipoEncuesta));
        return $result->result_array();
    }
    
    function getEstudiantesSinLlenarEncuestaPadres($idAula) {
        $sql = "SELECT p.nid_persona,
                       INITCAP(LOWER(CONCAT(split_part( p.nom_persona, ' ' , 1 ) ,' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers, 1, 1),'.'))) AS estudiante,
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE."', p.foto_persona)
                                                WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                                                ELSE CONCAT('".RUTA_SMILEDU."', '".FOTO_DEFECTO."') END AS foto_persona,
                       CASE WHEN pa.flg_encuesta IS NOT NULL AND pa.flg_recibido_encu_fisica IS NOT NULL THEN 'FÍSICO'
                            WHEN pa.flg_encuesta IS NOT NULL AND pa.flg_recibido_encu_fisica IS NULL     THEN 'WEB'
                            ELSE NULL END AS encuestado,
                       pa.flg_entrega_encu_fisica,
                       pa.flg_recibido_encu_fisica
                  FROM persona_x_aula pa,
                       persona        p
                 WHERE pa.__id_aula    = ?
                   AND pa.flg_acti     = '".FLG_ACTIVO."'
                   AND p.flg_acti      = '".FLG_ACTIVO."'
                   AND pa.__id_persona = p.nid_persona
                ORDER BY pa.flg_encuesta DESC";
        $result = $this->db->query($sql, array($idAula));
        return $result->result_array();
    }
    
    function getDatosPersonaXAulaEncuesta($idEstu, $idAula) {
        $sql = "SELECT flg_entrega_encu_fisica,
                       flg_recibido_encu_fisica
                  FROM persona_x_aula
                 WHERE __id_persona = ?
                   AND __id_aula    = ?
                   AND flg_acti     = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idEstu, $idAula));
        return $result->row_array();
    }
    
    function marcarEncuesta($idEstu, $idAula, $arryUpt) {
        $this->db->where('__id_persona', $idEstu);
        $this->db->where('__id_aula', $idAula);
        $this->db->update('persona_x_aula', $arryUpt);
        if($this->db->affected_rows() == 0) {
            throw new Exception('Error al actualizar datos de encuesta');
        }
        return array("error" => EXIT_SUCCESS, "msj" => 'Se actualiz&oacute;');
    }
    
    /**
     * Trae la cantidad de estudiantes x aula que no han llenado encuestas
     * y que se les entrego la encuesta en fisico, esto sirve para el momento
     * de subir la encuesta en excel poder comparar la cantidad de encuestas fisicas
     * en el excel.
     * @param $idAula
     * @author dfloresgonz
     * @since 29.09.2016
     */
    function getCountEstudiantesSinLlenarEncEntregFisico($idAula) {
        $sql = "SELECT COUNT(1) AS cnt
                  FROM persona_x_aula
                 WHERE __id_aula    = ?
                   AND flg_entrega_encu_fisica  IS NOT NULL
                   AND flg_recibido_encu_fisica IS NOT NULL
                   AND flg_encuesta             IS NULL
                   AND flg_acti     = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idAula));
        return $result->row()->cnt;
    }
    
    function getEstudiantesSinLlenarEncEntregFisico($idAula) {
        $sql = "SELECT p.nid_persona,
                       INITCAP(CONCAT(SPLIT_PART( p.nom_persona, ' ', 1),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' ) ) AS estudiante
                  FROM persona_x_aula pa,
                       persona        p
                 WHERE pa.__id_aula    = ?
                   AND pa.flg_entrega_encu_fisica  IS NOT NULL
                   AND pa.flg_recibido_encu_fisica IS NOT NULL
                   AND pa.flg_encuesta             IS NULL
                   AND pa.flg_acti     = '".FLG_ACTIVO."'
                   AND pa.__id_persona = p.nid_persona ";
        $result = $this->db->query($sql, array($idAula));
        return $result->result_array();
    }
    
    function actualizarEstudiantesFlgEncuestaFisica($idAula) {
        $sql = "UPDATE persona_x_aula
                   SET flg_encuesta = 1
                 WHERE __id_aula                = ?
                   AND flg_entrega_encu_fisica  IS NOT NULL
                   AND flg_recibido_encu_fisica IS NOT NULL
                   AND flg_encuesta             IS NULL
                   AND flg_acti                 = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idAula));
        return $this->db->affected_rows();
    }
    
    function actualizarEstudianteFlgEncuestaFisica($idAula, $idEstu) {
        $sql = "UPDATE persona_x_aula
                   SET flg_encuesta = 1
                 WHERE __id_aula    = ?
                   AND __id_persona = ?
                   AND flg_entrega_encu_fisica  IS NOT NULL
                   AND flg_recibido_encu_fisica IS NOT NULL
                   AND flg_encuesta             IS NULL
                   AND flg_acti                 = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idAula, $idEstu));
        return $this->db->affected_rows();
    }
    
    function getCantidadEncuestadosBySede_EFQM_Estu($db, $idEncuesta, $idSede) {
        $noSQL = 'db.senc_respuesta_encuesta.aggregate([
                      { $unwind  : "$preguntas" },
                      { $match   : { "id_encuesta" : '.$idEncuesta.' , "id_sede" : '.$idSede.' } },
                      { $group   : { _id : "$preguntas.id_dispositivo", 
                                     counta  : { $first : 1 }, 
                                     aula    : {$first  : "$id_aula"} , 
                                     id_sede : {$first  : "$id_sede"} } },
                	  { $group   : { _id : "$id_sede" , 
                                     count : {$sum : "$counta"} } }
                ])';
        $result = $db->execute('return '.$noSQL.'.toArray()');
        if(isset($result['retval']) && isset($result['retval'][0]['count']) ) {
            return $result['retval'][0]['count'];
        } else {
            return null;
        }
    }
    
    function getCantidadEncuestadosByAula_EFQM_Estu($idEncuesta, $idSede, $idAula) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $noSQL = 'db.senc_respuesta_encuesta.aggregate([
                    { $unwind  : "$preguntas" },
                    { $match   : { "id_encuesta" : '.$idEncuesta.' , "id_sede" : '.$idSede.' , "id_aula" : '.$idAula.' } },
                    { $group   : { _id : "$preguntas.id_dispositivo", 
                                   count   : { $first : 1 }, 
                                   aula    : {$first  : "$id_aula"} , 
                                   id_sede : {$first  : "$id_sede"} } },
		            { $group   : { _id : "$id_sede" , 
                                   count : {$sum : "$count"} } }
                 ])';
        $result = $db->execute('return '.$noSQL.'.toArray()');
        if(isset($result['retval']) && isset($result['retval'][0]['count']) ) {
            return $result['retval'][0]['count'];
        } else {
            return null;
        }
    }
}