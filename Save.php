<?php
require_once("FileLoader.php");
require_once("core/Constants.php");

if(!isset($_REQUEST["action"], $_REQUEST["student_no"], $_REQUEST["lab_no"])){	
	die("");
};

$action = $_REQUEST["action"];
$student_no = str_replace('/', '_', strtolower( $_REQUEST["student_no"]));
//$student_no = str_replace('\\', '_', strtolower($student_no));
$lab_no = $_REQUEST["lab_no"];
if($action == "get"){	
	$path = FortranCompiler::CODE_DIR. '/' . $student_no . '_' . $lab_no . ".f90";
	$save="";
	if(file_exists($path)){
	   $save = file_get_contents($path);
	}
	die($save);
}else if($action == "save"){
	$code = $_REQUEST["code"];
	parse_str($code);	
	$path = FortranCompiler::CODE_DIR. '/' . $student_no . '_' . $lab_no . ".f90";
	$get_file = file_put_contents($path, $code);
	die($code);
};
