<?php

namespace App\Http\Controllers;

use App\Models\Paste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Enums\ExpiryType;
use Illuminate\Validation\Rule;

class PasteController extends Controller
{
    public function createForm()
    {
        return view('paste.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'expiry' => ['required', Rule::in(ExpiryType::values())],
            'password' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:50',
        ]);

        $expiryEnum = ExpiryType::from((int) $request->expiry);
        $code = $this->generateUniqueCode();
        $expiresAt = $this->calculateExpiry($expiryEnum);

        $paste = Paste::create([
            'code' => $code,
            'content' => $request->input('content'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : null,
            'expiry_type' => $expiryEnum,
            'expires_at' => $expiresAt,
            'language' => $request->input('language'),
        ]);

        return redirect()->route('paste.show', $code);
    }

    public function show(Request $request, $code)
    {
        $paste = Paste::where('code', $code)->first();

        if (!$paste) {
            return view('paste.show', ['error' => 'Paste not found.']);
        }

        // Time-based expiry
        if ($paste->expires_at && now()->greaterThan($paste->expires_at)) {
            $paste->delete();
            return view('paste.show', ['error' => 'This paste has expired.']);
        }

        // One-time view expiry
        if ($paste->expiry_type === \App\Models\Enums\ExpiryType::AFTER_VIEW) {
            $paste->delete();
            return view('paste.show', ['paste' => $paste, 'qrCode' => $this->generateQRCode($code), 'language' => $paste->language]);
        }

        // Password protection
        if ($paste->password) {
            if ($request->isMethod('post')) {
                if (!Hash::check($request->input('password'), $paste->password)) {
                    return view('paste.show', ['requiresPassword' => true, 'error' => 'Incorrect password.']);
                }
            } else {
                return view('paste.show', ['requiresPassword' => true]);
            }
        }

        $paste->increment('views');

        return view('paste.show', [
            'paste' => $paste,
            'qrCode' => $this->generateQRCode($code),
            'language' => $paste->language,
        ]);
    }

    private function generateUniqueCode($length = 6)
    {
        do {
            $code = Str::random($length);
        } while (Paste::where('code', $code)->exists());

        return $code;
    }

    private function calculateExpiry(ExpiryType $expiry): ?\DateTime
    {
        $interval = $expiry->toInterval();
        return $interval ? now()->add($interval) : null;
    }

    private function generateQRCode($code)
    {
        $url = url("/$code");
        return QrCode::size(200)->generate($url);
    }
}
