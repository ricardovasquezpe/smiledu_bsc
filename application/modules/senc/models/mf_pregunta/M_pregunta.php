<?php
class M_pregunta extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getPreguntasByTipoEncuesta($tipoEncuesta){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       (SELECT string_agg(DISTINCT(EXTRACT(YEAR FROM  e1.fecha_registro)::text),', ') 
                          FROM senc.pregunta_x_enc_cate pec1,
                               senc.encuesta            e1
                         WHERE pec1._id_pregunta = p.id_pregunta
                           AND pec1._id_encuesta = e1.id_encuesta) as years
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas           p,
                       senc.encuesta            e
                 WHERE pec.flg_estado        = '".ESTADO_ACTIVO."'
                   AND pec._id_encuesta      = e.id_encuesta 
                   AND e._id_tipo_encuesta   IN ?
                   AND pec._id_tipo_pregunta IN (1,2,8)
                   AND pec._id_pregunta      = p.id_pregunta
                ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($tipoEncuesta));
        return $result->result();
    }
    
    function getPreguntasByTipoEncuestaPersona($tipoEncuesta, $persona){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       (SELECT string_agg(DISTINCT(EXTRACT(YEAR FROM  e1.fecha_registro)::text),', ')
                          FROM senc.pregunta_x_enc_cate pec1,
                               senc.encuesta            e1
                         WHERE pec1._id_pregunta = p.id_pregunta
                           AND pec1._id_encuesta = e1.id_encuesta) as years
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas           p,
                       senc.encuesta            e
                 WHERE pec.flg_estado        = '".ESTADO_ACTIVO."'
                   AND pec._id_encuesta      = e.id_encuesta
                   AND e.audi_pers_regi      =  ?
                   AND e._id_tipo_encuesta   IN ?
                   AND pec._id_tipo_pregunta IN (1,2,8)
                   AND pec._id_pregunta      = p.id_pregunta
                ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($persona, $tipoEncuesta));
        return $result->result();
    }
    
    function getPreguntasByIdEncuesta($encuesta){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado      = '".ESTADO_ACTIVO."'
                   AND pec._id_pregunta    = p.id_pregunta
                   AND pec._id_encuesta    = e.id_encuesta
                   AND e.id_encuesta       = ?
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($encuesta));
        return $result->result();
    }
    
    function getPreguntasTipoByIdEncuesta($encuesta){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       pec._id_tipo_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado      = '".ESTADO_ACTIVO."'
                   AND pec._id_pregunta    = p.id_pregunta
                   AND pec._id_encuesta    = e.id_encuesta
                   AND e.id_encuesta       = ?
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($encuesta));
        return $result->result();
    }
    
    function getPreguntasById($encuesta, $preguntas){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       pec._id_tipo_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado      = '".ESTADO_ACTIVO."'
                   AND pec._id_encuesta    = e.id_encuesta
                   AND e.id_encuesta       = ?
                   AND pec._id_pregunta    = p.id_pregunta
                   AND pec._id_pregunta IN ?
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($encuesta, $preguntas));
        return $result->result();
    }
    
    function insertPregunta($arrayData){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('senc.preguntas',$arrayData);
            $data['idPreg'] = $this->db->insert_id();
            if($this->db->affected_rows() != 1){
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = "Se insertó la pregunta";
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getIdByDescPregunta($descPreg){
        $sql = 'SELECT p.id_pregunta
                  FROM senc.preguntas p
                 WHERE LOWER(p.desc_pregunta) = LOWER(?)';
        $result = $this->db->query($sql,array($descPreg));
        if($result->num_rows() > 0){
            return $result->row()->id_pregunta;
        } else{
            return null;
        }
    }
    
    function verificaExistePreguntaInCategoriaEnc($idEncuesta,$idCategoria,$idPregunta){
        $sql = "SELECT COUNT(1) cuenta
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_encuesta  = ?
                   AND _id_categoria = ?
                   AND _id_pregunta  = ?";
        $result = $this->db->query($sql,array($idEncuesta,$idCategoria,$idPregunta));
        if($result->num_rows() > 0){
            return $result->row()->cuenta;
        } else{
            return null;
        } 
    }
    
    function getAllTipoPreguntas($idTipoPreg = null){
        $sql = "SELECT id_tipo_pregunta,
                       desc_tipo_preg,
                       CASE WHEN id_tipo_pregunta = ? THEN 'selected' ELSE null
                       END AS selected
                  FROM senc.tipo_pregunta";
        $result = $this->db->query($sql,array($idTipoPreg));
        return $result->result();
    }
    
    function getExisteTipoPregByPregunta($idEncuesta,$idPregunta){
        $sql = "SELECT todo.cuenta,
                       todo._id_tipo_pregunta
                  FROM (SELECT (SELECT COUNT(1) cuenta
                		  FROM senc.pregunta_x_enc_cate
                		 WHERE _id_encuesta =  ?
                		   AND _id_pregunta = ?) cuenta,
                	   (SELECT _id_tipo_pregunta 
                		  FROM senc.pregunta_x_enc_cate
                		 WHERE _id_encuesta = ?
                		   AND _id_pregunta = ?)) todo";
        $result = $this->db->query($sql,array($idEncuesta,$idPregunta,$idEncuesta,$idPregunta));
        return $result->row_array();
    }
    
    function updateTipoPreguntaByPregunta($idEncuestaSession,$idPregunta,$idOptionSelected){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idOptionSelected = ($idOptionSelected == -1) ? null : $idOptionSelected;
            $this->db->trans_begin();
            $data = $this->deleteOpcionesByPreguntaEncuesta($idEncuestaSession, $idPregunta);
            if($data['error'] == EXIT_ERROR){
                $this->db->trans_rollback();
                throw new Exception('No se actualizó');
            }
            $this->db->where('_id_encuesta' , $idEncuestaSession);
            $this->db->where('_id_pregunta' , $idPregunta);
            $this->db->update('senc.pregunta_x_enc_cate' , array('_id_tipo_pregunta' => $idOptionSelected));
            if($this->db->affected_rows() != 1){
                $this->db->trans_rollback();
                throw new Exception('No se actualizó');
            }
            $data['error'] = EXIT_SUCCESS;
            $this->db->trans_commit();
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function deleteOpcionesByPreguntaEncuesta($idEncuesta,$idPregunta){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('_id_encuesta',$idEncuesta);
            $this->db->where('_id_pregunta',$idPregunta);
            $this->db->delete('senc.alter_x_tipo_preg_x_preg');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Ocurrio un error... comuníquese con alguien a cargo');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getCantidadPreguntasObligatoriasByEncuesta($idEncuesta){
        $sql = "SELECT COUNT(1) cuenta
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_encuesta = ?
                   AND flg_obligatorio = '1'";
        $result = $this->db->query($sql,array($idEncuesta));
        return $result->row()->cuenta;
    }
    
    function getCantidadPregConAlternativas($idEncuesta){
        $sql = "SELECT(
                 SELECT COUNT(1) AS count
                  FROM(SELECT count(1)
                	     FROM senc.pregunta_x_enc_cate      pec,
                              senc.alter_x_tipo_preg_x_preg atpp
                        WHERE pec._id_encuesta = ?
                          AND pec._id_pregunta = atpp._id_pregunta
                          AND pec._id_encuesta = atpp._id_encuesta
                          AND pec._id_tipo_pregunta = atpp._id_tipo_pregunta 
                     GROUP BY pec._id_pregunta) AS COUNT
                    ) AS cant_preg_alter,
                    (
                	SELECT COUNT(1) cuenta
                      FROM senc.pregunta_x_enc_cate
                     WHERE _id_encuesta = ?
                    ) AS cant_preg_enc";
        $result = $this->db->query($sql,array($idEncuesta, $idEncuesta));
        return $result->row_array();
    }
    
    function getCantidadCategoriaConPreguntas($idEncuesta){
        $sql = "SELECT(
                       SELECT COUNT(1) cuenta
                         FROM senc.categoria_x_encuesta
                        WHERE _id_encuesta = ?) AS cant_cate_enc,
                       (SELECT COUNT(1) AS count
                        FROM(SELECT count(1)
                               FROM senc.pregunta_x_enc_cate  pec
                              WHERE pec._id_encuesta = ?
                           GROUP BY pec._id_categoria) AS COUNT) AS cant_cate_enc_preg";
        
        $result = $this->db->query($sql,array($idEncuesta, $idEncuesta));
        return $result->row_array();
    }
    
    function getAllPreguntas(){
        $sql = "SELECT p.id_pregunta,
                       p.desc_pregunta,
                       p.flg_estado,
                       p._id_servicio
                  FROM senc.preguntas p";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllServicios(){
        $sql = "SELECT s.id_servicio,
                       s.desc_servicio
                  FROM servicio s
                 WHERE flg_acti = '".ACTIVO_."'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function editServicioPregunta($idPregunta, $idServicio){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_pregunta' , $idPregunta);
            $this->db->update('senc.preguntas' , array('_id_servicio' => $idServicio));
            if($this->db->affected_rows() != 1){
                $this->db->trans_rollback();
                throw new Exception('No se actualizó');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = "Se actualizó";
            $this->db->trans_commit();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        return $data;   
    }
    
    function editPregunta($idPregunta, $descripcion){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_pregunta' , $idPregunta);
            $this->db->update('senc.preguntas' , array('desc_pregunta' => $descripcion));
            if($this->db->affected_rows() != 1){
                $this->db->trans_rollback();
                throw new Exception('No se actualizó');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = "Se actualizó";
            $this->db->trans_commit();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        
        return $data;
    }
    
    function getAllPreguntasServicios(){
        $sql = "SELECT p.id_pregunta,
                       p.desc_pregunta,
                       p.flg_estado,
                       p._id_servicio,
                       (SELECT COUNT(1)
                          FROM senc.pregunta_x_enc_cate pec,
                               senc.encuesta e
                          WHERE pec._id_pregunta = p.id_pregunta
                            AND e.id_encuesta = pec._id_encuesta
                            AND e.flg_estado IN ('".ENCUESTA_APERTURADA."', '".ENCUESTA_FINALIZADA."')) AS count,
                       array_to_json(p._id_indicador_bsc_array) as _id_indicador_bsc_array
                  FROM senc.preguntas p
                ORDER BY p.desc_pregunta";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getPreguntasCaritasByIdEncuesta($encuesta){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado      = '".ESTADO_ACTIVO."'
                   AND pec._id_pregunta    = p.id_pregunta
                   AND pec._id_encuesta    = e.id_encuesta
                   AND e.id_encuesta       = ?
                   AND (pec._id_tipo_pregunta = ".CINCO_CARITAS." OR
                        pec._id_tipo_pregunta = ".TRES_CARITAS."  OR
                        pec._id_tipo_pregunta = ".CUATRO_CARITAS.")
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($encuesta));
        return $result->result();
    }
    
    function verificaExistePreguntaInEnc($idEncuesta, $idPregunta){
        $sql = "SELECT COUNT(1) cuenta
                  FROM senc.pregunta_x_enc_cate
                 WHERE _id_encuesta  = ?
                   AND _id_pregunta  = ?";
        $result = $this->db->query($sql,array($idEncuesta, $idPregunta));
        if($result->num_rows() > 0){
            return $result->row()->cuenta;
        } else{
            return null;
        }
    }
    
    function getPreguntasTipoByIdEncuestas($encuestas){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       pec._id_tipo_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado   = '".ESTADO_ACTIVO."'
                   AND pec._id_pregunta = p.id_pregunta
                   AND pec._id_encuesta = e.id_encuesta
                   AND e.id_encuesta    IN ?
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, array($encuestas));
        return $result->result();
    }
    
    function getAllRespuestasPreguntasByEncuestas($idEncuesta = null){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                	{$unwind  : "$preguntas"},
                	{$match   : {id_encuesta : {$in : ['.$idEncuesta.']}}},
                	{$project : {"id_encuesta" : -1 , "id_sede" : -1, "id_nivel" : -1, "id_grado" : -1, "id_aula" : -1, "id_area" : -1, "preguntas.respuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.id_pregunta" : -1, "preguntas.id_dispositivo" : -1}},
                	{$group   : {_id : {pregunta : "$preguntas.id_pregunta", encuesta : "$id_encuesta"}, desc_pregunta : {$first : "$preguntas.desc_pregunta"}, respuesta : {$push : {desc_respuesta : "$preguntas.respuesta", id_unico : "$preguntas.id_dispositivo"}}, niveles : {$push : {id_sede : "$id_sede", id_nivel : "$id_nivel", id_grado : "$id_grado" , id_aula : "$id_aula", id_area : "$id_area", id_unico : "$preguntas.id_dispositivo"}}, id_encuesta : {$first : "$id_encuesta"} }},
                	{$sort    : {"id_encuesta" : 1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        if(isset($result['retval'])){
            return $result['retval'];
        } else{
            return array();
        }
    }
    
    function getAllIdDispositivos($idEncuesta){
        $sql = "SELECT id_device_info as dispositivo
                  FROM senc.device_info_encuestado
                 WHERE id_encuesta = ?";
        $result = $this->db->query($sql, Array($idEncuesta));
        return $result->result();
    }
    
    function getAllComentarios($idEncuesta){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_comentario.aggregate([
                      {$unwind  : "$propuestas"},
                      {$match   : {id_encuesta : '.$idEncuesta.'}},
                      {$project : {"id_encuesta" : -1, "propuestas.comentario" : -1,"propuestas.id_propuesta" : -1, "propuestas.id_dispositivo": -1,"desc_encuesta" :-1,"id_encuesta" : -1}},
                	  {$group   : {"_id" : "$propuestas.id_dispositivo", comentario : {$first : "$propuestas.comentario"}, propuestas : {$first : "$propuestas.id_propuesta"}, desc_encuesta : {$first : "$desc_encuesta"}, id_encuesta : {$first : "$id_encuesta"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        if(isset($result['retval'])){
            return $result['retval'];
        } else{
            return array();
        }
    }
    
    function getPropuestasByDispositivoEncuesta($idEncuesta,$idDispositivo){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_comentario.aggregate([
                	{$unwind  : "$propuestas"},
                	{$match   : {id_encuesta : '.$idEncuesta.'}},
                	{$match   : {"propuestas.id_dispositivo" : '.$idDispositivo.'}},
                	{$project : {"propuestas.id_propuesta" : -1}},
                	{$group   : {_id : "$propuestas.id_propuesta"}}
                	
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        if(isset($result['retval'][0]['_id'])){
            return $result['retval'][0]['_id'];
        } else{
            return array();
        }
    }
    
    function updateArrayPropByComentario($idEncuesta,$idDispositivo,$propuestas){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_comentario.update(
                   {
                     id_encuesta : '.$idEncuesta.',
                     propuestas: { $elemMatch: { id_dispositivo : '.$idDispositivo.' } }
                   },
                   { $set: { "propuestas.$.id_propuesta" : ['.$propuestas.'] } }
                )';
        $result = $db->execute('return '.$sql);
        return $result['ok'];
    }
    
    function getAllIndicadores(){
        $sql = "SELECT desc_indicador,
                       _id_indicador
                  FROM bsc.indicador";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getArrayJsonIndicadoByPregunta($idPregunta){
        $sql = "SELECT array_to_json(_id_indicador_bsc_array) as _id_indicador_bsc_array
                  FROM senc.preguntas
                 WHERE id_pregunta = ?";
        $result = $this->db->query($sql, array($idPregunta));
        return $result->row()->_id_indicador_bsc_array;
    }
    
    function editIndicadorPregunta($idPregunta, $indicadores){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try{
            $this->db->trans_begin();
            $this->db->where('id_pregunta' , $idPregunta);
            $this->db->update('senc.preguntas' , array('_id_indicador_bsc_array' => $indicadores));
            if($this->db->affected_rows() != 1){
                $this->db->trans_rollback();
                throw new Exception('No se actualizó');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            $this->db->trans_commit();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }
}