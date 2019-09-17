<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13.09.2019
 * Time: 14:36
 */ ?>


<div class="container border col-6 p-4 align-middle" style="position:absolute; left:0;right:0; top:50%; -webkit-transform:translateY(-50%) !important; -ms-transform:translateY(-50%) !important; transform:translateY(-50%) !important;">

    <!---->
    <div class="px-lg-5">

        <!-- logo -->
        <div class="text-center mb-3">
            <img alt="Logo knowledge base" src="application/libs/img/kb_logo.svg" class="col-6"/>
        </div>

        <!-- Form -->
        <form class="text-center" style="color: #757575;" action="#!">

            <!-- Titles -->
            <h1>Benvenuto</h1>
            <h2>Accedi al nostro sistema</h2>

            <!-- Name -->
            <div class="md-form mt-3">
                <input type="text" id="materialSubscriptionFormPasswords" class="form-control">
                <label for="materialSubscriptionFormPasswords">Name</label>
            </div>

            <!-- Email -->
            <div class="md-form">
                <input type="email" id="materialSubscriptionFormEmail" class="form-control">
                <label for="materialSubscriptionFormEmail">E-mail</label>
            </div>

            <!-- Sign in button -->
            <button class="btn btn-block" type="submit" style="background-color: #20d6a9; color: #000">Sign in</button>

        </form>
        <!-- Form -->
    </div>
</div>
