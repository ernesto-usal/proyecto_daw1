<!--FILA CATEGORÍA-->
<div class="clearfix">
    <div class="col-xs-12">
        <h2><?= $nombre_categoria ?></h2>
    </div>
</div>
<div class="clearfix">
    <div class="col-xs-8 col-md-4">
        <figure class="card_popular">
            <img src="<?= $array_perfiles_populares[0]["ruta_imagen_perfil"] ?>" alt="sample102"/>
            <figcaption>
                <h3><?= $array_perfiles_populares[0]["nombre_perfil"] ?></h3>
            </figcaption>
        </figure>
    </div>
    <div class="col-xs-8 col-md-4">
        <figure class="card_popular">
            <img src="<?= $array_perfiles_populares[1]["ruta_imagen_perfil"] ?>" alt="sample102"/>
            <figcaption>
                <h3><?= $array_perfiles_populares[1]["nombre_perfil"] ?></h3>
            </figcaption>
        </figure>
    </div>
    <div class="col-xs-8 col-md-4">
        <figure class="card_popular">
            <img src="<?= $array_perfiles_populares[2]["ruta_imagen_perfil"] ?>" alt="sample102"/>
            <figcaption>
                <h3><?= $array_perfiles_populares[2]["nombre_perfil"] ?></h3>
            </figcaption>
        </figure>
    </div>
</div><!--FILA CATEGORÍA-->


