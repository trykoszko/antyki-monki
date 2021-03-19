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
                })
        }

        function sendErrorMessage(message) {
            $.ajax({
                url: olxData.ajaxUrl,
                data: {
                    action: 'sendErrorNotice',
                    message: message
                }
            })
        }

        var $statusBar = $('#wp-admin-bar-olx-status')
        if ($statusBar.length) {
            checkStatus()
            setInterval(function () {
                checkStatus()
            }, 60000)
        }

        var $refreshStatsBtn = $('#wp-admin-bar-olx-refresh-stats')
        if ($refreshStatsBtn.length) {
            $refreshStatsBtn.on('click', function (e) {
                e.preventDefault()
                $.ajax({
                    url: olxData.ajaxUrl,
                    data: {
                        action: 'refreshAdvertStats'
                    }
                })
                    .done(function (res) {
                        console.log('res', res)
                        if (res.error) {
                            alert('Błąd odświeżania statystyk: ' + res.error.detail)
                        } else {
                            alert('Statystyki odświeżone')
                            console.log('Statystyki odświeżone', res)
                            location.reload()
                        }
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd podczas odświeżania statystyk')
                        console.error('Błąd podczas odświeżania statystyk', error)
                        sendErrorMessage('Admin.js->refreshStats error: ' + error.statusText)
                        return false
                    })
            })
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
                        console.log('res', res)
                        if (res.error || !res.success) {
                            alert('Błąd dodawania ogłoszenia')
                        } else {
                            alert('Ogłoszenie dodane')
                            console.log('Ogłoszenie dodane', res)
                            location.reload()
                        }
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd podczas dodawania ogłoszenia')
                        console.error('Błąd podczas dodawania ogłoszenia', error)
                        sendErrorMessage('Admin.js->publishAdvert error: ' + error.statusText)
                        return false
                    })
            })
        }

        // @TODO: add php ajax method for refreshing ad status from olx.
        // var $refreshStatusBtns = $('.js-olx-advert-refresh-status')
        // if ($refreshStatusBtns.length) {
        //     $refreshStatusBtn.on('click', function (e) {
        //         e.preventDefault()
        //         var $btn = $(this)
        //         var productId = $btn.attr('data-product-id')
        //         toggleButton($btn)
        //         $.ajax({
        //             url: olxData.ajaxUrl,
        //             data: {
        //                 action: 'refreshAdvertStatus',
        //                 nonce: olxData.security,
        //                 productId: productId
        //             }
        //         })
        //             .done(function (res) {
        //                 toggleButton($btn)
        //                 console.log('res', res)
        //                 if (res.error || !res.success) {
        //                     alert('Błąd odświeżania statusu ogłoszenia')
        //                 } else {
        //                     alert('Ogłoszenie powinno być już aktywne')
        //                     console.log('Ogłoszenie powinno być już aktywne', res)
        //                     location.reload()
        //                 }
        //             })
        //             .fail(function (error) {
        //                 toggleButton($btn)
        //                 alert('Błąd podczas odświeżania statusu')
        //                 console.error('Błąd podczas odświeżania statusu', error)
        //                 sendErrorMessage('Admin.js->refreshStatus error: ' + error.statusText)
        //                 return false
        //             })
        //     })
        // }

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
                        if (res.error) {
                            alert('Błąd aktualizacji ogłoszenia: ' + res.error.detail)
                        } else {
                            alert('Ogłoszenie zaktualizowane')
                            console.log('Ogłoszenie zaktualizowane', res)
                        }
                    })
                    .fail(function (error) {
                        toggleButton($btn)
                        alert('Błąd aktualizacji ogłoszenia')
                        console.error('Błąd aktualizacji ogłoszenia', error)
                        sendErrorMessage('Admin.js->updateAdvert error: ' + error.statusText)
                        return false
                    })
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
                        sendErrorMessage('Admin.js->deactivateAdvert error: ' + error.statusText)
                        return false
                    })
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
                        sendErrorMessage('Admin.js->advertSold error: ' + error.statusText)
                        return false
                    })
            })
        }

        var $olxFieldGroup = $('#acf-group_6027e82fe990b')
        if ($olxFieldGroup.length) {
            $olxFieldGroup.find('input, textarea').each(function() {
                var $field = $(this)
                var isTextarea = $field[0].tagName && $field[0].tagName.toLowerCase() == "textarea"
                if (isTextarea) {
                    $('<pre style="background: white; padding: 10px; break-word: break-all;">' + JSON.stringify(JSON.parse($field.val()), null, '\t') + '</pre>').insertBefore($field)
                } else {
                    $('<pre style="background: white; padding: 10px; break-word: break-all;">' + $field.val() + '</pre>').insertBefore($field)
                }
                $field.remove()
            })
        }

    })
})(jQuery)
