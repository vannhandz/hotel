<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Rooms</title>
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
        <div class="row">

            <div class="col-lg-3">
                <nav class="navbar navbar-expand-lg navbar-light bg-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
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
                                    <input type="checkbox" type="f1" class="form-check-input shadow-none mb-3">
                                    <label class="form-label" for="f1">Facility one</label>
                                </div>
                           </div>
                        </div>
                    </div>
                </nav>
            </div>

        </div>
    </div>

    <?php require('inc/footer.php') ?>

</body>

</html>