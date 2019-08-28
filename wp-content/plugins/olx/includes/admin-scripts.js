(function($) {
    $(document).ready(function(){

        let pushToOlx = () => {
            $('.push-to-olx').on('click', function(e) {
                e.preventDefault()
                var productId = $(this)[0].dataset.productId
                console.log(productId)
                if (window.confirm('Jestes pewny ze chcesz to wystawic na OLX? Mozliwe, ze zostanie naliczona oplata.')) {
                    console.log('Yes')
                    


                } else {
                    console.log('no')
                }
            })
        }
        pushToOlx()

   });
}(jQuery));
