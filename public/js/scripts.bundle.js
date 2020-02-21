var mApp = function () {
    var e = function () {
        $('[data-toggle="m-tooltip"]').each(function () {
            var e = $(this), t = e.data("skin") ? "m-tooltip--skin-" + e.data("skin") : "";
            e.tooltip({template: '<div class="m-tooltip ' + t + ' tooltip" role="tooltip">                    <div class="arrow"></div>                    <div class="tooltip-inner"></div>                </div>'})
        })
    }, t = function () {
        $('[data-toggle="m-popover"]').each(function () {
            var e = $(this), t = e.data("skin") ? "m-popover--skin-" + e.data("skin") : "";
            e.popover({template: '                <div class="m-popover ' + t + ' popover" role="tooltip">                    <div class="arrow"></div>                    <h3 class="popover-header"></h3>                    <div class="popover-body"></div>                </div>'})
        })
    }, a = function () {
        $('[data-scrollable="true"]').each(function () {
            var e, t, a = $(this);
            mUtil.isInResponsiveRange("tablet-and-mobile") ? (e = a.data("mobile-max-height") ? a.data("mobile-max-height") : a.data("max-height"), t = a.data("mobile-height") ? a.data("mobile-height") : a.data("height")) : (e = a.data("max-height"), t = a.data("max-height")), e && a.css("max-height", e), t && a.css("height", t), mApp.initScroller(a, {})
        })
    }, n = function () {
        $("body").on("click", "[data-close=alert]", function () {
            $(this).closest(".alert").hide()
        })
    };
    return {
        init: function () {
            mApp.initComponents()
        }, initComponents: function () {
            a(), e(), t(), n()
        }, scrollTo: function (e, t) {
            var a = e && e.length > 0 ? e.offset().top : 0;
            a += t || 0, jQuery("html,body").animate({scrollTop: a}, "slow")
        }, scrollToViewport: function (e) {
            var t = e.offset().top, a = e.height(), n = t - (mUtil.getViewPort().height / 2 - a / 2);
            jQuery("html,body").animate({scrollTop: n}, "slow")
        }, scrollTop: function () {
            mApp.scrollTo()
        }, initScroller: function (e, t) {
            mUtil.isMobileDevice() ? e.css("overflow", "auto") : (e.mCustomScrollbar("destroy"), e.mCustomScrollbar({
                scrollInertia: 0,
                autoDraggerLength: !0,
                autoHideScrollbar: !0,
                autoExpandScrollbar: !1,
                alwaysShowScrollbar: 0,
                axis: e.data("axis") ? e.data("axis") : "y",
                mouseWheel: {scrollAmount: 120, preventDefault: !0},
                setHeight: t.height ? t.height : "",
                theme: "minimal-dark"
            }))
        }, destroyScroller: function (e) {
            e.mCustomScrollbar("destroy")
        }, alert: function (e) {
            e = $.extend(!0, {
                container: "",
                place: "append",
                type: "success",
                message: "",
                close: !0,
                reset: !0,
                focus: !0,
                closeInSeconds: 0,
                icon: ""
            }, e);
            var t = mUtil.getUniqueID("App_alert"),
                a = '<div id="' + t + '" class="custom-alerts alert alert-' + e.type + ' fade in">' + (e.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : "") + ("" !== e.icon ? '<i class="fa-lg fa fa-' + e.icon + '"></i>  ' : "") + e.message + "</div>";
            return e.reset && $(".custom-alerts").remove(), e.container ? "append" == e.place ? $(e.container).append(a) : $(e.container).prepend(a) : 1 === $(".page-fixed-main-content").size() ? $(".page-fixed-main-content").prepend(a) : ($("body").hasClass("page-container-bg-solid") || $("body").hasClass("page-content-white")) && 0 === $(".page-head").size() ? $(".page-title").after(a) : $(".page-bar").size() > 0 ? $(".page-bar").after(a) : $(".page-breadcrumb, .breadcrumbs").after(a), e.focus && mApp.scrollTo($("#" + t)), e.closeInSeconds > 0 && setTimeout(function () {
                $("#" + t).remove()
            }, 1e3 * e.closeInSeconds), t
        }, block: function (e, t) {
            var a, n, o;
            if ("spinner" == (t = $.extend(!0, {
                opacity: .1,
                overlayColor: "",
                state: "brand",
                type: "spinner",
                centerX: !0,
                centerY: !0,
                message: "",
                shadow: !0,
                width: "auto"
            }, t)).type ? o = '<div class="m-spinner ' + (a = t.skin ? "m-spinner--skin-" + t.skin : "") + " " + (n = t.state ? "m-spinner--" + t.state : "") + '"></div' : (a = t.skin ? "m-loader--skin-" + t.skin : "", n = t.state ? "m-loader--" + t.state : "", size = t.size ? "m-loader--" + t.size : "", o = '<div class="m-loader ' + a + " " + n + " " + size + '"></div'), t.message && t.message.length > 0) {
                var i = "m-blockui " + (!1 === t.shadow ? "m-blockui-no-shadow" : "");
                html = '<div class="' + i + '"><span>' + t.message + "</span><span>" + o + "</span></div>", t.width = mUtil.realWidth(html) + 10, "body" == e && (html = '<div class="' + i + '" style="margin-left:-' + t.width / 2 + 'px;"><span>' + t.message + "</span><span>" + o + "</span></div>")
            } else html = o;
            var l = {
                message: html,
                centerY: t.centerY,
                centerX: t.centerX,
                css: {top: "30%", left: "50%", border: "0", padding: "0", backgroundColor: "none", width: t.width},
                overlayCSS: {backgroundColor: t.overlayColor, opacity: t.opacity, cursor: "wait"},
                onUnblock: function () {
                    r.css("position", ""), r.css("zoom", "")
                }
            };
            if ("body" == e) l.css.top = "50%", $.blockUI(l); else {
                var r = $(e);
                r.block(l)
            }
        }, unblock: function (e) {
            e && "body" != e ? $(e).unblock() : $.unblockUI()
        }, blockPage: function (e) {
            return mApp.block("body", e)
        }, unblockPage: function () {
            return mApp.unblock("body")
        }
    }
}();
$(document).ready(function () {
    mApp.init()
});
var mUtil = function () {
    var e = [], t = {sm: 544, md: 768, lg: 992, xl: 1200}, a = {
        brand: "#716aca",
        metal: "#c4c5d6",
        light: "#ffffff",
        accent: "#00c5dc",
        primary: "#5867dd",
        success: "#34bfa3",
        info: "#36a3f7",
        warning: "#ffb822",
        danger: "#f4516c"
    }, n = function () {
        var t, a = function () {
            for (var t = 0; t < e.length; t++) e[t].call()
        };
        jQuery(window).resize(function () {
            t && clearTimeout(t), t = setTimeout(function () {
                a()
            }, 250)
        })
    };
    return {
        init: function (e) {
            e && e.breakpoints && (t = e.breakpoints), e && e.colors && (a = e.colors), n()
        }, addResizeHandler: function (t) {
            e.push(t)
        }, runResizeHandlers: function () {
            _runResizeHandlers()
        }, getURLParam: function (e) {
            var t, a, n = window.location.search.substring(1).split("&");
            for (t = 0; t < n.length; t++) if ((a = n[t].split("="))[0] == e) return unescape(a[1]);
            return null
        }, isMobileDevice: function () {
            try {
                return document.createEvent("TouchEvent"), (void 0 !== window.orientation || "ontouchstart" in document.documentElement) && this.getViewPort().width < this.getBreakpoint("lg")
            } catch (e) {
                return !1
            }
        }, isDesktopDevice: function () {
            return !mUtil.isMobileDevice()
        }, getViewPort: function () {
            var e = window, t = "inner";
            return "innerWidth" in window || (t = "client", e = document.documentElement || document.body), {
                width: e[t + "Width"],
                height: e[t + "Height"]
            }
        }, isInResponsiveRange: function (e) {
            var t = this.getViewPort().width;
            return "general" == e || ("desktop" == e && t >= this.getBreakpoint("lg") + 1 || ("tablet" == e && t >= this.getBreakpoint("md") + 1 && t < this.getBreakpoint("lg") || ("mobile" == e && t <= this.getBreakpoint("md") || ("desktop-and-tablet" == e && t >= this.getBreakpoint("md") + 1 || "tablet-and-mobile" == e && t <= this.getBreakpoint("lg")))))
        }, getUniqueID: function (e) {
            return e + Math.floor(Math.random() * (new Date).getTime())
        }, getBreakpoint: function (e) {
            if ($.inArray(e, t)) return t[e]
        }, isset: function (e, t) {
            var a;
            if (-1 !== (t = t || "").indexOf("[")) throw new Error("Unsupported object path notation.");
            t = t.split(".");
            do {
                if (void 0 === e) return !1;
                if (a = t.shift(), !e.hasOwnProperty(a)) return !1;
                e = e[a]
            } while (t.length);
            return !0
        }, getHighestZindex: function (e) {
            for (var t, a, n = $(e); n.length && n[0] !== document;) {
                if (("absolute" === (t = n.css("position")) || "relative" === t || "fixed" === t) && (a = parseInt(n.css("zIndex"), 10), !isNaN(a) && 0 !== a)) return a;
                n = n.parent()
            }
        }, hasClasses: function (e, t) {
            for (var a = t.split(" "), n = 0; n < a.length; n++) if (0 == e.hasClass(a[n])) return !1;
            return !0
        }, realWidth: function (e) {
            var t = $(e).clone();
            t.css("visibility", "hidden"), t.css("overflow", "hidden"), t.css("height", "0"), $("body").append(t);
            var a = t.outerWidth();
            return t.remove(), a
        }, hasFixedPositionedParent: function (e) {
            var t = !1;
            return e.parents().each(function () {
                "fixed" != $(this).css("position") || (t = !0)
            }), t
        }, getRandomInt: function (e, t) {
            return Math.floor(Math.random() * (t - e + 1)) + e
        }, getColor: function (e) {
            return a[e]
        }, isAngularVersion: function () {
            return void 0 !== window.Zone
        }
    }
}();
$(document).ready(function () {
    mUtil.init()
}), jQuery.fn.extend({
    animateClass: function (e, t) {
        var a = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
        jQuery(this).addClass("animated " + e).one(a, function () {
            jQuery(this).removeClass("animated " + e)
        }), t && jQuery(this).one(a, t)
    }, animateDelay: function (e) {
        for (var t = ["webkit-", "moz-", "ms-", "o-", ""], a = 0; a < t.length; a++) jQuery(this).css(t[a] + "animation-delay", e)
    }, animateDuration: function (e) {
        for (var t = ["webkit-", "moz-", "ms-", "o-", ""], a = 0; a < t.length; a++) jQuery(this).css(t[a] + "animation-duration", e)
    }
}), function (e) {
    if (void 0 === mUtil) throw new Error("mUtil is required and must be included before mDatatable.");
    e.fn.mDatatable = function (t) {
        if (0 !== e(this).length && !e(this).hasClass("m-datatable--loaded")) {
            if ("" === e(this).attr("id")) throw new Error("ID is required.");
            var a = this;
            a.debug = !1;
            var n = {
                offset: 110, stateId: "m-meta", init: function (t) {
                    return n.setupBaseDOM.call(), n.setupDOM(a.table), e(a).on("m-datatable--on-layout-updated", n.afterRender), a.debug && n.stateRemove(n.stateId), "remote" !== t.data.type && "local" !== t.data.type || ((!1 === t.data.saveState || !1 === t.data.saveState.cookie && !1 === t.data.saveState.webstorage) && n.stateRemove(n.stateId), "local" === t.data.type && "object" == typeof t.data.source && (a.jsonData = n.dataMapCallback(t.data.source)), n.dataRender()), n.setHeadTitle.call(), n.setHeadTitle.call(this, a.tableFoot), null === t.data.type && (n.setupCellField.call(), n.setupTemplateCell.call(), n.setupSystemColumn.call()), void 0 !== t.layout.header && !1 === t.layout.header && e(a.table).find("thead").remove(), void 0 !== t.layout.footer && !1 === t.layout.footer && e(a.table).find("tfoot").remove(), null !== t.data.type && "local" !== t.data.type || n.layoutUpdate(), e(window).resize(n.fullRender), e(a).height(""), a
                }, layoutUpdate: function () {
                    n.setupSubDatatable.call(), n.setupSystemColumn.call(), n.columnHide.call(), n.sorting.call(), n.setupHover.call(), t.layout.scroll && void 0 === t.detail && 1 === n.getDepth() && n.lockTable.call(), e(a).trigger("m-datatable--on-layout-updated", {table: e(a.table).attr("id")})
                }, lockTable: function () {
                    var o = {
                        lockEnabled: !1, init: function () {
                            o.lockEnabled = e.grep(t.columns, function (e, t) {
                                return void 0 !== e.locked && !1 !== e.locked
                            }), 0 !== o.lockEnabled.length && (n.isLocked() || (a.oriTable = e(a.table).clone()), o.enable())
                        }, enable: function () {
                            var t = function (t) {
                                var i = o.lockEnabledColumns();
                                if (0 !== i.left.length || 0 !== i.right.length) if (e(t).find(".m-datatable__lock").length > 0) n.log("Locked container already exist in: ", t); else if (0 !== e(t).find(".m-datatable__row").length) {
                                    var l = e("<div/>").addClass("m-datatable__lock m-datatable__lock--left"),
                                        r = e("<div/>").addClass("m-datatable__lock m-datatable__lock--scroll"),
                                        s = e("<div/>").addClass("m-datatable__lock m-datatable__lock--right");
                                    e(t).find(".m-datatable__row").each(function () {
                                        var t = e("<tr/>").addClass("m-datatable__row").appendTo(l),
                                            a = e("<tr/>").addClass("m-datatable__row").appendTo(r),
                                            n = e("<tr/>").addClass("m-datatable__row").appendTo(s);
                                        e(this).find(".m-datatable__cell").each(function () {
                                            var o = e(this).data("locked");
                                            void 0 !== o ? (void 0 === o.left && !0 !== o || e(this).appendTo(t), void 0 !== o.right && e(this).appendTo(n)) : e(this).appendTo(a)
                                        }), e(this).remove()
                                    }), i.left.length > 0 && (e(a).addClass("m-datatable--lock"), e(l).appendTo(t)), (i.left.length > 0 || i.right.length > 0) && e(r).appendTo(t), i.right.length > 0 && (e(a).addClass("m-datatable--lock"), e(s).appendTo(t))
                                } else n.log("No row exist in: ", t)
                            };
                            e(a.table).children().each(function () {
                                var a = this;
                                0 === e(this).find(".m-datatable__lock").length && e(this).ready(function () {
                                    t(a)
                                })
                            })
                        }, lockEnabledColumns: function () {
                            var a = e(window).width(), n = t.columns, o = {left: [], right: []};
                            return e.each(n, function (e, t) {
                                void 0 !== t.locked && (void 0 !== t.locked.left && mUtil.getBreakpoint(t.locked.left) <= a && o.left.push(t.locked.left), void 0 !== t.locked.right && mUtil.getBreakpoint(t.locked.right) <= a && o.right.push(t.locked.right))
                            }), o
                        }
                    };
                    return o.init(), o
                }, fullRender: function () {
                    if (n.spinnerCallback(!0), e(a).removeClass("m-datatable--loaded"), n.isLocked()) {
                        var t = e(a.oriTable).children();
                        t.length > 0 && (e(a).removeClass("m-datatable--lock"), e(a.table).empty().html(t), a.oriTable = null, n.setupCellField.call(), o.redraw()), n.updateTableComponents.call()
                    }
                    n.updateRawData(), n.dataRender()
                }, afterRender: function (t, i) {
                    i.table === e(a.table).attr("id") && (n.isLocked() || o.redraw(), e(a).ready(function () {
                        e(a.tableBody).find(".m-datatable__row:even").addClass("m-datatable__row--even"), n.isLocked() && o.redraw(), e(a.tableBody).css("visibility", ""), e(a).addClass("m-datatable--loaded"), n.scrollbar.call(), n.spinnerCallback(!1)
                    }))
                }, setupHover: function () {
                    e(a.tableBody).find(".m-datatable__cell").off("mouseenter", "mouseleave").on("mouseenter", function () {
                        var t = e(this).closest(".m-datatable__row").addClass("m-datatable__row--hover"),
                            a = e(t).index() + 1;
                        e(t).closest(".m-datatable__lock").parent().find(".m-datatable__row:nth-child(" + a + ")").addClass("m-datatable__row--hover")
                    }).on("mouseleave", function () {
                        var t = e(this).closest(".m-datatable__row").removeClass("m-datatable__row--hover"),
                            a = e(t).index() + 1;
                        e(t).closest(".m-datatable__lock").parent().find(".m-datatable__row:nth-child(" + a + ")").removeClass("m-datatable__row--hover")
                    })
                }, adjustLockContainer: function () {
                    if (!n.isLocked()) return 0;
                    var t = e(a.tableHead).width(), o = e(a.tableHead).find(".m-datatable__lock--left").width(),
                        i = e(a.tableHead).find(".m-datatable__lock--right").width();
                    void 0 === o && (o = 0), void 0 === i && (i = 0);
                    var l = Math.floor(t - o - i);
                    return e(a.table).find(".m-datatable__lock--scroll").css("width", l), l
                }, dragResize: function () {
                    var t, n, o = !1, i = void 0;
                    e(a.tableHead).find(".m-datatable__cell").mousedown(function (a) {
                        i = e(this), o = !0, t = a.pageX, n = e(this).width(), e(i).addClass("m-datatable__cell--resizing")
                    }).mousemove(function (l) {
                        if (o) {
                            var r = e(i).index(), s = e(a.tableBody), d = e(i).closest(".m-datatable__lock");
                            if (d) {
                                var c = e(d).index();
                                s = e(a.tableBody).find(".m-datatable__lock").eq(c)
                            }
                            e(s).find(".m-datatable__row").each(function (a, o) {
                                e(o).find(".m-datatable__cell").eq(r).width(n + (l.pageX - t)).children().width(n + (l.pageX - t))
                            }), e(i).children().width(n + (l.pageX - t))
                        }
                    }).mouseup(function () {
                        e(i).removeClass("m-datatable__cell--resizing"), o = !1
                    }), e(document).mouseup(function () {
                        e(i).removeClass("m-datatable__cell--resizing"), o = !1
                    })
                }, initHeight: function () {
                    if (t.layout.height && t.layout.scroll) {
                        var n = e(a.tableHead).find(".m-datatable__row").height(),
                            o = e(a.tableFoot).find(".m-datatable__row").height(), i = t.layout.height;
                        void 0 !== n && (i -= n), void 0 !== o && (i -= o), e(a.tableBody).height(i)
                    }
                }, setupBaseDOM: function () {
                    a.old = e(a).clone(), t.layout.height && e(a).height(t.layout.height), "TABLE" === e(a).prop("tagName") ? (a.table = e(a).removeClass("m-datatable").addClass("m-datatable__table"), 0 === e(a.table).parents(".m-datatable").length && (a.wrap = e("<div/>").addClass("m-datatable").addClass("m-datatable--" + t.layout.theme), a.table.wrap(a.wrap))) : (a.wrap = e(a).addClass("m-datatable").addClass("m-datatable--" + t.layout.theme), a.table = e("<table/>").addClass("m-datatable__table").appendTo(a)), void 0 !== t.layout.class && e(a.wrap).addClass(t.layout.class), e(a.table).removeClass("m-datatable--destroyed").css("display", "block").attr("id", mUtil.getUniqueID("m-datatable--")), t.layout.height && e(a.table).height(t.layout.height), null === t.data.type && e(a.table).css("width", "").css("display", ""), a.tableHead = e(a.table).find("thead"), 0 === e(a.tableHead).length && (a.tableHead = e("<thead/>").prependTo(a.table)), a.tableBody = e(a.table).find("tbody"), 0 === e(a.tableBody).length && (a.tableBody = e("<tbody/>").appendTo(a.table)), void 0 !== t.layout.footer && t.layout.footer && (a.tableFoot = e(a.table).find("tfoot"), 0 === e(a.tableFoot).length && (a.tableFoot = e("<tfoot/>").appendTo(a.table)))
                }, setupCellField: function (n) {
                    void 0 === n && (n = e(a.table).children());
                    var o = t.columns;
                    e.each(n, function (t, a) {
                        e(a).find(".m-datatable__row").each(function (t, a) {
                            e(a).find(".m-datatable__cell").each(function (t, a) {
                                void 0 !== o[t] && e(a).data(o[t])
                            })
                        })
                    })
                }, setupTemplateCell: function (o) {
                    void 0 === o && (o = a.tableBody);
                    var i = t.columns;
                    e(o).find(".m-datatable__row").each(function (t, o) {
                        var l = e(o).data("obj");
                        l.rowIndex = t, l.getIndex = function () {
                            return t
                        }, l.getDatatable = function () {
                            return a
                        }, void 0 === l && (l = {}, e(o).find(".m-datatable__cell").each(function (t, a) {
                            var n = e.grep(i, function (t, n) {
                                return e(a).data("field") === t.field
                            })[0];
                            void 0 !== n && (l[n.field] = e(a).text())
                        })), e(o).find(".m-datatable__cell").each(function (t, a) {
                            var o = e.grep(i, function (t, n) {
                                return e(a).data("field") === t.field
                            })[0];
                            if (void 0 !== o && void 0 !== o.template) {
                                var r = "";
                                "string" == typeof o.template && (r = n.dataPlaceholder(o.template, l)), "function" == typeof o.template && (r = o.template(l));
                                var s = e("<span/>").append(r);
                                e(a).html(s), void 0 !== o.overflow && e(s).css("overflow", o.overflow)
                            }
                        })
                    })
                }, setupSystemColumn: function () {
                    if (0 !== a.jsonData.length) {
                        var n = t.columns;
                        e(a.tableBody).find(".m-datatable__row").each(function (t, a) {
                            e(a).find(".m-datatable__cell").each(function (t, a) {
                                var i = e.grep(n, function (t, n) {
                                    return e(a).data("field") === t.field
                                })[0];
                                if (void 0 !== i) {
                                    var l = e(a).text();
                                    if (void 0 !== i.selector && !1 !== i.selector) {
                                        if (e(a).find('.m-checkbox [type="checkbox"]').length > 0) return;
                                        e(a).addClass("m-datatable__cell--check");
                                        var r = e("<label/>").addClass("m-checkbox m-checkbox--single").append(e("<input/>").attr("type", "checkbox").attr("value", l).on("click", function () {
                                            e(this).is(":checked") ? o.setActive(this) : o.setInactive(this)
                                        })).append(e("<span/>"));
                                        void 0 !== i.selector.class && e(r).addClass(i.selector.class), e(a).children().html(r)
                                    }
                                    if (void 0 !== i.subtable && i.subtable) {
                                        if (e(a).find(".m-datatable__toggle-subtable").length > 0) return;
                                        e(a).children().html(e("<a/>").addClass("m-datatable__toggle-subtable").attr("href", "#").attr("data-value", l).append(e("<i/>").addClass(o.getOption("layout.icons.rowDetail.collapse"))))
                                    }
                                }
                            })
                        });
                        var i = function (t) {
                            var a = e.grep(n, function (a, n) {
                                return e(t).data("field") === a.field
                            })[0];
                            if (void 0 !== a && void 0 !== a.selector && !1 !== a.selector) {
                                if (e(t).find('.m-checkbox [type="checkbox"]').length > 0) return;
                                e(t).addClass("m-datatable__cell--check");
                                var i = e("<label/>").addClass("m-checkbox m-checkbox--single m-checkbox--all").append(e("<input/>").attr("type", "checkbox").on("click", function () {
                                    e(this).is(":checked") ? o.setActiveAll(!0) : o.setActiveAll(!1)
                                })).append(e("<span/>"));
                                void 0 !== a.selector.class && e(i).addClass(a.selector.class), e(t).children().html(i)
                            }
                        };
                        void 0 !== t.layout.header && !0 === t.layout.header && i(e(a.tableHead).find(".m-datatable__row").first().find(".m-datatable__cell").first()), void 0 !== t.layout.footer && !0 === t.layout.footer && i(e(a.tableFoot).find(".m-datatable__row").first().find(".m-datatable__cell").first())
                    }
                }, adjustCellsWidth: function () {
                    var t = e(a.tableHead).width(), o = n.getOneRow(a.tableHead, 1).length;
                    if (o > 0) {
                        t -= 15 * o;
                        var i = Math.floor(t / o);
                        i <= n.offset && (i = n.offset), e(a.table).find(".m-datatable__row").find(".m-datatable__cell").each(function (t, a) {
                            var n = i, o = e(a).data("width");
                            void 0 !== o && (n = o), e(a).children().css("width", n)
                        })
                    }
                }, adjustCellsHeight: function () {
                    e(a.table).find(".m-datatable__row"), e.each(e(a.table).children(), function (t, a) {
                        for (var o = 1; o <= n.getTotalRows(a); o++) {
                            var i = n.getOneRow(a, o, !1);
                            if (e(i).length > 0) {
                                var l = Math.max.apply(null, e(i).map(function () {
                                    return e(this).height()
                                }).get());
                                e(i).css("height", Math.ceil(l))
                            }
                        }
                    })
                }, setupDOM: function (t) {
                    e(t).find("> thead").addClass("m-datatable__head"), e(t).find("> tbody").addClass("m-datatable__body"), e(t).find("> tfoot").addClass("m-datatable__foot"), e(t).find("tr").addClass("m-datatable__row"), e(t).find("tr > th, tr > td").addClass("m-datatable__cell"), e(t).find("tr > th, tr > td").each(function (t, a) {
                        0 === e(a).find("span").length && e(a).wrapInner(e("<span/>").width(n.offset))
                    })
                }, scrollbar: function () {
                    var i = {
                        tableLocked: null,
                        mcsOptions: {
                            scrollInertia: 0,
                            autoDraggerLength: !0,
                            autoHideScrollbar: !0,
                            autoExpandScrollbar: !1,
                            alwaysShowScrollbar: 0,
                            mouseWheel: {scrollAmount: 120, preventDefault: !1},
                            advanced: {updateOnContentResize: !0, autoExpandHorizontalScroll: !0},
                            theme: "minimal-dark"
                        },
                        init: function () {
                            var n = mUtil.getViewPort().width;
                            if (t.layout.scroll) {
                                e(a).addClass("m-datatable--scroll");
                                var o = e(a.tableBody).find(".m-datatable__lock--scroll");
                                e(o).length > 0 ? (i.scrollHead = e(a.tableHead).find("> .m-datatable__lock--scroll > .m-datatable__row"), i.scrollFoot = e(a.tableFoot).find("> .m-datatable__lock--scroll > .m-datatable__row"), i.tableLocked = e(a.tableBody).find(".m-datatable__lock:not(.m-datatable__lock--scroll)"), n > mUtil.getBreakpoint("lg") ? i.mCustomScrollbar(o) : i.defaultScrollbar(o)) : (i.scrollHead = e(a.tableHead).find("> .m-datatable__row"), i.scrollFoot = e(a.tableFoot).find("> .m-datatable__row"), n > mUtil.getBreakpoint("lg") ? i.mCustomScrollbar(a.tableBody) : i.defaultScrollbar(a.tableBody))
                            } else e(a.table).css("height", "auto").css("overflow-x", "auto")
                        },
                        defaultScrollbar: function (t) {
                            e(t).css("overflow", "auto").css("max-height", o.getOption("layout.height")).on("scroll", i.onScrolling)
                        },
                        onScrolling: function (t) {
                            var a = e(this).scrollLeft(), n = e(this).scrollTop();
                            e(i.scrollHead).css("left", -a), e(i.scrollFoot).css("left", -a), e(i.tableLocked).each(function (t, a) {
                                e(a).css("top", -n)
                            })
                        },
                        mCustomScrollbar: function (t) {
                            var l = "xy";
                            null === o.getOption("layout.height") && (l = "x");
                            var r = e.extend({}, i.mcsOptions, {
                                axis: l,
                                setHeight: e(a.tableBody).height(),
                                callbacks: {
                                    whileScrolling: function () {
                                        var t = this.mcs;
                                        e(i.scrollHead).css("left", t.left), e(i.scrollFoot).css("left", t.left), e(i.tableLocked).each(function (a, n) {
                                            e(n).css("top", t.top)
                                        })
                                    }
                                }
                            });
                            !0 === o.getOption("layout.smoothScroll.scrollbarShown") && e(t).attr("data-scrollbar-shown", "true"), n.mCustomScrollbar(t, r), e(t).mCustomScrollbar("scrollTo", "top")
                        }
                    };
                    return i.init(), i
                }, mCustomScrollbar: function (t, n) {
                    e(a.tableBody).css("overflow", ""), 0 === e(t).find(".mCustomScrollbar").length && (e(a.tableBody).hasClass("mCustomScrollbar") && e(a.tableBody).mCustomScrollbar("destroy"), e(t).mCustomScrollbar(n))
                }, setHeadTitle: function (o) {
                    void 0 === o && (o = a.tableHead);
                    var i = t.columns, l = e(o).find(".m-datatable__row"), r = e(o).find(".m-datatable__cell");
                    0 === e(l).length && (l = e("<tr/>").appendTo(o)), e.each(i, function (t, n) {
                        var o = e(r).eq(t);
                        if (0 === e(o).length && (o = e("<th/>").appendTo(l)), void 0 !== n.title && e(o).html(n.title).attr("data-field", n.field).data(n), void 0 !== n.textAlign) {
                            var i = void 0 !== a.textAlign[n.textAlign] ? a.textAlign[n.textAlign] : "";
                            e(o).addClass(i)
                        }
                    }), n.setupDOM(o)
                }, dataRender: function () {
                    e(a.table).siblings(".m-datatable__pager").removeClass("m-datatable--paging-loaded");
                    var o = function (o) {
                        e(a).removeClass("m-datatable--error"), t.pagination && (void 0 !== o && t.data.serverPaging && "local" !== t.data.type ? n.paging(o.meta) : n.paging(null, function (t, o) {
                            e(t.pager).off().on("m-datatable--on-update-perpage", function (a, n) {
                                e(t.pager).remove(), t.init(n)
                            });
                            var i = Math.max(o.perpage * (o.page - 1), 0), l = Math.min(i + o.perpage, o.total);
                            n.updateRawData(), a.jsonData = e(a.jsonData).slice(i, l), n.insertData()
                        })), n.insertData()
                    };
                    "local" === t.data.type || void 0 === t.data.source.read && null !== a.jsonData ? o() : n.getData().done(o)
                }, insertData: function () {
                    var i = o.getDataSourceParam(),
                        l = e("<tbody/>").addClass("m-datatable__body").css("visibility", "hidden");
                    e.each(a.jsonData, function (n, o) {
                        for (var r = e("<tr/>").attr("data-row", n).data("obj", o), s = 0, d = [], c = t.columns.length, u = 0; u < c; u += 1) {
                            var m = t.columns[u], p = [];
                            if (i.sort.field === m.field && p.push("m-datatable__cell--sorted"), void 0 !== m.textAlign) {
                                var f = void 0 !== a.textAlign[m.textAlign] ? a.textAlign[m.textAlign] : "";
                                p.push(f)
                            }
                            d[s++] = '<td data-field="' + m.field + '"', d[s++] = ' class="' + p.join("") + '"', d[s++] = ">", d[s++] = o[m.field], d[s++] = "</td>"
                        }
                        e(r).append(d.join("")), e(l).append(r)
                    }), 0 === a.jsonData.length && (e("<span/>").addClass("m-datatable--error").width("100%").html(o.getOption("translate.records.noRecords")).appendTo(l), e(a).addClass("m-datatable--error")), e(a.tableBody).replaceWith(l), a.tableBody = l, n.setupDOM(a.table), n.setupCellField([a.tableBody]), n.setupTemplateCell(a.tableBody), n.layoutUpdate()
                }, updateTableComponents: function () {
                    a.tableHead = e(a.table).children("thead"), a.tableBody = e(a.table).children("tbody"), a.tableFoot = e(a.table).children("tfoot")
                }, getData: function () {
                    var i = {dataType: "json", method: "GET", data: {}, timeout: 3e4};
                    return "local" === t.data.type && (i.url = t.data.source), "remote" === t.data.type && (i.url = o.getOption("data.source.read.url"), "string" != typeof i.url && (i.url = o.getOption("data.source.read")), "string" != typeof i.url && (i.url = o.getOption("data.source")), i.data.datatable = e.extend({}, o.getDataSourceParam(), o.getOption("data.source.read.params")), i.method = "POST"), e.ajax(i).done(function (t, o, i) {
                        a.jsonData = n.dataMapCallback(t), e(a).trigger("m-datatable--on-ajax-done", a.jsonData)
                    }).fail(function (t, n, i) {
                        e(a).trigger("m-datatable--on-ajax-fail", [t]), e("<span/>").addClass("m-datatable--error").width("100%").html(o.getOption("translate.records.noRecords")).appendTo(a.tableBody), e(a).addClass("m-datatable--error")
                    }).always(function () {
                    })
                }, paging: function (i, l) {
                    var r = {
                        initCallback: !1,
                        meta: null,
                        pager: null,
                        paginateEvent: null,
                        pagerLayout: {pagination: null, info: null},
                        callback: null,
                        init: function (i) {
                            r.meta = i, void 0 !== i && null !== i || (r.meta = o.getDataSourceParam("pagination"), 0 === r.meta.perpage && (r.meta.perpage = t.data.pageSize || 10), r.meta.total = a.jsonData.length, r.initCallback = !0), r.meta.pages = Math.max(Math.ceil(r.meta.total / r.meta.perpage), 1), r.meta.page > r.meta.pages && (r.meta.page = r.meta.pages), r.paginateEvent = n.getTablePrefix(), r.pager = e(a.table).siblings(".m-datatable__pager"), e(r.pager).hasClass("m-datatable--paging-loaded") || (e(r.pager).remove(), 0 !== r.meta.pages && (o.setDataSourceParam("pagination", r.meta), r.callback = r.serverCallback, "function" == typeof l && (r.callback = l), r.addPaginateEvent(), r.populate(), r.meta.page = Math.max(r.meta.page || 1, r.meta.page), e(a).trigger(r.paginateEvent, r.meta), r.initCallback && r.callback(r, r.meta), r.pagingBreakpoint.call(), e(window).resize(r.pagingBreakpoint)))
                        },
                        serverCallback: function (e, t) {
                            n.dataRender()
                        },
                        populate: function () {
                            var t = o.getOption("layout.icons.pagination"),
                                n = o.getOption("translate.toolbar.pagination.items.default");
                            r.pager = e("<div/>").addClass("m-datatable__pager m-datatable--paging-loaded clearfix");
                            var i = e("<ul/>").addClass("m-datatable__pager-nav");
                            r.pagerLayout.pagination = i, e("<li/>").append(e("<a/>").attr("title", n.first).addClass("m-datatable__pager-link m-datatable__pager-link--first").append(e("<i/>").addClass(t.first)).on("click", r.gotoMorePage).attr("data-page", 1)).appendTo(i), e("<li/>").append(e("<a/>").attr("title", n.prev).addClass("m-datatable__pager-link m-datatable__pager-link--prev").append(e("<i/>").addClass(t.prev)).on("click", r.gotoMorePage)).appendTo(i), e("<li/>").append(e("<a/>").attr("title", n.more).addClass("m-datatable__pager-link m-datatable__pager-link--more-prev").html(e("<i/>").addClass(t.more)).on("click", r.gotoMorePage)).appendTo(i), e("<li/>").append(e("<input/>").attr("type", "text").addClass("m-pager-input form-control").attr("title", n.input).on("keyup", function () {
                                e(this).attr("data-page", Math.abs(e(this).val()))
                            }).on("keypress", function (e) {
                                13 === e.which && r.gotoMorePage(e)
                            })).appendTo(i);
                            for (var l = 0; l < r.meta.pages; l++) {
                                var s = l + 1;
                                e("<li/>").append(e("<a/>").addClass("m-datatable__pager-link m-datatable__pager-link-number").text(s).attr("data-page", s).on("click", r.gotoPage)).appendTo(i)
                            }
                            e("<li/>").append(e("<a/>").attr("title", n.more).addClass("m-datatable__pager-link m-datatable__pager-link--more-next").html(e("<i/>").addClass(t.more)).on("click", r.gotoMorePage)).appendTo(i), e("<li/>").append(e("<a/>").attr("title", n.next).addClass("m-datatable__pager-link m-datatable__pager-link--next").append(e("<i/>").addClass(t.next)).on("click", r.gotoMorePage)).appendTo(i), e("<li/>").append(e("<a/>").attr("title", n.last).addClass("m-datatable__pager-link m-datatable__pager-link--last").append(e("<i/>").addClass(t.last)).on("click", r.gotoMorePage).attr("data-page", r.meta.pages)).appendTo(i), o.getOption("toolbar.items.info") && (r.pagerLayout.info = e("<div/>").addClass("m-datatable__pager-info").append(e("<span/>").addClass("m-datatable__pager-detail"))), e.each(o.getOption("toolbar.layout"), function (t, a) {
                                e(r.pagerLayout[a]).appendTo(r.pager)
                            });
                            var d = e("<select/>").addClass("selectpicker m-datatable__pager-size").attr("title", o.getOption("translate.toolbar.pagination.items.default.select")).attr("data-width", "70px").val(r.meta.perpage).on("change", r.updatePerpage).prependTo(r.pagerLayout.info);
                            e.each(o.getOption("toolbar.items.pagination.pageSizeSelect"), function (t, a) {
                                var n = a;
                                -1 === a && (n = "All"), e("<option/>").attr("value", a).html(n).appendTo(d)
                            }), e(a).ready(function () {
                                e(".selectpicker").selectpicker().siblings(".dropdown-toggle").attr("title", o.getOption("translate.toolbar.pagination.items.default.select"))
                            }), r.paste()
                        },
                        paste: function () {
                            e.each(e.unique(o.getOption("toolbar.placement")), function (t, n) {
                                "bottom" === n && e(r.pager).clone(!0).insertAfter(a.table), "top" === n && e(r.pager).clone(!0).addClass("m-datatable__pager--top").insertBefore(a.table)
                            })
                        },
                        gotoMorePage: function (t) {
                            if (t.preventDefault(), "disabled" === e(this).attr("disabled")) return !1;
                            var a = e(this).attr("data-page");
                            return void 0 === a && (a = e(t.target).attr("data-page")), e(r.pager).find('.m-datatable__pager-link-number[data-page="' + a + '"]').trigger("click"), !1
                        },
                        gotoPage: function (t) {
                            t.preventDefault(), e(this).hasClass("m-datatable__pager-link--active") || (r.meta.page = parseInt(e(this).data("page")), e(a).trigger(r.paginateEvent, r.meta), r.callback(r, r.meta), e(r.pager).trigger("m-datatable--on-goto-page", r.meta))
                        },
                        updatePerpage: function (t) {
                            t.preventDefault(), null === o.getOption("layout.height") && e("html, body").animate({scrollTop: e(a).position().top}), r.pager = e(a.table).siblings(".m-datatable__pager").removeClass("m-datatable--paging-loaded"), t.originalEvent && (r.meta.perpage = parseInt(e(this).val())), e(r.pager).find("select.m-datatable__pager-size").val(r.meta.perpage).attr("data-selected", r.meta.perpage), o.setDataSourceParam("pagination", r.meta), e(r.pager).trigger("m-datatable--on-update-perpage", r.meta), e(a).trigger(r.paginateEvent, r.meta), r.callback(r, r.meta), r.updateInfo.call()
                        },
                        addPaginateEvent: function (t) {
                            e(a).off(r.paginateEvent).on(r.paginateEvent, function (t, i) {
                                n.spinnerCallback(!0), r.pager = e(a.table).siblings(".m-datatable__pager");
                                var l = e(r.pager).find(".m-datatable__pager-nav");
                                e(l).find(".m-datatable__pager-link--active").removeClass("m-datatable__pager-link--active"), e(l).find('.m-datatable__pager-link-number[data-page="' + i.page + '"]').addClass("m-datatable__pager-link--active"), e(l).find(".m-datatable__pager-link--prev").attr("data-page", Math.max(i.page - 1, 1)), e(l).find(".m-datatable__pager-link--next").attr("data-page", Math.min(i.page + 1, i.pages)), e(r.pager).each(function () {
                                    e(this).find('.m-pager-input[type="text"]').prop("value", i.page)
                                }), o.setDataSourceParam("pagination", r.meta), e(r.pager).find("select.m-datatable__pager-size").val(i.perpage).attr("data-selected", i.perpage), e(a.table).find('.m-checkbox > [type="checkbox"]').prop("checked", !1), e(a.table).find(".m-datatable__row--active").removeClass("m-datatable__row--active"), r.updateInfo.call(), r.pagingBreakpoint.call()
                            })
                        },
                        updateInfo: function () {
                            var t = Math.max(r.meta.perpage * (r.meta.page - 1) + 1, 1),
                                a = Math.min(t + r.meta.perpage - 1, r.meta.total);
                            e(r.pager).find(".m-datatable__pager-info").find(".m-datatable__pager-detail").html(n.dataPlaceholder(o.getOption("translate.toolbar.pagination.items.info"), {
                                start: t,
                                end: -1 === r.meta.perpage ? r.meta.total : a,
                                pageSize: -1 === r.meta.perpage || r.meta.perpage >= r.meta.total ? r.meta.total : r.meta.perpage,
                                total: r.meta.total
                            }))
                        },
                        pagingBreakpoint: function () {
                            var t = e(a.table).siblings(".m-datatable__pager").find(".m-datatable__pager-nav");
                            if (0 !== e(t).length) {
                                var n = o.getCurrentPage(), i = e(t).find(".m-pager-input").closest("li");
                                e(t).find("li").show(), e.each(o.getOption("toolbar.items.pagination.pages"), function (a, l) {
                                    if (mUtil.isInResponsiveRange(a)) {
                                        switch (a) {
                                            case"desktop":
                                            case"tablet":
                                                var s = Math.ceil(n / l.pagesNumber) * l.pagesNumber,
                                                    d = s - l.pagesNumber;
                                                e(i).hide(), e(t).each(function (t, a) {
                                                    e(a).find(".m-datatable__pager-link-number").closest("li").hide().slice(d, s).show()
                                                }), r.meta = o.getDataSourceParam("pagination"), r.paginationUpdate();
                                                break;
                                            case"mobile":
                                                e(i).show(), e(t).find(".m-datatable__pager-link--more-prev").closest("li").hide(), e(t).find(".m-datatable__pager-link--more-next").closest("li").hide(), e(t).find(".m-datatable__pager-link-number").closest("li").hide()
                                        }
                                        return !1
                                    }
                                })
                            }
                        },
                        paginationUpdate: function () {
                            var t = e(a.table).siblings(".m-datatable__pager").find(".m-datatable__pager-nav"),
                                n = e(t).find(".m-datatable__pager-link--more-prev"),
                                i = e(t).find(".m-datatable__pager-link--more-next"),
                                l = e(t).find(".m-datatable__pager-link--first"),
                                s = e(t).find(".m-datatable__pager-link--prev"),
                                d = e(t).find(".m-datatable__pager-link--next"),
                                c = e(t).find(".m-datatable__pager-link--last"),
                                u = e(t).find(".m-datatable__pager-link-number:visible"),
                                m = Math.max(e(u).first().data("page") - 1, 1);
                            e(n).each(function (t, a) {
                                e(a).attr("data-page", m)
                            }), 1 === m ? e(n).parent().hide() : e(n).parent().show();
                            var p = Math.min(e(u).last().data("page") + 1, r.meta.pages);
                            e(i).each(function (t, a) {
                                e(i).attr("data-page", p).show()
                            }), p === r.meta.pages && p === e(u).last().data("page") ? e(i).parent().hide() : e(i).parent().show(), 1 === r.meta.page ? (e(l).attr("disabled", !0).addClass("m-datatable__pager-link--disabled"), e(s).attr("disabled", !0).addClass("m-datatable__pager-link--disabled")) : (e(l).removeAttr("disabled").removeClass("m-datatable__pager-link--disabled"), e(s).removeAttr("disabled").removeClass("m-datatable__pager-link--disabled")), r.meta.page === r.meta.pages ? (e(d).attr("disabled", !0).addClass("m-datatable__pager-link--disabled"), e(c).attr("disabled", !0).addClass("m-datatable__pager-link--disabled")) : (e(d).removeAttr("disabled").removeClass("m-datatable__pager-link--disabled"), e(c).removeAttr("disabled").removeClass("m-datatable__pager-link--disabled"));
                            var f = o.getOption("toolbar.items.pagination.navigation");
                            f.first || e(l).remove(), f.prev || e(s).remove(), f.next || e(d).remove(), f.last || e(c).remove()
                        }
                    };
                    return r.init(i), r
                }, columnHide: function () {
                    var n = mUtil.getViewPort().width;
                    e.each(t.columns, function (t, o) {
                        if (void 0 !== o.responsive) {
                            var i = o.field, l = e.grep(e(a.table).find(".m-datatable__cell"), function (t, a) {
                                return i === e(t).data("field")
                            });
                            mUtil.getBreakpoint(o.responsive.hidden) >= n ? e(l).hide() : e(l).show(), mUtil.getBreakpoint(o.responsive.visible) <= n ? e(l).show() : e(l).hide()
                        }
                    })
                }, setupSubDatatable: function () {
                    var i = o.getOption("detail.content");
                    if ("function" == typeof i) {
                        if (e(a.table).find(".m-datatable__detail").length > 0) return;
                        e(a).addClass("m-datatable--subtable"), t.columns[0].subtable = !0;
                        var l = function (n) {
                            n.preventDefault();
                            var l = e(this).closest(".m-datatable__row"), r = e(l).next().toggle(),
                                s = e(this).closest("[data-field]:first-child").find(".m-datatable__toggle-subtable").data("value"),
                                d = e(this).find("i").removeAttr("class");
                            e(r).is(":hidden") ? (e(d).addClass(o.getOption("layout.icons.rowDetail.collapse")), e(l).removeClass("m-datatable__row--detail-expanded"), e(a).trigger("m-datatable--on-collapse-detail", [l])) : (e(d).addClass(o.getOption("layout.icons.rowDetail.expand")), e(l).addClass("m-datatable__row--detail-expanded"), e(a).trigger("m-datatable--on-expand-detail", [l]), n.data = e.grep(a.jsonData, function (e, a) {
                                return s === e[t.columns[0].field]
                            })[0], n.detailCell = e(r).find(".m-datatable__detail"), 0 === e(n.detailCell).find(".m-datatable").length && i(n))
                        }, r = t.columns;
                        e(a.tableBody).find(".m-datatable__row").each(function (t, a) {
                            e(a).find(".m-datatable__cell").each(function (t, a) {
                                var n = e.grep(r, function (t, n) {
                                    return e(a).data("field") === t.field
                                })[0];
                                if (void 0 !== n) {
                                    var i = e(a).text();
                                    if (void 0 !== n.subtable && n.subtable) {
                                        if (e(a).find(".m-datatable__toggle-subtable").length > 0) return;
                                        e(a).children().html(e("<a/>").addClass("m-datatable__toggle-subtable").attr("href", "#").attr("data-value", i).attr("title", o.getOption("detail.title")).on("click", l).append(e("<i/>").addClass(o.getOption("layout.icons.rowDetail.collapse"))))
                                    }
                                }
                            })
                        }), e(a.tableBody).find(".m-datatable__row").each(function () {
                            var t = e("<tr/>").addClass("m-datatable__row-detail").hide().append(e("<td/>").addClass("m-datatable__detail").attr("colspan", n.getTotalColumns()));
                            e(this).after(t), e(this).hasClass("m-datatable__row--even") && e(t).addClass("m-datatable__row-detail--even")
                        })
                    }
                }, dataMapCallback: function (e) {
                    var t = e;
                    return void 0 !== e.data && (t = e.data), t
                }, isSpinning: !1, spinnerCallback: function (e) {
                    if (e) {
                        if (!n.isSpinning) {
                            var t = o.getOption("layout.spinner");
                            !0 === t.message && (t.message = o.getOption("translate.records.processing")), n.isSpinning = !0, mApp.block(a, t)
                        }
                    } else n.isSpinning = !1, mApp.unblock(a)
                }, log: function (e, t) {
                    void 0 === t && (t = ""), a.debug && console.log(e, t)
                }, isLocked: function () {
                    return e(a).hasClass("m-datatable--lock") || !1
                }, replaceTableContent: function (t, n) {
                    void 0 === n && (n = a.tableBody), e(n).hasClass("mCustomScrollbar") ? e(n).find(".mCSB_container").html(t) : e(n).html(t)
                }, getExtraSpace: function (t) {
                    return parseInt(e(t).css("paddingRight")) + parseInt(e(t).css("paddingLeft")) + (parseInt(e(t).css("marginRight")) + parseInt(e(t).css("marginLeft"))) + Math.ceil(e(t).css("border-right-width").replace("px", ""))
                }, dataPlaceholder: function (t, a) {
                    var n = t;
                    return e.each(a, function (e, t) {
                        n = n.replace("{{" + e + "}}", t)
                    }), n
                }, getTableId: function (t) {
                    return void 0 === t && (t = ""), e(a).attr("id") + t
                }, getTablePrefix: function (e) {
                    return void 0 !== e && (e = "-" + e), "m-datatable__" + n.getTableId() + "-" + n.getDepth() + e
                }, getDepth: function () {
                    var t = 0, n = a.table;
                    do {
                        n = e(n).parents(".m-datatable__table"), t++
                    } while (e(n).length > 0);
                    return t
                }, stateKeep: function (e, a) {
                    e = n.getTablePrefix(e), !1 !== t.data.saveState && (t.data.saveState.webstorage && localStorage ? localStorage.setItem(e, JSON.stringify(a)) : Cookies.set(e, JSON.stringify(a)))
                }, stateGet: function (e, a) {
                    if (e = n.getTablePrefix(e), !1 !== t.data.saveState) {
                        var o = null;
                        return void 0 !== (o = t.data.saveState.webstorage && localStorage ? localStorage.getItem(e) : Cookies.get(e)) && null !== o ? JSON.parse(o) : void 0
                    }
                }, stateUpdate: function (t, a) {
                    var o = n.stateGet(t);
                    void 0 !== o && null !== o || (o = {}), n.stateKeep(t, e.extend({}, o, a))
                }, stateRemove: function (e) {
                    e = n.getTablePrefix(e), localStorage && localStorage.removeItem(e), Cookies.remove(e)
                }, getTotalColumns: function (t) {
                    return void 0 === t && (t = a.tableBody), e(t).find(".m-datatable__row").first().find(".m-datatable__cell").length
                }, getTotalRows: function (t) {
                    return void 0 === t && (t = a.tableBody), e(t).find(".m-datatable__row").first().parent().find(".m-datatable__row").length
                }, getOneRow: function (t, a, n) {
                    void 0 === n && (n = !0);
                    var o = e(t).find(".m-datatable__row:not(.m-datatable__row-detail):nth-child(" + a + ")");
                    return n && (o = o.find(".m-datatable__cell")), o
                }, hasOverflowCells: function (t) {
                    var a = e(t).find("tr:first-child").find(".m-datatable__cell"), n = 0;
                    return a.length > 0 && (e(a).each(function (t, a) {
                        n += Math.ceil(e(a).innerWidth())
                    }), n >= e(t).outerWidth())
                }, hasOverflowX: function (t) {
                    var a = e(t).find("*");
                    return a.length > 0 && Math.max.apply(null, e(a).map(function () {
                        return e(this).outerWidth(!0)
                    }).get()) > e(t).width()
                }, hasOverflowY: function (t) {
                    var a = e(t).find(".m-datatable__row"), n = 0;
                    return a.length > 0 && (e(a).each(function (t, a) {
                        n += Math.floor(e(a).innerHeight())
                    }), n > e(t).innerHeight())
                }, sortColumn: function (t, n, o) {
                    void 0 === n && (n = "asc"), void 0 === o && (o = !1);
                    var i = e(t).index(), l = e(a.tableBody).find(".m-datatable__row"),
                        r = e(t).closest(".m-datatable__lock").index();
                    -1 !== r && (l = e(a.tableBody).find(".m-datatable__lock:nth-child(" + (r + 1) + ")").find(".m-datatable__row"));
                    var s = e(l).parent();
                    e(l).sort(function (t, a) {
                        var l = e(t).find("td:nth-child(" + i + ")").text(),
                            r = e(a).find("td:nth-child(" + i + ")").text();
                        return o && (l = parseInt(l), r = parseInt(r)), "asc" === n ? l > r ? 1 : l < r ? -1 : 0 : l < r ? 1 : l > r ? -1 : 0
                    }).appendTo(s)
                }, sorting: function () {
                    var i = {
                        init: function () {
                            t.sortable && (e(a.tableHead).find(".m-datatable__cell:not(.m-datatable__cell--check)").addClass("m-datatable__cell--sort").off("click").on("click", i.sortClick), i.setIcon())
                        }, setIcon: function () {
                            var t = o.getDataSourceParam("sort"),
                                n = e(a.tableHead).find('.m-datatable__cell[data-field="' + t.field + '"]').attr("data-sort", t.sort),
                                i = e(n).find("span"), l = e(i).find("i"), r = o.getOption("layout.icons.sort");
                            e(l).length > 0 ? e(l).removeAttr("class").addClass(r[t.sort]) : e(i).append(e("<i/>").addClass(r[t.sort]))
                        }, sortClick: function (l) {
                            var r = o.getDataSourceParam("sort"), s = e(this).data("field");
                            n.getColumnByField(s);
                            if (e(a.tableHead).find(".m-datatable__cell > span > i").remove(), t.sortable) {
                                n.spinnerCallback(!0);
                                var d = "desc";
                                r.field === s && (d = r.sort), r = {
                                    field: s,
                                    sort: d = void 0 === d || "desc" === d ? "asc" : "desc"
                                }, o.setDataSourceParam("sort", r), i.setIcon(), !1 === t.data.serverSorting && n.updateRawData(), setTimeout(function () {
                                    n.dataRender(), e(a).trigger("m-datatable--on-sort", r)
                                }, 300)
                            }
                        }
                    };
                    i.init()
                }, updateRawData: function () {
                    var t = o.getDataSourceParam();
                    return "undefined" === e.type(a.fullJsonData) && (a.fullJsonData = a.jsonData), a.jsonData = e(a.fullJsonData).sort(function (e, a) {
                        return "asc" === t.sort.sort ? e[t.sort.field] > a[t.sort.field] ? 1 : e[t.sort.field] < a[t.sort.field] ? -1 : 0 : e[t.sort.field] < a[t.sort.field] ? 1 : e[t.sort.field] > a[t.sort.field] ? -1 : 0
                    }), "string" === e.type(t.query.generalSearch) && (a.jsonData = e.grep(a.jsonData, function (a) {
                        for (var n in a) if (a.hasOwnProperty(n) && "string" === e.type(a[n]) && a[n].toLowerCase().indexOf(t.query.generalSearch) > -1) return !0;
                        return !1
                    }), delete t.query.generalSearch), "object" === e.type(t.query) && (e.each(t.query, function (e, a) {
                        "" === a && delete t.query[e]
                    }), a.jsonData = n.filterArray(a.jsonData, t.query), a.jsonData = a.jsonData.filter(function () {
                        return !0
                    })), a.jsonData
                }, filterArray: function (t, a, n) {
                    if ("undefined" === e.type(n) && (n = "AND"), "object" !== e.type(a)) return t;
                    if (n = n.toUpperCase(), -1 === e.inArray(n, ["AND", "OR", "NOT"])) return [];
                    var o = Object.keys(a).length, i = [];
                    return e.each(t, function (t, l) {
                        var r = l, s = 0;
                        e.each(a, function (e, t) {
                            r.hasOwnProperty(e) && t == r[e] && s++
                        }), ("AND" == n && s == o || "OR" == n && s > 0 || "NOT" == n && 0 == s) && (i[t] = l)
                    }), t = i
                }, resetScroll: function () {
                    void 0 === t.detail && 1 === n.getDepth() && (e(a.table).find(".m-datatable__row").css("left", 0), e(a.table).find(".m-datatable__lock").css("top", 0), e(a.tableBody).scrollTop(0))
                }, getColumnByField: function (a) {
                    var n;
                    return e.each(t.columns, function (e, t) {
                        if (a === t.field) return n = t, !1
                    }), n
                }, getDefaultSortColumn: function () {
                    var a = {sort: "", field: ""};
                    return e.each(t.columns, function (t, n) {
                        if (void 0 !== n.sortable && -1 !== e.inArray(n.sortable, ["asc", "desc"])) return a = {
                            sort: n.sortable,
                            field: n.field
                        }, !1
                    }), a
                }, getHiddenDimensions: function (t, a) {
                    var n = {position: "absolute", visibility: "hidden", display: "block"},
                        o = {width: 0, height: 0, innerWidth: 0, innerHeight: 0, outerWidth: 0, outerHeight: 0},
                        i = e(t).parents().addBack().not(":visible");
                    a = "boolean" == typeof a && a;
                    var l = [];
                    return i.each(function () {
                        var e = {};
                        for (var t in n) e[t] = this.style[t], this.style[t] = n[t];
                        l.push(e)
                    }), o.width = e(t).width(), o.outerWidth = e(t).outerWidth(a), o.innerWidth = e(t).innerWidth(), o.height = e(t).height(), o.innerHeight = e(t).innerHeight(), o.outerHeight = e(t).outerHeight(a), i.each(function (e) {
                        var t = l[e];
                        for (var a in n) this.style[a] = t[a]
                    }), o
                }, getObject: function (e, t) {
                    return e.split(".").reduce(function (e, t) {
                        return null !== e && void 0 !== e[t] ? e[t] : null
                    }, t)
                }, extendObj: function (e, t, a) {
                    function n(e) {
                        var t = o[i++];
                        void 0 !== e[t] && null !== e[t] ? "object" != typeof e[t] && "function" != typeof e[t] && (e[t] = {}) : e[t] = {}, i === o.length ? e[t] = a : n(e[t])
                    }

                    var o = t.split("."), i = 0;
                    return n(e), e
                }
            };
            this.API = {row: null, record: null, column: null, value: null, params: null};
            var o = {
                redraw: function () {
                    return n.adjustCellsWidth.call(), n.adjustCellsHeight.call(), n.adjustLockContainer.call(), n.initHeight.call(), a
                }, load: function () {
                    return o.reload(), a
                }, reload: function () {
                    return !1 === t.data.serverFiltering && n.updateRawData(), n.dataRender(), e(a).trigger("m-datatable--on-reloaded"), a
                }, getRecord: function (t) {
                    return void 0 === a.tableBody && (a.tableBody = e(a.table).children("tbody")), e(a.tableBody).find(".m-datatable__cell:first-child").each(function (o, i) {
                        if (t == e(i).text()) {
                            a.API.row = e(i).closest(".m-datatable__row");
                            var l = a.API.row.index() + 1;
                            return a.API.record = a.API.value = n.getOneRow(a.tableBody, l), a
                        }
                    }), a
                }, getColumn: function (t) {
                    return a.API.column = a.API.value = e(a.API.record).find('[data-field="' + t + '"]'), a
                }, destroy: function () {
                    return e(a).trigger("m-datatable--on-destroy"), e(a).replaceWith(e(a.old).addClass("m-datatable--destroyed").show()), a
                }, sort: function (t, n) {
                    return void 0 === n && (n = "asc"), e(a.tableHead).find('.m-datatable__cell[data-field="' + t + '"]').trigger("click"), a
                }, getValue: function () {
                    return e(a.API.value).text()
                }, setActive: function (t) {
                    "string" === e.type(t) && (t = e(a.tableBody).find('.m-checkbox--single > [type="checkbox"][value="' + t + '"]')), e(t).prop("checked", !0);
                    var n = e(t).closest(".m-datatable__row").addClass("m-datatable__row--active"),
                        o = e(n).index() + 1;
                    e(n).closest(".m-datatable__lock").parent().find(".m-datatable__row:nth-child(" + o + ")").addClass("m-datatable__row--active");
                    var i = [];
                    e(n).each(function (t, a) {
                        var n = e(a).find('.m-checkbox--single:not(.m-checkbox--all) > [type="checkbox"]').val();
                        void 0 !== n && i.push(n)
                    }), e(a).trigger("m-datatable--on-check", [i])
                }, setInactive: function (t) {
                    "string" === e.type(t) && (t = e(a.tableBody).find('.m-checkbox--single > [type="checkbox"][value="' + t + '"]')), e(t).prop("checked", !1);
                    var n = e(t).closest(".m-datatable__row").removeClass("m-datatable__row--active"),
                        o = e(n).index() + 1;
                    e(n).closest(".m-datatable__lock").parent().find(".m-datatable__row:nth-child(" + o + ")").removeClass("m-datatable__row--active");
                    var i = [];
                    e(n).each(function (t, a) {
                        var n = e(a).find('.m-checkbox--single:not(.m-checkbox--all) > [type="checkbox"]').val();
                        void 0 !== n && i.push(n)
                    }), e(a).trigger("m-datatable--on-uncheck", [i])
                }, setActiveAll: function (t) {
                    t ? o.setActive(e(a.table).find(".m-datatable__cell")) : o.setInactive(e(a.table).find(".m-datatable__cell")), e(a.table).find('.m-checkbox [type="checkbox"]').prop("checked", t || !1)
                }, setSelectedRecords: function () {
                    return a.API.record = e(a.tableBody).find(".m-datatable__row--active"), a
                }, getSelectedRecords: function () {
                    return a.API.record
                }, getOption: function (e) {
                    return n.getObject(e, t)
                }, setOption: function (e, a) {
                    t = n.extendObj(t, e, a)
                }, search: function (a, i) {
                    void 0 !== i && (i = e.makeArray(i));
                    var l = function () {
                        var e = 0;
                        return function (t, a) {
                            clearTimeout(e), e = setTimeout(t, a)
                        }
                    }(), r = o.getDataSourceParam("query");
                    void 0 === i && (r.generalSearch = a), "object" == typeof i && (e.each(i, function (e, t) {
                        r[t] = a
                    }), e.each(r, function (e, t) {
                        "" === t && delete r[e]
                    })), o.setDataSourceParam("query", r), l(function () {
                        !1 === t.data.serverFiltering && n.updateRawData(), n.dataRender()
                    }, 300)
                }, setDataSourceParam: function (t, i) {
                    var l = n.getDefaultSortColumn();
                    a.API.params = e.extend({}, {
                        pagination: {page: 1, perpage: o.getOption("data.pageSize")},
                        sort: {sort: l.sort, field: l.field},
                        query: {}
                    }, a.API.params, n.stateGet(n.stateId)), a.API.params = n.extendObj(a.API.params, t, i), n.stateKeep(n.stateId, a.API.params)
                }, getDataSourceParam: function (t) {
                    var i = n.getDefaultSortColumn();
                    return a.API.params = e.extend({}, {
                        pagination: {page: 1, perpage: o.getOption("data.pageSize")},
                        sort: {sort: i.sort, field: i.field},
                        query: {}
                    }, a.API.params, n.stateGet(n.stateId)), "string" == typeof t ? n.getObject(t, a.API.params) : a.API.params
                }, getDataSourceQuery: function () {
                    return o.getDataSourceParam("query")
                }, setDataSourceQuery: function (e) {
                    o.setDataSourceParam("query", e)
                }, getCurrentPage: function () {
                    return e(a.table).siblings(".m-datatable__pager").last().find(".m-datatable__pager-nav").find(".m-datatable__pager-link.m-datatable__pager-link--active").data("page") || 1
                }, getPageSize: function () {
                    return e(a.table).siblings(".m-datatable__pager").last().find(".m-datatable__pager-size").val() || 10
                }
            };
            return e.each(o, function (e, t) {
                a[e] = t
            }), "string" == typeof t ? o[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? e.error("Method " + t + " does not exist") : (a.textAlign = {
                left: "m-datatable__cell--left",
                center: "m-datatable__cell--center",
                right: "m-datatable__cell--right"
            }, a.jsonData = null, t = e.extend(!0, {}, e.fn.mDatatable.defaults, t), e(a).data("options", t), e(a).trigger("m-datatable--on-init", t), n.init.apply(this, arguments)), a
        }
    }, e.fn.mDatatable.defaults = {
        data: {
            type: null,
            source: {read: {url: "", params: {}}},
            pageSize: 10,
            saveState: {cookie: !0, webstorage: !0},
            serverPaging: !1,
            serverFiltering: !1,
            serverSorting: !1
        },
        layout: {
            theme: "default",
            class: "m-datatable--brand",
            scroll: !1,
            height: null,
            footer: !1,
            header: !0,
            smoothScroll: {scrollbarShown: !0},
            spinner: {overlayColor: "#000000", opacity: 0, type: "loader", state: "brand", message: !0},
            icons: {
                sort: {asc: "la la-arrow-up", desc: "la la-arrow-down"},
                pagination: {
                    next: "la la-angle-right",
                    prev: "la la-angle-left",
                    first: "la la-angle-double-left",
                    last: "la la-angle-double-right",
                    more: "la la-ellipsis-h"
                },
                rowDetail: {expand: "fa fa-caret-down", collapse: "fa fa-caret-right"}
            }
        },
        sortable: !1,
        resizable: !1,
        filterable: !1,
        pagination: !0,
        columns: [],
        toolbar: {
            layout: ["pagination", "info"],
            placement: ["bottom"],
            items: {
                pagination: {
                    type: "default",
                    pages: {
                        desktop: {layout: "default", pagesNumber: 6},
                        tablet: {layout: "default", pagesNumber: 3},
                        mobile: {layout: "compact"}
                    },
                    navigation: {prev: !0, next: !0, first: !0, last: !0},
                    pageSizeSelect: [10, 20, 30, 50, 100]
                }, info: !0
            }
        },
        translate: {
            records: {processing: "Please wait...", noRecords: "No records found"},
            toolbar: {
                pagination: {
                    items: {
                        default: {
                            first: "First",
                            prev: "Previous",
                            next: "Next",
                            last: "Last",
                            more: "More pages",
                            input: "Page number",
                            select: "Select page size"
                        }, info: "Displaying {{start}} - {{end}} of {{total}} records"
                    }
                }
            }
        }
    }
}(jQuery), function (e) {
    e.fn.mDropdown = function (t) {
        var a = {}, n = e(this), o = {
            run: function (e) {
                return n.data("dropdown") ? a = n.data("dropdown") : (o.init(e), o.build(), o.setup(), n.data("dropdown", a)), a
            }, init: function (t) {
                a.events = [], a.eventOne = !1, a.close = n.find(".m-dropdown__close"), a.toggle = n.find(".m-dropdown__toggle"), a.arrow = n.find(".m-dropdown__arrow"), a.wrapper = n.find(".m-dropdown__wrapper"), a.scrollable = n.find(".m-dropdown__scrollable"), a.defaultDropPos = n.hasClass("m-dropdown--up") ? "up" : "down", a.currentDropPos = a.defaultDropPos, a.options = e.extend(!0, {}, e.fn.mDropdown.defaults, t), !0 === n.data("drop-auto") ? a.options.dropAuto = !0 : !1 === n.data("drop-auto") && (a.options.dropAuto = !1), a.scrollable.length > 0 && (a.scrollable.data("min-height") && (a.options.minHeight = a.scrollable.data("min-height")), a.scrollable.data("max-height") && (a.options.maxHeight = a.scrollable.data("max-height")))
            }, build: function () {
                mUtil.isMobileDevice() ? "hover" == n.data("dropdown-toggle") || "click" == n.data("dropdown-toggle") ? a.options.toggle = "click" : (a.options.toggle = "click", a.toggle.click(o.toggle)) : "hover" == n.data("dropdown-toggle") ? (a.options.toggle = "hover", n.mouseleave(o.hide)) : "click" == n.data("dropdown-toggle") ? a.options.toggle = "click" : "hover" == a.options.toggle ? (n.mouseenter(o.show), n.mouseleave(o.hide)) : a.toggle.click(o.toggle), a.close.length && a.close.on("click", o.hide), o.disableClose()
            }, setup: function () {
                a.options.placement && n.addClass("m-dropdown--" + a.options.placement), a.options.align && n.addClass("m-dropdown--align-" + a.options.align), a.options.width && a.wrapper.css("width", a.options.width), n.data("dropdown-persistent") && (a.options.persistent = !0), a.options.minHeight && a.scrollable.css("min-height", a.options.minHeight), a.options.maxHeight && (a.scrollable.css("max-height", a.options.maxHeight), a.scrollable.css("overflow-y", "auto"), mUtil.isDesktopDevice() && mApp.initScroller(a.scrollable, {})), o.setZindex()
            }, sync: function () {
                e(n).data("dropdown", a)
            }, disableClose: function () {
                n.on("click", ".m-dropdown--disable-close, .mCSB_1_scrollbar", function (e) {
                    e.preventDefault(), e.stopPropagation()
                })
            }, toggle: function () {
                return a.open ? o.hide() : o.show()
            }, setContent: function (e) {
                return n.find(".m-dropdown__content").html(e), a
            }, show: function () {
                if ("hover" == a.options.toggle && n.data("hover")) return o.clearHovered(), a;
                if (a.open) return a;
                if (a.arrow.length > 0 && o.adjustArrowPos(), o.eventTrigger("beforeShow"), o.hideOpened(), n.addClass("m-dropdown--open"), mUtil.isMobileDevice() && a.options.mobileOverlay) {
                    var t = a.wrapper.css("zIndex") - 1, i = e('<div class="m-dropdown__dropoff"></div>');
                    i.css("zIndex", t), i.data("dropdown", n), n.data("dropoff", i), n.after(i), i.click(function (t) {
                        o.hide(), e(this).remove(), t.preventDefault()
                    })
                }
                return n.focus(), n.attr("aria-expanded", "true"), a.open = !0, o.handleDropPosition(), o.eventTrigger("afterShow"), a
            }, clearHovered: function () {
                n.removeData("hover");
                var e = n.data("timeout");
                n.removeData("timeout"), clearTimeout(e)
            }, hideHovered: function (e) {
                if (e) {
                    if (!1 === o.eventTrigger("beforeHide")) return;
                    o.clearHovered(), n.removeClass("m-dropdown--open"), a.open = !1, o.eventTrigger("afterHide")
                } else {
                    if (!1 === o.eventTrigger("beforeHide")) return;
                    var t = setTimeout(function () {
                        n.data("hover") && (o.clearHovered(), n.removeClass("m-dropdown--open"), a.open = !1, o.eventTrigger("afterHide"))
                    }, a.options.hoverTimeout);
                    n.data("hover", !0), n.data("timeout", t)
                }
            }, hideClicked: function () {
                !1 !== o.eventTrigger("beforeHide") && (n.removeClass("m-dropdown--open"), n.data("dropoff") && n.data("dropoff").remove(), a.open = !1, o.eventTrigger("afterHide"))
            }, hide: function (e) {
                return !1 === a.open ? a : ("hover" == a.options.toggle ? o.hideHovered(e) : o.hideClicked(), "down" == a.defaultDropPos && "up" == a.currentDropPos && (n.removeClass("m-dropdown--up"), a.arrow.prependTo(a.wrapper), a.currentDropPos = "down"), a)
            }, hideOpened: function () {
                e(".m-dropdown.m-dropdown--open").each(function () {
                    e(this).mDropdown().hide(!0)
                })
            }, adjustArrowPos: function () {
                var e = n.outerWidth(), t = a.arrow.hasClass("m-dropdown__arrow--right") ? "right" : "left", o = 0;
                a.arrow.length > 0 && (mUtil.isInResponsiveRange("mobile") && n.hasClass("m-dropdown--mobile-full-width") ? (o = n.offset().left + e / 2 - Math.abs(a.arrow.width() / 2) - parseInt(a.wrapper.css("left")), a.arrow.css("right", "auto"), a.arrow.css("left", o), a.arrow.css("margin-left", "auto"), a.arrow.css("margin-right", "auto")) : a.arrow.hasClass("m-dropdown__arrow--adjust") && (o = e / 2 - Math.abs(a.arrow.width() / 2), n.hasClass("m-dropdown--align-push") && (o += 20), "right" == t ? (a.arrow.css("left", "auto"), a.arrow.css("right", o)) : (a.arrow.css("right", "auto"), a.arrow.css("left", o))))
            }, handleDropPosition: function () {
            }, setZindex: function () {
                var e = a.wrapper.css("z-index");
                mUtil.getHighestZindex(n) > e && a.wrapper.css("z-index", zindex)
            }, isPersistent: function () {
                return a.options.persistent
            }, isShown: function () {
                return a.open
            }, isInVerticalViewport: function () {
                var t = a.wrapper, n = t.offset(), o = t.outerHeight(), i = (t.width(), t.find("[data-scrollable]"));
                return i.length && (i.data("max-height") ? o += parseInt(i.data("max-height")) : i.data("height") && (o += parseInt(i.data("height")))), n.top + o < e(window).scrollTop() + e(window).height()
            }, eventTrigger: function (e) {
                for (i = 0; i < a.events.length; i++) {
                    var t = a.events[i];
                    if (t.name == e) {
                        if (1 != t.one) return t.handler.call(this, a);
                        if (0 == t.fired) return a.events[i].fired = !0, t.handler.call(this, a)
                    }
                }
            }, addEvent: function (e, t, n) {
                return a.events.push({name: e, handler: t, one: n, fired: !1}), o.sync(), a
            }
        };
        return o.run.apply(this, [t]), a.show = function () {
            return o.show()
        }, a.hide = function () {
            return o.hide()
        }, a.toggle = function () {
            return o.toggle()
        }, a.isPersistent = function () {
            return o.isPersistent()
        }, a.isShown = function () {
            return o.isShown()
        }, a.fixDropPosition = function () {
            return o.handleDropPosition()
        }, a.setContent = function (e) {
            return o.setContent(e)
        }, a.on = function (e, t) {
            return o.addEvent(e, t)
        }, a.one = function (e, t) {
            return o.addEvent(e, t, !0)
        }, a
    }, e.fn.mDropdown.defaults = {
        toggle: "click",
        hoverTimeout: 300,
        skin: "default",
        height: "auto",
        dropAuto: !0,
        maxHeight: !1,
        minHeight: !1,
        persistent: !1,
        mobileOverlay: !0
    }, mUtil.isMobileDevice() ? e(document).on("click", '[data-dropdown-toggle="click"] .m-dropdown__toggle, [data-dropdown-toggle="hover"] .m-dropdown__toggle', function (t) {
        t.preventDefault(), e(this).parent(".m-dropdown").mDropdown().toggle()
    }) : (e(document).on("click", '[data-dropdown-toggle="click"] .m-dropdown__toggle', function (t) {
        t.preventDefault(), e(this).parent(".m-dropdown").mDropdown().toggle()
    }), e(document).on("mouseenter", '[data-dropdown-toggle="hover"]', function (t) {
        e(this).mDropdown().toggle()
    })), e(document).on("click", function (t) {
        e(".m-dropdown.m-dropdown--open").each(function () {
            if (e(this).data("dropdown")) {
                var a = e(t.target), n = e(this).mDropdown(), o = e(this).find(".m-dropdown__toggle");
                o.length > 0 && !0 !== a.is(o) && 0 === o.find(a).length && 0 === a.find(o).length && 0 == n.isPersistent() ? n.hide() : 0 === e(this).find(a).length && n.hide()
            }
        })
    })
}(jQuery), function (e) {
    e.fn.mExample = function (t) {
        var a = {}, n = e(this), o = {
            run: function (e) {
                return n.data("example") ? a = n.data("example") : (o.init(e), o.build(), o.setup(), n.data("example", a)), a
            }, init: function (t) {
                a.events = [], a.scrollable = n.find(".m-example__scrollable"), a.options = e.extend(!0, {}, e.fn.mExample.defaults, t), a.scrollable.length > 0 && (a.scrollable.data("data-min-height") && (a.options.minHeight = a.scrollable.data("data-min-height")), a.scrollable.data("data-max-height") && (a.options.maxHeight = a.scrollable.data("data-max-height")))
            }, build: function () {
                mUtil.isMobileDevice()
            }, setup: function () {
            }, eventTrigger: function (e) {
                for (i = 0; i < a.events.length; i++) {
                    var t = a.events[i];
                    if (t.name == e) {
                        if (1 != t.one) return t.handler.call(this, a);
                        if (0 == t.fired) return a.events[i].fired = !0, t.handler.call(this, a)
                    }
                }
            }, addEvent: function (e, t, n) {
                a.events.push({name: e, handler: t, one: n, fired: !1}), o.sync()
            }
        };
        return o.run.apply(this, [t]), a.on = function (e, t) {
            return o.addEvent(e, t)
        }, a.one = function (e, t) {
            return o.addEvent(e, t, !0)
        }, a
    }, e.fn.mExample.defaults = {}
}(jQuery), function (e) {
    e.fn.mHeader = function (t) {
        var a = this, n = e(this), o = {
            run: function (e) {
                return n.data("header") ? a = n.data("header") : (o.init(e), o.reset(), o.build(), n.data("header", a)), a
            }, init: function (t) {
                a.options = e.extend(!0, {}, e.fn.mHeader.defaults, t)
            }, build: function () {
                o.toggle()
            }, toggle: function () {
                var t = 0;
                !1 === a.options.minimize.mobile && !1 === a.options.minimize.desktop || e(window).scroll(function () {
                    var n = 0;
                    mUtil.isInResponsiveRange("desktop") ? (n = a.options.offset.desktop, on = a.options.minimize.desktop.on, off = a.options.minimize.desktop.off) : mUtil.isInResponsiveRange("tablet-and-mobile") && (n = a.options.offset.mobile, on = a.options.minimize.mobile.on, off = a.options.minimize.mobile.off);
                    var o = e(this).scrollTop();
                    a.options.classic ? o > n ? (e("body").addClass(on), e("body").removeClass(off)) : (e("body").addClass(off), e("body").removeClass(on)) : (o > n && t < o ? (e("body").addClass(on), e("body").removeClass(off)) : (e("body").addClass(off), e("body").removeClass(on)), t = o)
                })
            }, reset: function () {
            }
        };
        return o.run.apply(a, [t]), a.publicMethod = function () {
        }, a
    }, e.fn.mHeader.defaults = {classic: !1, offset: {mobile: 150, desktop: 200}, minimize: {mobile: !1, desktop: !1}}
}(jQuery), function (e) {
    e.fn.mMenu = function (t) {
        var a = this, n = e(this), o = {
            run: function (e, t) {
                return n.data("menu") && !0 !== t ? a = n.data("menu") : (o.init(e), o.reset(), o.build(), n.data("menu", a)), a
            }, init: function (t) {
                a.options = e.extend(!0, {}, e.fn.mMenu.defaults, t), a.pauseDropdownHoverTime = 0
            }, build: function () {
                "accordion" === o.getSubmenuMode() && n.on("click", ".m-menu__toggle", o.handleSubmenuAccordion), ("dropdown" === o.getSubmenuMode() || o.isConditionalSubmenuDropdown()) && (n.on({
                    mouseenter: o.handleSubmenuDrodownHoverEnter,
                    mouseleave: o.handleSubmenuDrodownHoverExit
                }, '[data-menu-submenu-toggle="hover"]'), n.on("click", '[data-menu-submenu-toggle="click"] .m-menu__toggle', o.handleSubmenuDropdownClick), n.on("click", ".m-menu__link", o.handleSubmenuDropdownClose))
            }, reset: function () {
                n.off("click", ".m-menu__toggle", o.handleSubmenuAccordion), n.off({
                    mouseenter: o.handleSubmenuDrodownHoverEnter,
                    mouseleave: o.handleSubmenuDrodownHoverExit
                }, '[data-menu-submenu-toggle="hover"]'), n.off("click", '[data-menu-submenu-toggle="click"] .m-menu__toggle', o.handleSubmenuDropdownClick), a.find(".m-menu__submenu, .m-menu__inner").css("display", ""), a.find(".m-menu__item--hover").removeClass("m-menu__item--hover"), a.find(".m-menu__item--open:not(.m-menu__item--expanded)").removeClass("m-menu__item--open")
            }, getSubmenuMode: function () {
                return mUtil.isInResponsiveRange("desktop") ? mUtil.isset(a.options.submenu, "desktop.state.body") ? e("body").hasClass(a.options.submenu.desktop.state.body) ? a.options.submenu.desktop.state.mode : a.options.submenu.desktop.default : mUtil.isset(a.options.submenu, "desktop") ? a.options.submenu.desktop : void 0 : mUtil.isInResponsiveRange("tablet") && mUtil.isset(a.options.submenu, "tablet") ? a.options.submenu.tablet : !(!mUtil.isInResponsiveRange("mobile") || !mUtil.isset(a.options.submenu, "mobile")) && a.options.submenu.mobile
            }, isConditionalSubmenuDropdown: function () {
                return !(!mUtil.isInResponsiveRange("desktop") || !mUtil.isset(a.options.submenu, "desktop.state.body"))
            }, handleSubmenuDrodownHoverEnter: function (t) {
                if ("accordion" !== o.getSubmenuMode() && !1 !== a.resumeDropdownHover()) {
                    var n = e(this);
                    o.showSubmenuDropdown(n), 1 == n.data("hover") && o.hideSubmenuDropdown(n, !1)
                }
            }, handleSubmenuDrodownHoverExit: function (t) {
                if (!1 !== a.resumeDropdownHover() && "accordion" !== o.getSubmenuMode()) {
                    var n = e(this), i = a.options.dropdown.timeout, l = setTimeout(function () {
                        1 == n.data("hover") && o.hideSubmenuDropdown(n, !0)
                    }, i);
                    n.data("hover", !0), n.data("timeout", l)
                }
            }, handleSubmenuDropdownClick: function (t) {
                if ("accordion" !== o.getSubmenuMode()) {
                    var a = e(this).closest(".m-menu__item");
                    0 == a.hasClass("m-menu__item--hover") ? (a.addClass("m-menu__item--open-dropdown"), o.showSubmenuDropdown(a)) : (a.removeClass("m-menu__item--open-dropdown"), o.hideSubmenuDropdown(a, !0)), t.preventDefault()
                }
            }, handleSubmenuDropdownClose: function (t) {
                if ("accordion" !== o.getSubmenuMode()) {
                    var a = e(this).parents(".m-menu__item.m-menu__item--submenu");
                    a.length > 0 && !1 === e(this).hasClass("m-menu__toggle") && 0 === e(this).find(".m-menu__toggle").length && a.each(function () {
                        o.hideSubmenuDropdown(e(this), !0)
                    })
                }
            }, handleSubmenuAccordion: function (t) {
                if ("dropdown" !== o.getSubmenuMode()) {
                    var n = e(this), i = n.closest("li"), l = i.children(".m-menu__submenu, .m-menu__inner");
                    if (l.parent(".m-menu__item--expanded").length, l.length > 0) {
                        t.preventDefault();
                        var r = a.options.accordion.slideSpeed;
                        if (!1 === i.hasClass("m-menu__item--open")) {
                            if (!1 === a.options.accordion.expandAll) {
                                var s = n.closest(".m-menu__nav, .m-menu__subnav").find("> .m-menu__item.m-menu__item--open.m-menu__item--submenu:not(.m-menu__item--expanded)");
                                s.each(function () {
                                    e(this).children(".m-menu__submenu").slideUp(r, function () {
                                        o.scrollToItem(n)
                                    }), e(this).removeClass("m-menu__item--open")
                                }), s.length > 0 && !0
                            }
                            l.slideDown(r, function () {
                                o.scrollToItem(n)
                            }), i.addClass("m-menu__item--open")
                        } else l.slideUp(r, function () {
                            o.scrollToItem(n)
                        }), i.removeClass("m-menu__item--open")
                    }
                }
            }, scrollToItem: function (e) {
                mUtil.isInResponsiveRange("desktop") && a.options.accordion.autoScroll && !n.data("menu-scrollable") && mApp.scrollToViewport(e)
            }, hideSubmenuDropdown: function (e, t) {
                t && e.removeClass("m-menu__item--hover"), e.removeData("hover");
                var a = e.data("timeout");
                e.removeData("timeout"), clearTimeout(a)
            }, showSubmenuDropdown: function (t) {
                n.find(".m-menu__item--submenu.m-menu__item--hover").each(function () {
                    var a = e(this);
                    t.is(a) || a.find(t).length > 0 || t.find(a).length > 0 || o.hideSubmenuDropdown(a, !0)
                }), o.adjustSubmenuDropdownArrowPos(t), t.addClass("m-menu__item--hover"), "accordion" === o.getSubmenuMode() && a.options.accordion.autoScroll && mApp.scrollTo(t.children(".m-menu__item--submenu"))
            }, resize: function (t) {
                if ("dropdown" === o.getSubmenuMode()) {
                    var i, l = n.find("> .m-menu__nav > .m-menu__item--resize"), r = l.find("> .m-menu__submenu"),
                        s = mUtil.getViewPort().width;
                    n.find("> .m-menu__nav > .m-menu__item").length;
                    if ("dropdown" == o.getSubmenuMode() && (mUtil.isInResponsiveRange("desktop") && mUtil.isset(a.options, "resize.desktop") && (i = a.options.resize.desktop) && s <= l.data("menu-resize-desktop-breakpoint") || mUtil.isInResponsiveRange("tablet") && mUtil.isset(a.options, "resize.tablet") && (i = a.options.resize.tablet) && s <= l.data("menu-resize-tablet-breakpoint") || mUtil.isInResponsiveRange("mobile") && mUtil.isset(a.options, "resize.mobile") && (i = a.options.resize.mobile) && s <= l.data("menu-resize-mobile-breakpoint"))) {
                        var d = r.find("> .m-menu__subnav > .m-menu__item").length,
                            c = n.find("> .m-menu__nav > .m-menu__item:not(.m-menu__item--resize)").length;
                        if (!0 === i.apply()) d > 0 && r.find("> .m-menu__subnav > .m-menu__item").each(function () {
                            var t = e(this),
                                a = r.find("> .m-menu__nav > .m-menu__item:not(.m-menu__item--resize)").length;
                            if (n.find("> .m-menu__nav > .m-menu__item:not(.m-menu__item--resize)").eq(a - 1).after(t), !1 === i.apply()) return t.appendTo(r.find("> .m-menu__subnav")), !1;
                            d--, c++
                        }); else if (c > 0) for (var u = n.find("> .m-menu__nav > .m-menu__item:not(.m-menu__item--resize)"), m = u.length - 1, p = 0; p < u.length; p++) {
                            var f = e(u.get(m));
                            if (m--, !0 === i.apply()) break;
                            f.appendTo(r.find("> .m-menu__subnav")), d++, c--
                        }
                        d > 0 ? l.show() : l.hide()
                    } else r.find("> .m-menu__subnav > .m-menu__item").each(function () {
                        var t = r.find("> .m-menu__subnav > .m-menu__item").length;
                        n.find("> .m-menu__nav > .m-menu__item").get(t).after(e(this))
                    }), l.hide()
                }
            }, createSubmenuDropdownClickDropoff: function (t) {
                var a = t.find("> .m-menu__submenu").css("zIndex") - 1,
                    n = e('<div class="m-menu__dropoff" style="background: transparent; position: fixed; top: 0; bottom: 0; left: 0; right: 0; z-index: ' + a + '"></div>');
                e("body").after(n), n.on("click", function (a) {
                    a.stopPropagation(), a.preventDefault(), t.removeClass("m-menu__item--hover"), e(this).remove()
                })
            }, adjustSubmenuDropdownArrowPos: function (e) {
                var t = e.find("> .m-menu__submenu > .m-menu__arrow.m-menu__arrow--adjust"),
                    a = e.find("> .m-menu__submenu");
                e.find("> .m-menu__submenu > .m-menu__subnav");
                if (t.length > 0) {
                    var n;
                    e.children(".m-menu__link");
                    a.hasClass("m-menu__submenu--classic") || a.hasClass("m-menu__submenu--fixed") ? a.hasClass("m-menu__submenu--right") ? (n = e.outerWidth() / 2, a.hasClass("m-menu__submenu--pull") && (n += Math.abs(parseInt(a.css("margin-right")))), n = a.width() - n) : a.hasClass("m-menu__submenu--left") && (n = e.outerWidth() / 2, a.hasClass("m-menu__submenu--pull") && (n += Math.abs(parseInt(a.css("margin-left"))))) : a.hasClass("m-menu__submenu--center") || a.hasClass("m-menu__submenu--full") ? (n = e.offset().left - (mUtil.getViewPort().width - a.outerWidth()) / 2, n += e.outerWidth() / 2) : a.hasClass("m-menu__submenu--left") || a.hasClass("m-menu__submenu--right"), t.css("left", n)
                }
            }, pauseDropdownHover: function (e) {
                var t = new Date;
                a.pauseDropdownHoverTime = t.getTime() + e
            }, resumeDropdownHover: function () {
                return (new Date).getTime() > a.pauseDropdownHoverTime
            }, resetActiveItem: function (t) {
                n.find(".m-menu__item--active").each(function () {
                    e(this).removeClass("m-menu__item--active"), e(this).children(".m-menu__submenu").css("display", ""), e(this).parents(".m-menu__item--submenu").each(function () {
                        e(this).removeClass("m-menu__item--open"), e(this).children(".m-menu__submenu").css("display", "")
                    })
                }), !1 === a.options.accordion.expandAll && n.find(".m-menu__item--open").each(function () {
                    e(this).removeClass("m-menu__item--open")
                })
            }, setActiveItem: function (t) {
                o.resetActiveItem(), (t = e(t)).addClass("m-menu__item--active"), t.parents(".m-menu__item--submenu").each(function () {
                    e(this).addClass("m-menu__item--open")
                })
            }, getBreadcrumbs: function (t) {
                var a = [], n = (t = e(t)).children(".m-menu__link");
                return a.push({
                    text: n.find(".m-menu__link-text").html(),
                    title: n.attr("title"),
                    href: n.attr("href")
                }), t.parents(".m-menu__item--submenu").each(function () {
                    var t = e(this).children(".m-menu__link");
                    a.push({text: t.find(".m-menu__link-text").html(), title: t.attr("title"), href: t.attr("href")})
                }), a.reverse(), a
            }, getPageTitle: function (t) {
                return (t = e(t)).children(".m-menu__link").find(".m-menu__link-text").html()
            }
        };
        return o.run.apply(a, [t]), void 0 !== t && e(window).resize(function () {
            o.run.apply(a, [t, !0])
        }), a.setActiveItem = function (e) {
            return o.setActiveItem(e)
        }, a.getBreadcrumbs = function (e) {
            return o.getBreadcrumbs(e)
        }, a.getPageTitle = function (e) {
            return o.getPageTitle(e)
        }, a.getSubmenuMode = function () {
            return o.getSubmenuMode()
        }, a.pauseDropdownHover = function (e) {
            o.pauseDropdownHover(e)
        }, a.resumeDropdownHover = function () {
            return o.resumeDropdownHover()
        }, a
    }, e.fn.mMenu.defaults = {
        accordion: {slideSpeed: 300, autoScroll: !0, expandAll: !0},
        dropdown: {timeout: 500}
    }, e(document).on("click", function (t) {
        e('.m-menu__nav .m-menu__item.m-menu__item--submenu.m-menu__item--hover[data-menu-submenu-toggle="click"]').each(function () {
            var a = e(this).parent(".m-menu__nav").parent();
            menu = a.mMenu(), "dropdown" === menu.getSubmenuMode() && 0 == e(t.target).is(a) && 0 == a.find(e(t.target)).length && a.find('.m-menu__item--submenu.m-menu__item--hover[data-menu-submenu-toggle="click"]').removeClass("m-menu__item--hover")
        })
    })
}(jQuery), function (e) {
    e.fn.mMessenger = function (t) {
        var a = {}, n = e(this), o = {
            run: function (e) {
                return n.data("messenger") ? a = n.data("messenger") : (o.init(e), o.build(), o.setup(), n.data("messenger", a)), a
            }, init: function (t) {
                a.events = [], a.scrollable = n.find(".m-messenger__scrollable"), a.options = e.extend(!0, {}, e.fn.mMessenger.defaults, t), a.scrollable.length > 0 && (a.scrollable.data("data-min-height") && (a.options.minHeight = a.scrollable.data("data-min-height")), a.scrollable.data("data-max-height") && (a.options.maxHeight = a.scrollable.data("data-max-height")))
            }, build: function () {
                mUtil.isMobileDevice()
            }, setup: function () {
            }, eventTrigger: function (e) {
                for (i = 0; i < a.events.length; i++) {
                    var t = a.events[i];
                    if (t.name == e) {
                        if (1 != t.one) return t.handler.call(this, a);
                        if (0 == t.fired) return a.events[i].fired = !0, t.handler.call(this, a)
                    }
                }
            }, addEvent: function (e, t, n) {
                a.events.push({name: e, handler: t, one: n, fired: !1}), o.sync()
            }
        };
        return o.run.apply(this, [t]), a.on = function (e, t) {
            return o.addEvent(e, t)
        }, a.one = function (e, t) {
            return o.addEvent(e, t, !0)
        }, a
    }, e.fn.mMessenger.defaults = {}
}(jQuery), function (e) {
    e.fn.mOffcanvas = function (t) {
        var a = this, n = e(this), o = {
            run: function (e) {
                return n.data("offcanvas") ? a = n.data("offcanvas") : (o.init(e), o.build(), n.data("offcanvas", a)), a
            }, init: function (t) {
                a.events = [], a.options = e.extend(!0, {}, e.fn.mOffcanvas.defaults, t), a.overlay, a.classBase = a.options.class, a.classShown = a.classBase + "--on", a.classOverlay = a.classBase + "-overlay", a.state = n.hasClass(a.classShown) ? "shown" : "hidden", a.close = a.options.close, a.options.toggle && a.options.toggle.target ? (a.toggleTarget = a.options.toggle.target, a.toggleState = a.options.toggle.state) : (a.toggleTarget = a.options.toggle, a.toggleState = "")
            }, build: function () {
                e(a.toggleTarget).on("click", o.toggle), a.close && e(a.close).on("click", o.hide)
            }, sync: function () {
                e(n).data("offcanvas", a)
            }, toggle: function () {
                "shown" == a.state ? o.hide() : o.show()
            }, show: function () {
                if ("shown" != a.state) {
                    if (o.eventTrigger("beforeShow"), "" != a.toggleState && e(a.toggleTarget).addClass(a.toggleState), e("body").addClass(a.classShown), n.addClass(a.classShown), a.state = "shown", a.options.overlay) {
                        var t = e('<div class="' + a.classOverlay + '"></div>');
                        n.after(t), a.overlay = t, a.overlay.on("click", function (e) {
                            e.stopPropagation(), e.preventDefault(), o.hide()
                        })
                    }
                    return o.eventTrigger("afterShow"), a
                }
            }, hide: function () {
                if ("hidden" != a.state) return o.eventTrigger("beforeHide"), "" != a.toggleState && e(a.toggleTarget).removeClass(a.toggleState), e("body").removeClass(a.classShown), n.removeClass(a.classShown), a.state = "hidden", a.options.overlay && a.overlay.remove(), o.eventTrigger("afterHide"), a
            }, eventTrigger: function (e) {
                for (i = 0; i < a.events.length; i++) {
                    var t = a.events[i];
                    if (t.name == e) {
                        if (1 != t.one) return t.handler.call(this, a);
                        if (0 == t.fired) return a.events[i].fired = !0, t.handler.call(this, a)
                    }
                }
            }, addEvent: function (e, t, n) {
                a.events.push({name: e, handler: t, one: n, fired: !1}), o.sync()
            }
        };
        return o.run.apply(this, [t]), a.on = function (e, t) {
            return o.addEvent(e, t)
        }, a.one = function (e, t) {
            return o.addEvent(e, t, !0)
        }, a
    }, e.fn.mOffcanvas.defaults = {}
}(jQuery), function (e) {
    e.fn.mQuicksearch = function (t) {
        var a = this, n = e(this), o = {
            run: function (e) {
                return n.data("qs") ? a = n.data("qs") : (o.init(e), o.build(), n.data("qs", a)), a
            }, init: function (t) {
                a.options = e.extend(!0, {}, e.fn.mQuicksearch.defaults, t), a.form = n.find("form"), a.input = e(a.options.input), a.iconClose = e(a.options.iconClose), "default" == a.options.type && (a.iconSearch = e(a.options.iconSearch), a.iconCancel = e(a.options.iconCancel)), a.dropdown = n.mDropdown({mobileOverlay: !1}), a.cancelTimeout, a.processing = !1
            }, build: function () {
                a.input.keyup(o.handleSearch), "default" == a.options.type ? (a.input.focus(o.showDropdown), a.iconCancel.click(o.handleCancel), a.iconSearch.click(function () {
                    mUtil.isInResponsiveRange("tablet-and-mobile") && (e("body").addClass("m-header-search--mobile-expanded"), a.input.focus())
                }), a.iconClose.click(function () {
                    mUtil.isInResponsiveRange("tablet-and-mobile") && (e("body").removeClass("m-header-search--mobile-expanded"), o.closeDropdown())
                })) : "dropdown" == a.options.type && (a.dropdown.on("afterShow", function () {
                    a.input.focus()
                }), a.iconClose.click(o.closeDropdown))
            }, handleSearch: function (t) {
                var i = a.input.val();
                0 === i.length && (a.dropdown.hide(), o.handleCancelIconVisibility("on"), o.closeDropdown(), n.removeClass(a.options.hasResultClass)), i.length < a.options.minLength || 1 == a.processing || (a.processing = !0, a.form.addClass(a.options.spinner), o.handleCancelIconVisibility("off"), e.ajax({
                    url: a.options.source,
                    data: {query: i},
                    dataType: "html",
                    success: function (e) {
                        a.processing = !1, a.form.removeClass(a.options.spinner), o.handleCancelIconVisibility("on"), a.dropdown.setContent(e).show(), n.addClass(a.options.hasResultClass)
                    },
                    error: function (e) {
                        a.processing = !1, a.form.removeClass(a.options.spinner), o.handleCancelIconVisibility("on"), a.dropdown.setContent(a.options.templates.error.apply(a, e)).show(), n.addClass(a.options.hasResultClass)
                    }
                }))
            }, handleCancelIconVisibility: function (e) {
                "dropdown" != a.options.type && ("on" == e ? 0 === a.input.val().length ? (a.iconCancel.css("visibility", "hidden"), a.iconClose.css("visibility", "hidden")) : (clearTimeout(a.cancelTimeout), a.cancelTimeout = setTimeout(function () {
                    a.iconCancel.css("visibility", "visible"), a.iconClose.css("visibility", "visible")
                }, 500)) : (a.iconCancel.css("visibility", "hidden"), a.iconClose.css("visibility", "hidden")))
            }, handleCancel: function (e) {
                a.input.val(""), a.iconCancel.css("visibility", "hidden"), n.removeClass(a.options.hasResultClass), a.input.focus(), o.closeDropdown()
            }, closeDropdown: function () {
                a.dropdown.hide()
            }, showDropdown: function (e) {
                0 == a.dropdown.isShown() && a.input.val().length > a.options.minLength && 0 == a.processing && (a.dropdown.show(), e.preventDefault(), e.stopPropagation())
            }
        };
        return o.run.apply(a, [t]), a.test = function (e) {
        }, a
    }, e.fn.mQuicksearch.defaults = {minLength: 1, maxHeight: 300}
}(jQuery), function (e) {
    e.fn.mScrollTop = function (t) {
        var a = this, n = e(this), o = {
            run: function (e) {
                return n.data("scrollTop") ? a = n.data("scrollTop") : (o.init(e), o.build(), n.data("scrollTop", a)), a
            }, init: function (t) {
                a.element = n, a.events = [], a.options = e.extend(!0, {}, e.fn.mScrollTop.defaults, t)
            }, build: function () {
                navigator.userAgent.match(/iPhone|iPad|iPod/i) ? e(window).bind("touchend touchcancel touchleave", function () {
                    o.handle()
                }) : e(window).scroll(function () {
                    o.handle()
                }), n.on("click", o.scroll)
            }, sync: function () {
                e(n).data("scrollTop", a)
            }, handle: function () {
                e(window).scrollTop() > a.options.offset ? e("body").addClass("m-scroll-top--shown") : e("body").removeClass("m-scroll-top--shown")
            }, scroll: function (t) {
                t.preventDefault(), e("html, body").animate({scrollTop: 0}, a.options.speed)
            }, eventTrigger: function (e) {
                for (i = 0; i < a.events.length; i++) {
                    var t = a.events[i];
                    if (t.name == e) {
                        if (1 != t.one) return t.handler.call(this, a);
                        if (0 == t.fired) return a.events[i].fired = !0, t.handler.call(this, a)
                    }
                }
            }, addEvent: function (e, t, n) {
                a.events.push({name: e, handler: t, one: n, fired: !1}), o.sync()
            }
        };
        return o.run.apply(this, [t]), a.on = function (e, t) {
            return o.addEvent(e, t)
        }, a.one = function (e, t) {
            return o.addEvent(e, t, !0)
        }, a
    }, e.fn.mScrollTop.defaults = {offset: 300, speed: 600}
}(jQuery), function (e) {
    e.fn.mToggle = function (t) {
        var a = this, n = e(this), o = {
            run: function (e) {
                return n.data("toggle") ? a = n.data("toggle") : (o.init(e), o.build(), n.data("toggle", a)), a
            }, init: function (t) {
                a.element = n, a.events = [], a.options = e.extend(!0, {}, e.fn.mToggle.defaults, t), a.target = e(a.options.target), a.targetState = a.options.targetState, a.togglerState = a.options.togglerState, a.state = mUtil.hasClasses(a.target, a.targetState) ? "on" : "off"
            }, build: function () {
                n.on("click", o.toggle)
            }, sync: function () {
                e(n).data("toggle", a)
            }, toggle: function () {
                "off" == a.state ? o.on() : o.off()
            }, on: function () {
                return o.eventTrigger("beforeOn"), a.target.addClass(a.targetState), a.togglerState && n.addClass(a.togglerState), a.state = "on", o.eventTrigger("afterOn"), a
            }, off: function () {
                return o.eventTrigger("beforeOff"), a.target.removeClass(a.targetState), a.togglerState && n.removeClass(a.togglerState), a.state = "off", o.eventTrigger("afterOff"), a
            }, eventTrigger: function (e) {
                for (i = 0; i < a.events.length; i++) {
                    var t = a.events[i];
                    if (t.name == e) {
                        if (1 != t.one) return t.handler.call(this, a);
                        if (0 == t.fired) return a.events[i].fired = !0, t.handler.call(this, a)
                    }
                }
            }, addEvent: function (e, t, n) {
                a.events.push({name: e, handler: t, one: n, fired: !1}), o.sync()
            }
        };
        return o.run.apply(this, [t]), a.on = function (e, t) {
            return o.addEvent(e, t)
        }, a.one = function (e, t) {
            return o.addEvent(e, t, !0)
        }, a
    }, e.fn.mToggle.defaults = {togglerState: "", targetState: ""}
}(jQuery), $.notifyDefaults({template: '<div data-notify="container" class="alert alert-{0} m-alert" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss"></button><span data-notify="icon"></span><span data-notify="title">{1}</span><span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-animated bg-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'}), Chart.elements.Rectangle.prototype.draw = function () {
    function e(e) {
        return _[(w + e) % 4]
    }

    var t, a, n, o, i, l, r, s, d = this._chart.ctx, c = this._view, u = c.borderWidth,
        m = this._chart.options.barRadius ? this._chart.options.barRadius : 0;
    if (c.horizontal ? (t = c.base, a = c.x, n = c.y - c.height / 2, o = c.y + c.height / 2, i = a > t ? 1 : -1, l = 1, r = c.borderSkipped || "left") : (t = c.x - c.width / 2, a = c.x + c.width / 2, n = c.y > 2 * m ? c.y - m : c.y, i = 1, l = (o = c.base) > n ? 1 : -1, r = c.borderSkipped || "bottom"), u) {
        var p = Math.min(Math.abs(t - a), Math.abs(n - o)), f = (u = u > p ? p : u) / 2,
            g = t + ("left" !== r ? f * i : 0), h = a + ("right" !== r ? -f * i : 0), b = n + ("top" !== r ? f * l : 0),
            v = o + ("bottom" !== r ? -f * l : 0);
        g !== h && (n = b, o = v), b !== v && (t = g, a = h)
    }
    d.beginPath(), d.fillStyle = c.backgroundColor, d.strokeStyle = c.borderColor, d.lineWidth = u;
    var _ = [[t, o], [t, n], [a, n], [a, o]], w = ["bottom", "left", "top", "right"].indexOf(r, 0);
    -1 === w && (w = 0);
    var k = e(0);
    d.moveTo(k[0], k[1]);
    for (var C = 1; C < 4; C++) k = e(C), nextCornerId = C + 1, 4 == nextCornerId && (nextCornerId = 0), nextCorner = e(nextCornerId), width = _[2][0] - _[1][0], height = _[0][1] - _[1][1], x = _[1][0], y = _[1][1], (s = m) > height / 2 && (s = height / 2), s > width / 2 && (s = width / 2), d.moveTo(x + s, y), d.lineTo(x + width - s, y), d.quadraticCurveTo(x + width, y, x + width, y + s), d.lineTo(x + width, y + height - s), d.quadraticCurveTo(x + width, y + height, x + width - s, y + height), d.lineTo(x + s, y + height), d.quadraticCurveTo(x, y + height, x, y + height - s), d.lineTo(x, y + s), d.quadraticCurveTo(x, y, x + s, y);
    d.fill(), u && d.stroke()
}, $.fn.markdown.defaults.iconlibrary = "fa", $.fn.timepicker.defaults = $.extend(!0, {}, $.fn.timepicker.defaults, {
    icons: {
        up: "la la-angle-up",
        down: "la la-angle-down"
    }
}), jQuery.validator.setDefaults({
    errorElement: "div",
    errorClass: "form-control-feedback",
    focusInvalid: !1,
    ignore: "",
    errorPlacement: function (e, t) {
        var a = $(t).closest(".form-group").find(".m-form__help");
        a.length > 0 ? a.before(e) : $(t).after(e)
    },
    highlight: function (e) {
        $(e).closest(".form-group").addClass("has-danger"), $(e).hasClass("form-control")
    },
    unhighlight: function (e) {
        $(e).closest(".form-group").removeClass("has-danger")
    },
    success: function (e, t) {
        $(e).closest(".form-group").addClass("has-success").removeClass("has-danger"), $(e).closest(".form-group").find(".form-control-feedback").remove()
    }
});
var mLayout = function () {
    var e, t, a = function () {
        var e = $(".m-header"), t = {offset: {}, minimize: {}};
        "hide" == e.data("minimize-mobile") ? (t.minimize.mobile = {}, t.minimize.mobile.on = "m-header--hide", t.minimize.mobile.off = "m-header--show") : t.minimize.mobile = !1, "hide" == e.data("minimize") ? (t.minimize.desktop = {}, t.minimize.desktop.on = "m-header--hide", t.minimize.desktop.off = "m-header--show") : t.minimize.desktop = !1, e.data("minimize-offset") && (t.offset.desktop = e.data("minimize-offset")), e.data("minimize-mobile-offset") && (t.offset.mobile = e.data("minimize-mobile-offset")), e.mHeader(t)
    }, n = function () {
        $("#m_header_menu").mOffcanvas({
            class: "m-aside-header-menu-mobile",
            overlay: !0,
            close: "#m_aside_header_menu_mobile_close_btn",
            toggle: {target: "#m_aside_header_menu_mobile_toggle", state: "m-brand__toggler--active"}
        }), e = $("#m_header_menu").mMenu({
            submenu: {desktop: "dropdown", tablet: "accordion", mobile: "accordion"},
            resize: {
                desktop: function () {
                    var e = $("#m_header_nav").width();
                    return !($("#m_header_menu_container").width() + $("#m_header_topbar").width() + 20 > e)
                }
            }
        })
    }, o = function () {
        function e(e) {
            if (mUtil.isInResponsiveRange("tablet-and-mobile")) mApp.destroyScroller(e); else {
                var t = mUtil.getViewPort().height - $(".m-header").outerHeight() - (0 != $(".m-aside-left .m-aside__header").length ? $(".m-aside-left .m-aside__header").outerHeight() : 0) - (0 != $(".m-aside-left .m-aside__footer").length ? $(".m-aside-left .m-aside__footer").outerHeight() : 0);
                mApp.initScroller(e, {height: t})
            }
        }

        var a = $("#m_ver_menu"), n = {
            submenu: {
                desktop: {
                    default: 1 == a.data("menu-dropdown") ? "dropdown" : "accordion",
                    state: {body: "m-aside-left--minimize", mode: "dropdown"}
                }, tablet: "accordion", mobile: "accordion"
            }, accordion: {autoScroll: !0, expandAll: !1}
        };
        t = a.mMenu(n), a.data("menu-scrollable") && (e(t), mUtil.addResizeHandler(function () {
            e(t)
        }))
    }, i = function () {
        var e = $("#m_aside_left").hasClass("m-aside-left--offcanvas-default") ? "m-aside-left--offcanvas-default" : "m-aside-left";
        $("#m_aside_left").mOffcanvas({
            class: e,
            overlay: !0,
            close: "#m_aside_left_close_btn",
            toggle: {target: "#m_aside_left_offcanvas_toggle", state: "m-brand__toggler--active"}
        })
    }, l = function () {
        $("#m_aside_left_minimize_toggle").mToggle({
            target: "body",
            targetState: "m-brand--minimize m-aside-left--minimize",
            togglerState: "m-brand__toggler--active"
        }).on("toggle", function () {
            e.pauseDropdownHover(800), t.pauseDropdownHover(800)
        }), $("#m_aside_left_hide_toggle").mToggle({
            target: "body",
            targetState: "m-aside-left--hide",
            togglerState: "m-brand__toggler--active"
        }).on("toggle", function () {
            e.pauseDropdownHover(800), t.pauseDropdownHover(800)
        })
    }, r = function () {
        $("#m_aside_header_topbar_mobile_toggle").click(function () {
            $("body").toggleClass("m-topbar--on")
        }), setInterval(function () {
            $("#m_topbar_notification_icon .m-nav__link-icon").addClass("m-animate-shake"),
            $("#m_topbar_notification_icon .m-nav__link-badge").addClass("m-animate-blink")
        }, 3e3), setInterval(function () {
            $("#m_topbar_notification_icon .m-nav__link-icon").removeClass("m-animate-shake"),
            $("#m_topbar_notification_icon .m-nav__link-badge").removeClass("m-animate-blink")
        }, 6e3)
    }, s = function () {
        var e = $("#m_quicksearch");
        e.mQuicksearch({
            type: e.data("search-type"),
            source: "http://keenthemes.com/metronic/preview/inc/api/quick_search.php",
            spinner: "m-spinner m-spinner--skin-light m-spinner--right",
            input: "#m_quicksearch_input",
            iconClose: "#m_quicksearch_close",
            iconCancel: "#m_quicksearch_cancel",
            iconSearch: "#m_quicksearch_search",
            hasResultClass: "m-list-search--has-result",
            minLength: 1,
            templates: {
                error: function (e) {
                    return '<div class="m-search-results m-search-results--skin-light"><span class="m-search-result__message">Something went wrong</div></div>'
                }
            }
        })
    }, d = function () {
        $('[data-toggle="m-scroll-top"]').mScrollTop({offset: 300, speed: 600})
    };
    return {
        init: function () {
            this.initHeader(), this.initAside()
        }, initHeader: function () {
            a(), n(), r(), s(), d()
        }, initAside: function () {
            i(), o(), l()
        }
    }
}();
$(document).ready(function () {
    !1 === mUtil.isAngularVersion() && mLayout.init()
});
var mQuickSidebar = function () {
    var e = $("#m_quick_sidebar"), t = $("#m_quick_sidebar_tabs"), a = $("#m_quick_sidebar_close"),
        n = $("#m_quick_sidebar_toggle"), o = e.find(".m-quick-sidebar__content"), i = function () {
            var a = function () {
                var a = $("#m_quick_sidebar_tabs_messenger"), n = a.find(".m-messenger__messages"),
                    o = e.outerHeight(!0) - t.outerHeight(!0) - a.find(".m-messenger__form").outerHeight(!0) - 120;
                n.css("height", o), mApp.initScroller(n, {})
            };
            a(), mUtil.addResizeHandler(a)
        }, l = function () {
            var e = function () {
                var e = $("#m_quick_sidebar_tabs_settings"), a = mUtil.getViewPort().height - t.outerHeight(!0) - 60;
                e.css("height", a), mApp.initScroller(e, {})
            };
            e(), mUtil.addResizeHandler(e)
        }, r = function () {
            var e = function () {
                var e = $("#m_quick_sidebar_tabs_logs"), a = mUtil.getViewPort().height - t.outerHeight(!0) - 60;
                e.css("height", a), mApp.initScroller(e, {})
            };
            e(), mUtil.addResizeHandler(e)
        }, s = function () {
            i(), l(), r()
        }, d = function () {
            e.mOffcanvas({class: "m-quick-sidebar", close: a, toggle: n}), e.mOffcanvas().one("afterShow", function () {
                mApp.block(e), setTimeout(function () {
                    mApp.unblock(e), o.removeClass("m--hide"), s()
                }, 1e3)
            })
        };
    return {
        init: function () {
            d()
        }
    }
}();
$(document).ready(function () {
    mQuickSidebar.init()
});