(function ($) {
    $(document).ready(function () {

        function toggleButton($btn) {
            $btn.attr('disabled', !$btn.attr('disabled'))
            $btn.toggleClass('is-loading')
        }

        function checkStatus() {
            $statusBar.css({ backgroundColor: 'black' })
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

        var $statusBar = $('#wp-admin-bar-olx-status')
        if ($statusBar.length) {
            checkStatus()
            setInterval(function () {
                checkStatus()
            }, 60000)
        }

        var $publishAdvertBtns = $('.js-olx-advert-publish')
        if ($publishAdvertBtns.length) {
            $publishAdvertBtns.on('click', function (e) {
                e.preventDefault()
                var $btn = $(this)
                var productId = $btn.attr('data-product-id')
                toggleButton($btn)
                $.ajax({
                    url: olxData.ajaxUrl,
                    data: {
                        action: 'addAdvert',
                        nonce: olxData.security,
                        productId: productId
                    }
                })
                    .done(function (res) {
                        toggleButton($btn)
                        alert('Ogłoszenie dodane')
                        console.log('Ogłoszenie dodane', res)
                        location.reload()
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd podczas dodawania ogłoszenia')
                        console.error('Błąd podczas dodawania ogłoszenia', error)
                        return false
                    });
            })
        }

        var $updateAdvertBtns = $('.js-olx-advert-update')
        if ($updateAdvertBtns.length) {
            $updateAdvertBtns.on('click', function (e) {
                e.preventDefault()
                var $btn = $(this)
                var productId = $btn.attr('data-product-id')
                toggleButton($btn)
                $.ajax({
                    url: olxData.ajaxUrl,
                    data: {
                        action: 'updateAdvert',
                        nonce: olxData.security,
                        productId: productId
                    }
                })
                    .done(function (res) {
                        toggleButton($btn)
                        alert('Ogłoszenie zaktualizowane')
                        console.log('Ogłoszenie zaktualizowane', res)
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd aktualizacji ogłoszenia')
                        console.error('Błąd aktualizacji ogłoszenia', error)
                        return false
                    });
            })
        }

        var $deactivateAdvertBtns = $('.js-olx-advert-unpublish')
        if ($deactivateAdvertBtns.length) {
            $deactivateAdvertBtns.on('click', function (e) {
                e.preventDefault()
                var $btn = $(this)
                var productId = $btn.attr('data-product-id')
                toggleButton($btn)
                $.ajax({
                    url: olxData.ajaxUrl,
                    data: {
                        action: 'unpublishAdvert',
                        nonce: olxData.security,
                        productId: productId
                    }
                })
                    .done(function (res) {
                        toggleButton($btn)
                        alert('Ogłoszenie dezaktywowane')
                        console.log('Ogłoszenie dezaktywowane', res)
                        location.reload()
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd deaktywacji ogłoszenia')
                        console.error('Błąd deaktywacji ogłoszenia', error)
                        return false
                    });
            })
        }

        var $advertSoldBtns = $('.js-olx-advert-sold')
        if ($advertSoldBtns.length) {
            $advertSoldBtns.on('click', function (e) {
                e.preventDefault()
                var $btn = $(this)
                var productId = $btn.attr('data-product-id')
                toggleButton($btn)
                $.ajax({
                    url: olxData.ajaxUrl,
                    data: {
                        action: 'advertSold',
                        nonce: olxData.security,
                        productId: productId
                    }
                })
                    .done(function (res) {
                        toggleButton($btn)
                        alert('Ogłoszenie dezaktywowane')
                        console.log('Ogłoszenie dezaktywowane', res)
                        location.reload()
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd deaktywacji ogłoszenia')
                        console.error('Błąd deaktywacji ogłoszenia', error)
                        return false
                    });
            })
        }

    })
})(jQuery)
