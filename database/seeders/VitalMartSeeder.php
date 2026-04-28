<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VitalMartSeeder extends Seeder
{
    public function run()
    {
        // ─────────────────────────────────────────────
        // 1. TRUNCATE all related tables
        // ─────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('locations')->truncate();
        DB::table('products')->truncate();
        DB::table('product_localizations')->truncate();
        DB::table('product_categories')->truncate();
        DB::table('product_tags')->truncate();
        DB::table('product_taxes')->truncate();
        DB::table('product_variations')->truncate();
        DB::table('product_variation_stocks')->truncate();
        DB::table('product_variation_combinations')->truncate();
        DB::table('product_colors')->truncate();
        DB::table('categories')->truncate();
        DB::table('category_localizations')->truncate();
        DB::table('media_managers')->truncate();
        DB::table('currencies')->truncate();
        DB::table('carts')->truncate();
        DB::table('wishlists')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─────────────────────────────────────────────
        // 2. GIMS Location (is_default = 1)
        //    The StockLocation middleware sets session('stock_location_id')
        //    from Location::where('is_default',1)->first()->id
        //    Stock records MUST use this same id.
        // ─────────────────────────────────────────────
        $locationId = DB::table('locations')->insertGetId([
            'name'         => 'GIMS — Government Institute of Medical Sciences',
            'address'      => 'GIMS, Greater Noida, Uttar Pradesh, Pakistan',
            'latitude'     => '31.5204',
            'longitude'    => '74.3587',
            'is_default'   => 1,
            'is_published' => 1,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

        // ─────────────────────────────────────────────
        // 3. PKR Currency
        // ─────────────────────────────────────────────
        DB::table('currencies')->insert([
            'id'         => 1,
            'code'       => 'pkr',
            'name'       => 'Pakistani Rupee',
            'symbol'     => 'Rs.',
            'alignment'  => '0',
            'rate'       => '1',
            'is_active'  => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // ─────────────────────────────────────────────
        // 4. Register ALL media files (jpg/jpeg/png/gif/webp)
        //    logo.jpeg  → navbar_logo, footer_logo, admin_panel_logo
        //    loader.gif → frontend_preloader
        // ─────────────────────────────────────────────
        $mediaPath = 'public/uploads/media';
        $mediaMap  = [];

        $files = scandir(base_path($mediaPath));
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) continue;

            $type = ($ext === 'gif') ? 'gif' : 'image';

            $mediaId = DB::table('media_managers')->insertGetId([
                'user_id'         => 1,
                'media_file'      => 'uploads/media/' . $file,
                'media_size'      => filesize(base_path($mediaPath . '/' . $file)),
                'media_type'      => $type,
                'media_name'      => $file,
                'media_extension' => $ext,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            $mediaMap[$file] = $mediaId;
        }

        $logoId    = $mediaMap['logo.jpeg']  ?? null;
        $loaderId  = $mediaMap['loader.gif'] ?? null;

        // ─────────────────────────────────────────────
        // 5. Categories
        // ─────────────────────────────────────────────
        $categories = [
            'Grocery' => [
                'thumb' => 'wheat flour.jpg',
                'items' => [
                    'wheat flour.jpg', 'sella rice.jpg', 'basmati rice.jpg',
                    'white sugar.jpg', 'sufi ghee.jpg', 'sufi oil.jpg',
                    'daal chana.jpg', 'daal masoor.jpg', 'daal moong.jpg',
                ],
            ],
            'Snacks & Bakery' => [
                'thumb' => 'oreo biscuits.jpg',
                'items' => [
                    'bisuits.jpg', 'oreo biscuits.jpg', 'prince biscuits.jpg',
                    'cake.jpg', 'pastery.jpg', 'bread.jpg', 'bun.jpg', 'rusk.jpg',
                ],
            ],
            'Healthcare' => [
                'thumb' => 'panadol pakistan.jpg',
                'items' => [
                    'b-50 super vitamins.jpg', 'cac-100 vitamins.jpg', 'stevit multivitamins.jpg',
                    'disprin tablets.jpg', 'panadol pakistan.jpg', 'peditral ors.jpg',
                ],
            ],
            'Household' => [
                'thumb' => 'surf excel.jpg',
                'items' => [
                    'surf excel.jpg', 'harpic toilet cleaner.jpg', 'ferbreze air freshner.jpg',
                    'lemon max dishwash liquid.jpg', 'rose petal tissue paper.jpg',
                ],
            ],
            'Baby Care' => [
                'thumb' => 'diapers.jpg',
                'items' => [
                    'diapers.jpg', 'shield baby wipes.jpg',
                    'johnson baby lotion.jpg', 'lactogen 1.jpg',
                ],
            ],
        ];

        $prices = [
            'wheat flour.jpg'            => 1200,
            'sella rice.jpg'             => 350,
            'basmati rice.jpg'           => 450,
            'white sugar.jpg'            => 150,
            'sufi ghee.jpg'              => 600,
            'sufi oil.jpg'               => 580,
            'daal chana.jpg'             => 280,
            'daal masoor.jpg'            => 300,
            'daal moong.jpg'             => 320,
            'bisuits.jpg'                => 50,
            'oreo biscuits.jpg'          => 60,
            'prince biscuits.jpg'        => 40,
            'cake.jpg'                   => 500,
            'pastery.jpg'                => 150,
            'bread.jpg'                  => 120,
            'bun.jpg'                    => 40,
            'rusk.jpg'                   => 180,
            'b-50 super vitamins.jpg'    => 850,
            'cac-100 vitamins.jpg'       => 700,
            'stevit multivitamins.jpg'   => 1200,
            'disprin tablets.jpg'        => 20,
            'panadol pakistan.jpg'       => 40,
            'peditral ors.jpg'           => 50,
            'surf excel.jpg'             => 450,
            'harpic toilet cleaner.jpg'  => 350,
            'ferbreze air freshner.jpg'  => 650,
            'lemon max dishwash liquid.jpg' => 250,
            'rose petal tissue paper.jpg'   => 150,
            'diapers.jpg'                => 2500,
            'shield baby wipes.jpg'      => 350,
            'johnson baby lotion.jpg'    => 800,
            'lactogen 1.jpg'             => 1800,
        ];

        $allProductIds = [];

        foreach ($categories as $catName => $data) {
            // Resolve category thumbnail
            $thumbId = $mediaMap[$data['thumb']] ?? null;

            // Insert category
            $catId = DB::table('categories')->insertGetId([
                'name'            => $catName,
                'slug'            => Str::slug($catName),
                'parent_id'       => 0,
                'level'           => 0,
                'is_featured'     => 1,
                'is_top'          => 1,
                'thumbnail_image' => $thumbId,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);

            // Category localization
            DB::table('category_localizations')->insert([
                'category_id'     => $catId,
                'name'            => $catName,
                'thumbnail_image' => $thumbId,
                'lang_key'        => 'en',
            ]);

            // Products for this category
            foreach ($data['items'] as $filename) {
                if (!isset($mediaMap[$filename])) continue;

                $name    = ucwords(str_replace(['.jpg', '.jpeg', '.png'], '', $filename));
                $price   = $prices[$filename] ?? 200;
                $mediaId = $mediaMap[$filename];

                // Each image seeds ONE product (no duplicates — clean store)
                $productId = DB::table('products')->insertGetId([
                    'shop_id'           => 1,
                    'added_by'          => 'admin',
                    'name'              => $name,
                    'slug'              => Str::slug($name),
                    'thumbnail_image'   => $mediaId,
                    'gallery_images'    => json_encode([$mediaId, $mediaId, $mediaId]),
                    'min_price'         => $price,
                    'max_price'         => $price,
                    'stock_qty'         => 100,
                    'is_published'      => 1,
                    'is_featured'       => 1,
                    'has_variation'     => 0,   // simple product — no variant selector shown
                    'has_warranty'      => 0,
                    'min_purchase_qty'  => 1,
                    'max_purchase_qty'  => 10,
                    'short_description' => "Premium quality $name from Vital Mart.",
                    'description'       => "Vital Mart brings you the finest $name, sourced directly from trusted suppliers across Pakistan. Fresh, pure, and delivered to your doorstep.",
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);

                $allProductIds[] = $productId;

                // ── Product Localization ──────────────────────────
                DB::table('product_localizations')->insert([
                    'product_id'  => $productId,
                    'name'        => $name,
                    'description' => "Vital Mart brings you the finest $name, sourced directly from trusted suppliers across Pakistan. Fresh, pure, and delivered to your doorstep.",
                    'lang_key'    => 'en',
                ]);

                // ── Product ↔ Category ───────────────────────────
                DB::table('product_categories')->insert([
                    'product_id'  => $productId,
                    'category_id' => $catId,
                ]);

                // ── Product ↔ Theme ──────────────────────────────
                DB::table('product_themes')->insert([
                    'product_id' => $productId,
                    'theme_id'   => 1,
                ]);

                // ── Default Variation ────────────────────────────
                //    variation_key must be non-empty for the cart to work
                $variationId = DB::table('product_variations')->insertGetId([
                    'product_id'    => $productId,
                    'variation_key' => 'default',
                    'sku'           => 'VM-' . str_pad($productId, 5, '0', STR_PAD_LEFT),
                    'price'         => $price,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);

                // ── Variation Stock (linked to GIMS location) ────
                //    CRITICAL: location_id must match the default location
                //    so StockLocation middleware resolves stock correctly.
                DB::table('product_variation_stocks')->insert([
                    'product_variation_id' => $variationId,
                    'location_id'          => $locationId,   // ← GIMS
                    'stock_qty'            => 100,
                    'created_at'           => Carbon::now(),
                    'updated_at'           => Carbon::now(),
                ]);
            }
        }

        // ─────────────────────────────────────────────
        // 6. System Settings
        // ─────────────────────────────────────────────

        // Currency
        DB::table('system_settings')->where('entity', 'default_currency')
            ->update(['value' => 'pkr']);

        // Branding
        DB::table('system_settings')->where('entity', 'system_title')
            ->update(['value' => 'Vital Mart']);
        DB::table('system_settings')->where('entity', 'topbar_welcome_text')
            ->update(['value' => 'Welcome to Vital Mart']);
        DB::table('system_settings')->where('entity', 'topbar_email')
            ->update(['value' => 'info@vitalmart.pk']);
        DB::table('system_settings')->where('entity', 'topbar_location')
            ->update(['value' => 'GIMS, Pakistan']);

        // Logo (navbar, footer, admin)
        if ($logoId) {
            DB::table('system_settings')->where('entity', 'navbar_logo')
                ->update(['value' => $logoId]);
            DB::table('system_settings')->where('entity', 'footer_logo')
                ->update(['value' => $logoId]);
            DB::table('system_settings')->where('entity', 'admin_panel_logo')
                ->update(['value' => $logoId]);
        }

        // Preloader
        DB::table('system_settings')->updateOrInsert(
            ['entity' => 'enable_preloader'],
            ['value'  => '1']
        );
        if ($loaderId) {
            DB::table('system_settings')->updateOrInsert(
                ['entity' => 'frontend_preloader'],
                ['value'  => $loaderId]
            );
        }

        // Hero sliders
        $heroSliders = [
            [
                'id'        => rand(100000, 999999),
                'sub_title' => 'Fresh & Organic',
                'title'     => 'Welcome to Vital Mart',
                'text'      => 'Get fresh grocery products delivered to your doorstep in minutes.',
                'image'     => (string) ($mediaMap['wheat flour.jpg'] ?? ''),
                'link'      => '#',
            ],
            [
                'id'        => rand(100000, 999999),
                'sub_title' => 'Daily Essentials',
                'title'     => 'Best Quality Ghee & Oil',
                'text'      => 'We offer the purest Sufi Ghee and Oil for your healthy cooking.',
                'image'     => (string) ($mediaMap['sufi ghee.jpg'] ?? ''),
                'link'      => '#',
            ],
        ];
        DB::table('system_settings')->where('entity', 'hero_sliders')
            ->update(['value' => json_encode($heroSliders)]);

        // Featured / trending products
        $trendingIds = $allProductIds; 
        shuffle($trendingIds);
        $trendingIds = array_slice($trendingIds, 0, 12); // Take 12 products for a full grid
        
        $featuredIds = array_slice($allProductIds, 0, 6);
        
        DB::table('system_settings')->where('entity', 'featured_products_left')
            ->update(['value' => json_encode(array_slice($featuredIds, 0, 3))]);
        DB::table('system_settings')->where('entity', 'featured_products_right')
            ->update(['value' => json_encode(array_slice($featuredIds, 3, 3))]);
        DB::table('system_settings')->where('entity', 'top_trending_products')
            ->update(['value' => json_encode($trendingIds)]);
        DB::table('system_settings')->where('entity', 'weekly_best_deals')
            ->update(['value' => json_encode($trendingIds)]);
        DB::table('system_settings')->where('entity', 'best_selling_products')
            ->update(['value' => json_encode(array_slice($featuredIds, 0, 3))]);

        // Clean up old banners that are cluttering the UI with product images
        DB::table('system_settings')->where('entity', 'banner_section_one_banners')->update(['value' => '[]']);
        DB::table('system_settings')->where('entity', 'banner_section_two_banner_one')->update(['value' => null]);
        DB::table('system_settings')->where('entity', 'banner_section_two_banner_two')->update(['value' => null]);
        DB::table('system_settings')->where('entity', 'featured_center_banner')->update(['value' => null]);
        DB::table('system_settings')->where('entity', 'best_deal_banner')->update(['value' => null]);

        // Category sections
        $catIds = DB::table('categories')->pluck('id')->toArray();
        DB::table('system_settings')->where('entity', 'top_category_ids')
            ->update(['value' => json_encode($catIds)]);
        DB::table('system_settings')->where('entity', 'trending_product_categories')
            ->update(['value' => json_encode(array_slice($catIds, 0, 5))]); // Show more categories in filter
        DB::table('system_settings')->where('entity', 'product_listing_categories')
            ->update(['value' => json_encode($catIds)]);
        DB::table('system_settings')->where('entity', 'footer_categories')
            ->update(['value' => json_encode($catIds)]);

        // "You may be interested" section
        DB::table('system_settings')->updateOrInsert(
            ['entity' => 'enable_custom_product_section'],
            ['value'  => '1']
        );
        DB::table('system_settings')->updateOrInsert(
            ['entity' => 'custom_section_products_title'],
            ['value'  => 'You May Be Interested']
        );
        DB::table('system_settings')->updateOrInsert(
            ['entity' => 'custom_section_products_sub_title'],
            ['value'  => 'Handpicked products for your daily needs.']
        );
        DB::table('system_settings')->updateOrInsert(
            ['entity' => 'custom_section_products'],
            ['value'  => json_encode(array_slice($allProductIds, 6, 6))]
        );

        $this->command->info('✅  VitalMartSeeder done — ' . count($allProductIds) . ' products, GIMS location (id=' . $locationId . '), logo + preloader registered.');
    }
}
