<?php

namespace src\app\admin\controllers;

use src\app\admin\models\CustomerModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\CustomerViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class CustomerController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_list ()
  {
    $arrFilters = array(
        'name' => Get::text('name'),
        'email' => Get::text('email'),
        'pag' => Get::text('pag'),
    );

    $this->_data['list'] = $this->_model->getList($arrFilters);
    $this->_data['search'] = vh::formatFilters($arrFilters);

    $this->setErrorSessionData();

    $this->setListTemplate('customer_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $row = $id ? $this->_model->getById() : $this->getPrevious();

    $this->_data['table'] = vh::formatRow($row);

    $this->setSaveTemplate('customer_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

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
