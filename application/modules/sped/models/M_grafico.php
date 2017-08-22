<?php
class M_grafico extends  CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getAllIndicadores() {
        $sql = "SELECT i.nid_indicador,
                        INITCAP(i.desc_indicador) desc_indicador
                   FROM sped.indicador i";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getDataGraficoByIndicador($idIndicador, $fecInicio, $fecFin, $tipoGrafico) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $indicadores = "";
        foreach ($idIndicador as $id) {
            $indicadores .=  $id.',';
        }
        $indicadores = substr($indicadores,0,(strlen($indicadores)-1));
        $filtroFecha = $this->getFiltroFecha($fecInicio, $fecFin);
        $sql = 'db.rubrica_indi.aggregate([
                		{$match   : {id_indicador : {$in : ['.$indicadores.']}}},
                		{$unwind  : "$array_evas"},
                        '.$filtroFecha.'
                		{$project : {"id_indicador" : -1, "desc_indi" : -1, "array_evas.fec_eval" : -1}},
                		{$group   : {_id : {fec : "$array_evas.fec_eval", id_indicador : "$id_indicador"} , fec_eval : {$first : "$array_evas.fec_eval"}, id_indicador : {$first : "$id_indicador"}, desc_indi : {$first : "$desc_indi"}, count : {$sum : 1}}},
                		{$group   : {_id : "$fec_eval", lista_count : {$push : {count : "$count", id_indicador : "$id_indicador"}}, lista_nombres : {$push : {desc_indi : "$desc_indi", id_indicador : "$id_indicador"}}}},
                		{$sort    : {"fec_eval" : -1}},
                ])';
        $resutl = $db->execute('return '.$sql.'.toArray()');
        return $resutl;
    }
    
    function getDataGraficoDocente($idDocente, $idIndicador, $fecInicio, $fecFin, $tipoGrafico) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $docentes    = "";
        $indicadores = "";
        if(count($idDocente) != 0) {
            foreach ($idDocente as $idDoc) {
                $docentes .=  $idDoc.',';
            }
            $docentes = substr($docentes, 0,(strlen($docentes)-1));
        }
        if(count($idIndicador) != 0) {
            foreach ($idIndicador as $idIndi) {
                $indicadores .=  $idIndi.',';
            }
            $indicadores = substr($indicadores, 0,(strlen($indicadores)-1));
        }
        $filtroFecha = $this->getFiltroFecha($fecInicio, $fecFin);
        $sql = 'db.rubrica_docentes.aggregate([
                	{$match: {"id_docente" : {$in : ['.$docentes.']}}},
                	{$unwind  : "$array_evas"},
                	'.$filtroFecha.'
                	{$project : {"array_evas.id_evaluacion":-1,"nombre_docente" : -1,"array_evas.nota_vige" : -1,"array_evas.fec_eval":-1,"id_docente" : -1}},
                	{$group   : {_id : {fec_eval : "$array_evas.fec_eval", id_evaluacion : "$array_evas.id_evaluacion"}, desc : {$first : "$nombre_docente"}, id_docente : {$first : "$id_docente"}, nota_vige : {$first : "$array_evas.nota_vige"},fec_eval:{$first:"$array_evas.fec_eval"}, count : {$sum : 1}}},
            	    {$sort    : {"fec_eval" : 1}},	
                	{$group   : {_id : "$fec_eval", lista_eval : {$push : {nota : "$nota_vige", id_docente : "$id_docente", count : "$count"}},fec_eval : {$first : "$fec_eval"} ,desc_nombre : {$push : {desc : "$desc", id_docente : "$id_docente"}}}},
            	    {$sort    : {"fec_eval" : 1}}
        	    ])';    
        $resutl = $db->execute('return '.$sql.'.toArray()');
        return $resutl;
    }
    
    function getFiltroFecha($fecInicio,$fecFin) {
        $filtroFecha       = null;
        $filtroFechaInicio = null;
        $filtroFechaFin    = null;
        if($fecInicio != null) {
            $filtroFechaInicio = '$gte : "'.$fecInicio.'" ';
        }
        if($fecFin != null && $fecInicio != null) {
            $filtroFechaFin = ',';
        }
        if($fecFin != null) { 
            $filtroFechaFin    .= ' $lte : "'.$fecFin.'"';
        }
        if($filtroFechaFin != null || $filtroFechaInicio != null) {
            $filtroFecha       = '{$match: {"array_evas.fec_eval" : {'.$filtroFechaInicio.' '.$filtroFechaFin.'}}},';
        }
        return $filtroFecha;
    }
    
    function getPromedioIndicadores($idIndicador) {
        $indicadores = "";
        foreach ($idIndicador as $id) {
            $indicadores .=  $id.',';
        }
        $indicadores = substr($indicadores,0,(strlen($indicadores)-1));
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_indi.aggregate([
        				{$match  : {id_indicador : {$in : ['.$indicadores.']}}},
        				{$unwind : "$array_evas"},
        				{$sort   : {"array_evas.fec_eval" : 1}},
        				{$project : {"year": -1,"desc_indi" : -1, "id_indicador" : -1, "array_evas.id_evaluacion" : -1, "array_evas.nota_vige" : -1,"array_evas.fec_eval" : -1,"array_evas.valor" : -1}},
        				{$group   : {_id : "$id_indicador", desc_indi : {$first : "$desc_indi"},
        							nota_vige : {$avg : "$array_evas.nota_vige"}}}
                ])';;
        $result = $db->execute('return '.$sql);
        return $result['retval'];
    }
    
    function getDataDocenteIndicador($idDocente, $idIndicador, $fecInicio, $fecFin) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $docentes    = "";
        $indicadores = "";
        if(count($idDocente) != 0){
            foreach ($idDocente as $idDoc) {
                $docentes .=  $idDoc.',';
            }
            $docentes = substr($docentes,0,(strlen($docentes)-1));
        }
        if(count($idIndicador) != 0) {
            foreach ($idIndicador as $idIndi) {
                $indicadores .=  $idIndi.',';
            }
            $indicadores = substr($indicadores,0,(strlen($indicadores)-1));
        }
        $filtroFecha = $this->getFiltroFecha($fecInicio, $fecFin);
        $sql = 'db.rubrica_indi_doc.aggregate([
                		{$match   : {id_indicador : {$in : ['.$indicadores.']}}},
                		{$unwind  : "$array_evas"},
                		{$match   : {"array_evas.id_docente" : {$in : ['.$docentes.']}}},
                        '.$filtroFecha.'
                		{$project : {"id_indicador" :-1,"desc_indi" : -1 ,"array_evas.id_evaluacion" : -1,"array_evas.id_docente" : -1,"array_evas.nombre_docente" : -1,"array_evas.nota_vige" : -1,"array_evas.fec_eval" : -1}},
                		{$group   : {_id : {id_docente : "$array_evas.id_docente" , id_indicador : "$id_indicador", fec_eval : "$array_evas.fec_eval"},desc_indi : {$first : "$desc_indi"} , id_docente : {$first : "$array_evas.id_docente"}
                		, id_indicador : {$first : "$id_indicador"}, desc_doce : {$first : "$array_evas.nombre_docente"}, desc_indi : {$first : "$desc_indi"},nota_vige : {$first : "$array_evas.nota_vige"},fec_eval : {$first : "$array_evas.fec_eval"}}},
                		{$sort    : {"fec_eval" : 1}},
                		{$group   : {_id : "$fec_eval", fec_eval : {$first : "$fec_eval"}, data : {$push : {desc_indi : "$desc_indi", desc_doce : "$desc_doce", id_indi : "$id_indicador", nota_vige : "$nota_vige", id_docente : "$id_docente" }}}},
                		{$sort    : {"fec_eval" : 1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    //PROMEDIO DE INDICADORES POR AÑO   
    function getDataAreaIndicadores($idIndicador) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $indicadores = "";
        if(count($idIndicador) != 0){
            foreach ($idIndicador as $idIndi){
                $indicadores .=  $idIndi.',';
            }
            $indicadores = substr($indicadores,0,(strlen($indicadores)-1));
        }
        $sql = 'db.rubrica_indi.aggregate([
        				{$match  : {id_indicador : {$in : ['.$indicadores.']}}},
        				{$unwind : "$array_evas"},
        				{$sort   : {"year" : -1}},
        				{$project : {"year": -1,"desc_indi" : -1, "id_indicador" : -1, "array_evas.id_evaluacion" : -1, "array_evas.nota_vige" : -1,"array_evas.fec_eval" : -1,"array_evas.valor" : -1}},
        				{$group   : {_id : {year : "$year" , _id_indicador : "$id_indicador"}, desc_indi : {$first : "$desc_indi"},
        							notas : {$avg : "$array_evas.nota_vige"}, year : {$first : "$year"} , id_indicador : {$addToSet : "$id_indicador"}}},
        				{$group   : {_id : "$id_indicador" , notas : {$push : "$notas"}, desc_indi : {$first : "$desc_indi"}, year : {$push : "$year"}, id_indicador : {$addToSet : "$id_indicador"}}},
        				{$group   : {_id : "$id_indicador" , nota_vige : {$push : {notas : "$notas" , desc_indi : "$desc_indi"}}, year : {$first : "$year"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getPromedioAnualDocentes($idDocente,$fecInicio,$fecFin) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $docentes    = "";
        if(count($idDocente) != 0){
            foreach ($idDocente as $idDoc){
                $docentes .=  $idDoc.',';
            }
            $docentes = substr($docentes,0,(strlen($docentes)-1));
        }
        $filtroFecha = $this->getFiltroFecha($fecInicio, $fecFin);
        $filtroFecha = ($filtroFecha == null) ? '{$match : {"year" : {$ne : '.date('Y').'}}},' : $filtroFecha;
        $sql = 'db.rubrica_docentes.aggregate([
        			{$match   : {id_docente : {$in : ['.$docentes.']}}},
        			{$unwind  : "$array_evas"},
        			{$sort    : {"year" : 1}},
    			    '.$filtroFecha.'
        			{$project : {"array_evas.id_evaluacion" : -1, "nombre_docente" : -1, "array_evas.nota_vige" : -1, "id_docente" : -1, "array_evas.fec_eval":-1, "year":-1}},
        			{$group   : {_id : {year : "$year" , id_docente : "$id_docente"}, nombre_docente : {$last : "$nombre_docente"}, nota : {$avg : "$array_evas.nota_vige"}, id_docente : {$first : "$id_docente"}, year : {$first : "$year"}}},
                    {$sort    : {"id_docente" : -1}},
        			{$group   : {_id : "$year" , nombre_docente : {$addToSet : "$nombre_docente"}, prom : {$push : {nota : "$nota", year : "$year" , nombre_docente : "$nombre_docente"}}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDetalleEvalIndi($nomIndi,$fecha) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_indi.aggregate([
                		{$unwind  : "$array_evas"},
                		{$match   : {"desc_indi" : "'.$nomIndi.'"}},
                		{$match   : {"array_evas.fec_eval"  : "'.$fecha.'"}},
                		{$project : {"id_indicador" : -1, "desc_indi" : -1, "array_evas.fec_eval" : -1, "array_evas.nota_vige" : -1, "array_evas.id_evaluacion" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", nota_vige : {$first : "$array_evas.nota_vige"}, fec_eval : {$first : "$array_evas.fec_eval"}, desc_indi : {$first : "$desc_indi"}}},
                		{$sort    : {"array_evas.nota_vige" : -1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDataAux() {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_indi_doc.aggregate([
                		{$match   : {id_indicador : {$in : [102,109]}}},
                		{$unwind  : "$array_evas"},
                		{$match   : {"array_evas.id_docente" : {$in : [81]}}},
                
                		{$project : {"id_indicador" :-1,"desc_indi" : -1 ,"array_evas.id_evaluacion" : -1,"array_evas.id_docente" : -1,"array_evas.nombre_docente" : -1,"array_evas.nota_vige" : -1,"array_evas.fec_eval" : -1}},
                		{$group   : {_id : {id_docente : "$array_evas.id_docente" , id_indicador : "$id_indicador", fec_eval : "$array_evas.fec_eval"},desc_indi : {$first : "$desc_indi"} , id_docente : {$first : "$array_evas.id_docente"}
                		, id_indicador : {$first : "$id_indicador"}, desc_doce : {$first : "$array_evas.nombre_docente"}, desc_indi : {$first : "$desc_indi"},nota_vige : {$first : "$array_evas.nota_vige"},fec_eval : {$first : "$array_evas.fec_eval"}}},
                		{$sort    : {"fec_eval" : 1}},
                		{$group   : {_id : "$fec_eval", fec_eval : {$first : "$fec_eval"}, data : {$push : {desc_indi : "$desc_indi", desc_doce : "$desc_doce", id_indi : "$id_indicador", nota_vige : "$nota_vige", id_docente : "$id_docente" }}}},
                		{$sort    : {"fec_eval" : 1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getIdEvavaluacion($nombre,$fecha,$value) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_docentes.aggregate([
                        {$unwind  : "$array_evas"},
                	   	{$match   : {"array_evas.fec_eval" : "'.$fecha.'", nombre_docente : "'.$nombre.'", "array_evas.nota_vige" : '.$value.'}},
                		{$project : {"array_evas.id_evaluacion" : -1, "array_evas.nota_vige" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", nota_vige : {$first : "$array_evas.nota_vige"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result['retval'][0];
    }
    
    function getDataEvaluacionDocente($idEval) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_evaluadores.aggregate([		
                		{$unwind  : "$array_evas"},
                		{$match   : {"array_evas.id_evaluacion" : '.$idEval.'}},
                		{$project : {"array_evas.id_evaluacion" : -1 , "nombre_evaluador" : -1,"id_evaluador" : -1, "array_evas.fec_eval" : -1,"array_evas.nombre_docente" : -1,"array_evas.desc_area" : -1, "array_evas.abvr_sede" : -1, "array_evas.abvr_nivel" : -1, "array_evas.desc_aula" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", fec_eval : {$first : "$array_evas.fec_eval"},nombre_evaluador : {$first : "$nombre_evaluador"}, sede : {$first : "$array_evas.abvr_sede"}, nivel : {$first : "$array_evas.abvr_nivel"}, area : {$first : "$array_evas.desc_area"}, aula : {$first : "$array_evas.desc_aula"}, nombre_docente : {$first : "$array_evas.nombre_docente"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result['retval'][0];
    }
    
    function getIdEvavaluacionIndiDoc($nombreDocente, $nombreIndicador, $fecha) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_indi_doc.aggregate([
                        {$unwind  : "$array_evas"},
                	   	{$match   : {"array_evas.fec_eval" : "'.$fecha.'"}},
                	   	{$match   : {"array_evas.nombre_docente" : "'.$nombreDocente.'"}},
            	   	    {$match   : {desc_indi : "'.utf8_decode($nombreIndicador).'"}},
                		{$project : {"array_evas.id_evaluacion" : -1, "array_evas.nota_vige" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", nota_vige : {$first : "$array_evas.nota_vige"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        if(!isset($result['retval'][0])) {
            throw new Exception('Hubo un error');
        }
        return $result['retval'][0];
    }
    
    function getDataEvaluacionIndiDoce($idEval) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_evaluadores.aggregate([
                		{$unwind  : "$array_evas"},
                		{$match   : {"array_evas.id_evaluacion" : '.$idEval.'}},
                		{$project : {"array_evas.id_evaluacion" : -1 , "nombre_evaluador" : -1,"id_evaluador" : -1, "array_evas.fec_eval" : -1,"array_evas.nombre_docente" : -1,"array_evas.desc_area" : -1, "array_evas.abvr_sede" : -1, "array_evas.abvr_nivel" : -1, "array_evas.desc_aula" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", fec_eval : {$first : "$array_evas.fec_eval"},nombre_evaluador : {$first : "$nombre_evaluador"}, sede : {$first : "$array_evas.abvr_sede"}, nivel : {$first : "$array_evas.abvr_nivel"}, area : {$first : "$array_evas.desc_area"}, aula : {$first : "$array_evas.desc_aula"}, nombre_docente : {$first : "$array_evas.nombre_docente"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        if(!isset($result['retval'][0])) {
            throw new Exception('Hubo un error');
        }
        return $result['retval'][0];
    }
}