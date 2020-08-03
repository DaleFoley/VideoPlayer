function loadSelectedVideo(element, host)
{
    let videoID = element.id;
    $('video').attr('src', host + '/videos/index/' + videoID);
}