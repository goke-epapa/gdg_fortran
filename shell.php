<?php
ini_set('display_errors', true);
error_reporting(E_ALL ^ E_NOTICE);
$temp = "PROGRAM GOKE". "\n".
"IMPLICIT NONE". "\n". 
"WRITE(*,*)'HELLO WORLD'". "\n".
"STOP". "\n".
"END PROGRAM GOKE";
$string = isset($_POST["code"]) ? $_POST["code"] : $temp; 
file_put_contents("test2.f90",$string);
$error  = shell_exec("gfortran -o test2 test2.f90");
echo $error;
if(strlen($error) > 0){
	echo $error;
}
$output = shell_exec('./test2');
echo "<pre>$output</pre>";
?>
