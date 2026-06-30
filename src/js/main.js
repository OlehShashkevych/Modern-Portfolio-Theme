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
      curtain.getBoundingClientRect(); // Force reflow
      curtain.classList.add('leave');
      
      setTimeout(startReveals, 300); // Start reveals slightly after curtain starts leaving
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
      
      // Custom Cursor Logic with micro-delay (lerp)
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
        
        wrapper.addEventListener('mouseenter', (e) => {
          isHovering = true;
          
          // Initialize current position to mouse entry point to avoid flying in from 0,0
          const rect = wrapper.getBoundingClientRect();
          currentX = mouseX = e.clientX - rect.left;
          currentY = mouseY = e.clientY - rect.top;
          
          btn.style.transform = `translate(${currentX}px, ${currentY}px) scale(0)`;
          
          btn.classList.remove('is-leaving');
          btn.classList.add('is-active');
          
          updateBtnPosition();
        });
        
        wrapper.addEventListener('mousemove', (e) => {
          const rect = wrapper.getBoundingClientRect();
          mouseX = e.clientX - rect.left;
          mouseY = e.clientY - rect.top;
        });
        
        wrapper.addEventListener('mouseleave', () => {
          isHovering = false;
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
    }

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
              const delay = (charIndex++ * 0.064).toFixed(3);
              return `<span class="char" style="animation-delay:${delay}s">${ch}</span>`;
            })
            .join('');
          return `<span class="word">${chars}</span>`;
        })
        .join(' '); 
      el.innerHTML = html;
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
    function initParticles() {
      const container = document.getElementById('hero-particles') || document.getElementById('canvas-container');
      if (!container) return;

      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
      const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
      
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(window.devicePixelRatio);
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
      const currentRepelArrays = [];
      const particlesMeshes = [];

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

          geometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
          geometries.push(geometry);
          originalPosArrays.push(origPosArray);
          currentRepelArrays.push(repelArray);

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

      const colors = [
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
            const curRepel = currentRepelArrays[g];
            const cCount = counts[g];
            
            const mouseRadius = 3.0; 
            const mouseForce = 1.5;
            
            for(let i = 0; i < cCount; i++) {
                const i3 = i * 3;
                const i2 = i * 2;
                const ox = orig[i3];
                const oy = orig[i3+1];
                const oz = orig[i3+2];
                
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
                // Slower multipliers for smoother motion
                positions[i3+2] = oz + Math.sin(elapsedTime * 0.8 + ox) * 0.2 + Math.cos(elapsedTime * 0.6 + oy) * 0.2;
                positions[i3] = ox + curRepel[i2] + Math.sin(elapsedTime * 0.4 + oy) * 0.1;
                positions[i3+1] = oy + curRepel[i2+1] + Math.cos(elapsedTime * 0.5 + ox) * 0.1;
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
      renderer.setPixelRatio(window.devicePixelRatio);
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
        
        // Random scattered original positions (relative to their side)
        const isLeft = i < (particlesCount / 2);
        const xOffset = (Math.random() - 0.5) * (widthWorld / 2) * 0.8;
        const yOffset = (Math.random() - 0.5) * heightWorld * 0.8;
        const zOffset = (Math.random() - 0.5) * 2;

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

      const half = particlesCount / 2;

      // Pre-calculate 2D shape targets
      const ringTargets = [];
      for (let i = 0; i < half; i++) {
        // 2D Ring
        const angle = (i / half) * Math.PI * 2;
        const radius = 1.2 + (Math.random() * 0.2 - 0.1); // slight noise
        ringTargets.push({
          x: Math.cos(angle) * radius,
          y: Math.sin(angle) * radius
        });
      }

      const lotusTargets = [];
      for (let i = 0; i < half; i++) {
        // 2D Lotus (Rose curve)
        const angle = (i / half) * Math.PI * 20; // wrap around multiple times for density
        const petalFactor = Math.abs(Math.sin(3 * angle)); // 6 petals
        const radius = 0.4 + 1.2 * Math.pow(petalFactor, 1.5) + (Math.random() * 0.1);
        lotusTargets.push({
          x: Math.cos(angle) * radius,
          y: Math.sin(angle) * radius
        });
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

      function animate() {
        requestAnimationFrame(animate);
        
        const posAttr = geometry.attributes.position;
        const colAttr = geometry.attributes.color;
        
        shapeRotation += 0.01;
        widthWorld = heightWorld * camera.aspect;
        const centerXLeft = -widthWorld / 4;
        const centerXRight = widthWorld / 4;

        for (let i = 0; i < particlesCount; i++) {
          const isLeft = i < half;
          const i3 = i * 3;
          let tx, ty, tz, tc;
          
          const centerX = isLeft ? centerXLeft : centerXRight;

          if (activeState === 'left' && isLeft) {
            // Apply 2D Z-rotation to Ring target
            const st = ringTargets[i];
            const cos = Math.cos(shapeRotation);
            const sin = Math.sin(shapeRotation);
            tx = st.x * cos - st.y * sin + centerXLeft;
            ty = st.x * sin + st.y * cos;
            tz = 0;
            tc = colorLeft;
          } else if (activeState === 'right' && !isLeft) {
            // Apply 2D Z-rotation to Lotus target (slower spin)
            const tt = lotusTargets[i - half];
            const cos = Math.cos(-shapeRotation * 0.5);
            const sin = Math.sin(-shapeRotation * 0.5);
            tx = tt.x * cos - tt.y * sin + centerXRight;
            ty = tt.x * sin + tt.y * cos;
            tz = 0;
            tc = colorRight;
          } else {
            // Back to original floating positions (centered on their side)
            tx = originalPositions[i3] + Math.sin(shapeRotation * 0.5 + i) * 0.2 + centerX;
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

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        initParticles();
        initSplitPromoParticles();
      });
    } else {
      initParticles();
      initSplitPromoParticles();
    }
