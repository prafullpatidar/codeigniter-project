/*!
 * froala_editor v2.8.5 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms/
 * Copyright 2014-2018 Froala Labs
 */

! function(a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof module && module.exports ? module.exports = function(e, t) {
        return t === undefined && (t = "undefined" != typeof window ? require("jquery") : require("jquery")(e)), a(t)
    } : a(window.jQuery)
}(function(ye) {
    ye.extend(ye.FE.POPUP_TEMPLATES, {
        "image.insert": "[_BUTTONS_][_UPLOAD_LAYER_][_BY_URL_LAYER_][_PROGRESS_BAR_]",
        "image.edit": "[_BUTTONS_]",
        "image.alt": "[_BUTTONS_][_ALT_LAYER_]",
        "image.size": "[_BUTTONS_][_SIZE_LAYER_]"
    }), ye.extend(ye.FE.DEFAULTS, {
        imageInsertButtons: ["imageBack", "|", "imageUpload", "imageByURL"],
        imageEditButtons: ["imageReplace", "imageAlign", "imageCaption", "imageRemove", "|", "imageLink", "linkOpen", "linkEdit", "linkRemove", "-", "imageDisplay", "imageStyle", "imageAlt", "imageSize"],
        imageAltButtons: ["imageBack", "|"],
        imageSizeButtons: ["imageBack", "|"],
        imageUpload: !0,
        imageUploadURL: null,
        imageCORSProxy: "https://cors-anywhere.froala.com",
        imageUploadRemoteUrls: !0,
        imageUploadParam: "file",
        imageUploadParams: {},
        imageUploadToS3: !1,
        imageUploadMethod: "POST",
        imageMaxSize: 10485760,
        imageAllowedTypes: ["jpeg", "jpg", "png", "gif"],
        imageResize: !0,
        imageResizeWithPercent: !1,
        imageRoundPercent: !1,
        imageDefaultWidth: 300,
        imageDefaultAlign: "center",
        imageDefaultDisplay: "block",
        imageSplitHTML: !1,
        imageStyles: {
            "fr-rounded": "Rounded",
            "fr-bordered": "Bordered",
            "fr-shadow": "Shadow"
        },
        imageMove: !0,
        imageMultipleStyles: !0,
        imageTextNear: !0,
        imagePaste: !0,
        imagePasteProcess: !1,
        imageMinWidth: 16,
        imageOutputSize: !1,
        imageDefaultMargin: 5
    }), ye.FE.PLUGINS.image = function(p) {
        var g, l, f, d, o, a, u = "https://i.froala.com/upload",
            t = !1,
            i = 1,
            c = 2,
            m = 3,
            h = 4,
            v = 5,
            b = 6,
            y = 8,
            r = {};

        function w() {
            var e = p.popups.get("image.insert").find(".fr-image-by-url-layer input");
            e.val(""), g && e.val(g.attr("src")), e.trigger("change")
        }

        function s() {
            var e = p.popups.get("image.edit");
            if (e || (e = $()), e) {
                var t = ve();
                be() && (t = t.find(".fr-img-wrap")), p.popups.setContainer("image.edit", p.$sc), p.popups.refresh("image.edit");
                var a = t.offset().left + t.outerWidth() / 2,
                    i = t.offset().top + t.outerHeight();
                p.popups.show("image.edit", a, i, t.outerHeight())
            }
        }

        function E() {
            F()
        }

        function e() {
            for (var e, t, a = "IMG" == p.el.tagName ? [p.el] : p.el.querySelectorAll("img"), i = 0; i < a.length; i++) {
                var r = ye(a[i]);
                !p.opts.htmlUntouched && p.opts.useClasses ? ((p.opts.imageDefaultAlign || p.opts.imageDefaultDisplay) && (0 < (t = r).parents(".fr-img-caption").length && (t = t.parents(".fr-img-caption:first")), t.hasClass("fr-dii") || t.hasClass("fr-dib") || (t.addClass("fr-fi" + de(t)[0]), t.addClass("fr-di" + ue(t)[0]), t.css("margin", ""), t.css("float", ""), t.css("display", ""), t.css("z-index", ""), t.css("position", ""), t.css("overflow", ""), t.css("vertical-align", ""))), p.opts.imageTextNear || (0 < r.parents(".fr-img-caption").length ? r.parents(".fr-img-caption:first").removeClass("fr-dii").addClass("fr-dib") : r.removeClass("fr-dii").addClass("fr-dib"))) : p.opts.htmlUntouched || p.opts.useClasses || (p.opts.imageDefaultAlign || p.opts.imageDefaultDisplay) && (0 < (e = r).parents(".fr-img-caption").length && (e = e.parents(".fr-img-caption:first")), ge(e, e.hasClass("fr-dib") ? "block" : e.hasClass("fr-dii") ? "inline" : null, e.hasClass("fr-fil") ? "left" : e.hasClass("fr-fir") ? "right" : de(e)), e.removeClass("fr-dib fr-dii fr-fir fr-fil")), p.opts.iframe && r.on("load", p.size.syncIframe)
            }
        }

        function C(e) {
            void 0 === e && (e = !0);
            var t, a = Array.prototype.slice.call(p.el.querySelectorAll("img")),
                i = [];
            for (t = 0; t < a.length; t++)
                if (i.push(a[t].getAttribute("src")), ye(a[t]).toggleClass("fr-draggable", p.opts.imageMove), "" === a[t].getAttribute("class") && a[t].removeAttribute("class"), "" === a[t].getAttribute("style") && a[t].removeAttribute("style"), a[t].parentNode && a[t].parentNode.parentNode && p.node.hasClass(a[t].parentNode.parentNode, "fr-img-caption")) {
                    var r = a[t].parentNode.parentNode;
                    p.browser.mozilla || p.browser.msie || r.setAttribute("contenteditable", !1), r.setAttribute("draggable", !1), r.classList.add("fr-draggable");
                    var s = a[t].nextSibling;
                    s && s.setAttribute("contenteditable", !0)
                }
            if (o)
                for (t = 0; t < o.length; t++) i.indexOf(o[t].getAttribute("src")) < 0 && p.events.trigger("image.removed", [ye(o[t])]);
            if (o && e) {
                var n = [];
                for (t = 0; t < o.length; t++) n.push(o[t].getAttribute("src"));
                for (t = 0; t < a.length; t++) n.indexOf(a[t].getAttribute("src")) < 0 && p.events.trigger("image.loaded", [ye(a[t])])
            }
            o = a
        }

        function A() {
            if (l || function() {
                    var e;
                    p.shared.$image_resizer ? (l = p.shared.$image_resizer, d = p.shared.$img_overlay, p.events.on("destroy", function() {
                        l.removeClass("fr-active").appendTo(ye("body:first"))
                    }, !0)) : (p.shared.$image_resizer = ye('<div class="fr-image-resizer"></div>'), l = p.shared.$image_resizer, p.events.$on(l, "mousedown", function(e) {
                        e.stopPropagation()
                    }, !0), p.opts.imageResize && (l.append(n("nw") + n("ne") + n("sw") + n("se")), p.shared.$img_overlay = ye('<div class="fr-image-overlay"></div>'), d = p.shared.$img_overlay, e = l.get(0).ownerDocument, ye(e).find("body:first").append(d)));
                    p.events.on("shared.destroy", function() {
                        l.html("").removeData().remove(), l = null, p.opts.imageResize && (d.remove(), d = null)
                    }, !0), p.helpers.isMobile() || p.events.$on(ye(p.o_win), "resize", function() {
                        g && !g.hasClass("fr-uploading") ? oe(!0) : g && (A(), ce(), I(!1))
                    });
                    if (p.opts.imageResize) {
                        e = l.get(0).ownerDocument, p.events.$on(l, p._mousedown, ".fr-handler", S), p.events.$on(ye(e), p._mousemove, D), p.events.$on(ye(e.defaultView || e.parentWindow), p._mouseup, x), p.events.$on(d, "mouseleave", x);
                        var i = 1,
                            r = null,
                            s = 0;
                        p.events.on("keydown", function(e) {
                            if (g) {
                                var t = -1 != navigator.userAgent.indexOf("Mac OS X") ? e.metaKey : e.ctrlKey,
                                    a = e.which;
                                (a !== r || 200 < e.timeStamp - s) && (i = 1), (a == ye.FE.KEYCODE.EQUALS || p.browser.mozilla && a == ye.FE.KEYCODE.FF_EQUALS) && t && !e.altKey ? i = Q.call(this, e, 1, 1, i) : (a == ye.FE.KEYCODE.HYPHEN || p.browser.mozilla && a == ye.FE.KEYCODE.FF_HYPHEN) && t && !e.altKey ? i = Q.call(this, e, 2, -1, i) : p.keys.ctrlKey(e) || a != ye.FE.KEYCODE.ENTER || (g.before("<br>"), B(g)), r = a, s = e.timeStamp
                            }
                        }, !0), p.events.on("keyup", function() {
                            i = 1
                        })
                    }
                }(), !g) return !1;
            var e = p.$wp || p.$sc;
            e.append(l), l.data("instance", p);
            var t = e.scrollTop() - ("static" != e.css("position") ? e.offset().top : 0),
                a = e.scrollLeft() - ("static" != e.css("position") ? e.offset().left : 0);
            a -= p.helpers.getPX(e.css("border-left-width")), t -= p.helpers.getPX(e.css("border-top-width")), p.$el.is("img") && p.$sc.is("body") && (a = t = 0);
            var i = ve();
            be() && (i = i.find(".fr-img-wrap")), l.css("top", (p.opts.iframe ? i.offset().top : i.offset().top + t) - 1).css("left", (p.opts.iframe ? i.offset().left : i.offset().left + a) - 1).css("width", i.get(0).getBoundingClientRect().width).css("height", i.get(0).getBoundingClientRect().height).addClass("fr-active")
        }

        function n(e) {
            return '<div class="fr-handler fr-h' + e + '"></div>'
        }

        function R(e) {
            be() ? g.parents(".fr-img-caption").css("width", e) : g.css("width", e)
        }

        function S(e) {
            if (!p.core.sameInstance(l)) return !0;
            if (e.preventDefault(), e.stopPropagation(), p.$el.find("img.fr-error").left) return !1;
            p.undo.canDo() || p.undo.saveStep();
            var t = e.pageX || e.originalEvent.touches[0].pageX;
            if ("mousedown" == e.type) {
                var a = p.$oel.get(0).ownerDocument,
                    i = a.defaultView || a.parentWindow,
                    r = !1;
                try {
                    r = i.location != i.parent.location && !(i.$ && i.$.FE)
                } catch (o) {}
                r && i.frameElement && (t += p.helpers.getPX(ye(i.frameElement).offset().left) + i.frameElement.clientLeft)
            }(f = ye(this)).data("start-x", t), f.data("start-width", g.width()), f.data("start-height", g.height());
            var s = g.width();
            if (p.opts.imageResizeWithPercent) {
                var n = g.parentsUntil(p.$el, p.html.blockTagsQuery()).get(0) || p.el;
                s = (s / ye(n).outerWidth() * 100).toFixed(2) + "%"
            }
            R(s), d.show(), p.popups.hideAll(), pe()
        }

        function D(e) {
            if (!p.core.sameInstance(l)) return !0;
            var t;
            if (f && g) {
                if (e.preventDefault(), p.$el.find("img.fr-error").left) return !1;
                var a = e.pageX || (e.originalEvent.touches ? e.originalEvent.touches[0].pageX : null);
                if (!a) return !1;
                var i = a - f.data("start-x"),
                    r = f.data("start-width");
                if ((f.hasClass("fr-hnw") || f.hasClass("fr-hsw")) && (i = 0 - i), p.opts.imageResizeWithPercent) {
                    var s = g.parentsUntil(p.$el, p.html.blockTagsQuery()).get(0) || p.el;
                    r = ((r + i) / ye(s).outerWidth() * 100).toFixed(2), p.opts.imageRoundPercent && (r = Math.round(r)), R(r + "%"), (t = be() ? (p.helpers.getPX(g.parents(".fr-img-caption").css("width")) / ye(s).outerWidth() * 100).toFixed(2) : (p.helpers.getPX(g.css("width")) / ye(s).outerWidth() * 100).toFixed(2)) === r || p.opts.imageRoundPercent || R(t + "%"), g.css("height", "").removeAttr("height")
                } else r + i >= p.opts.imageMinWidth && (R(r + i), t = be() ? p.helpers.getPX(g.parents(".fr-img-caption").css("width")) : p.helpers.getPX(g.css("width"))), t !== r + i && R(t), ((g.attr("style") || "").match(/(^height:)|(; *height:)/) || g.attr("height")) && (g.css("height", f.data("start-height") * g.width() / f.data("start-width")), g.removeAttr("height"));
                A(), p.events.trigger("image.resize", [he()])
            }
        }

        function x(e) {
            if (!p.core.sameInstance(l)) return !0;
            if (f && g) {
                if (e && e.stopPropagation(), p.$el.find("img.fr-error").left) return !1;
                f = null, d.hide(), A(), s(), p.undo.saveStep(), p.events.trigger("image.resizeEnd", [he()])
            }
        }

        function U(e, t, a) {
            p.edit.on(), g && g.addClass("fr-error"),
                function(e) {
                    I();
                    var t = p.popups.get("image.insert").find(".fr-image-progress-bar-layer");
                    t.addClass("fr-error");
                    var a = t.find("h3");
                    a.text(e), p.events.disableBlur(), a.focus()
                }(p.language.translate("Something went wrong. Please try again.")), !g && a && J(a), p.events.trigger("image.error", [{
                    code: e,
                    message: r[e]
                }, t, a])
        }

        function $(e) {
            if (e) return p.$wp && p.events.$on(p.$wp, "scroll", function() {
                g && p.popups.isVisible("image.edit") && (p.events.disableBlur(), B(g))
            }), !0;
            var t = "";
            if (0 < p.opts.imageEditButtons.length) {
                t += '<div class="fr-buttons">', t += p.button.buildList(p.opts.imageEditButtons);
                var a = {
                    buttons: t += "</div>"
                };
                return p.popups.create("image.edit", a)
            }
            return !1
        }

        function I(e) {
            var t = p.popups.get("image.insert");
            if (t || (t = H()), t.find(".fr-layer.fr-active").removeClass("fr-active").addClass("fr-pactive"), t.find(".fr-image-progress-bar-layer").addClass("fr-active"), t.find(".fr-buttons").hide(), g) {
                var a = ve();
                p.popups.setContainer("image.insert", p.$sc);
                var i = a.offset().left + a.width() / 2,
                    r = a.offset().top + a.height();
                p.popups.show("image.insert", i, r, a.outerHeight())
            }
            void 0 === e && k(p.language.translate("Uploading"), 0)
        }

        function F(e) {
            var t = p.popups.get("image.insert");
            if (t && (t.find(".fr-layer.fr-pactive").addClass("fr-active").removeClass("fr-pactive"), t.find(".fr-image-progress-bar-layer").removeClass("fr-active"), t.find(".fr-buttons").show(), e || p.$el.find("img.fr-error").length)) {
                if (p.events.focus(), p.$el.find("img.fr-error").length && (p.$el.find("img.fr-error").remove(), p.undo.saveStep(), p.undo.run(), p.undo.dropRedo()), !p.$wp && g) {
                    var a = g;
                    oe(!0), p.selection.setAfter(a.get(0)), p.selection.restore()
                }
                p.popups.hide("image.insert")
            }
        }

        function k(e, t) {
            var a = p.popups.get("image.insert");
            if (a) {
                var i = a.find(".fr-image-progress-bar-layer");
                i.find("h3").text(e + (t ? " " + t + "%" : "")), i.removeClass("fr-error"), t ? (i.find("div").removeClass("fr-indeterminate"), i.find("div > span").css("width", t + "%")) : i.find("div").addClass("fr-indeterminate")
            }
        }

        function B(e) {
            ne.call(e.get(0))
        }

        function O() {
            var e = ye(this);
            p.popups.hide("image.insert"), e.removeClass("fr-uploading"), e.next().is("br") && e.next().remove(), B(e), p.events.trigger("image.loaded", [e])
        }

        function P(n, e, o, l, f) {
            p.edit.off(), k(p.language.translate("Loading image")), e && (n = p.helpers.sanitizeURL(n));
            var t = new Image;
            t.onload = function() {
                var e, t;
                if (l) {
                    p.undo.canDo() || l.hasClass("fr-uploading") || p.undo.saveStep();
                    var a = l.data("fr-old-src");
                    l.data("fr-image-pasted") && (a = null), p.$wp ? ((e = l.clone().removeData("fr-old-src").removeClass("fr-uploading").removeAttr("data-fr-image-pasted")).off("load"), a && l.attr("src", a), l.replaceWith(e)) : e = l;
                    for (var i = e.get(0).attributes, r = 0; r < i.length; r++) {
                        var s = i[r];
                        0 === s.nodeName.indexOf("data-") && e.removeAttr(s.nodeName)
                    }
                    if (void 0 !== o)
                        for (t in o) o.hasOwnProperty(t) && "link" != t && e.attr("data-" + t, o[t]);
                    e.on("load", O), e.attr("src", n), p.edit.on(), C(!1), p.undo.saveStep(), p.events.disableBlur(), p.$el.blur(), p.events.trigger(a ? "image.replaced" : "image.inserted", [e, f])
                } else e = M(n, o, O), C(!1), p.undo.saveStep(), p.events.disableBlur(), p.$el.blur(), p.events.trigger("image.inserted", [e, f])
            }, t.onerror = function() {
                U(i)
            }, I(p.language.translate("Loading image")), t.src = n
        }

        function N(e) {
            k(p.language.translate("Loading image"));
            var t = this.status,
                a = this.response,
                i = this.responseXML,
                r = this.responseText;
            try {
                if (p.opts.imageUploadToS3)
                    if (201 == t) {
                        var s = function(e) {
                            try {
                                var t = ye(e).find("Location").text(),
                                    a = ye(e).find("Key").text();
                                return !1 === p.events.trigger("image.uploadedToS3", [t, a, e], !0) ? (p.edit.on(), !1) : t
                            } catch (i) {
                                return U(h, e), !1
                            }
                        }(i);
                        s && P(s, !1, [], e, a || i)
                    } else U(h, a || i, e);
                else if (200 <= t && t < 300) {
                    var n = function(e) {
                        try {
                            if (!1 === p.events.trigger("image.uploaded", [e], !0)) return p.edit.on(), !1;
                            var t = JSON.parse(e);
                            return t.link ? t : (U(c, e), !1)
                        } catch (a) {
                            return U(h, e), !1
                        }
                    }(r);
                    n && P(n.link, !1, n, e, a || r)
                } else U(m, a || r, e)
            } catch (o) {
                U(h, a || r, e)
            }
        }

        function T() {
            U(h, this.response || this.responseText || this.responseXML)
        }

        function L(e) {
            if (e.lengthComputable) {
                var t = e.loaded / e.total * 100 | 0;
                k(p.language.translate("Uploading"), t)
            }
        }

        function M(e, t, a) {
            var i, r = "";
            if (t && void 0 !== t)
                for (i in t) t.hasOwnProperty(i) && "link" != i && (r += " data-" + i + '="' + t[i] + '"');
            var s = p.opts.imageDefaultWidth;
            s && "auto" != s && (s += p.opts.imageResizeWithPercent ? "%" : "px");
            var n = ye('<img src="' + e + '"' + r + (s ? ' style="width: ' + s + ';"' : "") + ">");
            ge(n, p.opts.imageDefaultDisplay, p.opts.imageDefaultAlign), n.on("load", a), n.on("error", function() {
                ye(this).addClass("fr-error"), U(y)
            }), p.edit.on(), p.events.focus(!0), p.selection.restore(), p.undo.saveStep(), p.opts.imageSplitHTML ? p.markers.split() : p.markers.insert(), p.html.wrap();
            var o = p.$el.find(".fr-marker");
            return o.length ? (o.parent().is("hr") && o.parent().after(o), p.node.isLastSibling(o) && o.parent().hasClass("fr-deletable") && o.insertAfter(o.parent()), o.replaceWith(n)) : p.$el.append(n), p.selection.clear(), n
        }

        function z() {
            p.edit.on(), F(!0)
        }

        function _(e, t) {
            if (void 0 !== e && 0 < e.length) {
                if (!1 === p.events.trigger("image.beforeUpload", [e, t])) return !1;
                var a, i = e[0];
                if ((null === p.opts.imageUploadURL || p.opts.imageUploadURL == u) && !p.opts.imageUploadToS3) return n = i, o = t || g, (l = new FileReader).addEventListener("load", function() {
                    var e = l.result;
                    if (l.result.indexOf("svg+xml") < 0) {
                        for (var t = atob(l.result.split(",")[1]), a = [], i = 0; i < t.length; i++) a.push(t.charCodeAt(i));
                        e = window.URL.createObjectURL(new Blob([new Uint8Array(a)], {
                            type: n.type
                        })), p.image.insert(e, !1, null, o)
                    }
                }, !1), I(), l.readAsDataURL(n), !1;
                if (i.name || (i.name = (new Date).getTime() + "." + (i.type || "image/jpeg").replace(/image\//g, "")), i.size > p.opts.imageMaxSize) return U(v), !1;
                if (p.opts.imageAllowedTypes.indexOf(i.type.replace(/image\//g, "")) < 0) return U(b), !1;
                if (p.drag_support.formdata && (a = p.drag_support.formdata ? new FormData : null), a) {
                    var r;
                    if (!1 !== p.opts.imageUploadToS3)
                        for (r in a.append("key", p.opts.imageUploadToS3.keyStart + (new Date).getTime() + "-" + (i.name || "untitled")), a.append("success_action_status", "201"), a.append("X-Requested-With", "xhr"), a.append("Content-Type", i.type), p.opts.imageUploadToS3.params) p.opts.imageUploadToS3.params.hasOwnProperty(r) && a.append(r, p.opts.imageUploadToS3.params[r]);
                    for (r in p.opts.imageUploadParams) p.opts.imageUploadParams.hasOwnProperty(r) && a.append(r, p.opts.imageUploadParams[r]);
                    a.append(p.opts.imageUploadParam, i, i.name);
                    var s = p.opts.imageUploadURL;
                    p.opts.imageUploadToS3 && (s = p.opts.imageUploadToS3.uploadURL ? p.opts.imageUploadToS3.uploadURL : "https://" + p.opts.imageUploadToS3.region + ".amazonaws.com/" + p.opts.imageUploadToS3.bucket),
                        function(t, a, e, r) {
                            function s() {
                                var e = ye(this);
                                e.off("load"), e.addClass("fr-uploading"), e.next().is("br") && e.next().remove(), p.placeholder.refresh(), B(e), A(), I(), p.edit.off(), t.onload = function() {
                                    N.call(t, e)
                                }, t.onerror = T, t.upload.onprogress = L, t.onabort = z, e.off("abortUpload").on("abortUpload", function() {
                                    4 != t.readyState && t.abort()
                                }), t.send(a)
                            }
                            var n = new FileReader;
                            n.addEventListener("load", function() {
                                var e = n.result;
                                if (n.result.indexOf("svg+xml") < 0) {
                                    for (var t = atob(n.result.split(",")[1]), a = [], i = 0; i < t.length; i++) a.push(t.charCodeAt(i));
                                    e = window.URL.createObjectURL(new Blob([new Uint8Array(a)], {
                                        type: "image/jpeg"
                                    }))
                                }
                                r ? (r.on("load", s), r.one("error", function() {
                                    r.off("load"), r.attr("src", r.data("fr-old-src")), U(y)
                                }), p.edit.on(), p.undo.saveStep(), r.data("fr-old-src", r.attr("src")), r.attr("src", e)) : M(e, null, s)
                            }, !1), n.readAsDataURL(e)
                        }(p.core.getXHR(s, p.opts.imageUploadMethod), a, i, t || g)
                }
            }
            var n, o, l
        }

        function W(e) {
            if (e.is("img") && 0 < e.parents(".fr-img-caption").length) return e.parents(".fr-img-caption")
        }

        function K(e) {
            var t = e.originalEvent.dataTransfer;
            if (t && t.files && t.files.length) {
                var a = t.files[0];
                if (a && a.type && -1 !== a.type.indexOf("image") && 0 <= p.opts.imageAllowedTypes.indexOf(a.type.replace(/image\//g, ""))) {
                    if (!p.opts.imageUpload) return e.preventDefault(), e.stopPropagation(), !1;
                    p.markers.remove(), p.markers.insertAtPoint(e.originalEvent), p.$el.find(".fr-marker").replaceWith(ye.FE.MARKERS), 0 === p.$el.find(".fr-marker").length && p.selection.setAtEnd(p.el), p.popups.hideAll();
                    var i = p.popups.get("image.insert");
                    i || (i = H()), p.popups.setContainer("image.insert", p.$sc);
                    var r = e.originalEvent.pageX,
                        s = e.originalEvent.pageY;
                    return p.opts.iframe && (s += p.$iframe.offset().top, r += p.$iframe.offset().left), p.popups.show("image.insert", r, s), I(), 0 <= p.opts.imageAllowedTypes.indexOf(a.type.replace(/image\//g, "")) ? (oe(!0), _(t.files)) : U(b), e.preventDefault(), e.stopPropagation(), !1
                }
            }
        }

        function H(e) {
            if (e) return p.popups.onRefresh("image.insert", w), p.popups.onHide("image.insert", E), !0;
            var t, a = "";
            p.opts.imageUpload || p.opts.imageInsertButtons.splice(p.opts.imageInsertButtons.indexOf("imageUpload"), 1), 1 < p.opts.imageInsertButtons.length && (a = '<div class="fr-buttons">' + p.button.buildList(p.opts.imageInsertButtons) + "</div>");
            var i = p.opts.imageInsertButtons.indexOf("imageUpload"),
                r = p.opts.imageInsertButtons.indexOf("imageByURL"),
                s = "";
            0 <= i && (t = " fr-active", 0 <= r && r < i && (t = ""), s = '<div class="fr-image-upload-layer' + t + ' fr-layer" id="fr-image-upload-layer-' + p.id + '"><strong>' + p.language.translate("Drop image") + "</strong><br>(" + p.language.translate("or click") + ')<div class="fr-form"><input type="file" accept="image/' + p.opts.imageAllowedTypes.join(", image/").toLowerCase() + '" tabIndex="-1" aria-labelledby="fr-image-upload-layer-' + p.id + '" role="button"></div></div>');
            var n = "";
            0 <= r && (t = " fr-active", 0 <= i && i < r && (t = ""), n = '<div class="fr-image-by-url-layer' + t + ' fr-layer" id="fr-image-by-url-layer-' + p.id + '"><div class="fr-input-line"><input id="fr-image-by-url-layer-text-' + p.id + '" type="text" placeholder="http://" tabIndex="1" aria-required="true"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageInsertByURL" tabIndex="2" role="button">' + p.language.translate("Insert") + "</button></div></div>");
            var o, l = {
                    buttons: a,
                    upload_layer: s,
                    by_url_layer: n,
                    progress_bar: '<div class="fr-image-progress-bar-layer fr-layer"><h3 tabIndex="-1" class="fr-message">Uploading</h3><div class="fr-loader"><span class="fr-progress"></span></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-dismiss" data-cmd="imageDismissError" tabIndex="2" role="button">OK</button></div></div>'
                },
                f = p.popups.create("image.insert", l);
            return p.$wp && p.events.$on(p.$wp, "scroll", function() {
                g && p.popups.isVisible("image.insert") && ce()
            }), o = f, p.events.$on(o, "dragover dragenter", ".fr-image-upload-layer", function() {
                return ye(this).addClass("fr-drop"), !1
            }, !0), p.events.$on(o, "dragleave dragend", ".fr-image-upload-layer", function() {
                return ye(this).removeClass("fr-drop"), !1
            }, !0), p.events.$on(o, "drop", ".fr-image-upload-layer", function(e) {
                e.preventDefault(), e.stopPropagation(), ye(this).removeClass("fr-drop");
                var t = e.originalEvent.dataTransfer;
                if (t && t.files) {
                    var a = o.data("instance") || p;
                    a.events.disableBlur(), a.image.upload(t.files), a.events.enableBlur()
                }
            }, !0), p.helpers.isIOS() && p.events.$on(o, "touchstart", '.fr-image-upload-layer input[type="file"]', function() {
                ye(this).trigger("click")
            }, !0), p.events.$on(o, "change", '.fr-image-upload-layer input[type="file"]', function() {
                if (this.files) {
                    var e = o.data("instance") || p;
                    e.events.disableBlur(), o.find("input:focus").blur(), e.events.enableBlur(), e.image.upload(this.files, g)
                }
                ye(this).val("")
            }, !0), f
        }

        function Y() {
            g && p.popups.get("image.alt").find("input").val(g.attr("alt") || "").trigger("change")
        }

        function X() {
            var e = p.popups.get("image.alt");
            e || (e = j()), F(), p.popups.refresh("image.alt"), p.popups.setContainer("image.alt", p.$sc);
            var t = ve();
            be() && (t = t.find(".fr-img-wrap"));
            var a = t.offset().left + t.outerWidth() / 2,
                i = t.offset().top + t.outerHeight();
            p.popups.show("image.alt", a, i, t.outerHeight())
        }

        function j(e) {
            if (e) return p.popups.onRefresh("image.alt", Y), !0;
            var t = {
                    buttons: '<div class="fr-buttons">' + p.button.buildList(p.opts.imageAltButtons) + "</div>",
                    alt_layer: '<div class="fr-image-alt-layer fr-layer fr-active" id="fr-image-alt-layer-' + p.id + '"><div class="fr-input-line"><input id="fr-image-alt-layer-text-' + p.id + '" type="text" placeholder="' + p.language.translate("Alternative Text") + '" tabIndex="1"></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageSetAlt" tabIndex="2" role="button">' + p.language.translate("Update") + "</button></div></div>"
                },
                a = p.popups.create("image.alt", t);
            return p.$wp && p.events.$on(p.$wp, "scroll.image-alt", function() {
                g && p.popups.isVisible("image.alt") && X()
            }), a
        }

        function G() {
            if (g) {
                var e = p.popups.get("image.size");
                e.find('input[name="width"]').val(g.get(0).style.width).trigger("change"), e.find('input[name="height"]').val(g.get(0).style.height).trigger("change")
            }
        }

        function q() {
            var e = p.popups.get("image.size");
            e || (e = V()), F(), p.popups.refresh("image.size"), p.popups.setContainer("image.size", p.$sc);
            var t = ve();
            be() && (t = t.find(".fr-img-wrap"));
            var a = t.offset().left + t.outerWidth() / 2,
                i = t.offset().top + t.outerHeight();
            p.popups.show("image.size", a, i, t.outerHeight())
        }

        function V(e) {
            if (e) return p.popups.onRefresh("image.size", G), !0;
            var t = {
                    buttons: '<div class="fr-buttons">' + p.button.buildList(p.opts.imageSizeButtons) + "</div>",
                    size_layer: '<div class="fr-image-size-layer fr-layer fr-active" id="fr-image-size-layer-' + p.id + '"><div class="fr-image-group"><div class="fr-input-line"><input id="fr-image-size-layer-width-' + p.id + '" type="text" name="width" placeholder="' + p.language.translate("Width") + '" tabIndex="1"></div><div class="fr-input-line"><input id="fr-image-size-layer-height' + p.id + '" type="text" name="height" placeholder="' + p.language.translate("Height") + '" tabIndex="1"></div></div><div class="fr-action-buttons"><button type="button" class="fr-command fr-submit" data-cmd="imageSetSize" tabIndex="2" role="button">' + p.language.translate("Update") + "</button></div></div>"
                },
                a = p.popups.create("image.size", t);
            return p.$wp && p.events.$on(p.$wp, "scroll.image-size", function() {
                g && p.popups.isVisible("image.size") && q()
            }), a
        }

        function Q(e, t, a, i) {
            return e.pageX = t, S.call(this, e), e.pageX = e.pageX + a * Math.floor(Math.pow(1.1, i)), D.call(this, e), x.call(this, e), ++i
        }

        function J(e) {
            (e = e || ve()) && !1 !== p.events.trigger("image.beforeRemove", [e]) && (p.popups.hideAll(), me(), oe(!0), p.undo.canDo() || p.undo.saveStep(), e.get(0) == p.el ? e.removeAttr("src") : (e.get(0).parentNode && "A" == e.get(0).parentNode.tagName ? (p.selection.setBefore(e.get(0).parentNode) || p.selection.setAfter(e.get(0).parentNode) || e.parent().after(ye.FE.MARKERS), ye(e.get(0).parentNode).remove()) : (p.selection.setBefore(e.get(0)) || p.selection.setAfter(e.get(0)) || e.after(ye.FE.MARKERS), e.remove()), p.html.fillEmptyBlocks(), p.selection.restore()), p.undo.saveStep())
        }

        function Z(e) {
            var t = e.which;
            if (g && (t == ye.FE.KEYCODE.BACKSPACE || t == ye.FE.KEYCODE.DELETE)) return e.preventDefault(), e.stopPropagation(), J(), !1;
            if (g && t == ye.FE.KEYCODE.ESC) {
                var a = g;
                return oe(!0), p.selection.setAfter(a.get(0)), p.selection.restore(), e.preventDefault(), !1
            }
            if (g && (t == ye.FE.KEYCODE.ARROW_LEFT || t == ye.FE.KEYCODE.ARROW_RIGHT)) {
                var i = g.get(0);
                return oe(!0), t == ye.FE.KEYCODE.ARROW_LEFT ? p.selection.setBefore(i) : p.selection.setAfter(i), p.selection.restore(), e.preventDefault(), !1
            }
            return g && t != ye.FE.KEYCODE.F10 && !p.keys.isBrowserAction(e) ? (e.preventDefault(), e.stopPropagation(), !1) : void 0
        }

        function ee(e) {
            if (e && "IMG" == e.tagName) {
                if (p.node.hasClass(e, "fr-uploading") || p.node.hasClass(e, "fr-error") ? e.parentNode.removeChild(e) : p.node.hasClass(e, "fr-draggable") && e.classList.remove("fr-draggable"), e.parentNode && e.parentNode.parentNode && p.node.hasClass(e.parentNode.parentNode, "fr-img-caption")) {
                    var t = e.parentNode.parentNode;
                    t.removeAttribute("contenteditable"), t.removeAttribute("draggable"), t.classList.remove("fr-draggable");
                    var a = e.nextSibling;
                    a && a.removeAttribute("contenteditable")
                }
            } else if (e && e.nodeType == Node.ELEMENT_NODE)
                for (var i = e.querySelectorAll("img.fr-uploading, img.fr-error, img.fr-draggable"), r = 0; r < i.length; r++) ee(i[r])
        }

        function te(e) {
            if (!1 === p.events.trigger("image.beforePasteUpload", [e])) return !1;
            g = ye(e), A(), s(), ce(), I(), g.one("load", function() {
                A(), I()
            });
            for (var t = atob(ye(e).attr("src").split(",")[1]), a = [], i = 0; i < t.length; i++) a.push(t.charCodeAt(i));
            _([new Blob([new Uint8Array(a)], {
                type: ye(e).attr("src").split(",")[0].replace(/data\:/g, "").replace(/;base64/g, "")
            })], g)
        }

        function ae() {
            p.opts.imagePaste ? p.$el.find("img[data-fr-image-pasted]").each(function(e, a) {
                if (p.opts.imagePasteProcess) {
                    var t = p.opts.imageDefaultWidth;
                    t && "auto" != t && (t += p.opts.imageResizeWithPercent ? "%" : "px"), ye(a).css("width", t).removeClass("fr-dii fr-dib fr-fir fr-fil"), ge(ye(a), p.opts.imageDefaultDisplay, p.opts.imageDefaultAlign)
                }
                if (0 === a.src.indexOf("data:")) te(a);
                else if (0 === a.src.indexOf("blob:") || 0 === a.src.indexOf("http") && p.opts.imageUploadRemoteUrls && p.opts.imageCORSProxy) {
                    var i = new Image;
                    i.crossOrigin = "Anonymous", i.onload = function() {
                        var e = p.o_doc.createElement("CANVAS"),
                            t = e.getContext("2d");
                        e.height = this.naturalHeight, e.width = this.naturalWidth, t.drawImage(this, 0, 0), a.src = e.toDataURL("image/png"), te(a)
                    }, i.src = (0 === a.src.indexOf("blob:") ? "" : p.opts.imageCORSProxy + "/") + a.src
                } else 0 !== a.src.indexOf("http") || 0 === a.src.indexOf("https://mail.google.com/mail") ? (p.selection.save(), ye(a).remove(), p.selection.restore()) : ye(a).removeAttr("data-fr-image-pasted")
            }) : p.$el.find("img[data-fr-image-pasted]").remove()
        }

        function ie(e) {
            var t = e.target.result,
                a = p.opts.imageDefaultWidth;
            a && "auto" != a && (a += p.opts.imageResizeWithPercent ? "%" : "px"), p.undo.saveStep(), p.html.insert('<img data-fr-image-pasted="true" src="' + t + '"' + (a ? ' style="width: ' + a + ';"' : "") + ">");
            var i = p.$el.find('img[data-fr-image-pasted="true"]');
            i && ge(i, p.opts.imageDefaultDisplay, p.opts.imageDefaultAlign), p.events.trigger("paste.after")
        }

        function re(e) {
            if (e && e.clipboardData && e.clipboardData.items) {
                var t = null;
                if (e.clipboardData.getData("text/rtf")) t = e.clipboardData.items[0].getAsFile();
                else
                    for (var a = 0; a < e.clipboardData.items.length && !(t = e.clipboardData.items[a].getAsFile()); a++);
                if (t) return i = t, (r = new FileReader).onload = ie, r.readAsDataURL(i), !1
            }
            var i, r
        }

        function se(e) {
            return e = e.replace(/<img /gi, '<img data-fr-image-pasted="true" ')
        }

        function ne(e) {
            if ("false" == ye(this).parents("[contenteditable]:not(.fr-element):not(.fr-img-caption):not(body):first").attr("contenteditable")) return !0;
            if (e && "touchend" == e.type && a) return !0;
            if (e && p.edit.isDisabled()) return e.stopPropagation(), e.preventDefault(), !1;
            for (var t = 0; t < ye.FE.INSTANCES.length; t++) ye.FE.INSTANCES[t] != p && ye.FE.INSTANCES[t].events.trigger("image.hideResizer");
            p.toolbar.disable(), e && (e.stopPropagation(), e.preventDefault()), p.helpers.isMobile() && (p.events.disableBlur(), p.$el.blur(), p.events.enableBlur()), p.opts.iframe && p.size.syncIframe(), g = ye(this), me(), A(), s(), p.browser.msie || p.selection.clear(), p.helpers.isIOS() && (p.events.disableBlur(), p.$el.blur()), p.button.bulkRefresh(), p.events.trigger("video.hideResizer")
        }

        function oe(e) {
            g && (le || !0 === e) && (p.toolbar.enable(), l.removeClass("fr-active"), p.popups.hide("image.edit"), g = null, pe(), f = null, d && d.hide())
        }
        r[i] = "Image cannot be loaded from the passed link.", r[c] = "No link in upload response.", r[m] = "Error during file upload.", r[h] = "Parsing response failed.", r[v] = "File is too large.", r[b] = "Image file type is invalid.", r[7] = "Files can be uploaded only to same domain in IE 8 and IE 9.";
        var le = !(r[y] = "Image file is corrupted.");

        function fe() {
            le = !0
        }

        function pe() {
            le = !1
        }

        function ge(e, t, a) {
            !p.opts.htmlUntouched && p.opts.useClasses ? (e.removeClass("fr-fil fr-fir fr-dib fr-dii"), a && e.addClass("fr-fi" + a[0]), t && e.addClass("fr-di" + t[0])) : "inline" == t ? (e.css({
                display: "inline-block",
                verticalAlign: "bottom",
                margin: p.opts.imageDefaultMargin
            }), "center" == a ? e.css({
                "float": "none",
                marginBottom: "",
                marginTop: "",
                maxWidth: "calc(100% - " + 2 * p.opts.imageDefaultMargin + "px)",
                textAlign: "center"
            }) : "left" == a ? e.css({
                "float": "left",
                marginLeft: 0,
                maxWidth: "calc(100% - " + p.opts.imageDefaultMargin + "px)",
                textAlign: "left"
            }) : e.css({
                "float": "right",
                marginRight: 0,
                maxWidth: "calc(100% - " + p.opts.imageDefaultMargin + "px)",
                textAlign: "right"
            })) : "block" == t && (e.css({
                display: "block",
                "float": "none",
                verticalAlign: "top",
                margin: p.opts.imageDefaultMargin + "px auto",
                textAlign: "center"
            }), "left" == a ? e.css({
                marginLeft: 0,
                textAlign: "left"
            }) : "right" == a && e.css({
                marginRight: 0,
                textAlign: "right"
            }))
        }

        function de(e) {
            if (void 0 === e && (e = ve()), e) {
                if (e.hasClass("fr-fil")) return "left";
                if (e.hasClass("fr-fir")) return "right";
                if (e.hasClass("fr-dib") || e.hasClass("fr-dii")) return "center";
                var t = e.css("float");
                if (e.css("float", "none"), "block" == e.css("display")) {
                    if (e.css("float", ""), e.css("float") != t && e.css("float", t), 0 === parseInt(e.css("margin-left"), 10)) return "left";
                    if (0 === parseInt(e.css("margin-right"), 10)) return "right"
                } else {
                    if (e.css("float", ""), e.css("float") != t && e.css("float", t), "left" == e.css("float")) return "left";
                    if ("right" == e.css("float")) return "right"
                }
            }
            return "center"
        }

        function ue(e) {
            void 0 === e && (e = ve());
            var t = e.css("float");
            return e.css("float", "none"), "block" == e.css("display") ? (e.css("float", ""), e.css("float") != t && e.css("float", t), "block") : (e.css("float", ""), e.css("float") != t && e.css("float", t), "inline")
        }

        function ce() {
            var e = p.popups.get("image.insert");
            e || (e = H()), p.popups.isVisible("image.insert") || (F(), p.popups.refresh("image.insert"), p.popups.setContainer("image.insert", p.$sc));
            var t = ve();
            be() && (t = t.find(".fr-img-wrap"));
            var a = t.offset().left + t.outerWidth() / 2,
                i = t.offset().top + t.outerHeight();
            p.popups.show("image.insert", a, i, t.outerHeight(!0))
        }

        function me() {
            if (g) {
                p.events.disableBlur(), p.selection.clear();
                var e = p.doc.createRange();
                e.selectNode(g.get(0)), p.browser.msie && e.collapse(!0), p.selection.get().addRange(e), p.events.enableBlur()
            }
        }

        function he() {
            return g
        }

        function ve() {
            return be() ? g.parents(".fr-img-caption:first") : g
        }

        function be() {
            return !!g && 0 < g.parents(".fr-img-caption").length
        }
        return {
            _init: function() {
                var i;
                p.events.$on(p.$el, p._mousedown, "IMG" == p.el.tagName ? null : 'img:not([contenteditable="false"])', function(e) {
                    if ("false" == ye(this).parents("[contenteditable]:not(.fr-element):not(.fr-img-caption):not(body):first").attr("contenteditable")) return !0;
                    p.helpers.isMobile() || p.selection.clear(), t = !0, p.popups.areVisible() && p.events.disableBlur(), p.browser.msie && (p.events.disableBlur(), p.$el.attr("contenteditable", !1)), p.draggable || "touchstart" == e.type || e.preventDefault(), e.stopPropagation()
                }), p.events.$on(p.$el, p._mouseup, "IMG" == p.el.tagName ? null : 'img:not([contenteditable="false"])', function(e) {
                    if ("false" == ye(this).parents("[contenteditable]:not(.fr-element):not(.fr-img-caption):not(body):first").attr("contenteditable")) return !0;
                    t && (t = !1, e.stopPropagation(), p.browser.msie && (p.$el.attr("contenteditable", !0), p.events.enableBlur()))
                }), p.events.on("keyup", function(e) {
                    if (e.shiftKey && "" === p.selection.text().replace(/\n/g, "") && p.keys.isArrow(e.which)) {
                        var t = p.selection.element(),
                            a = p.selection.endElement();
                        t && "IMG" == t.tagName ? B(ye(t)) : a && "IMG" == a.tagName && B(ye(a))
                    }
                }, !0), p.events.on("drop", K), p.events.on("element.beforeDrop", W), p.events.on("mousedown window.mousedown", fe), p.events.on("window.touchmove", pe), p.events.on("mouseup window.mouseup", function() {
                    if (g) return oe(), !1;
                    pe()
                }), p.events.on("commands.mousedown", function(e) {
                    0 < e.parents(".fr-toolbar").length && oe()
                }), p.events.on("blur image.hideResizer commands.undo commands.redo element.dropped", function() {
                    oe(!(t = !1))
                }), p.events.on("modals.hide", function() {
                    g && (me(), p.selection.clear())
                }), "IMG" == p.el.tagName && p.$el.addClass("fr-view"), p.events.$on(p.$el, p.helpers.isMobile() && !p.helpers.isWindowsPhone() ? "touchend" : "click", "IMG" == p.el.tagName ? null : 'img:not([contenteditable="false"])', ne), p.helpers.isMobile() && (p.events.$on(p.$el, "touchstart", "IMG" == p.el.tagName ? null : 'img:not([contenteditable="false"])', function() {
                    a = !1
                }), p.events.$on(p.$el, "touchmove", function() {
                    a = !0
                })), p.$wp ? (p.events.on("window.keydown keydown", Z, !0), p.events.on("keyup", function(e) {
                    if (g && e.which == ye.FE.KEYCODE.ENTER) return !1
                }, !0)) : p.events.$on(p.$win, "keydown", Z), p.events.on("toolbar.esc", function() {
                    if (g) {
                        if (p.$wp) p.events.disableBlur(), p.events.focus();
                        else {
                            var e = g;
                            oe(!0), p.selection.setAfter(e.get(0)), p.selection.restore()
                        }
                        return !1
                    }
                }, !0), p.events.on("toolbar.focusEditor", function() {
                    if (g) return !1
                }, !0), p.events.on("window.cut window.copy", function(e) {
                    if (g && p.popups.isVisible("image.edit") && !p.popups.get("image.edit").find(":focus").length) {
                        var t = ve();
                        be() ? (t.before(ye.FE.START_MARKER), t.after(ye.FE.END_MARKER), p.selection.restore(), p.paste.saveCopiedText(t.get(0).outerHTML, t.text())) : (me(), p.paste.saveCopiedText(g.get(0).outerHTML, g.attr("alt"))), "copy" == e.type ? setTimeout(function() {
                            B(g)
                        }) : (oe(!0), p.undo.saveStep(), setTimeout(function() {
                            p.undo.saveStep()
                        }, 0))
                    }
                }, !0), p.browser.msie && p.events.on("keydown", function(e) {
                    if (!p.selection.isCollapsed() || !g) return !0;
                    var t = e.which;
                    t == ye.FE.KEYCODE.C && p.keys.ctrlKey(e) ? p.events.trigger("window.copy") : t == ye.FE.KEYCODE.X && p.keys.ctrlKey(e) && p.events.trigger("window.cut")
                }), p.events.$on(ye(p.o_win), "keydown", function(e) {
                    var t = e.which;
                    if (g && t == ye.FE.KEYCODE.BACKSPACE) return e.preventDefault(), !1
                }), p.events.$on(p.$win, "keydown", function(e) {
                    var t = e.which;
                    g && g.hasClass("fr-uploading") && t == ye.FE.KEYCODE.ESC && g.trigger("abortUpload")
                }), p.events.on("destroy", function() {
                    g && g.hasClass("fr-uploading") && g.trigger("abortUpload")
                }), p.events.on("paste.before", re), p.events.on("paste.beforeCleanup", se), p.events.on("paste.after", ae), p.events.on("html.set", e), p.events.on("html.inserted", e), e(), p.events.on("destroy", function() {
                    o = []
                }), p.events.on("html.processGet", ee), p.opts.imageOutputSize && p.events.on("html.beforeGet", function() {
                    i = p.el.querySelectorAll("img");
                    for (var e = 0; e < i.length; e++) {
                        var t = i[e].style.width || ye(i[e]).width(),
                            a = i[e].style.height || ye(i[e]).height();
                        t && i[e].setAttribute("width", ("" + t).replace(/px/, "")), a && i[e].setAttribute("height", ("" + a).replace(/px/, ""))
                    }
                }), p.opts.iframe && p.events.on("image.loaded", p.size.syncIframe), p.$wp && (C(), p.events.on("contentChanged", C)), p.events.$on(ye(p.o_win), "orientationchange.image", function() {
                    setTimeout(function() {
                        g && B(g)
                    }, 100)
                }), $(!0), H(!0), V(!0), j(!0), p.events.on("node.remove", function(e) {
                    if ("IMG" == e.get(0).tagName) return J(e), !1
                })
            },
            showInsertPopup: function() {
                var e = p.$tb.find('.fr-command[data-cmd="insertImage"]'),
                    t = p.popups.get("image.insert");
                if (t || (t = H()), F(), !t.hasClass("fr-active"))
                    if (p.popups.refresh("image.insert"), p.popups.setContainer("image.insert", p.$tb), e.is(":visible")) {
                        var a = e.offset().left + e.outerWidth() / 2,
                            i = e.offset().top + (p.opts.toolbarBottom ? 10 : e.outerHeight() - 10);
                        p.popups.show("image.insert", a, i, e.outerHeight())
                    } else p.position.forSelection(t), p.popups.show("image.insert")
            },
            showLayer: function(e) {
                var t, a, i = p.popups.get("image.insert");
                if (g || p.opts.toolbarInline) {
                    if (g) {
                        var r = ve();
                        be() && (r = r.find(".fr-img-wrap")), a = r.offset().top + r.outerHeight(), t = r.offset().left + r.outerWidth() / 2
                    }
                } else {
                    var s = p.$tb.find('.fr-command[data-cmd="insertImage"]');
                    t = s.offset().left + s.outerWidth() / 2, a = s.offset().top + (p.opts.toolbarBottom ? 10 : s.outerHeight() - 10)
                }!g && p.opts.toolbarInline && (a = i.offset().top - p.helpers.getPX(i.css("margin-top")), i.hasClass("fr-above") && (a += i.outerHeight())), i.find(".fr-layer").removeClass("fr-active"), i.find(".fr-" + e + "-layer").addClass("fr-active"), p.popups.show("image.insert", t, a, g ? g.outerHeight() : 0), p.accessibility.focusPopup(i)
            },
            refreshUploadButton: function(e) {
                p.popups.get("image.insert").find(".fr-image-upload-layer").hasClass("fr-active") && e.addClass("fr-active").attr("aria-pressed", !0)
            },
            refreshByURLButton: function(e) {
                p.popups.get("image.insert").find(".fr-image-by-url-layer").hasClass("fr-active") && e.addClass("fr-active").attr("aria-pressed", !0)
            },
            upload: _,
            insertByURL: function() {
                var e = p.popups.get("image.insert").find(".fr-image-by-url-layer input");
                if (0 < e.val().length) {
                    I(), k(p.language.translate("Loading image"));
                    var t = e.val();
                    if (p.opts.imageUploadRemoteUrls && p.opts.imageCORSProxy && p.opts.imageUpload) {
                        var a = new XMLHttpRequest;
                        a.onload = function() {
                            200 == this.status ? _([new Blob([this.response], {
                                type: this.response.type || "image/png"
                            })], g) : U(i)
                        }, a.onerror = function() {
                            P(t, !0, [], g)
                        }, a.open("GET", p.opts.imageCORSProxy + "/" + t, !0), a.responseType = "blob", a.send()
                    } else P(t, !0, [], g);
                    e.val(""), e.blur()
                }
            },
            align: function(e) {
                var t = ve();
                t.removeClass("fr-fir fr-fil"), !p.opts.htmlUntouched && p.opts.useClasses ? "left" == e ? t.addClass("fr-fil") : "right" == e && t.addClass("fr-fir") : ge(t, ue(), e), me(), A(), s(), p.selection.clear()
            },
            refreshAlign: function(e) {
                g && e.find("> *:first").replaceWith(p.icon.create("image-align-" + de()))
            },
            refreshAlignOnShow: function(e, t) {
                g && t.find('.fr-command[data-param1="' + de() + '"]').addClass("fr-active").attr("aria-selected", !0)
            },
            display: function(e) {
                var t = ve();
                t.removeClass("fr-dii fr-dib"), !p.opts.htmlUntouched && p.opts.useClasses ? "inline" == e ? t.addClass("fr-dii") : "block" == e && t.addClass("fr-dib") : ge(t, e, de()), me(), A(), s(), p.selection.clear()
            },
            refreshDisplayOnShow: function(e, t) {
                g && t.find('.fr-command[data-param1="' + ue() + '"]').addClass("fr-active").attr("aria-selected", !0)
            },
            replace: ce,
            back: function() {
                g ? (p.events.disableBlur(), ye(".fr-popup input:focus").blur(), B(g)) : (p.events.disableBlur(), p.selection.restore(), p.events.enableBlur(), p.popups.hide("image.insert"), p.toolbar.showInline())
            },
            get: he,
            getEl: ve,
            insert: P,
            showProgressBar: I,
            remove: J,
            hideProgressBar: F,
            applyStyle: function(e, t, a) {
                if (void 0 === t && (t = p.opts.imageStyles), void 0 === a && (a = p.opts.imageMultipleStyles), !g) return !1;
                var i = ve();
                if (!a) {
                    var r = Object.keys(t);
                    r.splice(r.indexOf(e), 1), i.removeClass(r.join(" "))
                }
                "object" == typeof t[e] ? (i.removeAttr("style"), i.css(t[e].style)) : i.toggleClass(e), B(g)
            },
            showAltPopup: X,
            showSizePopup: q,
            setAlt: function(e) {
                if (g) {
                    var t = p.popups.get("image.alt");
                    g.attr("alt", e || t.find("input").val() || ""), t.find("input:focus").blur(), B(g)
                }
            },
            setSize: function(e, t) {
                if (g) {
                    var a = p.popups.get("image.size");
                    e = e || a.find('input[name="width"]').val() || "", t = t || a.find('input[name="height"]').val() || "";
                    var i = /^[\d]+((px)|%)*$/g;
                    g.removeAttr("width").removeAttr("height"), e.match(i) ? g.css("width", e) : g.css("width", ""), t.match(i) ? g.css("height", t) : g.css("height", ""), be() && (g.parent().removeAttr("width").removeAttr("height"), e.match(i) ? g.parent().css("width", e) : g.parent().css("width", ""), t.match(i) ? g.parent().css("height", t) : g.parent().css("height", "")), a && a.find("input:focus").blur(), B(g)
                }
            },
            toggleCaption: function() {
                var e;
                g && !be() ? ((e = g).parent().is("a") && (e = g.parent()), e.wrap("<span " + (p.browser.mozilla || p.browser.msie ? "" : 'contenteditable="false"') + 'class="fr-img-caption ' + g.attr("class") + '" style="' + (g.attr("style") ? g.attr("style") + " " : "") + "width: " + g.width() + 'px;" draggable="false"></span>'), e.wrap('<span class="fr-img-wrap"></span>'), e.after('<span class="fr-inner" contenteditable="true">' + ye.FE.START_MARKER + "Image caption" + ye.FE.END_MARKER + "</span>"), g.removeAttr("class").removeAttr("style").removeAttr("width"), oe(!0), p.selection.restore()) : (e = ve(), g.insertAfter(e), g.attr("class", e.attr("class").replace("fr-img-caption", "")).attr("style", e.attr("style")), e.remove(), B(g))
            },
            hasCaption: be,
            exitEdit: oe,
            edit: B
        }
    }, ye.FE.DefineIcon("insertImage", {
        NAME: "image"
    }), ye.FE.RegisterShortcut(ye.FE.KEYCODE.P, "insertImage", null, "P"), ye.FE.RegisterCommand("insertImage", {
        title: "Insert Image",
        undo: !1,
        focus: !0,
        refreshAfterCallback: !1,
        popup: !0,
        callback: function() {
            this.popups.isVisible("image.insert") ? (this.$el.find(".fr-marker").length && (this.events.disableBlur(), this.selection.restore()), this.popups.hide("image.insert")) : this.image.showInsertPopup()
        },
        plugin: "image"
    }), ye.FE.DefineIcon("imageUpload", {
        NAME: "upload"
    }), ye.FE.RegisterCommand("imageUpload", {
        title: "Upload Image",
        undo: !1,
        focus: !1,
        toggle: !0,
        callback: function() {
            this.image.showLayer("image-upload")
        },
        refresh: function(e) {
            this.image.refreshUploadButton(e)
        }
    }), ye.FE.DefineIcon("imageByURL", {
        NAME: "link"
    }), ye.FE.RegisterCommand("imageByURL", {
        title: "By URL",
        undo: !1,
        focus: !1,
        toggle: !0,
        callback: function() {
            this.image.showLayer("image-by-url")
        },
        refresh: function(e) {
            this.image.refreshByURLButton(e)
        }
    }), ye.FE.RegisterCommand("imageInsertByURL", {
        title: "Insert Image",
        undo: !0,
        refreshAfterCallback: !1,
        callback: function() {
            this.image.insertByURL()
        },
        refresh: function(e) {
            this.image.get() ? e.text(this.language.translate("Replace")) : e.text(this.language.translate("Insert"))
        }
    }), ye.FE.DefineIcon("imageDisplay", {
        NAME: "star"
    }), ye.FE.RegisterCommand("imageDisplay", {
        title: "Display",
        type: "dropdown",
        options: {
            inline: "Inline",
            block: "Break Text"
        },
        callback: function(e, t) {
            this.image.display(t)
        },
        refresh: function(e) {
            this.opts.imageTextNear || e.addClass("fr-hidden")
        },
        refreshOnShow: function(e, t) {
            this.image.refreshDisplayOnShow(e, t)
        }
    }), ye.FE.DefineIcon("image-align", {
        NAME: "align-left"
    }), ye.FE.DefineIcon("image-align-left", {
        NAME: "align-left"
    }), ye.FE.DefineIcon("image-align-right", {
        NAME: "align-right"
    }), ye.FE.DefineIcon("image-align-center", {
        NAME: "align-justify"
    }), ye.FE.DefineIcon("imageAlign", {
        NAME: "align-justify"
    }), ye.FE.RegisterCommand("imageAlign", {
        type: "dropdown",
        title: "Align",
        options: {
            left: "Align Left",
            center: "None",
            right: "Align Right"
        },
        html: function() {
            var e = '<ul class="fr-dropdown-list" role="presentation">',
                t = ye.FE.COMMANDS.imageAlign.options;
            for (var a in t) t.hasOwnProperty(a) && (e += '<li role="presentation"><a class="fr-command fr-title" tabIndex="-1" role="option" data-cmd="imageAlign" data-param1="' + a + '" title="' + this.language.translate(t[a]) + '">' + this.icon.create("image-align-" + a) + '<span class="fr-sr-only">' + this.language.translate(t[a]) + "</span></a></li>");
            return e += "</ul>"
        },
        callback: function(e, t) {
            this.image.align(t)
        },
        refresh: function(e) {
            this.image.refreshAlign(e)
        },
        refreshOnShow: function(e, t) {
            this.image.refreshAlignOnShow(e, t)
        }
    }), ye.FE.DefineIcon("imageReplace", {
        NAME: "exchange",
        FA5NAME: "exchange-alt"
    }), ye.FE.RegisterCommand("imageReplace", {
        title: "Replace",
        undo: !1,
        focus: !1,
        popup: !0,
        refreshAfterCallback: !1,
        callback: function() {
            this.image.replace()
        }
    }), ye.FE.DefineIcon("imageRemove", {
        NAME: "trash"
    }), ye.FE.RegisterCommand("imageRemove", {
        title: "Remove",
        callback: function() {
            this.image.remove()
        }
    }), ye.FE.DefineIcon("imageBack", {
        NAME: "arrow-left"
    }), ye.FE.RegisterCommand("imageBack", {
        title: "Back",
        undo: !1,
        focus: !1,
        back: !0,
        callback: function() {
            this.image.back()
        },
        refresh: function(e) {
            this.image.get() || this.opts.toolbarInline ? (e.removeClass("fr-hidden"), e.next(".fr-separator").removeClass("fr-hidden")) : (e.addClass("fr-hidden"), e.next(".fr-separator").addClass("fr-hidden"))
        }
    }), ye.FE.RegisterCommand("imageDismissError", {
        title: "OK",
        undo: !1,
        callback: function() {
            this.image.hideProgressBar(!0)
        }
    }), ye.FE.DefineIcon("imageStyle", {
        NAME: "magic"
    }), ye.FE.RegisterCommand("imageStyle", {
        title: "Style",
        type: "dropdown",
        html: function() {
            var e = '<ul class="fr-dropdown-list" role="presentation">',
                t = this.opts.imageStyles;
            for (var a in t)
                if (t.hasOwnProperty(a)) {
                    var i = t[a];
                    "object" == typeof i && (i = i.title), e += '<li role="presentation"><a class="fr-command" tabIndex="-1" role="option" data-cmd="imageStyle" data-param1="' + a + '">' + this.language.translate(i) + "</a></li>"
                }
            return e += "</ul>"
        },
        callback: function(e, t) {
            this.image.applyStyle(t)
        },
        refreshOnShow: function(e, t) {
            var a = this.image.getEl();
            a && t.find(".fr-command").each(function() {
                var e = ye(this).data("param1"),
                    t = a.hasClass(e);
                ye(this).toggleClass("fr-active", t).attr("aria-selected", t)
            })
        }
    }), ye.FE.DefineIcon("imageAlt", {
        NAME: "info"
    }), ye.FE.RegisterCommand("imageAlt", {
        undo: !1,
        focus: !1,
        popup: !0,
        title: "Alternative Text",
        callback: function() {
            this.image.showAltPopup()
        }
    }), ye.FE.RegisterCommand("imageSetAlt", {
        undo: !0,
        focus: !1,
        title: "Update",
        refreshAfterCallback: !1,
        callback: function() {
            this.image.setAlt()
        }
    }), ye.FE.DefineIcon("imageSize", {
        NAME: "arrows-alt"
    }), ye.FE.RegisterCommand("imageSize", {
        undo: !1,
        focus: !1,
        popup: !0,
        title: "Change Size",
        callback: function() {
            this.image.showSizePopup()
        }
    }), ye.FE.RegisterCommand("imageSetSize", {
        undo: !0,
        focus: !1,
        title: "Update",
        refreshAfterCallback: !1,
        callback: function() {
            this.image.setSize()
        }
    }), ye.FE.DefineIcon("imageCaption", {
        NAME: "commenting",
        FA5NAME: "comment-alt"
    }), ye.FE.RegisterCommand("imageCaption", {
        undo: !0,
        focus: !1,
        title: "Image Caption",
        refreshAfterCallback: !0,
        callback: function() {
            this.image.toggleCaption()
        },
        refresh: function(e) {
            this.image.get() && e.toggleClass("fr-active", this.image.hasCaption())
        }
    })
});