<?php

namespace Wemessage\ProductTags\Block;

class TagForm extends \Magento\Framework\View\Element\Template
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
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Magento\Framework\Registry $registry
     * @param \Wemessage\ProductTags\Model\TagsFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Wemessage\ProductTags\Model\TagsFactory $tagsFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_tagsFactory = $tagsFactory->create();
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
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
     * @return \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection $collection
     */
    public function getTags(){
    	return $this->_tagsFactory->getCollection()->addFieldToFilter('product_id', array('eq'=>$this->_product->getId()));
    }
    
    /**
     * @return string
     */
    public function getFrontendUrl(){
        return $this->_storeManager->getStore()->getBaseUrl().$this->_scopeConfig->getValue('producttags/options/tagslug', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

}
