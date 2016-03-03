<?php

namespace Smartbox\Integration\FrameworkBundle\Connectors;

use Smartbox\Integration\FrameworkBundle\Exceptions\SoapConnectorException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractSoapConfigurableConnector
 *
 * @package Smartbox\Integration\FrameworkBundle\Connectors
 */
abstract class AbstractSoapConfigurableConnector extends ConfigurableConnector {
    const REQUEST_PARAMETERS = 'parameters';
    const REQUEST_NAME = 'name';
    const SOAP_METHOD_NAME = 'soap_method';
    const SOAP_OPTIONS = 'soap_options';
    const SOAP_HEADERS = 'soap_headers';

    /** @var  \SoapClient */
    protected $soapClient;

    /**
     * @param $connectorOptions
     *
     * @return \SoapClient
     */
    public abstract function getSoapClient($connectorOptions);

    /**
     * {@inheritDoc}
     */
    public function executeStep($stepAction, $stepActionParams, $options, array &$context)
    {
        if(!parent::executeStep($stepAction,$stepActionParams,$options,$context)){
            switch ($stepAction){
                case self::STEP_REQUEST:
                    $this->request($stepActionParams, $options, $context);
                    return true;
            }
        }

        return false;
    }

    /**
     * @param string $methodName
     * @param array  $params
     * @param array  $connectorOptions
     * @param array  $soapOptions
     * @param array  $soapHeaders
     *
     * @return \stdClass
     */
    protected function performRequest($methodName, $params, array $connectorOptions, array $soapOptions = null, array $soapHeaders = null){
        $soapClient = $this->getSoapClient($connectorOptions);
        if(!$soapClient){
            throw new \RuntimeException("SoapConfigurableConnector requires a SoapClient as a dependency");
        }

        return $soapClient->__soapCall($methodName, $params, $soapOptions, $soapHeaders);
    }

    /**
     * @param array $stepActionParams
     * @param array $connectorOptions
     * @param array $context
     *
     * @throws \Smartbox\Integration\FrameworkBundle\Exceptions\SoapConnectorException
     */
    protected function request(array $stepActionParams, array $connectorOptions, array &$context)
    {
        $paramsResolver = new OptionsResolver();
        $paramsResolver->setRequired([
            self::SOAP_METHOD_NAME,
            self::REQUEST_PARAMETERS,
            self::REQUEST_NAME
        ]);

        $paramsResolver->setDefined([
            self::SOAP_OPTIONS,
            self::SOAP_HEADERS,
        ]);

        $params = $paramsResolver->resolve($stepActionParams);

        $requestName = $params[self::REQUEST_NAME];
        $soapMethodName = $params[self::SOAP_METHOD_NAME];
        $soapMethodParams = $this->resolve($params[self::REQUEST_PARAMETERS], $context);
        $soapOptions = isset($params[self::SOAP_OPTIONS]) ? $params[self::SOAP_OPTIONS] : [];
        $soapHeaders = isset($params[self::SOAP_HEADERS]) ? $params[self::SOAP_HEADERS] : [];

        // creates a proper set of SoapHeader objects
        $processedSoapHeaders = array_map(function($header){
            if (is_array($header)) {
                $header = new \SoapHeader($header[0], $header[1], $header[2]);
            }
            if (!$header instanceof \SoapHeader) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid soap header "%s". Expected instance of \SoapHeader or array containing 3 values representing'.
                    ' "namespace", "header name" and "header value"',
                    json_encode($header)
                ));
            }

            return $header;
        }, $soapHeaders);

        try{
            $result = $this->performRequest($soapMethodName,$soapMethodParams,$connectorOptions, $soapOptions, $processedSoapHeaders);
        }catch (\Exception $ex){
            $soapClient = $this->getSoapClient($connectorOptions);
            $exception = new SoapConnectorException($ex->getMessage(), $ex->getCode(), $ex);
            $exception->setRawRequest($soapClient->__getLastRequest());
            $exception->setRawResponse($soapClient->__getLastResponse());
            throw $exception;
        }

        $context[self::KEY_RESPONSES][$requestName] = $result;
    }
}
