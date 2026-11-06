<!DOCTYPE html>
<html>
<head>
<title>Finsight Presentation</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#111;
    color:white;
    overflow:hidden;
}

.slide{
    display:none;
    height:100vh;
    text-align:center;
    padding-top:150px;
}

.active{
    display:block;
}

h1{
    font-size:40px;
}

p{
    font-size:20px;
    width:70%;
    margin:auto;
}

.btn{
    position:absolute;
    bottom:20px;
    padding:10px 20px;
    background:#3498db;
    color:white;
    border:none;
    cursor:pointer;
}

.next{right:20px;}
.prev{left:20px;}
</style>
</head>

<body>

<div class="slide active">
    <h1>💰 Finsight Project</h1>
    <p>Expense Tracker & Budget Management System</p>
</div>

<div class="slide">
    <h1>🎯 Objective</h1>
    <p>To manage daily expenses and track budget effectively using a web application.</p>
</div>

<div class="slide">
    <h1>⚙ Features</h1>
    <p>
    ✔ Add Expenses<br>
    ✔ View Expenses<br>
    ✔ Budget Management<br>
    ✔ Charts & Reports<br>
    ✔ PDF Download
    </p>
</div>

<div class="slide">
    <h1>🛠 Technologies Used</h1>
    <p>PHP, MySQL, HTML, CSS, JavaScript, XAMPP</p>
</div>

<div class="slide">
    <h1>🏁 Thank You</h1>
    <p>Presented by: Your Name</p>
</div>

<button class="btn prev" onclick="changeSlide(-1)">⬅ Prev</button>
<button class="btn next" onclick="changeSlide(1)">Next ➡</button>

<script>
let index = 0;
let slides = document.querySelectorAll(".slide");

function showSlide(i){
    slides.forEach(s => s.classList.remove("active"));
    slides[i].classList.add("active");
}

function changeSlide(n){
    index += n;
    if(index < 0) index = slides.length - 1;
    if(index >= slides.length) index = 0;
    showSlide(index);
}
</script>

</body>
</html>