<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

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

<body>
    <div
        class="max-w-[90vw] md:max-w-[600px] lg:max-w-[874px] h-auto min-h-[400px] mx-auto flex flex-col justify-between p-4">
        <div
            class="mx-auto mt-8 border border-[#7d9f99] bg-[#f5f6f7] w-full max-w-[540px] px-6 py-6 rounded-md shadow-sm
           sm:px-8 sm:py-8">
            <h1 class="text-sm sm:text-base font-semibold text-[#061d27] mb-3">Welcome to PasteBinPro!</h1>
            <p class="text-xs sm:text-sm text-[#061d27] mb-5">
                Paste your content below, choose expiration settings, and protect it with a password.
            </p>

            <form action="{{ route('paste.store') }}" method="POST" id="paste-form">
                @csrf
                <select id="language" name="language"
                    class="text-xs sm:text-sm border border-[#ccc] my-2 px-3 py-2 flex-1 rounded-md text-[#666] focus:outline-none focus:ring-2 focus:ring-[#4d6d66]">
                    <option value="">Select Language (optional)</option>
                    <option value="php" {{ old('language') === 'php' ? 'selected' : '' }}>PHP</option>
                    <option value="js" {{ old('language') === 'js' ? 'selected' : '' }}>JavaScript</option>
                    <option value="css" {{ old('language') === 'css' ? 'selected' : '' }}>CSS</option>
                    <option value="html" {{ old('language') === 'html' ? 'selected' : '' }}>HTML</option>
                    <option value="json" {{ old('language') === 'json' ? 'selected' : '' }}>JSON</option>
                    <option value="bash" {{ old('language') === 'bash' ? 'selected' : '' }}>Bash</option>
                    <option value="python" {{ old('language') === 'python' ? 'selected' : '' }}>Python</option>
                </select>

                <textarea id="editor" name="content" rows="10"></textarea>
                @error('content')
                    <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                @enderror

                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    @php
                        use App\Models\Enums\ExpiryType;
                    @endphp

                    <select name="expiry"
                        class="text-xs sm:text-sm border border-[#ccc] px-3 py-2 flex-1 rounded-md text-[#666] focus:outline-none focus:ring-2 focus:ring-[#4d6d66]"
                        required>
                        <option value="{{ ExpiryType::NEVER->value }}"
                            {{ old('expiry') == ExpiryType::NEVER->value ? 'selected' : '' }}>
                            Never expire
                        </option>
                        <option value="{{ ExpiryType::AFTER_VIEW->value }}"
                            {{ old('expiry') == ExpiryType::AFTER_VIEW->value ? 'selected' : '' }}>
                            After one view
                        </option>
                        <option value="{{ ExpiryType::FIVE_MIN->value }}"
                            {{ old('expiry') == ExpiryType::FIVE_MIN->value ? 'selected' : '' }}>
                            5 minutes
                        </option>
                        <option value="{{ ExpiryType::TEN_MIN->value }}"
                            {{ old('expiry') == ExpiryType::TEN_MIN->value ? 'selected' : '' }}>
                            10 minutes
                        </option>
                        <option value="{{ ExpiryType::ONE_HOUR->value }}"
                            {{ old('expiry') == ExpiryType::ONE_HOUR->value ? 'selected' : '' }}>
                            1 hour
                        </option>
                        <option value="{{ ExpiryType::ONE_DAY->value }}"
                            {{ old('expiry') == ExpiryType::ONE_DAY->value ? 'selected' : '' }}>
                            1 day
                        </option>
                    </select>


                    <input type="password" name="password" placeholder="Password protection"
                        class="text-xs sm:text-sm border border-[#ccc] px-3 py-2 flex-1 rounded-md text-[#666] focus:outline-none focus:ring-2 focus:ring-[#4d6d66]"
                        value="{{ old('password') }}" />
                </div>

                @error('expiry')
                    <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                @enderror

                <button type="submit"
                    class="w-full bg-[#4d6d66] hover:bg-[#3b524b] transition-colors duration-300 text-white text-xs sm:text-sm py-3 rounded-md font-semibold">
                    Paste
                </button>
            </form>
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
        document.addEventListener('DOMContentLoaded', () => {
            const textarea = document.getElementById('editor');
            const form = document.getElementById('paste-form');
            const languageSelect = document.getElementById("language");

            const editor = CodeMirror.fromTextArea(textarea, {
                lineNumbers: true,
                theme: "eclipse",
                mode: "javascript", // Default mode
                lineWrapping: true
            });

            editor.setSize(null, "300px");

            languageSelect.addEventListener("change", () => {
                const mode = languageSelect.value || null;
                editor.setOption("mode", mode);
            });

            form.addEventListener('submit', (e) => {
                const content = editor.getValue().trim();

                if (!content) {
                    e.preventDefault();
                    alert('Please enter content.');
                    editor.focus();
                } else {
                    textarea.value = content; // Sync content
                }
            });
        });
    </script>
</body>

</html>
