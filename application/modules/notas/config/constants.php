<?php
defined('BASEPATH') OR exit('No direct script access allowed');

defined('TIPO_CURSO') OR define('TIPO_CURSO', "UGEL;EQUIVALENTE");

//TIPO DE CURSO
defined('CURSO_UGEL')  OR define('CURSO_UGEL'  , 1);
defined('CURSO_EQUIV') OR define('CURSO_EQUIV' , 2);

//PERMISOS DEL MODULO DE NOTAS
defined('ID_PERMISO_MIS_CURSOS')         OR define('ID_PERMISO_MIS_CURSOS'         , 64);
defined('ID_PERMISO_DOCENTES_AULAS')     OR define('ID_PERMISO_DOCENTES_AULAS'     , 54);
defined('ID_PERMISO_CURSOS_GRADO')       OR define('ID_PERMISO_CURSOS_GRADO'       , 52);
defined('ID_PERMISO_TUTORIA')            OR define('ID_PERMISO_TUTORIA'            , 65);
defined('ID_PERMISO_SELECCIONAR_TALLER') OR define('ID_PERMISO_SELECCIONAR_TALLER' , 83);
defined('ID_PERMISO_ASIGNAR_ALUMNO')     OR define('ID_PERMISO_ASIGNAR_ALUMNO'     , 87);
defined('ID_PERMISO_PESO_ASISTENCIA')    OR define('ID_PERMISO_PESO_ASISTENCIA'    , 40);
defined('ID_PERMISO_SOLICITUD_GRUPO')    OR define('ID_PERMISO_SOLICITUD_GRUPO'    , 53);
defined('ID_PERMISO_REPORTE')            OR define('ID_PERMISO_REPORTE'            , 90);

defined('FLG_JUSTIFICADO') OR define('FLG_JUSTIFICADO', '1');

//ASISTENCIAS
defined('ASISTENCIA_PRESENTE')     OR define('ASISTENCIA_PRESENTE', 'PRESENTE');
defined('ASISTENCIA_TARDE')        OR define('ASISTENCIA_TARDE', 'TARDE');
defined('ASISTENCIA_TARDE_JUSTIF') OR define('ASISTENCIA_TARDE_JUSTIF', 'TARDE_JUSTIF');
defined('ASISTENCIA_FALTA')        OR define('ASISTENCIA_FALTA', 'FALTA');
defined('ASISTENCIA_FALTA_JUSTIF') OR define('ASISTENCIA_FALTA_JUSTIF', 'FALTA_JUSTIF');

//ID ASISTENCIA
defined('ID_ASISTENCIA_TARDE')        OR define('ID_ASISTENCIA_TARDE'       , 1);
defined('ID_ASISTENCIA_TARDE_JUSTIF') OR define('ID_ASISTENCIA_TARDE_JUSTIF', 2);
defined('ID_ASISTENCIA_FALTA')        OR define('ID_ASISTENCIA_FALTA'       , 3);
defined('ID_ASISTENCIA_FALTA_JUSTIF') OR define('ID_ASISTENCIA_FALTA_JUSTIF', 4);

//ASISTENCIAS CSS
defined('ASISTENCIA_PRESENTE_CSS')     OR define('ASISTENCIA_PRESENTE_CSS', 'present');
defined('ASISTENCIA_TARDE_CSS')        OR define('ASISTENCIA_TARDE_CSS', 'delay');
defined('ASISTENCIA_TARDE_JUSTIF_CSS') OR define('ASISTENCIA_TARDE_JUSTIF_CSS', 'delay-justify');
defined('ASISTENCIA_FALTA_CSS')        OR define('ASISTENCIA_FALTA_CSS', 'absence');
defined('ASISTENCIA_FALTA_JUSTIF_CSS') OR define('ASISTENCIA_FALTA_JUSTIF_CSS', 'absence-justify');

defined('ASISTENCIAS_TIPOS')       OR define('ASISTENCIAS_TIPOS', ASISTENCIA_PRESENTE.';'.ASISTENCIA_TARDE.';'.ASISTENCIA_TARDE_JUSTIF.';'.ASISTENCIA_FALTA.';'.ASISTENCIA_FALTA_JUSTIF);

//ASIGNAR Y REASIGNAR TUTORES, COTUTORES
defined('ASIGNAR_TUTOR')     OR define('ASIGNAR_TUTOR', 1);
defined('ASIGNAR_COTUTOR')   OR define('ASIGNAR_COTUTOR', 2);
defined('REASIGNAR_COTUTOR') OR define('REASIGNAR_COTUTOR', 3);

//TIPOS DE AWARD
defined('AWARD_POSITIVO') OR define('AWARD_POSITIVO','1');
defined('AWARD_NEGATIVO') OR define('AWARD_NEGATIVO', '2');

//DOCENTES
defined('DOCENTE_TITULAR')  OR define('DOCENTE_TITULAR', '1');
defined('DOCENTE_SUPLENTE') OR define('DOCENTE_SUPLENTE', '0');

//TIPO
defined('TALLER_ARTISTICO') OR define('FLG_TALLER_ARTISTICO', 0);
defined('TALLER_DEPORTE')   OR define('FLG_TALLER_DEPORTE', 1);

//ESTADOS DEL GRUPO_X_ALUMNO
defined('ESTADO_GRUPO_REGISTRADO') OR define('ESTADO_GRUPO_REGISTRADO', 'REGISTRADO');
defined('ESTADO_GRUPO_SOLICITADO') OR define('ESTADO_GRUPO_SOLICITADO', 'SOLICITADO');
defined('ESTADO_GRUPO_ACEPTADO')   OR define('ESTADO_GRUPO_ACEPTADO', 'ACEPTADO');
defined('ESTADO_GRUPO_RECHAZADO')  OR define('ESTADO_GRUPO_RECHAZADO', 'RECHAZADO');

//FLG CALIFICACION
defined("FLG_CALIFICACION_NUMERO") OR define("FLG_CALIFICACION_NUMERO" , "1");
defined("FLG_CALIFICACION_LETRA")  OR define("FLG_CALIFICACION_LETRA"  , "2");

//ARRAY NOTA LETRA
defined("NOTA_AD") OR define("NOTA_AD" , "AD");
defined("NOTA_A")  OR define("NOTA_A"  , "A");
defined("NOTA_B")  OR define("NOTA_B"  , "B");
defined("NOTA_C")  OR define("NOTA_C"  , "C");
defined("NOTAS_LETRA") OR define("NOTAS_LETRA", json_encode(array (NOTA_AD,NOTA_A,NOTA_B,NOTA_C)));

//GRUPO
defined("GRUPO_LLENO")     OR define("GRUPO_SIN_CAPACIDAD" , "3");
defined("GRUPO_LLENO")     OR define("GRUPO_CON_CAPACIDAD" , "4");
defined("GRUPO_RECHAZADO") OR define("GRUPO_RECHAZADO"     , "2");
defined("GRUPO_ACEPTADO")  OR define("GRUPO_ACEPTADO"      , "1");

//FLAG PENDIENTE CAMBIO
defined("FLG_CAMBIO_PENDIENTE") OR define("FLG_CAMBIO_PENDIENTE" , "1");
defined("FLG_CAMBIO_EFECTUADO") OR define("FLG_CAMBIO_EFECTUADO" , "0");
defined("FLG_ASIGNADO_GRUPO")   OR define("FLG_ASIGNADO_GRUPO"   , "0");

//TIPO DE REPORTE 
defined('TIPO_REPORTE_ORDEN_MERITO') OR define('TIPO_REPORTE_ORDEN_MERITO', 1);
defined('TIPO_REPORTE_CURSO_GRADO')  OR define('TIPO_REPORTE_CURSO_GRADO' , 2);
defined('TIPO_PROFESOR_POR_AULA')    OR define('TIPO_PROFESOR_POR_AULA'	  , 3);
defined('ORDEN_MERITO') 		     OR define('ORDEN_MERITO', 'ORDEN_MERITO');
defined('CURSO_GRADO')  			 OR define('CURSO_GRADO' , 'CURSO_GRADO');
defined('PROFESOR_POR_AULA')  	 	 OR define('PROFESOR_POR_AULA' , 'PROFESOR_POR_AULA');
