<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/link.php') ?>
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $settings_r['site_title'] ?> FACILITIES</title>
</head>

<body class="bg-light">
    
    <?php require('inc/header.php') ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR FACALITIES</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ipsum nisi molestiae eligendi 
            illum commodi fuga, nostrum omnis facere voluptate, velit sequi adipisci? Reprehenderit 
            illum dolorem veniam nihil aliquid laborum culpa.
        </p>
    </div>

    <div class="container">
        <div class="row">
            
            <?php 
                $res =selectAll('facilities');
                $path =FACILITIES_IMG_PATH;

                while($row =mysqli_fetch_assoc($res))
                {
                    echo<<<data
                        <div class="col-lg-4 col-md-6 mb-5 px-4">
                            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="$path$row[icon]" width="40px">
                                    <h5 class="m-0 ms-3">$row[name]</h5>
                                </div>
                                <p>
                                   $row[description]</p>
                            </div>
                        </div>
                    data;
                }
            ?>

            
        </div>
    </div>

    <?php require('inc/footer.php') ?>

</body>

</html>