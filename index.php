<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $lat      = $_POST['latitude'];
    $lng      = $_POST['longitude'];
    $address  = $_POST['address'];
    $data = array();
    $jsonurl='https://api.weatherbit.io/v2.0/forecast/daily?&lat=' . $lat . '&lon=' . $lng . '&days=7&key=8768394a95814de0889912f66c9faa89'; 
    $json = file_get_contents($jsonurl);  
    $data = json_decode($json, TRUE); 

    $city    =  $data['city_name'];
    $country =  $data['country_code'];
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Weathermap</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css">
  </head>
  <body>
    <div id="postion">
      
    </div>
    <div class="container">
      <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {?>
      <div class="container">
        <h1 class="text-center">
          <?php echo "<span>".$city . " [ " . $country . " ] </span>" . " 's Week Forecast Weather"?>
        </h1>
        <div class="table-responsive">
          <table class="main-table text-center table table-bordered">
            <tr>
              <th>Date</th>
              <th>Average Temperature</th>             
              <th>Min Temperature</th>
              <th>Max Temperature</th>             
              <th>Wind Direction</th>
              <th>Weather</th>
            </tr>
            <?php  
              foreach ($data['data'] as $temp => $value) {
                  echo '<tr>';
                    echo '<td>' . $value['datetime'] . '</td>';
                    echo '<td>' . $value['temp'] . ' <span class="deg">&deg;C</span>' . '</td>';
                    echo '<td>' . $value['min_temp'] . '<span class="deg">&deg;C</span>' . '</td>';
                    echo '<td>' . $value['max_temp'] . '<span class="deg">&deg;C</span>' . '</td>';
                    echo '<td>' . $value['wind_cdir_full'] . '</td>';
                    echo '<td>' ;
                          echo "<span>".$value['weather']['description'] . '<img src="icons/' . $value['weather']['icon'] . '.png"width="20%"></span>'; 
                    echo  '</td>';
                  echo '</tr>';               
              }
            ?>

          </table>
        </div>
      </div>
      <?php }?>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <div class="form-group form-group-lg row">        
              <label class="col-sm-2  label-control lg">Location latitude</label>
              <div class="col-sm-10 col-md-5">  
                <input type="text" name="latitude"  class="form-control input-lg" autocomplete="off"  id="lat" placeholder="Location latitude"<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                          echo "value='" . $lat ."'" ;} ?> >
              </div>
          </div> 
          <div class="form-group form-group-lg row">        
              <label class="col-sm-2  label-control">Location longitude</label>
              <div class="col-sm-10 col-md-5">
                 <input type="text" name="longitude" class="form-control input-lg" autocomplete="off"  id="lng" placeholder="Location longitude" <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                          echo "value='" . $lng ."'" ;} ?> >
                 <input type="text" name="address" id="address" <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                          echo "value='" . $address ."'" ;} ?>  style="display: none;" >
              </div>
          </div>                  
          <div class="form-group">        
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" id='submit' value="Show Weather" class="btn btn-primary" style="background: #7471B8;color: #fff">     
            </div>
          </div>
      </form>
    </div>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map"></div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 ">
            <div class="copyright-text">
            <p>Copyright © 2018 Created By <strong><a href="https://www.facebook.com/mohamed.elkholany.1" target="_blank">Mohamed Elkholany</a></strong></p>
            </div>
          </div> <!-- End Col -->
          <div class="col-sm-6">              
            <ul class="social-link text-center">
            <li><a href="https://www.facebook.com/mohamed.elkholany.1" target="_blank"><span class="fa fa-facebook"></span></a></li>           
            <li><a href="https://www.linkedin.com/in/mohamedelkholany/" target="_blank"><span class="fa fa-linkedin"></span></a></li>           
            <li><a href="https://github.com/MohamedElkholany" target="_blank"><span class="fa fa-github"></span></a></li>           
            <li><a href="https://plus.google.com/108713325535278016765" target="_blank"><span class="fa fa-google-plus"></span></a></li>           
            </ul>             
          </div> <!-- End Col -->
        </div>
      </div>
    </div>  
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
	<script async defer 
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAE24hUkjrpIDCO4IIKZkTzqJ_7K2YDq4&libraries=places&callback=initMap" type="text/javascript">
	</script>
</body>
</html>