export function initAnimations() {
    // === Book card hover animation ===
    const bookCards = document.querySelectorAll('.book-card');
    bookCards.forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
    });

    // === Intersection Observer for scroll animations ===
    const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Animate sections on scroll
    document.querySelectorAll('.section, .book-card, .category-card, .feature-card').forEach(function (el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // === Hero animation (stagger children) ===
    const heroCards = document.querySelectorAll('.hero-book-card');
    heroCards.forEach(function (card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        card.style.transitionDelay = (index * 0.15) + 's';
        setTimeout(function () {
            card.style.opacity = '1';
            card.style.transform = index % 2 === 1 ? 'translateY(30px)' : 'translateY(0)';
        }, 100);
    });
}
