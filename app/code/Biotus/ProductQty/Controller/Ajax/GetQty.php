<?php

namespace Biotus\ProductQty\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Controller\ResultInterface;

class GetQty extends Action
{
    protected $resultJsonFactory;
    protected $productRepository;

    public function __construct(
        Context                    $context,
        JsonFactory                $resultJsonFactory,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            $productId = (int)$this->getRequest()->getParam('product_id');
            if (!$productId) {
                throw new InputException(__('Invalid product ID.'));
            }

            $product = $this->productRepository->getById($productId);
            $qty = $product->getExtensionAttributes()->getStockItem()->getQty();

            return $resultJson->setData(['qty' => $qty]);

        } catch (NoSuchEntityException $e) {
            return $resultJson->setData(['error' => __('Product not found.')]);

        } catch (InputException $e) {
            return $resultJson->setData(['error' => __('Invalid input: ' . $e->getMessage())]);

        } catch (\Exception $e) {
            return $resultJson->setData(['error' => __('An error occurred while retrieving the quantity.')]);
        }
    }
}
