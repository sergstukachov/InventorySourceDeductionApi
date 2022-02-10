<?php

declare(strict_types=1);

namespace SkillUp\InventorySourceDeductionApi\Model\DataMapping\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\OrderRepository;

/**
 * Provide system configuration value
 */
class ConfigProvider
{
    /**
     * Is enable system configuration value XPATH
     */
    public const XML_PATH_SHIPPING_API_QTY = 'shipping/shipping_api_qty/disable_shipping_qty';

    /**
     * Scope type
     */
    public const SCOPE_TYPE_STORE = 'store';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        OrderRepository $orderRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->orderRepository = $orderRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $orderId
     * @return false|mixed
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getIsEnable($orderId) {
        $scopeCode = $this->getStoreByOrder($orderId);
        return $this->scopeConfig->getValue(self::XML_PATH_SHIPPING_API_QTY, self::SCOPE_TYPE_STORE, $scopeCode) ?? false;
    }

    /**
     * @param $orderId
     * @return int|null
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function getStoreByOrder($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        return $order->getStoreId();
    }
}
