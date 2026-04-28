<?php
require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$errors = [];
$successMessage = '';
$name = '';
$email = '';

$getLength = static function (string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) && is_string($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) && is_string($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($name === '') {
        $errors[] = 'Full name is required.';
    } elseif ($getLength($name) < 2) {
        $errors[] = 'Full name must be at least 2 characters.';
    } elseif ($getLength($name) > 100) {
        $errors[] = 'Full name must not exceed 100 characters.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif ($getLength($email) > 150) {
        $errors[] = 'Email must not exceed 150 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif ($getLength($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    } elseif ($getLength($password) > 255) {
        $errors[] = 'Password must not exceed 255 characters.';
    }

    if ($confirmPassword === '') {
        $errors[] = 'Confirm password is required.';
    } elseif ($confirmPassword !== $password) {
        $errors[] = 'Password and confirm password must match.';
    }

    if (empty($errors)) {
        try {
            if ($userModel->emailExists($email)) {
                $errors[] = 'An account with this email already exists.';
            }
        } catch (Throwable $exception) {
            $errors[] = 'Unable to validate your email right now. Please try again.';
        }
    }

    if (empty($errors)) {
        try {
            $createdUserId = $userModel->createUser($name, $email, $password);

            if ($createdUserId) {
                $successMessage = 'Registration successful. You can now log in.';
                $name = '';
                $email = '';
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        } catch (Throwable $exception) {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>
<main class="page-main">
    <section class="auth-page">
        <div class="container">
            <div class="auth-card">
                <h1 class="auth-card__title">Create Your Account</h1>
                <p class="auth-card__description">Join Seasonal Wardrobe to prepare for easy login and future account features.</p>

                <?php if (!empty($errors)) : ?>
                    <ul class="form-error-list">
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($successMessage !== '') : ?>
                    <div class="form-success">
                        <p><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><a class="auth-link" href="index.php?page=login">Go to Login</a></p>
                    </div>
                <?php endif; ?>

                <form class="auth-form" id="registerForm" action="index.php?page=register" method="post" novalidate>
                    <div class="form-group">
                        <label for="registerName">Full Name</label>
                        <input
                            class="form-input"
                            type="text"
                            id="registerName"
                            name="name"
                            maxlength="100"
                            required
                            value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                        <div class="form-error-message" data-error-for="registerName"></div>
                    </div>

                    <div class="form-group">
                        <label for="registerEmail">Email</label>
                        <input
                            class="form-input"
                            type="email"
                            id="registerEmail"
                            name="email"
                            maxlength="150"
                            required
                            value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                        <div class="form-error-message" data-error-for="registerEmail"></div>
                    </div>

                    <div class="form-group">
                        <label for="registerPassword">Password</label>
                        <input
                            class="form-input"
                            type="password"
                            id="registerPassword"
                            name="password"
                            maxlength="255"
                            required
                        >
                        <div class="form-error-message" data-error-for="registerPassword"></div>
                    </div>

                    <div class="form-group">
                        <label for="registerConfirmPassword">Confirm Password</label>
                        <input
                            class="form-input"
                            type="password"
                            id="registerConfirmPassword"
                            name="confirm_password"
                            maxlength="255"
                            required
                        >
                        <div class="form-error-message" data-error-for="registerConfirmPassword"></div>
                    </div>

                    <button class="auth-submit" type="submit">Register</button>
                </form>

                <p class="auth-card__footer">
                    Already have an account?
                    <a class="auth-link" href="index.php?page=login">Login</a>
                </p>
            </div>
        </div>
    </section>
</main>
