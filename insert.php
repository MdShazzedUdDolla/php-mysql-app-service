<?php require "templates/header.php"; ?>

<div class="center-align">

    <?php

    if (isset($_POST['submit'])) {
        require "database/config.php";

        //Establish the connection
        $conn = mysqli_init();
        mysqli_ssl_set($conn,NULL,NULL,$sslcert,NULL,NULL);
        if(!mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL)){
            die('Failed to connect to MySQL: '.mysqli_connect_error());
        }

        $res = mysqli_query($conn, "SHOW TABLES LIKE 'usk'");
    
        if (mysqli_num_rows($res) <= 0) {
            //Create table if it does not exist
            $sql = file_get_contents("database/schema.sql");
            if(!mysqli_query($conn, $sql)){
                die('Table Creation Failed');
            }
        }

        // Insert data from form
        $fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$book = $_POST['book'];
$OperatingSystem = $_POST['OperatingSystem'];

        if ($stmt = mysqli_prepare($conn, "INSERT INTO usk fname, lname, email, phone, book, OperatingSystem) values(?,?,?,?,?,?)")) {
            mysqli_stmt_bind_param($stmt, "ssssss",$fname,$lname,$email,$phone,$book,$OperatingSystem);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 0) {
                echo "<h2>Catalog update failed.</h2>";
            }
            else {
                echo "<h2>Hi $fname Thank you for completing the survey.</h2>";
            }
            mysqli_stmt_close($stmt);
            
        }

        //Close the connection
        mysqli_close($conn);

    } else {

    ?>

    <h2>Registeration form</h2>
    <br>

    <form method="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="fname">First Name: </label>
      <input type="text" name="fname" id="fname" required><br><br>

      <label for="lname">Last Name: </label>
      <input type="text" name="lname" id="lname" required><br><br>

      <label for="email">Email: </label>
      <input type="email" name="email" id="email" required><br><br>

      <label for="phone">Phone:</label>
      <input type="tel" name="phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="555-555-5555" required><br><br>

      <h2>Publications</h2>
      <p>Which book would you like information about?</p>
      <select name="book" id="book">
        <option value="Internet and www How to program">Internet and www How to program</option>
        <option value="C++ How to program">C++ How to program</option>
        <option value="Java How to program">Java How to program</option>
        <option value="Visual basics How to program">Visual basics How to program</option>
      </select><br>

      <h2>Operating System</h2>
      <p>Which operating system do you use?</p>

      <input type="radio" name="OperatingSystem"  id="OperatingSystem" value="Windows">
      <label for="Windows">Windows</label>
      <input type="radio" name="OperatingSystem" id="OperatingSystem" value="Mac OS">
      <label for="Mac Os">Mac Os</label>
      <input type="radio" name="OperatingSystem" id="OperatingSystem" value="Linux">
      <label for="Linux">Linux</label>
      <input type="radio" name="OperatingSystem" id="OperatingSystem" value="Other">
      <label for="Other">Other</label>

      <br><br>

        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
        }
    ?>

    <br> <br> <br>
    

</div>

<?php require "templates/footer.php"; ?>

