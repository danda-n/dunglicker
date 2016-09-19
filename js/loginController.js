$.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^\w+$/i.test(value);
}, "Only letters, numbers and underscores are allowed.");

var validator = $("#registerForm").validate({
    rules:
            {
                registerEmail:
                        {
                            required: true,
                            email: true
                        },
                registerUsername:
                        {
                            required: true,
                            rangelength: [2, 20]
                        },
                registerPassword:
                        {
                            required: true,
                            alphanumeric: true,
                            rangelength: [2, 20]
                        },
                registerConfirmPassword:
                        {
                            required: true,
                            equalTo: "#registerPassword"
                        }
            },
    messages:
            {
                registerUsername:
                        {
                            required: "Please provide valid username.",
                            rangelength: "Use 2 to 20 characters."
                        },
                registerPassword:
                        {
                            required: "Please provide valid password.",
                            rangelength: "Use 2 to 20 characters."
                        },
                registerConfirmPassword:
                        {
                            required: "Please provide valid password.",
                            equalTo: "Passwords do not match!"
                        },
                registerEmail:
                        {
                            required: "Valid e-mail is required.",
                            email: "Valid e-mail is required."
                        }
            }
});

$("#btn_register").click(function () {
    validator.resetForm();
});

$(function () {
    $("#loginForm, #registerForm").submit(function () {
        $.post($(this).attr('action'), $(this).serialize(), function (response) 
        {
            if (response.success == true)
            {
            	window.location.href = "game.php";
            }
        }, 'json');
        return false;
    });
});