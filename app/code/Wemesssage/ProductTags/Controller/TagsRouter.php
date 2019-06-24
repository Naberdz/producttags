<?php


namespace Wemessage\ProductTags\Controller;

class TagsRouter implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $_actionFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_response;
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
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_actionFactory = $actionFactory;
        $this->_response = $response;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }
    /*
     * @param \Magento\Framework\App\RequestInterface $request
     * @retrun \Magento\Framework\App\ActionFactory
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $path = trim($request->getPathInfo(), '/');
        $array = explode('/', $path);
        if(strpos($path, $this->getFrontendSlug()) !== false) {
            $request->setModuleName('producttag')
                ->setControllerName('index')
                ->setActionName('index');
            if(isset($array[1])){
                $request->setParam('tag', end($array));
            }
       } else {
           return false;
       }
       return $this->_actionFactory->create(
           'Magento\Framework\App\Action\Forward',
           ['request' => $request]
       );
    }
   
    /**
     * @return string
     */
    public function getFrontendSlug(){
        return $this->_scopeConfig->getValue('producttags/options/tagslug', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}