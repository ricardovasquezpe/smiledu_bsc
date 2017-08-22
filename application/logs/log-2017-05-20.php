ERROR - 2017-05-20 23:48:01 --> Severity: Notice --> Use of undefined constant NO_MEDIDO - assumed 'NO_MEDIDO' C:\xampp\htdocs\smiledu\application\modules\bsc\models\mf_indicador\m_indicador.php 1377
ERROR - 2017-05-20 23:48:01 --> Severity: Warning --> pg_query(): Query failed: ERROR:  la referencia a la columna «__id_indicador» es ambigua
LINE 1: SELECT __id_indicador,
               ^ C:\xampp\htdocs\smiledu\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2017-05-20 23:48:01 --> Query error: ERROR:  la referencia a la columna «__id_indicador» es ambigua
LINE 1: SELECT __id_indicador,
               ^ - Invalid query: SELECT __id_indicador,
                     id_frecuencia,
                     id.year,
                     i.tipo_encuesta
                FROM bsc.frecuencia_medicion fm,
                     bsc.indicador           i,
                     bsc.indicador_detalle id
               WHERE fm.__id_indicador = i._id_indicador 
                 AND id.__id_indicador = i._id_indicador
                 AND id.tipo_regi      = 'INDI'
                 AND tipo_encuesta IS NOT NULL
                 AND flg_medido     = 'NO_MEDIDO'
                 AND fecha_medicion = (SELECT now()::date)
                 AND CASE WHEN NULL IS NOT NULL THEN __id_indicador = NULL
                     ELSE 1 = 1 END
ERROR - 2017-05-20 23:51:00 --> Severity: Warning --> pg_query(): Query failed: ERROR:  la referencia a la columna «__id_indicador» es ambigua
LINE 1: SELECT __id_indicador,
               ^ C:\xampp\htdocs\smiledu\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2017-05-20 23:51:00 --> Query error: ERROR:  la referencia a la columna «__id_indicador» es ambigua
LINE 1: SELECT __id_indicador,
               ^ - Invalid query: SELECT __id_indicador,
                     id_frecuencia,
                     id.year,
                     i.tipo_encuesta
                FROM bsc.frecuencia_medicion fm,
                     bsc.indicador           i,
                     bsc.indicador_detalle id
               WHERE fm.__id_indicador = i._id_indicador 
                 AND id.__id_indicador = i._id_indicador
                 AND id.tipo_regi      = 'INDI'
                 AND tipo_encuesta IS NOT NULL
                 AND flg_medido     = 'N'
                 AND fecha_medicion = (SELECT now()::date)
                 AND CASE WHEN NULL IS NOT NULL THEN __id_indicador = NULL
                     ELSE 1 = 1 END
ERROR - 2017-05-20 23:53:01 --> Severity: Warning --> pg_query(): Query failed: ERROR:  la referencia a la columna «__id_indicador» es ambigua
LINE 14: ...              AND CASE WHEN NULL IS NOT NULL THEN __id_indic...
                                                              ^ C:\xampp\htdocs\smiledu\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2017-05-20 23:53:01 --> Query error: ERROR:  la referencia a la columna «__id_indicador» es ambigua
LINE 14: ...              AND CASE WHEN NULL IS NOT NULL THEN __id_indic...
                                                              ^ - Invalid query: SELECT id.__id_indicador,
                     id_frecuencia,
                     id.year,
                     i.tipo_encuesta
                FROM bsc.frecuencia_medicion fm,
                     bsc.indicador           i,
                     bsc.indicador_detalle id
               WHERE fm.__id_indicador = i._id_indicador 
                 AND id.__id_indicador = i._id_indicador
                 AND id.tipo_regi      = 'INDI'
                 AND tipo_encuesta IS NOT NULL
                 AND flg_medido     = 'N'
                 AND fecha_medicion = (SELECT now()::date)
                 AND CASE WHEN NULL IS NOT NULL THEN __id_indicador = NULL
                     ELSE 1 = 1 END
ERROR - 2017-05-20 23:57:59 --> Severity: Notice --> Constant NO_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 165
ERROR - 2017-05-20 23:57:59 --> Severity: Notice --> Constant SI_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 166
ERROR - 2017-05-20 23:58:00 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '1'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-05-20 23:58:00 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '2'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-05-20 23:58:00 -->  - SELECT COUNT(1) AS cant
                FROM   bsc.categoria c,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind
                WHERE  c.__id_objetivo = '10'
                       AND ci.__id_categoria = c.id_categoria
                       AND ci.__id_indicador = ind._id_indicador
                       AND ind.cod_indi IS NOT NULL
ERROR - 2017-05-20 23:58:01 --> Severity: Notice --> Constant NO_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 165
ERROR - 2017-05-20 23:58:01 --> Severity: Notice --> Constant SI_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 166
ERROR - 2017-05-20 23:58:02 -->  - SELECT desc_linea_estrategica,
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
ERROR - 2017-05-20 23:58:03 --> Severity: Notice --> Constant NO_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 165
ERROR - 2017-05-20 23:58:03 --> Severity: Notice --> Constant SI_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 166
ERROR - 2017-05-20 23:58:38 --> Severity: Notice --> Constant NO_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 165
ERROR - 2017-05-20 23:58:38 --> Severity: Notice --> Constant SI_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 166
ERROR - 2017-05-20 23:58:38 -->  - SELECT desc_linea_estrategica,
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
ERROR - 2017-05-20 23:58:38 --> Severity: Notice --> Constant NO_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 165
ERROR - 2017-05-20 23:58:38 --> Severity: Notice --> Constant SI_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 166
ERROR - 2017-05-20 23:58:50 --> Severity: Notice --> Constant NO_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 165
ERROR - 2017-05-20 23:58:50 --> Severity: Notice --> Constant SI_MEDIDO already defined C:\xampp\htdocs\smiledu\application\modules\bsc\config\constants.php 166
