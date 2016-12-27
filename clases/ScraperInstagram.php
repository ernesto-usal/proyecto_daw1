<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/vendor/autoload.php";
//require_once "../vendor/autoload.php";
use InstagramScraper\Instagram;
use InstagramScraper\Model\Media;

date_default_timezone_set('Europe/Madrid');

class ScrapperInstagram
{

    /*---ATRIBUTOS---*/
    private $nombre_usuario;
    private $id_usuario;
    private $nombre_completo;
    private $numero_publicaciones;
    private $numero_seguidores;
    private $numero_seguidos;
    private $cuenta_instagram;
    private $array_media;

    /*---MÉTODOS---*/
    /*---Constructor de la clase*/
    public function __construct($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
        $this->cuenta_instagram = Instagram::getAccount($this->nombre_usuario);
        $this->id_usuario = $this->cuenta_instagram->id;
        $this->nombre_completo = $this->cuenta_instagram->fullName;
        $this->numero_publicaciones = $this->cuenta_instagram->mediaCount;
        $this->numero_seguidores = $this->cuenta_instagram->followedByCount;
        $this->numero_seguidos = $this->cuenta_instagram->followsCount;
        $this->array_media = Instagram::getMedias($this->nombre_usuario, 30/*$this->numero_publicaciones*/);
    }

    /*---Método para mostrar la información básica del perfil de instagram*/
    public function echo_basic_info()
    {
        print_r("-----INFO CUENTA INSTAGRAM-----" . '<br/>');
        print_r("&nbsp&nbsp&nbspNOMBRE CUENTA=> " . $this->nombre_usuario . '<br/>');
        print_r("&nbsp&nbsp&nbspID DE USUARIO=> " . $this->id_usuario . '<br/>');
        print_r("&nbsp&nbsp&nbspNOMBRE COMPLETO=> " . $this->nombre_completo . '<br/>');
        print_r("&nbsp&nbsp&nbspNº PUBLICACIONES=> " . $this->numero_publicaciones . '<br/>');
        print_r("&nbsp&nbsp&nbspNº SEGUIDORES=> " . $this->numero_seguidores . '<br/>');
        print_r("&nbsp&nbsp&nbspNº SEGUIDOS=> " . $this->numero_seguidos . '<br/>');
        print_r("<br/><br/>");
    }

    /*---Método para descargar el array de recursos media en una carpeta con el nombre del usuario*/
    public function descargar_array_media()
    {
        $num_repetidos = 0;

        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss")) {
            mkdir($_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss", 0777, true);
        }
        chdir($_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss");
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss/instagram")) {
            mkdir($_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss/instagram", 0777, true);
        }
        chdir($_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss/instagram");
        //Se crea la carpeta con el nombre del usuario si no existe
        if (!file_exists($this->nombre_usuario)) {
            mkdir($this->nombre_usuario, 0777, true);
        }
        //Se cambia el directorio actual al nuevo creado
        chdir($this->nombre_usuario);

        $array_resultado = array();

        //Se recorre el array de media resources y se descargan en la carpeta
        foreach ($this->array_media as $indice => $elemento) {
            //echo "+++Descargando Recurso "/*.(10-$indice).".*/.($this->numero_publicaciones-$indice)."\n\tTIPO=> ".$elemento->type/*."\n\tURL=> "*/;
            print_r("+++Descargando recurso " . date('Y-m-d H:i:s', $elemento->createdTime) . "
                        <br/>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTipo=> " . $elemento->type);
            if ($elemento->type == "video") {//CASO VÍDEO
                //echo $elemento->videoStandardResolutionUrl."\n";
                //$nombre_archivo = $this->nombre_usuario."_"/*.(10-$indice).*/.($this->numero_publicaciones-$indice).'.mp4';
                $nombre_archivo = date('Y-m-d H:i:s', $elemento->createdTime) . ".mp4";
                if (!file_exists($nombre_archivo)) {
                    copy($elemento->videoStandardResolutionUrl, $nombre_archivo);
                    print_r("<br/>");
                } else {
                    print_r("  NO SE DESCARGA PORQUE YA ESTÁ GUARDADO" . "<br/>");
                    $num_repetidos++;
                }
            } else {//CASO IMAGEN
                //echo $elemento->imageHighResolutionUrl."\n";
                //$nombre_archivo = $this->nombre_usuario."_"/*.(10-$indice).*/.($this->numero_publicaciones-$indice).'.png';
                $nombre_archivo = date('Y-m-d_H-i-s', $elemento->createdTime) . ".png";
                if (!file_exists($nombre_archivo)) {
                    copy($elemento->imageHighResolutionUrl, $nombre_archivo);
                    print_r("<br/>");
                } else {
                    print_r("  NO SE DESCARGA PORQUE YA ESTÁ GUARDADO" . "<br/>");
                    $num_repetidos++;
                }
            }


            /*print_r("&nbsp&nbspid_publicacion => ".$elemento->id. '<br/>');
            print_r("&nbsp&nbsptitulo => ".substr($elemento->caption, 0, 20)."...". '<br/>');
            print_r("&nbsp&nbspfecha => ".date('Y-m-d H:i:s', $elemento->createdTime). '<br/>');
            print_r("&nbsp&nbspruta_media => ".$_SERVER["DOCUMENT_ROOT"]."/proyecto_daw1/descargas_rrss/instagram/".
                    $this->nombre_usuario."/".$nombre_archivo. '<br/>');
            print_r("&nbsp&nbsptexto => ".$elemento->caption. '<br/>');
            print_r("&nbsp&nbspid_perfil => ".$this->id_usuario."($this->nombre_usuario)<br/>");*/


            $array_resultado[$indice] = [
                "id_publicacion" => $elemento->id,
                "titulo" => strlen($elemento->caption) < 20 ? $elemento->caption : substr($elemento->caption, 0, 20) . "...",
                "fecha_creacion" => date('Y-m-d H:i:s', $elemento->createdTime),
                "ruta_recurso_media" => $_SERVER["DOCUMENT_ROOT"] . "/proyecto_daw1/descargas_rrss/instagram/" .
                    $this->nombre_usuario . "/" . $nombre_archivo,
                "texto" => $elemento->caption,
                "id_perfil" => $this->id_usuario,
            ];
        }

        print_r("<br/><br/>");
        $num_descargados = ($indice + 1) - $num_repetidos;
        print_r("--- DESCARGADOS " . $num_descargados . " ARCHIVOS NUEVOS DE "
            . $this->numero_publicaciones . " PUBLICACIONES ---");
        print_r("<br/><br/>");

        return $array_resultado;
    }
}

?>