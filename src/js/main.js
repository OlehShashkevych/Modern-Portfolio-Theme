// Initialize all scripts
    // ── Dropdown script ──
    const dd = document.getElementById('lang-dd');
    if (dd) {
      const toggle = dd.querySelector('.lang-dropdown-toggle');
      toggle?.addEventListener('click', () => {
        const isOpen = dd.classList.toggle('open');
        toggle.setAttribute('aria-expanded', String(isOpen));
      });
      document.addEventListener('click', (e) => {
        if (!dd.contains(e.target)) {
          dd.classList.remove('open');
          toggle?.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // ── Page transition curtain script ──
    const curtain = document.getElementById('page-curtain');

    function curtainLeave() {
      if (!curtain) return;
      curtain.classList.remove('enter-from-top', 'covered');
      curtain.getBoundingClientRect(); // Force reflow
      curtain.classList.add('leave');
    }

    function initCurtain() {
      setTimeout(curtainLeave, 500);
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initCurtain);
    } else {
      initCurtain();
    }

    // Handle BFCache (when user clicks browser Back button)
    window.addEventListener('pageshow', (e) => {
      if (e.persisted) {
        curtainLeave();
      }
    });

    document.addEventListener('click', (e) => {
      const anchor = e.target?.closest('a');
      if (!anchor) return;

      const href = anchor.getAttribute('href');
      if (!href) return;

      const isExternal = anchor.target === '_blank';
      const isSameOrigin = href.startsWith('/') || href.startsWith(window.location.origin);
      const isHash = href.startsWith('#');
      const isSpecial =
        href.startsWith('mailto:') ||
        href.startsWith('tel:') ||
        href.includes('/wp-admin') ||
        href.includes('/wp-login');

      if (!isSameOrigin || isExternal || isHash || isSpecial) return;

      e.preventDefault();

      if (!curtain) {
        window.location.href = href;
        return;
      }

      curtain.classList.remove('leave');
      curtain.classList.add('enter-from-top');
      curtain.getBoundingClientRect(); // Force reflow
      curtain.classList.add('covered');

      setTimeout(() => {
        window.location.href = href;
      }, 700);
    });

    // ── Burger / mobile menu script ──
    const burgerBtn = document.getElementById('burger-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    function toggleMenu(open) {
      burgerBtn?.classList.toggle('open', open);
      mobileMenu?.classList.toggle('open', open);
      burgerBtn?.setAttribute('aria-expanded', String(open));
      mobileMenu?.setAttribute('aria-hidden', String(!open));
      document.body.style.overflow = open ? 'hidden' : '';
    }

    burgerBtn?.addEventListener('click', () => {
      const isOpen = mobileMenu?.classList.contains('open');
      toggleMenu(!isOpen);
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => toggleMenu(false));
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) toggleMenu(false);
    });

    // ── Scroll-reveal + Letter-split ──
    document.documentElement.classList.add('js-ready');

    document.querySelectorAll('[data-reveal="letters"]').forEach((el) => {
      const text = (el.textContent || '').trim();
      let charIndex = 0;
      const html = text
        .split(' ')
        .filter(w => w.length > 0)
        .map((word) => {
          const chars = word
            .split('')
            .map((ch) => {
              const delay = (charIndex++ * 0.032).toFixed(3);
              return `<span class="char" style="animation-delay:${delay}s">${ch}</span>`;
            })
            .join('');
          return `<span class="word">${chars}</span>`;
        })
        .join(' '); 
      el.innerHTML = html;
    });

    requestAnimationFrame(() => {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0, rootMargin: '0px 0px -60px 0px' });

      document.querySelectorAll('[data-reveal]').forEach((el) => io.observe(el));
    });
