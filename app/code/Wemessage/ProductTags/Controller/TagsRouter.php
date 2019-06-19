<?php


namespace Wemessage\ProductTags\Controller;

class TagsRouter implements \Magento\Framework\App\RouterInterface
{

    protected $actionFactory;
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
     */
   	public function __construct(
       	\Magento\Framework\App\ActionFactory $actionFactory,
       	\Magento\Framework\App\ResponseInterface $response,
       	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
   	) {
       	$this->actionFactory = $actionFactory;
       	$this->_response = $response;
       	$this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
   	}
   	
   	public function match(\Magento\Framework\App\RequestInterface $request)
   	{
   		$path = trim($request->getPathInfo(), '/');
   		$array = explode('/', $path);
       	if(strpos($path, $this->getFrontendUrl()) !== false) {
       		$request->setModuleName('producttag')
				->setControllerName('index')
				->setActionName('index');
			if(isset($array[1])){
				$request->setParam('tag', $array[1]);
			}
       } else {
           return false;
       }
       return $this->actionFactory->create(
           'Magento\Framework\App\Action\Forward',
           ['request' => $request]
       );
	}
   
   	/**
     * @return string
     */
    public function getFrontendUrl(){
        return $this->_scopeConfig->getValue('producttags/options/tagslug', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}