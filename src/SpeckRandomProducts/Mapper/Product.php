<?php

namespace SpeckRandomProducts\Mapper;

use SpeckCatalog\Mapper\Product as SpeckProductMapper;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate;

class Product extends SpeckProductMapper
{
    protected $siteId = 1; //todo: get from multisite

    /**
     *
     * @param int $limit
     * @param int $categoryId
     * @param bool $
     *
     * return array of \SpeckCatalog\Model\Product
     *
     *
     */
    public function getFeaturedProducts($limit=6, $categoryId=null, $traverseCategories=false)
    {
        if ($traverseCategories && !$categoryId){
            throw new \Exception('cannot traverse categories without a category id');
        }

        $siteId = $this->getSiteId();
        $table  = $this->getTableName();
        $select = $this->getSelect($table);
        $where  = new Where();
        $select->where($where);
        $select->limit($limit);

        $where->equalto('featured_product.website_id', $siteId);

        $select->join('featured_product', "{$table}.product_id = featured_product.product_id");

        if ($traverseCategories) {
            $categoryIds = $this->getChildCategoryIds($categoryId, array($categoryId));
        } else {
            $categoryIds = array($categoryId);
        }
        if ($categoryId) {
            $select->join('catalog_category_product', "{$table}.product_id = catalog_category_product.product_id");
            $nest = $where->AND->NEST;
            foreach ($categoryIds as $categoryId) {
                $nest->equalTo('category_id', $categoryId)->OR;
            }
        }

        return $this->selectManyModels($select);
    }

    public function extractSingleKeyFromRows($key, array $rows)
    {
        $return = array();

        foreach ($rows as $row) {
            $return[] = $row[$key];
        }

        return $return;
    }

    public function getChildCategoryIds($categoryId, $categoryIds = array())
    {
        $select = $this->getSelect('catalog_category_website');
        $select->where(array('parent_category_id' => $categoryId));
        $rows = $this->select($select)->toArray();
        $ids = $this->extractSingleKeyFromRows('category_id', $rows);

        foreach($ids as $id) {
            if (!in_array($id, $categoryIds)) {
                $categoryIds[] = $id;
                $categoryIds = $this->getChildCategoryIds($id, $categoryIds);
            }
        }

        return $categoryIds;
    }

    /**
     * @return siteId
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param $siteId
     * @return self
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }
}
