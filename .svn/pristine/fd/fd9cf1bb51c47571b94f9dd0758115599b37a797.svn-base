<?php
class M_lineaEstrat extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getLineasEstrategicas(){
        $sql = "SELECT desc_linea_estrategica,
                       _id_linea_estrategica,
                       info_linea_estrategica,
                        flg_amarillo,
                        flg_verde,
                  (SELECT COUNT(1)
                     FROM bsc.objetivo
                     WHERE __id_linea_estrategica = le._id_linea_estrategica
                  GROUP BY __id_linea_estrategica) AS nro_objetivos,

            
                 (SELECT COUNT(1)
                     FROM bsc.objetivo o,
                          bsc.categoria c,
                          bsc.categoria_x_indicador ci,
                          bsc.indicador ind
                     WHERE o.__id_linea_estrategica = le._id_linea_estrategica
                       AND c.__id_objetivo = o._id_objetivo
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
                  GROUP BY __id_linea_estrategica) AS nro_categorias,
            
            
                  (SELECT COUNT(1)
                     FROM bsc.objetivo obj,
                          bsc.categoria_x_indicador ci,
                          bsc.indicador ind,
                          bsc.categoria c
                    WHERE ci.__id_categoria           = c.id_categoria
                      AND ci.__id_indicador           = ind._id_indicador
                      AND c.__id_objetivo             =  obj._id_objetivo
                      AND obj.__id_linea_estrategica  = _id_linea_estrategica
                      AND ind.cod_indi IS NOT NULL) AS nro_indicadores,
                  
            (SELECT COUNT(1)
                   FROM
                     ( SELECT CASE WHEN (ind1.tipo_gauge = 'NORMAL' OR ind1.tipo_gauge = 'RATIO' OR ind1.tipo_gauge = 'REDUCCION')
                      AND (id.valor_actual_porcentaje >= COALESCE(MAX(c.valor_comparativa), -1))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 
                      
                      WHEN ind1.tipo_gauge = 'PUESTO'
                      AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje <= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = 'CERO'
                          AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
                      AND (id.valor_actual_porcentaje  = 0) THEN 1 WHEN ind1.tipo_gauge = 'MAXIMO'
                      AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 ELSE 0 END AS dorado
                      FROM 
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             cat,
                    	   bsc.objetivo ob,
                           bsc.indicador ind1
                      LEFT JOIN bsc.indicador_x_comparativa ic ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
                      LEFT JOIN bsc.comparativa c ON c._id_comparativa = ic.__id_comparativa
                     WHERE id.flg_acti              = '1'
                     AND id.tipo_regi             = 'INDI'
                     AND ind1._id_indicador         = id.__id_indicador
                	  AND ci.__id_indicador         = ind1._id_indicador
                	  AND ci.__id_categoria         = cat.id_categoria
                	  AND cat. __id_objetivo        = ob._id_objetivo
                	  AND ob.__id_linea_estrategica = _id_linea_estrategica
                     AND ind1.cod_indi IS NOT NULL
                      GROUP BY ind1._id_indicador,
                               id.valor_actual_porcentaje,
                               id.valor_meta

                               ) AS dorados
                   WHERE dorados.dorado = 1) AS dorados
            
            
                FROM bsc.linea_estrategica le
               WHERE flg_acti = ".FLG_ACTIVO."
                ORDER BY _id_linea_estrategica";
        $result = $this->db->query($sql);
        _logLastQuery();
        $data = $result->result();
        return $data;
    }
    
    function getPorcentajeByLineaEstrat($idLinea){
        $sql = "SELECT todos.cuentaTod AS tod,
                       TODOS._id_linea_estrategica,
                       TODOS.flg_amarillo,
                       todos.flg_verde,
                       todos.desc_linea_estrategica,
                       CASE
                           WHEN pasaron.cuentaPas IS NULL THEN 0
                           ELSE pasaron.cuentaPas
                       END AS pas,
            (SELECT COUNT(1)
                   FROM
                     ( SELECT CASE WHEN (ind1.tipo_gauge = 'NORMAL' OR ind1.tipo_gauge = 'RATIO' OR ind1.tipo_gauge = 'REDUCCION')
                      AND (id.valor_actual_porcentaje >= COALESCE(MAX(c.valor_comparativa), -1))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 
                      
                      WHEN ind1.tipo_gauge = 'PUESTO'
                      AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje <= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = 'CERO'
                          AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
                      AND (id.valor_actual_porcentaje  = 0) THEN 1 WHEN ind1.tipo_gauge = 'MAXIMO'
                      AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 ELSE 0 END AS dorado
            
            
                      FROM 
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             cat,
                    	   bsc.objetivo ob,
            bsc.indicador ind1
                      LEFT JOIN bsc.indicador_x_comparativa ic ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
                      LEFT JOIN bsc.comparativa c ON c._id_comparativa = ic.__id_comparativa
                     WHERE id.flg_acti              = '1'
                     AND id.tipo_regi             = 'INDI'
                     AND ind1._id_indicador         = id.__id_indicador
                	  AND ci.__id_indicador         = ind1._id_indicador
                	  AND ci.__id_categoria         = cat.id_categoria
                	  AND cat. __id_objetivo          = ob._id_objetivo
                	  AND ob.__id_linea_estrategica = TODOS._id_linea_estrategica
                     AND ind1.cod_indi IS NOT NULL
                      GROUP BY ind1._id_indicador,
                               id.valor_actual_porcentaje,
                               id.valor_meta) AS dorados
                   WHERE dorados.dorado = 1) AS dorados
                FROM
                  (SELECT COUNT(1) AS cuentaTod,
                                      le._id_linea_estrategica,
                                      le.flg_amarillo,
                                      le.flg_verde,
                                      le.desc_linea_estrategica
                   FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob
            
                   WHERE id.flg_acti              = '1'
                     AND id.tipo_regi             = 'INDI'
            
                     AND ind._id_indicador         = id.__id_indicador
                	  AND ci.__id_indicador         = ind._id_indicador
                	  AND ci.__id_categoria         = c.id_categoria
                	  AND c. __id_objetivo          = ob._id_objetivo
                	  AND ob.__id_linea_estrategica = le._id_linea_estrategica
            
                     AND ind.cod_indi IS NOT NULL
                     AND ((? <> 0
                           AND le._id_linea_estrategica = ?)
                          OR (? = 0
                              AND 1= 1))
                   GROUP BY le._id_linea_estrategica) todos
                LEFT JOIN
                  ( SELECT COUNT(1) AS cuentaPas,
                                       le._id_linea_estrategica,
                                       le.flg_amarillo,
                                       le.flg_verde
                   FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob
            
            
                   WHERE id.flg_acti              = '1'
                     AND id.tipo_regi             = 'INDI'
            
                     AND ind._id_indicador         = id.__id_indicador
                	  AND ci.__id_indicador         = ind._id_indicador
                	  AND ci.__id_categoria         = c.id_categoria
                	  AND c. __id_objetivo          = ob._id_objetivo
                	  AND ob.__id_linea_estrategica = le._id_linea_estrategica
            
                     AND ind.cod_indi IS NOT NULL
                     AND CASE WHEN ind.tipo_gauge = '".GAUGE_PUESTO."' THEN id.valor_actual_porcentaje <= id.valor_meta
                     AND id.valor_actual_porcentaje >= 1 WHEN ind.tipo_gauge = '".GAUGE_CERO."' THEN id.valor_actual_porcentaje <= id.valor_meta ELSE id.valor_actual_porcentaje >= id.valor_meta END
                   GROUP BY le._id_linea_estrategica ) pasaron ON todos._id_linea_estrategica = pasaron._id_linea_estrategica
                ORDER BY todos._id_linea_estrategica";
        $result = $this->db->query($sql,array($idLinea,$idLinea,$idLinea));
        
        if($idLinea == 0){
            $data = $result->result();
        } else{
            $data = $result->row_array();
        }
        return $data;
    }
    
    function getGaugesGenerales(){
        $sql = "SELECT SUM(cantidad)*100::NUMERIC/COUNT(1) AS porcentajeGeneral,

                  (SELECT valor_numerico1
                   FROM   bsc.config
                   WHERE  id_config = 1) AS valor_amarillo,
                
                  (SELECT valor_numerico2
                   FROM   bsc.config
                   WHERE  id_config = 1) AS valor_meta,
                
                  (SELECT COUNT(1)
                   FROM
                     ( SELECT CASE WHEN (ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
                      AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) ELSE 101 END))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
                      AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje <= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
                      AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
                      AND (id.valor_actual_porcentaje = 0) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
                      AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 ELSE 0 END AS dorado
                      FROM bsc.indicador_detalle id,
                           bsc.indicador ind1
                      LEFT JOIN bsc.indicador_x_comparativa ic ON ind1._id_indicador = ic.__id_indicador
                      LEFT JOIN bsc.comparativa c ON c._id_comparativa = ic.__id_comparativa
                      WHERE ind1._id_indicador = id.__id_indicador
                        AND id.tipo_regi = 'INDI'
                        AND ind1.cod_indi IS NOT NULL
                      GROUP BY ind1._id_indicador,
                               id.valor_actual_porcentaje,
                               id.valor_meta) AS dorados
                   WHERE dorados.dorado = 1) AS dorados
                FROM
                  ( SELECT lineas.pas/ lineas.tod::float AS cantidad
                   FROM
                     ( SELECT todos.cuentaTod AS tod,
                              CASE WHEN pasaron.cuentaPas IS NULL THEN 0 ELSE pasaron.cuentaPas END AS pas
                      FROM
                        (SELECT COUNT(1) AS cuentaTod,
                                            le._id_linea_estrategica
                         FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob
            
                         WHERE id.flg_acti              = '1'
                           AND tipo_regi                = 'INDI'
                           AND  ind._id_indicador         = id.__id_indicador
                    	  AND ci.__id_indicador         = ind._id_indicador
                    	  AND ci.__id_categoria         = c.id_categoria
                    	  AND c. __id_objetivo          = ob._id_objetivo
                    	  AND ob.__id_linea_estrategica = le._id_linea_estrategica
                           AND ind.cod_indi IS NOT NULL
                         GROUP BY le._id_linea_estrategica) todos
                      LEFT JOIN
                        (SELECT COUNT(1) AS cuentaPas,
                                            le._id_linea_estrategica
                         FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob
                              
                         WHERE id.flg_acti              = '1'
                           AND tipo_regi                = 'INDI'
                           AND ind._id_indicador         = id.__id_indicador
                    	  AND ci.__id_indicador         = ind._id_indicador
                    	  AND ci.__id_categoria         = c.id_categoria
                    	  AND c. __id_objetivo          = ob._id_objetivo
                    	  AND ob.__id_linea_estrategica = le._id_linea_estrategica
                           AND ind.cod_indi IS NOT NULL
                           AND CASE WHEN ind.tipo_gauge = '".GAUGE_PUESTO."' THEN id.valor_actual_porcentaje <= id.valor_meta
                           AND id.valor_actual_porcentaje >= 1 WHEN ind.tipo_gauge = '".GAUGE_CERO."' THEN id.valor_actual_porcentaje <= id.valor_meta ELSE id.valor_actual_porcentaje >= id.valor_meta END
                         GROUP BY le._id_linea_estrategica ) pasaron ON todos._id_linea_estrategica = pasaron._id_linea_estrategica) AS lineas ) AS institu";
        
        $general = $this->db->query($sql);
         
        return $general->row_array();
    }
    
    function getPorcentajeByObjetivos($idLineaEstrat,$idObjetivo){
        $sql = "SELECT (obj.pas::float/obj.tod)*100 AS porcentaje,
                       obj.desc_objetivo,
                       obj._id_objetivo,
                       obj.flg_amarillo,
                       obj.flg_verde,
                       obj.cod_obje,
                       obj.pas,
                       obj.tod,
                  (SELECT COUNT(1)
                   FROM
                     ( SELECT CASE WHEN (ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
                      AND (id.valor_actual_porcentaje >= COALESCE(max(c.valor_comparativa), -1))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
                      AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje <= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
                          AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
                      AND (id.valor_actual_porcentaje  = 0) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
                      AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 ELSE 0 END AS dorado
                      FROM bsc.indicador_detalle id,
                           bsc.indicador ind1
                      LEFT JOIN bsc.indicador_x_comparativa ic ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
                      LEFT JOIN bsc.comparativa c ON c._id_comparativa = ic.__id_comparativa
                      WHERE ind1._id_indicador = id.__id_indicador
                        AND id.tipo_regi       = 'INDI'
                        AND ind1.__id_objetivo = obj._id_objetivo
                        AND ind1.cod_indi IS NOT NULL
                      GROUP BY ind1._id_indicador,
                               id.valor_actual_porcentaje,
                               id.valor_meta) AS dorados
                   WHERE dorados.dorado = 1) AS dorados
                FROM
                  ( SELECT todos.cuentaTod AS tod,
                           todos._id_objetivo,
                           todos.desc_objetivo,
                           todos.flg_amarillo,
                           todos.flg_verde,
                           todos.cod_obje,
                           CASE
                               WHEN pasaron.cuentaPas IS NULL THEN 0
                               ELSE pasaron.cuentaPas
                           END AS pas
                   FROM
                     ( SELECT COUNT(1) AS cuentaTod,
                    			  ob.desc_objetivo,
                    			  ob._id_objetivo,
                    			  ob.flg_amarillo,
                    			  ob.flg_verde,
                    			  ob.cod_obje
                    	FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob
                    	WHERE ind._id_indicador         = id.__id_indicador
                    	  AND ci.__id_indicador         = ind._id_indicador
                    	  AND ci.__id_categoria         = c.id_categoria
                    	  AND c. __id_objetivo          = ob._id_objetivo
                    	  AND ob.__id_linea_estrategica = le._id_linea_estrategica
                            AND le._id_linea_estrategica    = ?
                    	AND id.tipo_regi                = 'INDI'
                    	AND ind.cod_indi IS NOT NULL
                        AND ((? <> 0
                              AND ob._id_objetivo = ?)
                             OR (? = 0
                                 AND 1 = 1))
                      GROUP BY ob._id_objetivo ) todos
                   LEFT JOIN
                     ( SELECT COUNT(1) AS cuentaPas,
                                          ob.desc_objetivo,
                                          ob._id_objetivo,
                                          ob.flg_amarillo,
                                          ob.flg_verde,
                                          ob.cod_obje
                    	FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob
                    	WHERE ind._id_indicador         = id.__id_indicador
                    	  AND ci.__id_indicador         = ind._id_indicador
                    	  AND ci.__id_categoria         = c.id_categoria
                    	  AND c. __id_objetivo          = ob._id_objetivo
                    	  AND ob.__id_linea_estrategica = le._id_linea_estrategica
                            AND le._id_linea_estrategica    = ?
                    	AND id.tipo_regi                = 'INDI'
                    	AND ind.cod_indi IS NOT NULL
                        AND CASE WHEN ind.tipo_gauge    = '".GAUGE_PUESTO."' THEN id.valor_actual_porcentaje <= id.valor_meta
                        AND id.valor_actual_porcentaje >= 1 WHEN ind.tipo_gauge = '".GAUGE_CERO."' THEN id.valor_actual_porcentaje <= id.valor_meta ELSE id.valor_actual_porcentaje >= id.valor_meta END
                      GROUP BY ob._id_objetivo ) pasaron ON todos._id_objetivo = pasaron._id_objetivo ) AS obj
                ORDER BY obj._id_objetivo";
        
        $result = $this->db->query($sql,array($idLineaEstrat,$idObjetivo,$idObjetivo,$idObjetivo,$idLineaEstrat));
        if($idObjetivo == 0){
            $data = $result->result();
        } else{
            $data = $result->row_array();
        }
        return $data;
    }
    
    function updateFlgAmarillo($arrayUpdate, $tabla, $id) {
        $this->db->where($id , $arrayUpdate[$id]);
        unset($arrayUpdate[$id]);
        $this->db->update("bsc.".$tabla, $arrayUpdate);
        if($this->db->affected_rows() != 1) {
            throw new Exception('(MLE-001)');
        }
        return array('error' => EXIT_SUCCESS, 'msj' => MSJ_UPT);
    }
    
    function countIndicadoresInObj($idObj){
        $sql = "SELECT COUNT(1) AS cant
                FROM   bsc.categoria_x_indicador ci
                WHERE  ci.__id_objetivo = ?";
        
        $result = $this->db->query($sql, Array($idObj));
        return ($result->row()->cant);
    }
    
    function countCategoriasInObj($idObj){
        $sql = "SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = ?
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL";
        
        
        /*(SELECT COUNT(1)
        FROM bsc.objetivo o,
        bsc.categoria c,
        bsc.categoria_x_indicador ci,
        bsc.indicador ind
        WHERE o.__id_linea_estrategica = le._id_linea_estrategica
        AND c.__id_objetivo = o._id_objetivo
        AND ci.__id_categoria = c.id_categoria
        AND ci.__id_indicador = ind._id_indicador
        AND ind.cod_indi IS NOT NULL
        GROUP BY __id_linea_estrategica)*/
    
        $result = $this->db->query($sql, Array($idObj));
        _logLastQuery();
        return ($result->row()->cant);
    }
    
    function getCategoriasByObjetivo($idObjetivo, $idCategoria){
        $sql = "SELECT (cat.pas::float/cat.tod)*100 AS porcentaje,
                       cat.desc_categoria,
                       cat.id_categoria,
                       cat.flg_amarillo,
                       cat.flg_verde,
                       cat.tod as cantIndica,
                       cat.pas,
                       cat.tod,
                  (SELECT COUNT(1)
                     FROM bsc.categoria_x_indicador c,
                          bsc.indicador ind
                    WHERE c.__id_categoria = cat.id_categoria
                      AND c.__id_indicador = ind._id_indicador
                      AND ind.cod_indi IS NOT NULL) AS nro_indicadores,
                  (SELECT COUNT(1)
                   FROM
                     ( SELECT CASE WHEN (ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
                      AND (id.valor_actual_porcentaje >= COALESCE(max(c.valor_comparativa), -1))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
                      AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje <= id.valor_meta) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
                          AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
                      AND (id.valor_actual_porcentaje  = 0) THEN 1 WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
                      AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                      AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1 ELSE 0 END AS dorado
                      FROM bsc.indicador_detalle id,
			               bsc.categoria_x_indicador ci,
                           bsc.indicador ind1
                      LEFT JOIN bsc.indicador_x_comparativa ic ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
                      LEFT JOIN bsc.comparativa c ON c._id_comparativa = ic.__id_comparativa
                      WHERE ind1._id_indicador = id.__id_indicador
                        AND id.tipo_regi       = 'INDI'
                        AND ci.__id_indicador = ind1._id_indicador
                        AND ci.__id_categoria = cat.id_categoria
                        AND ind1.cod_indi IS NOT NULL
                      GROUP BY ind1._id_indicador,
                               id.valor_actual_porcentaje,
                               id.valor_meta) AS dorados
                   WHERE dorados.dorado = 1) AS dorados
                FROM
                  ( SELECT todos.cuentaTod AS tod,
                           todos.id_categoria,
                           todos.desc_categoria,
                           todos.flg_amarillo,
                           todos.flg_verde,
                           CASE
                               WHEN pasaron.cuentaPas IS NULL THEN 0
                               ELSE pasaron.cuentaPas
                           END AS pas
                   FROM
                     ( SELECT COUNT(1) AS cuentaTod,
                                   c.desc_categoria,
                                   c.id_categoria,
                                   c.flg_verde,
                                   c.flg_amarillo
                                   
                      FROM bsc.indicador ind,
                           bsc.indicador_detalle id,
                           bsc.linea_estrategica le,
                           bsc.objetivo ob,
                           bsc.categoria c,
                           bsc.categoria_x_indicador ci
                           
                      WHERE id.tipo_regi                = 'INDI'
                        AND ob._id_objetivo             = ?
                        AND ind._id_indicador           = id.__id_indicador
                        AND ob.__id_linea_estrategica   = le._id_linea_estrategica
                        AND c.__id_objetivo             = ob._id_objetivo
                        AND ci.__id_categoria           = c.id_categoria
                        AND ci.__id_indicador           = ind._id_indicador
                        AND ind.cod_indi IS NOT NULL
                        AND ((? <> 0
                           AND c.id_categoria = ?)
                          OR (? = 0
                              AND 1= 1))
                      GROUP BY c.id_categoria) todos
                   LEFT JOIN
                     ( 
			SELECT COUNT(1) AS cuentaPas,
                                   c.desc_categoria,
                                   c.id_categoria,
                                   c.flg_verde,
                                   c.flg_amarillo
                      FROM bsc.indicador ind,
                           bsc.indicador_detalle id,
                           bsc.linea_estrategica le,
                           bsc.objetivo ob,
                           bsc.categoria c,
                           bsc.categoria_x_indicador ci
                      WHERE id.tipo_regi                = 'INDI'
                        AND ob._id_objetivo             = ?
                        AND ind._id_indicador           = id.__id_indicador
                        AND ob.__id_linea_estrategica   = le._id_linea_estrategica
                        AND c.__id_objetivo             = ob._id_objetivo
                        AND ci.__id_categoria           = c.id_categoria
                        AND ci.__id_indicador           = ind._id_indicador
                        AND CASE WHEN ind.tipo_gauge    = '".GAUGE_PUESTO."' THEN id.valor_actual_porcentaje <= id.valor_meta
                        AND id.valor_actual_porcentaje >= 1 WHEN ind.tipo_gauge = '".GAUGE_CERO."' THEN id.valor_actual_porcentaje <= id.valor_meta ELSE id.valor_actual_porcentaje >= id.valor_meta END
                      GROUP BY c.id_categoria
                      ) pasaron ON todos.id_categoria = pasaron.id_categoria ) AS cat
                ORDER BY cat.id_categoria";
        
        $result = $this->db->query($sql,array($idObjetivo, $idCategoria, $idCategoria, $idCategoria, $idObjetivo));
        
        $data = null;
    	if($idCategoria == 0){
            $data = $result->result();
        } else{
            $data = $result->row_array();
        }
        return $data;
    }
    
    function getComboLineasEstrategicas() {
        $sql = "SELECT INITCAP(desc_linea_estrategica) AS desc_linea_estrategica,
                       _id_linea_estrategica
                  FROM bsc.linea_estrategica
              ORDER BY desc_linea_estrategica";
        $result = $this->db->query($sql);
        return $result->result();
    }
}