<?php require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if (empty($full_name)) {
        $error = 'Пожалуйста, заполните имя';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$full_name, $phone, $address, $user['id']]);
            $success = 'Профиль успешно обновлен!';
            $user = getCurrentUser(); // Обновляем данные
        } catch(PDOException $e) {
            $error = 'Ошибка: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: rgba(255,255,255,0.95);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .container { max-width: 800px; margin: 2rem auto; padding: 0 20px; }
        .nav-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-links a {
            color: #333;
            text-decoration: none;
            margin-left: 30px;
            padding: 8px 16px;
            border-radius: 8px;
        }
        .profile-box {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .avatar {
            text-align: center;
            margin-bottom: 2rem;
        }
        .avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
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
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
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
        .info-text {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        footer {
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="profile-box">
            <div class="avatar">
                <img src="https://ui-avatars.com/api/?background=667eea&color=fff&size=120&name=<?= urlencode($user['full_name']) ?>" alt="Avatar">
            </div>
            
            <h2 style="text-align: center; margin-bottom: 2rem;">Мой профиль</h2>
            
            <?php if ($error): ?>
                <div class="alert error"><?= h($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert success"><?= h($success) ?></div>
            <?php endif; ?>
            
            <div class="info-text">
                <strong>Логин:</strong> <?= h($user['username']) ?><br>
                <strong>Email:</strong> <?= h($user['email']) ?><br>
                <strong>Роль:</strong> <?= $user['user_role'] === 'admin' ? 'Администратор' : 'Пользователь' ?><br>
                <strong>Дата регистрации:</strong> <?= $user['created_at'] ?>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label>Полное имя *</label>
                    <input type="text" name="full_name" value="<?= h($user['full_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="tel" name="phone" value="<?= h($user['phone']) ?>">
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <textarea name="address" rows="3"><?= h($user['address']) ?></textarea>
                </div>
                <button type="submit" class="btn">Обновить профиль</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
