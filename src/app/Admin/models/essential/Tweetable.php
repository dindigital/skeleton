<?php

namespace Admin\Models\Essential;

interface Tweetable
{

  /**
   * Seta o id da seção que eu estiver.
   */
  public function setId ( $id );

  /**
   * Retorna a mensagem formatada.
   */
  public function generateTweet ();

  /**
   * Avisa que a mensagem foi enviada com sucesso.
   */
  public function sentTweet ( $id_tweet );

  /**
   * Recupera os tweets gravados no banco para visualização.
   */
  public function getTweets ();
}
