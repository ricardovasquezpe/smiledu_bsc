<?php
defined('BASEPATH') or exit('No direct script access allowed');

class c_detalle_indicador extends CI_Controller
{

    private $_idUserSess = null;

    private $_idRol = null;

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('lib_deta_indi');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->model('mf_indicador/m_deta_indi_modal');
        $this->load->model('mf_indicador/m_comparativa');
        $this->load->model('mf_grafico/m_grafico');
        $this->load->model('mf_indicador/m_responsable_indicador');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_BSC, ID_PERMISO_CATEGORIA, BSC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol = _getSesion(BSC_ROL_SESS);
    }

    public function index()
    {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
        // //Modal Popup Iconos///
        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        // MENU
        $data['main'] = true;
        $data['ruta_logo'] = MENU_LOGO_BSC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
        $data['nombre_logo'] = NAME_MODULO_BSC;
        $data['titleHeader'] = 'Detalle del indicador';
        $data['rutaSalto'] = 'SI';
        if (_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_MEDICION || _getSesion(BSC_ROL_SESS) == ID_ROL_SUBDIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) {
            $data['return'] = '';
        }
        $menu = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        $idIndicador = _simpleDecryptInt(_getSesion("id_indicador"));
        _log("ID_INDICADOR: ".$idIndicador);
        $historiaInd = $this->m_indicador->getHistoriaByIndicador($idIndicador);
        $listaHijos = $this->m_indicador->getHijosByIndicador($idIndicador);
        $data['tablaHijos'] = $this->createTableHijos($listaHijos);
        $detalle = $this->pintarDetalle();
        $data['fechaModi'] = $detalle['fechaModi'];
        $data['historiaInd'] = $this->createLineaTiempoHistoria($historiaInd, 'none');
        $data['cardDetalle'] = $detalle['detalleModal'];
        $data['tb_comparativas'] = __buildTableAsignarComprativasXIndicador($idIndicador);
        $data = _buildTableHTMLFrecuencias($idIndicador, $data);
        $data['responsables'] = $this->getRepsonsableByIndicador($idIndicador);
        
        $this->load->view('vf_indicador/v_detalle_indicador', $data);
    }

    function comboObjetivos()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data['tablePersonasModal'] = $this->buildTablePersonaAddIndicadorHTML(null, null);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function createTableHijos($data)
    {
        $tabla = null;
        $tabla .= '<table id="tree" class="table tree table-condensed">';
        $idRolPromotor = _getSesion($this->_idRol);
        $tabla .= '<tr>
                       <td style="border-top-width:0px;color:rgba(0, 0, 0, 0.54);text-align:left" class="col-sm-5">Descripción</td>
                       <td style="border-top-width:0px;color:rgba(0, 0, 0, 0.54);text-align:center" class="col-sm-2">Actual</td>
                       <td style="border-top-width:0px;color:rgba(0, 0, 0, 0.54);text-align:center" class="col-sm-2">Meta</td>
                       <td style="border-top-width:0px;color:rgba(0, 0, 0, 0.54);text-align:center" class="col-sm-2">Acciones</td>
                  </tr>';
        $idIndicadorActual = 0;
        foreach ($data as $row) {
            $orden = str_replace(".", "", $row->orden);
            
            $classGrid = 'treegrid-' . $orden;
            $classGrid .= ($row->id_sede == 0 && $row->id_nivel == 0 && $row->id_grado == 0 && $row->id_aula == 0 && $row->id_disciplina == 0 && $row->id_area == 0) ? null : ' treegrid-parent-' . $this->getPadre($row->orden);
            $tipoValor = $row->tipo_valor;
            
            $idIndicadorNodo = $this->encrypt->encode($row->id_indicador_detalle);
            
            $onclick = null;
            $icono = null;
            if ($this->_idRol == ID_ROL_DIRECTOR || $this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
                $onclick = 'onclick="openEditMeta(\'' . $idIndicadorNodo . '\',' . $row->valor_meta . ')"';
                $icono = '<i class="mdi mdi-edit"></i>';
            }
            
            $btnAddEstructura = null;
            if ($this->_idRol == ID_ROL_DIRECTOR || $this->_idRol == ID_ROL_MEDICION || $this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
                $btnAddEstructura = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" style="opacity:0.3">
                                         <i class="mdi mdi-edit"></i>
                                     </button>';
                if ($row->boton == 1) {
                    $btnAddEstructura = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openEditEstructura(\'' . $idIndicadorNodo . '\')">
                                             <i class="mdi mdi-edit"></i> 
                                         </button>';
                }
            }
            
            $btnDetalle = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" style="opacity:0.3"><i class="mdi mdi-remove_red_eye"></i></button>';
            if ($row->btndetalle == 1) {
                $btnDetalle = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalDetalleByIndicador(\'' . $idIndicadorNodo . '\')"><i class="mdi mdi-remove_red_eye"></i></button>';
            }
            
            $descCantidad = null;
            if ($row->tipo_valor == TIPO_VALOR_PORCENTAJE) {
                $descCantidad = '<a style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none" data-toggle="tooltip" data-placement="bottom" data-original-title="' . (int) $row->valor_actual_numerico . '/' . $row->cant_alum_aula . '"><i class="mdi mdi-info_outline" style="margin-left:10px"></i></a>';
            }
            
            $collapsed = null;
            if ($row->tipo_regi == "SEDE" || $row->tipo_regi == "DISCIPLINA") {
                $collapsed = "collapsed";
            }
            
            $btnEditMeta = '<p class="link-dotted" ' . $onclick . '>' . $row->valor_meta . '</p>';
            $tabla .= '<tr class="' . $classGrid . ' ' . $collapsed . '" style="background-color:rgba(230, 230, 230, ' . $row->back_color . ')">
                          <td class="text-left">' . $row->desc_registro . $descCantidad . '</td>
                          <td class="text-center">' . $row->valor_actual_porcentaje . $tipoValor . _calculateIcon($row->diff_actual_y_anterior, $tipoValor) . '</td>
                          <td class="text-center">
                              ' . $btnEditMeta . '
                          </td>
                          <td style="text-align:center">
                              ' . $btnDetalle . $btnAddEstructura . '</td>
                      </tr>';
        }
        $tabla .= '</table>';
        return $tabla;
    }

    function editMeta()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idIndDetalle = $this->encrypt->decode(_post('idIndDeta'));
            $displayHisto = _post('displayHisto');
            $meta = _post('meta');
            if ($idIndDetalle == null || $meta == null) {
                throw new Exception(ANP);
            }
            $tipoGauge = $this->m_indicador->getTipoGaugeByIndicadorDetalle($idIndDetalle);
            if ($tipoGauge['tipo_gauge'] == GAUGE_CERO) {
                if ($meta < 0) {
                    $data['error'] = EXIT_WARNING;
                    throw new Exception("La meta no puede menor que 0");
                }
            } else {
                if ($meta <= 0) {
                    $data['error'] = EXIT_WARNING;
                    throw new Exception("La meta no puede ser 0 o menos");
                }
            }
            
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            $tipoEstructura = $this->m_indicador->getEstructuraFromIndicador($idIndicador);
            $data = $this->m_indicador->editarMeta($idIndicador, $idIndDetalle, $meta, $tipoEstructura);
            $valorAmarillo = $this->m_indicador->getValorAmarilloByIndicador($idIndicador);
            $data['flg_amarillo'] = $valorAmarillo['flg_amarillo'];
            if ($data['error'] == EXIT_SUCCESS) {
                $gauge = $this->createGraficoIndicadorGauge();
                
                $detalle = $this->pintarDetalle();
                $data['detalleModal'] = $detalle['detalleModal'];
                $data['fechaModi'] = $detalle['fechaModi'];
                
                $data['contGauge'] = $gauge['contGauge'];
                $data['posicion'] = $gauge['posicion'];
                $data['contMenuGauge'] = $gauge['contMenuGauge'];
                $data['tablaHijos'] = $this->createTableHijos($this->m_indicador->getHijosByIndicador($idIndicador));
                $data['historiaInd'] = $this->createLineaTiempoHistoria($this->m_indicador->getHistoriaByIndicador($idIndicador), $displayHisto);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getSpan($clase, $id)
    {
        return '<span class="' . $clase . ' editable editable-click" data-pk="' . $id . '">';
    }

    function getEstructuraByIndicador()
    {
        $idIndicadorDetalle = _decodeCI(_post('id_indicador_deta'));
        $dataUser = array(
            "id_indicador_detalle" => $idIndicadorDetalle
        );
        $this->session->set_userdata($dataUser);
        
        $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
        
        $data = null;
        if ($idIndicadorDetalle != null) {
            $res = $this->m_indicador->getEstructuraByIndicadorDetalle($idIndicadorDetalle);
            $data['tabla_estructura'] = $this->createTableEstructura($res);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function createTableEstructura($data)
    {
        $tmpl = array(
            'table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_estructura">',
            'table_close' => '</table>'
        );
        $this->table->set_template($tmpl);
        $head_0 = array(
            'data' => 'Descripci&oacute;n',
            'class' => 'text-left'
        );
        $head_1 = array(
            'data' => 'Seleccionar',
            'class' => 'text-center'
        );
        $this->table->set_heading($head_0, $head_1);
        $row_col0 = null;
        $row_col1 = null;
        $val = 1;
        foreach ($data as $row) {
            $id = _encodeCI($row->id);
            $descReg = _encodeCI($row->desc);
            $idIndicadorDeta = _encodeCI($row->id_indicador_detalle);
            
            $row_col1 = array(
                'data' => '  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="rol' . $val . '">
                                            <input type="checkbox"  id="rol' . $val . '" class="mdl-checkbox__input" attr-bd="' . $row->check . '" attr-descReg="' . $descReg . '" attr-idEst="' . $id . '" attr-idIndDeta="' . $idIndicadorDeta . '" attr-cambio="false" onchange="cambioCheckEstructura(this);" ' . $row->check . '>
                                            <span class="mdl-checkbox__label"></span>
                                        </label>',
                'class' => 'text-center'
            );
            
            $row_col0 = array(
                'data' => $row->desc,
                'class' => 'text-left'
            );
            $this->table->add_row($row_col0, $row_col1);
            $val ++;
        }
        $tabla = $this->table->generate();
        return $tabla;
    }

    function grabarEstructura()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $displayHisto = _post('displayHisto');
            $arrayGeneralInsert = array();
            $arrayGeneralDelete = array();
            
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            $tipoEstructura = _getSesion('tipo_estructura_indicador'); // tipo de estructura general
            $idIndicaDetalle = _getSesion('id_indicador_detalle'); // el que voy a cambiar su etructura
            
            $niv = $this->m_indicador->getNivelesByDetalleIndicador($idIndicaDetalle); // nivel del detalle indicador
            $orden = $this->m_indicador->getOrdenFromIndicadorDetalle($idIndicaDetalle); // ejemplo 6.0.0.0.0 = 6.
            $tipo = $this->m_utils->getById("bsc.indicador_detalle", "tipo_regi", "id_indicador_detalle", $idIndicaDetalle);
            
            $tipoIndPapa = $this->m_indicador->getindicadorDetalleFromIndicador($idIndicador, array(
                'valor_meta',
                'id_indicador_detalle'
            ));
            $meta = $tipoIndPapa['valor_meta'];
            $idIndicadorDetallePapa = $tipoIndPapa['id_indicador_detalle'];
            
            $myPostData = json_decode(_post('estructuras'), TRUE);
            foreach ($myPostData['estructura'] as $key => $estructura) {
                $idEst = $this->encrypt->decode($estructura['idEst']);
                $desc = $this->encrypt->decode($estructura['descReg']);
                $idIndDeta = $this->encrypt->decode($estructura['idIndDeta']);
                $newVal = ($estructura['valor'] == null) ? '0' : '1';
                
                if ($newVal == '0') {
                    array_push($arrayGeneralDelete, $idIndDeta);
                } else {
                    $i = 0;
                    $arrayDatos = array(
                        "__id_indicador" => $idIndicador,
                        "flg_acti" => FLG_ACTIVO,
                        "desc_registro" => $desc,
                        "audi_id_usua" => _getSesion('nid_persona'),
                        "audi_nomb_usua" => _getSesion('nombre_completo')
                    );
                    $arrayInsert = $this->generateInsert($orden, $tipo, $tipoEstructura, $idEst, $niv, $idEst, $arrayDatos);
                    array_push($arrayGeneralInsert, $arrayInsert);
                    if ($tipo == 'NIVEL' && $tipoEstructura == "SNGA") { // SOLO EN ESTOS CASOS
                        if ($i == 0) {
                            $aulas = $this->m_utils->getAulasByGrado($niv['id_sede'], $niv['id_nivel'], $idEst);
                            foreach ($aulas as $rr) {
                                $arrayDatos = array(
                                    "__id_indicador" => $idIndicador,
                                    "flg_acti" => FLG_ACTIVO,
                                    "desc_registro" => $rr->desc_aula,
                                    "audi_id_usua" => _getSesion('nid_persona'),
                                    "audi_nomb_usua" => _getSesion('nombre_completo')
                                );
                                $arrayInsert = $this->generateInsert($orden . '' . $idEst . '.', "GRADO", $tipoEstructura, $rr->nid_aula, $niv, $idEst, $arrayDatos);
                                array_push($arrayGeneralInsert, $arrayInsert);
                            }
                            $i = 1;
                        }
                    }
                }
            }
            $data = $this->m_indicador->insertUpdateEstructuraIndicador($arrayGeneralInsert, $arrayGeneralDelete, $tipoEstructura, $idIndicador, $idIndicadorDetallePapa, $meta);
            if ($data['error'] == EXIT_SUCCESS) {
                $res = $this->m_indicador->getEstructuraByIndicadorDetalle($idIndicaDetalle);
                // $data['tabla_estructura'] = $this->createTableEstructura($res);
                $listaHijos = $this->m_indicador->getHijosByIndicador($idIndicador);
                $gauge = $this->createGraficoIndicadorGauge();
                $data['contGauge'] = $gauge['contGauge'];
                $data['posicion'] = $gauge['posicion'];
                $data['contMenuGauge'] = $gauge['contMenuGauge'];
                $data['tablaHijos'] = $this->createTableHijos($listaHijos);
                $data['historiaInd'] = $this->createLineaTiempoHistoria($this->m_indicador->getHistoriaByIndicador($idIndicador), $displayHisto);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function generateInsert($orden, $tipo, $tipoEstructura, $idEstructura, $niveles, $idEstAulaGrado, $basico)
    {
        $tipoRegi = null;
        $colorBase = 0.6;
        if ($tipoEstructura == ESTRUCTURA_SNGA || $tipoEstructura == ESTRUCTURA_SNG || $tipoEstructura == ESTRUCTURA_SN || $tipoEstructura == ESTRUCTURA_S) {
            if ($tipo == "INDI") {
                $orden = $basico['__id_indicador'] . '.' . $idEstructura . '.0.0.0.0.0';
                $remp = array(
                    "id_sede" => $idEstructura
                );
                $niveles = array_replace($niveles, $remp);
                $tipoRegi = "SEDE";
                goto a;
            } else 
                if ($tipo == "SEDE") {
                    $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $idEstructura . '.0.0.0.0';
                    $remp = array(
                        "id_nivel" => $idEstructura
                    );
                    $niveles = array_replace($niveles, $remp);
                    $tipoRegi = "NIVEL";
                    $colorBase = 0.4;
                    goto a;
                } else 
                    if ($tipo == "NIVEL") {
                        $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $niveles['id_nivel'] . '.' . $idEstructura . '.0.0.0';
                        $remp = array(
                            "id_grado" => $idEstructura
                        );
                        $niveles = array_replace($niveles, $remp);
                        $tipoRegi = "GRADO";
                        $colorBase = 0.2;
                        goto a;
                    }  // SOLO PARA INGRESAR TODAS LAS AULAS DEL GRADO SELECCIONADO
else 
                        if ($tipo == "GRADO") {
                            $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $niveles['id_nivel'] . '.' . $idEstAulaGrado . '.' . $idEstructura . '.0.0';
                            $remp = array(
                                "id_aula" => $idEstructura,
                                "id_grado" => $idEstAulaGrado
                            );
                            $niveles = array_replace($niveles, $remp);
                            $tipoRegi = "AULA";
                            $colorBase = 0.0;
                            goto a;
                        }
            
            a:
            $mas = array(
                "orden" => $orden,
                "year" => date('Y'),
                "tipo_regi" => $tipoRegi,
                "back_color" => $colorBase
            );
            $basico = $basico + $mas + $niveles;
            goto f;
        } else 
            if ($tipoEstructura == ESTRUCTURA_DN) { // DISIPLINA, NIVEL
                if ($tipo == "INDI") {
                    $orden = $basico['__id_indicador'] . '.' . $idEstructura . '.0.0.0.0.0';
                    $remp = array(
                        "id_disciplina" => $idEstructura
                    );
                    $niveles = array_replace($niveles, $remp);
                    $tipoRegi = "DISCIPLINA";
                    goto b;
                }
                if ($tipo == "DISCIPLINA") {
                    $orden = $basico['__id_indicador'] . '.' . $niveles['id_disciplina'] . '.' . $idEstructura . '.0.0.0.0';
                    $remp = array(
                        "id_nivel" => $idEstructura
                    );
                    $niveles = array_replace($niveles, $remp);
                    $tipoRegi = "NIVEL";
                    $colorBase = 0.0;
                    goto b;
                }
                b:
                $mas = array(
                    "orden" => $orden,
                    "year" => date('Y'),
                    "tipo_regi" => $tipoRegi,
                    "back_color" => $colorBase
                );
                $basico = $basico + $mas + $niveles;
                goto f;
            } else 
                if ($tipoEstructura == ESTRUCTURA_SNA) { // SEDE, NIVEL, AREA
                    if ($tipo == "INDI") {
                        $orden = $basico['__id_indicador'] . '.' . $idEstructura . '.0.0.0.0.0';
                        $remp = array(
                            "id_sede" => $idEstructura
                        );
                        $niveles = array_replace($niveles, $remp);
                        $tipoRegi = "SEDE";
                        goto c;
                    } else 
                        if ($tipo == "SEDE") {
                            $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $idEstructura . '.0.0.0.0';
                            $remp = array(
                                "id_nivel" => $idEstructura
                            );
                            $niveles = array_replace($niveles, $remp);
                            $tipoRegi = "NIVEL";
                            $colorBase = 0.3;
                            goto c;
                        } else 
                            if ($tipo == "NIVEL") {
                                $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $niveles['id_nivel'] . '.' . $idEstructura . '.0.0.0';
                                $remp = array(
                                    "id_area" => $idEstructura
                                );
                                $niveles = array_replace($niveles, $remp);
                                $tipoRegi = "AREA";
                                $colorBase = 0.0;
                                goto c;
                            }
                    
                    c:
                    $mas = array(
                        "orden" => $orden,
                        "year" => date('Y'),
                        "tipo_regi" => $tipoRegi,
                        "back_color" => $colorBase
                    );
                    $basico = $basico + $mas + $niveles;
                    goto f;
                } else 
                    if ($tipoEstructura == ESTRUCTURA_SA) { // SEDE, AREA
                        if ($tipo == "INDI") {
                            $orden = $basico['__id_indicador'] . '.' . $idEstructura . '.0.0.0.0.0';
                            $remp = array(
                                "id_sede" => $idEstructura
                            );
                            $niveles = array_replace($niveles, $remp);
                            $tipoRegi = "SEDE";
                            goto d;
                        } else 
                            if ($tipo == "SEDE") {
                                $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $idEstructura . '.0.0.0.0';
                                $remp = array(
                                    "id_area" => $idEstructura
                                );
                                $niveles = array_replace($niveles, $remp);
                                $tipoRegi = "AREA";
                                $colorBase = 0.0;
                                goto d;
                            }
                        d:
                        $mas = array(
                            "orden" => $orden,
                            "year" => date('Y'),
                            "tipo_regi" => $tipoRegi,
                            "back_color" => $colorBase
                        );
                        $basico = $basico + $mas + $niveles;
                        goto f;
                    } else 
                        if ($tipoEstructura == ESTRUCTURA_SG) { // SEDE, GRADO
                            if ($tipo == "INDI") {
                                $orden = $basico['__id_indicador'] . '.' . $idEstructura . '.0.0.0.0.0';
                                $remp = array(
                                    "id_sede" => $idEstructura
                                );
                                $niveles = array_replace($niveles, $remp);
                                $tipoRegi = "SEDE";
                                goto e;
                            } else 
                                if ($tipo == "SEDE") {
                                    $orden = $basico['__id_indicador'] . '.' . $niveles['id_sede'] . '.' . $idEstructura . '.0.0.0.0';
                                    $remp = array(
                                        "id_grado" => $idEstructura
                                    );
                                    $niveles = array_replace($niveles, $remp);
                                    $tipoRegi = "GRADO";
                                    $colorBase = 0.0;
                                    goto e;
                                }
                            e:
                            $mas = array(
                                "orden" => $orden,
                                "year" => date('Y'),
                                "tipo_regi" => $tipoRegi,
                                "back_color" => $colorBase
                            );
                            $basico = $basico + $mas + $niveles;
                            goto f;
                        }
        
        f:
        return $basico;
    }

    function logout()
    {
        $this->session->set_userdata(array(
            "logout" => true
        ));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }

    function getPadre($orden)
    {
        $arr = explode('.', $orden);
        $padre = null;
        $pos = 0;
        $ln = 0;
        $cero = null;
        foreach ($arr as $var) {
            if ($var != 0) {
                $padre .= $var;
                $ln = strlen($var);
                $pos = $pos + $ln;
            } else {
                $cero .= '0';
            }
        }
        
        $padre = substr_replace($padre, '0', $pos - $ln, $ln);
        
        return $padre . $cero;
    }

    /**
     * Crea Tablas dependiendo del tipo de indicador
     */
    function getDetalleIndi()
    {
        $idDecryptIndicador = _decodeCI(_post('idIndDeta'));
        $data['error'] = EXIT_ERROR;
        $data['comboCond'] = 0;
        if ($idDecryptIndicador != null) {
            $detalleIndi = $this->m_deta_indi_modal->getDatosIndicadorDetalleById($idDecryptIndicador);
            if ($detalleIndi['tipo_regi'] == 'AULA') {
                $data['tutor'] = $this->m_utils->getTutorByAula($detalleIndi['id_aula'])['tutor'];
            }
            if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_1) {
                $data = $this->lib_deta_indi->buildTableIndicador1($detalleIndi['id_aula']);
                $data['error'] = EXIT_SUCCESS;
            } else 
                if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_2) {
                    $data += $this->lib_deta_indi->buildTableIndicador2($detalleIndi['id_aula'], $data['tutor']);
                    $data['error'] = EXIT_SUCCESS;
                } else 
                    if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_3) {
                        $data = $this->lib_deta_indi->buildTableIndicador3($detalleIndi['id_grado'], $detalleIndi['id_sede']);
                        $data['error'] = EXIT_SUCCESS;
                    } else 
                        if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_4) {
                            // CREA COMBO AULAS X INDICADOR 4
                            // CREA TABLA INDICADOR 4
                            $data = $this->lib_deta_indi->buildTableIndicador4(null);
                            $data['combo'] = null;
                            $data['combo'] .= '<select id = "selectCombo" name="selectCombo"   data-live-search="true"  onchange="getAlumnosByAulaOrdenMerito();"class="form-control pickerButn">
                                                        <option value="">Selec. Aula</option>';
                            $data['combo'] .= __buildComboAulas($detalleIndi['id_grado'], $detalleIndi['id_sede']);
                            $data['combo'] .= "</select>";
                            $data['comboCond'] = 1;
                            $data['error'] = EXIT_SUCCESS;
                        } else 
                            if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['tipo_eai'] != null) {
                                $data = $this->lib_deta_indi->buildTableIndicador5($detalleIndi['id_aula'], $detalleIndi['tipo_eai']);
                                $data['error'] = EXIT_SUCCESS;
                            }  // INDICADOR 9 Y 10 BUILD 9 (ECE)
else 
                                if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_9) {
                                    $data = $this->lib_deta_indi->buildTableIndicador9($detalleIndi['id_aula'], ECE_MATE);
                                    $data['error'] = EXIT_SUCCESS;
                                } else 
                                    if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_10) {
                                        $data = $this->lib_deta_indi->buildTableIndicador9($detalleIndi['id_aula'], ECE_LECTU);
                                        $data['error'] = EXIT_SUCCESS;
                                    }  // INDICADOR 11 Y 13 BUILD 9
else 
                                        if ($detalleIndi['id_ppu'] != null) {
                                            $data = $this->lib_deta_indi->buildTableIndicador8($detalleIndi['id_ppu'], $detalleIndi['id_sede'], $detalleIndi['id_grado']);
                                            $data['error'] = EXIT_SUCCESS;
                                        }  // INDICADOR 14 BUILD 11
else 
                                            if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_14) {
                                                $data = $this->lib_deta_indi->buildTableIndicador11($detalleIndi['id_sede'], $detalleIndi['id_grado'], PUCP);
                                                $data['error'] = EXIT_SUCCESS;
                                            }  // INDICADOR DEL 15 AL 18 BUILD 12
else 
                                                if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_15) {
                                                    $data = $this->lib_deta_indi->buildTableIndicador12($detalleIndi['id_sede'], $detalleIndi['id_grado'], UNAC);
                                                    $data['error'] = EXIT_SUCCESS;
                                                } else 
                                                    if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_16) {
                                                        $data = $this->lib_deta_indi->buildTableIndicador12($detalleIndi['id_sede'], $detalleIndi['id_grado'], UNI);
                                                        $data['error'] = EXIT_SUCCESS;
                                                    } else 
                                                        if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_17) {
                                                            $data = $this->lib_deta_indi->buildTableIndicador12($detalleIndi['id_sede'], $detalleIndi['id_grado'], UNMSM);
                                                            $data['error'] = EXIT_SUCCESS;
                                                        } else 
                                                            if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_18) {
                                                                $data = $this->lib_deta_indi->buildTableIndicador12($detalleIndi['id_sede'], $detalleIndi['id_grado'], FAUSTINO);
                                                                $data['error'] = EXIT_SUCCESS;
                                                            }  // INDICADOR DEL 19 AL 23 BUILD 10
else 
                                                                if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_19) {
                                                                    $data = $this->lib_deta_indi->buildTableIndicador10($detalleIndi['id_sede'], $detalleIndi['id_grado'], UPCH);
                                                                    $data['error'] = EXIT_SUCCESS;
                                                                } else 
                                                                    if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_20) {
                                                                        $data = $this->lib_deta_indi->buildTableIndicador10($detalleIndi['id_sede'], $detalleIndi['id_grado'], ULIMA);
                                                                        $data['error'] = EXIT_SUCCESS;
                                                                    } else 
                                                                        if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_21) {
                                                                            $data = $this->lib_deta_indi->buildTableIndicador10($detalleIndi['id_sede'], $detalleIndi['id_grado'], UP);
                                                                            $data['error'] = EXIT_SUCCESS;
                                                                        } else 
                                                                            if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_22) {
                                                                                $data = $this->lib_deta_indi->buildTableIndicador10($detalleIndi['id_sede'], $detalleIndi['id_grado'], PUCP);
                                                                                $data['error'] = EXIT_SUCCESS;
                                                                            } else 
                                                                                if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_23) {
                                                                                    $data = $this->lib_deta_indi->buildTableIndicador10($detalleIndi['id_sede'], $detalleIndi['id_grado'], UNMSM);
                                                                                    $data['error'] = EXIT_SUCCESS;
                                                                                }  // INDICADOR DEL 24 AL 28 BUILD 13
else 
                                                                                    if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_24) {
                                                                                        $data = $this->lib_deta_indi->buildTableIndicador13($detalleIndi['id_sede'], $detalleIndi['id_grado'], UPCH);
                                                                                        $data['error'] = EXIT_SUCCESS;
                                                                                    } else 
                                                                                        if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_25) {
                                                                                            $data = $this->lib_deta_indi->buildTableIndicador13($detalleIndi['id_sede'], $detalleIndi['id_grado'], ULIMA);
                                                                                            $data['error'] = EXIT_SUCCESS;
                                                                                        } else 
                                                                                            if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_26) {
                                                                                                $data = $this->lib_deta_indi->buildTableIndicador13($detalleIndi['id_sede'], $detalleIndi['id_grado'], UP);
                                                                                                $data['error'] = EXIT_SUCCESS;
                                                                                            } else 
                                                                                                if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_27) {
                                                                                                    $data = $this->lib_deta_indi->buildTableIndicador13($detalleIndi['id_sede'], $detalleIndi['id_grado'], PUCP);
                                                                                                    $data['error'] = EXIT_SUCCESS;
                                                                                                } else 
                                                                                                    if ($detalleIndi['tipo_regi'] == 'GRADO' && $detalleIndi['__id_indicador'] == INDICADOR_28) {
                                                                                                        $data = $this->lib_deta_indi->buildTableIndicador13($detalleIndi['id_sede'], $detalleIndi['id_grado'], UNMSM);
                                                                                                        $data['error'] = EXIT_SUCCESS;
                                                                                                    }  // INDICADOR DEL 29 AL 31 BUILD 14
else 
                                                                                                        if ($detalleIndi['tipo_regi'] == 'NIVEL' && $detalleIndi['__id_indicador'] == INDICADOR_29) {
                                                                                                            $data = $this->lib_deta_indi->buildTableIndicador14($detalleIndi['id_nivel'], COMPETITIVA, DEPORTIVA, $detalleIndi['id_disciplina']);
                                                                                                            $data['error'] = EXIT_SUCCESS;
                                                                                                        } else 
                                                                                                            if ($detalleIndi['tipo_regi'] == 'NIVEL' && $detalleIndi['__id_indicador'] == INDICADOR_30) {
                                                                                                                $data = $this->lib_deta_indi->buildTableIndicador14($detalleIndi['id_nivel'], FORMATIVA, DEPORTIVA, $detalleIndi['id_disciplina']);
                                                                                                                $data['error'] = EXIT_SUCCESS;
                                                                                                            } else 
                                                                                                                if ($detalleIndi['tipo_regi'] == 'NIVEL' && $detalleIndi['__id_indicador'] == INDICADOR_31) {
                                                                                                                    $data = $this->lib_deta_indi->buildTableIndicador14($detalleIndi['id_nivel'], '', ARTISTICA, $detalleIndi['id_disciplina']);
                                                                                                                    $data['error'] = EXIT_SUCCESS;
                                                                                                                }  // INDICADOR DEL 32-33 BUILD 15
else 
                                                                                                                    if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_32) {
                                                                                                                        $data = $this->lib_deta_indi->buildTableIndicador15($detalleIndi['id_aula'], NO_PARTICIPO);
                                                                                                                        $data['error'] = EXIT_SUCCESS;
                                                                                                                    } else 
                                                                                                                        if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_33) {
                                                                                                                            $data = $this->lib_deta_indi->buildTableIndicador15($detalleIndi['id_aula'], INGRESO);
                                                                                                                            $data['error'] = EXIT_SUCCESS;
                                                                                                                        }  // INDICADOR DEL 34-35 BUILD 16
else 
                                                                                                                            if ($detalleIndi['tipo_regi'] == 'SEDE' && $detalleIndi['__id_indicador'] == INDICADOR_34) {
                                                                                                                                $data = $this->lib_deta_indi->buildTableIndicador16($detalleIndi['id_sede'], CERTIFICADO_EFCE);
                                                                                                                                $data['error'] = EXIT_SUCCESS;
                                                                                                                            } else 
                                                                                                                                if ($detalleIndi['tipo_regi'] == 'SEDE' && $detalleIndi['__id_indicador'] == INDICADOR_35) {
                                                                                                                                    $data = $this->lib_deta_indi->buildTableIndicador16($detalleIndi['id_sede'], CERTIFICADO_NATIVO);
                                                                                                                                    $data['error'] = EXIT_SUCCESS;
                                                                                                                                }  // INDICADOR 36 BUILD 17
else 
                                                                                                                                    if ($detalleIndi['tipo_regi'] == 'AULA' && $detalleIndi['__id_indicador'] == INDICADOR_36) {
                                                                                                                                        $data = $this->lib_deta_indi->buildTableIndicador17($detalleIndi['id_aula']);
                                                                                                                                        $data['error'] = EXIT_SUCCESS;
                                                                                                                                    } else 
                                                                                                                                        if ($detalleIndi['tipo_regi'] == 'AREA' && $detalleIndi['__id_indicador'] == INDICADOR_58) {
                                                                                                                                            $data = $this->lib_deta_indi->buildTableIndicadorDocentesSD($detalleIndi['id_sede'], $detalleIndi['id_nivel'], $detalleIndi['id_area']);
                                                                                                                                            $data['error'] = EXIT_SUCCESS;
                                                                                                                                        }  // INDICADOR 90-101
else 
                                                                                                                                            if (in_array($detalleIndi['__id_indicador'], json_decode(ARRAY_INDI_PROC))) {
                                                                                                                                                $idAreaEncrypt = _encodeCI($detalleIndi['id_area']);
                                                                                                                                                $idSedeEncrypt = _encodeCI($detalleIndi['id_sede']);
                                                                                                                                                $idIndiEncrypt = _encodeCI($detalleIndi['__id_indicador']);
                                                                                                                                                $data['combo'] = null;
                                                                                                                                                $data['combo'] .= '<select id = "selectCombo" name="selectCombo" attr-idindicador="' . $idIndiEncrypt . '" attr-idarea="' . $idAreaEncrypt . '" attr-idsede="' . $idSedeEncrypt . '" data-live-search="true"  onchange="getTablaByArea();"class="form-control pickerButn">
                                           <option value="">Selec. Área</option>';
                                                                                                                                                $data['combo'] .= __buildComboAreasEspecificasByAreaGeneral($detalleIndi['id_area']);
                                                                                                                                                $data['combo'] .= "</select>";
                                                                                                                                                $data['comboCond'] = 1;
                                                                                                                                                $data['error'] = EXIT_SUCCESS;
                                                                                                                                            }
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getAlumnosByAulaMerito()
    {
        $data['error'] = EXIT_ERROR;
        $idAulaDecrypt = $this->encrypt->decode(_post('idAula'));
        if ($idAulaDecrypt != null) {
            $data = $this->lib_deta_indi->buildTableIndicador4($idAulaDecrypt);
            $data['error'] = EXIT_SUCCESS;
        } else {
            $data['error'] = EXIT_ERROR;
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function createLineaTiempoHistoria($data, $displayHisto)
    {
        $result = "";
        $cabecera = '<div class="form floating-label table_distance" style="text-align: center">
	                   <ul class="timeline collapse-lg timeline-hairline" id="historiaCardDiv">';
        $i = 0;
        foreach ($data as $var) {
            $class = "timeline-inverted";
            $time = strtotime($var->audi_fec_regi);
            $newformat = date('d/m/Y h:i:s A', $time);
            $foto = $var->google_foto;
            if ($foto == null) {
                $foto = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $var->foto_persona)) ? RUTA_IMG_PROFILE . 'colaboradores/' . $var->foto_persona : RUTA_IMG_PROFILE . "nouser.svg");
            }
            $result .= '<article class="timeline-entry">
                            <div class="timeline-entry-inner">
                                <div class="timeline-icon bg-success">
                                    <i class="entypo-feather"></i>
                                </div>
                                <div class="timeline-label">
                                    <h2  style="font-size:12px;" >
	                                    <a href="#">
	                                        <img class="img-circle img-responsive pull-left width-1" src="' . $foto . '" alt=""><strong>' . $var->audi_pers_regi . '</strong></a><br/> <span style="font-size:11px;" ><strong>' . $newformat . '</strong></span></h2>
                                    <p>Cambio: ' . $var->tipo_cambio . '</p>
                					<p>Desc: ' . $var->descripcion . '</p>
                				    <p>Anterior: ' . $var->valor_anterior . '</p>
                				    <p>Nuevo: ' . $var->valor_nuevo . '  </p>
                                </div>
                            </div>
                        </article>';
            $i ++;
        }
        if (count($data) == 0) {
            $result = '<img src="' . base_url() . 'public/files/images/indicador/imagen_empty_historico.svg">';
        } else {
            $result = $cabecera . $result . '</ul></div>';
        }
        return $result;
    }

    function pintarDetalle()
    {
        $html = null;
        $data = null;
        $fecha = null;
        $modis = $this->m_indicador->getUltimaModif_ActualFromIndicador(_simpleDecryptInt(_getSesion('id_indicador')));
        if ($modis != null) {
            $fecha = ($modis['audi_ult_modi_actual'] == null) ? null : date('d/m/Y h:i:s A', strtotime($modis['audi_ult_modi_actual']));
            $html .= '<strong>Última Modificación:</strong> ' . $fecha;
            $html .= '<br/><strong>Por:</strong> ' . $modis['audi_ult_pers_modi_actual'];
        }
        
        $data['detalleModal'] = $html;
        $data['fechaModi'] = $fecha;
        $valorAmarillo = $this->m_indicador->getValorAmarilloByIndicador(_simpleDecryptInt(_getSesion('id_indicador')));
        return $data;
    }

    function actualizarActual()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $displayHisto = _post('displayHisto');
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            $tipo_encuesta = $this->m_utils->getById("bsc.indicador", "tipo_encuesta", "_id_indicador", $idIndicador);
            $data = $this->m_indicador->actualizarActualIndicador($idIndicador, $tipo_encuesta);
            if ($data['error'] == EXIT_SUCCESS) {
                $data['tablaHijos'] = $this->createTableHijos($this->m_indicador->getHijosByIndicador($idIndicador));
                $detalle = $this->pintarDetalle();
                $data['detalleModal'] = $detalle['detalleModal'];
                $data['fechaModi'] = $detalle['fechaModi'];
                $data['historiaInd'] = $this->createLineaTiempoHistoria($this->m_indicador->getHistoriaByIndicador($idIndicador), $displayHisto);
                
                $gauge = $this->createGraficoIndicadorGauge();
                $data['contGauge'] = $gauge['contGauge'];
                $data['posicion'] = $gauge['posicion'];
                $data['contMenuGauge'] = $gauge['contMenuGauge'];
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getGraficos()
    {
        $data = $this->createGraficoIndicadorGauge();
        
        $g = $this->getGraficoIndicadorMediciones();
        if ($g != null) {
            $data += $g;
        }
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getGraficoIndicadorMediciones()
    {
        $idIndicadorEncr = _getSesion('id_indicador');
        $idIndicador = _simpleDecryptInt($idIndicadorEncr);
        $grafico = $this->m_grafico->getDataIndicadorMediciones($idIndicador);
        
        $result = $grafico['retval'];
        $data = $this->createGraficoIndicador($result);
        $tipoGraf = $this->m_utils->getById('bsc.indicador', 'tipo_gauge', '_id_indicador', _simpleDecryptInt(_getSesion('id_indicador')));
        $data['ppu'] = 0;
        if ($tipoGraf == GAUGE_PUESTO) {
            $data['ppu'] = 1;
        }
        return $data;
    }

    function createGraficoIndicador($grafico)
    {
        $arrayMetas = array();
        $arrayActuales = array();
        $arrayMediciones = array();
        $i = 1;
        if ($grafico != null) {
            foreach ($grafico as $var) {
                array_push($arrayMetas, $var['valor_meta']);
                array_push($arrayActuales, $var['valor_actual_porcentaje']);
                array_push($arrayMediciones, $i);
                $i ++;
            }
            $data['metas'] = json_encode($arrayMetas);
            $data['actuales'] = json_encode($arrayActuales);
            $data['mediciones'] = json_encode($arrayMediciones);
            
            return $data;
        }
    }

    function cambioRol()
    {
        $idRolEnc = _post($this->_idRol);
        $idRol = _simpleDecryptInt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
        
        $dataUser = array(
            "id_rol" => $idRol,
            "nombre_rol" => $nombreRol
        );
        $this->session->set_userdata($dataUser);
        
        $idRol = _getSesion('nombre_rol');
        
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }

    function createGraficoIndicadorGauge()
    {
        $idIndicadorCrypt = _getSesion('id_indicador');
        $idIndicador = _simpleDecryptInt($idIndicadorCrypt);
        $idObjetivoEnc = _getSesion('id_objetivo');
        $idObjetivo = _simpleDecryptInt($idObjetivoEnc);
        $idUsuario = _getSesion('nid_persona');
        $idRol = _getSesion(BSC_ROL_SESS);
        if ($idRol == ID_ROL_MEDICION) {
            $indicadores = $this->m_indicador->getIndicadoresAsigRespMedicion($idUsuario, 0, $idIndicador);
        } else 
            if ($idRol == ID_ROL_DIRECTOR_CALIDAD) {
                $indicadores = $this->m_indicador->getIndicadoresPlanEstrategico($idIndicador);
            } else {
                $indicadores = $this->m_indicador->getIndicadoresByObjetivo($idObjetivo, 0, $idIndicador);
            }
        $data['contGauge'] = $this->buildContenedorGaugeHTML($indicadores, POS_GAUGE);
        $data['posicion'] = POS_GAUGE;
        $data['contMenuGauge'] = $this->buildMenuGaugeHTML($idIndicadorCrypt, POS_GAUGE);
        return $data;
    }

    function buildContenedorGaugeHTML($indicadores, $posicion)
    {
        $opciones = $this->getOpcionesGauge($indicadores);
        $cont = '<div class="container-gauge cont_linea linEst' . $posicion . '" id="cont' . $posicion . '" attr-posicion ="' . $posicion . '" data-porcentaje="' . $opciones['porcentaje'] . '" data-porcent1="' . $opciones['porcentajeAmarillo'] . '" data-porcent2="' . $opciones['porcentajeVerde'] . '" 
                      data-cBack="' . $opciones['color'] . '" data-inicioG="' . $opciones['inicioG'] . '" data-finG="' . $opciones['finG'] . '" data-tipo="' . $indicadores['tipo_gauge'] . '"
                      data-colorVerde="' . $opciones['colorVerde'] . '" data-colorRojo="' . $opciones['colorRojo'] . '" data-codInd="' . $indicadores['cod_indi'] . '"></div>';
        return $cont;
    }

    function getBackroundColor($amarillo, $verde, $porcentaje, $tipo)
    {
        $color = null;
        if ($porcentaje <= $amarillo) {
            $color = "mdl-state__red";
            if ($tipo == GAUGE_PUESTO) {
                $color = "mdl-state__cyan";
            } else 
                if ($tipo == GAUGE_MAXIMO) {
                    $color = "mdl-state__red";
                } else 
                    if ($tipo == GAUGE_CERO) {
                        $color = "mdl-state__cyan";
                    }
        } else 
            if ($porcentaje < $verde && $porcentaje >= $amarillo) {
                $color = "mdl-state__yellow";
            } else 
                if ($porcentaje >= $verde) {
                    $color = "mdl-state__cyan";
                    if ($tipo == GAUGE_PUESTO) {
                        $color = "mdl-state__red";
                    } else 
                        if ($tipo == GAUGE_MAXIMO) {
                            $color = "mdl-state__cyan";
                        } else 
                            if ($tipo == GAUGE_CERO) {
                                $color = "mdl-state__red";
                            }
                }
        
        return $color;
    }

    function getRepsonsableByIndicador($idIndicador)
    {
        $personas = $this->m_responsable_indicador->getInfoResponsableByIndicador($idIndicador);
        $infoIndicador = $this->m_indicador->getInfoIndicador($idIndicador);
        $max = count($personas);
        $diff = $max - 1;
        $textRespo = null;
        if ($diff > 1) {
            $textRespo = $diff . " responsables";
        } else 
            if ($diff <= 1) {
                $textRespo = $diff . " responsable";
            }
        if (count($personas) >= 2) {
            $max = 1;
        }
        $i = 1;
        $result = null;
        foreach ($personas as $var) {
            $foto = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $var->foto_persona)) ? RUTA_IMG_PROFILE . 'colaboradores/' . $var->foto_persona : RUTA_IMG_PROFILE . "nouser.svg");
            $idPersona = _simple_encrypt($var->nid_persona);
            $foto = $var->google_foto;
            if ($foto == null) {
                $foto = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $var->foto_persona)) ? RUTA_IMG_PROFILE . 'colaboradores/' . $var->foto_persona : RUTA_IMG_PROFILE . "nouser.svg");
            }
            $result .= '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom" data-original-title="' . $var->nombrecompleto . '" onclick="verImagenResponsable(\'' . $foto . '\',\'' . $var->nombrecompleto . '\',\'' . $var->telf_pers . '\',\'' . $var->correo_pers . '\',\'' . $idPersona . '\')"><img src="' . $foto . '" class="img-circle width-1" alt="foto" width="40" height="40"></a>';
            if ($i == $max) {
                break;
            }
            $i ++;
        }
        if (count($personas) >= 2) {
            $result .= '<a onclick="abrirModalVerResponsables();" style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none;color:#757575;" data-toggle="tooltip" data-placement="bottom" data-original-title="y ' . $textRespo . ' más"><div style="width:38px;height:38px;text-align:center;border:3px solid #E5E5E5;border-radius:1000px"><p style="margin-top:4px">+' . $diff . '</p></div></a>';
        }
        if (_getSesion($this->_idRol) == ID_ROL_PROMOTOR) {
            $result .= '<a data-toggle="tooltip" onclick="openModalNewResponsableMedicion();" style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none;" data-placement="bottom" data-original-title="Agregar Responsable"><div><i class="mdi mdi-person_add" style="font-size:23px"></i></div></a>';
        }
        // CUANDO NO TIENE RESPONSABLES DE MEDICION
        if (count($personas) == 0) {
            $result = '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px" data-placement="bottom"><img src="' . (RUTA_IMG . "no_responsable.jpeg") . '" class="img-circle width-1 no_responsable" alt="foto" width="40" height="40"></a>';
        }
        $result .= '<a data-toggle="tooltip" style="cursor:pointer;display:inline-block;margin-right:10px;vertical-align:middle;text-decoration:none" data-placement="bottom" data-original-title="' . $infoIndicador['desc_linea_estrategica'] . ' / ' . $infoIndicador['desc_objetivo'] . '"><div><i class="mdi mdi-info" style="font-size:23px"></i></div></a>';
        return $result;
    }

    function getOpcionesGauge($indicadores)
    {
        $data['colorVerde'] = "#E2574C";
        $data['colorRojo'] = "#43AC6D";
        $data['inicioG'] = 0;
        $data['finG'] = 100;
        $data['porcentaje'] = $indicadores['valor_actual_porcentaje'];
        
        $data['porcentajeVerde'] = $indicadores['valor_meta'];
        $data['porcentajeAmarillo'] = $indicadores['flg_amarillo'];
        $data['dorado'] = 0;
        
        $colores = $this->getBackroundColor($indicadores['flg_amarillo'], $indicadores['valor_meta'], $indicadores['valor_actual_porcentaje'], $indicadores['tipo_gauge']);
        
        if ($indicadores['tipo_gauge'] == GAUGE_PUESTO) {
            $data['colorVerde'] = "#43AC6D";
            $data['colorRojo'] = "#E2574C";
            $data['inicioG'] = 1;
            $data['porcentajeVerde'] = $indicadores['flg_amarillo'];
            $data['porcentajeAmarillo'] = $indicadores['valor_meta'];
            $data['finG'] = $indicadores['valor_actual_porcentaje'] + $indicadores['flg_amarillo'];
            if ($indicadores['valor_actual_porcentaje'] == 0) {
                $data['finG'] = $indicadores['valor_meta'] + $indicadores['flg_amarillo'];
                $data['porcentaje'] = 100;
                $data['color'] = "#E2574C";
            }
            
            $colores = $this->getBackroundColor($indicadores['valor_meta'], $indicadores['flg_amarillo'], $indicadores['valor_actual_porcentaje'], $indicadores['tipo_gauge']);
        } 

        else 
            if ($indicadores['tipo_gauge'] == GAUGE_MAXIMO) {
                $data['colorVerde'] = "#E2574C";
                $data['colorRojo'] = "#43AC6D";
                $data['inicioG'] = 0;
                $data['finG'] = $indicadores['valor_actual_porcentaje'] + $indicadores['valor_meta'];
                if ($indicadores['valor_actual_porcentaje'] == 0) {
                    $data['finG'] = $indicadores['valor_meta'] + $indicadores['flg_amarillo'];
                    $data['porcentaje'] = 0;
                    $data['color'] = "#E2574C";
                }
                
                $colores = $this->getBackroundColor($indicadores['flg_amarillo'], $indicadores['valor_meta'], $indicadores['valor_actual_porcentaje'], $indicadores['tipo_gauge']);
            } 

            else 
                if ($indicadores['tipo_gauge'] == GAUGE_CERO) {
                    $data['colorVerde'] = "#43AC6D";
                    $data['colorRojo'] = "#E2574C";
                    $data['inicioG'] = 0;
                    $data['finG'] = 100;
                    $data['porcentajeVerde'] = $indicadores['flg_amarillo'];
                    $data['porcentajeAmarillo'] = $indicadores['valor_meta'];
                    
                    $colores = $this->getBackroundColor($indicadores['valor_meta'], $indicadores['flg_amarillo'], $indicadores['valor_actual_porcentaje'], $indicadores['tipo_gauge']);
                } 

                else 
                    if ($indicadores['tipo_gauge'] == GAUGE_REDUCCION) {
                        $data['colorVerde'] = "#E2574C";
                        $data['colorRojo'] = "#43AC6D";
                        $data['inicioG'] = $indicadores['valor_actual_porcentaje'] - 10;
                        $data['finG'] = $indicadores['valor_actual_porcentaje'] + 3 * $indicadores['valor_meta'];
                        $data['porcentajeVerde'] = $indicadores['valor_meta'];
                        $data['porcentajeAmarillo'] = $indicadores['flg_amarillo'];
                        
                        $colores = $this->getBackroundColor($indicadores['valor_meta'], $indicadores['flg_amarillo'], $indicadores['dorado'], $indicadores['valor_actual_porcentaje'], $indicadores['tipo_gauge']);
                    }
        
        $data['color'] = $colores;
        
        return $data;
    }

    function buildMenuGaugeHTML($idIndicador, $pos)
    {
        $menu = '   <button id="menu-gauge" class="mdl-button mdl-js-button mdl-button--icon">
	                   <i class="mdi mdi-more_vert"></i>
                    </button>
 	                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-gauge">
		               <li class="mdl-menu__item" onclick="openModalEditarValorAmarilloDetalle(\'' . $idIndicador . '\' , \'cont' . $pos . '\' , \'' . $pos . '\')"><i class="mdi mdi-edit"></i> Editar valor</li>
	                </ul>';
        return $menu;
    }

    function getValorAmarillo()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idIndicadorCrypt = _post('idIndicador');
            $dataUser = array(
                "id_indicador" => $idIndicadorCrypt
            );
            $idIndicador = _simpleDecryptInt($idIndicadorCrypt);
            $this->session->set_userdata($dataUser);
            $idCont = _post('idCont');
            $pos = _post('pos');
            if ($idIndicador == null) {
                throw new Exception('El id de indicador no es válido');
            }
            $valorAmarillo = $this->m_indicador->getValorAmarilloByIndicador($idIndicador);
            $data['valorAmarillo'] = $valorAmarillo['flg_amarillo'];
            $data['valorMeta'] = $valorAmarillo['valor_meta'];
            $data['error'] = EXIT_SUCCESS;
            $dataUser = array(
                'idCont' => $idCont,
                'posicion' => $pos
            );
            $this->session->set_userdata($dataUser);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function editValorAmarillo()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            $valorAmarillo = _post('valor');
            $idObjetivoEnc = _getSesion('id_objetivo');
            $idObjetivo = _simpleDecryptInt($idObjetivoEnc);
            $idUsuario = _getSesion('nid_persona');
            $idRol = _getSesion($this->_idRol);
            $posicion = _getSesion('posicion');
            $idCont = _getSesion('idCont');
            $data = _updateValorAmarilloByIndicador($idIndicador, $valorAmarillo, $idObjetivo, $idUsuario, $idRol, $posicion, $idCont);
            if ($idRol == ID_ROL_MEDICION) {
                $indicadores = $this->m_indicador->getIndicadoresAsigRespMedicion($idUsuario, 0, $idIndicador);
            } else {
                $indicadores = $this->m_indicador->getIndicadoresByObjetivo($idObjetivo, 0, $idIndicador);
            }
            $data['contGauge'] = $this->buildContenedorGaugeHTML($indicadores, $posicion);
            $data['idCont'] = $idCont;
            $data['posicion'] = $posicion;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /*
     * INICIO MODAL POPUP
     * AGREGAR PERSONAS RESPONSABLES DE MEDICION
     * 02/11/2015
     */
    function buildTablePersonaAddIndicadorHTML($idIndicador, $nombrePersona)
    {
        $listaTable = ($nombrePersona != null) ? $this->m_responsable_indicador->getAllPersonasByNombre($idIndicador, $nombrePersona) : array();
        $tmpl = array(
            'table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_persona_by_nombre">',
            'table_close' => '</table>'
        );
        $this->table->set_template($tmpl);
        
        $head_0 = array(
            'data' => '#',
            'class' => 'text-left'
        );
        $head_1 = array(
            'data' => 'Datos',
            'class' => 'text-left'
        );
        $head_2 = array(
            'data' => 'Indicador',
            'class' => 'text-center'
        );
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 1;
        
        foreach ($listaTable as $row) {
            $check_indicador = ($row->flg_acti == '1') ? 'checked' : null;
            $idCryptPersona = $this->encrypt->encode($row->nid_persona);
            $idCryptIndicador = $this->encrypt->encode($idIndicador);
            $row_col0 = array(
                'data' => $val,
                'class' => 'text-left'
            );
            $row_col1 = array(
                'data' => $row->nombrecompleto,
                'class' => 'text-left'
            );
            $row_col2 = array(
                'data' => '  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="indicador' . $val . '"> 
                                                        <input type="checkbox"  id="indicador' . $val . '" class="mdl-checkbox__input" ' . $check_indicador . ' attr-bd="' . $check_indicador . '" attr-idpersona="' . $idCryptPersona . '" attr-cambio="false" attr-idindicador="' . $idCryptIndicador . '" onchange="cambioCheckIndicador(this);">
                                                        <span class="mdl-checkbox__label"></span>
                                                    </label>',
                'class' => 'text-center'
            );
            $val ++;
            $this->table->add_row($row_col0, $row_col1, $row_col2);
        }
        
        $tabla = $this->table->generate();
        return $tabla;
    }

    function tablePersonasAddIndicador()
    {
        $data['error'] = EXIT_ERROR;
        try {
            $nombrePersona = _post('nombrePersona');
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            if (_getSesion($this->_idRol) == ID_ROL_PROMOTOR) {
                if ($nombrePersona != null) {
                    $data['tablePersonasModal'] = $this->buildTablePersonaAddIndicadorHTML($idIndicador, $nombrePersona);
                    $data['error'] = EXIT_SUCCESS;
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function grabarIndicadoresPersona()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $myPostData = json_decode(_post('personas'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            foreach ($myPostData['persona'] as $key => $persona) {
                $logeoUsario = _getSesion('nid_persona');
                $nombrePersona = _getSesion('nombre_completo');
                $idPersona = $this->encrypt->decode($persona['idPersona']);
                if ($idPersona == null) {
                    throw new Exception(ANP);
                }
                $newVal = ($persona['valor'] == null) ? '0' : '1';
                $condicion = $this->m_responsable_indicador->evaluaInsertUpdate($idPersona, $idIndicador);
                $arrayDatos = array(
                    "flg_acti" => $newVal,
                    "__id_persona" => $idPersona,
                    "__id_indicador" => $idIndicador,
                    "year" => date("Y"),
                    "audi_id_usua" => $logeoUsario,
                    "audi_nomb_usua" => $nombrePersona,
                    "audi_id_modi" => $logeoUsario,
                    "audi_nomb_modi" => $nombrePersona,
                    "audi_fec_modi" => date('D, d M Y H:i:s'),
                    "condicion" => $condicion
                );
                array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_responsable_indicador->updateInsertIndicadorPersona($arrayGeneral);
            if ($data['error'] == EXIT_SUCCESS) {
                $data['responsables'] = $this->getRepsonsableByIndicador($idIndicador);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    // /FIN MODAL POPUP///
    function setIdSistemaInSession()
    {
        $idSistema = $this->encrypt->decode(_post('id_sis'));
        $idRol = $this->encrypt->decode(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function enviarFeedBack()
    {
        $nombre = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje, $url, $nombre);
    }

    function getTablaByArea()
    {
        $idAreaEsp = _decodeCI(_post('idAreaEspecifica'));
        $idAreaGen = _decodeCI(_post('idAreaGeneral'));
        $idSede = _decodeCI(_post('idSede'));
        $idIndicador = _decodeCI(_post('idIndi'));
        $data['error'] = EXIT_SUCCESS;
        $data['msj'] = null;
        try {
            if ($idAreaEsp == null || $idAreaGen == null || $idSede == null || $idIndicador == null) {
                throw new Exception(null);
            }
            if ($idIndicador == INDICADOR_90 || $idIndicador == INDICADOR_92) {
                $data = $this->lib_deta_indi->buildTableIndicador90($idSede, $idAreaGen, $idAreaEsp, $idIndicador);
            } else 
                if ($idIndicador == INDICADOR_94) {
                    $data = $this->lib_deta_indi->buildTableIndicador94($idSede, $idAreaGen, $idAreaEsp);
                } else 
                    if ($idIndicador == INDICADOR_95 || $idIndicador == INDICADOR_96 || $idIndicador == INDICADOR_100 || $idIndicador == INDICADOR_101) {
                        $data = $this->lib_deta_indi->buildTableIndicadorIncidencia($idSede, $idAreaGen, $idAreaEsp, $idIndicador);
                    } else 
                        if ($idIndicador == INDICADOR_98 || $idIndicador == INDICADOR_99) {
                            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
                            $year = $this->m_indicador->getINDI_indicadorDetalle($idIndicador, 'year')['year'];
                            $data = $this->lib_deta_indi->buildTablePuntualidad_Asistencia($idSede, $idAreaGen, $idAreaEsp, $idIndicador, $year);
                        }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDetalleAsistenciaPuntualidad()
    {
        $nif = _decodeCI(_post('nif'));
        $data['tabla'] = null;
        if ($nif != null) {
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            $year = $this->m_indicador->getINDI_indicadorDetalle($idIndicador, 'year')['year'];
            $listaTable = $this->m_deta_indi_modal->getDetalleAsistencia_Puntualidad(trim($nif), $year);
            $tmpl = array(
                'table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-page-size="10"
			                                       data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                       data-show-columns="true" id="tb_deta_asis_punt">',
                
                'table_close' => '</table>'
            );
            $this->table->set_template($tmpl);
            $head_0 = array(
                'data' => '#',
                'class' => 'col-sm-1'
            );
            $head_1 = array(
                'data' => 'Fecha/Hora',
                'class' => 'col-sm-3'
            );
            $head_2 = array(
                'data' => 'Hora pactada',
                'class' => 'col-sm-2'
            );
            $head_3 = array(
                'data' => 'Detalle',
                'class' => 'col-sm-3'
            );
            $head_4 = array(
                'data' => 'Terminal',
                'class' => 'col-sm-2'
            );
            $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
            $val = 0;
            foreach ($listaTable as $row) {
                $val ++;
                $clase = (($row->descr == 'TEMPRANO') ? 'bg-success' : ($row->descr == 'TARDE' ? 'bg-danger' : null));
                $row_col0 = array(
                    'data' => $val,
                    'class' => $clase
                );
                $row_col1 = array(
                    'data' => $row->fech_hora_marcaje,
                    'class' => $clase
                );
                $row_col2 = array(
                    'data' => $row->hora_pactada,
                    'class' => $clase
                );
                $row_col3 = array(
                    'data' => $row->descr,
                    'class' => $clase
                );
                $row_col4 = array(
                    'data' => $row->terminal,
                    'class' => $clase
                );
                $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3, $row_col4);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tabla'] = $this->table->generate();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function verResponsablesIndicador()
    {
        $data = null;
        $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
        $personas = $this->m_responsable_indicador->getInfoResponsableByIndicador($idIndicador);
        $result = null;
        foreach ($personas as $var) {
            $idPersona = $this->lib_utils->simple_encrypt($var->nid_persona);
            $foto = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $var->foto_persona)) ? RUTA_IMG_PROFILE . 'colaboradores/' . $var->foto_persona : RUTA_IMG_PROFILE . "nouser.svg");
            $result .= '<div class="mdl-list__item col-sm-6" onclick="goToPerfilUsuario(\'' . $idPersona . '\')">
                            <span class="mdl-list__item-primary-content">
                                <img class="mdl-list__item-avatar" src="' . $foto . '"></i>
                                <span>' . $var->nombrecompleto . '</span>
                            </span>
                        </div>';
        }
        $data['personas'] = $result;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function cerrarIndicador()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            // $displayHisto = _post('displayHisto');
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            $data = $this->m_indicador->cerrarIndicador_CTRL($idIndicador);
            /*
             * if($data['error'] == EXIT_SUCCESS) {
             * $data['tablaHijos'] = $this->createTableHijos($this->m_indicador->getHijosByIndicador($idIndicador));
             * $detalle = $this->pintarDetalle();
             * $data['detalleModal'] = $detalle['detalleModal'];
             * $data['fechaModi'] = $detalle['fechaModi'];
             * $data['historiaInd'] = $this->createLineaTiempoHistoria($this->m_indicador->getHistoriaByIndicador($idIndicador), $displayHisto);
             *
             * $gauge = $this->createGraficoIndicadorGauge();
             * $data['contGauge'] = $gauge['contGauge'];
             * $data['posicion'] = $gauge['posicion'];
             * $data['contMenuGauge'] = $gauge['contMenuGauge'];
             * }
             */
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function grabarComparativasIndicador()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idIndicador = _simpleDecryptInt(_getSesion('id_indicador'));
            if ($idIndicador == null) {
                throw new Exception(ANP);
            }
            $myPostData = json_decode(_post('comparativas'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            foreach ($myPostData['comparativa'] as $key => $comparativa) {
                $idComparativa = _decodeCI($comparativa['idComparativa']);
                $newVal = ($comparativa['valor'] == null) ? '0' : '1';
                $yearActual = date('Y');
                
                $condicion = $this->m_comparativa->evaluaInsertUpdateComparativaXIndicador($idIndicador, $idComparativa);
                if ($idComparativa == null) {
                    throw new Exception(ANP);
                }
                $arrayDatos = array(
                    "__id_indicador" => $idIndicador,
                    "__id_comparativa" => $idComparativa,
                    "flg_acti" => $newVal,
                    "year_comparativa" => $yearActual,
                    "condicion" => $condicion['cuenta']
                );
                array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_comparativa->updateInsertComparativasXIndicador($arrayGeneral);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('No se registraron los datos');
            }
            $data['tablaCompXIndi'] = __buildTableAsignarComprativasXIndicador($idIndicador);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getResponsablesIndicador()
    {
        $idIndicador = _simpleDecryptInt(_getSesion("id_indicador"));
        $result = $this->m_responsable_indicador->getInfoResponsableByIndicador($idIndicador);
        $data['table'] = $this->buildTableResponsables($result);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function buildTableResponsables($result)
    {
        $tmpl = array(
            'table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_responsables">',
            'table_close' => '</table>'
        );
        $this->table->set_template($tmpl);
        $head_0 = array(
            'data' => '#',
            'style' => 'text-align:left'
        );
        $head_1 = array(
            'data' => 'Responsable',
            'style' => 'text-align:left'
        );
        $this->table->set_heading($head_0, $head_1);
        $val = 1;
        foreach ($result as $row) {
            $foto = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $row->foto_persona)) ? RUTA_IMG_PROFILE . 'colaboradores/' . $row->foto_persona : RUTA_IMG_PROFILE . "nouser.svg");
            $foto = $row->google_foto;
            if ($foto == null) {
                $foto = ((file_exists(FOTO_PROFILE_PATH . 'colaboradores/' . $row->foto_persona)) ? RUTA_IMG_PROFILE . 'colaboradores/' . $row->foto_persona : RUTA_IMG_PROFILE . "nouser.svg");
            }
            $img = '<div style="display:flex"><img src="' . $foto . '" class="img-circle width-1" alt="foto" width="30" height="30">' . $row->nombrecompleto . '</div>';
            $row_col0 = array(
                'data' => $val,
                'class' => 'text-left'
            );
            $row_col1 = array(
                'data' => $img,
                'class' => 'text-left'
            );
            $this->table->add_row($row_col0, $row_col1);
            $val ++;
        }
        return $this->table->generate();
    }
}