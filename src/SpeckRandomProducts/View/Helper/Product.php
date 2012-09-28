<?php

namespace SpeckRandomProducts\View\Helper;

use Zend\View\Helper\HelperInterface;
use Zend\View\Helper\AbstractHelper;

class Product extends AbstractHelper
{
    protected $products;
    protected $template;
    protected $callable;
    protected $params=array();
    protected $productService;

    public function __invoke($params=null)
    {
        $params = ($params ?: $this->params);
        $products = call_user_func_array($this->callable, $this->params);

        $html = '';
        foreach ($products as $product) {
            $product = $this->getProductService()->getFullProduct($product->getProductId());
            $html .= $this->getView()->render($this->template, array('product' => $product));
        }
        return $html;
    }

    /**
     * @return products
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param $products
     * @return self
     */
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param $callable
     * @return self
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
        return $this;
    }

    /**
     * @return productService
     */
    public function getProductService()
    {
        return $this->productService;
    }

    /**
     * @param $productService
     * @return self
     */
    public function setProductService($productService)
    {
        $this->productService = $productService;
        return $this;
    }
}
