<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/clases/ScraperInstagram.php";

require_once $_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/modelos/Publicacion_modelo.php";

$modelo_publicaciones = new Publicacion_Modelo();
//$scrapper = new ScrapperInstagram("leomessi");
//$scrapper->echo_basic_info();

function pretty_print($var = false)
{
    echo "\n<pre style=\"background: #FFFF99; font-size: 10px;\">\n";
    $var = print_r($var, true);
    echo $var . "\n</pre>\n";
}

/*pretty_print();

$array_publicaciones = $scrapper->descargar_array_media();

//pretty_print($array_publicaciones);



foreach ($array_publicaciones as $indice => $elemento){
    if (!$modelo_publicaciones->insertar_publicacion($elemento["id_publicacion"], $elemento["titulo"],
                                                $elemento["fecha_creacion"], $elemento["ruta_recurso_media"],
                                                $elemento["texto"], $elemento["id_perfil"])){

    }{
        //print_r("Fallo en la inserción"."<br/>");
    }
}

print_r("CONTENIDO TABLA PUBLICACIONES"."<br/>");
pretty_print($modelo_publicaciones->get_all_publicaciones());*/






//PAGINACIÓN
$num_filas_total = sizeof($modelo_publicaciones->get_all_publicaciones());

$filas_por_pagina = 20;

$num_paginas = ceil($num_filas_total / $filas_por_pagina);


$pagina_actual = min($num_paginas, filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, array(
    'options' => array(
        'default' => 1,
        'min_range' => 1,
    ),
)));


$offset_query = (($pagina_actual - 1) * $filas_por_pagina);


$inicio = $offset_query + 1;
$final = min(($offset_query + $filas_por_pagina), $num_filas_total);

$link_anterior = ($pagina_actual > 1) ? '<a href="?pagina=1" title="Primera pagina">&laquo;</a> <a href="?pagina=' . ($pagina_actual - 1) . '" title="Página anterior">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
$link_siguiente = ($pagina_actual < $num_paginas) ? '<a href="?pagina=' . ($pagina_actual + 1) . '" title="Pagina siguiente">&rsaquo;</a> <a href="?pagina=' . $num_paginas . '" title="Última página">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

// Display the paging information
echo '<div id="paginador"><p>', $link_anterior, ' Página ', $pagina_actual, ' de ', $num_paginas, ' páginas, mostrando ', $inicio, '-', $final, ' de ', $num_filas_total, ' resultados ', $link_siguiente, ' </p></div>';


pretty_print($modelo_publicaciones->get_pag_publicaciones($filas_por_pagina, $offset_query));





