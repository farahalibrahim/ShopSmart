<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-SignUp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../CSS/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!--link for icons-->
    <script src="https://kit.fontawesome.com/f1d5e2b530.js" crossorigin="anonymous"></script>
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<?php // include_once 'responseModal.inc.php' 
?>

<body>
    <div class="container">
        <div class="form-box">
            <h1 id="title">Sign Up</h1>
            <form action="#">
                <div id="status" style="display: none;"></div>
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input name="email" type="email" placeholder="name@example.com" required>
                    </div>
                    <div class="input-field" id="namefield">
                        <i class="fa-solid fa-user"></i>
                        <input name="name" type="text" placeholder="full name">
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-lock"></i>
                        <input name="pass" id="pass" type="password" placeholder="password" required>
                        <!-- <button class="pass_visibility" type="button" onclick="togglePasswordVisibility('pass')"><span class="material-symbols-outlined" id="passIcon">visibility</span></button><br> -->

                    </div>
                    <div class="input-field" id="phone">
                        <i class="fa-solid fa-phone"></i>
                        <input name="phone" type="text" placeholder="phone">
                    </div>

                    <div class="input-field" id="streetname">
                        <i class="fa-solid fa-location-dot"></i>
                        <input name="street" type="text" placeholder="street_address">
                    </div>

                    <div class="input-field" id="cityname">
                        <i class="fa-solid fa-city"></i>
                        <input name="city" type="text" placeholder="city">
                    </div>
                </div>
                <div class="btn-field">
                    <!--<button type="button" id="signupBtn">Sign Up</button>-->
                    <button type="submit" id="btn">Login</button>

                </div>
                <p id="signuphref" name="parag">New to website? <a href="#">Sign Up</a></p>
                <p id="loginhref" name="parag">Already a customer? <a href="#">Login</a></p>
            </form>
        </div>
    </div>




    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility(id) {
            var passwordInput = document.getElementById(id);
            var icon = document.getElementById(id + 'Icon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.textContent = "visibility_off";
            } else {
                passwordInput.type = "password";
                icon.textContent = "visibility";
            }
        }
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                event.preventDefault();

                var email = document.querySelector("input[name='email']").value;
                var pass = document.querySelector("input[name='pass']").value;

                // Regex patterns for validation
                var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                var passPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/; // At least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long

                // Validate inputs
                if (!emailPattern.test(email)) {
                    $('#status').css('display', 'block').text('Email must match example@example.com');
                    return;
                }
                if (!passPattern.test(pass)) {
                    $('#status').css('display', 'block').text('Password must have 1 upper, 1 lower, 1 digit, min 8 chars');
                    return;
                } // Determine which PHP file to execute
                // Determine which PHP file to execute
                var phpFile = document.getElementById("btn").innerHTML.trim() === "Login" ? "validate_login.php" : "signup.php";
                // Prepare data for AJAX request
                var data = {
                    email: email,
                    pass: pass
                };

                // If the button text is "Sign Up", include additional fields
                if (document.getElementById("btn").innerHTML.trim() === "Sign Up") {
                    var namefield = document.querySelector('input[name="name"]').value;
                    var phone = document.querySelector('input[name="phone"]').value;

                    var streetname = document.querySelector('input[name="street"]').value;
                    var cityname = document.querySelector('input[name="city"]').value;

                    // Regex patterns for validation
                    var namePattern = /^[A-Z][a-z]+ [A-Z][a-z]+$/;
                    var phonePattern = /^1?[-.\s]?\(?(\d{3})\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
                    if (!namePattern.test(namefield)) {
                        $('#status').css('display', 'block').text('Name must be as First Last');
                        return;
                    }
                    if (!phonePattern.test(phone)) {
                        $('#status').css('display', 'block').text('Phone must match either (XXX) XXX-XXXX or 1-XXX-XXX-XXXX');
                        return;
                    }

                    // Add additional fields to data
                    data.name = namefield;
                    data.phone = phone;
                    data.street = streetname;
                    data.city = cityname;
                }

                // Send AJAX request
                $.ajax({
                    url: phpFile,
                    type: 'post',
                    data: data,
                    success: function(response) {
                        // Handle response
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            switch (res.role) {
                                case 'user':
                                    window.location.href = 'main/index.php';
                                    break;
                                case 'packing':
                                    window.location.href = 'packing/packing.php';
                                    break;
                                case 'delivery':
                                    window.location.href = 'delivery/delivery.php';
                                    break;
                                case 'admin':
                                    window.location.href = 'admin/admin_v1.php';
                                    break;
                            }
                        } else {
                            $('#status').css('display', 'block').text(res.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle error
                        console.error(textStatus, errorThrown);
                    }
                });
            });
        });
        window.onload = function() {
            document.getElementById("loginhref").click();
        };

        let btn = document.getElementById("btn");
        let namefield = document.getElementById("namefield");
        let title = document.getElementById("title");
        let login_href = document.getElementById("loginhref");
        let signup_href = document.getElementById("signuphref");
        let phonefield = document.querySelector("#phone");

        let name = document.querySelector("input[name='name']");
        let phone = document.querySelector("input[name='phone']");
        let email = document.querySelector("input[name='email']");
        let pass = document.querySelector("input[name='pass']");
        let street = document.querySelector("input[name='street']");
        let city = document.querySelector("input[name='city']");

        document.getElementById("loginhref").addEventListener('click', function(event) {
            event.preventDefault();
            namefield.style.maxHeight = '0';
            phonefield.style.maxHeight = '0';
            streetname.style.maxHeight = '0';
            cityname.style.maxHeight = '0';
            title.innerHTML = "Login";
            btn.innerHTML = "Login";

            phone.required = false;
            street.required = false;
            city.required = false;
            name.required = false;

            login_href.style.display = 'none';
            signup_href.style.display = 'block';
        });

        document.getElementById("signuphref").addEventListener('click', function(event) {
            event.preventDefault();
            namefield.style.maxHeight = '65px';
            phonefield.style.maxHeight = '65px';
            streetname.style.maxHeight = '65px';
            cityname.style.maxHeight = '65px';
            title.innerHTML = "Sign Up";
            btn.innerHTML = "Sign Up";

            phone.required = true;
            street.required = true;
            city.required = true;
            name.required = true;

            login_href.style.display = 'block';
            signup_href.style.display = 'none';
        });

        // signupBtn.onclick = function() {
        //     namefield.style.maxHeight = '65px';
        //     phone.style.maxHeight = '65px';
        //     streetname.style.maxHeight = '65px';
        //     cityname.style.maxHeight = '65px';
        //     title.innerHTML = "Sign Up";


        //     signupBtn.classList.remove("disable");
        //     loginBtn.classList.add("disable");
        //     phone.required = true;
        //     street.required = true;
        //     city.required = true;
        //     namefield.required = true;
        // }
    </script>
</body>

</html>