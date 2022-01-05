<?php

namespace Source\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var PHPMailer
     */
    private $mail;

    /**
     * @var string
     */
    private $message;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        //Server settings
        //$this->mail->SMTPDebug = CONF_EMAIL_OPT_DEBUG;           //Enable verbose debug output
        $this->mail->isSMTP();                                   //Send using SMTP
        $this->mail->SMTPAuth = CONF_EMAIL_OPT_AUTH;             //Enable SMTP authentication
        //$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $this->mail->setLanguage(CONF_EMAIL_OPT_LANG);
        $this->mail->SMTPSecure = CONF_EMAIL_OPT_SECURE;
        $this->mail->CharSet = CONF_EMAIL_OPT_CHARSET;

        //Content
        $this->mail->isHTML(CONF_EMAIL_OPT_HTML);

        //Auth
        $this->mail->Host = CONF_EMAIL_HOST;
        $this->mail->Port = CONF_EMAIL_PORT;//Set the SMTP server to send through
        $this->mail->Username = CONF_EMAIL_USER;                     //SMTP username
        $this->mail->Password = CONF_EMAIL_PASS;                               //SMTP password
    }

    /**
     * Compõe o email, alimenta as propriedades
     *
     * @param string $subject
     * @param string $message
     * @param string $toEmail
     * @param string $toName
     * @return Email
     */
    public function bootstrap(string $subject, string $message, string $toEmail, string $toName): Email
    {
        $this->data = new \stdClass();// Objeto anônimo vazio para ser configurado
        $this->data->subject = $subject;
        $this->data->message = $message;
        $this->data->toEmail = $toEmail;
        $this->data->toName = $toName;

        return $this;
    }

    /**
     * Anexar arquivos
     * @param string $filePath
     * @param string $fileName
     * @return $this
     */
    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    /**
     * Envia o e-mail
     * @param string $fromEmail
     * @param string $fromName
     * @return bool
     */
    public function send(string $fromEmail = CONF_EMAIL_SENDER['address'], string $fromName = CONF_EMAIL_SENDER['name']): bool
    {
        if(empty($this->data)){
            $this->message = 'Erro ao enviar, favor verifique os dados';
            return false;
        }

        if(!is_email($this->data->toEmail)){
            $this->message = 'O e-mail de destinatário não é válido';
            return false;
        }

        if(!is_email($fromEmail)){
            $this->message = 'O e-mail de remetente não é válido';
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->message);
            $this->mail->addAddress($this->data->toEmail, $this->data->toName);
            $this->mail->setFrom($fromEmail, $fromName);

            if(!empty($this->data->attach)){
                foreach ($this->data->attach as $path => $name){
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;

        }catch (Exception $exception){
            $this->message = $exception->errorMessage();
            return false;
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
