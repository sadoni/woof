<?php
require_once "config.php";

session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$dog_name = $dog_photo = $dog_breed = $dog_birthday = $dog_location = $dog_description = "";

$sql = "SELECT id, username, dog_name, dog_breed, dog_birthday, dog_location, dog_description, dog_photo FROM users WHERE username='" . $_SERVER['QUERY_STRING'] . "'";
$result = $link->query($sql);
?>

<!doctype html>
<html lang="en">
  <head>
      <meta charset="utf-8">
	  <meta name="author" content="Ryan Black, Sai Adoni, Lauren Gold, Aanchal Saxena, Brooke Schulte">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	  
	  <link rel="stylesheet" href="style.css">

      <title>Woof! | Browse</title>
  </head>
  <body id="otherpuppy">
      <div class="nav">
		<a href="/"><div>Woof!</div></a>
		<a href="browse.php"><div>Browse</div></a>
		<a href="user.php?<?php echo $_SESSION["username"] ?>"><div class="active">Profile</div></a>
		<a href="logout.php"><div><img src="images/logout.png" alt="Logout" style="max-width: 50%;"></div></a>
	</div>
	  
	  <?php
	  	if ($result -> num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$dog_name = $row["dog_name"];
				$dog_photo = $row["dog_photo"];
				$dog_breed = $row["dog_breed"];
				$dog_birthday = $row["dog_birthday"];
				$dog_location = $row["dog_location"];
				$dog_description = $row["dog_description"];
			}
		} else {
			echo "yikes";
			echo $_SERVER['QUERY_STRING'];
		}
	  ?>
	<h1 class="title"><?php echo $dog_name ?></h1>
	<div class="back">
	  	<a href="browse.php">&larr;</a>
	</div>
	<div class="otherpuppy">
		<div>
			<img src="<?php echo $dog_photo ?>">
		</div>
		<div>
			<p><strong>Breed</strong>: <?php echo $dog_breed ?></p>
			<p><strong>Birthday</strong>: <?php echo $dog_birthday ?></p>
			<p><strong>Location</strong>: <?php echo $dog_location ?></p>
			<hr>
			<p>
				<strong>Description</strong>:
				<br>
				<?php echo $dog_description ?>
			</p>
		</div>	
	</div>

      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
<?php
	mysqli_close($link);
?>