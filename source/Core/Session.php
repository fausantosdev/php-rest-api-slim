<?php

namespace Source\Core;
// Classe stateles, pois sua propriedade é a própria sessão a qual se encarrega de manipular.

class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if(!session_id())// Se não existir um id de sessão(não existir sessão ativa), crie uma sessão.
        {
            session_save_path(CONF_APP_SESSION_PATH);// Define onde os arquivos de sessão serão salvos.(Só em ambiente de sesenvolvimento)
            session_start();// Cria a sessão.
        }
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if(!empty($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->has($name);
    }

    //---------------------------------------------------------------------------------------------------

    /**
     * Para obter a sessão, ou null, caso não tenha nenhuma.
     * @return object|null
     */
    public function all(): ?object
    {
        // Converte em um objeto.
        return (object) $_SESSION;
    }

    /**
     * Cria um índice na sessão.
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value): Session
    {
        /*
          Se recebe array como valor, converte para objeto, para que a sessão
          seja manipulada seguindo os fundamentos de OO.
        */
        $_SESSION[$key] = (is_array($value) ? (object) $value : $value);
        return $this;
    }

    /**
     * Remove um indice da sessão.
     * @param string $key
     * @return $this
     */
    public function unset(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * Verifica se um determinado índice existe na sessão.
     * @param string $key
     * @return boll
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     *  ** SEGURANÇA **
     * Regenera o id da sessão e exclui o arquivo anterior, sem alterar os dados da mesma.
     * @return $this
     */
    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }

    /**
     * Destroi a sessão(logoff).
     * @return $this
     */
    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }

    //---------------------------------------------------------------------------------------------------

    /**
     * @return Message|null
     * Cria a mensagem flash.
     */
    public function flash(): ?Message
    {
        if($this->has('flash'))
        {
            $flash = $this->flash;// Atribui o objeto flash.
            $this->unset('flash');// Limpa(ao atualizar o navegador ela já não existe mais)
            return $flash;
        }

        return null;// $flash é objeto da Message, caso ele não exista, retorna null.
    }

    /**
     * CSRF Token
     */
    public function csrf(): void
    {
        //session()->csrf_token
        $_SESSION['csrf_token'] = base64_encode(random_bytes(20));
    }
}
