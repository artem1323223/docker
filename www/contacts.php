<?php require_once 'config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, email, subject, message, priority) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            isLoggedIn() ? $_SESSION['user_id'] : null,
            $_POST['name'],
            $_POST['email'],
            $_POST['subject'],
            $_POST['message'],
            $_POST['priority']
        ]);
        
        $message = "Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.";
        
    } catch(PDOException $e) {
        $error = "Ошибка при отправке: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        .contacts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 0;
        }
        .form-section, .info-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .form-section h2, .info-section h2 {
            margin-bottom: 1.5rem;
            color: #333;
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
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .btn:hover { transform: translateY(-2px); }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info-item {
            margin-bottom: 1.5rem;
        }
        .info-item i {
            width: 30px;
            color: #667eea;
            margin-right: 10px;
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
            .contacts-grid { grid-template-columns: 1fr; gap: 20px; }
            .form-section, .info-section { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="page-container">
        <?php if ($message): ?>
            <div class="alert success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="contacts-grid">
            <div class="form-section">
                <h2><i class="fas fa-envelope"></i> Написать нам</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Ваше имя *</label>
                        <input type="text" name="name" required value="<?= isLoggedIn() ? htmlspecialchars(getCurrentUser()['full_name']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required value="<?= isLoggedIn() ? htmlspecialchars(getCurrentUser()['email']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Тема *</label>
                        <input type="text" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label>Приоритет</label>
                        <select name="priority">
                            <option value="low">Низкий</option>
                            <option value="medium" selected>Средний</option>
                            <option value="high">Высокий</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Сообщение *</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Отправить</button>
                </form>
            </div>
            
            <div class="info-section">
                <h2><i class="fas fa-address-card"></i> Контактная информация</h2>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Адрес:</strong>
                    <p style="margin-top: 5px;">г. Москва, ул. Техническая, д. 1</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <strong>Телефон:</strong>
                    <p style="margin-top: 5px;">+7 (495) 123-45-67</p>
                    <p>+7 (800) 555-35-35</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <strong>Email:</strong>
                    <p style="margin-top: 5px;">info@apt-tech.ru</p>
                    <p>support@apt-tech.ru</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <strong>Часы работы:</strong>
                    <p style="margin-top: 5px;">Пн-Пт: 9:00 - 18:00</p>
                    <p>Сб-Вс: выходной</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="page-container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>