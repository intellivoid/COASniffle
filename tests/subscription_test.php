<?php

    use COASniffle\Abstracts\ApplicationType;
    use COASniffle\COASniffle;
    use COASniffle\Exceptions\ApplicationAlreadyDefinedException;
use COASniffle\Exceptions\BadResponseException;
use COASniffle\Exceptions\CoaAuthenticationException;
    use COASniffle\Exceptions\InvalidRedirectLocationException;
    use COASniffle\Exceptions\RedirectParameterMissingException;
use COASniffle\Exceptions\RequestFailedException;
use COASniffle\Exceptions\UnsupportedAuthMethodException;

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src';
    require($SourceDirectory . DIRECTORY_SEPARATOR . 'COASniffle' . DIRECTORY_SEPARATOR . 'COASniffle.php');

    $ApplicationConfiguration = array(
        "PublicID" => "APP4e89d34d6756306f5b90684922458a6a3db0ee38a06147e08f4692ddda4c9094920bcd5d",
        "SecretKey" => "0f2135ff26f0ee4c19ce1fd0ecd6ad70cf50ab6160f089186f0d9cf9a7348ef84c09536f",
        "Type" => ApplicationType::ApplicationPlaceholder,
        "Redirect" => "http://localhost:5002/"
    );

    $COASniffle = new COASniffle();

    try
    {
        $COASniffle->defineApplication(
            $ApplicationConfiguration["PublicID"],
            $ApplicationConfiguration["SecretKey"],
            $ApplicationConfiguration["Type"]
        );
    }
    catch (ApplicationAlreadyDefinedException $e)
    {
        print("ERROR: The application was already defined before" . PHP_EOL);
        exit(0);
    }

    try
    {
        $AuthenticationRequest = $COASniffle->getCOA()->createAuthenticationRequest($ApplicationConfiguration['Redirect']);
        print("Request Token: " . $AuthenticationRequest['request_token'] . PHP_EOL);
        print("Authentication URL: " . $AuthenticationRequest['auth_url'] . PHP_EOL);
    }
    catch (InvalidRedirectLocationException $e)
    {
        print("ERROR: The given redirect location is invalid" . PHP_EOL);
        exit(0);
    }
    catch (RedirectParameterMissingException $e)
    {
        print("ERROR: The redirect parameter is missing" . PHP_EOL);
        exit(0);
    }
    catch (BadResponseException $e)
    {
        print("ERROR: The server returned a response which cannot be parsed" . PHP_EOL);
        exit(0);
    }
    catch (CoaAuthenticationException $e)
    {
        print("COA ERROR (" . $e->getCode() . "): " . $e->getMessage() . PHP_EOL);
        exit(0);
    }
    catch (RequestFailedException $e)
    {
        print("REQUEST FAILURE: " . $e->getCurlError() . PHP_EOL);
        exit(0);
    }
    catch (UnsupportedAuthMethodException $e)
    {
        print("ERROR: The requested authentication method is unsupported in this library" . PHP_EOL);
        exit(0);
    }

    print(PHP_EOL);
    $AccessToken = null;

    switch(COA_SNIFFLE_APP_TYPE)
    {
        case ApplicationType::Redirect:
            print("Success! Once authenticated the user will be redirected to the redirection URL" . PHP_EOL);
            print("with a GET parameter included called 'access_token', using that you can" . PHP_EOL);
            print("interact with the user's account." . PHP_EOL);
            exit(0);
            break;

        case ApplicationType::ApplicationPlaceholder:

            print("Waiting for User Authentication");

            while(True)
            {
                try
                {
                    $AccessToken = $COASniffle->getCOA()->getAccessToken($AuthenticationRequest['request_token']);
                }
                catch (BadResponseException $e)
                {
                    print("ERROR: The server returned a response which cannot be parsed" . PHP_EOL);
                    exit(0);
                }
                catch (CoaAuthenticationException $e)
                {
                    print("COA ERROR (" . $e->getCode() . "): " . $e->getMessage() . PHP_EOL);
                    exit(0);
                }
                catch (RequestFailedException $e)
                {
                    print("REQUEST FAILURE: " . $e->getCurlError() . PHP_EOL);
                    exit(0);
                }
                catch (UnsupportedAuthMethodException $e)
                {
                    print("ERROR: The requested authentication method is unsupported in this library" . PHP_EOL);
                    exit(0);
                }

                if(is_null($AccessToken))
                {
                    print(".");
                    sleep(1);
                }
                else
                {
                    print("Done" . PHP_EOL);
                    break;
                }
            }

            break;

        case ApplicationType::Code:
            if (PHP_OS == 'WINNT')
            {
                echo 'Enter Access Toke: ';
                $AccessToken = stream_get_line(STDIN, 1024, PHP_EOL);
            }
            else
            {
                $AccessToken = readline('Enter Access Token ');
            }
            break;
    }

    print("Access Token: " . $AccessToken . PHP_EOL);
    print(PHP_EOL);

    $Results = $COASniffle->getCOA()->createSubscription($AccessToken, '1ad5be0df411a1fb62758b4c54690d0a702b4955b54dd22115416fa7b1ce51b4a084818d');

    print(json_encode($Results->toArray(), JSON_PRETTY_PRINT));
