{{-- resources/views/user/customer-support//legal-script.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    const navLink = document.querySelector(`a[href="#${id}"]`);
                    if (navLink) {
                        document.querySelectorAll('nav a').forEach(a => a.classList.remove('text-theme-primary'));
                        navLink.classList.add('text-theme-primary');
                    }
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('section[id^="section-"]').forEach(section => {
            observer.observe(section);
        });
    });
</script>