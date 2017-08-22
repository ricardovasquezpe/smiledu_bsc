<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildComboCantidadNivelesRubrica')) {
    //Evaluacion Maestra Valores Tabla evmvalo
    function __buildComboCantidadNivelesRubrica() {
        $CI =& get_instance();
        $valores = explode(';', CANT_NIVELES_RUBRICA);
        $opcion = '';
        foreach ($valores as $val) {
            $opcion .= '<option value="'._simple_encrypt($val).'">'.$val.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboIndicadoresDocentes')) {
    function __buildComboIndicadoresDocentes($arryIndicadores) {
        $CI =& get_instance();
        $opt = null;
        foreach($arryIndicadores as $indi) {
            $opt .= '<option value="'.$CI->encrypt->encode($indi->nid_indicador).'">'.$indi->desc_indicador.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboByRol')) {
    function __buildComboByRol($idRol, $tipoEncryp = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $people = $CI->m_utils->getPersonalByRol($idRol);
        $opcion = null;
        foreach ($people as $var) {
            if($tipoEncryp != null) {
                $idPersona = _simple_encrypt($var->nid_persona);
            } else {
                $idPersona = $CI->encrypt->encode($var->nid_persona);
            }
            $opcion	.= '<option value='.$idPersona.'>'.$var->nombre_docente.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboNiveles')) {
    function __buildComboNiveles($tipoEncryp = null,$valueSelected = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $niveles = $CI->m_utils->getNivelesEducativos();
        $opcion = '';
        foreach ($niveles as $var) {
            if($tipoEncryp != null) {
                $idnivel = _simple_encrypt($var->nid_nivel);
            } else {
                $idnivel = $CI->encrypt->encode($var->nid_nivel);
            }
            $selected = ($valueSelected == $var->nid_nivel) ? 'selected' : null;
            $opcion	.= '<option '.$selected.' value='.$idnivel.'>'.strtoupper($var->desc_nivel).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboPPU')) {
    function __buildComboPPU() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $ppu = $CI->m_utils->getPPU();
        $opcion = null;
        foreach ($ppu as $ppudesc) {
            $idPPu = $CI->encrypt->encode($ppudesc->id_ppu);
            $opcion .= '<option value="'.$idPPu.'">'.strtoupper($ppudesc->desc_ppu).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboRoles')) {
    function __buildComboRoles($tipoEncryp = null){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $roles = $CI->m_utils->getRoles();
        $opcion = null;
        foreach ($roles as $rol) {
            if($tipoEncryp == 2){
                $idRol = ($rol->nid_rol);
            } else if($tipoEncryp != null) {
                $idRol = _simple_encrypt($rol->nid_rol);
            } else {
                $idRol = $CI->encrypt->encode($rol->nid_rol);
            }
            $opcion .= '<option value="'.$idRol.'">'._ucwords($rol->desc_rol).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboSedes')) {
    function __buildComboSedes($tipoEncryp = null, $valueSelect = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $sedes = $CI->m_utils->getSedes();
        $opcion = null;
        foreach ($sedes as $sed) {
            $idSede = null;
            if($tipoEncryp != null) {
                $idSede = _simple_encrypt($sed->nid_sede);
            } else {
                $idSede = $CI->encrypt->encode($sed->nid_sede);
            }
            $selected = "";
            if($valueSelect == $sed->nid_sede) {
                $selected = 'selected';
            }
            $opcion .= '<option '.$selected.' value="'.$idSede.'">'._ucwords($sed->desc_sede).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboSedesEcologicaTemp')) {
    function __buildComboSedesEcologicaTemp($tipoEncryp = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $sedes = $CI->m_utils->getSedesEcologica();
        $opcion = null;
        foreach ($sedes as $sed) {
            if($tipoEncryp != null) {
                $idSede = _simple_encrypt($sed->nid_sede);
            } else {
                $idSede = $CI->encrypt->encode($sed->nid_sede);
            }
            $opcion .= '<option value="'.$idSede.'" selected>'._ucwords($sed->desc_sede).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboUniversidades')) {
    function __buildComboUniversidades() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $univs = $CI->m_utils->getUniversidades();
        $opcion = null;
        foreach ($univs as $uni) {
            $idUniv = $CI->encrypt->encode($uni->id_universidad);
            $opcion .= '<option value="'.$idUniv.'">'._ucwords($uni->desc_univ).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboNivelesBySede')) {
    function __buildComboNivelesBySede($idSede, $tipoEncryp = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $niveles = $CI->m_utils->getNivelesBySede($idSede);
        $opcion = null;
        foreach ($niveles as $nivel){
            if($tipoEncryp != null) {
                $idNivel = _simple_encrypt($nivel->nid_nivel);
            } else {
                $idNivel = $CI->encrypt->encode($nivel->nid_nivel);
            }
            $opcion .= '<option value="'.$idNivel.'">'._ucwords($nivel->desc_nivel).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboNivelesBySedeYear')) {
    function __buildComboNivelesBySedeYear($idSede, $year, $tipoEncryp = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $niveles = $CI->m_utils->getNivelesBySedeYear($idSede, $year);
        $opcion = null;
        foreach ($niveles as $nivel){
            if($tipoEncryp != null) {
                $idNivel = _simple_encrypt($nivel->nid_nivel);
            } else {
                $idNivel = $CI->encrypt->encode($nivel->nid_nivel);
            }
            $opcion .= '<option value="'.$idNivel.'">'._ucwords($nivel->desc_nivel).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboNivelesSecundariaBySede')) {
    function __buildComboNivelesSecundariaBySede($idSede) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $niveles = $CI->m_utils->getNiveleSecundariasBySede($idSede);
        $opcion = null;
        foreach ($niveles as $nivel) {
            $idNivel = $CI->encrypt->encode($nivel->nid_nivel);
            $opcion .= '<option value="'.$idNivel.'">'.$nivel->desc_nivel.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGrados')) {
    function __buildComboGrados($idSede){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosBySede($idSede);
        $opcion = null;
        foreach ($grados as $grad) {
            $idGrado = $CI->encrypt->encode($grad->nid_grado);
            $opcion .= '<option value="'.$idGrado.'">'.$grad->desc_grado.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradoNivelBySede')) {
    function __buildComboGradoNivelBySede($idSede, $tipoEncryp = null) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $sedes = $CI->m_utils->getGradosNivelBySede($idSede);
        $opcion = null;
        foreach ($sedes as $sed) {
            if($tipoEncryp != null) {
                $idGradNivel = _simple_encrypt($sed->id_grado_nivel);
            } else {
                $idGradNivel = $CI->encrypt->encode($sed->id_grado_nivel);
            }
            $opcion .= '<option value="'.$idGradNivel.'">'.$sed->grado_nivel.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradosBySedeAll')) {
    function __buildComboGradosBySedeAll($idSede) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosBySedeAll($idSede);
        $opcion = null;
        foreach ($grados as $grad) {
            $opcion .= '<option value="'.$CI->encrypt->encode($grad->nid_grado).'">'.strtoupper($grad->desc_grado).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradosByNivel')) {
    /**
     * Construye el html para el combo de grados segun un nivel
     * @author cesar 17.09.2015
     * @param Integer $idNivel
     * @return string los option en html
     */
    function __buildComboGradosByNivel($idNivel, $idSede, $tipoEncryp = null,$valueSelected = null){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosByNivel($idNivel, $idSede);
        $opcion = null;
        foreach ($grados as $grad) {
            if($tipoEncryp != null) {
                $idgrado = _simple_encrypt($grad->nid_grado);
            } else {
                $idgrado = $CI->encrypt->encode($grad->nid_grado);
            }
            $selected = ($valueSelected == $grad->nid_grado) ? 'selected' : null;
            $opcion .= '<option '.$selected.' value="'.$idgrado.'">'.strtoupper($grad->desc_grado).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradosByNivel_SinAula')) {
    /**
     * Construye el html para el combo de grados segun un nivel
     * @author cesar 17.09.2015
     * @param Integer $idNivel
     * @return string los option en html
     */
    function __buildComboGradosByNivel_SinAula($idNivel, $tipoEncryp = null){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosByNivel_sinAula($idNivel);
        $opcion = null;
        foreach ($grados as $grad) {
            if($tipoEncryp != null) {
                $idgrado = _simple_encrypt($grad->nid_grado);
            } else {
                $idgrado = $CI->encrypt->encode($grad->nid_grado);
            }
            $opcion .= '<option value="'.$idgrado.'">'.($grad->desc_grado).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradosByNivelYear')) {
    /**
     * Construye el html para el combo de grados segun un nivel (variacion usando year)
     * @author dfloresgonz 09.10.2016
     * @param Integer $idNivel
     * @return string los option en html
     */
    function __buildComboGradosByNivelYear($idNivel, $idSede, $year, $tipoEncryp = null){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosByNivelYear($idNivel, $idSede, $year);
        $opcion = null;
        foreach ($grados as $grad) {
            if($tipoEncryp != null) {
                $idgrado = _simple_encrypt($grad->nid_grado);
            } else {
                $idgrado = $CI->encrypt->encode($grad->nid_grado);
            }
            $opcion .= '<option value="'.$idgrado.'">'.strtoupper($grad->desc_grado).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboNrosSimulacro')) {
    function __buildComboNrosSimulacro($idSede, $idGrado, $idUniv) {
        $CI =& get_instance();
        $CI->load->model('mf_mantenimiento/m_simulacro');
        $simulacros = $CI->m_simulacro->getNrosSimulacros($idSede, $idGrado, $idUniv);
        $opcion = null;
        foreach ($simulacros as $simulacro) {
            $opcion .= '<option value="'._simple_encrypt($simulacro->nro_simulacro).'">SIMULACRO '.$simulacro->nro_simulacro.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGrupoMigracion')) {
    function __buildComboGrupoMigracion($tipo) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grupos = $CI->m_utils->getGrupoMigracionCombo($tipo);
        $opcion = '';
        foreach ($grupos as $grup) {
            $opcion .= '<option value="'._simple_encrypt($grup->grupo_migracion).'">GRUPO '.$grup->grupo_migracion.'</option>';
        }
        return $opcion;
    }
}

///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
/////////////////////////////        CURSOS         ///////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if(!function_exists('__buildComboCursos')) {
    function __buildComboCursos() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $cursos = $CI->m_utils->getCursos();
        $opcion = null;
        foreach ($cursos as $var) {
            $opcion	.= '<option value='.$CI->encrypt->encode($var->id_curso).'>'.strtoupper($var->curso).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboTipoCurso')) {
    function __buildComboTipoCurso() {
        $opt = null;
        $CI =& get_instance();
        $tipoCurso = explode(";", TIPO_CURSO);
        $idx = 1;
        foreach($tipoCurso as $tc){
            $idIndi  = $CI->encrypt->encode($idx);
            $opt .= '<option value="'.$idIndi.'">'.$tc.'</option>';
            $idx++;
        }
        return $opt;
    }
}

///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//////////////////////////////        AULAS         ///////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if(!function_exists('__buildComboAllAulas')) {
    function __buildComboAllAulas() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $aulas = $CI->m_utils->getAllAulas();
        $opcion = null;
        foreach ($aulas as $aul) {
            $opcion .= '<option value="'.$CI->encrypt->encode($aul->nid_aula).'">'.strtoupper($aul->aula).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboAulas')) {
    function __buildComboAulas($idGrado, $id_sede){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $aulas = $CI->m_utils->getAulasByGrado($idGrado, $id_sede);
        $opcion = null;
        foreach ($aulas as $aul) {
            $idAulas = $CI->encrypt->encode($aul->nid_aula);
            $opcion .= '<option value="'.$idAulas.'">'._ucwords($aul->desc_aula).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboAulasYearSede')) {
	function __buildComboAulasYearSede($idGrado, $id_sede, $year){
		$CI =& get_instance();
		$CI->load->model('m_utils');
		$aulas = $CI->m_utils->getAulasByGradoSedeYear($idGrado, $id_sede, $year);
		$opcion = null;
		foreach ($aulas as $aul) {
			$idAulas = $CI->encrypt->encode($aul->nid_aula);
			$opcion .= '<option value="'.$idAulas.'">'._ucwords($aul->desc_aula).'</option>';
		}
		return $opcion;
	}
}

if(!function_exists('__buildComboAulasGrados')) {
    function __buildComboAulasGrados($idGrado) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $aulas = $CI->m_utils->getAulasByGradoSinSede($idGrado);
        $opcion = null;
        foreach ($aulas as $aul) {
            $idAulas = $CI->encrypt->encode($aul->nid_aula);
            $opcion .= '<option value="'.$idAulas.'">'._ucwords($aul->desc_aula).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboSoloAulas')) {
    function __buildComboSoloAulas($idCurso) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $aulas = $CI->m_utils->getAulas($idCurso);
        $opcion = null;
        foreach ($aulas as $aul) {
            $idAulas = $CI->encrypt->encode($aul->nid_aula);
            $opcion .= '<option value="'.$idAulas.'">'._ucwords($aul->desc_aula).'</option>';
        }
        return $opcion;
    }
}

///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//////////////////////////////        AREAS         ///////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if(!function_exists('__buildComboAreasEspecificas')) {
    function __buildComboAreasEspecificas() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $areas = $CI->m_utils->getAllAreasEspecificas();
        $opt = null;
        foreach($areas as $area) {
            $idIndi  = $CI->encrypt->encode($area->id_area);
            $opt .= '<option value="'.$idIndi.'">'._ucwords($area->desc_area).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboAreasEspecificasByAreaGeneral')) {
    function __buildComboAreasEspecificasByAreaGeneral($idAreaGeneral) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $areas = $CI->m_utils->getAllAreasEspecificasByGeneral($idAreaGeneral);
        $opt = null;
        foreach($areas as $area) {
            $opt .= '<option value="'._encodeCI($area['id_area']).'">'._ucwords($area['desc_area']).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboByGrupo')) {
    function __buildComboByGrupo($grupo, $valueSelect = null, $order = 'desc_combo') {
        $CI =& get_instance();
        $combo = $CI->m_utils->getComboTipoByGrupo($grupo, $order);
        $opcion = null;
        foreach ($combo as $row){
            $selected = null;
            if($valueSelect == $row->valor) {
                $selected = 'selected';
            }
            $opcion .= '<option '.$selected.' value="'._simple_encrypt($row->valor).'">'.$row->desc_combo.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboByGrupoNoEncryptId')) {
    function __buildComboByGrupoNoEncryptId($grupo, $valueSelect = null, $order = 'desc_combo') {
        $CI =& get_instance();
        $combo = $CI->m_utils->getComboTipoByGrupo($grupo, $order);
        $opcion = '';
        foreach ($combo as $row) {
            $selected = null;
            if($valueSelect == $row->valor) {
                $selected = 'selected';
            }
            $opcion .= '<option '.$selected.' value="'.($row->valor).'">'.$row->desc_combo.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboColegios')) {
    function __buildComboColegios($valueSelect = null) {
        $CI =& get_instance();
        $combo = $CI->m_utils->getAllColegios();
        $opcion = ' ';
        foreach ($combo as $row) {
            $selected = null;
            if($valueSelect == $row->id_colegio) {
                $selected = 'selected';
            }
            $idValor = _simple_encrypt($row->id_colegio);
            $opcion .= '<option '.$selected.' value="'.$idValor.'">'.utf8_decode(utf8_encode($row->desc_colegio)).'</option>';
        }
    
        return $opcion;
    }
}

if(!function_exists('__buildComboGradoNivel')) {
    function __buildComboGradoNivel() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $gradoNivel = $CI->m_utils->getGradoNivel();
        $opcion = '';
        foreach ($gradoNivel as $gn) {
            $idGradNivel = _simple_encrypt($gn->id_grado_nivel);
            $opcion .= '<option value="'.$idGradNivel.'">'.$gn->grado_nivel.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboUbigeoByTipo')) {
    function __buildComboUbigeoByTipo($idVal, $idVal1, $tipo, $valueSelect = null){
        $CI =& get_instance();
        $combo = null;
        if($tipo == 1) {
            $combo = $CI->m_utils->getAllDepartamentos();
        } else if($tipo == 2) {
            $combo = $CI->m_utils->getAllProvinciaByDepartamento($idVal);
        } else {
            $combo = $CI->m_utils->getAllDistritoByProvincia($idVal, $idVal1);
        }
        $opcion = null;
        foreach ($combo as $row) {
            $selected = null;
            if($valueSelect == $row->cod) {
                $selected = 'selected';
            }
            $idValor = _simple_encrypt($row->cod);
            $opcion .= '<option '.$selected.' value="'.$idValor.'">'.utf8_decode(utf8_encode($row->desc)).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboYearsAcademicos')) {
    function __buildComboYearsAcademicos() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $anios = $CI->m_utils->getYearsAcademicos();
        $opt = null;
        foreach($anios as $row) {
            $opt .= '<option value="'._encodeCI($row['year']).'">'.$row['year'].'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboGradoNivel_All')) {
    function __buildComboGradoNivel_All() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosNivel_All();
        $opcion = null;
        foreach ($grados as $grad) {
            $opcion .= '<option value="'._encodeCI($grad->nid_grado).'">'.$grad->grado_nivel.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboConceptosByTipo')) {
    function __buildComboConceptosByTipo($tipoConcepto){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $conceptos = $CI->m_utils->getAllConceptosByTipo($tipoConcepto);
        $opt = null;
        foreach($conceptos as $row) {
            $opt .= '<option value="'._encodeCI($row->id_concepto).'" data-monto="'.$row->monto_referencia.'">'.$row->desc_concepto.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboCiclosAcad')) {
    function __buildComboCiclosAcad() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $bimestres = $CI->m_utils->getBimestresPosibles();
        $opt = null;
        foreach($bimestres as $row) {
            $opt .= '<option value="'._encodeCI($row['id_ciclo']).'">'.$row['desc_ciclo_acad'].'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboBimestres')) {
    function __buildComboBimestres() {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $bimestres = $CI->m_utils->getBimestres();
        $opt = null;
        foreach($bimestres as $row) {
            $opt .= '<option value="'._encodeCI($row['id_ciclo']).'">'.$row['desc_ciclo_acad'].'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboSedesByYear')) {
    function __buildComboSedesByYear($year){
        $CI =& get_instance();
        $sedes = $CI->m_utils->getSedesByYear($year);
        $opcion = '';
        foreach ($sedes as $sed){
            $idSede = _simple_encrypt($sed->nid_sede);
            $opcion .= '<option value="'.$idSede.'">'.($sed->desc_sede).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradoNivelBySedeYear')) {
    function __buildComboGradoNivelBySedeYear($idSede,$year){
        $CI =& get_instance();
        $gradoNivel = $CI->m_utils->getGradoNivelBySedeYear($idSede, $year);
        $opcion = '';
        foreach ($gradoNivel as $gn){
            $idGradoNivel = _simple_encrypt($gn->id_grado_nivel);
            $opcion .= '<option value="'.$idGradoNivel.'">'.$gn->descrip.'</option>';
        }
        return $opcion;
    }
}


if(!function_exists('__buildComboSedesByID')) {
    function __buildComboSedesByID($arrayIds){
        $CI =& get_instance();
        $sedes = $CI->m_utils->getSedesByIds($arrayIds);
        $opcion = '';
        foreach ($sedes as $sed){
            $idSede = _simple_encrypt($sed->nid_sede);
            $opcion .= '<option value="'.$idSede.'">'.strtoupper($sed->desc_sede).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboPaises')) {
    function __buildComboPaises($tipoEncryp = null, $valueSelect = null){
        $CI =& get_instance();
        $combo = $CI->m_utils->getAllPaises();
        $opcion = ' ';
        foreach ($combo as $row){
            $selected = null;
            if($valueSelect == $row->id_pais) {
                $selected = 'selected';
            }
            if($tipoEncryp != null) {
                $idValor = _simple_encrypt($row->id_pais);
            } else {
                $idValor = $row->id_pais;
            }
            $opcion .= '<option '.$selected.' value="'.$idValor.'">'.utf8_decode(utf8_encode($row->desc_pais)).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboAreasGenerales')) {
    function __buildComboAreasGenerales(){
        $CI =& get_instance();
        $CI->load->model('m_utils_senc');
        $categorias = $CI->m_utils_senc->getAllAreasGenerales();
        $opt = null;
        foreach($categorias as $cat){
            $idArea  = _encodeCI($cat->id_area);
            $opt .= '<option value="'.$idArea.'">'._ucwords($cat->desc_area).'</option>';
        }
        return $opt;
    }
}

// if(!function_exists('__buildComboSedesTemp')) {
//     function __buildComboSedesTemp() {
//         $CI =& get_instance();
//         $CI->load->model('m_utils');
//         $sedes = $CI->m_utils->getSedes();
//         $opcion = null;
//         foreach ($sedes as $sed) {
//             $idSede = $CI->encrypt->encode($sed->nid_sede);
//             $opcion .= '<option value="'.$idSede.'" '.(($sed->nid_sede == 6) ? 'selected' : null).'>'._ucwords($sed->desc_sede).'</option>';
//         }
//         return $opcion;
//     }
// }

if(!function_exists('__buildComboYearByCompromisos')) {
    function __buildComboYearByCompromisos($valueSelect = null) {
        $CI =& get_instance();
        $combo = $CI->m_utils->getAllYearByCompromisos();
        $opcion = null;
        foreach ($combo as $row){
            $selected = null;
            if($valueSelect == $row->year) {
                $selected = 'selected';
            }
            $opcion .= '<option '.$selected.' value="'.($row->year).'">'.$row->year.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboSedesByCompromisos')) {
    function __buildComboSedesByCompromisos($year) {
        $CI =& get_instance();
        $combo = $CI->m_utils->getAllSedesByCompromisos($year);
        $opcion = null;
        foreach ($combo as $row){
            $idSede = _simple_encrypt($row->nid_sede);
            $opcion .= '<option value="'.$idSede.'">'._ucwords($row->desc_sede).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboNivelesBySedeCondicion')) {
    function __buildComboNivelesBySedeCondicion($idSede,$year){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $niveles = $CI->m_utils->getNivelesBySedeCondicion($idSede,$year);
        $opcion = null;
        foreach ($niveles as $nivel){
            $idNivel = _simple_encrypt($nivel->nid_nivel);
            $opcion .= '<option value="'.$idNivel.'">'._ucwords($nivel->desc_nivel).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboGradosByNivelCondicion')) {
    function __buildComboGradosByNivelCondicion($idNivel,$idSede,$year){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $grados = $CI->m_utils->getGradosByNivelCondicion($idNivel,$idSede,$year);
        $opcion = null;
        foreach ($grados as $grado){
            $idgrado = _simple_encrypt($grado->nid_grado);
            $opcion .= '<option value="'.$idgrado.'">'._ucwords($grado->desc_grado).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboAreasByIdGeneral')) {
    function __buildComboAreasByIdGeneral($idAreaGeneral){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $categorias = $CI->m_utils->getAreasByIdAreaGeneral($idAreaGeneral);
        $opt = null;
        foreach($categorias as $cat){
            $idArea  = _encodeCI($cat->id_area);
            $opt .= '<option value="'.$idArea.'">'._ucwords($cat->desc_area).'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboDisciplina')) {
    function __buildComboDisciplina(){
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $disciplinas = $CI->m_utils->getDisciplinas();
        $opcion = '';
        foreach ($disciplinas as $dis){
            $idDisciplina = $CI->encrypt->encode($dis->id_disciplina);
            $opcion .= '<option value="'.$idDisciplina.'">'.$dis->desc_disciplina.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboByGrupos')) {
    function __buildComboByGrupos($grupos) {
        $CI =& get_instance();
        $combo = $CI->m_utils->getComboTipoByGrupos($grupos);
        $arrayCombos = array();
        foreach ($combo as $row){
            $opcion = null;
            $valorDesc = explode(',',$row->valor_desc);
            foreach ($valorDesc as $var){
                $opt = explode('_',$var);
                $opcion .= '<option value="'._simple_encrypt($opt[0]).'">'.$opt[1].'</option>';
            }
            array_push($arrayCombos, $opcion);
        }
        return $arrayCombos;
    }
}

if(!function_exists('__buildComboByGruposNoEncrypt')) {
    function __buildComboByGruposNoEncrypt($grupos) {
        $CI =& get_instance();
        $combo = $CI->m_utils->getComboTipoByGrupos($grupos);
        $arrayCombos = array();
        foreach ($combo as $row){
            $opcion = null;
            $valorDesc = explode(',',$row->valor_desc);
            foreach ($valorDesc as $var){
                $opt = explode('_',$var);
                $opcion .= '<option value="'.$opt[0].'">'.$opt[1].'</option>';
            }
            array_push($arrayCombos, $opcion);
        }
        return $arrayCombos;
    }
}

if(!function_exists('_buildComboSedesRatificacion')) {
    function _buildComboSedesRatificacion($idnivel, $idgrado) {
        $CI =& get_instance();
        $CI->load->model('m_utils');
        $sedes = $CI->m_utils->getSedesRatificacion($idnivel, $idgrado);
        $opcion = null;
        foreach ($sedes as $sed) {
            $idSede = _simple_encrypt($sed->nid_sede);
            $opcion .= '<option value="'.$idSede.'">'.($sed->desc_sede).'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboYearFromNowByCompromisos')) {
    function __buildComboYearFromNowByCompromisos($valueSelect = null) {
        $CI =& get_instance();
        $combo = $CI->m_utils->getYearsFromNowByCompromisos();
        $opcion = null;
        foreach ($combo as $row){
            $selected = null;
            if($valueSelect == $row->year) {
                $selected = 'selected';
            }
            $opcion .= '<option '.$selected.' value="'.($row->year).'">'.$row->year.'</option>';
        }
        return $opcion;
    }
}

if(!function_exists('__buildComboTiposCronograma')) {
    function __buildComboTiposCronograma($opc = null) {
        $CI =& get_instance();
        $tipos = $CI->m_utils->getAllTiposCronograma($opc);
        $opt   = null;
        foreach($tipos as $tip){
            $idCrypt = _encodeCI($tip->id_tipo_cronograma);
            $opt    .= '<option value="'.$idCrypt.'">'.$tip->desc_tipo_cronograma.'</option>';
        }
        return $opt;
    }
}

if(!function_exists('__buildComboBancos')) {
    function __buildComboBancos() {
        $CI =& get_instance();
        $tipos = $CI->m_utils->getAllBancosActivos();
        $opt   = null;
        foreach($tipos as $tip){
            $idCrypt = _encodeCI($tip->id_banco);
            $opt    .= '<option value="'.$idCrypt.'">'.$tip->desc_banco.'</option>';
        }
        return $opt;
    }
}