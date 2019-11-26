<?php ?>

<div id="preloadImage" class="text-center vertical-align white opacity-100">
    <img class="vertical-align m-auto" src="/knowledge_base/application/libs/img/book.gif">
</div>

<script>

    //set preload div height
    var height = $(window).height();
    $("#preloadImage").height(height);

    //wait for the fully loading of the page
    $(document).ready(function () {
        $("#mainContainer").show();
        $("#preloadImage").hide();
    });
</script>

<div class="p-5" id="mainContainer" style="display: none">
    <h1>Ricerca casi</h1>
    <hr>

    <div class="text-center mb-3">
        <button class="btn text-white blue-gradient" data-toggle="collapse"
                class="text-primary" data-target="#filterMask"
                aria-expanded="false" aria-controls="filterMask" id="filterButton">
            Mostra filtri
        </button>
    </div>

    <!-- Filter mask -->
    <div class="collapse" id="filterMask">
        <div class="container">
            <form action="<?php echo URL ?>researchCases/showCases" method="post">

                <h3 class="text-center">Filtri Base:</h3>

                <!-- base filters  -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Scegli ordinamento</label>
                    <div class="col-sm-9">
                        <select class="browser-default custom-select" name="order_results">
                            <option value="0" <?php echo (isset($_SESSION['order_results']) && intval($_SESSION['order_results']) == 0) ? "selected" : null ?>>
                                Casi più recenti
                            </option>
                            <option value="2" <?php echo (isset($_SESSION['order_results']) && intval($_SESSION['order_results']) == 2) ? "selected" : null ?>>
                                Casi meno recenti
                            </option>
                            <option value="1" <?php echo (isset($_SESSION['order_results']) && intval($_SESSION['order_results']) == 1) ? "selected" : null ?>>
                                Casi più ricorrenti
                            </option>
                        </select>
                    </div>
                </div>

                <h3 class="text-center">Filtri avanzati:</h3>
                <hr>
                <!-- Search text -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Inserisci parola chiave</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" placeholder="Ricerca" aria-label="Search"
                               name="text_filter"
                               value="<?php echo (isset($_SESSION['text_filter'])) ? $_SESSION['text_filter'] : null ?>">
                    </div>
                </div>

                <!-- Search Category -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Seleziona categoria</label>
                    <div class="col-sm-9">
                        <select class="browser-default custom-select mdb-select" name="category_filter">
                            <option value="0">Tutte le categorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id'] ?>" <?php echo ((isset($_SESSION['category_filter']) && $_SESSION['category_filter'] == $category['id']) ? "selected>" : ">") . $category['name'] . "</option>" ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Date -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Scegli una data</label>
                    <div class="col-sm-9">
                        <input placeholder="20.10.2019" type="text" class="form-control datepicker"
                               name="date_filter" id="datepicker"
                               value="<?php echo (isset($_SESSION['date_filter'])) ? date_format(date_create($_SESSION['date_filter']), "d.m.Y") : null ?>">
                    </div>
                </div>

                <div class="container-fluid">
                    <input type="submit" value="cerca" class="btn blue-gradient text-white">
                </div>
            </form>
        </div>
        <hr>
    </div>
    <p class="m-0">Aggiungi <a href data-toggle="modal" data-target="#addCase"> caso </a></p>

    <!-- Add category -->
    <?php /** @var $is_admin contains if the user is an admin */
    if ($is_admin): ?>
        <p class="m-0">Aggiungi una <a href data-toggle="modal" data-target="#addCategory"> categoria </a></p>
        <p class="m-0"><a href data-toggle="modal" data-target="#deleteCategory">Elimina </a>una categoria </p>
    <?php endif; ?>
    <!-- End filter mask-->

    <h2 class="text-center">Risultati:</h2>
    <hr>

    <!-- Show cases -->

    <!-- Adapted table for using pagination -->
    <table id="casesTable" class="table table-responsive-lg" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($cases as $case): ?>

            <tr>
                <td class="w-100">

                    <!-- Uses to print times variable -->
                    <?php $key = $case['id']; ?>
                    <div class="card mt-4 mb-5">

                        <!-- Title -->
                        <div class="card-header blue-gradient">
                            <h2 class="text-center text-white"><b>Nome: </b><?php echo $case['title'] ?></h2>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <h5><b>ID:</b> <?php echo $case['id'] ?></h5>
                            <h5>
                                <b>Categoria:</b> <?php echo ($case['category_id'] != null) ? $cases_categories["$key"] : "-" ?>
                            </h5>
                            <h5><b>Variante di:</b> <?php echo ($case['variant'] == null) ? "-" : $case['variant'] ?>
                            </h5>
                            <h5><b>Data
                                    creazione:</b> <?php echo date_format(date_create($case['created_at']), "d.m.Y H:i:s") ?>
                            </h5>
                            <h5 class="font-weight-bold">Descrizione:</h5>
                            <p><?php echo $case['description'] ?></p>

                            <!-- Times -->
                            <h5><b>Numero di ripresentazioni: </b><?php echo $cases_times["$key"] ?> </h5>
                        </div>

                        <!-- Footer-->
                        <?php if ($is_admin): ?>
                            <div class="card-footer bg-transparent text-right">
                                <a class="btn text-white blue-gradient"
                                   onclick="showModifyModal('<?php echo str_replace("'", "\'", $case['title']) ?>' , <?php echo $case['id'] ?>, '<?php echo str_replace("'", "\'", $case['description']) ?>', <?php echo $case['category_id'] ?>, <?php echo $case['variant'] ?>)">Modifica</a>
                                <a class="btn text-white blue-gradient"
                                   data-toggle="modal" data-target="#confirmDeleteCase"
                                   onclick="deleteCase(<?php echo $case['id'] ?>, '<?php str_replace("'", "\'", $case['title']) ?>')">
                                    Elimina</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal add category -->
    <div class="modal fade modal-open" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategory"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Aggiungi una nuova categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="<?php echo URL ?>researchCases/addCategory" method="post">
                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Title -->
                        <div class="mt-3">
                            <label>Nome della categoria (massimo 50 caratteri)</label>
                            <input type="text" id="title" class="form-control" placeholder="Inserisci nome"
                                   name="new_category" required>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                        </button>
                        <input type="submit" class="btn blue-gradient" value="Aggiungi">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal add case-->
    <div class="modal fade modal-open" id="addCase" tabindex="-1" role="dialog" aria-labelledby="addCase"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Aggiungi un nuovo caso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="<?php echo URL ?>researchCases/addCase" method="post">
                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Case name -->
                        <div class="mt-3">
                            <label>Nome della caso (massimo 50 caratteri) <b class="text-danger">*</b></label>
                            <input type="text" id="title" class="form-control" placeholder="Inserisci nome"
                                   name="new_case_title" required
                                   value="<?php echo (isset($_SESSION['new_case_title'])) ? $_SESSION['new_case_title'] : "" ?>">
                        </div>

                        <!-- Case category -->
                        <div class="mt-3">
                            <label>Seleziona una categoria <b class="text-danger">*</b></label>
                            <select class="browser-default custom-select" required name="new_case_category">
                                <?php foreach ($categories as $category): ?>
                                    <?php echo "<option value='" . $category['id'] . "' selected>" . $category['name'] . "</option>" ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Case variant -->
                        <div class="mt-3">
                            <label>Variante del caso:</label>
                            <select class="browser-default custom-select" required name="new_case_variant">
                                <option value="0" selected>Nessun caso</option>
                                <?php foreach ($all_cases as $case_variant): ?>
                                    <?php echo "<option value=" . $case_variant['id'] . "selected>" . " (id: " . $case_variant['id'] . ") " . $case_variant['title'] . "</option>" ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Case description -->
                        <div class="mt-3">
                            <label>Descrizione <b class="text-danger">*</b></label>
                            <textarea type="text" name="new_case_description" rows="10"
                                      placeholder="Inserire descrizione"
                                      class="form-control md-textarea"
                                      required><?php echo (isset($_SESSION['new_case_description'])) ? $_SESSION['new_case_description'] : "" ?></textarea>
                        </div>

                        <p class="mt-3"><b class="text-danger">*</b> indica un campo obbligatorio</p>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                        </button>
                        <input type="submit" class="btn blue-gradient" value="Aggiungi">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal delete category -->
    <div class="modal fade modal-open" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="deleteCategory"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Seleziona la categoria che vuoi eliminare</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="<?php echo URL ?>researchCases/deleteCategory" method="post" id="deleteCategoryForm">
                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Title -->
                        <div class="mt-3">
                            <label>Elimina categoria</label>
                            <select class="browser-default custom-select" name="delete_category_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <?php echo "<option value=" . $category['id'] . ">" . $category['name'] . "</option>" ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                        </button>
                        <input type="button" class="btn blue-gradient" value="Elimina"
                               data-toggle="modal" data-target="#confirmDeleteCategory">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal modify case-->
    <div class="modal fade modal-open" style="overflow-x: hidden; overflow-scrolling: auto" id="modifyCase"
         tabindex="-1" role="dialog"
         aria-labelledby="modifyCase"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="caseTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="<?php echo URL ?>researchCases/modifyCase" method="post" id="modifyForm">
                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Id case -->
                        <input type="hidden" id="modifyCaseId" name="modify_case_id">

                        <!-- Case name -->
                        <div class="mt-3">
                            <label>Nome della caso (massimo 50 caratteri) <b class="text-danger">*</b></label>
                            <input type="text" id="modifyCaseTitle" class="form-control" placeholder="Inserisci nome"
                                   name="modify_case_title" required value="<?php echo $case['title'] ?>">
                        </div>

                        <!-- Case category -->
                        <div class="mt-3">
                            <label>Seleziona una categoria <b class="text-danger">*</b></label>
                            <select class="browser-default custom-select" required name="modify_case_category"
                                    id="modifyCaseCategorySelect">
                                <?php foreach ($categories as $category): ?>
                                    <?php echo '<option value="' . $category['id'] . '" id="category' . $category['id'] . '"' . (intval($category['id']) == intval($case['category_id']) ? "selected>" : ">") ?>
                                    <?php echo $category['name'] . '</option>' ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Case variant -->
                        <div class="mt-3">
                            <label>Variante del caso:</label>
                            <select class="browser-default custom-select" required name="modify_case_variant"
                                    id="modifyCaseVariantSelect">
                                <option value="0" selected>Nessun caso</option>

                                <!-- check if there is already a selected option -->
                                <?php foreach ($all_cases as $case_variant): ?>

                                    <!-- Check if the option is selected -->

                                    <?php echo '<option value="' . $case_variant['id'] . '" id="variant' . $case_variant['id'] . '"' . (intval($case_variant['id']) == intval($case['variant']) ? " selected >" : ">") ?><?php echo '(id: ' . $case_variant['id'] . ') ' . $case_variant['title'] . '</option>' ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Case description -->
                        <div class="mt-3">
                            <label>Descrizione <b class="text-danger">*</b></label>
                            <textarea type="text" name="modify_case_description" rows="10"
                                      placeholder="Inserire descrizione"
                                      id="caseDescription"
                                      class="form-control md-textarea"
                                      required></textarea>
                        </div>
                        <p class="mt-3"><b class="text-danger">*</b> indica un campo obbligatorio</p>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn blue-gradient" data-dismiss="modal"
                        >Chiudi
                        </button>
                        <input type="button" class="btn blue-gradient" value="Salva"
                               data-toggle="modal" data-target="#confirmModifyCase">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirm delete case-->
    <div class="modal fade modal-open" id="confirmDeleteCase" tabindex="-1" role="dialog"
         aria-labelledby="confirmDeleteCase"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Elimina caso </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="<?php echo URL ?>researchCases/deleteCase" method="post">
                    <!-- Body -->
                    <div class="modal-body">

                        <div>
                            <input type="hidden" id="caseToDeleteId" name="caseToDeleteId">
                        </div>

                        <!-- msg -->
                        <p id="deleteCaseMessage"></p>

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

    <!-- Modal Confirm delete category-->
    <div class="modal fade modal-open black" id="confirmDeleteCategory" tabindex="-1" role="dialog"
         aria-labelledby="confirmDeleteCategory"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">

            <!-- Content -->
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Elimina categoria </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <!-- msg -->
                    <p id="deleteCategoryMessage">Sei sicuro di voler eliminare questa categoria?</p>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                    </button>
                    <button type="submit" class="btn blue-gradient" id="deleteCategorySubmit">Elimina</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal modify case confirm -->
    <div class="modal fade modal-open black" id="confirmModifyCase" tabindex="-1" role="dialog"
         aria-labelledby="confirmModifyCase"
         aria-hidden="true">

        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered " role="document">

            <!-- Content -->
            <div class="modal-content" style="overflow: hidden">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Conferma modifiche </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <!-- msg -->
                    <p>Sei sicuro di voler salvare le modifiche?</p>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn blue-gradient" data-dismiss="modal">Chiudi
                    </button>
                    <button class="btn blue-gradient" id="confirmModifySubmit">Conferma</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/knowledge_base/application/libs/js/modifyCases.js"></script>
<script>
    $(document).ready(function () {
        $('#casesTable').DataTable({
            "lengthMenu": [[10, 15, 20], [10, 15, 20]],
            "ordering": false,
            "language": {
                "search": "Cerca in live:",
                "lengthMenu": "Mostra _MENU_ record per pagina",
                "zeroRecords": "Nessun record trovato",
                "info": "Pagina _PAGE_ di _PAGES_",
                "infoEmpty": "Nessun record disponibile",
                "infoFiltered": "(filtrato da _MAX_ record totali)",
                "paginate": {
                    "previous": "Precedente",
                    "next": "Successiva"
                }
            }
        });
        $('.dataTables_length').addClass('bs-select');
    });

    $('.datepicker').datepicker({
        format: "dd.mm.yyyy",
        weekStart: 1,
        todayBtn: "linked",
        clearBtn: true,
        language: "it",
        todayHighlight: true,
        autoclose: true,
    });
</script>
