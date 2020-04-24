<?php
/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$customerTaxClass = $objectManager->create(\Magento\Tax\Model\ClassModel::class)->load('Retail Customer', 'class_name');
$productTaxClass1 = $objectManager->create(\Magento\Tax\Model\ClassModel::class)->load('Taxable Goods', 'class_name');

$taxRate = [
    'tax_country_id' => 'US',
    'tax_region_id' => '0',
    'tax_postcode' => '*',
    'code' => '*',
    'rate' => '10',
];
$rate = $objectManager->create(\Magento\Tax\Model\Calculation\Rate::class)->setData($taxRate)->save();

/** @var Magento\Framework\Registry $registry */
$registry = $objectManager->get(\Magento\Framework\Registry::class);
$registry->unregister('_fixture/Magento_Tax_Model_Calculation_Rate');
$registry->register('_fixture/Magento_Tax_Model_Calculation_Rate', $rate);

$ruleData = [
    'code' => 'Test Rule',
    'priority' => '0',
    'position' => '0',
    'customer_tax_class_ids' => [$customerTaxClass->getId()],
    'product_tax_class_ids' => [$productTaxClass1->getId()],
    'tax_rate_ids' => [$rate->getId()],
];

$taxRule = $objectManager->create(\Magento\Tax\Model\Calculation\Rule::class)->setData($ruleData)->save();

$registry->unregister('_fixture/Magento_Tax_Model_Calculation_Rule');
$registry->register('_fixture/Magento_Tax_Model_Calculation_Rule', $taxRule);

$product = $objectManager->create('Magento\Catalog\Model\Product');
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(884)
    ->setAttributeSetId(4)
    ->setName('Product with daily deal')
    ->setSku('product_with_daily_deal')
    ->setUrlKey('product_with_daily_deal')
    ->setPrice(100)
    ->setTaxClassId($productTaxClass1->getId())
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setCanSaveCustomOptions(true)
    ->setSpecialPrice(50)
    ->save();