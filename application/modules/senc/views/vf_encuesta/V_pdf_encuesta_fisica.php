<?php
$pdfObj->WriteHTML(utf8_encode($htmlBody));
$pdfObj->Output(utf8_encode(strtolower($sede_desc)."_encuesta_fisica.pdf"), 'D');