<?php
//Code by Bcat95
include 'getImages.php';
?>
<?php
 function connectData(){
   //conect to mysqli
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "xinh-app";
   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
       die("Status: Data Connection failed: " . $conn->connect_error."<br/>");
   }
   return $conn;
 }
?>



<form class="form-horizontal" method="post">
    <button type="submit" name="Submit">Get Data Post</button>
</form>
<?php
  if(isset($_POST['Submit'])) {
    getJson('');
  }
?>
<?php
function getJson($api){
  //get 100 post / page
  $soluong=25;
  if ($api != ''){
    $url= $api;
  } else{
    $url_api = "https://graph.facebook.com/v2.10/1173636692750000/feed?fields=id,full_picture,created_time,message,likes,from,place,object_id&limit=".$soluong."&access_token=";
    $access_token= "";
    $url= $url_api."".$access_token;
  }
  $response = get_web_page($url);
  $resArr = array();//tao bien dang mang
  $resArr = json_decode($response,true); //decode response qua mang
  $data = $resArr['data'];
  if ($data) {
    for ($i=0 ; $i<sizeof($data); $i++){
      //post($data[$i]);
      updatePost($data[$i]);//update function
    }
  }
  $paging = $resArr['paging'];
  if ($paging) {
      $pagingNext = $paging['next'];
      getJson($pagingNext);
  };
}


function post($post){
  // chi luu nhung post co anh
  if ($post['full_picture']){
    checkPost($post);
  }
}

function updatePost($post){
  $conn = connectData();
  $from= $post['from'];
  //insert data to mysql
  $id_post = $post['id'];
  $user_id = $from['id'];
  $user_name = $from['name'];
  if (!empty($post['place'])) {
    // object exists in array; do something
    $place = $post['place'];
  } else {
    // object does not exist in array; do something else
    $place = '';
  }
  $object_id = $post['object_id'];
  $sql = "UPDATE post SET user_id='$user_id', user_name=N'$user_name', place=N'$place', object_id='$object_id'
  Where (id_post = '$id_post')";

  if ($conn->query($sql) === TRUE) {
      echo $id_post." record update successfully</br>";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}

function insertPost($post){
  $conn = connectData();
  //insert data to mysql
  $id_post = $post['id'];
  $full_picture = $post['full_picture'];
  $full_picture = getImages($full_picture,$id_post);
  $created_time = $post['created_time'];
  $message = $post['message'];
  $likes = $post['likes']['count'];
  $sql = "INSERT INTO post (id_post, full_picture, created_time, message, likes)
  VALUES ('$id_post', '$full_picture', '$created_time', N'$message', '$likes')";

  if ($conn->query($sql) === TRUE) {
      echo $id_post." record created successfully</br>";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}

function checkPost($post){
  $conn = connectData();
  $id_post = $post['id'];
  $sql = "SELECT `id_post` FROM post WHERE `id_post` = '$id_post'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) == 0) {
      while($row = mysqli_fetch_assoc($result)) {
        insertPost($row);
      }
    } else {
      echo " Trung khoa chinh <br/>";
      return false;
  }
  mysqli_close($conn);
}

function get_web_page($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 0,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content  = curl_exec($ch);
    curl_close($ch);
    return $content;
}
?>
