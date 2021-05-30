<?php 
    session_start();
    // echo "This data comes from php file without reloading page";
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']); 
    $lname = mysqli_real_escape_string($conn, $_POST['lname']); 
    $email = mysqli_real_escape_string($conn, $_POST['email']); 
    $password = mysqli_real_escape_string($conn, $_POST['password']); 

    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        // let's check user email is valid or not
        if(FILTER_var($email, FILTER_VALIDATE_EMAIL)){ //if email is valid
            // Let's check that email already exist in the database or not
            $sql = mysqli_query($conn ,"SELECT email FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){ // if email already exists
                echo "$email already exist!!!";
            }else{
                //let's check user uploaded files or not
                // if(isset($_FILES['image'])){//if file is uploaded
                if($_FILES['image']['size'] != 0){//if file is uploaded
                    $img_name = $_FILES['image']['name']; //getting user uploaded img name
                    $tmp_name = $_FILES['image']['tmp_name']; //This temporary name is used to save/move file in our folder
                
                    // let's explode image and get the last extension like png, jpg
                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode); //getting extension

                    $extensions = ['png', 'jpeg', 'jpg', 'jfif'];
                    if(in_array($img_ext, $extensions) === true){ // if user uploaded correct img extension
                        $time = time(); // This will return us current time, so for the name we will add time so that name will be unique
                        // So all the image file will have a unique name

                        // Let's move the user uploaded img to images folder
                        $new_img_name = $time.$img_name;
                        
                        if(move_uploaded_file($tmp_name, "C:/xampp/htdocs/Chat/images/".$new_img_name)){
                            $status = "Active Now"; 
                            $random_id = rand(time(), 10000000); //Creating Random ID for users

                            // Let's insert all user daqta inside table
                            $sql2 = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                                VALUES ({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}')" );
                            if($sql2){ //if these user data inserted
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'" );
                                if(mysqli_num_rows($sql3) > 0){
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id']; //Using this session we used user unique_id in other php file
                                    echo "success";
                                }
                                
                            }else{
                                echo "Something went wrong";
                            }                     
                        }
                    }else{
                        echo "Please select an Image file - jpeg, jpg, png, jfif";
                    }

                }else{
                    echo "Please select an image file!";
                }
            }
        }else{
            echo "$email - This is not a valid email";
        }
    }else{
        echo "All input field are required!";
    }

?>
