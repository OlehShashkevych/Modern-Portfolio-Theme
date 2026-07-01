import * as THREE from 'three';
import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

// Initialize all scripts
    // ── Dropdown script ──
    const dd = document.getElementById('lang-dd');
    if (dd) {
      const toggle = dd.querySelector('.lang-dropdown-toggle');
      const menu = document.getElementById('lang-menu');
      toggle?.addEventListener('click', () => {
        const isOpen = dd.classList.toggle('open');
        toggle.setAttribute('aria-expanded', String(isOpen));
        if (menu) {
          if (isOpen) {
            menu.classList.add('lang-menu-active');
            toggle.querySelector('svg')?.classList.add('rotate-180');
          } else {
            menu.classList.remove('lang-menu-active');
            toggle.querySelector('svg')?.classList.remove('rotate-180');
          }
        }
      });
      document.addEventListener('click', (e) => {
        if (!dd.contains(e.target)) {
          dd.classList.remove('open');
          toggle?.setAttribute('aria-expanded', 'false');
          if (menu) {
            menu.classList.remove('lang-menu-active');
            toggle.querySelector('svg')?.classList.remove('rotate-180');
          }
        }
      });
    }

    // ── Header Scroll Behavior ──
    const header = document.getElementById('main-header');
    let lastScrollY = window.scrollY;
    if (header) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > lastScrollY && window.scrollY > 50) {
          header.style.transform = 'translateY(-100%)';
        } else {
          header.style.transform = 'translateY(0)';
        }
        lastScrollY = window.scrollY;
      }, { passive: true });
    }

    // ── Scroll Marquee ──
    const scrollMarquees = document.querySelectorAll('.js-scroll-marquee');
    if (scrollMarquees.length > 0) {
      window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        scrollMarquees.forEach(marquee => {
          // Adjust multiplier for speed
          marquee.style.transform = `translateX(-${scrollY * 0.8}px)`;
        });
      }, { passive: true });
    }

    // ── Page transition curtain script ──
    const curtain = document.getElementById('page-curtain');



    function curtainLeave() {
      if (!curtain) {
          startReveals();
          return;
      }
      curtain.classList.remove('enter-from-top', 'covered');
      requestAnimationFrame(() => {
        curtain.classList.add('leave');
        setTimeout(startReveals, 300); // Start reveals slightly after curtain starts leaving
      });
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
      requestAnimationFrame(() => {
        curtain.classList.add('covered');
      });

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

    // ── Swiper Initialization & Custom Cursor ──
    const swiperEl = document.querySelector('.featured-projects-swiper');
    if (swiperEl) {
      
      const textContainer = document.getElementById('project-text-container');
      const textTitle = document.querySelector('.project-title span');
      const textExcerpt = document.querySelector('.project-excerpt');
      const textMeta = document.querySelector('.project-client-year');
      const textLink = document.querySelector('.project-link');

      const updateSliderText = (swiper) => {
        if (!textContainer) return;
        const activeSlide = swiper.slides[swiper.activeIndex];
        if (!activeSlide || activeSlide.classList.contains('pointer-events-none')) return;
        
        const title = activeSlide.getAttribute('data-title');
        const excerpt = activeSlide.getAttribute('data-excerpt');
        const client = activeSlide.getAttribute('data-client');
        const year = activeSlide.getAttribute('data-year');
        const url = activeSlide.getAttribute('data-url');
        
        // Slide up out
        textContainer.classList.add('text-slide-up-out');
        
        setTimeout(() => {
          // Update text
          if(textTitle) textTitle.textContent = title;
          if(textExcerpt) textExcerpt.textContent = excerpt;
          if(textMeta) textMeta.textContent = `${client} · ${year}`;
          if(textLink) textLink.setAttribute('href', url || '#');
          
          // Reset to bottom
          textContainer.classList.remove('text-slide-up-out');
          textContainer.classList.add('text-slide-up-in');
          
          // Allow DOM to update
          requestAnimationFrame(() => {
            requestAnimationFrame(() => {
               // Slide up in to normal position
               textContainer.classList.remove('text-slide-up-in');
            });
          });
        }, 300); // Wait for fade out
      };

      const mySwiper = new Swiper(swiperEl, {
        modules: [Navigation],
        slidesPerView: 'auto',
        spaceBetween: 24,
        navigation: {
          nextEl: '.swiper-btn-next',
          prevEl: '.swiper-btn-prev',
        },
        breakpoints: {
          768: { spaceBetween: 32 },
          1024: { spaceBetween: 40 }
        },
        on: {
          init: function () {
            // Set initial text without animation
            const activeSlide = this.slides[this.activeIndex];
            if (!activeSlide || !textContainer) return;
            const title = activeSlide.getAttribute('data-title');
            const excerpt = activeSlide.getAttribute('data-excerpt');
            const client = activeSlide.getAttribute('data-client');
            const year = activeSlide.getAttribute('data-year');
            const url = activeSlide.getAttribute('data-url');
            if(textTitle) textTitle.textContent = title;
            if(textExcerpt) textExcerpt.textContent = excerpt;
            if(textMeta) textMeta.textContent = `${client} · ${year}`;
            if(textLink) textLink.setAttribute('href', url || '#');
          },
          slideChange: function () {
            updateSliderText(this);
          }
        }
      });
          }

    // Custom Cursor Logic with micro-delay (lerp) - Global for all project-image-wrappers
    const imageWrappers = document.querySelectorAll('.project-image-wrapper');
    
    imageWrappers.forEach(wrapper => {
      const btn = wrapper.querySelector('.watch-case-btn');
      if (!btn) return;
      
      let mouseX = 0, mouseY = 0;
      let currentX = 0, currentY = 0;
      let isHovering = false;
      let animationFrameId;

      const lerp = (start, end, factor) => start + (end - start) * factor;

      const updateBtnPosition = () => {
        if (!isHovering) return;
        
        currentX = lerp(currentX, mouseX, 0.15); // Adjust factor for delay amount (lower = more delay)
        currentY = lerp(currentY, mouseY, 0.15);
        
        btn.style.transform = `translate(${currentX}px, ${currentY}px) scale(1)`;
        
        animationFrameId = requestAnimationFrame(updateBtnPosition);
      };
      
      let cachedRect = null;
      
      wrapper.addEventListener('mouseenter', (e) => {
        isHovering = true;
        
        // Initialize current position to mouse entry point to avoid flying in from 0,0
        cachedRect = wrapper.getBoundingClientRect();
        currentX = mouseX = e.clientX - cachedRect.left;
        currentY = mouseY = e.clientY - cachedRect.top;
        
        btn.style.transform = `translate(${currentX}px, ${currentY}px) scale(0)`;
        
        btn.classList.remove('is-leaving');
        btn.classList.add('is-active');
        
        updateBtnPosition();
      });
      
      wrapper.addEventListener('mousemove', (e) => {
        if (!cachedRect) return;
        mouseX = e.clientX - cachedRect.left;
        mouseY = e.clientY - cachedRect.top;
      });
      
      wrapper.addEventListener('mouseleave', () => {
        isHovering = false;
        cachedRect = null;
        cancelAnimationFrame(animationFrameId);
        
        btn.classList.remove('is-active');
        btn.classList.add('is-leaving');
        // Scale down at current position
        btn.style.transform = `translate(${currentX}px, ${currentY}px) scale(0)`;
      });
      
      // Ensure click on wrapper goes to the link
      wrapper.addEventListener('click', () => {
        const url = wrapper.getAttribute('data-project-url');
        if (url) window.location.href = url;
      });
    });

    mobileMenu?.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => toggleMenu(false));
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) toggleMenu(false);
    });

    // ── Blogs Slider ──
    const blogsSliderEl = document.querySelector('.blogs-slider');
    if (blogsSliderEl) {
      new Swiper(blogsSliderEl, {
        modules: [Navigation],
        slidesPerView: 1.2,
        spaceBetween: 24,
        navigation: {
          nextEl: '.blogs-next',
          prevEl: '.blogs-prev',
        },
        breakpoints: {
          768: { slidesPerView: 2.2, spaceBetween: 24 },
          1024: { slidesPerView: 3.2, spaceBetween: 32 },
          1280: { slidesPerView: 4.4, spaceBetween: 32 }
        }
      });
    }

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
              const delay = (charIndex++ * 0.064).toFixed(3);
              return `<span class="char" style="animation-delay:${delay}s">${ch}</span>`;
            })
            .join('');
          return `<span class="word">${chars}</span>`;
        })
        .join(' '); 
      el.innerHTML = html;
    });

    document.querySelectorAll('[data-reveal="typewriter"]').forEach((el) => {
      const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
      const textNodes = [];
      let node;
      while (node = walker.nextNode()) {
        if (node.nodeValue.trim().length > 0) {
          textNodes.push(node);
        }
      }
      
      let charIndex = 0;
      textNodes.forEach(textNode => {
        const text = textNode.nodeValue;
        const fragment = document.createDocumentFragment();

        const parentLi = textNode.parentElement ? textNode.parentElement.closest('li') : null;
        if (parentLi && !parentLi.dataset.hasMarkerDelay) {
           const delay = (charIndex * 0.005).toFixed(3);
           parentLi.style.setProperty('--marker-delay', `${delay}s`);
           parentLi.dataset.hasMarkerDelay = "true";
           parentLi.classList.add('delayed-marker');
        }

        for (let i = 0; i < text.length; i++) {
          const char = text[i];
          const span = document.createElement('span');
          span.textContent = char;
          span.className = 'type-char';
          span.style.animationDelay = `${(charIndex++ * 0.005).toFixed(3)}s`;
          fragment.appendChild(span);
        }
        textNode.parentNode.replaceChild(fragment, textNode);
      });
    });

    function startReveals() {
      requestAnimationFrame(() => {
        const io = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              io.unobserve(entry.target);
            }
          });
        }, { threshold: 0, rootMargin: '0px 0px -60px 0px' });
        
        const ioMiddle = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              ioMiddle.unobserve(entry.target);
            }
          });
        }, { threshold: 0, rootMargin: '0px 0px -70% 0px' });

        document.querySelectorAll('[data-reveal]').forEach((el) => {
          if (el.dataset.revealMargin === 'middle') {
            ioMiddle.observe(el);
          } else {
            io.observe(el);
          }
        });
      });
    }

    // ── Three.js Particle Background ──
    function getEnvelopeData(count) {
         const data = new Float32Array(count * 4);
         const thickness = 0.08;
         
         for(let i=0; i<count; i++) {
             data[i*4] = Math.random(); // t
             data[i*4+1] = (Math.random() - 0.5) * thickness; // dx
             data[i*4+2] = (Math.random() - 0.5) * thickness; // dy
             data[i*4+3] = (Math.random() - 0.5) * thickness * 2; // dz
         }
         return data;
    }
    
    function getEnvelopePos(t, w, h) {
         const lenTop = 2*w;
         const lenRight = 2*h;
         const lenBottom = 2*w;
         const lenLeft = 2*h;
         const lenDiag = Math.sqrt(w*w + h*h);
         
         const totalLen = lenTop + lenRight + lenBottom + lenLeft + lenDiag * 2;
         let r = t * totalLen;
         let x=0, y=0;
         if(r < lenTop) {
             x = -w + (r/lenTop)*2*w;
             y = h;
         } else if(r < lenTop + lenRight) {
             r -= lenTop;
             x = w;
             y = h - (r/lenRight)*2*h;
         } else if(r < lenTop + lenRight + lenBottom) {
             r -= (lenTop + lenRight);
             x = w - (r/lenBottom)*2*w;
             y = -h;
         } else if(r < lenTop + lenRight + lenBottom + lenLeft) {
             r -= (lenTop + lenRight + lenBottom);
             x = -w;
             y = -h + (r/lenLeft)*2*h;
         } else if(r < lenTop + lenRight + lenBottom + lenLeft + lenDiag) {
             r -= (lenTop + lenRight + lenBottom + lenLeft);
             let dt = r/lenDiag;
             x = -w + dt*w;
             y = h - dt*h;
         } else {
             r -= (lenTop + lenRight + lenBottom + lenLeft + lenDiag);
             let dt = r/lenDiag;
             x = 0 + dt*w; // Reversed to start at center
             y = 0 + dt*h;
         }
         return {x, y};
    }

    function initParticles(containerId, isPurpleOnly = false) {
      const container = document.getElementById(containerId);
      if (!container) return;

      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
      const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
      
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
      container.appendChild(renderer.domElement);

      function createCircleTexture() {
          const canvas = document.createElement('canvas');
          canvas.width = 64;
          canvas.height = 64;
          const context = canvas.getContext('2d');
          const gradient = context.createRadialGradient(32, 32, 0, 32, 32, 32);
          gradient.addColorStop(0, 'rgba(255,255,255,1)');
          gradient.addColorStop(0.8, 'rgba(255,255,255,0.8)');
          gradient.addColorStop(1, 'rgba(255,255,255,0)');
          context.fillStyle = gradient;
          context.fillRect(0, 0, 64, 64);
          return new THREE.CanvasTexture(canvas);
      }
      const circleTexture = createCircleTexture();

      const particlesCount = 2000;
      // We will split particles into 3 groups for 3 different sizes
      const groups = 3;
      const counts = [600, 800, 600]; // sum = 2000
      
      const geometries = [];
      const originalPosArrays = [];
      const currentBaseArrays = [];
      const currentRepelArrays = [];
      const particlesMeshes = [];
      const envelopeTargetsGroups = [];

      let isEnvelopeHover = false;
      if (containerId === 'footer-particles') {
          const btn = document.querySelector('.js-footer-email-btn');
          if (btn) {
              btn.addEventListener('mouseenter', () => { if (window.innerWidth > 768) isEnvelopeHover = true; });
              btn.addEventListener('mouseleave', () => isEnvelopeHover = false);
          }
      }

      const materials = [
        new THREE.PointsMaterial({ size: 0.02, map: circleTexture, transparent: true, blending: THREE.AdditiveBlending, depthWrite: false }),
        new THREE.PointsMaterial({ size: 0.04, map: circleTexture, transparent: true, blending: THREE.AdditiveBlending, depthWrite: false }),
        new THREE.PointsMaterial({ size: 0.06, map: circleTexture, transparent: true, blending: THREE.AdditiveBlending, depthWrite: false })
      ];

      camera.position.z = 4;
      const fovRad = (75 * Math.PI) / 180;
      const heightWorld = 2 * 4 * Math.tan(fovRad / 2); 
      
      // Calculate max radius based on the screen width to fill the section
      function getMaxRadius() {
          const widthWorld = heightWorld * camera.aspect;
          return Math.max(widthWorld, heightWorld) * 0.8;
      }

      let currentMaxRadius = getMaxRadius();

      for (let g = 0; g < groups; g++) {
          const geometry = new THREE.BufferGeometry();
          const posArray = new Float32Array(counts[g] * 3);
          const origPosArray = new Float32Array(counts[g] * 3);
          const repelArray = new Float32Array(counts[g] * 2); // store current repel offsets

          for(let i = 0; i < counts[g]; i++) {
            const i3 = i * 3;
            const i2 = i * 2;
            const theta = Math.random() * Math.PI * 2;
            
            // spread evenly across the section
            const r = Math.sqrt(Math.random()) * currentMaxRadius;

            const x = Math.cos(theta) * r;
            const y = Math.sin(theta) * r;
            const z = (Math.random() - 0.5) * 2; 
            
            posArray[i3] = x;
            posArray[i3+1] = y;
            posArray[i3+2] = z;
            
            origPosArray[i3] = x;
            origPosArray[i3+1] = y;
            origPosArray[i3+2] = z;
            
            repelArray[i2] = 0;
            repelArray[i2+1] = 0;
          }

          const curBaseArray = new Float32Array(counts[g] * 3);
          for(let i=0; i<counts[g]; i++) {
             curBaseArray[i*3] = origPosArray[i*3];
             curBaseArray[i*3+1] = origPosArray[i*3+1];
             curBaseArray[i*3+2] = origPosArray[i*3+2];
          }

          geometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
          geometries.push(geometry);
          originalPosArrays.push(origPosArray);
          currentBaseArrays.push(curBaseArray);
          currentRepelArrays.push(repelArray);
          
          if (containerId === 'footer-particles') {
              envelopeTargetsGroups.push(getEnvelopeData(counts[g]));
          }

          const mesh = new THREE.Points(geometry, materials[g]);
          scene.add(mesh);
          particlesMeshes.push(mesh);
      }

      let mouseX = 0;
      let mouseY = 0;
      let windowHalfX = window.innerWidth / 2;
      let windowHalfY = window.innerHeight / 2;

      document.addEventListener('mousemove', (event) => {
        mouseX = (event.clientX - windowHalfX);
        mouseY = (event.clientY - windowHalfY);
      });

      window.addEventListener('resize', () => {
        if (!container) return;
        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
        currentMaxRadius = getMaxRadius();
      });

      const clock = new THREE.Clock();

      const colors = isPurpleOnly ? [
          new THREE.Color('#9b51e0')
      ] : [
          new THREE.Color('#4a90e2'),
          new THREE.Color('#9b51e0'),
          new THREE.Color('#ff6b6b'),
          new THREE.Color('#5c55e9')
      ];

      function animate() {
        requestAnimationFrame(animate);
        const elapsedTime = clock.getElapsedTime();

        // 1. Calculate mouse target in world space
        const widthWorld = heightWorld * camera.aspect;
        const targetX = (mouseX / windowHalfX) * (widthWorld / 2);
        const targetY = -(mouseY / windowHalfY) * (heightWorld / 2);

        // 2. Color transition (slower: 5 seconds per color)
        const cycle = elapsedTime * 0.2; 
        const index1 = Math.floor(cycle) % colors.length;
        const index2 = (index1 + 1) % colors.length;
        const mix = cycle - Math.floor(cycle);
        const currentColor = colors[index1].clone().lerp(colors[index2], mix);
        
        materials.forEach(mat => mat.color.copy(currentColor));

        for (let g = 0; g < groups; g++) {
            // 3. Wave effect & Mouse repel on individual particles
            const positions = geometries[g].attributes.position.array;
            const orig = originalPosArrays[g];
            const curBase = currentBaseArrays[g];
            const curRepel = currentRepelArrays[g];
            const cCount = counts[g];
            
            const mouseRadius = 3.0; 
            const mouseForce = 1.5;
            
            for(let i = 0; i < cCount; i++) {
                const i3 = i * 3;
                const i2 = i * 2;
                
                let targetXPos = orig[i3];
                let targetYPos = orig[i3+1];
                let targetZPos = orig[i3+2];

                if (isEnvelopeHover) {
                    const envData = envelopeTargetsGroups[g];
                    const t_init = envData[i*4];
                    const dx = envData[i*4+1];
                    const dy = envData[i*4+2];
                    const dz = envData[i*4+3];
                    
                    // Freeze current_t to its initial value so they don't traverse the path
                    let current_t = t_init;
                    
                    const envPos = getEnvelopePos(current_t, 1.6, 1.0);
                    
                    targetXPos = envPos.x + dx + (widthWorld * 0.25);
                    targetYPos = envPos.y + dy;
                    targetZPos = dz;
                }

                curBase[i3] += (targetXPos - curBase[i3]) * 0.05;
                curBase[i3+1] += (targetYPos - curBase[i3+1]) * 0.05;
                curBase[i3+2] += (targetZPos - curBase[i3+2]) * 0.05;

                const ox = curBase[i3];
                const oy = curBase[i3+1];
                const oz = curBase[i3+2];
                
                const dx = ox - targetX;
                const dy = oy - targetY;
                const dist = Math.sqrt(dx*dx + dy*dy);
                
                let targetRepelX = 0;
                let targetRepelY = 0;
                
                if (dist < mouseRadius && dist > 0.001) {
                    const force = (1 - dist / mouseRadius) * mouseForce;
                    targetRepelX = (dx / dist) * force;
                    targetRepelY = (dy / dist) * force;
                }
                
                // Viscous lerp (0.02 is very slow/heavy)
                curRepel[i2] += (targetRepelX - curRepel[i2]) * 0.02;
                curRepel[i2+1] += (targetRepelY - curRepel[i2+1]) * 0.02;
                
                // Wave: modify Z and slightly X/Y, plus current repel state
                let waveAmp = isEnvelopeHover ? 0.08 : 0.1;
                let waveZAmp = isEnvelopeHover ? 0.12 : 0.2;
                
                // Increase frequency for the twitchy effect
                let freqZ1 = isEnvelopeHover ? 1.5 : 0.8;
                let freqZ2 = isEnvelopeHover ? 1.2 : 0.6;
                let freqX = isEnvelopeHover ? 2.0 : 0.4;
                let freqY = isEnvelopeHover ? 1.8 : 0.5;

                positions[i3+2] = oz + Math.sin(elapsedTime * freqZ1 + ox) * waveZAmp + Math.cos(elapsedTime * freqZ2 + oy) * waveZAmp;
                positions[i3] = ox + curRepel[i2] + Math.sin(elapsedTime * freqX + oy) * waveAmp;
                positions[i3+1] = oy + curRepel[i2+1] + Math.cos(elapsedTime * freqY + ox) * waveAmp;
            }
            geometries[g].attributes.position.needsUpdate = true;
        }

        // 4. Scroll scale shrink out
        if (container.id === 'hero-particles') {
            const scrollY = window.scrollY;
            const maxScroll = window.innerHeight * 0.8;
            const scrollScale = Math.max(0, 1 - (scrollY / maxScroll));
            particlesMeshes.forEach(mesh => {
               mesh.scale.set(scrollScale, scrollScale, scrollScale);
            });
        }

        renderer.render(scene, camera);
      }

      animate();

      window.addEventListener('resize', () => {
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
      });
    }

    // ── Split Promo Particles ──
    function initSplitPromoParticles() {
      const container = document.querySelector('.split-promo-canvas');
      if (!container) return;

      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
      const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
      
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
      container.appendChild(renderer.domElement);

      const particlesCount = 2000;
      const geometry = new THREE.BufferGeometry();
      
      const positions = new Float32Array(particlesCount * 3);
      const targetPositions = new Float32Array(particlesCount * 3);
      const originalPositions = new Float32Array(particlesCount * 3);
      
      const colors = new Float32Array(particlesCount * 3);
      const targetColors = new Float32Array(particlesCount * 3);

      // Base color: Purple (#9333ea) -> [0.57, 0.2, 0.91]
      // Left color: Pink (#ec4899) -> [0.92, 0.28, 0.6]
      // Right color: Blue (#3b82f6) -> [0.23, 0.51, 0.96]
      const colorBase = new THREE.Color('#9333ea');
      const colorLeft = new THREE.Color('#ec4899');
      const colorRight = new THREE.Color('#3b82f6');

      camera.position.z = 4;
      const fovRad = (75 * Math.PI) / 180;
      const heightWorld = 2 * 4 * Math.tan(fovRad / 2); 
      let widthWorld = heightWorld * camera.aspect;

      for (let i = 0; i < particlesCount; i++) {
        const i3 = i * 3;
        
        // Random scattered original positions across the whole container
        const xOffset = (Math.random() - 0.5) * widthWorld * 1.5;
        const yOffset = (Math.random() - 0.5) * heightWorld * 1.5;
        const zOffset = (Math.random() - 0.5) * 4;

        positions[i3] = xOffset;
        positions[i3+1] = yOffset;
        positions[i3+2] = zOffset;
        
        originalPositions[i3] = xOffset;
        originalPositions[i3+1] = yOffset;
        originalPositions[i3+2] = zOffset;

        colors[i3] = colorBase.r;
        colors[i3+1] = colorBase.g;
        colors[i3+2] = colorBase.b;
      }

      geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
      geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

      // Circular texture
      const canvas = document.createElement('canvas');
      canvas.width = 16;
      canvas.height = 16;
      const ctx = canvas.getContext('2d');
      ctx.beginPath();
      ctx.arc(8, 8, 8, 0, Math.PI * 2);
      ctx.fillStyle = '#fff';
      ctx.fill();
      const texture = new THREE.CanvasTexture(canvas);

      const material = new THREE.PointsMaterial({
        size: 0.03,
        map: texture,
        transparent: true,
        vertexColors: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending
      });

      const points = new THREE.Points(geometry, material);
      scene.add(points);

      // Pre-calculate 2D shape targets for all particles
      const ringTargets = [];
      for (let i = 0; i < particlesCount; i++) {
        // 2D Ring (Thicker)
        const angle = (i / particlesCount) * Math.PI * 2;
        const radius = 1.2 + (Math.random() * 0.4 - 0.2); // Thicker ring noise
        ringTargets.push({
          x: Math.cos(angle) * radius,
          y: Math.sin(angle) * radius
        });
      }

      const cameraTargets = [];
      for (let i = 0; i < particlesCount; i++) {
        // 2D Camera [ . ]
        const part = Math.random();
        const t = Math.random();
        let x, y;
        
        if (part < 0.3) {
          // Center circle
          const angle = Math.random() * Math.PI * 2;
          const r = 0.3 + (Math.random() - 0.5) * 0.1;
          x = Math.cos(angle) * r;
          y = Math.sin(angle) * r;
        } else if (part < 0.45) {
          // Left vertical bracket line
          x = -1.0;
          y = -1.0 + t * 2.0;
        } else if (part < 0.525) {
          // Left top bracket tip
          x = -1.0 + t * 0.5;
          y = 1.0;
        } else if (part < 0.6) {
          // Left bottom bracket tip
          x = -1.0 + t * 0.5;
          y = -1.0;
        } else if (part < 0.75) {
          // Right vertical bracket line
          x = 1.0;
          y = -1.0 + t * 2.0;
        } else if (part < 0.825) {
          // Right top bracket tip
          x = 1.0 - t * 0.5;
          y = 1.0;
        } else {
          // Right bottom bracket tip
          x = 1.0 - t * 0.5;
          y = -1.0;
        }
        
        // Minor scatter
        x += (Math.random() - 0.5) * 0.05;
        y += (Math.random() - 0.5) * 0.05;

        // Scale to fit visually
        x *= 0.8;
        y *= 0.8;

        cameraTargets.push({ x, y });
      }

      let activeState = 'none'; // 'none', 'left', 'right'
      let shapeRotation = 0;

      const leftCol = document.querySelector('.split-promo-left');
      const rightCol = document.querySelector('.split-promo-right');

      if (leftCol) {
        leftCol.addEventListener('mouseenter', () => { activeState = 'left'; });
        leftCol.addEventListener('mouseleave', () => { if(activeState === 'left') activeState = 'none'; });
      }
      if (rightCol) {
        rightCol.addEventListener('mouseenter', () => { activeState = 'right'; });
        rightCol.addEventListener('mouseleave', () => { if(activeState === 'right') activeState = 'none'; });
      }

      function getCenterInWorld(el) {
        if (!el || !container) return 0;
        const rect = el.getBoundingClientRect();
        const containerRect = container.getBoundingClientRect();
        const localX = (rect.left - containerRect.left) + rect.width / 2;
        const ndcX = (localX / containerRect.width) * 2 - 1;
        return ndcX * (widthWorld / 2);
      }

      function animate() {
        requestAnimationFrame(animate);
        
        const posAttr = geometry.attributes.position;
        const colAttr = geometry.attributes.color;
        
        shapeRotation += 0.01;
        widthWorld = heightWorld * camera.aspect;
        
        const centerXLeft = getCenterInWorld(leftCol);
        const centerXRight = getCenterInWorld(rightCol);

        for (let i = 0; i < particlesCount; i++) {
          const i3 = i * 3;
          let tx, ty, tz, tc;

          if (activeState === 'left') {
            // All particles form 2D Z-rotation Ring target
            const st = ringTargets[i];
            const cos = Math.cos(shapeRotation);
            const sin = Math.sin(shapeRotation);
            tx = st.x * cos - st.y * sin + centerXLeft;
            ty = st.x * sin + st.y * cos;
            tz = 0;
            tc = colorLeft;
          } else if (activeState === 'right') {
            // All particles form a non-rotating Camera target [ . ]
            const ct = cameraTargets[i];
            tx = ct.x + Math.sin(shapeRotation * 2 + i) * 0.05 + centerXRight;
            ty = ct.y + Math.cos(shapeRotation * 2 + i) * 0.05;
            tz = 0;
            tc = colorRight;
          } else {
            // Back to original floating positions (spread across full container)
            tx = originalPositions[i3] + Math.sin(shapeRotation * 0.5 + i) * 0.2;
            ty = originalPositions[i3+1] + Math.cos(shapeRotation * 0.3 + i) * 0.2;
            tz = originalPositions[i3+2];
            tc = colorBase;
          }

          // Lerp position
          posAttr.array[i3] += (tx - posAttr.array[i3]) * 0.05;
          posAttr.array[i3+1] += (ty - posAttr.array[i3+1]) * 0.05;
          posAttr.array[i3+2] += (tz - posAttr.array[i3+2]) * 0.05;

          // Lerp color
          colAttr.array[i3] += (tc.r - colAttr.array[i3]) * 0.05;
          colAttr.array[i3+1] += (tc.g - colAttr.array[i3+1]) * 0.05;
          colAttr.array[i3+2] += (tc.b - colAttr.array[i3+2]) * 0.05;
        }

        posAttr.needsUpdate = true;
        colAttr.needsUpdate = true;

        renderer.render(scene, camera);
      }

      animate();

      window.addEventListener('resize', () => {
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
      });
    }

    // ── Footer CTA Scroll Animation ──
    function initFooterAnimation() {
      const ctaBlock = document.querySelector('.js-footer-cta');
      if (!ctaBlock) return;

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            ctaBlock.classList.remove('scale-[0.95]');
            ctaBlock.classList.add('scale-100');
          }
        });
      }, { threshold: 0.2 });

      observer.observe(ctaBlock);
    }

    const initLazy = () => {
      initParticles('hero-particles', false);
      initFooterAnimation();

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            if (entry.target.id === 'footer-particles') {
              initParticles('footer-particles', true);
            } else if (entry.target.classList.contains('split-promo-canvas')) {
              initSplitPromoParticles();
            }
            obs.unobserve(entry.target);
          }
        });
      }, { rootMargin: '200px' });

      const footerParticles = document.getElementById('footer-particles');
      if (footerParticles) observer.observe(footerParticles);

      const promoCanvas = document.querySelector('.split-promo-canvas');
      if (promoCanvas) observer.observe(promoCanvas);
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initLazy);
    } else {
      initLazy();
    }
