<?php
require "conn.php";
require 'header.php';
?>
<div class="main-container">
	<div class="container">
			<div class="row">
				<div class="col-md-2 hidden-sm" id="leftCol">
					<div class="ui comments">
							<div class="comment">
							</div>
					</div>
				</div>
				<div class="col-md-8 col-sm-12">
					<div class="thread-list">
						<?php
							$sql = "SELECT * FROM post order by rand() limit 1";
							if(isset($_GET['id_post'])) {
								if ($_GET['id_post']!=0){
									$id_post_get=$_GET['id_post'];
									$sql = "SELECT * FROM post where `id_post`='$id_post_get' limit 1";
									}
							}
							getBySql($sql);

						?>

			     </div>
				</div>
				<div class="col-md-2 hidden-sm" id="rightCol">
					<div class="ui list-ite">
							<div class="">
							</div>
					</div>
				</div>
      </div>
  </div>
</div>
<?php
require 'footer.php';
?>

<?php
function getBySql($sql){
	global $conn;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$message = str_replace("#VSBG","",$row["message"]);
				$title = $row["user_name"].' - '.substr($message,  0, 120);
				$id_post = $row["id_post"];
				$full_picture = $row["full_picture"];
				$date = strtotime($row["created_time"]);
				$user_name = $row["user_name"];
				$user_id = $row["user_id"];
				$nextid= nextPost($row["created_time"],'1');
				$previd= nextPost($row["created_time"],'0');
			?>
	<!-- itmes -->
	<style>
		body{
			background-image: url(https://nhatkythuthuat.com/bc-app/vsbg/<?php echo $full_picture;?>);
		}
	</style>
	<div class="pos-nex"><a href="?id_post=<?php echo $nextid;?>">Next</a></div>
	<div class="pos-pre"><a href="?id_post=<?php echo $previd;?>">Prev</a></div>
	<div class="items ui segment cbds-box">
		<div class="info ui labeled " tabindex="0">
			<a title="Trang facebook của <?php echo $user_name;?>" target="_blank" href="https://123link.top/st/?api=eddeade9b9804c72855f66dd2fafd771b4e4a320&url=https://facebook.com/<?php echo $user_id;?>"><?php echo $user_name;?></a>
			<div class="ui ite-timl">
					<?php echo date("F j, Y, g:i a", $date);?></div>
			</div>
		<div class="ite-inn cbds-box-bod">
			<div class="ite-des">
					<p><?php echo $message;?></p>
			</div>
			<img class="ui fluid image" src="https://nhatkythuthuat.com/bc-app/vsbg/<?php echo $full_picture;?>">
		</div>
		<div class="ui divider"></div>
		<!--
		<div class="row">
			<div class="col-md-6">
				<a class="ui basic label" href="#">Thích</a>
				<a class="ui basic label" href="#">Bình luận</a>
						<a class="ui basic label" href="#">Chia sẻ
						</a>
			</div>
			<div class="col-md-6 text-right">
				11 <i class="comment outline icon" style="margin-right: 10px;"></i>
				17 <i class="empty heart icon"></i>
			</div>
		</div>
		-->
	</div>
	<!-- /itmes -->
	<?php }
	}else{
	echo "<center>Không có dữ liệu</center>";
	}
}

function nextPost($created_time,$next){
	global $conn;
	if ($next){
		$sql= "SELECT `id_post` FROM post WHERE created_time < '$created_time' ORDER BY created_time DESC LIMIT 1";
	}else{
		$sql= "SELECT `id_post` FROM post WHERE created_time > '$created_time' ORDER BY created_time ASC LIMIT 1";
	}
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {return  $row["id_post"];  }
	}else{
		return 0;
	}
}
