<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecaptchaController extends Controller
{
    /**
     * Verify reCAPTCHA token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->token,
            'remoteip' => $request->ip(),
        ]);

        $responseData = $response->json();

        if ($responseData['success'] && $responseData['score'] >= 0.5) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'reCAPTCHA verification failed'], 400);
    }
}