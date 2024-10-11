<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\BaseFeatureTestCase;

class ProductControllerTest extends BaseFeatureTestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function providerNewProduct(): array
    {
        return [
            [
                [
                    'active' => 1,
                    'name'   => 'Тест товар',
                    'type'   => '',
                    'label'  => 'NEW',
                    'price'  => 100,
                    'code'   => 'testcode',
                    'slug'   => 'slug',
                ],
            ],
        ];
    }

    /**
     * @covers ProductController::store
     * @route  admin.product.store
     *
     * @dataProvider providerNewProduct()
     */
    public function testStore($product_data)
    {
        $admin = $this->createAndLoginAdministrator();
        $response = $this->actingAs($admin)->post(route('admin.product.store'), $product_data);

        $response->assertRedirect(route('admin.product.index'));

        $this->assertDatabaseHas((new Product())->getTable(), [
            'name' => 'ТЕСТ ТОВАР',
        ]);
    }

    /**
     * @covers ProductController::update
     * @route  admin.product.update
     *
     * @dataProvider providerNewProduct()
     */
    public function testUpdate($product_data)
    {
        Product::create($product_data);
        $this->assertDatabaseHas((new Product())->getTable(), [
            'id' => 1,
            'name' => 'ТЕСТ ТОВАР',
        ]);

        $product = Product::where('id', 1)->first()->toArray();
        $product['name'] = 'новый тест товар';

        $admin = $this->createAndLoginAdministrator();
        $response = $this->actingAs($admin)->post(
            route('admin.product.update', ['product_id' => 1]),
            $product,
        );

        $response->assertRedirect(route('admin.product.modify', ['product_id' => 1]));

        $this->assertDatabaseHas((new Product())->getTable(), [
            'id' => 1,
            'name' => 'НОВЫЙ ТЕСТ ТОВАР',
        ]);
    }

    /**
     * @covers ProductController::delete
     * @route  admin.product.delete
     *
     * @dataProvider providerNewProduct()
     */
    public function testDelete($product_data)
    {
        Product::create($product_data);
        $product_table = (new Product())->getTable();
        $this->assertDatabaseHas($product_table, [
            'id' => 1,
            'name' => 'ТЕСТ ТОВАР',
        ]);

        $admin = $this->createAndLoginAdministrator();
        $response = $this->actingAs($admin)->post(route('admin.product.delete', ['product_id' => 1]));

        $response->assertRedirect(route('admin.product.index'));

        $this->assertDatabaseMissing($product_table, [
            'id' => 1,
            'name' => 'ТЕСТ ТОВАР',
            'deleted_at' => null,
        ]);

        $this->assertSoftDeleted($product_table, ['id' => 1, 'name' => 'ТЕСТ ТОВАР']);
    }
}
