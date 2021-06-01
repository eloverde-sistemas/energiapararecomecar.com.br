<?php

//-------- Setup autoloading
//$zf2Path = '/home/celpe/public_html/sigadm/lib/Zend2/library';

$zf2Path = $_SERVER["DOCUMENT_ROOT"]. '/sigadm/lib/Zend2/library';
require $zf2Path . '/Zend/Loader/AutoloaderFactory.php';

Zend\Loader\AutoloaderFactory::factory(array(
         'Zend\Loader\StandardAutoloader' => array(
             'autoregister_zf' => true
         )
));

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2.');
}

$idioma = 'pt';

//echo $idioma;
$_SESSION['idioma'] = $idioma;

$translate = new Zend\I18n\Translator\Translator();
$translate->addTranslationFile('phpArray', '../lang/lang-'.$idioma.'.php'); 

$translate->setLocale($idioma);