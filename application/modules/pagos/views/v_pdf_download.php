<?php
$pdfObj->WriteHTML(utf8_encode($cabecera.$html));
$pdfObj->Output($name, 'D');