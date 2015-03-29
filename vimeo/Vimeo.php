<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o. 
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\wrappers\vimeo;

use dlds\wrappers\vimeo\components\ApiWrapper;

class Vimeo extends \yii\base\Component {

    public $client_id;
    public $client_secret;
    public $access_token;

    /**
     * @var Vimeo current instance
     */
    private $_wrapper;

    /**
     * Retrieves api wrapper
     * @return VimeoWrapper api wrapper
     */
    public function api()
    {
        if (!$this->_wrapper)
        {
            $this->_wrapper = new ApiWrapper($this->client_id, $this->client_secret, $this->access_token);
        }

        return $this->_wrapper;
    }

    /**
     * Retrieves media list based on given params
     * @param array $params given params
     */
    public function getMediaList($params)
    {
        $response = $this->api()->request('/me/videos', $params);

        return $this->parseMediaListResponse($response);
    }

    /**
     * Retrieves media details based on given ID
     * @param int $id given ID
     */
    public function getMedia($id)
    {
        $response = $this->api()->request(sprintf('/videos/%s', $id));

        return $this->parseMediaResponse($response);
    }

    /**
     * Parses media response
     * @param array $response given response
     * @return array parsed response
     */
    private function parseMediaResponse($response)
    {
        $data = array();

        if (isset($response['body']) && isset($response['body']['name']))
        {
            $data['name'] = $response['body']['name'];
        }

        return $data;
    }

    /**
     * Parses medial list respose
     * @param array $response given respose
     * @return array parsed response
     */
    private function parseMediaListResponse($response)
    {
        $data = array();

        if (isset($response['body']) && isset($response['body']['data']))
        {
            foreach ($response['body']['data'] as $item)
            {
                $id = (int) preg_replace("/[^0-9]/", "", $item['uri']);

                if ($id)
                {
                    $data[] = array(
                        'id' => $id,
                        'name' => $item['name'],
                    );
                }
            }
        }

        return $data;
    }

}
