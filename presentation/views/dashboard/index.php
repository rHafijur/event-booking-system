    <?php 
        $pageTitle = "Dashboard";
        require_once __DIR__.'/../layouts/header.php';
    ?>
    
    <!-- Dashboard Content -->
    <div class="container my-5">
        <h1 class="text-center mb-4">Welcome, <?= htmlspecialchars($user->getName())?></h1>

        <!-- <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Events</h5>
                        <p class="card-text fs-3">12</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Events</h5>
                        <p class="card-text fs-3">5</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Past Events</h5>
                        <p class="card-text fs-3">7</p>
                    </div>
                </div>
            </div>
        </div> -->

    </div>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>
