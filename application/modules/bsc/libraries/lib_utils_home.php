<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_utils_home {
    function imprimir() {
        $print = "HOLA";
        log_message('error', $print);
        return $print;
    }
}