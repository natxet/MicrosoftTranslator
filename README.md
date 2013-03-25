Translate your texts using Microsoft's Bing Translation services HTTP API http://msdn.microsoft.com/en-us/library/ff512419.aspx

The code is based on the one provided by Microsoft at the documentation, prepared for composer. In two lines, you can have a translation service working!

Before working with the code, get your Access Token, using your MSN account. More info at: http://msdn.microsoft.com/en-us/library/hh454950.aspx

Don't get confused with the clientID. It's not the Customer ID nor your account key. The clientID is the text (possibly your app name or some plain-language text) that you specified when registering your application. You can view your client id here: https://datamarket.azure.com/developer/applications

There is a free data tier of 2 million characters per month. Check here if you haven't used yet your monthly limit: https://datamarket.azure.com/account/datasets

Use with composer:

	{
		"require": {
			"natxet/microsoft-translation-api": "*"
		},
		"minimum-stability": "dev"
	}

And then code something like this:

	include "vendor/autoload.php";
	$config      = array( 'clientID' => 'myproject', 'clientSecret' => 'PYdLDxusfg4+MPdLDxudLDxusfg4+sfg4+Q1XixZ=');
	$translator  = new \MicrosoftTranslator\Translate( $config );
	$translation = $translator->translate(array('Hola', 'Adiós'), 'en', 'es');
	var_dump( $translation );
