<?php


class Utils{

    public static function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function getReadStatements($code)
    {
        preg_match_all("/read|READ[ ]*\([ ]*\*[ ]*,\*[ ]*\)[ ]*([A-Z|a-z][A-Z|a-z|0-9]*)([ ]*[,][ ]*([A-Z|a-z][A-Z|a-z|0-9]*))*/", $code, $matches);
        $read_statements = $matches[0];
        return $read_statements;
    }


    public static function replaceReadStatements($code)
    {
        $read_statements = Utils::getReadStatements($code);
        $arg_count = 0;
        for ($i = 0, $len = count($read_statements); $i < $len; $i++) {
            $args = Utils::stripArgs($read_statements[$i]);
            $call_args = "";
            for ($j = 0, $argslen = count($args); $j < $argslen; $j++) {
                $call_args .= Utils::createCallArg(++$arg_count, $args[$j], $j == $argslen - 1);
            }
            $pos = strpos($code,$read_statements[$i]);
            if ($pos !== false) {
                $code = substr_replace($code,$call_args,$pos,strlen($read_statements[$i]));
            }
        }
        return $code;
    }

    public static function createCallArg($index, $arg, $is_last)
    {
        $call_arg = ($is_last)?"CALL GETARG($index,$arg)":"CALL GETARG($index,$arg)\n";
        return $call_arg;
    }


    public static function stripArgs($read_stmt)
    {
        preg_match_all("/read|READ[ ]*\([ ]*\*[ ]*,\*[ ]*\)/", $read_stmt, $read_clause);
        $read_stmt = trim(str_replace($read_clause[0][0], "", $read_stmt));
        return Utils::splitCSV($read_stmt);
    }


    public static function splitCSV($data)
    {
        preg_match_all("/[0-9|A-Z|a-z]+/", $data, $result);
        $arr = $result[0];
        return ($arr > 1) ? $arr : $arr[0];
    }
}
