<?php

/**
 * Helper class that manages the API key for the CBIS API.
 *
 * @package cbisimport
 */
class CbisClient extends SoapClient {
  function __construct($service, $options) {
    $url = variable_get('cbisimport_api_url', '');

    $this->service = $service;
    $this->apiKey = variable_get('cbisimport_api_key', '');
    $wsdl = sprintf('%s/%s.asmx?WSDL', $url, $service);

    // Enable logging of last request
    $options['trace'] = 1;

    try {
      parent::__construct($wsdl, $options);
    } catch (Exception $e) {
      drupal_set_message(t('Failed to access CBIS: @message', array(
        '@message' => $e->getMessage(),
      )));
    }
  }

  public function __call($method, $arguments) {
    if (empty($arguments)) {
      $arguments[] = array(
        'apiKey' => $this->apiKey
      );
    }
    else {
      $arguments[0] = array_merge(array(
          'apiKey' => $this->apiKey
        ), $arguments[0]);
    }

    $result = NULL;
    try {
      $result = parent::__call($method, $arguments);

      // Log the raw xml from CBIS in our transaction
      CbisTransaction::getCurrentTransaction()
        ->addState('raw_xml.xml', self::__getLastResponse());
    } catch (Exception $e) {
      drupal_set_message(t('Failed to access CBIS: @message', array(
        '@message' => $e->getMessage(),
      )));
    }
    return $result;
  }
}
