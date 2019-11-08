<?php ?>

<div class="p-5">
    <h1>Gestione utenti </h1>
    <p>In questa pagina puoi gestire gli gestire gli utenti</p>

    <!-- Add user -->
    <p>Aggiungi un <a href data-toggle="modal" data-target="#registrationModal"> utente </a>
    <hr>

    <p><b class="text-danger">Attenzione: </b>gli utenti eliminati non possono essere recuperati!</p>
    <table id="userTable" class="table text-center table-bordered table-responsive-lg table-hover" cellspacing="0"
           width="100%">
        <thead class="blue-gradient text-white table-borderless">
        <tr>
            <th class="th-sm">Id</th>
            <th class="th-sm">Nome</th>
            <th class="th-sm">Cognome</th>
            <th class="th-sm">E-mail</th>
            <!--<th class="th-sm">Richiesta cambio <br>password</th>-->
            <th class="th-sm">Admin</th>
            <th class="th-sm">Elimina utente</th>
        </tr>
        </thead>
        <tbody class="white table-borderless">
        <?php foreach ($users as $user): ?>
            <tr>
                <?php echo '<td>' . $user['id'] . '</td>' ?>
                <?php echo '<td>' . $user['name'] . '</td>' ?>
                <?php echo '<td>' . $user['surname'] . '</td>' ?>
                <?php echo '<td>' . $user['email'] . '</td>' ?>

                <!-- Check if the user is admin or not-->
                <?php if (intval($user['is_admin'] == 0)): ?>
                    <td>No</td>
                <?php else: ?>
                    <td>Si</td>
                <?php endif; ?>

                <td>
                    <a class="text-info"
                       data-toggle="modal" data-target="#deleteUserConfirm"
                       onclick="deleteUser(<?php echo $user['id'] ?>, '<?php echo str_replace("'", "\'", $user['name']) ?>')">
                        Elimina
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal add user -->
    <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModal"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-md modal-dialog modal-dialog-centered modal-md" role="document">

            <!-- modal content -->
            <div class="modal-content">
                <form action="<?php echo URL ?>users/createUser" method="post">

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
                            <label>Nome (massimo 50 caratteri)</label>
                            <input type="text" id="name"
                                   value="<?php echo (isset($_SESSION['new_user_name'])) ? $_SESSION['new_user_name'] : null ?>"
                                   class="form-control" name="name" required placeholder="Inserire nome">
                        </div>

                        <!-- Surname -->
                        <div class="mt-3">
                            <label class="">Cognome (massimo 50 caratteri)</label>
                            <input type="text" id="surname" class="form-control" name="surname"
                                   placeholder="Inserire cognome"
                                   value="<?php echo (isset($_SESSION['new_user_surname'])) ? $_SESSION['new_user_surname'] : null ?>"
                                   required>
                        </div>

                        <!-- Email -->
                        <div class="mt-3">
                            <label class="">Email</label>
                            <input type="email" id="email" class="form-control" name="email"
                                   value="<?php echo (isset($_SESSION['new_user_email'])) ? $_SESSION['new_user_email'] : null ?>"
                                   required placeholder="mario@esempio.com">
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
                            <label class="">Password (min 8 caratteri, max 50 caratteri, 1 maiuscola ed un
                                numero)</label>
                            <input type="password" id="password" class="form-control" name="password"
                                   required placeholder="Inserire password">
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-3">
                            <label class="">Conferma password</label>
                            <input type="password" id="confirmPassword" class="form-control"
                                   placeholder="Confermare password"
                                   name="confirm_pass" required onkeyup="confirmPassCorrect()">
                        </div>
                        <!-- Error msg -->
                    </div>


                    <!-- buttons of the modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                        </button>
                        <input type="submit" class="btn blue-gradient">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div class="modal fade modal-open" id="deleteUserConfirm" tabindex="-1" role="dialog"
         aria-labelledby="deleteUserConfirm"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Elimina utente </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="<?php echo URL ?>users/deleteUser" method="post">
                    <!-- Body -->
                    <div class="modal-body">

                        <div>
                            <input type="hidden" id="userToDeleteId" name="userToDeleteId">
                        </div>

                        <!-- msg -->
                        <p id="deleteMessage"></p>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                        </button>
                        <input type="submit" class="btn blue-gradient" value="Elimina">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/knowledge_base/application/libs/js/deleteUser.js"></script>
<script>
    $(document).ready(function () {
        $('#userTable').DataTable( {
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "ordering": false,
            "language": {
                "lengthMenu": "Mostra _MENU_ record per pagina",
                "zeroRecords": "Nessun record trovato",
                "info": "Pagina _PAGE_ di _PAGES_",
                "infoEmpty": "Nessun record disponibile",
                "infoFiltered": "(filtrato da _MAX_ record totali)",
                "search": "Cerca:",
                "paginate": {
                    "previous": "Precedente",
                    "next": "Successiva"
                }
            }
        } );
        $('.dataTables_length').addClass('bs-select');
    });
</script>

</body

