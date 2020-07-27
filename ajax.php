<?php
	// connect to the database
	$db = mysqli_connect() or die("Ошибка " . mysqli_error($db));
	// all post methods
	if (isset($_POST['priority']) && $_POST['priority']==1) {
		$task_id = mysqli_real_escape_string($db, $_POST['task_id']);
		$project_id = mysqli_real_escape_string($db, $_POST['id']);
		$query = "SELECT priority FROM tasks WHERE id='$task_id';";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_row($result);
		$priority = $row[0];
		mysqli_free_result($result);
		if($priority > 1){
			
			$query2 = "SELECT id FROM tasks WHERE project_id = '$project_id' and priority = '$priority' - 1;";
			$result2 = mysqli_query($db, $query2);
			$row2 = mysqli_fetch_row($result2);
			$down_task_id = $row2[0];
			mysqli_free_result($result2);
			
			$query1 = "UPDATE tasks 
			SET priority=priority+1 
			WHERE id=(SELECT id FROM tasks
			Where project_id = '$project_id' 
			AND priority = (SELECT priority-1 FROM tasks WHERE id='$task_id'));";
			mysqli_query($db, $query1);
			
			$query2 = "UPDATE tasks 
			SET priority=priority-1 
			WHERE id='$task_id';";
			mysqli_query($db, $query2);
	
			echo json_encode(array('success' => 1, 'pr' => $project_id, 'up' => $task_id, 'down' => $down_task_id));
		}
		else{
			echo json_encode(array('success' => 0));
		}
	}
	
	if(isset($_POST['priority']) && $_POST['priority']==-1) {
		$task_id = mysqli_real_escape_string($db, $_POST['task_id']);
		$project_id = mysqli_real_escape_string($db, $_POST['id']);
		//find max(priority)
		$query = "SELECT max(priority) FROM tasks;";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_row($result);
		$priority_max = $row[0];
		mysqli_free_result($result);
		
		$query3 = "SELECT priority FROM tasks WHERE id='$task_id';";
		$result3 = mysqli_query($db, $query3);
		$row2 = mysqli_fetch_row($result3);
		$priority = $row2[0];
		mysqli_free_result($result3);
		if($priority < $priority_max){
			$query4 = "SELECT id FROM tasks WHERE project_id = '$project_id' and priority = '$priority' + 1;";
			$result4 = mysqli_query($db, $query4);
			$row4 = mysqli_fetch_row($result4);
			$down_task_id = $row4[0];
			mysqli_free_result($result4);
			
			$query1 = "UPDATE tasks 
			SET priority=priority-1 
			WHERE id=(SELECT id FROM tasks
			Where project_id = '$project_id' 
			AND priority = (SELECT priority+1 FROM tasks WHERE id='$task_id'));";
			mysqli_query($db, $query1);
			
			$query2 = "UPDATE tasks 
			SET priority=priority+1 
			WHERE id='$task_id';";
			mysqli_query($db, $query2);
			echo json_encode(array('success' => -1, 'pr' => $project_id, 'up' => $task_id, 'down' => $down_task_id));
		}
		else{
			echo json_encode(array('success' => 0));
		}
	}
	mysqli_close($db);
?>