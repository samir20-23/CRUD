<?php
// Database connection parameters
$host = "localhost";
$user = "SAMIR";
$pass = "samir123";
$dbname = "booking";
$tbname = "tour";
$dsn = "mysql:host=$host;dbname=$dbname";

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to select the image
    $sql = "SELECT tour_img  FROM $tbname WHERE tour_id = :tour_id";
    $stmt = $pdo->prepare($sql);

    // Bind the tour_id parameter
    $tour_id = 58; // Example tour_id
    $stmt->bindParam(':tour_id', $tour_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Assuming the image is stored as binary data in the tour_img column
        $tour_img = $row['tour_img'];

        // Display the image
        echo '<img src="data:image/jpeg;base64,'.base64_encode($tour_img).'" alt="Tour Image"/>';
    } else {
        echo "No image found for the given tour_id.";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
