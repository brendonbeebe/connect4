<?php

/**
 * FacebookOAuthService class file.
 *
 * Register application: https://developers.facebook.com/apps/
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
require_once dirname(dirname(__FILE__)) . '/EOAuth2Service.php';

/**
 * Facebook provider class.
 * @package application.extensions.eauth.services
 */
class FacebookExtraOAuthService extends EOAuth2Service {

    protected $name = 'facebook_extra';
    protected $title = 'Facebook';
    protected $type = 'OAuth';
    protected $jsArguments = array('popup' => array('width' => 585, 'height' => 290));
    protected $client_id = '';
    protected $client_secret = '';
    protected $scope = 'email,friends_relationship_details,friends_location,user_location,user_relationships,friends_relationships,user_likes,friends_likes,user_education_history,friends_education_history,friends_religion_politics,user_religion_politics,friends_work_history,user_work_history,user_birthday,friends_birthday';
    protected $providerOptions = array(
        'authorize' => 'https://www.facebook.com/dialog/oauth',
        'access_token' => 'https://graph.facebook.com/oauth/access_token',
    );

    protected function fetchAttributes() {
        $info = (object) $this->makeSignedRequest('https://graph.facebook.com/me');
        $photo = (object) $this->makeSignedRequest('https://graph.facebook.com/me?fields=picture.width(500).height(500)');
        //Uncomment if you would like to see facebook's response

        switch ($info->gender) {
            case "male":
                $info->gender = 'M';
                break;
            case "female":
                $info->gender = 'F';
                break;
            default:
                $info->gender = '';
        }

        $this->attributes['id'] = $info->id;
        $this->attributes['user_name'] = $info->name;
        $this->attributes['url'] = $info->link;
        $this->attributes['email'] = $info->email;
        $this->attributes['gender'] = $info->gender;
        $this->attributes['photo'] = $photo->picture->data->url;
    }

    protected function getCodeUrl($redirect_uri) {
        if (strpos($redirect_uri, '?') !== false) {
            $url = explode('?', $redirect_uri);
            $url[1] = preg_replace('#[/]#', '%2F', $url[1]);
            $redirect_uri = implode('?', $url);
        }

        $this->setState('redirect_uri', $redirect_uri);

        $url = parent::getCodeUrl($redirect_uri);
        if (isset($_GET['js']))
            $url .= '&display=popup';

        return $url;
    }

    protected function getTokenUrl($code) {
        return parent::getTokenUrl($code) . '&redirect_uri=' . urlencode($this->getState('redirect_uri'));
    }

    protected function getAccessToken($code) {
        $response = $this->makeRequest($this->getTokenUrl($code), array(), false);
        parse_str($response, $result);
        return $result;
    }

    /**
     * Saveeecess token to the session.
     * @param array $token access token array.
     */
    protected function saveAccessToken($token) {
        $this->setState('auth_token', $token['access_token']);
        $this->setState('expires', isset($token['expires']) ? time() + (int) $token['expires'] - 60 : 0);
        $this->access_token = $token['access_token'];
    }

    /**
     * Returns the error info from json.
     * @param stdClass $json the json response.
     * @return array the error array with 2 keys: code and message. Should be null if no errors.
     */
    protected function fetchJsonError($json) {
        if (isset($json->error)) {
            return array(
                'code' => $json->error->code,
                'message' => $json->error->message,
            );
        }
        else
            return null;
    }

    public function getName() {
        return $this->getUserName();
    }

    public function getUserName() {
        return $this->attributes['user_name'];
    }

}
