document.addEventListener('DOMContentLoaded', function () {
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function getMessageElement(input) {
        return document.querySelector('[data-error-for="' + input.id + '"]');
    }

    function showError(input, message) {
        var messageElement = getMessageElement(input);

        if (!messageElement) {
            return;
        }

        messageElement.textContent = message;
        input.setAttribute('aria-invalid', 'true');
    }

    function clearError(input) {
        var messageElement = getMessageElement(input);

        if (!messageElement) {
            return;
        }

        messageElement.textContent = '';
        input.removeAttribute('aria-invalid');
    }

    function validateRegisterForm() {
        var form = document.getElementById('registerForm');

        if (!form) {
            return;
        }

        var nameInput = document.getElementById('registerName');
        var emailInput = document.getElementById('registerEmail');
        var passwordInput = document.getElementById('registerPassword');
        var confirmPasswordInput = document.getElementById('registerConfirmPassword');

        function validateField(input) {
            var value = input.value.trim();

            if (input === nameInput) {
                if (value === '') {
                    return 'Full name is required.';
                }

                if (value.length < 2) {
                    return 'Full name must be at least 2 characters.';
                }
            }

            if (input === emailInput) {
                if (value === '') {
                    return 'Email is required.';
                }

                if (!emailPattern.test(value)) {
                    return 'Please enter a valid email address.';
                }
            }

            if (input === passwordInput) {
                if (input.value === '') {
                    return 'Password is required.';
                }

                if (input.value.length < 6) {
                    return 'Password must be at least 6 characters.';
                }
            }

            if (input === confirmPasswordInput) {
                if (input.value === '') {
                    return 'Confirm password is required.';
                }

                if (input.value !== passwordInput.value) {
                    return 'Password and confirm password must match.';
                }
            }

            return '';
        }

        [nameInput, emailInput, passwordInput, confirmPasswordInput].forEach(function (input) {
            input.addEventListener('input', function () {
                var message = validateField(input);

                if (message === '') {
                    clearError(input);

                    if (input === passwordInput || input === confirmPasswordInput) {
                        var confirmMessage = validateField(confirmPasswordInput);

                        if (confirmMessage === '') {
                            clearError(confirmPasswordInput);
                        } else if (confirmPasswordInput.value !== '') {
                            showError(confirmPasswordInput, confirmMessage);
                        }
                    }
                } else {
                    showError(input, message);
                }
            });
        });

        form.addEventListener('submit', function (event) {
            var hasErrors = false;

            [nameInput, emailInput, passwordInput, confirmPasswordInput].forEach(function (input) {
                var message = validateField(input);

                if (message !== '') {
                    showError(input, message);
                    hasErrors = true;
                } else {
                    clearError(input);
                }
            });

            if (hasErrors) {
                event.preventDefault();
            }
        });
    }

    function validateLoginForm() {
        var form = document.getElementById('loginForm');

        if (!form) {
            return;
        }

        var emailInput = document.getElementById('loginEmail');
        var passwordInput = document.getElementById('loginPassword');

        function validateField(input) {
            var value = input.value.trim();

            if (input === emailInput) {
                if (value === '') {
                    return 'Email is required.';
                }

                if (!emailPattern.test(value)) {
                    return 'Please enter a valid email address.';
                }
            }

            if (input === passwordInput && input.value === '') {
                return 'Password is required.';
            }

            return '';
        }

        [emailInput, passwordInput].forEach(function (input) {
            input.addEventListener('input', function () {
                var message = validateField(input);

                if (message === '') {
                    clearError(input);
                } else {
                    showError(input, message);
                }
            });
        });

        form.addEventListener('submit', function (event) {
            var hasErrors = false;

            [emailInput, passwordInput].forEach(function (input) {
                var message = validateField(input);

                if (message !== '') {
                    showError(input, message);
                    hasErrors = true;
                } else {
                    clearError(input);
                }
            });

            if (hasErrors) {
                event.preventDefault();
            }
        });
    }

    function validateForgotPasswordForm() {
        var form = document.getElementById('forgotPasswordForm');

        if (!form) {
            return;
        }

        var emailInput = document.getElementById('forgotEmail');
        var passwordInput = document.getElementById('forgotPassword');
        var confirmPasswordInput = document.getElementById('forgotConfirmPassword');

        function validateField(input) {
            var value = input.value.trim();

            if (input === emailInput) {
                if (value === '') {
                    return 'Email is required.';
                }

                if (!emailPattern.test(value)) {
                    return 'Please enter a valid email address.';
                }
            }

            if (input === passwordInput) {
                if (input.value === '') {
                    return 'New password is required.';
                }

                if (input.value.length < 6) {
                    return 'New password must be at least 6 characters.';
                }
            }

            if (input === confirmPasswordInput) {
                if (input.value === '') {
                    return 'Confirm new password is required.';
                }

                if (input.value !== passwordInput.value) {
                    return 'New password and confirm new password must match.';
                }
            }

            return '';
        }

        [emailInput, passwordInput, confirmPasswordInput].forEach(function (input) {
            input.addEventListener('input', function () {
                var message = validateField(input);

                if (message === '') {
                    clearError(input);

                    if (input === passwordInput || input === confirmPasswordInput) {
                        var confirmMessage = validateField(confirmPasswordInput);

                        if (confirmMessage === '') {
                            clearError(confirmPasswordInput);
                        } else if (confirmPasswordInput.value !== '') {
                            showError(confirmPasswordInput, confirmMessage);
                        }
                    }
                } else {
                    showError(input, message);
                }
            });
        });

        form.addEventListener('submit', function (event) {
            var hasErrors = false;

            [emailInput, passwordInput, confirmPasswordInput].forEach(function (input) {
                var message = validateField(input);

                if (message !== '') {
                    showError(input, message);
                    hasErrors = true;
                } else {
                    clearError(input);
                }
            });

            if (hasErrors) {
                event.preventDefault();
            }
        });
    }

    validateRegisterForm();
    validateLoginForm();
    validateForgotPasswordForm();
});
