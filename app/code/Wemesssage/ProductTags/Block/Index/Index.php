<?php


namespace Wemessage\ProductTags\Block\Index;

class Index extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;
    /**
     * @var \Wemessage\ProductTags\Model\TagsFactory
     */
    protected $_tagsFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $_imageBuilder;
    /**
     * Constructor
     *
     * @param \Magento\Catalog\Block\Product\Context  $context
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Wemessage\ProductTags\Model\TagsFactory $tagsFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
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
    /**
     * Redefining layout
     *
     * @return parent::_prepareLayout()
     */
    protected function _prepareLayout()
    {
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
                    'Wemessage\ProductTags\Block\CustomPager',
                    'wemessage.tags.pager'
                )->setAvailableLimit(array($this->getNumberOfProducts()=>$this->getNumberOfProducts()))
                ->setShowPerPage(true)
                ->setCollection(
                    $this->getTaggedCollection()
                );
            $this->setChild('pager', $pager);
            $this->getTaggedCollection()->load();
        }
        $this->pageConfig->setRobots('NOINDEX,NOFOLLOW');
        $this->pageConfig->getTitle()->set($title);
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle(
                $this->escapeHtml($title)
            );
        }
        return parent::_prepareLayout();
    }
    /**
     * @return \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection
     */
    public function getTaggedCollection(){
        $page = ($this->_request->getParam('p')) ? $this->_request->getParam('p') : 1;
        $pageSize=($this->_request->getParam('limit'))? $this->_request->getParam('limit') : $this->getNumberOfProducts();
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
    /**
     * @return \Wemessage\ProductTags\Model\ResourceModel\Tags\Collection
     */
    public function getAllTags(){
        $collection = $this->_tagsFactory->getCollection();
        $collection->getSelect()->columns('COUNT(*) AS amount')->group('tag');
        return $collection;
    }
    /**
     * @return pagerhtml
     */
    public function getCustomPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return string
     */
    public function getDisplayMode(){
        return 'grid';
    }
    /**
     * @return string
     */
    public function getTagsFrontendUrl(){
        return $this->_storeManager->getStore()->getBaseUrl().$this->_scopeConfig->getValue('producttags/options/tagslug', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    /**
     * @return integer
     */
    public function getNumberOfProducts(){
        return $this->_scopeConfig->getValue('producttags/options/numberofproducts', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
                        'link' => $this->getTagsFrontendUrl()
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
