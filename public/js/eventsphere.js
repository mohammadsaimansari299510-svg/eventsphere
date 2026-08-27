/**
 * EventSphere — Main JavaScript
 * Premium interactions, animations, and UI enhancements
 */

document.addEventListener('DOMContentLoaded', function () {

  /* ============================================================
     1. NAVBAR — Scroll Effect + Hamburger Mobile Menu
     ============================================================ */
  const navbar = document.querySelector('.navbar');
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');

  // Navbar scroll effect
  if (navbar) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 20) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // Hamburger toggle
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      const isOpen = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close on clicking outside / overlay
    mobileMenu.addEventListener('click', function (e) {
      if (e.target === mobileMenu) closeMobileMenu();
    });

    // Close on escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeMobileMenu();
    });
  }

  function closeMobileMenu() {
    if (mobileMenu) mobileMenu.classList.remove('open');
    if (hamburger) hamburger.classList.remove('open');
    document.body.style.overflow = '';
  }

  // Close mobile menu on nav link click
  document.querySelectorAll('.mobile-nav-links .nav-link').forEach(link => {
    link.addEventListener('click', closeMobileMenu);
  });

  /* ============================================================
     2. PORTAL SIDEBAR — Mobile Toggle
     ============================================================ */
  const sidebarToggle = document.getElementById('sidebarToggle');
  const portalSidebar = document.getElementById('portalSidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');

  function openSidebar() {
    if (portalSidebar) portalSidebar.classList.add('open');
    if (sidebarOverlay) sidebarOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    if (portalSidebar) portalSidebar.classList.remove('open');
    if (sidebarOverlay) sidebarOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', openSidebar);
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }

  /* ============================================================
     3. AUTO-DISMISS ALERTS
     ============================================================ */
  document.querySelectorAll('.alert-auto-dismiss').forEach(function (alert) {
    setTimeout(function () {
      alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      alert.style.opacity = '0';
      alert.style.transform = 'translateY(-10px)';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });

  document.querySelectorAll('.alert-close').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const alert = this.closest('.alert');
      if (alert) {
        alert.style.transition = 'opacity 0.3s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
      }
    });
  });

  /* ============================================================
     4. MODAL SYSTEM
     ============================================================ */
  // Open
  document.querySelectorAll('[data-modal-target]').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      const modalId = this.getAttribute('data-modal-target');
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  // Close via [data-modal-close]
  document.querySelectorAll('[data-modal-close]').forEach(function (closeBtn) {
    closeBtn.addEventListener('click', function () {
      const modal = this.closest('.modal-backdrop');
      if (modal) closeModal(modal);
    });
  });

  // Close on backdrop click
  document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
    backdrop.addEventListener('click', function (e) {
      if (e.target === this) closeModal(this);
    });
  });

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-backdrop.show').forEach(closeModal);
    }
  });

  function closeModal(modal) {
    modal.classList.remove('show');
    document.body.style.overflow = '';
  }

  /* ============================================================
     5. FAQ ACCORDION
     ============================================================ */
  document.querySelectorAll('.accordion-trigger').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      const item = this.closest('.accordion-item');
      const body = item.querySelector('.accordion-body');
      const content = item.querySelector('.accordion-content');
      const isOpen = item.classList.contains('open');

      // Close all
      document.querySelectorAll('.accordion-item.open').forEach(function (openItem) {
        if (openItem !== item) {
          const openBody = openItem.querySelector('.accordion-body');
          openItem.classList.remove('open');
          openBody.style.height = '0';
        }
      });

      // Toggle current
      if (isOpen) {
        item.classList.remove('open');
        body.style.height = '0';
      } else {
        item.classList.add('open');
        body.style.height = content.scrollHeight + 'px';
      }
    });
  });

  /* ============================================================
     6. GALLERY LIGHTBOX
     ============================================================ */
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const lightboxVideo = document.getElementById('lightboxVideo');
  const lightboxClose = document.getElementById('lightboxClose');

  document.querySelectorAll('[data-lightbox]').forEach(function (item) {
    item.addEventListener('click', function () {
      const src = this.getAttribute('data-lightbox');
      const type = this.getAttribute('data-type') || 'image';

      if (!lightbox) return;

      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';

      if (type === 'video' && lightboxVideo) {
        lightboxVideo.src = src;
        lightboxVideo.style.display = 'block';
        if (lightboxImg) lightboxImg.style.display = 'none';
      } else if (lightboxImg) {
        lightboxImg.src = src;
        lightboxImg.style.display = 'block';
        if (lightboxVideo) { lightboxVideo.src = ''; lightboxVideo.style.display = 'none'; }
      }
    });
  });

  function closeLightbox() {
    if (lightbox) {
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
      if (lightboxVideo) lightboxVideo.src = '';
    }
  }

  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  if (lightbox) {
    lightbox.addEventListener('click', function (e) {
      if (e.target === this) closeLightbox();
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && lightbox && lightbox.classList.contains('open')) {
      closeLightbox();
    }
  });

  /* ============================================================
     7. STAR RATING INTERACTIVITY
     ============================================================ */
  document.querySelectorAll('.star-rating-input').forEach(function (container) {
    const stars = container.querySelectorAll('.star-icon');
    const hiddenInput = container.querySelector('input[type="hidden"]');

    stars.forEach(function (star, index) {
      star.addEventListener('click', function () {
        const rating = index + 1;
        if (hiddenInput) hiddenInput.value = rating;
        updateStars(stars, rating);
      });

      star.addEventListener('mouseenter', function () {
        updateStars(stars, index + 1);
      });
    });

    container.addEventListener('mouseleave', function () {
      const current = hiddenInput ? parseInt(hiddenInput.value) || 0 : 0;
      updateStars(stars, current);
    });
  });

  function updateStars(stars, rating) {
    stars.forEach((s, idx) => {
      if (idx < rating) {
        s.classList.add('active');
        s.style.color = '#F59E0B';
      } else {
        s.classList.remove('active');
        s.style.color = '';
      }
    });
  }

  /* ============================================================
     8. SCROLL REVEAL ANIMATION
     ============================================================ */
  const revealEls = document.querySelectorAll('.reveal');

  if (revealEls.length > 0) {
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

    revealEls.forEach(el => observer.observe(el));
  }

  /* ============================================================
     9. COUNTER ANIMATION (count-up)
     ============================================================ */
  function animateCounter(el, target, duration) {
    const start = 0;
    const startTime = performance.now();
    const suffix = el.getAttribute('data-suffix') || '';

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      // Ease out cubic
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.round(start + (target - start) * eased);
      el.textContent = current.toLocaleString() + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }

    requestAnimationFrame(update);
  }

  const counterEls = document.querySelectorAll('[data-counter]');
  if (counterEls.length > 0) {
    const counterObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          const el = entry.target;
          const target = parseInt(el.getAttribute('data-counter'), 10);
          animateCounter(el, target, 1800);
          counterObserver.unobserve(el);
        }
      });
    }, { threshold: 0.5 });

    counterEls.forEach(el => counterObserver.observe(el));
  }

  /* ============================================================
     10. PASSWORD VISIBILITY TOGGLE
     ============================================================ */
  document.querySelectorAll('.input-group-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const input = this.closest('.input-group').querySelector('input');
      if (!input) return;
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      const icon = this.querySelector('i');
      if (icon) {
        icon.className = isPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
      }
    });
  });

  /* ============================================================
     11. GALLERY FILTER
     ============================================================ */
  document.querySelectorAll('.gallery-filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.gallery-filter-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const filter = this.getAttribute('data-filter');

      document.querySelectorAll('.gallery-item[data-type]').forEach(function (item) {
        if (filter === 'all' || item.getAttribute('data-type') === filter) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

  /* ============================================================
     12. SOCIAL SHARE (keep existing)
     ============================================================ */
  // window.shareEvent is defined below

  /* ============================================================
     13. FORM SUBMIT LOADING STATE
     ============================================================ */
  document.querySelectorAll('form[data-loading]').forEach(function (form) {
    form.addEventListener('submit', function () {
      const submitBtn = this.querySelector('[type="submit"]');
      if (submitBtn) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
      }
    });
  });

  /* ============================================================
     14. STAGGERED CARD ANIMATION
     ============================================================ */
  const staggerContainers = document.querySelectorAll('[data-stagger]');
  staggerContainers.forEach(function (container) {
    const children = container.children;
    Array.from(children).forEach(function (child, i) {
      child.style.animationDelay = (i * 0.08) + 's';
      child.classList.add('reveal');
    });
  });

  /* ============================================================
     15. CAPACITY BAR COLOR LOGIC
     ============================================================ */
  document.querySelectorAll('.progress-bar-fill').forEach(function (bar) {
    const pct = parseFloat(bar.style.width);
    if (pct >= 90) bar.classList.add('danger');
    else if (pct >= 70) bar.classList.add('warning');
  });

  console.log('EventSphere Premium JS loaded ✓');
});

/* ============================================================
   SOCIAL SHARE — Global Helper
   ============================================================ */
function shareEvent(platform, title, url, hashtags) {
  const encodedTitle = encodeURIComponent(title);
  const encodedUrl   = encodeURIComponent(url);
  const encodedTags  = encodeURIComponent(hashtags || 'EventSphere,CollegeEvents');

  let shareUrl = '';

  if      (platform === 'whatsapp') shareUrl = `https://api.whatsapp.com/send?text=${encodedTitle}%20${encodedUrl}`;
  else if (platform === 'twitter')  shareUrl = `https://twitter.com/intent/tweet?text=${encodedTitle}&url=${encodedUrl}&hashtags=${encodedTags}`;
  else if (platform === 'facebook') shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
  else if (platform === 'linkedin') shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
  else if (platform === 'email')    shareUrl = `mailto:?subject=${encodedTitle}&body=Check%20out%20this%20event:%20${encodedUrl}`;

  if (shareUrl) window.open(shareUrl, '_blank', 'width=600,height=450');
}
