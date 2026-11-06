<?php

$conn = new mysqli("127.0.0.1","root","","finsight");

$id = $_GET['id'];

$sql = "DELETE FROM expenses WHERE id=$id";

if($conn->query($sql) === TRUE){
    header("Location: view_expense.php");
}else{
    echo "Error deleting record";
}

?>