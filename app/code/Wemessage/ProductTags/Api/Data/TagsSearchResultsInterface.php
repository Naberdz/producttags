<?php


namespace Wemessage\ProductTags\Api\Data;

interface TagsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Tags list.
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Wemessage\ProductTags\Api\Data\TagsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
