$(document).ready(function()
{
    let formElement;
    let progressBarElement;
    let completeLabel;
    let submitButton;

    formElement = $('form');
    progressBarElement = $('.progress-bar');
    completeLabel = $('#complete-label');
    submitButton = $('#submitButton');

    $('#submitButton').on('click', function()
    {
        $('.progress').css('visibility', 'visible');

        let fileUploaded = uploadFile(formElement[0],
            progressBarElement[0],
            completeLabel,
            submitButton,
            'upload');

        //Uncomment to test a trivial promise example.
        // let promise = Promise.resolve(fileUploaded);
        // promise.then(function(resolve)
        // {
        //     console.log(resolve);
        // }, function(error)
        // {
        //     console.log(error);
        // });
    });
});