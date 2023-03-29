<?php

session_start();

require_once 'globalFn.php';


function conn(){
    $c = new mysqli('localhost', 'root', '', 'parkingparker');
    
    if ($c->connect_error) {
        return die("Connection failed: " . $c->connect_error);
    }
    return $c;
}



function dbSelect($query)
{

    $conn = conn();
    
    $sql = $query;
    $result = $conn->query($sql);

    $data = [];

    if ($result->num_rows > 0) 
        while($row = $result->fetch_assoc()) 
            $data = $row;

    $conn->close();
    return $data;
}

function dbInsert($query,$params = [])
{
    $conn = conn();
    $result = 0;

    if ($conn->query($query) === TRUE) 
        $result = 1;
    
    $conn->close();
    return $result;
}



function getContactinfo($contactNumber)
{
    
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM users WHERE contactNumber = ?"); 
    $stmt->bind_param("s", $contactNumber);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $result = $result->fetch_assoc(); // fetch data 
    return $result;
}

function db_getParkingLocations($data)
{

    if(!isset($data["search"]))
        return [];
        
    $data = "%".$data["search"]."%";

    $conn = conn();
    $query = "SELECT * FROM lots WHERE name like ? or address like ?";
    $stmt  = $conn->prepare($query);
    
    $stmt->bind_param("ss", $data,$data);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;
}

function db_getVehicleInfo($data,$isArray = false){
    if(!isset($data))
        return [];

    $conn = conn();
    $query = "SELECT * FROM vehicles WHERE vehicleId = ? ";
    $stmt  = $conn->prepare($query);
    
    $stmt->bind_param("ss", $data,$data);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    if($isArray)
        $db = $result->fetch_all(MYSQLI_ASSOC);
    else
        $db = $result->fetch_assoc(); // fetch data 

    $conn->close();
    return $db;
}

function db_checkVehicleIsReserved($vehicleId) {
    if(!$vehicleId)
        return [];

    $conn = conn();
    $query = "SELECT COUNT(vehicleId) AS reserved FROM reservations 
        WHERE vehicleId = ?
        AND NOW() BETWEEN reservationDateTime AND DATE_ADD(reservationDateTime, INTERVAL 1 DAY)
    ";
    $stmt  = $conn->prepare($query);
    $date = date("Y-m-d");
    $stmt->bind_param("i",$vehicleId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_assoc(); // fetch data 
    $conn->close();
    return $db["reserved"];
}

function db_loadActivityInfo($data)
{
    if(!isset($data))
        return [];

    $conn = conn();
    $query = "SELECT 
        c.name lotName,
        c.address lotAddress,
        a.reservationDT,
        a.reservationDateTime,
        a.startDateTime,
        a.endDateTime,
        a.isPaid,
        b.plateNum,
        b.vehicleType,
        a.reservationCode,
        CONCAT(b.vehicleBrand, ' ', b.vehicleModel) as vehicleName,
        CONCAT(d.firstName, ' ', d.lastName) as userName,
        e.price,
        a.amountPaid
    FROM reservations a 
    INNER JOIN vehicles b ON a.vehicleId = b.vehicleId
    INNER JOIN lots c ON a.lotId = c.lotId
    INNER JOIN users d ON a.userId = d.userId
    LEFT JOIN lots_prices e ON a.lotPriceId = e.lotPriceId
    WHERE a.reservationCode = ? ";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $data);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $result = $result->fetch_assoc(); // fetch data 
    $conn->close();
    return $result ? $result : [];
}


function db_validateParkingLotAvailability($lotId,$dt)
{
    
    if(!$lotId)
        return [];
        
    $conn = conn();
    $query = "SELECT 
                a.*,
                COUNT(reservationId) as lotSlotReserved, 
                lotSlot - COUNT(reservationId) AS lotSlotAvailable
            FROM lots a
            LEFT JOIN reservations b ON a.lotId = b.lotId AND 
                CASE 
                    WHEN b.isPaid = 0 AND  '$dt' BETWEEN b.startDateTime AND DATE_ADD(b.startDateTime, INTERVAL 15 MINUTE)THEN 
                        FALSE 
                    ELSE 
                        '$dt'BETWEEN b.startDateTime AND b.endDateTime
                END
            WHERE a.lotId = ?
            GROUP BY a.lotId
            HAVING lotSlotAvailable > 0";
    $stmt  = $conn->prepare($query);
    $dt = date("Y-m-d H:i:s");
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $result = $result->fetch_assoc(); // fetch data 
    if(empty($result))
        return true;

    return false;
        
}

function db_getParkingReservationInformation($data,$dt = "'NOW()'")
{

    if(!$data)
        return [];
        
    $conn = conn();
    $query = "SELECT 
                a.*,
                COUNT(reservationId) as lotSlotReserved, 
                lotSlot - COUNT(reservationId) AS lotSlotAvailable
            FROM lots a
            LEFT JOIN reservations b ON a.lotId = b.lotId AND 
                CASE 
                    WHEN b.isPaid = 0 AND  $dt BETWEEN b.startDateTime AND DATE_ADD(b.startDateTime, INTERVAL 15 MINUTE)THEN 
                        FALSE 
                    ELSE 
                        $dt BETWEEN b.startDateTime AND b.endDateTime
                END
            WHERE a.lotId = ?
            GROUP BY a.lotId";
    $stmt  = $conn->prepare($query);
    $dt = date("Y-m-d H:i:s");
    $stmt->bind_param("i", $data);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $result = $result->fetch_assoc(); // fetch data 
    return $result;
}

function db_getUserVehicles($id)
{
    if(!$id)
        return [];
        
    $conn = conn();
    $query = "SELECT * FROM vehicles WHERE userId = ?";
    $stmt  = $conn->prepare($query);
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;
}

function db_getCurrentActivities($userId)
{
    if(!$userId)
        return [];
        
    $dt = date("Y-m-d H:i:s");
    $conn = conn();
    $query = "SELECT 
            a.*,
            b.plateNum,
            b.vehicleBrand,
            b.vehicleModel,
            c.name,
            c.lng,
            c.lat,
            c.address,
            DATE_ADD(reservationDateTime, INTERVAL 15 MINUTE)
        FROM reservations a 
        INNER JOIN vehicles b ON a.vehicleId = b.vehicleId
        INNER JOIN lots c ON a.lotId = c.lotId
        WHERE a.userId = ?
        AND CASE a.isPaid 
            WHEN 1 THEN 
                CASE a.status 
                    WHEN 1 THEN FALSE
                    ELSE  NOW() BETWEEN a.startDateTime and endDateTime
                END 
            ELSE 
                NOW() BETWEEN a.startDateTime AND DATE_ADD(a.startDateTime, INTERVAL 15 MINUTE)
            END
        ORDER BY a.isPaid DESC, a.startDateTime ASC";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;
}


function getLotInfo($placeId)
{
    
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM lots WHERE placeId = ?"); 
    $stmt->bind_param("s", $placeId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $result = $result->fetch_assoc(); // fetch data 
    $conn->close();
    return $result;
}

function db_loadParkingInfoData($userId)
{
    if(!$userId)
        return [];
        
    $dt = date("Y-m-d H:i:s");
    $conn = conn();
    $query = "SELECT 
        a.*,
        CONCAT(DATE_FORMAT(startDateTime,'%m/%d/%y %H:%i'), ' - ', DATE_FORMAT(endDateTime, '%H:%i')) startEnd, 
        b.plateNum,
        c.price
    FROM reservations a 
    INNER JOIN vehicles b ON a.vehicleId = b.vehicleId
    LEFT JOIN lots_prices c ON a.lotId = c.lotId
    WHERE a.lotId = ? AND a.isPaid = 1 ORDER BY a.reservationDT DESC";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;
}

function db_loadParkingRates($data){
    
    if(!$data)
        return [];
        
    $conn = conn();
    $query = "SELECT * FROM lots_prices WHERE lotId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $data);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;
}
function db_loadActivities($user){
    if(!$user)
        return [];
        
    $conn = conn();
    $query = "SELECT a.*, 
        c.name placeName , 
        c.address placeAddress, 
        b.vehicleBrand, 
        b.vehicleModel, 
        b.plateNum 
    FROM parkingparker.reservations a 
    INNER JOIN vehicles b ON a.vehicleId = b.vehicleId
    INNER JOIN lots c ON a.lotId = c.lotId
    WHERE a.userId = ?
    ORDER BY reservationDT DESC";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;

}

function db_loadVehicles($user)
{
    if(!$user)
        return [];
        
    $conn = conn();
    $query = "SELECT * FROM vehicles WHERE userId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;  
}

function db_validateAddVehicle($data)
{
    $userId = $data->userId;
    $plateNum = $data->plateNum;
    $vehicleType = $data->vehicleType;
    $vehicleBrand = $data->vehicleBrand;
    $vehicleModel = $data->vehicleModel;

    $conn = conn();
    $query = "SELECT * FROM vehicles WHERE 
       userId = ? AND
       plateNum = ? AND
       vehicleType = ? AND
       vehicleBrand = ? AND
       vehicleModel = ?
    ";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("issss", $userId,$plateNum,$vehicleType,$vehicleBrand,$vehicleModel);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;  
}

function db_updateProfile($data){
    $conn = conn();
    $set = [];
    foreach($data as $a=>$b)
        $set[] = "$a = '$b'";

    $set = implode(",",$set);
    $query = "UPDATE users 
        SET $set
    WHERE userId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user']->userId);
    return $stmt->execute();
}

function db_adminLock($userId,$isLocked){
    $conn = conn();
    $query = "UPDATE users 
        SET isLocked = $isLocked
    WHERE userId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}


function db_deleteParkingRate($lotPriceid) {
        
    $conn = conn();
    $query = "DELETE FROM lots_prices WHERE lotPriceId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $lotPriceid);
    return $stmt->execute();
}

function db_deleteAdmin($userId) {
        
    $conn = conn();
    $query = "DELETE FROM users WHERE userId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $query = "DELETE FROM lots_users WHERE userId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}


function ppInsertDatabase($table,$params = [],$lastId = false) {
    $conn = conn();
    $result = 0;

    $cols = [];
    $vals = [];
    $vals_p = [];

    $cnt = 0;
    foreach($params as $key=>$val){
        $cols[$cnt] = $key;
        $vals[$cnt] = $val;
        $vals_p[$cnt] = "?";
        $cnt++;   
    }
    
    $cols = implode(",",$cols);
    $vals_p = implode(",",$vals_p);

    $query = "INSERT INTO $table ($cols) VALUES ($vals_p)";
    $stmt = $conn->prepare($query); // prepare
    $result = $stmt->execute($vals); // execute with data! 
    
    if($lastId){
        $lastId =  $conn->insert_id;
        $conn->close();
        return $lastId;
    }

     


    return $result;
}

function ppUpdateDatabase($table,$params = []) {
    $conn = conn();
    $result = 0;

    $cols = [];
    $vals = [];
    $vals_p = [];

    $cnt = 0;
    foreach($params as $key=>$val){
        $cols[$cnt] = $key;
        $vals[$cnt] = $val;
        $vals_p[$cnt] = "?";
        $cnt++;   
    }
    
    $cols = implode(",",$cols);
    $vals_p = implode(",",$vals_p);

    $query = "INSERT INTO $table ($cols) VALUES ($vals_p)";
    $stmt = $conn->prepare($query); // prepare
    $result = $stmt->execute($vals); // execute with data! 
    
    $conn->close();
    return $result;
}


function db_loadLotRates($lotId)
{
    if(!$lotId)
        return [];

    // return $lotId;
        
    $conn = conn();
    $query = "SELECT * FROM lots_prices WHERE lotId = ? ORDER BY price ASC";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;  
}

function db_getLotRateById($id)
{
    
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM lots_prices WHERE lotPriceId = ?"); 
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $result = $result->fetch_assoc(); // fetch data 
    $conn->close();
    return $result;
}


function db_loadAdmins($user){
        
    $conn = conn();
    $query = "SELECT *,
        CASE $user
            WHEN userId THEN 'disabled'
            ELSE ''
        END as _action
    FROM users 
    WHERE userType = 1";
    $stmt  = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;

}

function db_loadGateAccounts($placeId){
        
    $conn = conn();
    $query = "SELECT a.* FROM users a
    INNER JOIN lots_users b ON a.userId = b.userId
    INNER JOIN lots c ON b.lotId = c.lotId
    WHERE a.userType = 2 AND
    c.placeId = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $placeId);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $db = $result->fetch_all(MYSQLI_ASSOC); 
    $conn->close();
    return $db;

}


