function loadSelectedVideo(element)
{
    let videoID = element.id;
    $('video').attr('src', 'http://localhost:8000/videos/index/' + videoID);
}