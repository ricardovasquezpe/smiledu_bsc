<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'C_login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['logOnlySistem']      = 'c_login/logOnlySistem';
$route['logSistem']          = 'c_login/logSistem';
$route['getSistemByUser']    = 'c_login/getSistemByUser';
$route['default_controller'] = 'c_login';

$route['logear']             = 'c_login/log';
$route['logOut']             = 'c_main/logOut';
$route['restablecer']        = 'c_login/enviarCorreoUsuario';
$route['editar']             = 'c_perfil/editarDatosPersona';
$route['changePass']         = 'c_perfil/cambiarContraseña';
$route['checkClaveNow']      = 'c_utils/checkClaveNow';
$route['exiCampo']           = 'c_utils/existeByCampoCtrl';
$route['exiCampoById']       = 'c_utils/existeByCampoCtrlById';
$route['cambiarFoto']        = 'c_perfil/cambiarFoto';
$route['subirFoto']          = 'c_perfil/subirFoto';
$route['irASistemaSess']     = 'cf_mantenimiento/c_mantenimiento/irASistemaSess';
$route['cambiarFoto']        = 'c_perfil/cambiarFoto';
$route['setIdSistemaInSession']        = 'c_main/setIdSistemaInSession';
//SIMULACROS
$route['comboGradosSede']    = 'c_simulacro/comboGradosSede';
$route['comboAulasGrado']    = 'c_simulacro/comboAulasGrado';
$route['getAlumnosFromAula'] = 'c_simulacro/getAlumnosFromAula';
$route['getAlumnosChecked']  = 'c_simulacro/getAlumnosChecked';
$route['getUnivFromTable']   = 'c_simulacro/getUnivFromTable';
$route['checkUniv']          = 'c_simulacro/checkUniv';
//ADMISION
$route['comboGradosSede']    = 'c_admision/comboGradosSede';
$route['comboAulasGrado']    = 'c_admision/comboAulasGrado';
$route['getAlumnosFromAula2'] = 'c_admision/getAlumnosFromAula2';
$route['getAlumnosChecked2']  = 'c_admision/getAlumnosChecked2';
$route['getUnivFromTable2']   = 'c_admision/getUnivFromTable2';
$route['checkUniv2']          = 'c_admision/checkUniv2';
//DISCIPLINAS
$route['getDisciplinas']     = 'c_disciplina/getDisciplinasByTipo';
$route['insCompetencia']     = 'cf_mantenimiento/c_disciplina/insCompetencia';
//CERTIFICACION DOCENTES
$route['grabarDocentesIngles'] = 'c_cert_ing_doc/grabarDocentesIngles';
//ALUMNO EAI
$route['comboSedesNivel']           = 'c_alumno_eai/comboSedesNivel';
$route['getComboGradoByNivel_Ctrl'] = 'c_alumno_eai/getComboGradoByNivel_Ctrl';
$route['comboAulasByGradoUtils']    = 'c_alumno_eai/comboAulasByGradoUtils';
$route['getAlumnosFromAula']        = 'c_alumno_eai/getAlumnosFromAula';
$route['grabarEAI']                  = 'c_alumno_eai/grabarEAI';
//ALUMNO ECE
$route['comboSedesNivelEce']           = 'c_ece_alumnos/comboSedesNivelEce';
$route['getComboGradoByNivel_CtrlEce'] = 'c_ece_alumnos/getComboGradoByNivel_CtrlEce';
$route['comboAulasByGradoUtilsEce']    = 'c_ece_alumnos/comboAulasByGradoUtilsEce';
$route['getAlumnosFromAulaEce']        = 'c_ece_alumnos/getAlumnosFromAulaEce';
$route['grabarECE']                    = 'c_ece_alumnos/grabarECE';
//ROLES PERMISOS SISTEMAS
$route['getRolesFromSistema']           = 'c_roles_permisos_sistemas/getRolesFromSistema';
$route['getSistemaFromPermiso']         = 'c_roles_permisos_sistemas/getSistemaFromPermiso';
$route['grabarRolesSistema']         = 'c_roles_permisos_sistemas/grabarRolesSistema';
$route['grabarSistemaPermiso']         = 'c_roles_permisos_sistemas/grabarSistemaPermiso';
//GRADOS PPU
$route['comboGradosNivel']           = 'c_grado_ppu/comboGradosNivel';
$route['getPPUfromGrado']         = 'c_grado_ppu/getPPUfromGrado';
$route['grabarGradoPuestos']         = 'c_grado_ppu/grabarGradoPuestos';
$route['getAllAulaByAlumno']         = 'c_grado_ppu/getAllAulaByAlumno';
$route['comboGradosAula']         = 'c_grado_ppu/comboGradosAula';
$route['grabarAlumnosPuestos']         = 'c_grado_ppu/grabarAlumnosPuestos';
//ENCUESTA CLIENTE Y FAMILIA
$route['comboSedesNivel']           = 'c_en_cli_fa/comboSedesNivel';
$route['getComboGradoByNivel_Ctrl'] = 'c_en_cli_fa/getComboGradoByNivel_Ctrl';
$route['comboAulasByGradoUtils']    = 'c_en_cli_fa/comboAulasByGradoUtils';
$route['grabarIndClientes']         = 'c_en_cli_fa/grabarIndClientes';
$route['getTablaByFiltro']         = 'c_en_cli_fa/getTablaByFiltro';
//PERSONAL ADMINISTRATIVO
$route['comboSedesNivel']           = 'c_encuesta_docente/comboSedesNivel';
$route['getNivelFromTabla']         = 'c_encuesta_docente/getNivelFromTabla';
$route['grabarIndDocentes']         = 'c_encuesta_docente/grabarIndDocentes';

//ENCUESTA DOCENTE
$route['getTablaByFiltro']           = 'c_encu_pers_admin/getTablaByFiltro';
$route['grabarIndPersAd']         = 'c_encu_pers_admin/grabarIndPersAd';

//UTILS
//$route['comboAulasGradoUtils'] = 'c_utils/comboAulasGradoUtils';
//PERSONA ROL
$route['getAllPersonaByRol'] = 'cf_mantenimiento/c_persona_rol/getAllPersonaByRol';

//DESCRIPCION SISTEMA LOGIN
$route['descripcionSistem'] = 'c_login/getDescripcionSistem';