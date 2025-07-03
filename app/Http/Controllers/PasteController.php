<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PasteRequest;
use App\Models\Enums\ExpiryType;
use App\Models\Paste;
use App\Services\PasteService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class PasteController extends Controller
{
    public function __construct(
        private PasteService $pasteService
    ) {}

    public function createForm(): View
    {
        return view('paste.create', [
            'expiryTypes' => ExpiryType::labels(),
        ]);
    }

    public function store(PasteRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $paste = $this->pasteService->store($validated);

        return redirect()->route('paste.show', $paste->code);
    }

    public function show(Request $request, Paste $code): View|RedirectResponse
    {
        $key = 'paste-password:' . $code->id . ':' . $request->ip();

        try {
            // If password is required
            if ($code->password) {

                // If not POST, show password form
                if (! $request->isMethod('post')) {
                    return view('paste.show', ['requiresPassword' => true]);
                }

                // Check rate limit before validating password
                if (RateLimiter::tooManyAttempts($key, 5)) {
                    $seconds = RateLimiter::availableIn($key);

                    return redirect()
                        ->route('paste.show', $code->code)
                        ->withErrors([
                            'password' => "Too many attempts. Try again in {$seconds} seconds.",
                        ]);
                }

                // Password check
                if (! Hash::check($request->input('password'), $code->password)) {
                    RateLimiter::hit($key, 300); // lockout for 5 mins (300 seconds)

                    return redirect()
                        ->route('paste.show', $code->code)
                        ->withErrors(['password' => 'Invalid password provided.']);
                }

                // Password correct → clear attempts
                RateLimiter::clear($key);
            }

            $data = $this->pasteService->handleView($code);

            return view('paste.show', [
                'paste' => $code,
                'qrCode' => $data['qrCode'] ?? null,
                'language' => $code->language,
                'requiresPassword' => false,
            ]);
        } catch (Exception $e) {
            return view('paste.show', ['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
