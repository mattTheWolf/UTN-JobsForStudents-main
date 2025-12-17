<?php 
    namespace Controllers;

    require 'vendor/autoload.php';

    use Helpers\MessageHelper;
    use PHPMailer\PHPMailer\PHPMailer;

    class EmailController{
        private $email;

        public function __construct(){
            $this->mail = new PHPMailer;

            $this->mail->isSMTP();                           
            $this->mail->Host = 'smtp.gmail.com';            
            $this->mail->SMTPAuth = true;                    
            $this->mail->Username = 'utnjobsforstudents@gmail.com';
            $this->mail->Password = 'jobsforstudents';
            $this->mail->SMTPSecure = 'tls';          
            $this->mail->Port = 587;                  
            $this->mail->setFrom('utnjobsforstudents@gmail.com', 'UTN - Jobs for Students');
            $this->mail->addReplyTo('utnjobsforstudents@gmail.com', 'UTN - Jobs for Students');
            $this->mail->isHTML(true); 
        }

        public function sendEmail($toemail, $subject, $message){
            $bodyContent = $message;
            $bodyContent = 'Dear UTN student:';
            $bodyContent .='<p>'.$message.'</p>';
            $bodyContent .= 'UTN Jobs for Students 2021 - '.date('Y');

            $this->mail->Body = $bodyContent;
            $this->mail->addAddress($toemail);   
            $this->mail->Subject = $subject;

            if(!$this->mail->send())
                $message = MessageHelper::EMAILNOTSENT.$this->mail->ErrorInfo;
            else
                $message = MessageHelper::EMAILSENT;
        }
    }
?>