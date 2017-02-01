<?php


include_once __DIR__.'/../libs/phpmailer/class.phpmailer.php';
include_once __DIR__.'/../libs/phpmailer/class.smtp.php';

/**
 * Description of Mailer
 *
 * @author Puchky Juraj
 */

class Mailer {
    private $to;
    private $subject;
    private $message;
    private $additional_headers = "";
    private $mail;
    function __construct($from,$to,$subject,$message,$charset="utf-8") {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        /*
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-type: text/html; charset=\"$charset\"" . "\r\n";
        $headers .= "To: $to" . "\r\n";
        $headers .= "From: $from" . "\r\n";
        $this->additional_headers = $headers;
        */
        $this->mail = new PHPMailer;
        
  
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Debugoutput = 'html';
        
        $this->mail->Host = 'localhost';
        $this->mail->Port = 25;
        // $mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = false;
        
        $this->mail->setFrom($from,'');
        
        //Set an alternative reply-to address
        $this->mail->addReplyTo($from, '');
        
        //Set who the message is to be sent to
        $this->mail->addAddress($to, '');
        
        //Set the subject line
        $this->mail->Subject = $subject;
        
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($message);
        
        //Replace the plain text body with one created manually
        //$this->mail->AltBody = 'This is a plain-text message body';
   
        
        
    }
    public function send() {
       //return mail($this->to, $this->subject, $this->message, $this->additional_headers);
           //send the message, check for errors
        if (!$this->mail->send()) {
        	return false;
        } else {
        	return true;
        }        
    }
}
