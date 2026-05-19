<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function enviarCodigoCorreo($correo, $codigo) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'adminxd67@gmail.com';
        $mail->Password = 'lhvy nptd chje zssh';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('adminxd67@gmail.com', 'Sistema de Ventas');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Codigo de verificacion';
        $mail->Body = "
            <h3>Codigo de verificacion</h3>
            <p>Tu codigo para verificar tu cuenta es:</p>
            <h2>$codigo</h2>
        ";
        $mail->AltBody = 'Tu codigo de verificacion es: ' . $codigo;

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Error al enviar correo: " . $mail->ErrorInfo;
        exit();
    }
}
?>
