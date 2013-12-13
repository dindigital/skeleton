<?

namespace src\app\adm005\models;

use src\models\Configuracao;

/**
 *
 * @package app.models
 */
class ConfiguracaoModel extends Configuracao
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Configuração');
  }

  public function setTitleHome ( $title_home )
  {
    if ( $title_home == '' )
      throw new \Exception('Title Home é obrigatório');

    $this->_table->title_home = $title_home;
  }

  public function setDescriptionHome ( $description_home )
  {
    if ( $description_home == '' )
      throw new \Exception('Description Home é obrigatório');

    $this->_table->description_home = $description_home;
  }

  public function setKeywordsHome ( $keywords_home )
  {
    if ( $keywords_home == '' )
      throw new \Exception('Keywords Home é obrigatório');

    $this->_table->keywords_home = $keywords_home;
  }

  public function setTitleInterna ( $title_interna )
  {
    if ( $title_interna == '' )
      throw new \Exception('Title Internas é obrigatório');

    $this->_table->title_interna = $title_interna;
  }

  public function setDescriptionInterna ( $description_interna )
  {
    if ( $description_interna == '' )
      throw new \Exception('Description Internas é obrigatório');

    $this->_table->description_interna = $description_interna;
  }

  public function setKeywordsInterna ( $keywords_interna )
  {
    if ( $keywords_interna == '' )
      throw new \Exception('Keywords Internas é obrigatório');

    $this->_table->keywords_interna = $keywords_interna;
  }

  public function setQtdHoras ( $qtd_horas )
  {
    $qtd_horas = intval($qtd_horas);

    if ( $qtd_horas < 0 )
      throw new \Exception('Qtd. hora deve ser um número maior ou igual a 0');

    $this->_table->qtd_horas = $qtd_horas;
  }

  public function setEmailAvisos ( $email_avisos )
  {
    if ( !\lib\Validation\Validate::email($email_avisos) )
      throw new \Exception('E-mail avisos deve ser um e-mail válido');

    $this->_table->email_avisos = $email_avisos;
  }

  public function getById ()
  {
    $SQL = '
    SELECT
      *
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, '1');

    if ( !count($result) )
      throw new \Exception('Configuração não encontrada.');

    return $result[0];
  }

  public function atualizar ( $title_home, $description_home, $keywords_home, $title_interna, $description_interna, $keywords_interna, $qtd_horas, $email_avisos )
  {
    if ( !$this->_dao->countByPk($this->_table, '1') )
      throw new \Exception('Registro não encontrado.');

    $this->setTitleHome($title_home);
    $this->setDescriptionHome($description_home);
    $this->setKeywordsHome($keywords_home);
    $this->setTitleInterna($title_interna);
    $this->setDescriptionInterna($description_interna);
    $this->setKeywordsInterna($keywords_interna);
    $this->setQtdHoras($qtd_horas);
    $this->setEmailAvisos($email_avisos);

    $this->_dao->update($this->_table, '1');
  }

}

