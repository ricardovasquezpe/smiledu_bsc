<?php defined('BASEPATH') OR exit('No direct script access allowed');
//ESTADO DE LAS FICHAS
define('INACTIVO_', '0');
define('ACTIVO_', '1');
define('PENDIENTE_', '2');

define('ACTUALIZAR_CONT','1');
define('ACTUALIZAR_ARRAY','0');

define('ID_ENCUESTA', '54'); //18
define('ID_ENCUESTA_EFQM', '18');

//TIPO DE ENCUESTAS EFQM
define('TIPO_ENCUESTA_ALUMNOS'  , '2');
define('TIPO_ENCUESTA_DOCENTE'  , '1');
define('TIPO_ENCUESTA_PADREFAM' , '3');
define('TIPO_ENCUESTA_PERSADM'  , '4');
define('TIPO_ENCUESTA_LIBRE'    , '5');

//TIPO DE ENCUESTADOS LIBRE
defined('TIP_ENCU_LIBRE_PADRES') OR define('TIP_ENCU_LIBRE_PADRES', '1');
defined('TIP_ENCU_LIBRE_ESTUD')  OR define('TIP_ENCU_LIBRE_ESTUD', '2');
defined('TIP_ENCU_LIBRE_INVITA') OR define('TIP_ENCU_LIBRE_INVITA', '3');
defined('TIP_ENCU_LIBRE_DOCENT') OR define('TIP_ENCU_LIBRE_DOCENT', '4');
defined('TIP_ENCU_LIBRE_ADMINS') OR define('TIP_ENCU_LIBRE_ADMINS', '5');

//NIVELES 
defined('SEDE')       OR define('SEDE'       , 'S');
defined('NIVEL')      OR define('NIVEL'      , 'N');
defined('GRADO')      OR define('GRADO'      , 'G');
defined('AULA')       OR define('AULA'       , 'AU');
defined('AREA')       OR define('AREA'       , 'AR');
defined('DISCIPLINA') OR define('DISCIPLINA' , 'D');
//NIVEL PARA DOCENTE
defined('NIVELDOC')   OR define('NIVELDOC'   , 'ND');
//NIVEL PARA PERS. ADMINISTRATIVO
defined('SEDE_AREA')  OR define('SEDE_AREA' , 'SA');
defined('AREA_ADM')   OR define('AREA_ADM'  , 'AREA_ADM');
//ENCUESTA LIBRE
defined('TIPO_ENCUESTADO') OR define('TIPO_ENCUESTADO', 'TIPO_ENCUESTADO');
//ARRAY TIPO ENCUESTA
define('ARRAY_TIPO_ENCU', json_encode(array('1' => 'DOCENTE')));

//flag obligatorio
define('FLG_OBLIGATORIO', '1');
define('FLG_ANONIMA'    , '1');
define('FLG_NO_ANONIMA' , '0');

//flag general de Schoowl de la tabla area
// define('FLG_GENERAL', '0');

//constantes de db.senc_respuesta_encuesta
define('ID_SEDE', '2');
define('ID_NIVEL', '1');
define('ID_GRADO', '1');
define('ID_AULA', '15');
define('NRO_PARTICIPANTES', '50');
define('ID_AREA', '5');


//TIPO FILTRO
define('PREGUNTA', 'P');
define('PROPUESTA', 'PROP');

//TIPO DE ENCUESTAS
define('ENCUESTA_CREADA'     , 'CREADA');
define('ENCUESTA_CERRADA'    , 'CERRADA');
define('ENCUESTA_FINALIZADA' , 'FINALIZADA');
define('ENCUESTA_APERTURADA' , 'APERTURADA');
define('ENCUESTA_BLOQUEADA'  , 'BLOQUEADA');
define('ENCUESTA_STAND_BY'   , 'STAND BY');
define('ID_TIPO_ENCUESTA',2);

//CAMBIO ESTADO ENCUESTA
define('CERRAR_ENCUESTA'    , 'CERRAR');
define('APERTURAR_ENCUESTA' , 'APERTURAR');

define('ALTERNATIVA_PERTENECE', '1');
define('PREGUNTA_PERTENECE_ENCUESTA', '1');
define('ID_PROPUESTA_MEJORA', '41');
define('ID_EXISTE_BD','1');
define('DESC_NO_EXISTE_BD','0');

//ESTADOS PREGUNTAS Y CATEGORIAS
defined('ESTADO_ACTIVO')   OR define('ESTADO_ACTIVO', 'ACTIVO');
defined('ESTADO_INACTIVO') OR define('ESTADO_INACTIVO', 'INACTIVO');

//INSERT, UPDATE, DELETE PREG CATE ENCUESTA
define('INSERT_PREG' , 'INSERT');
define('UPDATE_PREG' , 'UPDATE');
define('DELETE_PREG' , 'DELETE');

//TIPO PREGUNTA
define('TIPO_PREG_LIBRE','0');
define('TIPO_PREG_PROPUESTA','3');
define('TIPO_PREG_OPT_MULTI','4');
define('TIPO_PREG_CASILLA','5');
define('TIPO_PREG_LISTA','6');
define('TIPO_PREG_DOS_OPT','7');

//ENCUESTA CREADA
//define('ENCUESTA_CREADA','3');

//ARRAY TIPOS DE PREGUNTA
define('ARRAY_TIP_PREG_COMBO', json_encode(array(1,2)));

//INSERT, UPDATE, DELETE X CATEGORIAS Y PREGUNTAS
define('SIN_CAMBIO'    , '0');
define('INSERT_IN_ENC' , '1');
define('UPDATE_IN_ENC' , '2');
define('DELETE_IN_ENC' , '3');

//TIPO DE PREGUNTA
define('CINCO_CARITAS', '1');
define('TRES_CARITAS', '2');
define('PROPUESTA_MEJORA','3');
define('OPCION_MULTIPLE', '4');
define('CASILLAS_VERIFICACION', '5');
define('LISTA_DESPLEGABLE', '6');
define('DOS_OPCIONES', '7');
define('CUATRO_CARITAS', '8');
define('CARITAS', 'CARITAS');

//TIPO ENCUESTADO
define('PADRE'         , 'P');
define('ESTUDIANTE'    , 'E');
define('PERSONAL_ADMINISTRATIVO' , 'A');
define('DOCENTE'       , 'D');
define('INVITADO'      , 'I');

//ALTERNATIVAS
define('ALTERNATIVA_BLANCO','0'); //Solo para las migraciones
define('ALTERNATIVA_MUY_SATISFECHO','1');
define('ALTERNATIVA_SATISFECHO','2');
define('ALTERNATIVA_NORMAL','3');
define('ALTERNATIVA_INSATISFECHO','4');
define('ALTERNATIVA_MUY_INSATISFECHO','5');
define('ALTERNATIVA_SI' , '13');
define('ALTERNATIVA_NO' , '14');

//ACCION DEL ENCUESTADO
define('REALIZO_ENCUESTA'   , '1');
define('NO_REALIZO_ENCUESTA' , null);

define('ARRAY_ROLES_PRIVILEGIADOS_SENC', json_encode(array(ID_ROL_DIRECTOR, ID_ROL_DIRECTOR_TI, ID_ROL_MARKETING, ID_ROL_PROMOTOR, ID_ROL_SUBDIRECTOR, ID_ROL_ADMINISTRADOR)));

// CELDAS EN EXCEL
define('COLUMNA_4CARITAS'   , 'A');
define('COLUMNA_5CARITAS'   , 'B');
define('COLUMNA_2OPCIONES'  , 'C');
define('COLUMNA_3CARITAS'   , 'D');
define('COLUMNA_DESPEGABLE' , 'E');
define('FILA_INICIAL'       ,  4);
define('COLUMNA_INICIAL'    , 'A');

//CODIGO DE CELDA DE COMENTARIO EN EL EXCEL DE MIGRACION
defined('CODIGO_COMENTARIO_CELDA') OR define('CODIGO_COMENTARIO_CELDA', 999999);

//PROPUESTA DE MEJORA
define('CANT_MAX_PALABRAS', 5);

//COLORES ARRAY
define('ARRAY_COLORES_CARITAS', json_encode(array('MUY SATISFECHO' => '#4CAF50','SATISFECHO' => '#8BC34A','NORMAL' => '#FFEB3B','INSATISFECHO' => '#FF9800','MUY INSATISFECHO' => '#FF5722', 'BLANCO' => 'black')));

//PERMISO A CONTROLADORES
defined('ID_PERMISO_SEGUIMIENTO_EFQM') OR define('ID_PERMISO_SEGUIMIENTO_EFQM', 81);
defined('ID_PERMISO_ENCU_FISICA_EFQM') OR define('ID_PERMISO_ENCU_FISICA_EFQM', 82);
defined('ID_PERMISO_PROP_MEJORA')      OR define('ID_PERMISO_PROP_MEJORA'      , 55);
defined('ID_PERMISO_PREGUNTAS')        OR define('ID_PERMISO_PREGUNTAS'        , 32);
defined('ID_PERMISO_ADMIN_ENC')        OR define('ID_PERMISO_ADMIN_ENC'        , 47);

//CONSTANTS REFRESH CHARTS
defined('TIPO_ENCU') OR define('TIPO_ENCU', 'TIPO_ENCU');

//SERVICIOS COMPLEMENTARIOS DE LA INSTITUCION
defined('SERVICIO_MOVILIDAD')  OR define('SERVICIO_MOVILIDAD' , '1');
defined('SERVICIO_COMEDOR')    OR define('SERVICIO_COMEDOR'   , '2');
defined('SERVICIO_BIBLIOTECA') OR define('SERVICIO_BIBLIOTECA', '6');

defined('SERVICIOS_COMPLEMENTARIOS') OR define('SERVICIOS_COMPLEMENTARIOS'  , SERVICIO_MOVILIDAD.';'.SERVICIO_COMEDOR.';'.SERVICIO_BIBLIOTECA);

///// TIPOS DE PERMISOS AL COMPARTIR UNA ENCUESTA
defined('PERMISO_COMPARTIR') OR define('PERMISO_COMPARTIR', 'c');
defined('PERMISO_EDITAR')    OR define('PERMISO_EDITAR'   , 'e');
defined('PERMISO_GRAFICOS')  OR define('PERMISO_GRAFICOS' , 'g');
