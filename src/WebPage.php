<?php
declare(strict_types = 1);
class WebPage
{
    private string $head;
    private string $title;
    private string $body;

    /**
     * WebPage constructor.
     * @param string $title Titre de la page
     */
    public function __construct(string $title = ""){
        $this->title = $title;
        $this->head = "";
        $this->body = "";
    }

    /**
     * Retourne le contenu de $this->head
     * @return string head
     */
    public function getHead():string
    {
        return $this->head;
    }

    /**Retourne le contenu de $this->title
     * @return string
     */
    public function getTitle():string
    {
        return $this->title;
    }

    /**Affecter le titre de la page
     * @param string $title le titre
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**Retourne le contenu de $this->body
     * @return string
     */
    public function getBody():string
    {
        return $this->body;
    }

    /**Ajouter un contenu dans $this->body
     * @param string $content
     */
    public function appendToHead(string $content){
        $this->head.=$content;
    }

    /**Ajouter un contenu CSS dans $this->head
     * @param string $css
     */
    public function appendCss(string $css){
        $style = "<style>".$css."</style>";
        $this->appendToHead($style);
    }

    /**Ajouter l'URL d'un script CSS dans $this->head
     * @param string $url L'URL du script CSS
     */
    public function appendCssUrl(string $url){
        $link = '<link rel="stylesheet" href="'.$url.'">';
        $this->appendToHead($link);
    }

    /**Ajouter un contenu JS dans $this->head
     * @param string $js
     */
    public function appendJs(string $js){
        $script= '<script type="text/javascript">'.$js.'</script>';
        $this->appendToHead($script);
    }

    /**Ajouter l'URL d'un script JS dans $this->head
     * @param string $url L'URL du script JS
     */
    public function appendJsUrl(string $js){
        $script= '<script type="text/javascript" src="'.$js.'"></script>';
        $this->appendToHead($script);
    }

    /**Ajouter un contenu dans $this->body
     * @param string $content
     */
    public function appendContent(string $content){
        $this->body.=$content;
    }

    /**Produire la page web complète
     * @return string
     */
    public function toHTML():string{
        $html= '<!doctype html><html lang="fr"><head>'.$this->head.'<meta charset="utf-8"><meta name="viewport"><title>'.$this->title.'</title></head><body>'.$this->body.'<div id="foot"> '.$this->getLastModification().'</div></body></html>';
        return $html;
    }

    /**Donner la date et l'heure de la dernière modification du script principale
     * @return string
     */
    public static function getLastModification():string{
        return "Dernière modification de cette page le ".date("d/m/y",getlastmod())." à ".date("H:i:s");
    }

    /**Protéger les caractères principaux pouvant dégrader la page Web
     * @param string $string la chaine à protéger
     * @return string la chaine protégée
     */
    public static function escapeString(string $string):string{
        return htmlspecialchars($string,3|16,"UTF-8");
    }
}