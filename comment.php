<?php
// Retrieve the user session or any necessary authentication checks

// Retrieve the comment and video ID from the request
$comment = $_POST['comment'];
$videoId = $_POST['videoId'];

// Perform any necessary data validation or sanitization

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "whats_shorts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to insert the comment
$sql = "INSERT INTO comments (user_id, video_id, comment) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $userId, $videoId, $commentText);

// Replace $userId with the user's ID associated with the comment
$userId = "1";
$commentText = $comment;

$stmt->execute();

// Check if the comment is successfully inserted
if ($stmt->affected_rows > 0) {
    // Retrieve the last inserted comment ID
    $commentId = $stmt->insert_id;

    // Prepare and execute the SQL query to fetch the inserted comment
    $sql = "SELECT comment_text FROM comments WHERE comment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commentId);

    $stmt->execute();

    // Fetch the comment
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $commentText = $row['comment_text'];

    // Prepare the response array
    $response = array(
        'comment' => $commentText
    );

    // Convert the response to JSON and send it back
    echo json_encode($response);
} else {
    // Handle any errors or send an appropriate response
    echo "Error: Failed to insert the comment.";
}

// Close the database connection
$conn->close();
?>
