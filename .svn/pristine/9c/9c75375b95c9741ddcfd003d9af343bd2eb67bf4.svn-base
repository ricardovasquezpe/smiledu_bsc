<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('_getArrayStringFromArray')) {
    /**
     * Desencripta usando mcrypt_decrypt
     * @author rVasquez
     * @since 22.03.2016
     * @param $toDecrypt variable que sera desencriptada
     * @return variable desencriptada
     */
    function _getArrayStringFromArray($data, $decrypt = null) {
        $arrayIds = null;
	    foreach ($data as $var){
	        $id = null;
	        if($decrypt == 1){
	            $id = $this->lib_utils->simple_decrypt($var, CLAVE_ENCRYPT);
	        }else{
	            $id = $this->encrypt->decode($var);
	        }
	        if($id != null){
	            $arrayIds .= $id.',';
	        }
	    }
	    $arrayIds = substr($arrayIds,0,(strlen($arrayIds)-1));
	    return $arrayIds;
    }
}

if(!function_exists('_getFotoFromCookie')) {
    function _getFotoFromCookie($cookieVal) {
        return _simple_decrypt($cookieVal);
    }
}

if(!function_exists('_searchInputHTML')) {
    function _searchInputHTML($texto){
        $data['btnSearch'] = '<a type="button" class="mdl-button mdl-js-button mdl-button--icon" onclick="setFocus(\'#searchMagic\')" id="openSearch" style="margin: 0 7.5px;">
                                  <i class="mdi mdi-magic md-0"></i>
                              </a>';
        $data['inputSearch'] = '<div class="mdl-header-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-magic md-0"></i>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield" id="cont_inputtext_busqueda">
                                        <input class="mdl-textfield__input" type="text" id="searchMagic">
                                        <label class="mdl-textfield__label" for="searchMagic">'.$texto.'</label>
                                    </div>
                                    <div class="mdl-icon mdl-right">
                                        <a type="button" class="mdl-button mdl-js-button mdl-button--icon" id="closeSearch">
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                    </div>
                                </div>';
        return $data;
    }
}

if(!function_exists('buildEditItemCronograma')) {
    /**
     * Transforma la fecha en el formato indicado para una tabla
     * @author dfloresgonz
     * @since 22.03.2016
     * @param date $fecha
     * @param string $formato d/m/Y, d/m/Y h:i:s A
     * @return fecha con formato
     */
    function buildEditItemCronograma($result,$cabecera=null) {
        $i=0; $text='';  //print_r($result[0]);exit;
        foreach ($result[0] as $item)
        {
            $text.='<div class="col-sm-12">';
            $text.='<div class="mdl-textfield mdl-js-textfield">';
            $text.='<input class="mdl-textfield__input" type="text" id="item_cronograma_'.$i.'" name="item_cronograma_'.$i.'" value="'.$item.'">';
            $text.='<label class="mdl-textfield__label" for="item_cronograma_'.$i.'">'.$cabecera[$i].'</label>';
            $text.='</div></div>'; $i++;
        }
        return $text;
    }

}
if(!function_exists('validar_decimales')) {
    function __validar_decimales($num) {
        if (preg_match("/^[01.]*$/",$num)){
            return 1;
        } else {
            return 0;
        }
    }
}

if(!function_exists('__combos_cronograma_detalle')) {
    function __combos_cronograma_detalle($id) {
        $checked1 = '';$checked2 = '';$checked3 = '';
        switch($id){
            case '1': $checked1 = "checked"; break;
            case '2': $checked2 = "checked"; break;
            case '3': $checked3 = "checked"; break;
            default : $checked3 = "checked"; break;
        }
        return '<div class="col-sm-12 p-0">
        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="matricula">
        <input type="radio" id="matricula" class="mdl-radio__button" name="options" value="'._encodeCI(1).'" '.$checked1.'>
        <span class="mdl-radio__label">Matricula</span>
        </label>
        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="ratificacion">
        <input type="radio" id="ratificacion" class="mdl-radio__button" name="options" value="'._encodeCI(2).'" '.$checked2.'>
        <span class="mdl-radio__label">Ratificación</span>
        </label>
        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="cuotas">
        <input type="radio" id="cuotas" class="mdl-radio__button" name="options" value="'._encodeCI(3).'" '.$checked3.'>
        <span class="mdl-radio__label">Cuota</span>
        </label>
        </div>';
    }
}

if(!function_exists('__mesesTexto')) {
    function __mesesTexto($id = 1){
        $id = (int)$id;
        $meses = array(1 =>'Enero',2 =>'Febrero',3 =>'Marzo',4 =>'Abril',5 =>'Mayo',6 =>'Junio',7 =>'Julio',8 =>'Agosto',9 =>'Setiembre',10 =>'Octubre',11=>'Noviembre',12=>'Diciembre');
        return $meses[$id];
    }
}

if(!function_exists('__mesesTextoNumber')) {
    function __mesesTextoNumber($id = 'Enero'){
        $meses = array('Enero' =>1,'Febrero'=>2,'Marzo' =>3,'Abril' =>4,'Mayo' =>5,'Junio' =>6,'Julio'=>7,'Agosto' =>8,'Setiembre' =>9,'Octubre' =>10,'Noviembre'=>11,'Diciembre'=>12);
        return $meses[$id];
    }
}

if(!function_exists('__urls_amigables')) {
    function __urls_amigables($url) {
    
        // Tranformamos todo a minusculas
        $url = strtolower($url);
    
        //Rememplazamos caracteres especiales latinos
        $find = array('ï¿½', 'ï¿½', 'ï¿½', 'ï¿½', 'ï¿½', 'ï¿½');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace ($find, $repl, $url);
    
        // Aï¿½aadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace ($find, '-', $url);
    
        // Eliminamos y Reemplazamos demï¿½s caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        return $url;
    }
}

if(!function_exists('__generateFormatString')) {
    function __generateFormatString($correlativo,$length){
        $lengthCorre = strlen($correlativo);
        $correlativoNew = null;
        for($i = $lengthCorre; $i < $length ; $i++){
            $correlativoNew .= '0';
        }
        $correlativoNew .= $correlativo;
        return $correlativoNew;
    }
}