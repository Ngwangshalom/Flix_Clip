document.querySelectorAll('.like-btn').forEach(likeBtn => {
    likeBtn.addEventListener('click', function(event) {
      event.preventDefault();
      const videoId = this.getAttribute('data-video-id');
      const isLiked = this.classList.contains('liked');
  
      // Send an AJAX request to like.php
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'like.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const likeCountElement = likeBtn.querySelector('span');
  
            if (likeCountElement) {
              if (isLiked) {
                // Decrement the like count
                likeCountElement.textContent = response.likeCount;
              } else {
                // Increment the like count
                likeCountElement.textContent = response.likeCount;
              }
            }
  
            // Toggle the liked class
            likeBtn.classList.toggle('liked');
          } else {
            console.error('Error: ' + xhr.status);
          }
        }
      };
  
      // Prepare the request data
      const requestData = {
        videoId: videoId,
        action: isLiked ? 'remove' : 'add'
      };
  
      // Send the request
      xhr.send(JSON.stringify(requestData));
    });
  });
  