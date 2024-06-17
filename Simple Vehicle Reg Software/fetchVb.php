<?php
include("database.php");

// Fetch vehicle brands and types from the database
$sql = "SELECT * FROM vehicle_brand";
$result = mysqli_query($conn, $sql);

$vehicleBrandsByType = [];

while ($row = mysqli_fetch_assoc($result)) {
    $vehicleBrandsByType[$row['vehicle_type']][] = $row['brand_name'];
}

mysqli_close($conn);
?>

