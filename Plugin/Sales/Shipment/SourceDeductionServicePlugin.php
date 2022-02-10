<?php

declare(strict_types=1);

namespace SkillUp\InventorySourceDeductionApi\Plugin\Sales\Shipment;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventorySourceDeductionApi\Model\SourceDeductionService;
use Magento\InventorySourceDeductionApi\Model\SourceDeductionRequestInterface;
use SkillUp\InventorySourceDeductionApi\Model\DataMapping\Config\ConfigProvider;

/**
 *  Check admin param and disable save cheng in stock for store
 */
class SourceDeductionServicePlugin
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * @param SourceDeductionService $subject
     * @param callable $proceed
     * @param SourceDeductionRequestInterface $sourceDeductionRequest
     * @return void
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function aroundExecute(
        SourceDeductionService $subject,
        callable $proceed,
        SourceDeductionRequestInterface $sourceDeductionRequest
    ) {
        $orderId = $sourceDeductionRequest->getSalesEvent()->getObjectId();
        if (!$this->configProvider->getIsEnable($orderId)) {
            return $proceed($sourceDeductionRequest);
        }
    }
}
