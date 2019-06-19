<?php


namespace Wemessage\ProductTags\Model\Data;

use Wemessage\ProductTags\Api\Data\TagsInterface;

class Tags extends \Magento\Framework\Api\AbstractExtensibleObject implements TagsInterface
{

    /**
     * Get tags_id
     * @return string|null
     */
    public function getTagsId()
    {
        return $this->_get(self::TAGS_ID);
    }

    /**
     * Set tags_id
     * @param string $tagsId
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setTagsId($tagsId)
    {
        return $this->setData(self::TAGS_ID, $tagsId);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Wemessage\ProductTags\Api\Data\TagsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Wemessage\ProductTags\Api\Data\TagsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Wemessage\ProductTags\Api\Data\TagsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get product_name
     * @return string|null
     */
    public function getProductName()
    {
        return $this->_get(self::PRODUCT_NAME);
    }

    /**
     * Set product_name
     * @param string $productName
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setProductName($productName)
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }

    /**
     * Get tag
     * @return string|null
     */
    public function getTag()
    {
        return $this->_get(self::TAG);
    }

    /**
     * Set tag
     * @param string $tag
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setTag($tag)
    {
        return $this->setData(self::TAG, $tag);
    }
}
