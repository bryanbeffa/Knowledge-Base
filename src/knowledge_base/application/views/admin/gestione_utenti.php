<?php ?>

<div class="container pt-5">
    <h1>Gestione utenti </h1>

    <table class="table text-center mt-5 mb-5">
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>E-mail</th>
            <th>Richiesta cambio password</th>
            <th>Admin</th>
            <th>Elimina</th>
        </tr>

        <?php foreach ($users as $user): ?>
            <tr>
                <?php echo '<td>' . $user['id'] . '</td>' ?>
                <?php echo '<td>' . $user['name'] . '</td>' ?>
                <?php echo '<td>' . $user['surname'] . '</td>' ?>
                <?php echo '<td>' . $user['email'] . '</td>' ?>

                <!-- Check if the user is admin or not-->
                <?php if(intval($user['change_pass'] == 0)): ?>
                    <td>No</td>
                        <?php else: ?>
                    <td>Si</td>
                <?php endif; ?>

                <!-- Check if the user is admin or not-->
                <?php if(intval($user['is_admin'] == 0)): ?>
                    <td>No</td>
                <?php else: ?>
                    <td>Si</td>
                <?php endif; ?>

                <td><a href="#">X </a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Add user -->
    <p>Aggiungi un <a href data-toggle="modal" data-target="#registrationModal"> utente </a></div>

<!-- Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModal"
     aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">

        <!-- modal content -->
        <div class="modal-content">
            <form action="" method="post">

                <!-- modal header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Inserisci nuovo utente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- body of the modal -->
                <div class="modal-body">


                    <!-- Name -->
                    <div class="md-form">
                        <input type="text" id="materialSubscriptionFormEmail" class="form-control" name="name" required>
                        <label for="materialSubscriptionFormEmail">Nome</label>
                    </div>

                    <!-- Surname -->
                    <div class="md-form">
                        <input type="text" id="materialSubscriptionFormEmail" class="form-control" name="surname"
                               required>
                        <label for="materialSubscriptionFormEmail">Cognome</label>
                    </div>

                    <!-- Email -->
                    <div class="md-form mt-3">
                        <input type="email" id="materialSubscriptionFormPasswords" class="form-control" name="email"
                               required>
                        <label for="materialSubscriptionFormPasswords">Email</label>
                    </div>

                    <!-- Password -->
                    <div class="md-form">
                        <input type="password" id="materialSubscriptionFormEmail" class="form-control" name="password"
                               required>
                        <label for="materialSubscriptionFormEmail">Password</label>
                    </div>

                    <!-- Password -->
                    <div class="md-form">
                        <input type="password" id="materialSubscriptionFormEmail" class="form-control"
                               name="confirm_pass" required>
                        <label for="materialSubscriptionFormEmail">Conferma password</label>
                    </div>

                </div>

                <!-- buttons of the modal -->
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #20d6a9" data-dismiss="modal">Chiudi
                    </button>
                    <input type="submit" class="btn" style="background-color: #20d6a9">
                </div>
            </form>
        </div>
    </div>
</div>

</div>
</body