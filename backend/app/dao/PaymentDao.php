<?php

require_once __DIR__ . "/../Utils.php";

class PaymentDao {

    private $conn;

    public function __construct()
  {
    try {
      
      $servername = Utils::get_env("DB_HOST", "balkanbaza");
    $username = Utils::get_env("DB_USER", "root");
    $password = Utils::get_env("DB_PASSWORD", "?Password123");
    $schema = Utils::get_env("DB_NAME", "balkanbaza");
    $port = Utils::get_env("DB_PORT", "balkanbaza");


        $this->conn = new PDO(
            "mysql:host=$servername;dbname=$schema;port=$port",
            $username,
            $password
        );
        
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
         // echo "Connected successfully";
        } catch (PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
        }

    public function recordPayPalPayment($data) {
        $sql = "INSERT INTO transactions (sender_id, receiver_id, gig_id, amount, status, transaction_date, method)
                VALUES (:sender_id, :receiver_id, :gig_id, :amount, 'completed', NOW(), 'paypal')";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':sender_id' => $data['sender_id'],
            ':receiver_id' => $data['receiver_id'],
            ':gig_id' => $data['gig_id'],
            ':amount' => $data['amount']
        ]);
    }

    public function markApplicationAsPaid($gig_id, $user_id) {
        $sql = "UPDATE applications SET paid = 1 WHERE gig_id = :gig_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':gig_id' => $gig_id,
            ':user_id' => $user_id
        ]);
    }

    public function recordCryptoPayment($data) {
        $sql = "INSERT INTO transactions (sender_id, receiver_id, gig_id, amount, status, transaction_date, method)
                VALUES (:sender_id, :receiver_id, :gig_id, :amount, 'completed', NOW(), 'crypto')";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':sender_id' => $data['sender_id'],
            ':receiver_id' => $data['receiver_id'],
            ':gig_id' => $data['gig_id'],
            ':amount' => $data['amount']
        ]);
    }

}
