<?php

//attendance_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
        //query to fetch particular teacher grade attendande data
		$query = "
		SELECT * FROM attendance_table 
		INNER JOIN student_table 
		ON student_table.student_id = attendance_table.student_id 
		INNER JOIN class_table 
		ON class_table.class_id = student_table.student_class_id 
		WHERE attendance_table.teacher_id = '".$_SESSION["teacher_id"]."' AND (
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			student_table.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR student_table.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR attendance_table.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR attendance_table.attendance_date LIKE "%'.$_POST["search"]["value"].'%") 
			';
		}
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
			';
		}
		else
		{
			$query .= '
			ORDER BY attendance_table.attendance_id DESC 
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach($result as $row)
		{
			$sub_array = array();
			$status = '';
			if($row["attendance_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}

			if($row["attendance_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}

			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["class_name"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'attendance_table'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "Add")
	{
		$attendance_date = '';
		$error_attendance_date = '';
		$error = 0;
		if(empty($_POST["attendance_date"]))
		{
			$error_attendance_date = 'Attendance Date is required';
			$error++;
		}
		else
		{
			$attendance_date = $_POST["attendance_date"];
		}

		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_attendance_date'			=>	$error_attendance_date
			);
		}
		else
		{
			$student_id = $_POST["student_id"];
			$query = '
			SELECT attendance_date FROM attendance_table 
			WHERE teacher_id = "'.$_SESSION["teacher_id"].'" 
			AND attendance_date = "'.$attendance_date.'"
			';
			$statement = $connect->prepare($query);
			$statement->execute();
			if($statement->rowCount() > 0)
			{
				$output = array(
					'error'					=>	true,
					'error_attendance_date'	=>	'Attendance Data Already Exists on this date'
				);
			}
			else
			{
				for($count = 0; $count < count($student_id); $count++)
				{
					$data = array(
						':student_id'			=>	$student_id[$count],
						':attendance_status'	=>	$_POST["attendance_status".$student_id[$count].""],
						':attendance_date'		=>	$attendance_date,
						':teacher_id'			=>	$_SESSION["teacher_id"]
					);

					$query = "
					INSERT INTO attendance_table
					(student_id, attendance_status, attendance_date, teacher_id) 
					VALUES (:student_id, :attendance_status, :attendance_date, :teacher_id)
					";
					$statement = $connect->prepare($query);
					$statement->execute($data);
				}
				$output = array(
					'success'		=>	'Data Added Successfully',
				);
			}
		}
		echo json_encode($output);
	}

	if($_POST["action"] == "index_fetch")
	{
		$query = "
		SELECT * FROM attendance_table 
		INNER JOIN student_table 
		ON student_table.student_id = attendance_table.student_id 
		INNER JOIN class_table
		ON class_table.class_id = student_table.student_class_id 
		WHERE attendance_table.teacher_id = '".$_SESSION["teacher_id"]."' AND (
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			student_table.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR student_table.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR class_table.class_name LIKE "%'.$_POST["search"]["value"].'%" )
			';
		}
		$query .= 'GROUP BY student_table.student_id ';
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
			';
		}
		else
		{
			$query .= '
			ORDER BY student_table.student_roll_number ASC 
			';
		}

		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["class_name"];
			$sub_array[] = get_attendance_percentage($connect, $row["student_id"]);
			$sub_array[] = '<button type="button" name="report_button" id="'.$row["student_id"].'" class="btn btn-info btn-sm report_button">Report</button>';
			$data[] = $sub_array;
		}
		$output = array(
			'draw'					=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}
}


?>