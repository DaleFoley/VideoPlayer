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
        uploadFile(formElement[0],
            progressBarElement[0],
            completeLabel,
            submitButton,
            'upload');
    });
});