<?php
require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$errors = [];
$email = '';

if (isset($_SESSION['user_id'])) {
    $alreadyLoggedIn = true;
} else {
    $alreadyLoggedIn = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';

        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        if (empty($errors)) {
            try {
                $user = $userModel->findByEmail($email);

                if ($user === null || !password_verify($password, $user['password_hash'])) {
                    $errors[] = 'Invalid email or password.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];

                    header('Location: index.php?page=home');
                    exit;
                }
            } catch (Throwable $exception) {
                $errors[] = 'Login failed. Please try again.';
            }
        }
    }
}
?>
<main class="page-main">
    <section class="auth-page">
        <div class="container">
            <div class="auth-card">
                <h1 class="auth-card__title">Welcome Back</h1>
                <p class="auth-card__description">Log in to continue exploring seasonal collections and your account features.</p>

                <?php if ($alreadyLoggedIn) : ?>
                    <div class="form-success">
                        <p>You are already logged in.</p>
                        <p><a class="auth-link" href="index.php?page=home">Go to Home</a></p>
                    </div>
                <?php else : ?>
                    <?php if (!empty($errors)) : ?>
                        <ul class="form-error-list">
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <form class="auth-form" action="index.php?page=login" method="post" novalidate>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                class="form-input"
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                class="form-input"
                                type="password"
                                id="password"
                                name="password"
                                required
                            >
                        </div>

                        <button class="auth-submit" type="submit">Login</button>
                    </form>

                    <p class="auth-card__footer">
                        Don't have an account?
                        <a class="auth-link" href="index.php?page=register">Register</a>
                    </p>
                    <p class="auth-card__footer">
                        <a class="auth-link" href="index.php?page=forgot-password">Forgot password?</a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
