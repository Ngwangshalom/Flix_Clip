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