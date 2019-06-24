<?php

namespace Wemessage\ProductTags\Block;

class AlsoTagged extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Wemessage\ProductTags\Model\TagsFactory
     */
    protected $_tagsFactory;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $_product;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * Constructor
     *
     * @param \Magento\Catalog\Block\Product\Context  $context
     * @param \Magento\Framework\Registry $registry
     * @param \Wemessage\ProductTags\Model\TagsFactory $tagsFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Registry $registry,
        \Wemessage\ProductTags\Model\TagsFactory $tagsFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_tagsFactory = $tagsFactory->create();
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_collectionFactory = $collectionFactory;
    }
    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getCurrentProduct()
    {
        if (is_null($this->_product)) {
            $this->_product = $this->_registry->registry('product');
            if (!$this->_product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }
        return $this->_product;
    }
    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    public function getTaggedProducts($pageSize){
        $tagCollection = $this->getTags();
        $productIds = [];
        foreach($tagCollection as $tag){
            $productsTagged = $this->getTagCollection($tag->getTag());
            foreach($productsTagged as $prod){
                if($this->_product->getId() != $prod->getProductId()){
                    $productIds[] = $prod->getProductId();
                }
            }
        }
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('entity_id', array('in'=>$productIds));
        $collection->setPageSize($pageSize);
        $collection->addAttributeToSelect('*');
        return $collection;
    }
    /**
     * @return \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection $collection
     */
    public function getTags(){
        if(is_null($this->_product)){
            $this->getCurrentProduct();
        }
        return $this->_tagsFactory->getCollection()->addFieldToFilter('product_id', array('eq'=>$this->_product->getId()));
    }
    /**
     * @return \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection $collection
     */
    public function getTagCollection($tag){
        return $this->_tagsFactory->getCollection()->addFieldToFilter('tag', array('eq'=>$tag));
    }
    /**
     * @return integer
     */
    public function getProductLimit(){
        return $this->_scopeConfig->getValue('producttags/options/taggedproductslimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}