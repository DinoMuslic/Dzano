<?php

require_once __DIR__ . '/../dao/PaymentDao.php';

class CryptoService {

    private $paymentDao;

    public function __construct() {
        $this->paymentDao = new PaymentDao();
    }

    public function handleSimulatedWebhook($payload) {
        $event = $payload['event'] ?? null;

        if (!$event || $event['type'] !== 'charge:confirmed') {
            return false;
        }

        $metadata = $event['data']['metadata'];
        $pricing = $event['data']['pricing']['local'];

        $this->paymentDao->recordCryptoPayment([
            'sender_id' => $metadata['sender_id'],
            'receiver_id' => $metadata['receiver_id'],
            'gig_id' => $metadata['gig_id'],
            'amount' => $pricing['amount']
        ]);

        // Mark application as paid (same as for PayPal)
        $this->paymentDao->markApplicationAsPaid($metadata['gig_id'], $metadata['receiver_id']);

        return true;
    }
}
