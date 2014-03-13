<?php

namespace src\app\admin\helpers\Issuu;

use Exception;

/**
 * Class Issuu
 *
 * @package default
 * @author Jair Cueva Junior
 */
class Issuu
{

  protected $_api_key;
  protected $_api_secret;
  protected $_url = 'http://api.issuu.com/1_0';
  protected $_fields;
  protected $_action;

  public function __construct ( $api_key, $api_secret )
  {
    $this->_api_key = $api_key;
    $this->_api_secret = $api_secret;
  }

  public function document_url_upload ( $url, $name, $title )
  {
    if ( 'pdf' != strtolower(pathinfo($url, PATHINFO_EXTENSION)) )
      throw new Exception('Upload no Issuu é restringido a arquivos PDF');

    $this->_action = 'issuu.document.url_upload';
    $this->_fields['slurpUrl'] = $url;
    $this->_fields['name'] = $name;
    $this->_fields['title'] = $title;

    $response_json = $this->call();
    $document = $response_json->rsp->_content->document;

    $return = array();
    $return['link'] = "http://issuu.com/{$document->username}/docs/{$document->name}";
    $return['document_id'] = $document->documentId;
    $return['name'] = $document->name;

    return $return;
  }

  public function document_delete ( $name )
  {
    $this->_action = 'issuu.document.delete';
    $this->_fields['names'] = $name;

    return $this->call();
  }

  protected function setSignature ()
  {
    ksort($this->_fields);

    $serialized = '';

    foreach ( $this->_fields as $k => $v ) {
      if ( $k != 'file' )
        $serialized .= $k . $v;
    }

    $this->_fields['signature'] = md5($this->_api_secret . $serialized);
  }

  protected function call ()
  {
    $this->_fields['action'] = $this->_action;
    $this->_fields['format'] = 'json';
    $this->_fields['apiKey'] = $this->_api_key;
    $this->setSignature();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $this->_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->_fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response_text = curl_exec($ch);

    $this->_fields = array();

    if ( curl_errno($ch) )
      throw new Exception(curl_error($ch), 1);

    curl_close($ch);

    $response_json = json_decode($response_text);
    if ( json_last_error() )
      throw new Exception('Invalid JSON: ' . print_r($response_text), 1);

    if ( $error = @$response_json->rsp->_content->error )
      throw new Exception($error->message);

    return $response_json;
  }

}
