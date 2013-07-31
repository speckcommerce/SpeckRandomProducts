<?php

namespace SpeckRandomProducts\View\Helper;

use Zend\View\Helper\HelperInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Product extends AbstractHelper
{
    protected $products;
    protected $template;
    protected $callable;
    protected $params = array();
    protected $productService;
    protected $clips = array();
    protected $views = array();

    public function __invoke($params=null)
    {
        $params = ($params ?: $this->params);
        $products = call_user_func_array($this->callable, $params);

        $html = '';
        foreach ($products as $product) {
            $product = $this->getProductService()->getFullProduct($product->getProductId());
            $view = new ViewModel(array('product' => $product));
            $this->views[] = $view->setTemplate($this->getTemplate());
        }
        return $this;
    }

    public function renderViewsToClips($returnClips = false)
    {
        foreach ($this->getViews() as $view) {
            $this->clips[] = $this->getView()->render($view);
        }
        if ($returnClips) {
            return $this->getClips();
        }
        return $this;
    }

    public function __toString()
    {
        $html = '';
        $clips = $this->renderViewsToClips(true);
        foreach ($clips as $clip) {
            $html .= $this->getView()->render($view);
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

    /**
     * @return clips
     */
    public function getClips()
    {
        return $this->clips;
    }

    /**
     * @param $clips
     * @return self
     */
    public function setClips($clips)
    {
        $this->clips = $clips;
        return $this;
    }

    /**
     * @return views
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param $views
     * @return self
     */
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }
}
