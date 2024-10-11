<?php

namespace Tests\Unit\Services\Shop\Products;

use App\Contracts\Shop\Services\Products\ProductPrepareDataServiceContract;
use App\Enum\ProductType;
use App\Models\Product;
use App\Services\Shop\Products\ProductPrepareDataService;
use PHPUnit\Framework\TestCase;

class ProductPrepareDataServiceTest extends TestCase
{
    private ProductPrepareDataServiceContract $productPrepareDataService;

    public function setUp(): void
    {
        $this->productPrepareDataService = new ProductPrepareDataService();
    }

    public function providerPrepareUpdateProductData(): array
    {
        return [
            [
                [
                    'name'       => ' Тест товар ',
                    'type'       => '',
                    'label'      => 'NEW',
                    'categories' => '1',
                ],
                [
                    'name'       => 'ТЕСТ ТОВАР',
                    'type'       => ProductType::PRODUCT->value,
                    'label'      => 'new',
                    'slug'       => 'test-tovar',
                ],
                '1',
            ],
            [
                [
                    'name'       => ' Тест товар ',
                    'type'       => ProductType::VARIATION->value,
                ],
                [
                    'name'       => 'ТЕСТ ТОВАР',
                    'type'       => ProductType::VARIATION->value,
                    'slug'       => 'test-tovar',
                    'label'      => null,
                ],
                '',
            ],
        ];
    }

    /**
     * @covers ProductPrepareDataService::prepareUpdateProductData
     *
     * @dataProvider providerPrepareUpdateProductData
     *
     * @return void
     */
    public function testPrepareUpdateProductData($product_data, $result_product_data, $result_categories)
    {
        $product = new Product(['name' => 'none']);

        [$product_data, $categories] = $this->productPrepareDataService->prepareUpdateProductData($product, $product_data);

        $this->assertSame($product_data, $result_product_data);
        $this->assertSame($categories, $result_categories);
    }

    public function providerPrepareCreateProductData(): array
    {
        return [
            [
                [
                    'name'       => ' Тест товар ',
                    'type'       => '',
                    'slug'       => ' test slug ',
                    'label'      => 'NEW',
                    'categories' => '1',
                ],
                [
                    'name'       => 'ТЕСТ ТОВАР',
                    'type'       => ProductType::PRODUCT->value,
                    'slug'       => 'test-slug',
                    'label'      => 'new',
                    'parent_id'  => null,
                    'code'       => '',
                ],
                '1',
            ],
        ];
    }

    /**
     * @covers ProductPrepareDataService::prepareCreateProductData
     *
     * @dataProvider providerPrepareCreateProductData
     *
     * @return void
     */
    public function testPrepareCreateProductData($product_data, $result_product_data, $result_categories)
    {
        [$product_data, $categories] = $this->productPrepareDataService->prepareCreateProductData($product_data);

        $result_product_data['code'] = $product_data['code'];

        $this->assertSame($product_data, $result_product_data);
        $this->assertSame($categories, $result_categories);
    }
}
