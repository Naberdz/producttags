<?php
namespace Wemessage\ProductTags\Controller\Add;

class Index extends \Magento\Framework\App\Action\Action
{
	/**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
    /**
     * @var \Wemessage\ProductTags\Model\Tags
     */
    protected $_tags;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @apram \Wemessage\ProductTags\Model\Tags $tags
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Wemessage\ProductTags\Model\Tags $tags
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_tags = $tags;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
    	$response = [];
    	$data = $this->getRequest()->getPostValue();

    	if(!$data['tag']){
    		$response = array('error'=>true, 'msg'=>__('Please fill in tags'));
    	} else {
    		$allTags = explode(',', $data['tag']);
    		foreach($allTags as $tagName){
    			$tag = $this->_tags;
    			$tag->setProductId($data['product_id']);
    			$tag->setProductName($data['product_name']);
    			$tag->setTag($tagName);
    			$tag->save();
    		}
    		$response = array('success'=>true, 'msg'=>__('Thank you for your input'));
    	}
    	
        try {
            return $this->jsonResponse($response);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
