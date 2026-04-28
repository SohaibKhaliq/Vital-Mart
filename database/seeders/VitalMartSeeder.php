<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLocalization;
use App\Models\ProductCategory;
use App\Models\MediaManager;
use Carbon\Carbon;

class VitalMartSeeder extends Seeder
{
    public function run()
    {
        // 1. Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('product_localizations')->truncate();
        DB::table('product_categories')->truncate();
        DB::table('product_tags')->truncate();
        DB::table('product_taxes')->truncate();
        DB::table('product_variations')->truncate();
        DB::table('product_variation_stocks')->truncate();
        DB::table('product_variation_combinations')->truncate();
        DB::table('categories')->truncate();
        DB::table('category_localizations')->truncate();
        DB::table('media_managers')->truncate();
        DB::table('currencies')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Add PKR Currency
        DB::table('currencies')->insert([
            [
                'id' => '1',
                'code' => 'pkr',
                'name' => 'Pakistani Rupee',
                'symbol' => 'Rs.',
                'alignment' => '0',
                'rate' => '1',
                'is_active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // 3. Register Media Files
        $mediaPath = 'public/uploads/media';
        $files = scandir(base_path($mediaPath));
        $mediaMap = [];
        $id = 1;

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) continue;

            $filePath = 'uploads/media/' . $file;
            $mediaId = DB::table('media_managers')->insertGetId([
                'user_id' => 1,
                'media_file' => $filePath,
                'media_size' => filesize(base_path($mediaPath . '/' . $file)),
                'media_type' => 'image',
                'media_name' => $file,
                'media_extension' => $extension,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $mediaMap[$file] = $mediaId;
        }

        // 4. Create Categories
        $categories = [
            'Grocery' => [
                'items' => ['wheat flour.jpg', 'sella rice.jpg', 'basmati rice.jpg', 'white sugar.jpg', 'sufi ghee.jpg', 'sufi oil.jpg', 'daal chana.jpg', 'daal masoor.jpg', 'daal moong.jpg'],
                'icon' => 'grocery.png'
            ],
            'Snacks & Bakery' => [
                'items' => ['bisuits.jpg', 'oreo biscuits.jpg', 'prince biscuits.jpg', 'cake.jpg', 'pastery.jpg', 'bread.jpg', 'bun.jpg', 'rusk.jpg'],
                'icon' => 'bakery.png'
            ],
            'Healthcare' => [
                'items' => ['b-50 super vitamins.jpg', 'cac-100 vitamins.jpg', 'stevit multivitamins.jpg', 'disprin tablets.jpg', 'panadol pakistan.jpg', 'peditral ors.jpg'],
                'icon' => 'healthcare.png'
            ],
            'Household' => [
                'items' => ['surf excel.jpg', 'harpic toilet cleaner.jpg', 'ferbreze air freshner.jpg', 'lemon max dishwash liquid.jpg', 'rose petal tissue paper.jpg'],
                'icon' => 'household.png'
            ],
            'Baby Care' => [
                'items' => ['diapers.jpg', 'shield baby wipes.jpg', 'johnson baby lotion.jpg', 'lactogen 1.jpg'],
                'icon' => 'babycare.png'
            ]
        ];

        $categoryIds = [];
        foreach ($categories as $catName => $data) {
            $catId = DB::table('categories')->insertGetId([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'parent_id' => 0,
                'level' => 0,
                'is_featured' => 1,
                'is_top' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $categoryIds[$catName] = $catId;

            // Add localization
            DB::table('category_localizations')->insert([
                'category_id' => $catId,
                'name' => $catName,
                'lang_key' => 'en',
            ]);
        }

        // 5. Create Products
        $prices = [
            'wheat flour.jpg' => 1200,
            'sella rice.jpg' => 350,
            'basmati rice.jpg' => 450,
            'white sugar.jpg' => 150,
            'sufi ghee.jpg' => 600,
            'sufi oil.jpg' => 580,
            'daal chana.jpg' => 280,
            'daal masoor.jpg' => 300,
            'daal moong.jpg' => 320,
            'bisuits.jpg' => 50,
            'oreo biscuits.jpg' => 60,
            'prince biscuits.jpg' => 40,
            'cake.jpg' => 500,
            'pastery.jpg' => 150,
            'bread.jpg' => 120,
            'bun.jpg' => 40,
            'rusk.jpg' => 180,
            'b-50 super vitamins.jpg' => 850,
            'cac-100 vitamins.jpg' => 700,
            'stevit multivitamins.jpg' => 1200,
            'disprin tablets.jpg' => 20,
            'panadol pakistan.jpg' => 40,
            'peditral ors.jpg' => 50,
            'surf excel.jpg' => 450,
            'harpic toilet cleaner.jpg' => 350,
            'ferbreze air freshner.jpg' => 650,
            'lemon max dishwash liquid.jpg' => 250,
            'rose petal tissue paper.jpg' => 150,
            'diapers.jpg' => 2500,
            'shield baby wipes.jpg' => 350,
            'johnson baby lotion.jpg' => 800,
            'lactogen 1.jpg' => 1800,
        ];

        foreach ($categories as $catName => $data) {
            foreach ($data['items'] as $filename) {
                if (!isset($mediaMap[$filename])) continue;

                $name = ucwords(str_replace(['.jpg', '.png', '.jpeg'], '', $filename));
                $price = $prices[$filename] ?? rand(100, 1000);
                $mediaId = $mediaMap[$filename];

                // Create product multiple times to fill the store as requested
                for ($i = 0; $i < 2; $i++) {
                    $productId = DB::table('products')->insertGetId([
                        'shop_id' => 1,
                        'name' => $name . ($i > 0 ? " - Special Pack" : ""),
                        'slug' => Str::slug($name . ($i > 0 ? "-$i" : "")),
                        'thumbnail_image' => $mediaId,
                        'gallery_images' => json_encode([$mediaId, $mediaId, $mediaId]),
                        'min_price' => $price,
                        'max_price' => $price,
                        'stock_qty' => 100,
                        'is_published' => 1,
                        'is_featured' => 1,
                        'short_description' => "High quality $name from Vital Mart. Fresh and organic.",
                        'description' => "Vital Mart provides the best $name in Pakistan. Our products are sourced directly from manufacturers to ensure quality and freshness. $name is a must-have for your daily needs.",
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    DB::table('product_localizations')->insert([
                        'product_id' => $productId,
                        'name' => $name . ($i > 0 ? " - Special Pack" : ""),
                        'description' => "Vital Mart provides the best $name in Pakistan. Our products are sourced directly from manufacturers to ensure quality and freshness. $name is a must-have for your daily needs.",
                        'lang_key' => 'en',
                    ]);

                    DB::table('product_categories')->insert([
                        'product_id' => $productId,
                        'category_id' => $categoryIds[$catName],
                    ]);

                    // Add to theme
                    DB::table('product_themes')->insert([
                        'product_id' => $productId,
                        'theme_id' => 1,
                    ]);
                }
            }
        }

        // 6. Update System Settings
        DB::table('system_settings')->where('entity', 'default_currency')->update(['value' => 'pkr']);
        DB::table('system_settings')->where('entity', 'topbar_welcome_text')->update(['value' => 'Welcome to Vital Mart']);
        DB::table('system_settings')->where('entity', 'system_title')->update(['value' => 'Vital Mart']);
        DB::table('system_settings')->updateOrInsert(['entity' => 'enable_preloader'], ['value' => '1']);
        DB::table('system_settings')->updateOrInsert(['entity' => 'frontend_preloader'], ['value' => $mediaMap['sufi oil.jpg'] ?? '']);
        
        // You may be interested section
        DB::table('system_settings')->updateOrInsert(['entity' => 'enable_custom_product_section'], ['value' => '1']);
        DB::table('system_settings')->updateOrInsert(['entity' => 'custom_section_products_title'], ['value' => 'You may be interested']);
        DB::table('system_settings')->updateOrInsert(['entity' => 'custom_section_products_sub_title'], ['value' => 'Handpicked products for your daily needs.']);
        
        $customProductIds = DB::table('products')->pluck('id')->take(6)->toArray();
        DB::table('system_settings')->updateOrInsert(['entity' => 'custom_section_products'], ['value' => json_encode($customProductIds)]);
        
        $heroSliders = [
            [
                'id' => rand(100000, 999999),
                'sub_title' => 'Fresh & Organic',
                'title' => 'Welcome to Vital Mart',
                'text' => 'Get fresh grocery products delivered to your doorstep in minutes.',
                'image' => (string) ($mediaMap['wheat flour.jpg'] ?? ''),
                'link' => '#'
            ],
            [
                'id' => rand(100000, 999999),
                'sub_title' => 'Daily Essentials',
                'title' => 'Best Quality Ghee & Oil',
                'text' => 'We offer the purest Sufi Ghee and Oil for your healthy cooking.',
                'image' => (string) ($mediaMap['sufi ghee.jpg'] ?? ''),
                'link' => '#'
            ]
        ];
        DB::table('system_settings')->where('entity', 'hero_sliders')->update(['value' => json_encode($heroSliders)]);

        // 7. Update Featured Products settings to use new IDs
        $newProductIds = DB::table('products')->pluck('id')->take(6)->toArray();
        DB::table('system_settings')->where('entity', 'featured_products_left')->update(['value' => json_encode(array_slice($newProductIds, 0, 3))]);
        DB::table('system_settings')->where('entity', 'featured_products_right')->update(['value' => json_encode(array_slice($newProductIds, 3, 3))]);
        DB::table('system_settings')->where('entity', 'top_trending_products')->update(['value' => json_encode($newProductIds)]);
        DB::table('system_settings')->where('entity', 'weekly_best_deals')->update(['value' => json_encode($newProductIds)]);
        DB::table('system_settings')->where('entity', 'best_selling_products')->update(['value' => json_encode(array_slice($newProductIds, 0, 3))]);
    }
}
