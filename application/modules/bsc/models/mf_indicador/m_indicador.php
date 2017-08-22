<?php
class M_indicador extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    //ya no se usa
    function getIndicadoresByObjetivo($idObjetivo, $opc,$idIndicador){
        $sql = "SELECT DISTINCT ind._id_indicador, 
                                id.desc_registro, 
                                id.valor_meta, 
                                id.valor_actual_porcentaje, 
                                id.tipo_valor, 
                                ind.__codigo_criterio_efqm, 
                                id.diff_actual_y_anterior, 
                                id.flg_amarillo, 
                                ind.tipo_gauge, 
                                ind.cod_indi,
                  (SELECT desc_frecuencia
                   FROM bsc.frecuencia_medicion
                   WHERE __id_indicador = ind._id_indicador
                   ORDER BY fecha_medicion DESC LIMIT 1),
                  (SELECT CASE WHEN (ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
            			       AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE 101 END))
                               AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            		 	   WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
            		 	        AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                                AND (id.valor_actual_porcentaje <= id.valor_meta)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
            			        AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
            		 	        AND (id.valor_actual_porcentaje = 0)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
            		 	        AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END ))
                                AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            			   ELSE 0
            			   END AS dorado
                   FROM bsc.indicador ind1
                   LEFT JOIN bsc.indicador_x_comparativa ic ON ind1._id_indicador = ic.__id_indicador
                   LEFT JOIN bsc.comparativa c ON c._id_comparativa = ic.__id_comparativa
                   WHERE ind1._id_indicador = ind._id_indicador
                   GROUP BY ind1._id_indicador), CASE
                                                     WHEN ? <> 0 THEN c.desc_comparativa
                                                 END AS desc_comparativa,
                                                 CASE
                                                     WHEN ? <> 0 THEN c.valor_comparativa
                                                 END AS valor_comparativa,
                                                 CASE
                                                     WHEN ? <> 0 THEN id.tipo_valor
                                                 END AS tipo_valor
                FROM bsc.indicador_detalle id,
                     bsc.objetivo ob,
                     bsc.indicador ind
                LEFT JOIN bsc.indicador_x_comparativa ic ON CASE
                                                            WHEN ? <> 0 THEN ind._id_indicador = ic.__id_indicador
                                                        END
                LEFT JOIN bsc.comparativa c ON CASE
                                               WHEN ? <> 0 THEN c._id_comparativa = ic.__id_comparativa
                                           END
                WHERE id.tipo_regi      = 'INDI'
                  AND id.__id_indicador = ind._id_indicador
                  AND id.flg_acti       = '1'
                  AND ob._id_objetivo   = ?
                  AND ((? <> 0
                        AND id.__id_indicador = ?)
                       OR (? = 0
                           AND 1 = 1))
                  AND ob._id_objetivo = ind.__id_objetivo
                ORDER BY _id_indicador";
        $result = $this->db->query($sql, array($opc,$opc,$opc,$opc,$opc,$idObjetivo,$idIndicador,$idIndicador,$idIndicador));
        if($idIndicador == 0){
            $data = $result->result();
        } else{
            $data = $result->row_array();
        }
        return $data;
    }
    
    function getIndicadoresByCategoria($idCategoria, $opc,$idIndicador){
        $sql = "SELECT DISTINCT ind._id_indicador, 
                                id.desc_registro, 
                                id.valor_meta, 
                                id.valor_actual_porcentaje, 
                                id.tipo_valor, 
                                ind.__codigo_criterio_efqm, 
                                id.diff_actual_y_anterior, 
                                id.flg_amarillo, 
                                ind.tipo_gauge, 
                                ind.cod_indi,
                  (SELECT desc_frecuencia
                   FROM bsc.frecuencia_medicion
                   WHERE __id_indicador = ind._id_indicador
                   ORDER BY fecha_medicion DESC LIMIT 1),
                  (SELECT CASE WHEN (ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
            			       AND (id.valor_actual_porcentaje >= COALESCE(max(c.valor_comparativa), -1))
                               AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            		 	   WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
            		 	        AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                                AND (id.valor_actual_porcentaje <= id.valor_meta)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
            			        AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
            		 	        AND (id.valor_actual_porcentaje = 0)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
            		 	        AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END ))
                                AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            			   ELSE 0
            			   END AS dorado
            
            			    FROM bsc.indicador ind1
            			    LEFT JOIN bsc.indicador_x_comparativa ic 
            				   ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
            			    LEFT JOIN bsc.comparativa c 
            				   ON c._id_comparativa = ic.__id_comparativa
            				   WHERE ind1._id_indicador = ind._id_indicador
            			    GROUP BY ind1._id_indicador), CASE
                                                     WHEN ? <> 0 THEN c.desc_comparativa
                                                 END AS desc_comparativa,
                                                 CASE
                                                     WHEN ? <> 0 THEN c.valor_comparativa
                                                 END AS valor_comparativa,
                                                 CASE
                                                     WHEN ? <> 0 THEN id.tipo_valor
                                                 END AS tipo_valor
                FROM bsc.indicador_detalle id,
                     bsc.objetivo ob,
                     bsc.categoria_x_indicador ci,
                     bsc.indicador ind
                LEFT JOIN bsc.indicador_x_comparativa ic ON CASE
                                                            WHEN ? <> 0 THEN ind._id_indicador = ic.__id_indicador
                                                        END
                LEFT JOIN bsc.comparativa c ON CASE
                                               WHEN ? <> 0 THEN c._id_comparativa = ic.__id_comparativa
                                           END
                WHERE id.tipo_regi      = 'INDI'
                  AND id.__id_indicador = ind._id_indicador
                  AND id.flg_acti       = '1'
                  AND ob._id_objetivo   = ci.__id_objetivo
                  AND ci.__id_categoria = ?
                  AND ci.__id_indicador = id.__id_indicador
                  AND ind.cod_indi IS NOT NULL
                  AND ((? <> 0
                        AND id.__id_indicador = ?)
                       OR (? = 0
                           AND 1 = 1))
                ORDER BY _id_indicador";
        
        $result = $this->db->query($sql, array($opc,$opc,$opc,$opc,$opc,$idCategoria,$idIndicador,$idIndicador,$idIndicador));
        
        if($idIndicador == 0){
            $data = $result->result();
        } else{
            $data = $result->row_array();
        }
        return $data;
    }
    
    function getHijosByIndicador($idIndicador) {
        $sql = "SELECT id.desc_registro, 
        		       id.__id_indicador,
        		       id.id_indicador_detalle,
        		       id.valor_meta,
        		       id.valor_actual_porcentaje,
        		       id.tipo_valor,
        		       id.orden,
                       id.valor_actual_numerico,
        		       id.id_sede,
        		       id.id_nivel,
        		       id.id_grado,
        		       id.id_aula,
                       id.id_disciplina,
                       id.id_area,
        		       id.diff_actual_y_anterior,
        		       id.tipo_regi,
                       id.flg_amarillo,
                       ind.tipo_gauge,
                       id.cant_alum_aula,
                       id.tipo_regi,
                       id.back_color,
                       CASE WHEN ind._id_tipo_estructura = 'SNGA' AND (id.tipo_regi = 'AULA' OR id.tipo_regi  = 'GRADO') THEN 0
    		                WHEN ind._id_tipo_estructura = 'SNG'  AND (id.tipo_regi = 'AULA' OR id.tipo_regi  = 'GRADO') THEN 0
                            WHEN ind._id_tipo_estructura = 'SN'   AND (id.tipo_regi = 'AULA' OR id.tipo_regi  = 'GRADO' OR id.tipo_regi = 'NIVEL') THEN 0
                            WHEN ind._id_tipo_estructura = 'S'    AND (id.tipo_regi = 'AULA' OR id.tipo_regi  = 'GRADO' OR id.tipo_regi = 'SEDE' OR id.tipo_regi = 'NIVEL') THEN 0
                            WHEN ind._id_tipo_estructura = 'DN'   AND (id.tipo_regi = 'NIVEL') THEN 0
                            WHEN ind._id_tipo_estructura = 'SNA'  AND (id.tipo_regi = 'AREA')  THEN 0    
                            WHEN ind._id_tipo_estructura = 'SA'   AND (id.tipo_regi = 'AREA')  THEN 0
                            WHEN ind._id_tipo_estructura = 'SG'   AND (id.tipo_regi = 'GRADO') THEN 0
                            ELSE 1 END AS boton,
                       CASE WHEN ind._id_tipo_estructura = 'SNGA' AND (id.tipo_regi = 'AULA')  THEN 1
                            WHEN ind._id_tipo_estructura = 'SNG'  AND (id.tipo_regi = 'GRADO') THEN 1
                            WHEN ind._id_tipo_estructura = 'SN'   AND (id.tipo_regi = 'NIVEL') THEN 1
                            WHEN ind._id_tipo_estructura = 'DN'   AND (id.tipo_regi = 'NIVEL') THEN 1
                            WHEN ind._id_tipo_estructura = 'SNA'  AND (id.tipo_regi = 'AREA')  THEN 1
                            WHEN ind._id_tipo_estructura = 'SA'   AND (id.tipo_regi = 'AREA')  THEN 1
                            WHEN ind._id_tipo_estructura = 'SG'   AND (id.tipo_regi = 'GRADO') THEN 1
                            WHEN ind._id_tipo_estructura = 'S'    AND (id.tipo_regi = 'SEDE')  THEN 1
			                ELSE 0 END AS btnDetalle
    		      FROM bsc.indicador_detalle id,
    		           bsc.indicador ind
    		     WHERE id.__id_indicador = ?
    		       AND ind._id_indicador = id.__id_indicador
    		  ORDER BY id.orden";
        $result = $this->db->query($sql, array($idIndicador));
        return $result->result();
    }
    
    function editarMeta($idIndicador, $idIndDetalle, $meta, $tipoEstructura) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $sql    = null;
            $result = null;
            if($tipoEstructura == ESTRUCTURA_SNGA || $tipoEstructura == ESTRUCTURA_SNG || $tipoEstructura == ESTRUCTURA_S) {
                $sql = "SELECT * FROM bsc.cambiar_meta_snga(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, $meta, 
                                                       _getSesion('nid_persona'), 
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_DN) {
                $sql = "SELECT * FROM bsc.cambiar_meta_dn(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_SG) {
                $sql = "SELECT * FROM bsc.cambiar_meta_sg(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_SNA) {
                $sql = "SELECT * FROM bsc.cambiar_meta_sna(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_SA) {
                $actMeta = 1;
                $sql = "SELECT * FROM bsc.cambiar_meta_sa(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            }
            if($result == null) {
                throw new Exception('(MI-001)');
            }
            if($result->num_rows() == 1) {
                if($result->row()->resultado == 'OK') {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj']   = MSJ_UPT;
                } else {
                    $data['msj'] = $result->row()->resultado;
                }
            } else {
                $data['msj'] = '(MI-002)';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function editarIndicadorHijo($pk, $campo, $valor) {
        try {
            $data = array($campo => (empty($valor) ? null : $valor));
            $this->db->where('id_indicador_detalle', $pk);
            $this->db->update('bsc.indicador_detalle', $data);
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error update');
            }
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
    
    function getEstructuraFromIndicador($idIndicador) {
        $sql = "SELECT tipo_estructura
                  FROM bsc.indicador_detalle
                 WHERE __id_indicador = ?
                   AND tipo_regi = 'INDI' 
                   AND year      = (SELECT EXTRACT(YEAR FROM now()) ) 
                   AND flg_acti  = '".FLG_ACTIVO."' 
                       LIMIT 1";
        $result = $this->db->query($sql, array($idIndicador));
        return ($result->row()->tipo_estructura);
    }
    
    function getQueryEstructuraByIndicadorDetalle($idIndicadorDetalle){
        $sql = "SELECT te.query,
                       te.tipo_estructura,
                       te.tipo_regi,
                       te._id_tipo_estructura
                  FROM (SELECT ind.tipo_regi,
                        	   (SELECT ind1.tipo_estructura 
                        	   	  FROM bsc.indicador_detalle ind1
                        	   	 WHERE ind1.tipo_regi      = 'INDI'
                        	   	   AND ind1.__id_indicador = ind.__id_indicador)
                	      FROM   bsc.indicador_detalle ind
                	     WHERE  ind.id_indicador_detalle = ? ) as tipo,
                       bsc.tipo_estructura te
                 WHERE te.tipo_estructura = tipo.tipo_estructura
                   AND te.tipo_regi       = tipo.tipo_regi";
        $result = $this->db->query($sql, array($idIndicadorDetalle));
        return $result->row_array();
    }
    
    function getEstructuraByIndicadorDetalle($idIndicadorDetalle){
        $query = $this->getQueryEstructuraByIndicadorDetalle($idIndicadorDetalle);
        if($query['tipo_estructura'] == ESTRUCTURA_SG && $query['tipo_regi'] == 'INDI') {
            $idSede = $this->m_utils->getById('indicador_detalle', 'id_sede_ppu', 'id_indicador_detalle', $idIndicadorDetalle, "bsc");
            $query = str_replace("?ind_deta", $idIndicadorDetalle, $query['query']);
            $query = str_replace("?idsede", $idSede, $query);
            $result = $this->db->query($query);
        } else {
            $query = str_replace("?ind_deta", $idIndicadorDetalle, $query['query']);
            $result = $this->db->query($query);
        }
        return $result->result();
    }
    
    function insertUpdateEstructuraIndicador($listaInsert, $listDelete, $tipoEstructura, $idIndicador, $idIndDetalle, $meta) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        $result = null;
        try {
            if(count($listaInsert) > 0) {
                $this->db->insert_batch('bsc.indicador_detalle', $listaInsert);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('(MI-003)');
                }
            }
            if(count($listDelete) > 0) {
                $cont = 0;
                foreach ($listDelete as $id) {
                    $niv = $this->getNivelesByDetalleIndicador($id);
                    $sql = "DELETE FROM bsc.indicador_detalle_log
                             WHERE __id_indicador_detalle IN (SELECT id_indicador_detalle
                                                                FROM bsc.indicador_detalle id
                                                               WHERE id.__id_indicador = ?
                                                                 AND CASE WHEN ? <> 0 THEN id.id_sede = ?
                                                                          ELSE id.id_sede = id.id_sede   END 
                                                                 AND CASE WHEN ? <> 0 THEN id.id_nivel = ?
                                                                          ELSE id.id_nivel = id.id_nivel END 
                                                                 AND CASE WHEN ? <> 0 THEN id.id_grado = ?
                                                                          ELSE id.id_grado = id.id_grado END
                                                                 AND CASE WHEN ? <> 0 THEN id.id_aula = ?
                                                                          ELSE id.id_aula = id.id_aula   END
                                                                 AND CASE WHEN ? <> 0 THEN id.id_area = ?
                                                                          ELSE id.id_area = id.id_area   END
                                                                 AND CASE WHEN ? <> 0 THEN id.id_disciplina = ?
                                                                          ELSE id.id_disciplina = id.id_disciplina END)";
                    $result = $this->db->query($sql, array($niv['__id_indicador'],
                                                           $niv['id_sede'] ,$niv['id_sede'],
                                                           $niv['id_nivel'],$niv['id_nivel'],
                                                           $niv['id_grado'],$niv['id_grado'],
                                                           $niv['id_aula'] ,$niv['id_aula'],
                                                           $niv['id_area'] ,$niv['id_area'],
                                                           $niv['id_disciplina'],$niv['id_disciplina']));
                    //Borro de indicador_detalle_log ahora si a borrar de indicador_detalle
                    $sql = "DELETE FROM bsc.indicador_detalle id
                            WHERE id.__id_indicador = ?
                              AND CASE WHEN ? <> 0 THEN id.id_sede = ?
                                       ELSE id.id_sede = id.id_sede   END 
                              AND CASE WHEN ? <> 0 THEN id.id_nivel = ?
                                       ELSE id.id_nivel = id.id_nivel END 
                              AND CASE WHEN ? <> 0 THEN id.id_grado = ?
                                       ELSE id.id_grado = id.id_grado END
                              AND CASE WHEN ? <> 0 THEN id.id_aula = ?
                                       ELSE id.id_aula = id.id_aula   END
                              AND CASE WHEN ? <> 0 THEN id.id_area = ?
                                       ELSE id.id_area = id.id_area   END
                              AND CASE WHEN ? <> 0 THEN id.id_disciplina = ?
                                       ELSE id.id_disciplina = id.id_disciplina END ";
                    
                    $this->db->query($sql, array($niv['__id_indicador'],
                                                 $niv['id_sede'] ,$niv['id_sede'],
                                                 $niv['id_nivel'],$niv['id_nivel'],
                                                 $niv['id_grado'],$niv['id_grado'],
                                                 $niv['id_aula'] ,$niv['id_aula'],
                                                 $niv['id_area'] ,$niv['id_area'],
                                                 $niv['id_disciplina'],$niv['id_disciplina']));
                }
            }
            $actMeta = null;
            if($tipoEstructura == ESTRUCTURA_SNGA || $tipoEstructura == ESTRUCTURA_SNG || $tipoEstructura == ESTRUCTURA_S) {
                $actMeta = 1;
                $sql = "SELECT * FROM bsc.cambiar_meta_snga(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, $meta,
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_DN) {
                $actMeta = 1;
                $sql = "SELECT * FROM bsc.cambiar_meta_dn(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_SG) {
                $actMeta = 1;
                $sql = "SELECT * FROM bsc.cambiar_meta_sg(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_SA) {
                $actMeta = 1;
                $sql = "SELECT * FROM bsc.cambiar_meta_sa(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            } else if($tipoEstructura == ESTRUCTURA_SNA) {
                $actMeta = 1;
                $sql = "SELECT * FROM bsc.cambiar_meta_sna(?, ?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($idIndicador, $idIndDetalle, intval($meta),
                                                       _getSesion('nid_persona'),
                                                       _getSesion('nombre_completo')));
            }
            $tipo_encuesta = $this->m_utils->getById("bsc.indicador", "tipo_encuesta", "_id_indicador", $idIndicador);
            $data = $this->actualizarActualIndicador($idIndicador, $tipo_encuesta);
            if($result == null && $actMeta != null) {
                throw new Exception('(MI-009)');
            }
            if($result == null) {//NO MODIFICO META
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = MSJ_UPT;
                $this->db->trans_commit();
            } else {
                if($result->row()->resultado == 'OK') {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj']   = MSJ_UPT;
                    $this->db->trans_commit();
                } else {
                    throw new Exception('(MI-006)');
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getOrdenFromIndicadorDetalle($idIndicadorDetalle){
        $sql = "SELECT orden 
                FROM   bsc.indicador_detalle
                WHERE  id_indicador_detalle = ?";
        
        $result = $this->db->query($sql, array($idIndicadorDetalle));
        $data   = $result->row_array();
        
        $orden  = $data['orden'];
        $numero = $orden;
        for($i = 1; $i< strlen($orden)+1; $i++){
            $n = substr($orden, $i-1, 1);
            if($n == '0'){
                $numero = substr($orden, 0, $i-1);
                break;
            }
        }
        
        return $numero;
    }
    
  function getNivelesByDetalleIndicador($idDetalleIndicador){
      $sql = "SELECT id_sede,
                     __id_indicador,
                     id_nivel,
                     id_grado,
                     id_aula,
                     id_area,
                     id_disciplina
              FROM   bsc.indicador_detalle
              WHERE  id_indicador_detalle = ?";
      
      $result = $this->db->query($sql, array($idDetalleIndicador));
      $data   = $result->row_array();
      
      return $data;
  }
  
  function insertAllaulasByGrado($idGrado){
      
  }
  
  function getIndicadoresAsigRespMedicion($idUsuario, $opc, $idIndicador){
      $sql =   "SELECT DISTINCT 
                       ind._id_indicador, 
                       id.desc_registro,
                       id.valor_meta,
                       id.valor_actual_porcentaje,
                       id.tipo_valor,
                       ob._id_objetivo,
                       c.desc_comparativa,
                       ind.__codigo_criterio_efqm,
                       tipo_gauge,
                       ind.cod_indi,
                       CASE WHEN id.id_ppu <> 0 THEN 1
                       ELSE 0 
                       END AS ppu,
                        
                       (SELECT desc_frecuencia
    			       FROM bsc.frecuencia_medicion 
    			       WHERE __id_indicador = ind._id_indicador
    			       ORDER BY fecha_medicion DESC limit 1),
          
                      (SELECT CASE WHEN(ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
            			       AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE 101 END))
                               AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            		 	   WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
            		 	        AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                                AND (id.valor_actual_porcentaje <= id.valor_meta)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
            			       AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
            		 	        AND (id.valor_actual_porcentaje = 0)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
            		 	        AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END ))
                                AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            			   ELSE 0
            			   END AS dorado
            
            			    FROM bsc.indicador ind1
            			    LEFT JOIN bsc.indicador_x_comparativa ic 
            				   ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
            			    LEFT JOIN bsc.comparativa c 
            				   ON c._id_comparativa = ic.__id_comparativa
            				   WHERE ind1._id_indicador = ind._id_indicador
            			    GROUP BY ind1._id_indicador),
          
                       id.diff_actual_y_anterior,
                       id.flg_amarillo,
                       id.orden,
                       id.__id_indicador,
          
                       case when ? <> 0
                       then c.desc_comparativa
                       end as desc_comparativa,
          
                      CASE WHEN ? <> 0
                      THEN c.valor_comparativa
                      END AS valor_comparativa,
                        
                      CASE WHEN ? <> 0
                      THEN id.tipo_valor
                      END AS tipo_valor
          
                  FROM bsc.indicador_detalle id,
                       bsc.indicador_responsable ir,
                       bsc.objetivo ob,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind 
                       
                       LEFT JOIN bsc.indicador_x_comparativa ic ON
		             case when ? <> 0
		             then ind._id_indicador = ic.__id_indicador
				end
		        LEFT JOIN bsc.comparativa c ON 
		        case when ? <> 0
		        then c._id_comparativa = ic.__id_comparativa
		        end
          
                 WHERE id.tipo_regi        = 'INDI'
                   AND   id.flg_acti       = '1'
                   AND   ir.flg_acti       = '1'
                   AND   ir.__id_persona   = ?
                   AND   id.__id_indicador = ind._id_indicador
                   AND   ob._id_objetivo   = ci.__id_objetivo
                   AND   id.__id_indicador = ci.__id_indicador
                   AND   ir.__id_indicador = id.__id_indicador
                   AND   (( ? <> 0 AND id.__id_indicador = ?) OR ( ? = 0 AND 1 = 1 ))
                 ORDER BY id.__id_indicador";
      
      
      $result = $this->db->query($sql, array($opc,$opc,$opc,$opc,$opc,$idUsuario,$idIndicador,$idIndicador,$idIndicador));
      if($idIndicador == 0) {
          $data = $result->result();
      } else {
          $data = $result->row_array();
      }
      return $data;
  }
  
  function getIndicadoresPlanEstrategico($idIndicador = null) {
      $sql = "  SELECT DISTINCT 
                	   ind._id_indicador, 
                	   id.desc_registro,
                	   id.valor_meta,
                	   id.valor_actual_porcentaje,
                	   id.tipo_valor,
                	   ob._id_objetivo,
                	   c.desc_comparativa,
                	   ind.__codigo_criterio_efqm,
                	   tipo_gauge,
                	   ind.cod_indi,
                       CASE WHEN id.id_ppu <> 0 THEN 1 ELSE 0 END AS ppu,
                       (SELECT desc_frecuencia
                    	  FROM bsc.frecuencia_medicion 
                    	 WHERE __id_indicador = ind._id_indicador
                    	ORDER BY fecha_medicion DESC LIMIT 1),
                       (SELECT CASE WHEN(ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
                            			     AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) 
                                                                                      ELSE 101 END)) AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1
                            		WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."' AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) 
                                                                                                                       ELSE id.valor_actual_porcentaje + 1 END))
                                                                              AND (id.valor_actual_porcentaje <= id.valor_meta) THEN 1
                            		WHEN ind1.tipo_gauge = '".GAUGE_CERO."'   AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0 THEN MIN(c.valor_comparativa) ELSE -1 END))
                            		 	                                                                                    AND (id.valor_actual_porcentaje = 0) THEN 1
                            		WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."' AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0 THEN MAX(c.valor_comparativa) 
                                                                                                                       ELSE id.valor_actual_porcentaje + 1 END )) 
                                                                              AND (id.valor_actual_porcentaje >= id.valor_meta) THEN 1
                            	    ELSE 0
                                END AS dorado
                          FROM bsc.indicador ind1 LEFT JOIN bsc.indicador_x_comparativa ic ON (ind1._id_indicador = ic.__id_indicador   AND ic.flg_acti = '1')
                            			          LEFT JOIN bsc.comparativa             c  ON c._id_comparativa   = ic.__id_comparativa
                         WHERE ind1._id_indicador = ind._id_indicador
                         GROUP BY ind1._id_indicador
                        ),
                        id.diff_actual_y_anterior,
                        id.flg_amarillo,
                        id.orden,
                        id.__id_indicador,
                        c.desc_comparativa,
                        c.valor_comparativa,
                        id.tipo_valor
                  FROM bsc.indicador_detalle id,
                       bsc.objetivo ob,
                       bsc.categoria_x_indicador ci,
                       bsc.indicador ind LEFT JOIN bsc.indicador_x_comparativa ic ON case when 0 <> 0 then ind._id_indicador = ic.__id_indicador   end
                		                 LEFT JOIN bsc.comparativa c              ON case when 0 <> 0 then c._id_comparativa = ic.__id_comparativa end
                 WHERE id.tipo_regi        = 'INDI'
                   AND id.flg_acti         = '1'
                   AND ind.cod_indi        IS NOT NULL
                   AND   id.__id_indicador = ind._id_indicador
                   AND   ob._id_objetivo   = ci.__id_objetivo
                   AND   id.__id_indicador = ci.__id_indicador
                   AND CASE WHEN ? IS NOT NULL THEN ind._id_indicador = ?
                       ELSE 1 = 1 END
                ORDER BY id.__id_indicador";
      $result = $this->db->query($sql, array($idIndicador, $idIndicador));
      if($idIndicador == null){
          $data = $result->result();
      } else{
          $data = $result->row_array();
      }
      return $data;
  }
  
  function getHistoriaByIndicador($idIndicador){
      $sql = "SELECT descripcion,
                     diff_actual_y_anterior,
                     audi_pers_regi,
                     audi_id_usua,
                     audi_fec_regi,
                     p.foto_persona,
                     valor_anterior,
                     valor_nuevo,
                     tipo_cambio,
                     p.google_foto
                FROM bsc.indicador_detalle_log ind,
                     persona p
               WHERE __id_indicador   = ?
                 AND ind.audi_id_usua = p.nid_persona
                 --AND EXTRACT(YEAR FROM audi_fec_regi) = "._YEAR_."
             ORDER BY audi_fec_regi desc";
      $result = $this->db->query($sql, array($idIndicador));
      return $result->result(); 
  }
  
  function actualizarActualIndicador($idIndicador, $tipo_encuesta) {
      $data['error']    = EXIT_ERROR;
      $data['msj']      = null;
      try {
          $result      = null;
          $cantCambios = null;
          $resultado   = null;
          //COMENTADO POR dfloresgonz 3.11.16 ya no se leera del mongo para encuestas
          /*if($tipo_encuesta != null) {
              $result = $this->actualizarActualIndicadorMongoDB($idIndicador);
              $cantCambios = 1;
              $resultado   = $result['msj'];
          } else {
              $sql    = "SELECT * FROM bsc.actualizar_actual_indicador(?, ?, ?) resultado";
              $result = $this->db->query($sql, array($idIndicador, _getSesion('nid_persona'),
                                         _getSesion('nombre_completo')));
              $cantCambios = $result->num_rows();
              $resultado   = $result->row()->resultado;
          }*/
          $sql    = "SELECT * FROM bsc.actualizar_actual_indicador(?, ?, ?) resultado";
          $result = $this->db->query($sql, array($idIndicador, 
                                                 _getSesion('nid_persona'),
                                                 _getSesion('nombre_abvr')));
          $cantCambios = $result->num_rows();
          $resultado   = $result->row()->resultado;
          if($resultado == null) {
              throw new Exception('(MI-007)');
          }
          if($cantCambios != 1) {
              throw new Exception('(MI-008)');
          }
          if($resultado != 'OK') {
              throw new Exception($resultado);
          }
          $data['error'] = EXIT_SUCCESS;
          $data['msj']   = MSJ_UPT;
      } catch(Exception $e) {
          $data['msj'] = $e->getMessage();
      }
      return $data;
  }
  
  function getUltimaModif_ActualFromIndicador($idIndicador) {
      $sql = "SELECT audi_ult_modi_actual,
                     audi_ult_id_modi_actual,
                     audi_ult_pers_modi_actual
                FROM bsc.indicador_detalle
               WHERE __id_indicador = ?
                 AND flg_acti       = '1'
                 AND tipo_regi      = 'INDI'
                 AND year           = (SELECT EXTRACT(YEAR FROM now())) LIMIT 1";
      $result = $this->db->query($sql, array($idIndicador));
      if($result->num_rows() == 1) {
          return ($result->row_array());
      } else {
          return null;
      }
  }
  
  function getindicadorDetalleFromIndicador($idIndicador, $camposArray) {
      $columnas = implode(",", $camposArray);
      $sql = "SELECT {$columnas}
                FROM bsc.indicador_detalle
               WHERE __id_indicador = ?
                 AND flg_acti       = '1'
                 AND tipo_regi      = 'INDI'
                 AND year           = (SELECT EXTRACT(YEAR FROM now())) LIMIT 1";
      $result = $this->db->query($sql, array($idIndicador));
      if($result->num_rows() == 1) {
          return ($result->row_array());
      } else {
          return null;
      }
  }
  
  function getValorAmarilloByIndicador($idIndicador){
      $sql = "SELECT ind.flg_amarillo,
                     ind.valor_meta
                FROM bsc.indicador_detalle ind
               WHERE ind.__id_indicador = ?
                 AND ind.tipo_regi      = 'INDI'";
      $result = $this->db->query($sql, array($idIndicador));
      $data   = $result->row_array();
      return $data;
  }
  
  function actualizaFlgAmarillo($idIndicador, $valorAmarillo){
      $data['error']    = EXIT_ERROR;
      $data['msj']      = MSJ_ERROR;
      try{
          $this->db->where('__id_indicador', $idIndicador);
          $this->db->where('tipo_regi'     , 'INDI');
          $this->db->where('flg_acti'      , FLG_ACTIVO);
          $this->db->where('year'          , date("Y"));
          $this->db->update('bsc.indicador_detalle', $valorAmarillo);
          $data['error']    = EXIT_SUCCESS;
          $data['msj']      = MSJ_UPT;
      } catch (Exception $e){
          $data['msj'] = $e->getMessage();
      }
      return $data;
  }
  
  function insertNuevaMedicion($arrayData){
      $data['error']    = EXIT_ERROR;
      $data['msj']      = MSJ_ERROR;
      try {
          $this->db->insert('bsc.frecuencia_medicion' , $arrayData);
          $data['error']    = EXIT_SUCCESS;
          $data['msj']      = MSJ_INS;
      } catch (Exception $e) {
          $data['msj'] = $e->getMessage();
      }
      return $data;
  }
  
  function getIndicadoresByObjetivoByAño($idObjetivo,$yearIndi){
      $sql = "SELECT i._id_indicador,
                     i.desc_indicador,
                     i.cod_indi  
                  FROM bsc.indicador i,
                       bsc.indicador_detalle id
                 WHERE i.__id_objetivo = ?
                   AND id.tipo_regi = 'INDI'
                   AND id.year = ?
                   AND id.__id_indicador = i._id_indicador
                 ORDER BY i._id_indicador";
      $result = $this->db->query($sql, array($idObjetivo,$yearIndi));
      return $result->result();
  }
  
  function getIndicadorDetalleByIndicador($idIndicador,$yearIndi){
      $sql = "SELECT ind.id_indicador_detalle,
		             ind.id_sede,
		             ind.id_nivel,
		             ind.id_grado,
		             ind.id_aula,
		             ind.id_area,
                     ind.id_disciplina,
		             ind.valor_meta,
		             ind.valor_actual_porcentaje,
		             ind.year,
		             ind.tipo_valor,
                     ind.desc_registro,
		             ind.tipo_calculo,
		             ind.tipo_indicador,
		             ind.tipo_estructura,
		             ind.audi_id_usua,
			         ind.flg_amarillo,
			         ind.tipo_regi,
                     ind.id_sede_ppu,
                     ind.id_ppu,
                     ind.orden,
                     (SELECT CASE WHEN ind.year = (SELECT EXTRACT(YEAR FROM now()) )  
                             THEN 1 ELSE 0 end as actual 
                        FROM bsc.indicador_detalle ind
                       WHERE ind.__id_indicador = ?
                         AND ind.tipo_regi = 'INDI' 
                         AND ind.year = (SELECT EXTRACT(YEAR FROM now()) ) )
                FROM bsc.indicador_detalle ind
               WHERE ind.__id_indicador = ?
                 AND ind.tipo_regi = 'INDI'
                 AND ind.year      = ?";
      $result = $this->db->query($sql, array($idIndicador,$idIndicador,$yearIndi));
      return $result->row_array();
  }
  
  function getMetaByIndicadorDetalle($idIndicadorDetalle){
      $sql = "SELECT idl.valor_anterior,
                     idl.valor_nuevo,
                     idl.audi_fec_regi,
                     idl.audi_pers_regi
                FROM bsc.indicador_detalle_log idl
               WHERE idl.__id_indicador_detalle = ?";
      $result = $this->db->query($sql, array($idIndicadorDetalle));
      return $result->result();
  }
  
  function insertNuevoIndicador($arrayData){
      $data['error']    = EXIT_ERROR;
      $data['msj']      = null;
      $data['cabecera'] = CABE_UPT;
      try {
          $this->db->insert('bsc.indicador_detalle' , $arrayData);
          $data['error']    = EXIT_SUCCESS;
          $data['msj']      = MSJ_INS;
          $data['cabecera'] = CABE_INS;
      } catch (Exception $e){
          $data['msj'] = $e->getMessage();
      }
      return $data;
  }
  
  function getCampoByIndicador($idIndicador){
      $sql = "SELECT valor_actual_numerico,CASE WHEN (id_ppu > 0 ) 
                          THEN id_ppu 
                          ELSE 0 
                           END AS aux 
                FROM bsc.indicador_detalle 
               WHERE tipo_regi      = 'INDI' 
                 AND __id_indicador = ?";
      $result = $this->db->query($sql,array($idIndicador));
      $data = $result->row_array();
      return $data;
  }
  
  function getTipoGaugeByIndicadorDetalle($indicadorDetalle){
      $sql = "SELECT tipo_gauge
                FROM bsc.indicador
               WHERE _id_indicador = (SELECT __id_indicador 
                                        FROM bsc.indicador_detalle 
                                       WHERE id_indicador_detalle = ?)";
      $result = $this->db->query($sql,array($indicadorDetalle));
      $data = $result->row_array();
      return $data;
  }
  
  function getInfoIndicador($idIndicador){
      $sql = "SELECT desc_objetivo,
                     desc_linea_estrategica
                FROM bsc.indicador i,
                     bsc.objetivo o,
                     bsc.linea_estrategica le
                
                WHERE i.__id_objetivo          = o._id_objetivo
                AND   o.__id_linea_estrategica = le._id_linea_estrategica
                AND   i._id_indicador          = ?";
      $result = $this->db->query($sql,array($idIndicador));
      $data = $result->row_array();
      return $data;
  }
  
  function getAllIndicadoresWithCategorias(){
      $sql = "SELECT i.desc_indicador,
                     i._id_indicador,
             (SELECT string_agg(desc_categoria,', ') 
             FROM bsc.categoria_x_indicador ci,
                  bsc.categoria c
             WHERE ci.__id_categoria = c.id_categoria
             AND   ci.__id_indicador = i._id_indicador) as categorias
             FROM bsc.indicador i";
      
      $result = $this->db->query($sql);
      return $result->result();
  }
  
  function getCategoriasByIndicador($idIndicador){
      $sql = "SELECT c.desc_categoria,
            	     c.id_categoria,
                     c.__id_objetivo,
            CASE WHEN ci.__id_indicador  IS NULL THEN ''
                      ELSE '1'
                      END AS check
            FROM bsc.categoria c
            LEFT JOIN bsc.categoria_x_indicador ci
            ON c.id_categoria = ci.__id_categoria
            AND ci.__id_indicador = ?";
      
      $result = $this->db->query($sql,array($idIndicador));
      return $result->result();
  }
  
  function updateInsertCategoriaIndicador($arrayDatos){
      $data['error']    = EXIT_ERROR;
      $data['msj']      = MSJ_ERROR;
      $this->db->trans_begin();
      try {
          $cont = 0;
          foreach ($arrayDatos as $dato) {
              if($dato['condicion'] == 0 ) {//insert
                  unset($dato['condicion']);
                  $this->db->insert('bsc.categoria_x_indicador', $dato);
                  $cont = $cont + $this->db->affected_rows();  
              } else if($dato['condicion'] == 1){//delete
                  unset($dato['condicion']);
                  $this->db->where('__id_categoria', $dato['__id_categoria']);
                  $this->db->where('__id_indicador', $dato['__id_indicador']);
                  $this->db->delete('bsc.categoria_x_indicador');   
                  $cont = $cont + $this->db->affected_rows();
              }
          }
          if($cont != count($arrayDatos) ) {
              $this->db->trans_rollback();
              throw new Exception('(MI-002)');
          }
          if ($this->db->trans_status() === FALSE) {
              $data['msj']   = '(MI-001)';
              $this->db->trans_rollback();
          } else {
              $data['error']     = EXIT_SUCCESS;
              $data['msj']       = MSJ_INS;
              $this->db->trans_commit();
          }
      } catch (Exception $e) {
          $data['msj'] = $e->getMessage();
          $this->db->trans_rollback();
      }
      return $data;
  }
  
  function evaluaInsertUpdateCatInd($idCategoria, $idIndicador){
      $sql = "SELECT COUNT(1) AS cuenta
                FROM bsc.categoria_x_indicador
                WHERE __id_categoria = ?
                AND   __id_indicador = ?";
      $result = $this->db->query($sql, array($idCategoria, $idIndicador));
      return $result->row()->cuenta;
  }
  
  function cerrarIndicador_CTRL($idIndicador) {
      $data['error']    = EXIT_ERROR;
      $data['msj']      = MSJ_ERROR;
      try {
          $sql = "SELECT * FROM bsc.cerrar_indicador(?, ?, ?) resultado";
          $result = $this->db->query($sql, array($idIndicador, _getSesion('nid_persona'),
                                                 _getSesion('nombre_completo')));
          if($result == null) {
              throw new Exception('(MI-004)');
          }
          if($result->num_rows() == 1) {
              if($result->row()->resultado == 'OK') {
                  $data['error'] = EXIT_SUCCESS;
                  $data['msj']   = MSJ_UPT;
              } else {
                  $data['msj'] = $result->row()->resultado;
              }
          } else {
              $data['msj'] = '(MI-005)';
          }
      }catch(Exception $e) {
          $data['msj'] = $e->getMessage();
      }
      return $data;
  }
  
  function getCountPreguntasIndicador($idIndicador){
       $sql = "SELECT COUNT(1) AS count
                 FROM senc.preguntas
                WHERE _id_indicador_bsc = ?
             GROUP BY _id_indicador_bsc";
       $result = $this->db->query($sql, array($idIndicador));
       return $result->row()->count;
  }
  
  function getINDI_indicadorDetalle($idIndicador, $columnas) {
      $sql = 'SELECT '.$columnas.'
               FROM bsc.indicador_detalle 
              WHERE __id_indicador = ?
                AND tipo_regi = \'INDI\' ';
      $result = $this->db->query($sql, array($idIndicador));
      return $result->row_array();
  }
  
  function actualizarActualIndicadorMongoDB($idIndicador1 = null){
      try{
          $data['error']    = EXIT_ERROR;
          $data['msj']      = MSJ_ERROR;
          $this->db->trans_begin();
          $indicadores = null;
          if($idIndicador1 == null) {
              $indicadores = $this->getAllIndicadoresActualizarMongodb();
          } else {
              $indicadores = $this->getIndicadoresActualizarMongodb($idIndicador1);
          }
          foreach ($indicadores as $ind){
              $idIndicador = $ind->__id_indicador;
              $year = $ind->year;

              $idPersona     = _getSesion('nid_persona');
              $nombrePersona = _getSesion('nombre_completo');
              
              $indicador  = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'INDI');
              $sedes      = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'SEDE');
              $cant_satis = 0;
              $cant_part  = 0;
              
              $cons_cant_part_indi   = 0;
              $cons_cant_satisf_indi = 0;
              
              if($ind->tipo_encuesta == TIPO_ENCUESTA_ALUMNOS || $ind->tipo_encuesta == TIPO_ENCUESTA_PADREFAM){
                  foreach($sedes as $s) {
                      $cons_cant_part_sede   = 0;
                      $cons_cant_satisf_sede = 0;
                      $estructura = "AND id_sede = ".$s->id_sede;
                      $niveles    = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'NIVEL',$estructura);
                      foreach($niveles as $n) {
                          $cons_cant_part_nivel   = 0;
                          $cons_cant_satisf_nivel = 0;
                          $estructura = "AND id_sede  = ".$s->id_sede.
                          " AND id_nivel = ".$n->id_nivel;
                          $grados     = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'GRADO',$estructura);
                          foreach($grados as $g) {
                              $cons_cant_part_grado   = 0;
                              $cons_cant_satisf_grado = 0;
                              $estructura = "AND id_sede  = ".$s->id_sede.
                              " AND id_nivel = ".$n->id_nivel.
                              " AND id_grado = ".$g->id_grado;
                              $aulas      = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'AULA',$estructura);
                              foreach($aulas as $a) {
                                  $res        = $this->queryMongodb($idIndicador, $ind->tipo_encuesta, '"preguntas.id_aula" : '.$a->id_aula, $year);
                                  if($res['ok'] != 1) {
                                      throw new Exception('(QUERY MONGO)');
                                  }
                                  $porcentaje = (count($res['retval']) > 0) ? ($res['retval'][0]['count']*100)/$res['retval'][0]['part'] : 0;
                                  $valor_actual_numerico = (count($res['retval']) > 0) ? $res['retval'][0]['count'] : 0;
                                  $cant_alum_aula        = (count($res['retval']) > 0) ? $res['retval'][0]['part']  : 0;
              
                                  //ACTUALIZAMOS EL AULA CON $res['retval'][0]['count'] Y $res['retval'][0]['part']
                                  $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                                      "valor_actual_numerico"     => $valor_actual_numerico,
                                      "valor_actual_ultimo"       => $a->valor_actual_porcentaje,
                                      "cant_alum_aula"            => $cant_alum_aula,
                                      "diff_actual_y_anterior"    => $porcentaje - $a->valor_actual_porcentaje,
                                      "audi_ult_id_modi_actual"   => $idPersona,
                                      "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                                      "audi_ult_pers_modi_actual" => $nombrePersona,
                                      "audi_id_modi"              => $idPersona,
                                      "audi_fec_modi"             => date('D, d M Y H:i:s'),
                                      "audi_pers_modi"            => $nombrePersona);
                                  $this->actualizarIndicadorDetalle($dataDetalle, $a->id_indicador_detalle);
                                  
                                  //ACUMULAMOS PARA GRADO
                                  $cons_cant_satisf_grado += (count($res['retval']) > 0) ? $res['retval'][0]['count'] : 0;
                                  $cons_cant_part_grado   += (count($res['retval']) > 0) ? $res['retval'][0]['part']  : 0;
                              }
                              //ACTUALIZAMOS GRADO CON $cons_cant_part_grado Y $cons_cant_satisf_grado
                              $porcentaje = ($cons_cant_satisf_grado > 0) ? ($cons_cant_satisf_grado*100)/$cons_cant_part_grado : 0;
                              $dataDetalle = array("valor_actual_porcentaje"    => $porcentaje,
                                  "valor_actual_numerico"     => $cons_cant_satisf_grado,
                                  "valor_actual_ultimo"       => $g->valor_actual_porcentaje,
                                  "cant_alum_aula"            => $cons_cant_part_grado,
                                  "diff_actual_y_anterior"    => $porcentaje - $g->valor_actual_porcentaje,
                                  "audi_ult_id_modi_actual"   => $idPersona,
                                  "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                                  "audi_ult_pers_modi_actual" => $nombrePersona,
                                  "audi_id_modi"              => $idPersona,
                                  "audi_fec_modi"             => date('D, d M Y H:i:s'),
                                  "audi_pers_modi"            => $nombrePersona);
                              $this->actualizarIndicadorDetalle($dataDetalle, $g->id_indicador_detalle);
              
                              $cons_cant_part_nivel   += $cons_cant_part_grado;
                              $cons_cant_satisf_nivel += $cons_cant_satisf_grado;
                          }
                          //ACTUALIZAMOS NIVEL CON $cons_cant_part_nivel Y $cons_cant_satisf_nivel
                          $porcentaje = ($cons_cant_satisf_nivel > 0) ? ($cons_cant_satisf_nivel*100)/$cons_cant_part_nivel : 0;
                          $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                              "valor_actual_numerico"     => $cons_cant_satisf_nivel,
                              "valor_actual_ultimo"       => $n->valor_actual_porcentaje,
                              "cant_alum_aula"            => $cons_cant_part_nivel,
                              "diff_actual_y_anterior"    => $porcentaje - $n->valor_actual_porcentaje,
                              "audi_ult_id_modi_actual"   => $idPersona,
                              "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                              "audi_ult_pers_modi_actual" => $nombrePersona,
                              "audi_id_modi"              => $idPersona,
                              "audi_fec_modi"             => date('D, d M Y H:i:s'),
                              "audi_pers_modi"            => $nombrePersona);
                          $this->actualizarIndicadorDetalle($dataDetalle, $n->id_indicador_detalle);
              
                          $cons_cant_part_sede   += $cons_cant_part_nivel;
                          $cons_cant_satisf_sede += $cons_cant_satisf_nivel;
                      }
                      //ACTUALIZAMOS SEDE CON $cons_cant_part_sede Y $cons_cant_satisf_sede
                      $porcentaje = ($cons_cant_satisf_sede > 0) ? ($cons_cant_satisf_sede*100)/$cons_cant_part_sede : 0;
                      $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                          "valor_actual_numerico"     => $cons_cant_satisf_sede,
                          "valor_actual_ultimo"       => $s->valor_actual_porcentaje,
                          "cant_alum_aula"            => $cons_cant_part_sede,
                          "diff_actual_y_anterior"    => $porcentaje - $s->valor_actual_porcentaje,
                          "audi_ult_id_modi_actual"   => $idPersona,
                          "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                          "audi_ult_pers_modi_actual" => $nombrePersona,
                          "audi_id_modi"              => $idPersona,
                          "audi_fec_modi"             => date('D, d M Y H:i:s'),
                          "audi_pers_modi"            => $nombrePersona);
                      $this->actualizarIndicadorDetalle($dataDetalle, $s->id_indicador_detalle);
              
                      $cons_cant_part_indi   += $cons_cant_part_sede;
                      $cons_cant_satisf_indi += $cons_cant_satisf_sede;
                  }
              } else if($ind->tipo_encuesta == TIPO_ENCUESTA_DOCENTE) {
                  foreach($sedes as $s) {
                      $cons_cant_part_sede   = 0;
                      $cons_cant_satisf_sede = 0;
                      $estructura = "AND id_sede = ".$s->id_sede;
                      $areas = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'AREA',$estructura);
                      foreach($areas as $a){
                          $res        = $this->queryMongodb($idIndicador, TIPO_ENCUESTA_DOCENTE, '"preguntas.id_area" : '.$a->id_area, $year);
                          if($res['ok'] != 1){
                              throw new Exception('(QUERY MONGO)');
                          }
                          $porcentaje = (count($res['retval']) > 0) ? ($res['retval'][0]['count']*100)/$res['retval'][0]['part'] : 0;
                          $valor_actual_numerico = (count($res['retval']) > 0) ? $res['retval'][0]['count'] : 0;
                          $cant_alum_aula        = (count($res['retval']) > 0) ? $res['retval'][0]['part']  : 0;
              
                          //ACTUALIZAMOS EL AREA CON $res['retval'][0]['count'] Y $res['retval'][0]['part']
                          $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                              "valor_actual_numerico"     => $valor_actual_numerico,
                              "valor_actual_ultimo"       => $a->valor_actual_porcentaje,
                              "cant_alum_aula"            => $cant_alum_aula,
                              "diff_actual_y_anterior"    => $porcentaje - $a->valor_actual_porcentaje,
                              "audi_ult_id_modi_actual"   => $idPersona,
                              "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                              "audi_ult_pers_modi_actual" => $nombrePersona,
                              "audi_id_modi"              => $idPersona,
                              "audi_fec_modi"             => date('D, d M Y H:i:s'),
                              "audi_pers_modi"            => $nombrePersona);
                          $this->actualizarIndicadorDetalle($dataDetalle, $a->id_indicador_detalle);
              
                          //ACUMULAMOS PARA SEDE
                          $cons_cant_satisf_sede += (count($res['retval']) > 0) ? $res['retval'][0]['count'] : 0;
                          $cons_cant_part_sede   += (count($res['retval']) > 0) ? $res['retval'][0]['part']  : 0;
                      }
                      $porcentaje = ($cons_cant_satisf_sede > 0) ? ($cons_cant_satisf_sede*100)/$cons_cant_part_sede : 0;
                      $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                          "valor_actual_numerico"     => $cons_cant_satisf_sede,
                          "valor_actual_ultimo"       => $s->valor_actual_porcentaje,
                          "cant_alum_aula"            => $cons_cant_part_sede,
                          "diff_actual_y_anterior"    => $porcentaje - $s->valor_actual_porcentaje,
                          "audi_ult_id_modi_actual"   => $idPersona,
                          "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                          "audi_ult_pers_modi_actual" => $nombrePersona,
                          "audi_id_modi"              => $idPersona,
                          "audi_fec_modi"             => date('D, d M Y H:i:s'),
                          "audi_pers_modi"            => $nombrePersona);
                      $this->actualizarIndicadorDetalle($dataDetalle, $s->id_indicador_detalle);
              
                      $cons_cant_part_indi   += $cons_cant_part_sede;
                      $cons_cant_satisf_indi += $cons_cant_satisf_sede;
                  }
              } else if($ind->tipo_encuesta == TIPO_ENCUESTA_PERSADM) {
                  foreach($sedes as $s){
                      $cons_cant_part_sede   = 0;
                      $cons_cant_satisf_sede = 0;
                      $estructura = "AND id_sede = ".$s->id_sede;
                      $niveles = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'NIVEL',$estructura);
                      foreach($niveles as $n){
                          $cons_cant_part_nivel   = 0;
                          $cons_cant_satisf_nivel = 0;
                          $estructura = "AND id_sede  = ".$s->id_sede.
                          " AND id_nivel = ".$n->id_nivel;
                          $areas = $this->getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, 'AREA',$estructura);
                          foreach($areas as $a){
                              $res        = $this->queryMongodb($idIndicador, TIPO_ENCUESTA_PERSADM, '"preguntas.id_area" : '.$a->id_area, $year);
                              if($res['ok'] != 1){
                                  throw new Exception('(QUERY MONGO)');
                              }
                              $porcentaje = (count($res['retval']) > 0) ? ($res['retval'][0]['count']*100)/$res['retval'][0]['part'] : 0;
                              $valor_actual_numerico = (count($res['retval']) > 0) ? $res['retval'][0]['count'] : 0;
                              $cant_alum_aula        = (count($res['retval']) > 0) ? $res['retval'][0]['part']  : 0;
              
                              //ACTUALIZAMOS EL AREA CON $res['retval'][0]['count'] Y $res['retval'][0]['part']
                              $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                                  "valor_actual_numerico"     => $valor_actual_numerico,
                                  "valor_actual_ultimo"       => $a->valor_actual_porcentaje,
                                  "cant_alum_aula"            => $cant_alum_aula,
                                  "diff_actual_y_anterior"    => $porcentaje - $a->valor_actual_porcentaje,
                                  "audi_ult_id_modi_actual"   => $idPersona,
                                  "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                                  "audi_ult_pers_modi_actual" => $nombrePersona,
                                  "audi_id_modi"              => $idPersona,
                                  "audi_fec_modi"             => date('D, d M Y H:i:s'),
                                  "audi_pers_modi"            => $nombrePersona);
                              $this->actualizarIndicadorDetalle($dataDetalle, $a->id_indicador_detalle);
              
                              //ACUMULAMOS PARA NIVEL
                              $cons_cant_satisf_nivel += (count($res['retval']) > 0) ? $res['retval'][0]['count'] : 0;
                              $cons_cant_part_nivel   += (count($res['retval']) > 0) ? $res['retval'][0]['part']  : 0;
                          }
                          $porcentaje = ($cons_cant_satisf_nivel > 0) ? ($cons_cant_satisf_nivel*100)/$cons_cant_part_nivel : 0;
                          $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                              "valor_actual_numerico"     => $cons_cant_satisf_nivel,
                              "valor_actual_ultimo"       => $g->valor_actual_porcentaje,
                              "cant_alum_aula"            => $cons_cant_part_grado,
                              "diff_actual_y_anterior"    => $porcentaje - $g->valor_actual_porcentaje,
                              "audi_ult_id_modi_actual"   => $idPersona,
                              "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                              "audi_ult_pers_modi_actual" => $nombrePersona,
                              "audi_id_modi"              => $idPersona,
                              "audi_fec_modi"             => date('D, d M Y H:i:s'),
                              "audi_pers_modi"            => $nombrePersona);
                          $this->actualizarIndicadorDetalle($dataDetalle, $g->id_indicador_detalle);
              
                          $cons_cant_part_sede   += $cons_cant_part_nivel;
                          $cons_cant_satisf_sede += $cons_cant_satisf_nivel;
                      }
                      $porcentaje = ($cons_cant_satisf_sede > 0) ? ($cons_cant_satisf_sede*100)/$cons_cant_part_sede : 0;
                      $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                          "valor_actual_numerico"     => $cons_cant_satisf_sede,
                          "valor_actual_ultimo"       => $s->valor_actual_porcentaje,
                          "cant_alum_aula"            => $cons_cant_part_sede,
                          "diff_actual_y_anterior"    => $porcentaje - $s->valor_actual_porcentaje,
                          "audi_ult_id_modi_actual"   => $idPersona,
                          "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                          "audi_ult_pers_modi_actual" => $nombrePersona,
                          "audi_id_modi"              => $idPersona,
                          "audi_fec_modi"             => date('D, d M Y H:i:s'),
                          "audi_pers_modi"            => $nombrePersona);
                      $this->actualizarIndicadorDetalle($dataDetalle, $s->id_indicador_detalle);
              
                      $cons_cant_part_indi   += $cons_cant_part_sede;
                      $cons_cant_satisf_indi += $cons_cant_satisf_sede;
                  }
              }
              //ACTUALIZAMOS INDICADOR CON $cons_cant_part_indi Y $cons_cant_satisf_indi
              $porcentaje = ($cons_cant_satisf_indi > 0) ? ($cons_cant_satisf_indi*100)/$cons_cant_part_indi : 0;
              $dataDetalle = array("valor_actual_porcentaje"   => $porcentaje,
                  "valor_actual_numerico"     => $cons_cant_satisf_indi,
                  "valor_actual_ultimo"       => $indicador['valor_actual_porcentaje'],
                  "cant_alum_aula"            => $cons_cant_part_indi,
                  "diff_actual_y_anterior"    => $porcentaje - $indicador['valor_actual_porcentaje'],
                  "audi_ult_id_modi_actual"   => $idPersona,
                  "audi_ult_modi_actual"      => date('D, d M Y H:i:s'),
                  "audi_ult_pers_modi_actual" => $nombrePersona,
                  "audi_id_modi"              => $idPersona,
                  "audi_fec_modi"             => date('D, d M Y H:i:s'),
                  "audi_pers_modi"            => $nombrePersona);
              $this->actualizarIndicadorDetalle($dataDetalle, $indicador['id_indicador_detalle']);
              //ACTUALIZAR DE S => N
              if($idIndicador1 == null){
                  $this->actualizarEstadoFrecuenciaMedicion($ind->id_frecuencia);
              }
              /*FIN FOR!!*/
          }
          $this->db->trans_commit();
          $data['error'] = EXIT_SUCCESS;
          $data['msj']   = 'OK';
      } catch(Exception $e) {
          $data['msj'] = $e->getMessage();
          $this->db->trans_rollback();
      }
      return $data;
  }
  
  function getIdIndicadorDetalleFromidIndicadorPapa($idIndicador, $tipoRegi, $estructura = null){
      $sql = "SELECT id_indicador_detalle,
                     id_sede,
                     id_nivel,
                     id_grado,
                     id_aula,
                     id_area,
                     valor_actual_porcentaje
                FROM bsc.indicador_detalle
               WHERE __id_indicador = ?
                 AND tipo_regi      = ? 
                 AND year           = (SELECT EXTRACT(YEAR FROM now()) - 2 ) ".$estructura;
      $result = $this->db->query($sql, array($idIndicador, $tipoRegi));
      if($tipoRegi == 'INDI') {
          return $result->row_array();
      } else {
          return $result->result();
      }
  }
  
  function queryMongodb($idIndicador, $idTipoEncuesta, $estructura, $year){
      $m = new MongoClient(MONGO_CONEXION);
      $db = $m->selectDB(SMILEDU_MONGO);
      $sql = 'db.senc_satisfaccion_encuesta.aggregate([
            	 {$unwind  : "$preguntas"},
            	 {$match   : {"preguntas.id_indicador":'.$idIndicador.',"id_tipo_encuesta":'.$idTipoEncuesta.','.$estructura.', "year" : '.$year.'}},
            	 {$project : {"preguntas.count":-1, "year":-1, "id_tipo_encuesta":-1, "preguntas.cant_participantes":-1}},
            	 {$group   : {_id:{id_pregunta:"$preguntas.id_pregunta",year:"$year", id_tipo_encuesta:"$id_tipo_encuesta"}, count: {$sum: "$preguntas.count"},part: {$sum: "$preguntas.cant_participantes"},
            				  year:{$first:"$year"}}},
            	 {$project : {"id_pregunta":-1, "count":-1,"part":-1,"year":-1,"id_tipo_encuesta":-1,"porcentaje" : { "$multiply" : [100, { $cond: [ { $eq: [ "$part", 0 ] }, 0, {"$divide":["$count", "$part"]} ] } ] }}} ,
            	 {$sort    : {"id_tipo_encuesta":1, "id_pregunta" :1, "year":1}}
            ])';
      $result = $db->execute('return '.$sql.'.toArray()');
      return $result;
  }
  
  function actualizarIndicadorDetalle($dataInd, $idIndicadorDetalle){
      $this->db->where('id_indicador_detalle', $idIndicadorDetalle);
      $this->db->update('bsc.indicador_detalle', $dataInd);
      
      if($this->db->affected_rows() < 1){
          throw new Exception('(MI-010)');
      }
      return EXIT_SUCCESS;
  }
  
  function getAllIndicadoresActualizarMongodb($idIndicador = null){
      $sql = "SELECT id.__id_indicador,
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
                 AND flg_medido     = '".NO_MEDIDO."'
                 AND fecha_medicion = (SELECT now()::date)
                 AND CASE WHEN ? IS NOT NULL THEN id.__id_indicador = ?
                     ELSE 1 = 1 END";
      
      $result = $this->db->query($sql, array($idIndicador, $idIndicador));
      return $result->result();
  }
  
  function getIndicadoresActualizarMongodb($idIndicador){
      $sql = "SELECT i._id_indicador as __id_indicador,
                     id.year,
                     i.tipo_encuesta
                FROM bsc.indicador i,
                     bsc.indicador_detalle id
               WHERE _id_indicador = ?
                 AND i._id_indicador = id.__id_indicador
                 AND id.tipo_regi    = 'INDI'";
      $result = $this->db->query($sql, array($idIndicador));
      return $result->result();
  }
  
  function actualizarEstadoFrecuenciaMedicion($id_frecuencia){
      $data['error']    = EXIT_ERROR;
      $data['msj']      = MSJ_ERROR;
      try{
          $this->db->where('id_frecuencia', $id_frecuencia);
          $this->db->update('bsc.frecuencia_medicion', array("flg_medido" => SI_MEDIDO, "fecha_medido" => date('d M Y H:i:s')));
          if($this->db->affected_rows() == 1){
              $data['error']    = EXIT_SUCCESS;
              $data['msj']      = 'OK';
          }
      } catch (Exception $e){
          $data['msj'] = $e->getMessage();
      } 
      return $data;
  }
  
  function getIndicadoresByCodigo($codigo) {
      $sql = 'SELECT i._id_indicador,
                     i.desc_indicador,
                     i.cod_indi
                FROM bsc.indicador i
               WHERE (UPPER(i.cod_indi) = UPPER(?)
                      OR UPPER(i.desc_indicador) LIKE UPPER(?)) 
                 AND cod_indi IS NOT NULL';
      $result = $this->db->query($sql, array($codigo, '%'.$codigo.'%'));
      return $result->result_array();
  }
  
  function getIndicadoresExcel() {
      $sql = 'SELECT i._id_indicador,
                     (SELECT desc_objetivo FROM bsc.objetivo o WHERE o._id_objetivo = i.__id_objetivo),
                     i._id_tipo_estructura,
                     i.desc_indicador,
                     i.cod_indi
                FROM bsc.indicador i';
      $result = $this->db->query($sql);
      return $result->result_array();
  }
  
  function getIndicadoresByDesc($desc){
      $sql =   "SELECT DISTINCT
                       ind._id_indicador,
                       id.desc_registro,
                       id.valor_meta,
                       id.valor_actual_porcentaje,
                       id.tipo_valor,
                       ob._id_objetivo,
                       ind.__codigo_criterio_efqm,
                       tipo_gauge,
                       ind.cod_indi,
                       CASE WHEN id.id_ppu <> 0 THEN 1
                       ELSE 0
                       END AS ppu,
  
                       (SELECT desc_frecuencia
    			       FROM bsc.frecuencia_medicion
    			       WHERE __id_indicador = ind._id_indicador
    			       ORDER BY fecha_medicion DESC limit 1),
  
                      (SELECT CASE WHEN(ind1.tipo_gauge = '".GAUGE_NORMAL."' OR ind1.tipo_gauge = '".GAUGE_RATIO."' OR ind1.tipo_gauge = '".GAUGE_REDUCCION."')
            			       AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE 101 END))
                               AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            		 	   WHEN ind1.tipo_gauge = '".GAUGE_PUESTO."'
            		 	        AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END))
                                AND (id.valor_actual_porcentaje <= id.valor_meta)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_CERO."'
            			       AND (id.valor_actual_porcentaje <= (CASE WHEN MIN(c.valor_comparativa) <> 0
            					   THEN MIN(c.valor_comparativa) ELSE -1 END))
            		 	        AND (id.valor_actual_porcentaje = 0)
            			   THEN 1
            			   WHEN ind1.tipo_gauge = '".GAUGE_MAXIMO."'
            		 	        AND (id.valor_actual_porcentaje >= (CASE WHEN MAX(c.valor_comparativa) <> 0
            					   THEN MAX(c.valor_comparativa) ELSE id.valor_actual_porcentaje + 1 END ))
                                AND (id.valor_actual_porcentaje >= id.valor_meta)
            			   THEN 1
            			   ELSE 0
            			   END AS dorado
  
            			    FROM bsc.indicador ind1
            			    LEFT JOIN bsc.indicador_x_comparativa ic
            				   ON (ind1._id_indicador = ic.__id_indicador AND ic.flg_acti = '1')
            			    LEFT JOIN bsc.comparativa c
            				   ON c._id_comparativa = ic.__id_comparativa
            				   WHERE ind1._id_indicador = ind._id_indicador
            			    GROUP BY ind1._id_indicador),
  
                       id.diff_actual_y_anterior,
                       id.flg_amarillo,
                       id.orden,
                       id.__id_indicador
  
                   FROM bsc.indicador ind,
                    	   bsc.indicador_detalle id,
                    	   bsc.categoria_x_indicador ci,
                    	   bsc.categoria             c,
                    	   bsc.linea_estrategica le,
                    	   bsc.objetivo ob,
            			       bsc.indicador_responsable ir
                     WHERE id.tipo_regi        = 'INDI'
                     AND   id.flg_acti       = '1'
                       AND   ir.flg_acti       = '1'
                       AND ind._id_indicador         = id.__id_indicador
                	   AND ci.__id_indicador         = ind._id_indicador
                	   AND ci.__id_categoria         = c.id_categoria
                	   AND c. __id_objetivo          = ob._id_objetivo
                	   AND ob.__id_linea_estrategica = le._id_linea_estrategica
            		   AND   ir.__id_indicador = id.__id_indicador      
            		   AND ind.cod_indi IS NOT NULL 
                       AND   (UPPER(UNACCENT(ind.desc_indicador)) LIKE UPPER(UNACCENT(?))
            			      OR UPPER(UNACCENT(ind.cod_indi)) LIKE UPPER(UNACCENT(?)))
                 ORDER BY id.__id_indicador";
  
  
      $result = $this->db->query($sql, array('%'.$desc.'%', '%'.$desc.'%'));
      $data = $result->result();
      return $data;
  }
}