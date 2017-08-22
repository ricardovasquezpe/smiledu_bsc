<?php
class M_rubrica extends CI_Model{

    function __construct() {
        parent::__construct();
        /*$this->load->model('m_utils');
        $this->load->model('m_evaluar');*/
    }

    function getCriteriosByRubrica($idRubrica) {
        $sql = "SELECT rc.id_criterio AS nid_criterio,
                       c.desc_criterio,
                       rc.peso_porcentaje
                  FROM sped.rubricar_x_criterio rc,
                       sped.criterio            c
                 WHERE rc.id_rubrica  = ?
                   AND rc.id_criterio = c.nid_criterio
                ORDER BY rc.orden";
        $result  = $this->db->query($sql, $idRubrica);
        return ($result->result());
    }
   
    function getAllCriteriosByFicha($ficha) {                                                                          
        $sql = "SELECT c.nid_criterio,
                       c.desc_criterio,
                       rci.id_rubrica
                  FROM sped.criterio c LEFT JOIN sped.rubri_crit_indi rci ON (c.nid_criterio = rci.id_criterio AND rci.id_rubrica = ?)
                GROUP BY c.nid_criterio, 
                         c.desc_criterio,
                         rci.id_rubrica
                ORDER BY c.nid_criterio";
        $result  = $this->db->query($sql,$ficha);
        return ($result->result());
    } 
    
    function insertDeleteRubCriInd($idRubrica,$idCriterio,$idIndicador,$descrip,$flg,$cantInd,$cantval,$valMax) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            if ($flg == 1) {
                 $arrayDatos = array("id_rubrica"   => $idRubrica,
                                     "id_criterio"  => $idCriterio,
                                     "id_indicador" => $idIndicador,
                                     "desc_criterio"=> $descrip,
                                     "cant_indis"   => $cantInd,
                                     "cant_vals"    => $cantval,
                                     "valor_max_criterio" => 0);
                 $this->db->insert('sped.rubri_crit_indi', $arrayDatos);
            } else {
                 $arrayDatos = array("id_rubrica"   => $idRubrica,
                                     "id_criterio"  => $idCriterio);
                 $this->db->delete('sped.rubrica_valor_leyenda', $arrayDatos);
                 $this->db->delete('sped.rubri_crit_indi', $arrayDatos);
            }
            if ( $this->db->trans_status() === FALSE) {
                 $data['msj'] = '(MA-001)';
                 $this->db->trans_rollback();
            } else {
                 $data['error'] = EXIT_SUCCESS;
                 $data['msj'] = MSJ_INS;
                 $data['cabecera'] = CABE_INS;
                 $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
             $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getBuscarDescripIdCriterio($idCriterio){
        $sql = "SELECT desc_criterio 
                  FROM sped.criterio
                 WHERE nid_criterio = ?";
        $result = $this->db->query($sql,$idCriterio);
        return $result->row()->desc_criterio;
    }
    
    //MODAL POPUP DEL DETALLE//
    function getAllIndicadoresByCriterio($nidFicha) {
        $sql = " SELECT i.nid_indicador,
                	    i.desc_indicador,
                	    rci.id_indicador AS flg
                   FROM sped.indicador i LEFT JOIN sped.rubri_crit_indi rci ON (i.nid_indicador = rci.id_indicador AND rci.id_rubrica = ?)
                  WHERE rci.id_indicador IS NULL
                    AND i.nid_indicador != 0
                  ORDER BY i.nid_indicador";
        $result  = $this->db->query($sql,$nidFicha);
        return ($result->result());
    }
 
    function buscarIdIndicador($idRubrica,$idCriterio){
        $sql = "SELECT count(1) count
                  FROM sped.rubri_crit_indi
                 WHERE id_rubrica = ?
                   AND id_criterio = ?
                   AND id_indicador != 0";
        $result = $this->db->query($sql,array($idRubrica,$idCriterio));
        return $result->row()->count;
    }
    
    function buscarDescripIdIndicador($idIndicador){
        $sql = "SELECT desc_indicador 
                  FROM sped.indicador
                 WHERE nid_indicador = ?";
        $result = $this->db->query($sql,$idIndicador);
        return $result->row()->desc_indicador;
    }

    function countIdRubIdCritBYRubValLey($idRubrica,$idCriterio){
        $sql = "SELECT count(1) count
                  FROM sped.rubrica_valor_leyenda
                 WHERE id_criterio = ?
                   AND id_rubrica  = ?";
        $result = $this->db->query($sql,array($idCriterio,$idRubrica));
        return $result->row()->count;       
    }

    function countIdRubIdCritIdindiBYRci($idRubrica,$idCriterio,$idindi) {
        $sql = "SELECT count(1) count
                  FROM sped.rubri_crit_indi
                 WHERE id_criterio = ?
                   AND id_rubrica  = ?
                   AND id_indicador= ?";
        $result = $this->db->query($sql,array($idCriterio,$idRubrica,$idindi));
        return $result->row()->count;       
    }
    
    function insertarFactores_x_SubFactores($arrayGeneral, $idRubrica, $idFactor) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $this->db->trans_begin();
        try {
            foreach ($arrayGeneral as $dat) {
                $this->db->insert('sped.rubri_crit_indi', $dat);
            }
            $rpta = $this->recalcularValoresLeyendas($idRubrica, $idFactor);
            if($rpta !== EXIT_SUCCESS) {
                throw new Exception($rpta);
            }
            $data['error'] = $rpta;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function recalcularValoresLeyendas($idRubrica, $idFactor) {
        //inserto los indicadores y ahora recalcular los valores
        $maxValor = $this->getPeso_CantidadSubFactores($idRubrica, $idFactor);
        if($maxValor == 0) {
            //throw new Exception('Debe poner el peso del Factor');
            return 'Debe poner el peso del Factor';
        }
        $cantValores = $this->m_utils->getById('sped.rubrica', 'cant_valores', 'nid_ficha', $idRubrica, null);
        if($cantValores == null || $cantValores == 0) {
            //throw new Exception('Debe asignar la cantidad de valores');
            return 'Debe asignar la cantidad de niveles';
        }
        //1. Recorrer cada subfactor
        $subfactores = $this->getIndicadores($idRubrica, $idFactor);
        foreach ($subfactores as $fact) {
            //1.1 revisar si tiene leyendas
            $leyendas = $this->getLeyendaByRubValLey($idFactor, $idRubrica, $fact->id_indicador);
            $sumatoria = ($maxValor / ($cantValores - 1) );
            if(count($leyendas) > 0) {
                //1.1.2 Si tiene, recorrer las variables y actualizar sus valores
                $i = 1;
                $valorAnterior = 0;
                foreach ($leyendas as $ley) {
                    $valorFinal = $this->calcularValor($i, $maxValor, $cantValores, $sumatoria, $valorAnterior);
                    $arrayDatosLey = array("valor" => $valorFinal, "color_radio_button" => $this->calcularColorRadioButton($valorFinal, $maxValor) );

                    $this->db->where('id_rubrica'  , $idRubrica);
                    $this->db->where('id_criterio' , $idFactor);
                    $this->db->where('id_indicador', $fact->id_indicador);
                    $this->db->where('orden' , $i);
                    $this->db->update('sped.rubrica_valor_leyenda', $arrayDatosLey);
                    $i++;
                }
            } else if(count($leyendas) == 0) {
                //1.1.1 Si no tiene, recorrer $cantValores e insertar
                $valorAnterior = 0;
                for ($i = 1; $i <= $cantValores; $i++ ) {
                    $valorFinal = $this->calcularValor($i, $maxValor, $cantValores, $sumatoria, $valorAnterior);
                    $valorAnterior = $valorFinal;
                    $arrayDatosLey = array(
                        "id_rubrica"         => $idRubrica,
                        "id_criterio"        => $idFactor,
                        "id_indicador"       => $fact->id_indicador,
                        "valor"              => $valorFinal,
                        "leyenda"            => '...pendiente...',
                        "orden"              => $i,
                        "color_radio_button" => $this->calcularColorRadioButton($valorFinal, $maxValor),
                    );
                    $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatosLey);
                }
            }
        }
        return EXIT_SUCCESS;
    }
    
    function calcularValor($i, $maxValor, $cantValores, $sumatoria, $valorAnterior) {
        $valorFinal = 0;
        if($i > 1 && $i < $cantValores) {//No es el 1ro ni el ultimo
            $val = ($valorFinal + $sumatoria );//( floor(($valorFinal + $sumatoria ) * 100 ) / 100 );
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
    
    function getPeso_CantidadSubFactores($idRubrica, $idFactor) {
        $sql = "SELECT CASE WHEN tabla.cant_subfac > 0 THEN ((tabla.peso_porcentaje * 20 / 100) / tabla.cant_subfac)
                            ELSE 0 END AS max_valor
                  FROM (SELECT peso_porcentaje,
                               (SELECT COUNT(1)
                            	  FROM sped.rubri_crit_indi
                            	 WHERE id_rubrica    = ?
                            	   AND id_criterio   = ?
                                   AND id_indicador <> 0) AS cant_subfac
                          FROM sped.rubricar_x_criterio
                         WHERE id_rubrica  = ?
                           AND id_criterio = ? ) tabla";
        $result = $this->db->query($sql, array($idRubrica, $idFactor, $idRubrica, $idFactor));
        if($result->num_rows() == 1) {
            return $result->row()->max_valor;
        }
        return null;
    }
    
    function getPesoFromFactor($idRubrica, $idFactor) {
        $sql = "SELECT peso_porcentaje
                  FROM sped.rubricar_x_criterio
                 WHERE id_rubrica  = ?
                   AND id_criterio = ?";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        if($result->num_rows() == 1) {
            return $result->row()->peso_porcentaje;
        }
        return null;
    }
    
    function getNextOrdenFactor($idRubrica) {
        $sql = "SELECT COALESCE(MAX(orden), 0) + 1 next_orden_factor
                  FROM sped.rubricar_x_criterio
                 WHERE id_rubrica = ?";
        $result  = $this->db->query($sql, array($idRubrica));
        return $result->row()->next_orden_factor;
    }
    
    function getNextOrdenSubFactor($idRubrica, $idFactor) {
        $sql = "SELECT COALESCE(MAX(orden), 0) + 1 next_orden_subfactor
                  FROM sped.rubri_crit_indi
                 WHERE id_rubrica  = ?
                   AND id_criterio = ?";
        $result  = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->row()->next_orden_subfactor;
    }
    
    function updateDeleteIndicadores($arrayGeneral, $flg, $valor)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            foreach ($arrayGeneral as $dato) {
                $condicion = $this->countIdRubIdCritBYRubValLey($dato['id_rubrica'], $dato['id_criterio']); 
                if ($condicion == 0) {
                    if ($flg == 1) {
                        if ($dato['ACCION'] == 0) {
                            unset($dato['ACCION']);
                            $arrayDatos = array("id_indicador"   => $dato['id_indicador'],
                                                "desc_indicador" => $dato['desc_indicador'],
                                                "cant_indis"     => $dato['cant_indis'],
                                                "cant_vals"      => $dato['cant_vals'],
                                                "valor_max_criterio" => $dato['valor_max_criterio']);
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);
                            $this->db->where("id_indicador", 0);
                            $this->db->update('sped.rubri_crit_indi', $arrayDatos);                            
                            for ($i = 1; $valor >= $i; $i ++) {
                                $val = $i;
                                $arrayDatosLey = array("id_rubrica"    => $dato['id_rubrica'],
                                                        "id_criterio"  => $dato['id_criterio'],
                                                        "id_indicador" => $dato['id_indicador'],
                                                        "valor"        => $val,
                                                        "leyenda"      => '');
                                $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatosLey);
                            }
                            $existeInIndi = $this->countIdRubIdCritIdindiBYRci($dato['id_rubrica'], $dato['id_criterio'],$dato['id_indicador']);                          
                            if($existeInIndi == 0){                           
                            $this->db->insert('rubri_crit_indi', $dato);  
                                for ($i = 1; $valor >= $i; $i ++) {
                                    $val = $i;
                                    $arrayDatosLey = array("id_rubrica"    => $dato['id_rubrica'],
                                                            "id_criterio"  => $dato['id_criterio'],
                                                            "id_indicador" => $dato['id_indicador'],
                                                            "valor"        => $val,
                                                            "leyenda"      => '');
                                    $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatosLey);
                                }
                            }
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);;
                            $this->db->update('sped.rubri_crit_indi', array("cant_indis" => $dato['cant_indis'],"cant_vals" => $dato['cant_vals']));
     
                        } else {
                            unset($dato['ACCION']);
                            $this->db->insert('sped.rubri_crit_indi', $dato);
                            for ($i = 1; $valor >= $i; $i ++) {
                                $val = $i;
                                $arrayDatosLey = array("id_rubrica"    => $dato['id_rubrica'],
                                                        "id_criterio"  => $dato['id_criterio'],
                                                        "id_indicador" => $dato['id_indicador'],
                                                        "valor"        => $val,
                                                        "leyenda"      => '');
                                $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatosLey);
                            }
                            $arrayDatos = array("cant_indis"     => $dato['cant_indis'],
                                                "cant_vals"=> $dato['cant_vals']);
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);;
                            $this->db->update('sped.rubri_crit_indi', $arrayDatos);                                                   
                        }
                    } else {
                        $this->db->where("id_rubrica", $dato['id_rubrica']);
                        $this->db->where("id_criterio", $dato['id_criterio']);
                        $this->db->delete('sped.rubri_crit_indi', $dato);
                        $cont = $cont + $this->db->affected_rows();
                    }
                } else {
                    if ($flg == 1) {                       
                        if ($dato['ACCION'] != 0) {
                            unset($dato['ACCION']);
                            $arrayDatos = array("id_rubrica"     => $dato['id_rubrica'],
                                                "id_criterio"    => $dato['id_criterio'],
                                                "desc_criterio"  => $dato['desc_criterio'],
                                                "id_indicador"   => $dato['id_indicador'],
                                                "desc_indicador" => $dato['desc_indicador'],
                                                "cant_indis"     => $dato['cant_indis'],
                                                "cant_vals"      => $dato['cant_vals'],
                                                "valor_max_criterio" => 0);
                            $this->db->insert('sped.rubri_crit_indi', $arrayDatos);                   
                            for ($i = 1; $valor >= $i; $i ++) {
                                $val = $i;
                                $arrayDatosLey = array("id_rubrica"    => $dato['id_rubrica'],
                                                        "id_criterio"  => $dato['id_criterio'],
                                                        "id_indicador" => $dato['id_indicador'],
                                                        "valor"        => $val,
                                                        "leyenda"      => '');
                            $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatosLey);
                            }
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);
                            $arrayDatos = array("valor_max_criterio"  => $dato['valor_max_criterio']);
                            $this->db->update('sped.rubri_crit_indi',$arrayDatos);
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);
                            $this->db->update('sped.rubri_crit_indi',array("cant_indis" => $dato['cant_indis'],"cant_vals"=> $dato['cant_vals']));
                        }else{
                            unset($dato['ACCION']);
                            $this->db->insert('sped.rubri_crit_indi', $dato);
                            for ($i = 1; $valor >= $i; $i ++) {
                                $val = $i;
                                $arrayDatosLey = array("id_rubrica"    => $dato['id_rubrica'],
                                                        "id_criterio"  => $dato['id_criterio'],
                                                        "id_indicador" => $dato['id_indicador'],
                                                        "valor"        => $val,
                                                        "leyenda"      => '');
                                $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatosLey);
                            }
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);
                            $arrayDatos = array("valor_max_criterio"  => $dato['valor_max_criterio']);
                            $this->db->update('sped.rubri_crit_indi',$arrayDatos);
                            $this->db->where("id_rubrica", $dato['id_rubrica']);
                            $this->db->where("id_criterio", $dato['id_criterio']);
                            $this->db->update('sped.rubri_crit_indi',array("cant_indis" => $dato['cant_indis'],"cant_vals"=> $dato['cant_vals']));
                        }
                    } else {
                        unset($dato['ACCION']);
                        $this->db->where("id_rubrica", $dato['id_rubrica']);
                        $this->db->where("id_criterio", $dato['id_criterio']);
                        $this->db->delete('sped.rubrica_valor_leyenda', $dato);
                        $this->db->delete('sped.rubri_crit_indi', $dato);
                        $cont = $cont + $this->db->affected_rows();
                    }
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj'] = '(MR-001)';
                $this->db->trans_rollback();
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = MSJ_INS;
                $data['cabecera'] = CABE_INS;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    //FIN MODAL POPUP DEL DETALLE//
    
    function getIndicadores($idRubrica, $idCriterio) {
        $sql = "SELECT i.desc_indicador,
            		   rci.id_rubrica,
			           rci.id_criterio,
                       rci.id_indicador            
                  FROM sped.rubri_crit_indi rci,
			           sped.indicador  i
                 WHERE rci.id_rubrica   = ?
	               AND rci.id_criterio  = ?
	               AND rci.id_indicador = i.nid_indicador
                   AND rci.id_indicador <> 0
                 ORDER BY rci.orden";
        $result  = $this->db->query($sql, array($idRubrica, $idCriterio));
        return ($result->result());
    }
    
    function getIndicadoresParaAsignar($idRubrica, $idCriterio) {
        $sql = "SELECT i.desc_indicador,
                       i.nid_indicador
                  FROM sped.indicador i
                 WHERE i.nid_indicador NOT IN (SELECT rci.id_indicador
                                				 FROM sped.rubri_crit_indi rci,
                                				      sped.indicador  i
                                				WHERE rci.id_rubrica    = ?
                                				  --AND rci.id_criterio = ?
                                				  AND rci.id_indicador  = i.nid_indicador
                                				  AND rci.id_indicador != 0)";
        $result = $this->db->query($sql, array($idRubrica, $idCriterio));
        return ($result->result_array());
    }
    
    function getFactoresParaAsignar($idRubrica) {
        $sql = "SELECT i.desc_criterio,
                       i.nid_criterio
                  FROM sped.criterio i
                 WHERE i.nid_criterio NOT IN (SELECT rc.id_criterio
                                                FROM sped.rubricar_x_criterio rc
                                               WHERE rc.id_rubrica  = ?)";
        $result = $this->db->query($sql, array($idRubrica));
        return ($result->result_array());
    }
    
    function insertar_factores_a_rubrica($arrayGeneral) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            foreach ($arrayGeneral as $dat) {
                $this->db->insert('sped.rubricar_x_criterio', $dat);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function borrarFactorModelo($idRubrica, $idFactor) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {
            $existEvas = $this->verificarSiHayEvaluaciones_Factor($idRubrica, $idFactor);
            if($existEvas > 0) {
                throw new Exception('(MR-008)');
            }
            //BORRAR LEYENDAS
            $arrayDatosRvl = array("id_rubrica"   => $idRubrica,
                                   "id_criterio"  => $idFactor);
            $this->db->delete('sped.rubrica_valor_leyenda', $arrayDatosRvl);
            
            //ACTUALIZAR EL ORDEN DE LOS OTROS FACTORES
            $factToUptOrdn = $this->getFactoresToUpdateOrdenAfterBorrar($idRubrica, $idFactor);
            foreach ($factToUptOrdn as $fact) {
                $this->db->where('id_rubrica' , $idRubrica);
                $this->db->where('id_criterio', $fact['id_criterio']);
                $this->db->update('sped.rubricar_x_criterio' , array("orden" => ($fact['orden'] - 1) ));
            }
            //BORRAR SUBFACTORES
            $this->db->delete('sped.rubri_crit_indi', $arrayDatosRvl);
            
            //BORRAR FACTOR
            $this->db->delete('sped.rubricar_x_criterio', $arrayDatosRvl);
            
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-007)');
            }
            $data['suma_pesos'] = $this->getSumaPesosByRubrica($idRubrica);
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_DEL;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getBorrarIndicador($idFactor, $idRubrica, $idSubFactor) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {
            //VALIDAR SI HAY EVALUACIONES
            $existEvas = $this->verificarSiHayEvaluaciones_SubFactor($idRubrica, $idFactor, $idSubFactor);
            if($existEvas > 0) {
                throw new Exception('(MR-002)');
            }
            //BORRAR LEYENDAS
            $arrayDatosRvl = array("id_rubrica"   => $idRubrica,
                                   "id_criterio"  => $idFactor,
                                   "id_indicador" => $idSubFactor);
            $this->db->delete('sped.rubrica_valor_leyenda', $arrayDatosRvl);
            
            //TRAER LOS MAYORES
            $subFactUptOrdn = $this->getSubFactoresToUpdateOrdenAfterBorrar($idRubrica, $idFactor, $idSubFactor);
            //FOREACH Y UPDATE - 1
            foreach ($subFactUptOrdn as $subf) {
                $this->db->where('id_rubrica'  , $idRubrica);
                $this->db->where('id_criterio' , $idFactor);
                $this->db->where('id_indicador', $subf['id_indicador']);
                $this->db->update('sped.rubri_crit_indi', array("orden" => ($subf['orden'] - 1) ));
            }
            //BORRAR SUBFACTOR
            $this->db->delete('sped.rubri_crit_indi', $arrayDatosRvl);
            
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-007)');
            }
            $rpta = $this->recalcularValoresLeyendas($idRubrica, $idFactor);
            if($rpta !== EXIT_SUCCESS) {
                $data['msj'] = $rpta;
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_DEL;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function cambiarPesoFactor($idRubrica, $idFactor, $peso) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {
            $this->db->where('id_rubrica' , $idRubrica);
            $this->db->where('id_criterio', $idFactor);
            $this->db->update('sped.rubricar_x_criterio', array('peso_porcentaje' => $peso) );

            $rpta = $this->recalcularValoresLeyendas($idRubrica, $idFactor);
            if($rpta == EXIT_SUCCESS) {
                $estadoRubrica = $this->m_utils->getById('sped.rubrica', 'flg_acti', 'nid_ficha', $idRubrica);
                if($estadoRubrica == ACTIVO_) {//FLG DE RECALCULAR RESULTADOS
                    $this->db->where('nid_ficha', $idRubrica);
                    $this->db->update('sped.rubrica', array('flg_acti' => RUBRICA_ESTADO_POR_REACTIVAR) );
                }
            }
            $data['suma_pesos'] = $this->getSumaPesosByRubrica($idRubrica);
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_UPT;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function cambiarCantidadValoresRubrica($idRubrica, $cantValores) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {
            $this->db->where('nid_ficha' , $idRubrica);
            $this->db->update('sped.rubrica', array('cant_valores' => $cantValores) );
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-010)');
            }
            $this->modificarCrearLeyendas($idRubrica, $cantValores);
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_UPT;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function modificarCrearLeyendas($idRubrica, $cantValores) {
        $factores = $this->getCriteriosByRubrica($idRubrica);
        foreach ($factores as $fact) {
            $subFactores = $this->getSubFactorLeyenda($idRubrica, $fact->nid_criterio);
            foreach ($subFactores as $subFac) {
                $res = $subFac['max_orden'] - $cantValores;
                $operacion = null;
                $orden     = null;
                if($res > 0) {//BORRAR LEYENDAS
                    $operacion = 'DELETE';
                    $orden = $cantValores + 1;//Orden a modificar o borrar
                } else if($res < 0) {//INSERTAR LEYENDAS
                    $operacion = 'INSERT';
                    $orden = $subFac['max_orden'] + 1;
                }
                
                ////////// EJECUTAR ////////////
                if($operacion == null) {
                    throw new Exception('No hay cambios que registrar');
                }
                $res = abs($res);
                for ($i = 0; $i < $res; $i++) {
                    $orden = $orden + $i;
                    if($operacion == 'DELETE') {
                        
                        $this->db->where('id_rubrica' , $idRubrica);
                        $this->db->where('id_criterio', $fact->nid_criterio);
                        $this->db->where('id_indicador', $subFac['id_subfactor']);
                        $this->db->where('orden' , $orden);
                        
                        $this->db->delete('sped.rubrica_valor_leyenda');
                    } else if($operacion == 'INSERT') {
                        $arrayDatos = array(
                            "id_rubrica"   => $idRubrica,
                            "id_criterio"  => $fact->nid_criterio,
                            "id_indicador" => $subFac['id_subfactor'],
                            "valor"        => -1,
                            "leyenda"      => '',
                            "orden"        => $orden
                        );
                        $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatos);
                    }
                }
            }//Fin subfactores
            $cantSubFactores = $this->getCantidadSubFactoresByFactor($idRubrica, $fact->nid_criterio);
            if($cantSubFactores > 0) {
                $rpta = $this->recalcularValoresLeyendas($idRubrica, $fact->nid_criterio);
                if($rpta !== EXIT_SUCCESS) {
                    throw new Exception($rpta);
                }
            }
        }//Fin factores
    }
    
    function getSubFactorLeyenda($idRubrica, $idFactor) {
        $sql = "SELECT id_indicador AS id_subfactor, 
                       MAX(orden)   AS max_orden
                  FROM sped.rubrica_valor_leyenda
                 WHERE id_rubrica  = ?
                   AND id_criterio = ?
                GROUP BY id_indicador";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->result_array();
    }
    
    function getFactorACambiarOrden($idRubrica, $orden, $direccion) {
        $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
        $sql = "SELECT id_criterio
                  FROM sped.rubricar_x_criterio
                 WHERE id_rubrica = ?
                   AND orden      = ?";
        $result = $this->db->query($sql, array($idRubrica, $orden));
        if($result->num_rows() == 1) {
            return $result->row()->id_criterio;
        }
        return null;
    }
    
    function getSubFactorACambiarOrden($idRubrica, $idFactor, $orden, $direccion) {
        $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
        $sql = "SELECT id_indicador
                  FROM sped.rubri_crit_indi
                 WHERE id_rubrica  = ?
                   AND id_criterio = ?
                   AND orden       = ?";
        $result = $this->db->query($sql, array($idRubrica, $idFactor, $orden));
        if($result->num_rows() == 1) {
            return $result->row()->id_indicador;
        }
        return null;
    }
    
    function updateFactor_Orden($array1, $array2) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $this->db->where('id_rubrica'  , $array1['id_rubrica']);
            $this->db->where('id_criterio' , $array1['id_criterio']);
            $this->db->update('sped.rubricar_x_criterio',array('orden' => $array1['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-011)');
            }
            $this->db->where('id_rubrica'  , $array2['id_rubrica']);
            $this->db->where('id_criterio' , $array2['id_criterio']);
            $this->db->update('sped.rubricar_x_criterio',array('orden' => $array2['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-012)');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function updateSubFactor_Orden($array1, $array2) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $this->db->where('id_rubrica'  , $array1['id_rubrica']);
            $this->db->where('id_criterio' , $array1['id_criterio']);
            $this->db->where('id_indicador' , $array1['id_indicador']);
            $this->db->update('sped.rubri_crit_indi',array('orden' => $array1['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-013)');
            }
            $this->db->where('id_rubrica'  , $array2['id_rubrica']);
            $this->db->where('id_criterio' , $array2['id_criterio']);
            $this->db->where('id_indicador' , $array2['id_indicador']);
            $this->db->update('sped.rubri_crit_indi',array('orden' => $array2['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-014)');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    /*function getFactoresRestantes_NoSeteados($idRubrica, $idFactor) {
        $sql = "SELECT id_criterio
                  FROM rubricar_x_criterio
                 WHERE id_rubrica   = ?
                   AND id_criterio <> ?
                   AND flg_peso_seteado IS NULL";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->result_array();
    }
    
    function getSumaPesoFactoresSeteados($idRubrica) {
        $sql = "SELECT SUM(peso_porcentaje) suma_pesos
                  FROM rubricar_x_criterio
                 WHERE id_rubrica  = ?
                   AND flg_peso_seteado IS NOT NULL";
        $result = $this->db->query($sql, array($idRubrica));
        if($result->num_rows() == 1) {
            return $result->row()->suma_pesos;
        }
        return 0;
    }*/
    
    function insertDeleteFichaValor($arrayGeneral, $idRubrica, $valorMax, $valor){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {         
            if($valor > $valorMax) {             
                foreach($arrayGeneral as $dato) {
                    $arrayDatos = array("id_rubrica"   => $dato['id_rubrica'],
                                        "id_criterio"  => $dato['id_criterio'],
                                        "id_indicador" => $dato['id_indicador'],
                                        "valor"        => $dato['valor'],
                                        "leyenda"      => '');                   
                     $this->db->insert('sped.rubrica_valor_leyenda', $arrayDatos);                                  
                     $valorMax = $this->getValorMaxByNidFicha($idRubrica);    
                     $this->db->where("id_rubrica", $dato['id_rubrica']);
                     $this->db->update('sped.rubri_crit_indi', array("valor_max_criterio" =>$valorMax));
                }
            } else if($valor < $valorMax) {
                //borrar en leye
               foreach($arrayGeneral as $dato) {
                   $arrayDatos = array("id_rubrica"   => $dato['id_rubrica'],
                                        "id_criterio"  => $dato['id_criterio'],
                                        "id_indicador" => $dato['id_indicador'],
                                        "valor"        => $dato['valor']);
                    $this->db->delete('sped.rubrica_valor_leyenda',$arrayDatos);
                    //borrar en fiva
                    $valorMax = $this->getValorMaxByNidFicha($idRubrica);
                    $this->db->where("id_rubrica", $dato['id_rubrica']);
                    $this->db->update('sped.rubri_crit_indi', array("valor_max_criterio" =>$valorMax));
                }
            }
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('(MR-003)');
            } else {
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = MSJ_INS;
                $data['cabecera'] = CABE_INS;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
  
    function getValorMaxByNidFicha($idRub){
        $sql = "SELECT COALESCE(MAX(valor), 0) max_val 
				  FROM sped.rubrica_valor_leyenda
				 WHERE id_rubrica = ?";
        $result = $this->db->query($sql,$idRub);
        return $result->row()->max_val;
    }
    
    function getMaxValorByRubricaFactor($idRubrica, $idFactor) {
        $sql = "SELECT COALESCE(MAX(valor), 0) max_val
				  FROM sped.rubrica_valor_leyenda
				 WHERE id_rubrica  = ?
                   AND id_criterio = ?";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->row()->max_val;
    }
    
    function getMaxValorByRubricaFactor_Leyenda($idRubrica, $idFactor) {
        $sql = "SELECT COALESCE(MAX(valor), 0) max_val
				  FROM sped.leyenda_x_evaluacion
				 WHERE id_rubrica = ?
                   AND id_factor  = ?
                   AND flg_acti   = '".FLG_ACTIVO."' ";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->row()->max_val;
    }
    
    function getCountNidFichaByRci($idRub, $idCrit){
        $sql = "SELECT COUNT(1) count
        		  FROM sped.rubri_crit_indi
        		 WHERE id_rubrica  = ?
        		   AND id_criterio = ?";
        $result = $this->db->query($sql,array($idRub, $idCrit));
        return $result->row()->count;
    }
    
    function getCountValorByCriterio($idRub, $idCrit){
        $sql = "SELECT COUNT(1) count
                  FROM sped.rubrica_valor_leyenda
                 WHERE id_rubrica  = ?
                   AND id_criterio = ?";
        $result = $this->db->query($sql,array($idRub, $idCrit));
        return $result->row()->count;
    }
    
    function getValorFromLeyenda($idRubrica, $idFactor, $idSubFactor, $orden) {
        $sql = "SELECT valor
                  FROM sped.rubrica_valor_leyenda
                 WHERE id_rubrica   = ?
                   AND id_criterio  = ?
                   AND id_indicador = ?
                   AND orden        = ?";
        $result = $this->db->query($sql,array($idRubrica, $idFactor, $idSubFactor, $orden));
        return $result->row()->valor;
    }

    //Busca la ficha en la tabla evdfiva
    // ademas trae los valores nidfiva y nidValoracion que se usaran para modal popup valores
  /*  function getFichaByEvdfiva($nidFicha, $db_sped = null, $arryNewValors){
        $sql = "   SELECT evd.nidFicha, 
                	      evd.nidfiva, 
                	      evd.nidValoracion, 
                          evm.desc_valor,
                          evm.valor
                	 FROM evdfiva evd,
                		  evmvalo evm
                	WHERE evd.nidValoracion = evm.idValoracion
                      AND nidFicha          = ? ";
        if($arryNewValors != null) {
            $sql .= "AND evd.nidValoracion IN ({$arryNewValors})";
        }
        ($db_sped == null) ? $db_sped = $this->load->database('sped', TRUE) : $db_sped;        
        $result = $db_sped->query($sql,$nidFicha);
        return ($result->result());
    }*/
    //Buscar si existe Indicador en la tabla rubrica criterio indicador
    function getbuscarIdIndicadorRci($nidFicha){
        $sql = "SELECT rci.id_indicador
                  FROM sped.indicador i,
                       sped.rubri_crit_indi rci
                 WHERE id_rubrica = ?
                   AND rci.id_indicador = i.nid_indicador";    
        $result = $this->db->query($sql,$nidFicha);
        return ($result->result());
    }

    function insertUpdateLeyenda($arrayGeneral) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $cont = 0;
            $this->db->trans_begin();
            foreach ($arrayGeneral as $dato) {
                $this->db->where("id_rubrica", $dato['id_rubrica']);
                $this->db->where("id_criterio", $dato['id_criterio']);
                $this->db->where("id_indicador", $dato['id_indicador']);
                $this->db->where("valor", $dato['valor']);
                unset($dato['id_rubrica']);
                unset($dato['id_criterio']);
                unset($dato['id_indicador']);
                unset($dato['valor']);
                $this->db->update('sped.rubrica_valor_leyenda', array("leyenda" => $dato['leyenda']));
                $cont = $cont + $this->db->affected_rows();
            }
            if($cont != count($arrayGeneral)) {
                throw new Exception('(MR-004)');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getLeyendaByRubValLey($nidCriterio, $nidRub, $nidInd){
        $sql = "SELECT valor,
                       leyenda,
                       color_radio_button
                  FROM sped.rubrica_valor_leyenda 
                 WHERE id_criterio  = ?
                   AND id_indicador = ?
                   AND id_rubrica   = ?
                ORDER BY orden";
        $result = $this->db->query($sql,array($nidCriterio, $nidInd, $nidRub));
        return ($result->result());
    }
       /////////////////////////////cons_rubrica//////////////////////
    function buscarIdFichMax() {
        $sql = "SELECT COALESCE(MAX(nid_ficha), 0) + 1  max_val 
                  FROM sped.rubrica";
        $result = $this->db->query($sql);
        return $result->row()->max_val;
    }
   
    function getCountIdFichaByRubrica($anio,$mes,$dia,$tipoFi,$tipoFiCur) {
        $sql = "SELECT COUNT(1) cnt
                  FROM sped.rubrica r
                 WHERE EXTRACT(YEAR FROM r.fec_rubrica) = ? 
                   AND EXTRACT(MONTH FROM r.fec_rubrica) = ?
                   AND EXTRACT(DAY FROM r.fec_rubrica) = ?
                   AND r.tipo_rubrica = ? 
                   AND r.tipo_rubrica_curso = ?";
        $result = $this->db->query($sql,array($anio,$mes,$dia,$tipoFi,$tipoFiCur));
        return $result->row()->cnt;
    }
    
    function insertFicha($version){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $arrayDatos = array(
                "desc_version" => $version,
                "flg_acti"     => PENDIENTE_,
                "fec_rubrica"  => date('Y-m-d')
            );
            $this->db->insert('sped.rubrica', $arrayDatos);
            if ($this->db->affected_rows() != 1) {
                throw new Exception('(MA-002)');
            }
            $data['newIdRubrica'] = $this->db->insert_id();
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
 
    function getAllFichas() {
        $sql = "SELECT rubrica.nid_ficha id_rubrica,
                       rubrica.desc_version,
                       rubrica.estado,
                       rubrica.fecha,
                       CASE WHEN rubrica.estado = '".RUBRICA_PENDIENTE."' OR rubrica.editar = 0 THEN 'SI' ELSE 'NO' END AS flg_editar,
                       CASE WHEN rubrica.estado = '".RUBRICA_ACTIVA."' AND rubrica.dar_baja = 0 THEN 'SI' ELSE 'NO' END AS flg_desactivar
                  FROM (SELECT r.nid_ficha,
                               r.desc_version,
                               CASE r.flg_acti WHEN '3' THEN '".RUBRICA_REACTIVAR."'
                                               WHEN '2' THEN '".RUBRICA_PENDIENTE."'
                                    		   WHEN '1' THEN '".RUBRICA_ACTIVA."'
                                    		   WHEN '0' THEN '".RUBRICA_INACTIVA."' END AS estado,
                               TO_CHAR(r.fec_rubrica, 'DD/MM/YYYY') as fecha,
                               r.cant_valores,
                               (SELECT COUNT(1) dar_baja
                            	  FROM sped.evaluacion
                            	 WHERE id_rubrica = r.nid_ficha
                            	   AND estado_evaluacion IN ('PENDIENTE','NO EJECUTADO')) dar_baja,
                               (SELECT COUNT(1) editar
                            	  FROM sped.evaluacion
                            	 WHERE id_rubrica = r.nid_ficha) editar
                 FROM sped.rubrica r
                ORDER BY r.nid_ficha DESC ) rubrica";
        $result  = $this->db->query($sql);
        return ($result->result());
    }
    
    function puedeEditar($idRubrica) {
        $sql = "SELECT * FROM
                    (SELECT COUNT(1) editar
                      FROM sped.evaluacion
                     WHERE id_rubrica = ?) AS hay_evas,
                    (SELECT flg_acti AS estado
                       FROM sped.rubrica
                      WHERE nid_ficha = ?) AS estado";
        $result  = $this->db->query($sql, array($idRubrica, $idRubrica) )->row_array();
        if($result['editar'] == 0 && $result['estado'] == PENDIENTE_) {
            return true;
        }
        return false;
    }
    
    function checkIfAllFactoresHaveSubFactores($idRubrica) {
        $sql = "SELECT COUNT(1)	AS cnt
                  FROM sped.rubricar_x_criterio rc
                 WHERE rc.id_rubrica = ?
                   AND rc.id_criterio NOT IN (SELECT rci.id_criterio
                                				FROM sped.rubri_crit_indi rci
                                			   WHERE rci.id_rubrica = ?)";
        $result = $this->db->query($sql, array($idRubrica, $idRubrica));
        if($result->row()->cnt == 0) {
            return true;
        }
        return false;
    }
    
    function cambiarEstado($idFicha, $estado, $reactivado = null) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();
            $m = new MongoClient(MONGO_CONEXION);
            $db = $m->selectDB(SMILEDU_MONGO);
            if($estado == ACTIVO_) {
                //VALIDAR SI LOS PESOS ESTAN BIEN
                $cntPesosNulo = $this->getFactoresPesosNulos($idFicha);
                if($cntPesosNulo > 0) {
                    throw new Exception('Falta asignar pesos a los factores');
                }
                //VALIDAR SI LOS PESOS SUMAN 100
                $sumaPesos = $this->getSumaPesosByRubrica($idFicha);
                if($sumaPesos != 100) {
                    throw new Exception('La suma de los pesos debe ser exactamente 100%');
                }
                //VALIDAR SI T0DOS LOS FACTORES TIENEN AL MENOS UN SUBFACTOR
                $isOk = $this->checkIfAllFactoresHaveSubFactores($idFicha);
                if(!$isOk) {
                    throw new Exception('Hay factores sin Subfactores. Asigne al menos uno a todos los factores.');
                }
                //SI ES REACTIVADA RECALCULAR LAS EVALUACIONES
                if($reactivado == RUBRICA_ESTADO_POR_REACTIVAR) {
                    //Recalcular evaluaciones (solo del year actual)
                    $evasAffctd = $this->getEvaluacionesAfectadas($idFicha);//Del presente anio
                    $cantValores = $this->m_utils->getById('sped.rubrica', 'cant_valores', 'nid_ficha', $idFicha);
                    foreach ($evasAffctd as $eva) {
                        $factores = $this->getCriteriosByRubrica($idFicha);
                        foreach ($factores as $factor) {
                            $subFactorsUpt = $this->getSubFactoresToUpdate($eva['id_evaluacion'], $factor->nid_criterio);
                            /////////////////////  Recalcular en pgSQL  /////////////////////
                            $pesoFactor = $this->getPesoFromFactor($idFicha, $factor->nid_criterio);
                            $maxValor   = (($pesoFactor * 20 / 100)) / count($subFactorsUpt);
                            foreach ($subFactorsUpt as $subF) {
                                ///////////////Actualizar leyenda_x_evaluacion/////////////////
                                $leyendas = $this->m_evaluar->getPosiblesValoresCriterioEvaluar($eva['id_evaluacion'], $idFicha, $factor->nid_criterio, $subF['id_indicador']);
                                $sumatoria = ($maxValor / ($cantValores - 1) );
                                $i = 1;
                                $valorAnterior = 0;
                                foreach ($leyendas as $ley) {
                                    $valorFinal = $this->calcularValor($i, $maxValor, $cantValores, $sumatoria, $valorAnterior);
                                    $arrayDatosLey = array("valor" => $valorFinal, "color_radio_button" => $this->calcularColorRadioButton($valorFinal, $maxValor) );
                                
                                    $this->db->where('id_evaluacion' , $eva['id_evaluacion']);
                                    $this->db->where('id_rubrica'  , $idFicha);
                                    $this->db->where('id_factor'   , $factor->nid_criterio);
                                    $this->db->where('id_subfactor', $subF['id_indicador']);
                                    $this->db->where('orden'       , $ley->orden);
                                    $this->db->update('sped.leyenda_x_evaluacion', $arrayDatosLey);
                                    $i++;
                                }
                                //////////////////////////////////////////////////
                                $newValor = $this->m_evaluar->getNewValor($eva['id_evaluacion'], $idFicha, $factor->nid_criterio, $subF['id_indicador'], $subF['orden_valor']);
                                $this->db->where('id_evaluacion', $eva['id_evaluacion']);
                                $this->db->where('id_rubrica', $idFicha);
                                $this->db->where('id_criterio', $factor->nid_criterio);
                                $this->db->where('id_indicador', $subF['id_indicador']);
                                $this->db->update('sped.rubri_crit_indi_deta', array('valor_indi' => $newValor));
                            }
                            /////////////////////  Recalcular en MongoDB  /////////////////////
                            $subFactorsMongoUpt = $this->getSubFactoresMONGOToUpdate($eva['id_evaluacion'], $factor->nid_criterio);
                            //
                            if(count($subFactorsMongoUpt) > 0) {
                                foreach ($subFactorsMongoUpt as $subF) {
                                    if(!isset($subF['id_indicador'])) {
                                        continue;
                                    }
                                    $newValor = $this->m_evaluar->getNewValor($eva['id_evaluacion'], $idFicha, $factor->nid_criterio, $subF['id_indicador'], $subF['array_evas'][0]['orden_valor']);
                                    $maxValorByCrit = $this->getMaxValorByRubricaFactor_Leyenda($idFicha, $factor->nid_criterio);
                                    $newVigesimal = $newValor * 20 / $maxValorByCrit;
                                    //ACTUALIZAR rubrica_indi
                                    $nosql = 'db.rubrica_indi.update(
                                                { id_indicador: '.$subF['id_indicador'].' , mes : '.$subF['mes'].' , year : '.$subF['year'].' ,
                                                  "array_evas.id_evaluacion" : '.$subF['array_evas'][0]['id_evaluacion'].',
                                                  "array_evas.id_factor"     : '.$subF['array_evas'][0]['id_factor'].'
                                                },
                                                { $set: { "array_evas.$.valor"     : '.$newValor.',
                                                          "array_evas.$.nota_vige" : '.$newVigesimal.' }
                                       	     })';
                                    $result = $db->execute('return '.$nosql);
                                    //ACTUALIZAR rubrica_indi_doc
                                    $nosql = 'db.rubrica_indi_doc.update(
                                                { id_indicador: '.$subF['id_indicador'].' , mes : '.$subF['mes'].' , year : '.$subF['year'].' ,
                                                  "array_evas.id_evaluacion" : '.$subF['array_evas'][0]['id_evaluacion'].',
                                                  "array_evas.id_factor"     : '.$subF['array_evas'][0]['id_factor'].'
                                                },
                                                { $set: { "array_evas.$.nota_vige" : '.$newVigesimal.' }
                                       	     })';
                                    $result = $db->execute('return '.$nosql);
                                }
                            }
                        }
                        //ACTUALIZAR NOTAS FINALES PGSQL
                        $newNota_vigesimal = $this->m_evaluar->getPuntajeTotalByEvaluacionFicha($eva['id_evaluacion']);
                        $ptje_final = $newNota_vigesimal * 100 / 20;
                        $this->db->where('id_evaluacion', $eva['id_evaluacion']);
                        $this->db->update('sped.evaluacion'  , array('ptje_final'     => $ptje_final,
                                                                'nota_vigesimal' => $newNota_vigesimal));
                        //ACTUALIZAR NOTAS FINALES MONGODB
                        $evalMongo = $this->getEvaluacionDocente_Mongo($eva['id_evaluacion']);
                        if(isset($evalMongo['array_evas'][0]['id_evaluacion'])) {
                            $nosql = 'db.rubrica_docentes.update(
                                    { "array_evas.id_evaluacion" : '.$evalMongo['array_evas'][0]['id_evaluacion'].'
                                    },
                                    { $set: { "array_evas.$.nota_vige" : '.$newNota_vigesimal.' }
                           	     })';
                            $result = $db->execute('return '.$nosql);
                        }
                    }
                }
                //Desactivar los otros tipos
                $this->db->where_not_in('flg_acti', array(PENDIENTE_, RUBRICA_ESTADO_POR_REACTIVAR) );
                $this->db->where_not_in('nid_ficha', $idFicha);
                $this->db->update('sped.rubrica', array("flg_acti" => INACTIVO_));
            }            
            $this->db->where("nid_ficha", $idFicha);
            $this->db->update('sped.rubrica', array("flg_acti" => $estado));    
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;  
    }
    
    function getFactoresPesosNulos($idRubrica) {
        $sql = "SELECT COUNT(1) cnt
                  FROM sped.rubricar_x_criterio
                 WHERE id_rubrica  = ?
                   AND peso_porcentaje IS NULL";
        $result = $this->db->query($sql, array($idRubrica));
        return $result->row()->cnt;
    }
    
    function getSumaPesosByRubrica($idRubrica) {
        $sql = "SELECT SUM(peso_porcentaje) suma_pesos
                  FROM sped.rubricar_x_criterio
                 WHERE id_rubrica  = ?";
        $result = $this->db->query($sql, array($idRubrica));
        if($result->num_rows() == 1) {
            return $result->row()->suma_pesos;
        }
        return 0;
    }
    
    ////MODAL POPUP NUEVO CRITERIO////
    function buscarNidCriterioMax() {
        $sql = "SELECT COALESCE(MAX(nid_criterio), 0) + 1  max_val 
                  FROM sped.criterio";
        $result = $this->db->query($sql);
        return $result->row()->max_val;
    }
    
    function insertCriterio($descrip){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $this->db->trans_begin();
        try {
            $existe = $this->checkIfExiste($descrip, 'sped.criterio', 'desc_criterio');
            if($existe) {
                throw new Exception('El factor ya existe, ingrese otro.');
            }
            $arrayDatos = array("desc_criterio" => $descrip);
            $this->db->insert('sped.criterio', $arrayDatos);
            if($this->db->affected_rows() != 1) {   
                throw new Exception('(MR-005)');
            }
            $newId = $this->db->insert_id();
            $data['new_id_factor'] = _simple_encrypt($newId);
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_INS;
            $this->db->trans_commit();
            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;   
    }
    
    function checkIfExiste($descrip, $tabla, $campo) {
        $sql = "SELECT 1
                  FROM $tabla
                 WHERE UPPER($campo) = UPPER(?)";
        $result = $this->db->query($sql, $descrip);
        if($result->num_rows() == 0) {
            return false;
        }
        return true;
    }
    
    function buscarNidIndicadorMax() {
        $sql = "SELECT COALESCE(MAX(nid_indicador), 0) + 1  max_val
                  FROM sped.indicador";
        $result = $this->db->query($sql);
        return $result->row()->max_val;
    }
    
    function insertIndicador($descrip) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $existe = $this->checkIfExiste($descrip, 'sped.indicador', 'desc_indicador');
            if($existe) {
                throw new Exception('El subfactor ya existe, ingrese otro.');
            }
            $arrayDatos = array("desc_indicador" => $descrip);
            $this->db->insert('sped.indicador', $arrayDatos);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MR-006)');
            }
            $newId = $this->db->insert_id();
            $data['new_id_subfactor'] = _simple_encrypt($newId);
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = MSJ_INS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getCountIdIndicadorByRci($idFicha){
        $sql ="SELECT COUNT(id_indicador) cont
                 FROM sped.rubri_crit_indi
                WHERE id_rubrica    = ?
                  AND id_indicador != 0";
        $result = $this->db->query($sql,array($idFicha));
        return $result->row()->cont;
    }
    
    function getCantidadSubFactoresByFactor($idRubrica, $idFactor) {
        $sql ="SELECT COUNT(1) cont
                 FROM sped.rubri_crit_indi
                WHERE id_rubrica  = ?
                  AND id_criterio = ?";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->row()->cont;
    }
    
    function buscarIdIndiIdCritBYRubrica($ficha){
        $sql = "SELECT id_indicador,
                       id_criterio
                  FROM sped.rubri_crit_indi
                 WHERE id_rubrica = ?
                   AND id_indicador != 0";
        $result = $this->db->query($sql,$ficha);
        return ($result->result());
    }
    
    function verificarSiHayEvaluaciones_SubFactor($idRubrica, $idFactor, $idSubFactor) {
        $sql ="SELECT COUNT(1) cont
                 FROM sped.rubri_crit_indi_deta
                WHERE id_rubrica   = ?
                  AND id_criterio  = ?
                  AND id_indicador = ?";
        $result = $this->db->query($sql,array($idRubrica, $idFactor, $idSubFactor));
        return $result->row()->cont;
    }
    
    function verificarSiExiste_SubFactorFactor($idRubrica, $idFactor, $idSubFactor) {
        $sql ="SELECT COUNT(1) cont
                 FROM sped.rubri_crit_indi
                WHERE id_rubrica   = ?
                  AND id_criterio  = ?
                  AND id_indicador = ?";
        $result = $this->db->query($sql,array($idRubrica, $idFactor, $idSubFactor));
        return $result->row()->cont;
    }
    
    function verificarSiExiste_FactorRubrica($idRubrica, $idFactor) {
        $sql ="SELECT COUNT(1) cont
                 FROM sped.rubricar_x_criterio
                WHERE id_rubrica   = ?
                  AND id_criterio  = ?";
        $result = $this->db->query($sql,array($idRubrica, $idFactor));
        return $result->row()->cont;
    }
    
    function verificarSiHayEvaluaciones_Factor($idRubrica, $idFactor) {
        $sql ="SELECT COUNT(1) cont
                 FROM sped.rubri_crit_indi_deta
                WHERE id_rubrica   = ?
                  AND id_criterio  = ?";
        $result = $this->db->query($sql,array($idRubrica, $idFactor));
        return $result->row()->cont;
    }
    
    function getEvaluacionesAfectadas($idRubrica) {
        $sql = "SELECT id_evaluacion,
                       id_evaluador
                  FROM sped.evaluacion
                 WHERE id_evaluacion IN (SELECT id_evaluacion
                                           FROM sped.rubri_crit_indi_deta 
                                          WHERE id_rubrica  = ?
                                         GROUP BY id_evaluacion)
                   AND EXTRACT(YEAR FROM fecha_inicio) = "._YEAR_;
        $result = $this->db->query($sql, array($idRubrica));
        return $result->result_array();
    }
    
    function getSubFactoresToUpdate($idEvaluacion, $idFactor) {
        $sql = "SELECT id_indicador,
                       orden_valor
                  FROM sped.rubri_crit_indi_deta 
                 WHERE id_evaluacion = ?
                   AND id_criterio   = ?
                   AND flg_no_aplica IS NULL";
        $result = $this->db->query($sql, array($idEvaluacion, $idFactor));
        return $result->result_array();
    }
    
    function getSubFactoresMONGOToUpdate($idEvaluacion, $idFactor) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $nosql = 'db.rubrica_indi.find({ array_evas : { 
                                                          $elemMatch : {"id_evaluacion" : '.$idEvaluacion.' , 
                                                                        "id_factor"     : '.$idFactor.' } 
                                                      } 
                                       })';
        $result = $db->execute('return '.$nosql.'.toArray()');
        return $result['ok'] == SUCCESS_MONGO ? $result['retval'] : array();
    }
    
    function getEvaluacionDocente_Mongo($idEvaluacion) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $nosql = 'db.rubrica_docentes.find({ array_evas : { $elemMatch : {"id_evaluacion" : '.$idEvaluacion.' } } })';
        $result = $db->execute('return '.$nosql.'.toArray()');
        return $result['ok'] == SUCCESS_MONGO ? ( isset($result['retval'][0]) ? $result['retval'][0] : array() ) : array();
    }
    
    /*function getSubFactores_Docentes_MONGOToUpdate($idEvaluacion, $idFactor) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $nosql = 'db.rubrica_indi_doc.find({ array_evas : {
                                                          $elemMatch : {"id_evaluacion" : '.$idEvaluacion.' ,
                                                                        "id_factor"     : '.$idFactor.' }
                                                      }
                                       })';
        $result = $db->execute('return '.$nosql.'.toArray()');
        return $result;
    }*/
    
    function getSubFactoresToUpdateOrdenAfterBorrar($idRubrica, $idFactor, $idSubFactor) {
        $sql = "SELECT orden,
                       id_indicador
                  FROM sped.rubri_crit_indi rci
                 WHERE rci.id_rubrica   = ?
                   AND rci.id_criterio  = ?
                   AND rci.orden > (SELECT orden
                                      FROM sped.rubri_crit_indi
                                     WHERE id_rubrica   = rci.id_rubrica
                                       AND id_criterio  = rci.id_criterio
                                       AND id_indicador = ?)
                ORDER BY rci.orden";
        $result = $this->db->query($sql, array($idRubrica, $idFactor, $idSubFactor));
        return $result->result_array();
    }
    
    function getFactoresToUpdateOrdenAfterBorrar($idRubrica, $idFactor) {
        $sql = "SELECT rc.id_criterio,
                       rc.orden
                  FROM sped.rubricar_x_criterio rc
                 WHERE rc.id_rubrica   = ?
                   AND rc.orden > (SELECT orden
                                     FROM sped.rubricar_x_criterio rcc
                                    WHERE rcc.id_rubrica = rc.id_rubrica
                                      AND rcc.id_criterio = ?)
                ORDER BY rc.orden";
        $result = $this->db->query($sql, array($idRubrica, $idFactor));
        return $result->result_array();
    }
    
    function getSubFactores($idSubFactores) {
        $sql = "SELECT desc_indicador
                  FROM sped.indicador
                 WHERE nid_indicador IN ($idSubFactores)";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
}