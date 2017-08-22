<?php defined('BASEPATH') OR exit('No direct script access allowed');

define('ID_PERMISO_COMPARATIVAS', 36);
define('ID_PERMISO_CONFIGURACION_PTJ', 34);
define('ID_PERMISO_GRAFICOS_BSC', 38);
define('ID_PERMISO_CATEGORIA', 43);
define('ID_PERMISO_RESPONSABLE', 33);

//ESTRUCTURAS DE INDICADOR
define('ESTRUCTURA_SNGA', 'SNGA');
define('ESTRUCTURA_SNG', 'SNG');
define('ESTRUCTURA_SN', 'SN');
define('ESTRUCTURA_SG', 'SG');
define('ESTRUCTURA_S', 'S');
define('ESTRUCTURA_DN', 'DN');
define('ESTRUCTURA_SNA', 'SNA');
define('ESTRUCTURA_SA', 'SA');

//NUMERO INDICADOR
define('INDICADOR_1' , 1);
define('INDICADOR_2' , 2);
define('INDICADOR_3' , 3);
define('INDICADOR_4' , 4);
define('INDICADOR_5' , 5);
define('INDICADOR_6' , 6);
define('INDICADOR_7' , 7);
define('INDICADOR_8' , 8);
define('INDICADOR_9' , 9);
define('INDICADOR_10' , 10);
define('INDICADOR_11' , 11);
define('INDICADOR_12' , 12);
define('INDICADOR_13' , 13);
define('INDICADOR_14' , 14);
define('INDICADOR_15' , 15);
define('INDICADOR_16' , 16);
define('INDICADOR_17' , 17);
define('INDICADOR_18' , 18);
define('INDICADOR_19' , 19);
define('INDICADOR_20' , 20);
define('INDICADOR_21' , 21);
define('INDICADOR_22' , 22);
define('INDICADOR_23' , 23);
define('INDICADOR_24' , 24);
define('INDICADOR_25' , 25);
define('INDICADOR_26' , 26);
define('INDICADOR_27' , 27);
define('INDICADOR_28' , 28);
define('INDICADOR_29' , 29);
define('INDICADOR_30' , 30);
define('INDICADOR_31' , 31);
define('INDICADOR_32' , 32);
define('INDICADOR_33' , 33);
define('INDICADOR_34' , 34);
define('INDICADOR_35' , 35);
define('INDICADOR_36' , 36);
define('INDICADOR_40' , 40);
define("INDI_CLIENTE_FAMILIA", json_encode(array ('39','40','41','42','43','44','45','46','47','48','49','50','71','73','74')));

/*INDICADORES DEL MONGO*/
define("INDI_ALUMNO", json_encode(array ('39','40','41','42','43','44', '104')));
define("INDI_PADRE_FAM", json_encode(array ('45','46','47','48','49','50','71','73','74')));
define("INDI_DOCENTE", json_encode(array ('52','53','54','55')));
define("INDI_NO_DOCENTE", json_encode(array ('79','80')));
/*FIN INDICADORES DEL MONGO*/

define('INDICADOR_52' , 52);
define('INDICADOR_58' , 58);
//PERSONAL ADMINISTRATIVO
define('INDICADOR_79' , 79);
define('INDICADOR_80' , 80);
define('INDICADOR_81' , 81);
define('INDICADOR_82' , 82);
define('INDICADOR_86' , 86);

//Indicadores de proceso
define('INDICADOR_90', 90);
define('INDICADOR_92', 92);
define('INDICADOR_93', 93);
define('INDICADOR_94', 94);
define('INDICADOR_95', 95);
define('INDICADOR_96', 96);
define('INDICADOR_98', 98);
define('INDICADOR_99', 99);
define('INDICADOR_100', 100);
define('INDICADOR_101', 101);
define('ARRAY_INDI_PROC', json_encode(array(90,92,93,94,95,96,98,99,100,101)));


//TIPO EAI
define('EAI_MATE' , 'EAI-MATE');
define('EAI_COMU' , 'EAI-COMU');
define('EAI_CIEN' , 'EAI-CIEN');
define('EAI_INFO' , 'EAI-INFO');

//MATERIAS
define('MATEMATICA'   , 'medida_rash_eai_mate');
define('COMUNICACION' , 'medida_rash_eai_comu');
define('CIENCIA'      , 'medida_rash_eai_ciencia');
define('INFORMATICA'  , 'medida_rash_eai_infor' );
//indicadores
define('IND_MATEMATICA'   , 'ind_logro_eai_mate');
define('IND_COMUNICACION' , 'ind_logro_eai_comu');
define('IND_CIENCIA'      , 'ind_logro_eai_ciencia');
define('IND_INFORMATICA'  , 'ind_logro_eai_infor' );

//ECE nombres
define('ECE_MATE'  , 'matematica' );
define('ECE_LECTU'  , 'lectura' );

//TABLA CONFIG(NOTAS)
define('CONFIG_1','1');
define('CONFIG_2','2');
define('CONFIG_3','3');

//UNIVERSIDADES_COD
define('PUCP'  , '1');
define('UNI'   , '2');
define('UNAC'  , '3');
define('UNMSM' , '4');
define('UNJFSC', '5');
define('UPCH'  , '6');
define('ULIMA' , '7');
define('UP'    , '8');
define('FAUSTINO' , '9');

//DISCIPLINAS DETALLE - NIVEL COMPETITIVO
define('FORMATIVA'  , 'FORMATIVA');
define('COMPETITIVA', 'COMPETITIVA');

//DISCIPLINAS
define('DEPORTIVA'  , 'DEPORTIVA');
define('ARTISTICA'  , '');

//CERTIFICADOS DE DOCENTES
define('CERTIFICADO_EFCE'   , 'flg_certi_efce'  );
define('CERTIFICADO_NATIVO' , 'flg_ingles_nativo');

//Estados de Certificacion de ingles//EVALUACIONES DE CERTIFICACION INTERNACIONAL
define('APROBO'         , 'A'  );
define('SOLO_PARTICIPO' , 'P');

//ASISTENCIA DE ALUMNOS
define('TARDE' , 'TARDE'  );
define('FALTA' , 'FALTA');

//ESTADOS DE CERTIFICACION DE INGLES
define('NO_PARTICIPO', '0');
define('INGRESO'     , '2');

//GRADOS DE LOS ESTUDIASTES
define('SEC_CUARTO'  , '14');
define('SEC_QUINTO'  , '15');

//PPU DE LECTURA/NUMERICO/CIENCIAS
define('PPU_NUMERICO', '1');
define('PPU_CIENCIAS', '2');
define('PPU_LECTURA' , '3');

//TIPO_REGISTRO
define('NIVEL' , 'NIVEL');
define('GRADO' , 'GRADO');
define('AULA'  , 'AULA' );

//FRECUENCIA DE MEDICIÓN
/*define('NO_MEDIDO', 'N');
define('SI_MEDIDO', 'S');*/

//EAI
define('EAI', 'EAI');

//MEDIDA RASH
define('INICIO', '1');
define('PROCESO', '2');

//DATA INDICADORES GAUGE
define('TODO', 'TODO');
define('UNO' , 'UNO');
define('POS_GAUGE' , 1);

//TIPOS DE GAUGE
define('GAUGE_NORMAL' , 'NORMAL');    //NORMAL  0 - 100% LO MEJOR ES 100%
define('GAUGE_PUESTO' , 'PUESTO');    //PUESTOS 1 - N POSCICION LO MEJOR ES 1
define('GAUGE_CERO'   , 'CERO');      //TARDANZA 0 - N LO MEJOR ES 0
define('GAUGE_MAXIMO' , 'MAXIMO');    //POSTULANTES 0 - N LO MEJOR ES N
define('GAUGE_RATIO' , 'RATIO');    //NORMAL  0 - 1 LO MEJOR ES 1
define('GAUGE_REDUCCION' , 'REDUCCION');    //NORMAL  -N +N LO MEJOR ES +N

//CONFIG FLG GENERAL
define('CONFIG_GENERAL', 1);

define('TIPO_VALOR_PORCENTAJE', '%');

define("ARRAY_SERVICIO_MONGO", json_encode(array ('COMEDOR','MOBILIDAD','ENFERMERIA','PSICOPEDAGOGICA','BIBLIOTECA','SECRETARIA',
'COMUNICACION_SUBDIRECTOR','COMUNICACION_COORDINADOR',
'DESARROLLO_FORMATIVO','INSTITUCION','COMUNICACION_INSTITUCION','ATENCION_PORTERIA',
'ATENCION_TUTOR','COMUNICACION_SUBDIRECTOR','COMUNICACION_JEFE_AREA')));


//TIPO DE ENCUESTAS
define('TIPO_ENCUESTA_DOCENTE'  , '1');
define('TIPO_ENCUESTA_ALUMNOS'  , '2');
define('TIPO_ENCUESTA_PADREFAM' , '3');
define('TIPO_ENCUESTA_PERSADM'  , '4');
define('TIPO_ENCUESTA_LIBRE'    , '5');