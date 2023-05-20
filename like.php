<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "whap_shorts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the video ID and action from the request
$requestData = json_decode(file_get_contents('php://input'), true);
$videoId = $requestData['videoId'];
$action = $requestData['action'];

// Perform the necessary action based on the request
if ($action === 'add') {
  // Check if the user has already liked the video
  session_start();
  $hasLikedVideo = isset($_SESSION['liked_videos'][$videoId]);
  
  if (!$hasLikedVideo) {
    // User has not liked the video, so increment the like count
    $sql = "UPDATE videos SET likes = likes + 1 WHERE id = $videoId";
    $conn->query($sql);
    
    // Update the session to mark the video as liked
    $_SESSION['liked_videos'][$videoId] = true;
  }

  // Get the updated like count
  $sql = "SELECT likes FROM videos WHERE id = $videoId";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $likeCount = $row['likes'];

  // Prepare the response data
  $response = array('likeCount' => $likeCount);
  echo json_encode($response);
} elseif ($action === 'remove') {
  // Check if the user has already liked the video
  session_start();
  $hasLikedVideo = isset($_SESSION['liked_videos'][$videoId]);
  
  if ($hasLikedVideo) {
    // User has liked the video, so decrement the like count
    $sql = "UPDATE videos SET likes = likes - 1 WHERE id = $videoId";
    $conn->query($sql);
    
    // Update the session to mark the video as unliked
    unset($_SESSION['liked_videos'][$videoId]);
  }

  // Get the updated like count
  $sql = "SELECT likes FROM videos WHERE id = $videoId";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $likeCount = $row['likes'];

  // Prepare the response data
  $response = array('likeCount' => $likeCount);
  echo json_encode($response);
}

// Close the database connection
$conn->close();
?>
