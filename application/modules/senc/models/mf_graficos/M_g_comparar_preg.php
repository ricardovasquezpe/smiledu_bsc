<?php
class M_g_comparar_preg extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getGraficoByPregunta($preguntas, $tipoEncuesta, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":{"$in" : ['.$preguntas.']}, "id_tipo_encuesta":{"$in" : ['.$tipoEncuesta.']}}},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
                     {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
                     {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoByYear($preguntas, $tipoEncuesta, $year, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":{"$in" : ['.$preguntas.']}, "id_tipo_encuesta":{"$in" : ['.$tipoEncuesta.']}, "year":{"$in" : ['.$year.']}}},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
                     {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
                     {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoBySedes($pregunta, $tipoEncuesta, $sedes, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":'.$pregunta.', "id_tipo_encuesta":'.$tipoEncuesta.', "id_sede": {"$in":['.$sedes.']}}},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
					 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
					 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoByNiveles($pregunta, $tipoEncuesta, $sedes, $niveles, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":'.$pregunta.', "id_tipo_encuesta":'.$tipoEncuesta.', "id_sede": {"$in":['.$sedes.']}, "id_nivel": {"$in":['.$niveles.']}}},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
					 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
					 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoByGrados($pregunta, $tipoEncuesta, $sedes, $niveles, $grados, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":'.$pregunta.', "id_tipo_encuesta":'.$tipoEncuesta.', "id_sede": {"$in":['.$sedes.']}, "id_nivel": {"$in":['.$niveles.']}, "id_grado": {"$in":['.$grados.']} }},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
					 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
					 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoByAulas($pregunta, $tipoEncuesta, $aulas, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":'.$pregunta.', "id_tipo_encuesta":'.$tipoEncuesta.', "preguntas.id_aula": {"$in":['.$aulas.']} }},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
					 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
					 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }  

    function getGraficoByAreas($pregunta, $tipoEncuesta, $sedes, $niveles, $areas, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
         
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":'.$pregunta.', "id_tipo_encuesta":'.$tipoEncuesta.', "id_sede": {"$in":['.$sedes.']}, "id_nivel": {"$in":['.$niveles.']}, "preguntas.id_area": {"$in":['.$areas.']} }},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
					 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
					 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoByAreasSedes($pregunta, $tipoEncuesta, $sedes, $areas, $satisfaccion){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
					 {$unwind  : "$preguntas"},
                     {$match   : {"preguntas.id_pregunta":'.$pregunta.', "id_tipo_encuesta":'.$tipoEncuesta.', "id_sede": {"$in":['.$sedes.']}, "preguntas.id_area": {"$in":['.$areas.']} }},
					 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "desc_tipo_encuesta":-1, "preguntas.id_pregunta":-1, "preguntas.desc_pregunta":-1, "preguntas.cant_participantes":-1}},
					 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
                                  year:{$first:"$year"}, id_tipo_encuesta:{$first:"$id_tipo_encuesta"}, desc_tipo_encuesta:{$first:"$desc_tipo_encuesta"}, id_pregunta:{$first:"$preguntas.id_pregunta"}, desc_pregunta:{$first:"$preguntas.desc_pregunta"}}},
					 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"desc_tipo_encuesta":-1,"id_pregunta":-1,"desc_pregunta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
					 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
}