<?php defined('BASEPATH') OR exit('No direct script access allowed');

//ESTADO EVALUACION
define('PENDIENTE', 'PENDIENTE');
define('EJECUTADO', 'EJECUTADO');
define('NO_EJECUTADO', 'NO EJECUTADO');
define('POR_JUSTIFICAR', 'POR JUSTIFICAR');
define('JUSTIFICADO', 'JUSTIFICADO');
define('INJUSTIFICADO', 'INJUSTIFICADO');

//COLORES BOOTSTRAP-CALENDAR http://bootstrap-calendar.azurewebsites.net/
defined('EVT_COLR_PEND_INFO_CELESTE') OR define('EVT_COLR_PEND_INFO_CELESTE', 'event-info');
defined('EVT_COLR_EJEC_SUCC_VERDE')   OR define('EVT_COLR_EJEC_SUCC_VERDE', 'event-success');
defined('EVT_COLR_NOEJ_WARN_AMBAR')   OR define('EVT_COLR_NOEJ_WARN_AMBAR', 'event-warning');
defined('EVT_COLR_POJU_INVE_NEGRO')   OR define('EVT_COLR_POJU_INVE_NEGRO', 'event-inverse');
defined('EVT_COLR_JUST_SPEC_MORADO')  OR define('EVT_COLR_JUST_SPEC_MORADO', 'event-special');
defined('EVT_COLR_PEND_INJU_ROJO')    OR define('EVT_COLR_PEND_INJU_ROJO', 'event-important');

//ESTADO DE LAS FICHAS
define('INACTIVO_', '0');
define('ACTIVO_', '1');
define('PENDIENTE_', '2');
defined('RUBRICA_ESTADO_POR_REACTIVAR') OR define('RUBRICA_ESTADO_POR_REACTIVAR', '3');

defined('RUBRICA_PENDIENTE') OR define('RUBRICA_PENDIENTE', 'PENDIENTE');
defined('RUBRICA_ACTIVA') OR define('RUBRICA_ACTIVA', 'ACTIVO');
defined('RUBRICA_INACTIVA') OR define('RUBRICA_INACTIVA', 'INACTIVO');
defined('RUBRICA_REACTIVAR') OR define('RUBRICA_REACTIVAR', 'POR REACTIVAR');

//Configuraciones
define('CONFIG_MIN_MAX_SUB', '1');
define('CONFIG_MIN_MAX_COR', '2');

//Tipos de visita
define('VISITA_OPINADA', 'OPINADA');
define('VISITA_NO_OPINADA', 'NO OPINADA');
define('VISITA_SEMI_OPINADA', 'SEMI OPINADA');

//TIPO DE GRAFICOS
//1 = DocenteGeneral || 2 = DocenteXIndicador || 3 = IndicadorGeneral
define('DOCENTE_GENERAL', 1);
define('DOCENTE_INDICADOR', 2);
define('INDICADOR_GENERAL', 3);

//FFMPEG
define('RUTA_BASE_FFMPEG', 'C:/ffmpeg/bin');
define('RUTA_VIDEO_THUMB', 'uploads/modulos/sped/evidencias/video_thumb/');

//COLORES DE ESTADO EVALUACIONES
define('COLORES_EVALUACIONES', json_encode(array ('EJECUTADO'=>'green' , 'PENDIENTE' => 'blue' , 'NO EJECUTADO' => 'orange' , 'JUSTIFICADO' => 'black' , 'INJUSTIFICADO' => 'red' , 'POR JUSTIFICAR' => 'purple')));

//FLG_CALENDARIO
define('FLG_LABORABLE' , 1);
define('FLG_DIA_SEMANA', 1);
define('FLG_FERIADO'   , 0);

//ID SPED CONFIG
define('ID_SPED_CONFIG_1', 1);
define('ID_SPED_CONFIG_2', 2);

defined('CANT_NIVELES_RUBRICA') OR define('CANT_NIVELES_RUBRICA', '3;4;5');

defined('VALOR_NO_APLICA') OR define('VALOR_NO_APLICA', -1);
defined('DESC_NO_APLICA') OR define('DESC_NO_APLICA', 'N.A.');

//Controladores
defined('ID_PERMISO_RUBRICA') OR define('ID_PERMISO_RUBRICA', 6);
defined('ID_PERMISO_AGENDA')  OR define('ID_PERMISO_AGENDA', 1);
defined('ID_PERMISO_CONSULTAR_EVALUACIONES') OR define('ID_PERMISO_CONSULTAR_EVALUACIONES', 2);
defined('ID_PERMISO_GRAFICOS')  OR define('ID_PERMISO_GRAFICOS', 56);
defined('ID_PERMISO_GONFIG_SPED')  OR define('ID_PERMISO_GONFIG_SPED', 3);