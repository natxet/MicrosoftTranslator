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
    protected $service_url = array(
        'single'   => "http://api.microsofttranslator.com/V2/Ajax.svc/TranslateArray",
        'multiple' => "http://api.microsofttranslator.com/V2/Ajax.svc/Translate"
    );

    /**
     * @var string The token, if you have it
     */
    static protected $token;

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
     * @param string|array $texts         the string to translate. If array, an array of strings
     * @param string       $to            ISO code 2 chars of the output text language
     * @param string       $from          ISO code 2 chars of the input text language
     *
     * @return string|array|bool The translated string or FALSE if error occurs. If array given, array returned.
     */
    public function translate( $texts, $to, $from )
    {
        try {
            if (!is_array( $texts )) {
                // TO-DO: Do this also for arrays, if all items are empty, avoid connecting
                if( empty( $texts ) ) return '';
                $texts = array( $texts );
            }
            $translations = array();
            $response     = json_decode( $this->requestService( $this->getUrl( 'single', $texts, $to, $from ) ) );
            foreach ( $response as $translation ) {
                $translations[] = $translation->TranslatedText;
            }
            if (count( $translations ) == 1) {
                return $translations[0];
            } else {
                return $translations;
            }

        } catch ( \Exception $e ) {
            error_log( "MicrosoftTranslator Translate error: " . $e->getMessage() . PHP_EOL );
            return false;
        }

    }

    /**
     * @return string the token, stored in a static property for runtime cache.
     */
    protected function getToken()
    {
        if (empty( self::$token )) {
            $authObj     = new AccessTokenAuthentication();
            self::$token = $authObj->getTokens(
                $this->grantType,
                $this->scopeUrl,
                $this->clientID,
                $this->clientSecret,
                $this->authUrl
            );
        }
        return self::$token;
    }

    protected function getUrl( $service = 'single', array $texts, $to, $from )
    {
        $params = array( 'to' => $to, 'from' => $from, 'texts' => json_encode( $texts ) );
        return $this->service_url[$service] . '?' . \http_build_query( $params );
    }

    /**
     * @param string $url the URL to call
     *
     * @return string the CURL response, plain text, without the BOM bytes
     */
    protected function requestService( $url )
    {
        $auth_header   = "Authorization: Bearer " . $this->getToken();
        $translator    = new HTTPTranslator();
        $curl_response = $translator->curlRequest( $url, $auth_header );
        // Get rid of the UTF-16 BOM HEXCODE http://stackoverflow.com/a/7128250/1237569
        $curl_response = substr( $curl_response, 3 );
        return $curl_response;
    }

}
