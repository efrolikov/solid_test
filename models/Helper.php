<?php

namespace models;

/**
 * Provide useful functions
 */
class Helper
{
    /**
     * Logger
     *
     * @param mixed $data data to log
     * @return false|void
     */
    public static function log($data)
    {
        if (is_array($data) || is_object($data)) $data = print_r($data, true);
        $date = date('d.m.Y H:i:s',time());

        $bt = debug_backtrace();
        $place = ''; $line = '';
        if (!empty($bt)) {
            $place = $bt[0]['file'] ?? '';
            $line = $bt[0]['line'] ?? '';
        }

        $data = $date.' | '.$place.'('.$line.') | '.$data."\r\n";
        $filename =  ROOT_DIR.'log'.(strtolower(PHP_OS)=='linux'? '/' : '\\').'log';

        if (!is_writable($filename)) return false;

        if (!$handle = fopen($filename, 'a')) return false;
        if (fwrite($handle, $data) === FALSE) return false;
        fclose($handle);
    }
}