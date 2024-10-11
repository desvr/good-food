<?php

namespace Database\Seeders;

use App\Enum\OrderDataType;
use App\Enum\OrderStatus;
use App\Enum\ProductType;
use App\Enum\ShippingType;
use App\Models\Administrator;
use App\Models\Category;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderData;
use App\Models\OrderProducts;
use App\Models\OrderTransactions;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductFeatureValue;
use App\Models\ProductOption;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $user1 = User::factory()->create([
             'name' => 'Денис',
             'birthday' => '1995-06-30',
             'phone' => '71111111111',
             'phone_verified_at' => now(),
             'avatar' => 'storage/images/profiles/avatar1.jpeg',
             'remember_token' => Str::random(10),
             'telegram_token' => Str::random(10),
         ]);

        $user2 = User::factory()->create([
            'name' => 'Наталья',
            'birthday' => '1995-06-30',
            'phone' => '71111111112',
            'phone_verified_at' => now(),
            'avatar' => 'storage/images/profiles/avatar2.jpeg',
            'remember_token' => Str::random(10),
            'telegram_token' => Str::random(10),
        ]);

        $user3 = User::factory()->create([
            'name' => 'Паша',
            'birthday' => '1995-06-30',
            'phone' => '71111111113',
            'phone_verified_at' => now(),
            'avatar' => 'storage/images/profiles/avatar3.jpeg',
            'remember_token' => Str::random(10),
            'telegram_token' => Str::random(10),
        ]);

        $admin = Administrator::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
        ]);

        $categories = Category::factory()->createMany([
            ['id' => 1, 'name' => 'Без категории', 'slug' => 'none'],
            ['id' => 2, 'name' => 'Роллы', 'slug' => 'rolly'],
            ['id' => 3, 'name' => 'Салаты', 'slug' => 'salaty'],
            ['id' => 4, 'name' => 'Супы', 'slug' => 'supy'],
            ['id' => 5, 'name' => 'Бургеры', 'slug' => 'burgery'],
            ['id' => 6, 'name' => 'Напитки', 'slug' => 'napitky'],
            ['id' => 7, 'name' => 'Разное', 'slug' => 'raznoe'],
        ]);

        $products = Product::factory()->createMany([
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('БЕКОН ЧИЗ')),
                'slug'        => Str::slug(strtolower('БЕКОН ЧИЗ')),
                'description' => 'Бекон, cливочный сыр, помидор, огурец, зелёный лук, микс кунжута. Полит соусом Терияки.',
                'image'       => 'storage/images/products/default/rolly/1.jpeg',
                'weight'      => 120,
                'calories'    => 226.7,
                'price'       => 124,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТОКИО')),
                'slug'        => Str::slug(strtolower('ТОКИО')),
                'description' => 'Угорь, крабовое мясо, сливочный сыр, огурец. Украшен соусом Унаги и миксом кунжута.',
                'image'       => 'storage/images/products/default/rolly/2.jpeg',
                'weight'      => 108,
                'calories'    => 172.6,
                'price'       => 180,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('НАОКИ')),
                'slug'        => Str::slug(strtolower('НАОКИ')),
                'description' => 'Снежный краб, сливочный сыр, огурец, икра Масаго. Под шапкой из копчёной курицы, жареного лосося. Украшен стружкой тунца.',
                'image'       => 'storage/images/products/default/rolly/3.jpeg',
                'weight'      => 120,
                'calories'    => 187,
                'price'       => 140,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЭБИ С СОУСОМ ТОМ ЯМ')),
                'slug'        => Str::slug(strtolower('ЭБИ С СОУСОМ ТОМ ЯМ')),
                'description' => 'Лосось, сливочный сыр, креветки, огурец, зелёный лук, икра Масаго.',
                'image'       => 'storage/images/products/default/rolly/5.jpeg',
                'weight'      => 124,
                'calories'    => 176.7,
                'price'       => 252,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ФЬЮЖН')),
                'slug'        => Str::slug(strtolower('ФЬЮЖН')),
                'description' => 'Рыбные наггетсы, сливочный сыр, помидор, салат Айсберг, тортилья. Полит сладким соусом Чили и украшен зелёным луком.',
                'image'       => 'storage/images/products/default/rolly/7.jpeg',
                'weight'      => 108,
                'calories'    => 192.8,
                'price'       => 140,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТЕХАС')),
                'slug'        => Str::slug(strtolower('ТЕХАС')),
                'description' => 'Бекон, сливочный сыр, огурец, жареный лук. Под шапкой из сырного соуса, полит соусом Терияки, украшен миксом кунжута.',
                'image'       => 'storage/images/products/default/rolly/10.jpeg',
                'weight'      => 128,
                'calories'    => 217.6,
                'price'       => 156,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('УНАГИ ДЕ ЛЮКС')),
                'slug'        => Str::slug(strtolower('УНАГИ ДЕ ЛЮКС')),
                'description' => 'Нежный угорь, сливочный сыр, огурец, белковая красная икра. Полит соусом Унаги.',
                'image'       => 'storage/images/products/default/rolly/13.jpeg',
                'weight'      => 165,
                'calories'    => 215.9,
                'price'       => 469,
                'label'       => 'new',
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('КАЛИФОРНИЯ ЛЮКС')),
                'slug'        => Str::slug(strtolower('КАЛИФОРНИЯ ЛЮКС')),
                'description' => 'Креветки, огурец, икра Масаго, майонез.',
                'image'       => 'storage/images/products/default/rolly/19.jpeg',
                'weight'      => 104,
                'calories'    => 153.8,
                'price'       => 180,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЧИКЕН ЧИЗ')),
                'slug'        => Str::slug(strtolower('ЧИКЕН ЧИЗ')),
                'description' => 'Курица копчёная, огурец, помидор, сливочный сыр, салат Айсберг, соус Табаджан, тортилья.',
                'image'       => 'storage/images/products/default/rolly/22.jpeg',
                'weight'      => 108,
                'calories'    => 159.4,
                'price'       => 140,
                'label'       => 'sale',
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ГЕЙША')),
                'slug'        => Str::slug(strtolower('ГЕЙША')),
                'description' => 'Свежий лосось, огурец, сыр Президент, икра Масаго.',
                'image'       => 'storage/images/products/default/rolly/23.jpeg',
                'weight'      => 104,
                'calories'    => 178,
                'price'       => 196,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('VIP РОЛЛ')),
                'slug'        => Str::slug(strtolower('VIP РОЛЛ')),
                'description' => 'Свежий лосось, сливочный сыр, огурец, белковая красная икра.',
                'image'       => 'storage/images/products/default/rolly/25.jpeg',
                'weight'      => 165,
                'calories'    => 204.6,
                'price'       => 419,
                'label'       => 'new',
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЯСАЙ ТЕМПУРА')),
                'slug'        => Str::slug(strtolower('ЯСАЙ ТЕМПУРА')),
                'description' => 'Темпура ролл с помидором, огурцом и салатом Айсберг. Под шапкой из соуса Крем-брюле, полит соусом Унаги и клубничным топпингом, с миксом кунжута.',
                'image'       => 'storage/images/products/default/rolly/26.jpeg',
                'weight'      => 154,
                'calories'    => 155.2,
                'price'       => 150,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('БАНЗАЙ')),
                'slug'        => Str::slug(strtolower('БАНЗАЙ')),
                'description' => 'Темпура ролл с угрём, снежным крабом, сливочным сыром, миксом кунжута, зелёным луком. Полит соусом Унаги.',
                'image'       => 'storage/images/products/default/rolly/33.jpeg',
                'weight'      => 110,
                'calories'    => 214,
                'price'       => 165,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('АГУРО')),
                'slug'        => Str::slug(strtolower('АГУРО')),
                'description' => 'Темпура ролл с креветками, огурцом, сливочным сыром, икрой Масаго, миксом кунжута, в сухарях. Полит сливочным соусом Чили.',
                'image'       => 'storage/images/products/default/rolly/35.jpeg',
                'weight'      => 125,
                'calories'    => 197,
                'price'       => 135,
                'label'       => null,
                'active'      => 1,
            ],
            // САЛАТЫ
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ФУНЧОЗА')),
                'slug'        => Str::slug(strtolower('ФУНЧОЗА')),
                'description' => 'Фунчоза, морковь, болгарский перец, огурец, зелёный лук.',
                'image'       => 'storage/images/products/default/salaty/42.jpeg',
                'weight'      => 155,
                'calories'    => 39.1,
                'price'       => 169,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТОРИ САРАДА')),
                'slug'        => Str::slug(strtolower('ТОРИ САРАДА')),
                'description' => 'Курица жареная, капуста пекинская, огурец, ростки сои, петрушка, лимон, арахис.',
                'image'       => 'storage/images/products/default/salaty/43.jpeg',
                'weight'      => 135,
                'calories'    => 135.2,
                'price'       => 159,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЦЕЗАРЬ С КРЕВЕТКАМИ')),
                'slug'        => Str::slug(strtolower('ЦЕЗАРЬ С КРЕВЕТКАМИ')),
                'description' => 'Креветки, салат Айсберг, сыр, помидор, соус Цезарь, сухарики.',
                'image'       => 'storage/images/products/default/salaty/44.jpeg',
                'weight'      => 143,
                'calories'    => 94,
                'price'       => 299,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЦЕЗАРЬ С КУРИЦЕЙ')),
                'slug'        => Str::slug(strtolower('ЦЕЗАРЬ С КУРИЦЕЙ')),
                'description' => 'Филе куриное копчёное, сыр, помидор, салат Айсберг, соус Цезарь, сухарики.',
                'image'       => 'storage/images/products/default/salaty/45.jpeg',
                'weight'      => 153,
                'calories'    => 100,
                'price'       => 269,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТОРИ НО ШИИТАКИ')),
                'slug'        => Str::slug(strtolower('ТОРИ НО ШИИТАКИ')),
                'description' => 'Филе куриное копчёное, грибы Шиитаки в Терияки, огурец, салат Айсберг, зелёный лук, майонез ореховый.',
                'image'       => 'storage/images/products/default/salaty/46.jpeg',
                'weight'      => 150,
                'calories'    => 113.6,
                'price'       => 199,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЧУКА')),
                'slug'        => Str::slug(strtolower('ЧУКА')),
                'description' => 'Чука, огурец, рис, соус ореховый, кунжут.',
                'image'       => 'storage/images/products/default/salaty/47.jpeg',
                'weight'      => 192,
                'calories'    => 135.2,
                'price'       => 239,
                'label'       => null,
                'active'      => 1,
            ],
            // СУПЫ
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ЧИКЕН РАМЕН')),
                'slug'        => Str::slug(strtolower('ЧИКЕН РАМЕН')),
                'description' => 'Секретный японский рецепт бульона! Удон, курица, зелёный лук, ростки сои и кунжут.',
                'image'       => 'storage/images/products/default/supy/36.jpeg',
                'weight'      => 480,
                'calories'    => 76.5,
                'price'       => 239,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТОМ ЯМ С КРЕВЕТКОЙ')),
                'slug'        => Str::slug(strtolower('ТОМ ЯМ С КРЕВЕТКОЙ')),
                'description' => 'Креветка, кальмары, помидор, шампиньоны, кинза. На основе кокосового молока, куриного бульона и пасты из морепродуктов. Подаётся без риса.',
                'image'       => 'storage/images/products/default/supy/37.jpeg',
                'weight'      => 300,
                'calories'    => 40.9,
                'price'       => 389,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТОМ ЯМ С КУРИЦЕЙ')),
                'slug'        => Str::slug(strtolower('ТОМ ЯМ С КУРИЦЕЙ')),
                'description' => 'Филе куриное, помидор, шампиньоны, кинза. На основе кокосового молока, куриного бульона и пасты из морепродуктов. Подаётся без риса.',
                'image'       => 'storage/images/products/default/supy/38.jpeg',
                'weight'      => 300,
                'calories'    => 50.6,
                'price'       => 269,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('МИСО С ЛОСОСЕМ')),
                'slug'        => Str::slug(strtolower('МИСО С ЛОСОСЕМ')),
                'description' => 'Паста мисо, лосось, грибы Шиитаке, морские водоросли, зелёный лук.',
                'image'       => 'storage/images/products/default/supy/39.jpeg',
                'weight'      => 275,
                'calories'    => 23.9,
                'price'       => 189,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ТОМ ЯМ С ЛАПШОЙ')),
                'slug'        => Str::slug(strtolower('ТОМ ЯМ С ЛАПШОЙ')),
                'description' => 'Курица жареная, рисовая лапша, кинза. На основе кокосового молока, куриного бульона и пасты из морепродуктов.',
                'image'       => 'storage/images/products/default/supy/40.jpeg',
                'weight'      => 275,
                'calories'    => 44.5,
                'price'       => 219,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('МИСО')),
                'slug'        => Str::slug(strtolower('МИСО')),
                'description' => 'Паста мисо, грибы Шиитаке, морские водоросли, зелёный лук.',
                'image'       => 'storage/images/products/default/supy/41.jpeg',
                'weight'      => 260,
                'calories'    => 19.5,
                'price'       => 119,
                'label'       => null,
                'active'      => 1,
            ],
            // БУРГЕРЫ
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('СУШИ-БУРГЕР С КУРИЦЕЙ')),
                'slug'        => Str::slug(strtolower('СУШИ-БУРГЕР С КУРИЦЕЙ')),
                'description' => 'Рисовая булочка, обжаренная в сухарях Панко, курица, помидор, лист салата, сыр Чеддер, соус бургер.',
                'image'       => 'storage/images/products/default/burgery/48.jpeg',
                'weight'      => 330,
                'calories'    => 190.6,
                'price'       => 269,
                'label'       => 'sale',
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('СУШИ-БУРГЕР С ЛОСОСЕМ')),
                'slug'        => Str::slug(strtolower('СУШИ-БУРГЕР С ЛОСОСЕМ')),
                'description' => 'Рисовая булочка, обжаренная в сухарях Панко, лосось, помидор, лист салата, с добавлением соусов: сырный, Табаджан и бургер.',
                'image'       => 'storage/images/products/default/burgery/49.jpeg',
                'weight'      => 350,
                'calories'    => 146.9,
                'price'       => 399,
                'label'       => 'new',
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('СУШИ-БУРГЕР С БЕКОНОМ')),
                'slug'        => Str::slug(strtolower('СУШИ-БУРГЕР С БЕКОНОМ')),
                'description' => 'Рисовая булочка, обжаренная в сухарях Панко, бекон, помидор, огурец, лист салата, сыр Чеддер, соус бургер.',
                'image'       => 'storage/images/products/default/burgery/50.jpeg',
                'weight'      => 350,
                'calories'    => 183.6,
                'price'       => 299,
                'label'       => null,
                'active'      => 1,
            ],
            // НАПИТКИ
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ИМБИРНЫЙ АПЕЛЬСИН')),
                'slug'        => Str::slug(strtolower('ИМБИРНЫЙ АПЕЛЬСИН')),
                'description' => '',
                'image'       => 'storage/images/products/default/napitky/61.jpeg',
                'weight'      => 300,
                'calories'    => 33.4,
                'price'       => 75,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('МОРС КЛЮКВА')),
                'slug'        => Str::slug(strtolower('МОРС КЛЮКВА')),
                'description' => '',
                'image'       => 'storage/images/products/default/napitky/62.jpeg',
                'weight'      => 300,
                'calories'    => 31.7,
                'price'       => 89,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('МОРС ЧЁРНАЯ СМОРОДИНА')),
                'slug'        => Str::slug(strtolower('МОРС ЧЁРНАЯ СМОРОДИНА')),
                'description' => '',
                'image'       => 'storage/images/products/default/napitky/63.jpeg',
                'weight'      => 300,
                'calories'    => 30,
                'price'       => 89,
                'label'       => null,
                'active'      => 1,
            ],
            // РАЗНОЕ
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('СОЕВЫЙ СОУС')),
                'slug'        => Str::slug(strtolower('СОЕВЫЙ СОУС')),
                'description' => '',
                'image'       => 'storage/images/products/default/raznoe/64.jpeg',
                'weight'      => 30,
                'calories'    => 72,
                'price'       => 25,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ВАСАБИ')),
                'slug'        => Str::slug(strtolower('ВАСАБИ')),
                'description' => '',
                'image'       => 'storage/images/products/default/raznoe/65.jpeg',
                'weight'      => 5,
                'calories'    => 238,
                'price'       => 15,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('ИМБИРЬ')),
                'slug'        => Str::slug(strtolower('ИМБИРЬ')),
                'description' => '',
                'image'       => 'storage/images/products/default/raznoe/66.jpeg',
                'weight'      => 20,
                'calories'    => 17.8,
                'price'       => 25,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('СОУС ТЕРИЯКИ')),
                'slug'        => Str::slug(strtolower('СОУС ТЕРИЯКИ')),
                'description' => '',
                'image'       => 'storage/images/products/default/raznoe/67.jpeg',
                'weight'      => 30,
                'calories'    => 65,
                'price'       => 25,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'name'        => ucfirst(strtolower('СОУС КИСЛО-СЛАДКИЙ')),
                'slug'        => Str::slug(strtolower('СОУС КИСЛО-СЛАДКИЙ')),
                'description' => '',
                'image'       => 'storage/images/products/default/raznoe/68.jpeg',
                'weight'      => 30,
                'calories'    => 64,
                'price'       => 25,
                'label'       => null,
                'active'      => 1,
            ],
        ])->each(function($product) {
            $category_name = explode('/', $product->image);
            $product->categories()->attach(
                Category::where('slug', '=', $category_name[4])->firstOr(function () {
                    Category::where('slug', '=', 'none')->first();
                })
            );
        });

        $products_variations = Product::factory()->createMany([
            [
                'code'        => uniqid(),
                'parent_id'   => 1,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('БЕКОН ЧИЗ')),
                'slug'        => Str::slug(strtolower('БЕКОН ЧИЗ')),
                'description' => 'Бекон, cливочный сыр, помидор, огурец, зелёный лук, микс кунжута. Полит соусом Терияки.',
                'image'       => 'storage/images/products/default/rolly/1.jpeg',
                'weight'      => 140,
                'calories'    => 266.7,
                'price'       => 150,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 1,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('БЕКОН ЧИЗ')),
                'slug'        => Str::slug(strtolower('БЕКОН ЧИЗ')),
                'description' => 'Бекон, cливочный сыр, помидор, огурец, зелёный лук, микс кунжута. Полит соусом Терияки.',
                'image'       => 'storage/images/products/default/rolly/1.jpeg',
                'weight'      => 160,
                'calories'    => 306.7,
                'price'       => 170,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 2,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('ТОКИО')),
                'slug'        => Str::slug(strtolower('ТОКИО')),
                'description' => 'Угорь, крабовое мясо, сливочный сыр, огурец. Украшен соусом Унаги и миксом кунжута.',
                'image'       => 'storage/images/products/default/rolly/2.jpeg',
                'weight'      => 130,
                'calories'    => 212.4,
                'price'       => 220,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 2,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('ТОКИО')),
                'slug'        => Str::slug(strtolower('ТОКИО')),
                'description' => 'Угорь, крабовое мясо, сливочный сыр, огурец. Украшен соусом Унаги и миксом кунжута.',
                'image'       => 'storage/images/products/default/rolly/2.jpeg',
                'weight'      => 152,
                'calories'    => 242.2,
                'price'       => 250,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 3,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('НАОКИ')),
                'slug'        => Str::slug(strtolower('НАОКИ')),
                'description' => 'Снежный краб, сливочный сыр, огурец, икра Масаго. Под шапкой из копчёной курицы, жареного лосося. Украшен стружкой тунца.',
                'image'       => 'storage/images/products/default/rolly/3.jpeg',
                'weight'      => 140,
                'calories'    => 220,
                'price'       => 170,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 3,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('НАОКИ')),
                'slug'        => Str::slug(strtolower('НАОКИ')),
                'description' => 'Снежный краб, сливочный сыр, огурец, икра Масаго. Под шапкой из копчёной курицы, жареного лосося. Украшен стружкой тунца.',
                'image'       => 'storage/images/products/default/rolly/3.jpeg',
                'weight'      => 253,
                'calories'    => 242.2,
                'price'       => 195,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 4,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('ЭБИ С СОУСОМ ТОМ ЯМ')),
                'slug'        => Str::slug(strtolower('ЭБИ С СОУСОМ ТОМ ЯМ')),
                'description' => 'Лосось, сливочный сыр, креветки, огурец, зелёный лук, икра Масаго.',
                'image'       => 'storage/images/products/default/rolly/5.jpeg',
                'weight'      => 150,
                'calories'    => 207.80,
                'price'       => 295,
                'label'       => null,
                'active'      => 1,
            ],
            [
                'code'        => uniqid(),
                'parent_id'   => 4,
                'type'        => ProductType::VARIATION->value,
                'name'        => ucfirst(strtolower('ЭБИ С СОУСОМ ТОМ ЯМ')),
                'slug'        => Str::slug(strtolower('ЭБИ С СОУСОМ ТОМ ЯМ')),
                'description' => 'Лосось, сливочный сыр, креветки, огурец, зелёный лук, икра Масаго.',
                'image'       => 'storage/images/products/default/rolly/5.jpeg',
                'weight'      => 176,
                'calories'    => 238.9,
                'price'       => 338,
                'label'       => null,
                'active'      => 1,
            ],
        ])->each(function($product) {
            $category_name = explode('/', $product->image);
            $product->categories()->attach(
                Category::where('slug', '=', $category_name[4])->firstOr(function () {
                    Category::where('slug', '=', 'none')->first();
                })
            );
        });

        $product_features = ProductFeature::factory()->createMany([
            ['name' => 'Порция', 'value' => 'portion'],
        ]);

        $product_feature_values = ProductFeatureValue::factory()->createMany([
            ['name' => '8 шт.', 'value' => 8],
            ['name' => '10 шт.', 'value' => 10],
            ['name' => '12 шт.', 'value' => 12],
        ]);

        $product_variations = ProductVariation::factory()->createMany([
            [
                'product_id'       => 1,
                'feature_id'       => 1,
                'feature_value_id' => 1,
            ],
            [
                'product_id'       => 38,
                'feature_id'       => 1,
                'feature_value_id' => 2,
            ],
            [
                'product_id'       => 39,
                'feature_id'       => 1,
                'feature_value_id' => 3,
            ],
            [
                'product_id'       => 2,
                'feature_id'       => 1,
                'feature_value_id' => 1,
            ],
            [
                'product_id'       => 40,
                'feature_id'       => 1,
                'feature_value_id' => 2,
            ],
            [
                'product_id'       => 41,
                'feature_id'       => 1,
                'feature_value_id' => 3,
            ],
            [
                'product_id'       => 3,
                'feature_id'       => 1,
                'feature_value_id' => 1,
            ],
            [
                'product_id'       => 42,
                'feature_id'       => 1,
                'feature_value_id' => 2,
            ],
            [
                'product_id'       => 43,
                'feature_id'       => 1,
                'feature_value_id' => 3,
            ],
            [
                'product_id'       => 4,
                'feature_id'       => 1,
                'feature_value_id' => 1,
            ],
            [
                'product_id'       => 44,
                'feature_id'       => 1,
                'feature_value_id' => 2,
            ],
            [
                'product_id'       => 45,
                'feature_id'       => 1,
                'feature_value_id' => 3,
            ],
        ]);

        $payments = Payment::factory()->createMany([
            [
                'name' => config('payments.stripe_checkout.db.name'),
                'method' => config('payments.stripe_checkout.db.method'),
                'processor' => config('payments.stripe_checkout.db.processor'),
                'settings' => config('payments.stripe_checkout.db.settings'),
                'webhook_key' => config('payments.stripe_checkout.db.webhook_key'),
                'template' => config('payments.stripe_checkout.db.template'),
            ],
            [
                'name' => config('payments.yookassa.db.name'),
                'method' => config('payments.yookassa.db.method'),
                'processor' => config('payments.yookassa.db.processor'),
                'settings' => config('payments.yookassa.db.settings'),
                'webhook_key' => config('payments.yookassa.db.webhook_key'),
                'template' => config('payments.yookassa.db.template'),
            ],
        ]);

        $rolly_category_id = Category::where('slug', '=', 'rolly')->first()->only('id')['id'];
        $options = Option::factory()->createMany([
            [
                'category_id' => $rolly_category_id,
                'type'        => 'category',
                'name'        => 'Классические',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'category',
                'name'        => 'Фирменные',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'category',
                'name'        => 'Темпурные',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'category',
                'name'        => 'Запеченные',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'ingredient',
                'name'        => 'Лосось',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'ingredient',
                'name'        => 'Угорь',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'ingredient',
                'name'        => 'Креветки',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'ingredient',
                'name'        => 'Острые',
            ],
            [
                'category_id' => $rolly_category_id,
                'type'        => 'ingredient',
                'name'        => 'Вегетерианские',
            ],
        ]);

        $product_options = ProductOption::factory()->createMany([
            ['product_id' => 2, 'option_id' => 2],
            ['product_id' => 2, 'option_id' => 6],
            ['product_id' => 3, 'option_id' => 2],
            ['product_id' => 3, 'option_id' => 3],
            ['product_id' => 3, 'option_id' => 5],
            ['product_id' => 4, 'option_id' => 4],
            ['product_id' => 4, 'option_id' => 5],
            ['product_id' => 4, 'option_id' => 7],
            ['product_id' => 5, 'option_id' => 1],
            ['product_id' => 6, 'option_id' => 2],
            ['product_id' => 7, 'option_id' => 1],
            ['product_id' => 7, 'option_id' => 6],
            ['product_id' => 8, 'option_id' => 4],
            ['product_id' => 8, 'option_id' => 7],
            ['product_id' => 9, 'option_id' => 1],
            ['product_id' => 10, 'option_id' => 1],
            ['product_id' => 10, 'option_id' => 5],
            ['product_id' => 11, 'option_id' => 2],
            ['product_id' => 11, 'option_id' => 5],
            ['product_id' => 12, 'option_id' => 3],
            ['product_id' => 13, 'option_id' => 3],
            ['product_id' => 13, 'option_id' => 6],
            ['product_id' => 14, 'option_id' => 3],
            ['product_id' => 14, 'option_id' => 7],
        ]);

        $order = Order::factory()->create([
            'user_id' => $user1,
            'name' => 'Денис',
            'phone' => '+7 (111) 111-1111',
            'verified' => 0,
            'status' => OrderStatus::PAID->value,
            'shipping_type' => ShippingType::DELIVERY->value,
            'delivery_area' => 'Новый город / Верхняя терасса / Нижняя терасса',
            'delivery_address' => 'Филатова, д. 15, подъезд 3, этаж 5, кв. 220',
            'is_preorder' => 1,
            'preorder_datetime' => '02/04/2025 13:00',
            'note' => 'Позвонить заранее до приезда курьера',
            'number_persons' => 2,
            'payment_method' => config('payments.stripe_checkout.db.method'),
            'promotion_id' => null,
            'original_price' => 2088,
            'result_price' => 1588,
            'bonus_points' => null,
            'condition_id' => 999,
            'condition_data' => [
                "promotions" => [
                    "name" => "ПРОМО500",
                    "sum_value" => -500,
                ],
            ],
            'request_send' => 0,
            'receipt_code' => 3660,
        ]);

        OrderProducts::factory()->createMany([
            [
                'order_id' => $order->id,
                'product_id' => 13,
                'quantity' => 4,
                'original_item_price' => 165,
                'result_item_price' => 165,
                'original_subtotal_price' => 660,
                'result_subtotal_price' => 660,
                'data' => [
                    "name" => "БАНЗАЙ",
                    "quantity" => 4,
                    "result_item_price" => 165,
                    "original_item_price" => 165,
                    "result_subtotal_price" => 660,
                    "original_subtotal_price" => 660,
                ],
            ],
            [
                'order_id' => $order->id,
                'product_id' => 1,
                'quantity' => 2,
                'original_item_price' => 124,
                'result_item_price' => 124,
                'original_subtotal_price' => 248,
                'result_subtotal_price' => 248,
                'data' => [
                    "name" => "ТОКИО",
                    "quantity" => 4,
                    "variations" => [
                        "Порция" => "12 шт.",
                    ],
                    "result_item_price" => 250,
                    "original_item_price" => 250,
                    "result_subtotal_price" => 1000,
                    "original_subtotal_price" => 1000,
                ],
            ],
            [
                'order_id' => $order->id,
                'product_id' => 2,
                'quantity' => 1,
                'original_item_price' => 180,
                'result_item_price' => 180,
                'original_subtotal_price' => 180,
                'result_subtotal_price' => 180,
                'data' => [
                    "name" => "БЕКОН ЧИЗ",
                    "quantity" => 2,
                    "variations" => [
                        "Порция" => "8 шт."
                    ],
                    "result_item_price" => 124,
                    "original_item_price" => 124,
                    "result_subtotal_price" => 248,
                    "original_subtotal_price" => 248,
                ],
            ],
            [
                'order_id' => $order->id,
                'product_id' => 41,
                'quantity' => 4,
                'original_item_price' => 250,
                'result_item_price' => 250,
                'original_subtotal_price' => 1000,
                'result_subtotal_price' => 1000,
                'data' => [
                    "name" => "ТОКИО",
                    "quantity" => 1,
                    "variations" => [
                        "Порция" => "8 шт."
                    ],
                    "result_item_price" => 180,
                    "original_item_price" => 180,
                    "result_subtotal_price" => 180,
                    "original_subtotal_price" => 180,
                ],
            ],
        ]);

        OrderData::destroy($order->id);
        OrderData::factory()->createMany([
            [
                'order_id' => $order->id,
                'type' => OrderDataType::HISTORY->value,
                'data' => [
                    ["updated_at" => "28.09.2024 14:01:47", "order_status" => "O"],
                    ["updated_at" => "28.09.2024 14:02:15", "order_status" => "P"]
                ],
            ],
            [
                'order_id' => $order->id,
                'type' => OrderDataType::PAYMENT->value,
                'data' => [
                    [
                        "status" => "checkout_created",
                        "session_id" => "cs_test_b1tO8sqJKWjbtvooXUkRCuJAyjr312rkkSS3uFxv6oQEgKnAaHuDah27AX",
                        "updated_at" => "2024-09-28 14:01:49"
                    ],
                    [
                        "status" => "success",
                        "session_id" => "cs_test_b1tO8sqJKWjbtvooXUkRCuJAyjr312rkkSS3uFxv6oQEgKnAaHuDah27AX",
                        "updated_at" => "2024-09-28 14:02:15"
                    ],
                ],
            ],
        ]);

        OrderTransactions::factory()->create([
            'order_id' => $order->id,
            'payment_id' => 1,
            'transaction_key' => 'cs_test_b1tO8sqJKWjbtvooXUkRCuJAyjr312rkkSS3uFxv6oQEgKnAaHuDah27AX',
            'amount' => 1588,
        ]);
    }
}
