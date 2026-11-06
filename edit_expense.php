<?php
$conn = new mysqli("127.0.0.1","root","","finsight");

$id = $_GET['id'];

$sql = "SELECT * FROM expenses WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if(isset($_POST['update'])){
    $amount = $_POST['amount'];
    $category = $_POST['category'];

    // Force date to CURRENT date (ignore selected date)
    $date = date('Y-m-d');

    $description = $_POST['description'];

    // DEBUG
    // echo "<div style='color:blue'>EDIT DEBUG date={$date}</div>";

    $update = "UPDATE expenses SET 
                amount='$amount',
                category='$category',
                date='$date',
                description='$description'
                WHERE id=$id";

    if($conn->query($update)){
        header("Location: dashboard.php");
    }
}
?>

<h2>Edit Expense</h2>

<form method="POST">

Amount  
<input type="number" name="amount" value="<?php echo $row['amount']; ?>"><br><br>

Category  
<input type="text" name="category" value="<?php echo $row['category']; ?>"><br><br>

Date  
<input type="date" name="date" value="<?php echo $row['date']; ?>"><br><br>

Description  
<input type="text" name="description" value="<?php echo $row['description']; ?>"><br><br>

<button type="submit" name="update">Update Expense</button>

</form>