<?php

namespace src\app\admin\controllers;

use src\app\admin\models\CustomerModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class CustomerSaveController extends BaseControllerAdm
{

  protected $_model;
  protected $_id;

  public function __construct ( $id )
  {
    $this->_id = $id;
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();

  }

  public function get ()
  {
    $this->defaultSavePage('customer_save.phtml', $this->_id);

  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'business_name' => Post::text('business_name'),
          'name' => Post::text('name'),
          'document' => Post::text('document'),
          'email' => Post::text('email'),
          'address_postcode' => Post::text('address_postcode'),
          'address_street' => Post::text('address_street'),
          'address_area' => Post::text('address_area'),
          'address_number' => Post::text('address_number'),
          'address_complement' => Post::text('address_complement'),
          'address_state' => Post::text('address_state'),
          'address_city' => Post::text('address_city'),
          'phone_ddd' => Post::text('phone_ddd'),
          'phone_number' => Post::text('phone_number')
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

  }

}
