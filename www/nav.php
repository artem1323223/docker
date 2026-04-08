<?php
// Единая навигация для всех страниц
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="index.php" class="logo">АПТ Техникум</a>
        </div>
        <button class="nav-toggle" id="navToggle">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-menu" id="navMenu">
            <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Главная</a></li>
            <li><a href="about.php" class="<?= $current_page == 'about.php' ? 'active' : '' ?>">О нас</a></li>
            <li><a href="students.php" class="<?= $current_page == 'students.php' ? 'active' : '' ?>">Студенты</a></li>
            <li><a href="teachers.php" class="<?= $current_page == 'teachers.php' ? 'active' : '' ?>">Преподаватели</a></li>
            <li><a href="messages.php" class="<?= $current_page == 'messages.php' ? 'active' : '' ?>">Сообщения</a></li>
            <li><a href="contacts.php" class="<?= $current_page == 'contacts.php' ? 'active' : '' ?>">Контакты</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">Профиль</a></li>
                <?php if (isAdmin()): ?>
                    <li><a href="admin.php" class="<?= $current_page == 'admin.php' ? 'active' : '' ?>">
                        <i class="fas fa-shield-alt"></i> Админ-панель
                    </a></li>
                <?php endif; ?>
                <li><a href="logout.php">Выйти</a></li>
            <?php else: ?>
                <li><a href="login.php" class="<?= $current_page == 'login.php' ? 'active' : '' ?>">Войти</a></li>
                <li><a href="register.php" class="<?= $current_page == 'register.php' ? 'active' : '' ?>">Регистрация</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
/* ===== СТИЛИ ТОЛЬКО ДЛЯ ШАПКИ ===== */
.navbar {
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    width: 100%;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
    min-height: 70px;
    max-height: 70px;
}

.nav-brand .logo {
    font-size: 1.5rem;
    font-weight: bold;
    text-decoration: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    white-space: nowrap;
}

.nav-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #667eea;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 5px;
}

.nav-menu li {
    margin: 0;
}

.nav-menu li a {
    display: block;
    padding: 8px 18px;
    text-decoration: none;
    color: #333;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s;
    white-space: nowrap;
}

.nav-menu li a:hover,
.nav-menu li a.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Адаптив */
@media (max-width: 992px) {
    .nav-container {
        padding: 0 20px;
        height: auto;
        min-height: 60px;
        flex-wrap: wrap;
    }
    
    .nav-toggle {
        display: block;
    }
    
    .nav-menu {
        display: none;
        width: 100%;
        flex-direction: column;
        padding: 15px 0;
    }
    
    .nav-menu.show {
        display: flex;
    }
    
    .nav-menu li a {
        text-align: center;
        padding: 10px;
        white-space: normal;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('navToggle');
    const menu = document.getElementById('navMenu');
    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('show');
        });
    }
});
</script>