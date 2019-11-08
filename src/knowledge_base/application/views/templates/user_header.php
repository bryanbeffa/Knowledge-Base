<?php ?>

<body class="modal-open-noscroll">
<!--Navbar -->
<nav class="mb-1 navbar navbar-expand-lg navbar-light nav-text-dimension blue-gradient">

    <!-- small screens toggle button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
            aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- menu items -->
    <div class="collapse navbar-collapse " id="navbarSupportedContent-333">
        <ul class="navbar-nav mr-auto">
            <!-- Cases -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URL.'researchCases/showCases'?>">Ricerca Casi
                </a>
            </li>
        </ul>

        <!-- logout button -->
        <ul class="navbar-nav ml-auto nav-flex-icons">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URL.'users/logout'?>">Logout
                </a>
            </li>
        </ul>

    </div>
</nav>
<!--/.Navbar -->
