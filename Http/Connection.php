<?php

namespace Happyr\ApiClient\Http;

use Happyr\ApiClient\Configuration;
use Happyr\ApiClient\Exceptions\HttpException;

/**
 * Class Connection
 *
 * This class handles the connection to the API-Server
 */
class Connection
{
    /**
     * @var \Happyr\ApiClient\Configuration configuration
     *
     *
     */
    protected $configuration;

    /**
     * @var HttpRequestInterface request
     *
     *
     */
    protected $request;

    /**
     * Init the connection with our current configuration
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration, HttpRequestInterface $request=null)
    {
        $this->configuration=$configuration;

        if($request==null){
            $HttpRequestClass=$this->configuration->httpRequestClass;
            $request= new $HttpRequestClass();
        }
        $this->request=$request;
    }



    /**
     * Send a request. This will return the response.
     *
     * @param string $uri
     * @param array $data
     * @param string $httpVerb
     *
     * @return Response
     * @throws \Happyr\ApiClient\Exceptions\HttpException if we got a response code bigger or equal to 300
     * @throws \InvalidArgumentException
     */
    public function sendRequest($uri, array $data=array(), $httpVerb='GET'){
        $request=$this->request->createNew();

        if($httpVerb=='POST'){
            $this->preparePostData($ch, $data);
            $request->setOption(CURLOPT_URL, $this->buildUrl($uri));
        }
        elseif($httpVerb=='GET'){
            $request->setOption(CURLOPT_URL, $this->buildUrl($uri, $data));
        }
        else{
            throw new \InvalidArgumentException('httpVerb must be eihter "GET" or "POST"');
        }



        // Set a referer and user agent
        if(isset($_SERVER['HTTP_HOST'])){
            $request->setOption(CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        }
        $request->setOption(CURLOPT_USERAGENT, 'HappyrApiClient/'.$this->configuration->version);

        //do not include the http header in the result
        $request->setOption(CURLOPT_HEADER, 0);

        //return the data
        $request->setOption(CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        $request->setOption(CURLOPT_TIMEOUT, 10);

        //follow redirects
        $request->setOption(CURLOPT_FOLLOWLOCATION, true);

        //get headers
        $headers=array_merge(
            $this->getAcceptHeader(),
            $this->getAuthenticationHeader()
        );

        //add headers
        $request->setOption(CURLOPT_HTTPHEADER, $headers);

        //execute post
        $body = $request->execute();

        //get the http status code
        $httpStatus = $request->getInfo(CURLINFO_HTTP_CODE);

        //if we got some non good http response code
        if($httpStatus>=300){
            //throw exceptions
            throw new HttpException($httpStatus, $body);
        }

        //close connection
        $request->close();


        return new Response($body,$httpStatus);
    }



    /**
     * Get the accept header.
     * We specify the api version here.
     *
     * We choose to use xml over json because of the better backwards compatibility
     *
     * @return array
     */
    protected function getAcceptHeader()
    {
        return array(
            'Accept: application/vnd.happyrecruiting-v'.$this->configuration->version.'+'.$this->configuration->format,
        );
    }

    /**
     * Get the wsse authentication header
     *
     *
     * @return array
     */
    protected function getAuthenticationHeader()
    {
        $wsse=new Wsse($this->configuration->username, $this->configuration->token);

        return $wsse->getHeaders();
    }

    /**
     * Build the url with baseUrl and uri
     *
     * @param string $uri
     * @param array $filters
     *
     * @return string
     */
    protected function buildUrl($uri, array $filters= array()){
        $filterString='';

        //add the filter on the filter string
        if(count($filters)>0){
            $filterString='?';
            foreach($filters as $key=>$value){
                $filterString.=$key.'='.$value.'&';
            }
            rtrim($filterString,'&');
        }

        return $this->configuration->baseUrl.$uri.$filterString;
    }

    /**
     * Load the curl object with the post data
     *
     * @param var &$ch
     * @param array $data
     *
     */
    protected function preparePostData(&$ch, array $data=array())
    {
        $dataString='';

        //urlify the data for the POST
        foreach($data as $key=>$value) {
            $dataString .= $key.'='.$value.'&';
        }
        //remove the last '&'
        rtrim($dataString, '&');

        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $dataString);
    }
}
