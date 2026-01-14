import '@css/pages/about.css';

const accordionItems = document.querySelectorAll('.accordion-item');
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        header.addEventListener('click', () => {
            accordionItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            item.classList.toggle('active');
        });
    });

    const timelineCards = document.querySelectorAll('.timeline-card');
    
    function checkTimeline() {
        const triggerBottom = window.innerHeight * 0.8;
        
        timelineCards.forEach(card => {
            const cardTop = card.getBoundingClientRect().top;
            
            if(cardTop < triggerBottom) {
                card.style.opacity = "1";
                card.style.transform = "translateX(0)";
            }
        });
    }
    
    // Initialize
    timelineCards.forEach(card => {
        card.style.opacity = "0";
        if(card.classList.contains('left')) {
            card.style.transform = "translateX(-50px)";
        } else {
            card.style.transform = "translateX(50px)";
        }
        card.style.transition = "all 0.8s ease";
    });
    
    window.addEventListener('scroll', checkTimeline);
    checkTimeline(); // Initial check
