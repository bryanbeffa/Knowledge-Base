<?php ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="p-5">
    <h1>Ricerca casi</h1>
    <hr>

    <!-- Filter mask -->
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
                    <input class="form-control" type="text" placeholder="Ricerca" aria-label="Search" name="text_filter"
                           value="<?php echo (isset($_SESSION['text_filter'])) ? $_SESSION['text_filter'] : null ?>">
                </div>
            </div>

            <!-- Search Category -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Seleziona categoria</label>
                <div class="col-sm-9">
                    <select class="browser-default custom-select" name="category_filter">
                        <option value="0">Tutte le categorie</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo  $category['id']?>" <?php echo ((isset($_SESSION['category_filter']) && $_SESSION['category_filter'] == $category['id'])? "selected>" : ">") . $category['name'] . "</option>" ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Date -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Scegli una data</label>
                <div class="col-sm-9">
                    <input placeholder="Seleziona una data" type="date" class="form-control datepicker"
                           name="date_filter"
                           value="<?php echo (isset($_SESSION['date_filter'])) ? $_SESSION['date_filter'] : null ?>">
                </div>
            </div>

            <div class="container-fluid">
                <input type="submit" value="cerca" class="btn w-100" style="background-color: #20d6a9">
            </div>
        </form>
    </div>
    <hr>
    <!-- End filter mask-->

    <h2>Risultati:</h2>

    <!-- Show cases -->
    <?php foreach ($cases as $case): ?>

        <!-- Uses to print times variable -->
        <?php $key = $case['id']; ?>
        <div class="card mt-4 mb-5">

            <!-- Title -->
            <div class="card-header" style="background-color: #20d6a9">
                <h2 class="text-center"><b>Nome: </b><?php echo $case['title'] ?></h2>
            </div>

            <!-- Body -->
            <div class="card-body">
                <h5><b>ID:</b> <?php echo $case['id'] ?></h5>
                <h5><b>Categoria:</b> <?php echo ($case['category_id'] != null) ? $cases_categories["$key"] : "-" ?>
                </h5>
                <h5><b>Variante di:</b> <?php echo ($case['variant'] == null) ? "-" : $case['variant'] ?> </h5>
                <h5><b>Data creazione:</b> <?php echo date_format(date_create($case['created_at']), "d.m.Y H:i:s") ?>
                </h5>
                <h5 class="font-weight-bold">Descrizione:</h5>
                <p><?php echo $case['description'] ?></p>

                <!-- Times -->
                <h5><b>Numero di ripresentazioni: </b><?php echo $cases_times["$key"] ?> </h5>
            </div>

            <!-- Footer-->
            <?php if ($is_admin): ?>
                <div class="card-footer bg-transparent text-right">
                    <a class="btn text-dark" style="background-color: #20d6a9"
                       onclick="showModifyModal('<?php echo $case['title'] ?>' , '<?php echo $case['id'] ?>', '<?php echo $case['description'] ?>', '<?php echo $case['category_id'] ?>', '<?php echo $case['variant'] ?>')">Modifica</a>
                    <a class="btn" style="background-color: #20d6a9"
                       href="<?php echo URL . "researchCases/deleteCase/" . $case['id'] ?>"
                       onclick="return confirm('Sei sicuro di voler eliminare questo caso?');"> Elimina</a>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
    <hr>

    <!-- Add case -->
    <p>Aggiungi <a href data-toggle="modal" data-target="#addCase"> caso </a></p>

    <!-- Add category -->
    <?php /** @var $is_admin contains if the user is an admin */
    if ($is_admin): ?>
        <p>Aggiungi una <a href data-toggle="modal" data-target="#addCategory"> categoria </a></p>
    <?php endif; ?>
    <?php if ($is_admin): ?>
        <p><a href data-toggle="modal" data-target="#deleteCategory">Elimina </a>una categoria </p>
    <?php endif; ?>
</div>

<!-- Modal add category -->
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategory"
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
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #20d6a9">Chiudi
                    </button>
                    <input type="submit" class="btn" style="background-color: #20d6a9" value="Aggiungi">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal add case-->
<div class="modal fade" id="addCase" tabindex="-1" role="dialog" aria-labelledby="addCase"
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
                                <?php echo "<option value=" . $category['id'] . "selected>" . $category['name'] . "</option>" ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Case variant -->
                    <div class="mt-3">
                        <label>Variante del caso:</label>
                        <select class="browser-default custom-select" required name="new_case_variant">
                            <option value="0" selected>Nessun caso</option>
                            <?php foreach ($cases as $case): ?>
                                <?php echo "<option value=" . $case['id'] . "selected>" . " (id: " . $case['id'] . ") " . $case['title'] . "</option>" ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Case description -->
                    <div class="mt-3">
                        <label>Descrizione <b class="text-danger">*</b></label>
                        <textarea type="text" name="new_case_description" rows="10" placeholder="Inserire descrizione"
                                  class="form-control md-textarea"
                                  required><?php echo (isset($_SESSION['new_case_description'])) ? $_SESSION['new_case_description'] : "" ?></textarea>
                    </div>

                    <p class="mt-3"><b class="text-danger">*</b> indica un campo obbligatorio</p>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #20d6a9">Chiudi
                    </button>
                    <input type="submit" class="btn" style="background-color: #20d6a9" value="Aggiungi">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal delete category -->
<div class="modal fade" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="deleteCategory"
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

            <form action="<?php echo URL ?>researchCases/deleteCategory" method="post">
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
                    <button type="button" class="btn" data-dismiss="modal" style="background-color: #20d6a9">Chiudi
                    </button>
                    <input type="submit" class="btn" style="background-color: #20d6a9" value="Elimina"
                           onclick="return confirm('Sei sicuro di voler eliminare questa categoria?');">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal modify case-->
<div class="modal" id="modifyCase" tabindex="-1" role="dialog"
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

            <form action="<?php echo URL ?>researchCases/modifyCase" method="post">
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
                            <?php foreach ($cases as $variant): ?>

                                <!-- Check if the option is selected -->
                                <?php echo '<option value="' . $variant['id'] . '" id="variant' . $variant['id'] . '"' . (intval($variant['id']) == intval($case['variant']) ? " selected >" : ">") ?><?php echo '(id: ' . $variant['id'] . ') ' . $variant['title'] . '</option>' ?>
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
                    <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #20d6a9">Chiudi
                    </button>
                    <input type="submit" class="btn" style="background-color: #20d6a9" value="Salva"
                           onclick="return confirm('Sei sicuro di voler modificare questo caso?');">
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="/knowledge_base/application/libs/js/ModifyCases.js"></script>