$(document).ready(() => {


    //login form using modal
    $("form#login_form").on("submit", function (e) {
        e.preventDefault();

        let email = $('#email').val();
        let password = $('#password').val();
        $('#login_err').html('');

        $.ajax({
            url: "index.php",
            data: { key: "saveData", password: password, email: email },
            method: "POST",
            success: function (response) {
                responseData = JSON.parse(response)
                if (responseData.status == 403) {
                    $('#login_err').html(responseData.message)
                }

                if (responseData.status == 200) {
                    $('#email').val('');
                    $('#password').val('');
                    $('#login-modal').modal('hide');
                    window.location.href = "http://localhost/college_ecom/public/index.php"
                }

            },
            error: function (response) {

            }
        });
    })




});