<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_createTableCursos')) {
    function _createTableCursos($arrayCursos) {
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbCursos" data-show-columns="false">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        foreach($arrayCursos as $row) {
            $cursosEquiv = explode(',', $row['string_agg']);
            $row_col0  = array('data' => '<FONT>'.$row['desc_curso'] , 'style'=>'width:20%; border-left-color: white;text-align:left;padding-left:10px;', 'rowspan' => count($cursosEquiv));
            //$this->table->add_row($row_col0);
            $j = 0;
            if($cursosEquiv[0] != ''){
                foreach($cursosEquiv as $cursoEquiv) {
                    $cursoEquiv = explode('|', $cursoEquiv);
                    if($j == 0){
                        $row_col11 = array('data' => '<FONT>'.strtolower($cursoEquiv[0]).'</FONT>' , 'style'=>'width:10%; border-left-color: white;text-align:left;padding-left:10px;');
                        $CI->table->add_row($row_col0, $row_col11);
                    } else {
                        $row_col11 = array('data' => '<FONT>'.strtolower($cursoEquiv[0]).'</FONT>' , 'style'=>'width:10%; border-left-color: white;text-align:left;padding-left:10px;');
                        $CI->table->add_row($row_col11);
                    }
                    $j++;
                }
            } else{
                $CI->table->add_row($row_col0);
            }
        }
        $head_1 = array('data' => '<FONT>Cursos</FONT>' ,'style' =>'border-bottom: 1px solid #ccc;padding-left:10px;color:#757575; left;');
        $head_2 = array('data' => '<FONT>Cursos Equiv</FONT><br>','style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
        
        $CI->table->set_heading( $head_1, $head_2);
         
        $tabla = $CI->table->generate();
        return $tabla;
    }
} 

if(!function_exists('_createTableOrdenMerito')) {
    function _createTableOrdenMerito($arrayAlumno, $indic=null, $idGrado=null) {
        $CI =& get_instance();
        $cont = 0;
        $fotoPrimerPuesto = null;
        $nomAlumno        = null;
        $grado            = null;
         $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbOrdMerito" data-show-columns="false">',
    				   'table_close' => '</table>');
        $CI->table->set_template($tmpl);

        $head_1 = array('data' => '<FONT>Orden</FONT>' ,'style' =>'border-bottom: 1px solid #ccc;padding-left:10px;color:#757575; left;');
    	$head_2 = array('data' => '<FONT>Apellido y Nombre</FONT><br>','style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
    	$head_3 = array('data' => '<FONT>Grado</FONT><br>','style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
    	$head_4 = array('data' => '<FONT>Sede</FONT><br>','style'  =>'border-bottom: 1px solid #ccc;color:#757575; center;');
    	$head_5 = array('data' => '<FONT>Nota</FONT><br>','style'  =>'border-bottom: 1px solid #ccc;color:#757575; center;');

        $CI->table->set_heading( $head_1, $head_2, $head_3, $head_4, $head_5);
    	foreach($arrayAlumno as $row) {
    	    $cont++;
    	    $fotoPrimerPuesto = ($cont == 1) ? '<img src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                                                <p class="classroom-value" style="display: inline">' : $fotoPrimerPuesto;
    	    $nomAlumno        = ($cont == 1) ? $row['nombre_corto'] : $nomAlumno;
    	    $grado            = ($cont == 1) ? $row['grado'] : $grado;

    	   if($indic != null) {
    	       $fotoAlum = null;
    	   } else {
    	       $fotoAlum = '<img src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                             <p class="classroom-value" style="display: inline">';
    	   }
    	    
    	    if($idGrado == null) {
    	        $row_2 = array('data' => $row['grado'], 'class' => 'text-left');
    	    } else {
    	        $row_3 = array('data' => $row['desc_sede'], 'class' => 'text-left');
    	    }

    		$row_1 = array('data' => $fotoAlum.$row['nombre_corto'], 'class' => 'text-left btnAlumnoID', 'data-id_alumno' => _simple_encrypt($row['nid_persona']));
    		$row_0 = array('data' => $row['rank'] , 'class' => 'text-left');
    		$row_2 = array('data' => $row['grado'], 'class' => 'text-left');
    		$row_4 = array('data' => $row['promedio'], 'class' => 'text-left');
    		$row_3 = array('data' => $row['desc_sede'], 'class' => 'text-left');
    		$CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
    	}
        $table = $CI->table->generate();
        return array($table, $fotoPrimerPuesto, $nomAlumno, $grado) ;
    }
}

if(!function_exists('_createTableProfesorAula')) {
    function _createTableProfesor($arrayProf) {
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbProfAula" data-show-columns="false">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        //        $head_0_1 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => '<FONT>#</FONT>' ,'style' =>'border-bottom: 1px solid #ccc;padding-left:10px;color:#757575; left;');
        $head_2 = array('data' => '<FONT>Apellido y Nombre</FONT><br>'  ,'style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
        $head_3 = array('data' => '<FONT>Cursos</FONT><br>','style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
        //$head_4 = array('data' => '<FONT>Puntaje</FONT><br>'     ,'style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
        $cnt = 1;
        //$head_3   = array('data' => 'Accion');
        $CI->table->set_heading( $head_1, $head_2, $head_3);
        foreach($arrayProf as $row) {
            $fotoDocente = '<img src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                             <p class="classroom-value" style="display: inline">';
            $row_1 = array('data' => $fotoDocente.$row['nom_persona'], 'class' => 'text-left');
            $row_0 = array('data' => $cnt , 'class' => 'text-left');
            $row_2 = array('data' => $row['desc_curso'], 'class' => 'text-left');
            $CI->table->add_row($row_0, $row_1, $row_2);
            $cnt++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableOrdenMerito')) {
    function _createTableOrdenMerito($arrayAlumno) {
        $CI =& get_instance();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbCursos" data-show-columns="false">',
                      'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        //        $head_0_1 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => '<FONT>Orden</FONT>' ,'style' =>'border-bottom: 1px solid #ccc;padding-left:10px;color:#757575; left;');
        $head_2 = array('data' => '<FONT>Apellido y Nombre</FONT><br>'  ,'style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
        $head_3 = array('data' => '<FONT>Nota</FONT><br>','style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');
        //$head_4 = array('data' => '<FONT>Puntaje</FONT><br>'     ,'style' =>'border-bottom: 1px solid #ccc;color:#757575; center;');

        //$head_3   = array('data' => 'Accion');
        $CI->table->set_heading( $head_1, $head_2, $head_3);
        foreach($arrayAlumno as $row) {
            $fotoAlum    = '<img src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                            <p class="classroom-value" style="display: inline">';

            $row_1 = array('data' => $fotoAlum.$row['nombre_corto'], 'class' => 'text-left btnAlumnoID', 'data-id_alumno' => _simple_encrypt($row['nid_persona']));
            $row_0 = array('data' => $row['rank'] , 'class' => 'text-left');
            $row_2 = array('data' => $row['promedio'], 'class' => 'text-left');
            $CI->table->add_row($row_0, $row_1, $row_2);
        }
        $table = $CI->table->generate();
        return $table;
    }
}
    