<?php


namespace Wemessage\ProductTags\Block\Index;

class Index extends \Magento\Framework\View\Element\Template
{
	protected $_request;
	protected $_tagsFactory;
	protected $_priceHelper;
	protected $_collectionFactory;
	protected $_imageBuilder;
	
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Wemessage\ProductTags\Model\TagsFactory $tagsFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_request = $request;
        $this->_tagsFactory = $tagsFactory->create();
        $this->_collectionFactory = $collectionFactory;
        $this->_imageBuilder = $imageBuilder;
    }
    
    protected function _prepareLayout()
    {
        //parent::_prepareLayout();
        $title = '';
		if($this->_request->getParam('tag')){
			$title = __('Tag %1', $this->_request->getParam('tag'));
        	$this->_addBreadcrumbs($this->_request->getParam('tag'), 'tags');
        } else {
        	$title = __('Tags');
        	$this->_addBreadcrumbs();
        }
        if ($this->getTaggedCollection()) {
            $pager = $this->getLayout()
            	->createBlock(
                	'Magento\Theme\Block\Html\Pager',
                	'wemessage.tags.pager'
            	)->setAvailableLimit(array(2=>2))
                ->setShowPerPage(true)
                ->setCollection(
                    $this->getTaggedCollection()
                );
            $this->setChild('pager', $pager);
            $this->getTaggedCollection()->load();
        }
		$this->pageConfig->getTitle()->set($title);
		$pageMainTitle = $this->getLayout()->getBlock('page.main.title');
		if ($pageMainTitle) {
			$pageMainTitle->setPageTitle(
				$this->escapeHtml($title)
			);
		}
        return parent::_prepareLayout();
    }
    public function getTaggedCollection(){
    	$page = ($this->_request->getParam('p')) ? $this->_request->getParam('p') : 1;
    	$pageSize=($this->_request->getParam('limit'))? $this->_request->getParam('limit') : 2;
    	if($this->_request->getParam('tag')){
    		$tagCollection = $this->_tagsFactory->getCollection()->addFieldToFilter('tag', array('eq'=>$this->_request->getParam('tag')));
    	} else {
    		$tagCollection = $this->_tagsFactory->getCollection();
    	}
    	$productIds = [];
    	foreach($tagCollection as $tag){
    		$productIds[] = $tag->getProductId();
    	}
    	//var_dump($productIds);
    	$collection = $this->_collectionFactory->create();
    	$collection->addFieldToFilter('entity_id', array('in'=>$productIds));
    	$collection->setPageSize($pageSize);
    	$collection->setCurPage($page);
    	$collection->addAttributeToSelect('*');
    	return $collection;
    }
    
    public function getAllTags(){
    	$collection = $this->_tagsFactory->getCollection();
    	$collection->getSelect()->columns('COUNT(*) AS amount')->group('tag');
    	return $collection;
    }
    
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    public function getImage($product, $imageId){
    	return $this->_imageBuilder->setProduct($product)
        	->setImageId($imageId)
        	->create();
	}
	public function getMode(){
		return 'grid';
	}
	/**
     * @return string
     */
    public function getFrontendUrl(){
        return $this->_storeManager->getStore()->getBaseUrl().$this->_scopeConfig->getValue('producttags/options/tagslug', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
	/**
     * Prepare breadcrumbs
     *
     * @param  string $title
     * @param  string $key
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs($title = null, $key = null)
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))
        ) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            if($title){
				$breadcrumbsBlock->addCrumb(
					'tag',
					[
						'label' => __('Tags'),
						'title' => __('Tags'),
						'link' => $this->getFrontendUrl()
					]
				);

            
			
				$breadcrumbsBlock->addCrumb($key, [
					'label' => $title,
					'title' => $title
				]);
			} else {
				$breadcrumbsBlock->addCrumb(
					'tag',
					[
						'label' => __('Tags'),
						'title' => __('Tags'),
					]
				);
			}
        }
    }
}
