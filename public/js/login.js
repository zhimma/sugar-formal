var SnippetLogin = function() {
    var e = $("#m_login"),
        l = function() {
            $("#m_login_signin_submit").click(function(e) {
                e.preventDefault();
                var a = $(this),
                    t = $(this).closest("form");
                t.validate({
                    rules: {
                        email: {
                            required: !0,
                            email: !0
                        },
                        password: {
                            required: !0
                        }
                    }
                }), t.valid() && (a.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), t.submit())
            })
        },
        s = function() {
            $("#m_login_signup_submit").click(function(a) {
                a.preventDefault();
                var r = $(this),
                    n = $(this).closest("form");
                n.validate({
                    rules: {
                        name: {
                            required: !0
                        },
                        title: {
                            required: !0
                        },
                        email: {
                            required: !0,
                            email: !0
                        },
                        password: {
                            required: !0,
                            minlength: 6
                        },
                        engroup: {
                            required: !0
                        },
                        password_confirmation: {
                            required: !0,
                            minlength: 6
                        },
                        agree: {
                            required: !0
                        }
                    }
                }), n.valid() && (r.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), n.submit())
            })
        }
    return {
        init: function() {
           l(), s()
        }
    }
}();
$(document).ready(function() {
    SnippetLogin.init()
});