<?php


    namespace COASniffle\Utilities;


    use COASniffle\Exceptions\UnsupportedAuthMethodException;

    /**
     * Class RequestBuilder
     * @package COASniffle\Utilities
     */
    class RequestBuilder
    {
        /**
         * Sends a standard POST Request with the required fields for Intellivoid Accounts API Endpoint
         *
         * @param string $authMethod
         * @param array $parameters
         * @param array $payload
         * @return array
         * @throws UnsupportedAuthMethodException
         */
        public static function sendRequest(string $authMethod, array $parameters, array $payload): array
        {
            switch(strtolower($authMethod))
            {
                case 'otl':
                case 'khm':
                case 'coa':
                    $authMethod = strtolower($authMethod);
                    break;

                default:
                    throw new UnsupportedAuthMethodException();
            }

            // Build the data
            $GetParameters = '?' . http_build_query($parameters);
            $RequestUrl = COA_SNIFFLE_ENDPOINT . '/auth/' . $authMethod . $GetParameters;

            $CurlClient = curl_init($RequestUrl);
            curl_setopt($CurlClient, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($CurlClient, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($CurlClient, CURLOPT_HEADER, true);
            curl_setopt($CurlClient, CURLOPT_FOLLOWLOCATION, false);
            $CurlResponse = curl_exec($CurlClient);
            curl_close($CurlClient);

            $response_method = array(
                'body' => $CurlResponse,
                'content_type' => curl_getinfo($CurlClient, CURLINFO_CONTENT_TYPE),
                'response_code' => curl_getinfo($CurlClient, CURLINFO_HTTP_CODE)
            );

            if (preg_match('~Location: (.*)~i', $CurlResponse, $match)) {
                $response_method['redirect_location'] = trim($match[1]);
            }
            else
            {
                $response_method['redirect_location'] = trim($match[1]);
            }

            return $response_method;
        }
    }