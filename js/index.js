$(function(){
    getCurrentActivities()
})

let myPos
var _args = {}; 
var PARAMS = PARAMS || (function(){

    return {
        init : function(Args) {
            _args = Args;
        }
    };
}());



function getCurrentActivities()
{
    let activity = ""
    let s = new Date()
    $.get(_args.getCurrentActivitiesURL,
        function (data, textStatus, jqXHR) {
            if(data){
                $.each(data, function (a, b) { 
                    let isPaid = ""
                    let expiration = ""
                    if(b.isPaid == 1){
                        isPaid = "<span class='text-white float-right'><b>PAID </b></span>"
                        expiration = ""
                    }
                    else{
                        let t = new Date(b.reservationDateTime)
                        t = 15 - Math.floor(( s - t)/ 1000 / 60);
                        
                        expiration = `${t}m`
                        isPaid = `<small class='float-right'>${expiration}</small>`
                    }
                    let bg = b.isPaid == 1 ? "bg-success" : "";
                    let txt = b.isPaid == 1 ? "text-white" : "";
                    
                    let link = "<a class='btn btn-success btn-round float-right' href='"+_args.baseUrl+"checkout.php?d="+b.reservationCode+"'>"
                        +"<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cash' viewBox='0 0 16 16'>"
                        +"<path d='M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z'/>"
                        +"<path d='M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z'/>"
                        +"</svg>"
                        +"</svg> Pay Reservation</a>";


                    if(b.isPaid == 1){
                        link = "<a class='btn btn-info btn-round float-right' href='"+_args.baseUrl+"reservation.php?d="+b.reservationCode+"'>"
                        +"<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pin-map' viewBox='0 0 16 16'><path fill-rule='evenodd' d='M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8l3-4z'></path><path fill-rule='evenodd' d='M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z'></path>"
                        +"</svg> Open Information</a>";
                    }

                    activity += "<div class='col-12'>"
                        +"<div class='card mb-3 "+bg+" shadow-sm bg-gradient direction-rtl'>"
                        +"<div class='d-flex m-3 justify-content-between align-items-center border-0'><h4 class='font-weight-bold'>"+b.name+"</h4> "+isPaid+"</div>"
                        +"<div class='card-body  pt-1'>"
                        +"<p class='"+txt+" mb-4'>"+b.address+" </p>"
                        +"<p class='"+txt+" mb-4'>"+b.reservationDateTime+" </p>"
                        +link
                        +"<a href='"+_args.routesURL+"?lng="+b.lng+"&lat="+b.lat+"'> Go Now</a>"
                        +"</div></div></div>"
                });
                $("#currentActivitiesList").html(activity)
                
            }
        },
    );
}