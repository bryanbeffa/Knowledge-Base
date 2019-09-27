<?php ?>

<div class="p-5">
    <h1>Ricerca casi</h1>
    <hr>

    <!-- Filter mask -->
    <div class="container">
        <form action="" method="post">

            <h3 class="text-center">Filtri Base:</h3>

            <!-- base filters  -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Scegli ordinamento</label>
                <div class="col-sm-9">
                    <select class="browser-default custom-select" name="orderResults">
                        <option value="0" selected>Casi più recenti</option>
                        <option value="1">Casi più ricorrenti</option>
                    </select>
                </div>
            </div>

            <h3 class="text-center">Filtri avanzati:</h3>
            <hr>
            <!-- Search text -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Inserisci parola chiave</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" placeholder="Ricerca" aria-label="Search">
                </div>
            </div>

            <!-- Category -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Seleziona categoria</label>
                <div class="col-sm-9">
                    <select class="browser-default custom-select" name="category_filter">
                        <option value="0" selected>Tutte le categorie</option>
                        <?php foreach ($categories as $category): ?>
                            <?php echo "<option value=" . $category['id'] . ">" . $category['name'] . "</option>" ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Date -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Scegli una data</label>
                <div class="col-sm-9">
                    <input placeholder="Seleziona una data" type="date" class="form-control datepicker">
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

    <!-- Esempio di caso -->
    <div class="card mt-4 mb-4">

        <!-- Title -->
        <div class="card-header" style="background-color: #20d6a9">
            <h2 class="text-center"><b>Nome: </b>Prova</h2>
        </div>

        <!-- Body -->
        <div class="card-body">
            <h5><b>Categoria:</b> Reti aziendali</h5>
            <h5><b>Variante di:</b> Caso numero 2</h5>
            <h5><b>Data creazione:</b> 26.09.2019 14:36:02</h5>
            <h5 class="font-weight-bold">Descrizione:</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                software like Aldus PageMaker including versions of Lorem Ipsum.</p>

            <!-- Times -->
            <h5 class="font-weight-bold">Times: 6</h5>
        </div>

        <!-- Footer-->
        <div class="card-footer bg-transparent">
            <button class="btn" style="background-color: #20d6a9"> Elimina</button>
            <button class="btn" style="background-color: #20d6a9"> Modifica</button>
        </div>

    </div>

    <hr>

    <!-- Add case -->
    <p>Aggiungi <a href data-toggle="modal" data-target="#addCase"> caso </a></p>

    <!-- Add category -->
    <?php /** @var $is_admin contains if the user is an admin */
    if ($is_admin): ?>
        <p>Aggiungi una <a href data-toggle="modal" data-target="#addCategory"> categoria </a></p>
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

            <form action="<?php echo URL ?>home/addCategory" method="post">
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

            <form action="<?php echo URL ?>" method="post">
                <!-- Body -->
                <div class="modal-body">

                    <!-- Case name -->
                    <div class="mt-3">
                        <label>Nome della caso (massimo 50 caratteri) <b class="text-danger">*</b></label>
                        <input type="text" id="title" class="form-control" placeholder="Inserisci nome"
                               name="new_case_title" required>
                    </div>

                    <!-- Case category -->
                    <div class="mt-3">
                        <label>Seleziona una categoria <b class="text-danger">*</b></label>
                        <select class="browser-default custom-select" required name="category">
                            <?php foreach ($categories as $category): ?>
                                <?php echo "<option value=" . $category['id'] . "selected>" . $category['name'] . "</option>" ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Case variant -->
                    <div class="mt-3">
                        <label>Variante del caso:</label>
                        <select class="browser-default custom-select" required name="variant">
                            <option value="0" selected>Nessun caso</option>
                            <?php foreach ($cases as $case): ?>
                                <?php echo "<option value=" . $case['id'] . "selected>" . $case['title'] . "</option>" ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Case description -->
                    <div class="mt-3">
                        <label>Descrizione <b class="text-danger">*</b></label>
                        <textarea type="text" name="new_case_description" rows="10" placeholder="Inserire descrizione"
                                  class="form-control md-textarea"></textarea>
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


