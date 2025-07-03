@extends('layout')
@section('content')
    <div class="w-full p-6" x-data="showPaste">
        @if (isset($error))
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
            >
                {{ $error }}
            </div>
        @elseif ($requiresPassword)
            <form method="POST" action="">
                @csrf
                <label class="block font-semibold mb-2 dark:text-white">
                    This paste is password-protected:
                </label>
                <div class="mb-2">
                    <x-forms.input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    />
                </div>
                <x-button>Submit</x-button>
            </form>
        @elseif (isset($paste))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <div class="flex mb-2 justify-between">
                        <span
                            class="font-medium text-sm text-zinc-800 dark:text-white"
                        >
                            Content
                        </span>
                        <span
                            id="copy-content-status"
                            class="text-green-600 text-sm hidden"
                        >
                            Content copied!
                        </span>
                    </div>

                    <div
                        class="relative flex mb-2 h-[60vh] sm:h-[70vh] md:h-[80vh]"
                    >
                        <div
                            id="editor"
                            class="border border-gray-300 rounded-md min-h-[200px] overflow-auto flex-1 w-full bg-white"
                        ></div>
                        <input
                            type="hidden"
                            name="language"
                            id="language"
                            value="{{ $paste->language }}"
                        />
                        <input
                            type="hidden"
                            name="content"
                            id="editor-content"
                            value="{{ $paste->content }}"
                        />
                        <button
                            @click="copyContent()"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                            title="Copy content"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2H8z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                @if (isset($qrCode))
                    <div class="md:col-span-1 mt-7">
                        <div
                            class="flex flex-col justify-between text-center bg-white dark:bg-[#282c34] rounded-lg p-4 h-[60vh] sm:h-[70vh] md:h-[80vh]"
                        >
                            <div>
                                <span class="font-semibold dark:text-white">
                                    QR Code:
                                </span>
                                <div class="flex justify-center mt-2">
                                    {!! $qrCode !!}
                                </div>

                                <div
                                    class="mt-4 flex items-center gap-2 flex-wrap sm:flex-nowrap"
                                >
                                    <p class="text-sm text-gray-500">Link:</p>
                                    <x-forms.input
                                        name="paste_link"
                                        :value="url($paste->code)"
                                        readonly
                                        class="w-full flex-1"
                                    />

                                    <button
                                        @click="copyLink()"
                                        class="text-blue-600 hover:text-blue-800 dark:text-white dark:hover:text-gray-400"
                                        title="Copy link"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6l6 6v8a2 2 0 01-2 2H8z"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <p
                                    id="copy-status"
                                    class="text-green-600 text-sm mt-2 hidden"
                                >
                                    Copied to clipboard!
                                </p>
                            </div>

                            <div class="text-right mt-4">
                                <x-button
                                    tag="a"
                                    href="{{ route('paste.create') }}"
                                >
                                    Create New Paste
                                </x-button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
