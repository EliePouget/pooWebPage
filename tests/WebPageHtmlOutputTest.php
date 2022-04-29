<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class WebPageHtmlOutputTest extends TestCase
{
    /**
     * @param string $htmlString Le texte du document HTML
     * @return DOMDocument
     * @throws Exception
     */
    protected function createDomDocumentFromHtmlString(string $htmlString): DOMDocument
    {
        libxml_use_internal_errors(false);
        libxml_clear_errors();

        $domDocument = new DOMDocument();
        $domDocument->strictErrorChecking = true;
        if ($htmlString === '') {
            $this->fail('HTML string is empty');
        }
        if (@$domDocument->loadHTML($htmlString, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_PEDANTIC) === false) {
            $this->fail('unable to load HTML from string');
        }
        if (($lastError = libxml_get_last_error()) !== false) {
            $this->fail('unable to load HTML from string: '.$lastError->message);
        }

        return $domDocument;
    }

    /**
     * @param DOMDocument $domDocument Le document à étudier
     * @param string $xPathExpression L'expression XPath de recherche
     * @return DOMNodeList
     *
     * @see https://devhints.io/xpath
     * @see https://riptutorial.com/xpath
     */
    protected function xPathQuery(DOMDocument $domDocument, string $xPathExpression): DOMNodeList
    {
        $DOMXPath = new DOMXPath($domDocument);

        if (($result = @$DOMXPath->query($xPathExpression)) !== false) {
            return $result;
        }
        $this->markTestSkipped('bad XPath expression?');
    }

    protected function assertWebPageMatchesXPath(WebPage $webPage, string $xPathExpression, string $message = ''): void
    {
        $domDocument = $this->createDomDocumentFromHtmlString($webPage->toHTML());
        $domNodeList = $this->xPathQuery($domDocument, $xPathExpression);
        $this->assertCount(1, $domNodeList, $message);
    }

    public function testBasicHtmlStructure()
    {
        $webPage = new WebPage('title');

        $this->assertWebPageMatchesXPath($webPage, '/html', 'html ?');
        $this->assertWebPageMatchesXPath($webPage, '/html[contains(@lang, "fr")]', 'language ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/head');
        $this->assertWebPageMatchesXPath($webPage, '/html/head/meta[contains(translate(@charset, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz"), "utf-8")]', 'meta charset ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/head/meta[contains(@name, "viewport")]', 'meta viewport ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/head/title', 'title ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/body');
    }

    public function testTitleAsConstructorParameter()
    {
        $title = 'test / Title';
        $webPage = new WebPage($title);

        $this->assertWebPageMatchesXPath($webPage, '/html/head/title[normalize-space(text()) = "' . $title . '"]');
    }

    public function testSetTitle()
    {
        $webPage = new WebPage();
        $title = 'test / Title';
        $webPage->setTitle($title);

        $this->assertWebPageMatchesXPath($webPage, '/html/head/title[normalize-space(text()) = "' . $title . '"]');
    }

    public function testAppendToHead()
    {
        $webPage = new WebPage('title');
        $webPage->appendToHead('<meta http-equiv="refresh" content="30">');

        $this->assertWebPageMatchesXPath($webPage, '/html/head/meta[@http-equiv = "refresh"]', 'appendToHead ?');
    }

    public function testAppendCss()
    {
        $webPage = new WebPage('title');
        $css = '#css { color: red;}';
        $webPage->appendCss($css);

        $this->assertWebPageMatchesXPath($webPage, '/html/head/style[normalize-space(text()) = "' . $css . '"]', 'style ?');
    }

    public function testAppendCssUrl()
    {
        $webPage = new WebPage('title');
        $url = 'http://example.com/css/style.css';
        $webPage->appendCssUrl($url);

        $this->assertWebPageMatchesXPath($webPage, '/html/head/link[@href = "' . $url . '"]', 'link ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/head/link[@href = "' . $url . '" and @rel = "stylesheet"]', 'link rel= ?');
    }

    public function testAppendJs()
    {
        $webPage = new WebPage('title');
        $js = "windows.alert('hello world!');";
        $webPage->appendJs($js);

        $this->assertWebPageMatchesXPath($webPage, '/html/head/script[normalize-space(text()) = "' . $js . '"]', 'script ?');
    }

    public function testAppendJsUrl()
    {
        $webPage = new WebPage('title');
        $url = 'http://example.com/js/script.js';
        $webPage->appendJsUrl($url);

        $this->assertWebPageMatchesXPath($webPage, '/html/head/script[@src = "' . $url . '"]', 'script ?');
    }

    public function testAppendContent()
    {
        $webPage = new WebPage('title');
        $webPage->appendContent('<div class="content">Hello</div>');
        $webPage->appendContent('<div id="world">World!</div>');

        $this->assertWebPageMatchesXPath($webPage, '/html/body/div[@class = "content" and text() = "Hello"]', 'content ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/body/div[@id = "world" and text() = "World!"]', 'content ?');
        $this->assertWebPageMatchesXPath($webPage, '/html/body/div[@class = "content"]/following-sibling::div[@id = "world"]', 'content order ?');
    }
}
