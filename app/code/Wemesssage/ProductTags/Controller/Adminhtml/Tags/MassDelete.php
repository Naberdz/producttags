<?php


namespace Wemessage\ProductTags\Controller\Adminhtml\Tags;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $_filter;

    protected $_tagsFactory;

    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Wemessage\ProductTags\Model\TagsFactory $tagsFactory,
        \Magento\Backend\App\Action\Context $context
        ) {
        $this->_filter = $filter;
        $this->_tagsFactory = $tagsFactory->create();
        parent::__construct($context);
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $recordDeleted = 0;
        $data = $this->getRequest()->getPost();

        $collection = $this->_tagsFactory->getCollection()->addFieldToFilter('tags_id', array('in'=>$data['selected']));
        
        foreach ($collection as $record) {
            $model = $this->_objectManager->create(\Wemessage\ProductTags\Model\Tags::class);
            $model->load($record->getId());
            try {                
                $model->delete();
                $recordDeleted++;
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to grid
                return $resultRedirect->setPath('*/*/');
            }
        }
        if(!$recordDeleted){
            // display error message
            $this->messageManager->addErrorMessage(__('We can\'t find a Tags to delete.'));
        } else {
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $recordDeleted));
        }
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
