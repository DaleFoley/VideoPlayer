function uploadFile(form, progressBar, completeLabel, submitButton, url)
{
    if(form == null) return false;
    if(progressBar == null) return false;
    if(url == null) return false;

    if(form.length === 0) return false;
    if(progressBar.length === 0) return false;

    let isDisplayCompletionText = completeLabel != null;
    let isSubmitButtonPresent = submitButton != null;

    let formData = new FormData(form);

    $(progressBar).attr('aria-valuenow', 0);
    $(progressBar).css('width', 0);
    $(progressBar).prop('class', 'progress-bar');

    if(isDisplayCompletionText)
    {
        $(completeLabel).css('color', 'black');
        $(completeLabel).text('');
    }

    if(isSubmitButtonPresent)
    {
        $(submitButton).prop('disabled', 'true');
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        xhr: function()
        {
            let xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', function (e)
            {
                let percentageComplete = Math.round((e.loaded / e.total) * 100);
                $(progressBar).attr('aria-valuenow', percentageComplete);
                $(progressBar).css('width', percentageComplete + '%');

                if(isDisplayCompletionText)
                {
                    $(completeLabel).text(percentageComplete + '% complete');
                }
            });

            xhr.upload.addEventListener('loadend', function()
            {
                $(progressBar).prop('class', 'progress-bar bg-success');
                if(isDisplayCompletionText)
                {
                    $(completeLabel).css('color', 'white');
                    $(completeLabel).text('Finished Uploading');
                }

                if(isSubmitButtonPresent)
                {
                    $(submitButton).prop('disabled', '');
                }
            });

            return xhr;
        }
    });
}