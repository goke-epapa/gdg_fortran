<?php
require_once("FileLoader.php");

$temp = "PROGRAM GOKE". "\n".
    "IMPLICIT NONE". "\n".
    "CHARACTER(len=32)::A". "\n".
    "READ(*,*)A". "\n".
    "WRITE(*,*)'NO CODE PROVIDED', A". "\n".
    "STOP". "\n".
    "END PROGRAM GOKE";

$string = isset($_POST["code"]) ? $_POST["code"] : $temp;
$fortranCompiler = new FortranCompiler();
$fortranCompiler->createErrorFile();
$fortranCompiler->createFortranFile($string);
$fortranCompiler->compile($fortranCompiler->createCompileCommand());
if($fortranCompiler->isCompiled()){
    echo "<pre>".$fortranCompiler->executeProgram()."</pre>";
}else{
    echo "<pre>".$fortranCompiler->compileError()."</>";
}