<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13.09.2019
 * Time: 14:36
 */
require_once 'application/config/config.php';
?>

<body style="background: url('/knowledge_base/application/libs/img/login_bg.jpg')">
    <div class="container border col-md-6 p-4 align-middle white" style="opacity: 0.95; position:absolute; left:0;right:0; top:50%; -webkit-transform:translateY(-50%) !important; -ms-transform:translateY(-50%) !important; transform:translateY(-50%) !important;">

        <!---->
        <div class="px-lg-5">

            <!-- logo -->
            <div class="text-center mb-3">
                <img alt="Logo knowledge base" src="/knowledge_base/application/libs/img/kb_logo.svg" class="col-6"/>
            </div>

            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="<?php echo URL?>home/login" method="post">

                <!-- Titles -->
                <h1>Benvenuto</h1>
                <h2>Accedi al nostro sistema</h2>

                <!-- Email -->
                <div class="md-form mt-3">
                    <input type="email" id="email" class="form-control" name="email">
                    <label for="email">Email</label>
                </div>

                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="password" class="form-control" name="password">
                    <label for="password">Password</label>
                </div>

                <!-- Sign in button -->
                <button class="btn btn-block" type="submit" style="background-color: #20d6a9; color: #000">Sign in</button>

            </form>
            <!-- Form -->
        </div>
    </div>
</body>