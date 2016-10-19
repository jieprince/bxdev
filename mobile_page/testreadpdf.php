<?php
$file_name = "11.pdf";
header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
header('Content-type: application/pdf');
readfile("1.pdf");
exit(0);
?>