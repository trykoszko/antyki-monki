(function ($) {
    $(document).ready(function () {

        function checkOlxStatus() {
            $.ajax({
                url: olxData.ajaxUrl,
                data: {
                    action: 'checkStatus',
                    nonce: olxData.security
                }
            })
                .done(function (res) {
                    console.log('res', res)
                })
                .fail(function (error) {
                    console.log('error', error)
                });
        }

        var $statusBar = $('#wp-admin-bar-olx-status')
        if ($statusBar.length) {
            $statusBar.on('click', function (e) {
                e.preventDefault()
                checkOlxStatus()
            })
            var status = checkOlxStatus()
            var styles = {}
            if (status === 'online') {
                styles.backgroundColor = 'darkgreen'
            } else {
                styles.backgroundColor = 'darkred'
            }
            $statusBar.css(styles)
        }

    })
})(jQuery)
