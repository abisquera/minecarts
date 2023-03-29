<?php
 

function loginUser($data){
    $user = getContactinfo($data['contactNumber']);


    if(empty($user) || !$user)
        return [
            "h"=>"Sign In Failed",
            "m"=>"Incorrect Username or Password",
            "s"=>"warning"
        ];

    $user = (object) $user;

    if($data['password'] != $user->password)
        return [
            "h"=>"Sign In Failed",
            "m"=>"Incorrect Username or Password",
            "s"=>"warning"
        ];
        
    if($user->isLocked)
        return [
            "h"=>"Sign In Failed",
            "m"=>"This account is Locked",
            "s"=>"warning"
        ];
        

    $_SESSION['user'] = $user;
    
    $_SESSION["fixedRate"] = 100;
    $_SESSION["fixedRateHours"] = 24;

    return [
        $user,
        "s"=>"success"
    ];

}

function addUser($data){
    
    $data = array_map(function($a){
        return trim($a);
      },$data);


    if(!isset($data['contactNumber']) || 
      !isset($data['lastName']) ||
      !isset($data['firstName']) ||
      !isset($data['middleName']) ||
      !isset($data['password']) ||
      !isset($data['repassword'])
    )
        return [
            "h"=>"Sign Up Failed",
            "m"=>"Please fill all the required fields",
            "s"=>"warning"
        ];
    

    
    if($data['password'] != $data['repassword'])
        return [
            "h"=>"Sign Up Failed",
            "m"=>"Confirm password does not match",
            "s"=>"warning"
        ];


    $field = [];
    $row = [];
    foreach($_POST as $x => $y){
        if($x != 'repassword') {
        $field[] = $x;
        $row[] = $y;
        }
    }
    $cols = implode(",",$field);
    $row = implode("','",$row);

    if(!empty(getContactinfo($data['contactNumber'])))
        return [
            "h"=>"Sign Up Failed",
            "m"=>"Contact number already exist!",
            "s"=>"warning"
        ];
        


    $query = "INSERT INTO users($cols) VALUES ('$row')";
    $result = dbInsert($query);

    if($result)
        return [
            "h"=>"New User",
            "m"=>"You have successfully registered to Parking Parker!",
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Sign Up Failed",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];
        
}


function session($data)
{
    return $_SESSION;
}

function getParkingReservationInformation($data)
{
    if(isset($data['lotId'])){

        $dt = $data['dt'] ? "'".$data['dt'].":00'" : "NOW()";

        return db_getParkingReservationInformation($data['lotId'], $dt);
    }
    return [];
}

function reserveParking($data)
{
    $data = (object) $data;

    if(!$data->lotId)
        return [
            "h"=>"Reserve",
            "m"=>"Please select Parking Place",
            "s"=>"warning"
        ];
    if(!$data->vehicleId)
        return [
            "h"=>"Reserve",
            "m"=>"Please select your vehicle",
            "s"=>"warning"
        ];
    if(!$data->reservationDateTime)
        return [
            "h"=>"Reserve",
            "m"=>"Please select reservation date and time",
            "s"=>"warning"
        ];

    $parkingInfo = (object) db_getParkingReservationInformation($data->lotId);

    if(empty($parkingInfo))
        return [
            "h"=>"Reserve",
            "m"=>"Incorrect Location",
            "s"=>"warning"
        ];

    if(!$parkingInfo->lotSlotAvailable)
        return [
            "h"=>"Reserve",
            "m"=>"No Available parking slot",
            "s"=>"warning"
        ];


    if(db_validateParkingLotAvailability($data->lotId,$data->reservationDateTime))
        return [
            "h"=>"Reserve",
            "m"=>"There is no available parking lot at this hour",
            "s"=>"warning"
        ];

    if(db_checkVehicleIsReserved($data->vehicleId)){
        if(!isset($data->dualReservation))
            return [
                "h"=>"Reserve",
                "m"=>"Vehicle is already reserved with in 1 day. Do you wish to reserve again?",
                "s"=>"info"
            ];

    }


    $reservationCode = hash("sha256",$_SESSION['user']->userId.$data->reservationDateTime.$data->vehicleId.$parkingInfo->lotId.date("Y-m-d H:i:s"));

    $lotAmount = $_SESSION["fixedRate"];

    $hours = $_SESSION['fixedRateHours'] . " hours";

    if($data->schedType == 1){
        unset($data->schedType);
        $data->lotPriceId = null;
    } else {
        $lotPrice = (object) db_getLotRateById($data->lotPriceId);

        if(empty($lotPrice))
            return [
                "h"=>"Reserve",
                "m"=>"Invalid Lot Rate",
                "s"=>"warning"
            ];
            
        $hours = intval($lotPrice->hours) . " hours";
        $lotAmount = $lotPrice->price;
    }

    $dt = new DateTime($data->reservationDateTime);
    $data->startDateTime = $dt->format("Y-m-d H:i:s");
    date_add($dt, date_interval_create_from_date_string($hours));
    $data->endDateTime = $dt->format("Y-m-d H:i:s");


    $request = ppInsertDatabase("reservations",[
        "lotId"=>$parkingInfo->lotId,
        "lotPriceId"=>$data->lotPriceId,
        "vehicleId"=>$data->vehicleId,
        "userId"=>$_SESSION['user']->userId,
        "isPaid"=>0,
        "status"=>0,
        "reservationCode"=>$reservationCode,
        "startDateTime"=>$data->startDateTime,
        "endDateTime"=>$data->endDateTime,
        "reservationDateTime"=>$data->reservationDateTime
    ]);



    if($request)
        return [
            "h"=>"Reserve",
            "m"=>"You have successfully reserved your vehicle with Parking Parker! You have 15mins left to arrive parking location to avoid the expiration of your reservation or Pay Now",
            "s"=>"success",
            "c"=>$reservationCode,
            "a"=>$lotAmount
        ];
}

function getParkingLocations($data)
{
    return db_getParkingLocations($data);
}

function getCurrentActivities()
{
    $session = $_SESSION['user'];
    return db_getCurrentActivities($session->userId);
}

function getUserVehicles(){
    $session = $_SESSION['user'];
    return db_getUserVehicles($session->userId);
}
function addParkingLot($data)
{
    $data = (object) $data;
    if(!isset($data->placeId) || $data->placeId == "")
        return [
            "h"=>"Add Parking Lot",
            "m"=>"Please add available parking lots",
            "s"=>"warning"
        ];

    if(!isset($data->name) || $data->name == "")
        return [
            "h"=>"Add Parking Lot",
            "m"=>"Please enter Parking Name",
            "s"=>"warning"
        ];

    if(!isset($data->address) || $data->address == "")
        return [
            "h"=>"Add Parking Lot",
            "m"=>"Please enter Address",
            "s"=>"warning"
        ];
        

    if(!isset($data->lng) || !isset($data->lat) || $data->lat == 0 || $data->lng == 0)
        return [
            "h"=>"Add Parking Lot",
            "m"=>"Address not Found",
            "s"=>"warning"
        ];

    if(!isset($data->lotSlot))
        return [
            "h"=>"Add Parking Lot",
            "m"=>"Please enter number of slots",
            "s"=>"warning"
        ];
        

    if($data->lotSlot <= 0)
        return [
            "h"=>"Add Lot Failed",
            "m"=>"Slot must be greater than 0",
            "s"=>"warning"
        ];

    $field = [$data->placeId];
    $conn = conn();
    $query = "SELECT * FROM lots WHERE placeId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", ...$field);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    

    if(!empty($db))
        return [
            "h"=>"Add Parking Lot",
            "m"=>"Parking place is already registered in the system.",
            "s"=>"info"
        ];

    $result = ppInsertDatabase("lots",$data);
    if($result)
        return [
            "h"=>"Add Parking Lot",
            "m"=>"You have successfully registered to Parking Parker!",
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Add Parking Lot",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];
    
}


function loadActivityInfoCheckout($data)
{
    $data = (object) db_loadActivityInfo($data["d"]);
    if(!$data)
        return [
            "h"=>file_get_contents(base_url()."../404.php")
        ];

    if($data->isPaid)
        return [
            "h"=>file_get_contents(base_url()."../404.php")
        ];

    return [
        "h"=>file_get_contents(base_url()."../checkout-content.php"),
        "d"=>$data
    ];
}

function loadActivityInfo($data)
{
    $data = (object) db_loadActivityInfo($data["d"]);
    if(!$data)
        return [
            "h"=>file_get_contents(base_url()."../404.php")
        ];

    if(!$data->isPaid)
        return [
            "h"=>file_get_contents(base_url()."../404.php")
        ];


    $qr = makeQR($data->reservationCode);
    return [
        "qr"=>$qr,
        "d"=>$data
    ];
}

function pponApprove($data)
{



    $data = (object) $data;

    $amount =  $data->purchase_units[0]["amount"]['value'];

    $conn = conn();
    $query = "UPDATE reservations
    SET isPaid = 1, amountPaid = ?
    WHERE reservationCode = ?";
    $stmt = $conn->prepare($query); // prepare
    $stmt->bind_param('ds',$amount, $data->reservationCode);
    $result = $stmt->execute(); // execute with data! 
    

    $request = ppInsertDatabase("payments",[
        "paymentChannel"=>"paypal",
        "amount"=>$amount,
        "reservationCode"=>$data->reservationCode
    ]);

    return [
        "h"=>file_get_contents(base_url()."../checkout-confirmed.php"),
        "d"=>$data
    ];
}

function loadParkingInfo($data){

    return getLotInfo($data["id"]);
}

function loadParkingInfoData($data){
    return db_loadParkingInfoData($data["id"]);
}
function loadParkingRates($data){
    return db_loadParkingRates($data);
}
function addParkingRates($data){
    if(!$data)
        return [
            "h"=>"Add Parking Rate",
            "m"=>"Invalid Information",
            "s"=>"warning"
        ];
    foreach($data as $a)
        if(empty($a) || $a == 0) 
            return [
                "h"=>"Add Parking Rate",
                "m"=>"Please fill all fields",
                "s"=>"warning"
            ];
 
    $lotInfo = (object) getLotInfo($data["placeId"]);

    if(empty($lotInfo))
        return [
            "h"=>"Add Parking Rate",
            "m"=>"Parking place not found",
            "s"=>"info"
        ];

    $data["lotId"] = $lotInfo->lotId;

    unset($data["placeId"]);
    
    ppInsertDatabase("lots_prices",$data);

     
    return [
        "h"=>"Add Parking Rate",
        "m"=>"You have successfully added new parking Rate!",
        "s"=>"success",
        'd'=>$lotInfo->lotId
    ];
    
}

function deleteParkingRate($data){
    $delete =  db_deleteParkingRate($data["lotPriceId"]);

    if(!$delete)
        return [
            "h"=>"Delete Parking Rate",
            "m"=>"Failed to Delete Parking Rate",
            "s"=>"warning"
        ];
    else 
        return [
            "h"=>"Delete Parking Rate",
            "m"=>"You have successfully removed parking rate",
            "s"=>"success"
        ];
}

function loadActivities()
{
    return db_loadActivities($_SESSION['user']->userId);
}

function updateProfile($data)
{
    foreach($data as $a){
        if($a == "")
            return [
                "h"=>"Update User",
                "m"=>"Please fill all fields",
                "s"=>"warning"
            ];
    }

    if(db_updateProfile($data))
        return [
            "h"=>"Update User",
            "m"=>"You have successfully updated your information",
            "s"=>"success"
        ];
        
    return [
        "h"=>"Update User",
        "m"=>"Failed to updated user information, please contact administrator",
        "s"=>"warning"
    ];
}

function getRouteURL($data){
    return url_scheme($data["orig"],$data["des"]);
}


function loadVehicles()
{
    $user = $_SESSION["user"]->userId;
    return db_loadVehicles($user);
}

function addVehicle($data)
{
    $data = (object) $data;

    foreach($data as $a)
        if($a == "")
            return [
                "h"=>"Add Vehicle",
                "m"=>"Please fill all fields",
                "s"=>"warning"
            ];


    $user = $_SESSION["user"]->userId;
    $data->userId =  $user;   

    if(count(db_validateAddVehicle($data)))
        return [
            "h"=>"Add Vehicle",
            "m"=>"Vehicle already exists!",
            "s"=>"info",
            "d"=>db_validateAddVehicle($data)
        ];

    $request = ppInsertDatabase("vehicles",(array) $data);
    if($request)
        return [
            "h"=>"Add Vehicle",
            "m"=>"You have successfully add your Vehicle",
            "s"=>"success"
        ];
        
    return [
        "h"=>"Add Vehicle",
        "m"=>"Failed to updated user information, please contact administrator",
        "s"=>"warning"
    ];
    
}

function loadLotRates($data)
{
    if(!isset($data["lotId"]))
        return [];

    return db_loadLotRates($data["lotId"]);
}


function addAdmin($data)
{

    $cols = [];
    $field = [];
    $row = [];
    foreach($data as $a=>$b)
    {
        if(in_array($a,["contactNumber","firstName","lastName","email","password","rePassword"])){
            if(!in_array($a,["rePassword"])){
                $field[] = $a;
                $row[] = $b;
            }

            if($b == "" || $b == null)
                return [
                    "h"=>"Add Admin",
                    "m"=>"Please fill all required fields",
                    "s"=>"warning"
                ];
        }
    }

    $cols = implode(",",$field);
    $row = implode("','",$row);


    $data = (object) $data;

    if($data->password != $data->rePassword)
        return [
            "h"=>"Add Admin",
            "m"=>"Password does not match!",
            "s"=>"warning"
        ];

    unset($data->rePassword);

    if(!empty(getContactinfo($data->contactNumber)))
        return [
            "h"=>"Add Admin",
            "m"=>"Contact number already exist!",
            "s"=>"warning"
        ];

    

    $data = (array) $data;
    $data["userType"] = 1;
    $request = ppInsertDatabase("users",$data);

    if($request)
        return [
            "h"=>"Add Admin",
            "m"=>"You have successfully registered new Admin to Parking Parker!",
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Add Admin",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];
        

}

function adminLock($data)
{
    if($_SESSION['user']->userId == $data["id"])
        return [
            "h"=>"Delete Admin",
            "m"=>"Failed to Delete Admin",
            "s"=>"warning"
        ];

    $isLock = $data['lock'] ? 0 : 1;


    $res = db_adminLock($data["id"],$isLock);

    $m = "Admin Locked! This account cannot be accessed for a while";
    
    if($isLock)
        $m = "Admin Unlocked! This account can now be accessed!";
    
    if($res)
        return [
            "h"=>"Lock/Unlock Admin",
            "m"=>$m,
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Lock/Unlock Admin",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];

}


function adminDelete($data)
{
    if($_SESSION['user']->userId == $data["id"])
        return [
            "h"=>"Delete Admin",
            "m"=>"Failed to Delete Admin",
            "s"=>"warning"
        ];

    $res = db_deleteAdmin($data["id"]);

    if($res)
        return [
            "h"=>"Delete Admin",
            "m"=>"Admin deleted!",
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Delete Admin",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];
}

function loadAdmins()
{
    return db_loadAdmins($_SESSION['user']->userId);
}

function loadGateAccounts($data)
{
    return db_loadGateAccounts($data["placeId"]);
}

function addGateAccount($data)
{
    foreach($data as $a=>$b)
    {
        if(in_array($a,["placeId","contactNumber","firstName","password","rePassword"])){
            if(!in_array($a,["rePassword"])){
            }

            if($b == "" || $b == null)
                return [
                    "h"=>"Add Parking Gate Account",
                    "m"=>"Please fill all required fields",
                    "s"=>"warning"
                ];
        }
    }



    $data = (object) $data;

    if($data->password != $data->rePassword)
        return [
            "h"=>"Add Parking Gate Account",
            "m"=>"Password does not match!",
            "s"=>"warning"
        ];

    unset($data->rePassword);

    if(!empty(getContactinfo($data->contactNumber)))
        return [
            "h"=>"Add Parking Gate Account",
            "m"=>"Contact number already exist!",
            "s"=>"warning"
        ];

    
    $lot = (object) getLotInfo($data->placeId);



    $data = (array) $data;
    $data["userType"] = 2;


    unset($data["placeId"]);

    
    $request = ppInsertDatabase("users",$data,true);
    if($request) {

        $data = [
            "userId"=>$request,
            "lotId"=>$lot->lotId
        ];
        
        $requestFinal = ppInsertDatabase("lots_users",$data);
        
        if($requestFinal)
            return [
                "h"=>"Add Parking Gate Account",
                "m"=>"You have successfully registered new Parking Gate Account to Parking Parker!",
                "s"=>"success"
            ];
            
        return [
            "h"=>"Add Parking Gate Account",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];
    }
    
    return [
        "h"=>"Add Parking Gate Account",
        "m"=>"There is a problem about your information please contact support.",
        "s"=>"warning"
    ];
        
}


function gateAccountLock($data)
{
    $isLock = $data['lock'] ? 0 : 1;


    $res = db_adminLock($data["id"],$isLock);

    $m = "Account Locked! This account cannot be accessed for a while";
    
    if($isLock)
        $m = "Account Unlocked! This account can now be accessed!";
    
    if($res)
        return [
            "h"=>"Lock/Unlock Account",
            "m"=>$m,
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Lock/Unlock Account",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];

}


function gateAccountDelete($data)
{
    $res = db_deleteAdmin($data["id"]);

    if($res)
        return [
            "h"=>"Delete Account",
            "m"=>"Account deleted!",
            "s"=>"success"
        ];
    else 
        return [
            "h"=>"Delete Account",
            "m"=>"There is a problem about your information please contact support.",
            "s"=>"warning"
        ];
}