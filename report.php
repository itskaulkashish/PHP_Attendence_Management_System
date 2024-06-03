<?php

//report.php

if(isset($_GET["action"]))
{
	include('database_connection.php');
	require_once 'admin/pdf.php';
	session_start();
	if($_GET["action"] == "attendance_report")
	{
		if(isset($_GET["from_date"], $_GET["to_date"]))
		{
			$pdf = new Pdf();
			$query = "
			SELECT attendance_date FROM attendance_table 
			WHERE teacher_id = '".$_SESSION["teacher_id"]."' 
			AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."')
			GROUP BY attendance_date 
			ORDER BY attendance_date ASC
			";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$output = '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br />';
			foreach($result as $row)
			{
				$output .= '
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			        	<td><b>Date - '.$row["attendance_date"].'</b></td>
			        </tr>
			        <tr>
			        	<td>
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Student Name</b></td>
			        				<td><b>Roll Number</b></td>
			        				<td><b>Class</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';
				$sub_query = "
				SELECT * FROM attendance_table
			    INNER JOIN student_table 
			    ON student_table.student_id = attendance_table.student_id 
			    INNER JOIN class_table 
			    ON class_table.class_id = student.student_class_id 
			    WHERE teacher_id = '".$_SESSION["teacher_id"]."' 
				AND attendance_date = '".$row["attendance_date"]."'
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["student_name"].'</td>
						<td>'.$sub_row["student_roll_number"].'</td>
						<td>'.$sub_row["class_name"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}
				$output .= '
					</table>
					</td>
					</tr>
				</table><br />
				';
			}
			$file_name = 'Attendance Report.pdf';
			$pdf->loadHtml($output);
			$pdf->render();
			$pdf->stream($file_name, array("Attachment" => false));
			exit(0);
		}
	}

	if($_GET["action"] == "student_report")
	{
		if(isset($_GET["student_id"], $_GET["from_date"], $_GET["to_date"]))
		{
			$pdf = new Pdf();
			$query = "
			SELECT * FROM student_table
			INNER JOIN class_table
			ON class_table.class_id = student_table.student_class_id 
			WHERE student_table.student_id = '".$_GET["student_id"]."' 
			";

			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$output = '';
			foreach($result as $row)
			{
				$output .= '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br /><br />
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			            <td width="25%"><b>Student Name</b></td>
			            <td width="75%">'.$row["student_name"].'</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>Roll Number</b></td>
			            <td width="75%">'.$row["student_roll_number"].'</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>Class</b></td>
			            <td width="75%">'.$row["class_name"].'</td>
			        </tr>
			        <tr>
			        	<td colspan="2" height="5">
			        		<h3 align="center">Attendance Details</h3>
			        	</td>
			        </tr>
			        <tr>
			        	<td colspan="2">
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Attendance Date</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';
				$sub_query = "
				SELECT * FROM attendance_table 
				WHERE student_id = '".$_GET["student_id"]."' 
				AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."') 
				ORDER BY attendance_date ASC
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["attendance_date"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}
				$output .= '
						</table>
					</td>
					</tr>
				</table>
				';
				$file_name = 'Attendance Report.pdf';
				$pdf->loadHtml($output);
				$pdf->render();
				$pdf->stream($file_name, array("Attachment" => false));
				exit(0);
			}
		}
	}
}


?>