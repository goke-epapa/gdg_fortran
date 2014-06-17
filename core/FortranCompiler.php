<?php
require_once("Constants.php");
require_once("Utils.php");

class FortranCompiler
{

    public $programName;
    public $filename;
    public $errorFilename;
    public $cli_args;
    public $tempMemoryVariable;

    const CODE_DIR = 'lab_session';

    public function __construct($filename = NULL)
    {
        if (!file_exists(FortranCompiler::CODE_DIR)) {
            mkdir(FortranCompiler::CODE_DIR);
        }
        $filename = ($filename == NULL) ? Utils::random_string(16) : str_replace('/', '_', strtolower($filename));
        $filename = FortranCompiler::CODE_DIR . '/' . $filename;
        $this->programName = $filename;
        $this->filename = $filename . FORTRAN_FILE_EXTENSION;
        $this->errorFilename = $filename . ERROR_FILE_EXTENSION;
    }

    public function createCompileCommand()
    {
        return "gfortran -o " . $this->programName . " " . $this->filename . " 2> " . $this->errorFilename;
    }

    public function createFortranFile($source)
    {
        $source = $this->insertTempMemoryVariable($source);
        $source = Utils::replaceReadStatements($source, $this->tempMemoryVariable);
        file_put_contents($this->filename, $source);
    }

    public function createErrorFile()
    {
        file_put_contents($this->errorFilename, "");
    }

    public function isCompiled()
    {
        $errror = file_get_contents($this->errorFilename);
        return strlen($errror) == 0;
    }

    public function compileError()
    {
        $errror = file_get_contents($this->errorFilename);
        return $errror;
    }

    public function compile($command)
    {
        shell_exec($command);
    }

    public function executeProgram($cli_args)
    {
        $code_data = file_get_contents($this->programName . ".f90");
        $code = str_replace("GETARG", "GET_COMMAND_ARGUMENT", $code_data);
        file_put_contents($this->programName . ".f90", $code);
        return shell_exec("./" . $this->programName . " " . $cli_args);
        //return shell_exec("echo '".$cli_args."'| ./".$this->programName );
    }

    public function insertTempMemoryVariable($source)
    {
        $this->tempMemoryVariable = Utils::getRandomVariableName();
        $implicitNoneStatement = "implicit none";
        $declarationString = "CHARACTER(LEN = 72)::" . $this->tempMemoryVariable;
        $source = str_ireplace($implicitNoneStatement, $implicitNoneStatement . "\n" . $declarationString, $source);
        return $source;
    }

}
