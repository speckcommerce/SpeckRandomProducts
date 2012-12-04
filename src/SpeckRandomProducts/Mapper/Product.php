<?php

namespace SpeckRandomProducts\Mapper;

use SpeckCatalog\Mapper\Product as SpeckProductMapper;

class Product extends SpeckProductMapper
{
    public function getFeaturedProducts($limit=null)
    {
        $siteId = 1;
        $limit = ($limit ?: 6);
        $table = $this->getTableName();
        $linker = 'featured_product';
        $select = $this->getSelect($table)
            ->join($linker, $table . '.product_id = ' . $linker . '.product_id')
            ->where(array('website_id' => $siteId))
            ->limit($limit);
        return $this->selectMany($select);
    }
}
