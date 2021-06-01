<?php

//-------- Setup autoloading
$zf2Path = $_SERVER["DOCUMENT_ROOT"]. '/lib/Zend2/library';
//$zf2Path = $_SERVER["DOCUMENT_ROOT"]. '/lib/Zend2/library';
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

if( in_array($_SESSION['idioma'], array('pt', 'es', 'en')) ){
	$idioma = $_SESSION['idioma'];
}

$_SESSION['idioma'] = $idioma;

$translate = new Zend\I18n\Translator\Translator();
$translate->addTranslationFile('phpArray', '../lang/lang-'.$_SESSION['idioma'].'.php'); 

$translate->setLocale($_SESSION['idioma']);