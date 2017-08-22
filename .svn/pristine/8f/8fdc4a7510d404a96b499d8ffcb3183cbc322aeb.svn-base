<?php
class M_desempeno_evaluadores extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getEvaluacionesPorRol($fecInicio, $fecFin) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $filtro = null;
        if($fecInicio != null){
            $filtro .= '{$match : {"array_evas.fec_eval" : {$gte : "'.$fecInicio.'"} } },';
        }
        if($fecFin != null){
            $filtro .= '{$match : {"array_evas.fec_eval" : {$lte : "'.$fecFin.'"} } },';
        }
        $sql = 'db.rubrica_evaluadores.aggregate([
                		{$unwind  : "$array_evas"},
                		{$project : {"array_evas.id_evaluacion" : -1 , "id_evaluador" : -1 , "rol" : -1 , "array_evas.estado" : -1, "array_evas.fec_eval" : -1}},
                        '.$filtro.'
                        {$match   : {"rol" : {$exists : true}}},
                		{$group   : {_id : {rol : "$rol" , estado : "$array_evas.estado"} , count : {$sum : 1}, roles : {$first : "$rol"}, estado : {$first : "$array_evas.estado"}}},
                        {$sort    : {"rol" : -1}},
                		{$group   : {_id : "$estado" , lista : {$push : { contador : "$count" , roles : "$roles"}} , r : {$push : "$roles"}}},
                		{$sort    : {"roles" : -1}}
                ])';
        $resutl = $db->execute('return '.$sql.'.toArray()'); 
        return $resutl;
    }
    
    function getAllDiasLaborablesByRango($fechaInicio, $fechaFin) {
        $sql = "SELECT COUNT(pk_fecha) dias_laborables
                  FROM calendario c
                 WHERE c.flg_laborable = ".FLG_LABORABLE."
                   AND c.flg_dia_semana = ".FLG_DIA_SEMANA."
                   AND c.flg_feriado = ".FLG_FERIADO."
                   AND pk_fecha BETWEEN ? AND ? ";
        $result = $this->db->query($sql,array($fechaInicio, $fechaFin));
        return $result->row()->dias_laborables;
    }
    
    function getAllEvaluacionesEjecutadas($fecInicio, $fecFin, $listaEvaluadores, $listaRoles) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $result = array('retval' => array());
        $filtroEvaluadores = "";
        $filtroRoles = "";
        $evaluadores = "";
        $roles       = "";
        if(is_array($listaEvaluadores) && count($listaEvaluadores) > 0) {
            foreach($listaEvaluadores as $id){
                $idDecry = $this->encrypt->decode($id);
                $evaluadores .= $idDecry.',';
            }
            $evaluadores = substr($evaluadores,0,(strlen($evaluadores)-1));
            $filtroEvaluadores = '{$match   : {id_evaluador : {$in : ['.$evaluadores.']}}},';
        }
        if(is_array($listaRoles) && count($listaRoles) > 0) {
            foreach($listaRoles as $id){
                $idDecry = $this->encrypt->decode($id);
                $roles .= $idDecry.',';
            }
            $roles = substr($roles,0,(strlen($roles)-1));
            $filtroRoles = '{$match   : {rol : {$in : ['.$roles.']}}},'; 
        }
        try{
            if($fecInicio == null) {
                throw new Exception('No se especifico la fecha de inicio');
            }
            if($fecFin == null) {
                throw new Exception('No se especifico la fecha fin');
            }
            $sql = 'db.rubrica_evaluadores.aggregate([
                        '.$filtroEvaluadores.'
                        '.$filtroRoles.'
                		{$unwind  : "$array_evas"},
                		{$project : {"array_evas.id_evaluacion" : -1 , "id_evaluador" : -1 , "rol" : -1 , "array_evas.estado" : -1, "nombre_evaluador" : -1, "array_evas.fec_eval" : -1}},
                        {$match   : {"array_evas.fec_eval" : { $gte : "'.$fecInicio.'" , $lte : "'.$fecFin.'"}}},
                		{$match   : {"array_evas.estado" : "EJECUTADO"}},
                        {$match   : {"rol" : {$exists : true}}},
                		{$group   : {_id : "$id_evaluador", rol : {$first : "$rol"}, count : {$sum : 1}, nombre_evaluador : {$first : "$nombre_evaluador"}}},
                		{$group   : {_id : "$id_evaluador", lista : {$push : {rol : "$rol", count : "$count", nombre_evaluador : "$nombre_evaluador" }}}}
                ])';
            $result = $db->execute('return '.$sql.'.toArray()');
        } catch(Exception $e) {
            $result = array('retval' => array());
        }
        return $result;
    }
    
    function getEvaluacionesMaxAndMin($rol) {
        $sql = "SELECT valor_num_1,
                       valor_num_2
                  FROM sped.sped_config sc
                 WHERE (4 = ? AND id_config = ".ID_SPED_CONFIG_1.") OR (2 = ? AND id_config = ".ID_SPED_CONFIG_2.")";
        $result = $this->db->query($sql,array($rol,$rol));
        return $result->row_array();
    }
    
    function getEvaluacionesLineaTiempo($fecInicio, $fecFin, $listaEvaluadores, $listaRoles) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $filtroEvaluadores = "";
        $filtroRoles = "";
        $evaluadores = "";
        $roles       = "";
        if(is_array($listaEvaluadores) && count($listaEvaluadores) > 0) {
            foreach($listaEvaluadores as $id){
                $idDecry = $this->encrypt->decode($id);
                $evaluadores .= $idDecry.',';
            }
            $evaluadores = substr($evaluadores,0,(strlen($evaluadores)-1));
            $filtroEvaluadores = '{$match   : {id_evaluador : {$in : ['.$evaluadores.']}}},';
        }
        if(is_array($listaRoles) && count($listaRoles) > 0) {
            foreach($listaRoles as $id) {
                $idDecry = $this->encrypt->decode($id);
                $roles .= $idDecry.',';
            }
            $roles = substr($roles,0,(strlen($roles)-1));
            $filtroRoles = '{$match   : {rol : {$in : ['.$roles.']}}},';
        }
        $sql = 'db.rubrica_evaluadores.aggregate([
                        '.$filtroEvaluadores.'
                        '.$filtroRoles.'
                		{$unwind  : "$array_evas"},
                		{$project : {"array_evas.id_evaluacion" : -1 , "id_evaluador" : -1 , "rol" : -1 , "array_evas.estado" : -1, "nombre_evaluador" : -1, "array_evas.fec_eval" : -1}},
                        {$match   : {"array_evas.fec_eval" : { $gte : "'.$fecInicio.'" , $lte : "'.$fecFin.'"}}},
                		{$group   : {_id : {fec : "$array_evas.fec_eval", estado : "$array_evas.estado"} , fec_eval : {$first : "$array_evas.fec_eval"}, estado : {$first : "$array_evas.estado"}, count : {$sum : 1}}},
                        {$sort    : {"fec_eval" : -1,"estado" : -1}},
                        {$group   : {_id : "$fec_eval", eval : {$push : {fec_eval : "$fec_eval", estado : "$estado", count : "$count"}}, fec_eval : {$first : "$fec_eval"}, estado : {$first : "$estado"}}},
                        {$sort    : {"fec_eval" : 1}},
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function getDetalleEvaluadores($cat, $fecInicio, $fecFin) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_evaluadores.aggregate([
                		{$unwind  : "$array_evas"},
                		{$match   : {"nombre_evaluador" : "'.$cat.'"}},
                		{$match   : {"array_evas.estado" : "EJECUTADO"}},
                        {$match   : {"array_evas.fec_eval" : { $gte : "'.$fecInicio.'" , $lte : "'.$fecFin.'"}}},
                		{$project : {"array_evas.id_evaluacion" : -1 , "id_evaluador" : -1, "nombre_evaluador" : -1 , "rol" : -1 , "array_evas.estado" : -1, "array_evas.fec_eval" : -1,"array_evas.nombre_docente" : -1}},
                		{$group   : {_id : "$id_evaluador", count : {$sum : 1}, rol : {$first : "$rol"}}},
                ])';
        $result = $db->execute('return '.$sql.'.toArray()')['retval'];
        return $result;
    }
    
    function getDataPersona($idPersona, $rol) {
        $sql = "SELECT CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) as nombrecompleto,
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."',p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
                       CASE WHEN ".ID_ROL_EVALUADOR." = ? THEN 'Evaluador de Área'
                            ELSE 'Evaluador de Sede'
                       END AS rol,
                       sc.valor_num_1,
                       sc.valor_num_2
                 FROM persona p,
                      sped.sped_config    sc
                WHERE p.nid_persona = ?
                  AND ((".ID_ROL_SUBDIRECTOR." = ? AND id_config = ".ID_SPED_CONFIG_1.") OR 
                       (".ID_ROL_EVALUADOR."   = ? AND id_config = ".ID_SPED_CONFIG_2."))";
        $result = $this->db->query($sql,array($rol, $idPersona, $rol, $rol));
        return $result->row_array();
    }
    
    function getDetalleRoles($cat,$estado, $fecInicio, $fecFin) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_evaluadores.aggregate([
                		{$unwind  : "$array_evas"},
                		{$match   : {"rol" : '.$cat.'}},
                		{$match   : {"array_evas.estado" : "'.$estado.'"}},
                		{$match   : {"array_evas.fec_eval" : { $gte : "'.$fecInicio.'" , $lte : "'.$fecFin.'"}}},
                		{$project : {"array_evas.id_evaluacion" : -1 , "id_evaluador" : -1, "nombre_evaluador" : -1 , "rol" : -1 , "array_evas.estado" : -1, "array_evas.fec_eval" : -1,"array_evas.nombre_docente" : -1,"array_evas.desc_area" : -1, "array_evas.abvr_sede" : -1, "array_evas.abvr_nivel" : -1, "array_evas.desc_aula" : -1, "array_evas.fec_eval" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", nombre_evaluador : {$first : "$nombre_evaluador"}, sede : {$first : "$array_evas.abvr_sede"}, nivel : {$first : "$array_evas.abvr_nivel"}, area : {$first : "$array_evas.desc_area"}, aula : {$first : "$array_evas.desc_aula"}, nombre_docente : {$first : "$array_evas.nombre_docente"}, fec_eval : {$first : "$array_evas.fec_eval"}}},
                		{$sort    : {"fec_eval" : 1}}    
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result['retval'];
    }
    
    function getDetalleLinea($fec_eval, $estado) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_evaluadores.aggregate([
                		{$unwind  : "$array_evas"},
                		{$match   : {"array_evas.fec_eval" : "'.$fec_eval.'"}},
                		{$match   : {"array_evas.estado" : "'.$estado.'"}},
                		{$project : {"array_evas.id_evaluacion" : -1 , "id_evaluador" : -1, "nombre_evaluador" : -1 , "rol" : -1 , "array_evas.estado" : -1, "array_evas.fec_eval" : -1,"array_evas.nombre_docente" : -1,"array_evas.desc_area" : -1, "array_evas.abvr_sede" : -1, "array_evas.abvr_nivel" : -1, "array_evas.desc_aula" : -1, "array_evas.fec_eval" : -1}},
                		{$group   : {_id : {id_eval : "$array_evas.id_evaluacion", fec_eval : "$array_evas.fec_eval"}, nombre_evaluador : {$first : "$nombre_evaluador"}, sede : {$first : "$array_evas.abvr_sede"}, nivel : {$first : "$array_evas.abvr_nivel"}, area : {$first : "$array_evas.desc_area"}, aula : {$first : "$array_evas.desc_aula"}, nombre_docente : {$first : "$array_evas.nombre_docente"}, fec_eval : {$first : "$array_evas.fec_eval"}}},
                		{$sort    : {"fec_eval" : 1}}    
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result['retval'];
    }
    
    function getDetalleEvaluadorEval($idEvaluador, $fec_inicio, $fec_fin) {
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.rubrica_evaluadores.aggregate([
                		{$unwind  : "$array_evas"},
                		{$match   : {"id_evaluador" : '.$idEvaluador.'}},
                		{$match   : {"array_evas.estado" : "EJECUTADO"}},
                		{$match   : {"array_evas.fec_eval" : { $gte : "'.$fec_inicio.'" , $lte : "'.$fec_fin.'"}}},
                		{$project : {"array_evas.id_evaluacion" : -1 , "nombre_evaluador" : -1,"id_evaluador" : -1, "array_evas.fec_eval" : -1,"array_evas.nombre_docente" : -1,"array_evas.desc_area" : -1, "array_evas.abvr_sede" : -1, "array_evas.abvr_nivel" : -1, "array_evas.desc_aula" : -1}},
                		{$group   : {_id : "$array_evas.id_evaluacion", fec_eval : {$first : "$array_evas.fec_eval"},nombre_evaluador : {$first : "$nombre_evaluador"}, sede : {$first : "$array_evas.abvr_sede"}, nivel : {$first : "$array_evas.abvr_nivel"}, area : {$first : "$array_evas.desc_area"}, aula : {$first : "$array_evas.desc_aula"}, nombre_docente : {$first : "$array_evas.nombre_docente"}}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result['retval'];
    }
}