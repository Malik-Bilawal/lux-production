    
import '@css/home.css';

    document.addEventListener("DOMContentLoaded", function () {
        console.log("Home page script loaded");
    
        // Meta tags (safe access)
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const newsletterMeta = document.querySelector('meta[name="newsletter-route"]');
    
        const csrfToken = csrfMeta ? csrfMeta.content : null;
        const newsletterRoute = newsletterMeta ? newsletterMeta.content : null;
    


    
        swiperJS.onload = () => {
    
            const swiper = new Swiper(".myHeroSwiper", {
                loop: true,
                speed: 2500,
                autoplay: { delay: 4000, disableOnInteraction: false },
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 1.2,
                spaceBetween: 30,
                coverflowEffect: {
                    rotate: 25,        
                    stretch: 0,
                    depth: 300,        
                    modifier: 1.5,     
                    slideShadows: true 
                },
            
                on: {
                    init: function () {
                 
                    },
                    slideChange: function () {
                        const activeIndex = this.realIndex;
    
                        const dots = document.querySelectorAll("#custom-pagination .dot");
                        dots.forEach((dot, index) => {
                            if (index === activeIndex) {
                                dot.classList.add("bg-white", "scale-125", "shadow-lg", "ring-2", "ring-white/50");
                                dot.classList.remove("bg-white/40");
                            } else {
                                dot.classList.remove("bg-white", "scale-125", "shadow-lg", "ring-2", "ring-white/50");
                                dot.classList.add("bg-white/40");
                            }
                        });
                    }
                }
            });
        };
    

    const emailInput = document.getElementById("newsletterEmail");
    const submitBtn = document.getElementById("newsletterSubmit");

   
    

    if (emailInput && submitBtn) {
        submitBtn.addEventListener("click", () => {
            submitBtn.disabled = true;
            submitBtn.textContent = "Subscribing...";

            fetch(newsletterRoute, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ email: emailInput.value })
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.textContent = "SUBSCRIBE";
                Swal.fire({
                    icon: "success",
                    title: "Subscribed!",
                    text: data.message,
                    confirmButtonColor: "#4f46e5",
                    background: "#1e293b",
                    color: "#f8fafc",
                    iconColor: "#00f2fe"
                });
                emailInput.value = "";
            })
            .catch(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = "SUBSCRIBE"; 
                Swal.fire({
                    icon: "error",
                    title: "Oops!",
                    text: "Something went wrong. Please try again.",
                    confirmButtonColor: "#ef4444",
                    background: "#1e293b",
                    color: "#f8fafc"
                });
            });
        });
    }
});
