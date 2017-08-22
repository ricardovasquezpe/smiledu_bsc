<?php

class M_grafico extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getDataIndicadorMediciones($idIndicador){
        try{
            $m   = new MongoClient(MONGO_CONEXION);
            $db  = $m->selectDB(SMILEDU_MONGO);
            $yearActual = date("Y");
            $sql = 'db.indicador.aggregate([
                  {$match:{__id_indicador:'.$idIndicador.'}},
                  {$match:{year:'.$yearActual.'}},
                  {$project:{"valor_meta":-1,"valor_actual_porcentaje":-1,"nro_medicion":-1}},
                  {$sort:{nro_medicion: 1}},
                ])';
            $result = $db->execute('return '.$sql.'.toArray()');
            
            return $result;
        }catch(Exception $e){
            return null;
        }
    }
    
    function getDataHistorica(){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.indicador.aggregate([
                  {$sort:{__id_indicador: -1}},
                   {$project:{"__id_indicador":-1, "valor_meta":-1,"valor_actual_porcentaje":-1,"year":-1,"nro_medicion":-1,"fecha_medicion":-1,"comparativas":-1,"sedes":-1,"niveles":-1,"grados":-1,"aulas":-1}}
                ])';         
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function insertDataHistorica($array){
        $this->db->insert_batch("bsc.historico_indicador", $array);
    }
    
    function getDataHistoricaAulas(){
        $m   = new MongoClient(MONGO_CONEXION);
        $db  = $m->selectDB(SMILEDU_MONGO);
        $sql = 'db.indicador.aggregate([
                  {$sort:{__id_indicador: -1}},
                  {$project:{"__id_indicador":-1,"year":-1,"nro_medicion":-1,"fecha_medicion":-1,"aulas":-1}}
                ])';
        $result = $db->execute('return '.$sql.'.toArray()');
        return $result;
    }
    
    function updateIndicadorHistorico($indicador, $year, $nroMedicion, $arrayUpdate){
        $this->db->where("_id_indicador", $indicador);
        $this->db->where("year", $year);
        $this->db->where("nro_medicion", $nroMedicion);
        $this->db->update("bsc.historico_indicador", $arrayUpdate);
    }
    
    //FUNCIONES POSTGRES
    function getDataGraficoByIndicadorPostgres($idIndicador){
        $sql = "SELECT _id_indicador,
                       year,
                       valor_meta,
                       valor_actual_porcentaje,
                       nro_medicion,
                       fecha_medicion,
                       comparativas,
                       sedes
                  FROM bsc.historico_indicador hi
                 WHERE _id_indicador = ?
                   AND nro_medicion in (SELECT MAX(nro_medicion)
                                          FROM bsc.historico_indicador
                                         WHERE _id_indicador = hi._id_indicador
                                           AND year          = hi.year
                                      GROUP BY _id_indicador, year
                                      ORDER BY year)
              ORDER BY year";
        $result = $this->db->query($sql,array($idIndicador));
        return $result->result();
    }
    
    function getDataGraficoBySedePostgre($idIndicador, $idSede){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_sede' AS id_sede,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((sedes)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idSede));
        return $result->result();
    }
    
    function getDataGraficoByNivelPostgre($idIndicador, $idSede, $idNivel){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_nivel' AS id_nivel,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((niveles)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer  = ?
                  AND (tab.data->>'id_nivel')::integer = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idSede, $idNivel));
        return $result->result();
    }
    
    function getDataGraficoByGradoPostgre($idIndicador, $idSede, $idNivel, $idGrado){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_grado' AS id_grado,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((grados)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer  = ?
                  AND (tab.data->>'id_nivel')::integer = ?
                  AND (tab.data->>'id_grado')::integer = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idSede, $idNivel, $idGrado));
        return $result->result();
    }
    
    function getDataGraficoByAulaPostgre($idIndicador, $idAula){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_aula' AS id_aula,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((aulas)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_aula')::integer = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idAula));
        return $result->result();
    }
    
    function getDataGraficoByDisciplinaPostgre($idIndicador, $idDisciplina){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_disciplina' AS id_disciplina,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((disciplinas)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_disciplina')::integer = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idDisciplina));
        return $result->result();
    }
    
    function getDataGraficoByDisciplinaNivelPostgre($idIndicador, $idDisciplina, $idNivel){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_nivel' AS id_nivel,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((niveles)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_disciplina')::integer = ?
                  AND (tab.data->>'id_nivel')::integer      = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idDisciplina, $idNivel));
        return $result->result();
    }
    
    function getDataGraficoBySedeNivelAreaPostgre($idIndicador, $idSede, $idNivel, $idArea){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_area' AS id_area,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((areas)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer  = ?
                  AND (tab.data->>'id_nivel')::integer = ?
                  AND (tab.data->>'id_area')::integer  = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idSede, $idNivel, $idArea));
        return $result->result();
    }
    
    function getDataGraficoBySedeAreaPostgre($idIndicador, $idSede, $idArea){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_area' AS id_area,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((areas)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer  = ?
                  AND (tab.data->>'id_area')::integer  = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idSede, $idArea));
        return $result->result();
    }
    
    function getDataGraficoBySedeGradoPostgre($idIndicador, $idSede, $idGrado){
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_grado' AS id_grado,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((grados)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer  = ?
                  AND (tab.data->>'id_grado')::integer = ?
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $idSede, $idGrado));
        return $result->result();
    }
    
    function getDataGraficoBySedeMultiPostgres($idIndicador, $idSedes, $year){
        $sed = array();
        foreach($idSedes as $var){
            $idSede = $this->encrypt->decode($var);
            array_push($sed, $idSede);
        }
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'descripcion' AS descripcion,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((sedes)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_sede')::integer IN ?
                  AND CASE WHEN ? IS NOT NULL THEN year = ?
                       ELSE 1 = 1 END
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $sed, $year, $year));
        return $result->result();
    }
    
    function getDataGraficoByDisciplinaMultiPostgres($idIndicador, $idDisciplinas, $year){
        $dis = array();
        foreach($idDisciplinas as $var){
            $idDis = $this->encrypt->decode($var);
            array_push($dis, $idDis);
        }
        $sql = "SELECT tab.data->>'valor_meta' AS valor_meta,
                       tab.year,
                       tab.data->>'id_sede' AS id_sede,
                       tab.data->>'valor_actual_porcentaje' AS valor_actual_porcentaje,
                       tab.nro_medicion,
                       tab.fecha_medicion   
                  FROM (SELECT jsonb_array_elements((sedes)::jsonb) AS data,
                               year,
                               nro_medicion,
                               fecha_medicion
                	  FROM bsc.historico_indicador hi 
                	 WHERE _id_indicador = ?
                	   AND nro_medicion in (SELECT MAX(nro_medicion)
                		                      FROM bsc.historico_indicador
                                    		 WHERE _id_indicador = hi._id_indicador
                                    		   AND year          = hi.year
                                    	      GROUP BY _id_indicador, year
                                    	      ORDER BY year)
                  ) AS tab
                WHERE (tab.data->>'id_disciplina')::integer IN ?
                  AND CASE WHEN ? IS NOT NULL THEN year = ?
                       ELSE 1 = 1 END
             ORDER BY tab.year";
        $result = $this->db->query($sql,array($idIndicador, $dis, $year, $year));
        return $result->result();
    }
}