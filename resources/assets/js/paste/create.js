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
        const form = document.getElementById('paste-form');
        const languageSelect = document.getElementById("language");

        const isDark = Alpine.store('theme').dark;
        const initialContent = hiddenInput.value || '';

        const state = EditorState.create({
            doc: initialContent,
            extensions: [
                basicSetup,
                this.languageCompartment.of(this.getLanguageExtension(language)),
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

        languageSelect.addEventListener("change", () => {
            const newLang = languageSelect.value;
            this.view.dispatch({
                effects: this.languageCompartment.reconfigure(this.getLanguageExtension(newLang))
            });
        });

        form.addEventListener('submit', (e) => {
            const content = this.view.state.doc.toString().trim();
            hiddenInput.value = content;
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

});
