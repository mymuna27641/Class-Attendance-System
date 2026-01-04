<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $attendance_date = $_POST['attendance_date'];
    $attendance_data = $_POST['attendance'];

    foreach ($attendance_data as $student_id => $status) {
        // Check if attendance for this student on this date already exists
        $check_sql = "SELECT * FROM attendance WHERE course_id = ? AND student_id = ? AND attendance_date = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("iis", $course_id, $student_id, $attendance_date);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Update existing record
            $update_sql = "UPDATE attendance SET status = ? WHERE course_id = ? AND student_id = ? AND attendance_date = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("siis", $status, $course_id, $student_id, $attendance_date);
            $stmt_update->execute();
        } else {
            // Insert new record
            $insert_sql = "INSERT INTO attendance (course_id, student_id, attendance_date, status) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($insert_sql);
            $stmt_insert->bind_param("iiss", $course_id, $student_id, $attendance_date, $status);
            $stmt_insert->execute();
        }
    }

    header("Location: dashboard.php?message=Attendance saved successfully!");
}
?>