<?php


namespace Wemessage\ProductTags\Model;

use Magento\Framework\Api\DataObjectHelper;
use Wemessage\ProductTags\Api\Data\TagsInterfaceFactory;
use Wemessage\ProductTags\Api\Data\TagsInterface;

class Tags extends \Magento\Framework\Model\AbstractModel
{

    protected $tagsDataFactory;

    protected $_eventPrefix = 'wemessage_producttags_tags';
    protected $dataObjectHelper;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TagsInterfaceFactory $tagsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Wemessage\ProductTags\Model\ResourceModel\Tags $resource
     * @param \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TagsInterfaceFactory $tagsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Wemessage\ProductTags\Model\ResourceModel\Tags $resource,
        \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection $resourceCollection,
        array $data = []
    ) {
        $this->tagsDataFactory = $tagsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve tags model with tags data
     * @return TagsInterface
     */
    public function getDataModel()
    {
        $tagsData = $this->getData();
        
        $tagsDataObject = $this->tagsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $tagsDataObject,
            $tagsData,
            TagsInterface::class
        );
        
        return $tagsDataObject;
    }
}
