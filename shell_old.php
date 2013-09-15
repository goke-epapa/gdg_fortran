<?php
ini_set('display_errors', true);
error_reporting(E_ALL ^ E_NOTICE);

define("ERROR","Compilation Error");
$temp = "PROGRAM GOKE". "\n".
"IMPLICIT NONE". "\n". 
"WRITE(*,*)'HELLO WORLD'". "\n".
"STOP". "\n".
"END PROGRAM GOKE";

$string = isset($_POST["code"]) ? $_POST["code"] : $temp;
file_put_contents("test2.f90",$string);
file_put_contents("test2.err","");
shell_exec("gfortran -o test2 test2.f90 2> test2.err");
$error = file_get_contents("test2.err");
if(strlen($error) > 0){
	echo $error;
    exit();
}
$output = shell_exec('./test2 1');
echo "<pre>$output</pre>";

