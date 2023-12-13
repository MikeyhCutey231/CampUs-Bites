<?php require 'script.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>test phase</title>
</head>
<body>

	<h1>backend design lol</h1>

    <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
        <label>Type a username</label>
        <input type="text" name="username"><br>

        <label>Type a password</label>
        <input type="text" name="password"><br>

        <label>Enter fullname:</label>
        <input type="text" name="fullname"><br>

        <label>Type email</label>
        <input type="text" name="email"><br>

        <label>Type phone number</label>
        <input type="text" name="phoneNumber"><br>

        <label>upload picture</label>
        <input type="file" name="profilePic[]" accept="image/*"><br>

        <button type="submit" name="record">Submit</button>

        <p class="error"><?php echo $validate_err ?></p>
        <p class="success"><?php echo $rec_success ?></p>
    </form>
</body>
</html>