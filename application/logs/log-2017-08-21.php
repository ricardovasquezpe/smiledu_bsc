<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2017-08-21 00:55:18 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 00:55:29 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:29 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:29 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:34 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '3'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:37 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '4'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:37 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '5'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:39 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '7'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:44 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '7'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:55 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:55 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:55:55 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:01 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:01 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:01 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:10 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '8'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:10 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '11'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:10 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '12'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:10 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '13'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:10 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '14'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:10 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '15'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 00:56:40 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:05:32 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:06:10 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:06:16 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:06:52 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:08:50 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:10:16 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:10:48 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 01:10:48 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 01:10:48 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 01:13:18 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
ERROR - 2017-08-21 01:13:32 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '3'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 01:13:50 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '4'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 01:13:50 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '5'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-21 01:18:03 -->  - SELECT desc_linea_estrategica,
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
               WHERE flg_acti = 1
                ORDER BY _id_linea_estrategica
