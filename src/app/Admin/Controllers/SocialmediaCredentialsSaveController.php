<?php

namespace Admin\Controllers;

use Din\Essential\Models\SocialmediaCredentialsModel as model;
use Din\Http\Post;
use Helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class SocialmediaCredentialsSaveController extends BaseControllerAdm
{

    protected $_model;

    public function __construct ()
    {
        parent::__construct();
        $this->_model = new model;
        $this->setEntityData();
        $this->require_permission();

    }

    public function get ()
    {
        $this->_data['table'] = $this->_model->getById('1');
        $this->setSaveTemplate('socialmedia_credentials_save.phtml');

    }

    public function post ()
    {
        try {

            $info = array(
                'link_twitter' => Post::text('link_twitter'),
                'link_facebook' => Post::text('link_facebook'),
                'link_google' => Post::text('link_google'),
                'link_instagram' => Post::text('link_instagram'),
                'link_flickr' => Post::text('link_flickr'),
                'link_youtube' => Post::text('link_youtube'),
                'link_soundcloud' => Post::text('link_soundcloud'),
                'link_issuu' => Post::text('link_issuu'),
                'fb_likebox' => Post::text('fb_likebox'),
                'fb_app_id' => Post::text('fb_app_id'),
                'fb_app_secret' => Post::text('fb_app_secret'),
                'fb_page' => Post::text('fb_page'),
                'fb_access_token' => Post::text('fb_access_token'),
                'tw_user' => Post::text('tw_user'),
                'tw_consumer_key' => Post::text('tw_consumer_key'),
                'tw_consumer_secret' => Post::text('tw_consumer_secret'),
                'tw_access_token' => Post::text('tw_access_token'),
                'tw_access_secret' => Post::text('tw_access_secret'),
                'issuu_key' => Post::text('issuu_key'),
                'issuu_secret' => Post::text('issuu_secret'),
                'sc_client_id' => Post::text('sc_client_id'),
                'sc_client_secret' => Post::text('sc_client_secret'),
                'sc_token' => Post::text('sc_token'),
                'instagram_user' => Post::text('instagram_user'),
                'googleplus_user' => Post::text('googleplus_user'),
                'youtube_user' => Post::text('youtube_user'),
                'youtube_id' => Post::text('youtube_id'),
                'youtube_secret' => Post::text('youtube_secret'),
                'youtube_token' => Post::text('youtube_token'),
                'ga_view' => Post::text('ga_view'),
                'discus_username' => Post::text('discus_username'),
            );

            $this->_model->setId('1');

            $this->saveAndRedirect($info);
        } catch (Exception $e) {
            JsonViewHelper::display_error_message($e);
        }

    }

}
