<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <link rel="icon" href="./favicon.png" />
    <link rel="apple-touch-icon" href="./favicon.png" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="Pinboard Viewer">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Font Awsome GET YOUR OWN KEY -->
    <script src="https://kit.fontawesome.com/10207a6b26.js" crossorigin="anonymous"></script>

    <!-- Local Styles -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Pinboard Viewer</title>

</head>

<body onload="" class="body">

    <!-- Navigation Bar -->
    <nav class="navbar  navbar-dark bg-dark  sticky-top mx-auto px-1">


        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <button class="nav-link btn btn-dark" onclick="javascript:popHistory();" type="button" aria-expanded="false" aria-label="Top">
            <span class="fas fa-chevron-left"></span>
        </button>

        <button class="nav-link btn btn-dark" onclick="
            javascript:getRecent();" type="button" aria-expanded="false" aria-label="Random">
            <span class="fas fa-user"></span>
        </button>

        <button class="nav-link btn btn-dark" onclick="javascript:getTagCloud();" type="button" aria-expanded="false" aria-label="Top">
            <span class="fas fa-tags"></span>
        </button>

        <button class="nav-link btn btn-dark" onclick="javascript:getRecent();" type="button" aria-expanded="false" aria-label="Random">
            <span class="fas fa-user-lock"></span>
        </button>


        <!-- main_nav -->
        <div class="collapse navbar-collapse" id="main_nav">

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/dougpark/pinboard_viewer">
                        <span class="fas fa-external-link-alt"></span>
                        Project Details at GitHub
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://pinboard.in">
                        <span class="fas fa-external-link-alt"></span>
                        Pinboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://">
                        <span class="fas fa-external-link-alt"></span>
                        TBD
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about" data-toggle="modal">
                        <span class="far fa-arrow-alt-circle-right"></span>
                        About
                    </a>
                </li>

            </ul>

        </div> <!-- end main_nav navbar-collapse.// -->
    </nav>


    <div id="about" class="modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">

                <div class="modal-header">
                    <div class="text-center">
                        <h1 class="">Pinboard Viewer</h1>
                    </div>

                </div>

                <div class="modal-body">
                    <div id="about-header" class="">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn btn-outline-success" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Contacts modal panel -->


    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-lg-6 ">
                <div id="output">Pinboard viewer loading...</div>
            </div>
        </div>
    </div>
    </div>




    <!-- Important for Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous">
    </script>

    <!-- Local Js -->
    <script src="main.js"></script>
</body>

</html>