<?php

namespace SpeckRandomProducts;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'speckFeaturedProducts' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $helper = new View\Helper\Product;
                    $helper->setTemplate('product');
                    $helper->setCallable(array($sm->get('SpeckRandomProductMapper'), 'getFeaturedProducts'));
                    $helper->setProductService($sm->get('speckcatalog_product_service'));
                    return $helper;
                },
            ),
        );
    }

    public function getConfig()
    {
        return array(
            'service_manager' => array(
                'invokables' => array(
                    'SpeckRandomProductMapper'=> 'SpeckRandomProducts\Mapper\Product',
                ),
            ),
            'view_manager' => array(
                'template_path_stack' => array(
                    __DIR__ . '/view'
                ),
            ),
        );
    }
}
