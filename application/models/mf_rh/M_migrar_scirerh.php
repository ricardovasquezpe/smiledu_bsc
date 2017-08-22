<?php

class M_migrar_scirerh extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('M_utils');
    }
    
    function getQueryMigrar($mes) {
        return " SELECT LOWER(p.nombres)            AS nom_persona,
                        LOWER(p.apellido_paterno)   AS ape_pate_pers,
                        LOWER(p.apellido_materno)   AS ape_mate_pers,
                        p.fecha_nacimiento          AS fec_naci,
                        CASE WHEN p.tipo_doc_id = '01' THEN 2
                             WHEN p.tipo_doc_id = '04' THEN 1
                             WHEN p.tipo_doc_id = '06' THEN 3
                             WHEN p.tipo_doc_id = '07' THEN 4
                             WHEN p.tipo_doc_id = '11' THEN 5
                             ELSE NULL END          AS id_tipo_doc,
                        p.nro_doc                   AS nro_documento,
                        CASE WHEN p.sexo_id = '01' THEN '1' ELSE '2' END AS sexo,
                        CASE WHEN c.e_civil_id = '01' THEN 1
                             WHEN c.e_civil_id = '02' THEN 2
                             WHEN c.e_civil_id = '03' THEN 3
                             WHEN c.e_civil_id = '04' THEN 4
                             WHEN c.e_civil_id = '05' THEN 5
                             ELSE NULL END AS estado_civil,
                        CASE WHEN p.telefono  = '' THEN NULL ELSE p.telefono  END AS telf_pers,
                        CASE WHEN p.telefono2 = '' THEN NULL ELSE p.telefono2 END AS telf_pers_2,
                        CASE WHEN p.telefono3 = '' THEN NULL ELSE p.telefono3 END AS telf_pers_3,
                        CASE WHEN LTRIM(RTRIM(p.dpto+p.prov+p.dist)) = '' THEN NULL ELSE LTRIM(RTRIM(p.dpto+p.prov+p.dist)) END AS codigo_ubigeo,
                        CASE WHEN p.direccion = '' THEN NULL ELSE p.direccion END AS direccion,
                        p.compania_id               AS empresa_id,
                        p.personal_id,
                        CASE WHEN p.estado_id          = '01' THEN '1' ELSE '0'           END AS flg_acti,
                        CASE WHEN p.email              = '' THEN NULL ELSE p.email        END AS correo_pers,
                        CASE WHEN p.correo_otro        = '' THEN NULL ELSE p.correo_otro  END AS correo_inst,
                        CASE WHEN p.correo_otro2       = '' THEN NULL ELSE p.correo_otro2 END AS correo_admi,
                        CASE WHEN p.grupo_sanguineo_id = '' THEN NULL ELSE p.grupo_sanguineo_id END AS tipo_sangre,
                        p.area_id,
                        CASE WHEN p.area_id = '004' THEN 'DOCENTE' ELSE a.descripcion END AS desc_area,
                        p.nro_hijos,
                        p.tipo_via_id,
                        tv.descripcion AS tipo_via_desc,
                        p.numero_via,
                        p.interior_via,
                        p.tipo_zona_id,
                        tz.descripcion AS tipo_zona_desc,
                        CASE WHEN p.nombre_zona = '' THEN NULL ELSE p.nombre_zona END AS nombre_zona,
                        CASE WHEN p.referencia  = '' THEN NULL ELSE p.referencia  END AS referencia,
                        pa.periodo_id,
                        pa.fecha_ingreso,
                        pa.fecha_cese,
                        pa.fecha_ini_contrato,
                        pa.fecha_fin_contrato,
                        CASE WHEN p.convenio_evita_tributacion_id = '1'  THEN 14
                             WHEN p.convenio_evita_tributacion_id = '2'  THEN 18
                             WHEN p.convenio_evita_tributacion_id = '3'  THEN 19
                             WHEN p.convenio_evita_tributacion_id = '4'  THEN 20
                             WHEN p.convenio_evita_tributacion_id = '5'  THEN 23
                             WHEN p.convenio_evita_tributacion_id = '6'  THEN 26
                             WHEN p.convenio_evita_tributacion_id = '7'  THEN 29
                             WHEN p.convenio_evita_tributacion_id = '8'  THEN 36
                             WHEN p.convenio_evita_tributacion_id = '9'  THEN 37
                             WHEN p.convenio_evita_tributacion_id = '10' THEN 41
                             WHEN p.convenio_evita_tributacion_id = '11' THEN 53
                             ELSE NULL END AS id_area_general,
                        cet.descripcion                 AS desc_area_general,
                        CASE WHEN p.proyecto_id = '0000000001' THEN 17
                             WHEN p.proyecto_id = '0000000002' THEN 15
                             WHEN p.proyecto_id = '0000000003' THEN 16
                             WHEN p.proyecto_id = '0000000004' THEN 3
                             WHEN p.proyecto_id = '0000000005' THEN 7
                             WHEN p.proyecto_id = '0000000006' THEN 5
                             WHEN p.proyecto_id = '0000000007' THEN 4
                             WHEN p.proyecto_id = '0000000008' THEN 8
                             WHEN p.proyecto_id = '0000000009' THEN 6
                             WHEN p.proyecto_id = '0000000010' THEN 13
                             WHEN p.proyecto_id = '0000000011' THEN 12
                             WHEN p.proyecto_id = '0000000012' THEN 2
                             WHEN p.proyecto_id = '0000000013' THEN 1
                             WHEN p.proyecto_id = '0000000014' THEN 9
                             WHEN p.proyecto_id = '0000000015' THEN 60
                             WHEN p.proyecto_id = '0000000016' THEN 10
                             WHEN p.proyecto_id = '0000000017' THEN 42
                             WHEN p.proyecto_id = '0000000018' THEN 43
                             WHEN p.proyecto_id = '0000000019' THEN 44
                             WHEN p.proyecto_id = '0000000020' THEN 11
                             WHEN p.proyecto_id = '0000000021' THEN 21
                             WHEN p.proyecto_id = '0000000022' THEN 59
                             WHEN p.proyecto_id = '0000000023' THEN 22
                             WHEN p.proyecto_id = '0000000024' THEN 24
                             WHEN p.proyecto_id = '0000000025' THEN 25
                             WHEN p.proyecto_id = '0000000026' THEN 49
                             WHEN p.proyecto_id = '0000000027' THEN 27
                             WHEN p.proyecto_id = '0000000028' THEN 28
                             WHEN p.proyecto_id = '0000000029' THEN 50
                             WHEN p.proyecto_id = '0000000030' THEN 30
                             WHEN p.proyecto_id = '0000000031' THEN 31
                             WHEN p.proyecto_id = '0000000032' THEN 32
                             WHEN p.proyecto_id = '0000000033' THEN 33
                             WHEN p.proyecto_id = '0000000034' THEN 34
                             WHEN p.proyecto_id = '0000000035' THEN 35
                             WHEN p.proyecto_id = '0000000036' THEN 51
                             WHEN p.proyecto_id = '0000000037' THEN 45
                             WHEN p.proyecto_id = '0000000038' THEN 46
                             WHEN p.proyecto_id = '0000000039' THEN 52
                             WHEN p.proyecto_id = '0000000040' THEN 38
                             WHEN p.proyecto_id = '0000000041' THEN 39
                             WHEN p.proyecto_id = '0000000042' THEN 40
                             WHEN p.proyecto_id = '0000000043' THEN 47
                             WHEN p.proyecto_id = '0000000044' THEN 54
                             WHEN p.proyecto_id = '0000000045' THEN 55
                             WHEN p.proyecto_id = '0000000045' THEN 55
                             WHEN p.proyecto_id = '0000000046' THEN 56
                             WHEN p.proyecto_id = '0000000047' THEN 57
                             WHEN p.proyecto_id = '0000000048' THEN 58
                             ELSE NULL END      AS id_area_especifica,
                        (SELECT pro.descripcion FROM dbo.proyecto pro WHERE p.proyecto_id = pro.proyecto_id) AS desc_area_especifica,
                        CASE WHEN p.situacion_id = '01' THEN 13
                        WHEN p.situacion_id = '02' THEN 14
                        WHEN p.situacion_id = '03' THEN 7
                        WHEN p.situacion_id = '04' THEN 42
                        WHEN p.situacion_id = '05' THEN 43
                        WHEN p.situacion_id = '06' THEN 44
                        WHEN p.situacion_id = '07' THEN 45
                        WHEN p.situacion_id = '08' THEN 46
                        WHEN p.situacion_id = '09' THEN 3
                        WHEN p.situacion_id = '10' THEN 16
                        WHEN p.situacion_id = '11' THEN 17
                        WHEN p.situacion_id = '12' THEN 47
                        WHEN p.situacion_id = '13' THEN 48
                        WHEN p.situacion_id = '14' THEN 18
                        WHEN p.situacion_id = '15' THEN 19
                        WHEN p.situacion_id = '16' THEN 20
                        WHEN p.situacion_id = '17' THEN 49
                        WHEN p.situacion_id = '18' THEN 50
                        WHEN p.situacion_id = '19' THEN 40
                        WHEN p.situacion_id = '20' THEN 4
                        WHEN p.situacion_id = '21' THEN 21
                        WHEN p.situacion_id = '22' THEN 22
                        WHEN p.situacion_id = '23' THEN 23
                        WHEN p.situacion_id = '24' THEN 24
                        WHEN p.situacion_id = '25' THEN 25
                        WHEN p.situacion_id = '26' THEN 26
                        WHEN p.situacion_id = '27' THEN 27
                        WHEN p.situacion_id = '28' THEN 28
                        WHEN p.situacion_id = '29' THEN 29
                        WHEN p.situacion_id = '30' THEN 30
                        WHEN p.situacion_id = '31' THEN 31
                        WHEN p.situacion_id = '32' THEN 32
                        WHEN p.situacion_id = '33' THEN 33
                        WHEN p.situacion_id = '34' THEN 11
                        WHEN p.situacion_id = '35' THEN 34
                        WHEN p.situacion_id = '36' THEN 35
                        WHEN p.situacion_id = '37' THEN 1
                        WHEN p.situacion_id = '38' THEN 36
                        WHEN p.situacion_id = '39' THEN 37
                        WHEN p.situacion_id = '40' THEN 38
                        WHEN p.situacion_id = '41' THEN 39
                        ELSE NULL END              AS id_cargo_schoowl,
                        (SELECT sit.descripcion FROM dbo.situacion sit WHERE p.situacion_id = sit.situacion_id) AS desc_cargo_schoowl,
                        pa.categoria_auxiliar_id        AS id_jornada_laboral,
                        cataux.descripcion              AS desc_jornada_laboral,
                        pa.categoria_auxiliar2_id       AS flg_recibos,
                        CASE WHEN pa.personal_anexo_id = '00001' THEN 6
                             WHEN pa.personal_anexo_id = '00002' THEN 2
                             WHEN pa.personal_anexo_id = '00003' THEN 5
                             WHEN pa.personal_anexo_id IN ('00004','00007') THEN 3
                             WHEN pa.personal_anexo_id = '00005' THEN 4
                             WHEN pa.personal_anexo_id = '00008' THEN 1
                             WHEN pa.personal_anexo_id = '00006' THEN 7
                        ELSE NULL END              AS id_sede_control,
                        panex.descripcion               AS desc_sede_control,
                        CASE WHEN pa.personal_anexo2_id = '00000001' THEN 1
                             WHEN pa.personal_anexo2_id = '00000002' THEN 2
                             WHEN pa.personal_anexo2_id = '00000003' THEN 3
                             WHEN pa.personal_anexo2_id = '00000004' THEN 4
                             WHEN pa.personal_anexo2_id = '00000005' THEN 5
                             ELSE NULL END              AS id_nivel_control,
                        panex2.descripcion              AS desc_nivel_control
                   FROM dbo.Personal                   p,
                        dbo.Personal_activo            pa,
                        dbo.periodo                    pe,
                        dbo.mes                        m,
                        dbo.estados                    e,
                        dbo.e_civil                    c,
                        dbo.rh_area                    a,
                        dbo.tipo_via                   tv,
                        dbo.tipo_zona                  tz,
                        dbo.convenio_evita_tributacion cet,
                        --dbo.proyecto                   pro,
                        --dbo.situacion                  sit,
                        dbo.categoria_auxiliar         cataux,
                        dbo.personal_anexo             panex,
                        dbo.personal_anexo2            panex2
                  WHERE m.ejercicio_id                    = YEAR(getdate())
                    AND m.nmes                            = $mes
                    AND p.personal_id                     = pa.personal_id
                    AND pa.periodo_id                     = pe.periodo_id
                    AND pe.mes_id                         = m.mes_id
                    AND pe.compania_id                    = p.compania_id
                    AND p.compania_id                     = pa.compania_id
                    AND e.codigo                          = p.estado_id
                    AND c.e_civil_id                      = p.e_civil_id
                    AND p.area_id                         = a.area_id
                    AND p.tipo_via_id                     = tv.tipo_via_id
                    AND p.tipo_zona_id                    = tz.tipo_zona_id
                    AND p.convenio_evita_tributacion_id   = cet.convenio_evita_tributacion_id
                    --AND p.proyecto_id                   = pro.proyecto_id
                    --AND p.situacion_id                  = sit.situacion_id
                    AND pa.categoria_auxiliar_id          = cataux.categoria_auxiliar_id
                    AND pa.personal_anexo_id              = panex.personal_anexo_id
                    AND pa.personal_anexo2_id             = panex2.personal_anexo2_id
                  ORDER BY p.apellido_paterno, p.apellido_materno, p.nombres";
    }

    function getPersonalScirerh($transaccion = false, $codMigracion = null) {
        if($codMigracion == null) {
            $sql = "SELECT COALESCE(MAX(grupo_migracion), 1) + 1 cod_migra
                      FROM log_migracion
                     WHERE tipo_migracion = ?";
            $result = $this->db->query($sql, array(_PERSONAL_));
            $codMigracion = $result->row()->cod_migra;
        }
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $sql = $this->getQueryMigrar('MONTH(getdate())');
        $personal = $db_scirerh->query($sql);
        if (count($personal->result()) == 0) {
            $sql = $this->getQueryMigrar("MONTH(getdate()) - 1");
            $personal = $db_scirerh->query($sql);
        }
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        if($transaccion) {
            $this->db->trans_begin();
        }
        try {
            $personalActivoArry = array();
            foreach ($personal->result() as $row) {
                //Verificar si el NRO_DOC existe
                $idPers = $this->m_utils->getById('persona', 'nid_persona', 'nro_documento', $row->nro_documento);
                $arryInsUpt = array(
                    "nom_persona"         => ucwords($row->nom_persona) ,
                    "ape_pate_pers"       => ucwords($row->ape_pate_pers),
                    "ape_mate_pers"       => ucwords($row->ape_mate_pers),
                    "fec_naci"            => $row->fec_naci,
                    "tipo_documento"      => $row->id_tipo_doc,
                    "nro_documento"       => $row->nro_documento,
                    "sexo"                => $row->sexo,
                    "estado_civil"        => $row->estado_civil,
                    "telf_pers"           => $row->telf_pers,
                    "telf_pers_2"         => $row->telf_pers_2,
                    "telf_pers_3"         => $row->telf_pers_3,
                    "scirerh_personal_id" => $row->personal_id,
                    //"flg_acti"            => $row->flg_acti,
                    "correo_pers"         => ($row->correo_pers != null) ? strtolower($row->correo_pers) : null,
                    "correo_inst"         => ($row->correo_inst != null) ? strtolower($row->correo_inst) : null,
                    "correo_admi"         => ($row->correo_admi != null) ? strtolower($row->correo_admi) : null,
                    "tipo_sangre"         => ($row->tipo_sangre != null && $row->tipo_sangre != '' ? $row->tipo_sangre : null) );
                $arryPers = array(
                    "id_persona"           => null,
                    "id_tipo_area"         => $row->area_id,
                    "desc_tipo_area"       => $row->desc_area,
                    "nro_hijos"            => $row->nro_hijos,
                    "condicion_laboral"    => 'PLANILLA',
                    "tipo_via_id"          => $row->tipo_via_id,
                    "tipo_via_desc"        => $row->tipo_via_desc,
                    "numero_via"           => $row->numero_via,
                    "interior_via"         => $row->interior_via,
                    "tipo_zona_id"         => $row->tipo_zona_id,
                    "tipo_zona_desc"       => $row->tipo_zona_desc,
                    "nombre_zona"          => $row->nombre_zona,
                    "referencia"           => $row->referencia,
                    "codigo_ubigeo"        => $row->codigo_ubigeo,
                    "direccion"            => $row->direccion,
                    "id_area_general"      => $row->id_area_general,
                    "id_area_especifica"   => $row->id_area_especifica,
                    "id_cargo"             => $row->id_cargo_schoowl,
                    "id_jornada_laboral"   => $row->id_jornada_laboral,
                    "id_sede_control"      => $row->id_sede_control,
                    "id_nivel_control"     => $row->id_nivel_control,
                    "desc_area_general"    => $row->desc_area_general,
                    "desc_area_especifica" => $row->desc_area_especifica,
                    "desc_cargo"           => $row->desc_cargo_schoowl,
                    "desc_jornada_laboral" => $row->desc_jornada_laboral,
                    "desc_sede_control"    => $row->desc_sede_control,
                    "desc_nivel_control"   => $row->desc_nivel_control
                );
                if($idPers == null) {
                    //Check si existe en persona
                    $idPers = $this->m_utils->getById('persona', 'nid_persona', 'scirerh_personal_id', $row->personal_id);
                    if($idPers == null) {//INSERTAR
                        //GENERAR USUARIO
                        $correo = null;
                        if($row->correo_inst != null) {
                            $correo = $row->correo_inst;
                        } else if($row->correo_admi != null) {
                            $correo = $row->correo_admi;
                        }
                        if($correo != null) {//crear usuario!!!!
                            if (strpos($correo, '@') !== false) {
                                $arryInsUpt['usuario'] = explode('@', $correo)[0];
                                $arryInsUpt['clave']   = '123';
                            }
                        }
                        if($row->fecha_cese != '1900-01-01 00:00:00.000') {
                            if(date('Y-m-d H:i:s') <= $row->fecha_cese) {
                                $arryInsUpt['flg_acti'] = '1';
                                array_push($personalActivoArry, $idPers);
                            } else {
                                $arryInsUpt['flg_acti'] = '0';
                                if($arryInsUpt['flg_recibos'] == '000000000000001') {
                                    $arryInsUpt['flg_acti'] = '1';
                                    array_push($personalActivoArry, $idPers);
                                }
                            }
                        } else {
                            $arryInsUpt['flg_acti'] = '1';
                            array_push($personalActivoArry, $idPers);
                        }
                        $this->db->insert('persona', $arryInsUpt);
                        $idPers = $this->db->insert_id();
                        $arryPers['id_persona'] = $idPers;
                        $this->db->insert('rrhh.personal_detalle', $arryPers);
                        if($row->id_cargo_schoowl != null) {
                            $this->db->insert('persona_x_rol', array("nid_persona" => $idPers, "nid_rol" => $row->id_cargo_schoowl, "flg_acti" => FLG_ACTIVO) );
                        }
                        /////////////////
                        $this->registrarLogMigracion(ucwords($row->ape_pate_pers).' '.ucwords($row->ape_mate_pers).' '.ucwords($row->nom_persona), 'NUEVO PERSONAL', $codMigracion);
                        $this->grabarHuellas($row->nro_documento, $idPers, true);
                    } else { //UPDATE
                        $usuario = $this->m_utils->getById('persona', 'usuario', 'nid_persona', $idPers);
                        if($usuario == null) {//SI NO TIENE USUARIO, REVISAR SI TIENE CORREOS PARA PODER GENERARLE LOS USUARIOS
                            $correo = null;
                            if($row->correo_inst != null) {
                                $correo = $row->correo_inst;
                            } else if($row->correo_admi != null) {
                                $correo = $row->correo_admi;
                            }
                            if($correo != null) {//crear usuario!!!!
                                if (strpos($correo, '@') !== false) {
                                    $arryInsUpt['usuario'] = explode('@', $correo)[0];
                                    $arryInsUpt['clave']   = '123';
                                }
                            }
                        }
                        if($row->fecha_cese != '1900-01-01 00:00:00.000') {
                            if(date('Y-m-d H:i:s') <= $row->fecha_cese) {
                                $arryInsUpt['flg_acti'] = '1';
                                array_push($personalActivoArry, $idPers);
                            } else {
                                $arryInsUpt['flg_acti'] = '0';
                            }
                        } else {
                            $arryInsUpt['flg_acti'] = '1';
                            array_push($personalActivoArry, $idPers);
                        }
                        $this->db->where('nid_persona', $idPers);
                        $this->db->update('persona', $arryInsUpt);
                        //
                        unset($arryPers['id_persona']);
                        $this->db->where('id_persona', $idPers);
                        $this->db->update('rrhh.personal_detalle', $arryPers);
                        /*if($row->flg_acti == '0' || $row->fecha_cese != '1900-01-01 00:00:00.000') {
                            $this->registrarLogMigracion(ucwords($row->ape_pate_pers).' '.ucwords($row->ape_mate_pers).' '.ucwords($row->nom_persona), 'PERSONAL DE BAJA', $codMigracion);
                        }*/
                        if($row->id_cargo_schoowl != null) {
                            $hasRol = $this->m_utils->checkIfUserHasRol_Aux($idPers, $row->id_cargo_schoowl);
                            if(!$hasRol) {
                                $this->db->insert('persona_x_rol', array("nid_persona" => $idPers, "nid_rol" => $row->id_cargo_schoowl, "flg_acti" => FLG_ACTIVO) );
                            } else {//check if is activo
                                $hasRol = $this->m_utils->checkIfUserHasRol($idPers, $row->id_cargo_schoowl);
                                if(!$hasRol) {
                                    $this->db->where('nid_persona', $idPers);
                                    $this->db->where('nid_rol'    , $row->id_cargo_schoowl);
                                    $this->db->update('persona_x_rol', array("flg_acti" => FLG_ACTIVO) );
                                }
                            }
                        }
                        $this->grabarHuellas($row->nro_documento, $idPers, false);
                        //array_push($personalActivoArry, $idPers);
                    }
                    $this->registrarUpdtPersActivo($idPers, $row);
                } else {//ya existe el nro_doc, registrar su personal_activo
                    $usuario = $this->m_utils->getById('persona', 'usuario', 'nid_persona', $idPers);
                    if($usuario == null) {//SI NO TIENE USUARIO, REVISAR SI TIENE CORREOS PARA PODER GENERARLE LOS USUARIOS
                        $correo = null;
                        if($row->correo_inst != null) {
                            $correo = $row->correo_inst;
                        } else if($row->correo_admi != null) {
                            $correo = $row->correo_admi;
                        }
                        if($correo != null) {//crear usuario!!!!
                            if (strpos($correo, '@') !== false) {
                                $arryInsUpt['usuario'] = explode('@', $correo)[0];
                                $arryInsUpt['clave']   = '123';
                            }
                        }
                    }
                    if($row->fecha_cese != '1900-01-01 00:00:00.000') {
                        if(date('Y-m-d H:i:s') <= $row->fecha_cese) {
                            $arryInsUpt['flg_acti'] = '1';
                            array_push($personalActivoArry, $idPers);
                        } else {
                            $arryInsUpt['flg_acti'] = '0';
                        }
                    } else {
                        $arryInsUpt['flg_acti'] = '1';
                        array_push($personalActivoArry, $idPers);
                    }
                    unset($arryPers['id_persona']);
                    $this->db->where('nid_persona', $idPers);
                    $this->db->update('persona', $arryInsUpt);
                    
                    $this->db->where('id_persona', $idPers);
                    $this->db->update('rrhh.personal_detalle', $arryPers);
                    /*if($row->flg_acti == '0' || $row->fecha_cese != '1900-01-01 00:00:00.000') {
                        $this->registrarLogMigracion(ucwords($row->ape_pate_pers).' '.ucwords($row->ape_mate_pers).' '.ucwords($row->nom_persona), 'PERSONAL DE BAJA', $codMigracion);
                    }*/
                    if($row->id_cargo_schoowl != null) {
                        $hasRol = $this->m_utils->checkIfUserHasRol_Aux($idPers, $row->id_cargo_schoowl);
                        if(!$hasRol) {
                            $this->db->insert('persona_x_rol', array("nid_persona" => $idPers, "nid_rol" => $row->id_cargo_schoowl, "flg_acti" => FLG_ACTIVO) );
                        } else {//check if is activo
                            $hasRol = $this->m_utils->checkIfUserHasRol($idPers, $row->id_cargo_schoowl);
                            if(!$hasRol) {
                                $this->db->where('nid_persona', $idPers);
                                $this->db->where('nid_rol'    , $row->id_cargo_schoowl);
                                $this->db->update('persona_x_rol', array("flg_acti" => FLG_ACTIVO) );
                            }
                        }
                    }
                    //array_push($personalActivoArry, $idPers);
                    $this->registrarUpdtPersActivo($idPers, $row, $arryInsUpt, $arryPers);
                }
            }
            ///////////////// DESACTIVAR A TODO EL PERSONAL QUE NO ESTUVO EN ESTA LISTA!!! /////////////////
            $idPersonas = null;
            $mIds_size = count($personalActivoArry);
            $i = 1;
            foreach($personalActivoArry as $row) {
                if($i == $mIds_size) {
                    $idPersonas .= $row;
                } else {
                    $idPersonas .= $row.', ';
                }
                $i++;
            }
            $this->db->escape($idPersonas);
            $desactivarSQL = "UPDATE persona SET flg_acti = '0' WHERE nid_persona NOT IN ($idPersonas)       AND nid_persona IN (SELECT id_persona FROM rrhh.personal_detalle WHERE condicion_laboral = 'PLANILLA') ";
            $result = $this->db->query($desactivarSQL);
            if($result != 1) {
                throw new Exception('Error al desactivar en persona');
            }
            $desactivarSQL = "UPDATE persona_x_rol SET flg_acti = '0' WHERE nid_persona NOT IN ($idPersonas) AND nid_persona IN (SELECT id_persona FROM rrhh.personal_detalle WHERE condicion_laboral = 'PLANILLA') ";
            $result = $this->db->query($desactivarSQL);
            if($result != 1) {
                throw new Exception('Error al desactivar en persona_x_rol');
            }
            ///////////////// DESACTIVAR A TODO EL PERSONAL QUE NO ESTUVO EN ESTA LISTA!!! /////////////////
            $data['msj'] = utf8_encode('OK');
            $data['error'] = EXIT_SUCCESS;
            if($transaccion) {
                $this->db->trans_commit();
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
            if($transaccion) {
                $this->db->trans_rollback();
            }
        }
        return $data;
    }
    
    function registrarUpdtPersActivo($idPers, $row, $arryInsUptPersona = null, $arryPersDetalle = null) {
        $persActivo = $this->getPersonalActivo($idPers, $row->periodo_id);
        $arryPersAct = array("id_persona"          => $idPers,
                             "scirerh_personal_id" => $row->personal_id,
                             "cid_empresa"         => $row->empresa_id,
                             "cid_periodo"         => $row->periodo_id,
                             "fecha_ingreso"       => $row->fecha_ingreso,
                             "fecha_cese"          => $row->fecha_cese,
                             "fecha_ini_contrato"  => $row->fecha_ini_contrato,
                             "fecha_fin_contrato"  => $row->fecha_fin_contrato);
        $arryPersDetalle['plazo_contrato'] = (($row->fecha_fin_contrato == '1900-01-01 00:00:00.000') ? 'INDETERMINADO' : 'TEMPORAL');
        if($arryInsUptPersona != null) {
            $this->db->where('nid_persona', $idPers);
            $this->db->update('persona', $arryInsUptPersona);
        }
        if($persActivo == 0) {//No tiene personal activo
            $this->db->insert('rrhh.personal_activo', $arryPersAct);
            $this->db->where('id_persona', $idPers);
            $this->db->update('rrhh.personal_detalle', $arryPersDetalle);
        } else {//updatear historico de personal activo
            $this->db->where('id_persona', $idPers);
            $this->db->where('cid_periodo', $row->periodo_id);
            $this->db->update('rrhh.personal_activo', $arryPersAct);
            //
            $this->db->where('id_persona', $idPers);
            $this->db->update('rrhh.personal_detalle', $arryPersDetalle);
        }
    }
    
    function getPersonalActivo($idPersona, $idPeriodo) {
        $sql = "SELECT COUNT(1)
                  FROM rrhh.personal_activo
                 WHERE id_persona  = ?
                   AND cid_periodo = ?";
        $result = $this->db->query($sql, array($idPersona, $idPeriodo));
        return $result->row()->count;
    }
    
    function registrarLogMigracion($descMigracion, $detalle, $codMigracion) {
        $idPers = (_getSesion('nid_persona') != null)     ? $this->session->userdata('nid_persona') : 0;
        $nombre = (_getSesion('nombre_completo') != null) ? _getSesion('nombre_abvr')               : 'PROCESO DE MIGRACIÓN';
        $dataInsert = array("desc_migracion"  => $descMigracion,
                            "tipo_migracion"  => _PERSONAL_,
                            "detalle"         => $detalle,
                            "grupo_migracion" => $codMigracion,
                            "audi_usua_regi"  => $idPers,
                            "audi_pers_regi"  => $nombre);
        $this->db->insert('log_migracion', $dataInsert);
    }
    
    function grabarHuellas($nroDoc, $idPersona, $insert) {
        $nroDoc = '0'.$nroDoc;
        $sql = "SELECT * FROM dbo.empleados_huellas00 where idempleado = ?
                UNION ALL
                SELECT * FROM dbo.empleados_huellas01 where idempleado = ?
                UNION ALL
                SELECT * FROM dbo.empleados_huellas02 where idempleado = ?
                UNION ALL
                SELECT * FROM dbo.empleados_huellas03 where idempleado = ?
                UNION ALL
                SELECT * FROM dbo.empleados_huellas04 where idempleado = ?
                UNION ALL
                SELECT * FROM dbo.empleados_huellas05 where idempleado = ? ";
        $db_zkEnt = $this->load->database('zkEnterprise', TRUE);
        $huellas = $db_zkEnt->query($sql, array($nroDoc, $nroDoc, $nroDoc, $nroDoc, $nroDoc, $nroDoc));
        $huellas = (array) $huellas->result();
        $huellas = array_unique($huellas, SORT_REGULAR);
        if($insert) {
            foreach ($huellas as $row) {
                $dataInsert = array("id_persona"     => $idPersona,
                                    "empleado_id_zk" => $row->IdEmpleado,
                                    "dedo"           => $row->Dedo,
                                    "cadena"         => $row->Cadena,
                                    "algoritmo"      => $row->Algoritmo);
                $this->db->insert('rrhh.huella', $dataInsert);
            }
        } else {
            foreach ($huellas as $row) {
                $existeHuella = $this->getExisteHuellaByPersona($idPersona, $row->Dedo, $row->Cadena, $row->Algoritmo);
                if($existeHuella == 0) {
                    $dataInsert = array("id_persona"     => $idPersona,
                                        "empleado_id_zk" => $row->IdEmpleado,
                                        "dedo"           => $row->Dedo,
                                        "cadena"         => $row->Cadena,
                                        "algoritmo"      => $row->Algoritmo);
                    $this->db->insert('rrhh.huella', $dataInsert);
                }
            }
        }
    }
    
    function getExisteHuellaByPersona($idPersona, $dedo, $cadena, $algoritmo) {
        $sql = "SELECT COUNT(1)
                  FROM rrhh.huella
                 WHERE id_persona = ?
                   AND dedo       = ?
                   AND cadena     = ?
                   AND algoritmo  = ?";
        $result = $this->db->query($sql, array($idPersona, $dedo, $cadena, $algoritmo));
        return $result->row()->count;
    }
    
    function actualizarDatos($idPersGlobal, $idPeriodoGlobal, $arryUpdatePersonal, $arryUpdatePersonalActivo) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = 'No hubo cambios';
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $db_scirerh->trans_begin();
        try {
            $rowsAffected = 0;
            if(count($arryUpdatePersonal) > 0) {
                $db_scirerh->where('Personal_Id', $idPersGlobal);
                $db_scirerh->update('dbo.Personal', $arryUpdatePersonal);
                $rowsAffected = $db_scirerh->affected_rows();
            }
            if(count($arryUpdatePersonalActivo) > 0) {
                $db_scirerh->where('Personal_Id', $idPersGlobal);
                $db_scirerh->where('Periodo_Id', $idPeriodoGlobal);
                $db_scirerh->update('dbo.Personal_activo', $arryUpdatePersonalActivo);
                $rowsAffected = $rowsAffected + $db_scirerh->affected_rows();
            }
            if($rowsAffected > 0) {
                $db_scirerh->trans_commit();
                $data = $this->getDatosActualizadosForTablaHTML($idPersGlobal, $idPeriodoGlobal);
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = 'Se modificaron los datos';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $db_scirerh->trans_rollback();
        }
        return $data;
    }
    
    function getQuery($mes) {
        return " SELECT LOWER(p.apellido_paterno+' '+p.apellido_materno+' '+p.nombres) AS nombre_completo,
                        p.apellido_paterno+' '+p.apellido_materno AS apellidos,
                        LOWER(p.nombres)            AS nombres,
                        p.tipo_doc_id               AS id_tipo_doc,
                        p.nro_doc                   AS nro_documento,
                        p.compania_id               AS empresa_id,
                        com.descripcion             AS empresa_razon_social,
                        p.personal_id,
                        pa.periodo_id,
                        CASE WHEN p.estado_id          = '01' THEN '1' ELSE '0' END AS flg_acti,
                        CASE WHEN p.email              = '' THEN NULL ELSE p.email        END AS correo_pers,
                        CASE WHEN p.correo_otro        = '' THEN NULL ELSE p.correo_otro  END AS correo_inst,
                        CASE WHEN p.correo_otro2       = '' THEN NULL ELSE p.correo_otro2 END AS correo_adm,
                        p.convenio_evita_tributacion_id AS id_area_general,
                        cet.descripcion                 AS desc_area_general,
                        p.proyecto_id                   AS id_area_especifica,
                        (SELECT pro.descripcion FROM dbo.proyecto pro WHERE p.proyecto_id = pro.proyecto_id) AS desc_area_especifica,
                        p.situacion_id                  AS id_cargo_schoowl,
                        (SELECT sit.descripcion FROM dbo.situacion sit WHERE p.situacion_id = sit.situacion_id) AS desc_cargo_schoowl,
                        pa.categoria_auxiliar_id        AS id_jornada_laboral,
                        cataux.descripcion              AS desc_jornada_laboral,
                        pa.personal_anexo_id            AS id_sede_control,
                        panex.descripcion               AS desc_sede_control,
                        pa.personal_anexo2_id           AS id_nivel_control,
                        panex2.descripcion              AS desc_nivel_control
                        FROM dbo.Personal              p,
                        dbo.Personal_activo            pa,
                        dbo.periodo                    pe,
                        dbo.mes                        m,
                        dbo.compania                   com,
                        dbo.convenio_evita_tributacion cet,
                        /*dbo.proyecto                   pro,
                        dbo.situacion                  sit,*/
                        dbo.categoria_auxiliar         cataux,
                        dbo.personal_anexo             panex,
                        dbo.personal_anexo2            panex2
                  WHERE m.ejercicio_id                  = YEAR(getdate())
                    AND m.nmes                          = $mes
                    AND p.estado_id                     = '01'
                    AND p.personal_id                   = pa.personal_id
                    AND pa.periodo_id                   = pe.periodo_id
                    AND pe.mes_id                       = m.mes_id
                    AND pe.compania_id                  = p.compania_id
                    AND p.compania_id                   = pa.compania_id
                    AND p.compania_id                   = com.compania_id
                    AND p.convenio_evita_tributacion_id = cet.convenio_evita_tributacion_id
                    /*AND p.proyecto_id                   = pro.proyecto_id
                    AND p.situacion_id                  = sit.situacion_id*/
                    AND pa.categoria_auxiliar_id        = cataux.categoria_auxiliar_id
                    AND pa.personal_anexo_id            = panex.personal_anexo_id
                    AND pa.personal_anexo2_id           = panex2.personal_anexo2_id
                    ORDER BY p.apellido_paterno, p.apellido_materno, p.nombres";
    }
    
    function getPersonalUpdate() {
        $sql = $this->getQuery("MONTH(getdate())");
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $personal = $db_scirerh->query($sql);
        if (count($personal->result()) == 0) {
            $sql = $this->getQuery("MONTH(getdate()) - 1");
            $personal = $db_scirerh->query($sql);
        }
        return $personal->result();
    }
    
    function getAreasGenerales() {
        $sql = "SELECT convenio_evita_tributacion_id AS id,
                       descripcion                   AS descr
                  FROM dbo.convenio_evita_tributacion
                -- WHERE convenio_evita_tributacion_id <> '0' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $areas = $db_scirerh->query($sql);
        return $areas->result();
    }
    
    function getAreasEspecificas() {
        $sql = "SELECT proyecto_id AS id,
                       descripcion AS descr
                  FROM dbo.proyecto
                 WHERE estado_id  = '01'
                   --AND proyecto_id <> '0000000000' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $areas = $db_scirerh->query($sql);
        return $areas->result();
    }
    
    function getCargos() {
        $sql = "SELECT situacion_id AS id,
                       descripcion  AS descr
                  FROM dbo.situacion
                 --WHERE situacion_id <> '00' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $cargos = $db_scirerh->query($sql);
        return $cargos->result();
    }
    
    function getJornadasLaborales() {
        $sql = "SELECT categoria_auxiliar_id AS id,
                       descripcion           AS descr
                  FROM dbo.categoria_auxiliar
                 --WHERE categoria_auxiliar_id <> '000000000000000' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $jornadas = $db_scirerh->query($sql);
        return $jornadas->result();
    }
    
    function getSedesControl() {
        $sql = "SELECT personal_anexo_id AS id,
                       descripcion       AS descr
                  FROM dbo.personal_anexo
                 --WHERE personal_anexo_id <> '00000000' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $sedes = $db_scirerh->query($sql);
        return $sedes->result();
    }
    
    function getNivelesControl() {
        $sql = "SELECT personal_anexo2_id AS id,
                       descripcion        AS descr
                  FROM dbo.personal_anexo2
                 --WHERE personal_anexo2_id <> '00000000' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $niveles = $db_scirerh->query($sql);
        return $niveles->result();
    }
    
    function getAreasEspecificasByGeneral($idAreaGeneral) {
        $sql = "SELECT proyecto_id AS id,
                       descripcion AS descr
                  FROM dbo.proyecto
                 WHERE estado_id                     = '01'
                   AND LTRIM(RTRIM(codigo_auxiliar)) = ?
                   --AND proyecto_id <> '0000000000' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $areas = $db_scirerh->query($sql, array($idAreaGeneral));
        return $areas->result();
    }
    
    function getCargosByGeneral($idAreaGeneral) {
        $sql = "SELECT situacion_id AS id,
                       descripcion  AS descr
                  FROM dbo.situacion
                 WHERE LTRIM(RTRIM(cod_auxiliar)) = ?
                 --WHERE situacion_id <> '00' ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $cargos = $db_scirerh->query($sql, array($idAreaGeneral));
        return $cargos->result();
    }
    
    function getDatosActualizadosForTablaHTML($idPersonal, $idPeriodo) {
        $sql = "SELECT TOP 1 cet.descripcion                    AS area_general,
                             pro.descripcion                    AS area_especifica,
                             sit.descripcion                    AS cargo,
                             cataux.descripcion                 AS jornada_laboral,
                             panex.descripcion                  AS sede_control,
                             panex2.descripcion                 AS nivel_control,
                             cet.convenio_evita_tributacion_id  AS id_area_general,
                             p.proyecto_id                      AS id_area_especifica,
                             p.situacion_id                     AS id_cargo_schoowl,
                             pa.categoria_auxiliar_id           AS id_jornada_laboral,
                             pa.personal_anexo_id               AS id_sede_control,
                             pa.personal_anexo2_id              AS id_nivel_control,
                             LOWER(p.apellido_paterno+' '+p.apellido_materno+' '+p.nombres) AS nombre_completo,
                             p.personal_id,
                             pa.periodo_id,
                             CASE WHEN p.email              = '' THEN NULL ELSE p.email END AS correo_pers,
                             CASE WHEN p.correo_otro        = '' THEN NULL ELSE p.correo_otro  END AS correo_inst,
                             CASE WHEN p.correo_otro2       = '' THEN NULL ELSE p.correo_otro2 END AS correo_adm
                  FROM dbo.Personal                   p,
                       dbo.Personal_activo            pa,
                       dbo.convenio_evita_tributacion cet,
                       dbo.proyecto                   pro,
                       dbo.situacion                  sit,
                       dbo.categoria_auxiliar         cataux,
                       dbo.personal_anexo             panex,
                       dbo.personal_anexo2            panex2
                 WHERE p.personal_id                   = ?
                   AND pa.periodo_id                   = ?
                   AND pa.personal_id                  = p.personal_id
                   AND p.convenio_evita_tributacion_id = cet.convenio_evita_tributacion_id
                   AND p.proyecto_id                   = pro.proyecto_id
                   AND p.situacion_id                  = sit.situacion_id
                   AND pa.categoria_auxiliar_id        = cataux.categoria_auxiliar_id
                   AND pa.personal_anexo_id            = panex.personal_anexo_id
                   AND pa.personal_anexo2_id           = panex2.personal_anexo2_id";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $datos = $db_scirerh->query($sql, array($idPersonal, $idPeriodo));
        return $datos->row_array();
    }
    
    function getPersonalRecibos() {
        $sql = "SELECT p.nid_persona,
                       p.scirerh_personal_id AS personal_id,
                       p.correo_pers,
                       NULL AS periodo_id,
                       pd.desc_empresa_recibos AS empresa_razon_social,
                       INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona)) AS nombre_completo,
                       p.nro_documento,
                       pd.id_area_general,
                       pd.id_area_especifica,
                       pd.id_cargo,
                       pd.id_jornada_laboral,
                       pd.id_sede_control,
                       pd.id_nivel_control,
                       pd.desc_area_general,
                       pd.desc_area_especifica,
                       pd.id_cargo AS id_cargo_schoowl,
                       pd.desc_cargo AS desc_cargo_schoowl,
                       pd.desc_jornada_laboral,
                       pd.desc_sede_control,
                       pd.desc_nivel_control,
                       p.correo_inst,
                       p.correo_admi AS correo_adm
                  FROM persona p,
                       rrhh.personal_detalle pd
                 WHERE p.nid_persona = pd.id_persona
                   AND pd.condicion_laboral = 'RECIBOS'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function actualizarPersonalRecibosSpreadSheet($arryPersonal) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            foreach ($arryPersonal as $row) {
                if($row['nro_documento'] == null || $row['nro_documento'] == '' || $row['nombres'] == null || $row['nombres'] == '' ||
                   $row['apellido_paterno'] == null || $row['apellido_paterno'] == '' || $row['apellido_materno'] == null || $row['apellido_materno'] == '' ) {
                    continue;
                }
                //Revisar por num doc
                $estadoCivil = null;
                switch (strtoupper($row['estado_civil'])) {
                    case 'SOLTERO'           : $estadoCivil = 1; break;
                    case 'CASADO/CONVIVIENTE': $estadoCivil = 2; break;
                    case 'VIUDO (A)'         : $estadoCivil = 3; break;
                    case 'DIVORCIADO (A)'    : $estadoCivil = 4; break;
                    case 'CONVIVIENTE'       : $estadoCivil = 5; break;
                    default: $estadoCivil = null; break;
                }
                $tipoDocumento = null;
                switch (strtoupper($row['tipo_documento'])) {
                    case 'DNI' : $tipoDocumento = 2; break;
                    case 'C.E.': $tipoDocumento = 1; break;
                    default: $tipoDocumento = null; break;
                }
                $sedeControl = null;
                switch (strtoupper($row['sede_control'])) {
                    case 'SIN SEDE DE CONTROL' : $sedeControl = null; break;
                    case 'SEDE CENTRAL'        : $sedeControl = 6; break;
                    case 'SEDE ECOLOGICA'      : $sedeControl = 2; break;
                    case 'SEDE SUPERIOR'       : $sedeControl = 5; break;
                    case 'SEDE INDUSTRIAL'     : $sedeControl = 3; break;
                    case 'SEDE INICIAL'        : $sedeControl = 4; break;
                    case 'ROTATIVOS'           : $sedeControl = 7; break;
                    case 'SEDE INDUSTRIAL- A. CONTABILIDAD' : $sedeControl = 3; break;
                    case 'HUACHO'              : $sedeControl = 1; break;
                    default: $sedeControl = null; break;
                }
                $idPers = $this->m_utils->getById('persona', 'nid_persona', 'nro_documento', $row['nro_documento']);
                $arryInsUpt = array("nom_persona"         => ucwords($row['nombres']) ,
                                    "ape_pate_pers"       => ucwords($row['apellido_paterno']),
                                    "ape_mate_pers"       => ucwords($row['apellido_materno']),
                                    "fec_naci"            => $row['fecha_nacimiento'],
                                    "tipo_documento"      => $tipoDocumento,
                                    "nro_documento"       => $row['nro_documento'],
                                    "sexo"                => ($row['sexo'] == 'masculino' ? '1' : '2'),
                                    "estado_civil"        => $estadoCivil,
                                    "telf_pers"           => ($row['telefono1'] == '' ? null : $row['telefono1']),
                                    "telf_pers_2"         => ($row['telefono2'] == '' ? null : $row['telefono2']),
                                    "telf_pers_3"         => ($row['telefono3'] == '' ? null : $row['telefono3']),
                                    "flg_acti"            => ($row['estado'] == 'ACTIVO' ? '1' : '0'),
                                    "correo_pers"         => ($row['correo_personal'] != null) ? strtolower($row['correo_personal']) : null,
                                    "correo_inst"         => ($row['correo_inst']     != null) ? strtolower($row['correo_inst']) : null,
                                    "correo_admi"         => ($row['correo_admin']    != null) ? strtolower($row['correo_admin']) : null,
                                    "tipo_sangre"         => ($row['tipo_sangre'] != null && $row['tipo_sangre'] != '' ? strtoupper($row['tipo_sangre']) : null) ) ;
                $arryPers = array("id_persona"           => null,
                                  "id_tipo_area"         => (strtoupper($row['tipo_area']) == 'ADMINISTRATIVO' ? '001' : '004'),
                                  "desc_tipo_area"       => strtoupper($row['tipo_area']),
                                  "nro_hijos"            => ($row['nro_hijos'] == null ? 0 : $row['nro_hijos']),
                                  "condicion_laboral"    => 'RECIBOS',
                                  "tipo_via_id"          => $this->getIdByDesc("tipo_via", "tipo_via_id", "descripcion", $row['tipo_via']),
                                  "tipo_via_desc"        => ($row['tipo_via'] == null ? null : ucwords($row['tipo_via']) ),
                                  "numero_via"           => $row['nro_via'],
                                  "interior_via"         => ($row['interior_via'] == ''  ? null : $row['interior_via']) ,
                                  "tipo_zona_id"         => $this->getIdByDesc("tipo_zona", "tipo_zona_id", "descripcion", $row['tipo_zona']),
                                  "tipo_zona_desc"       => ($row['tipo_zona'] == null ? null : ucwords($row['tipo_zona']) ),
                                  "nombre_zona"          => ($row['nombre_zona'] == null ? null : ucwords($row['nombre_zona']) ),
                                  "referencia"           => ($row['referencia'] == null ? null : ucfirst($row['referencia']) ),
                                  "departamento"         => ($row['departamento'] == '' ? null : $row['departamento']),
                                  "manzana"              => ($row['manzana']      == '' ? null : strtoupper($row['manzana']) ),
                                  "lote"                 => ($row['lote']         == '' ? null : strtoupper($row['lote']) ),
                                  "kilometro"            => ($row['kilometro']    == '' ? null : strtoupper($row['kilometro']) ),
                                  "bloque"               => ($row['block']        == '' ? null : strtoupper($row['block']) ),
                                  "etapa"                => ($row['etapa']        == '' ? null : strtoupper($row['etapa']) ),
                                  "id_area_general"      => $this->getIdByDesc("convenio_evita_tributacion", "convenio_evita_tributacion_id", "descripcion", $row['area_general']),
                                  "id_area_especifica"   => $this->getIdByDesc("proyecto", "proyecto_id", "descripcion", $row['area_especifica']),
                                  "id_cargo"             => $this->getIdByDesc("situacion", "situacion_id", "descripcion", $row['cargo']),
                                  "id_jornada_laboral"   => $this->getIdByDesc("categoria_auxiliar", "categoria_auxiliar_id", "descripcion", $row['jornada_laboral']),
                                  "id_sede_control"      => $sedeControl,
                                  "id_nivel_control"     => $this->getIdByDesc("personal_anexo2", "personal_anexo2_id", "descripcion", $row['nivel_control']),
                                  "desc_area_general"    => strtoupper($row['area_general']),
                                  "desc_area_especifica" => strtoupper($row['area_especifica']),
                                  "desc_cargo"           => strtoupper($row['cargo']),
                                  "desc_jornada_laboral" => strtoupper($row['jornada_laboral']),
                                  "desc_sede_control"    => strtoupper($row['sede_control']),
                                  "desc_nivel_control"   => strtoupper($row['nivel_control']),
                                  "cid_empresa_recibos"  => $this->getIdByDesc("compania", "compania_id", "descripcion", $row['empresa']),
                                  "desc_empresa_recibos" => ($row['nivel_control'] == null ? null : strtoupper($row['empresa']) )
                                  //"codigo_ubigeo"     => $row->codigo_ubigeo
                                  );
                if($idPers == null) {//INSERT
                    $correo = null;
                    if($arryInsUpt['correo_inst'] != null) {
                        $correo = $arryInsUpt['correo_inst'];
                    } else if($arryInsUpt['correo_admi'] != null) {
                        $correo = $arryInsUpt['correo_admi'];
                    }
                    if($correo != null) {//crear usuario!!!!
                        if (strpos($correo, '@') !== false) {
                            $arryInsUpt['usuario'] = explode('@', $correo)[0];
                            $arryInsUpt['clave']   = '123';
                        }
                    }
                    $this->db->insert('persona', $arryInsUpt);
                    $idPers = $this->db->insert_id();
                    $arryPers['id_persona'] = $idPers;
                    $this->db->insert('rrhh.personal_detalle', $arryPers);
                    if($arryPers['id_cargo'] != null) {
                        $this->db->insert('persona_x_rol', array("nid_persona" => $idPers, "nid_rol" => $arryPers['id_cargo'], "flg_acti" => FLG_ACTIVO) );
                    }
                } else {//UPDATE
                    $usuario = $this->m_utils->getById('persona', 'usuario', 'nid_persona', $idPers);
                    if($usuario == null) {//SI NO TIENE USUARIO, REVISAR SI TIENE CORREOS PARA PODER GENERARLE LOS USUARIOS
                        $correo = null;
                        if($arryInsUpt['correo_inst'] != null) {
                            $correo = $arryInsUpt['correo_inst'];
                        } else if($arryInsUpt['correo_admi'] != null) {
                            $correo = $arryInsUpt['correo_admi'];
                        }
                        if($correo != null) {//crear usuario!!!!
                            if (strpos($correo, '@') !== false) {
                                $arryInsUpt['usuario'] = explode('@', $correo)[0];
                                $arryInsUpt['clave']   = '123';
                            }
                        }
                    }
                    if($arryPers['id_cargo'] != null) {
                        $hasRol = $this->m_utils->checkIfUserHasRol_Aux($idPers, $arryPers['id_cargo']);
                        if(!$hasRol) {
                            $this->db->insert('persona_x_rol', array("nid_persona" => $idPers, "nid_rol" => $arryPers['id_cargo'], "flg_acti" => FLG_ACTIVO) );
                        } else {//check if is activo
                            $hasRol = $this->m_utils->checkIfUserHasRol($idPers, $arryPers['id_cargo']);
                            if(!$hasRol) {
                                $this->db->where('nid_persona', $idPers);
                                $this->db->where('nid_rol'    , $arryPers['id_cargo']);
                                $this->db->update('persona_x_rol', array("flg_acti" => FLG_ACTIVO) );
                            }
                        }
                    }
                    $this->db->where('nid_persona', $idPers);
                    $this->db->update('persona', $arryInsUpt);
                    unset($arryPers['id_persona']);
                    $this->db->where('id_persona', $idPers);
                    $this->db->update('rrhh.personal_detalle', $arryPers);
                }
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = 'Se migró la información de personal por recibos';
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getIdByDesc($tabla, $id, $columnDesc, $descTipoZona) {
        $sql = "SELECT TOP 1 ".$id." id
                  FROM dbo.".$tabla."
                 WHERE UPPER(".$columnDesc.") = UPPER(?) ";
        $db_scirerh = $this->load->database('scirerh', TRUE);
        $result = $db_scirerh->query($sql, array($descTipoZona));
        return ($result->num_rows() == 1 ? $result->row()->id : null );
    }
}