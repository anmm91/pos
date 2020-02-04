<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products=['pro one','pro two'];
        foreach ($products as $product){
            \App\Product::create([
                'category_id'=>1,
                'ar'=>['name'=>$product,'description'=>$product.' desc'],
                'en'=>['name'=>$product,'description'=>$product.' desc'],
                'purchase_price'=>20,
                'sale_price'=>30,
                'stock'=>100,
            ]);
        }
    }
}
