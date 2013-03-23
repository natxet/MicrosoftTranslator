<?php

$config = array(
    'clientID' => '',
    'clientSecret' => ''
);
$t = new \MicrosoftTranslator\Translate( $config );
$translation = $t->translate('Hola', 'es', 'en');
echo $translation;
