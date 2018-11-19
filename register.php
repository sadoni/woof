<?php
require_once "config.php";
 
$username = $password = $confirm_password = $dog_name = $dog_breed = $dog_birthday = $dog_location = $dog_desc = "";
$username_err = $password_err = $confirm_password_err = $dog_name_err = $dog_breed_err = $dog_birthday_err = $dog_location_err = $dog_desc_err = $dog_photo_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
	//check username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is taken, please select a new one.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Yikes";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
	
	//check dog name
	if(empty(trim($_POST["dog_name"]))){
        $dog_name_err = "Please enter your puppy's name.";     
    } else {
        $dog_name = trim($_POST["dog_name"]);
    }
	
	//check dog breed
	if(empty(trim($_POST["dog_breed"]))){
        $dog_breed_err = "Please enter your puppy's breed.";     
    } else {
        $dog_breed = trim($_POST["dog_breed"]);
    }
	
	//check dog birthday
	if(empty(trim($_POST["dog_birthday"]))) {
        $dog_birthday_err = "Please enter your puppy's birthday.";     
    } else {
        $dog_birthday = $_POST["dog_birthday"];
		$dog_birthday = date("Y-m-d", strtotime($dog_birthday));
    }
	
	//check dog location
	if(empty(trim($_POST["dog_location"]))) {
        $dog_location_err = "Please enter your puppy's location.";     
    } else {
        $dog_location = trim($_POST["dog_location"]);
    }
	
	//check dog description
	if(empty(trim($_POST["dog_desc"]))) {
        $dog_desc_err = "Please enter a description for your puppy.";     
    } else {
        $dog_desc = trim($_POST["dog_desc"]);
    }
	
	//check dog photo
	if(empty($_FILES["dog_photo"]["name"])) {
        $dog_photo_err = "Please select a photo for your puppy.";     
    } else {
        $dog_photo = $_FILES["dog_photo"]["tmp_name"];
		$target_dir = "images/uploads/";
		$target_file = $target_dir . basename($dog_photo);
		
		//remove when fixed
		//$dog_photo = $target_file;
		
		if (move_uploaded_file($_FILES["dog_photo"]["tmp_name"], $target_file)) {
			$dog_photo = $target_file;
		} else {
			$dog_photo_err = "Sorry, there was an error uploading your file.";
		}
		
    }
    
	//check password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have 6+ characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
	//confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    //check input errors 
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($dog_name_err) && empty($dog_breed_err) && empty($dog_birthday_err) && empty($dog_location_err) && empty($dog_desc_err) && empty($dog_photo_err)){
        
        $sql = "INSERT INTO users (username, password, dog_name, dog_breed, dog_birthday, dog_location, dog_description, dog_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
		//do the thing
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssssss", $username, $hash_pass, $dog_name, $dog_breed, $dog_birthday, $dog_location, $dog_desc, $dog_photo);
            
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
            } else{
                echo "Yikes Input Errors";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name="author" content="Ryan Black, Sai Adoni, Lauren Gold, Aanchal Saxena, Brooke Schulte">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Woof - Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="register">
		<div class="holder">
			<h1 id="title">Woof! - Register</h1>
			<div class="register">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
					<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
						<label>Username</label>
						<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
						<span class="help-block"><?php echo $username_err; ?></span>
					</div>    
					<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
						<label>Password</label>
						<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
						<span class="help-block"><?php echo $password_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
						<label>Confirm Password</label>
						<input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
						<span class="help-block"><?php echo $confirm_password_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dog_name)) ? 'has-error' : ''; ?>">
						<label>Your Puppy's Name</label>
						<input type="text" name="dog_name" class="form-control" value="<?php echo $dog_name; ?>">
						<span class="help-block"><?php echo $dog_name_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dog_breed)) ? 'has-error' : ''; ?>">
						<label>Your Puppy's Breed</label>
						<input type="text" name="dog_breed" class="form-control" value="<?php echo $dog_breed; ?>">
						<span class="help-block"><?php echo $dog_breed_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dog_birthday)) ? 'has-error' : ''; ?>">
						<label>Your Puppy's (Estimated) Birthday</label>
						<input type="date" name="dog_birthday" class="form-control" value="<?php echo $dog_birthday; ?>">
						<span class="help-block"><?php echo $dog_birthday_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dog_location)) ? 'has-error' : ''; ?>">
						<label>Your Puppy's Location</label>
						<input type="text" name="dog_location" class="form-control" value="<?php echo $dog_location; ?>">
						<span class="help-block"><?php echo $dog_location_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dog_desc)) ? 'has-error' : ''; ?>">
						<label>A Short Description of Your Puppy</label>
						<input type="textarea" name="dog_desc" class="form-control" value="<?php echo $dog_desc; ?>" style="height: calc(5.5rem + 2px);">
						<span class="help-block"><?php echo $dog_desc_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dog_photo)) ? 'has-error' : ''; ?>">
						<label>A Cute Photo of Your Puppy</label>
						<input type="file" name="dog_photo" class="form-control" value="<?php echo $dog_photo; ?>" style="background-color: initial; border: 0;">
						<span class="help-block"><?php echo $dog_photo_err; ?></span>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" value="Submit" style="display: block; margin: 0 auto;">
					</div>
					<p class="text-center">Already have an account? <a href="/">Login here</a>!</p>
				</form>
			</div>
		</div>
	</div> 
</body>
</html>