<?php


    namespace COASniffle\Handlers;


    use COASniffle\Abstracts\ApplicationType;
    use COASniffle\COASniffle;
    use COASniffle\Exceptions\CoaAuthenticationException;
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
         * @param string $redirect
         * @return string
         * @throws CoaAuthenticationException
         * @throws UnsupportedAuthMethodException
         * @throws RedirectParameterMissingException
         */
        public function requestAuthentication(string $redirect="None"): string
        {
            if(COA_SNIFFLE_APP_TYPE == ApplicationType::Redirect)
            {
                if($redirect == "None")
                {
                    throw new RedirectParameterMissingException();
                }
            }

            $Response = RequestBuilder::sendRequest(
                'coa', array(
                    'action' => "request_authentication"
                ),
                array(
                    'application_id' => COA_SNIFFLE_APP_PUBLIC_ID
                )
            );

            if(is_null($Response['x_coa_error']) == false)
            {
                throw new CoaAuthenticationException($Response['x_coa_error']);
            }

            return $Response['redirect_location'];
        }
    }