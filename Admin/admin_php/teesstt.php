<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Form with Input Validation</title>
</head>
<body>

<?php
$nameErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the name field is empty
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // Additional validation or processing can be added here
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name">
    <span style="color: red;"><?php echo $nameErr; ?></span>
    <br><br>
    <input type="submit" value="Submit">
</form>

</body>
</html>
