<?php
ini_set("display_errors", 0);
error_reporting(0);

require_once __DIR__ . '/DbConnection/dbConnect.php';


$user = 'admin';
if ($_REQUEST['cronDate'] && $_REQUEST['cronDate'] !="") {
    $currentDate = $_REQUEST['cronDate'];
} else {
    $currentDate = date('Y-m-d');
}
$ddate = date("ymd");
$currentTime = time();
$time = date('h:i:s A', $currentTime);
$FROM = $currentDate . ", " . $time;
$startDateTime = date('H:i:s');
$cronAt = date('Y-m-d H:i:s');

$values = [];
$pickupCodeArray = [];
$transCount = 0;

$sql = "SELECT reg.region_name, lm.loc_id , lm.location , sh.shop_id , sh.service_type , sh.shop_name , sc.pri_ce , sc.sec_ce , rc.ce_name , rc.mobile1 , rc.mobile2 , cldet.client_name , cudet.cust_name
        FROM region_master reg 
		INNER JOIN radiant_location rl ON reg.region_id=rl.region_id 
		INNER JOIN location_master lm ON rl.location_id=lm.loc_id
		INNER JOIN shop_details sh ON lm.loc_id = sh.location
		INNER JOIN shop_cemap sc ON sh.shop_id = sc.shop_id
		INNER JOIN radiant_ce rc ON sc.pri_ce = rc.ce_id
		INNER JOIN cust_details cudet ON sh.cust_id = cudet.cust_id
		INNER JOIN client_details cldet ON cudet.client_id = cldet.client_id
		WHERE sh.shop_id NOT IN (SELECT dt.pickup_code FROM daily_trans dt where dt.pickup_date ='" . $currentDate . "' AND (dt.type='Pickup' OR dt.type='Delivery') AND dt.status='Y') AND reg.status='Y' AND rl.status='Y' AND lm.status='Y' AND sh.status='Y' AND sc.status='Y' AND rc.status='Y' AND cudet.status='Y' AND cldet.status='Y' AND sh.pickup_type='Beat' AND sh.point_type='Active'
        order by reg.region_name Asc;";

$query = mysqli_query($writeConnection,$sql);
$rowsReturn = mysqli_num_rows($query);

if ($query) {
    if ($rowsReturn > 0) {

        $transNoQuery = mysqli_query($writeConnection,"SELECT COUNT(trans_id) AS trans_count FROM daily_trans where pickup_date = '" . $currentDate . "'");
        $transCount = mysqli_fetch_array($transNoQuery)['trans_count'];

        if ($transCount == 0) $transCount++;

        while ($row = mysqli_fetch_array($query)) {
            if($row['service_type']=="Cash Pickup" || $row['service_type']=="Cash Delivery" || $row['service_type']=="Both" 
            || $row['service_type']=="Cheque Pickup"){
                $transNo = sprintf('%05d.%s', $transCount, $ddate);
                $tregion = $row['region_name'];
                $type = ($row['service_type'] == "Cash Pickup" || $row['service_type'] == "Both" || $row['service_type'] == "Cheque Pickup") ? "Pickup" : "Delivery";
    
    
                if(!in_array($row['shop_id'],$pickupCodeArray)){
                    $pickupCodeArray[] = $row['shop_id'];
                    $values[] = "('" . $transNo . "', '" . $row['region_name'] . "', '" . $row['location'] . "', '" . $row['client_name'] . "', '" . $row['cust_name'] . "', '" . $row['shop_id'] . "', '" . $row['shop_name'] . "', '0', '" . $type . "', '', '" . $row['pri_ce'] . "', '" . $row['mobile1'] . "', '" . $row['mobile2'] . "', '" . $user . "', '" . $FROM . "', '" . $currentDate . "', 'Y')";
                    $transCount++;
                }
            }
        }
        $transCount--;

        $sql4 = "INSERT INTO daily_trans (trans_no, region, location, client_name, cust_name, pickup_code, pickup_name, pickup_amount, type, client_code, staff_id, mobile1, mobile2, sent_by, sms_sent, pickup_date, status) VALUES " . implode(',', $values);
        $query_run = mysqli_query($writeConnection,$sql4);

        if ($query_run) {
            $statusCode = "200";
            $state = "Success";
            $message = "Beat Created";
        } else {
            $statusCode = "501";
            $state = "Failure";
            $message = mysqli_error($writeConnection);
        }
    } else {
        $statusCode = "201";
        $state = "Success";
        $message = "No Data to insert";
    }
} else {
    $statusCode = "501";
    $state = "Failure";
    $message = mysqli_error($writeConnection);
}

$endDateTime = date('H:i:s');
$duration = $startDateTime . ' - ' . $endDateTime;

$responseArray = [
    "statusCode" => $statusCode,
    "state" => $state,
    "message" => $message,
    "date" => $currentDate,
    "time" => $time,
    "count" => $transCount
];

$responseData = json_encode($responseArray, true);

$logQuery = mysqli_query($writeConnection,"INSERT INTO `rcms_cron_logs` (`id`, `cron_name`, `cron_at`, `duration`, `status`) VALUES (NULL, 'DailyBeat', '" . $cronAt . "', '" . $duration . "', '" . $responseData . "')");

echo $responseData;

?>