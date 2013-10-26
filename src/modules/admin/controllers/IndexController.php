<?php

/**
 * Brief description of the controller
 */
class Admin_IndexController extends My_Controller
{
    /**
     * Your main action
     * Put a comment before each action and briefly describe what it does !
     */
    public function indexAction()
    {
        $this->getServiceManager()->getLog()->log('This is a test !', Zend_Log::INFO);

        $article = $this->getServiceManager()->getModel()->getFrontPageArticle();
        $article = \Michelf\Markdown::defaultTransform($article);

        $this->view->article = $article;
        $this->view->facebook = $this->getServiceManager()->getFacebook();
    }

    public function facebookAction()
    {
        $this->view->facebook = $this->getServiceManager()->getFacebook();
    }
}