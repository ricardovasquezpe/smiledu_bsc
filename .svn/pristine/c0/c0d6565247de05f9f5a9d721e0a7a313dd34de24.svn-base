<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('NOMBRE_SISTEMA', 'Sistema de matricula');
define('NAME_MODULO', 'Matr&iacute;cula');
define('COLOR_BARRA_ANDROID', '#3F41B5');
define('FAVICON', "http://" .(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null)."/sima/public/img/iconsSistem/icon_matricula.png");

//ID PERMISOS //DEFINIR AUN
define('ID_PERMISO_AULA', 50);
define('ID_PERMISO_MATRICULA', 52);
define('ID_PERMISO_CURSOS_GRADO', 53);
define('ID_PERMISO_ALUMNO', 51);
define('ID_PERMISO_PROFSOR_AULA', 54);
define('ID_PERMISO_SOLICITUD_TRASLADO', 63);
define('ID_PERMISO_CONFIRMACION_DATOS', 80);
define('ID_PERMISO_CONFIGURACION_MATRICULA', 86);

//Tipo de traslados
defined('TIPO_TRASLADO_INTERSEDES') OR define('TIPO_TRASLADO_INTERSEDES', 1);
defined('TIPO_TRASLADO_INTRASEDE')  OR define('TIPO_TRASLADO_INTRASEDE', 2);

//ESTADOS DE TRASLADO
define('SOLICITUD_ACEPTADA', 'ACEPTADA');
define('SOLICITUD_SOLICITADA', 'SOLICITADA');
define('SOLICITUD_RECHAZADA', 'RECHAZADA');

//TIPO NOTA
defined('TIPO_NOTA_NUMERICO') OR define('TIPO_NOTA_NUMERICO', 1);
defined('TIPO_NOTA_ALFABETICO')  OR define('TIPO_NOTA_ALFABETICO', 2);

//TIPO CICLO
defined('TIPO_CICLO_REGULAR') OR define('TIPO_CICLO_REGULAR', 1);
defined('TIPO_CICLO_VERANO')  OR define('TIPO_CICLO_VERANO', 2);

//CAMPOS OBLIGATORIOS
define("CAMPOS_OBLIGATORIOS_ESTUDIANTE", json_encode(array ('nom_persona','ape_pate_pers','ape_mate_pers','nro_documento','fec_naci','sexo')));
define("CAMPOS_OBLIGATORIOS_AULA", json_encode(array ('nid_sede','nid_nivel','nid_grado','capa_max','year','nombre_letra','tipo_ciclo')));
define("CAMPOS_OBLIGATORIOS_MATRICULA_1", json_encode(array ('parentesco','ape_paterno','ape_materno','nombres','tipo_doc_identidad',
    'nro_doc_identidad','flg_apoderado','flg_resp_economico','flg_vive','flg_vive_con_alumno','fec_naci','nacionalidad','telf_fijo',
    'telf_celular','email1','religion','idioma','estado_civil')));

define("OPCION_CARNET_EXTRANJERIA", 1);
define("OPCION_DNI", 2);

define("DESEMPLEADO", 2);
define("EMPLEADO", 1);

define("PAIS_RESIDENTE", 173); //PERU

//TIPO DE REPORTE
defined('TIPO_REPORTE_TUTORES')  OR define('TIPO_REPORTE_TUTORES', 1);
defined('TIPO_REPORTE_BIRTHDAY')  OR define('TIPO_REPORTE_BIRTHDAY', 2);
defined('TIPO_REPORTE_AUlA')  OR define('TIPO_REPORTE_AUlA', 3);
defined('TIPO_REPORTE_ESTADOS')  OR define('TIPO_REPORTE_ESTADOS', 4);
defined('TIPO_REPORTE_ALUMNOS')  OR define('TIPO_REPORTE_ALUMNOS', 5);
defined('TIPO_REPORTE_FAMILIAR') OR define('TIPO_REPORTE_FAMILIAR', 6);
defined('TIPO_REPORTE_DOCENTES') OR define('TIPO_REPORTE_DOCENTES', 7);
defined('TIPO_REPORTE_TRASLADOS') OR define('TIPO_REPORTE_TRASLADOS', 8);
defined('TIPO_REPORTE_RATIFICACION') OR define('TIPO_REPORTE_RATIFICACION', 9);

defined('OPCION_REPORT_AULA_LISTA')  OR define('OPCION_REPORT_AULA_LISTA', 1);
defined('OPCION_REPORT_AULA_FIRMAS')  OR define('OPCION_REPORT_AULA_FIRMAS', 2);
defined('OPCION_REPORT_AULA_CONSOLIDADO')  OR define('OPCION_REPORT_AULA_CONSOLIDADO', 3);
defined('OPCION_REPORTE_FAMILIAR_GRADO')  OR define('OPCION_REPORTE_FAMILIAR_GRADO', 1);
defined('OPCION_REPORTE_FAMILIAR_DISTRITO')  OR define('OPCION_REPORTE_FAMILIAR_DISTRITO', 2);
defined('OPCION_REPORTE_FAMILIAR_PROFESION')  OR define('OPCION_REPORTE_FAMILIAR_PROFESION', 3);
defined('OPCION_REPORTE_TRASLADO_SOLICITADO')  OR define('OPCION_REPORTE_TRASLADO_SOLICITADO', "SOLICITADA");
defined('OPCION_REPORTE_TRASLADO_RECHAZADO')  OR define('OPCION_REPORTE_TRASLADO_RECHAZADO', "RECHAZADA");
defined('OPCION_REPORTE_TRASLADO_ACEPTADO')  OR define('OPCION_REPORTE_TRASLADO_ACEPTADO', "ACEPTADA");
defined('OPCION_REPORTE_RATIFICACION_NOINICIADA')         OR define('OPCION_REPORTE_RATIFICACION_NOINICIADA', "NOINICIADA");
defined('OPCION_REPORTE_RATIFICACION_DECLARACIONJURADA')  OR define('OPCION_REPORTE_RATIFICACION_DECLARACIONJURADA', "DECLARACION");
defined('OPCION_REPORTE_RATIFICACION_GENERADA')           OR define('OPCION_REPORTE_RATIFICACION_GENERADA', "GENERADA");
defined('OPCION_REPORTE_RATIFICACION_PAGADA')             OR define('OPCION_REPORTE_RATIFICACION_PAGADA', "PAGADA");

//NUMERO DE CARGA DE AULAS
defined('NUMERO_AULAS_CARGA') OR define('NUMERO_AULAS_CARGA', 12);
defined('TIPO_CICLO_VERANO')  OR define('TIPO_CICLO_VERANO', 2);

//INCIDENCIAS
defined('INCIDENCIA_MATRICULA') OR define('INCIDENCIA_MATRICULA', "MATRICULA");
defined('INCIDENCIA_DESMATRICULA')  OR define('INCIDENCIA_DESMATRICULA', "DESMATRICULA");

//SITUACION LABORAL
defined('SITUACION_LABORAL_DESEMPLEADO')   OR define('SITUACION_LABORAL_DESEMPLEADO', 2);

//COMBOS
defined('COMBO_DISCAPACIDAD')           OR define('COMBO_DISCAPACIDAD', 49);
//CODIGOS DE ALUMNO Y FAMILIA
defined('COD_FAMILIA')    OR define('COD_FAMILIA', 'F');
defined('COD_ESTUDIANTE') OR define('COD_ESTUDIANTE', 'E');

defined('CANT_ALUMNOS_SCROLL') OR define('CANT_ALUMNOS_SCROLL', 10);

//CONFIGURACION
defined('CONFIG_MATRICULA')        OR define('CONFIG_MATRICULA'        , 'M');
defined('CONFIG_RATIFICACION')     OR define('CONFIG_RATIFICACION'     , 'R');
defined('CONFIG_TRASLADOS')        OR define('CONFIG_TRASLADOS'        , 'T');

//RETIRADO
defined('ESTADO_RETIRADO_PERSONA_AULA')        OR define('ESTADO_RETIRADO_PERSONA_AULA'        , 0);

