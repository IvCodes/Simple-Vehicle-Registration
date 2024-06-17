<?php

include("fetchVb.php");

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Registration</title>
</head>
<body>
    <h1>Register your Vehicle</h1>
    <hr>

<h2>Vehicle Registration Form</h2>
    <form action="index.php" method="post" onsubmit="return validateForm()">
        <label for="owner_name">Owner Name:</label>
        <input type="text" id="owner_name" name="owner_name" required><br>
        <br>

        <label for="nic_number">NIC Number:</label>
        <input type="text" id="nic_number" name="nic_number" required><br>
        <br>

        <label for="registration_year">Registration Year(Vehicle):</label>
        <input type="date" id="registration_year" name="reg_year" required><br>
        <br>

        <label for="owner_address">Owner Address:</label> <br>
        <textarea id="owner_address" name="owner_address" rows="4" required></textarea><br>
        <br>

        <label for="chassis_number">Chassis Number:</label>
        <input type="text" id="chassis_number" name="chassis_number" pattern="[A-HJ-NPR-Z0-9]{17}" required><br>
        <small>Enter a 17-character alphanumeric chassis number (excluding I, O, Q)</small> <br> <br>
      
        <label>Vehicle Type:</label><br>
        <select id="vehicleType" name="vehicleType" onchange="updateTestBrands()">
            <option value ="00">Select option</option>
            <option value="01">Type 01(CAR)</option>
            <option value="02">Type 02(SUV)</option>
            <option value="03">Type 03(TRUCK)</option>
            <option value="04">Type 04(BIKE)</option>

        </select>
        <br><br>

        <label>Vehicle Brands:</label><br>
        <div id="testBrandsContainer">
            <!-- populated dynamically -->
        </div>

        <br>

        <button type="submit">Submit</button>

        <br>
        <br>


    </form>
    <script>
        
    const vehicleBrandsByType = <?php echo json_encode($vehicleBrandsByType); ?>;

    function updateTestBrands() {
        const selectedType = document.getElementById("vehicleType").value;
        const testBrandsContainer = document.getElementById("testBrandsContainer");
        testBrandsContainer.innerHTML = ""; 

        if (selectedType in vehicleBrandsByType) {
            vehicleBrandsByType[selectedType].forEach(brand => {
                const brandCheckbox = document.createElement("input");
                brandCheckbox.type = "checkbox";
                brandCheckbox.name = "vehicle_brands[]";
                brandCheckbox.value = brand;
                brandCheckbox.id = brand;

                const brandLabel = document.createElement("label");
                brandLabel.htmlFor = brand;
                brandLabel.textContent = ` ${brand}`;

                testBrandsContainer.appendChild(brandCheckbox);
                testBrandsContainer.appendChild(brandLabel);
                testBrandsContainer.appendChild(document.createElement("br"));
            });

        } 
    }

    function validateForm() {
        const selectedBrands = document.querySelectorAll('input[name="vehicle_brands[]"]:checked');
        if (selectedBrands.length === 0) {
            alert("Please select at least one vehicle brand.");
            return false; 
        }
        return true;
    }

    

    // Calling the updatetest brand function
    updateTestBrands();
</script>

       
</body>

</html>

<?php 
include("database.php");

//using server SGB to get the post method


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form

    //adding sanitizing and validation to prevent cross site scripting 

    $owner_name = htmlspecialchars($_POST["owner_name"], ENT_QUOTES, 'UTF-8');
    $nic_number = htmlspecialchars($_POST["nic_number"], ENT_QUOTES, 'UTF-8');
    $registration_year = $_POST["reg_year"]; 
    $owner_address = htmlspecialchars($_POST["owner_address"], ENT_QUOTES, 'UTF-8');
    $chassis_number = filter_var($_POST["chassis_number"], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-HJ-NPR-Z0-9]{17}$/")));
    $vehicle_type = $_POST["vehicleType"];
    $vehicle_brands = implode(",",$_POST["vehicle_brands"]);

    // var_dump($vehicle_brands);

    // Validation
    $errors = [];

    if (empty($owner_name)) {
        $errors[] = "Owner name is required.";
    }

   // Validate NIC number
   $nic_number = isset($_POST["nic_number"]) ? $_POST["nic_number"] : "";

   if (strlen($nic_number) === 12) {
    // Valid NIC number with exactly 12 characters
} else {
    $errors[] = "NIC number should have exactly 10 characters.";
}


    if (empty($owner_address)) {
        $errors[] = "Owner address is required.";
    }

    if (!$chassis_number) {
        $errors[] = "Chassis number is invalid or empty.";
    }

    if (empty($vehicle_type) || $vehicle_type === "00") {
        $errors[] = "Please select a valid vehicle type.";
    }

    if (empty($vehicle_brands)) {
        $errors[] = "Please select at least one vehicle brand.";
    }

    // If there are no errors, proceed to database insertion
    if (empty($errors)) {
        $sql = "INSERT INTO owners (owner_name, nic_number, reg_year, owner_address, chassis_number, vehicle_type, vehicle_brand) 
                VALUES ('$owner_name', '$nic_number', '$registration_year', '$owner_address', '$chassis_number', '$vehicle_type', ' $vehicle_brands')";

        try {
            mysqli_query($conn, $sql);
            echo "Vehicle is now registered.";
        } catch (mysqli_sql_exception $exception) {
            echo "Error: " . $exception->getMessage();
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}

mysqli_close($conn);
?>


<footer>
    <hr>

    <br>
    <p>© Vehicle Registration 2023</p>
    <p><a href="mailto:easaraivanjaya24@gmail.com">Email</a></p>
    
</footer>
