<?php


namespace Wemessage\ProductTags\Model;

use Magento\Framework\Reflection\DataObjectProcessor;
use Wemessage\ProductTags\Api\TagsRepositoryInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\DataObjectHelper;
use Wemessage\ProductTags\Api\Data\TagsSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use Wemessage\ProductTags\Model\ResourceModel\Tags as ResourceTags;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Wemessage\ProductTags\Model\ResourceModel\Tags\CollectionFactory as TagsCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Wemessage\ProductTags\Api\Data\TagsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;

class TagsRepository implements TagsRepositoryInterface
{

    protected $tagsCollectionFactory;

    protected $tagsFactory;

    protected $resource;

    protected $dataTagsFactory;

    protected $extensionAttributesJoinProcessor;

    protected $extensibleDataObjectConverter;
    protected $dataObjectProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $dataObjectHelper;

    protected $searchResultsFactory;


    /**
     * @param ResourceTags $resource
     * @param TagsFactory $tagsFactory
     * @param TagsInterfaceFactory $dataTagsFactory
     * @param TagsCollectionFactory $tagsCollectionFactory
     * @param TagsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceTags $resource,
        TagsFactory $tagsFactory,
        TagsInterfaceFactory $dataTagsFactory,
        TagsCollectionFactory $tagsCollectionFactory,
        TagsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->tagsFactory = $tagsFactory;
        $this->tagsCollectionFactory = $tagsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTagsFactory = $dataTagsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Wemessage\ProductTags\Api\Data\TagsInterface $tags
    ) {
        /* if (empty($tags->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $tags->setStoreId($storeId);
        } */
        
        $tagsData = $this->extensibleDataObjectConverter->toNestedArray(
            $tags,
            [],
            \Wemessage\ProductTags\Api\Data\TagsInterface::class
        );
        
        $tagsModel = $this->tagsFactory->create()->setData($tagsData);
        
        try {
            $this->resource->save($tagsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the tags: %1',
                $exception->getMessage()
            ));
        }
        return $tagsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($tagsId)
    {
        $tags = $this->tagsFactory->create();
        $this->resource->load($tags, $tagsId);
        if (!$tags->getId()) {
            throw new NoSuchEntityException(__('Tags with id "%1" does not exist.', $tagsId));
        }
        return $tags->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->tagsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Wemessage\ProductTags\Api\Data\TagsInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Wemessage\ProductTags\Api\Data\TagsInterface $tags
    ) {
        try {
            $tagsModel = $this->tagsFactory->create();
            $this->resource->load($tagsModel, $tags->getTagsId());
            $this->resource->delete($tagsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Tags: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($tagsId)
    {
        return $this->delete($this->getById($tagsId));
    }
}
