<?php

namespace MicrosoftTranslator;

class AccessTokenAuthentication
{
    /**
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client secret.
     * @param string $authUrl      Oauth Url.
     *
     * @return string the access token
     * @throws \Exception if there is a CURL error
     */
    function getTokens( $grantType, $scopeUrl, $clientID, $clientSecret, $authUrl )
    {
        //Initialize the Curl Session.
        $ch = curl_init();
        //Create the request Array.
        $paramArr = array(
            'grant_type'    => $grantType,
            'scope'         => $scopeUrl,
            'client_id'     => $clientID,
            'client_secret' => $clientSecret
        );
        //Create an Http Query.//
        $paramArr = http_build_query( $paramArr );
        //Set the Curl URL.
        curl_setopt( $ch, CURLOPT_URL, $authUrl );
        //Set HTTP POST Request.
        curl_setopt( $ch, CURLOPT_POST, true );
        //Set data to POST in HTTP "POST" Operation.
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $paramArr );
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        //Execute the  cURL session.
        $strResponse = curl_exec( $ch );
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno( $ch );
        if ($curlErrno) {
            $curlError = curl_error( $ch );
            throw new \Exception( $curlError );
        }
        //Close the Curl Session.
        curl_close( $ch );
        //Decode the returned JSON string.
        $objResponse = json_decode( $strResponse );
        if (!empty( $objResponse->error )) {
            throw new \Exception( $objResponse->error_description );
        }
        return $objResponse->access_token;
    }
}
