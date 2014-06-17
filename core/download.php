<?php
/**
 * Created by JetBrains PhpStorm.
 * User: epapa
 * Date: 9/20/13
 * Time: 12:02 PM
 * To change this template use File | Settings | File Templates.
 */
require "./Utils.php";
if(isset($_REQUEST["filename"])){
    Utils::serveSource($_REQUEST["filename"]);
}