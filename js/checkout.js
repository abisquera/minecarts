
var _args = {};
var PARAMS = PARAMS || (function(){

    return {
        init : function(Args) {
            _args = Args;
        }
    };
}());
$(function(){
    loadActivity()
  
})
function loadActivity(){
    $.get(_args.loadActivityInfoURL,
        function (data, textStatus, jqXHR) {
            $("#checkoutContainer").html(data.h)
            let rate = 0;
            if(data.d){
                let dat = data.d
                if(dat.price == 0 || dat.price == null)
                  rate = _args.fixedRate;
                else
                  rate = dat.price

                $.each(dat, function (a, b) {
                    if(a == "reservationCode")
                      $(`#${a}`).val(b)
                    if(a == "price")
                      $(`#${a}`).html(new Intl.NumberFormat('en-PH', { style: 'currency', currency : "PHP" }).format(rate))
                    else
                      $(`#${a}`).html(b)

                });

                initPayPalButton(rate);
            }
        }
    );
}

function initPayPalButton(rate) {
    paypal.Buttons({
      style: {
        shape: 'rect',
        color: 'gold',
        layout: 'vertical',
        label: 'paypal',
        
      },

      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            "amount":{"currency_code":"PHP","value":rate},
          }]
        });
      },

      onApprove: function(data, actions) {
        return actions.order.capture().then(function(orderData) {
          
          // Full available details

            // Show a success message within this page, e.g.
            orderData.reservationCode = $("#reservationCode").val()
            $.post(_args.pponApproveURL,orderData,
                function (data, textStatus, jqXHR) {
                    $("#checkoutContainer").html(data.h)
                    let dat = data.d
                    $("#orderIDpp").html(`Your Transaction ID is ${dat.id}`)
                    $("#orderCheckoutCode").attr("href", _args.baseURL+"reservation.php?d="+orderData.reservationCode);
                }
            );

            // Or go to another URL:  actions.redirect('thank_you.html');
            
        });
      },

      onError: function(err) {
        console.log(err);
      }
    }).render('#paypal-button-container');
  }





  