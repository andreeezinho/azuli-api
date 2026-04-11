<?php

namespace App\Infra\Services\Email;

use App\Infra\Services\Email\Templates\TemplateRenderer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Infra\Services\Log\LogService;

class EmailService {

    private $mail;
    private $templateRender;

    public function __construct(TemplateRenderer $templateRender){
        $this->templateRender = $templateRender;
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = true;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Username = $_ENV['EMAIL'];
        $this->mail->Password = $_ENV['EMAIL_CODE'];
        $this->mail->setFrom($this->mail->Username, $_ENV['SITE_NAME']);
    }

    public function sendPasswordReset(string $email, string $user, int $code, string $expirationTime) : bool {
        $subject = 'Redefinição de Senha';
        
        $data = [
            'user' => $user,
            'code' => $code,
            'expiration_time' => date('d/m/Y H:i:s', strtotime($expirationTime))
        ];

        $body = $this->templateRender->renderResetPasswordTemplate($data);

        return $this->send($email, $subject, $body, $user);
    }

    public function send(string $to, string $subject, string $body, ?string $toName) : bool {
        try{

            $this->mail->addAddress($to, $toName ?? $to);

            $this->mail->Subject = $subject;

            $this->mail->isHTML(true);

            $this->mail->Body = $body;

            $this->mail->send();

            return true;

        }catch(Exception $e){
            LogService::logError($e->getMessage());
            return false;
        }
    }

}