;
(function ($, elementor) {
    'use strict';

    $(window).on('elementor/frontend/init', function () {
        var ModuleHandler = elementorModules.frontend.handlers.Base,
            Notation;

        Notation = ModuleHandler.extend({

            bindEvents: function () {
                this.run();
            },

            getDefaultSettings: function () {
                return {
                    type: 'underline',
                    multiline: true
                };
            },

            onElementChange: debounce(function (prop) {
                if (prop.indexOf('ep_notation_') !== -1) {
                    this.run();
                }
            }, 400),

            settings: function (key) {
                return this.getElementSettings('ep_notation_' + key);
            },

            run: function () {

                if (this.settings('active') != 'yes') {
                    return;
                }

                var
                    $element = this.$element,
                    $widgetId = 'ep-' + this.getID(),
                    $elementID = this.getID(),
                    $globalthis = this;

                var $list = this.settings('list');

                var rtl = ($("body").hasClass("rtl")) ? true : false;


                $list.forEach(element => {
                    var
                        $selectElement = '',
                        bracketOn = '',
                        options = this.getDefaultSettings();

                    if (element.ep_notation_select_type == 'widget') {
                        if ($('.elementor-editor-active').length > 0) {
                            $($globalthis.findElement(' > :not(style)').get(1)).attr('data-notation', $widgetId);
                        } else {
                            $($globalthis.findElement(' > :not(style)').get(0)).attr('data-notation', $widgetId);
                        }
                        $selectElement = '[data-notation="' + $widgetId + '"]';
                    }
                    if (element.ep_notation_select_type == 'custom') {
                        var customSelector = element.ep_notation_custom_selector;


                        if (element.ep_notation_custom_selector && customSelector.length > 1) {
                            $selectElement = '[data-id="' + $elementID + '"] ' + ' ' + customSelector;
                        } else {
                            $selectElement = '.-bdt-empty';
                        }

                    }

                    if (element.ep_notation_type == 'bracket') {
                        bracketOn = element.ep_notation_bracket_on;
                        bracketOn = bracketOn.split(',');
                        options.brackets = bracketOn;
                    }

                    if ($selectElement.length > 0) {

                        var n1 = document.querySelector($selectElement);

                        options.type = element.ep_notation_type;
                        options.color = element.ep_notation_color || '#f23427';
                        options.animationDuration = element.ep_notation_anim_duration.size || 800;
                        options.strokeWidth = element.ep_notation_stroke_width.size || 1;
                        options.rtl = rtl;



                        if ($($selectElement).length > 0) {
                            epObserveTarget($element[0], function () {

                                const t = "http://www.w3.org/2000/svg";
                                class e {
                                    constructor(t) {
                                        this.seed = t;
                                    }
                                    next() {
                                        return this.seed ? (2 ** 31 - 1 & (this.seed = Math.imul(48271, this.seed))) / 2 ** 31 : Math.random();
                                    }
                                }

                                function s(t, e, s, i, n) {
                                    return {
                                        type: "path",
                                        ops: c(t, e, s, i, n)
                                    };
                                }

                                function i(t, e, i) {
                                    const n = (t || []).length;
                                    if (n > 2) {
                                        const s = [];
                                        for (let e = 0; e < n - 1; e++) s.push(...c(t[e][0], t[e][1], t[e + 1][0], t[e + 1][1], i));
                                        return e && s.push(...c(t[n - 1][0], t[n - 1][1], t[0][0], t[0][1], i)), {
                                            type: "path",
                                            ops: s
                                        };
                                    }
                                    return 2 === n ? s(t[0][0], t[0][1], t[1][0], t[1][1], i) : {
                                        type: "path",
                                        ops: []
                                    };
                                }

                                function n(t, e, s, n, o) {
                                    return function (t, e) {
                                        return i(t, !0, e);
                                    }([
                                        [t, e],
                                        [t + s, e],
                                        [t + s, e + n],
                                        [t, e + n]
                                    ], o);
                                }

                                function o(t, e, s, i, n) {
                                    return function (t, e, s, i) {
                                        const [n, o] = l(i.increment, t, e, i.rx, i.ry, 1, i.increment * h(.1, h(.4, 1, s), s), s);
                                        let r = f(n, null, s);
                                        if (!s.disableMultiStroke) {
                                            const [n] = l(i.increment, t, e, i.rx, i.ry, 1.5, 0, s), o = f(n, null, s);
                                            r = r.concat(o);
                                        }
                                        return {
                                            estimatedPoints: o,
                                            opset: {
                                                type: "path",
                                                ops: r
                                            }
                                        };
                                    }(t, e, n, function (t, e, s) {
                                        const i = Math.sqrt(2 * Math.PI * Math.sqrt((Math.pow(t / 2, 2) + Math.pow(e / 2, 2)) / 2)),
                                            n = Math.max(s.curveStepCount, s.curveStepCount / Math.sqrt(200) * i),
                                            o = 2 * Math.PI / n;
                                        let r = Math.abs(t / 2),
                                            h = Math.abs(e / 2);
                                        const c = 1 - s.curveFitting;
                                        return r += a(r * c, s), h += a(h * c, s), {
                                            increment: o,
                                            rx: r,
                                            ry: h
                                        };
                                    }(s, i, n)).opset;
                                }

                                function r(t) {
                                    return t.randomizer || (t.randomizer = new e(t.seed || 0)), t.randomizer.next();
                                }

                                function h(t, e, s, i = 1) {
                                    return s.roughness * i * (r(s) * (e - t) + t);
                                }

                                function a(t, e, s = 1) {
                                    return h(-t, t, e, s);
                                }

                                function c(t, e, s, i, n, o = !1) {
                                    const r = o ? n.disableMultiStrokeFill : n.disableMultiStroke,
                                        h = u(t, e, s, i, n, !0, !1);
                                    if (r) return h;
                                    const a = u(t, e, s, i, n, !0, !0);
                                    return h.concat(a);
                                }

                                function u(t, e, s, i, n, o, h) {
                                    const c = Math.pow(t - s, 2) + Math.pow(e - i, 2),
                                        u = Math.sqrt(c);
                                    let f = 1;
                                    f = u < 200 ? 1 : u > 500 ? .4 : -.0016668 * u + 1.233334;
                                    let l = n.maxRandomnessOffset || 0;
                                    l * l * 100 > c && (l = u / 10);
                                    const g = l / 2,
                                        d = .2 + .2 * r(n);
                                    let p = n.bowing * n.maxRandomnessOffset * (i - e) / 200,
                                        _ = n.bowing * n.maxRandomnessOffset * (t - s) / 200;
                                    p = a(p, n, f), _ = a(_, n, f);
                                    const m = [],
                                        w = () => a(g, n, f),
                                        v = () => a(l, n, f);
                                    return o && (h ? m.push({
                                        op: "move",
                                        data: [t + w(), e + w()]
                                    }) : m.push({
                                        op: "move",
                                        data: [t + a(l, n, f), e + a(l, n, f)]
                                    })), h ? m.push({
                                        op: "bcurveTo",
                                        data: [p + t + (s - t) * d + w(), _ + e + (i - e) * d + w(), p + t + 2 * (s - t) * d + w(), _ + e + 2 * (i - e) * d + w(), s + w(), i + w()]
                                    }) : m.push({
                                        op: "bcurveTo",
                                        data: [p + t + (s - t) * d + v(), _ + e + (i - e) * d + v(), p + t + 2 * (s - t) * d + v(), _ + e + 2 * (i - e) * d + v(), s + v(), i + v()]
                                    }), m;
                                }

                                function f(t, e, s) {
                                    const i = t.length,
                                        n = [];
                                    if (i > 3) {
                                        const o = [],
                                            r = 1 - s.curveTightness;
                                        n.push({
                                            op: "move",
                                            data: [t[1][0], t[1][1]]
                                        });
                                        for (let e = 1; e + 2 < i; e++) {
                                            const s = t[e];
                                            o[0] = [s[0], s[1]], o[1] = [s[0] + (r * t[e + 1][0] - r * t[e - 1][0]) / 6, s[1] + (r * t[e + 1][1] - r * t[e - 1][1]) / 6], o[2] = [t[e + 1][0] + (r * t[e][0] - r * t[e + 2][0]) / 6, t[e + 1][1] + (r * t[e][1] - r * t[e + 2][1]) / 6], o[3] = [t[e + 1][0], t[e + 1][1]], n.push({
                                                op: "bcurveTo",
                                                data: [o[1][0], o[1][1], o[2][0], o[2][1], o[3][0], o[3][1]]
                                            });
                                        }
                                        if (e && 2 === e.length) {
                                            const t = s.maxRandomnessOffset;
                                            n.push({
                                                op: "lineTo",
                                                data: [e[0] + a(t, s), e[1] + a(t, s)]
                                            });
                                        }
                                    } else 3 === i ? (n.push({
                                        op: "move",
                                        data: [t[1][0], t[1][1]]
                                    }), n.push({
                                        op: "bcurveTo",
                                        data: [t[1][0], t[1][1], t[2][0], t[2][1], t[2][0], t[2][1]]
                                    })) : 2 === i && n.push(...c(t[0][0], t[0][1], t[1][0], t[1][1], s));
                                    return n;
                                }

                                function l(t, e, s, i, n, o, r, h) {
                                    const c = [],
                                        u = [],
                                        f = a(.5, h) - Math.PI / 2;
                                    u.push([a(o, h) + e + .9 * i * Math.cos(f - t), a(o, h) + s + .9 * n * Math.sin(f - t)]);
                                    for (let r = f; r < 2 * Math.PI + f - .01; r += t) {
                                        const t = [a(o, h) + e + i * Math.cos(r), a(o, h) + s + n * Math.sin(r)];
                                        c.push(t), u.push(t);
                                    }
                                    return u.push([a(o, h) + e + i * Math.cos(f + 2 * Math.PI + .5 * r), a(o, h) + s + n * Math.sin(f + 2 * Math.PI + .5 * r)]), u.push([a(o, h) + e + .98 * i * Math.cos(f + r), a(o, h) + s + .98 * n * Math.sin(f + r)]), u.push([a(o, h) + e + .9 * i * Math.cos(f + .5 * r), a(o, h) + s + .9 * n * Math.sin(f + .5 * r)]), [u, c];
                                }

                                function g(t, e) {
                                    return {
                                        maxRandomnessOffset: 2,
                                        roughness: "highlight" === t ? 3 : 1.5,
                                        bowing: 1,
                                        stroke: "#000",
                                        strokeWidth: 1.5,
                                        curveTightness: 0,
                                        curveFitting: .95,
                                        curveStepCount: 9,
                                        fillStyle: "hachure",
                                        fillWeight: -1,
                                        hachureAngle: -41,
                                        hachureGap: -1,
                                        dashOffset: -1,
                                        dashGap: -1,
                                        zigzagOffset: -1,
                                        combineNestedSvgPaths: !1,
                                        disableMultiStroke: "double" !== t,
                                        disableMultiStrokeFill: !1,
                                        seed: e
                                    };
                                }

                                function d(e, r, h, a, c, u) {
                                    const f = [];
                                    let l = h.strokeWidth || 2;
                                    const d = function (t) {
                                        const e = t.padding;
                                        if (e || 0 === e) {
                                            if ("number" == typeof e) return [e, e, e, e];
                                            if (Array.isArray(e)) {
                                                const t = e;
                                                if (t.length) switch (t.length) {
                                                    case 4:
                                                        return [...t];
                                                    case 1:
                                                        return [t[0], t[0], t[0], t[0]];
                                                    case 2:
                                                        return [...t, ...t];
                                                    case 3:
                                                        return [...t, t[1]];
                                                    default:
                                                        return [t[0], t[1], t[2], t[3]];
                                                }
                                            }
                                        }
                                        return [5, 5, 5, 5];
                                    }(h),
                                        p = void 0 === h.animate || !!h.animate,
                                        _ = h.iterations || 2,
                                        m = h.rtl ? 1 : 0,
                                        w = g("single", u);
                                    switch (h.type) {
                                        case "underline": {
                                            const t = r.y + r.h + d[2];
                                            for (let e = m; e < _ + m; e++) e % 2 ? f.push(s(r.x + r.w, t, r.x, t, w)) : f.push(s(r.x, t, r.x + r.w, t, w));
                                            break;
                                        }
                                        case "strike-through": {
                                            const t = r.y + r.h / 2;
                                            for (let e = m; e < _ + m; e++) e % 2 ? f.push(s(r.x + r.w, t, r.x, t, w)) : f.push(s(r.x, t, r.x + r.w, t, w));
                                            break;
                                        }
                                        case "box": {
                                            const t = r.x - d[3],
                                                e = r.y - d[0],
                                                s = r.w + (d[1] + d[3]),
                                                i = r.h + (d[0] + d[2]);
                                            for (let o = 0; o < _; o++) f.push(n(t, e, s, i, w));
                                            break;
                                        }
                                        case "bracket": {
                                            const t = Array.isArray(h.brackets) ? h.brackets : h.brackets ? [h.brackets] : ["right"],
                                                e = r.x - 2 * d[3],
                                                s = r.x + r.w + 2 * d[1],
                                                n = r.y - 2 * d[0],
                                                o = r.y + r.h + 2 * d[2];
                                            for (const h of t) {
                                                let t;
                                                switch (h) {
                                                    case "bottom":
                                                        t = [
                                                            [e, r.y + r.h],
                                                            [e, o],
                                                            [s, o],
                                                            [s, r.y + r.h]
                                                        ];
                                                        break;
                                                    case "top":
                                                        t = [
                                                            [e, r.y],
                                                            [e, n],
                                                            [s, n],
                                                            [s, r.y]
                                                        ];
                                                        break;
                                                    case "left":
                                                        t = [
                                                            [r.x, n],
                                                            [e, n],
                                                            [e, o],
                                                            [r.x, o]
                                                        ];
                                                        break;
                                                    case "right":
                                                        t = [
                                                            [r.x + r.w, n],
                                                            [s, n],
                                                            [s, o],
                                                            [r.x + r.w, o]
                                                        ];
                                                }
                                                t && f.push(i(t, !1, w));
                                            }
                                            break;
                                        }
                                        case "crossed-off": {
                                            const t = r.x,
                                                e = r.y,
                                                i = t + r.w,
                                                n = e + r.h;
                                            for (let o = m; o < _ + m; o++) o % 2 ? f.push(s(i, n, t, e, w)) : f.push(s(t, e, i, n, w));
                                            for (let o = m; o < _ + m; o++) o % 2 ? f.push(s(t, n, i, e, w)) : f.push(s(i, e, t, n, w));
                                            break;
                                        }
                                        case "circle": {
                                            const t = g("double", u),
                                                e = r.w + (d[1] + d[3]),
                                                s = r.h + (d[0] + d[2]),
                                                i = r.x - d[3] + e / 2,
                                                n = r.y - d[0] + s / 2,
                                                h = Math.floor(_ / 2),
                                                a = _ - 2 * h;
                                            for (let r = 0; r < h; r++) f.push(o(i, n, e, s, t));
                                            for (let t = 0; t < a; t++) f.push(o(i, n, e, s, w));
                                            break;
                                        }
                                        case "highlight": {
                                            const t = g("highlight", u);
                                            l = .95 * r.h;
                                            const e = r.y + r.h / 2;
                                            for (let i = m; i < _ + m; i++) i % 2 ? f.push(s(r.x + r.w, e, r.x, e, t)) : f.push(s(r.x, e, r.x + r.w, e, t));
                                            break;
                                        }
                                    }
                                    if (f.length) {
                                        const s = function (t) {
                                            const e = [];
                                            for (const s of t) {
                                                let t = "";
                                                for (const i of s.ops) {
                                                    const s = i.data;
                                                    switch (i.op) {
                                                        case "move":
                                                            t.trim() && e.push(t.trim()), t = `M${s[0]} ${s[1]} `;
                                                            break;
                                                        case "bcurveTo":
                                                            t += `C${s[0]} ${s[1]}, ${s[2]} ${s[3]}, ${s[4]} ${s[5]} `;
                                                            break;
                                                        case "lineTo":
                                                            t += `L${s[0]} ${s[1]} `;
                                                    }
                                                }
                                                t.trim() && e.push(t.trim());
                                            }
                                            return e;
                                        }(f),
                                            i = [],
                                            n = [];
                                        let o = 0;
                                        const r = (t, e, s) => t.setAttribute(e, s);
                                        for (const a of s) {
                                            const s = document.createElementNS(t, "path");
                                            if (r(s, "d", a), r(s, "fill", "none"), r(s, "stroke", h.color || "currentColor"), r(s, "stroke-width", "" + l), p) {
                                                const t = s.getTotalLength();
                                                i.push(t), o += t;
                                            }
                                            e.appendChild(s), n.push(s);
                                        }
                                        if (p) {
                                            let t = 0;
                                            for (let e = 0; e < n.length; e++) {
                                                const s = n[e],
                                                    r = i[e],
                                                    h = o ? c * (r / o) : 0,
                                                    u = a + t,
                                                    f = s.style;
                                                f.strokeDashoffset = "" + r, f.strokeDasharray = "" + r, f.animation = `rough-notation-dash ${h}ms ease-out ${u}ms forwards`, t += h;
                                            }
                                        }
                                    }
                                }
                                class p {
                                    constructor(t, e) {
                                        this._state = "unattached", this._resizing = !1, this._seed = Math.floor(Math.random() * 2 ** 31), this._lastSizes = [], this._animationDelay = 0, this._resizeListener = () => {
                                            this._resizing || (this._resizing = !0, setTimeout(() => {
                                                this._resizing = !1, "showing" === this._state && this.haveRectsChanged() && this.show();
                                            }, 400));
                                        }, this._e = t, this._config = JSON.parse(JSON.stringify(e)), this.attach();
                                    }
                                    get animate() {
                                        return this._config.animate;
                                    }
                                    set animate(t) {
                                        this._config.animate = t;
                                    }
                                    get animationDuration() {
                                        return this._config.animationDuration;
                                    }
                                    set animationDuration(t) {
                                        this._config.animationDuration = t;
                                    }
                                    get iterations() {
                                        return this._config.iterations;
                                    }
                                    set iterations(t) {
                                        this._config.iterations = t;
                                    }
                                    get color() {
                                        return this._config.color;
                                    }
                                    set color(t) {
                                        this._config.color !== t && (this._config.color = t, this.refresh());
                                    }
                                    get strokeWidth() {
                                        return this._config.strokeWidth;
                                    }
                                    set strokeWidth(t) {
                                        this._config.strokeWidth !== t && (this._config.strokeWidth = t, this.refresh());
                                    }
                                    get padding() {
                                        return this._config.padding;
                                    }
                                    set padding(t) {
                                        this._config.padding !== t && (this._config.padding = t, this.refresh());
                                    }
                                    attach() {
                                        if ("unattached" === this._state && this._e.parentElement) {
                                            ! function () {
                                                if (!window.__rno_kf_s) {
                                                    const t = window.__rno_kf_s = document.createElement("style");
                                                    t.textContent = "@keyframes rough-notation-dash { to { stroke-dashoffset: 0; } }", document.head.appendChild(t);
                                                }
                                            }();
                                            const e = this._svg = document.createElementNS(t, "svg");
                                            e.setAttribute("class", "rough-annotation");
                                            const s = e.style;
                                            s.position = "absolute", s.top = "0", s.left = "0", s.overflow = "visible", s.pointerEvents = "none", s.width = "100px", s.height = "100px";
                                            const i = "highlight" === this._config.type;
                                            if (this._e.insertAdjacentElement(i ? "beforebegin" : "afterend", e), this._state = "not-showing", i) {
                                                const t = window.getComputedStyle(this._e).position;
                                                (!t || "static" === t) && (this._e.style.position = "relative");
                                            }
                                            this.attachListeners();
                                        }
                                    }
                                    detachListeners() {
                                        window.removeEventListener("resize", this._resizeListener), this._ro && this._ro.unobserve(this._e);
                                    }
                                    attachListeners() {
                                        this.detachListeners(), window.addEventListener("resize", this._resizeListener, {
                                            passive: !0
                                        }), !this._ro && "ResizeObserver" in window && (this._ro = new window.ResizeObserver(t => {
                                            for (const e of t) e.contentRect && this._resizeListener();
                                        })), this._ro && this._ro.observe(this._e);
                                    }
                                    haveRectsChanged() {
                                        if (this._lastSizes.length) {
                                            const t = this.rects();
                                            if (t.length !== this._lastSizes.length) return !0;
                                            for (let e = 0; e < t.length; e++)
                                                if (!this.isSameRect(t[e], this._lastSizes[e])) return !0;
                                        }
                                        return !1;
                                    }
                                    isSameRect(t, e) {
                                        const s = (t, e) => Math.round(t) === Math.round(e);
                                        return s(t.x, e.x) && s(t.y, e.y) && s(t.w, e.w) && s(t.h, e.h);
                                    }
                                    isShowing() {
                                        return "not-showing" !== this._state;
                                    }
                                    refresh() {
                                        this.isShowing() && !this.pendingRefresh && (this.pendingRefresh = Promise.resolve().then(() => {
                                            this.isShowing() && this.show(), delete this.pendingRefresh;
                                        }));
                                    }
                                    show() {
                                        switch (this._state) {
                                            case "unattached":
                                                break;
                                            case "showing":
                                                this.hide(), this._svg && this.render(this._svg, !0);
                                                break;
                                            case "not-showing":
                                                this.attach(), this._svg && this.render(this._svg, !1);
                                        }
                                    }
                                    hide() {
                                        if (this._svg)
                                            for (; this._svg.lastChild;) this._svg.removeChild(this._svg.lastChild);
                                        this._state = "not-showing";
                                    }
                                    remove() {
                                        this._svg && this._svg.parentElement && this._svg.parentElement.removeChild(this._svg), this._svg = void 0, this._state = "unattached", this.detachListeners();
                                    }
                                    render(t, e) {
                                        let s = this._config;
                                        e && (s = JSON.parse(JSON.stringify(this._config)), s.animate = !1);
                                        const i = this.rects();
                                        let n = 0;
                                        i.forEach(t => n += t.w);
                                        const o = s.animationDuration || 800;
                                        let r = 0;
                                        for (let e = 0; e < i.length; e++) {
                                            const h = o * (i[e].w / n);
                                            d(t, i[e], s, r + this._animationDelay, h, this._seed), r += h;
                                        }
                                        this._lastSizes = i, this._state = "showing";
                                    }
                                    rects() {
                                        const t = [];
                                        if (this._svg)
                                            if (this._config.multiline) {
                                                const e = this._e.getClientRects();
                                                for (let s = 0; s < e.length; s++) t.push(this.svgRect(this._svg, e[s]));
                                            } else t.push(this.svgRect(this._svg, this._e.getBoundingClientRect()));
                                        return t;
                                    }
                                    svgRect(t, e) {
                                        const s = t.getBoundingClientRect(),
                                            i = e;
                                        return {
                                            x: (i.x || i.left) - (s.x || s.left),
                                            y: (i.y || i.top) - (s.y || s.top),
                                            w: i.width,
                                            h: i.height
                                        };
                                    }
                                }

                                function _(t, e) {
                                    return new p(t, e);
                                }

                                function m(t) {
                                    let e = 0;
                                    for (const s of t) {
                                        const t = s;
                                        t._animationDelay = e;
                                        e += 0 === t.animationDuration ? 0 : t.animationDuration || 800;
                                    }
                                    const s = [...t];
                                    return {
                                        show() {
                                            for (const t of s) t.show();
                                        },
                                        hide() {
                                            for (const t of s) t.hide();
                                        }
                                    };
                                }

                                var a1 = _(n1, options);
                                a1.show();
                            });
                        }

                    }

                });


            }

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
            elementorFrontend.elementsHandler.addHandler(Notation, {
                $element: $scope
            });
        });

    });

}(jQuery, window.elementorFrontend));
