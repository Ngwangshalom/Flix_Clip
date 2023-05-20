<?php
session_start();

if (isset($_POST['videoId'])) {
  $videoId = $_POST['videoId'];

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

  // Check if user has already liked the video
  function hasLikedVideo($videoId) {
    return isset($_SESSION['liked_videos'][$videoId]);
  }

  // Increment video likes and update database
  function incrementVideoLikes($videoId) {
    global $conn;
    $sql = "UPDATE videos SET likes = likes + 1 WHERE id = $videoId";
    $conn->query($sql);
  }

  // Decrement video likes and update database
  function decrementVideoLikes($videoId) {
    global $conn;
    $sql = "UPDATE videos SET likes = likes - 1 WHERE id = $videoId";
    $conn->query($sql);
  }

  // Toggle video likes
  function toggleVideoLikes($videoId) {
    if (hasLikedVideo($videoId)) {
      decrementVideoLikes($videoId);
      unset($_SESSION['liked_videos'][$videoId]);
    } else {
      incrementVideoLikes($videoId);
      $_SESSION['liked_videos'][$videoId] = true;
    }

    // Return updated likes count
    $sql = "SELECT likes FROM videos WHERE id = $videoId";
    $result = $conn->query($sql);
    $likes = $result->fetch_assoc()['likes'];

    // echo json_encode(['likes' => $likes]);
    $response = array('likes' => $likes);
    echo json_encode($response);
  }

  // Call the toggleVideoLikes function
  toggleVideoLikes($videoId);

  // Close the database connection
  $conn->close();
}

?>
