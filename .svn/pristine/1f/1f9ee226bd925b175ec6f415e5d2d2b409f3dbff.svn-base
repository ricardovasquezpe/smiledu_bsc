<?php

class M_g_propuesta_mejora extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getGraficoPropuestaMejoraByPropuesta($propuestas,$encuesta){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
	                {$unwind  : "$preguntas"},
	                {$match   : {"preguntas.id_propuesta" : {$in : ['.$propuestas.']}}},
                    {$match   : {id_encuesta : '.$encuesta.'}},
	                {$project : {"preguntas.count":-1, "desc_encuesta":-1, "preguntas.id_propuesta":-1, "preguntas.desc_propuesta":-1, "cant_participantes":-1, "id_encuesta" : -1}},
	                {$group   : {_id:"$preguntas.id_propuesta", count: {$sum: "$preguntas.count"},part: {$sum: "$cant_participantes"},desc_encuesta : {$first : "$desc_encuesta"},
				                 propuesta:{$first:"$preguntas.desc_propuesta"}, id_encuesta : {$first : "$id_encuesta"}}}, 
                    {$project : {"_id":-1,"desc_encuesta":-1, "count":-1,"part":-1,"propuesta":-1, "id_encuesta" : -1}} ,  
                    {$group   : {_id:"$id_encuesta",sum : {$sum : "$count"}, part : {$last : "$part"}, desc_propuesta : {$push : "$propuesta"}, id_propuesta : {$push : "$_id"}, count : {$push : "$count"}, desc_encuesta : {$first : "$desc_encuesta" } }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoPropuestaMejoraBySede($propuestas,$encuesta,$sedes){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
	                {$unwind  : "$preguntas"},
	                {$match   : {"preguntas.id_propuesta" : {$in : ['.$propuestas.']}}},
                    {$match   : {id_encuesta : '.$encuesta.'}},
                    {$match   : {id_sede : {$in : ['.$sedes.']}}},
	                {$project : {"preguntas.count":-1, "desc_encuesta":-1, "preguntas.id_propuesta":-1, "preguntas.desc_propuesta":-1, "cant_participantes":-1, "id_encuesta" : -1}},
	                {$group   : {_id:"$preguntas.id_propuesta", count: {$sum: "$preguntas.count"},part: {$sum: "$cant_participantes"},desc_encuesta : {$first : "$desc_encuesta"},
				                 propuesta:{$first:"$preguntas.desc_propuesta"}, id_encuesta : {$first : "$id_encuesta"} }}, 
                    {$project : {"_id":-1,"desc_encuesta":-1, "count":-1,"part":-1,"propuesta":-1, "id_encuesta" : -1}} ,  
                    {$group   : {_id:"$id_encuesta",sum : {$sum : "$count"}, part : {$last : "$part"}, desc_propuesta : {$push : "$propuesta"}, id_propuesta : {$push : "$_id"}, count : {$push : "$count"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoPropuestaMejoraBySedeNivel($propuestas,$encuesta,$sedes,$nivel){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
	                {$unwind  : "$preguntas"},
	                {$match   : {"preguntas.id_propuesta" : {$in : ['.$propuestas.']}}},
                    {$match   : {id_encuesta : '.$encuesta.'}},
                    {$match   : {id_sede : {$in : ['.$sedes.']}}},
                    {$match   : {id_nivel : {$in : ['.$nivel.']}}},
	                {$project : {"preguntas.count":-1, "desc_encuesta":-1, "preguntas.id_propuesta":-1, "preguntas.desc_propuesta":-1, "cant_participantes":-1, "id_encuesta" : -1}},
	                {$group   : {_id:"$preguntas.id_propuesta", count: {$sum: "$preguntas.count"},part: {$sum: "$cant_participantes"},desc_encuesta : {$first : "$desc_encuesta"},
				                 propuesta:{$first:"$preguntas.desc_propuesta"}, id_encuesta : {$first : "$id_encuesta"} }}, 
                    {$project : {"_id":-1,"desc_encuesta":-1, "count":-1,"part":-1,"propuesta":-1, "id_encuesta" : -1}} ,  
                    {$group   : {_id:"$id_encuesta",sum : {$sum : "$count"}, part : {$last : "$part"}, desc_propuesta : {$push : "$propuesta"}, id_propuesta : {$push : "$_id"}, count : {$push : "$count"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoPropuestaMejoraByGrado($propuestas,$encuesta,$sedes,$nivel,$grados){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
	                {$unwind  : "$preguntas"},
	                {$match   : {"preguntas.id_propuesta" : {$in : ['.$propuestas.']}}},
                    {$match   : {id_encuesta : '.$encuesta.'}},
                    {$match   : {id_sede : {$in : ['.$sedes.']}}},
                    {$match   : {id_nivel : {$in : ['.$nivel.']}}},
                    {$match   : {id_grado : {$in : ['.$grados.']}}},
	                {$project : {"preguntas.count":-1, "desc_encuesta":-1, "preguntas.id_propuesta":-1, "preguntas.desc_propuesta":-1, "cant_participantes":-1, "id_encuesta" : -1}},
	                {$group   : {_id:"$preguntas.id_propuesta", count: {$sum: "$preguntas.count"},part: {$sum: "$cant_participantes"},desc_encuesta : {$first : "$desc_encuesta"},
				                 propuesta:{$first:"$preguntas.desc_propuesta"}, id_encuesta : {$first : "$id_encuesta"} }}, 
                    {$project : {"_id":-1,"desc_encuesta":-1, "count":-1,"part":-1,"propuesta":-1, "id_encuesta" : -1}} ,  
                    {$group   : {_id:"$id_encuesta",sum : {$sum : "$count"}, part : {$last : "$part"}, desc_propuesta : {$push : "$propuesta"}, id_propuesta : {$push : "$_id"}, count : {$push : "$count"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoPropuestaMejoraByAula($propuestas,$encuesta,$sedes,$nivel,$grados,$aulas){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
	                {$unwind  : "$preguntas"},
	                {$match   : {"preguntas.id_propuesta" : {$in : ['.$propuestas.']}}},
                    {$match   : {id_encuesta : '.$encuesta.'}},
                    {$match   : {id_sede : {$in : ['.$sedes.']}}},
                    {$match   : {id_nivel : {$in : ['.$nivel.']}}},
                    {$match   : {id_grado : {$in : ['.$grados.']}}},
                    {$match   : {"preguntas.id_aula" : {$in : ['.$aulas.']}}},
	                {$project : {"preguntas.count":-1, "desc_encuesta":-1, "preguntas.id_propuesta":-1, "preguntas.desc_propuesta":-1, "cant_participantes":-1, "id_encuesta" : -1}},
	                {$group   : {_id:"$preguntas.id_propuesta", count: {$sum: "$preguntas.count"},part: {$sum: "$cant_participantes"},desc_encuesta : {$first : "$desc_encuesta"},
				                 propuesta:{$first:"$preguntas.desc_propuesta"}, id_encuesta : {$first : "$id_encuesta"} }}, 
                    {$project : {"_id":-1,"desc_encuesta":-1, "count":-1,"part":-1,"propuesta":-1, "id_encuesta" : -1}} ,  
                    {$group   : {_id:"$id_encuesta",sum : {$sum : "$count"}, part : {$last : "$part"}, desc_propuesta : {$push : "$propuesta"}, id_propuesta : {$push : "$_id"}, count : {$push : "$count"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getGraficoPropuestaMejoraByArea($propuestas,$encuesta,$sedes,$nivel,$areas){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
	                {$unwind  : "$preguntas"},
	                {$match   : {"preguntas.id_propuesta" : {$in : ['.$propuestas.']}}},
                    {$match   : {id_encuesta : '.$encuesta.'}},
                    {$match   : {id_sede : {$in : ['.$sedes.']}}},
                    {$match   : {id_nivel : {$in : ['.$nivel.']}}},
                    {$match   : {"preguntas.id_area" : {$in : ['.$areas.']}}},
	                {$project : {"preguntas.count":-1, "desc_encuesta":-1, "preguntas.id_propuesta":-1, "preguntas.desc_propuesta":-1, "cant_participantes":-1, "id_encuesta" : -1}},
	                {$group   : {_id:"$preguntas.id_propuesta", count: {$sum: "$preguntas.count"},part: {$sum: "$cant_participantes"},desc_encuesta : {$first : "$desc_encuesta"},
				                 propuesta:{$first:"$preguntas.desc_propuesta"}, id_encuesta : {$first : "$id_encuesta"} }}, 
                    {$project : {"_id":-1,"desc_encuesta":-1, "count":-1,"part":-1,"propuesta":-1, "id_encuesta" : -1}} ,  
                    {$group   : {_id:"$id_encuesta",sum : {$sum : "$count"}, part : {$last : "$part"}, desc_propuesta : {$push : "$propuesta"}, id_propuesta : {$push : "$_id"}, count : {$push : "$count"}, desc_encuesta : {$first : "$desc_encuesta"} }}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getComentarioByPropuestaMejora($idProp, $idEnc){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_propuesta_comentario.aggregate([
                    {$unwind  : "$propuestas"},
                    {$match   : {id_encuesta : '.$idEnc.'}},
                    {$match   : {"propuestas.id_propuesta" : '.$idProp.'}},
                    {$project : {"propuestas.comentario" : -1}}
                ])';
        
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getCantParticipantesByEncuesta($idEncuesta,$sede,$nivel,$grado){
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $filtroSede  = ($sede != null)  ? '{$match   : {id_sede  : {$in : ['.$sede.']}}},'  : null;
        $filtroNivel = ($nivel != null) ? '{$match   : {id_nivel : {$in : ['.$nivel.']}}},' : null;
        $filtroGrado = ($grado != null) ? '{$match   : {id_grado : {$in : ['.$grado.']}}},' : null;
        $sql = 'db.senc_propuesta_mejora_encuesta.aggregate([
                	{$match   : {id_encuesta : '.$idEncuesta.'}},
                	    '.$filtroSede.$filtroNivel.$filtroGrado.'
                	{$project : {"cant_participantes":-1, "id_encuesta":-1}},
                	{$group   : {_id : "$id_encuesta" , part : {$sum : "$cant_participantes"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        if(isset($result['retval'][0])){
            return $result['retval'][0]['part'];
        } else{
            return 0;
        }
    }
}