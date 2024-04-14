<?php
namespace LastUnits\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class ProductLoadAfter implements ObserverInterface
{
    protected $stockRegistry;

    public function __construct(StockRegistryInterface $stockRegistry)
    {
        $this->stockRegistry = $stockRegistry;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $stockItem = $this->stockRegistry->getStockItem($product->getId());
        if ($stockItem->getQty() < 50) {
            $product->setData('show_last_units_tag', true);
        }
    }
}
