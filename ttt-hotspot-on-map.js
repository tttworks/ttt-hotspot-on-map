/**
 * ============================================
 * TTT Hotspot on Map - JS 脚本
 * @version     1.0.20
 * @date        2026-06-28
 * @description 修复aln首次百分比parseFloat('81%')=81px漂移 + label_pos_mobile默认bottom + 水平溢出防线
 * ============================================
 */

add_action( 'wp_enqueue_scripts',                'ttt_hotspot_on_map_enqueue' );
add_action( 'elementor/preview/enqueue_scripts', 'ttt_hotspot_on_map_enqueue' );
function ttt_hotspot_on_map_enqueue() {
    $js = <<<'JS'
(function () {
    'use strict';
    var DEBUG = true, SVG_NS = 'http://www.w3.org/2000/svg';
    function L() { if (DEBUG) console.log.apply(console, ['[ZHOM]'].concat(Array.prototype.slice.call(arguments))); }
    function ph(raw) { try { return JSON.parse(raw || '[]'); } catch (e) { return []; } }
    function bzb(x1, y1, x2, y2, cv, bs) {
        var dx = x2 - x1, dy = y2 - y1, d = Math.sqrt(dx * dx + dy * dy);
        if (d < 0.01) return '';
        var s = (bs >= 0) ? 1 : -1, off = d * cv * s;
        var px = -dy / d, py = dx / d;
        return 'M ' + x1 + ' ' + y1 + ' C ' + (x1 + dx * 0.3 + px * off) + ' ' + (y1 + dy * 0.3 + py * off) + ', ' + (x1 + dx * 0.7 + px * off) + ' ' + (y1 + dy * 0.7 + py * off) + ', ' + x2 + ' ' + y2;
    }
    function sa(el, a) { for (var k in a) if (a.hasOwnProperty(k)) el.setAttribute(k, a[k]); }
    function prgb(c) { var m; m = c.match(/rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*([\d.]+))?\s*\)/); if (m) return { r: parseInt(m[1]), g: parseInt(m[2]), b: parseInt(m[3]), a: m[4] !== undefined ? parseFloat(m[4]) : 1 }; m = c.match(/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/); if (m) return { r: parseInt(m[1], 16), g: parseInt(m[2], 16), b: parseInt(m[3], 16), a: 1 }; return { r: 0, g: 0, b: 0, a: 1 }; }
    function rgs(rgb, a) { return 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + (a !== undefined ? a : rgb.a) + ')'; }
    var isEditor = (typeof window.elementor !== 'undefined') || (typeof window.parent !== 'undefined' && window.parent !== window && typeof window.parent.elementor !== 'undefined');
    function iw(w) {
        if (w.classList.contains('zhom-init')) return;
        w.classList.add('zhom-init');
        var mi = w.querySelector('.zhom-map-image'),
            os = w.querySelector('.zhom-overlay'),
            ml = w.querySelector('.zhom-markers-layer'),
            ov = w.querySelector('.zhom-orbit-layer');
        if (!mi || !os || !ml || !ov) return;
        var ds = w.dataset;
        var cfg = {
            mUrl: ds.mapUrl || '',
            oms: ds.orbitModeSel || 'both', opr: ds.orbitProgress === 'true', odt: ds.orbitDotShow === 'true',
            odr: ds.orbitDirection || 'spoke_to_center', osp: parseFloat(ds.orbitSpeed) || 5,
            oar: ds.orbitArrange || 'simultaneous', ood: ds.orbitOrder || 'clockwise',
            osm: ds.orbitSpeedMode || 'uniform',
            ehv: ds.enableHover === 'true', ecl: ds.enableClick === 'true',
            dcv: parseFloat(ds.defaultCurvature) || 0.02,
            gp:  ds.globalPulse === 'true',
            cc: ds.curveColor || 'rgba(1,51,61,0.2)', cw: parseFloat(ds.curveWidth) || 1,
            cdl: parseFloat(ds.curveDashLength) || 3, cdg: parseFloat(ds.curveDashGap) || 4,
            odc: ds.orbitDotColor || 'rgba(1,51,61,1)', ods: parseFloat(ds.orbitDotSize) || 8,
            odg: ds.orbitDotGlow === 'true',
            ots: ds.orbitTrailStyle || 'solid', otc: ds.orbitTrailColor || '#59A498',
            otw: parseFloat(ds.orbitTrailWidth) || 1,
            hts: ph(ds.hotspots)
        };
        var vbW = 1675, vbH = 1082;
        L('v1.0.20', cfg);
        os.setAttribute('viewBox', '0 0 ' + vbW + ' ' + vbH);
        os.style.width = '100%'; os.style.height = '100%';
        ov.setAttribute('viewBox', '0 0 ' + vbW + ' ' + vbH);
        ov.style.width = '100%'; ov.style.height = '100%';
        var ctrs = [], spks = [];
        cfg.hts.forEach(function(h, i) { h._i = i; h.xS = h.x; h.yS = h.y; if (h.type === 'center') ctrs.push(h); else spks.push(h); });
        var conns = [];
        spks.forEach(function(s) { var t = (s.connect_to || '').trim(), c = t ? ctrs.find(function(ct) { return ct.label === t; }) : null; if (!c && ctrs.length > 0) c = ctrs[0]; if (c) conns.push({ s: s, c: c }); });
        var am = [];
        cfg.hts.forEach(function(h, idx) {
            var lp = h.label_pos || 'right', nl = h.label || '';
            // 响应式文字位置：小屏读取 label_pos_mobile，未设置 fallback 到 label_pos
            var mpos = h.label_pos_mobile;
            function getLp() { return (mpos && window.innerWidth <= 767) ? mpos : lp; }
            function setFlex(el, pos) { if (!nl) return; if (pos === 'top') el.style.flexDirection = 'column-reverse'; else if (pos === 'bottom') el.style.flexDirection = 'column'; else if (pos === 'left') el.style.flexDirection = 'row-reverse'; else el.style.flexDirection = 'row'; }
            var curLp = getLp();
            var el = document.createElement('div');
            el.className = 'zhom-marker zhom-label-' + curLp;
            el.setAttribute('data-idx', idx); el.setAttribute('data-type', h.type);
            // 层叠：自定义图标>普通点>连接线。overlay=2, marker=3, orbit=4。marker 在 z=3 层内即可
            if (h.icon) el.style.zIndex = 13;
            else el.style.zIndex = 11;
            el.style.left = h.x + '%'; el.style.top = h.y + '%';
            // 记录初始值为百分比，aln() 首次执行时若读到百分比字符串则忽略(非像素)
            el._initPct = true;
            setFlex(el, curLp);
            var an = document.createElement('div'); an.className = 'zhom-icon-anchor';
            if (cfg.gp) { var pw = document.createElement('div'); pw.className = 'zhom-pulse-wrap'; var pr = document.createElement('div'); pr.className = 'zhom-pulse-ring'; pw.appendChild(pr); an.appendChild(pw); }
            var ic = document.createElement('div'); ic.className = 'zhom-marker-icon';
            if (h.icon) { var im = document.createElement('img'); im.src = h.icon; im.alt = h.label || 'Hotspot'; ic.appendChild(im); }
            else { var dt = document.createElement('div'); dt.className = 'zhom-marker-dot'; ic.appendChild(dt); }
            an.appendChild(ic); el.appendChild(an);
            if (nl) { var lb = document.createElement('div'); lb.className = 'zhom-marker-label'; lb.textContent = nl; el.appendChild(lb); }
            if (h.show_popup && h.info_content) { var pp = document.createElement('div'); pp.className = 'zhom-info-popup'; pp.innerHTML = h.info_content; el.appendChild(pp); }
            if (h.draggable && isEditor) { el.classList.add('zhom-draggable'); }
            var tip = document.createElement('div'); tip.className = 'zhom-drag-tip';
            if (h.draggable && isEditor) { tip.innerHTML = '<span class="zhom-tip-text">可拖拽</span><button class="zhom-save-btn" type="button">保存坐标</button>'; } else { tip.style.display = 'none'; }
            el.appendChild(tip);
            ml.appendChild(el);
            am.push({ el: el, an: an, d: h, xP: h.x, yP: h.y, tip: tip });
        });
        function imR() { return mi.getBoundingClientRect(); }
        function aln() { var ir = imR(); if (ir.width <= 0 || ir.height <= 0) return; am.forEach(function(mk) { var tx = mk.xP / 100 * ir.width + ir.left; var ty = mk.yP / 100 * ir.height + ir.top; var cl = parseFloat(mk.el.style.left); if (mk.el._initPct && String(mk.el.style.left).indexOf('%') !== -1) { cl = 0; mk.el._initPct = false; } if (isNaN(cl)) cl = 0; var ct = parseFloat(mk.el.style.top); if (String(mk.el.style.top).indexOf('%') !== -1) ct = 0; if (isNaN(ct)) ct = 0; var ar = mk.an.getBoundingClientRect(); if (ar.width <= 0) return; mk.el.style.left = (cl + (tx - ar.left - ar.width / 2)) + 'px'; mk.el.style.top  = (ct + (ty - ar.top - ar.height / 2)) + 'px'; }); }
        function a2v(mk) { return { vx: mk.xP / 100 * vbW, vy: mk.yP / 100 * vbH }; }
        var cg = document.createElementNS(SVG_NS, 'g');
        cg.setAttribute('class', 'zhom-curves-group');
        os.appendChild(cg);
        var cp = [];
        function rbc() {
            while (cg.firstChild) cg.removeChild(cg.firstChild);
            cp = [];
            conns.forEach(function(conn, idx) {
                var ccv = a2v(am[conn.c._i] || am[0]);
                var scv = a2v(am[conn.s._i] || am[1]);
                var cf = (typeof conn.s.curvature === 'number') ? conn.s.curvature : 1;
                var bs = (conn.s.bend === 'reverse') ? -1 : 1;
                var cv = cfg.dcv * cf;
                var d = bzb(ccv.vx, ccv.vy, scv.vx, scv.vy, cv, bs);
                if (!d) return;
                var pe = document.createElementNS(SVG_NS, 'path');
                sa(pe, { 'd': d, 'fill': 'none', 'stroke': cfg.cc, 'stroke-width': cfg.cw,
                    'stroke-dasharray': cfg.cdl + ' ' + cfg.cdg, 'stroke-linecap': 'round',
                    'class': 'zhom-curve zhom-curve-' + idx, 'data-si': conn.s._i, 'data-ci': conn.c._i });
                cg.appendChild(pe);
                cp.push({ p: pe, d: d, s: conn.s, c: conn.c, ln: pe.getTotalLength ? pe.getTotalLength() : 0 });
            });
        }
        rbc();
        var orb = { ds: [], trs: [], fid: null, st: { ord: undefined } };
        if (cfg.oms !== 'off' && conns.length > 0) {
            buildOrbit();
            var t0 = null;
            function stp(ts) {
                if (t0 === null) t0 = ts;
                var el = ts - t0, dur = cfg.osp * 1000;
                if (cfg.oar === 'simultaneous') {
                    if (cfg.osm === 'sync_arrival' && orb.ds.length > 1) {
                        var mx = 0; orb.ds.forEach(function(d) { var l = d.sp && d.sp.getTotalLength ? d.sp.getTotalLength() : 0; if (l > mx) mx = l; });
                        orb.ds.forEach(function(d) { var l = d.sp && d.sp.getTotalLength ? d.sp.getTotalLength() : 1; upd(d, el, dur * (l / Math.max(mx, 0.01))); });
                    } else { orb.ds.forEach(function(d) { upd(d, el, dur); }); }
                } else {
                    var tc = dur * orb.ds.length, cy = el % tc;
                    var ai = Math.floor(cy / dur); if (ai >= orb.ds.length) ai = 0;
                    var ph = (cy % dur) / dur;
                    if (orb.st.ord === undefined && ctrs.length > 0) {
                        var ct = ctrs[0];
                        var ags = conns.map(function(cn, i) { return { idx: i, ang: Math.atan2(cn.s.y - ct.y, cn.s.x - ct.x) }; });
                        if (cfg.ood === 'clockwise') ags.sort(function(a, b) { return a.ang - b.ang; }); else ags.sort(function(a, b) { return b.ang - a.ang; });
                        orb.st.ord = ags.map(function(a) { return a.idx; });
                    }
                    var ord = orb.st.ord || orb.ds.map(function(_, i) { return i; });
                    var oi = ord[ai % ord.length];
                    orb.ds.forEach(function(d, i) {
                        if (i === oi) { sph(d, ph); if (d.de) d.de.setAttribute('opacity', '1'); if (d.ge) d.ge.setAttribute('opacity', '1'); }
                        else { if (d.de) d.de.setAttribute('opacity', '0'); if (d.ge) d.ge.setAttribute('opacity', '0'); }
                    });
                }
                orb.fid = requestAnimationFrame(stp);
            }
            function upd(d, el, dur) { var raw = (el % dur) / dur; sph(d, raw); if (d.de) d.de.setAttribute('opacity', '1'); if (d.ge) d.ge.setAttribute('opacity', '1'); }
            function sph(d, phase) {
                var sp = d.sp; if (!sp) return;
                var len = sp.getTotalLength ? sp.getTotalLength() : 0; if (len <= 0) return;
                var shown = Math.max(0, Math.min(100, phase * 100));
                var ep;
                if (cfg.odr === 'spoke_to_center') ep = 1 - phase;
                else if (cfg.odr === 'bidirectional') ep = phase <= 0.5 ? 1 - phase * 2 : (phase - 0.5) * 2;
                else ep = phase;
                if (d.de) { var dist = ep * len; var pt = sp.getPointAtLength ? sp.getPointAtLength(dist) : null; if (pt) { d.de.setAttribute('cx', pt.x); d.de.setAttribute('cy', pt.y); if (d.ge) { d.ge.setAttribute('cx', pt.x); d.ge.setAttribute('cy', pt.y); } } }
                if (d.tl && d.tln > 0) {
                    d.tl.setAttribute('pathLength', '100');
                    d.tl.setAttribute('stroke-dasharray', shown + ' ' + (100 - shown));
                    if (cfg.odr === 'spoke_to_center') { d.tl.setAttribute('stroke-dashoffset', -(100 - shown)); }
                    else if (cfg.odr === 'bidirectional') { d.tl.setAttribute('stroke-dashoffset', phase <= 0.5 ? -(100 - shown) : 0); }
                    else { d.tl.setAttribute('stroke-dashoffset', 0); }
                    d.tl.style.transition = 'none';
                }
            }
            orb.ds.forEach(function(d) { sph(d, 0); if (d.de) d.de.setAttribute('opacity', '1'); if (d.ge) d.ge.setAttribute('opacity', '1'); });
            orb.fid = requestAnimationFrame(stp);
        }
        function buildOrbit() {
            var oldOg = ov.querySelector('.zhom-orbits-group'); if (oldOg) oldOg.parentNode.removeChild(oldOg);
            orb.ds = []; orb.trs = [];
            var og = document.createElementNS(SVG_NS, 'g'); og.setAttribute('class', 'zhom-orbits-group'); ov.appendChild(og);
            conns.forEach(function(conn, idx) {
                var cd = (cp.length > idx) ? cp[idx].d : ''; if (!cd) return;
                var tl = null, tln = 0;
                if (cfg.opr && cfg.ots !== 'none') {
                    tl = document.createElementNS(SVG_NS, 'path');
                    var ta = { 'd': cd, 'fill': 'none', 'stroke': cfg.otc, 'stroke-width': cfg.otw, 'stroke-linecap': 'round', 'class': 'zhom-orbit-trail zhom-orbit-trail-' + idx, 'pathLength': '100', 'stroke-dasharray': '0 100', 'stroke-dashoffset': '0' };
                    if (cfg.ots === 'dashed') ta['stroke-dasharray'] = '0.5 0.5';
                    sa(tl, ta); og.appendChild(tl);
                    tln = tl.getTotalLength ? tl.getTotalLength() : 0;
                    orb.trs.push({ el: tl, ln: tln });
                }
                var de = null, ge = null;
                if (cfg.odt) {
                    de = document.createElementNS(SVG_NS, 'circle');
                    sa(de, { 'r': cfg.ods / 2, 'fill': cfg.odc, 'class': 'zhom-orbit-dot zhom-orbit-dot-' + idx, 'opacity': '0' });
                    og.appendChild(de);
                    if (cfg.odg) { ge = document.createElementNS(SVG_NS, 'circle'); sa(ge, { 'r': cfg.ods, 'fill': rgs(prgb(cfg.odc), 0.25), 'class': 'zhom-orbit-glow zhom-orbit-glow-' + idx, 'opacity': '0' }); og.appendChild(ge); }
                }
                var sp = (cp.length > idx) ? cp[idx].p : null;
                orb.ds.push({ de: de, ge: ge, tl: tl, tln: tln, sp: sp, idx: idx });
            });
        }
        am.forEach(function(mk) {
            var dr = false, sp = null;
            mk.el.addEventListener('mousedown', function(e) {
                if (!mk.el.classList.contains('zhom-draggable') || e.button !== 0) return;
                e.preventDefault(); e.stopPropagation();
                dr = true; sp = { x: e.clientX, y: e.clientY }; mk.el.classList.add('zhom-dragging');
            });
            document.addEventListener('mousemove', function(e) {
                if (!dr) return;
                var ir = imR(); if (ir.width <= 0 || ir.height <= 0) return;
                mk.xP += (e.clientX - sp.x) / ir.width * 100; mk.yP += (e.clientY - sp.y) / ir.height * 100;
                mk.xP = Math.max(0, Math.min(100, mk.xP)); mk.yP = Math.max(0, Math.min(100, mk.yP));
                mk.d.x = mk.xP; mk.d.y = mk.yP;
                if (cfg.hts[mk.d._i]) { cfg.hts[mk.d._i].x = mk.xP; cfg.hts[mk.d._i].y = mk.yP; cfg.hts[mk.d._i].xS = mk.xP; cfg.hts[mk.d._i].yS = mk.yP; }
                aln(); sp = { x: e.clientX, y: e.clientY };
            });
            document.addEventListener('mouseup', function() {
                if (!dr) return; dr = false; mk.el.classList.remove('zhom-dragging');
                console.info('[ZHOM] + ' + mk.d.label + ' @ X:' + mk.xP.toFixed(2) + '% Y:' + mk.yP.toFixed(2) + '%');
                rbc(); buildOrbit();
            });
        });
        if (cfg.ehv) {
            am.forEach(function(mk) {
                mk.el.addEventListener('mouseenter', function() { var ix = parseInt(mk.el.getAttribute('data-idx')); mk.el.classList.add('zhom-hover'); cp.forEach(function(pp) { if (parseInt(pp.p.getAttribute('data-si')) === ix || parseInt(pp.p.getAttribute('data-ci')) === ix) pp.p.classList.add('zhom-curve-hover'); }); });
                mk.el.addEventListener('mouseleave', function() { mk.el.classList.remove('zhom-hover'); cp.forEach(function(pp) { pp.p.classList.remove('zhom-curve-hover'); }); });
            });
        }
        if (cfg.ecl) {
            am.forEach(function(mk) { mk.el.addEventListener('click', function(e) { e.stopPropagation(); var p = mk.el.querySelector('.zhom-info-popup'); if (!p) return; w.querySelectorAll('.zhom-info-popup.zhom-popup-visible').forEach(function(pp) { if (pp !== p) pp.classList.remove('zhom-popup-visible'); }); p.classList.toggle('zhom-popup-visible'); }); });
            w.addEventListener('click', function() { w.querySelectorAll('.zhom-info-popup.zhom-popup-visible').forEach(function(p) { p.classList.remove('zhom-popup-visible'); }); });
        }
        function sy() { var r = imR(); if (r.width > 0 && r.height > 0) { os.style.width = r.width + 'px'; os.style.height = r.height + 'px'; ov.style.width = r.width + 'px'; ov.style.height = r.height + 'px'; }
            // 响应式文字位置：viewport 跨越 768px 断点时切换 class + flex
            am.forEach(function(mk) { var mpos = mk.d.label_pos_mobile; if (!mpos) return; var targ = (window.innerWidth <= 767) ? mpos : (mk.d.label_pos || 'right'); var cur = mk.el.className.match(/zhom-label-(\w+)/); if (cur && cur[1] === targ) return; mk.el.className = mk.el.className.replace(/zhom-label-\w+/, 'zhom-label-' + targ); var nl = mk.d.label; if (nl) { if (targ === 'top') mk.el.style.flexDirection = 'column-reverse'; else if (targ === 'bottom') mk.el.style.flexDirection = 'column'; else if (targ === 'left') mk.el.style.flexDirection = 'row-reverse'; else mk.el.style.flexDirection = 'row'; } });
            aln(); }
        if (mi.complete) sy(); else mi.addEventListener('load', sy);
        window.addEventListener('resize', sy);
        if (typeof ResizeObserver !== 'undefined') { try { new ResizeObserver(sy).observe(w); } catch (e) {} }
        w._zhom = { fid: orb.fid, cfg: cfg, sy: sy };
    }
    function iA(scope) { (scope || document).querySelectorAll('.zhom-map-wrapper:not(.zhom-init)').forEach(function(w) { try { iw(w); } catch (e) { w.classList.add('zhom-init'); } }); }
    function bt() { iA(); if (typeof MutationObserver !== 'undefined') new MutationObserver(function() { iA(); }).observe(document.body, { childList: true, subtree: true }); if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) elementorFrontend.hooks.addAction('frontend/element_ready/global', function($s) { iA($s[0]); }); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bt); else bt();
})();
JS;

    wp_register_script( 'ttt-hotspot-on-map', '', [], null, true );
    wp_enqueue_script(  'ttt-hotspot-on-map' );
    wp_add_inline_script( 'ttt-hotspot-on-map', $js );
}
