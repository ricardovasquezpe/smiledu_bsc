<?php
class M_utils_senc extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllTipoEncuestado(){
        $sql = "SELECT id_tipo_encuestado,
                        UPPER(desc_tipo_enc) desc_tipo_enc
                   FROM senc.tipo_encuestado";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAulasByGradoYearMulti($sedeIdsDecry,$nivelIdsDecry,$gradosIdsDecry,$year){
        $sql = "SELECT nid_aula,
                       CONCAT(a.desc_aula,' (',a.year,')') desc_aula
                  FROM aula a
                 WHERE a.nid_sede  IN ?
                   AND a.nid_nivel IN ?
                   AND a.nid_grado IN ?
                   AND a.year = ?
                   ORDER BY a.desc_aula";
        $result = $this->db->query($sql,array($sedeIdsDecry, $nivelIdsDecry,$gradosIdsDecry,$year));
        return $result->result();
    }
     
    function getNivelesByMultiSedesYear($sedes,$year){
        $sql = "SELECT a.nid_nivel,
                        UPPER(n.desc_nivel) desc_nivel
                   FROM aula a,
                        nivel n
                  WHERE nid_sede    IN ?
                    AND a.nid_nivel = n.nid_nivel
                    AND a.year      = ?
                  GROUP BY a.nid_nivel,
                        n.nid_nivel,
                        desc_nivel
                  ORDER BY a.nid_nivel";
        $result = $this->db->query($sql,array($sedes,$year));
        return $result->result();
    }
     
    function getAulasByGradoMultiYear($sede,$nivel,$grado,$year){
        $sql = "SELECT nid_aula,
                       a.desc_aula
                  FROM aula a
                 WHERE a.year       = ?
                   AND a.nid_sede  IN ?
                   AND a.nid_nivel IN ?
                   AND a.nid_grado IN ?
                   ORDER BY a.desc_aula";
        $result = $this->db->query($sql,array($year, $sede, $nivel, $grado));
        return $result->result();
    }
    
    function getAllPropuestasMejora($idEnc){
        $sql = "SELECT p.id_propuesta,
                        upper(p.desc_propuesta) desc_propuesta
                   FROM senc.propuesta_mejora p
                  WHERE p._id_encuesta = ?
               ORDER BY p.desc_propuesta";
        $result = $this->db->query($sql,array($idEnc));
        return $result->result();
    }
    
    function getAllTipoPreguntaCaritas(){
        $sql = "SELECT tp.id_tipo_pregunta,
                        tp.desc_tipo_preg
                   FROM senc.tipo_pregunta tp
                  WHERE tp.id_tipo_pregunta IN ?";
        $result = $this->db->query($sql,array(json_decode(ARRAY_TIP_PREG_COMBO)));
        return $result->result();
    }
    
    function getGradosByMultiNivelSede($sede,$nivel, $year) {//dfloresgonz 02.10.16 $year
        $sql = "SELECT a.nid_grado,
                       CONCAT(g.abvr,' ',n.abvr) AS desc_grado
                  FROM grado g,
                       nivel n,
                       aula  a
                 WHERE g.id_nivel  IN ?
                   AND a.nid_sede  IN ?
                   AND n.nid_nivel = g.id_nivel
                   AND a.nid_grado = g.nid_grado
                   AND a.nid_nivel = g.id_nivel
                   AND a.year      = ?
                   --AND a.flg_acti  = ".FLG_ACTIVO."
                GROUP BY a.nid_grado,CONCAT(g.abvr,' ',n.abvr)
                ORDER BY a.nid_grado";
        $result = $this->db->query($sql, array($nivel, $sede, $year));
        return $result->result();
    }
    
    function getNivelesByMultiSedes($sedes){
        $sql = "SELECT a.nid_nivel,
                        UPPER(n.desc_nivel) desc_nivel
                   FROM aula a,
                        nivel n
                  WHERE flg_acti    = '".FLG_ACTIVO."'
                    AND nid_sede    IN ?
                    AND a.nid_nivel = n.nid_nivel
                  GROUP BY a.nid_nivel,
                        n.nid_nivel,
                        desc_nivel
                  ORDER BY a.nid_nivel";
        $result = $this->db->query($sql,array($sedes));
        return $result->result();
    }
    
    function getAllAreasGenerales(){
        $sql = "SELECT id_area,
                        desc_area
                   FROM area
                  WHERE flg_general = '".FLG_ACTIVO."'
                    AND id_area NOT IN ('".ID_AREA_ACADEMICA."')";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getAllTipoEncuesta(){
        $sql = "SELECT te.id_tipo_encuesta,
                        te.desc_tipo_encuesta
                   FROM senc.tipo_encuesta te
                 ORDER BY te.id_tipo_encuesta";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getEncuestaIdActivaByTipo($idTipoEnc){
        $sql = "SELECT id_encuesta
                  FROM senc.encuesta
                 WHERE flg_estado = '".ENCUESTA_APERTURADA."'
                   AND _id_tipo_encuesta = ?";
        $result = $this->db->query($sql,array($idTipoEnc));
        if($result->num_rows() > 0) {
            return ($result->row()->id_encuesta);
        } else{
            return null;
        }
    }
    
    function getDataCambio(){
        $sql = "SELECT * 
                  FROM senc.copy_excel
                 WHERE flg_encuesta <> '-1'
                 ORDER BY flg_encuesta ";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function updateDescAndIdsRespuestaEncuesta($idEncuesta,$idPreguntaBase1,$descPregunta,$idPreguntaBase2,$idPreguntaCambio,$flg_encuesta){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = null;
        for($i = 0; $i <= 5; $i++){
            $sql = 'db.senc_respuesta_encuesta.update(
                    {id_encuesta : '.$idEncuesta.',
                     preguntas:
                               {
                                   $elemMatch: {
                                       id_pregunta  : '.$idPreguntaBase1.',
                                       id_respuesta : '.$i.'
                                   }
                               }
                    },
                    { $set: {"preguntas.$.desc_pregunta" : "'.$descPregunta.'"} },
					{ multi: true }
                )';
            $result = $db->execute('return '.utf8_encode($sql));
        }
        if($flg_encuesta == '0'){
            for($i = 0; $i <= 5; $i++){
                $sql = 'db.senc_respuesta_encuesta.update(
                            {id_encuesta : 6,
                             preguntas:
                                       {
                                           $elemMatch: {
                                               id_pregunta  : '.$idPreguntaBase2.',
                                               id_respuesta : '.$i.'
                                           }
                                       }
                            },
                            { $set: {"preguntas.$.id_pregunta" : '.$idPreguntaCambio.' , "preguntas.$.desc_pregunta" : "'.$descPregunta.'"} },
					        { multi: true }
                        )';
                $result = $db->execute('return '.utf8_encode($sql));
            }
        }
    }
    
    function updateDescAndIdsSatisfaccionEncuesta($idEncuesta,$idPreguntaBase1,$descPregunta,$idPreguntaBase2,$idPreguntaCambio,$flg_encuesta){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        
        $sql = null;
        $aulas = $this->getAulasByPregunta($idEncuesta,$idPreguntaBase1);
        foreach ($aulas as $aula){
            $sql = 'db.senc_satisfaccion_encuesta.update(
                        {id_encuesta : '.$idEncuesta.',
                         preguntas:
                                   {
                                       $elemMatch: {
                                           id_pregunta  : '.$idPreguntaBase1.',
                                           id_aula      : '.$aula.'
                                       }
                                   }
                        },
                        { $set: {"preguntas.$.desc_pregunta" : "'.$descPregunta.'"} },
    					{ multi: true }
                    )';
            $result = $db->execute('return '.utf8_encode($sql));
        }
        
        if($flg_encuesta == 0){
            $aulas = $this->getAulasByPregunta(6,$idPreguntaBase2);
            foreach($aulas as $aula){
                $sql = 'db.senc_satisfaccion_encuesta.update(
                                {id_encuesta : 6,
                                 preguntas:
                                           {
                                               $elemMatch: {
                                                   id_pregunta  : '.$idPreguntaBase2.',
                                                   id_aula      : '.$aula.'
                                               }
                                           }
                                },
                                { $set: {"preguntas.$.id_pregunta" : '.$idPreguntaCambio.' , "preguntas.$.desc_pregunta" : "'.$descPregunta.'"} },
					            { multi: true }
                            )';
                $result = $db->execute('return '.utf8_encode($sql));
            }
        }
    }
    
    function updateDescAndIdsInsatisfaccionEncuesta($idEncuesta,$idPreguntaBase1,$descPregunta,$idPreguntaBase2,$idPreguntaCambio,$flg_encuesta){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $aulas = $this->getAulasByPregunta($idEncuesta,$idPreguntaBase1,'insatisfaccion');
        foreach ($aulas as $aula){
            $sql = 'db.senc_insatisfaccion_encuesta.update(
                    {id_encuesta : '.$idEncuesta.',
                     preguntas:
                               {
                                   $elemMatch: {
                                       id_pregunta  : '.$idPreguntaBase1.',
                                       id_aula      : '.$aula.'
                                   }
                               }
                    },
                    { $set: {"preguntas.$.desc_pregunta" : "'.$descPregunta.'"} },
					{ multi: true }
                )';
            $result = $db->execute('return '.utf8_encode($sql));
        }
        if($flg_encuesta == 0){
            $aulas = $this->getAulasByPregunta(6,$idPreguntaBase2,'insatisfaccion');
            foreach($aulas as $aula){
                $sql = 'db.senc_insatisfaccion_encuesta.update(
                            {id_encuesta : 6,
                             preguntas:
                                       {
                                           $elemMatch: {
                                               id_pregunta  : '.$idPreguntaBase2.',
                                               id_aula      : '.$aula.'
                                           }
                                       }
                            },
                            { $set: {"preguntas.$.id_pregunta" : '.$idPreguntaCambio.' , "preguntas.$.desc_pregunta" : "'.$descPregunta.'"} },
					        { multi: true }
                        )';
                $result = $db->execute('return '.utf8_encode($sql));
            }
        } 
    }
    
    function getAulasByPregunta($idEncuesta,$idPregunta,$satisfaccion = 'satisfaccion'){
        $m   = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.senc_'.$satisfaccion.'_encuesta.aggregate([
                    {$unwind  : "$preguntas"},
                	{$match   : {id_encuesta : '.$idEncuesta.', "preguntas.id_pregunta" : '.$idPregunta.'}},
                	{$project : {"preguntas.id_aula" : -1, "preguntas.id_pregunta" : -1}},
                	{$group   : {_id : "$preguntas.id_pregunta", aulas : {$addToSet : "$preguntas.id_aula"} }}
                ])';
        $result = $db->execute('return '.utf8_encode($sql).'.toArray()');
        if(isset($result['retval'][0])){
            return $result['retval'][0]['aulas'];
        } else{
            return array();   
        }
    }
}