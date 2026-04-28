<?php
require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$errors = [];
$successMessage = '';
$email = '';

$getLength = static function (string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
};

if (isset($_SESSION['user_id'])) {
    $alreadyLoggedIn = true;
} else {
    $alreadyLoggedIn = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
        $newPassword = isset($_POST['new_password']) && is_string($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirmNewPassword = isset($_POST['confirm_new_password']) && is_string($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';

        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if ($newPassword === '') {
            $errors[] = 'New password is required.';
        } elseif ($getLength($newPassword) < 6) {
            $errors[] = 'New password must be at least 6 characters.';
        } elseif ($getLength($newPassword) > 255) {
            $errors[] = 'New password must not exceed 255 characters.';
        }

        if ($confirmNewPassword === '') {
            $errors[] = 'Confirm new password is required.';
        } elseif ($confirmNewPassword !== $newPassword) {
            $errors[] = 'New password and confirm new password must match.';
        }

        if (empty($errors)) {
            try {
                if (!$userModel->emailExists($email)) {
                    $errors[] = 'No account was found with that email address.';
                }
            } catch (Throwable $exception) {
                $errors[] = 'Unable to verify your email right now. Please try again.';
            }
        }

        if (empty($errors)) {
            try {
                if ($userModel->updatePasswordByEmail($email, $newPassword)) {
                    $successMessage = 'Password reset successful. You can now log in with your new password.';
                    $email = '';
                } else {
                    $errors[] = 'Password reset failed. Please try again.';
                }
            } catch (Throwable $exception) {
                $errors[] = 'Password reset failed. Please try again.';
            }
        }
    }
}
?>
<main class="page-main">
    <section class="auth-page">
        <div class="container">
            <div class="auth-card">
                <h1 class="auth-card__title">Reset Your Password</h1>
                <p class="auth-card__description">Update your account password with this simplified academic reset form.</p>
                <p class="auth-note">For this academic project, password reset is simplified and does not send a real email.</p>

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

                    <?php if ($successMessage !== '') : ?>
                        <div class="form-success">
                            <p><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><a class="auth-link" href="index.php?page=login">Go to Login</a></p>
                        </div>
                    <?php else : ?>
                        <form class="auth-form" id="forgotPasswordForm" action="index.php?page=forgot-password" method="post" novalidate>
                            <div class="form-group">
                                <label for="forgotEmail">Email</label>
                                <input
                                    class="form-input"
                                    type="email"
                                    id="forgotEmail"
                                    name="email"
                                    maxlength="150"
                                    required
                                    value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                                >
                                <div class="form-error-message" data-error-for="forgotEmail"></div>
                            </div>

                            <div class="form-group">
                                <label for="forgotPassword">New Password</label>
                                <input
                                    class="form-input"
                                    type="password"
                                    id="forgotPassword"
                                    name="new_password"
                                    maxlength="255"
                                    required
                                >
                                <div class="form-error-message" data-error-for="forgotPassword"></div>
                            </div>

                            <div class="form-group">
                                <label for="forgotConfirmPassword">Confirm New Password</label>
                                <input
                                    class="form-input"
                                    type="password"
                                    id="forgotConfirmPassword"
                                    name="confirm_new_password"
                                    maxlength="255"
                                    required
                                >
                                <div class="form-error-message" data-error-for="forgotConfirmPassword"></div>
                            </div>

                            <button class="auth-submit" type="submit">Reset Password</button>
                        </form>

                        <p class="auth-card__footer">
                            Remember your password?
                            <a class="auth-link" href="index.php?page=login">Login</a>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
