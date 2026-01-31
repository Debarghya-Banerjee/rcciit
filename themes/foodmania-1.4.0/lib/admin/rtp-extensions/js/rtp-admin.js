/**
 * Admin Scripts
 *
 * @package Foodmania
 */

jQuery(document).ready(function() {

    // Enable Internationalization 
    const { __, _x, _n, sprintf } = wp.i18n;

    /* Plugins */
    jQuery(".rtp-manage-plugin  a.rtp-manage-plugin-action").on('click', function(
        e
    ) {
        var elem = jQuery(this),
            plugin_action = elem.data("action"),
            plugin = elem.data("plugin"),
            plugin_title = elem.data("plugin-title"),
            nonce = elem.data("nonce"),
            that = this;

        if (plugin_action === "purchase") {
            return true;
        } else {
            e.preventDefault();
        }

        if (
            elem.hasClass("delete") &&
            !confirm(
                sprintf(__('Are you sure you want to delete %s plugin ?', 'foodmania'), plugin_title))
        ) {
            return false;
        }

        //@todo:Add Loading
        var siteUrl = elem.attr("data-site-url");
        elem.after(
            '<img class="rtp-ajax-loder" src="' +
            siteUrl +
            '/img/loader.gif" alt="loader" />'
        );

        jQuery.ajax({
            url: ajaxurl,
            type: "post",
            data: {
                action: "rtp_plugin_manage",
                plugin_action: jQuery(this).data("action"),
                plugin: jQuery(this).data("plugin"),
                nonce: jQuery(this).data("nonce"),
            },
            success: function(data) {
                if (data.success === false) {

                    alert(__('Fatal Error Occured could not complete action.', 'foodmania'));
                } else {
                    location.reload();
                    jQuery(".rtp-ajax-loder").remove();
                }
            },
        });
    });

    /*
     * Submit Form
     */
    jQuery("#rtp-submit-request").click(function() {
        var flag = true;
        var name = jQuery("#name").val();
        var email = jQuery("#email").val();
        var website = jQuery("#website").val();
        var phone = jQuery("#phone").val();
        var subject = jQuery("#subject").val();
        var details = jQuery("#details").val();
        var request_type = jQuery('input[name="request_type"]').val();
        var request_id = jQuery('input[name="request_id"]').val();
        var server_address = jQuery('input[name="server_address"]').val();
        var ip_address = jQuery('input[name="ip_address"]').val();
        var server_type = jQuery('input[name="server_type"]').val();
        var user_agent = jQuery('input[name="user_agent"]').val();
        var tickit_nonce = jQuery('input[name="tickit_nonce"]').val();
        var form_data = {
            name: name,
            email: email,
            website: website,
            phone: phone,
            subject: subject,
            details: details,
            request_id: request_id,
            request_type: "premium_support",
            server_address: server_address,
            ip_address: ip_address,
            server_type: server_type,
            user_agent: user_agent,
            tickit_nonce: tickit_nonce,
        };

        if (request_type == "bug_report") {
            var wp_admin_username = jQuery("#wp_admin_username").val();
            if (wp_admin_username == "") {
                alert(__('Please enter WP Admin Login.', 'foodmania'));
                return false;
            }
            var wp_admin_pwd = jQuery("#wp_admin_pwd").val();
            if (wp_admin_pwd == "") {
                alert(__('Please enter WP Admin password.', 'foodmania'));
                return false;
            }
            var ssh_ftp_host = jQuery("#ssh_ftp_host").val();
            if (ssh_ftp_host == "") {
                alert(__('Please enter SSH / FTP host.', 'foodmania'));
                return false;
            }
            var ssh_ftp_username = jQuery("#ssh_ftp_username").val();
            if (ssh_ftp_username == "") {
                alert(__('Please enter SSH / FTP login.', 'foodmania'));
                return false;
            }
            var ssh_ftp_pwd = jQuery("#ssh_ftp_pwd").val();
            if (ssh_ftp_pwd == "") {
                alert(__('Please enter SSH / FTP password.', 'foodmania'));
                return false;
            }
            form_data = {
                name: name,
                email: email,
                website: website,
                phone: phone,
                subject: subject,
                details: details,
                request_id: request_id,
                request_type: "premium_support",
                server_address: server_address,
                ip_address: ip_address,
                server_type: server_type,
                user_agent: user_agent,
                tickit_nonce: tickit_nonce,
                wp_admin_username: wp_admin_username,
                wp_admin_pwd: wp_admin_pwd,
                ssh_ftp_host: ssh_ftp_host,
                ssh_ftp_username: ssh_ftp_username,
                ssh_ftp_pwd: ssh_ftp_pwd,
            };
        }
        for (formdata in form_data) {
            if (form_data[formdata] == "" && formdata != "phone") {
                alert("Please enter " + formdata.replace("_", " ") + " field.");
                return false;
            }
        }
        data = {
            action: "rtp_submit_request",
            form_data: form_data,
        };
        jQuery.post(ajaxurl, data, function(data) {
            data = data.trim();
            if (data == "false") {
                alert(__('Please fill all the fields.', 'foodmania'));
                return false;
            }
            jQuery("#rtp_service_contact_container").empty().append(data);
        });
        return false;
    });

    //For Redux support section
    jQuery(".rtp-manage-plugin")
        .parents(".form-table")
        .find("th")
        .css("width", "0");
    jQuery("#rtp-support").parents(".form-table").find("th").css("width", "0");
    jQuery("#rtp-debug-info")
        .parents(".form-table")
        .find("th")
        .css("width", "0");
});