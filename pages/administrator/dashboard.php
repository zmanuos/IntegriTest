<?php
    include 'header.php';
    include '../../includes/authentication.php';
    include '../../models/students.php';
    include '../../models/teachers.php';
    include '../../models/groups.php';


Student::setConnection($conn);
$student = new Student();
$totalStudents = $student->getTotalStudents();

Teacher::setConnection($conn);
$teacher = new Teacher();
$totalTeachers = $teacher->getTotalTeachers();

Group::setConnection($conn);
$totalGroups = Group::getTotalGroups();

$topGroup = Group::getTopGroupAverage();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../../images/favicon/favicon.ico" type="image/x-icon">
    <title>Dashboard</title>
</head>
<body>
<div id="containers-title">
        <label for="">Dashboard</label>
    </div>
    <div class="dashboard-container">
        <div class="box student">
            <i class="fas fa-user-graduate icon"></i>
                <div>
                    <h3 id="students-count"><?php echo $totalStudents; ?></h3>
                    <p>Estudiantes Registrados</p>
                </div>
        </div>
        
        <div class="box teacher">
            <i class="fas fa-chalkboard-teacher icon"></i>
            <div>
                <h3 id="teachers-count"><?php echo $totalTeachers?></h3>
                <p>Docentes Registrados</p>
            </div>
        </div>
        
        <div class="box groups">
            <i class="fas fa-users icon"></i>
            <div>
                <h3 id="groups-count"><?php echo $totalGroups?></h3>
                <p>Cantidad de Grupos</p>
            </div>
        </div>
        
        <div class="box highest">
        <div>
    <h3>Promedio más Alto</h3>
    <p>
        Grupo <?php echo htmlspecialchars($topGroup['grupo']); ?> 
        <i class="fas fa-star icon"></i> 
        <?php echo htmlspecialchars($topGroup['promedio']); ?>
    </p>
</div>
        </div>
    </div>
</body>
</html>
