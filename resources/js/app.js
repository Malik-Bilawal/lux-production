import './bootstrap';
import './user/components/navbar.js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('load', () => {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'none';
    }
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(() => console.log('Service Worker Registered'))
        .catch(err => console.log('SW registration failed:', err));
}

let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    const btn = document.createElement('button');
    btn.innerText = "Add to Home Screen";

    btn.className = "fixed bottom-5 right-5 bg-brand-black text-brand-white hover:bg-brand-gold hover:text-brand-black py-3 px-5 rounded-lg shadow-lg z-[1000] transition-colors duration-300";

    document.body.appendChild(btn);

    btn.addEventListener('click', async () => {
        btn.style.display = 'none';
        deferredPrompt.prompt();
        const result = await deferredPrompt.userChoice;
        console.log('User choice:', result.outcome);
        deferredPrompt = null;
    });
});

window.addEventListener('appinstalled', () => {
    console.log('App successfully installed!');
});

const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
const isInStandalone = window.navigator.standalone === true;
if (isIos && !isInStandalone) {

    console.log('User is on iOS and not in standalone mode. PWA prompt can be shown.');
}

