<?php


    namespace COASniffle\Handlers;


    use COASniffle\Exceptions\BadResponseException;
    use COASniffle\Utilities\RequestBuilder;

    class OTL
    {
        public static function registerHost(string $remote_host, string $user_agent): string
        {
            $Parameters =  array(
                'remote_host' => $remote_host,
                'user_agent' => $user_agent,
            );
            $Response = RequestBuilder::sendRequest(
                'khm',
                array(
                    'action' => "register_host",
                ), $Parameters
            );

            $ResponseJson = json_decode($Response['content'], true);
            if($ResponseJson == false)
            {
                throw new BadResponseException();
            }

            if($ResponseJson['status'] == false)
            {
                throw new KhmException($Response['content'], $Parameters);
            }

            return $ResponseJson['host_id'];
        }
    }