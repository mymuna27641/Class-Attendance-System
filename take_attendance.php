<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connection.php';
$course_id = $_GET['course_id'];

$sql = "SELECT s.id, s.name, s.roll_number FROM students s 
        JOIN course_students cs ON s.id = cs.student_id 
        WHERE cs.course_id = $course_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Take Attendance</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="attendance-container">
        <h2>Take Attendance for <?php echo date('Y-m-d'); ?></h2>
        <form action="save_attendance.php" method="post">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="attendance_date" value="<?php echo date('Y-m-d'); ?>">
            <table>
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Student Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['roll_number']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $row['id']; ?>]" value="present" required> Present</label>
                            <label><input type="radio" name="attendance[<?php echo $row['id']; ?>]" value="absent"> Absent</label>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit">Save Attendance</button>
        </form>
    </div>
</body>
</html>