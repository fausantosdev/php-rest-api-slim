<?php

namespace Source\Database;

use PDO;
use PDOException;

class Connection
{
    // Membros somente da classe para que nunca tenha um novo objeto instanciando a conexão
    private const DBDRIVER  = CONF_DATABASE_DRIVER;
    private const DBHOST    = CONF_DATABASE_HOST;
    private const DBNAME    = CONF_DATABASE_NAME;
    private const DBUSER    = CONF_DATABASE_USER;
    private const DBPASS    = CONF_DATABASE_PASS;
    private const DBOPTIONS = CONF_DATABASE_OPTIONS;

    /**
     * Armament o objeto PDO
     * @var PDO|null
     */
    private static ?PDO $instance = null;

    /**
     * @var PDOException|null
     */
    private static ?PDOException $error = null;

    /**
     * @return PDO|null
     */
    public final static function getConnection(): ?PDO
    {
        // Garante que tenha apenas um objeto, uma conexão por usuário.
        if(empty(self::$instance))
        {
            try{
                 self::$instance = new PDO(
                    self::DBDRIVER . ":host=" . self::DBHOST . ";dbname=" . self::DBNAME,
                    self::DBUSER,
                    self::DBPASS,
                    self::DBOPTIONS
                );
            }catch (PDOException $exception){
                self::$error = $exception;
                return null;
            }
        }

        return self::$instance;
    }

    /**
     * @return PDOException|null
     */
    public final static function getErrors(): ?PDOException
    {
        return self::$error;
    }

    // Para que não seja construidos novos objetos nem clones.
    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
