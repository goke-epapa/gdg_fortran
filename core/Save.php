<?php
require_once("FileLoader.php");
require_once("Constants.php");
$query = new HttpQueryString(); 
die($query::toArray());
if(!isset($_REQUEST["action"], $_REQUEST["student_no"], $_REQUEST["lab_no"])){	
	die("");
}
$action = $_REQUEST["action"];
if($action == "get"){	
	$path = FortranCompiler::CODE_DIR . '/' . $student_no . '_' . $lab_no . FORTRAN_FILE_EXTENSION;
	$save = file_get_contents($path);
	die(json_encode($save));
}else if($action == "save"){
	$code = $_REQUEST["code"];
	$query = new HttpQueryString(); 	
	$path = FortranCompiler::CODE_DIR . '/' . $student_no . '_' . $lab_no . FORTRAN_FILE_EXTENSION;
	$get_file = file_put_contents($path, $code);
	die(json_encode($get_file));
}
