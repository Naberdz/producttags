<?php


namespace Wemessage\ProductTags\Controller\Adminhtml\Tags;

class Delete extends \Wemessage\ProductTags\Controller\Adminhtml\Tags
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('tags_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Wemessage\ProductTags\Model\Tags::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Tags.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['tags_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Tags to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
