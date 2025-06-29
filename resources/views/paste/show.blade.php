<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/eclipse.min.css" />

    <!-- Optional themes & addons -->
    <style>
        .CodeMirror {
            border-radius: 0.375rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-2xl bg-white p-6 rounded-xl shadow-md">

        <h1 class="text-2xl font-bold mb-4">Paste</h1>

        {{-- Show error --}}
        @if (isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $error }}
            </div>
        @elseif (isset($requiresPassword))
            {{-- Show password form --}}
            <form method="POST" action="">
                @csrf
                <label class="block font-semibold mb-2">This paste is password-protected:</label>
                @if (isset($error))
                    <div class="text-red-600 mb-2">{{ $error }}</div>
                @endif
                <input type="password" name="password" class="w-full border p-2 rounded mb-4"
                    placeholder="Enter password" required>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit</button>
            </form>
        @elseif (isset($paste))
            {{-- Show paste content --}}
            <div class="mb-6">
                <label class="block font-semibold mb-2 text-gray-700">Content:</label>

                <div class="relative">
                    <textarea id="code-viewer" readonly>{{ $paste->content }}</textarea>

                    <button onclick="copyContent()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                        title="Copy content">
                        <!-- Heroicon: Clipboard -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2H8z" />
                        </svg>
                    </button>
                </div>

                <p id="copy-content-status" class="text-green-600 text-sm mt-2 hidden">Content copied!</p>
            </div>


            {{-- QR Code --}}
            @if (isset($qrCode))
                <div class="mb-4 text-center">
                    <label class="block font-semibold mb-2 text-gray-700">QR Code:</label>
                    <div class="flex justify-center">
                        {!! $qrCode !!}
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <p class="text-sm text-gray-500">Link:</p>
                        <input id="paste-link" value="{{ url($paste->code) }}" readonly
                            class="text-sm border px-2 py-1 rounded w-full" />

                        <button onclick="copyLink()" class="text-blue-600 hover:text-blue-800" title="Copy link">
                            <!-- Heroicon: Clipboard Copy -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2H8z" />
                            </svg>
                        </button>
                    </div>

                    <p id="copy-status" class="text-green-600 text-sm mt-2 hidden">Copied to clipboard!</p>

                </div>
            @endif
        @endif

        <div class="mt-6 text-right">
            <a href="{{ route('paste.create') }}" class="text-blue-600 hover:underline">Create New Paste</a>
        </div>

    </div>
    <!-- Base CodeMirror -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>

    <!-- Required modes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/php/php.min.js"></script>
    <script>
        function copyLink() {
            const input = document.getElementById('paste-link');
            input.select();
            input.setSelectionRange(0, 99999); // For mobile
            navigator.clipboard.writeText(input.value).then(() => {
                const status = document.getElementById('copy-status');
                status.classList.remove('hidden');
                setTimeout(() => status.classList.add('hidden'), 2000);
            });
        }

        function copyContent() {
            const content = document.getElementById('code-viewer').innerText;
            navigator.clipboard.writeText(content).then(() => {
                const status = document.getElementById('copy-content-status');
                status.classList.remove('hidden');
                setTimeout(() => status.classList.add('hidden'), 2000);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const viewer = document.getElementById('code-viewer');
            if (viewer) {
                const editor = CodeMirror.fromTextArea(viewer, {
                    lineNumbers: true,
                    readOnly: true,
                    theme: 'eclipse',
                    mode: '{{ $paste->language ?? 'plaintext' }}',
                    lineWrapping: true
                });
                editor.setSize(null, "auto");
            }
        });
    </script>
</body>

</html>
