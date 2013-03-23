<?php

namespace MicrosoftTranslator;

class Translate
{
    /**
     * @var string Client ID of the application
     * @link http://msdn.microsoft.com/en-us/library/hh454950.aspx
     *
     */
    protected $clientID = '';

    /**
     * @var string Secret key of the application
     * @link http://msdn.microsoft.com/en-us/library/hh454950.aspx
     *
     */
    protected $clientSecret = '';

    /**
     * @var string OAuth Url
     */
    protected $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";

    /**
     * @var string Application Scope Url
     */
    protected $scopeUrl = "http://api.microsofttranslator.com";

    /**
     * @var string Application grant type
     */
    protected $grantType = "client_credentials";

    /**
     * @var string Base url for translate, ending in ? or & for including params as GET
     */
    protected $translateBaseUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?";

    /**
     * @param array $config the config, minimum: clientID and clientSecret
     */
    public function __construct( $config )
    {
        $this->clientID     = $config['clientID'];
        $this->clientSecret = $config['clientSecret'];

        if (!empty( $config['authUrl'] )) {
            $this->authUrl = $config['authUrl'];
        }
        if (!empty( $config['scopeUrl'] )) {
            $this->scopeUrl = $config['scopeUrl'];
        }
        if (!empty( $config['grantType'] )) {
            $this->grantType = $config['grantType'];
        }
    }

    /**
     * @param string $inputStr     the string to translate
     * @param string $fromLanguage ISO code 2 chars of the input text language
     * @param string $toLanguage   ISO code 2 chars of the output text language
     *
     * @return string|bool The translated string or FALSE if error occurs
     */
    public function translate( $inputStr, $fromLanguage, $toLanguage )
    {
        try {
            //Create the AccessTokenAuthentication object.
            $authObj = new AccessTokenAuthentication();

            //Get the Access token.
            $accessToken = $authObj->getTokens(
                $this->grantType,
                $this->scopeUrl,
                $this->clientID,
                $this->clientSecret,
                $this->authUrl
            );

            $authHeader    = "Authorization: Bearer " . $accessToken;
            $params        = "text=" . urlencode( $inputStr ) . "&to=" . $toLanguage . "&from=" . $fromLanguage;
            $translateUrl  = $this->translateBaseUrl . $params;
            $translatorObj = new HTTPTranslator();
            $curlResponse  = $translatorObj->curlRequest( $translateUrl, $authHeader );

            //Interprets a string of XML into an object.
            $xmlObj = simplexml_load_string( $curlResponse );
            foreach ( (array) $xmlObj[0] as $val ) {
                // TO-DO: this is strange, Microsoft guys... Why a foreach?
                $translatedStr = $val;
            }
            return $translatedStr;

        } catch ( Exception $e ) {

            error_log( "MicrosoftTranslator Translate error: " . $e->getMessage() . PHP_EOL );
            return false;
        }

    }
}
