function uploadFile(form, progressBar)
{
    if(form.length === 0) return;
    if(progressBar.length === 0) return;

    let formData = new FormData(form[0]);
    $.ajax({
        url: 'upload',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        xhr: function()
        {
            let xhr = new XMLHttpRequest();
            console.log(xhr);

            xhr.upload.addEventListener('progress', function (e)
            {
               console.log(e);
            });

            return xhr;
        }
    });
}