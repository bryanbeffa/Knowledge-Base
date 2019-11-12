<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13.09.2019
 * Time: 14:36
 */
require_once 'application/config/config.php';
?>

<body class="login-bg-image blue-gradient">
    <div class="container border col-md-6 p-4 align-middle white vertical-align border-radius" id="loginMask">

        <!---->
        <div class="px-lg-5">

            <!-- logo -->
            <div class="text-center mb-3">
                <img alt="Logo knowledge base" src="/knowledge_base/application/libs/img/login_logo.png" class="col-5 mb-3"/>
            </div>

            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="<?php echo URL?>home/login" method="post">

                <!-- Titles -->
                <h1>Benvenuto</h1>
                <h2>Accedi al nostro sistema</h2>

                <!-- Email -->
                <div class="md-form mt-3">
                    <input type="email" id="email" class="form-control" name="email" required>
                    <label for="email">Email</label>
                </div>

                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="password" class="form-control" name="password" required>
                    <label for="password">Password</label>
                </div>

                <!-- Sign in button -->
                <button class="btn btn-block blue-gradient" type="submit">Sign in</button>

            </form>
            <!-- Form -->
        </div>
    </div>
</body>