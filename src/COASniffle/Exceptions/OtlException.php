<?php


    namespace COASniffle\Exceptions;


    use Exception;

    /**
     * Class OtlException
     * @package COASniffle\Exceptions
     */
    class OtlException extends Exception
    {
        /**
         * @var string
         */
        private $response_raw;

        /**
         * @var array
         */
        private $parameters;

        /**
         * @var int
         */
        private $status_code;

        /**
         * @var string
         */
        private $error_message;

        /**
         * OtlException constructor.
         * @param string $response_raw
         * @param array $parameters
         * @param int $status_code
         * @param string $message
         */
        public function __construct(string $response_raw, array $parameters, int $status_code, string $message)
        {
            parent::__construct("There was an error with the OTL response", 0, null);
            $this->response_raw = $response_raw;
            $this->parameters = $parameters;
            $this->status_code = $status_code;
            $this->error_message = $message;
        }
    }