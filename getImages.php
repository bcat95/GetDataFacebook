<?php
  function getImages($imageUrl,$imageName){
    @$rawImage = file_get_contents($imageUrl);
    if($rawImage){
      file_put_contents("images/".$imageName.'.jpg',$rawImage);
      return  "images/".$imageName.".jpg";
    } else{
    return 'images/noimage.jpg';
    }
  }
?>
