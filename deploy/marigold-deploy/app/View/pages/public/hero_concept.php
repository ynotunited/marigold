<?php
// app/View/pages/public/hero_concept.php
// Standalone "Hero Concept" preview — a cinematic 3D WebGL product turntable.
// This page is NOT part of the live site; it renders a complete standalone
// document so the hero can be auditioned in isolation and iterated on quickly.
$conceptProducts = $concept_products ?? [];
$heroProducts = [];
foreach ($conceptProducts as $cp) {
    if (empty($cp['img']) || empty($cp['name'])) {
        continue;
    }
    $heroProducts[] = [
        'id'    => (string) $cp['id'],
        'name'  => (string) $cp['name'],
        'cat'   => (string) ($cp['cat'] ?? ''),
        'price' => round((float) ($cp['price'] ?? 0), 0),
        'img'   => (string) app_url($cp['img']),
    ];
}
if (count($heroProducts) > 12) {
    $heroProducts = array_slice($heroProducts, 0, 12);
}
$heroJson = json_encode($heroProducts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Hero Concept &mdash; Marigold Signature</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,560;1,9..144,300;1,9..144,400;1,9..144,500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --ink: #0B0E0D;
        --ink-2: #101412;
        --ink-3: #161B18;
        --gold: #C89B3C;
        --gold-soft: #E9CE8A;
        --gold-line: rgba(200, 155, 60, 0.45);
        --ivory: #F3ECDC;
        --muted: rgba(243, 236, 220, 0.58);
        --ease: cubic-bezier(0.22, 1, 0.36, 1);
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { background: var(--ink); }
    body {
        font-family: "Manrope", system-ui, sans-serif;
        color: var(--ivory);
        background: var(--ink);
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
    }
    ::selection { background: var(--gold); color: var(--ink); }

    #stage {
        position: fixed; inset: 0; z-index: 0;
        background:
            radial-gradient(100% 80% at 78% 16%, rgba(200, 155, 60, 0.14), transparent 55%),
            radial-gradient(90% 70% at 8% 90%, rgba(24, 60, 44, 0.26), transparent 60%),
            linear-gradient(160deg, var(--ink) 0%, var(--ink-2) 55%, var(--ink-3) 100%);
    }
    #stage canvas { display: block; width: 100%; height: 100%; }

    .grain {
        position: fixed; inset: 0; z-index: 40; pointer-events: none; opacity: 0.5;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.28'/%3E%3C/svg%3E");
        mix-blend-mode: overlay;
    }
    .vignette {
        position: fixed; inset: 0; z-index: 30; pointer-events: none;
        background: radial-gradient(125% 100% at 50% 40%, transparent 50%, rgba(5, 7, 6, 0.7) 100%);
    }

    /* ---- top bar ---- */
    .top {
        position: fixed; top: 0; left: 0; right: 0; z-index: 50;
        display: flex; align-items: center; justify-content: space-between;
        padding: 26px clamp(20px, 4vw, 56px);
        pointer-events: none;
    }
    .brand {
        display: flex; align-items: center; gap: 14px; pointer-events: auto;
        text-decoration: none; color: var(--ivory);
    }
    .brand .mark {
        width: 38px; height: 38px; border-radius: 50%;
        border: 1px solid var(--gold-line);
        display: grid; place-items: center;
        font-family: "Fraunces", serif; font-size: 17px; font-weight: 500; color: var(--gold-soft);
        background: radial-gradient(circle at 32% 28%, rgba(233, 206, 138, 0.16), transparent 62%);
    }
    .brand .name { font-size: 12px; letter-spacing: 0.34em; text-transform: uppercase; font-weight: 700; }
    .brand .name small { display: block; color: var(--muted); font-weight: 500; letter-spacing: 0.22em; font-size: 9px; margin-top: 3px; }

    .top-right { display: flex; align-items: center; gap: 26px; pointer-events: auto; }
    .top-right a { color: var(--ivory); opacity: 0.82; text-decoration: none; font-size: 12.5px; font-weight: 600; transition: opacity 0.25s var(--ease); }
    .top-right a:hover { opacity: 1; }
    .preview-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 16px; border: 1px solid var(--gold-line); border-radius: 999px;
        color: var(--gold-soft); font-size: 11px; font-weight: 800; letter-spacing: 0.18em; text-transform: uppercase;
        background: rgba(11, 14, 13, 0.5); backdrop-filter: blur(8px);
    }
    .preview-pill i { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 12px var(--gold); }

    /* ---- hero copy (left) ---- */
    .hero-copy {
        position: relative; z-index: 20;
        min-height: 100dvh;
        display: flex; flex-direction: column; justify-content: center;
        padding: 120px clamp(20px, 4vw, 56px) 96px;
        max-width: 660px; margin: 0;
    }
    .eyebrow {
        display: inline-flex; align-items: center; gap: 14px;
        font-size: 11px; font-weight: 800; letter-spacing: 0.42em; text-transform: uppercase;
        color: var(--gold-soft); margin-bottom: 30px;
    }
    .eyebrow::before { content: ""; width: 60px; height: 1px; background: var(--gold-line); }
    h1 {
        font-family: "Fraunces", Georgia, serif;
        font-weight: 400; font-size: clamp(46px, 6vw, 104px); line-height: 0.96;
        letter-spacing: -0.015em; max-width: 14ch; text-wrap: balance;
    }
    h1 em {
        font-style: italic; font-weight: 300; color: var(--gold-soft);
        background: linear-gradient(100deg, var(--gold-soft) 20%, var(--gold) 80%);
        -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
    }
    .lede {
        margin-top: 30px; max-width: 42ch; color: var(--muted);
        font-size: clamp(15px, 1.4vw, 18px); line-height: 1.7; font-weight: 500;
    }
    .cta-row { display: flex; align-items: center; flex-wrap: wrap; gap: 18px; margin-top: 42px; }
    .btn {
        display: inline-flex; align-items: center; gap: 14px;
        min-height: 58px; padding: 0 30px; border-radius: 999px;
        text-decoration: none; font-size: 13px; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase;
        transition: transform 0.3s var(--ease), box-shadow 0.3s var(--ease), background 0.3s var(--ease), border-color 0.3s var(--ease);
    }
    .btn-gold { background: var(--gold); color: var(--ink); box-shadow: 0 18px 44px rgba(200, 155, 60, 0.28); }
    .btn-gold:hover { transform: translateY(-2px); background: var(--gold-soft); box-shadow: 0 24px 60px rgba(200, 155, 60, 0.34); }
    .btn-ghost { border: 1px solid rgba(243, 236, 220, 0.28); color: var(--ivory); background: rgba(11, 14, 13, 0.4); backdrop-filter: blur(8px); }
    .btn-ghost:hover { transform: translateY(-2px); border-color: var(--gold-line); color: var(--gold-soft); }
    .btn .arr { font-size: 18px; line-height: 0; }

    /* ---- bottom rail ---- */
    .rail {
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 45;
        display: flex; align-items: center; justify-content: space-between;
        gap: 20px; flex-wrap: wrap;
        padding: 22px clamp(20px, 4vw, 56px);
        border-top: 1px solid rgba(243, 236, 220, 0.12);
        background: linear-gradient(180deg, rgba(11, 14, 13, 0) 0%, rgba(11, 14, 13, 0.72) 55%);
        pointer-events: none;
    }
    .rail .hud { pointer-events: auto; }
    .rail-inline { display: flex; align-items: center; gap: 18px; }
    .rail .label { font-size: 10px; letter-spacing: 0.3em; text-transform: uppercase; color: var(--muted); font-weight: 700; }
    .rail .val { font-size: 13px; font-weight: 700; margin-top: 5px; color: var(--ivory); }
    .rail .val small { color: var(--muted); font-weight: 600; }
    .scroll-cue {
        display: flex; align-items: center; gap: 12px;
        font-size: 10px; letter-spacing: 0.3em; text-transform: uppercase; color: var(--muted); font-weight: 700;
    }
    .scroll-cue .line { width: 1px; height: 44px; background: var(--gold); opacity: 0.7; transform-origin: top; animation: cue 2.2s var(--ease) infinite; }
    @keyframes cue { 0% { transform: scaleY(0); } 50% { transform: scaleY(1); } 100% { transform: scaleY(0); transform-origin: bottom; } }

    @media (max-width: 900px) {
        .top-right a.lnk { display: none; }
        .hero-copy { max-width: 560px; }
    }
    @media (max-width: 760px) {
        .hero-copy { max-width: none; }
        h1 { max-width: 12ch; }
        .scroll-cue { display: none; }
    }
    @media (max-width: 480px) {
        h1 { font-size: clamp(42px, 13vw, 64px); }
        .cta-row .btn { width: 100%; justify-content: center; }
        .rail .hud:nth-child(2) { display: none; }
    }
</style>
</head>
<body>

    <div id="stage" aria-hidden="true"></div>
    <div class="vignette" aria-hidden="true"></div>
    <div class="grain" aria-hidden="true"></div>

    <header class="top">
        <a class="brand" href="<?= app_url('/') ?>">
            <span class="mark">M</span>
            <span class="name">Marigold Signature<small>Corporate Gifting</small></span>
        </a>
        <nav class="top-right">
            <span class="preview-pill"><i></i> Hero Concept</span>
            <a class="lnk" href="<?= app_url('/shop') ?>">Shop</a>
            <a class="lnk" href="<?= app_url('/quote-request') ?>">Request a Quote</a>
        </nav>
    </header>

    <main class="hero-copy">
        <div class="eyebrow">Est. 2011 &mdash; Lagos, Nigeria</div>
        <h1>Gifts that make your brand <em>stay remembered.</em></h1>
        <p class="lede">Premium merchandise, branded essentials and event gifts, curated for teams, clients and milestone moments &mdash; presented in a living 3D catalogue.</p>
        <div class="cta-row">
            <a class="btn btn-gold" href="<?= app_url('/shop') ?>">Explore the collection <span class="arr">&rarr;</span></a>
            <a class="btn btn-ghost" href="<?= app_url('/quote-request') ?>">Request a quote</a>
        </div>
    </main>

    <footer class="rail">
        <div class="hud rail-inline">
            <div>
                <div class="label">Featured</div>
                <div class="val" id="hudName">&mdash;</div>
            </div>
            <div style="width:1px;height:26px;background:rgba(243,236,220,0.16)"></div>
            <div>
                <div class="label">Price</div>
                <div class="val" id="hudPrice">&mdash;</div>
            </div>
            <div style="width:1px;height:26px;background:rgba(243,236,220,0.16)"></div>
            <div>
                <div class="label">Origin</div>
                <div class="val" id="hudOrigin">Nigeria</div>
            </div>
        </div>
        <div class="scroll-cue"><span class="line"></span> Scroll</div>
    </footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
(function () {
    "use strict";

    var PRODUCTS = <?= $heroJson ?>;

    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var container = document.getElementById("stage");
    if (!container || (typeof THREE === "undefined")) {
        document.body.style.minHeight = "100dvh";
        return;
    }

    var isNarrow = window.innerWidth < 860;

    /* ------------------------------------------------------------------ */
    /*  Scene / camera / renderer                                          */
    /* ------------------------------------------------------------------ */
    var scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x0B0E0D, 0.02);

    var camera = new THREE.PerspectiveCamera(42, container.clientWidth / container.clientHeight, 0.1, 120);
    var camBase = { x: isNarrow ? 3.6 : 1.6, y: isNarrow ? 0.4 : 0.7 };
    var lookBase = { x: isNarrow ? 8.5 : 5.6, y: 0.35, z: -0.6 };
    camera.position.set(camBase.x, camBase.y, 15);

    var renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.shadowMap.enabled = false;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;
    container.appendChild(renderer.domElement);

    /* ------------------------------------------------------------------ */
    /*  Lights                                                            */
    /* ------------------------------------------------------------------ */
    scene.add(new THREE.AmbientLight(0x33403a, 1.0));

    var key = new THREE.DirectionalLight(0xfff2d0, 1.6);
    key.position.set(6, 9, 8);
    scene.add(key);

    var rim = new THREE.DirectionalLight(0xc89b3c, 2.4);
    rim.position.set(-7, 4, -6);
    scene.add(rim);

    var fill = new THREE.DirectionalLight(0x7defc0, 0.32);
    fill.position.set(0, -3, 6);
    scene.add(fill);

    /* ------------------------------------------------------------------ */
    /*  Product cards — crisp images, no plates, no alpha mask             */
    /* ------------------------------------------------------------------ */
    var products = PRODUCTS.slice(0, 4);
    var cards = [];

    function makeCardMaterial(img) {
        var tx = new THREE.Texture(img);
        tx.needsUpdate = true;
        tx.anisotropy = 8;
        return new THREE.MeshStandardMaterial({
            map: tx,
            roughness: 0.34,
            metalness: 0.5,
            side: THREE.DoubleSide
        });
    }

    function fallbackMaterial(text) {
        var c = document.createElement("canvas");
        c.width = 512; c.height = 640;
        var ctx = c.getContext("2d");
        var grad = ctx.createLinearGradient(0, 0, 0, c.height);
        grad.addColorStop(0, "#1E2723");
        grad.addColorStop(1, "#111614");
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, c.width, c.height);
        ctx.strokeStyle = "rgba(200,155,60,0.55)";
        ctx.lineWidth = 6;
        ctx.strokeRect(24, 24, c.width - 48, c.height - 48);
        ctx.fillStyle = "#E9CE8A";
        ctx.font = "600 56px Fraunces, serif";
        ctx.textAlign = "left"; ctx.textBaseline = "bottom";
        ctx.fillText(text.slice(0, 18), 56, 120);
        ctx.font = "500 30px Fraunces, serif";
        ctx.fillStyle = "rgba(243,236,220,0.55)";
        ctx.fillText("Marigold Signature", 56, 160);
        var tx = new THREE.CanvasTexture(c);
        tx.anisotropy = 4;
        return new THREE.MeshStandardMaterial({ map: tx, roughness: 0.4, metalness: 0.35, side: THREE.DoubleSide });
    }

    function addCard(p, w, h, x, y, z, ry, rx) {
        var geo = new THREE.PlaneGeometry(w, h);
        var entry = { baseX: x, baseY: y, baseZ: z, phase: Math.random() * Math.PI * 2, amp: 0.1 + Math.random() * 0.08 };
        var img = new Image();
        img.crossOrigin = "anonymous";
        img.onload = function () {
            entry.mesh = new THREE.Mesh(geo, makeCardMaterial(img));
            entry.mesh.position.set(x, y, z);
            entry.mesh.rotation.y = ry;
            entry.mesh.rotation.x = rx;
            scene.add(entry.mesh);
            cards.push(entry);
        };
        img.onerror = function () {
            entry.mesh = new THREE.Mesh(geo, fallbackMaterial(p.name));
            entry.mesh.position.set(x, y, z);
            entry.mesh.rotation.y = ry;
            entry.mesh.rotation.x = rx;
            scene.add(entry.mesh);
            cards.push(entry);
        };
        img.src = p.img;
    }

    /* ------------------------------------------------------------------ */
    /*  2x2 grid — big products on the right stage, no circular motion     */
    /* ------------------------------------------------------------------ */
    var scale = isNarrow ? 0.6 : 1;
    var cardH = 4.4 * scale;                  // generous size
    var cardW = 3.5 * scale;
    var gapX = 5.6 * scale;                   // horizontal space between column centres
    var gapY = 5.9 * scale;                   // vertical space between row centres
    var gridX = isNarrow ? 7.4 : 7.6;
    var gridY = 0.35;
    var gridZ = -0.9;
    var lookBase = { x: isNarrow ? 7.4 : 6.4, y: 0.35, z: -0.4 };

    var cols = [-0.5, 0.5];
    var rows = [-0.5, 0.5];
    var slot = 0;
    for (var r = 0; r < rows.length; r++) {
        for (var c = 0; c < cols.length; c++) {
            if (!products[slot]) break;
            var x = gridX + cols[c] * gapX;
            var y = gridY + rows[r] * gapY;
            var z = gridZ + (r === 1 ? 0.6 : 0);
            var ry = cols[c] > 0 ? -0.14 : 0.14;   // slight inward angle for 3D depth
            var rx = rows[r] < 0 ? 0.06 : -0.04;
            addCard(products[slot], cardW, cardH, x, y, z, ry, rx);
            slot++;
        }
    }
    var feature = products[0] || null;

    /* ------------------------------------------------------------------ */
    /*  Ambient depth: gold ring + wire globe + halo                       */
    /* ------------------------------------------------------------------ */
    var ring = new THREE.Mesh(
        new THREE.RingGeometry(4.6, 5.0, 120),
        new THREE.MeshStandardMaterial({ color: 0xC89B3C, metalness: 0.95, roughness: 0.2, transparent: true, opacity: 0.3, side: THREE.DoubleSide })
    );
    ring.position.set(gridX, gridY, gridZ + 5.4);
    ring.rotation.set(0.4, 0.1, 0.5);
    scene.add(ring);

    var globe = new THREE.Mesh(
        new THREE.IcosahedronGeometry(isNarrow ? 1.1 : 1.7, 1),
        new THREE.MeshStandardMaterial({ color: 0x0d1210, metalness: 0.45, roughness: 0.9, wireframe: true, transparent: true, opacity: 0.16 })
    );
    globe.position.set(gridX + (isNarrow ? 2.6 : 4.7), gridY + 3.4, gridZ + 1.4);
    scene.add(globe);

    var halo = null;
    (function () {
        var g = document.createElement("canvas");
        g.width = 256; g.height = 256;
        var c = g.getContext("2d");
        var grad = c.createRadialGradient(128, 128, 8, 128, 128, 128);
        grad.addColorStop(0, "rgba(200,155,60,0.4)");
        grad.addColorStop(1, "rgba(200,155,60,0)");
        c.fillStyle = grad; c.fillRect(0, 0, 256, 256);
        var tx = new THREE.CanvasTexture(g);
        halo = new THREE.Sprite(new THREE.SpriteMaterial({ map: tx, transparent: true, opacity: 0.8, blending: THREE.AdditiveBlending, depthWrite: false }));
        halo.scale.set(isNarrow ? 8 : 10, isNarrow ? 8 : 10, 1);
        halo.position.set(gridX, gridY - 0.4, gridZ - 4);
        scene.add(halo);
    })();

    /* ------------------------------------------------------------------ */
    /*  Gold dust particles (right field)                                  */
    /* ------------------------------------------------------------------ */
    var N = isNarrow ? 300 : 600;
    var pos = new Float32Array(N * 3);
    for (var i = 0; i < N; i++) {
        var r = 2 + Math.random() * 9;
        var a = Math.random() * Math.PI * 2;
        pos[i * 3] = gridX + Math.cos(a) * r;
        pos[i * 3 + 1] = gridY + (Math.random() - 0.5) * 9;
        pos[i * 3 + 2] = gridZ + Math.sin(a) * r + 1;
    }
    var ptGeo = new THREE.BufferGeometry();
    ptGeo.setAttribute("position", new THREE.BufferAttribute(pos, 3));
    var ptMat = new THREE.PointsMaterial({ color: 0xD9B45C, size: isNarrow ? 0.035 : 0.045, transparent: true, opacity: 0.6, blending: THREE.AdditiveBlending, depthWrite: false });
    var dust = new THREE.Points(ptGeo, ptMat);
    scene.add(dust);

    /* ------------------------------------------------------------------ */
    /*  HUD                                                                */
    /* ------------------------------------------------------------------ */
    var hudName = document.getElementById("hudName");
    var hudPrice = document.getElementById("hudPrice");
    function setHud(p) {
        if (!p || !hudName) return;
        var name = p.name || "";
        hudName.textContent = name.length > 34 ? name.slice(0, 34) + "…" : name;
        if (hudPrice) hudPrice.innerHTML = window.Marigold ? window.Marigold.fmtMoney(window.Marigold.convertPrice(p.price || 0)) : ("\u20A6" + Number(p.price || 0).toLocaleString("en-NG"));
    }
    setHud(feature || products[0]);

    /* ------------------------------------------------------------------ */
    /*  Pointer parallax                                                   */
    /* ------------------------------------------------------------------ */
    var target = { x: 0, y: 0 }, current = { x: 0, y: 0 };
    window.addEventListener("pointermove", function (e) {
        if (e.touches && e.touches.length) { e = e.touches[0]; }
        target.x = (e.clientX / window.innerWidth) * 2 - 1;
        target.y = -((e.clientY / window.innerHeight) * 2 - 1);
    }, { passive: true });

    /* ------------------------------------------------------------------ */
    /*  Animation loop — products breathe in place; the scene stays alive  */
    /* ------------------------------------------------------------------ */
    var t = 0;
    function tick() {
        t += 0.016;

        current.x += (target.x - current.x) * 0.045;
        current.y += (target.y - current.y) * 0.045;

        camera.position.x = camBase.x + current.x * 1.5;
        camera.position.y = camBase.y + current.y * 0.8;
        camera.lookAt(lookBase.x, lookBase.y, lookBase.z);

        /* each card gently sways in place — no travelling, just aliveness */
        cards.forEach(function (ct, idx) {
            if (!ct.mesh) return;
            var s = ct.phase;
            var dy = Math.sin(t * 0.55 + s);
            ct.mesh.position.x = ct.baseX + Math.sin(t * 0.4 + s) * 0.06 * scale;
            ct.mesh.position.y = ct.baseY + dy * 0.06 * scale;
            ct.mesh.position.z = ct.baseZ + Math.sin(t * 0.3 + s) * 0.04 * scale;
            ct.mesh.rotation.z = dy * 0.02;
            ct.mesh.rotation.y = (cols[idx % 2] > 0 ? -0.14 : 0.14) + Math.sin(t * 0.5 + s) * 0.03;
            ct.mesh.rotation.x = (rows[Math.floor(idx / 2)] < 0 ? 0.06 : -0.04) + Math.sin(t * 0.42 + s) * 0.02;
        });

        ring.rotation.z += 0.0006;
        halo.material.opacity = 0.8 + Math.sin(t * 1.2) * 0.18;
        globe.rotation.x += 0.0022;
        globe.rotation.y += 0.0015;
        dust.rotation.y += 0.0004;

        renderer.render(scene, camera);
    }

    function resize() {
        var w = container.clientWidth, h = container.clientHeight;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    }
    window.addEventListener("resize", resize);
    window.addEventListener("orientationchange", resize);

    if (reduceMotion) {
        renderer.render(scene, camera);
    } else {
        renderer.setAnimationLoop(tick);
    }
})();
</script>
</body>
</html>