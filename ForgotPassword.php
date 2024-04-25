<?php
require_once ('lib/PageTemplate.php');
require_once ('Database.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (!isset($TPL)) {
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Login";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];


    $query = "SELECT * FROM userdetails WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        
        $reset_token = bin2hex(random_bytes(32)); 


        $query = "UPDATE userdetails SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reset_token, $email]);

       
        $mail = new PHPMailer(true); 
        try {

            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = 'live.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 587;
            $phpmailer->Username = 'api';
            $phpmailer->Password = '********1570';


            $mail->setFrom('marcus@supershop.com', 'Marcus Gustavsson');
            $mail->addAddress($email);
            $mail->addReplyTo("noreply@supershop.com", "No-Reply");
            $mail->isHTML(true);
            $mail->Subject = 'Lösenordsåterställning';
            $mail->Body = "Hej,\n\nDu har begärt en återställning av ditt lösenord. Klicka på följande länk för att återställa ditt lösenord:\n\n";


            $mail->send();
            

            echo "<p>En återställningslänk har skickats till din e-postadress. Vänligen kontrollera din inkorg.</p>";
        } catch (Exception $e) {

            echo "E-post kunde inte skickas. Felmeddelande: {$mail->ErrorInfo}";
        }
    } else {

        echo "<p>Ogiltig e-postadress. Vänligen försök igen.</p>";
    } 
}
?>


<p>
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>User<strong>&nbsp;FORGOT PASSWORD</strong></p>
                <form action="ForgotPassword.php" method="post">
                    <input class="input" type="email" name="email" placeholder="Enter Your Email">
                    <br />
                    <br />
                    <button class="newsletter-btn"><i class="fa fa-envelope"></i> Request password reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
</p>