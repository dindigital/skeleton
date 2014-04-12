<?php

namespace src\app\admin\models\essential;

interface Facepostable
{

  /**
   * Seta o id da seção que eu estiver.
   */
  public function setId ( $id );

  /**
   * Retorna o post a ser enviado. Seguindo o padrao:
    $post = array(
    'name'=>'',
    'message'=>'',
    'link'=>'',
    'description'=>'',
    'picture'=>'',
    );
   */
  public function generatePost ();

  /**
   * Avisa que a mensagem foi enviada com sucesso.
   */
  public function sentPost ( $id_facepost );

  /**
   * Recupera os tweets gravados no banco para visualização.
   */
  public function getPosts ();
}
