<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure storage product directory exists
        $storageDir = storage_path('app/public/products');
        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        // Fetch category map
        $categories = DB::table('categories')->pluck('id', 'slug');

        $productsData = [
            // Electronics (cat 1)
            [
                'category_slug' => 'electronics',
                'name' => 'Wireless Noise Canceling Headphones',
                'short_description' => 'Immersive spatial audio with active noise cancellation and 30-hour battery life.',
                'description' => "Experience premium high-fidelity audio with our Wireless Noise Canceling Headphones. Featuring custom-tuned 40mm acoustic drivers, ultra-soft memory foam ear cushions, dual beamforming microphones for crystal-clear calls, and fast Bluetooth 5.3 connectivity. Enjoy up to 30 hours of continuous playback on a single charge.",
                'price' => 4999.00,
                'discount_price' => 3499.00,
                'stock' => 25,
                'icon' => '🎧',
                'bg_color' => '#4f46e5',
            ],
            [
                'category_slug' => 'electronics',
                'name' => 'Smart Fitness Watch Series 5',
                'short_description' => 'AMOLED touch display, heart rate sensor, GPS tracking, and 50m water resistance.',
                'description' => "Track your health and workout goals with the Smart Fitness Watch Series 5. Features a crisp 1.43-inch HD AMOLED screen, continuous SpO2 and heart rate monitoring, built-in dual-band GPS, 100+ sports modes, and up to 14 days of battery longevity. Compatible with iOS and Android.",
                'price' => 2999.00,
                'discount_price' => 2299.00,
                'stock' => 40,
                'icon' => '⌚',
                'bg_color' => '#0ea5e9',
            ],
            [
                'category_slug' => 'electronics',
                'name' => 'Portable Bluetooth Speaker 20W',
                'short_description' => 'Heavy bass sound, IPX7 waterproof casing, and TWS stereo pairing.',
                'description' => "Take your party anywhere with our 20W Portable Bluetooth Speaker. Equipped with passive radiators for deep bass, an IPX7 fully waterproof aluminum housing, RGB ambient light sync, and up to 18 hours of playtime. Connect two speakers together for true wireless stereo.",
                'price' => 1999.00,
                'discount_price' => 1499.00,
                'stock' => 15,
                'icon' => '🔊',
                'bg_color' => '#8b5cf6',
            ],
            [
                'category_slug' => 'electronics',
                'name' => 'Ultra HD 4K Streaming Web Camera',
                'short_description' => '4K 30fps video resolution, autofocus glass lens, and integrated privacy cover.',
                'description' => "Look professional in meetings and live streams. This 4K Web Camera features auto low-light correction, dual noise-canceling stereo mics, plug-and-play USB connection, and a 90-degree field of view.",
                'price' => 3499.00,
                'discount_price' => 2799.00,
                'stock' => 18,
                'icon' => '📷',
                'bg_color' => '#10b981',
            ],

            // Fashion (cat 2)
            [
                'category_slug' => 'fashion',
                'name' => 'Classic Cotton Oxford Shirt',
                'short_description' => '100% breathable organic cotton, button-down collar, regular fit.',
                'description' => "A timeless classic for casual and formal wardrobes. Crafted from 100% premium long-staple organic cotton, pre-shrunk, soft to the touch, and tailored with double-needle stitching for lasting durability.",
                'price' => 1499.00,
                'discount_price' => 999.00,
                'stock' => 50,
                'icon' => '👔',
                'bg_color' => '#2563eb',
            ],
            [
                'category_slug' => 'fashion',
                'name' => 'Lightweight Running Sneakers',
                'short_description' => 'Breathable mesh upper, high-rebound EVA sole, lightweight cushioning.',
                'description' => "Engineered for maximum comfort and speed. These running sneakers feature an engineered air mesh upper, shock-absorbing EVA foam midsole, non-slip rubber tread, and ergonomic footbed support.",
                'price' => 2499.00,
                'discount_price' => 1799.00,
                'stock' => 30,
                'icon' => '👟',
                'bg_color' => '#f97316',
            ],
            [
                'category_slug' => 'fashion',
                'name' => 'Urban Denim Jacket Premium',
                'short_description' => 'Vintage wash denim, brass button closures, double chest pockets.',
                'description' => "Upgrade your casual layer with this premium urban denim jacket. Built from heavy-duty 12oz cotton denim with classic contrast stitching, reinforced seams, and adjustable button waist tabs.",
                'price' => 2999.00,
                'discount_price' => 2199.00,
                'stock' => 20,
                'icon' => '🧥',
                'bg_color' => '#475569',
            ],
            [
                'category_slug' => 'fashion',
                'name' => 'Slim Fit Chino Pants Navy',
                'short_description' => 'Stretch cotton twill fabric, sleek slim fit, wrinkle-resistant finish.',
                'description' => "Versatile slim fit chinos designed to transition smoothly from office to evening out. Features 98% cotton with 2% elastane for comfortable 4-way stretch.",
                'price' => 1799.00,
                'discount_price' => 1299.00,
                'stock' => 35,
                'icon' => '👖',
                'bg_color' => '#1e293b',
            ],

            // Home & Kitchen (cat 3)
            [
                'category_slug' => 'home-kitchen',
                'name' => 'High-Speed Blender 1200W',
                'short_description' => 'Multi-speed blender with stainless steel 6-point blades and 2L BPA-free pitcher.',
                'description' => "Pulverize ice, frozen fruit, and tough ingredients in seconds. Features a powerful 1200W motor, variable speed dial, pulse control, and pre-programmed smoothie settings.",
                'price' => 3999.00,
                'discount_price' => 2999.00,
                'stock' => 12,
                'icon' => '🍹',
                'bg_color' => '#ef4444',
            ],
            [
                'category_slug' => 'home-kitchen',
                'name' => 'Non-Stick Ceramic Cookware Set 5-Piece',
                'short_description' => 'Non-toxic ceramic coating, induction compatible, heat-resistant handles.',
                'description' => "Cook healthy meals effortlessly with 100% PFOA and PTFE-free ceramic cookware. Includes frying pan, saucepan with glass lid, and casserole pot with stay-cool silicone handles.",
                'price' => 4599.00,
                'discount_price' => 3499.00,
                'stock' => 10,
                'icon' => '🍳',
                'bg_color' => '#d97706',
            ],
            [
                'category_slug' => 'home-kitchen',
                'name' => 'Smart Touch Electric Kettle 1.7L',
                'short_description' => 'Digital temperature presets, double-wall stainless steel, auto shut-off.',
                'description' => "Brew your tea or coffee at exact ideal temperatures. Features 5 preset temperature modes, keep-warm function, cool-touch exterior, and rapid 1500W boil speed.",
                'price' => 2199.00,
                'discount_price' => 1699.00,
                'stock' => 22,
                'icon' => '🫖',
                'bg_color' => '#059669',
            ],
            [
                'category_slug' => 'home-kitchen',
                'name' => 'Ergonomic Memory Foam Bed Pillow',
                'short_description' => 'Contoured neck support, cooling gel bamboo cover, hypoallergenic.',
                'description' => "Say goodbye to morning neck pain. High-density contoured memory foam adapts to your sleeping position for optimal spinal alignment. Removable machine-washable bamboo cover.",
                'price' => 1299.00,
                'discount_price' => 899.00,
                'stock' => 45,
                'icon' => '🛏️',
                'bg_color' => '#64748b',
            ],

            // Beauty (cat 4)
            [
                'category_slug' => 'beauty',
                'name' => 'Hydrating Vitamin C Facial Serum 30ml',
                'short_description' => 'Brightens skin tone, reduces fine lines, enriched with hyaluronic acid.',
                'description' => "Revitalize your skin with 20% pure Vitamin C and Ferulic Acid serum. Fades dark spots, boosts collagen synthesis, and provides antioxidant protection against urban pollution.",
                'price' => 999.00,
                'discount_price' => 699.00,
                'stock' => 60,
                'icon' => '🧴',
                'bg_color' => '#ec4899',
            ],
            [
                'category_slug' => 'beauty',
                'name' => 'Luxury Eau De Parfum Unisex 100ml',
                'short_description' => 'Notes of amberwood, bergamot, cedar, and subtle white musk.',
                'description' => "A captivating scent that lingers all day. Crafted in France with natural botanical extracts and essential oils. Housed in a heavy glass spray bottle.",
                'price' => 3499.00,
                'discount_price' => 2499.00,
                'stock' => 14,
                'icon' => '✨',
                'bg_color' => '#a855f7',
            ],
            [
                'category_slug' => 'beauty',
                'name' => 'Sonic Facial Cleansing Brush',
                'short_description' => 'Medical-grade silicone bristles, 8 vibration speeds, waterproof design.',
                'description' => "Deeply cleanse pores and remove 99.5% of dirt, oil, and makeup residue. Ultra-hygienic silicone touchpoints gently exfoliate without causing skin irritation.",
                'price' => 1599.00,
                'discount_price' => 1199.00,
                'stock' => 28,
                'icon' => '🧼',
                'bg_color' => '#f43f5e',
            ],
            [
                'category_slug' => 'beauty',
                'name' => 'Organic Argan Hair Repair Oil 100ml',
                'short_description' => 'Cold-pressed Moroccan argan oil, tames frizz and restores shine.',
                'description' => "Nourish dry and damaged hair from roots to ends. Rich in Vitamin E and essential fatty acids to repair split ends and add silkiness without leaving greasy residue.",
                'price' => 899.00,
                'discount_price' => 599.00,
                'stock' => 55,
                'icon' => '🌿',
                'bg_color' => '#84cc16',
            ],

            // Accessories (cat 5)
            [
                'category_slug' => 'accessories',
                'name' => 'Genuine Leather Men\'s Bifold Wallet',
                'short_description' => 'Full-grain leather, RFID blocking technology, 8 card slots.',
                'description' => "Handcrafted from 100% full-grain cowhide leather that develops a rich patina over time. Embedded RFID-blocking layer protects your cards against unauthorized scanning.",
                'price' => 1299.00,
                'discount_price' => 899.00,
                'stock' => 40,
                'icon' => '👛',
                'bg_color' => '#b45309',
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Water Resistant Travel Laptop Backpack',
                'short_description' => 'Fits up to 15.6" laptops, USB charging port, anti-theft back pocket.',
                'description' => "Organize your daily commute or weekend travels. Includes padded laptop compartment, tablet sleeve, luggage strap, and water-repellent oxford fabric shell.",
                'price' => 2499.00,
                'discount_price' => 1799.00,
                'stock' => 30,
                'icon' => '🎒',
                'bg_color' => '#334155',
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Polarized UV400 Aviator Sunglasses',
                'short_description' => 'Stainless steel frame, TAC HD polarized lenses, anti-glare protection.',
                'description' => "Classic aviator silhouette with modern high-definition polarized lenses. Eliminates reflected glare from roads and water while blocking 100% of UVA/UVB rays.",
                'price' => 1499.00,
                'discount_price' => 999.00,
                'stock' => 35,
                'icon' => '🕶️',
                'bg_color' => '#f59e0b',
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Minimalist Stainless Steel Watch',
                'short_description' => 'Japanese quartz movement, 30m water resistance, mesh strap.',
                'description' => "Sleek 40mm ultra-thin stainless steel case with scratch-resistant mineral glass. Adjustable quick-release mesh strap for effortless style.",
                'price' => 3299.00,
                'discount_price' => 2399.00,
                'stock' => 18,
                'icon' => '⌚',
                'bg_color' => '#6b7280',
            ],
        ];

        foreach ($productsData as $index => $item) {
            $slug = Str::slug($item['name']);
            $imageFilename = "products/{$slug}.svg";
            $fullImagePath = storage_path("app/public/{$imageFilename}");

            // Generate clean SVG product placeholder image
            $svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="450" viewBox="0 0 600 450">
    <rect width="600" height="450" fill="{$item['bg_color']}" opacity="0.1"/>
    <rect x="20" y="20" width="560" height="410" rx="20" fill="#ffffff" stroke="{$item['bg_color']}" stroke-width="4"/>
    <circle cx="300" cy="180" r="80" fill="{$item['bg_color']}" opacity="0.15"/>
    <text x="300" y="210" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="80" text-anchor="middle">{$item['icon']}</text>
    <text x="300" y="320" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="24" font-weight="bold" fill="#0f172a" text-anchor="middle">{$item['name']}</text>
    <text x="300" y="360" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="18" font-weight="600" fill="{$item['bg_color']}" text-anchor="middle">₹{$item['discount_price']} (Reg ₹{$item['price']})</text>
</svg>
SVG;
            File::put($fullImagePath, $svgContent);

            $categoryId = $categories[$item['category_slug']] ?? 1;

            DB::table('products')->updateOrInsert(
                ['slug' => $slug],
                [
                    'category_id' => $categoryId,
                    'name' => $item['name'],
                    'slug' => $slug,
                    'short_description' => $item['short_description'],
                    'description' => $item['description'],
                    'image' => $imageFilename,
                    'price' => $item['price'],
                    'discount_price' => $item['discount_price'],
                    'stock' => $item['stock'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
