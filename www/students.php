<?php require_once 'config.php';

$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM students ORDER BY group_name, full_name");
$students = $stmt->fetchAll();

$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalGroups = $pdo->query("SELECT COUNT(DISTINCT group_name) FROM students")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Студенты - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Сброс стилей */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Единый контейнер как на всех страницах */
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Стили для страницы студентов */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .student-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .student-card:hover { transform: translateY(-5px); }
        
        .student-photo {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .student-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        
        .student-info { padding: 1.5rem; }
        
        .student-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .student-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 8px 0;
            color: #666;
            font-size: 0.85rem;
        }
        
        .student-detail i { width: 20px; color: #667eea; }
        
        .badge-group {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-top: 10px;
        }

        .btn-details {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
            text-align: center;
            width: 100%;
        }
        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        footer {
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            .page-container { padding: 20px 15px; }
            .stats { grid-template-columns: 1fr; }
            .students-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="page-container">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Наши студенты</h1>
            <p>Талантливые и целеустремленные будущие IT-специалисты</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3><?= $totalStudents ?></h3>
                <p>Всего студентов</p>
            </div>
            <div class="stat-card">
                <h3><?= $totalGroups ?></h3>
                <p>Учебных групп</p>
            </div>
            <div class="stat-card">
                <h3>4</h3>
                <p>Курса обучения</p>
            </div>
        </div>

        <div class="students-grid">
            <?php foreach($students as $student): ?>
            <div class="student-card">
                <div class="student-photo">
                    <img src="uploads/students/<?= htmlspecialchars($student['photo']) ?>?v=<?= time() ?>" 
                         onerror="this.src='https://ui-avatars.com/api/?background=667eea&color=fff&size=100&name=<?= urlencode($student['full_name']) ?>'"
                         alt="Фото студента">
                </div>
                <div class="student-info">
                    <div class="student-name"><?= htmlspecialchars($student['full_name']) ?></div>
                    <div class="student-detail"><i class="fas fa-id-card"></i> <?= htmlspecialchars($student['student_id']) ?></div>
                    <div class="student-detail"><i class="fas fa-envelope"></i> <?= htmlspecialchars($student['email']) ?></div>
                    <div class="student-detail"><i class="fas fa-phone"></i> <?= htmlspecialchars($student['phone']) ?></div>
                    <div class="student-detail"><i class="fas fa-calendar-alt"></i> Родился: <?= date('d.m.Y', strtotime($student['birth_date'])) ?></div>
                    <div class="student-detail"><i class="fas fa-graduation-cap"></i> Группа: <?= htmlspecialchars($student['group_name']) ?>, <?= $student['course'] ?> курс</div>
                    <div class="student-detail"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($student['specialty']) ?></div>
                    <div class="badge-group"><i class="fas fa-calendar-week"></i> Поступление: <?= $student['enrollment_year'] ?></div>
                    <a href="student_view.php?id=<?= $student['id'] ?>" class="btn-details">
                        <i class="fas fa-eye"></i> Подробнее
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <div class="page-container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>