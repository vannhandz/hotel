<?php
require('inc/esentials.php');
adminLogin();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <?php require('inc/link.php'); ?>
</head>

<body class="bg-light">

    <div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between">
        <h3 class="mb-0 h-font">HOTEL</h3>
        <a href="logout.php" class="btn btn-light btn-sm">LOG OUT</a>
    </div>

    <div class="col-lg-2 bg-dark border-top border-3 border-secondary">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid flex-lg-column align-items-stretch">
                <h4 class="mt-2 text-light">ADMIN PANEL</h4>
                <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse flex-lg-column align-items-stretch mt-2" id="filterDropdown">

                    <div class="border bg-light p-3 rounded mb-3">
                        <h5 class="mb-3" style="font-size:18px;">CHECK AVAILABILITY</h5>
                        <label class="form-label">Check-in</label>
                        <input type="date" class="form-control shadow-none mb-3">
                        <label class="form-label">Check-out</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="border bg-light p-3 rounded mb-3">
                        <h5 class="mb-3" style="font-size:18px;">FACILITES</h5>
                        <div class="mb-2">
                            <input type="checkbox" type="f1" class="form-check-input shadow-none me-1">
                            <label class="form-check-label" for="f1">Facility one</label>
                        </div>
                        <div class="mb-2">
                            <input type="checkbox" type="f2" class="form-check-input shadow-none me-1">
                            <label class="form-check-label" for="f2">Facility two</label>
                        </div>
                        <div class="mb-2">
                            <input type="checkbox" type="f3" class="form-check-input shadow-none me-1">
                            <label class="form-check-label" for="f3">Facility three</label>
                        </div>
                    </div>
                    <div class="border bg-light p-3 rounded mb-3">
                        <h5 class="mb-3" style="font-size:18px;">GUESTS</h5>
                        <div class="d-flex">
                            <div class="me-3">
                                <label class="form-label">Adults</label>
                                <input type="nmber" class="form-control shadow-none">
                            </div>
                            <div>
                                <label class="form-label">Children</label>
                                <input type="nmber" class="form-control shadow-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <?php require('inc/scripts.php'); ?>
</body>

</html>