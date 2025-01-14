<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Contact</title>
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ipsum nisi molestiae eligendi
            illum commodi fuga, nostrum omnis facere voluptate, velit sequi adipisci? Reprehenderit
            illum dolorem veniam nihil aliquid laborum culpa.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 ">
                    <iframe class="w-100 rounded mb-4" height="320px"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d18192.268290608525!2d108.22291725750793!3d16.057427023619635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142176372388b9f%3A0x8b97363057db4165!2sHanami%20Hotel%20Danang!5e1!3m2!1svi!2s!4v1736313965959!5m2!1svi!2s"></iframe>
                    <h5>Address</h5>
                    <a href="https://maps.app.goo.gl/XbwfZjM4SxWmAtsHA" target="_blank"
                        class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i>Hanami Hotel
                    </a>
                    <h5 class="mt-4">Call us</h5>
                    <a href="tel: 0905432992" class="d-inline-block mb-2 text-decoration-none text-dark"><i
                            class="bi bi-telephone-fill "></i>+84905432992
                    </a>
                    <br>
                    <a href="tel: 0905432992" class="d-inline-block  text-decoration-none text-dark"><i
                            class="bi bi-telephone-fill  "></i>+84905432992
                    </a>
                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: HanamiHotel@gmail.com" class="d-inline-block  text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i> HanamiHotel@gmail.com
                    </a>
                    <h5 class="mt-4">Follow us</h5>
                    <a href="#" class="d-inline-block  text-dark fs-5 me-2">
                        <i class="bi bi-twitter me-1"></i>
                    </a>
                    <a href="#" class="d-inline-block  text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="#" class="d-inline-block  text-dark fs-5 ">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4 ">
                    <form>
                        <h5>Send a message</h5>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Name</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Email</label>
                            <input type="email" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Subject</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight:500;">Message</label>
                            <textarea class="form-control shadow-none" row="5" style="resize:none;"></textarea>
                        </div>
                        <button type="submit" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

</body>

</html>