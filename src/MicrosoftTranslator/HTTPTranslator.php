<?php

namespace MicrosoftTranslator;

Class HTTPTranslator
{
    /**
     * Create and execute the HTTP CURL request.
     *
     * @param string $url        HTTP Url.
     * @param string $authHeader Authorization Header string.
     *
     * @return string string the response as a string
     * @throws \Exception
     */
    function curlRequest( $url, $authHeader )
    {
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt( $ch, CURLOPT_URL, $url );
        //Timeout of 5 seconds
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt( $ch, CURLOPT_TIMEOUT, 5);
        //Set the HTTP HEADER Fields.
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $authHeader, "Content-Type: text/xml" ) );
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        //Execute the  cURL session.
        $curlResponse = curl_exec( $ch );
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno( $ch );
        if ($curlErrno) {
            $curlError = curl_error( $ch );
            throw new \Exception( $curlError );
        }
        //Close a cURL session.
        curl_close( $ch );
        return $curlResponse;
    }
}
