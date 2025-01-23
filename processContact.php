<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $help = htmlspecialchars($_POST['help']);
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);

    if (!$email) {
        echo "Invalid email format.";
        exit;
    }

    //PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Config del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pepeveras845@gmail.com'; //dirección de correo
        $mail->Password = 'wbxr lgnu uxku firs'; //contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom($email, $fullname);
        $mail->addAddress('contact@calirisk.com'); // Cambia al correo real de contacto de Calirisk
        $mail->isHTML(true);
        $mail->Subject = "Contact Request from $fullname";
        $mail->Body = "
            <h2>Contact Request</h2>
            <p><strong>Service Requested:</strong> $help</p>
            <p><strong>Name:</strong> $fullname</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
        ";

        $mail->send();
        echo "Thank you!, we will contact you as soon as possible ";
    } catch (Exception $e) {
        echo "There was an error sending your message. Error: {$mail->ErrorInfo}";
    }
}
//script para dirijir a Home despues de 3 segundos
    echo '<script> 
            setTimeout(function(){
                window.location.href = "index.html";
            }, 3000);
          </script>';
?>