(function ($) {
    $(document).ready(function () {

        var $statusBar = $('#wp-admin-bar-olx-status')
        if ($statusBar.length) {
            $.ajax({
                url: olxData.ajaxUrl,
                data: {
                    action: 'checkStatus',
                    nonce: olxData.security
                }
            })
                .done(function (res) {
                    var styles = {}
                    if (res.success && res.data) {
                        styles.backgroundColor = 'darkgreen'
                    } else {
                        styles.backgroundColor = 'darkred'
                    }
                    $statusBar.css(styles)
                })
                .fail(function (error) {
                    console.log('error', error)
                    return false
                });
        }

    })
})(jQuery)
