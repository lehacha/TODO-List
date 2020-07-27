<?php

	// connect to the database
	$db = mysqli_connect() or die("Ошибка " . mysqli_error($db));
	
	// all post, get methods
	if (isset($_POST['save_pr_name'])) {
		$project_name = mysqli_real_escape_string($db, $_POST['project_name']);
		$id = mysqli_real_escape_string($db, $_POST['id']);
		$query = "UPDATE projects SET name = '$project_name' WHERE id = '$id';";
		mysqli_query($db, $query);
		header('location: index.php');
	}
	
	if (isset($_POST['save_task_name'])) {
		$task_name = mysqli_real_escape_string($db, $_POST['task_name']);
		$task_dl = mysqli_real_escape_string($db, $_POST['task_dl']);
		$task_id = mysqli_real_escape_string($db, $_POST['task_id']);
		$query = "UPDATE tasks SET name = '$task_name', dl = '$task_dl' WHERE id = '$task_id';";
		mysqli_query($db, $query);
		header('location: index.php');
	}
	
	if (isset($_GET['delete_project'])) {
		$id = mysqli_real_escape_string($db, $_GET['delete_project']);
		$query1 = "DELETE FROM projects WHERE id='$id';";
		$query2 = "DELETE FROM tasks WHERE project_id='$id';";
		mysqli_query($db, $query1);
		mysqli_query($db, $query2);
		header('location: index.php');
	}
	
	if (isset($_GET['delete_task'])) {
		$task_id = mysqli_real_escape_string($db, $_GET['delete_task']);
		$query1 = "SELECT tasks.id, tasks.priority
		FROM projects LEFT JOIN tasks 
		ON projects.id = tasks.project_id 
		WHERE projects.id = (SELECT project_id FROM tasks WHERE id = '$task_id')
		AND tasks.priority > (SELECT priority FROM tasks WHERE id = '$task_id');";
		$result1 = mysqli_query($db, $query1);
		if($result1){
			$num_rows = mysqli_num_rows($result1);
			for ($i = 0 ; $i < $num_rows ; ++$i)
			{
				$row = mysqli_fetch_row($result1);
				$query2 = "UPDATE tasks SET priority='$row[1]'-1 WHERE id='$row[0]';";
				$result2 = mysqli_query($db, $query2);
			}
		}
		mysqli_free_result($result1);
		$query = "DELETE FROM tasks WHERE id='$task_id';";
		mysqli_query($db, $query);
		header('location: index.php');
	}
	
	if (isset($_GET['change_status0'])) {
		$id = mysqli_real_escape_string($db, $_GET['change_status0']);
		$query = "UPDATE tasks SET status = 1 WHERE id='$id';";
		mysqli_query($db, $query);
		header('location: index.php');
	}
	
	if (isset($_GET['change_status1'])) {
		$id = mysqli_real_escape_string($db, $_GET['change_status1']);
		$query = "UPDATE tasks SET status = 0 WHERE id='$id';";
		mysqli_query($db, $query);
		header('location: index.php');
	}
	
	if (isset($_POST['add_task'])) {
		$task_name = mysqli_real_escape_string($db, $_POST['task_name']);
		if($task_name == ""){
			$task_name = "New Task";
		}
		$id = mysqli_real_escape_string($db, $_POST['id']);
		$query1 = "SELECT max(priority)
		FROM projects LEFT JOIN tasks 
		ON projects.id = tasks.project_id 
		WHERE projects.id = '$id';";
		$result1 = mysqli_query($db, $query1);
		if($result1){
			$row = mysqli_fetch_row($result1);
			$priority = $row[0] + 1;
		}
		else{
			$priority = 1;
		}
		mysqli_free_result($result1);
		$date_today = date('Y-m-d');
		$query2 = "INSERT INTO tasks(name, status, project_id, dl, priority) VALUES('$task_name', 0, '$id', '$date_today', '$priority');";
		mysqli_query($db, $query2);
		header('location: index.php');
	}
	
	if (isset($_POST['add_project'])) {
		$query = "INSERT INTO projects(name) VALUES('New project');";
		mysqli_query($db, $query);
		header('location: index.php');
	}
	mysqli_close($db);
?>