<?php

namespace HappyR\ApiClient;

/**
 * Class Configuration
 *
 * This is the configuration of the client
 */
class Configuration
{
    //your username here
    public $username='';

    //Your api token goes here
    public $token='';

    //must end with a slash (/)
    public $baseUrl='http://happyrecruiting.se/api/';

    //The version of the API you want to use
    public $version='1.0';

    //You should have a good reason not to choose xml.
    public $format='xml';

    /**
     * @var string
     *
     * the class to use when serializing stuff. The class must implement
     * the HappyR\ApiClient\Serializer\SerializerInterface
     */
    public $serializerClass='\HappyR\ApiClient\Serializer\JmsSerializer';

    /**
     * @var string
     *
     * the class to use when making a http request. The class must implement
     * the HappyR\ApiClient\Http\HttpRequestInterface
     */
    public $httpRequestClass='\HappyR\ApiClient\Http\CurlRequest';


    //if true, we will throw exceptions on error
    public $enableExceptions=false;

    //enables debug mode
    public $debug=false;

    /**
     * @param null $username
     * @param null $token
     */
    public function __construct($username=null,$token=null)
    {
        if($token!=null){
            $this->token=$token;
        }

        if($username!=null){
            $this->username=$username;
        }
    }


}
