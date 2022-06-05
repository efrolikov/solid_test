<?php

namespace models;

/**
 * Class of application
 */
class App
{
    /**
     * DB PDO object
     *
     * @var \PDO|null
     */
    public static $db;

    /**
     * set db connect
     *
     * @param ?\PDO $db
     * @return void
     */
    public static function setDb(?\PDO $db)
    {
        self::$db = $db;
    }

}