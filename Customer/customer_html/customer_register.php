<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <title>CBites Customer Register</title>
    <link rel="stylesheet" href="../../Customer/customer_css/customer_register.css">
</head>
<body>
    <div class="wrapper">
        <div class="wavey1">
            <img src="../../Icons/register_wavey1.svg" alt="" width="420px">
        </div>
        <div class="wavey2">
            <img src="../../Icons/register_wavey2.svg" alt="" width="250px">
        </div>

        <div class="header-content">
            <p class="reg">Register Now!</p>
            <p class="univ">University of Southeastern Philippines Tagum Unit</p>
            <p class="ecanteen">E-Canteen Service System</p>
        </div>
        <div class="register-box">
                <div class="first-row">
                    <div class="fname">
                        <p>First Name <span style="color: red;">*</span></p>
                        <input type="text" id="fname" oninput="validateLettersOnly(this)">
                    </div>
                    <div class="mname">
                        <p>Middle Name <span style="color: red;">*</span></p>
                        <input type="text" id="mname" oninput="validateLettersOnly(this)">
                    </div>
                    <div class="lname">
                        <p>Last Name <span style="color: red;">*</span></p>
                        <input type="text" id="lname" oninput="validateLettersOnly(this)">
                    </div>
                    <div class="suffix">
                        <p>Suffix <span style="color: red;">*</span></p>
                        <select id="suffix">
                        <option value="None">None</option>
                            <option value="Jr">Jr</option>
                            <option value="Sr">Sr</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                          </select>
                    </div>
                </div>
                <div class="second-row">
                    <div class="pnum">
                        <p>Phone Number <span style="color: red;">*</span></p>
                        <input type="text" id="phoneNumber" oninput="validatePhoneNumber(this)">
                    </div>
                    <div class="gender">
                        <p>Gender *</p>
                        <select id="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                          </select>
                    </div>
                    <div class="studentnumber">
                        <p>Student UseP Email <span style="color: red;">*</span></p>
                        <input type="email"  id="emailInput" placeholder="Enter your email">
                    </div>
                </div>
                <div class="third-row">
                    <div class="address">
                        <p>UseP School Landmark <span style="color: red;">*</span></p>
                        <input type="text" id="usepLandmark">
                    </div>
                </div>

                <div class="policyContainer">
                    <input type="checkbox" name="">
                    <label for="">I agree to the <span><a href="../../Customer/customer_html/customerPolicy.php" style="color:#F6AC32;">Terms Condition</a></span> and <span><a href="../../Customer/customer_html/customerPolicy.php" style="color:#F6AC32;">Policies</a></span></label>
                </div>

                <div class="fourth-row">
                    <div class="error error-message animate__animated animate__pulse" id="verificationResult">
                        
                    </div>
                </div>
               
                <div class="register-button">
                    <button type="submit" onclick="verifyEmail()" style="cursor:pointer;">Register Account</button>
                </div>
        </div>
    </div>


    <script>
    function verifyEmail() {
        // Collect all input values
        const fname = document.getElementById('fname').value;
        const mname = document.getElementById('mname').value;
        const lname = document.getElementById('lname').value;
        const suffix = document.getElementById('suffix').value;
        const phoneNumber = document.getElementById('phoneNumber').value;
        const gender = document.getElementById('gender').value;
        const usepEmail = document.getElementById('emailInput').value;
        const usepLandmark = document.getElementById('usepLandmark').value;
        const checkBox = $("input[type='checkbox']").is(':checked'); // Check if the checkbox is checked

        if (!checkBox) {
            // Display an error if the checkbox is not checked
            $('#verificationResult').text('Kindly check the Terms and Policies checkbox.');
            $('#verificationResult').css('display', 'block');
            return; // Abort further processing
        }

        // Check if the email ends with "@usep.edu.ph"
        if (usepEmail.toLowerCase().endsWith('@usep.edu.ph')) {
            // Perform AJAX request to the PHP endpoint
            $.ajax({
                type: 'POST',
                url: '../functions/registerStaff.php', // Change to your actual PHP endpoint
                data: {
                    fname: fname,
                    mname: mname,
                    lname: lname,
                    suffix: suffix,
                    phoneNumber: phoneNumber,
                    gender: gender,
                    usepEmail: usepEmail,
                    usepLandmark: usepLandmark
                },
                dataType: 'json',
                success: function (response) {
                    $('#verificationResult').text(response.message);

                    if (response.status === 'success') {
                        // Redirect to the new PHP file
                        window.location.href = response.redirect;
                    }

                    $('#verificationResult').css('display', response.status === 'error' ? 'block' : 'none');
                },
                error: function (xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            $('#verificationResult').text('Invalid email. Please enter an email ending with "@usep.edu.ph".');
            // Show the error message
            $('#verificationResult').css('display', 'block');
        }
    }


        function validatePhoneNumber(input) {
            // Remove non-numeric characters
            input.value = input.value.replace(/[^0-9]/g, '');

            // Limit the input to 12 characters
            if (input.value.length > 12) {
                input.value = input.value.substring(0, 12);
            }
        }

        function validateLettersOnly(input) {
            // Remove non-letter characters
            input.value = input.value.replace(/[^a-zA-Z]/g, '');
        }
    </script>

</body>
</html>