/* ==========================================================================
   MARIGOLD SIGNATURE — page-specific rendering
   ========================================================================== */

(function () {
  "use strict";

  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }
  function qs(name) { return new URLSearchParams(window.location.search).get(name); }

  const M = window.Marigold;
  const naira = M.naira;
  const imgSrc = M.imgSrc;
  const card = M.productCard;

  function slugCat(id) {
    const c = CATEGORIES.find((x) => x.id === id);
    return c ? c.label : id;
  }

  function reveal() {
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add("in"); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    $$(".reveal:not(.in)").forEach((el) => io.observe(el));
  }

  /* ---------------------------------- HOME ---------------------------------- */
  function renderHome() {
    const catGrid = $("#catGrid");
    if (catGrid) {
      // Category tiles derive from the live catalogue so admin changes reflect.
      const defs = {
        "corporate-gifts": { img: "1503602642458-232111445657", label: "Corporate Gifts", sub: "Curation" },
        "apparel": { img: "1521572163474-6864f9cf17ab", label: "Branded Apparel", sub: "Uniform & Wear" },
        "desk-office": { img: "1531346878377-a5be20888e57", label: "Desk & Office", sub: "Everyday Carry" },
        "drinkware": { img: "1602143407151-7111542de6e8", label: "Drinkware", sub: "On Brand" },
        "tech-gadgets": { img: "1505740420928-5e560c06d30e", label: "Tech & Gadgets", sub: "High Value" },
        "event": { img: "1553062407-98eeb64c6a62", label: "Event Essentials", sub: "Activations" },
      };
      const tiles = CATEGORIES.slice(0, 6).map(function (c) {
        const fallback = defs[c.id] || {};
        const first = PRODUCTS.find((p) => p.cat === c.id);
        return {
          k: c.id,
          label: c.label,
          sub: fallback.sub || "Collection",
          img: first && first.img ? first.img : (fallback.img || ""),
        };
      });
      catGrid.innerHTML = tiles.map(function (d) {
        return (
          '<a class="cat-card reveal" href="' + appUrl('/shop?cat=') + d.k + '">' +
          '<img src="' + imgSrc(d.img, 500) + '" alt="' + d.label + '" loading="lazy">' +
          '<span class="cat-label"><small>' + d.sub + "</small><span>" + d.label + "</span></span>" +
          "</a>"
        );
      }).join("");
    }

    const feat = $("#featuredGrid");
    if (feat) {
      const ranked = PRODUCTS.slice().sort(function (a, b) {
        const r = (p) => (p.badge ? 0 : 1);
        return r(a) - r(b);
      });
      feat.innerHTML = ranked.slice(0, 4).map(card).join("");
    }

    const testi = $("#testiGrid");
    if (testi) {
      const picks = [TESTIMONIALS[0], TESTIMONIALS[1], TESTIMONIALS[2]];
      testi.innerHTML = picks.map(function (t) {
        return (
          '<div class="testi-card reveal">' +
          '<div class="stars">★★★★★</div>' +
          "<p>" + t.quote + "</p>" +
          '<div class="who"><span class="av">' + t.initial + "</span><span><strong>" + t.name + "</strong><small>" + t.role + "</small></span></div>" +
          "</div>"
        );
      }).join("");
    }

    reveal();
  }

  /* ---------------------------------- SHOP ---------------------------------- */
  function renderShop() {
    const grid = $("#shopGrid");
    if (!grid) return;

    const catParam = qs("cat");
    let activeCat = catParam || "all";
    if (!CATEGORIES.some((c) => c.id === activeCat)) activeCat = "all";

    const searchInput = $("#sideSearch") || $("#shopSearch");
    const sortSel = $("#shopSort");
    const countEl = $("#shopCount");
    const priceMin = $("#priceMin");
    const priceMax = $("#priceMax");
    const priceClear = $("#priceClear");

    let query = "";
    let minP = 0;
    let maxP = Infinity;
    let page = 1;
    const PER_PAGE = 8;
    const pagEl = $("#shopPagination");

    const featBox = $("#sideFeatured");
    if (featBox) {
      const ranked = PRODUCTS.slice().sort(function (a, b) {
        const r = (p) => (p.badge ? 0 : 1);
        return r(a) - r(b);
      });
      featBox.innerHTML = ranked.slice(0, 3).map(function (p) {
        return (
          '<a class="feat-item" href="' + appUrl('/product?id=') + p.id + '">' +
          '<div class="feat-thumb"><img src="' + imgSrc(p.img, 160) + '" alt="' + p.name + '" loading="lazy"></div>' +
          '<div class="feat-info"><span class="feat-name">' + p.name + '</span><span class="feat-price">' + naira(p.price) + "</span></div>" +
          "</a>"
        );
      }).join("");
    }

    function renderPagination(total, pages) {
      if (!pagEl) return;
      if (pages <= 1) { pagEl.innerHTML = ""; return; }
      let html =
        '<button class="pg-btn" data-pg="' + (page - 1) + '"' + (page === 1 ? " disabled" : "") + ' aria-label="Previous page">&larr; Prev</button>';
      for (let i = 1; i <= pages; i++) {
        html += '<button class="pg-btn' + (i === page ? " active" : "") + '" data-pg="' + i + '">' + i + "</button>";
      }
      html +=
        '<button class="pg-btn" data-pg="' + (page + 1) + '"' + (page === pages ? " disabled" : "") + ' aria-label="Next page">Next &rarr;</button>';
      pagEl.innerHTML = html;
    }

    function apply() {
      let list = PRODUCTS.slice();
      if (activeCat !== "all") list = list.filter((p) => p.cat === activeCat);
      if (query) {
        const q = query.toLowerCase();
        list = list.filter(function (p) {
          return (p.name + " " + p.short + " " + slugCat(p.cat)).toLowerCase().indexOf(q) !== -1;
        });
      }
      if (minP > 0 || maxP !== Infinity) {
        list = list.filter((p) => p.price >= minP && p.price <= maxP);
      }
      if (sortSel.value === "price-asc") list.sort((a, b) => a.price - b.price);
      if (sortSel.value === "price-desc") list.sort((a, b) => b.price - a.price);
      if (sortSel.value === "name") list.sort((a, b) => a.name.localeCompare(b.name));

      const total = list.length;
      const pages = Math.max(1, Math.ceil(total / PER_PAGE));
      if (page > pages) page = pages;

      countEl.textContent = total + " product" + (total === 1 ? "" : "s");

      if (!total) {
        grid.innerHTML =
          '<div class="shop-empty" style="grid-column:1/-1">' +
          "<h3>No matches found</h3><p>Try a different category, price range or search term.</p>" +
          "</div>";
        renderPagination(0, 0);
        return;
      }
      const start = (page - 1) * PER_PAGE;
      grid.innerHTML = list.slice(start, start + PER_PAGE).map(card).join("");
      renderPagination(total, pages);
      reveal();
    }

    if (pagEl) {
      pagEl.addEventListener("click", function (e) {
        const b = e.target.closest("[data-pg]");
        if (!b || b.disabled) return;
        const pg = parseInt(b.dataset.pg, 10);
        if (pg >= 1) {
          page = pg;
          apply();
          const head = $(".grid-head");
          const top = head ? head.getBoundingClientRect().top + window.scrollY - 90 : 0;
          window.scrollTo({ top: top, behavior: "smooth" });
        }
      });
    }

    if (searchInput) {
      searchInput.addEventListener("input", function () { query = this.value.trim(); page = 1; apply(); });
    }
    sortSel.addEventListener("change", function () { page = 1; apply(); });
    if (priceMin) priceMin.addEventListener("input", function () { minP = parseInt(this.value, 10) || 0; page = 1; apply(); });
    if (priceMax) priceMax.addEventListener("input", function () { maxP = parseInt(this.value, 10) || Infinity; page = 1; apply(); });
    if (priceClear) priceClear.addEventListener("click", function () {
      minP = 0; maxP = Infinity;
      priceMin.value = ""; priceMax.value = "";
      page = 1;
      apply();
    });

    apply();
  }

  /* -------------------------------- PRODUCT -------------------------------- */
  function renderProduct() {
    const root = $("#productRoot");
    if (!root) return;
    const id = qs("id") || (window.location.pathname.split("/").filter(Boolean).pop()) || "";
    const p = PRODUCTS.find((x) => x.id === id);
    if (!p) {
      root.innerHTML = '<div class="shop-empty"><h3>Product not found</h3><p><a class="btn btn-gold" style="margin-top:16px" href="' + appUrl('/shop') + '">Back to shop</a></p></div>';
      return;
    }

    document.title = p.name + " — Marigold Signature";
    document.querySelector('meta[name="description"]') &&
      document.querySelector('meta[name="description"]').setAttribute("content", p.desc);

    const extraImgs = {
      "signature-gift-box": ["1503602642458-232111445657", "1549465220-1a8b9238cd48"],
      "executive-watch": ["1578683010236-d716f9a3f461", "1523275335684-37898b6baf30"],
      "studio-headphones": ["1505740420928-5e560c06d30e", "1583394838336-acd977736f90"],
    };
    const thumbs = (extraImgs[p.id] || []).filter((t, i, a) => a.indexOf(t) === i).slice(0, 3);
    const galleryImgs = [p.img].concat(thumbs.filter((t) => t !== p.img));

    const metaRows = Object.keys(p.specs || {}).map(function (k) {
      return "<li><span>" + k + "</span><span>" + p.specs[k] + "</span></li>";
    }).join("");

    const perks =
      '<div class="perk"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Nationwide delivery</div>' +
      '<div class="perk"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 5v6c0 5.25 3.4 10.24 8 11 4.6-.76 8-5.75 8-11V5z"/><path d="m9 12 2 2 4-4"/></svg>Quality-checked units</div>' +
      '<div class="perk"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>Bespoke branding</div>';

    root.innerHTML =
      '<div class="pdetail">' +
      '<div class="gallery reveal">' +
      '<div class="main-img"><img id="mainImg" src="' + imgSrc(p.img, 1000) + '" alt="' + p.name + '"></div>' +
      (galleryImgs.length > 1
        ? '<div class="thumbs">' + galleryImgs.map(function (t, i) {
            return '<img src="' + imgSrc(t, 200) + '" data-src="' + imgSrc(t, 1000) + '" class="' + (i === 0 ? "active" : "") + '" alt="Thumbnail">';
          }).join("") + "</div>"
        : "") +
      "</div>" +
      '<div class="pinfo reveal d1">' +
      '<div class="crumbs"><a href="' + appUrl('/') + '">Home</a><span>/</span><a href="' + appUrl('/shop') + '">Shop</a><span>/</span><a href="' + appUrl('/shop?cat=') + p.cat + '">' + slugCat(p.cat) + "</a></div>" +
      '<span class="eyebrow">' + (p.badge ? p.badge + " · " : "") + slugCat(p.cat) + "</span>" +
      '<h1 class="display h2">' + p.name + "</h1>" +
      '<div class="p-price">' + naira(p.price) + " <small>per unit · bulk pricing available</small></div>" +
      '<p class="p-desc">' + p.desc + "</p>" +
      '<ul class="p-meta">' + metaRows + "</ul>" +
      '<div class="qty-row">' +
      '<div class="opt-note" style="width:100%;margin-bottom:-2px">Quantity</div>' +
      '<div class="qty">' +
      '<button id="qtyMinus" type="button">−</button><input id="qtyInput" value="1" inputmode="numeric" aria-label="Quantity"><button id="qtyPlus" type="button">+</button>' +
      "</div>" +
      "</div>" +
      '<div style="margin-bottom:20px">' +
      '<div class="opt-note">Personalisation note (optional)</div>' +
      '<textarea class="field" id="personalNote" placeholder="e.g. Engrave with company logo — debossed, gold foil"></textarea>' +
      "</div>" +
      '<button class="btn btn-gold btn-lg btn-block" id="addBtn" data-act="add" data-id="' + p.id + '">Add to Cart — <span id="addBtnTotal">' + naira(p.price) + "</span></button>" +
      '<a href="' + appUrl('/contact') + '" class="btn btn-ghost btn-lg btn-block" style="margin-top:12px">Request Bulk Quote</a>' +
      '<div class="perk-row">' + perks + "</div>" +
      "</div>" +
      "</div>" +
      '<section class="related">' +
      '<div class="related-head"><div><span class="eyebrow">You may also like</span><h2 class="display h2">Related gifts</h2></div>' +
      '<a class="btn btn-ghost" href="' + appUrl('/shop') + '">View all <span class="arr">→</span></a></div>' +
      '<div class="pgrid" id="relatedGrid"></div>' +
      "</section>";

    /* gallery thumb swapping */
    $$(".thumbs img", root).forEach((t) => t.addEventListener("click", function () {
      $("#mainImg").src = this.dataset.src;
      $$(".thumbs img", root).forEach((x) => x.classList.remove("active"));
      this.classList.add("active");
    }));

    /* qty stepper + live price */
    const qtyInput = $("#qtyInput");
    const addBtnTotal = $("#addBtnTotal");
    function syncPrice() {
      if (!addBtnTotal) return;
      const q = Math.max(1, parseInt(qtyInput.value, 10) || 1);
      qtyInput.value = q;
      addBtnTotal.textContent = naira(p.price * q);
    }
    $$("#qtyMinus,#qtyPlus").forEach((b) => b && b.addEventListener("click", function () {
      let v = parseInt(qtyInput.value, 10) || 1;
      v = this.id === "qtyPlus" ? v + 1 : Math.max(1, v - 1);
      qtyInput.value = v;
      syncPrice();
    }));
    qtyInput.addEventListener("input", syncPrice);

    /* related */
    const related = PRODUCTS.filter((x) => x.cat === p.cat && x.id !== p.id).slice(0, 4);
    const filler = PRODUCTS.filter((x) => x.id !== p.id).filter((x) => !related.some((r) => r.id === x.id));
    const picks = related.concat(filler).slice(0, 4);
    $("#relatedGrid").innerHTML = picks.map(card).join("");

    reveal();
  }

  /* -------------------------------- CONTACT -------------------------------- */
  function initContact() {
    const form = $("#contactForm");
    if (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }
        const csrf = document.querySelector('#checkoutForm input[name="csrf_token"]');
        const payload = {
          name: $("#ctName").value,
          company: $("#ctCompany").value,
          email: $("#ctEmail").value,
          phone: $("#ctPhone").value,
          subject: $("#ctService").value,
          message: $("#ctMsg").value,
        };
        const btn = form.querySelector('button[type="submit"]');
        if (btn) { btn.disabled = true; }
        fetch(appUrl("/contact"), {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-Token": csrf ? csrf.value : "" },
          body: JSON.stringify(payload),
        })
          .then(function (res) { return res.json().catch(function () { return {}; }); })
          .then(function (body) {
            if (body.ok) {
              M.toast(body.message || "Message sent — our team will respond within 1 business day.");
              form.reset();
            } else {
              let msg = "We could not send your message. Please try again.";
              const err = body && body.error;
              if (typeof err === "string") {
                msg = err;
              } else if (err && typeof err === "object") {
                const first = Object.values(err)[0];
                msg = Array.isArray(first) ? first[0] : String(first);
              }
              M.toast(msg);
            }
          })
          .catch(function () { M.toast("Network error. Please try again."); })
          .finally(function () { if (btn) { btn.disabled = false; } });
      });
    }
  }

  /* ---------------------------------- BLOG ----------------------------------- */
  function postCard(p) {
    return (
      '<a class="blog-card reveal" href="' + appUrl('/blog/') + p.id + '">' +
      '<div class="bc-img"><img src="' + imgSrc(p.img, 800) + '" alt="' + p.title + '"></div>' +
      '<div class="bc-body">' +
      '<div class="bc-meta"><span class="cat">' + p.category + '</span><span class="dot"></span><span>' + p.date + "</span></div>" +
      "<h3>" + p.title + "</h3>" +
      "<p>" + p.excerpt + "</p>" +
      '<span class="ct-link">Read article <span class="arr">&rarr;</span></span>' +
      "</div>" +
      "</a>"
    );
  }

  function renderBlog() {
    const featured = $("#featuredRoot");
    const grid = $("#blogGrid");
    if (!grid) return;
    if (featured && POSTS.length) {
      const p = POSTS[0];
      featured.innerHTML =
        '<a class="blog-featured reveal" href="' + appUrl('/blog/') + p.id + '">' +
        '<div class="bf-img"><img src="' + imgSrc(p.img, 1200) + '" alt="' + p.title + '"></div>' +
        '<div class="bf-body">' +
        '<div class="bc-meta"><span class="cat">' + p.category + '</span><span class="dot"></span><span>' + p.date + " · " + p.readTime + "</span></div>" +
        '<h2 class="display" style="font-size:clamp(26px,3vw,34px);line-height:1.25;margin-bottom:14px">' + p.title + "</h2>" +
        '<p style="font-size:15px;color:var(--muted);margin-bottom:20px">' + p.excerpt + "</p>" +
        '<span class="ct-link">Read the article <span class="arr">&rarr;</span></span>' +
        "</div>" +
        "</a>";
    }
    grid.innerHTML = POSTS.slice(featured ? 1 : 0).map(postCard).join("");
    reveal();
  }

  function renderPost() {
    const hero = $("#postHero");
    const body = $("#postBody");
    const related = $("#relatedGrid");
    if (!hero || !body) return;
    const id = qs("id");
    const p = POSTS.find((x) => x.id === id);
    if (!p) {
      hero.innerHTML = '<div class="shop-empty"><h3>Article not found</h3><p><a class="btn btn-gold" style="margin-top:16px" href="' + appUrl('/blog') + '">Back to blog</a></p></div>';
      return;
    }
    document.title = p.title + " — Marigold Signature";
    hero.innerHTML =
      '<div class="crumbs"><a href="' + appUrl('/') + '">Home</a><span>/</span><a href="' + appUrl('/blog') + '">Blog</a><span>/</span><span>' + p.category + "</span></div>" +
      '<span class="eyebrow reveal">' + p.category + "</span>" +
      '<h1 class="display h1 reveal">' + p.title + "</h1>" +
      '<div class="post-meta reveal"><span>' + p.date + '</span><span class="sep">•</span><span>' + p.readTime + '</span><span class="sep">•</span><span>Marigold Studio</span></div>';
    const content = p.body.map(function (blk) {
      if (blk.h2) return "<h2>" + blk.h2 + "</h2>";
      if (blk.pull) return '<div class="pull">' + blk.pull + "</div>";
      return "<p>" + blk.p + "</p>";
    }).join("");
    body.innerHTML =
      content +
      '<div class="blog-cta">' +
      "<h3>Planning something special?</h3>" +
      "<p>Talk to the Marigold team about your next gifting programme or event.</p>" +
      '<a class="btn btn-gold btn-lg" href="' + appUrl('/contact') + '">Get a Quote <span class="arr">&rarr;</span></a>' +
      "</div>";
    if (related) {
      const same = POSTS.filter((x) => x.cat === p.cat && x.id !== p.id).slice(0, 3);
      const fill = POSTS.filter((x) => x.id !== p.id && !same.some((s) => s.id === x.id)).slice(0, 3 - same.length);
      related.innerHTML = same.concat(fill).map(postCard).join("");
    }
    reveal();
  }

  /* ----------------------------- image slider ------------------------------- */
  function initSlider() {
    const el = $(".meeting-slider");
    if (!el || typeof window.Swiper === "undefined") return;
    new window.Swiper(el, {
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 1.25,
      spaceBetween: 16,
      loop: true,
      speed: 600,
      autoplay: { delay: 3200, disableOnInteraction: false, pauseOnMouseEnter: true },
      pagination: { el: ".ms-pagination", clickable: true },
      navigation: { nextEl: ".ms-next", prevEl: ".ms-prev" },
      breakpoints: {
        860: { slidesPerView: 2.2, spaceBetween: 24 },
      },
    });
  }

  /* ---------------------------------- INIT ---------------------------------- */
  document.addEventListener("DOMContentLoaded", function () {
    const page = document.body.dataset.page;
    if (page === "home") renderHome();
    else if (page === "shop") renderShop();
    else if (page === "product") renderProduct();
    else if (page === "contact") initContact();
    else if (page === "blog") renderBlog();
    else if (page === "blog-post") renderPost();
    initSlider();
  });
})();
