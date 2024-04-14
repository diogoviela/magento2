<?php
namespace ModuleDSV\LastUnits\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class CheckStock implements ObserverInterface
{
    protected $layout;

    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product->getExtensionAttributes() && $product->getExtensionAttributes()->getStockItem()) {
            $stockItem = $product->getExtensionAttributes()->getStockItem();
            $stockQty = $stockItem->getQty();
            if ($stockQty < 50) {
                $product->setData('show_last_units_tag', true);
                $this->addLastUnitsBlock($product);
            }
        }
    }

    protected function addLastUnitsBlock($product)
    {
        $block = $this->layout->createBlock(\Magento\Framework\View\Element\Template::class)
                              ->setTemplate('ModuleDSV_LastUnits::last_units.phtml')
                              ->setData('product', $product);
        $this->layout->getBlock('content')->append($block);
    }
}
