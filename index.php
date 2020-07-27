<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="images/lamp.png" />
	<title>Ruby Garage Test</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="test.css">
</head>
<body>
	<center><h2>SIMPLE TODO LIST</h2>
	FROM RUBY GARAGE</center>
	
		<?php  
			$db = mysqli_connect() or die("Ошибка " . mysqli_error($db));
			$query ="SELECT *
			FROM projects LEFT JOIN tasks 
			ON projects.id = tasks.project_id
			ORDER by projects.id, tasks.priority";
			if(isset($_GET['edit_project'])) {
				$pr_id = $_GET['edit_project'];
				$update_pr = true;
			}
			if(isset($_GET['edit_task'])) {
				$task_id = $_GET['edit_task'];
				$update_task = true;
			}
			$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db)); 
			if($result)
			{
				$rows = mysqli_num_rows($result); // количество полученных строк
				 
				$id = -1;
				for ($i = 0 ; $i < $rows ; ++$i)
				{
					$row = mysqli_fetch_row($result);
					if($row[0] > $id){
						if($id != -1){
							echo "</div>";
						}
						$id = $row[0];?> 
						<div class="cont_row" style="padding-top: 30px;">
							<div class="col-lg-4"></div>
							<div class="col-lg-4" id="proj">
								<div class="col-lg-1"><img src="images/note.png" width="32" height="26"></div>
								<form method="post" action="index.php">
									
									<?php if($update_pr && $pr_id == $id) { ?>
									<div class="col-lg-8">
										<input id="input_project_name" type="text" name="project_name" value="<?php echo $row[1]; ?>">
									</div>
									<?php } 
									else {?>
									<div class="col-lg-8"><?php echo $row[1]; ?></div>
									<?php } ?>
									<div class="col-lg-3">
										<ul class="nav navbar-nav" id="proj_menu">
											<?php if($update_pr && $pr_id == $id) { ?>
											<li class="nav-item">
												<input type="text" name="id" style="display:none;" value="<?php echo $id; ?>">
												<button type="submit" name="save_pr_name">
													<img src="images/save.png" width="20" height="20" title="Save">
												</button>
											</li>
											<li class="nav-item">|</li>
											<?php } ?>
											<li class="nav-item">
												<?php if($update_pr && $pr_id == $id) { ?>
													<a href="/">
												<?php } 
												else { ?>
													<a href="index.php?edit_project=<?php echo $id; ?>">
												<?php } ?>
													<img src="images/pencil.png" width="20" height="20" title="Edit">
												</a>
											</li>
											<li class="nav-item">|</li>
											<li class="nav-item">
												<a href="index.php?delete_project=<?php echo $id; ?>">
													<img src="images/tc.png" width="20" height="20" title="Delete">
												</a>
											</li>
										</ul>
									</div>
								</form>
							</div>
							<div class="col-lg-4"></div>
						</div>
						<div class="cont_row">
							<div class="col-lg-4"></div>
							<div class="col-lg-4" id="add_task">
							<form method="post" action="index.php">
								<div class="col-lg-1"><img src="images/plus.png" width="30" height="30"></div>
								<div class="col-lg-9" style="padding-right:0px;">
									<input type="text" name="id" style="display:none;" value="<?php echo $id; ?>">
									<input id="task_input" type="text" name="task_name" placeholder="Start typing here to create a task">
								</div>
								<div class="col-lg-2" style="padding-left:0px;">
									<button id="task_add" type="submit" name="add_task">Add Task</button>
								</div>
							</form>
							</div>
							<div class="col-lg-4"></div>
						</div>
						<div class="cont_column">
					<?php 
					}
					if($row[3]) {
					?>
					<div class="cont_row" id="task_<?php echo $row[0]; ?>_<?php echo $row[2]; ?>" style="order:<?php echo $row[7]; ?>;">
						<div class="col-lg-4"></div>
						<div class="col-lg-4" id="task">
							<?php if($row[4] == 0) { ?>
							<div class="col-lg-1" style="border-right: 1px solid grey;">
								<a href="index.php?change_status0=<?php echo $row[2]; ?>">
									<img src="images/checkbox0.png" width="20" height="20" style="filter: invert(0.30);">
								</a>
							</div>
							<? } ?>
							<?php if($row[4] == 1) { ?>
							<div class="col-lg-1" style="border-right: 1px solid grey;">
								<a href="index.php?change_status1=<?php echo $row[2]; ?>">
									<img src="images/checkbox1.png" width="20" height="20" style="filter: invert(0.30);">
								</a>
							</div>
							<? } ?>
							<?php if($update_task && $task_id == $row[2]) { ?>
								<form method="post" action="index.php" >
								<div class="col-lg-3">
									<input id="input_task_name" type="text" name="task_name" value="<?php echo $row[3]; ?>">
								</div>
								<div class="col-lg-3" style="padding-left:0px;">
									<input id="input_task_dl" type="date" name="task_dl" value="<?php echo $row[6]; ?>">
								</div>
							<?php } 
							else { ?>
								<div class="col-lg-3"><?php echo $row[3]; ?></div>
								<div class="col-lg-3"><?php echo $row[6]; ?></div>
							<?php } ?>
								<div class="col-lg-5" style="border-left: 1px solid grey;">
									<ul class="nav navbar-nav" id="task_menu">
										<?php if($update_task && $task_id == $row[2]) { ?>
											<li class="nav-item">
												<input type="text" name="task_id" style="display:none;" value="<?php echo $row[2]; ?>">
												<button type="submit" name="save_task_name">
													<img src="images/save.png" width="20" height="20" title="Save">
												</button>
												</form>
											</li>
											<li class="nav-item">|</li>
										<?php } ?>
										<li class="nav-item">
											<?php if($update_task && $task_id == $row[2]) { ?>
												<a href="/">
											<?php } 
											else { ?>
												<a href="index.php?edit_task=<?php echo $row[2]; ?>">
											<?php } ?>
												<img src="images/pencil_bl.png" width="20" height="20" title="Edit" style="filter: invert(0.40);">
											</a>
										</li>
											<li class="nav-item">|</li>
											<li class="nav-item">
												<a href="index.php?delete_task=<?php echo $row[2]; ?>">
													<img src="images/tc_bl.png" width="20" height="20" title="Delete" style="filter: invert(0.40);">
												</a>
											</li>
											<li class="nav-item">|</li>
											<li class="nav-item">
											<form class="change_priority_up" method="post">
												<input type="text" name="task_id" style="display:none;" value="<?php echo $row[2]; ?>">
												<input type="text" name="id" style="display:none;" value="<?php echo $id; ?>">
												<button type="submit" name="up">
													<img src="images/up.png" width="20" height="20" title="Up priority">
												</button>
											</form>
											</li>
											<li class="nav-item">
											<form class="change_priority_down" method="post">
												<input type="text" name="task_id" style="display:none;" value="<?php echo $row[2]; ?>">
												<input type="text" name="id" style="display:none;" value="<?php echo $id; ?>">
												<button type="submit" name="down">
													<img src="images/up.png" width="20" height="20" title="Down priority" style="transform: scaleY(-1);">
												</button>
											</form>
											</li>
									</ul>
								</div>
							
						</div>
						<div class="col-lg-4"></div>
					</div>
					
				<?php 
					}
				}
				echo "</div>";
				// очищаем результат
				mysqli_free_result($result);
			}
			 
			mysqli_close($db);
		?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.change_priority_up').submit(function(e) {
				
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: 'ajax.php',
					data: $(this).serialize() + "&priority=1",
					success: function(response)
					{
						var jsonData = JSON.parse(response);
		 
						if (jsonData.success != 0)
						{
							let id = "#task_" + jsonData.pr + "_" + jsonData.up;
							let id_down = "#task_" + jsonData.pr + "_" + jsonData.down;
							console.log(id + " : insertBEFORE : " + id_down);
							$(id).css("order", parseInt($(id).css("order")) - 1);
							$(id_down).css("order", parseInt($(id_down).css("order")) + 1);
						}
						else
						{
							console.log("NOTHING");
						}
				   }
			   });
			 });
			 $('.change_priority_down').submit(function(e) {
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: 'ajax.php',
					data: $(this).serialize() + "&priority=-1",
					success: function(response)
					{
						var jsonData = JSON.parse(response);
		 
						if (jsonData.success != 0)
						{
							let id = "#task_" + jsonData.pr + "_" + jsonData.up;
							let id_down = "#task_" + jsonData.pr + "_" + jsonData.down;
							$(id).css("order", parseInt($(id).css("order")) + 1);
							$(id_down).css("order", parseInt($(id_down).css("order")) - 1);
						}
						else
						{
							console.log("NOTHING");
						}
				   }
			   });
			 });
			 
		});
	</script>
	<form method="post" action="index.php">
		<center><button type="submit" class="add_project" name="add_project">Add TODO List</button></center>
	</form>
	
	<div style="color: #e5e5e5; margin-top: 30px;margin-bottom: 20px;"><center>&copy;Ruby Garage</center></div>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>