<?php ?>

<div class="p-5">
    <h1>Gestione utenti </h1>

    <table class="table table-bordered text-center mt-5 mb-5">
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>E-mail</th>
            <th>Richiesta cambio <br>password</th>
            <th>Admin</th>
            <th>Elimina utente</th>
        </tr>

        <?php foreach ($users as $user): ?>
            <tr>
                <?php echo '<td>' . $user['id'] . '</td>' ?>
                <?php echo '<td>' . $user['name'] . '</td>' ?>
                <?php echo '<td>' . $user['surname'] . '</td>' ?>
                <?php echo '<td>' . $user['email'] . '</td>' ?>

                <td>
                    <a class="text-info" href="<?php echo URL . "home/requestChangePass/" . $user['id'] ?>">
                        Effettua richiesta
                    </a>
                </td>

                <!-- Check if the user is admin or not-->
                <?php if (intval($user['is_admin'] == 0)): ?>
                    <td>No</td>
                <?php else: ?>
                    <td>Si</td>
                <?php endif; ?>

                <td>
                    <a class="text-info" href="<?php echo URL . "home/deleteUser/" . $user['id'] ?>"
                       onclick="return confirm('Sei sicuro di voler eliminare questo utente?');">
                        Elimina
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Add user -->
    <p>Aggiungi un <a href data-toggle="modal" data-target="#registrationModal"> utente </a>


        <!-- Modal -->
        <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModal"
             aria-hidden="true">

            <script type="text/javascript" src="/knowledge_base/application/libs/js/UserRegistration.js"></script>

            <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
            <div class="modal-dialog modal-dialog-centered" role="document">

                <!-- modal content -->
                <div class="modal-content">
                    <form action="<?php echo URL ?>home/createUser" method="post">

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
                            <div class="mt-3">
                                <label>Nome:</label>
                                <input type="text" id="name"
                                       value="<?php echo (isset($_SESSION['new_user_name'])) ? $_SESSION['new_user_name'] : null ?>"
                                       class="form-control" name="name" required placeholder="Inserire nome">
                            </div>

                            <!-- Surname -->
                            <div class="mt-3">
                                <label class="">Cognome:</label>
                                <input type="text" id="surname" class="form-control" name="surname"
                                       placeholder="Inserire cognome"
                                       required>
                            </div>

                            <!-- Email -->
                            <div class="mt-3">
                                <label class="">Email:</label>
                                <input type="email" id="email" class="form-control" name="email"
                                       required placeholder="email">
                            </div>

                            <!-- User Privileges -->
                            <div class="mt-3 custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="baseUser"
                                       name="is_admin" value="0" checked="checked">
                                <label class="custom-control-label" for="baseUser">Utente base</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="adminUser"
                                       name="is_admin" value="1">
                                <label class="custom-control-label" for="adminUser">Utente admin</label>
                            </div>

                            <!-- Password -->
                            <div class="mt-3">
                                <label class="">Password:</label>
                                <input type="password" id="password" class="form-control" name="password"
                                       required placeholder="Inserire password">
                            </div>

                            <!-- Confirm Password -->
                            <div class="mt-3">
                                <label class="">Conferma password:</label>
                                <input type="password" id="confirmPassword" class="form-control"
                                       placeholder="Confermare password"
                                       name="confirm_pass" required onkeyup="confirmPassCorrect()">
                            </div>
    <p class="mt-3" id="errorMsg"></p>
    <!-- Error msg -->
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


</body

