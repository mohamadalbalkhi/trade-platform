<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class GoogleAuthController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $this->generateBase32Secret();
            $user->save();
        }

        $appName = config('app.name', 'DawnEX');
        $issuer = urlencode($appName);
        $account = urlencode($user->email);
        $secret = $user->google2fa_secret;

        $otpAuthUrl = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($otpAuthUrl);

        return view('google-auth', [
            'user' => $user,
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $secret,
        ]);
    }

    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();
        $code = trim((string) $request->code);

        if (!$user->google2fa_secret) {
            return redirect('/google-auth')->with('error', 'Google Authenticator secret not found.');
        }

        if (!preg_match('/^\d{6}$/', $code)) {
            return redirect('/google-auth')->with('error', 'Invalid Google Authenticator code format.');
        }

        if (!$this->verifyTotpCode($user->google2fa_secret, $code)) {
            return redirect('/google-auth')->with('error', 'Invalid Google Authenticator code.');
        }

        $user->google2fa_enabled = true;
        $user->google2fa_confirmed_at = Carbon::now();
        $user->save();

        return redirect('/google-auth')->with('success', 'Google Authenticator enabled successfully.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();
        $code = trim((string) $request->code);

        if (!$user->google2fa_enabled || !$user->google2fa_secret) {
            return redirect('/google-auth')->with('error', 'Google Authenticator is not enabled.');
        }

        if (!preg_match('/^\d{6}$/', $code)) {
            return redirect('/google-auth')->with('error', 'Invalid Google Authenticator code format.');
        }

        if (!$this->verifyTotpCode($user->google2fa_secret, $code)) {
            return redirect('/google-auth')->with('error', 'Invalid Google Authenticator code.');
        }

        $user->google2fa_enabled = false;
        $user->google2fa_confirmed_at = null;
        $user->google2fa_secret = null;
        $user->save();

        return redirect('/google-auth')->with('success', 'Google Authenticator disabled successfully.');
    }

    public function verifyTotpCode(string $secret, string $code, int $window = 1): bool
    {
        $secret = trim($secret);
        $code = trim($code);

        if ($secret === '' || !preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $timestamp = (int) floor(time() / 30);

        for ($i = -$window; $i <= $window; $i++) {
            $calculated = $this->getTotpCode($secret, $timestamp + $i);

            if (hash_equals($calculated, $code)) {
                return true;
            }
        }

        return false;
    }

    private function getTotpCode(string $secret, int $timestamp): string
    {
        $secretKey = $this->base32Decode($secret);

        if ($secretKey === '') {
            return '000000';
        }

        $time = pack('N*', 0) . pack('N*', $timestamp);
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;

        $truncatedHash = substr($hash, $offset, 4);
        $value = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;
        $modulo = $value % 1000000;

        return str_pad((string) $modulo, 6, '0', STR_PAD_LEFT);
    }

    private function generateBase32Secret(int $length = 32): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, $maxIndex)];
        }

        return $secret;
    }

    private function base32Decode(string $secret): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = strtoupper(trim($secret));

        $binaryString = '';
        $secretLength = strlen($secret);

        for ($i = 0; $i < $secretLength; $i++) {
            $currentChar = $secret[$i];
            $position = strpos($alphabet, $currentChar);

            if ($position === false) {
                continue;
            }

            $binaryString .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
        }

        $decoded = '';

        foreach (str_split($binaryString, 8) as $byte) {
            if (strlen($byte) === 8) {
                $decoded .= chr(bindec($byte));
            }
        }

        return $decoded;
    }
}