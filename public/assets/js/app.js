(() => {
  'use strict';
  const root = document.documentElement;
  const storedTheme = (() => { try { return localStorage.getItem('peridona-theme'); } catch (_) { return null; } })();
  const preferred = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  root.dataset.theme = storedTheme || preferred;

  const themeButton = document.querySelector('.theme-toggle');
  const updateThemeButton = () => {
    if (!themeButton) return;
    const dark = root.dataset.theme === 'dark';
    themeButton.innerHTML = `<span class="theme-icon" aria-hidden="true">${dark ? '☀' : '☾'}</span>`;
    themeButton.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
  };
  updateThemeButton();
  themeButton?.addEventListener('click', () => {
    root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
    try { localStorage.setItem('peridona-theme', root.dataset.theme); } catch (_) { /* Private browsing may disable storage. */ }
    updateThemeButton();
  });

  const menuToggle = document.querySelector('.mobile-menu-toggle');
  const navPanel = document.querySelector('.nav-panel');
  menuToggle?.addEventListener('click', () => {
    const open = navPanel?.classList.toggle('open') ?? false;
    menuToggle.setAttribute('aria-expanded', String(open));
    menuToggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  });
  navPanel?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    navPanel.classList.remove('open');
    menuToggle?.setAttribute('aria-expanded', 'false');
  }));
  document.addEventListener('click', event => {
    if (!navPanel || !menuToggle || !navPanel.classList.contains('open')) return;
    if (!navPanel.contains(event.target) && !menuToggle.contains(event.target)) {
      navPanel.classList.remove('open');
      menuToggle.setAttribute('aria-expanded', 'false');
    }
  });

  const header = document.querySelector('.site-header');
  const backToTop = document.querySelector('.back-to-top');
  const onScroll = () => {
    const moved = window.scrollY > 24;
    header?.classList.toggle('scrolled', moved);
    backToTop?.classList.toggle('visible', window.scrollY > 500);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
  backToTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  const revealItems = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });
    revealItems.forEach(item => observer.observe(item));
  } else {
    revealItems.forEach(item => item.classList.add('is-visible'));
  }

  const motionReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const animateHero = () => {
    const activeContent = document.querySelector('.hero-slide.swiper-slide-active .hero-content');
    if (window.gsap && activeContent && !motionReduced) {
      window.gsap.fromTo(activeContent.children, { y: 26, opacity: 0 }, { y: 0, opacity: 1, duration: .75, stagger: .11, ease: 'power3.out', clearProps: 'transform,opacity' });
    }
  };
  if (window.Swiper && document.querySelector('.hero-swiper')) {
    new Swiper('.hero-swiper', {
      loop: true,
      speed: 950,
      effect: 'fade',
      fadeEffect: { crossFade: true },
      autoplay: { delay: 6500, disableOnInteraction: false, pauseOnMouseEnter: true },
      pagination: { el: '.hero-pagination', clickable: true },
      navigation: { nextEl: '.hero-next', prevEl: '.hero-prev' },
      a11y: { enabled: true },
      on: { init: animateHero, slideChangeTransitionEnd: animateHero }
    });
  }

  // A second editorial carousel gives the homepage a more visual, discovery-led feel.
  if (window.Swiper && document.querySelector('.audience-swiper')) {
    new Swiper('.audience-swiper', {
      slidesPerView: 1.12,
      spaceBetween: 14,
      speed: 750,
      watchOverflow: true,
      grabCursor: true,
      pagination: { el: '.audience-pagination', type: 'progressbar' },
      navigation: { nextEl: '.audience-next', prevEl: '.audience-prev' },
      breakpoints: {
        560: { slidesPerView: 1.8, spaceBetween: 16 },
        820: { slidesPerView: 2.5, spaceBetween: 18 },
        1120: { slidesPerView: 3.15, spaceBetween: 20 }
      },
      a11y: { enabled: true }
    });
  }

  // Gentle hover lift for desktop only; never runs on touch screens or reduced-motion devices.
  if (!motionReduced && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('.service-card, .admin-role-card, .stat-card').forEach(card => {
      card.addEventListener('pointermove', event => {
        const rect = card.getBoundingClientRect();
        const x = (event.clientX - rect.left) / rect.width - .5;
        const y = (event.clientY - rect.top) / rect.height - .5;
        card.style.setProperty('--pointer-x', `${(x + .5) * 100}%`);
        card.style.setProperty('--pointer-y', `${(y + .5) * 100}%`);
      });
    });
  }

  document.querySelectorAll('[data-before-after]').forEach(widget => {
    const range = widget.querySelector('.ba-range');
    const update = () => widget.style.setProperty('--split', `${range.value}%`);
    range?.addEventListener('input', update);
    update();
  });

  const filterButtons = document.querySelectorAll('[data-filter]');
  const projectCards = document.querySelectorAll('.portfolio-grid [data-category]');
  filterButtons.forEach(button => button.addEventListener('click', () => {
    const selected = button.dataset.filter;
    filterButtons.forEach(item => item.classList.toggle('active', item === button));
    projectCards.forEach(card => {
      const visible = selected === 'all' || card.dataset.category === selected;
      card.hidden = !visible;
      if (visible) card.classList.add('is-visible');
    });
  }));

  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        form.reportValidity();
      }
    });
  });
})();
