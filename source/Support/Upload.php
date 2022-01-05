<?php

namespace Source\Support;

final class Upload
{
    private static $filename = '';
    private static $errors = [];

    /**
     * @return string
     */
    public static function getFilename(): string
    {
        return self::$filename;
    }

    /**
     * @param string $filename
     */
    private static function setFilename(string $filename): void
    {
        self::$filename = str_replace(' ', '-', $filename);
    }

    /**
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }

    /**
     * @param string $error message
     */
    private function setErrors(string $error): void
    {
        array_push(self::$errors, $error);
    }

    public static function uploader($_file, string $directory = null)
    {
        $file_name  = $_file["name"];
        $file_temp  = $_file["tmp_name"];
        $file_type  = $_file["type"];
        $file_size  = $_file["size"];
        $file_error = $_file["error"];

        $upload_dir = isset($directory) ? CONF_APP_UPLOAD_DIR . "/" . $directory : CONF_APP_UPLOAD_DIR;

        // TODO implementar o filtro de tipos.

        try {
            if ( !isset($file_error) || is_array($file_error) )
            {
                self::setErrors("parâmetros inválidos - 1");
            }

            switch ($file_error)
            {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    self::setErrors("nenhum arquivo enviado - 2");
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    self::setErrors("limite máximo escedido - 3");
                default:
                    self::setErrors("erros desconhecidos - 4");
            }

            if ($file_size > 200000000)
            {
                self::setErrors("limite máximo escedido - 5");
            }

            $basic = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-';

            //----------------------------------------------------
            $hash = "";
            $hash_size = 30;
            for($count = 0; $hash_size > $count; $count++)
            {
                $hash .= $basic[rand(0, strlen($basic) - 1)];
            }
            //----------------------------------------------------

            if(count(self::$errors) > 0)
            {
                return false;//json_encode(["response" => $errors]);
                exit();
            }

            self::setFilename("{$hash}-{$file_name}");

            if (
            !move_uploaded_file(
                $file_temp,
                sprintf($upload_dir . '/%s',
                    self::getFilename()
                )
            )
            ) {
                // Falha ao mover o arquivo enviado.
                self::setErrors('Erro ao mover a imagem para o destino');
                return false;//json_encode(["response" => $errors]);
                exit();
            }

            return true;

        } catch (\RuntimeException $e) {
            self::setErrors($e.getMessage());
            return false;//json_encode(["response" => $errors]);
        }
    }

    public static function test(): void
    {
        echo '{
            "name": "Just tests"
        }';
    }
}
