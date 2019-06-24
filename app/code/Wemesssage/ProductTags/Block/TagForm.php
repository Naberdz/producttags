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
	 * @var \Magento\Customer\Model\Session
	 */
    protected $_session;
    /**
	 * @var \Magento\Customer\Model\Url
	 */
    protected $_customerUrl;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Magento\Framework\Registry $registry
     * @param \Wemessage\ProductTags\Model\TagsFactory $tagsFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $session
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Wemessage\ProductTags\Model\TagsFactory $tagsFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $session,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_tagsFactory = $tagsFactory->create();
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_session = $session;
        $this->_customerUrl = $customerUrl;
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
	/**
	 * @return boolean
	 */
	public function getOnlyForCustomers(){
		return $this->_scopeConfig->getValue('producttags/options/onlyforcustomers', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	/**
	 * @return boolean
	 */
	public function getIsLoggedIn(){
		return $this->_session->isLoggedIn();
	}
	
	/**
     * @return string
     */
    public function getRegisterUrl()
    {
        return $this->_customerUrl->getRegisterUrl();
    }
    /**
     * @return string
     */
    public function getLoginLink()
    {
        return $this->_customerUrl->getLoginUrl();
    }
}
