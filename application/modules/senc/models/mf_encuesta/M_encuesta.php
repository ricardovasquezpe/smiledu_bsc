<?php
class M_encuesta extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    /**
     * Retornar las categorias de una encuesta
     * @param $idEncuesta
     * @author fernando
     * @since 03.03.2016
     * @return Lista de categorias
     */
    function getCategoriasByEncuesta($idEncuesta,$notCate = array(0)){
        $sql = " SELECT x._id_categoria,
            	        x.orden,
            	        c.desc_cate
                   FROM senc.categoria_x_encuesta x,
            	        senc.categoria            c
                  WHERE x._id_categoria = c.id_categoria
                    AND x._id_categoria NOT IN ?
                    AND x._id_encuesta  = ?
                    AND x.flg_estado    = '".ESTADO_ACTIVO."'
               ORDER BY x.orden";
        $result = $this->db->query($sql, array($notCate,$idEncuesta));
        return $result->result();
    }
    
    function getPreguntasByCategoria($idEncuesta,$idCategoria, $arrayServ, $tipoEncuGlobal){
        $sql="   SELECT   x.orden,
                	      e.desc_enc,
                	      c.desc_cate,
                	      p.desc_pregunta,
                          x._id_pregunta,
                          x.flg_obligatorio,
                          p._id_servicio,
                          x._id_tipo_pregunta
                   FROM   senc.pregunta_x_enc_cate x,
                	      senc.encuesta            e,
                	      senc.preguntas           p,
                	      senc.categoria           c
                  WHERE   x.flg_estado    = '".ESTADO_ACTIVO."'
                    AND   x._id_encuesta  = ?
                    AND   x._id_categoria = ?
                    AND   (
                             ( ? IS NULL AND 1 = 1) OR 
                             ( (p._id_servicio IS NOT NULL AND p._id_servicio IN  ? ) OR (? IS NOT NULL AND p._id_servicio IS NULL )) 
                          )
                    AND   ( ? IS NOT NULL AND ((x.tipo_encuestado IS NOT NULL AND x.tipo_encuestado = ?) OR (x.tipo_encuestado IS NULL AND 1 = 1)) 
                            OR 1 = 1)
                    AND   x._id_encuesta  = e.id_encuesta
                    AND   x._id_pregunta  = p.id_pregunta
                    AND   x._id_categoria = c.id_categoria
               --ORDER BY   random()
                 ORDER BY x.orden";
        $result = $this->db->query($sql, array($idEncuesta,$idCategoria, (($arrayServ == null) ? null : $arrayServ) , (($arrayServ == null) ? array(0) : $arrayServ) , (($arrayServ == null) ? null : $arrayServ) , $tipoEncuGlobal, $tipoEncuGlobal));
        return $result->result();
    }

    function getEncuestasByTipoEncuesta($tipoEncuesta){
        $sql = "SELECT e.id_encuesta,
                       e.desc_enc,
                       e.flg_estado,
                       e.titulo_encuesta
                  FROM senc.encuesta e
                 WHERE e._id_tipo_encuesta = ?
              ORDER BY e.fecha_apertura DESC";
        $result = $this->db->query($sql,array($tipoEncuesta));
        return $result->result();
    }
    
    function getEncuestasByTipoEncuestaPersona($idTipo, $idPersona){
        $sql = "SELECT e.id_encuesta,
                       e.desc_enc,
                       e.flg_estado,
                       e.titulo_encuesta
	  			  FROM senc.encuesta e
	 		     WHERE audi_pers_regi = ?
                   AND e._id_tipo_encuesta = ?
			     UNION
			    SELECT e.id_encuesta,
                       e.desc_enc,
                       e.flg_estado,
                       e.titulo_encuesta
	  			  FROM senc.encuesta e
	 		     WHERE id_encuesta IN (SELECT id_encuesta
				 					     FROM (SELECT id_encuesta,
					 							      (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS id_persona
					 						     FROM senc.encuesta) AS t
											    WHERE t.id_persona = ?)";
        $result = $this->db->query($sql,array($idPersona, $idTipo, $idPersona));
        return $result->result();
    }
    function getEncuestasByTipoEncuestaExcel($arrayTipoEncuestas){
        $sql = "SELECT e.id_encuesta,
                       e.desc_enc,
                       e.flg_estado
                  FROM senc.encuesta e
                 WHERE e._id_tipo_encuesta IN ?
                   AND e.flg_estado = '".ESTADO_ACTIVO."'";
        $result = $this->db->query($sql,array($arrayTipoEncuestas));
        return $result->result();
    }

    function getAllPreguntasxResp($idPregun,$idRespuesta){ 
        $sql="  SELECT 
                  FROM senc.pregunta_x_repuesta pxr
                 WHERE pxr._id_pregunta  = ?
                   AND pxr._id_respuesta = ?
              ORDER BY audi_fec_regi DESC";
        $result = $this->db->query($sql,array($idPregun,$idRespuesta));
        return $result->result();
        
    }
    function getAlternativas($idPregunta, $idEncuesta){
        $sql = " SELECT atp._id_alternativa,
                        atp._id_tipo_pregunta,
                        atp._id_pregunta,
                        a.desc_alternativa,
                        a.css_alternativa,
                        atp.orden
                   FROM senc.alter_x_tipo_preg_x_preg atp,
                    	senc.alternativa              a
                  WHERE atp._id_encuesta    = ?
                    AND atp._id_pregunta    = ?
                    AND atp._id_alternativa = a.id_alternativa
               ORDER BY atp.orden";
            
        
        $result = $this->db->query($sql,array($idEncuesta,$idPregunta));
        return $result->result();
    }
    
    function getcantFlagOblibyEncuesta($idEncuesta, $idArrayServ) {
        $sql=" SELECT COUNT(1) cuenta
                FROM senc.pregunta_x_enc_cate x,
                     senc.preguntas p
               WHERE ( x.flg_obligatorio = '".FLG_OBLIGATORIO."' OR ( (( ? IS NULL AND 1 = 1) OR ( ? IS NOT NULL AND p._id_servicio IN ? )) AND x.flg_obligatorio = '1' ))
                 AND x._id_encuesta    = ?
                 AND x.flg_estado      = '".ESTADO_ACTIVO."'
                 AND x._id_pregunta    = p.id_pregunta";
        $result = $this->db->query($sql, array($idArrayServ, 
                                               $idArrayServ ,
            (($idArrayServ == null) ? array(0) : $idArrayServ),$idEncuesta) );
        return $result->row()->cuenta;
    }
    
    function getcantFlagOblibyEncbyTipoEncGlo($idEncuesta, $idArrayServ, $tipoEncuGlobal) {
        $sql=" SELECT COUNT(1) cuenta
                 FROM senc.pregunta_x_enc_cate x,
                      senc.preguntas p
                WHERE x.flg_obligatorio = '".FLG_OBLIGATORIO."'
                  AND x._id_encuesta    = ?
                  AND x.flg_estado      = '".ESTADO_ACTIVO."'     
                  AND p.id_pregunta = x._id_pregunta            
                  AND ((p._id_servicio IS NOT NULL AND p._id_servicio IN ? ) OR (p._id_servicio IS NULL AND 1 = 1))
                  AND ((x.tipo_encuestado IS NOT NULL AND x.tipo_encuestado = ?) OR (x.tipo_encuestado IS NULL AND 1 = 1))";
        $result = $this->db->query($sql, array($idEncuesta, $idArrayServ, $tipoEncuGlobal));
        return $result->row()->cuenta;
    }
    
    function getIdsPreguntasByEncuesta($idEncuesta){
        $sql = "SELECT _id_pregunta	
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_encuesta = ?";
        $result = $this->db->query($sql, array($idEncuesta));
        $arrayIds = array();
        foreach ($result->result() as $row) {
            array_push($arrayIds, $row->_id_pregunta);
        }
        return $arrayIds;
    }
    
    function getExistePreguntaInEncuesta($idPreg, $idEncuesta){
        $sql = "SELECT COUNT(1),
                       flg_obligatorio
                  FROM senc.pregunta_x_enc_cate 
                 WHERE _id_encuesta = ?
                   AND _id_pregunta = ?
                 GROUP BY flg_obligatorio";
        $result = $this->db->query($sql, array($idEncuesta,$idPreg));
        return $result->row_array();
    }
    
    function getExisteAlterInPregunta($idPreg, $idEncuesta, $idAlter){
        $sql = " SELECT COUNT(1) alter_pertenece
                   FROM senc.tipo_pregunta tp,
                        senc.pregunta_x_enc_cate x
                  WHERE tp.id_tipo_pregunta = x._id_tipo_pregunta
                    AND x._id_encuesta      = ?
                    AND x._id_pregunta      = ?
                    AND ?                   = ANY(tp.array_alternativa::int[])";
        $result = $this->db->query($sql, array($idEncuesta, $idPreg, $idAlter));
        return $result->row()->alter_pertenece;
    }
    
    function getCantPregOblixCate($idCategoria,$idEncuesta, $idArrayServ = null){
        $sql = " SELECT COUNT(1) cantpregpbli
                   FROM senc.pregunta_x_enc_cate x,
                        senc.preguntas p
                  WHERE x._id_categoria   = ?
                    AND x._id_encuesta    = ?
                    AND x.flg_obligatorio = '".FLG_OBLIGATORIO."'
                    AND x.flg_estado      = '".ESTADO_ACTIVO."'
                    AND p.id_pregunta     = x._id_pregunta 
                    AND ((p._id_servicio IS NOT NULL AND p._id_servicio IN ? ) OR (p._id_servicio IS NULL AND 1 = 1))";
        $result = $this->db->query($sql, array($idCategoria, $idEncuesta, $idArrayServ));
        return $result->row()->cantpregpbli;
    }
    
    /**
     * @author Fernando Luna
     * @param arrayPreguntasRpta $arrayPregRpta
     * @param tipoEncuesta $tipoEnc
     * @param SedeNivelGradoArea $SNGAula
     * @return Inserta las respuestas en la coleccion senc_respuesta_encuesta
     */
    function insertRptaMongoDB($arrayPregRpta, $tipoEnc, $SNGAula, $idEncuesta, $idDispositivo, $arrayRollBack) {
        $data['error'] = ERROR_MONGO;
        $data['msj']   = null;
        try{
            $m  = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            $sqlCount = null;
            $tipoEncuestado = _getSesion('tipoEncuestadoLibre');
            $niveles = null;
            $tipoEncuestadoFirstLetter = null;
            if($tipoEncuestado != null) {
                $tipoEncuestado = $this->getAllTipoEncuestadosByEncuesta($idEncuesta, $tipoEncuestado);               
                $tipoEncuestadoFirstLetter = substr($tipoEncuestado, 0, 1);                
            }
            if($tipoEncuestadoFirstLetter != null) {//@PENDIENTE Llamar a la tabla senc.tipo_encuestado
                if($tipoEncuestadoFirstLetter == PADRE || $tipoEncuestadoFirstLetter == ESTUDIANTE) {
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                } else if($tipoEncuestadoFirstLetter == PERSONAL_ADMINISTRATIVO) {
                    $niveles = 'id_area : '.$SNGAula['nid_area'].',';
                } else if($tipoEncuestadoFirstLetter == DOCENTE){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_area  : '.$SNGAula['nid_area'].',';
                } else if($tipoEncuestadoFirstLetter == INVITADO) {
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }
            } else {
                $niveles = ''.(($tipoEnc == TIPO_ENCUESTA_DOCENTE || $tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_nivel : '.$SNGAula['nid_nivel'].',' : null).'
                        	'.(($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_grado : '.$SNGAula['nid_grado'].', id_aula : '.$SNGAula['nid_aula'].',': 'id_area : '.$SNGAula['nid_area'].',').'';
            }
            //Busca por las pk id_encuesta, year, id_sede, id_nivel, id_grado si existe el documento
            $sqlCount = 'db.senc_respuesta_encuesta.find({
                        	id_encuesta       : '.$idEncuesta.',
                        	year              : '.date("Y").',
                        	id_sede           : '.$SNGAula['nid_sede'].',
                        	'.$niveles.'
                        	tipo_encuesta     : '.$tipoEnc.'
                        }).count()';
            $resultCount = $db->execute('return '.utf8_encode($sqlCount));
            if($resultCount['ok'] == ERROR_MONGO){_log($sqlCount);
                throw new Exception('Errooooooooooooooor.......count vuelva a intentarlo por favor');
            }
            $cond = $resultCount['retval'];_log('$cond:::>>> '.$cond);
            //Inserta nuevo documento en caso no halla encontrado nada
            $nivelArray = null;
            //dfloresgonz 03.05.2016 Para el grafico 4 y botar el grafico separado por tipo de encuestado
            $descTipoEncuestado = ($tipoEnc == TIPO_ENCUESTA_LIBRE) ? "tipo_encuestado : \"".$tipoEncuestado."\"  ," : null;
            if($cond == INSERTA){
                $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
                $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
                $sql = 'db.senc_respuesta_encuesta.insert(
                       {
                         id_encuesta       :'.$idEncuesta.',
                    	 year              : '.date("Y").',
                    	 id_sede           : '.$SNGAula['nid_sede'].',
                    	 '.$niveles.'
                    	 tipo_encuesta     : '.$tipoEnc.',
                         fecha		       : "'.($fecha_apertura).'",      
                         desc_encuesta     : "'.$desc_encuesta.'",
                         preguntas         : [ ';
                if($tipoEncuestadoFirstLetter == PADRE   || $tipoEncuestadoFirstLetter  == ESTUDIANTE ||  $tipoEncuestadoFirstLetter == PERSONAL_ADMINISTRATIVO || 
                   $tipoEncuestadoFirstLetter == DOCENTE ||  $tipoEncuestadoFirstLetter == INVITADO) {                   
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else {
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Concatena los array por cada pregunta con su respuesta
                foreach($arrayPregRpta as $row) {
                    if($row['respuesta'] != null) {
                        $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                        $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                        $sql .= '{
                                id_pregunta    : '.$row['id_pregunta'].',
                                desc_pregunta  : "'.$pregunta.'",
                                id_respuesta   : '.$row['respuesta'].',
                                respuesta      : "'.strtoupper($respuesta).'",
                                id_dispositivo : '.$idDispositivo.',
                                '.$descTipoEncuestado.'
                                count          : 1
                             },';
                    }
                }
                $sql = substr($sql, 0, (strlen($sql)-1));
                $sql .= ']
                       }
                    )';
                $result = $db->execute('return '.utf8_encode($sql));
                $deleteInsert = 'db.senc_respuesta_encuesta.remove({
                                         id_encuesta   : '.$idEncuesta.',
                                         year          : '.date('Y').',
                                         id_sede       : '.$SNGAula['nid_sede'].',
                    	                 '.$niveles.'
                                         tipo_encuesta : '.$tipoEnc.'
                                     })';
                array_push($arrayRollBack, $deleteInsert);
                if($result['ok'] == ERROR_MONGO) {
                    throw new Exception('(ME-001)');
                }
                $data['error'] = SUCCESS_MONGO;
                //Detecta que lo que se realizara sera un update
            } else if($cond == ACTUALIZA) {
                if($tipoEncuestadoFirstLetter == PADRE   || $tipoEncuestadoFirstLetter  == ESTUDIANTE ||  $tipoEncuestadoFirstLetter == PERSONAL_ADMINISTRATIVO || 
                   $tipoEncuestadoFirstLetter == DOCENTE ||  $tipoEncuestadoFirstLetter == INVITADO) {                   
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else {
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Evalua por cada pregunta con respuesta si existe o no para verificar la siguiente acciï¿½n
                foreach($arrayPregRpta as $row) {
                    $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                    $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                    $sql = 'db.senc_respuesta_encuesta.update(
                       {     id_encuesta : '.$idEncuesta.',
							 year : '.date("Y").',
							 id_sede : '.$SNGAula['nid_sede'].',
							 '.$niveles.'
							 tipo_encuesta:'.$tipoEnc.'},
                             {$push: { preguntas: {
                    							id_pregunta : '.$row['id_pregunta'].',
                    							desc_pregunta : "'.$pregunta.'",
                    							id_respuesta  : '.$row['respuesta'].',
            								    respuesta   : "'.strtoupper($respuesta).'",
            								    id_dispositivo : '.$idDispositivo.',
            								    '.$descTipoEncuestado.'
            								    count       : 1
                    							}
                    	      }
                    	}
                    )';
                    $result = $db->execute('return '.utf8_encode($sql));
                    if($result['ok'] == ERROR_MONGO) { 
                        throw new Exception('Erroooooooooooorr............al actualizar respuesta encuesta');
                    }
                    $pullPreguntas = 'db.senc_respuesta_encuesta.update(
                                        { },
                                        {$pull : {preguntas : {
                                                    id_pregunta : '.$row['id_pregunta'].',
                        							id_respuesta  : '.$row['respuesta'].',
                                                    id_dispositivo : '.$idDispositivo.'}}},
                                        {multi : true}
                                    )';
                    array_push($arrayRollBack, $pullPreguntas);
                }        
                $data['error'] = SUCCESS_MONGO;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        $data['arrayRollBack'] = $arrayRollBack;
        return $data;
    }
    
    /**
     * @author Fernando Luna
     * @param arrayPreguntasRpta $arrayPregRpta
     * @param tipoEncuesta $tipoEnc
     * @param SedeNivelGradoArea $SNGAula
     * @return Inserta las respuestas en la colecciï¿½n senc_satisfacciï¿½n_encuesta
     */
    public function llenaEncSatistaccion($arrayPregRpta, $tipoEnc, $SNGAula, $idEncuesta, $arrayRollBack, $id_tipo_Enc){
        $data['error'] = ERROR_MONGO;
        try{
            $m   = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            $id_tipo_enc = $this->m_utils->getById('senc.tipo_encuesta','id_tipo_encuesta', 'id_tipo_encuesta', $idEncuesta);
            $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
            $desc_tipo_enc = $this->getDescTipoEncByIdTipoEnc($id_tipo_Enc);
            $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
            $sqlCount = null;
            $tipoEncuestado = $this->session->userdata('tipoEncuestadoLibre');
            $niveles = null;
            if($tipoEncuestado != null){
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E'){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }else if($tipoEncuestado == 'A'){
                    $niveles = 'id_area : '.$SNGAula['nid_area'].',';
                }else if($tipoEncuestado == 'D'){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_area  : '.$SNGAula['nid_area'].',';
                }else if($tipoEncuestado == 'I'){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }
            } else{
                $niveles = ''.(($tipoEnc == TIPO_ENCUESTA_DOCENTE || $tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_nivel : '.$SNGAula['nid_nivel'].',' : null).'
                    	   '.(($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_grado : '.$SNGAula['nid_grado'].',' : null).'';
            }
            //Busca por las pk id_encuesta, year, id_sede, id_nivel, id_grado si existe el documento
            $sqlCount = 'db.senc_satisfaccion_encuesta.find({
                    	id_encuesta       : '.$idEncuesta.',
                    	year              : '.date("Y").',
                    	id_sede           : '.$SNGAula['nid_sede'].',
                    	'.$niveles.'  
                    	id_tipo_encuesta  : '.$tipoEnc.'
                    }).count()';
            $resultCount = $db->execute('return '.utf8_encode($sqlCount));
            if($resultCount['ok'] == ERROR_MONGO){
                throw new Exception('Error.............. count satisfaccion_encuesta');
            }
            $cond = $resultCount['retval'];
            //Inserta nuevo documento en caso no halla encontrado nada
            $nivelArray = null;
            if($cond == INSERTA){
                $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
                
                $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
                $sql = 'db.senc_satisfaccion_encuesta.insert(
                       {
                         id_encuesta          : '.$idEncuesta.',
                    	 year                 : '.date("Y").',
                    	 id_sede              : '.$SNGAula['nid_sede'].',
                    	 '.$niveles.'
                    	 id_tipo_encuesta     : '.$tipoEnc.',
                         fecha		          : "'.($fecha_apertura).'",
                         desc_encuesta        : "'.$desc_encuesta.'",
                         desc_tipo_encuesta   : "'.strtoupper($desc_tipo_enc).'",
                         preguntas         : [ ';
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else{
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Concatena los array por cada pregunta con su respuesta
                foreach($arrayPregRpta as $row) {
                    $idTipoPregunta = $this->getIdTipoPreguntaByPreguntaEncuesta($row['id_pregunta'],$idEncuesta);
                    if($idTipoPregunta == 1 || $idTipoPregunta == 2 || $idTipoPregunta == 8) {
                        $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                        $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                        $tipo_serv = $this->m_utils->getById('senc.preguntas', '_id_servicio', 'id_pregunta', $row['id_pregunta']);
                        $tipo_serv = ($tipo_serv == null) ? 0 : $tipo_serv;
                        $sql .= '{
                                id_pregunta   : '.$row['id_pregunta'].',
                                desc_pregunta : "'.$pregunta.'",
                                tipo_servicio : '.$tipo_serv.',     
                                '.$nivelArray.'
                                '.(($row['respuesta'] == 4 || $row['respuesta'] == 5) ? 'count : 1' : 'count:0') .',
                                cant_participantes : 1
                             },';
                    }
                }
                $sql = substr($sql, 0, (strlen($sql)-1));
                $sql .= ']
                       }
                    )';
                $deleteInsert = 'db.senc_satisfaccion_encuesta.remove({
                                         id_encuesta   : '.$idEncuesta.',
                                         year          : '.date('Y').',
                                         id_sede       : '.$SNGAula['nid_sede'].',
                    	                 '.$niveles.'
                                         id_tipo_encuesta : '.$tipoEnc.'
                                     })';
                array_push($arrayRollBack, $deleteInsert);
                $result = $db->execute('return '.utf8_encode($sql));
                if($result['ok'] == ERROR_MONGO){
                    throw new Exception('Error.............. insertar satisfaccion_encuesta');
                }
                $data['error'] = SUCCESS_MONGO;
                //Detecta que lo que se realizara sera un update
            } else if($cond >= ACTUALIZA){
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else{
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Evalua por cada pregunta con respuesta si existe o no para verificar la siguiente acciï¿½n
                foreach($arrayPregRpta as $row) {
                    $idTipoPregunta = $this->getIdTipoPreguntaByPreguntaEncuesta($row['id_pregunta'],$idEncuesta);
                    if($idTipoPregunta == 1 || $idTipoPregunta == 2 || $idTipoPregunta == 8){
                        if($row['respuesta'] != null) {
                            $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                            //@PENDIENTE - RICARDO VASQUEZ
                            /*$tipo_serv = $this->m_utils->getById('preguntas', '_id_servicio', 'id_pregunta', $row['id_pregunta'], 'senc');
                            $tipo_serv = ($tipo_serv == null) ? 0 : $tipo_serv;*/
                            $sqlCount ='db.senc_satisfaccion_encuesta.find({
                                         id_encuesta    : '.$idEncuesta.',
                                         year          : '.date("Y").',
                                         id_sede       : '.$SNGAula['nid_sede'].',
                                         '.$niveles.'
                                         id_tipo_encuesta : '.$tipoEnc.',
                                    	 preguntas: {
                                        		$elemMatch: {
                                                        id_pregunta   : '.$row['id_pregunta'].',
                                        				'.$nivelArray.'
                                        				}
                                              }
                                    }).count()';
                            $resultCount = $db->execute('return '.utf8_encode($sqlCount));
                            if($resultCount['ok'] == ERROR_MONGO){
                                throw new Exception('Error.............. verifica siguiente accion satisfaccion_encuesta');
                            }
                            $cond = $resultCount['retval'];
                        }
                        //Realiza un update al contador del array incrementandolo en 1
                        if($cond == ACTUALIZAR_CONT){
                            if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                                $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                            } else{
                                $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                            }
                            $sql = 'db.senc_satisfaccion_encuesta.update(
                                       { 	 id_encuesta : '.$idEncuesta.',
                							 year : '.date("Y").',
                							 id_sede : '.$SNGAula['nid_sede'].',
                							 '.$niveles.'
                							 id_tipo_encuesta:'.$tipoEnc.',
                							 preguntas: {
                                        		$elemMatch: {
                							     '.$nivelArray.'
                								  id_pregunta : '.$row['id_pregunta'].'
                								  }
                                              }
                					   },
                					    { $inc: { "preguntas.$.count": '.(($row['respuesta'] == 4 || $row['respuesta'] == 5) ? '1' : '0').', "preguntas.$.cant_participantes" : 1 } }
                                    )';
                            $countMenos = 'db.senc_satisfaccion_encuesta.update(
                                               { 	 id_encuesta : '.$idEncuesta.',
                        							 year : '.date("Y").',
                        							 id_sede : '.$SNGAula['nid_sede'].',
                        							 '.$niveles.'
                        							 id_tipo_encuesta:'.$tipoEnc.',
                        							 preguntas: {
                                                		$elemMatch: {
                        							     '.$nivelArray.'
                        								  id_pregunta : '.$row['id_pregunta'].'
                        								  }
                                                      }
                        					   },
                        					    { $inc: { "preguntas.$.count": '.(($row['respuesta'] == 4 || $row['respuesta'] == 5) ? '-1' : '0').', "preguntas.$.cant_participantes" : -1 } }
                                            )';
                            array_push($arrayRollBack, $countMenos);
                            $result = $db->execute('return '.$sql);
                            if($result['ok'] == ERROR_MONGO){
                                throw new Exception('Error.............. actualiza contador satisfaccion_encuesta');
                            }
                            //Realiza un update al array haciendole un push
                        } else if($cond == ACTUALIZAR_ARRAY){
                            if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                                $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                            } else{
                                $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                            }
                            $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                            $tipo_serv = $this->m_utils->getById('senc.preguntas', '_id_servicio', 'id_pregunta', $row['id_pregunta']);
                            $tipo_serv = ($tipo_serv == null) ? 0 : $tipo_serv;
                            $sql = 'db.senc_satisfaccion_encuesta.update(
                                       {     id_encuesta : '.$idEncuesta.',
                							 year : '.date("Y").',
                							 id_sede : '.$SNGAula['nid_sede'].',
                							 '.$niveles.'
                							 id_tipo_encuesta:'.$tipoEnc.'},
                                       { $push: { preguntas: {
                                    							id_pregunta   : '.$row['id_pregunta'].',
                                    							desc_pregunta : "'.strtoupper($pregunta).'",
                                    							tipo_servicio : '.$tipo_serv.',
                            								    '.$nivelArray.'
                            								    '.(($row['respuesta'] == 4 || $row['respuesta'] == 5) ? 'count : 1' : 'count:0') .',
                            								     cant_participantes : 1
                                    							}
                                    			}
                                    	}
                                    )';
                            $pullPreguntas = 'db.senc_satisfaccion_encuesta.update(
                                                 { },
                                                 {$pull : {preguntas : {
                                                           '.$nivelArray.'
                                                           id_pregunta   : '.$row['id_pregunta'].'
                                                           }}},
                                                 {multi : true}
                                             )';
                            array_push($arrayRollBack, $pullPreguntas);
                            $result = $db->execute('return '.utf8_encode($sql));
                            if($result['ok'] == ERROR_MONGO){                               
                                throw new Exception('Error.............. actualiza array satisfaccion_encuesta');
                            }
                        }
                    }
                }
               
                $data['error'] = SUCCESS_MONGO;
            }
        } catch(Exception $e){_log('catch mongo:: '.$e->getMessage());
            $data['msj'] = $e->getMessage();
        }
        $data['arrayRollBack'] = $arrayRollBack;
        return $data;
    }
    
    /**
     * @author Fernando Luna
     * @param arrayPreguntasRpta $arrayPregRpta
     * @param tipoEncuesta $tipoEnc
     * @param SedeNivelGradoArea $SNGAula
     * @return Inserta las respuestas en la colecciï¿½n senc_insatisfaccion_encuesta
     */
    public function llenaEncInsatistaccion($arrayPregRpta, $tipoEnc, $SNGAula, $idEncuesta, $arrayRollBack, $id_tipo_Enc){
        $data['error'] = ERROR_MONGO;
        try{
            $m   = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            $id_tipo_enc = $this->m_utils->getById('senc.tipo_encuesta','id_tipo_encuesta', 'id_tipo_encuesta', $idEncuesta);
            $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
            $desc_tipo_enc = $this->getDescTipoEncByIdTipoEnc($id_tipo_Enc);
            $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
            $sqlCount = null;
            $tipoEncuestado = $this->session->userdata('tipoEncuestadoLibre');
            $niveles = null;
            if($tipoEncuestado != null){
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E') {
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }else if($tipoEncuestado == 'A') {
                    $niveles = 'id_area : '.$SNGAula['nid_area'].',';
                }else if($tipoEncuestado == 'D') {
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_area  : '.$SNGAula['nid_area'].',';
                }else if($tipoEncuestado == 'I') {
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }
            } else {
                $niveles = ''.(($tipoEnc == TIPO_ENCUESTA_DOCENTE || $tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_nivel : '.$SNGAula['nid_nivel'].',' : null).'
                    	   '.(($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_grado : '.$SNGAula['nid_grado'].',' : null).'';
            }
            //Busca por las pk id_encuesta, year, id_sede, id_nivel, id_grado si existe el documento
            $sqlCount = 'db.senc_insatisfaccion_encuesta.find({
                    	id_encuesta       : '.$idEncuesta.',
                    	year              : '.date("Y").',
                    	id_sede           : '.$SNGAula['nid_sede'].',
                    	'.$niveles.'
                    	id_tipo_encuesta  : '.$tipoEnc.'
                    }).count()';
            $resultCount = $db->execute('return '.utf8_encode($sqlCount));
            if($resultCount['ok'] == ERROR_MONGO){
                throw new Exception('Errooooooooooooooooor......... count insertar actualizar insatisfaccion encuesta');
            }
            $cond = $resultCount['retval'];
            //Inserta nuevo documento en caso no halla encontrado nada
            $nivelArray = null;
            if($cond == INSERTA){
                $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
        
                $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
                $sql = 'db.senc_insatisfaccion_encuesta.insert(
                       {
                         id_encuesta          : '.$idEncuesta.',
                    	 year                 : '.date("Y").',
                    	 id_sede              : '.$SNGAula['nid_sede'].',
                    	 '.$niveles.'
                    	 id_tipo_encuesta     : '.$tipoEnc.',
                         fecha		          : "'.($fecha_apertura).'",
                         desc_encuesta        : "'.$desc_encuesta.'",
                         desc_tipo_encuesta   : "'.strtoupper($desc_tipo_enc).'",
                         preguntas         : [ ';
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else{
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Concatena los array por cada pregunta con su respuesta
                foreach($arrayPregRpta as $row) {
                    $idTipoPregunta = $this->getIdTipoPreguntaByPreguntaEncuesta($row['id_pregunta'],$idEncuesta);
                    if($idTipoPregunta == 1 || $idTipoPregunta == 2 || $idTipoPregunta == 8){
                        $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                        $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                        $sql .= '{
                                id_pregunta   : '.$row['id_pregunta'].',
                                desc_pregunta : "'.$pregunta.'",
                                '.$nivelArray.'
                                '.(($row['respuesta'] == 1 || $row['respuesta'] == 2) ? 'count : 1' : 'count:0') .',
                                cant_participantes : 1
                             },';
                    }
                }
                $sql = substr($sql, 0, (strlen($sql)-1));
                $sql .= ']
                       }
                    )';
                $deleteInsert = 'db.senc_insatisfaccion_encuesta.remove({
                                         id_encuesta   : '.$idEncuesta.',
                                         year          : '.date('Y').',
                                         id_sede       : '.$SNGAula['nid_sede'].',
                    	                 '.$niveles.'
                                         id_tipo_encuesta : '.$tipoEnc.'
                                     })';
                array_push($arrayRollBack, $deleteInsert);
                $result = $db->execute('return '.utf8_encode($sql));
                if($result['ok'] == ERROR_MONGO){
                    throw new Exception('Errooooooooooooooooor......... insertar insatisfaccion_encuesta');
                }
                $data['error'] = SUCCESS_MONGO;
                //Detecta que lo que se realizara sera un update
            } else if($cond >= ACTUALIZA){
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else{
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Evalua por cada pregunta con respuesta si existe o no para verificar la siguiente acciï¿½n
                foreach($arrayPregRpta as $row) {
                    $idTipoPregunta = $this->getIdTipoPreguntaByPreguntaEncuesta($row['id_pregunta'],$idEncuesta);
                    if($idTipoPregunta == 1 || $idTipoPregunta == 2 || $idTipoPregunta == 8){
                        if($row['respuesta'] != null) {
                            $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                            $sqlCount ='db.senc_insatisfaccion_encuesta.find({
                                         id_encuesta    : '.$idEncuesta.',
                                         year          : '.date("Y").',
                                         id_sede       : '.$SNGAula['nid_sede'].',
                                         '.$niveles.'
                                         id_tipo_encuesta : '.$tipoEnc.',
                                    	 preguntas: {
                                        		$elemMatch: {
                                                        '.$nivelArray.'
                                                        id_pregunta   : '.$row['id_pregunta'].'
                                        				}
                                              }
                                    }).count()';
                            $resultCount = $db->execute('return '.utf8_encode($sqlCount));
                            if($resultCount['ok'] == ERROR_MONGO){
                                throw new Exception('Errooooooooooooooooor......... count verificar siguiente accion insatisfaccion encuesta');
                            }
                            $cond = $resultCount['retval'];
                        }
                        //Realiza un update al contador del array incrementandolo en 1
                        $nivelArray = null;
                        if($cond == ACTUALIZAR_CONT){
                            if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                                $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                            } else{
                                $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                            }
                            $sql = 'db.senc_insatisfaccion_encuesta.update(
                                       { 	 id_encuesta : '.$idEncuesta.',
                							 year : '.date("Y").',
                							 id_sede : '.$SNGAula['nid_sede'].',
                							 '.$niveles.'
                							 id_tipo_encuesta:'.$tipoEnc.',
                							 preguntas: {
                                        		$elemMatch: {
                								  id_pregunta : '.$row['id_pregunta'].',
                								  '.$nivelArray.'
                								  }
                                              }
                					   },
                					    { $inc: { "preguntas.$.count": '.(($row['respuesta'] == 1 || $row['respuesta'] == 2) ? '1' : '0').', "preguntas.$.cant_participantes" : 1 } }
                                    )';
                            $result = $db->execute('return '.$sql);
                            $countMenos = 'db.senc_insatisfaccion_encuesta.update(
                                               { 	 id_encuesta : '.$idEncuesta.',
                        							 year : '.date("Y").',
                        							 id_sede : '.$SNGAula['nid_sede'].',
                        							 '.$niveles.'
                        							 id_tipo_encuesta:'.$tipoEnc.',
                        							 preguntas: {
                                                		$elemMatch: {
                        							     '.$nivelArray.'
                        								  id_pregunta : '.$row['id_pregunta'].'
                        								  }
                                                      }
                        					   },
                        					    { $inc: { "preguntas.$.count": '.(($row['respuesta'] == 1 || $row['respuesta'] == 2) ? '-1' : '0').', "preguntas.$.cant_participantes" : -1 } }
                                            )';
                            array_push($arrayRollBack, $countMenos);
                            if($result['ok'] == ERROR_MONGO){
                                throw new Exception('Errooooooooooooooooor......... actualizar contador insatisfaccion encuesta');
                            }
                            //Realiza un update al array haciendole un push
                        } else if($cond == ACTUALIZAR_ARRAY){
                            if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                                $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                            } else{
                                $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                            }
                            $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                            $sql = 'db.senc_insatisfaccion_encuesta.update(
                                       {     id_encuesta : '.$idEncuesta.',
                							 year : '.date("Y").',
                							 id_sede : '.$SNGAula['nid_sede'].',
                							 '.$niveles.'
                							 id_tipo_encuesta:'.$tipoEnc.'},
                                       { $push: { preguntas: {
                                    							id_pregunta   : '.$row['id_pregunta'].',
                                    							desc_pregunta : "'.$pregunta.'",
                            								    '.$nivelArray.'
                            								    '.(($row['respuesta'] == 1 || $row['respuesta'] == 2) ? 'count : 1' : 'count:0') .',
                            								     cant_participantes : 1
                                    							}
                                    			}
                                    	}
                                    )';
                            $result = $db->execute('return '.utf8_encode($sql));
                            $pullPreguntas = 'db.senc_insatisfaccion_encuesta.update(
                                                 { },
                                                 {$pull : {preguntas : {
                                                           '.$nivelArray.'
                                                           id_pregunta   : '.$row['id_pregunta'].'
                                                           }}},
                                                 {multi : true}
                                             )';
                            array_push($arrayRollBack, $pullPreguntas);
                            if($result['ok'] == ERROR_MONGO){
                                throw new Exception('Errooooooooooooooooor......... actualizar array insatisfaccion encuesta');
                            }
                        }
                    }
                }
                $data['error'] = SUCCESS_MONGO;
            }
        }catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        $data['arrayRollBack'] = $arrayRollBack;
        return $data;
    }
     
    /**
     * @author Fernando Luna
     * @param arrayPreguntasRpta $arrayPregRpta
     * @param tipoEncuesta $tipoEnc
     * @param SedeNivelGradoArea $SNGAula
     * @return Inserta las respuestas en la colecciï¿½n senc_propuesta_mejora_encuesta
     */
    function insertPropuMejora($arrayProMejoRpta, $tipoEnc,$SNGAula,$idEncuesta,$arrayRollBack){
        $data['error'] = ERROR_MONGO; 
        try{
            $m   = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            $tipoEncuestado = $this->session->userdata('tipoEncuestadoLibre');
            $niveles = null;
            if($tipoEncuestado != null){
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E'){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }else if($tipoEncuestado == 'A'){
                    $niveles = 'id_area : '.$SNGAula['nid_area'].',';
                }else if($tipoEncuestado == 'D'){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_area  : '.$SNGAula['nid_area'].',';
                }else if($tipoEncuestado == 'I'){
                    $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                id_grado : '.$SNGAula['nid_grado'].',
                                id_aula  : '.$SNGAula['nid_aula'].',';
                }
            } else{
                $niveles = ''.(($tipoEnc == TIPO_ENCUESTA_DOCENTE || $tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_nivel : '.$SNGAula['nid_nivel'].',' : null).'
                    	   '.(($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_grado : '.$SNGAula['nid_grado'].',' : null).'';
            }
            $sqlCount = null;
            //Busca por las pk id_encuesta, year, id_sede, id_nivel, id_grado si existe el documento
            $sqlCount = 'db.senc_propuesta_mejora_encuesta.find({
                    	id_encuesta       : '.$idEncuesta.',
                    	year              : '.date("Y").',
                    	id_sede           : '.$SNGAula['nid_sede'].',
                    	'.$niveles.'
                    	tipo_encuesta     : '.$tipoEnc.'
                    }).count()';
            $resultCount = $db->execute('return '.utf8_encode($sqlCount));
            if($resultCount['ok'] == ERROR_MONGO){
                throw new Exception('Errooooooooooooooooooooor.................... count insertar actualizar propuesta de mejora');
            }
            $cond = $resultCount['retval'];
            //Inserta nuevo documento en caso no halla encontrado nada
            $nivelArray = null;
            if($cond == INSERTA){
                $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
                $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
                $sql = 'db.senc_propuesta_mejora_encuesta.insert(
                       {
                         id_encuesta       :'.$idEncuesta.',
                    	 year              : '.date("Y").',
                    	 id_sede           : '.$SNGAula['nid_sede'].',
                    	 '.$niveles.'
                    	 tipo_encuesta     : '.$tipoEnc.',
                         fecha		       : "'.($fecha_apertura).'",
                         desc_encuesta     : "'.strtoupper($desc_encuesta).'",
                         cant_participantes : 1,
                         preguntas         : [ ';
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else{
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Concatena los array por cada pregunta con su respuesta
                foreach($arrayProMejoRpta as $row) {                  
                        $desc_propuesta = $this->m_utils->getById('senc.propuesta_mejora','desc_propuesta', 'id_propuesta', $row['id_propuesta']);
                        $sql .= '{
                                id_propuesta   : '.$row['id_propuesta'].',
                                desc_propuesta : "'.$desc_propuesta.'",
                                '.$nivelArray.'
                                count         : 1
                             },';
                }
                $sql = substr($sql, 0, (strlen($sql)-1));
                $sql .= ']
                       }
                    )';
                $result = $db->execute('return '.utf8_encode($sql));
                $deleteInsert = 'db.senc_propuesta_mejora_encuesta.remove({
                                         id_encuesta   : '.$idEncuesta.',
                                         year          : '.date('Y').',
                                         id_sede       : '.$SNGAula['nid_sede'].',
                    	                 '.$niveles.'
                                         tipo_encuesta : '.$tipoEnc.'
                                     })';
                array_push($arrayRollBack, $deleteInsert);
                if($result['ok'] == ERROR_MONGO){
                    throw new Exception('Errooooooooooooooooooooor.................... insertar propuesta de mejora');
                }
                $data['error'] = SUCCESS_MONGO;
                //Detecta que lo que se realizara sera un update
            } else if($cond >= ACTUALIZA){
                if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                    $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                } else{
                    $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                }
                //Evalua por cada pregunta con respuesta si existe o no para verificar la siguiente acciï¿½n
                foreach($arrayProMejoRpta as $row) {
                    $desc_propuesta = $this->m_utils->getById('senc.propuesta_mejora','desc_propuesta', 'id_propuesta', $row['id_propuesta']);
                        $sqlCount ='db.senc_propuesta_mejora_encuesta.find({
                                 id_encuesta    : '.$idEncuesta.',
                                 year          : '.date("Y").',
                                 id_sede       : '.$SNGAula['nid_sede'].',
                                 '.$niveles.'
                                 tipo_encuesta : '.$tipoEnc.',
                            	 preguntas: {
                                		$elemMatch: {
                                                '.$nivelArray.'
                                                id_propuesta   : '.$row['id_propuesta'].',
                                				}
                                      }
                            }).count()';
                        $resultCount = $db->execute('return '.utf8_encode($sqlCount));
                        if($resultCount['ok'] == ERROR_MONGO){
                            throw new Exception('Errooooooooooooooooooooor.................... evaluar actualizar contador o array propuesta de mejora');
                        }
                        $cond = $resultCount['retval'];
                    //Realiza un update al contador del array incrementandolo en 1
                    if($cond == ACTUALIZAR_CONT){
                        if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                            $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                        } else{
                            $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                        }
                        $sql = 'db.senc_propuesta_mejora_encuesta.update(
                           { 	 id_encuesta : '.$idEncuesta.',
    							 year : '.date("Y").',
    							 id_sede : '.$SNGAula['nid_sede'].',
    							 '.$niveles.'
    							 tipo_encuesta:'.$tipoEnc.',
    							 preguntas: {
                            		$elemMatch: {
    							                '.$nivelArray.'
    								            id_propuesta   : '.$row['id_propuesta'].'
    								  }
                                  }
    					   },
    					    { $inc: { "preguntas.$.count": 1 } }
                        )';
                        $result = $db->execute('return '.$sql);
                        $countMenos = 'db.senc_propuesta_mejora_encuesta.update(
                                               { 	 id_encuesta : '.$idEncuesta.',
                        							 year : '.date("Y").',
                        							 id_sede : '.$SNGAula['nid_sede'].',
                        							 '.$niveles.'
                        							 id_tipo_encuesta:'.$tipoEnc.',
                        							 preguntas: {
                                                		$elemMatch: {
                        							      '.$nivelArray.'
                        								  id_propuesta : '.$row['id_propuesta'].',
                        								  }
                                                      }
                        					   },
                        					    { $inc: { "preguntas.$.count": -1 } }
                                            )';
                        array_push($arrayRollBack, $countMenos);
                        if($result['ok'] == ERROR_MONGO){
                            throw new Exception('Errooooooooooooooooooooor.................... actualiza contador propuesta de mejora');
                        }
                        //Realiza un update al array haciendole un push
                    } else if($cond == ACTUALIZAR_ARRAY){
                        if($tipoEncuestado == 'P' || $tipoEncuestado == 'E' || $tipoEncuestado == 'A' || $tipoEncuestado == 'D' || $tipoEncuestado == 'I'){
                            $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                        } else{
                            $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                        }
                        $sql = 'db.senc_propuesta_mejora_encuesta.update(
                           {     id_encuesta : '.$idEncuesta.',
    							 year : '.date("Y").',
    							 id_sede : '.$SNGAula['nid_sede'].',
    							 '.$niveles.'
    							 tipo_encuesta:'.$tipoEnc.'},
                           { $push: { preguntas: {
                        							id_propuesta   : '.$row['id_propuesta'].',
                        							desc_propuesta : "'.$desc_propuesta.'",
                                                    '.$nivelArray.'
                								    count       : 1
                        							}
                        			}
                        	}
                        )';
                        $result = $db->execute('return '.utf8_encode($sql));
                        $pullPreguntas = 'db.senc_propuesta_mejora_encuesta.update(
                                                 { },
                                                 {$pull : {preguntas : {
                                                           '.$nivelArray.'
                                                           id_propuesta   : '.$row['id_propuesta'].'
                                                           }}},
                                                 {multi : true}
                                             )';
                        array_push($arrayRollBack, $pullPreguntas);
                        if($result['ok'] == ERROR_MONGO){
                            throw new Exception('Errooooooooooooooooooooor.................... actualiza array propuesta de mejora');
                        }
                    }
                }
                //Incrementa el contador de cantidad participantes en cada propuesta de mejora
                $sql = 'db.senc_propuesta_mejora_encuesta.update(
                           { 	 id_encuesta : '.$idEncuesta.',
    							 year : '.date("Y").',
    							 id_sede : '.$SNGAula['nid_sede'].',
    							 '.$niveles.'
    							 tipo_encuesta:'.$tipoEnc.',
    							 preguntas: {
                            		$elemMatch: {
                                                '.(substr($nivelArray, 0, (strlen($nivelArray)-1))).'
    								  }
                                  }
    					   },
    					    { $inc: { "cant_participantes": 1 } }
                        )';
                $result = $db->execute('return '.$sql);
                $countMenos = 'db.senc_propuesta_mejora_encuesta.update(
                                               { 	 id_encuesta : '.$idEncuesta.',
                        							 year : '.date("Y").',
                        							 id_sede : '.$SNGAula['nid_sede'].',
                        							 '.$niveles.'
                        							 id_tipo_encuesta:'.$tipoEnc.',
                        							 preguntas: {
                                                		$elemMatch: {
                        								  '.(substr($nivelArray, 0, (strlen($nivelArray)-1))).'
                        								  }
                                                      }
                        					   },
                        					    { $inc: { "cant_participantes": -1 } }
                                            )';
                array_push($arrayRollBack, $countMenos);
                if($result['ok'] == ERROR_MONGO){
                    throw new Exception('Errooooooooooooooooooooor.................... actualiza cantidad_participantes propuesta de mejora general');
                }
                $data['error'] = SUCCESS_MONGO;
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        $data['arrayRollBack'] = $arrayRollBack;
        return $data;
    }
    
    function getAllEncuestas(){
        $sql="  SELECT e.id_encuesta,
                       e.titulo_encuesta,
                       e.audi_pers_regi,
                       e.audi_nomb_regi,
                       e.desc_enc,
                       to_char(e.fecha_apertura,'DD/MM/YYYY') fecha_apertura,
                       to_char(e.fecha_cierre,'DD/MM/YYYY') fecha_cierre,
                       e.flg_estado,
                       te.desc_tipo_encuesta,
                       to_char(e.fecha_registro,'DD/MM/YYYY') fecha_registro,
                       e._id_tipo_encuesta,
                       COALESCE(e.cant_encuestados, 0) AS cant_encuestados
                  FROM senc.encuesta      e,
                       senc.tipo_encuesta te
                 WHERE te.id_tipo_encuesta = e._id_tipo_encuesta
                ORDER BY e.flg_estado ASC,e.fecha_registro";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllEncuestasByPersona($idPersona){
        $sql="  SELECT e.id_encuesta,
                       e.titulo_encuesta,
                       e.audi_pers_regi,
                       e.audi_nomb_regi,
                       e.desc_enc,
                       to_char(e.fecha_apertura,'DD/MM/YYYY') fecha_apertura,
                       to_char(e.fecha_cierre,'DD/MM/YYYY') fecha_cierre,
                       e.flg_estado,
                       te.desc_tipo_encuesta,
                       to_char(e.fecha_registro,'DD/MM/YYYY') fecha_registro,
                       e._id_tipo_encuesta,
                       COALESCE(e.cant_encuestados, 0) AS cant_encuestados
                  FROM senc.encuesta      e,
                       senc.tipo_encuesta te
                 WHERE te.id_tipo_encuesta = e._id_tipo_encuesta
                   AND e.audi_pers_regi    = ?
                UNION
                SELECT e.id_encuesta,
                       e.titulo_encuesta,
                       e.audi_pers_regi,
                       e.audi_nomb_regi,
                       e.desc_enc,
                       to_char(e.fecha_apertura,'DD/MM/YYYY') fecha_apertura,
                       to_char(e.fecha_cierre,'DD/MM/YYYY') fecha_cierre,
                       e.flg_estado,
                       te.desc_tipo_encuesta,
                       to_char(e.fecha_registro,'DD/MM/YYYY') fecha_registro,
                       e._id_tipo_encuesta,
                       COALESCE(e.cant_encuestados, 0) AS cant_encuestados
                  FROM senc.encuesta e,
                       senc.tipo_encuesta te
	 		     WHERE te.id_tipo_encuesta = e._id_tipo_encuesta
                   AND id_encuesta IN (SELECT id_encuesta
				 					     FROM (SELECT id_encuesta,
					 							      (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS id_persona
					 						     FROM senc.encuesta) AS t
											    WHERE t.id_persona = ? )
              ORDER BY fecha_registro, flg_estado DESC";
        $result = $this->db->query($sql, array($idPersona, $idPersona));
        return $result->result();
    }
    
    function cambiaEstadoEncuesta($idEncuesta, $arrayData, $idTipoEnc){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->where('id_encuesta' , $idEncuesta);
            $this->db->update('senc.encuesta',$arrayData);
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo cambiar el estado');
            }
            $data['error'] = EXIT_SUCCESS;
            if($arrayData['flg_estado'] == ENCUESTA_BLOQUEADA){
                $this->db->where('id_encuesta' , $idEncuesta);
                $this->db->update('senc.encuesta',array('cant_encuestados' => null));
                if($this->db->affected_rows() != 1){
                    throw new Exception('No se actualizï¿½ la cantidad de participantes');
                }
            }
            $data['msj']   = MSJ_UPT;
            if($idTipoEnc != ENCUESTA_APERTURADA){
                $this->db->trans_commit();
            }
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
  
    function insertDescProp($arrayInsert){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = MSJ_INSERT_ERROR;
        try{
            $this->db->insert('senc.propuesta_mejora',$arrayInsert);
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo insertar el dato');
            }
            $rpta['id_propInsert'] = $this->db->insert_id();
            $rpta['error']      = EXIT_SUCCESS;
            $rpta['msj']        = MSJ_INSERT_SUCCESS;
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    function getPropuestas(){
        $sql = "SELECT id_propuesta,
                       desc_propuesta 
                  FROM senc.propuesta_mejora
                 WHERE flg_estado = ?";
        $result = $this->db->query($sql,array(FLG_ESTADO));
        $arryPropuestas = array();
        foreach($result->result() as $row){
            array_push($arryPropuestas, array("id" => $row->id_propuesta, "name" => utf8_encode($row->desc_propuesta) ));           
        }
        return $arryPropuestas;
    }
    
    /**
     * @author Cesar Villarreal Carhuas 18/03/2016
     * @param array de los datos a ingresar $arrayData
     */
    function saveEncuestaInactiva($arrayData){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('senc.encuesta',$arrayData);
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo ingresar la encuesta');
            }
            $data['idEncuesta'] = $this->db->insert_id();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    /**
     * @author Cï¿½sar Villarreal 04/04/16
     * @param arrayPregCate $arrayPregCatEnc
     * @param arrayUpdate $arrayUpdate
     * @param arrayDelete $arrayDelete
     * @param arrayDeleteOpt $arrayPregDelOpt
     * @param idEncuesta $idEncuesta
     * @return Inserta, actualiza o elimina dependiendo de lo que llega en cada array por cada encuesta
     */
    function insertaPregCateByEncuesta($arrayPregCatEnc,$arrayUpdate,$arrayDelete,$arrayPregDelOpt,$idEncuesta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $cont = 0;
            //INSERT
            foreach ($arrayPregCatEnc as $row){
                $this->db->insert('senc.pregunta_x_enc_cate',$row);
                if($this->db->affected_rows() != 1 ){
                    throw new Exception(ANP);
                }
            }
            //UPDATE
            foreach($arrayUpdate as $row){
                $idTipoPreg = $this->getLastIdTipoPreguntaByPreguntaEncuesta($row['_id_pregunta'],$row['_id_encuesta']);
                if($idTipoPreg != $row['_id_tipo_pregunta'] || ($row['_id_tipo_pregunta'] == CINCO_CARITAS || $row['_id_tipo_pregunta'] == CUATRO_CARITAS || $row['_id_tipo_pregunta'] == TRES_CARITAS || $row['_id_tipo_pregunta'] == DOS_OPCIONES)){
                    $data = $this->deleteAllAlternativasByPreguntaEncuesta($row['_id_pregunta'],$row['_id_encuesta']);
                }
                if($row['_id_tipo_pregunta'] == CINCO_CARITAS || $row['_id_tipo_pregunta'] == CUATRO_CARITAS || $row['_id_tipo_pregunta'] == TRES_CARITAS || $row['_id_tipo_pregunta'] == DOS_OPCIONES){
                    $caritas = $this->getAlternativasByTipo($row['_id_tipo_pregunta']);
                    $arrayAlternativas = array();
                    $cont = count($caritas);
                    foreach($caritas as $rowCarita){
                        array_push($arrayAlternativas, array('_id_alternativa'   => $rowCarita->id_alternativa,
                                                             '_id_tipo_pregunta' => $row['_id_tipo_pregunta'],
                                                             '_id_encuesta'      => $row['_id_encuesta'],
                                                             '_id_pregunta'      => $row['_id_pregunta'],
                                                             'orden'             => $cont
                                                        ));
                        $cont--;
                    }
                    $this->insertNewCaritasAlternativas($arrayAlternativas);
                }
                $this->db->where('_id_encuesta'  , $row['_id_encuesta']);
                $this->db->where('_id_pregunta'  , $row['_id_pregunta']);
                $this->db->where('_id_categoria' , $row['_id_categoria']);
                unset($row['_id_encuesta']);
                unset($row['_id_pregunta']); 
                unset($row['_id_categoria']);
                $this->db->update('senc.pregunta_x_enc_cate',$row);
            }
            //NUEVO
            $actualOrdem = (count($arrayDelete) == 1) ? $this->getActualOrdenByPreguntaEncCate($idEncuesta,$arrayDelete[0]['_id_categoria'],$arrayDelete[0]['_id_pregunta']) : null;
            ///////
            foreach($arrayDelete as $row){
                $data = $this->deleteAllAlternativasByPreguntaEncuesta($row['_id_pregunta'], $row['_id_encuesta']);
                if($data['error'] == EXIT_ERROR){
                    throw new Exception('No se eliminaron las alternativas');
                }
                $this->db->where('_id_encuesta'  , $row['_id_encuesta']);
                $this->db->where('_id_pregunta'  , $row['_id_pregunta']);
                $this->db->where('_id_categoria' , $row['_id_categoria']);
                $this->db->delete('senc.pregunta_x_enc_cate');
            }
            //NUEVO
            if(count($arrayDelete) == 1){
                $this->updateOrdenByEncuestaCategoria($idEncuesta,$arrayDelete[0]['_id_categoria'],$actualOrdem);
            }
            ///////
            //DELETE
            $data['msj']   = 'SE REGISTRï¿½ LA ENCUESTA';
            $data['error'] = EXIT_SUCCESS;
            $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function insertCategoriaEncuesta($arrayData,$opcion){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            if($opcion == INSERT_IN_ENC){
                $this->db->insert('senc.categoria_x_encuesta',$arrayData);
                if($this->db->affected_rows() != 1){
                    throw new Exception(ANP);
                }
                if($this->db->trans_status() === FALSE){
                    throw new Exception(ANP);
                }
            } else if($opcion == DELETE_IN_ENC){
                $this->db->where('_id_encuesta'  , $arrayData['_id_encuesta']);
                $this->db->where('_id_categoria' , $arrayData['_id_categoria']);
                $this->db->delete('senc.categoria_x_encuesta');
                if($this->db->affected_rows() != 1){
                    throw new Exception(ANP);
                }
                $this->updateOrdenByEncuesta($arrayData['_id_encuesta'],$arrayData['orden']);
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getPregCateByEncuesta($idEncuesta){
        $sql = "SELECT p._id_categoria,
                       p._id_pregunta,
                       p.flg_obligatorio,
                       p._id_tipo_pregunta,
                       p.tipo_encuestado
                  FROM senc.pregunta_x_enc_cate p
                 WHERE p._id_encuesta = ?
                   AND CASE WHEN p.tipo_encuestado IS NULL THEN p.tipo_encuestado IS NULL ELSE 1 = 1 END";
        $result = $this->db->query($sql, array($idEncuesta));
        return $result->result();
    }
    
    function deletePregCateByEncuesta($idEncuesta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->where('_id_encuesta' , $idEncuesta);
            $this->db->delete('senc.pregunta_x_enc_cate');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Ocurrio un error... comunï¿½quese con alguien a cargo');
            }
            $this->db->where('_id_encuesta' , $idEncuesta);
            $this->db->delete('senc.categoria_x_encuesta');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Ocurrio un error... comunï¿½quese con alguien a cargo');
            }
            $data['error'] = EXIT_SUCCESS;
            $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    function getDescTipoEncByIdTipoEnc($id_tipo_Enc){
        $sql = "SELECT tie.desc_tipo_encuesta
                  FROM senc.tipo_encuesta tie,
                       senc.encuesta e
                 WHERE _id_tipo_encuesta   = ?	
                   AND e._id_tipo_encuesta = tie.id_tipo_encuesta";
        $result = $this->db->query($sql, array($id_tipo_Enc));
        return $result->row()->desc_tipo_encuesta;
    }
    
    function getCantTipoEncuestadoByIdEnc($idEncuesta){
        $sql = " SELECT ete._id_tipo_encuestado,
                        te.desc_tipo_enc,
                        te.abvr_tipo_enc
                   FROM senc.encuesta_x_tipo_encuestado ete,
			            senc.tipo_encuestado te,
                        senc.encuesta e		
                  WHERE ete._id_encuesta = ?
                    AND ete._id_encuesta = e.id_encuesta
                    AND ete._id_tipo_encuestado = te.id_tipo_encuestado
               ORDER BY ete._id_tipo_encuestado";
        $result = $this->db->query($sql, array($idEncuesta));
        return $result->result();
    }
    
    function getCantServicio(){
        $sql = "SELECT id_servicio,
                       INITCAP(desc_servicio) as desc_servicio
                  FROM servicio
                 WHERE flg_acti = '".FLG_ACTIVO."'
                   AND flg_show_in_encuesta = '1'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getCantPropM($idEncuesta){
        $sql = "SELECT id_propuesta,
                       INITCAP(desc_propuesta) as desc_propuesta
                  FROM senc.propuesta_mejora
                 WHERE flg_estado   = '".ESTADO_ACTIVO."'
                   AND _id_encuesta = ?
              ORDER BY count DESC";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->result();
    }
    
    function getCantRestantePropM($arrayPropM){
        $sql = "   SELECT id_propuesta,
                          desc_propuesta
                     FROM senc.propuesta_mejora
                    WHERE flg_estado   = '".ESTADO_ACTIVO."'
                      AND id_propuesta NOT IN ?
                 ORDER BY id_propuesta";
        $result = $this->db->query($sql, array($arrayPropM));
        return $result->result();
    }
    
    function getDesc5CaritasbyId(){
        $sql = "SELECT a.desc_alternativa
                  FROM senc.alternativa a
                 WHERE a.id_alternativa IN (1,2,3,4,5)
              ORDER BY id_alternativa DESC ";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getDesc4CaritasbyId(){
        $sql ="SELECT a.desc_alternativa
                 FROM senc.alternativa a
                WHERE a.id_alternativa IN (1,2,4,5)
             ORDER BY a.id_alternativa DESC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getDesc3CaritasbyId(){
        $sql ="SELECT a.desc_alternativa
                 FROM senc.alternativa a
                WHERE a.id_alternativa IN (1,3,5)
             ORDER BY a.id_alternativa DESC";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getDesc2Opciones(){
        $sql ="SELECT a.desc_alternativa
                 FROM senc.alternativa a
                WHERE a.id_alternativa IN (13,14)";      
        $result = $this->db->query($sql);
        return $result->result();
    }
    function getOpcionesByEncuestaPregunta($idEncuesta,$idPregunta){
        $sql = "SELECT a.id_alternativa,
                       a.desc_alternativa,
                       alter.id_alter_x_tipo_preg_x_preg
                  FROM senc.alter_x_tipo_preg_x_preg alter,
                       senc.alternativa a
                 WHERE alter._id_pregunta    = ?
                   AND alter._id_encuesta    = ?
                   AND alter._id_alternativa = a.id_alternativa
              ORDER BY a.id_alternativa";
        $result = $this->db->query($sql,array($idPregunta,$idEncuesta));
        return $result->result();
    }
    
    function deleteOpcionesByPreguntaEncuesta($idEncuesta,$idPregunta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('_id_encuesta' , $idEncuesta);
            $this->db->where('_id_pregunta' , $idPregunta);
            $this->db->delete('senc.alter_x_tipo_preg_x_preg');
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Ocurrio un error... comunï¿½quese con alguien a cargo');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function insertOpcionesByPregunta($arrayInsertAlter,$arrayInserAlterPreg,$arrayUpdate){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
             $this->db->trans_begin();
            if(isset($arrayInsertAlter['desc_alternativa'])){
                $this->db->insert('senc.alternativa',$arrayInsertAlter);
                $lastId = $this->db->insert_id();
                $arrayInserAlterPreg['_id_alternativa'] = $lastId;
                $this->db->insert('senc.alter_x_tipo_preg_x_preg',$arrayInserAlterPreg);
            }
            if(isset($arrayUpdate['id_alternativa'])){
                $this->db->where('id_alternativa'    , $arrayUpdate['id_alternativa']);
                unset($arrayUpdate['id_alternativa']);
                $this->db->update('senc.alternativa' , $arrayUpdate);
            }
            $data['error'] = EXIT_SUCCESS;
             $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
             $this->db->trans_rollback();
        }
    }
    
    function deleteOpcionByPregunta($iaAlterPreg){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('id_alter_x_tipo_preg_x_preg' , $iaAlterPreg);
            $this->db->delete('senc.alter_x_tipo_preg_x_preg');
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Ocurrio un error... comunï¿½quese con alguien a cargo');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function updateTipoPreguntaByEncuesta($arrayUpdate,$idEncuestaSession,$idTipoEncuesta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_encuesta'    , $idEncuestaSession);
            $this->db->update('senc.encuesta' , $arrayUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Ocurriï¿½ un error... comunï¿½quese con alguien a cargo');
            }
            $data['flg_condicionCombo'] = EXIT_ERROR;
            if($idTipoEncuesta != TIPO_ENCUESTA_LIBRE){
                $data = $this->deleteTipoEncuestadosByEncuesta($idEncuestaSession);
                if($data['error'] == EXIT_ERROR){
                    throw new Exception($data['msj']);
                }
                $data['flg_condicionCombo'] = EXIT_SUCCESS;
            }
            $data['error'] = EXIT_SUCCESS;
            $this->db->trans_commit();
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function updateEncuesta($arrayUpdate, $idEncuestaSession){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('id_encuesta'    , $idEncuestaSession);
            $this->db->update('senc.encuesta' , $arrayUpdate);
            if ($this->db->affected_rows() != 1) {
                throw new Exception('Ocurriï¿½ un error... comunï¿½quese con alguien a cargo');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = 'Se actualizo la encuesta';
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function verificarTituloEncuesta($titulo, $idEncuesta) {
        $sql = "SELECT CASE WHEN UPPER(titulo_encuesta) = UPPER(?) THEN '1' ELSE '0' END AS editar
                  FROM senc.encuesta
                 WHERE id_encuesta = ?";
        $result = $this->db->query($sql,array($titulo, $idEncuesta));
        if($result->num_rows() == 1) {
            return $result->row()->editar;
        } else {
            return '1';//No editara porque es igual el titulo
        }
    }
    
    function deleteTipoEncuestadosByEncuesta($idEncuestaSession){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $result = $this->cuentaPreguntasAndTipoEncuestados($idEncuestaSession);       
            if($result != null){
                $this->db->where('_id_encuesta' , $idEncuestaSession);
                $this->db->delete('senc.encuesta_x_tipo_encuestado');
                if($this->db->trans_status() === FALSE && $result['count_encuestados'] != $this->db->affected_rows()){
                    throw new Exception('No se realizo la acciï¿½n');
                }
                $this->db->where('_id_encuesta' , $idEncuestaSession);
                $this->db->update('senc.pregunta_x_enc_cate',array('tipo_encuestado' => null));
                if($this->db->trans_status() === FALSE && $result['count_preguntas'] != $this->db->affected_rows()){
                   throw new Exception('No se realizo la acciï¿½n'); 
                }
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function cuentaPreguntasAndTipoEncuestados($idEncuestado){
        $sql = "SELECT todo.count_encuestados,
                       todo.count_preguntas
                  FROM (SELECT 
                       (SELECT COUNT(1) count_encuestados
                          FROM senc.encuesta_x_tipo_encuestado
                	 WHERE _id_encuesta = ? ),
                       (SELECT COUNT(1) count_preguntas
                          FROM senc.pregunta_x_enc_cate
                         WHERE _id_encuesta = ? )) todo";
        $result = $this->db->query($sql,array($idEncuestado,$idEncuestado));
        if($result->num_rows() == 1){
            return $result->row_array();
        } else{
            return null;
        }
    }
    
    function deleteAllAlternativasByPreguntaEncuesta($idPregunta,$idEncuesta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try{
            $this->db->where('_id_pregunta' , $idPregunta);
            $this->db->where('_id_encuesta' , $idEncuesta);
            $this->db->delete('senc.alter_x_tipo_preg_x_preg');
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getTipoPregComboByEncuesta($idEncuesta){
        $sql = "SELECT _id_tipo_pregunta 
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_encuesta = ?
                   AND _id_tipo_pregunta IN ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($idEncuesta,json_decode(ARRAY_TIP_PREG_COMBO)));
        if(count($result->result()) == 0){
            return null;
        }else{
            return $result->row()->_id_tipo_pregunta;
        }
    }
    
    function getIdTipoPreguntaByPreguntaEncuesta($idPregunta,$idEncuesta){
        $sql = "SELECT _id_tipo_pregunta
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_pregunta = ?
                   AND _id_encuesta = ?";
        $result = $this->db->query($sql,array($idPregunta,$idEncuesta));
        if($result->num_rows() == 1) {
            return $result->row()->_id_tipo_pregunta;
        } else{
            
        }
    }
    
    function getExisteAlterInPreguntaMulti($idPreg,$idEncuesta,$idAlternativa){
        $sql = " SELECT COUNT(1) as cuenta
                   FROM senc.alter_x_tipo_preg_x_preg atp
                  WHERE atp._id_encuesta    = ?
                    AND atp._id_pregunta    = ?
                    AND atp._id_alternativa = ?";
        $result = $this->db->query($sql,array($idEncuesta,$idPreg,$idAlternativa));
        return $result->row()->cuenta;
    }
    
    function getIDByDescripPropMejora($descPropuesta){
        $sql = 'SELECT id_propuesta
                 FROM senc.propuesta_mejora
                WHERE UPPER(desc_propuesta) = ?';
        $result = $this->db->query($sql, array($descPropuesta));
        if($result->num_rows() == 1) {
            return $result->row()->id_propuesta;
        } else {
            return null;
        }
    }
    
    function getCountServiciosInPregByEncuesta($idEncuesta){
        $sql = " SELECT COUNT(1) count
                   FROM senc.pregunta_x_enc_cate x,
                        senc.preguntas p
                  WHERE x._id_encuesta = ?
                    AND p.id_pregunta  = x._id_pregunta
                    AND p._id_servicio IS NOT NULL";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->row()->count;
    }
    
    function getSedeNivelGradoByAula($idAula){
        $sql = "SELECT a.nid_sede,
                       a.nid_grado,
                       a.nid_nivel,
                       a.nid_aula
                  FROM aula a
                 WHERE nid_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        return $result->row_array();
    }
    
    
    function insertDeviceInfoEncuestado($arryInfo) {
        $this->db->insert('senc.device_info_encuestado', $arryInfo);
        if($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        }
    }
    
    function insertDeviceInfoEncuestadoAux($arryInfo) {
        $this->db->insert('senc.device_info_encuestado', $arryInfo);
        if($this->db->affected_rows() != 1) {
            throw new Exception('Hubo un error al registrar tu encuesta');
            //return $this->db->insert_id();
        }
        return array('error' => EXIT_SUCCESS, 'msj' => 'Se registr&oacute; tu encuesta. Gracias!','id_dispositivo' => $this->db->insert_id());
    }
    
    function borrarDeviceInfoEncuestado($idDeviceInfo) {
        $this->db->where('id_device_info', $idDeviceInfo);
        $this->db->delete('senc.device_info_encuestado');
        if($this->db->affected_rows() == 1) {
            return 1;
        }
        return null;
    }
    
    //Trae las preguntas segun el tipo de usuario (PADRE,ALUMNO O INVITADO) filtros(idEnc y tipoencuestado)
    function getPregInicial($idEncuesta, $tipoEncuestado){
        $sql ="   SELECT  ete._id_pregunta,
                          p.desc_pregunta,
                          x.flg_obligatorio,
                          ete._id_tipo_pregunta
                    FROM  senc.pregunta_x_enc_cate x,
                          senc.encuesta_x_tipo_encuestado ete,
                          senc.preguntas           p
                   WHERE  ete._id_encuesta  = 54
                     AND  ete._id_tipo_encuestado = 1
                     AND  x.flg_estado    = 'ACTIVO'
                     AND  x._id_categoria IS NULL
                     AND  ete._id_pregunta = p.id_pregunta          
                ORDER BY  x.orden
                LIMIT 1";
        $result = $this->db->query($sql, array($idEncuesta, $tipoEncuestado));
        return $result->row_array();
    }
    
    function getCantidadPropM($idPropM){
        $sql = "SELECT desc_propuesta
                  FROM senc.propuesta_mejora
                 WHERE id_propuesta = ?";
        $result = $this->db->query($sql, array($idPropM));
        if($result->num_rows() == 1) {
            return $result->row()->desc_propuesta;
        } else {
            return null;
        }
    }
    
    function getIdPropMbyDesc($desc_propM,$idEncuesta){
        $sql = 'SELECT id_propuesta
                  FROM senc.propuesta_mejora 
                 WHERE lower(desc_propuesta) = ?
                   AND _id_encuesta = ?';
        $result = $this->db->query($sql, array($desc_propM,$idEncuesta));
        if($result->num_rows() == 1) {
            return $result->row()->id_propuesta;
        } else {
            return null;
        }
    }
    
    function updateFlgEncuestaPersona($idPersona, $array_update, $tipo_enc) {
        if($tipo_enc == TIPO_ENCUESTA_PADREFAM) {
            $this->db->where('__id_persona'    , $idPersona);
            $this->db->where('year_academico'  , _getYear());
            $this->db->update('persona_x_aula' , $array_update);
        } else {
            $this->db->where('nid_persona'    , $idPersona);
            $this->db->update('persona' , $array_update);
        }
        if($this->db->affected_rows() != 1) {
            throw new Exception('No se pudo actualizar el dato');
        }
    }
    
    function getCantAlumnosAula($idAula){
        $sql = "SELECT COUNT(1) as cuenta
                  FROM persona_x_aula
                  WHERE __id_aula = ?";
        $result = $this->db->query($sql, array($idAula));
        return $result->row()->cuenta;
    }
    
    function executeRollBack($arrayRollBack){
        $data['error'] = ERROR_MONGO;
        $data['msj'] = null;
        try{
            $m   = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            foreach($arrayRollBack as $sql){
                $result = $db->execute('return '.utf8_encode($sql));
                if($result['ok'] == ERROR_MONGO){
                    throw new Exception('Error al realizar el rollback');
                }
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getHijosByCodFamilia($codFam){
        //@PENDIENTE Cambiar el cod_familia_temp por el de SMILEDU
        $sql = "SELECT CONCAT(SPLIT_PART(INITCAP(p.nom_persona), ' ', 1),' ',p.ape_pate_pers,' ',p.ape_mate_pers) AS nombre_completo,
                       CASE WHEN pa.flg_encuesta = 1 THEN 'check'
                            ELSE 'person'
                       END AS estado,
                       CONCAT(g.abvr,' ', n.abvr, ' - ', s.abvr) AS ubic,
                       a.nid_aula,
                       p.nid_persona
                  FROM sima.detalle_alumno da,
                       persona              p,
                       persona_x_aula      pa,
                       aula                 a,
                       sede                 s,
                       nivel                n,
                       grado                g
                 WHERE pa.year_academico   = "._YEAR_."
                   AND da.cod_familia_temp = ? 
                   AND da.nid_persona      = p.nid_persona
                   AND p.nid_persona       = pa.__id_persona
                   AND da.nid_persona      = pa.__id_persona
                   AND a.nid_aula          = pa.__id_aula
                   AND a.nid_sede          = s.nid_sede
                   AND a.nid_grado         = g.nid_grado
                   AND a.nid_nivel         = n.nid_nivel";
        $result = $this->db->query($sql, array($codFam));
        return $result->result();
    }
    function getPreguntasbyIdEncuesta($idEncuesta){
        $sql = "SELECT x._id_pregunta,
                       x._id_tipo_pregunta,
                       p.desc_pregunta                      
                  FROM senc.pregunta_x_enc_cate x,
                       senc.categoria c,
                       senc.categoria_x_encuesta ce,
                       senc.preguntas p
                 WHERE x._id_encuesta = ?
                   AND x._id_pregunta = p.id_pregunta
                   AND x._id_categoria = c.id_categoria
                   AND ce._id_categoria = c.id_categoria
                   AND ce._id_encuesta = x._id_encuesta
                   AND x.flg_estado = '".ESTADO_ACTIVO."'
              ORDER BY ce.orden,x.orden";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->result();
        
    }
    
    function checkIf_sePuedeEncuestarEnAula($idAula, $idEncuesta) {
        $sql = "SELECT CASE WHEN (estu_aula.cnt - encu.cnt) > 0 THEN TRUE
                            ELSE FALSE END AS result
                  FROM (SELECT COUNT(1) AS cnt
                	  FROM senc.device_info_encuestado
                	 WHERE id_encuesta = ?
                	   AND nid_aula    = ?) AS encu,
                        (SELECT COUNT(1) as cnt
                           FROM persona_x_aula
                          WHERE __id_aula = ?) AS estu_aula";
        $result = $this->db->query($sql ,array($idEncuesta, $idAula, $idAula));
        if($result->num_rows() != 1) {
            return false;
        } else {
            return $result->row()->result;
        }
        /*try {
            $m   = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            $sql = 'db.senc_respuesta_encuesta.aggregate([
                	{$unwind  : "$preguntas"},
                	{$project : {"id_aula" : -1, "id_encuesta" : -1, "preguntas.id_dispositivo" : -1}},
                    {$match   : {id_aula     : '.$idAula.'}},
                	{$match   : {id_encuesta : '.$idEncuesta.'}},
                	{$group   : {_id:"$preguntas.id_dispositivo"}},
                	{$group   : {_id:"$_id"}}
                ])';
            $result = $db->execute('return '.utf8_encode($sql));
            if($result['ok'] == ERROR_MONGO) {
                _log('ERROR!!! getCountParticipantesByEncuestaAula.... '.$fuente.'  '. print_r($result[0], true) );
                return null;
            } else {
                $count  = (count($result['retval']['_firstBatch']));
                if($fuente != 'Index'){
                    _log('getCountParticipantesByEncuestaAula::: '.$fuente.'  '.$count);
                }
                return $count;
            }
        } catch (Exception $e) {
//            _log('errorrrrrr getCountParticipantesByEncuestaAula::::: '.$e->getMessage()); 
        }
        return null;*/
    }
    
    function getCantEncuestasRealizadasByAula($idAula, $idEncuesta) {
        $sql = 'SELECT COUNT(1) AS cnt
            	  FROM senc.device_info_encuestado
            	 WHERE id_encuesta = ?
            	   AND nid_aula    = ?';
        $result = $this->db->query($sql ,array($idEncuesta, $idAula));
        return $result->row()->cnt;
    }
    
    function getNivelesByTipoEncuesta($idTipoEncuesta,$lastNivel,$idPersona){
        $niveles = array();
        //@pendiente constantes sirech
        $sql = "SELECT id_sede_control  as sede,
                           CASE WHEN (id_nivel_control = 5 OR id_nivel_control = 4) THEN 3
                                WHEN (id_nivel_control = 3 OR id_nivel_control = 2) THEN 2
                                ELSE id_nivel_control
                           END AS nivel,
                           CASE WHEN('".TIPO_ENCUESTA_DOCENTE."' = ?) THEN trim(to_char(id_area_especifica,'9999999999999'))
                                            	                      ELSE trim(to_char(id_area_general,'9999999999999'))
                           END              as id_area
                      FROM rrhh.personal_detalle
                     WHERE id_persona = ?
                     LIMIT 1";
        if($idTipoEncuesta == TIPO_ENCUESTA_PERSADM){
            $result = $this->db->query($sql,array($idTipoEncuesta,$idPersona));
            $result = $result->row_array();
            $niveles['nid_sede'] = $result['sede'];
            $niveles['nid_area'] = $result['id_area']; 
        } else if($idTipoEncuesta == TIPO_ENCUESTA_DOCENTE){
            $result = $this->db->query($sql,array($idTipoEncuesta,$idPersona));
            $result = $result->row_array();
            $niveles['nid_sede']  = $result['sede'];
            $niveles['nid_nivel'] = $result['nivel'];
            $niveles['nid_area']  = $result['id_area'];
        } else{
            $sql = "SELECT a.nid_sede,
                           a.nid_grado,
                           a.nid_nivel,
                           a.nid_aula
                      FROM aula a
                     WHERE nid_aula = ?";
            $result = $this->db->query($sql, array($lastNivel));
            $niveles = $result->row_array();
        }
        return $niveles;
    }
    
    function getAlternativasByTipo($idTipoPreg){
        $filtroIn = null;
        $orden    = null;
        if($idTipoPreg == TRES_CARITAS){
            $filtroIn = ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_MUY_INSATISFECHO;
        } else if($idTipoPreg == CUATRO_CARITAS){
            $filtroIn = ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_INSATISFECHO;
        } else if($idTipoPreg == CINCO_CARITAS){
            $filtroIn = ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_INSATISFECHO;
        } else if($idTipoPreg == DOS_OPCIONES){
            $filtroIn = ALTERNATIVA_SI.','.ALTERNATIVA_NO;
            $orden = 'DESC';
        }
        $sql = "SELECT id_alternativa,
                       desc_alternativa
                  FROM senc.alternativa
                 WHERE id_alternativa IN(".$filtroIn.")
              ORDER BY id_alternativa ".$orden;
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function insertNewCaritasAlternativas($caritas){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert_batch('senc.alter_x_tipo_preg_x_preg', $caritas); 
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getDescEncbyIdEnc($idEncuesta){
        $sql = " SELECT desc_enc
                   FROM senc.encuesta
                  WHERE id_encuesta = ?";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->row_array();
    }
    
    function getIdEncbyDesc($idEncuesta){
        $sql = " SELECT _id_tipo_encuesta
                   FROM senc.encuesta
                  WHERE id_encuesta = ?";
        $result = $this->db->query($sql,array($idEncuesta));
        if($result->num_rows() == 1) {
            return $result->row()->_id_tipo_encuesta;
        }
        return null;
    
    }
    function getIdPregsbyDesc($desc_pregs){
        $sql = " SELECT id_pregunta
                   FROM senc.preguntas
                  WHERE desc_pregunta = ?";
        $result = $this->db->query($sql,array($desc_pregs));
        return $result->row_array();
    }
    
    function getIdAlterbyIdPreg($idEncuesta, $idPregunta, $desc_alter){
        $sql = " SELECT atp._id_alternativa
                   FROM senc.alter_x_tipo_preg_x_preg atp,
		                senc.alternativa a
                  WHERE atp._id_encuesta = ?
                    AND atp._id_pregunta = ?
                    AND a.desc_alternativa = ?
                    AND atp._id_alternativa = a.id_alternativa";
        $result = $this->db->query($sql,array($idEncuesta, $idPregunta, ''.$desc_alter.''));
        return $result->row_array();
    }
    
    function getIdAlterPropMbyDesc($desc_alter){
        $sql = " SELECT id_propuesta AS id
                   FROM senc.propuesta_mejora
                  WHERE desc_propuesta = UPPER(?)";
        $result = $this->db->query($sql,array($desc_alter));
        if(count($result->result()) == 0){
            return null;
        }else{
            return  $result->row()->id;
        }
    }
    
    function getTipoEncuestaActiva(){
        $sql = "SELECT COUNT(1) AS cuenta
                  FROM senc.encuesta e
                 WHERE e.flg_estado = '".ESTADO_ACTIVO."'
                   AND _id_tipo_encuesta = ".TIPO_ENCUESTA_PADREFAM;
        
        $result = $this->db->query($sql);
        return  $result->row()->cuenta;
    }
    
    function getIdTipoEncbyIdEnc($idEncuesta){
        $sql = " SELECT _id_tipo_encuesta as id
                   FROM senc.encuesta
                  WHERE id_encuesta = ?";
        $result = $this->db->query($sql,array($idEncuesta));
        return  $result->row()->id;
    }
    
    function getTipoencuestadoByEncuestaPregunta($idEncuesta, $idPregunta){
        $sql = "SELECT x.tipo_encuestado
                  FROM senc.pregunta_x_enc_cate x
                 WHERE x._id_encuesta = ?
                   AND x._id_pregunta = ?";
        $result = $this->db->query($sql,array($idEncuesta,$idPregunta));
        if($result->num_rows() > 0){
            return  $result->row()->tipo_encuestado;
        } else{
            return null;
        }
    }
    
    function getYearFromTipoEncuesta($idTipoEncuestas){
        $sql = "SELECT EXTRACT (year FROM fecha_registro) as year
                  FROM senc.encuesta
                 WHERE _id_tipo_encuesta IN ?
              GROUP BY EXTRACT (year FROM fecha_registro)
              ORDER BY EXTRACT (year FROM fecha_registro)";
    
        $result = $this->db->query($sql, array($idTipoEncuestas));
        return $result->result();
    }
    
    /**
     * @author Cesar Villarreal
     * @param int $idEncuesta
     * @return Elimina todos los documentos en el mongo con esa encuesta
     */
    function deleteDataEncuestasByEstadoBloqueo($idEncuesta){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        try{
            //ELIMINAR RESPUESTA
            $sql = 'db.senc_respuesta_encuesta.remove({
                      id_encuesta   : '.$idEncuesta.',
                      year          : '.date('Y').'
                    })';
            $result = $db->execute('return '.utf8_encode($sql));
            if($result['ok'] == ERROR_MONGO){
                throw new Exception('No se elimin&oacute;');
            }
            //ELIMINAR INSATISFACCION
            $sql = 'db.senc_insatisfaccion_encuesta.remove({
                  id_encuesta   : '.$idEncuesta.',
                  year          : '.date('Y').'
                })';
            
            $result = $db->execute('return '.utf8_encode($sql));
            if($result['ok'] == ERROR_MONGO){
                throw new Exception('No se elimin&oacute;');
            }
            //ELIMINAR SATISFACCION
            $sql = 'db.senc_satisfaccion_encuesta.remove({
                  id_encuesta   : '.$idEncuesta.',
                  year          : '.date('Y').'
                })';
            $result = $db->execute('return '.utf8_encode($sql));
            if($result['ok'] == ERROR_MONGO){
                throw new Exception('No se elimin&oacute;');
            }
            //ELIMINAR PROPUESTA MEJORA 
            $sql = 'db.senc_propuesta_mejora_encuesta.remove({
                      id_encuesta   : '.$idEncuesta.',
                      year          : '.date('Y').'
                    })';
            $result = $db->execute('return '.utf8_encode($sql));
            if($result['ok'] == ERROR_MONGO){
                throw new Exception('No se elimin&oacute;');
            }
            //ELIMINAR PROPUESTA MEJORA COMENTARIO
            $sql = 'db.senc_propuesta_comentario.remove({
                      id_encuesta   : '.$idEncuesta.',
                      year          : '.date('Y').'
                    })';
            $result = $db->execute('return '.utf8_encode($sql));
            if($result['ok'] == ERROR_MONGO){
                throw new Exception('No se elimin&oacute;');
            }
            $this->db->delete('senc.device_info_encuestado', array('id_encuesta' => $idEncuesta));
            if ($this->db->trans_status() === FALSE){
                throw new Exception('No se elimin&oacute;');
            }
        } catch(Exception $e){
            
        }
        return $result['ok'];
    }
    
    function insertPreguntaEncuesta($arrayInsert){
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $this->db->insert('senc.pregunta_x_enc_cate',$arrayInsert);
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo insertar :( int&eacute;ntelo nuevamente....');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = "Se agreg&oacute; la pregunta";
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function insertPropuestaMejoraComentario($arrayProMejoRpta, $tipoEnc,$SNGAula,$idEncuesta,$idDispositivo,$arrayRollBack,$comentario){
        $data['error'] = ERROR_MONGO;
        $data['msj']   = null;
        try{
            if(count($arrayProMejoRpta) > 0) {
                $m   = new MongoClient(MONGO_CONEXION);
                $db = $m->selectDB(SMILEDU_MONGO);
                $sqlCount = null;
                $tipoEncuestado = $this->session->userdata('tipoEncuestadoLibre');
                $niveles = null;
                $tipoEncuestado = $this->getAllTipoEncuestadosByEncuesta($idEncuesta,$tipoEncuestado);
                $tipoEncuestadoFirstLetter = null;
                if($tipoEncuestado != null) {
                    $tipoEncuestadoFirstLetter = substr($tipoEncuestado, 0,1);
                }
                $niveles = null;
                if($tipoEncuestadoFirstLetter != null){//@PENDIENTE Llamar a la tabla senc.tipo_encuestado
                    if($tipoEncuestadoFirstLetter == 'P' || $tipoEncuestadoFirstLetter == 'E'){
                        $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                    id_grado : '.$SNGAula['nid_grado'].',
                                    id_aula  : '.$SNGAula['nid_aula'].',';
                    }else if($tipoEncuestadoFirstLetter == 'A'){
                        $niveles = 'id_area : '.$SNGAula['nid_area'].',';
                    }else if($tipoEncuestadoFirstLetter == 'D'){
                        $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                    id_area  : '.$SNGAula['nid_area'].',';
                    }else if($tipoEncuestadoFirstLetter == 'I'){
                        $niveles = 'id_nivel : '.$SNGAula['nid_nivel'].',
                                    id_grado : '.$SNGAula['nid_grado'].',
                                    id_aula  : '.$SNGAula['nid_aula'].',';
                    }
                } else{
                    $niveles = ''.(($tipoEnc == TIPO_ENCUESTA_DOCENTE || $tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_nivel : '.$SNGAula['nid_nivel'].',' : null).'
                            	'.(($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_grado : '.$SNGAula['nid_grado'].', id_aula : '.$SNGAula['nid_aula'].',': 'id_area : '.$SNGAula['nid_area'].',').'';
                }
                //Busca por las pk id_encuesta, year, id_sede, id_nivel, id_grado si existe el documento
                $sqlCount = 'db.senc_propuesta_comentario.find({
                            	id_encuesta       : '.$idEncuesta.',
                            	year              : '.date("Y").',
                            	id_sede           : '.$SNGAula['nid_sede'].',
                            	'.$niveles.'
                            	tipo_encuesta     : '.$tipoEnc.'
                            }).count()';
                $resultCount = $db->execute('return '.utf8_encode($sqlCount));
                if($resultCount['ok'] == ERROR_MONGO){_log('5:: '.$sqlCount);
                    throw new Exception('Errooooooooooooooor.......count vuelva a intentarlo por favor');
                }
                $cond = $resultCount['retval'];_log(('$cond:>>>:: 5:: '.$cond));
                //Inserta nuevo documento en caso no halla encontrado nada
                $nivelArray = null;
                $arrayIdsPropuestas = null;
                foreach($arrayProMejoRpta as $row){
                    $arrayIdsPropuestas .= $row['id_propuesta'].',';
                }
                $arrayIdsPropuestas = substr($arrayIdsPropuestas,0,(strlen($arrayIdsPropuestas)-1));
                if($cond == INSERTA){
                    $desc_encuesta = $this->m_utils->getById('senc.encuesta','desc_enc', 'id_encuesta', $idEncuesta);
                    $fecha_apertura = $this->m_utils->getById('senc.encuesta','fecha_apertura', 'id_encuesta', $idEncuesta);
                    //dfloresgonz 03.05.2016 Para el grafico 4 y botar el grafico separado por tipo de encuestado
                    $descTipoEncuestado = ($tipoEnc == TIPO_ENCUESTA_LIBRE) ? ",tipo_encuestado : \"".$tipoEncuestado."\" " : null;
                    $sql = 'db.senc_propuesta_comentario.insert(
                               {
                                 id_encuesta       :'.$idEncuesta.',
                            	 year              : '.date("Y").',
                            	 id_sede           : '.$SNGAula['nid_sede'].',
                            	 '.$niveles.'
                            	 tipo_encuesta     : '.$tipoEnc.',
                                 fecha		       : "'.($fecha_apertura).'",
                                 desc_encuesta     : "'.$desc_encuesta.'",
                                 propuestas        : [ {
                                        id_propuesta    : ['.$arrayIdsPropuestas.'],
                                        comentario       : "'.$comentario.'",
                                        id_dispositivo  : '.$idDispositivo.'
                                        '.$descTipoEncuestado.'
                                     }]
                               }
                            )';
                    $result = $db->execute('return '.utf8_encode($sql));
                    $deleteInsert = 'db.senc_propuesta_comentario.remove({
                                             id_encuesta   : '.$idEncuesta.',
                                             year          : '.date('Y').',
                                             id_sede       : '.$SNGAula['nid_sede'].',
                        	                 '.$niveles.'
                                             tipo_encuesta : '.$tipoEnc.'
                                         })';
                    array_push($arrayRollBack, $deleteInsert);
                    if($result['ok'] == ERROR_MONGO){
                        throw new Exception('Erroooooooooooooor..............respuesta_encuesta');
                    }
                    $data['error'] = SUCCESS_MONGO;
                    //Detecta que lo que se realizara sera un update
                } else if($cond >= ACTUALIZA){
                    if($tipoEncuestadoFirstLetter == 'P' || $tipoEncuestadoFirstLetter == 'E' || $tipoEncuestadoFirstLetter == 'A' || $tipoEncuestadoFirstLetter == 'D' || $tipoEncuestadoFirstLetter == 'I'){
                        $nivelArray = 'id_aula : '.$SNGAula['nid_aula'].',';
                    } else{
                        $nivelArray = ($tipoEnc == TIPO_ENCUESTA_ALUMNOS || $tipoEnc == TIPO_ENCUESTA_PADREFAM) ? 'id_aula : '.$SNGAula['nid_aula'].',' : 'id_area : '.$SNGAula['nid_area'].',';
                    }
                    //dfloresgonz 03.05.2016 Para el grafico 4 y botar el grafico separado por tipo de encuestado
                    $descTipoEncuestado = ($tipoEnc == TIPO_ENCUESTA_LIBRE) ? "tipo_encuestado : \"".$tipoEncuestado."\" ," : null;
                    //Evalua por cada pregunta con respuesta si existe o no para verificar la siguiente acciï¿½n
                    $sql = 'db.senc_propuesta_comentario.update(
                               {     id_encuesta : '.$idEncuesta.',
        							 year : '.date("Y").',
        							 id_sede : '.$SNGAula['nid_sede'].',
        							 '.$niveles.'
        							 tipo_encuesta:'.$tipoEnc.'},
                                     {$push: { propuestas: {
                            							id_propuesta : ['.$arrayIdsPropuestas.'],
                    								    comentario : "'.$comentario.'",
                    								    id_dispositivo  : '.$idDispositivo.',
                                                        '.$descTipoEncuestado.'
                    								    count       : 1
                            							}
                            	      }
                            	}
                            )';
                    $result = $db->execute('return '.utf8_encode($sql));
                    if($result['ok'] == ERROR_MONGO){_log('error comentario: '.$sql);
                        throw new Exception('Erroooooooooooorr............al actualizar respuesta encuesta');
                    }
                    $pullPreguntas = 'db.senc_respuesta_encuesta.update(
                                        { },
                                        {$pull : {preguntas : {
                                                    id_dispositivo : '.$idDispositivo.'}}},
                                        {multi : true}
                                    )';
                    array_push($arrayRollBack, $pullPreguntas);
                    
                    $data['error'] = SUCCESS_MONGO;
                }
            } else {
                $data['error'] = SUCCESS_MONGO;
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        $data['arrayRollBack'] = $arrayRollBack;
        return $data;
    }
    
    function getAllTipoEncuestadosByEncuesta($idEncuesta, $tipo_encuestado){
        $sql = "SELECT te.desc_tipo_enc
                  FROM senc.encuesta_x_tipo_encuestado ete,
                       senc.tipo_encuestado            te
                 WHERE ete._id_encuesta        = ?
                   AND ete._id_tipo_encuestado = te.id_tipo_encuestado
                   AND te.abvr_tipo_enc        = ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($idEncuesta,$tipo_encuestado));
        if($result->num_rows() > 0){
            return $result->row()->desc_tipo_enc;
        } else {
            return null;
        }
    }
    
    function aumentaContPropM($idPropM){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $sql = "UPDATE senc.propuesta_mejora SET count = (count+1) WHERE id_propuesta = ?";
            $this->db->query($sql,array($idPropM));
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function aumentaCantEnc($idEncuesta,$cant = 1){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $sql = "UPDATE senc.encuesta SET cant_encuestados = COALESCE(cant_encuestados, 0) + ".$cant." WHERE id_encuesta = ?";
            $this->db->query($sql,array($idEncuesta));
            if($this->db->affected_rows() != 1){
                throw new Exception('No se pudo');
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function deleteCateSinPregByEncuesta($arrayDelete){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $cont = 0;
            foreach($arrayDelete as $row){
                $this->db->where('_id_encuesta'  , $row['_id_encuesta']);
                $this->db->where('_id_categoria' , $row['_id_categoria']);
                $this->db->delete('senc.categoria_x_encuesta');
                $cont++;
            }
            $countArray = count($arrayDelete);
            if ($this->db->trans_status() === FALSE || $cont != $countArray){
                $this->db->trans_rollback();
                throw new Exception('No se pudo realizar la acciï¿½n');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage(); 
        }
        return $data;
    }
    
    function finalizaEncuestasByTipo($idTipoEncuesta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('_id_tipo_encuesta' , $idTipoEncuesta);
            $this->db->where('flg_estado'        , ENCUESTA_APERTURADA);
            $this->db->update('senc.encuesta',array('flg_estado' => ENCUESTA_FINALIZADA));
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('No se pudo cambiar el estado');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getAllTipoEncuestados($idEncuesta){
        $sql = "SELECT te.id_tipo_encuestado,
                       te.desc_tipo_enc,
                       CASE WHEN ete._id_tipo_encuestado IS NULL THEN '' ELSE 'checked'  END AS checktipoenc,
                       CASE WHEN ete._id_tipo_encuestado IS NULL THEN '' ELSE 'selected' END AS select_tipo_enc
                  FROM senc.tipo_encuestado te
                       LEFT JOIN senc.encuesta_x_tipo_encuestado ete ON(ete._id_tipo_encuestado = te.id_tipo_encuestado AND ete._id_encuesta = ?)";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->result();
    }
    
    function insertTipoEncuestadoByEncuesta($arrayInsert,$checked){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            if($checked == "true"){
                $this->db->insert('senc.encuesta_x_tipo_encuestado',$arrayInsert);
                if($this->db->affected_rows() != 1){
                    throw new Exception('No se pudo insertar');
                }
                $data['error'] = EXIT_SUCCESS;
            } else{
                $this->db->where('_id_encuesta',$arrayInsert['_id_encuesta']);
                $this->db->where('_id_tipo_encuestado',$arrayInsert['_id_tipo_encuestado']);
                $this->db->delete('senc.encuesta_x_tipo_encuestado');
                $data = $this->deleteAllTipoEncuestadoInPreguntaByTipoEnc($arrayInsert['_id_tipo_encuestado'], $arrayInsert['_id_encuesta']);
            }
            if($data['error'] != EXIT_SUCCESS){
                $this->db->trans_rollback();
                throw new Exception('Error');
            }
            $this->db->trans_commit();
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage(); 
        }
        return $data;
    }
    
    function deleteAllTipoEncuestadoInPreguntaByTipoEnc($idTipoEnc,$idEncuestaSession){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $abvrTipoEnc = $this->m_utils->getById('senc.tipo_encuestado', 'abvr_tipo_enc', 'id_tipo_encuestado', $idTipoEnc);
            $this->db->where('tipo_encuestado' , $abvrTipoEnc);
            $this->db->where('_id_encuesta'    , $idEncuestaSession);
            $this->db->update('senc.pregunta_x_enc_cate',array('tipo_encuestado' => null));
            if($this->db->trans_status() === FALSE){
                throw new Exception('No se realizï¿½ la acciï¿½n');
            }
            $data['error']  = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getAllTipoEncuestadosByEncuestaPregunta($idEncuesta,$idPregunta){
        $sql = "SELECT ete._id_tipo_encuestado,
                       te.desc_tipo_enc,
                       ete._id_pregunta,
                       te.abvr_tipo_enc,
                       CASE WHEN pec.tipo_encuestado IS NOT NULL AND pec.tipo_encuestado = te.abvr_tipo_enc THEN 'checked' ELSE null 
                       END AS checked_tipo_enc
                  FROM senc.tipo_encuestado te,
                       senc.encuesta_x_tipo_encuestado ete
                       LEFT JOIN senc.pregunta_x_enc_cate pec ON (ete._id_encuesta = pec._id_encuesta AND pec._id_pregunta = ?)
                 WHERE ete._id_encuesta = ?
                   AND ete._id_tipo_encuestado = te.id_tipo_encuestado";
        $result = $this->db->query($sql,array($idPregunta,$idEncuesta));
        return $result->result();
    }
    
    function countPregEncuesta($idEncuesta){
        $sql=" SELECT COUNT(1) cuenta
                 FROM senc.pregunta_x_enc_cate x
                WHERE x._id_encuesta    = ?";
        
        $result = $this->db->query($sql, array($idEncuesta));
        return $result->row()->cuenta;
    }
    function updateFlgEncuestaRol($tipoEnc){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = MSJ_UPT;
        try{
            $result = null;
            if($tipoEnc == TIPO_ENCUESTA_PERSADM){
                $sql = "UPDATE persona_x_rol
                           SET flg_encuesta = null
                         WHERE nid_rol NOT IN (".ID_ROL_DOCENTE.",".ID_ROL_ESTUDIANTE.")";
                $result = $this->db->query($sql);
            }else{
                $rol = ID_ROL_ESTUDIANTE;//ALUMNO
                if($tipoEnc == TIPO_ENCUESTA_DOCENTE){
                    $rol = ID_ROL_DOCENTE;
                }
                $sql = "UPDATE persona_x_rol
                           SET flg_encuesta = null
                         WHERE nid_rol = ?";
                $result = $this->db->query($sql, array($rol));
            }
            $rpta['error']      = EXIT_SUCCESS;
            $rpta['msj']        = MSJ_UPT;
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    
    function cambiarEstadoEncuesta($idEncuesta, $estado){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('id_encuesta', $idEncuesta);
            $this->db->update('senc.encuesta',array('flg_estado'     => $estado,
                                                    'fecha_apertura' => date('Y-m-d')
            ));
            if($this->db->trans_status() === FALSE){
                throw new Exception('No se realiz&oacute; la acci&oacute;n');
            }
            $data['error']  = EXIT_SUCCESS;
            $data['msj'] = "Se apertur&oacute;";
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getLastIdTipoPreguntaByPreguntaEncuesta($idPregunta,$idEncuesta){
        $sql = "SELECT pec._id_tipo_pregunta
                  FROM senc.pregunta_x_enc_cate pec
                 WHERE pec._id_pregunta = ?
                   AND pec._id_encuesta = ?";
        $result = $this->db->query($sql,array($idPregunta,$idEncuesta));
        return $result->row()->_id_tipo_pregunta;
    }
    
    function getTipoPreguntaByPreguntaEncuesta($idEncuesta,$idPregunta){
        $sql = "SELECT _id_tipo_pregunta
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_encuesta  = ?
                   AND _id_pregunta = ?";
        $result = $this->db->query($sql,array($idEncuesta,$idPregunta));
        if($result->num_rows() == 1) {
            return $result->row()->_id_tipo_pregunta;
        } else{
            return null;
        }
    }
    
    function getCountEncuestasByTipo($idTipoEncuesta){
        $sql = "SELECT (COUNT(1)+1) cant_enc
                  FROM senc.encuesta e
                 WHERE e._id_tipo_encuesta = ?
                   AND (SELECT EXTRACT (YEAR FROM e.fecha_registro)) = (SELECT EXTRACT(YEAR FROM now()))";
        $result = $this->db->query($sql,array($idTipoEncuesta));
        return $result->row()->cant_enc;
    }
    
    function getEncuestasByTipoEncuestaLibreAperturada($tipoEncuesta, $tipoEncuestado){
        $sql = "SELECT e.id_encuesta,
                       e._id_tipo_encuesta,
                       e.desc_enc,
                       e.flg_estado,
                       e.titulo_encuesta,
                       e.fecha_apertura,
                       COALESCE(e.cant_encuestados, 0) as cant_encuestados,
                       CASE WHEN e._id_tipo_encuesta = ".TIPO_ENCUESTA_LIBRE." THEN '../senc/c_encuesta_nueva/c_encuesta'
                            ELSE '../senc/c_encuesta_nueva/c_encuesta_efqm' END AS ruta
                  FROM senc.encuesta e
                 WHERE e._id_tipo_encuesta IN (?, ?)
                   AND e.flg_estado = ?
                   AND CASE WHEN e._id_tipo_encuesta = ".TIPO_ENCUESTA_LIBRE." THEN
                                 ? IN (SELECT ete._id_tipo_encuestado FROM senc.encuesta_x_tipo_encuestado ete WHERE ete._id_encuesta = e.id_encuesta)
                            ELSE 1 = 1 END
              ORDER BY e.fecha_apertura DESC";
        $result = $this->db->query($sql,array($tipoEncuesta, TIPO_ENCUESTA_LIBRE, ENCUESTA_APERTURADA, $tipoEncuestado));
        return $result->result();
    }
    
    function updateOrdenByEncuestaCategoria($idEncuesta,$idCategoria,$actualOrdem){
        $sql = "UPDATE senc.pregunta_x_enc_cate
                   SET orden = orden - 1
                 WHERE orden > ?
                   AND _id_categoria = ? 
                   AND _id_encuesta  = ? ";
        $this->db->query($sql,array($actualOrdem, $idCategoria, $idEncuesta));
    }
    
    function getActualOrdenByPreguntaEncCate($idEncuesta,$idCategoria,$idPregunta){
        $sql = "SELECT x.orden
                  FROM senc.pregunta_x_enc_cate x
                 WHERE x._id_encuesta  = ?
                   AND x._id_categoria = ?
                   AND x._id_pregunta  = ?";
        $result = $this->db->query($sql,array($idEncuesta, $idCategoria, $idPregunta));
        return $result->row()->orden;
    }
    
    function getPrevNextPregunta($idEncuesta,$idCategoria,$orden,$direccion){
        $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
        $sql = "SELECT _id_pregunta
                  FROM senc.pregunta_x_enc_cate 
                 WHERE orden = ?
                   AND _id_categoria = ?
                   AND _id_encuesta = ?";
        $result = $this->db->query($sql,array($orden, $idCategoria, $idEncuesta));
        return $result->row()->_id_pregunta;
    }
    
    function updateOrdenByPregunta($array1,$array2){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('_id_pregunta'  , $array1['_id_pregunta']);
            $this->db->where('_id_categoria' , $array1['_id_categoria']);
            $this->db->where('_id_encuesta'  , $array1['_id_encuesta']);
            $this->db->update('senc.pregunta_x_enc_cate',array('orden' => $array1['orden']));
            if($this->db->affected_rows() != 1){
                throw new Exception('No se actualiz&oacute;');
            }
            $this->db->where('_id_pregunta'  , $array2['_id_pregunta']);
            $this->db->where('_id_categoria' , $array2['_id_categoria']);
            $this->db->where('_id_encuesta'  , $array2['_id_encuesta']);
            $this->db->update('senc.pregunta_x_enc_cate',array('orden' => $array2['orden']));
            if($this->db->affected_rows() != 1){
                throw new Exception('No se actualiz&oacute;');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getPrevNextCategoria($idEncuesta,$orden,$direccion){
        $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
        $sql = "SELECT _id_categoria
                  FROM senc.categoria_x_encuesta 
                 WHERE orden = ?
                   AND _id_encuesta = ?";
        $result = $this->db->query($sql,array($orden,$idEncuesta));
        return $result->row()->_id_categoria;
    }
    
    function updateOrdenByCategoria($array1,$array2){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('_id_categoria' , $array1['_id_categoria']);
            $this->db->where('_id_encuesta'  , $array1['_id_encuesta']);
            $this->db->update('senc.categoria_x_encuesta',array('orden' => $array1['orden']));
            if($this->db->affected_rows() != 1){
                throw new Exception('No se actualiz&oacute;');
            }
            $this->db->where('_id_categoria' , $array2['_id_categoria']);
            $this->db->where('_id_encuesta'  , $array2['_id_encuesta']);
            $this->db->update('senc.categoria_x_encuesta',array('orden' => $array2['orden']));
            if($this->db->affected_rows() != 1){
                throw new Exception('No se actualiz&oacute;');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function updateOrdenByEncuesta($idEncuesta,$actualOrdem){
        $sql = "UPDATE senc.categoria_x_encuesta
                   SET orden = orden - 1
                 WHERE orden > ?
                   AND _id_encuesta  = ? ";
        $this->db->query($sql,array($actualOrdem, $idEncuesta));
    
    }
    
    function verificaTieneTipoEncuestadosByEncuesta($idEncuesta){
        $sql = "SELECT COUNT(1) cuenta
                  FROM  senc.encuesta_x_tipo_encuestado x
                 WHERE x._id_encuesta = ?";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->row()->cuenta;
    }
    
    function validarEncuestaAperturada($idEncuesta){
        $sql = "SELECT COUNT(1) cuenta
                  FROM senc.encuesta 
                 WHERE id_encuesta  = ?
                   AND flg_estado = ?";
        $result = $this->db->query($sql,array($idEncuesta, ENCUESTA_APERTURADA));
        return $result->row()->cuenta;
    }
    
    function getTipoEncuestaById($tipoEncuestas){
        $sql = "SELECT desc_tipo_encuesta,
                       id_tipo_encuesta
                  FROM senc.tipo_encuesta
                 WHERE id_tipo_encuesta IN ?";
        $result = $this->db->query($sql,array($tipoEncuestas));
        return $result->result();
    }
    
    function getYearFromEncuesta($idEncuesta){
        $sql = "SELECT EXTRACT (YEAR FROM fecha_registro) as year
                  FROM senc.encuesta
                 WHERE id_encuesta = ?";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->row()->year;
    }
    
    function getAllRespuestasByEncuesta($idEncuesta = null){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                	{$unwind  : "$preguntas"},
                	{$match   : {"id_encuesta" : 7}},
                	{$project : {"preguntas.desc_pregunta" : -1,"preguntas.id_pregunta" : -1,"preguntas.respuesta" : -1,"preguntas.id_dispositivo" : -1 , "id_sede" : -1,"id_nivel" : -1,"id_grado" : -1,"id_aula" : -1,"id_area" : -1,"desc_encuesta" : -1}},
                	{$group   : {_id: {pregunta : "$preguntas.id_pregunta", persona : "$preguntas.id_dispositivo"}, desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_encuesta : {$first : "$desc_encuesta"}, respuesta : {$first : "$preguntas.respuesta"} , id_sede : {$first : "$id_sede"}, id_nivel : {$first : "$id_nivel"} 	, id_grado : {$first : "$id_grado"}, id_aula : {$first : "$id_aula"}, id_area : {$first : "$id_area"} }}
                ])';
        $result = $db->execute('return '.utf8_encode($sql).'.toArray()');
    }
    
    function getAllPreguntasCategoriaTabla($idEncuesta){
        $sql = "SELECT c.desc_cate,
                       (SELECT string_agg(CONCAT(p.desc_pregunta,'|',p.id_pregunta),',')
                          FROM senc.preguntas p,
                               senc.pregunta_x_enc_cate pec
                         WHERE p.id_pregunta = pec._id_pregunta
                           AND pec._id_categoria = c.id_categoria
                           AND pec._id_encuesta  = ?) preguntas
                  FROM senc.encuesta e,
                       senc.categoria c,
                       senc.categoria_x_encuesta ce
                 WHERE e.id_encuesta  = ?
                   AND e.id_encuesta  = ce._id_encuesta
                   AND c.id_categoria = ce._id_categoria";
        $result = $this->db->query($sql,array($idEncuesta,$idEncuesta));
        return $result->result();
    }
    
    function getRptasByPregunta($arrayIdRpta){
        $sql = "SELECT string_agg(a.desc_alternativa,',') respuestas
                  FROM senc.alternativa a
                 WHERE a.id_alternativa IN ?";
        $result = $this->db->query($sql,array($arrayIdRpta));
        return $result->row()->respuestas;
    }
    
    function getNivelesByTipoEncuestado($tipoEncuestado,$idPersona){
        $result = null;
        if($tipoEncuestado == 'D' || $tipoEncuestado == 'A'){
            $sql = "SELECT id_sede_control  as sede,
                           id_nivel_control as nivel,
                           CASE WHEN('D' = ?) THEN trim(to_char(id_area_especifica,'9999999999999'))
                    	                      ELSE trim(to_char(id_area_general,'9999999999999'))
                           END              as id_area
                      FROM rrhh.personal_detalle
                     WHERE id_persona = ?
                     LIMIT 1";
            $result = $this->db->query($sql,array($tipoEncuestado,$idPersona))->row_array();
        } else{
            $sql = "SELECT a.nid_sede  as sede,
                           a.nid_nivel as nivel,
                           a.nid_grado as grado,
                           a.nid_aula  as aula
                      FROM persona_x_aula pa,
                           aula a
                     WHERE pa.__id_persona = ?
                       AND pa.__id_aula    = a.nid_aula
                    ORDER BY year_academico DESC
                     LIMIT 1";
            $result = $this->db->query($sql,array($idPersona))->row_array();
        }
        $data['sede']  = ($result['sede']);
        $data['nivel'] = ($result['nivel']);
        $data['grado'] = (isset($result['id_grado']) ? $result['id_grado'] : null);
        $data['aula']  = (isset($result['id_aula']) ? $result['id_aula'] : null);
        $data['area']  = ($result['id_area']);
        return $data;
    }
    
    function getTipoEncuestadosByEncuesta($encuestas){
        $sql = "SELECT tp.id_tipo_encuestado,
                       UPPER(tp.desc_tipo_enc) desc_tipo_enc
                  FROM senc.tipo_encuestado tp,
                       senc.encuesta_x_tipo_encuestado e
                 WHERE tp.id_tipo_encuestado = e._id_tipo_encuestado
                   AND e._id_encuesta IN ?
                 GROUP BY tp.id_tipo_encuestado
                 ORDER BY tp.desc_tipo_enc";
        $result = $this->db->query($sql, array($encuestas));
        return $result->result();
    }
    
    function getCorreoByPersAdmin($idPersona,$tipoEncuGlobal){
        $sql = "/*SELECT( CASE WHEN 'A' = ? 
                             THEN  (*/SELECT CASE WHEN p.correo_inst IS NOT NULL 
                                                THEN p.correo_inst
                                                ELSE p.correo_admi 
                                            END AS correo
                                      FROM persona p
                                     WHERE p.nid_persona = ?/*)
                             WHEN 'D' = ?
                             THEN (SELECT CASE WHEN email1 IS NOT NULL 
                                               THEN email1
                                           END AS correo
                                     FROM familiar 
                                    WHERE id_familiar = ?) 
                             ELSE ''
                       END ) AS correo*/";
        $result = $this->db->query($sql, array($tipoEncuGlobal,$idPersona,$tipoEncuGlobal,$idPersona));
        return $result->row()->correo;
    }
    
    function getCantParticipanesPregObli($idEncuesta,$idPregunta){
        $sql = "SELECT
                    (SELECT SUM(cant_encuestados) cant_encuestados
                       FROM senc.encuesta
                      WHERE id_encuesta IN ?),
                    
                    (SELECT flg_obligatorio
                       FROM senc.pregunta_x_enc_cate
                      WHERE _id_encuesta IN ?
                        AND _id_pregunta = ?)";
        $result = $this->db->query($sql,array($idEncuesta,$idEncuesta,$idPregunta));
        return $result->row_array();
    }
    
    function getEncuestaAperturadaEFQM_PPFF() {
        $sql = "SELECT id_encuesta
                  FROM senc.encuesta
                 WHERE _id_tipo_encuesta = ".TIPO_ENCUESTA_PADREFAM."
                   AND flg_estado        = '".ENCUESTA_APERTURADA."' ";
        $result = $this->db->query($sql);
        if($result->num_rows() != 1) {
            return null;
        }
        return $result->row()->id_encuesta;
    }
    
    function getEncuestasEFQM_Aperturadas() {
        $sql = "SELECT id_encuesta,
                       titulo_encuesta,
                       row_number() OVER() AS rownum
                  FROM senc.encuesta
                 WHERE _id_tipo_encuesta IN (".TIPO_ENCUESTA_PADREFAM.", ".TIPO_ENCUESTA_ALUMNOS.",".TIPO_ENCUESTA_DOCENTE.",".TIPO_ENCUESTA_PERSADM.")
                   AND flg_estado        = '".ENCUESTA_APERTURADA."' ";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
    
    function countEncuestaByTipoEnc($tipoEnc){
        $sql = "SELECT COUNT(1) as count
                  FROM senc.encuesta
                 WHERE _id_tipo_encuesta = ?
                   AND flg_estado = '".ENCUESTA_APERTURADA."'";
        $result = $this->db->query($sql, array($tipoEnc));
        return $result->row()->count;
    }
    
    function getAllArrPullMongo(){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
//         $sql = "SELECT string_agg(trim(to_char(id_device_info,'999999')),',') disp
//                   FROM senc.device_info_encuestado
//                  WHERE id_encuesta = 32
//                    AND id_device_info NOT IN (1188,1185,1193)";
//         $disp = $this->db->query($sql)->row()->disp;
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  : "$preguntas"},
                    {$match   : {id_encuesta : 37}},
                    {$match   : {"preguntas.id_dispositivo" : {$in : [5455,5453,5452,5451]} } }
            ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        
        $sql = 'db.senc_propuesta_comentario.aggregate([
                    {$unwind  : "$propuestas"},
                    {$match   : {id_encuesta : 37}},
                    {$match   : {"propuestas.id_dispositivo" : {$in : [5455,5453,5452,5451]} } }
            ])';
        $result2 = $db->execute('return '.$sql.'.toArray()');
        return array($result['retval'],$result2['retval']);
    }
    
    function sanearColecciones($arrayGeneral,$array = array(array())){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        for($i = 0; $i < count($arrayGeneral[0]); $i++){
            $result = $db->execute('return '.$arrayGeneral[0][$i]);
            $result = $db->execute('return '.$arrayGeneral[1][$i]);
        }
        for($i = 0; $i < count($array[0]); $i++){
            $result = $db->execute('return '.$array[0][$i]);
            $result = $db->execute('return '.$array[1][$i]);
        }
    }
    
    function datosProceso($idAula){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                	{$unwind  :  "$preguntas"},
                	{$match   : { id_encuesta : 32}},
                	//{$match   : { id_aula     : {$in : ['.$idAula.']} } },
                	{$match   : { "preguntas.id_pregunta" : {$nin : [99,98] } } },
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1, 
                				  "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1, "id_aula" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta", aula : "$id_aula" } , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"}, 
                				  desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"}, id_aula : {$first : "$id_aula"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : {pregunta : "$id_pregunta", aula : "$id_aula"}, datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }
                ])';
        $result2 = $db->execute('return '.$sql.'.toArray()');
        return $result2['retval'];
    }
    
    function getArraysByPreguntaEncuesta($idEncuestas,$preguntas){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_satisfaccion_encuesta.aggregate([
                    { $unwind : "$preguntas"},
                	{ $match  : { id_encuesta : {$in : ['.$idEncuestas.']} } },
                    { $match  : {"preguntas.id_pregunta" : {$in : ['.$preguntas.'] } } },
                	{ $group  : { _id : {id_encuesta : "$id_encuesta", id_aula : "$preguntas.id_aula",id_pregunta: "$preguntas.id_pregunta"}, id_grado : {$first : "$id_grado"}, id_nivel : {$first : "$id_nivel"}, id_sede : {$first : "$id_sede"} } }
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result['retval'];
    }
    
    function getPreguntasByEncuestas($encuestas){
        $sql = "SELECT _id_pregunta,
                       _id_indicador_bsc AS _id_servicio
                  FROM senc.pregunta_x_enc_cate pec,
                       senc.preguntas p
                 WHERE _id_encuesta IN ?
                   AND p.id_pregunta = pec._id_pregunta
                   AND p._id_indicador_bsc IS NOT NULL 
              GROUP BY _id_pregunta, _id_indicador_bsc";
        $result = $this->db->query($sql, array($encuestas));
        return $result->result();
    }
    
    function executeQuerysServicios($arrayGeneral){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        foreach($arrayGeneral as $sql) {
            $result = $db->execute('return '.$sql);
        }
    }
    
    function getArrayPreguntas(){
        $sql = "select string_agg(trim(to_char(_id_pregunta,'9999')),',') preguntas from senc.pregunta_x_enc_cate where _id_encuesta = 32";
        $result = $this->db->query($sql);
        return explode(',', $result->row()->preguntas);
    }
	
	function getPropuestasByDispositivos(){
		$m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
		$sql = 'db.senc_respuesta_encuesta.aggregate([
				{$unwind  : "$preguntas"},
				{$project : {"id_aula" : -1, "id_encuesta" : -1, "preguntas.id_dispositivo" : -1}},
				{$match   : {id_aula     : 51241}},
				{$match   : {id_encuesta : 32}},
			    {$sort    : {"preguntas.id_dispositivo" : 1}},
				{$group   : {_id:"$preguntas.id_dispositivo"}},
				{$group   : {_id:"$_id"}},
				{$sort    : {"_id" : 1}}
			])';
	    $result = $db->execute('return '.$sql.'.toArray()');
		$str = null;
		foreach($result['retval'] as $row){
			$str .= $row['_id'].',';
		}
	}
	
	function getAllEncuestasLibres(){
	    $sql = "SELECT string_agg(trim(to_char(id_encuesta,'99999')),',') encuestas
                  FROM senc.encuesta
                 WHERE _id_tipo_encuesta = 5";
	    $result = $this->db->query($sql);
	    return $result->row()->encuestas;
	}
	
	function getRespuestasDispositivos($encuestas){
	    $m   = new MongoClient(MONGO_CONEXION);
	    $db  = $m->selectDB(SMILEDU_MONGO);
	    $sql = 'db.senc_respuesta_encuesta.aggregate([
					{ $unwind : "$preguntas"},
                	{ $match  : { id_encuesta : {$in : [27]}, "preguntas.id_dispositivo" : {$lt : 3151} } },
					{ $group  : { _id : "$preguntas.id_dispositivo",id_sede : {$first : "$id_sede"},id_nivel : {$first : "$id_nivel"},id_grado : {$first : "$id_grado"},id_aula : {$first : "$id_aula"}, arrayPreguntas : {$push : {id_pregunta : "$preguntas.id_pregunta", desc_pregunta : "$preguntas.desc_pregunta", id_respuesta : "$preguntas.id_respuesta", respuesta : "$preguntas.respuesta", tipo_encuestado : "$preguntas.tipo_encuestado", count : "$preguntas.count"}   } }}
                ])';
	    $result = $db->execute('return '.$sql.'.toArray()');
	    return $result['retval'];
	}
	
	function getPropuestasDispositivos($encuestas){
	    $m   = new MongoClient(MONGO_CONEXION);
	    $db  = $m->selectDB(SMILEDU_MONGO);
	    $sql = 'db.senc_propuesta_comentario.aggregate([
                	{ $unwind : "$propuestas"}, 
                	{ $match  : { id_encuesta : {$in : [27]}, "propuestas.id_dispositivo" : {$lt : 3151} } },
                	{ $group  : { _id : "$propuestas.id_dispositivo", propuestas : {$first : "$propuestas.id_propuesta"} , comentario : {$first : "$propuestas.comentario"} , tipo_encuestado : {$first : "$propuestas.tipo_encuestado"}   }}
                ])';
	    $result = $db->execute('return '.$sql.'.toArray()');
	    return $result['retval'];
	}
	
	function updateDispositivos($arrayUpdate){
	    $this->db->update_batch('senc.device_info_encuestado', $arrayUpdate,'id_device_info');
	}
	
	function actualizarCompartidos($idEncuesta, $jsonNew) {
	    $currentJSONB = $this->m_utils->getById('senc.encuesta', 'compartidos_jsonb', 'id_encuesta', $idEncuesta);
	    $sql = 'SELECT CONCAT(\'[\',jsonb_agregar, \']\') AS jsonb_agregar
	              FROM jsonb_agregar(?, ?)';
	    $result = $this->db->query($sql, array($currentJSONB, $jsonNew));
	    $this->db->where('id_encuesta', $idEncuesta);
	    $this->db->update('senc.encuesta', array('compartidos_jsonb' => ($result->row()->jsonb_agregar)));
	    if($this->db->affected_rows() != 1) {
	        throw new Exception('No se pudo actualizar el listado de compartidos');
	    }
	    return array('error' => EXIT_SUCCESS, 'msj' => 'Se actualizó correctamente');
	}
	
	function quitarCompartido($idEncuesta, $idPersona) {
	    $currentJSONB = $this->m_utils->getById('senc.encuesta', 'compartidos_jsonb', 'id_encuesta', $idEncuesta);
	    $sql = 'SELECT CONCAT(\'[\',jsonb_borrar, \']\') AS jsonb_borrar
	              FROM jsonb_borrar(?, ?)';
	    $result = $this->db->query($sql, array($currentJSONB, $idPersona));
	    $this->db->where('id_encuesta', $idEncuesta);
	    $this->db->update('senc.encuesta', array('compartidos_jsonb' => ($result->row()->jsonb_borrar)));
	    if($this->db->affected_rows() != 1) {
	        throw new Exception('No se pudo actualizar el listado de compartidos');
	    }
	    return array('error' => EXIT_SUCCESS, 'msj' => 'Se actualizó correctamente');
	}
	
	function getListadoCompartidosByEncuesta($idEncuesta) {
	    $sql = "SELECT t.*,
                	   CONCAT(SPLIT_PART(INITCAP(p.nom_persona),' ',1),' ', UPPER(p.ape_pate_pers), ' ', UPPER(p.ape_mate_pers)) nombres,
                	   CASE WHEN p.correo_inst IS NOT NULL THEN p.correo_inst
                			WHEN p.correo_admi IS NOT NULL THEN p.correo_admi
                			WHEN p.correo_pers IS NOT NULL THEN p.correo_pers
                			ELSE NULL END AS correo,
                	   CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p.foto_persona)
                			WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                			ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
                	   ROW_NUMBER() OVER () AS rnum
                  FROM (SELECT (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS nid_persona,
                               (jsonb_array_elements(compartidos_jsonb)->>'permisos')::text AS permisos
                		  FROM senc.encuesta e
                		 WHERE e.id_encuesta = ?) AS t,
                       persona p
                 WHERE p.nid_persona = t.nid_persona";
	    $result = $this->db->query($sql, array($idEncuesta));
	    return $result->result_array();
	}
	
	function check_SitieneElPermiso($idEncuesta, $permiso, $idPersona) {
	    $sql = "SELECT 1
                  FROM (SELECT (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS id_persona,
                			   (jsonb_array_elements(compartidos_jsonb)->>'permisos')::text AS permisos
                		  FROM senc.encuesta e
                		 WHERE e.id_encuesta = ?) AS t
                 WHERE t.permisos   LIKE ?
	               AND t.id_persona = ?";
	    $result = $this->db->query($sql, array($idEncuesta, '%'.$permiso.'%', $idPersona));
	    if($result->num_rows() == 1) {
	        return true;
	    }
	    return false;
	}
	
	function check_SiPuedesQuitarPermiso($idEncuesta, $idPersona, $idPersonaSess) {
	    $sql = "SELECT 1
                  FROM (SELECT (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS id_persona,
	                           (jsonb_array_elements(compartidos_jsonb)->>'id_pers_comparte')::integer AS id_pers_comparte
                		  FROM senc.encuesta e
                		 WHERE e.id_encuesta = ?) AS t
                 WHERE t.id_pers_comparte = ?
	               AND t.id_persona       = ?";
	    $result = $this->db->query($sql, array($idEncuesta, $idPersonaSess, $idPersona));
	    if($result->num_rows() == 1) {
	        return true;
	    }
	    return false;
	}
	
	function getCurrentPermisos($idEncuesta, $idPersona, $permisoToAdd) {
	    $sql = "SELECT CONCAT(t.permisos, ?) AS permisos
                  FROM (SELECT (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS id_persona,
                			   (jsonb_array_elements(compartidos_jsonb)->>'permisos')::text AS permisos
                		  FROM senc.encuesta e
                		 WHERE e.id_encuesta = ?) AS t
                 WHERE t.id_persona = ?";
	    $result = $this->db->query($sql, array($permisoToAdd, $idEncuesta, $idPersona));
	    if($result->num_rows() == 1) {
	        return $result->row()->permisos;
	    }
	    return null;
	}
	
	function getPermisosAfterBorrar($idEncuesta, $idPersona, $permisosToDelete) {
	    $sql = "SELECT REPLACE(t.permisos, ?, '') AS permisos
                  FROM (SELECT (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer AS id_persona,
                			   (jsonb_array_elements(compartidos_jsonb)->>'permisos')::text AS permisos
                		  FROM senc.encuesta e
                		 WHERE e.id_encuesta = ?) AS t
                 WHERE t.id_persona = ?";
	    $result = $this->db->query($sql, array($permisosToDelete, $idEncuesta, $idPersona));
	    if($result->num_rows() == 1) {
	        return $result->row()->permisos;
	    }
	    return null;
	}
	
	function buscarPersonalSinCompartidos($busqueda, $idEncuesta, $idPersonaBusqueda) {
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
                   AND UPPER(CONCAT(SPLIT_PART(INITCAP(p.nom_persona),' ',1),' ',p.ape_pate_pers,' ',p.ape_mate_pers)) LIKE UPPER(?)
                   AND p.nid_persona <> ?
                   AND p.nid_persona <> (SELECT e.audi_pers_regi FROM senc.encuesta e WHERE id_encuesta = ?)
                   AND p.nid_persona NOT IN (SELECT (jsonb_array_elements(compartidos_jsonb)->>'id_pers_compartido')::integer
                                               FROM senc.encuesta e
                                              WHERE e.id_encuesta = ?) ";
	    $result = $this->db->query($sql, array('%'.$busqueda.'%', $idPersonaBusqueda, $idEncuesta, $idEncuesta));
	    return $result->result_array();
	}
}