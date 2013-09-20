<?php
require_once("Constants.php");
require_once("Utils.php");

class FortranCompiler{

    public $programName;
    public $filename;
    public $errorFilename;

    public function __construct(){
        $randomFileName = Utils::random_string(16);
        $this->programName = $randomFileName;
        $this->filename = $randomFileName . FORTRAN_FILE_EXTENSION;
        $this->errorFilename = $randomFileName . ERROR_FILE_EXTENSION;
    }

    public function createCompileCommand(){
        return "gfortran -o ". $this->programName ." ". $this->filename." 2> " . $this->errorFilename;
    }

    public function createFortranFile($string){
        $string = Utils::replaceReadStatements($string);
        file_put_contents($this->filename,$string);
    }

    public function createErrorFile(){
        file_put_contents($this->errorFilename,"");
    }

    public function isCompiled(){
        $errror = file_get_contents($this->errorFilename);
        return strlen($errror) == 0;
    }

    public function compileError(){
        $errror = file_get_contents($this->errorFilename);
        return $errror;
    }

    public function compile($command){
        shell_exec($command);
    }

    public function executeProgram($cli_args){
        return shell_exec("./".$this->programName ." ".$cli_args);
    }

}