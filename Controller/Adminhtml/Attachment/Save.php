<?php

namespace TUTJunior\CourseType\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use TUTJunior\CourseType\Model\ResourceModel\Attachment;
use TUTJunior\CourseType\Model\AttachmentFactory;
use TUTJunior\CourseType\Model\Attachment as ModelAttachment;

class Save extends Action
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;


    public function __construct
    (
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Psr\Log\LoggerInterface $logger,
        Attachment $attachmentResource,
        AttachmentFactory $attachmentFactory,
        ModelAttachment $customModel,
        Context $context
    )
    {
        $this->assetRepo = $assetRepo;
        $this->logger = $logger;
        $this->customModel = $customModel;
        $this->attachmentResource = $attachmentResource;
        $this->attachmentFactory = $attachmentFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        /**
         * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
         */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id')?:null;
//            $model = $this->attachmentFactory->create();
//            $this->attachmentResource->load($model, $id);
            $customModel = $this->customModel->load($id);
            if($data['attachment_type'] == 'file'){
                $data['icon'] = $this->getViewFileUrl("TUTJunior_CourseType::icons-file.png");
            } if($data['attachment_type'] == 'image') {
                $data['icon'] = $this->getViewFileUrl("TUTJunior_CourseType::icons-image.png");
            }
            $data['customer_group'] = json_encode($data['customer_group']);
            $data['mine_type'] = $data['file_path'][0]['type'];
            $data['file_size'] = $data['file_path'][0]['size'];
            $data['file_path'] = json_encode($data['file_path']);
//            $model->setData($data);
            $customModel->setData($data)->setId($id);
            try {
//                $this->attachmentResource->save($model);
                $customModel->save();
                $this->messageManager->addSuccessMessage(__('You saved the Brand.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $customModel->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Brand.'));
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Retrieve url of a view file
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function getViewFileUrl($fileId, array $params = [])
    {
        try {
            $params = array_merge(['_secure' => $this->getRequest()->isSecure()], $params);
            return $this->assetRepo->getUrlWithParams($fileId, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->logger->critical($e);
            return $this->_backendUrl->getUrl('', ['_direct' => 'core/index/notFound']);
        }
    }
}
