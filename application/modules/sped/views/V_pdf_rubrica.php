<?php
$pdfObj->WriteHTML(utf8_encode($cabecera.$htmlBody));
$pdfObj->Output("evaluacion.pdf", 'D');