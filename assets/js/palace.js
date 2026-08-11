/* The Royal Palace of Benin — interactions */
(function () {
  "use strict";

  /* Sticky navigation state */
  var nav = document.querySelector(".nav");
  function navState() {
    if (!nav) return;
    var solid = window.scrollY > 40 || document.body.classList.contains("menu-open");
    nav.classList.toggle("nav--solid", solid);
    nav.classList.toggle("nav--float", !solid);
  }
  navState();
  window.addEventListener("scroll", navState, { passive: true });

  /* Mobile drawer */
  var burger = document.querySelector(".burger");
  if (burger) {
    burger.addEventListener("click", function () {
      var open = document.body.classList.toggle("menu-open");
      burger.setAttribute("aria-expanded", open ? "true" : "false");
      document.body.style.overflow = open ? "hidden" : "";
      navState();
    });
  }
  document.querySelectorAll(".drawer a").forEach(function (a) {
    a.addEventListener("click", function () {
      document.body.classList.remove("menu-open");
      document.body.style.overflow = "";
      navState();
    });
  });

  /* Scroll reveals */
  var reveals = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && reveals.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add("in"); io.unobserve(e.target); }
      });
    }, { rootMargin: "0px 0px -8% 0px", threshold: 0.12 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add("in"); });
  }

  /* Gentle hero parallax */
  var heroImg = document.querySelector(".hero__media img");
  if (heroImg && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    var raf = null;
    window.addEventListener("scroll", function () {
      if (raf) return;
      raf = requestAnimationFrame(function () {
        var y = Math.min(window.scrollY, 900);
        heroImg.style.transform = "translate3d(0," + (y * 0.16) + "px,0) scale(1.04)";
        raf = null;
      });
    }, { passive: true });
  }

  /* Anniversary countdown — 3 October 2026, opening day of the celebration */
  var cd = document.querySelector("[data-countdown]");
  if (cd) {
    var target = new Date(cd.getAttribute("data-countdown")).getTime();
    var cells = cd.querySelectorAll("b");
    var tick = function () {
      var diff = target - Date.now();
      if (diff < 0) diff = 0;
      var s = Math.floor(diff / 1000);
      var vals = [Math.floor(s / 86400), Math.floor((s % 86400) / 3600), Math.floor((s % 3600) / 60), s % 60];
      cells.forEach(function (c, i) { c.textContent = String(vals[i]).padStart(2, "0"); });
    };
    tick();
    setInterval(tick, 1000);
  }

  /* Animated counters (only for real figures present in markup) */
  var counters = document.querySelectorAll("[data-count]");
  if (counters.length && "IntersectionObserver" in window) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        var el = e.target;
        var end = parseFloat(el.getAttribute("data-count"));
        var suffix = el.getAttribute("data-suffix") || "";
        var t0 = performance.now();
        var dur = 1400;
        (function step(now) {
          var p = Math.min((now - t0) / dur, 1);
          var eased = 1 - Math.pow(1 - p, 3);
          el.textContent = Math.round(end * eased).toLocaleString() + suffix;
          if (p < 1) requestAnimationFrame(step);
        })(t0);
        cio.unobserve(el);
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { cio.observe(el); });
  }

  /* Accordions */
  document.querySelectorAll(".acc__btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var item = btn.closest(".acc__item");
      var panel = item.querySelector(".acc__panel");
      var open = item.classList.toggle("open");
      btn.setAttribute("aria-expanded", open ? "true" : "false");
      panel.style.maxHeight = open ? panel.scrollHeight + "px" : "0px";
    });
  });

  /* Forms — submitted via fetch() to contact-submit.php, same visual acknowledgement as before */
  document.querySelectorAll("form[data-ack]").forEach(function (form) {
    form.addEventListener("submit", function (ev) {
      ev.preventDefault();
      var ok = form.querySelector(".form__ok");
      var err = form.querySelector(".form__error");
      var btn = form.querySelector("button[type=submit]");
      if (err) { err.classList.remove("show"); err.textContent = ""; }
      if (btn) { btn.disabled = true; }

      fetch("contact-submit.php", {
        method: "POST",
        body: new FormData(form),
        headers: { "X-Requested-With": "XMLHttpRequest" }
      })
        .then(function (res) { return res.json().catch(function () { return { success: false, message: "Unexpected server response." }; }); })
        .then(function (data) {
          if (data.success) {
            if (ok) ok.classList.add("show");
            form.reset();
          } else if (err) {
            err.textContent = data.message || "Something went wrong. Please try again.";
            err.classList.add("show");
          }
        })
        .catch(function () {
          if (err) {
            err.textContent = "Could not reach the server. Please check your connection and try again.";
            err.classList.add("show");
          }
        })
        .finally(function () {
          if (btn) { btn.disabled = false; }
        });
    });
  });

  /* Gallery category filter */
  var filterBar = document.querySelector("[data-gallery-filters]");
  if (filterBar) {
    var figures = document.querySelectorAll("[data-lightbox-gallery] > figure");
    filterBar.addEventListener("click", function (ev) {
      var btn = ev.target.closest("[data-filter]");
      if (!btn) return;
      filterBar.querySelectorAll(".btn").forEach(function (b) { b.classList.remove("is-active"); });
      btn.classList.add("is-active");
      var filter = btn.getAttribute("data-filter");
      figures.forEach(function (fig) {
        var match = filter === "*" || fig.getAttribute("data-category") === filter;
        if (match) fig.removeAttribute("hidden"); else fig.setAttribute("hidden", "");
      });
    });
  }

  /* Gallery lightbox */
  var lightbox = document.querySelector("[data-lightbox-root]");
  if (lightbox) {
    var lbImg = lightbox.querySelector("[data-lightbox-image]");
    var lbCaption = lightbox.querySelector("[data-lightbox-figcaption]");
    var openLightbox = function (src, caption) {
      lbImg.setAttribute("src", src);
      lbCaption.textContent = caption || "";
      lightbox.removeAttribute("hidden");
      document.body.style.overflow = "hidden";
    };
    var closeLightbox = function () {
      lightbox.setAttribute("hidden", "");
      lbImg.setAttribute("src", "");
      document.body.style.overflow = "";
    };
    document.querySelectorAll("[data-lightbox-src]").forEach(function (img) {
      img.addEventListener("click", function () {
        openLightbox(img.getAttribute("data-lightbox-src"), img.getAttribute("data-lightbox-caption"));
      });
    });
    lightbox.addEventListener("click", function (ev) {
      if (ev.target === lightbox || ev.target.closest("[data-lightbox-close]")) closeLightbox();
    });
    document.addEventListener("keydown", function (ev) {
      if (ev.key === "Escape") closeLightbox();
    });
  }
})();
