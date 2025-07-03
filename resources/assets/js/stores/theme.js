export default {
    dark: localStorage.theme === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    init() {
        document.documentElement.classList.toggle('dark', this.dark);
    },
    toggle() {
        this.dark = !this.dark;
        localStorage.theme = this.dark ? 'dark' : 'light';
        document.documentElement.classList.toggle('dark', this.dark);
    }
};
