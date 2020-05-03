<html>
<head>
<script type="text/javascript">
function formSubmit()
  {
  document.getElementById("myForm").submit()
  }
</script>
</head>

<body>





<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 25%;
  padding: 10px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
/* Style the buttons */
.btn {
  border: none;
  outline: none;
  padding: 12px 16px;
  background-color: #f1f1f1;
  cursor: pointer;
}

.btn:hover {
  background-color: #ddd;
}

.btn.active {
  background-color: #666;
  color: white;
}
</style>


<h2>資料列表</h2>

<div id="btnContainer">
  <!-- <button class="btn" onclick="listView()"><i class="fa fa-bars"></i> List</button>  -->
  <button class="btn active" onclick="gridView()"><i class="fa fa-th-large"></i> 列表</button>
</div>
<br>


<div class="row">
  <div class="column" style="background-color:white;">
    <h2>編號</h2>
  </div>
  <div class="column" style="background-color:white;">
    <h2>訊息</h2>
  </div>
  <div class="column" style="background-color:white;">
    <h2>建立時間</h2>
  </div>
  <div class="column" style="background-color:white;">
    <h2>更新時間</h2>
  </div>
</div>
@foreach($lists as $list)
<div class="row">
  <div class="column" style="background-color:#aaa;">
    <h2>{{$list->id}}</h2>
  </div>
  <div class="column" style="background-color:#bbb;">
    <h2>{{$list->message}}</h2>
  </div>
  <div class="column" style="background-color:#bbb;">
    <h2>{{$list->createdAt}}</h2>
  </div>
  <div class="column" style="background-color:#bbb;">
    <h2>{{$list->updatedAt}}</h2>
  </div>
</div>
@endforeach


<script>
// Get the elements with class="column"
var elements = document.getElementsByClassName("column");

// Declare a loop variable
var i;

// List View
function listView() {
  for (i = 0; i < elements.length; i++) {
    elements[i].style.width = "100%";
  }
}

// Grid View
function gridView() {
  for (i = 0; i < elements.length; i++) {
    elements[i].style.width = "50%";
  }
}

/* Optional: Add active class to the current button (highlight it) */
var container = document.getElementById("btnContainer");
var btns = container.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>


</body>

</html>