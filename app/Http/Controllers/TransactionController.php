<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    private string $apiUrl = 'https://app.ligdicash.com/pay/v01/redirect/checkout-invoice/create';

    public function store(Request $request): JsonResponse
    {
        // Validation de la requête
        $validated = $request->validate([
            'amount' => 'required|integer|min:100',
        ]);

        try {
            $curl = curl_init();

            $payload = [
                'commande' => [
                    'invoice' => [
                        'items' => [
                            [
                                'name' => 'Payment avec Payme',
                                'description' => '',
                                'quantity' => 1,
                                'unit_price' => $validated['amount'],
                                'total_price' => $validated['amount']
                            ]
                        ],
                        'total_amount' => (string) $validated['amount'],
                        'devise' => 'XOF',
                        'description' => 'Payment avec Payme',
                        'customer' => '',
                        'customer_firstname' => 'Cheik',
                        'customer_lastname' => 'Cissé',
                        'customer_email' => 'contact@cheikcisse.com'
                    ],
                    'store' => [
                        'name' => 'Payme',
                        'website_url' => 'https://payme.com'
                    ],
                    'actions' => [
                        'cancel_url' => 'http://localhost:8000/success',
                        'return_url' => 'http://localhost:8000/cancel',
                        'callback_url' => 'https://c511-41-138-98-104.ngrok-free.app/api/callback'
                    ],
                    'custom_data' => [
                        'transaction_id' => 'ORD-' . time(),
                    ]
                ]
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Apikey: ' . config('services.ligdicash.api_key'),
                    'Authorization: Bearer ' . config('services.ligdicash.auth_token'),
                    'Accept: application/json',
                    'Content-Type: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpCode !== 200) {
                throw new \Exception('Erreur lors de la communication avec LigdiCash');
            }

            curl_close($curl);
            $responseData = json_decode($response);

            // Création de la transaction
            $transaction = Transaction::create([
                'token' => $responseData->token ?? null,
                'amount' => $validated['amount'],
                'payment_link' => $responseData->response_text ?? null,
                'status' => Transaction::STATUS_PENDING,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $transaction,
                'success_url' => 'http://localhost:8000/success',
                'failure_url' => 'http://localhost:8000/cancel',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function payment_callback(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload);

        $token = $event->token;
        $status = $event->status;

        Log::info('callback', ['event' => $event]);

        $transaction = Transaction::where('token', $token)->first(); // Ou avec le transaction_id ou tout autre identifiant unique

        Log::info('transaction', ['transaction' => $transaction]);

        if ($transaction->status === Transaction::STATUS_PENDING && $status === "completed") {
            // Mettre à jour le statut de la transaction dans la base de données
            $transaction->status = Transaction::STATUS_SUCCESS;
            $transaction->details = $event;
            $transaction->save();
            // Livrer le produit ou valider la commande
            //process_order($transaction);
            // Envoyer un email de confirmation
            // ...

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction validée avec succès'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors de la validation de la transaction'
        ], 500);
    }
}
