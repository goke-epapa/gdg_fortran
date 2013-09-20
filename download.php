<?php
require_once "Constants.php";
$filename = $_GET["filename"];
$source = $_GET["source"];
Download::createDownloadableSourceFile($filename, FORTRAN_FILE_EXTENSION, $source);


