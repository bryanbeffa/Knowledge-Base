<?php ?>

<div class="p-5">
    <h1>Ricerca casi</h1>
    <hr>
    <div class="container-fluid">
        <form action="" method="post">

            <h3>Filtri Base</h3>
            <!-- base filters  -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Scegli ordinamento</label>
                <div class="col-sm-10">
                    <select class="browser-default custom-select" name="orderResults">
                        <option value="0" selected>Casi più recenti</option>
                        <option value="1">Casi più ricorrenti</option>
                    </select>
                </div>
            </div>


            <h3>Filtri avanzati</h3>
            <!-- Search text -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Inserisci parola chiave</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" placeholder="Ricerca" aria-label="Search">
                </div>
            </div>

            <!-- Category -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Seleziona categoria</label>
                <div class="col-sm-10">
                    <select class="browser-default custom-select" searchable="Search here..">
                        <option value="0" selected>Tutte le categorie</option>
                        <?php foreach ($categories as $category): ?>
                            <?php echo "<option value=" . $category['id'] . "selected>" . $category['name']. "</option>" ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Date -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Scegli una data</label>
                <div class="col-sm-10">
                    <input placeholder="Seleziona una data" type="date" class="form-control datepicker">
                </div>
            </div>

            <input type="submit" value="cerca" class="btn" style="background-color: #20d6a9">
        </form>
    </div>
    <hr>

    <h2>Risultati:</h2>

    <!-- Esempio di caso -->
    <div class="card mt-4 mb-4">

        <!-- Title -->
        <div class="card-header bg-transparent">
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

    <!-- Add case -->
    <p>Aggiungi <a href data-toggle="modal" data-target="#"> caso </a>

        <!-- Add category -->
    <p>Aggiungi una <a href data-toggle="modal" data-target="#"> categoria </a>
</div>
