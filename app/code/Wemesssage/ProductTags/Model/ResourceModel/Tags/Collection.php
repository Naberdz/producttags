<?php


namespace Wemessage\ProductTags\Model\ResourceModel\Tags;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Wemessage\ProductTags\Model\Tags::class,
            \Wemessage\ProductTags\Model\ResourceModel\Tags::class
        );
    }
}
