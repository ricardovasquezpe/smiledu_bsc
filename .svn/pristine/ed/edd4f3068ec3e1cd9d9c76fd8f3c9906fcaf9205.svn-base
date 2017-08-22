<?php

$pdfObj->SetColumns(2);
$CI =& get_instance();
$CI->load->model('m_crear_encuesta');

$cantAulas = count($aulasBySede);
$cnt = 0;
foreach ($aulasBySede as $row) {
    $htmlBody = '<h3 align="center">Listado de Estudiantes</h3>';
    $htmlBody .= '<span style="font-weight: bold;">Aula:</span>&nbsp;('.$row['nid_aula'].')&nbsp;&nbsp;'.$row['aula'].'&nbsp;&nbsp;&nbsp;
                          <span style="font-weight: bold;">Tutor:</span>&nbsp;'.$row['tutor'].'<br><br>';
    $estudiantes = $CI->m_crear_encuesta->getEstudiantesSinLlenarEncuestaPadres($row['nid_aula']);
    $htmlBody .= '<table border="1" style="border-collapse: collapse;">';
    $htmlBody .= '<tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th>Vía</th>
                    <th>¿Enc. Física entregada?</th>
                    <th>¿Enc. Física recibida?</th>
                  </tr>';
    $val = 0;
    foreach($estudiantes as $row) {
        $val++;
        $htmlBody .= "<tr>
                        <td>$val</td>
                        <td>".$row['estudiante']."</td>
                        <td>".$row['encuestado']."</td>
                        <td>".($row['encuestado'] != null ? '------------' : null)."</td>
                        <td>".($row['encuestado'] != null ? '------------' : null)."</td>
                      </tr>";
    }
    $htmlBody .= '</table>';
    $pdfObj->WriteHTML(utf8_encode($htmlBody));
    $cnt++;
    if($cnt < $cantAulas) {
        $pdfObj->AddColumn();
    }
}

$pdfObj->Output(utf8_encode(strtolower($sede_desc))."_listado_tutores.pdf", 'D');