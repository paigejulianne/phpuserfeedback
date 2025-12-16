<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use App\Helpers\Url;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController {
    
    public function showLinkRequestForm() {
        view('auth/forgot_password');
    }

    public function sendResetLinkEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if (!$user) {
                // Return success anyway to prevent enumeration? 
                // For this app, let's be explicit for better UX for now, or generic. 
                // Let's go with generic "If email exists..."
                Url::redirect('password/reset?status=sent');
            }

            $resetModel = new PasswordReset();
            $token = $resetModel->createToken($email);

            if ($token) {
                $this->sendEmail($email, $token);
            }

            Url::redirect('password/reset?status=sent');
        }
    }

    public function showResetForm() {
        $token = $_GET['token'] ?? '';
        view('auth/reset_password', ['token' => $token]);
    }

    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirmation = $_POST['password_confirmation'] ?? '';

            if (empty($token) || empty($email) || empty($password)) {
                 Url::redirect('password/reset/form?token=' . $token . '&error=missing_fields');
            }

            if ($password !== $password_confirmation) {
                 Url::redirect('password/reset/form?token=' . $token . '&error=password_mismatch');
            }

            $resetModel = new PasswordReset();
            $record = $resetModel->findByToken($token);

            if (!$record || $record['email'] !== $email) {
                 Url::redirect('password/reset/form?token=' . $token . '&error=invalid_token');
            }

            // Update Password
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            if ($user) {
                $userModel->updatePassword($user['id'], password_hash($password, PASSWORD_DEFAULT));
            }

            // Delete Token
            $resetModel->deleteByEmail($email);

            Url::redirect('login?status=password_reset');
        }
    }

    private function sendEmail($to, $token) {
        $config = require __DIR__ . '/../Config/config.php';
        $mail = new PHPMailer(true);
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . Url::to('password/reset/form?token=' . $token);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $config['mail_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['mail_user'];
            $mail->Password   = $config['mail_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $config['mail_port'];

            //Recipients
            $mail->setFrom($config['mail_from'], $config['mail_from_name']);
            $mail->addAddress($to);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            $mail->Body    = "Click here to reset your password: <a href='$resetLink'>$resetLink</a>";
            $mail->AltBody = "Click here to reset your password: $resetLink";

            $mail->send();
        } catch (Exception $e) {
            // Log error?
            // error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}
