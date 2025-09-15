<?php

require_once __DIR__ . '/../services/CryptoService.php';
require_once __DIR__ . '/../../config/config.php';

Flight::set('crypto_service', new CryptoService());

/**
 * @OA\Post(
 *     path="/crypto/create-payment",
 *     summary="Create a new crypto payment (simulated)",
 *     tags={"Payments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"amount", "gig_id", "sender_id", "receiver_id"},
 *             @OA\Property(property="amount", type="number"),
 *             @OA\Property(property="gig_id", type="integer"),
 *             @OA\Property(property="sender_id", type="integer"),
 *             @OA\Property(property="receiver_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Returns hosted crypto payment URL")
 * )
 */
Flight::route('POST /crypto/create-payment', function () {
    $data = Flight::request()->data->getData();

    Flight::json([
        "payment_url" => BASE_URL . "/frontend/#fake-coinbase"
        ]);

});

/**
 * @OA\Post(
 *     path="/crypto/webhook",
 *     summary="Handle crypto payment webhook (simulated)",
 *     tags={"Payments"},
 *     @OA\Response(response=200, description="Webhook received and processed")
 * )
 */
Flight::route('POST /crypto/webhook', function () {
    $payload = json_decode(file_get_contents("php://input"), true);
    $allowSimulation = true;

    if (!$allowSimulation) {
        $sigHeader = $_SERVER["HTTP_X_CC_WEBHOOK_SIGNATURE"] ?? '';
        if ($sigHeader !== 'fake_signature') {
            Flight::halt(403, "Invalid webhook signature");
            return;
        }
    }


    $success = Flight::get('crypto_service')->handleSimulatedWebhook($payload);

    if ($success) {
        Flight::json(['success' => true]);
    } else {
        Flight::halt(400, "Invalid webhook payload");
    }
});
