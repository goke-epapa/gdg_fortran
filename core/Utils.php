<?php


class Utils
{

    public static function createDownloadableSourceFile($source)
    {
//        session_start();
        $_SESSION["source"] = $source;
    }

    public static function pushSource($filename, $extension, $source)
    {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: filename=$filename.$extension");
        header("Content-Type: application/octet-stream;");
        header("Content-Transfer-Encoding: binary");
        echo($source);
    }


    public static function getRandomVariableName($length = 10)
    {
        if ($length <= 0 || !is_int($length)) {
            $length = 2;
        }
        $firstVar = range('a', 'z');
        $variableName = $firstVar[rand(0, 25)];
        $variableName = $variableName . Utils::random_string($length - 1);
        return $variableName;
    }


    public static function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function getReadStatements($code)
    {
        preg_match_all("/(read|READ)[ ]*\([ ]*\*[ ]*,\*[ ]*\)[ ]*([A-Z|a-z][A-Z|a-z|0-9]*)([ ]*[,][ ]*([A-Z|a-z][A-Z|a-z|0-9]*))*/", $code, $matches);
        $read_statements = $matches[0];
        return $read_statements;
    }


    public static function replaceReadStatements($code, $tempMemoryVariable)
    {
        $read_statements = Utils::getReadStatements($code);
        $arg_count = 0;
        for ($i = 0, $len = count($read_statements); $i < $len; $i++) {
            $args = Utils::stripArgs($read_statements[$i]);
            $call_args = "";
            for ($j = 0, $argslen = count($args); $j < $argslen; $j++) {
                $call_args .= Utils::createCallArg(++$arg_count, $tempMemoryVariable, $j == $argslen - 1, $args[$j], $tempMemoryVariable);
            }
            $pos = strpos($code, $read_statements[$i]);
            if ($pos !== false) {
                $code = substr_replace($code, $call_args, $pos, strlen($read_statements[$i]));
            }
        }
        echo $code;
        return $code;
    }

    public static function createCallArg($index, $tempMemoryVariable, $is_last, $actualVariable)
    {
        $call_arg = "CALL GETARG($index,$tempMemoryVariable)\nREAD($tempMemoryVariable,*)$actualVariable";
        return $call_arg;
    }


    public static function stripArgs($read_stmt)
    {
        preg_match_all("/(read|READ)[ ]*\([ ]*\*[ ]*,\*[ ]*\)/", $read_stmt, $read_clause);
        $read_stmt = trim(str_replace($read_clause[0][0], "", $read_stmt));
        return Utils::splitCSV($read_stmt);
    }


    public static function splitCSV($data)
    {
        preg_match_all("/[0-9|A-Z|a-z]+/", $data, $result);
        $arr = $result[0];
        return ($arr > 1) ? $arr : $arr[0];
    }

    public static function serveSource($filename)
    {

        if ($file = fopen("../" . $filename, 'r')) {
            $fsize = filesize("../" . $filename);
            $mime_type = Utils::getMimeType("../" . $filename);
            header("Content-type: $mime_type");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
            header("Content-length: $fsize");
            while (!feof($file)) {
                $buffer = fread($file, 2048);
                echo $buffer;
            }
            fclose($file);
            exit;
        } else {
            // do some error handling
        }

    }


    /**
     * @param $filepath
     * @return string
     * gets the mime type of file in the specified path
     */
    static function getMimeType($filepath)
    {
        if (!preg_match('/\.[^\/\\\\]+$/', $filepath)) {
            mime_content_type($filepath);
        }
        switch (strtolower(preg_replace('/^.*\./', '', $filepath))) {
            // START MS Office 2007 Docs
            case 'docx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            case 'docm':
                return 'application/vnd.ms-word.document.macroEnabled.12';
            case 'dotx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
            case 'dotm':
                return 'application/vnd.ms-word.template.macroEnabled.12';
            case 'xlsx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            case 'xlsm':
                return 'application/vnd.ms-excel.sheet.macroEnabled.12';
            case 'xltx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
            case 'xltm':
                return 'application/vnd.ms-excel.template.macroEnabled.12';
            case 'xlsb':
                return 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
            case 'xlam':
                return 'application/vnd.ms-excel.addin.macroEnabled.12';
            case 'pptx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            case 'pptm':
                return 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
            case 'ppsx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
            case 'ppsm':
                return 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12';
            case 'potx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.template';
            case 'potm':
                return 'application/vnd.ms-powerpoint.template.macroEnabled.12';
            case 'ppam':
                return 'application/vnd.ms-powerpoint.addin.macroEnabled.12';
            case 'sldx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.slide';
            case 'sldm':
                return 'application/vnd.ms-powerpoint.slide.macroEnabled.12';
            case 'one':
                return 'application/msonenote';
            case 'onetoc2':
                return 'application/msonenote';
            case 'onetmp':
                return 'application/msonenote';
            case 'onepkg':
                return 'application/msonenote';
            case 'thmx':
                return 'application/vnd.ms-officetheme';
            //END MS Office 2007 Docs

        }
        return mime_content_type($filepath);
    }
}
