<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        try {
            // Cấu hình Server SMTP
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com'; // Máy chủ SMTP của Google
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'vmanhsaber119@gmail.com';
            $this->mail->Password = 'ucsl lxkx hjcx amum'; // TODO: Thay thế bằng App Password thực tế
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Tùy chọn ENCRYPTION_STARTTLS ở cổng 587 hoặc ENCRYPTION_SMTPS ở cổng 465
            $this->mail->Port = 465;
            $this->mail->CharSet = 'UTF-8';

            // Người gửi mặc định
            $this->mail->setFrom('vmanhsaber119@gmail.com', 'Book4u Store');
        } catch (Exception $e) {
            error_log("SMTP Error: " . $this->mail->ErrorInfo);
        }
    }

    /**
     * Gửi email
     * @param string $to Email người nhận
     * @param string $subject Tiêu đề email
     * @param string $body Nội dung (hỗ trợ HTML)
     * @return bool Trả về true nếu gửi thành công
     */
    public function sendMail($to, $subject, $body)
    {
        try {
            $this->mail->addAddress($to);

            // Nội dung email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Lỗi gửi email: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
