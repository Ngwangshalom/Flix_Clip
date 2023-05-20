<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TikTok</title>
  <script src="https://kit.fontawesome.com/1a015cf62c.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css">
  <!-- Include Video.js stylesheet -->
  <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet" />

  <style>
  
  </style>
</head>
<body>
 

  <main>
  

    <div class="right">
      <?php
      session_start();

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
      }

      // Fetch video data from the database
      $sql = "SELECT * FROM videos";
      $result = $conn->query($sql);

      // Check if videos are found
      if ($result->num_rows > 0) {
        // Fetch all videos into an array
        $videos = [];
        while ($row = $result->fetch_assoc()) {
          $videos[] = $row;
        }

        // Shuffle the videos array
        shuffle($videos);

        // Loop through the shuffled videos and generate HTML for each video
        foreach ($videos as $video) {
          $videoId = $video['id'];
          $videoUrl = $video['url'];
          $videoLikes = $video['likes'];
          $videoComments = $video['comments'];
          $videoShares = $video['shares'];
          $username = $video['username'];
          $caption = $video['caption'];
          $avatarUrl = $video['avatar_url'];

          $isLiked = hasLikedVideo($videoId);
          ?>
          <div class="post" align="center">
            <div class="post-info">
              <div class="user">
                <img src="<?php echo $avatarUrl; ?>" alt="avatar">
                <div>
                  <h6><?php echo $username; ?></h6>
                  <p><?php echo $caption; ?></p>
                </div>
              </div>
              <button>Upload</button>
               <button>Follow</button>
            </div>
            <div class="post-content">
              <div class="video-container">
                <!-- Use the <video> element directly -->
                <video class="video__player" width="100%" height="auto" autoplay loop unmuted>
                  <source src="<?php echo $videoUrl; ?>" type="video/mp4">
                </video>
              </div>
              <div class="video-icons">
              <a href="#" class="like-btn <?php if ($isLiked) echo 'liked'; ?>" data-video-id="<?php echo $videoId; ?>">
  <i class="fas fa-heart fa-lg"></i>
  <span><?php echo $videoLikes; ?></span>
</a>

                <a href="#"><i class="fas fa-comment-dots fa-lg"></i><span><?php echo $videoComments; ?></span></a>
                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($video['url']); ?>" target="_blank"class="share-btn" data-bs-toggle="bottomsheet" data-bs-target="#share-bottomsheet-<?php echo $videoId; ?>">
  <i class="fas fa-share fa-lg"></i><span><?php echo $videoShares; ?></span>
</a>

              </div>
            </div>
          </div>
          
          <?php
        }
      } else {
        // No videos found
        echo "<p>No more videos.</p>";
      }

      // Close the database connection
      $conn->close();
      ?>
    </div>
  </main>

  <script src="https://vjs.zencdn.net/7.15.4/video.js"></script>
  <script type="text/javascript" src="like.js"></script>

  <script type="text/javascript" src="script.js"></script>

<script>
  const videos = document.querySelectorAll('.video__player');

videos.forEach(video => {
let playing = false;
let observer = new IntersectionObserver(entries => {
entries.forEach(entry => {
if (entry.intersectionRatio >= 0.5 && entry.intersectionRatio < 0.8 && playing) {
  video.pause();
  playing = false;
} else if (entry.intersectionRatio >= 0.8 && !playing && entry.isIntersecting) {
  video.play();
  playing = true;
} else if ((entry.intersectionRatio < 0.5 || !entry.isIntersecting) && playing) {
  video.pause();
  playing = false;
}
});
}, { threshold: [0.7, 0.8] });

observer.observe(video);
});
</script>
</body>
</html>
