<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class NotificationController
{
    private PHPMailer $mailer;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host       = $_ENV['SMTP_HOST'];
        $this->mailer->SMTPAuth   = filter_var($_ENV['SMTP_AUTH'], FILTER_VALIDATE_BOOLEAN);
        $this->mailer->Username   = $_ENV['SMTP_USER'];
        $this->mailer->Password   = $_ENV['SMTP_PASS'];
        $this->mailer->SMTPSecure = $_ENV['SMTP_SECURE'];
        $this->mailer->Port       = (int)$_ENV['SMTP_PORT'];


        $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
    }

    public function send(array $notifications): void
    {
        foreach ($notifications as $favId => $data) {
            $email = $data['email'] ?? null;
            $links = $data['links'] ?? [];

            if (!$email || empty($links)) {
                continue;
            }

            // filtering links that were already sent
            $links = $this->filterAlreadySent($favId, $links);
            if (empty($links)) {
                continue;
            }

            $subject = "New listings for your favorite #$favId";

            $body = "Hello,\n\nWe found new listings for you:\n\n";
            foreach ($links as $link) {
                $body .= " - " . $link . "\n";
            }
            $body .= "\nBest regards,\nApartment Search Bot";

            try {
                $this->mailer->clearAddresses();
                $this->mailer->addAddress($email);

                $this->mailer->Subject = $subject;
                $this->mailer->Body    = $body;

                $this->mailer->send();

                echo "Email sent to $email<br>";


                $this->storeSentNotifications($favId, $links);
            } catch (Exception $e) {
                echo "Failed to send email to $email: {$this->mailer->ErrorInfo}<br>";
            }
        }
    }

    private function storeSentNotifications(int $favId, array $links): void
    {
        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO sent_notifications (favorite_id, listing_link)
            VALUES (:favorite_id, :listing_link)
        ");

        foreach ($links as $link) {
            $stmt->execute([
                ':favorite_id' => $favId,
                ':listing_link' => $link,
            ]);
        }
    }

    private function filterAlreadySent(int $favId, array $links): array
    {

        $placeholders = implode(',', array_fill(0, count($links), '?'));

        $stmt = $this->pdo->prepare("
        SELECT listing_link 
        FROM sent_notifications 
        WHERE favorite_id = ? 
          AND listing_link IN ($placeholders)
    ");


        $params = array_merge([$favId], $links);

        $stmt->execute($params);
        $alreadySent = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return array_diff($links, $alreadySent);
    }
}
