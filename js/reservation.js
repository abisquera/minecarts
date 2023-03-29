
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
            if(data.h){
              $("#reservationContainer").html(data.h)
              return;
            }
            if(data.d){
                let dat = data.d
                $.each(dat, function (a, b) {
                    if(a == "amountPaid")
                        $(`#${a}`).html(new Intl.NumberFormat('en-PH', { style: 'currency', currency : "PHP" }).format(b))
                    else
                        $(`#${a}`).html(b)
                });
            }
            if(data.qr){
              $("#reservationCodeIMG").attr('src',data.qr);
            }
        }
    );
}




  