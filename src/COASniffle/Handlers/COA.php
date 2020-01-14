<?php


    namespace COASniffle\Handlers;


    use COASniffle\COASniffle;
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

        public function requestAuthentication(): string
        {
            $Response = RequestBuilder::sendRequest(
                'coa', array(
                    'action' => "request_authentication"
                ),
                array(
                    'application_id' => COA_SNIFFLE_APP_PUBLIC_ID
                )
            );

            var_dump($Response);
        }
    }