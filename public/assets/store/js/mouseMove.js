! function (t) {
  var e = {};

  function r(n) {
    if (e[n]) return e[n].exports;
    var o = e[n] = {
      i: n,
      l: !1,
      exports: {}
    };
    return t[n].call(o.exports, o, o.exports, r), o.l = !0, o.exports
  }
  r.m = t, r.c = e, r.d = function (t, e, n) {
    r.o(t, e) || Object.defineProperty(t, e, {
      enumerable: !0,
      get: n
    })
  }, r.r = function (t) {
    "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
      value: "Module"
    }), Object.defineProperty(t, "__esModule", {
      value: !0
    })
  }, r.t = function (t, e) {
    if (1 & e && (t = r(t)), 8 & e) return t;
    if (4 & e && "object" == typeof t && t && t.__esModule) return t;
    var n = Object.create(null);
    if (r.r(n), Object.defineProperty(n, "default", {
        enumerable: !0,
        value: t
      }), 2 & e && "string" != typeof t)
      for (var o in t) r.d(n, o, function (e) {
        return t[e]
      }.bind(null, o));
    return n
  }, r.n = function (t) {
    var e = t && t.__esModule ? function () {
      return t.default
    } : function () {
      return t
    };
    return r.d(e, "a", e), e
  }, r.o = function (t, e) {
    return Object.prototype.hasOwnProperty.call(t, e)
  }, r.p = "", r(r.s = 24)
}([function (t, e) {
  var r = t.exports = "undefined" != typeof window && window.Math == Math ? window : "undefined" != typeof self && self.Math == Math ? self : Function("return this")();
  "number" == typeof __g && (__g = r)
}, function (t, e, r) {
  var n = r(7),
    o = r(18);
  t.exports = r(3) ? function (t, e, r) {
    return n.f(t, e, o(1, r))
  } : function (t, e, r) {
    return t[e] = r, t
  }
}, function (t, e, r) {
  var n = r(14)("wks"),
    o = r(6),
    i = r(0).Symbol,
    s = "function" == typeof i;
  (t.exports = function (t) {
    return n[t] || (n[t] = s && i[t] || (s ? i : o)("Symbol." + t))
  }).store = n
}, function (t, e, r) {
  t.exports = !r(16)(function () {
    return 7 != Object.defineProperty({}, "a", {
      get: function () {
        return 7
      }
    }).a
  })
}, function (t, e) {
  var r = {}.hasOwnProperty;
  t.exports = function (t, e) {
    return r.call(t, e)
  }
}, function (t, e) {
  var r = t.exports = {
    version: "2.5.7"
  };
  "number" == typeof __e && (__e = r)
}, function (t, e) {
  var r = 0,
    n = Math.random();
  t.exports = function (t) {
    return "Symbol(".concat(void 0 === t ? "" : t, ")_", (++r + n).toString(36))
  }
}, function (t, e, r) {
  var n = r(8),
    o = r(28),
    i = r(29),
    s = Object.defineProperty;
  e.f = r(3) ? Object.defineProperty : function (t, e, r) {
    if (n(t), e = i(e, !0), n(r), o) try {
      return s(t, e, r)
    } catch (t) {}
    if ("get" in r || "set" in r) throw TypeError("Accessors not supported!");
    return "value" in r && (t[e] = r.value), t
  }
}, function (t, e, r) {
  var n = r(9);
  t.exports = function (t) {
    if (!n(t)) throw TypeError(t + " is not an object!");
    return t
  }
}, function (t, e) {
  t.exports = function (t) {
    return "object" == typeof t ? null !== t : "function" == typeof t
  }
}, function (t, e) {
  t.exports = {}
}, function (t, e, r) {
  var n = r(31),
    o = r(19);
  t.exports = function (t) {
    return n(o(t))
  }
}, function (t, e, r) {
  var n = r(0),
    o = r(1),
    i = r(4),
    s = r(6)("src"),
    u = Function.toString,
    c = ("" + u).split("toString");
  r(5).inspectSource = function (t) {
    return u.call(t)
  }, (t.exports = function (t, e, r, u) {
    var a = "function" == typeof r;
    a && (i(r, "name") || o(r, "name", e)), t[e] !== r && (a && (i(r, s) || o(r, s, t[e] ? "" + t[e] : c.join(String(e)))), t === n ? t[e] = r : u ? t[e] ? t[e] = r : o(t, e, r) : (delete t[e], o(t, e, r)))
  })(Function.prototype, "toString", function () {
    return "function" == typeof this && this[s] || u.call(this)
  })
}, function (t, e, r) {
  var n = r(14)("keys"),
    o = r(6);
  t.exports = function (t) {
    return n[t] || (n[t] = o(t))
  }
}, function (t, e, r) {
  var n = r(5),
    o = r(0),
    i = o["__core-js_shared__"] || (o["__core-js_shared__"] = {});
  (t.exports = function (t, e) {
    return i[t] || (i[t] = void 0 !== e ? e : {})
  })("versions", []).push({
    version: n.version,
    mode: r(15) ? "pure" : "global",
    copyright: "?? 2018 Denis Pushkarev (zloirock.ru)"
  })
}, function (t, e) {
  t.exports = !1
}, function (t, e) {
  t.exports = function (t) {
    try {
      return !!t()
    } catch (t) {
      return !0
    }
  }
}, function (t, e, r) {
  var n = r(9),
    o = r(0).document,
    i = n(o) && n(o.createElement);
  t.exports = function (t) {
    return i ? o.createElement(t) : {}
  }
}, function (t, e) {
  t.exports = function (t, e) {
    return {
      enumerable: !(1 & t),
      configurable: !(2 & t),
      writable: !(4 & t),
      value: e
    }
  }
}, function (t, e) {
  t.exports = function (t) {
    if (void 0 == t) throw TypeError("Can't call method on  " + t);
    return t
  }
}, function (t, e, r) {
  var n = r(40),
    o = r(22);
  t.exports = Object.keys || function (t) {
    return n(t, o)
  }
}, function (t, e) {
  var r = Math.ceil,
    n = Math.floor;
  t.exports = function (t) {
    return isNaN(t = +t) ? 0 : (t > 0 ? n : r)(t)
  }
}, function (t, e) {
  t.exports = "constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(",")
}, function (t, e, r) {
  var n = r(7).f,
    o = r(4),
    i = r(2)("toStringTag");
  t.exports = function (t, e, r) {
    t && !o(t = r ? t : t.prototype, i) && n(t, i, {
      configurable: !0,
      value: e
    })
  }
}, function (t, e, r) {
  t.exports = r(47)
}, function (t, e, r) {
  for (var n = r(26), o = r(20), i = r(12), s = r(0), u = r(1), c = r(10), a = r(2), l = a("iterator"), f = a("toStringTag"), p = c.Array, h = {
      CSSRuleList: !0,
      CSSStyleDeclaration: !1,
      CSSValueList: !1,
      ClientRectList: !1,
      DOMRectList: !1,
      DOMStringList: !1,
      DOMTokenList: !0,
      DataTransferItemList: !1,
      FileList: !1,
      HTMLAllCollection: !1,
      HTMLCollection: !1,
      HTMLFormElement: !1,
      HTMLSelectElement: !1,
      MediaList: !0,
      MimeTypeArray: !1,
      NamedNodeMap: !1,
      NodeList: !0,
      PaintRequestList: !1,
      Plugin: !1,
      PluginArray: !1,
      SVGLengthList: !1,
      SVGNumberList: !1,
      SVGPathSegList: !1,
      SVGPointList: !1,
      SVGStringList: !1,
      SVGTransformList: !1,
      SourceBufferList: !1,
      StyleSheetList: !0,
      TextTrackCueList: !1,
      TextTrackList: !1,
      TouchList: !1
    }, v = o(h), d = 0; d < v.length; d++) {
    var y, m = v[d],
      g = h[m],
      x = s[m],
      w = x && x.prototype;
    if (w && (w[l] || u(w, l, p), w[f] || u(w, f, m), c[m] = p, g))
      for (y in n) w[y] || i(w, y, n[y], !0)
  }
}, function (t, e, r) {
  "use strict";
  var n = r(27),
    o = r(30),
    i = r(10),
    s = r(11);
  t.exports = r(33)(Array, "Array", function (t, e) {
    this._t = s(t), this._i = 0, this._k = e
  }, function () {
    var t = this._t,
      e = this._k,
      r = this._i++;
    return !t || r >= t.length ? (this._t = void 0, o(1)) : o(0, "keys" == e ? r : "values" == e ? t[r] : [r, t[r]])
  }, "values"), i.Arguments = i.Array, n("keys"), n("values"), n("entries")
}, function (t, e, r) {
  var n = r(2)("unscopables"),
    o = Array.prototype;
  void 0 == o[n] && r(1)(o, n, {}), t.exports = function (t) {
    o[n][t] = !0
  }
}, function (t, e, r) {
  t.exports = !r(3) && !r(16)(function () {
    return 7 != Object.defineProperty(r(17)("div"), "a", {
      get: function () {
        return 7
      }
    }).a
  })
}, function (t, e, r) {
  var n = r(9);
  t.exports = function (t, e) {
    if (!n(t)) return t;
    var r, o;
    if (e && "function" == typeof (r = t.toString) && !n(o = r.call(t))) return o;
    if ("function" == typeof (r = t.valueOf) && !n(o = r.call(t))) return o;
    if (!e && "function" == typeof (r = t.toString) && !n(o = r.call(t))) return o;
    throw TypeError("Can't convert object to primitive value")
  }
}, function (t, e) {
  t.exports = function (t, e) {
    return {
      value: e,
      done: !!t
    }
  }
}, function (t, e, r) {
  var n = r(32);
  t.exports = Object("z").propertyIsEnumerable(0) ? Object : function (t) {
    return "String" == n(t) ? t.split("") : Object(t)
  }
}, function (t, e) {
  var r = {}.toString;
  t.exports = function (t) {
    return r.call(t).slice(8, -1)
  }
}, function (t, e, r) {
  "use strict";
  var n = r(15),
    o = r(34),
    i = r(12),
    s = r(1),
    u = r(10),
    c = r(37),
    a = r(23),
    l = r(45),
    f = r(2)("iterator"),
    p = !([].keys && "next" in [].keys()),
    h = function () {
      return this
    };
  t.exports = function (t, e, r, v, d, y, m) {
    c(r, e, v);
    var g, x, w, b = function (t) {
        if (!p && t in L) return L[t];
        switch (t) {
        case "keys":
        case "values":
          return function () {
            return new r(this, t)
          }
        }
        return function () {
          return new r(this, t)
        }
      },
      O = e + " Iterator",
      S = "values" == d,
      C = !1,
      L = t.prototype,
      k = L[f] || L["@@iterator"] || d && L[d],
      _ = k || b(d),
      P = d ? S ? b("entries") : _ : void 0,
      j = "Array" == e && L.entries || k;
    if (j && (w = l(j.call(new t))) !== Object.prototype && w.next && (a(w, O, !0), n || "function" == typeof w[f] || s(w, f, h)), S && k && "values" !== k.name && (C = !0, _ = function () {
        return k.call(this)
      }), n && !m || !p && !C && L[f] || s(L, f, _), u[e] = _, u[O] = h, d)
      if (g = {
          values: S ? _ : b("values"),
          keys: y ? _ : b("keys"),
          entries: P
        }, m)
        for (x in g) x in L || i(L, x, g[x]);
      else o(o.P + o.F * (p || C), e, g);
    return g
  }
}, function (t, e, r) {
  var n = r(0),
    o = r(5),
    i = r(1),
    s = r(12),
    u = r(35),
    c = function (t, e, r) {
      var a, l, f, p, h = t & c.F,
        v = t & c.G,
        d = t & c.S,
        y = t & c.P,
        m = t & c.B,
        g = v ? n : d ? n[e] || (n[e] = {}) : (n[e] || {}).prototype,
        x = v ? o : o[e] || (o[e] = {}),
        w = x.prototype || (x.prototype = {});
      for (a in v && (r = e), r) f = ((l = !h && g && void 0 !== g[a]) ? g : r)[a], p = m && l ? u(f, n) : y && "function" == typeof f ? u(Function.call, f) : f, g && s(g, a, f, t & c.U), x[a] != f && i(x, a, p), y && w[a] != f && (w[a] = f)
    };
  n.core = o, c.F = 1, c.G = 2, c.S = 4, c.P = 8, c.B = 16, c.W = 32, c.U = 64, c.R = 128, t.exports = c
}, function (t, e, r) {
  var n = r(36);
  t.exports = function (t, e, r) {
    if (n(t), void 0 === e) return t;
    switch (r) {
    case 1:
      return function (r) {
        return t.call(e, r)
      };
    case 2:
      return function (r, n) {
        return t.call(e, r, n)
      };
    case 3:
      return function (r, n, o) {
        return t.call(e, r, n, o)
      }
    }
    return function () {
      return t.apply(e, arguments)
    }
  }
}, function (t, e) {
  t.exports = function (t) {
    if ("function" != typeof t) throw TypeError(t + " is not a function!");
    return t
  }
}, function (t, e, r) {
  "use strict";
  var n = r(38),
    o = r(18),
    i = r(23),
    s = {};
  r(1)(s, r(2)("iterator"), function () {
    return this
  }), t.exports = function (t, e, r) {
    t.prototype = n(s, {
      next: o(1, r)
    }), i(t, e + " Iterator")
  }
}, function (t, e, r) {
  var n = r(8),
    o = r(39),
    i = r(22),
    s = r(13)("IE_PROTO"),
    u = function () {},
    c = function () {
      var t, e = r(17)("iframe"),
        n = i.length;
      for (e.style.display = "none", r(44).appendChild(e), e.src = "javascript:", (t = e.contentWindow.document).open(), t.write("<script>document.F=Object<\/script>"), t.close(), c = t.F; n--;) delete c.prototype[i[n]];
      return c()
    };
  t.exports = Object.create || function (t, e) {
    var r;
    return null !== t ? (u.prototype = n(t), r = new u, u.prototype = null, r[s] = t) : r = c(), void 0 === e ? r : o(r, e)
  }
}, function (t, e, r) {
  var n = r(7),
    o = r(8),
    i = r(20);
  t.exports = r(3) ? Object.defineProperties : function (t, e) {
    o(t);
    for (var r, s = i(e), u = s.length, c = 0; u > c;) n.f(t, r = s[c++], e[r]);
    return t
  }
}, function (t, e, r) {
  var n = r(4),
    o = r(11),
    i = r(41)(!1),
    s = r(13)("IE_PROTO");
  t.exports = function (t, e) {
    var r, u = o(t),
      c = 0,
      a = [];
    for (r in u) r != s && n(u, r) && a.push(r);
    for (; e.length > c;) n(u, r = e[c++]) && (~i(a, r) || a.push(r));
    return a
  }
}, function (t, e, r) {
  var n = r(11),
    o = r(42),
    i = r(43);
  t.exports = function (t) {
    return function (e, r, s) {
      var u, c = n(e),
        a = o(c.length),
        l = i(s, a);
      if (t && r != r) {
        for (; a > l;)
          if ((u = c[l++]) != u) return !0
      } else
        for (; a > l; l++)
          if ((t || l in c) && c[l] === r) return t || l || 0;
      return !t && -1
    }
  }
}, function (t, e, r) {
  var n = r(21),
    o = Math.min;
  t.exports = function (t) {
    return t > 0 ? o(n(t), 9007199254740991) : 0
  }
}, function (t, e, r) {
  var n = r(21),
    o = Math.max,
    i = Math.min;
  t.exports = function (t, e) {
    return (t = n(t)) < 0 ? o(t + e, 0) : i(t, e)
  }
}, function (t, e, r) {
  var n = r(0).document;
  t.exports = n && n.documentElement
}, function (t, e, r) {
  var n = r(4),
    o = r(46),
    i = r(13)("IE_PROTO"),
    s = Object.prototype;
  t.exports = Object.getPrototypeOf || function (t) {
    return t = o(t), n(t, i) ? t[i] : "function" == typeof t.constructor && t instanceof t.constructor ? t.constructor.prototype : t instanceof Object ? s : null
  }
}, function (t, e, r) {
  var n = r(19);
  t.exports = function (t) {
    return Object(n(t))
  }
}, function (t, e, r) {
  "use strict";
  r.r(e);
  r(25);

  function n(t, e) {
    for (var r = 0; r < e.length; r++) {
      var n = e[r];
      n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n)
    }
  }
  var o = function () {
    function t(e) {
      ! function (t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
      }(this, t), this.params = e || {}, this.wrapperClass = this.params.hasOwnProperty("wrapperClass") ? e.wrapperClass : ".cm-pointer", this.cursorClass = this.params.hasOwnProperty("cursorClass") ? this.params.cursorClass : ".cm-pointer__dot", this.cursorOverClass = this.params.hasOwnProperty("cursorOverClass") ? this.params.cursorOverClass : ".cm-pointer__over", this.linkClass = this.params.hasOwnProperty("linkClass") ? this.params.linkClass : ".cm-link", this.visibleClass = "is-visible", this.hideClass = "is-hide", this.hoverClass = "is-hover", this.activeClass = "is-active", this.$wrapper = null, this.$cursor = null, this.$cursorOver = null, this.$targetLink = null, this.cursorActived = !1, this.cursorHover = !1, this.cursorShown = !1, this.breakpoint = 768, this.delay = 8, this.x = 0, this.y = 0, this.endX = 0, this.endY = 0
    }
    return function (t, e, r) {
      e && n(t.prototype, e), r && n(t, r)
    }(t, [{
      key: "init",
      value: function () {
        this.setup(), this.setupEventListeners(), this.animateMoveOver()
      }
    }, {
      key: "setup",
      value: function () {
        this.$wrapper = document.querySelector(this.wrapperClass), this.$cursor = document.querySelector(this.cursorClass), this.$cursorOver = document.querySelector(this.cursorOverClass), this.$targetLink = document.querySelectorAll(this.linkClass), this.endX = window.innerWidth / 2, this.endY = window.innerHeight / 2
      }
    }, {
      key: "setupEventListeners",
      value: function () {
        var t = this;
        this.$targetLink.forEach(function (e) {
          e.addEventListener("mouseover", function () {
            t.cursorHover = !0, t.toggleCursorHover()
          }), e.addEventListener("mouseout", function () {
            t.cursorHover = !1, t.toggleCursorHover()
          })
        }), window.matchMedia("screen and (max-width: ".concat(this.breakpoint, "px)")).matches ? this.$wrapper.style.display = "none" : this.$wrapper.style.display = "block", window.addEventListener("resize", function () {
          window.matchMedia("screen and (max-width: ".concat(t.breakpoint, "px)")).matches ? t.$wrapper.style.display = "none" : t.$wrapper.style.display = "block"
        }), document.addEventListener("mousedown", function () {
          t.cursorActived = !0, t.toggleCursorActive()
        }), document.addEventListener("mouseup", function () {
          t.cursorActived = !1, t.toggleCursorActive()
        }), document.addEventListener("mousemove", function (e) {
          t.cursorShown = !0, t.toggleCursorShown(), t.handlePosition(e)
        }), document.addEventListener("mouseenter", function (e) {
          t.cursorShown = !0, t.toggleCursorShown()
        }), document.addEventListener("mouseleave", function (e) {
          t.cursorShown = !1, t.toggleCursorShown()
        })
      }
    }, {
      key: "handlePosition",
      value: function (t) {
        this.endX = t.clientX, this.endY = t.clientY, this.$cursor.style.top = this.endY + "px", this.$cursor.style.left = this.endX + "px"
      }
    }, {
      key: "animateMoveOver",
      value: function () {
        this.x += (this.endX - this.x) / this.delay, this.y += (this.endY - this.y) / this.delay, this.$cursorOver.style.top = this.y + "px", this.$cursorOver.style.left = this.x + "px", requestAnimationFrame(this.animateMoveOver.bind(this))
      }
    }, {
      key: "toggleCursorShown",
      value: function () {
        this.cursorShown ? (this.$cursorOver.classList.remove(this.hideClass), this.$cursor.classList.remove(this.hideClass), this.$cursorOver.classList.add(this.visibleClass), this.$cursor.classList.add(this.visibleClass)) : (this.$cursorOver.classList.remove(this.visibleClass), this.$cursor.classList.remove(this.visibleClass), this.$cursorOver.classList.add(this.hideClass), this.$cursor.classList.add(this.hideClass))
      }
    }, {
      key: "toggleCursorHover",
      value: function () {
        this.cursorHover ? this.$cursorOver.classList.add(this.hoverClass) : this.$cursorOver.classList.remove(this.hoverClass)
      }
    }, {
      key: "toggleCursorActive",
      value: function () {
        this.cursorActived ? this.$cursorOver.classList.add(this.activeClass) : this.$cursorOver.classList.remove(this.activeClass)
      }
    }]), t
  }();
  window.addEventListener("load", function () {
    new o({
      wrapperClass: ".cm-pointer"
    }).init()
  })
}]);