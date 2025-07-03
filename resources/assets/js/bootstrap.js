import Alpine from 'alpinejs'
import themeStore from './stores/theme'
import create from './paste/create'
import show from './paste/show'

window.Alpine = Alpine
Alpine.store('theme', themeStore)
Alpine.data('createPaste', create)
Alpine.data('showPaste', show)
Alpine.start()