<?php


    namespace COASniffle\Handlers;


    use COASniffle\Abstracts\ApplicationType;
    use COASniffle\COASniffle;
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

        /**
         * Returns the location for the user to authenticate to
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
                'coa', array(
                    'action' => "request_authentication"
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
    }