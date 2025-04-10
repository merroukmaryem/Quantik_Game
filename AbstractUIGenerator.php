<?php
namespace Quantik2024;

class AbstractUIGenerator{

    protected static  function  getDebutHtml(string $title = "title content"): string {

        return "<!doctype html>
<html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>$title</title>
        <link rel='stylesheet' href='Style.css' type='text/css' />
        </head>
    <body>";

    }

    protected static function getFinHTML():string{
        return "</body>
                    </html>";
    }
    public static  function getPageErreur(string $message, string $urlLien): string
    {return self::getDebutHtml("400: Bad request").
        "<h1>$message</h1>
           
          <p><a href=\"$urlLien\">Retour à l'accueil</a></p>".self::getFinHTML();
    }

}