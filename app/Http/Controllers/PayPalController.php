<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    /**
     * create transaction.
     *
     * @return Application|Factory|View
     */
    public function createTransaction()
    {
        return view('transaction');
    }

    /**
     * process transaction.
     *
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function processTransaction(): RedirectResponse
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "1000.00"
                    ]
                ]
            ]
        ]);
        if (isset($response['id'])) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] === 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()
                ->route('createTransaction')
                ->with('error', 'Something went wrong.');
        }

        return redirect()
            ->route('createTransaction')
            ->with('error', $response['message'] ?? 'Something went wrong.');
    }

    /**
     * success transaction.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function successTransaction(Request $request): RedirectResponse
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            return redirect()
                ->route('createTransaction')
                ->with('success', 'Transaction complete.');
        }

        return redirect()
            ->route('createTransaction')
            ->with('error', $response['message'] ?? 'Something went wrong.');
    }

    /**
     * cancel transaction.
     *
     * @return RedirectResponse
     */
    public function cancelTransaction(): RedirectResponse
    {
        return redirect()
            ->route('createTransaction')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
