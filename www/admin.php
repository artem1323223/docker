<?php
require_once 'config.php';

// Проверка прав администратора
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$pdo = getDBConnection();
$active_tab = $_GET['tab'] ?? 'students';
$message = '';
$error = '';

// Обработка загрузки фото для студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo_student'])) {
    $student_id = $_POST['student_id'];
    
    $upload_dir = 'uploads/students/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = 'student_' . $student_id . '_' . time() . '.' . $ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $stmt = $pdo->prepare("UPDATE students SET photo = ? WHERE id = ?");
                $stmt->execute([$new_filename, $student_id]);
                $message = "Фото успешно загружено!";
            } else {
                $error = "Ошибка при загрузке файла";
            }
        } else {
            $error = "Разрешенные форматы: JPG, PNG, GIF";
        }
    } else {
        $error = "Выберите файл для загрузки";
    }
}

// Обработка загрузки фото для преподавателя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    
    $upload_dir = 'uploads/teachers/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = 'teacher_' . $teacher_id . '_' . time() . '.' . $ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $stmt = $pdo->prepare("UPDATE teachers SET photo = ? WHERE id = ?");
                $stmt->execute([$new_filename, $teacher_id]);
                $message = "Фото успешно загружено!";
            } else {
                $error = "Ошибка при загрузке файла";
            }
        } else {
            $error = "Разрешенные форматы: JPG, PNG, GIF";
        }
    } else {
        $error = "Выберите файл для загрузки";
    }
}

// Добавление студента
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, email, phone, birth_date, address, group_name, course, specialty, enrollment_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([
            $_POST['student_id'],
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['birth_date'],
            $_POST['address'],
            $_POST['group_name'],
            $_POST['course'],
            $_POST['specialty'],
            $_POST['enrollment_year']
        ]);
        $message = "Студент успешно добавлен!";
        header('Location: admin.php?tab=students&success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Ошибка: " . $e->getMessage();
    }
}

// Добавление преподавателя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $stmt = $pdo->prepare("INSERT INTO teachers (teacher_id, full_name, email, phone, birth_date, address, position, department, degree, experience_years, specialization) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([
            $_POST['teacher_id'],
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['birth_date'],
            $_POST['address'],
            $_POST['position'],
            $_POST['department'],
            $_POST['degree'],
            $_POST['experience_years'],
            $_POST['specialization']
        ]);
        $message = "Преподаватель успешно добавлен!";
        header('Location: admin.php?tab=teachers&success=1');
        exit;
    } catch(PDOException $e) {
        $error = "Ошибка: " . $e->getMessage();
    }
}

// Удаление студента
if (isset($_GET['delete_student'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$_GET['delete_student']]);
    header('Location: admin.php?tab=students');
    exit;
}

// Удаление преподавателя
if (isset($_GET['delete_teacher'])) {
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->execute([$_GET['delete_teacher']]);
    header('Location: admin.php?tab=teachers');
    exit;
}

// Получение данных
$students = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll();
$teachers = $pdo->query("SELECT * FROM teachers ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-header {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .admin-header h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 10px 25px;
            background: #f0f0f0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .tab-content {
            display: none;
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
        }
        .tab-content.active {
            display: block;
        }
        
        /* Кнопка добавления */
        .add-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .add-btn:hover {
            transform: translateY(-2px);
        }
        
        /* Модальное окно */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 85vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
        }
        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
        }
        
        /* Карточки */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 1rem;
        }
        .card {
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
            padding: 1rem;
        }
        .card-photo {
            text-align: center;
            margin-bottom: 1rem;
        }
        .card-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .card-photo form {
            margin-top: 10px;
        }
        .card-photo input[type="file"] {
            margin: 5px 0;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }
        .btn-sm {
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert.success { background: #d4edda; color: #155724; }
        .alert.error { background: #f8d7da; color: #721c24; }
        
        footer {
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        @media (max-width: 768px) {
            .page-container { padding: 10px; }
            .admin-header { flex-direction: column; text-align: center; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="page-container">
        <div class="admin-header">
            <h1><i class="fas fa-shield-alt"></i> Админ-панель</h1>
            <div class="tabs">
                <button class="tab-btn <?= $active_tab == 'students' ? 'active' : '' ?>" onclick="showTab('students')">👨‍🎓 Студенты</button>
                <button class="tab-btn <?= $active_tab == 'teachers' ? 'active' : '' ?>" onclick="showTab('teachers')">👨‍🏫 Преподаватели</button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Вкладка Студенты -->
        <div id="tab-students" class="tab-content <?= $active_tab == 'students' ? 'active' : '' ?>">
            <button class="add-btn" onclick="openModal('student')">
                <i class="fas fa-plus-circle"></i> Добавить студента
            </button>

            <div class="cards-grid">
                <?php foreach($students as $student): ?>
                <div class="card">
                    <div class="card-photo">
                        <img src="uploads/students/<?= htmlspecialchars($student['photo']) ?>?v=<?= time() ?>" 
                             onerror="this.src='https://ui-avatars.com/api/?background=667eea&color=fff&size=100&name=<?= urlencode($student['full_name']) ?>'">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                            <input type="file" name="photo" accept="image/*" required>
                            <button type="submit" name="upload_photo_student" class="btn btn-primary btn-sm">Загрузить фото</button>
                        </form>
                    </div>
                    <h4><?= htmlspecialchars($student['full_name']) ?></h4>
                    <p><strong>Студбилет:</strong> <?= htmlspecialchars($student['student_id']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                    <p><strong>Группа:</strong> <?= htmlspecialchars($student['group_name']) ?>, <?= $student['course'] ?> курс</p>
                    <a href="?delete_student=<?= $student['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Удалить студента?')">Удалить</a>
                    <a href="student_view.php?id=<?= $student['id'] ?>" target="_blank" class="btn btn-primary btn-sm">Просмотр</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Вкладка Преподаватели -->
        <div id="tab-teachers" class="tab-content <?= $active_tab == 'teachers' ? 'active' : '' ?>">
            <button class="add-btn" onclick="openModal('teacher')">
                <i class="fas fa-plus-circle"></i> Добавить преподавателя
            </button>

            <div class="cards-grid">
                <?php foreach($teachers as $teacher): ?>
                <div class="card">
                    <div class="card-photo">
                        <img src="uploads/teachers/<?= htmlspecialchars($teacher['photo']) ?>?v=<?= time() ?>" 
                             onerror="this.src='https://ui-avatars.com/api/?background=764ba2&color=fff&size=100&name=<?= urlencode($teacher['full_name']) ?>'">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>">
                            <input type="file" name="photo" accept="image/*" required>
                            <button type="submit" name="upload_photo_teacher" class="btn btn-primary btn-sm">Загрузить фото</button>
                        </form>
                    </div>
                    <h4><?= htmlspecialchars($teacher['full_name']) ?></h4>
                    <p><strong>Должность:</strong> <?= htmlspecialchars($teacher['position']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($teacher['email']) ?></p>
                    <p><strong>Кафедра:</strong> <?= htmlspecialchars($teacher['department']) ?></p>
                    <a href="?delete_teacher=<?= $teacher['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Удалить преподавателя?')">Удалить</a>
                    <a href="teacher_view.php?id=<?= $teacher['id'] ?>" target="_blank" class="btn btn-primary btn-sm">Просмотр</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Модальное окно для добавления студента -->
    <div id="modal-student" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-graduate"></i> Добавление студента</h2>
                <button class="close-modal" onclick="closeModal('student')">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>ФИО *</label>
                            <input type="text" name="full_name" placeholder="Иванов Иван Иванович" required>
                        </div>
                        <div class="form-group">
                            <label>Номер студбилета *</label>
                            <input type="text" name="student_id" placeholder="ST-2024-001" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" placeholder="student@apt.ru" required>
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" name="phone" placeholder="+7 (999) 123-45-67">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Дата рождения</label>
                            <input type="date" name="birth_date">
                        </div>
                        <div class="form-group">
                            <label>Группа *</label>
                            <input type="text" name="group_name" placeholder="П-41" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Курс</label>
                            <select name="course">
                                <option value="1">1 курс</option>
                                <option value="2">2 курс</option>
                                <option value="3">3 курс</option>
                                <option value="4">4 курс</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Год поступления</label>
                            <input type="number" name="enrollment_year" placeholder="2024">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Специальность *</label>
                        <input type="text" name="specialty" placeholder="Программная инженерия" required>
                    </div>
                    <div class="form-group">
                        <label>Адрес</label>
                        <textarea name="address" rows="2" placeholder="г. Москва, ул. Примерная, д. 1"></textarea>
                    </div>
                    <button type="submit" name="add_student" class="submit-btn">Добавить студента</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Модальное окно для добавления преподавателя -->
    <div id="modal-teacher" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-chalkboard-user"></i> Добавление преподавателя</h2>
                <button class="close-modal" onclick="closeModal('teacher')">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>ФИО *</label>
                            <input type="text" name="full_name" placeholder="Смирнов Сергей Викторович" required>
                        </div>
                        <div class="form-group">
                            <label>Табельный номер *</label>
                            <input type="text" name="teacher_id" placeholder="TCH-001" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" placeholder="teacher@apt.ru" required>
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" name="phone" placeholder="+7 (495) 123-45-67">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Дата рождения</label>
                            <input type="date" name="birth_date">
                        </div>
                        <div class="form-group">
                            <label>Должность *</label>
                            <input type="text" name="position" placeholder="Профессор / Доцент / Преподаватель" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Кафедра *</label>
                            <input type="text" name="department" placeholder="Кафедра программирования" required>
                        </div>
                        <div class="form-group">
                            <label>Ученая степень</label>
                            <input type="text" name="degree" placeholder="Доктор / Кандидат наук">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Стаж (лет)</label>
                            <input type="number" name="experience_years" placeholder="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Специализация</label>
                        <textarea name="specialization" rows="2" placeholder="Java, Python, Алгоритмы"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Адрес</label>
                        <textarea name="address" rows="2" placeholder="г. Москва, ул. Профессорская, д. 1"></textarea>
                    </div>
                    <button type="submit" name="add_teacher" class="submit-btn">Добавить преподавателя</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="page-container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>

    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`tab-${tab}`).classList.add('active');
            event.target.classList.add('active');
        }

        function openModal(type) {
            document.getElementById(`modal-${type}`).style.display = 'flex';
        }

        function closeModal(type) {
            document.getElementById(`modal-${type}`).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>