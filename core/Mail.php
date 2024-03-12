<?php

namespace Mvc\Core;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require ROOT_PATH . "/vendor/PHPMailer/PHPMailer/src/Exception.php";
require ROOT_PATH . "/vendor/PHPMailer/PHPMailer/src/PHPMailer.php";
require ROOT_PATH . "/vendor/PHPMailer/PHPMailer/src/SMTP.php";

class Mail{

    protected $from;
    protected $to;
    protected $subject;
    protected $message;
    protected $mail_obj;
    protected $template_data = array();

    public function getMailObj()
    {
        if(!$this->mail_obj){
            $this->mail_obj = new PHPMailer(true);
        }
        return $this->mail_obj;
    }

    public function send($queue_id)
    {
        $mail = $this->getMailObj();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        try {
            $mail->isSMTP(); 
            $mail->Host = HOST; 
            $mail->SMTPAuth = true; 
            $mail->Username = USERNAME; 
            $mail->Password = PASSWORD;
            $mail->SMTPSecure = SMTP; 
            $mail->Port = 587; 
            $mail->setFrom(FROM_EMAIL, FROM_NAME);
            $mail->addAddress($this->to); 
            $mail->isHTML(true); 
            $mail->Subject = $this->subject;
            $mail->Body = $this->message;
            $mail->send();
            return $queue_id;
        } catch (Exception $e) {
            error_log('Error sending email: ', $mail->ErrorInfo);
            return false;
        }
    }

    public function sendQueue()
    {
        DB::table("email_queue")->insert([
            "to_email" => $this->to,
            "subject" => $this->subject,
            "message" => $this->message
        ]);
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function template(string $path,array $data)
    {
        extract($data);
        ob_start();
        include $path;
        $templateContent = ob_get_clean();
        $this->message = $templateContent;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }
}