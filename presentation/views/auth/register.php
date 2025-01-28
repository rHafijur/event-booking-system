<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require_once __DIR__.'/../layouts/flash_messages.php'; ?>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg w-100" style="max-width: 400px;">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Register</h2>
                <form action="<?=url("/register")?>" method="POST">
                    <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($GLOBALS['csrf_token'] ?? ''); ?>">

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars(old('name'))?>" id="name" name="name" required placeholder="Enter your full name">
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars(old('email'))?>" id="email" name="email" required placeholder="Enter your email">
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Your password">
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="text-center my-3">
                    <hr class="my-3">
                    <span class="text-muted">Already have an account?</span>
                </div>

                <!-- Login Button -->
                <div class="d-grid">
                    <a href="<?=url("/login")?>" class="btn btn-outline-secondary">Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
