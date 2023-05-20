function toggleCommentForm() {
  const bottomSheet = document.querySelector('.bottom-sheet');
  bottomSheet.classList.toggle('open');
}

  
  // Function to handle comment submission
  function handleCommentSubmit(event) {
    event.preventDefault();
  
    const commentInput = document.querySelector('.comment-form input[name="comment"]');
    const commentText = commentInput.value.trim();
    const videoId = document.querySelector('.video-container').getAttribute('data-video-id'); // Get the video ID
  
    if (commentText !== '') {
      // Send an AJAX request to comment.php
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'comment.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            displayComment(response.comment); // Display the comment in real-time
          } else {
            console.error('Error: ' + xhr.status);
          }
        }
      };
  
      // Prepare the request data
      const requestData = {
        comment: commentText,
        videoId: videoId // Add the video ID to the request data
      };
  
      // Send the request
      xhr.send(JSON.stringify(requestData));
  
      // Reset the comment input
      commentInput.value = '';
    }
  }
  
  // Function to display the comment in real-time
  function displayComment(comment) {
    const commentsList = document.querySelector('.comments-list');
  
    // Create a new comment item
    const commentItem = document.createElement('li');
    commentItem.textContent = comment;
  
    // Add the comment item to the comments list
    commentsList.appendChild(commentItem);
  }
  
  // Event listeners
  document.querySelector('.comment-btn').addEventListener('click', toggleCommentForm);
  // Event listener for the comment button
  const commentForm = document.querySelector('.comment-form');
if (commentForm) {
  commentForm.addEventListener('submit', handleCommentSubmit);
}
