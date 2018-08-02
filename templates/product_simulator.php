<br/>
<span class="js-pmt-payment-type"></span>
<div class="PmtSimulator" style="display:none"
     data-pmt-num-quota="<?php echo $settings['min_installments'] ; ?>"
     data-pmt-max-ins="<?php echo $settings['max_installments']; ?>" data-pmt-style="blue"
     data-pmt-type="<?php echo $settings['simulator_product']; ?>"
     data-pmt-discount="0" data-pmt-amount=""
     data-pmt-expanded="no">
</div>
<script>

function getWoocommercePrice(simulatorObject)
{
    if (typeof pmtClient !== 'undefined' && typeof pmtClient.simulator.getPublicKey()=='undefined') {
        pmtClient.setPublicKey(simulatorObject.publicKey);
    }
    // PRICE
    priceDiv = document.querySelectorAll(simulatorObject.selector);
    if( priceDiv !== 'undefined' ) {
        pricesLength = document.querySelectorAll(simulatorObject.selector).length;
        if( pricesLength !== 'undefined' && pricesLength > 0 ) {
            pricesLength = pricesLength - 1;
            price = document.querySelectorAll(simulatorObject.selector)[pricesLength].innerText.toString();
            price = price.replace(/€|&euro/g, '').replace(',','.').replace(',','.');
            pointPieces = price.split(".");
            if (pointPieces!=='undefined' && pointPieces.length == '3')
            {
                price = pointPieces[0].concat(pointPieces[1]).concat('.').concat(pointPieces[2]);
            }

            price = parseFloat(price);
            if( price!=='undefined' && price!='' ) {
                currentPrice = document.getElementsByClassName('PmtSimulator')[0].getAttribute('data-pmt-amount');

                if( simulatorObject.quantity_selector !== 'undefined' && simulatorObject.quantity_selector!='' ) {
                    qtys = document.querySelectorAll(simulatorObject.quantity_selector);
                    if(qtys.length == 1) {
                        qty = parseFloat(document.querySelector(simulatorObject.quantity_selector).value);
                        price = price * qty;
                    }
                }
                if( price < simulatorObject.min_amount || price > simulatorObject.max_amount ) {
                    document.getElementsByClassName('PmtSimulator')[0].style.display = 'none';
                    document.getElementsByClassName('PmtSimulator')[0].setAttribute('data-pmt-amount', '0');
                } else if (currentPrice != price ) {
                        document.getElementsByClassName('PmtSimulator')[0].style.display = '';
                        document.getElementsByClassName('PmtSimulator')[0].setAttribute('data-pmt-amount', price);
                        pmtClient.simulator.reload();
                        }
            }
        }
    }
}

// CONFIG VARS
var min_amount = '<?php echo $settings['min_amount'];?>';
min_amount = (min_amount != '') ? parseFloat(min_amount) : '0.00';

var max_amount = '<?php echo $settings['max_amount'];?>';
max_amount = (max_amount != '') ? parseFloat(max_amount) : '10000000.00';

var quantity_selector = '<?php echo html_entity_decode($settings['quantity_selector']); ?>';

var selector = '<?php echo html_entity_decode($settings['price_selector']);?>';

var public_key = '<?php echo html_entity_decode($settings['public_key']);?>';

if (selector != '') {
    var simulatorObject = {min_amount:min_amount,
                            max_amount:max_amount,
                            selector:selector,
                            quantity_selector:quantity_selector,
                            public_key:public_key
    };
    setInterval(function () {
        getWoocommercePrice(simulatorObject);
    }, 2000);
}

</script>
<script>
    if (typeof pmtClient !== 'undefined') {
        pmtClient.setPublicKey('<?php echo $settings['public_key']; ?>');
        pmtClient.simulator.reload();
    }
</script>
