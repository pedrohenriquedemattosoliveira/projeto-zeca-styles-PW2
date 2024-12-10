        // Menu mobile
        const hamburger = document.querySelector('.hamburger');
        const menu = document.querySelector('.menu');

        hamburger.addEventListener('click', () => {
            menu.classList.toggle('active');
        });

        // Scroll suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animação ao scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.background = '#000';
            } else {
                header.style.background = '#1a1a1a';
            }
        });
