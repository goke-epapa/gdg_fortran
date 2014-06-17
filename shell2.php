<?php
require_once("FileLoader.php");

$temp = "PROGRAM GOKE". "\n".
    "IMPLICIT NONE". "\n".
    "WRITE(*,*)'Type Your Program In The Text Editor :)'". "\n".
    "STOP". "\n".
    "END PROGRAM GOKE";
$check = false;
if(isset($_REQUEST["code"])){
    $check = strlen($_REQUEST["code"]) > 0;
}
$cli_args = "";
if(isset($_REQUEST["stdin"])){
    $args = $_REQUEST["stdin"];
    $args_array = explode(",",$args);
    $cli_args = implode(" ",$args_array);
}
$string = $check ? $_REQUEST["code"] : $temp;
$fortranCompiler = new FortranCompiler();
$fortranCompiler->createErrorFile();
$fortranCompiler->createFortranFile($string);
$fortranCompiler->compile($fortranCompiler->createCompileCommand());
$result = array();
if($fortranCompiler->isCompiled() && $check){
    $result["status"] = true;
    $result["result"] = $fortranCompiler->executeProgram($cli_args);
//    $result["link"] =  $fortranCompiler->filename;
    $result["link"] =  "./core/download.php?filename=".$fortranCompiler->filename;
    echo json_encode($result);
}else{
    $error_msg = !$check ? $fortranCompiler->executeProgram($cli_args) : $fortranCompiler->compileError();
    $result = array("status" => false, "error_message" => $error_msg);
    echo json_encode($result);
}
