<?php

$htmlBody = '<h3 align="center">Listado del Personal '.$tipoPersonal.'</h3>';
$htmlBody .= '<strong>Sede:</strong> '.$sede_desc.'<br><br>';
$htmlBody .= '<table border="1" style="border-collapse: collapse;" width="100%">';
$htmlBody .= '<tr>
                  <th>#</th>
                  <th>Personal</th>
                  <th>¿Realiz&oacute;?</th>
                  <th>Cargo</th>
                  <th>Usuario</th>
              </tr>';
                  
foreach ($personalBySede as $row) {
    $htmlBody .= "<tr>
                    <td>".$row['rownum']."</td>
                    <td>".$row['persona']."</td>
                    <td align=\"center\">".$row['realizo']."</td>
                    <td>".$row['cargo']."</td>
                    <td>".$row['usuario']."</td>
                  </tr>";
}
$htmlBody .= '</table>';
$pdfObj->WriteHTML(utf8_encode($htmlBody));
$pdfObj->Output(utf8_encode(strtolower($sede_desc).'_'.$tipo_encu."_listado.pdf"), 'D');
