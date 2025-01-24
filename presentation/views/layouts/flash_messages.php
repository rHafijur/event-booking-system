<?php $flashMessages = getFlashMessages(); ?>

<?php if (!empty($flashMessages)): ?>
    <div class="container mt-3">
        <?php foreach ($flashMessages as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($type); ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
