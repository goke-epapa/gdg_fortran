<?php
require_once("FileLoader.php");
require_once("core/Constants.php");

$temp = "PROGRAM GOKE" . "\n" .
    "IMPLICIT NONE" . "\n" .
    "WRITE(*,*)'Type Your Program In The Text Editor :)'" . "\n" .
    "STOP" . "\n" .
    "END PROGRAM GOKE";
$check = false;
if (!isset($_POST["lab_no"], $_POST["matric"])) {
    $error_msg = "Lab Number or Matric Number not specified.";
    $result = array("status" => false, "error_message" => $error_msg);
    die(json_encode($result));
}
$lab_no = $_POST["lab_no"];
$student_no = $_POST["matric"];
if (isset($_POST["code"])) {
    $check = strlen($_POST["code"]) > 0;
}


$cli_args = "";
if (isset($_POST["stdin"])) {
    $args = $_POST["stdin"];
    $args_array = explode(",", $args);
    $cli_args = implode(" ", $args_array);
}
$string = $check ? $_POST["code"] : $temp;
$fortranCompiler = new FortranCompiler($student_no . '_' . $lab_no);
$fortranCompiler->cli_args = $cli_args;
$fortranCompiler->createErrorFile();
$fortranCompiler->createFortranFile($string);
$fortranCompiler->compile($fortranCompiler->createCompileCommand());
$result = array();

if ($fortranCompiler->isCompiled() && $check) {
    $result["status"] = true;
    $result["result"] = $fortranCompiler->executeProgram($cli_args);
    $result["result"] = str_replace("\n", "<br/>", $result["result"]);
//    $result["link"] =  $fortranCompiler->filename;
    $result["link"] = "./core/download.php?filename=" . $fortranCompiler->filename;
    echo json_encode($result);
} else {
    //$error_msg = $fortranCompiler->executeProgram($cli_args);
    $error_msg = !$check ? $fortranCompiler->executeProgram($cli_args) : $fortranCompiler->compileError();
    $result = array("status" => false, "error_message" => $error_msg);
    echo json_encode($result);
}
