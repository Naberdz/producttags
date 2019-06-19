<?php


namespace Wemessage\ProductTags\Api\Data;

interface TagsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRODUCT_ID = 'product_id';
    const TAGS_ID = 'tags_id';
    const PRODUCT_NAME = 'product_name';
    const TAG = 'tag';

    /**
     * Get tags_id
     * @return string|null
     */
    public function getTagsId();

    /**
     * Set tags_id
     * @param string $tagsId
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setTagsId($tagsId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Wemessage\ProductTags\Api\Data\TagsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Wemessage\ProductTags\Api\Data\TagsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Wemessage\ProductTags\Api\Data\TagsExtensionInterface $extensionAttributes
    );

    /**
     * Get product_name
     * @return string|null
     */
    public function getProductName();

    /**
     * Set product_name
     * @param string $productName
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setProductName($productName);

    /**
     * Get tag
     * @return string|null
     */
    public function getTag();

    /**
     * Set tag
     * @param string $tag
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     */
    public function setTag($tag);
}
