<?php
include("config.php");
$rm = $_GET['rm'];
echo "$id";
$query= "DELETE FROM expenses WHERE ide = '$rm' ";
$data= mysqli_query($conn,$query);
if($data)
{
    echo "<script>alert('expense deleted');</script>";
    header('Location: user.php');
}
else
{

    echo "<script>alert('Failed to delete');</script>";
}
?>
