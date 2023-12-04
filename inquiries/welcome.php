<?php
include('../config.php');

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT  DISTINCT agent_location AS value,agent_location AS label FROM agent ORDER BY agent_location ASC";
$result = $conn->query($sql);
$conn->close();
$location = null;
$platform = null;
if (!empty($_REQUEST['location'])) {
  $location = strtolower($_REQUEST['location']);
}

if (!empty($_REQUEST['platform'])) {
  $platform = strtolower($_REQUEST['platform']);
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GFG Leads 2023</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

  <link rel="stylesheet" href="styles.css">
</head>

<body class="page-wrapper">
  <!--  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
<a class="navbar-brand" href="http://localhost:8084/andrew/leads.gfgproperty/Home">
            <img class="img-responsive" width="50" src="images/logo.png"> GFG Leads 2023            </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
       
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
             <li class="nav-item">
              <a class="nav-link " aria-current="page" href="https://leads.gfgproperty.com/home">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link " aria-current="page" href="https://leads.gfgproperty.com/agent">Agent</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Inquiry</a>
            </li>
           
          </ul>
          
        </div>
      </div>
    </nav> -->

  <div class="container">
    <p class="custom_text">Let us know your desire <br> location and we will find the <br> <span style="color: #008DC7;">ideal room for you</span>!</p>
    <div class="row main_row">
      <div class="col-sm-2 col-lg-2"></div>
      <div class="col-sm-12 col-lg-8">
        <form method="post" action="https://join.ibilik.com/inquiry/add">
          <input type="hidden" name="platform" value="<?php echo $platform?>">
          <div>
            <div class="mb-3 row">
              <label for="prospect_name" class="col-sm-2 col-md-2 col-form-label">Your Name</label>
              <div class="col-sm-4 col-md-4">
                <input type="text" class="form-control" id="prospect_name" name="prospect_name" value="" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="prospect_phone" class="col-sm-2 col-md-2 col-form-label">Your Phone</label>
              <div class="col-sm-4 col-md-4">
                <input type="text" class="form-control" id="prospect_phone" name="prospect_phone" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="inputPassword" class="col-sm-2 col-md-2 col-form-label">Select Location</label>
              <div class="col-sm-4 col-md-4">
                <select required="" class="form-select" aria-label="Default select example" name="select_location" id="select_location">
                  <option value=""> Select Location </option>
                  <?php
                  if ($result->num_rows > 0) {// output data of each row
                    while ($row = $result->fetch_assoc()) {?>
                      <option value="<?php echo $row['value'] ?>" <?php if (!empty($location) && $location == strtolower($row['value'])) { ?> selected="selected" <?php } ?>><?php echo $row['label']; ?></option>
                      <?php
                    }
                  }
                  ?>

                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="inputPassword" class="col-sm-2 col-md-2 col-form-label">Budget rental</label>
              <div class="col-sm-4 col-md-4">
                <select required="" class="form-select" aria-label="Default select example" name="budget_rental" id="budget_rental">
                  <option value=""> Budget rental </option>
                  <option value="Below : RM500">Below : RM500</option>
                  <option value="Above : RM500">Above : RM500</option>
                  <option value="Above : RM800">Above : RM800</option>
                </select>
              </div>
            </div>


            <div class="mb-2 row">
              <label for="inputPassword" class="col-sm-2 col-md-2 col-form-label"></label>
              <div class="col-sm-4"><button type="submit" class="btn btn-primary">Submit</button>

              </div>
            </div>
          </div>

          <input id="ctrl-datetime" value="<?php echo date("Y-m-d H:i:s") ?>" type="hidden" placeholder="Enter Datetime" required="" name="datetime" class="form-control " />
          <input id="ctrl-assign_agent_name" value="" type="hidden" placeholder="Enter Assign Agent Name" name="assign_agent_name" class="form-control " />
          <input id="ctrl-assign_agent_phone" value="" type="hidden" placeholder="Enter Assign Agent Phone" name="assign_agent_phone" class="form-control " />


        </form>
      </div>

      <div class="mobile_img">
        <img src="./images/GFG_Lead_Portal_mobile_bg.jpg" alt="">
      </div>

    </div>

  </div>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <!--   <script src="main.js"></script> -->
</body>
 

</html>