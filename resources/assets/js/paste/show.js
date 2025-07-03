import { EditorView, basicSetup } from "codemirror";
import { javascript } from "@codemirror/lang-javascript";
import { php } from "@codemirror/lang-php";
import { html } from "@codemirror/lang-html";
import { css } from "@codemirror/lang-css";
import { python } from "@codemirror/lang-python";
import { json } from "@codemirror/lang-json";
import { oneDark } from "@codemirror/theme-one-dark";
import { EditorState, Compartment } from "@codemirror/state"

export default () => ({
    view: null,
    themeCompartment: new Compartment(),
    languageCompartment: new Compartment(),
    init() {
        const editor = document.getElementById('editor');
        const hiddenInput = document.getElementById('editor-content');
        const language = document.getElementById("language");

        const isDark = Alpine.store('theme').dark;
        const initialContent = hiddenInput.value || '';

        const state = EditorState.create({
            doc: initialContent,
            extensions: [
                basicSetup,
                this.languageCompartment.of(this.getLanguageExtension(language.value)),
                this.themeCompartment.of(isDark ? oneDark : []),
            ]
        });

        this.view = new EditorView({
            state,
            parent: editor
        });

        this.$watch('$store.theme.dark', (newDark) => {
            this.view.dispatch({
                effects: this.themeCompartment.reconfigure(newDark ? oneDark : [])
            });
        });

    },
    getLanguageExtension(lang) {
        switch (lang) {
            case "php": return php();
            case "js": return javascript();
            case "html": return html();
            case "css": return css();
            case "python": return python();
            case "json": return json();
            default: return javascript();
        }
    },
    copyLink() {
        const input = document.getElementById('paste_link');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(() => {
            const status = document.getElementById('copy-status');
            status.classList.remove('hidden');
            setTimeout(() => status.classList.add('hidden'), 2000);
        });
    },
    copyContent() {
        if (this.view) {
            const content = this.view.state.doc.toString();
            navigator.clipboard.writeText(content)
                .then(() => {
                    const status = document.getElementById('copy-content-status');
                    status.classList.remove('hidden');
                    setTimeout(() => status.classList.add('hidden'), 2000);
                })
                .catch(err => {
                    alert("Copy failed: " + err);
                });
        }
    }
});
