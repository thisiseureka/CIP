(() => {
    var t;
    (t = jQuery)(document).ready(function () {
        t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_gmail_auth_origin]']").val(uACF7DPE_Pram.site_url),
            t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_gmail_redirect_url]']").val(uACF7DPE_Pram.redirect_url),
            t("#uacf7dp-test-connection").on("click", function (e) {
                e.preventDefault();
                let n = t("select[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_connection_type]']").val(),
                    i = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_imap_email_address]']").val(),
                    s = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_imap_email_password]']").val(),
                    a = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_imap_email_server]']").val(),
                    l = t("select[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_imp_connection_type]']").val(),
                    c = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_imp_connection_port]']").val(),
                    o = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_gmail_address]']").val(),
                    f = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_gmail_client]']").val(),
                    p = t("input[id='uacf7_settings[uacf7dp_email_piping_tap][uacf7dp_gmail_client_secret]']").val();
                if ("imap" == n) {
                    if ("" == i || "" == s || "" == a || "" == l || "" == c) return void alert("Please fill all the fields");
                    t.ajax({
                        url: uACF7DPE_Pram.ajax_url,
                        type: "POST",
                        data: { action: "uacf7dp_test_imap_connection", email: i, password: s, server: a, connection_type: l, connection_port: c, _nonce: uACF7DPE_Pram.nonce },
                        beforeSend: function () {
                            t("#uacf7dp-test-connection").addClass("tf-btn-loading");
                        },
                        success: function (e) {
                            t("#uacf7dp-test-connection").removeClass("tf-btn-loading"),
                                "success" == e.status ? t(".uacf7dp-connection-result").addClass("connection-success").html(e.message) : "error" == e.status && t(".uacf7dp-connection-result").addClass("connection-failed").html(e.message);
                        },
                        error: function (e) {
                            console.log(e);
                        },
                    });
                } else if ("gmail" == n) {
                    if ("" == o || "" == f || "" == p) return void alert("Please fill all the fields");
                    t.ajax({
                        url: uACF7DPE_Pram.ajax_url,
                        type: "POST",
                        data: { action: "uacf7dp_test_gmail_connection", email: o, client_id: f, client_secret: p, _nonce: uACF7DPE_Pram.nonce },
                        beforeSend: function () {
                            t("#uacf7dp-test-connection").addClass("tf-btn-loading");
                        },
                        success: function (e) {
                            t("#uacf7dp-test-connection").removeClass("tf-btn-loading"),
                                "success" == e.status
                                    ? (t(".uacf7dp-connection-result").addClass("connection-success").html(e.message), (window.location.href = e.url))
                                    : "error" == e.status && t(".uacf7dp-connection-result").addClass("connection-failed").html(e.message);
                        },
                        error: function (e) {
                            console.log(e);
                        },
                    });
                }
            }),
            t("#uacf7dp_entire_reply_mail_head_btn").on("click", function (e) {
                let n = t(this),
                    i = uACF7DPE_Pram.uacf7dp_connection_type;
                "imap" == i
                    ? t.ajax({
                          url: uACF7DPE_Pram.ajax_url,
                          type: "POST",
                          data: { action: "uacf7dp_single_imap_sync", _nonce: uACF7DPE_Pram.nonce },
                          beforeSend: function () {
                              n.find('svg').addClass("animation_rotate");
                          },
                          success: function (t) {
                              n.find('svg').removeClass("animation_rotate"), "success" == t.status ? window.location.reload() : "error" == t.status && alert(t.message);
                          },
                      })
                    : "gmail" == i &&
                    t.ajax({
                          url: uACF7DPE_Pram.ajax_url,
                          type: "POST",
                          data: { action: "uacf7dp_single_gmail_sync", _nonce: uACF7DPE_Pram.nonce },
                          beforeSend: function () {
                              n.find('svg').addClass("animation_rotate");
                          },
                          success: function (t) {
                              n.find('svg').removeClass("animation_rotate"), "success" == t.status ? window.location.reload() : "error" == t.status && alert(t.message);
                          },
                    });     
            });
    });
})();