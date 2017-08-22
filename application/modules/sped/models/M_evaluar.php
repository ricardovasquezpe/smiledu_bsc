<?php

class M_evaluar extends  CI_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('m_rubrica');
    }
    
    function getCriteriosRubrica($idRubrica) {
        $sql = "SELECT id_criterio,
                       desc_criterio
                  FROM sped.rubri_crit_indi
                 WHERE id_rubrica = ?
                 GROUP BY id_criterio, desc_criterio
                 ORDER BY id_criterio";
        $result = $this->db->query($sql, $idRubrica);
        return ($result->result());
    }
    
    function getIndicadoresByCriterioRubrica($idEvaluacion, $idRubrica, $idCriterio) {
        $maxValFactor = $this->m_rubrica->getMaxValorByRubricaFactor_Leyenda($idRubrica, $idCriterio);
        $sql = "SELECT ci.id_criterio,
                       ci.id_indicador,
                       ci.desc_indicador,
                       d.valor_indi AS valor,
                       ROUND(((COALESCE(d.valor_indi, 0) * 20 ) / ? )) nota_vigesimal
                  FROM sped.rubri_crit_indi ci LEFT JOIN sped.rubri_crit_indi_deta d ON (    ci.id_rubrica   = d.id_rubrica 
                                                                                         AND ci.id_criterio  = d.id_criterio 
                                                                                         AND ci.id_indicador = d.id_indicador
                                                                                         AND d.id_evaluacion = ?)
                 WHERE ci.id_rubrica  = ?
                   AND ci.id_criterio = ?
                 ORDER BY ci.orden";
        $result = $this->db->query($sql, array($maxValFactor, $idEvaluacion, $idRubrica, $idCriterio));
        return ($result->result());
    }
    
    function getPosiblesValoresCriterio($idRubrica, $idCriterio, $idIndicador) {
        $sql = "SELECT valor,
                       leyenda desc_leyenda,
                       color_radio_button
                  FROM sped.rubrica_valor_leyenda
                 WHERE id_rubrica   = ?
                   AND id_criterio  = ?
                   AND id_indicador = ?
                 GROUP BY valor, leyenda, color_radio_button
                 ORDER BY valor";
        $result = $this->db->query($sql, array($idRubrica, $idCriterio, $idIndicador));
        return $result->result();
    }
    
    function getPosiblesValoresCriterioEvaluar($idEvaluacion, $idRubrica, $idCriterio, $idIndicador) {
        $sql = "SELECT valor,
                       leyenda desc_leyenda,
                       color_radio_button,
                       orden
                  FROM sped.leyenda_x_evaluacion
                 WHERE id_evaluacion = ?
                   AND id_rubrica    = ?
                   AND id_factor     = ?
                   AND id_subfactor  = ?
                   AND flg_acti      = '".FLG_ACTIVO."'
                 GROUP BY valor, leyenda, color_radio_button, orden
                 ORDER BY orden";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idCriterio, $idIndicador));
        return $result->result();
    }
    
    function getOrdenByValor_Leyenda($idEvaluacion, $idRubrica, $idFactor, $idSubFactor, $valor) {
        $sql = "SELECT orden
                  FROM sped.leyenda_x_evaluacion
                 WHERE id_evaluacion = ?
                   AND id_rubrica    = ?
                   AND id_factor     = ?
                   AND id_subfactor  = ?
                   AND valor         = ?";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idFactor, $idSubFactor, $valor));
        if($result->num_rows() == 1) {
            return $result->row()->orden;
        }
        return null;
    }
    
    function getOrdenByValor($idRubrica, $idFactor, $idSubFactor, $valor) {
        $sql = "SELECT orden
                  FROM sped.rubrica_valor_leyenda
                 WHERE id_rubrica   = ?
                   AND id_criterio  = ?
                   AND id_indicador = ?
                   AND valor        = ?";
        $result = $this->db->query($sql, array($idRubrica, $idFactor, $idSubFactor, $valor));
        if($result->num_rows() == 1) {
            return $result->row()->orden;
        }
        return null;
    }
    
    function getCantSubFactoresNoAplica($idEvaluacion, $idFactor) {
        $sql = "SELECT COUNT(1) cnt
                  FROM sped.rubri_crit_indi_deta
                 WHERE id_evaluacion = ?
                   AND id_criterio   = ?
                   AND flg_no_aplica IS NOT NULL";
        $result = $this->db->query($sql, array($idEvaluacion, $idFactor));
        return $result->row()->cnt;
    }
    
    function existeEvaluacionIndicador($idEvaluacion, $idRubrica, $idCriterio, $idIndicador) {
        $sql = "SELECT COUNT(1) cnt
                  FROM sped.rubri_crit_indi_deta
                 WHERE id_evaluacion = ?
                   AND id_rubrica    = ?
                   AND id_criterio   = ?
                   AND id_indicador  = ?";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idCriterio, $idIndicador));
        return $result->row()->cnt;
    }
    
    /*function existeIndicadorValor($nidCritIndi, $idEvaluacion) {
        $sql = "SELECT COUNT(1) cnt
                  FROM evdresu
                 WHERE nidCriterioIndicador = ?
                   AND nidEvaluacion        = ?";
        $db_schoowl = $this->load->database('sped', TRUE);
        $result = $db_schoowl->query($sql, array($nidCritIndi, $idEvaluacion));
        return $result->row()->cnt;
    }
    */
    function getMaxValueCriterio($idRubrica, $idCriterio) {
        $sql = "SELECT MAX(valor_max_criterio) max_val 
                  FROM sped.rubri_crit_indi 
                 WHERE id_rubrica  = ?
                   AND id_criterio = ?";
        $result = $this->db->query($sql, array($idRubrica, $idCriterio));
        if($result->num_rows() == 1) {
            return $result->row()->max_val;
        } else {
            return null;
        }
    }
    
    function registrarValorIndicador($idEvaluacion, $idRubrica, $idCriterio, $idIndicador, $valor) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $exisEva = $this->existeEvaluacionIndicador($idEvaluacion, $idRubrica, $idCriterio, $idIndicador);
            $maxValor = $this->m_rubrica->getMaxValorByRubricaFactor_Leyenda($idRubrica, $idCriterio);
            $ordenValor = null;
            if($valor != VALOR_NO_APLICA) {
                $ordenValor = $this->getOrdenByValor_Leyenda($idEvaluacion, $idRubrica, $idCriterio, $idIndicador, $valor);
            }
            if($exisEva == 0) {//INS
                $dataInsert = array("id_evaluacion" => $idEvaluacion,
                                    "id_rubrica"    => $idRubrica,
                                    "id_criterio"   => $idCriterio,
                                    "id_indicador"  => $idIndicador,
                                    "valor_indi"    => $valor,
                                    "orden_valor"   => $ordenValor);
                if($valor == VALOR_NO_APLICA) {
                    $dataInsert['flg_no_aplica'] = 1;
                }
                $this->db->insert('sped.rubri_crit_indi_deta', $dataInsert);
            } else {//UPT
                $dataUpdate = array("valor_indi" => $valor, "orden_valor" => $ordenValor);
                if($valor == VALOR_NO_APLICA) {
                    $dataUpdate['flg_no_aplica'] = 1;
                }
                $this->db->where('id_evaluacion', $idEvaluacion);
                $this->db->where('id_rubrica'   , $idRubrica);
                $this->db->where('id_criterio'  , $idCriterio);
                $this->db->where('id_indicador' , $idIndicador);
                $this->db->update('sped.rubri_crit_indi_deta', $dataUpdate);
            }
            if($valor == VALOR_NO_APLICA) {
                $this->db->where('id_evaluacion', $idEvaluacion);
                $this->db->where('id_rubrica'   , $idRubrica);
                $this->db->where('id_factor'    , $idCriterio);
                $this->db->where('id_subfactor' , $idIndicador);
                $this->db->update('sped.leyenda_x_evaluacion', array("flg_acti" => FLG_INACTIVO));
                
                //Recalcular valores del factor en leyenda_x_evaluacion
                $this->recalcularValoresLeyendaAux($idEvaluacion, $idRubrica, $idCriterio);
                //Actualizar en rubri_crit_indi_deta los que ya hayan sido insertados
                //1.recorrer indicadores deta
                $subFactUpdate = $this->getSubFactoresToUpdate($idEvaluacion, $idRubrica, $idCriterio);
                foreach ($subFactUpdate as $subF) {
                    $newValor = $this->getNewValor($idEvaluacion, $idRubrica, $idCriterio, $subF['id_indicador'], $subF['orden_valor']);
                    $this->db->where('id_evaluacion', $idEvaluacion);
                    $this->db->where('id_rubrica'   , $idRubrica);
                    $this->db->where('id_criterio'  , $idCriterio);
                    $this->db->where('id_indicador' , $subF['id_indicador']);
                    $this->db->update('sped.rubri_crit_indi_deta', array("valor_indi" => $newValor));
                }
            }
            
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
            //Nota Final temporal
            $data['notaFinal'] = $this->getPuntajeTotalByEvaluacionFicha($idEvaluacion);
            $data['colorGeneral'] = ($data['notaFinal'] <= 10.49) ? 'mdl-color-text--red-500' : (($data['notaFinal'] >= 10.50 && $data['notaFinal'] <= 16.49) ? 'mdl-color-text--amber-500' : 'mdl-color-text--green-500' );
            //
            $data['promedio'] = $this->getPromedioCriterio($idEvaluacion, $idRubrica, $idCriterio);
            
            $cntSubF = count($this->getSubFactoresAplicables($idEvaluacion, $idRubrica, $idCriterio));
            $vigesimalFactor = $data['promedio'] * 20 / ($maxValor * $cntSubF); // //MAXIMO VALOR * CANTIDAD DE INDICADORES EN CRITERIO
            //
            $data['cssPromedio'] = ($vigesimalFactor <= 10.49) ? 'label-danger' : (($vigesimalFactor >= 10.50 && $vigesimalFactor <= 16.49) ? 'label-warning' : 'label-success' );
            $data['terminoFicha'] = $this->checkFichaFinalizada($idRubrica, $idEvaluacion)['actual'];
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getPromedioCriterio($idEvaluacion, $idRubrica, $idCriterio) {
        $sql = "SELECT ROUND( COALESCE(SUM(valor_indi), 0), 1) valor_criterio
                  FROM sped.rubri_crit_indi_deta
                 WHERE id_evaluacion = ?
                   AND id_rubrica    = ?
                   AND id_criterio   = ?
                   AND flg_no_aplica IS NULL";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idCriterio));
        if($result->num_rows() == 1) {
            return $result->row()->valor_criterio;
        } else {
            return null;
        }
    }
    /*
    function checkRubricaFinalizada($idEvaluacion, $idRubrica) {
        $sql = "SELECT COUNT(1) cant
                  FROM evdcrin c LEFT JOIN evdresu r ON (c.nidCriterioIndicador = r.nidCriterioIndicador AND r.nidEvaluacion = ?)
                 WHERE c.nidFicha = ?
                   AND r.valor IS NULL";
        $db_schoowl = $this->load->database('sped', TRUE);
        $result = $db_schoowl->query($sql, array($idEvaluacion, $idRubrica));
        return $result->row()->cant;
    }*/
    
    function recalcularValoresLeyendaAux($idEvaluacion, $idRubrica, $idFactor) {
        $subfactores = $this->getSubFactoresAplicables($idEvaluacion, $idRubrica, $idFactor);

        $pesoFactor = $this->m_rubrica->getPesoFromFactor($idRubrica, $idFactor);
        $maxValor   = (($pesoFactor * 20 / 100)) / count($subfactores);
        $cantValores = $this->m_utils->getById('sped.rubrica', 'cant_valores', 'nid_ficha', $idRubrica, null);
        //1. Recorrer cada subfactor
        foreach ($subfactores as $fact) {
            //1.1 revisar si tiene leyendas
            $leyendas = $this->getPosiblesValoresCriterioEvaluar($idEvaluacion, $idRubrica, $idFactor, $fact['id_indicador']);
            $sumatoria = ($maxValor / ($cantValores - 1) );
            if(count($leyendas) > 0) {
                //1.1.2 Si tiene, recorrer las variables y actualizar sus valores
                $i = 1;
                $valorAnterior = 0;
                foreach ($leyendas as $ley) {
                    $valorFinal = $this->calcularValor($i, $maxValor, $cantValores, $sumatoria, $valorAnterior);
                    $arrayDatosLey = array("valor" => $valorFinal, "color_radio_button" => $this->calcularColorRadioButton($valorFinal, $maxValor) );
                    $this->db->where('id_evaluacion', $idEvaluacion);
                    $this->db->where('id_rubrica'  , $idRubrica);
                    $this->db->where('id_factor'   , $idFactor);
                    $this->db->where('id_subfactor', $fact['id_indicador']);
                    $this->db->where('orden'       , $ley->orden);
                    $this->db->update('sped.leyenda_x_evaluacion', $arrayDatosLey);
                    $i++;
                }
            }
        }
        return EXIT_SUCCESS;
    }
    
    function calcularValor($i, $maxValor, $cantValores, $sumatoria, $valorAnterior) {
        $valorFinal = 0;
        if($i > 1 && $i < $cantValores) {//No es el 1ro ni el ultimo
            $val = ($valorFinal + $sumatoria );
            $valorFinal = $val + $valorAnterior;
        } else if($i == $cantValores) {
            $valorFinal = $maxValor;
        }
        return $valorFinal;
    }
    
    function calcularColorRadioButton($valorFinal, $maxValor) {
        $vigesimal = round( ((20 * $valorFinal) / $maxValor), 2);
        if($vigesimal <= 10.49) {
            return 'radio-danger';
        } else if ($vigesimal >= 10.50 && $vigesimal <= 16.49) {
            return 'radio-warning';
        } else if($vigesimal >= 16.50 && $vigesimal <= 20) {
            return 'radio-success';
        }
    }
    
    function getSubFactoresAplicables($idEvaluacion, $idRubrica, $idFactor) {
        $sql = "SELECT id_indicador
                  FROM sped.rubri_crit_indi i
                 WHERE id_rubrica    = ?
                   AND id_criterio   = ?
                   AND id_indicador NOT IN (SELECT d.id_indicador
                            			      FROM sped.rubri_crit_indi_deta d
                            			     WHERE d.id_evaluacion = ?
                            			       AND d.id_rubrica    = i.id_rubrica
                            			       AND d.id_criterio   = i.id_criterio
                            			       AND d.flg_no_aplica IS NOT NULL)";
        $result = $this->db->query($sql, array($idRubrica, $idFactor, $idEvaluacion));
        return $result->result_array();
    }
    
    function getSubFactoresToUpdate($idEvaluacion, $idRubrica, $idFactor) {
        $sql = "SELECT id_indicador,
                       orden_valor
			      FROM sped.rubri_crit_indi_deta d
			     WHERE id_evaluacion = ?
			       AND id_rubrica    = ?
			       AND id_criterio   = ?
			       AND flg_no_aplica IS NULL";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idFactor));
        return $result->result_array();
    }
    
    function getNewValor($idEvaluacion, $idRubrica, $idFactor, $idSubFactor, $orden) {
        $sql = "SELECT valor
                  FROM sped.leyenda_x_evaluacion
                 WHERE id_evaluacion = ?
                   AND id_rubrica    = ?
                   AND id_factor     = ?
                   AND id_subfactor  = ?
                   AND orden         = ?
                   AND flg_acti      = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idFactor, $idSubFactor, $orden));
        return $result->row()->valor;
    }
    
    /////////////////////////// REACTIVAR SUBFACTOR //////////////////////////////////
    
    function reactivarSubFactor($idEvaluacion, $idRubrica, $idFactor, $idSubFactor) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            //Borrar deta
            $this->db->where('id_evaluacion', $idEvaluacion);
            $this->db->where('id_rubrica'   , $idRubrica);
            $this->db->where('id_criterio'  , $idFactor);
            $this->db->where('id_indicador' , $idSubFactor);
            $this->db->delete('sped.rubri_crit_indi_deta');
            //Reactivar Leyenda
            $this->db->where('id_evaluacion', $idEvaluacion);
            $this->db->where('id_rubrica'   , $idRubrica);
            $this->db->where('id_factor'    , $idFactor);
            $this->db->where('id_subfactor' , $idSubFactor);
            $this->db->update('sped.leyenda_x_evaluacion', array("flg_acti" => FLG_ACTIVO));
            //Recalcular
            $rpta = $this->recalcularValoresLeyendaAux($idEvaluacion, $idRubrica, $idFactor);
            if($rpta != EXIT_SUCCESS) {
                throw new Exception($rpta);
            }
            $subFactUpdate = $this->getSubFactoresToUpdate($idEvaluacion, $idRubrica, $idFactor);
            foreach ($subFactUpdate as $subF) {
                $newValor = $this->getNewValor($idEvaluacion, $idRubrica, $idFactor, $subF['id_indicador'], $subF['orden_valor']);
                $this->db->where('id_evaluacion', $idEvaluacion);
                $this->db->where('id_rubrica'   , $idRubrica);
                $this->db->where('id_criterio'  , $idFactor);
                $this->db->where('id_indicador' , $subF['id_indicador']);
                $this->db->update('sped.rubri_crit_indi_deta', array("valor_indi" => $newValor));
            }
            $data['terminoFicha'] = $this->checkFichaFinalizada($idRubrica, $idEvaluacion)['actual'];
            $data['error'] = $rpta;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function checkFichaFinalizada($idRubrica, $idEvaluacion) {
        $sql = "SELECT todo.rubri_indis total,
                       todo.eva_indis   evaluado,
                       todo.rubri_indis - todo.eva_indis AS actual
                  FROM ( SELECT (SELECT COUNT(1) rubri_indis
                                   FROM sped.rubri_crit_indi
                                  WHERE id_rubrica = ?),
                        (SELECT COUNT(1) eva_indis
                           FROM sped.rubri_crit_indi_deta
                          WHERE id_evaluacion = ?) ) todo";
        $result = $this->db->query($sql, array($idRubrica, $idEvaluacion));
        return $result->row_array();
    }
    
    function getPuntajeTotalByEvaluacionFicha($idEvaluacion) {
        $sql = "SELECT ROUND( COALESCE(SUM(valor_indi), 0), 2) suma
                  FROM sped.rubri_crit_indi_deta
                 WHERE id_evaluacion = ?
                   AND flg_no_aplica IS NULL";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->row()->suma;
    }
    
    function ejecutarEvaluacion($idEvaluacion, $arrayUpdate) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $respuestas       = null;
            $docIndisResul = $this->getResultadosDocentesIndicadores($idEvaluacion);
            foreach($docIndisResul as $row) {
                $respuestas .= '{
                                    "id_criterio"     : '.$row['id_criterio'].',
                                    "id_indicador"    : '.$row['id_indicador'].',
                                    "valor_indi"      : '.$row['valor_indi'].',
                                    "orden_valor"     : '.$row['orden_valor'].',
                                    "valor_vigesimal" : '.$row['valor_vigesimal'].',
                                    "desc_indicador"  : "'._ucwords($row['desc_indicador']).'",
                                    "id_docente"      : '.$row['nid_persona'].',
                                    "docente"         : "'._ucwords($row['nombrecompleto']).'"
                                },';
            }
            $respuestas = rtrim(trim($respuestas), ",");
            $arrayUpdate['respuestas_jsonb']   = '{ "respuestas" : [ '.$respuestas.' ] }';
            $this->db->where('id_evaluacion', $idEvaluacion);
            $this->db->update('sped.evaluacion', $arrayUpdate);
            if($this->db->affected_rows() != 1) {
                throw new Exception('ME-003');
            }
            //$indisResus = $this->getResultadosIndicadores($idEvaluacion);
            /*$docIndisResul = $this->getResultadosDocentesIndicadores($idEvaluacion);
            $m = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            foreach ($docIndisResul as $indi) {
                $msj = $this->insertIndiMongoDB($indi, $idEvaluacion, $db);
                $msj = $this->insertMongoDocIndiPuntajes($idEvaluacion, $indi, $db);
            }
            if($this->db->trans_status() === FALSE) {
                throw new Exception('(ME-002)');
            }*/
            /*$docIndisResul = $this->getResultadosDocentesIndicadores($idEvaluacion);
            foreach ($docIndisResul as $indi){
                $msj = $this->insertMongoDocIndiPuntajes($idEvaluacion,$indi,$db);
            }*/
            /*$docResulGeneral = $this->getDocentePuntajeGeneral($idEvaluacion);
            $msj = $this->insertMongoDocPuntajeGeneral($idEvaluacion, $docResulGeneral, $db);
            $msjUpt = $this->updateMongoEvaluadorSedeArea($idEvaluacion, $db);*/
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = 'Se registr&oacute; la evaluaci&oacute;n';
            //Enviar correo de notificacion del docente evaluado
            $datosCorreo = $this->getDatosAlFinalizar($idEvaluacion);
            if($datosCorreo['destino'] != null) {
                $body = $this->armarBodyCorreoConfirmEvaluacion($datosCorreo);
                __enviarEmail($datosCorreo['destino'], 'Te han evaluado! :D (No responder)', $body);
            } else {
                $data['msj'] = $data['msj'].'. Pero no se envi&oacute; el correo. El docente no tiene un correo registrado.';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getResultadosIndicadores($idEvaluacion) {
        $sql = "SELECT tab.*,
                       CASE WHEN tab.valor_indi >= 0 THEN ROUND((tab.valor_indi * 20 / tab.max_val), 2)
                            ELSE 0 END AS valor_vigesimal
                  FROM (SELECT d.id_rubrica,
                    	       d.id_criterio,
                    	       d.id_indicador,
                    	       d.valor_indi,
                    	       d.desc_indicador,
                    	       (SELECT MAX(valor) AS max_val
                    	          FROM sped.rubrica_valor_leyenda l
                    	         WHERE l.id_rubrica   = d.id_rubrica
                    	           AND l.id_criterio  = d.id_criterio
                    	           AND l.id_indicador = d.id_indicador )
                    	  FROM sped.rubri_crit_indi_deta d
                    	 WHERE d.id_evaluacion = ?) AS tab";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->result();
    }

    function getResultadosDocentesIndicadores($idEvaluacion) {
        $sql = "SELECT rcid.id_rubrica,
            	       rcid.id_criterio,
            	       rcid.id_indicador,
            	       rcid.valor_indi,
                       rcid.orden_valor,
            	       ROUND((rcid.valor_indi * 20 / (SELECT MAX(valor) AS max_val
                                            		    FROM sped.leyenda_x_evaluacion l
                                            		   WHERE l.id_evaluacion = ?
                                                         AND l.id_rubrica    = rcid.id_rubrica
                                            		     AND l.id_factor     = rcid.id_criterio
                                            		     AND l.id_subfactor  = rcid.id_indicador ) ), 2) valor_vigesimal,
                       rcid.desc_indicador,
            	       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombrecompleto,
            	       p.nid_persona
            	  FROM sped.rubri_crit_indi_deta rcid,
            	       sped.evaluacion           e,
            	       main                      m,
            	       persona                   p
            	 WHERE e.id_evaluacion    = ?
            	   AND m.nid_main         = e.id_horario
            	   AND rcid.id_evaluacion = e.id_evaluacion
            	   AND p.nid_persona      = m.nid_persona
                   AND rcid.flg_no_aplica IS NULL";
        $result = $this->db->query($sql, array($idEvaluacion, $idEvaluacion));
        return $result->result_array();
    }
    
    function insertIndiMongoDB($indi, $idEvaluacion, $db) {
        $sqlCount = 'db.rubrica_indi.find({
                    	id_indicador : '.$indi->id_indicador.',
                    	mes 	     : '.date("m").',
                    	year		 : '.date("Y").'
                    }).count()';
        $resultCount = $db->execute('return '.$sqlCount);
        $cond = $resultCount['retval'];
        if($cond == INSERTA){
            $sql = 'db.rubrica_indi.insert(
                   {
                     id_indicador : '.$indi->id_indicador.',
                	 desc_indi    : "'.utf8_encode($indi->desc_indicador).'",
                	 mes          : '.date("m").',
                	 year         : '.date("Y").',
                     array_evas: [{
                        id_evaluacion : '.$idEvaluacion.',
                        id_rubrica    : '.$indi->id_rubrica.',
                        fec_eval      : "'.date("Y-m-d").'",
                		valor         : '.$indi->valor_indi.',
                		nota_vige     : '.$indi->valor_vigesimal.',
                		orden_valor   : '.$indi->orden_valor.',
                		id_factor     : '.$indi->id_criterio.'
                     }]
                   }
                )';
        } else if($cond == ACTUALIZA){
            $sql = 'db.rubrica_indi.update(
                       { id_indicador: '.$indi->id_indicador.' , mes : '.date("m").' , year : '.date("Y").'},
                       { $push: { array_evas: {
                    							id_evaluacion : '.$idEvaluacion.',
                    							id_rubrica    : '.$indi->id_rubrica.',
                    							fec_eval	  : "'.date("Y-m-d").'",
                    							valor		  : '.$indi->valor_indi.',
                    							nota_vige 	  : '.$indi->valor_vigesimal.',
                    							orden_valor   : '.$indi->orden_valor.',
                    							id_factor     : '.$indi->id_criterio.'
                    					      }
                    			} 
                    	}
                    )';
        }
        //Hay ciertas funciones que no tiene el .toArray()
        $result = $db->execute('return '.$sql);
        return $result;
    }
    
    function insertMongoDocIndiPuntajes($idEvaluacion,$docIndisResul,$db){
        $sqlCount = 'db.rubrica_indi_doc.find({
                    	id_indicador : '.$docIndisResul->id_indicador.',
                    	mes 	     : '.date("m").',
                    	year		 : '.date("Y").'
                    }).count()';
        $resultCount = $db->execute('return '.$sqlCount);
        if($resultCount['retval'] == INSERTA){
            $sql = 'db.rubrica_indi_doc.insert(
                    	{
                    		id_indicador : '.$docIndisResul->id_indicador.',
                    		desc_indi    : "'.utf8_encode($docIndisResul->desc_indicador).'",
                    		mes          : '.date("m").',
                    		year         : '.date("Y").',
                    		"array_evas"   :
                    		[
                    			{
                    				id_evaluacion  : '.$idEvaluacion.',
                    				id_rubrica     : '.$docIndisResul->id_rubrica.',
                    				id_docente     : '.$docIndisResul->nid_persona.',
                    				fec_eval       : "'.date("Y-m-d").'",
                    				nombre_docente : "'.utf8_encode($docIndisResul->nombrecompleto).'",
                    				nota_vige      : '.$docIndisResul->valor_vigesimal.',
                    				orden_valor    : '.$docIndisResul->orden_valor.',
                    				id_factor      : '.$docIndisResul->id_criterio.'
                    			}
                    		]
                    	}
                    )';
        }else if($resultCount['retval'] == ACTUALIZA){
            $sql = 'db.rubrica_indi_doc.update(
                       { id_indicador: '.$docIndisResul->id_indicador.' , mes : '.date("m").' , year : '.date("Y").'},
                       { $push: { array_evas: {
                    							id_evaluacion  : '.$idEvaluacion.',
                    							id_rubrica     : '.$docIndisResul->id_rubrica.',
                                				id_docente     : '.$docIndisResul->nid_persona.',
                                				fec_eval       : "'.date("Y-m-d").'",
                                				nombre_docente : "'.utf8_encode($docIndisResul->nombrecompleto).'",
                                				nota_vige      : '.$docIndisResul->valor_vigesimal.',
                                				orden_valor    : '.$docIndisResul->orden_valor.',
                                				id_factor      : '.$docIndisResul->id_criterio.'
                    					      }
                    			} 
                	   }
                    )';  
        }
        $result = $db->execute('return '.$sql);
        return $result;
    }
    
    function getDocentePuntajeGeneral($idEvaluacion){
        $sql = "SELECT CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombrecompleto,
                	   m.nid_persona,
                	   e.id_evaluacion,
                	   e.id_rubrica,
                	   (SELECT ROUND(SUM(valor_indi), 2) AS nota_vigesimal
                	      FROM sped.rubri_crit_indi_deta
                	     WHERE id_evaluacion = e.id_evaluacion
                           AND flg_no_aplica IS NULL)
                  FROM sped.evaluacion e,
                	   persona         p,
                	   main            m
                 WHERE e.id_evaluacion = ?
                   AND e.id_horario    = m.nid_main
                   AND p.nid_persona   = m.nid_persona";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->row_array();
    }
    
    function insertMongoDocPuntajeGeneral($idEvaluacion, $docResulGeneral, $db){
        $sqlCount = 'db.rubrica_docentes.find({
                        	id_docente   : '.$docResulGeneral['nid_persona'].',
                        	mes 	     : '.date("m").',
                        	year		 : '.date("Y").'
                        }).count()';
        $resultCount = $db->execute('return '.$sqlCount);
        if($resultCount['retval'] == INSERTA){
            $sql = 'db.rubrica_docentes.insert(
                    	{
                    		id_docente     : '.$docResulGeneral['nid_persona'].',
                    		nombre_docente : "'.utf8_encode($docResulGeneral['nombrecompleto']).'",
                    		mes            : '.date("m").',
                    		year           : '.date("Y").',
                    		"array_evas"   : 
                    		[
                    			{
                    				id_evaluacion : '.$idEvaluacion.',
                    				nota_vige     : '.$docResulGeneral['nota_vigesimal'].',
                    				fec_eval      : "'.date("Y-m-d").'",
                    				id_rubrica    : '.$docResulGeneral['id_rubrica'].'
                    			}
                    		]
                    	}
                    )';
        } else if($resultCount['retval'] == ACTUALIZA){
            $sql = 'db.rubrica_docentes.update(
                       { id_docente: '.$docResulGeneral['nid_persona'].' , mes : '.date("m").' , year : '.date("Y").'},
                       { $push: { array_evas: {
                    							id_evaluacion : '.$idEvaluacion.',
                                				nota_vige     : '.$docResulGeneral['nota_vigesimal'].',
                            				    fec_eval      : "'.date("Y-m-d").'",
                                				id_rubrica    : '.$docResulGeneral['id_rubrica'].'
                    					      }
                    			} 
                	   }
                    )';
        }
        $result = $db->execute('return '.$sql);
        return $result;
    }
    
    function checkIfPendiente() {
        $sql = "SELECT COUNT(1) cnt
                  FROM sped.evaluacion
                 WHERE id_evaluacion     = ?
                   AND estado_evaluacion IN ('".PENDIENTE."', '".NO_EJECUTADO."')
                   AND id_rubrica        = ? ";
        $result = $this->db->query($sql, array(_getSesion('id_evaluacion'), _getSesion('id_rubrica_eval')));
        if($result->row()->cnt == 1) {
            return true;
        }
        return false;
    }
    
    function insertEvidencia($insertArray) {
        $this->db->insert('sped.evidencia', $insertArray);
        if($this->db->affected_rows() != 1) {
            throw new Exception('ME-001');
        }
        return array('error' => EXIT_SUCCESS, 'msj' => 'Se subi&oacute; el archivo');
    }
    
    function getEvidenciasEvaluacion($idEvaluacion) {
        $sql = "SELECT id_evidencia,
                       ruta_archivo,
                       tipo_archivo,
                       thumbnail_video
                  FROM sped.evidencia
                 WHERE id_evaluacion = ?";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->result();
    }
    
    function validarArchivo($idEvidencia, $idEvaluacion) {
        $sql = "SELECT ruta_archivo,
                       thumbnail_video
                  FROM sped.evidencia
                 WHERE id_evidencia  = ?
                   AND id_evaluacion = ?";
        $result = $this->db->query($sql, array($idEvidencia, $idEvaluacion));
        if($result->num_rows() == 1) {
            return $result->row_array();
        }
        return false;
    }
    
    function borrarEvidencia($idEvidencia) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = NO_ACCION;
        $this->db->where('id_evidencia', $idEvidencia);
        $this->db->delete('sped.evidencia');
        if($this->db->affected_rows() == 1) {
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_DEL;
        }
        return $data;
    }
    
    function getDatosDocenteEvaluado($idEvaluacion) {
        $sql = "SELECT CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ', INITCAP(p.nom_persona) ) docente,
                       CASE WHEN p.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."',p.foto_persona)
                            WHEN p.google_foto  IS NOT NULL THEN p.google_foto
                            ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END AS foto_persona,
                       CONCAT(TO_CHAR(e.fecha_inicio::date, 'DD/MM/YYYY'),' ',TO_CHAR(e.fecha_inicio, 'hh12:mi AM'),' - ',TO_CHAR(e.fecha_fin, 'hh12:mi AM')) fecha,
                       c.desc_curso AS curso,
                       CONCAT(a.desc_aula,' / ',g.abvr,' ',n.abvr,' / ',s.abvr) aula
                  FROM sped.evaluacion e,
                       persona p,
                       main    m,
                       aula    a,
                       sede    s,
                       nivel   n,
                       grado   g,
                       (SELECT id_curso,
                               desc_curso
                          FROM cursos
                        UNION ALL
                        SELECT id_curso_equiv,
                               desc_curso_equiv
                          FROM curso_equivalente) AS c
                    WHERE e.id_evaluacion = ?
                      AND e.id_horario    = m.nid_main
                      AND m.nid_persona   = p.nid_persona
                      AND m.nid_curso     = c.id_curso
                      AND m.nid_aula      = a.nid_aula
                      AND a.nid_sede      = s.nid_sede
                      AND a.nid_grado     = g.nid_grado
                      AND a.nid_nivel     = n.nid_nivel";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->row_array();
    }
    
    function grabarTema($tema, $idEvaluacion) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = NO_ACCION;
        $this->db->where('id_evaluacion', $idEvaluacion);
        $this->db->update('sped.evaluacion', array('tema' => $tema));
        if($this->db->affected_rows() == 1) {
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_DEL;
        }
        return $data;
    }
    
    function getDatosAlFinalizar($idEvaluacion) {
        $sql = "SELECT CASE WHEN p.correo_inst IS NOT NULL THEN p.correo_inst
                            WHEN p.correo_admi IS NOT NULL THEN p.correo_admi
                            ELSE NULL END AS destino,
                       CONCAT(INITCAP(SPLIT_PART(nom_persona, ' ', 1)),' ',ape_pate_pers,' ',SUBSTRING(ape_mate_pers,1, 1),'.' ) AS docente,
                       INITCAP(c.desc_curso) AS curso,
                       CONCAT(INITCAP(a.desc_aula)) aula,
                       CONCAT(g.abvr,' ',n.abvr,' / ',s.abvr) grado_nivel_sede,
                       (SELECT CASE WHEN p2.correo_inst IS NOT NULL THEN p2.correo_inst
                                    WHEN p2.correo_admi IS NOT NULL THEN p2.correo_admi
                                    ELSE NULL END FROM persona p2 WHERE p2.nid_persona = e.id_evaluador) remitente,
                       (SELECT CONCAT(p2.ape_pate_pers,' ', p2.ape_mate_pers,', ', INITCAP(p2.nom_persona)) FROM persona p2 WHERE p2.nid_persona = e.id_evaluador) evaluador,
                       TO_CHAR(e.fecha_evaluacion::date, 'DD/MM/YYYY') fec_eva,
                       CONCAT(TO_CHAR(e.fecha_inicio::date, 'DD/MM/YYYY'),' ',TO_CHAR(e.fecha_inicio, 'hh12:mi AM'),' - ',TO_CHAR(e.fecha_fin, 'hh12:mi AM')) fec_visita,
                       e.id_evaluacion,
                       p.usuario
                  FROM sped.evaluacion e,
                       persona p,
                       main    m,
                       aula    a,
                       sede    s,
                       nivel   n,
                       grado   g,
                       (SELECT id_curso,
                               desc_curso
                          FROM cursos
                        UNION ALL
                        SELECT id_curso_equiv,
                               desc_curso_equiv
                          FROM curso_equivalente) AS c
                 WHERE id_evaluacion = ?
                   AND e.id_horario  = m.nid_main
                   AND m.nid_persona = p.nid_persona
                   AND m.nid_curso   = c.id_curso
                   AND m.nid_aula    = a.nid_aula
                   AND a.nid_sede    = s.nid_sede
                   AND a.nid_grado   = g.nid_grado
                   AND a.nid_nivel   = n.nid_nivel";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->row_array();
    }
    
    function armarBodyCorreoConfirmEvaluacion($datosCorreo) {
        $nombreRol = $this->m_utils->getById('rol', 'desc_rol', 'nid_rol', _getSesion(SPED_ROL_SESS));
        $html = '<table style="border-collapse:collapse;width:500px" width="500" cellpadding="0" cellspacing="0">
                     <tbody>
                         <tr>
                             <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                             <td align="center" valign="middle" style="text-align:center;vertical-align:middle;background-color:#000000">
                                 <table style="width:100%;border-collapse:collapse">
                                     <tbody>
                                         <tr>
                                             <td width="140px" style="width:140px;padding: 5px" align="left">
                                                 <img alt="Smiledu" src="http://181.224.241.203/schoowl/public/img/logo-smiledu.png" border="0" style="border:0px;max-height:40px; float:left;" class="CToWUd">
                                                 <label style="color:#ffffff;font-size:24px; padding-top: 5px; padding-left: 5px; float:left;">Smiledu</label>
                                             </td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </td>
                             <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                         </tr>
                         <tr>
                             <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 0px 5px">&nbsp;</td>
                             <td align="center" valign="middle" style="text-align:center;vertical-align:top;background-color:#fafafa;font-size:14px;color:#333333">
                                 <br>
                                 <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;font-size:19px">
                                     <p>Hola <b>'.$datosCorreo['docente'].'</b></p>
                                 </div>
                                 <br>
                                     Ud. fue evaluad@ el día '.$datosCorreo['fec_eva'].' por <u>'._getSesion('nombre_abvr').'</u>  ('.$nombreRol.')
                                 <br><br>
                                 <div>
                                     <table style="border-collapse:collapse;width:460px">
                                         <tbody>
                                             <tr>
                                                 <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:50%" valign="middle">
                                                     <b>Curso</b>
                                                     <br>
                                                     <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%">'.$datosCorreo['curso'].'</div>
                                                 </td>
                                                 <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:50%" valign="middle">
                                                     <b>Aula</b>
                                                     <br>
                                                     <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%">'.$datosCorreo['aula'].'<br>'.$datosCorreo['grado_nivel_sede'].'</div>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 <br>
                             </div>
                             <br>
                             <div style="font-size:11px">
                                 <p>Puede consultar sus resultados en la opción de "Consultar Evaluaciones" en el Módulo de Evaluación de docentes. ID: '.$datosCorreo['id_evaluacion'].'</p>
                                 <p>Ingresa a la plataforma <a href="'.RUTA_SMILEDU.'" target="_blank">SmilEDU</a>, con tu usuario: '.$datosCorreo['usuario'].' y tu respectiva clave. </p>
                             </div>
                         </td>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 5px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;font-size:10px;color:#888888"><br>
                             Si no tienes tu usuario/clave obtenlo en el link de "Recuperar contraseña" en la pantalla de inicio o comunícate con el Administrador de la plataforma
                         </td>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                 </tbody>
             </table>';
        return '<div align="center" style="text-align:center;"><center>'.$html.'</center></div>';
    }
    
    /*function actualizarEvaluacion($idEvaluacion, $evidencias) {
        $this->db->where('id_evaluacion', $idEvaluacion);
        $this->db->update('evaluacion', array("evidencias_array" => $evidencias));
    }*/
    
    function updateMongoEvaluadorSedeArea($idEvaluacion,$db){
        $result = array();
        try{
            $sql = 'db.rubrica_evaluadores.update(
                		{id_evaluador : '._getSesion('id_persona').', "array_evas.id_evaluacion" : '.$idEvaluacion.' },
                		{$set : {"array_evas.$.estado" : "'.EJECUTADO.'", "array_evas.$.fec_eval" : "'.date('Y-m-d').'"}},
                		{ upsert: true }
                )';
            $result = $db->execute('return '.$sql);
        } catch(Exception $e){
            $result = array();
        }
        return $result;
    }
    
    function getListaMongoUpt() {
        $sql = "SELECT e.id_evaluador,
                       e.id_evaluacion,
                       e.estado_evaluacion
                  FROM sped.evaluacion e
                 WHERE e.flg_mongo_upt = '".FLG_ACTIVO."'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function updateFlgMongo($idEvaluacion) {
        $this->db->where('id_evaluacion' , $idEvaluacion);
        $this->db->update('sped.evaluacion', array('flg_mongo_upt' => null));
        return true;
    }
}