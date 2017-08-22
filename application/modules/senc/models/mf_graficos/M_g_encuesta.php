<?php

class M_g_encuesta extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    /**
     * @author César Villarreal 03/03/2016
     * @param Id de la pregunta para buscar ca $idPregunta
     * @param unknown $idEncuesta
     * @return Suma de calificación por pregunta
     */
    function getGraficoEncuestaByPregunta($preguntas,$idEncuesta){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},					 
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"} , id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    /**
     * @author César Villarreal 04/03/2016
     * @param idPregunta seleccionada  $idPregunta
     * @param idEncuesta seleccionada $idEncuesta
     * @param idSede seleccionada $idSede
     * @return Suma de la calificacion por pregunta
     */
    function getGraficoEncuestaBySede($preguntas,$idEncuesta,$sedes,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {id_sede : {$in : ['.$sedes.']}}},
                  '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},					 
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoEncuestaByNivel($preguntas,$idEncuesta,$idSede,$idNivel,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {id_sede : {$in : ['.$idSede.']}}},
                  {$match   : {id_nivel : {$in : ['.$idNivel.']}}},
                  '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},                   
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoEncuestaByGrado($preguntas,$idEncuesta,$idSede,$idNivel,$grados,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {id_sede : {$in : ['.$idSede.']}}},
                  {$match   : {id_nivel : {$in : ['.$idNivel.']}}},
                  {$match   : {id_grado : {$in : ['.$grados.']}}},
                  '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    //"preguntas.id_aula" cambio a id_aula
    function getGraficoEncuestaByAula($preguntas,$idEncuesta,$idSede,$idNivel,$idGrados,$aulas,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {id_sede : {$in : ['.$idSede.']}}},
                  {$match   : {id_nivel : {$in : ['.$idNivel.']}}},
                  {$match   : {id_grado : {$in : ['.$idGrados.']}}},
                  {$match   : {id_aula : {$in : ['.$aulas.']}}},
                  '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    //"preguntas.id_area" cambio a id_area
    function getGraficoEncuestaByArea($preguntas,$idEncuesta,$idSede,$idNivel,$areas,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {id_sede : {$in : ['.$idSede.']}}},
                  {$match   : {id_nivel : {$in : ['.$idNivel.']}}},
                  {$match   : {id_area : {$in : ['.$areas.']}}},
                  '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1,"preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        _log($sql);
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    /*PARA C_G_PREGUNTA*/
    function getGraficoEncuestaTipoByPregunta($preguntas,$idEncuestas,$sedes){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $matchSedes          = ($sedes != null || $sedes != "") ? '{$match : { id_sede : {$in : ['.$sedes.'] } } },' : null;
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : {$in : ['.$idEncuestas.']}}},
                  '.$matchSedes.'
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    /*PARA PREGUNTA CHECKBOX*/
    function getGraficoEncuestaTipoByTipoPregCheck($preguntas,$idEncuestas,$tipoEncuestado,$sedes){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $matchTipoEncuestado = ($tipoEncuestado != null) ? '{$match  : {"preguntas.tipo_encuestado" : "'.$tipoEncuestado.'" } },' : null;
        $matchSedes          = ($sedes != null || $sedes != "") ? '{$match : { id_sede : {$in : ['.$sedes.'] } } },' : null;
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : {$in : ['.$idEncuestas.']}}},
                  '.$matchTipoEncuestado.$matchSedes.'
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1,
                               "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.id_dispositivo" : -1}},
                  {$group   : {_id: "$preguntas.id_dispositivo", count: { $sum: "$preguntas.count"},respuesta : {$first : "$preguntas.respuesta"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, 
                               sum : {$first : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$group   : {_id: "$respuesta", count: { $sum : "$count"}, preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"}, sum : {$sum : "$sum"} } },
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"},cant_encu: {$sum : "$sum"} , desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoEncuestado($preguntas, $idEncuesta, $encuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {"preguntas.tipo_encuestado" : "'.$encuestado.'"}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoPreguntasByEncuestas($preguntas, $idEncuestas,$sedes){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $matchSedes = ($sedes != null || $sedes != "") ? '{$match : { id_sede : {$in : ['.$sedes.'] } } },' : null;
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : {$in : ['.$idEncuestas.']}}},
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  '.$matchSedes.'
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql.'.toArray()');
        
        return $result;
    }
    
    function getGraficoEncuestadoEncuestas($preguntas, $idEncuestas, $encuestado,$sedes){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $matchSedes = ($sedes != null || $sedes != "") ? '{$match : { id_sede : {$in : ['.$sedes.'] } } },' : null; 
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : {$in : ['.$idEncuestas.']}}},
                  {$match   : {"preguntas.tipo_encuestado" : "'.$encuestado.'"}},
                  '.$matchSedes.'
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getPreguntasById($encuestas, $preguntas){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       pec._id_tipo_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado      = '".ESTADO_ACTIVO."'
                   AND pec._id_encuesta    = e.id_encuesta
                   AND e.id_encuesta       IN ?
                   AND pec._id_pregunta    = p.id_pregunta
                   AND pec._id_pregunta    IN ?
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($encuestas, $preguntas));
        
        return $result->result();
    }
    
    function getPreguntasTipoByIdEncuestas($encuestas){
        $sql = "SELECT DISTINCT (p.id_pregunta),
                       p.desc_pregunta,
                       pec._id_tipo_pregunta
                 FROM  senc.pregunta_x_enc_cate pec,
                       senc.preguntas p,
                       senc.encuesta e
                 WHERE pec.flg_estado      = '".ESTADO_ACTIVO."'
                   AND pec._id_pregunta    = p.id_pregunta
                   AND pec._id_encuesta    = e.id_encuesta
                   AND e.id_encuesta       IN ?
              ORDER BY p.id_pregunta";
        $result = $this->db->query($sql, Array($encuestas));
        return $result->result();_logLastQuery();
    }
    /*FIN C_G_PREGUNTA*/
    
    //"preguntas.id_area" cambio a id_area
    function getGraficoEncuestaBySedeArea($preguntas,$idEncuesta,$idSede,$areas,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {id_sede : {$in : ['.$idSede.']}}},
                  {$match   : {id_area : {$in : ['.$areas.']}}},
                  '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    //Traer datos de las preguntas generales 
    function getDataPreguntasGlobalesEncuesta($top,$idEncuesta,$nombre_coleccion){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $top  = ($top != null || is_numeric($top)) ? ', {$limit   : '.$top.'}' : null;
//         $sql = 'db.senc_'.$nombre_coleccion.'_encuesta.aggregate([
//         			{$unwind  : "$preguntas"},
//                     {$match   : {id_encuesta : '.$idEncuesta.'}},
//         			{$project : {"preguntas.count" : -1,"preguntas.desc_pregunta" : -1,"preguntas.id_pregunta":-1,"desc_encuesta":-1,"id_encuesta":-1,"year":-1}},
//         			{$group   : {_id : {id_pregunta : "$preguntas.id_pregunta" , id_encuesta : "$id_encuesta"}, id_pregunta : {$first :  "$preguntas.id_pregunta"}, year : {$first : "$year"} ,id_encuesta : {$first : "$id_encuesta"} , suma : {$sum : "$preguntas.count"}, desc_pregunta : {$first : "$preguntas.desc_pregunta"},desc_encuesta : {$first : "$desc_encuesta"},count : {$sum : "$preguntas.count"}}},
//         			{$group   : {_id : "$id_encuesta" , countTotal : {$sum : "$count"}, arrayData : {$push : {count : "$count" ,desc_pregunta : "$desc_pregunta", desc_encuesta : "$desc_encuesta"}}}},
//         			{$unwind  : "$arrayData"},
//         			{$project : {detalle : {$concat:[{$substr: [ "$arrayData.count", 0, 10 ]},"/",{$substr: [ "$countTotal", 0, 10 ]}]}, "countTotal" : -1,"arrayData.desc_encuesta" : -1,"arrayData.desc_pregunta" : -1,"_id" :-1 , "porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$countTotal", 0 ] }, 0, {"$divide":["$arrayData.count", "$countTotal"]} ] } ] }}},
//         			{$sort    : {"porcentaje" : -1}},
//         			{$group   : {_id : {pregunta : "$arrayData.desc_pregunta" ,encuesta : "$arrayData.desc_encuesta"}, desc_pregunta : {$first : "$arrayData.desc_pregunta"}, detalle : {$first : "$detalle"} , desc_encuesta : {$first : "$arrayData.desc_encuesta"}, porcentaje : {$first : "$porcentaje"}}}
//                     '.$top.'
//                     ,{$sort    : {"porcentaje" : 1}},
//                 ])';
        $sql = 'db.senc_'.$nombre_coleccion.'_encuesta.aggregate([
                	 {$unwind  : "$preguntas"},
                     {$match   : {id_encuesta : '.$idEncuesta.'}},
                	 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1,"id_encuesta":-1,"desc_encuesta" : -1}},
                	 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",id_encuesta:"$id_encuesta"}, count: {$sum: "$preguntas.count"},sumTotal: {$sum: "$preguntas.cant_participantes"},
                				  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_encuesta:{$first:"$desc_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"},id_encuesta : {$first : "$id_encuesta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
                	 {$project : {detalle : {$concat:[{$substr: [ "$count", 0, 10 ]},"/",{$substr: [ "$sumTotal", 0, 10 ]}]} , "desc_encuesta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$count", 0 ] }, 0, {"$divide":["$count", "$sumTotal"]} ] } ] }}},
                     {$sort    : {"porcentaje" : 1}}
                	     '.$top.'
                ])';_log('$sql-'.$sql);
        $result = $db->execute('return '.$sql.'.toArray()');
        if(isset($result['retval'])){
            return $result['retval'];
        } else{
            return array();
        }
    }
    
    function getGraficoEncuestaByTipoEncuestado($preguntas,$idEncuesta,$tipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                  {$unwind  : "$preguntas"},
                  {$match   : {"preguntas.id_pregunta" : {$in : ['.$preguntas.']}}},
                  {$match   : {id_encuesta : '.$idEncuesta.'}},
                  {$match   : {"preguntas.tipo_encuestado" : "'.$tipoEncuestado.'"}},
                  {$match   : {"preguntas.id_respuesta" : {$in : ['.ALTERNATIVA_MUY_SATISFECHO.','.ALTERNATIVA_SATISFECHO.','.ALTERNATIVA_NORMAL.','.ALTERNATIVA_INSATISFECHO.','.ALTERNATIVA_MUY_INSATISFECHO.','.ALTERNATIVA_BLANCO.']}}},
                  {$project : {"id_encuesta" : -1, "preguntas.id_respuesta":-1, "preguntas.count":-1, "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1}},
                  {$group   : {_id:"$preguntas.respuesta", count: { $sum: "$preguntas.count"}, id_respuesta : {$first : "$preguntas.id_respuesta"} , desc_encuesta : {$first : "$desc_encuesta"}, sum : {$push : "$preguntas.count"}, preguntas : {$addToSet : "$preguntas.desc_pregunta"}, id_encuesta : {$first : "$id_encuesta"} }},
                  {$sort    : {"id_respuesta" : -1}},
                  {$group   : {_id:"$id_encuesta", count: { $push: "$count"}, desc_respuestas : {$push : "$_id"} ,sum : {$sum : "$count"}, desc_preguntas : {$first : "$preguntas"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDatosGraficoTutoriaByEncuesta($idEncuesta){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  :  "$preguntas"},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1, 
                	              "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta"} , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"}, 
                	              desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : "$id_pregunta", datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }	
                				 
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDatosGraficoTutoriaByTipoEnc($idEncuesta,$descTipoEncuestado){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  :  "$preguntas"},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1,
                	              "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta"} , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"},
                	              desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : "$id_pregunta", datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }
      
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDatosGraficoTutoriaBySedes($idEncuesta,$descTipoEncuestado,$idSedes){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  :  "$preguntas"},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    '.(($descTipoEncuestado != null) ? '{$match : {"preguntas.tipo_encuestado" : "'.$descTipoEncuestado.'"}},' : null).'
                    {$match   : { id_sede : {$in : ['.$idSedes.']} }},
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1,
                	              "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta"} , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"},
                	              desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : "$id_pregunta", datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }
           
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDatosGraficoTutoriaBySedesNiveles($idEncuesta,$descTipoEncuestado,$idSedes,$idNiveles){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  :  "$preguntas"},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_sede     : {$in : ['.$idSedes.']} }},
                    {$match   : { id_nivel    : {$in : ['.$idNiveles.']} }},
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1,
                	              "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta"} , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"},
                	              desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : "$id_pregunta", datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }
      
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDatosGraficoTutoriaBySedesNivelesGrados($idEncuesta,$descTipoEncuestado,$idSedes,$idNiveles,$idGrados){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  :  "$preguntas"},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_sede     : {$in : ['.$idSedes.']} }},
                    {$match   : { id_nivel    : {$in : ['.$idNiveles.']} }},
                    {$match   : { id_grado    : {$in : ['.$idGrados.']} }},
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1,
                	              "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta"} , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"},
                	              desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : "$id_pregunta", datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }
        
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDatosGraficoTutoriaBySedesNivelesGradosAulas($idEncuesta,$descTipoEncuestado,$idSedes,$idNiveles,$idGrados,$idAulas){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_respuesta_encuesta.aggregate([
                    {$unwind  :  "$preguntas"},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_encuesta : '.$idEncuesta.'}},
                    {$match   : { id_sede     : {$in : ['.$idSedes.']} }},
                    {$match   : { id_nivel    : {$in : ['.$idNiveles.']} }},
                    {$match   : { id_grado    : {$in : ['.$idGrados.']} }},
                    {$match   : { id_aula     : {$in : ['.$idAulas.']} }},
                	{$project : { "preguntas.id_respuesta":-1, "preguntas.id_pregunta" : -1, "preguntas.count":-1,
                	              "preguntas.respuesta":-1, "desc_encuesta" : -1, "preguntas.desc_pregunta" : -1, "preguntas.respuesta" : -1}},
                	{$group   : { _id : {pregunta : "$preguntas.id_pregunta" , respuesta : "$preguntas.id_respuesta"} , id_pregunta : {$first : "$preguntas.id_pregunta"} , count : { $sum: "$preguntas.count"} , id_respuesta : {$first : "$preguntas.id_respuesta"},
                	              desc_pregunta : {$first : "$preguntas.desc_pregunta"}, desc_respuesta : {$first : "$preguntas.respuesta"} }},
                	{$sort    : {"id_respuesta" : 1} },
                	{$group   : { _id : "$id_pregunta", datos : {$push : {respuesta : "$count" , desc_respuesta : "$desc_respuesta", id_respuesta : "$id_respuesta" }}, total : {$sum : "$count"} , desc_pregunta : {$first : "$desc_pregunta"}  } }
        
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getTituloGraficoTutoriaByAula($idAula){
        $sql = "SELECT CONCAT(g.abvr,' ',n.abvr,' ',a.desc_aula,' (',s.desc_sede,')',' : ',(CONCAT(INITCAP(SPLIT_PART(nom_persona, ' ', 1)),' ',ape_pate_pers,' ',SUBSTRING(ape_mate_pers,1, 1),'.' ))) titulo
                  FROM nivel n,
                       sede  s,
                       grado g,
                       aula  a
                       LEFT JOIN persona p ON(a.id_tutor = p.nid_persona )
                 WHERE a.nid_aula = ?
                   AND a.nid_grado = g.nid_grado
                   AND a.nid_sede  = s.nid_sede
                   AND n.nid_nivel = a.nid_nivel";
        $result = $this->db->query($sql, Array($idAula));
        return $result->row()->titulo;
    }
}




