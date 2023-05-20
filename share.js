
  document.querySelectorAll('.share-btn').forEach(shareBtn => {
    shareBtn.addEventListener('click', function(event) {
      event.preventDefault();
      const targetId = this.getAttribute('data-bs-target');
      const bottomsheet = document.querySelector(targetId);
      const bs = new bootstrap.BottomSheet(bottomsheet);
      bs.show();
    });
  });
