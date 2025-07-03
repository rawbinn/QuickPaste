@extends('layout')
@php
    $languages = [
        '' => 'Select Language (optional)',
        'php' => 'PHP',
        'js' => 'JavaScript',
        'css' => 'CSS',
        'html' => 'HTML',
        'json' => 'JSON',
        'bash' => 'Bash',
        'python' => 'Python',
    ];
@endphp

@section('content')
    <div
        class="h-auto min-h-[400px] mx-auto flex flex-col justify-between"
        x-data="createPaste"
    >
        <div class="py-8">
            <form
                action="{{ route('paste.store') }}"
                method="POST"
                id="paste-form"
            >
                @csrf
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5"
                >
                    <div class="mb-3 sm:mb-0">
                        <h1
                            class="text-sm sm:text-base font-semibold text-[#061d27] dark:text-white mb-3"
                        >
                            Welcome to
                            {{ config('app.name') }}
                        </h1>
                        <p
                            class="text-xs sm:text-sm text-[#061d27] dark:text-white"
                        >
                            Paste your content below, choose expiration
                            settings, and protect it with a password.
                        </p>
                    </div>
                    <div>
                        <x-forms.select
                            name="language"
                            :options="$languages"
                        />
                    </div>
                </div>
                <div class="h-[60vh] relative mb-2 flex">
                    <div
                        id="editor"
                        class="border border-gray-300 rounded-md min-h-[200px] overflow-auto flex-[1_1_100%] w-full bg-white"
                    ></div>

                    <input
                        type="hidden"
                        name="content"
                        id="editor-content"
                        value="{{ old('content') }}"
                    />
                </div>

                @error('content')
                    <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
                @enderror

                <div
                    class="flex flex-col sm:flex-row sm:flex-wrap md:flex-nowrap gap-3 mt-4 mb-2"
                >
                    <div class="w-full sm:flex-1">
                        <x-forms.select
                            :options="$expiryTypes"
                            name="expiry"
                            selectedValue="{{ old('expiry') }}"
                            required="true"
                        />
                    </div>

                    <div class="w-full sm:flex-1">
                        <x-forms.input
                            type="password"
                            name="password"
                            placeholder="Password protection"
                            value="{{ old('password') }}"
                            autocomplete="off"
                        />
                    </div>

                    <div class="w-full md:flex-1">
                        <x-button is-full-width>Paste</x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
