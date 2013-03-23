Using Microsoft's Bing Translation services http://www.bing.com/translator/ HTTP API http://msdn.microsoft.com/en-us/library/ff512419.aspx

The code is based on the one provided by Microsoft at the documentation, prepared for composer. In two lines, you can have a translation service working!

Before working with the code, get your Access Token, using your MSN account. More info at: http://msdn.microsoft.com/en-us/library/hh454950.aspx


Use with composer:

	{
		"require": {
			"natxet/microsoft-translation-api": "*"
		},
		"minimum-stability": "dev"
	}

And the in your code:

	include "vendor/autoload.php";

	$t = new \MicrosoftTranslator\Translate( $azure_config );
	$translation = $t->translate('Hola', 'es', 'en');
	echo $translation;

And you're done!
