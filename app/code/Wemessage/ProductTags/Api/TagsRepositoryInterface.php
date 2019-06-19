<?php


namespace Wemessage\ProductTags\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TagsRepositoryInterface
{

    /**
     * Save Tags
     * @param \Wemessage\ProductTags\Api\Data\TagsInterface $tags
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Wemessage\ProductTags\Api\Data\TagsInterface $tags
    );

    /**
     * Retrieve Tags
     * @param string $tagsId
     * @return \Wemessage\ProductTags\Api\Data\TagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($tagsId);

    /**
     * Retrieve Tags matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Wemessage\ProductTags\Api\Data\TagsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Tags
     * @param \Wemessage\ProductTags\Api\Data\TagsInterface $tags
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Wemessage\ProductTags\Api\Data\TagsInterface $tags
    );

    /**
     * Delete Tags by ID
     * @param string $tagsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($tagsId);
}
