<?php
require_once("FileLoader.php");

$temp = "PROGRAM GOKE". "\n".
    "IMPLICIT NONE". "\n".
    "WRITE(*,*)'NO CODE PROVIDED'". "\n".
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