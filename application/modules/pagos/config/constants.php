<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//CONCEPTOS
defined('INGRESO')               OR define('INGRESO', 1);
defined('EGRESO')                OR define('EGRESO', 2);
define('COMBO_TIPO_MOVIMIENTO', 38);
defined('COMBO_TIPO_CONCEPTO')   OR define('COMBO_TIPO_CONCEPTO', 29);
defined('TIPO_ESPECIFICO')       OR define('TIPO_ESPECIFICO',2);
defined('TIPO_GENERAL')          OR define('TIPO_GENERAL',1);
defined('TIPO_EGRESO')           OR define('TIPO_EGRESO', 'EGRESO');
defined('TIPO_INGRESO')          OR define('TIPO_INGRESO', 'INGRESO');
//BECA
defined('NUM_ROWS_CERO')  OR define('NUM_ROWS_CERO', 0);
defined('NUM_ROWS_UNO')   OR define('NUM_ROWS_UNO', 1);
defined('beca_100')       OR define('beca_100', 0);
//DOCUMENTOS
define('DOC_RECIBO' , 'RECIBO');
define('DOC_BOLETA' , 'BOLETA');
define('DOC_IMPRESO', 'IMPRESO');
//FLAG MOVIMIENTO PERSONAL
defined('FLG_MOVI_ESTUDIANTE') OR define('FLG_MOVI_ESTUDIANTE', 0);
defined('FLG_MOVI_COLABORADORES') OR define('FLG_MOVI_COLABORADORES', 1); 
//FLAG IMPRESO
define('FLG_IMPRESO'    , '1');
define('FLG_NO_IMPRESO' , '0');
define('FLG_MIGRADO'    , '1');

//FLAG RESPONSABLE ECONOMICO
defined('FLG_RESPONSABLE')   OR define('FLG_RESPONSABLE', 1);
//ACCIONES
define('ANULAR'    , 'ANULAR');
define('IMPRIMIR'  , 'IMPRIMIR');
define('PAGAR'     , 'PAGAR');
define('GENERAR'   , 'GENERAR');
define('RETIRAR'   , 'RETIRAR');
define('CERRAR'    , 'CERRAR');
define('DEVOLVER'  , 'DEVOLVER');
define('CREADO'    , 'CREADO');

//SIN SERIE
defined('SERIE_DEFAULT') OR define('SERIE_DEFAULT', '0000');

defined('NUMERO_CARGA') OR define('NUMERO_CARGA', '12');

//CONSTANTES MIGRACIÓN
defined('OP_VENTA')         OR define('OP_VENTA' , '02');
defined('OP_ASIENTO')       OR define('OP_ASIENTO' , '04');
defined('SECCION_BANCO')    OR define('SECCION_BANCO' , '00000000000000000000000000000000');
defined('COD_SUB_CONCEPTO') OR define('COD_SUB_CONCEPTO' , '00');
defined('VAL_SUB_CONCEPTO') OR define('VAL_SUB_CONCEPTO' , '00000000000000');
defined('CUENTA_CLIENTE')          OR define('CUENTA_CLIENTE' , '00000000000000000000');
defined('ADICIONALES')          OR define('ADICIONALES' , '000000000000000000');
defined('DNI')              OR define('DNI' , 'L');
defined('CONCEPTO_BANBIF')  OR define('CONCEPTO_BANBIF' , '000');
defined('IMPORTE_BANBIF')   OR define('IMPORTE_BANBIF' , '0000000000');
defined('TIPO_MONEDA_SOLES')          OR define('TIPO_MONEDA_SOLES' , 'PEN');
defined('MONEDA_SOLES_BCP')          OR define('MONEDA_SOLES_BCP' , '0');
defined('LIMITE_CORRELATIVO_X_DIA')          OR define('LIMITE_CORRELATIVO_X_DIA' , '8');
defined('CUENTA_EN_BCP')          OR define('CUENTA_EN_BCP' , '112827700000');
defined('COD_SERVICIO_BCP')          OR define('COD_SERVICIO_BCP' , '000000');
defined('TIPO_MORA')          OR define('TIPO_MORA' , '00');
defined('TIPO_DESCUENTO')          OR define('TIPO_DESCUENTO' , '00');
defined('CODIGO_SCOTIA')          OR define('CODIGO_SCOTIA' , '001');
defined('CODIGO_BANBIF')          OR define('CODIGO_BANBIF' , '002');
defined('CODIGO_BCP')          OR define('CODIGO_BCP' , '194');
defined('IMPORTE_COBRAR')          OR define('IMPORTE_COBRAR' , '00000000000');
defined('CONCEPTO_COMERCIO')  OR define('CONCEPTO_COMERCIO' , '00');
defined('IMPORTE_COMERCIO')   OR define('IMPORTE_COMERCIO' , '0');
defined('MONTO_MINIMO')          OR define('MONTO_MINIMO' , '000000000');
defined('DCT_PAGO_NUMERICO')          OR define('DCT_PAGO_NUMERICO' , '00000000000000000000');
defined('DCT_ID_NUMERICO')          OR define('DCT_ID_NUMERICO' , '000000000000000000000000000');

//CUENTAS CONTABLES
defined('CTA_POR_COBRAR')     OR define('CTA_POR_COBRAR'     , '12121');
defined('CTA_PROPIA')         OR define('CTA_PROPIA'         , '40111');
defined('CTA_SERV_PRESTADOS') OR define('CTA_SERV_PRESTADOS' , '70412');
defined('CTA_10111')          OR define('CTA_10111' , '10111');

defined('TIPO_CAMBIO')      OR define('TIPO_CAMBIO'      , '3.158');
defined('TIPO_CAMBIO_NONE') OR define('TIPO_CAMBIO_NONE' , '0.000');
defined('DEBE')  OR define('DEBE'  , 'D');
defined('HABER') OR define('HABER' , 'H');
defined('SOLES') OR define('SOLES' , 'S');

//ESTADOS CAJA
defined('APERTURADA')            OR define('APERTURADA'            , 'APERTURADA');
defined('CERRADA')               OR define('CERRADA'               , 'CERRADA');
defined('REAPERTURADA')          OR define('REAPERTURADA'          , 'REAPERTURADA');
defined('CERRADA_EMERGENCIA')    OR define('CERRADA_EMERGENCIA'    , 'CERRADA_EMERGENCIA');

//CODIGOS BANCO
defined('BANCO_BANBIF')        OR define('BANCO_BANBIF'        , '1');
defined('BANCO_BBVA')          OR define('BANCO_BBVA'          , '2');
defined('BANCO_BCP')           OR define('BANCO_BCP'           , '3');
defined('BANCO_SCOTIA')        OR define('BANCO_SCOTIA'        , '4');
defined('BANCO_COMERCIO')      OR define('BANCO_COMERCIO'      , '5');
defined('CODIGO_CLASE')        OR define('CODIGO_CLASE'        , '000');
defined('CODIGO_GRUPO_BANBIF') OR define('CODIGO_GRUPO_BANBIF' , '0001');

//FLG MIGRACION
defined('EXPORTAR') OR define('EXPORTAR' , 'EXPORTAR');
defined('IMPORTAR') OR define('IMPORTAR' , 'IMPORTAR');

defined('NUMERO_CRONOGRAMA_X_SEDE')     OR define('NUMERO_CRONOGRAMA_X_SEDE', 2); // NUMERO DE CRONOGRAMA POR SEDE ANUAL
defined('FLG_GENERAL_AREA_ESPECIFICO')  OR define('FLG_GENERAL_AREA_ESPECIFICO', 0);

//TIPO CORREOS
defined('CUOTA_VENCIDA')   OR define('CUOTA_VENCIDA'   , 2);
defined('PRONTO_PAGO')     OR define('PRONTO_PAGO'     , 3);
defined('REC_VENCIMIENTO') OR define('REC_VENCIMIENTO' , 4);

defined('FLG_CERRADO')     OR define('FLG_CERRADO' , 1);

//TIPO CUOTA INGRESO
defined('FLG_CI_FAMILIA')    OR define('FLG_CI_FAMILIA'    , 1);
defined('FLG_CI_ESTUDIANTE') OR define('FLG_CI_ESTUDIANTE' , 2);

//FLG PENSIONES CERRADAS
defined('FLG_CERRADO_MATRICULA')  OR define('FLG_CERRADO_MATRICULA', 1);
defined('FLG_CERRADO_CUOTA')      OR define('FLG_CERRADO_CUOTA', 2);
defined('FLG_CERRADO_TODO')       OR define('FLG_CERRADO_TODO', 3);

//CARGA DE LOS CARDS
defined('NUMERO_CARDS_CARGA')     OR define('NUMERO_CARDS_CARGA', 10); 

//FLAGS BANCO
defined('FLG_COLEGIO') OR define('FLG_COLEGIO' , '0');
defined('FLG_BANCO')   OR define('FLG_BANCO'   , '1');
defined('BCP')         OR define('BCP'         , 'BCP');
defined('BBVA')        OR define('BBVA'        , 'BBVA');
defined('BANBIF')      OR define('BANBIF'      , 'BANBIF');
defined('COMERCIO')    OR define('COMERCIO'    , 'COMERCIO');
defined('LUGAR_PAGO_BANCO')    OR define('LUGAR_PAGO_BANCO'   , 'Banco');
defined('LUGAR_PAGO_COLEGIO')  OR define('LUGAR_PAGO_COLEGIO' , 'Colegio');

//ARRAY IMAGENES - BANCO
defined('IMAGENES_BANCO_ID') OR define('IMAGENES_BANCO_ID', json_encode(array ('1' => 'banbif.png' , '2' => 'bbva.png', '3' => 'bcp.png' , '4' => 'scotiabank.png','5' => 'comercio.png')));