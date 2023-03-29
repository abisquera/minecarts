
var _args = {}; 
var markers = []
var gMap
var myPos = {}
var lotRates = [];
var PARAMS = PARAMS || (function(){

    return {
        init : function(Args) {
            _args = Args;
        },
        getParkingLocations : function(){
            return getParkingLocations(_args.getParkingLocationsURL)
        },
        getUserVehicles : function(){
          return getUserVehicles(_args.getUserVehiclesURL)
        }
    };
}());


$(function(){
  const config = {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minDate : date = new Date(),
    minuteIncrement : 30
}
  flatpickr("#datetimepicker1",config)
  getParkingLocations(_args.getParkingLocationsURL+"?search="+$("#search").val())
  getUserVehicles(_args.getUserVehiclesURL)
})

function getUserVehicles(url){
  $.get(url,
      function (data, textStatus, jqXHR) {
        $("#vehicleId")
          .find('option')
          .remove()
          .end()
        if(data.length)
          $.each(data, function (a, b) { 
            $("#vehicleId").append(new Option(b.vehicleBrand + " " + b.vehicleModel + " " + b.plateNum, b.vehicleId));
          });
        else 
          $("#vehicleId").append(new Option("No Vehicle", ""));
      }
  );
}

function getParkingLocations(url) {
  for (let i = 0; i < markers.length; i++) {
      markers[i].setMap(null);
  }
    $.get(url,
        function (data, textStatus, jqXHR) {
          let nearest = []

          $.each(data, function (a, b) { 
              mapMarker(b,gMap)
              if(myPos){
                  nearest.push({
                      cls :  distance(myPos.lng,myPos.lat, b.lng, b.lat),
                      info : b
                  })
              }
          });
          nearest.sort((a, b) => a.cls - b.cls);
          let near = nearest[0]
          gMap.setCenter({ lat : near.info.lat, lng : near.info.lng});
        }
    );
}


function toRad(Value) {
    /** Converts numeric degrees to radians */
    return Value * Math.PI / 180;
}
  
function distance(lon1, lat1, lon2, lat2) {
  var R = 6371; // Radius of the earth in km
  var dLat = toRad(lat2-lat1);  // Javascript functions in radians
  var dLon = toRad(lon2-lon1); 
  var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
          Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * 
          Math.sin(dLon/2) * Math.sin(dLon/2); 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in km
  return d;
  }
  

function initMap() {
    const uluru =  { lat: 13.756896, lng:  121.07 };
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: uluru,
    });

    gMap = map
    if(navigator.geolocation){
      navigator.geolocation.getCurrentPosition(
        (position) => {
          myPos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          new google.maps.Marker({
              position: myPos,
              label: "me",
              map: map,
          });
          // map.setCenter(myPos);
        },
        () => {
        }
      );
    }


    $('#search').keyup(delay(function (e) {
      getParkingLocations(_args.getParkingLocationsURL+"?search="+$(this).val())
    }, 500));
    
}

function mapMarker(data,map){
    let marker =  new google.maps.Marker({
      position: {lat : data.lat, lng : data.lng },
      label: "PP",
      map: map,
    });
    marker.addListener("click", () => {
        map.setCenter(marker.getPosition());
        let mod = $("#reserveParking")
        mod.find("#lotId").val(data.lotId)
        mod.find("#lotSlotReserved").html(0)
        mod.find("#lotSlotAvailabled").html(0)
        $(".single-plan-check").removeClass("active")
        getParkingReservationInformation(data.lotId)
        let fixed = $("#schedType1")
        fixed.closest(".single-plan-check").addClass("active")
        fixed.prop("checked",true)
        loadLotRates(data.lotId)
        mod.modal("show")
    });
    
  markers.push(marker);
    
}

function getParkingReservationInformation(lotId, dt = null)
{
  $.get(_args.getParkingReservationInformationURL, { lotId : lotId, dt : dt},
    function (res, textStatus, jqXHR) {
      let mod = $("#reserveParking")
      mod.find("#reserveParkingLabel").html(res.name)
      mod.find("#address").html(res.address)
      mod.find("#lotSlot").html(res.lotSlot)
      mod.find("#lotSlotReserved").html(res.lotSlotReserved)
      mod.find("#lotSlotAvailable").html(res.lotSlotAvailable)
    }
  );
}

$("#reserveParkingForm").find(".btn-success").on("click",function(){
  let frm = $("#reserveParkingForm")
  let mod = $("#reserveParking")
  $.post(_args.reserveParkingURL, frm.serializeArray(),
    function (res, textStatus, jqXHR) {
      if(res.s == "success"){
        swal({
          title: res.h,
          text: res.m,
          icon: res.s,
          buttons: ["Close",  "Pay Now"],
        })
        .then((willExit) => {
          if(res.c && willExit)
            window.location.replace(_args.baseUrl+"checkout.php?d="+res.c);

        });
      }
      else{
        if(res.s == "info"){
          let dat = frm.serializeArray()
          dat.push({
            name : "dualReservation",
            value : 1
          })
            swal({
              title: res.h,
              text: res.m,
              icon: res.s,
              buttons: ["Close",  "Continue"],
            })
            .then((willExit) => {
              if(willExit){
                payNow(dat)
              }
              mod.modal("hide")
            });
        }
        else {
          swal(res.h,res.m,res.s)
        }
      }
      mod.modal("hide")
    }
  );
})

function payNow(data)
{
  $.post(_args.reserveParkingURL, data,
    function (res, textStatus, jqXHR) {
      if(res.s == "success"){
        swal({
          title: res.h,
          text: res.m,
          icon: res.s,
          buttons: ["Close",  "Pay Now"],
        })
        .then((willExit) => {
          if(res.c)
            window.location.replace(_args.baseUrl+"checkout.php?d="+res.c);
        });
      }
      else{
        swal(res.h,res.m,res.s)
      }
    }
  );
  }


$(".schedType").on("change",function(){
  let st = $(this).val()

  $("#selectDateTimeHR").removeClass("d-none")
  $("#lotPriceId").attr("disabled",false)
  if(st == 1){
    $("#selectDateTimeHR").addClass("d-none")
    $("#lotPriceId").attr("disabled",true)
  }
})

function loadLotRates(lotId)
{
  $.get(_args.loadLotRatesURL, { lotId : lotId },
    function (data, textStatus, jqXHR) {
      
      lotRates = data

      $("#lotPriceId")
        .find('option')
        .remove()
        .end()

      if(data.length == 0){
        $("input[name=schedType][value=0]").attr("disabled",true)
        $("#lotPriceId").attr("disabled",true)
        $("#selectDateTimeHR").addClass("d-none")
        return
      }
      $("input[name=schedType][value=0]").attr("disabled",false)
        $("#lotPriceId").attr("disabled",false)

      $.each(data, function (a, b) { 
        $("#lotPriceId").append(new Option(b.hours + "h - " +  new Intl.NumberFormat('en-PH', { style: 'currency', currency : "PHP" }).format(b.price), b.lotPriceId));
      });
    }
  );
}

$("#datetimepicker1").on("change",function(){
  getParkingReservationInformation($("#lotId").val(),$(this).val())
})


window.initMap = initMap;
