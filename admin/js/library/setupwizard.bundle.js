!(function (e, t) {
    "object" == typeof exports && "object" == typeof module ? (module.exports = t()) : "function" == typeof define && define.amd ? define([], t) : "object" == typeof exports ? (exports.rexWizard = t()) : (e.rexWizard = t());
})(this, () => {
    return (
        (e = {
            987: (e) => {
                function t(e, t) {
                    var n = document.createElement("style");
                    (n.type = "text/css"), (n.innerHTML = "." + e + " {" + t + "}"), document.head.appendChild(n);
                }
                e.exports = function e(n) {
                    var i = n.general,
                        r = n.steps,
                        o = document.getElementById(null == i ? void 0 : i.targetElement) || d(null == i ? void 0 : i.targetElement, document.body),
                        l = document.getElementById("progressBarContainer") || d("progressBarContainer", null == o ? void 0 : o.parentNode, o);
                    function d(e, t) {
                        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : null,
                            i = document.createElement("div");
                        return (i.id = e), i.setAttribute("style", "display:flex; align-items:center; justify-content:space-around"), n ? t.insertBefore(i, n) : t.appendChild(i), i;
                    }
                    function a(e, n, i) {
                        e.innerHTML = "";
                        var r,
                            o,
                            l = document.createElement("div"),
                            d = "setup-wizard__pregress-bar";
                        t(d, "width:100%; text-align: center; display:flex;"),
                            (o = d),
                            (r = l).classList ? r.classList.add(o) : (r.className += " " + o),
                        null == n ||
                        n.forEach(function (e, n) {
                            var r = document.createElement("div");
                            (r.innerHTML = ""),
                                (r.innerHTML =
                                    n === i || n > i
                                        ? "  <p> <span>  ".concat(n + 1, "  </span>  ").concat(null == e ? void 0 : e.stepText, "  </p> ")
                                        : '\n              <p>\n                  <span>\n                      <svg width="8" height="8" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">\n                          <path d="M0.935499 4.11026C0.75836 4.11077 0.584981 4.16272 0.435422 4.26009C0.285862 4.35746 0.166233 4.49629 0.0903747 4.66049C0.0145167 4.8247 -0.0144703 5.00759 0.00676735 5.18799C0.028005 5.36839 0.0985994 5.53894 0.210383 5.67991L2.59335 8.67442C2.67832 8.78265 2.78723 8.86851 2.91104 8.92489C3.03485 8.98127 3.16999 9.00652 3.30522 8.99857C3.59447 8.98263 3.8556 8.82391 4.02209 8.56294L8.97213 0.385047L8.97465 0.380997C9.0211 0.307842 9.00604 0.162868 8.91017 0.0717941C8.88383 0.0467839 8.85279 0.0275693 8.81894 0.0153333C8.78509 0.00309728 8.74916 -0.00190095 8.71336 0.00064652C8.67755 0.00319399 8.64264 0.0132332 8.61076 0.0301458C8.57888 0.0470584 8.55072 0.0704864 8.528 0.0989868C8.52623 0.101228 8.5244 0.103435 8.52251 0.105608L3.53034 5.89168C3.51134 5.91369 3.48827 5.93162 3.46246 5.94442C3.43666 5.95721 3.40863 5.96462 3.38001 5.9662C3.35139 5.96779 3.32274 5.96353 3.29574 5.95367C3.26874 5.9438 3.24392 5.92853 3.22272 5.90874L1.56591 4.3621C1.39384 4.20029 1.16893 4.11044 0.935499 4.11026Z"\n                              fill="white" />\n                      </svg> \n                  </span> \n                  '.concat(
                                            null == e ? void 0 : e.stepText,
                                            "\n              </p>\n          "
                                        ));
                            var o = "setup-wizard__pregress-step";
                            t(o, "display: inline-block; position: relative; padding: 15px 68.5px 15px 16px; background: #E5E8F3;"),
                                r.classList.add(o),
                                r.classList.add(n < i ? "step-visited" : n === i ? "step-active" : "step-not-visited"),
                                l.appendChild(r);
                        }),
                            e.appendChild(l);
                    }
                    function p(e, t, n) {
                        var i, r, o;
                        if (((e.innerHTML = ""), null !== (i = t[null == n ? void 0 : n.currentStep]) && void 0 !== i && i.title)) {
                            var l,
                                d = document.createElement("h1");
                            (d.textContent = null === (l = t[null == n ? void 0 : n.currentStep]) || void 0 === l ? void 0 : l.title), e.appendChild(d);
                        }
                        if (null !== (r = t[null == n ? void 0 : n.currentStep]) && void 0 !== r && r.description) {
                            var a,
                                p = document.createElement("p");
                            (p.textContent = null === (a = t[null == n ? void 0 : n.currentStep]) || void 0 === a ? void 0 : a.description), e.appendChild(p);
                        }
                        var u = document.createElement("div");
                        (u.innerHTML = null === (o = t[null == n ? void 0 : n.currentStep]) || void 0 === o ? void 0 : o.html), (u.className = "active-step"), e.appendChild(u);
                    }
                    function u(e) {
                        e < r.length && e >= 0 && ((i.currentStep = e), p(o, r, i), a(l, r, null == i ? void 0 : i.currentStep));
                    }
                    return (
                        (function (e, t, n) {
                            var i = e.logo,
                                r = e.logoStyles,
                                o = document.getElementById("wizardLogo");
                            !o && i ? (((o = document.createElement("img")).id = "wizardLogo"), (o.src = i), (o.alt = "Wizard Logo"), o.classList.add(r || ""), t.insertBefore(o, n)) : o && i && ((o.src = i), o.classList.add(r || ""));
                        })(i, null == l ? void 0 : l.parentNode, l),
                            a(l, r, null == i ? void 0 : i.currentStep),
                            p(o, r, i),
                            {
                                rexWizard: e,
                                previousStep: function () {
                                    u((null == i ? void 0 : i.currentStep) - 1);
                                },
                                nextStep: function () {
                                    u((null == i ? void 0 : i.currentStep) + 1);
                                },
                                skipStep: function () {
                                    var e, t;
                                    (e = null == i ? void 0 : i.currentStep),
                                        u(
                                            -1 !==
                                            (t =
                                                null == r
                                                    ? void 0
                                                    : r.findIndex(function (t, n) {
                                                        return n > e && !t.isSkip;
                                                    }))
                                                ? t
                                                : (null == r ? void 0 : r.length) - 1
                                        );
                                },
                                getCurrentStep: function () {
                                    return null == i ? void 0 : i.currentStep;
                                },
                            }
                    );
                };
            },
        }),
            (t = {}),
            (function n(i) {
                var r = t[i];
                if (void 0 !== r) return r.exports;
                var o = (t[i] = { exports: {} });
                return e[i](o, o.exports, n), o.exports;
            })(987)
    );
    var e, t;
});
