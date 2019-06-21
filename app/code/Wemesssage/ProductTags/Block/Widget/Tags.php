<?php

namespace Wemessage\ProductTags\Block\Widget;

class Tags extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	protected $_template = "widget/producttags.phtml";
	
	/**
	 * @var \Wemessage\ProductTags\Model\TagsFactory
	 */
	protected $_tagsFactory;
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
     * @param \Wemessage\ProductTags\Model\TagsFactory $tagsFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Wemessage\ProductTags\Model\TagsFactory $tagsFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_tagsFactory = $tagsFactory->create();
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }
    
    /**
     * retrieve tags
     *
     * @param integer $limit
     * @return \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection $collection
     */
	public function getAllTags($limit = 10){
    	$collection = $this->_tagsFactory->getCollection();
    	$collection->getSelect()->columns('COUNT(*) AS amount')->group('tag');
    	$collection->setOrder('amount', 'DESC');
    	$collection->setPageSize($limit);
    	return $collection;
    }
    
    /**
     * @return string
     */
    public function getFrontendUrl(){
        return $this->_storeManager->getStore()->getBaseUrl().$this->_scopeConfig->getValue('producttags/options/tagslug', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
