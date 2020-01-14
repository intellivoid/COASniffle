<?php


    namespace COASniffle\Handlers;


    use COASniffle\Abstracts\ApplicationType;
    use COASniffle\COASniffle;
    use COASniffle\Exceptions\BadResponseException;
    use COASniffle\Exceptions\CoaAuthenticationException;
    use COASniffle\Exceptions\InvalidRedirectLocationException;
    use COASniffle\Exceptions\RedirectParameterMissingException;
    use COASniffle\Exceptions\UnsupportedAuthMethodException;
    use COASniffle\Utilities\RequestBuilder;

    /**
     * Class COA
     * @package COASniffle\Handlers
     */
    class COA
    {
        /**
         * @var COASniffle
         */
        private $COASniffle;

        /**
         * COA constructor.
         * @param COASniffle $COASniffle
         */
        public function __construct(COASniffle $COASniffle)
        {
            $this->COASniffle = $COASniffle;
        }

        public function createAuthenticationRequest(string $redirect="None"): array
        {
            if(COA_SNIFFLE_APP_TYPE == ApplicationType::Redirect)
            {
                if($redirect == "None")
                {
                    throw new RedirectParameterMissingException();
                }

                if(filter_var($redirect, FILTER_VALIDATE_URL) == false)
                {
                    throw new InvalidRedirectLocationException();
                }
            }

            $Response = RequestBuilder::sendRequest(
                'coa',
                array(
                    'action' => "create_authentication_request",
                ),
                array(
                    'application_id' => COA_SNIFFLE_APP_PUBLIC_ID,
                    'redirect' => $redirect
                )
            );

            $ResponseJson = json_decode($Response['content'], true);
            if($ResponseJson == false)
            {
                throw new BadResponseException();
            }

            if($ResponseJson['status'] == false)
            {
                throw new CoaAuthenticationException($ResponseJson['error_code']);
            }

            return array(
                'request_token' => $ResponseJson['request_token'],
                'auth_url' => $ResponseJson['auth_url']
            );

        }

        /**
         * Requests for authentication and Returns the location for the user to authenticate to.
         *
         * This is the same as getAuthenticationURL() but it actually processes the
         * request to the URL you get from getAuthenticationURL() to get the redirect
         * URL that the user should open.
         *
         * This function isn't supposed to be used, instead use createAuthenticationRequest()
         * which accomplishes the same as this but you also get the Request Token instead of
         * just the authentication URL
         *
         * @param bool $include_host
         * @param string $redirect
         * @return string
         * @throws CoaAuthenticationException
         * @throws RedirectParameterMissingException
         * @throws UnsupportedAuthMethodException
         * @throws InvalidRedirectLocationException
         */
        public function requestAuthentication(bool $include_host=True, string $redirect="None"): string
        {
            if(COA_SNIFFLE_APP_TYPE == ApplicationType::Redirect)
            {
                if($redirect == "None")
                {
                    throw new RedirectParameterMissingException();
                }

                if(filter_var($redirect, FILTER_VALIDATE_URL) == false)
                {
                    throw new InvalidRedirectLocationException();
                }
            }

            $Response = RequestBuilder::sendRequest(
                'coa',
                array(
                    'action' => "request_authentication",
                ),
                array(
                    'application_id' => COA_SNIFFLE_APP_PUBLIC_ID,
                    'redirect' => $redirect
                )
            );

            if(is_null($Response['x_coa_error']) == false)
            {
                throw new CoaAuthenticationException($Response['x_coa_error']);
            }

            if($include_host)
            {
                return COA_SNIFFLE_ENDPOINT . $Response['redirect_location'];
            }

            return $Response['redirect_location'];
        }

        /**
         * Builds the authentication request URL only where the request token would be created
         * upon request
         *
         * Useful for adding the URL to a href value of a button/link which allows the user
         * to request for authentication to your Application
         *
         * @param string $redirect
         * @return string
         * @throws InvalidRedirectLocationException
         * @throws RedirectParameterMissingException
         */
        public function getAuthenticationURL(string $redirect="None"): string
        {
            if(COA_SNIFFLE_APP_TYPE == ApplicationType::Redirect)
            {
                if($redirect == "None")
                {
                    throw new RedirectParameterMissingException();
                }

                if(filter_var($redirect, FILTER_VALIDATE_URL) == false)
                {
                    throw new InvalidRedirectLocationException();
                }
            }

            $Parameters = array(
                'action' => "request_authentication",
                'application_id' => COA_SNIFFLE_APP_PUBLIC_ID,
                'redirect' => $redirect,
                'wrapper' => 'COASniffle'
            );

            $GetParameters = '?' . http_build_query($Parameters);
            return COA_SNIFFLE_ENDPOINT . '/auth/coa' . $GetParameters;
        }
    }