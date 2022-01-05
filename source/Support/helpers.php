<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

use Source\Database\Connection;


/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return bool
 * Verifica se é uma senha válida de acordo com as configurações definidas
 */
function is_valid_passwd(string $password){
    return strlen($password) >= CONF_PASSWORD_MIN_SIZE && strlen($password) <= CONF_PASSWORD_MAX_SIZE && !is_numeric($password); // TODO colocar um filtro de string
}

/**
 * @param string $password
 * @return bool
 * Verifica se é uma senha. OBS: não usar na criação.
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo']) {
        return true;
    }

    return (mb_strlen($password) >= CONF_PASSWORD_MIN_SIZE && mb_strlen($password) <= CONF_PASSWORD_MAX_SIZE && !is_numeric($password) ? true : false);
}

/**
 * @param string $password
 * @return string
 * Gera a senha.
 */
function passwd(string $password): string
{
    return password_hash($password, CONF_PASSWORD_ALGO, CONF_PASSWORD_OPTIONS);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 * Transforma uma string normal em um slug
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 * Converte uma requisição em um nome de classe
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 * Primeiras letras de cada palavra em maiúscula
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 * Gera resumo de texto por quantidade de palavra
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 * Gera resumo de texto por quantcaracteres
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * ################
 * ###   CORE   ###
 * ################
 */

/**
 * @return PDO
 */
function db(): ?PDO
{
    return Connection::getConnection();
}

function dbErrorConnection ()
{
    if(is_null(Connection::getConnection()))
    {
        //var_dump(Connection::getErrors()->getMessage()); exit();

        echo json_encode([
            'status' => false,
            'message' => 'Database connection error'//Connection::getErrors()->getMessage()
        ]);
        exit();
    }
}

function dbError(): ?PDOException
{
    return Connection::getErrors();
}

/**
 * @return \Source\Core\Message
 */
function message(): \Source\Core\Message
{
    return new \Source\Core\Message();
}

/**
 * @return \Source\Core\Session
 */
function session(): \Source\Core\Session
{
    return new \Source\Core\Session();
}

/**
 * #################
 * ###   MODEL   ###
 * #################
 */

/**
 * @return \Source\Models\User
 */
function user(): \Source\Models\User
{
    return new \Source\Models\User();
}

/**
 * ##################
 * ###   Others   ###
 * ##################
 */
function convertDate (string $date, string $format) {
    return date($format, strtotime($date));
}
