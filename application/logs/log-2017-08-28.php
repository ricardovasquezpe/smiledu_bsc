<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2017-08-28 12:51:21 -->  - SELECT desc_linea_estrategica,
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
ERROR - 2017-08-28 12:51:25 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-28 12:51:25 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-28 12:51:25 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-28 12:51:40 --> ID_INDICADOR: 102
ERROR - 2017-08-28 12:51:41 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:51:48 --> ID_INDICADOR: 102
ERROR - 2017-08-28 12:51:48 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:51:57 --> ID_INDICADOR: 102
ERROR - 2017-08-28 12:51:57 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:53:16 --> ID_INDICADOR: 102
ERROR - 2017-08-28 12:53:17 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:55:11 --> ID_INDICADOR: 102
ERROR - 2017-08-28 12:55:12 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:55:57 --> ID_INDICADOR: 102
ERROR - 2017-08-28 12:55:58 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:59:02 --> ID_INDICADOR: 2
ERROR - 2017-08-28 12:59:06 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:59:25 --> ID_INDICADOR: 2
ERROR - 2017-08-28 12:59:28 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 12:59:57 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:00:00 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:00:27 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:00:30 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:00:32 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:00:36 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:00:46 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:00:50 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:02:41 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:02:45 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:02:59 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:03:02 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:06:23 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:06:26 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:07:07 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:07:10 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:08:40 -->  - SELECT desc_linea_estrategica,
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
ERROR - 2017-08-28 13:11:40 --> Severity: Warning --> unlink(c:/wamp64/tmp\smiledud3039f0418b57c77641649ed0417dc829c9fef47): Permission denied C:\wamp64\www\smiledu\system\libraries\Session\drivers\Session_files_driver.php 311
ERROR - 2017-08-28 13:11:40 --> Severity: Warning --> session_destroy(): Session object destruction failed C:\wamp64\www\smiledu\system\libraries\Session\Session.php 628
ERROR - 2017-08-28 13:12:00 -->  - SELECT desc_linea_estrategica,
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
ERROR - 2017-08-28 13:12:05 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-28 13:12:05 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-28 13:12:05 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-08-28 13:12:12 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:12:15 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:13:09 --> ID_INDICADOR: 2
ERROR - 2017-08-28 13:13:12 --> Severity: Error --> Class 'MongoClient' not found C:\wamp64\www\smiledu\application\modules\bsc\models\mf_grafico\m_grafico.php 10
ERROR - 2017-08-28 13:13:14 --> 404 Page Not Found: ../modules/bsc/controllers/cf_indicador//index
