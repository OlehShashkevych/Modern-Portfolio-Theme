import * as THREE from 'three';

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

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initParticles);
    } else {
      initParticles();
    }
