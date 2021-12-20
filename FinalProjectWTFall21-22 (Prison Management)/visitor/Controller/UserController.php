  
<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
//session_start(); 
$uname="";
$err_uname="";
$password="";
$err_pass="";
$err_db="";
$hasError  = false;

include 'Model/db_config.php';
	 
	if(isset($_POST["*##"])){
				
		
			$rs = insertUser($_POST["name"],$_POST["uname"] ,$_POST["email"] ,$_POST["password"] ,$_POST["address"] ,$_POST["gender"] ,$_POST["usertype"] );
		 

	}


//----------------------------------------------------------------------------------------

else if (isset($_POST["edit_profile"])){
    //do validations
    //if no error
    $rs = updateProfile($_POST["username"],$_POST["password"],$_POST["gender"],$_POST["email"],$_POST["address"],$_POST["id"]);
    if($rs === true){
        header("Location: visitor_profile.php");
    }
    $err_db = $rs;
}





else if(isset($_POST["delete_users"])){
				
		
	$rs = delete_users_function($_POST["id"] );
	//$name,$uname,$email,$password,$address,$gender,$usertype
	if($rs === true){
		header("Location: alluser.php");
	}
	 


}
	elseif(isset($_POST["btn_login"])){
		
		
		/* $users = getalluser();*/

		 if(empty($_POST["uname"])){
			$hasError  = true;
			$err_uname = "Username Required";
		}
		else{
			$uname = $_POST["uname"];
		}
		if(empty($_POST["password"])){
			$hasError  = true;
			$err_pass = "Password Required";
		}
		else{
			$password = $_POST["password"];
		}
		
		//do others

		/*if(!$hasError)
		{
			foreach($users as $u)
			{
				if($uname == $u["username"] && $password ==$u["password"])
				{
					$_SESSION["loggeduser"] = $uname;
					//setcookie("loggeduser",$uname,time()+120);
					header("Location: dashboard.php");
				}
				else{
					 
					//echo "Invalid Username or password";
					$err_db = "Invalid Username and password ";
					header("Location: login.php");
				}
				
				
			}
		}
		else
		{
			$err_db = "Username and password invalid";
		}*/
//---------------------------------------------------
		/*$uname = $_POST["uname"];
		$password = $_POST["password"];*/

		if(!$hasError){

			
			if(authenticateUser($uname,$password)){

				$_SESSION["loggeduser"] = $uname;
				//have to create type check function
				$allTypes=typecheck($uname);
			
				//var_dump($allTypes);
				foreach($allTypes as $t )
						{
							//var_dump($t["usertype"]);
							if($t["usertype"]=="admin")
							 {
							
								header("Location: Admin/admin_home.php");
								$_SESSION["usertype"]=$t["usertype"];
								
							 }
							 else if($t["usertype"]=="employee")
							 {
								header("Location: Employee/employee_home.php");
								$_SESSION["usertype"]=$t["usertype"];
							 }
							 else if($t["usertype"]=="customer")
							 {
								header("Location: Customer/home.php");
								$_SESSION["usertype"]=$t["usertype"];
							 }

                             else if($t["usertype"]=="deliveryman")
							 {
								header("Location: Delivery Man/delivery_home.php");
								$_SESSION["usertype"]=$t["usertype"];
							 }
						}

				//header("Location: dashboard.php");
							 
		  }
		  else{
			$err_db = "Username and password invalid";
			header("Location: login.php");
			
		  }
		}
	   
			 
	 
	//--------------------------------------------------------------------

	}


	//-----------------------------------------------------------------------------------------
	function insertUser($name,$uname,$email,$password,$address,$gender,$usertype){
		$query = "insert into visitor(id,name,username,`password`,gender,email,address,usertype) values (NULL,'$name','$uname','$password','$gender','$email','$address','$usertype' )";
		return execute($query);
		
	}

    function updateProfile($name,$password,$gender,$email,$address,$id){
        $query = "update visitor set username='$name',password='$password',gender='$gender',email='$email',address='$address' where id = '$id'";
		return execute($query);
    }
	function authenticateUser($uname,$password){
		$query = "select * from visitor where username='$uname' and password='$password'";
		$rs = get($query);
		if(count($rs) > 0){
			return true;
		}
		return false;
		
	}
	function checkUsername($uname){
		$query = "select name from visitor where username='$uname'";
		$rs = get($query);
		if(count($rs) > 0){
			return true;
		}
		return false;
		
	}

	function getalluser(){
		$query = "select * from visitor";
		$rs = get($query);
		return $rs;
	}

	function delete_users_function($id)
	{
		$query = "delete from visitor where id = $id";
		return execute($query);
	}

	function getuser($id){
		$query = "select * from visitor where id = $id ";
		$rs = get($query);
		return $rs[0];	
	}

	function typecheck($username){
		$query = "select usertype from visitor where username='$username'";
		$rs = get($query);
		return $rs;	
	}

	function getprofile($id){
		$query = "select * from visitor where id='$id'";
		$rs = get($query);
		return $rs;	
	}


	function getCasefile($id){
		$query = "select * from casefile where id='$id'";
		$rs = get($query);
		return $rs;	
	}


   




?>