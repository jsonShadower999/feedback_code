<?php

require_once 'config.php';

$error = isset($_GET['error']) && ! empty($_GET['error']) ? $_GET['error'] : null;

// connect to mysql database
try {
  $dns = 'mysql:host=' . HOST . ';dbname=' . DB;
  $pdo = new PDO($dns, USER, PASS);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo '<p style="color:red">mysql connection failed: ' . $e->getMessage() . '</p>';
  echo '<p>try to refresh</p>';
}

// select data from database
$stmt = $pdo->prepare('SELECT * from emails ORDER BY created_at DESC');
$stmt->execute();

$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Database Form</title>

  <style>
      body {
      font-family: Arial, sans-serif;
      background-color: antiquewhite;
      margin: 0;
      padding: 0;
    }

    .DBForm,
    .Fields {
      background-color: #fff;
      border: 2px solid #222;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin: 20px auto;
      max-width: 600px;
      padding: 20px;
    }

    .DBForm h1,
    .Fields h2 {
      color: #333;
      text-align: center;
    }
    .input {
      width: calc(100% - 20px);
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
    }

    .input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 4px;
      margin-top: 10px;
      transition: background-color 0.3s;
    }
    .input[type="submit"]:hover {
      background-color: #45a049;
    }

    .Error {
      color: tomato;
      margin-top: 10px;
      text-align: center;
    }

    .Fields {
      margin-top: 20px;
    }

    .Fields .field {
      border-bottom: 1px solid #ddd;
      padding: 10px;
      font-size: 14px;
    }
    .rating {
      margin-bottom: 20px;
    }

    .star {
      font-size: 5vh;
      cursor: pointer;
      
    }

    .one, .two, .three, .four, .five {
      color: rgb(24,159,14)
    }


    /* .DBForm,
    .Fields {
      border: 2px solid #222;
      border-radius: 2px;
    }

    .DBForm {
      padding: 1rem;
    }
    .DBForm .input {
      width: 222px;
    }

    .Error {
      color: tomato;
      margin-top: 0.6rem;
    }

    .Fields .field {
      border-bottom: 1px solid #222;
      padding: 1rem;
    } */
  </style>
</head>

<body>
  <h1>Database Form</h1>
  <form
    method="post"
    action="action.php"
    class="DBForm"
  >
  <label for="email" name="email" id="email" >Provide us with your Email access point :</label>
    <input
      type="email"
      name="email"
      placeholder="Type your email address"
      class="input"
    />
  
    <div class="error">
    <?php
    // Check if $emailErr is set and not empty
    if (isset($emailErr) && !empty($emailErr)) {
      echo '<span class="error">' . $emailErr . '</span>';
    }
    ?>
  </div>
    <br><br><br>
    <label for="Name" name="Name" id="Name">Provide us your Name :</label> 
    <input type="text" name="Name" placeholder="Type Your Name" class="input"/>
    <?php
    // Initialize $emailErr variable
    $nameErr = isset($_GET['nameErr']) ? $_GET['nameErr'] : null;

    if (!empty($nameErr)) {
        echo '<p style="color: red;">Error: ' . htmlspecialchars($nameErr) . '</p>';
    }
    ?>
    <br><br><br>
    <label for="rating" name="rate" id="rating">Provide us Review /Rate your experience with us :</label>  
    <div class="rating">
  <input type="radio" id="star1" name="rating" value="1">
  <label for="star1">1</label>
  <input type="radio" id="star2" name="rating" value="2">
  <label for="star2">2</label>
  <input type="radio" id="star3" name="rating" value="3">
  <label for="star3">3</label>
  <input type="radio" id="star4" name="rating" value="4">
  <label for="star4">4</label>
  <input type="radio" id="star5" name="rating" value="5">
  <label for="star5">5</label>
  <input type="radio" id="star6" name="rating" value="6">
  <label for="star6">6</label>
  <input type="radio" id="star7" name="rating" value="7">
  <label for="star7">7</label>
  <input type="radio" id="star8" name="rating" value="8">
  <label for="star8">8</label>
  <input type="radio" id="star9" name="rating" value="9">
  <label for="star9">9</label>
  <input type="radio" id="star10" name="rating" value="10">
  <label for="star10">10</label>
</div>

<div class="error">
    <?php
    // Check if $emailErr is set and not empty
    if (isset($rateErr) && !empty($rateErr)) {
      echo '<span class="error">' . $rateErr . '</span>';
    }
    ?>
  </div>
    <div class="card">
        <h2> Star Rating :</h2>
        <br />
        <input type="radio" id="star5" name="rating1" value="1" onclick="starlogic(1)" />
        <label for="star5" class="star">★</label>
      <input type="radio" id="star5" name="rating1" value="2" onclick="starlogic(2)" />
        <label for="star5" class="star">★</label>
      <input type="radio" id="star5" name="rating1" value="3" onclick="starlogic(3)" />
        <label for="star5" class="star">★</label>
      <input type="radio" id="star5" name="rating1" value="4" onclick="starlogic(4)" />
        <label for="star5" class="star">★</label>
      <input type="radio" id="star5" name="rating1" value="5" onclick="starlogic(5)" />
        <label for="star5" class="star">★</label>
   
       
        <h3 id="output">
              Rating is: 0/5
          </h3>
    </div>
        
<label for="country">Provide us the Country you line in :</label>
<select id="country" name="country">
    <option value="" selected disabled>Select from list here</option>
    <?php
    $countries = include('countries.php'); // Assuming this file returns an array of countries

    foreach ($countries as $country) {
        echo "<option value=\"$country\">$country</option>";
    }
    ?>

</select>

<div class="error">
    <?php
    // Check if $emailErr is set and not empty
    if (isset($countryErr) && !empty($countryErr)) {
      echo '<span class="error">' . $countryErr . '</span>';
    }
    ?>
  </div>
<br><br><br><br><br>
    





    <input
      type="submit"
      name="action"
      value="add"
     />
  </form>

  <div class="Error">
    <?php if ( ! is_null($error)) echo 'error: ' . $error; ?>
  </div>

  <h2>Email addresses</h2>
  <div class="Fields">
    <?php
      foreach ($emails as $key => $email) {
        ?><div class="field"><?php
          $created_date = date('d.m.Y', $email['created_at']);
          echo $created_date . ' – ' . $email['address'];
        ?></div><?php
      }
    ?>
  </div>
  <script>
    let stars = 
    document.getElementsByClassName("star");
let output = 
    document.getElementById("output");
 
// Funtion to update rating
function starlogic(n) {
    remove();
    let cls='';
    for (let i = 0; i < n; i++) {
        if (n == 1) cls = "one";
        else if (n == 2) cls = "two";
        else if (n == 3) cls = "three";
        else if (n == 4) cls = "four";
        else if (n == 5) cls = "five";
        stars[i].className = "star " + cls;
    }
    output.innerText = "Rating is: " + n + "/5";
}
 
// To remove the pre-applied styling
function remove() {
    let i = 0;
    while (i < 5) {
        stars[i].className = "star";
        i++;
    }
}


  </script>
</body>
</html>
