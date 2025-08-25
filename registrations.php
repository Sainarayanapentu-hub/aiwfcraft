<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name       = $_POST['name'];
    $fathername = $_POST['fathername'];
    $gender     = $_POST['gender'];
    $dob        = $_POST['dob'];
    $contact    = $_POST['contact'];
    $address    = $_POST['address'];
    $dist_mandal = $_POST['dist_mandal']; 
    $occupation = $_POST['occupation'];
    $membership = $_POST['membership'];

    // Handle file upload (photo)
    $photoName = "";
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if not exists
        }
        // unique name for photo
        $photoName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photoName;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    }
    // Save to MySQL database
    $conn = new mysqli('localhost','root','', 'aiwf');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("INSERT INTO registration 
    (name, fathername, gender, dob, contact, address, dist_mandal, occupation, membership, photo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", 
    $name, $fathername, $gender, $dobs, $contact, $address, 
    $dist_mandal, $occupation, $membership, $photoName
    );

        if($stmt->execute()){
            $message = " విజయవంతంగా నమోదు అయ్యారు ";
        } else {
            $message = "పొరపాటు జరిగింది: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<?php if(!empty($message)): ?>
    <div style="padding:15px; margin:15px; background:#e8f5e9; border:1px solid #2e7d32; color:#2e7d32; display:flex; align-items:center; gap:10px;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/Yes_check.svg/1024px-Yes_check.svg.png" 
             alt="Success" width="30" height="30" />
        <span><?php echo $message; ?></span>
    </div>
<?php endif; ?>