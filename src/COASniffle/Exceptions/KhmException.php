<?php


    namespace COASniffle\Exceptions;


    use Exception;

    /**
     * Class KhmException
     * @package COASniffle\Exceptions
     */
    class KhmException extends Exception
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
         * KhmException constructor.
         * @param string $response_raw
         * @param array $parameters
         */
        public function __construct(string $response_raw, array $parameters)
        {
            parent::__construct("There was an error with the KHM response", 0, null);
            $this->response_raw = $response_raw;
            $this->parameters = $parameters;
        }
    }