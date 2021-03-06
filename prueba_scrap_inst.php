<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/clases/ScraperInstagram.php";

require_once $_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/modelos/Publicacion_modelo.php";

$modelo_publicaciones = new Publicacion_Modelo();
$scrapper = new ScrapperInstagram("neymarjr");
$scrapper->echo_basic_info();

function pretty_print($var = false)
{
    echo "\n<pre style=\"background: #FFFF99; font-size: 10px;\">\n";
    $var = print_r($var, true);
    echo $var . "\n</pre>\n";
}

$array_publicaciones = $scrapper->descargar_array_media();

foreach ($array_publicaciones as $indice => $elemento){
    if (!$modelo_publicaciones->insertar_publicacion($elemento["id_publicacion"], $elemento["titulo"],
                                                $elemento["fecha_creacion"], $elemento["ruta_recurso_media"],
                                                $elemento["texto"], $elemento["id_perfil"])){

    }{
        //print_r("Fallo en la inserción"."<br/>");
    }
}

print_r("CONTENIDO TABLA PUBLICACIONES"."<br/>");
pretty_print($modelo_publicaciones->get_all_publicaciones());
//pretty_print($modelo_publicaciones->get_publicaciones_perfil($scrapper->id_usuario));
/*$modelo_publicaciones = new Publicacion_Modelo();
$publicaciones_perfil = $modelo_publicaciones->get_publicaciones_perfil("leomessi");
pretty_print($publicaciones_perfil);*/