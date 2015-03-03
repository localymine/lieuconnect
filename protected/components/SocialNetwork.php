<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SocialNetwork
 *
 * @author khangld
 */
class SocialNetwork extends CWidget {

    public $type = 'follow';
    
    public $acc_twitter = '';   // email
    public $acc_facebook = '';  // email
    public $data_href = '';
    public $fb_margin_top = -9;
    
    public $title = '';
    public $description = '';
    public $image_url = '';

    public function init() {
        
    }

    public function run() {
        
        switch ($this->type){
            case 'twitter-follow':
                $this->render('social-twitter-follow', array(
                    'acc_twitter' => $this->acc_twitter,
                ));
                break;
            case 'twitter-share':
                $this->render('social-twitter-share', array(
                    'data_href' => $this->data_href,
                ));
                break;
            case 'facebook-like':
                $this->render('social-facebook-like', array(
                    'data_href' => $this->data_href,
                ));
                break;
            case 'facebook-share':
                $this->render('social-facebook-share', array(
                    'data_href' => $this->data_href,
                ));
                break;
            case 'fixed-share-left':
                $this->render('social-fixed-share-left', array(
                    'share_url' => $this->data_href,
                    'title' => $this->title,
                    'description' => $this->description,
                    'image_url' => $this->image_url,
                ));
                break;
            case 'fixed-share-bottom':
                $this->render('social-fixed-share-bottom', array(
                    'share_url' => $this->data_href,
                    'title' => $this->title,
                    'description' => $this->description,
                    'image_url' => $this->image_url,
                ));
                break;
            default:
                $this->render('social-network');
                break;
        }
        
    }

}
