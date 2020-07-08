//This function takes advantage of the simplicity of the FormData API for uploading.
//This is less flexible than pure AJAX, and the result cannot be stringified.
function uploadFileFormData(form, progressBar, completeLabel, submitButton, url)
{
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

    return $.ajax({
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

//TODO: Add different ways to upload file data, i.e pure AJAX as opposed to using FormData API.
function uploadFile(form, progressBar, completeLabel, submitButton, url)
{
    return uploadFileFormData(form, progressBar, completeLabel, submitButton, url);
}