<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Gloudemans\Shoppingcart\Facades\Cart;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'user_name' => 'admin',
            'email' => 'admin@nouniestore.com',
            'phone_number' => '+1234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Manager User
        User::create([
            'first_name' => 'Manager',
            'last_name' => 'User',
            'user_name' => 'manager',
            'email' => 'manager@nouniestore.com',
            'phone_number' => '+1234567891',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            [
                'name' => ['en' => 'Electronics', 'ar' => 'إلكترونيات', 'fr' => 'Électronique'],
                'slug' => 'electronics',
                'description' => ['en' => 'Latest electronic devices and gadgets', 'ar' => 'أحدث الأجهزة الإلكترونية', 'fr' => 'Derniers appareils électroniques'],
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Fashion', 'ar' => 'موضة', 'fr' => 'Mode'],
                'slug' => 'fashion',
                'description' => ['en' => 'Trendy clothing and accessories', 'ar' => 'ملابس وإكسسوارات عصرية', 'fr' => 'Vêtements et accessoires tendance'],
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Home & Garden', 'ar' => 'المنزل والحديقة', 'fr' => 'Maison & Jardin'],
                'slug' => 'home-garden',
                'description' => ['en' => 'Everything for your home and garden', 'ar' => 'كل ما تحتاجه للمنزل والحديقة', 'fr' => 'Tout pour votre maison et jardin'],
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Sports & Outdoors', 'ar' => 'رياضة وأنشطة خارجية', 'fr' => 'Sports & Plein air'],
                'slug' => 'sports-outdoors',
                'description' => ['en' => 'Sports equipment and outdoor gear', 'ar' => 'معدات رياضية وأدوات خارجية', 'fr' => 'Équipements sportifs et de plein air'],
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Books & Media', 'ar' => 'كتب ووسائط', 'fr' => 'Livres & Médias'],
                'slug' => 'books-media',
                'description' => ['en' => 'Books, movies, and music', 'ar' => 'كتب وأفلام وموسيقى', 'fr' => 'Livres, films et musique'],
                'is_active' => true,
            ],
            [
                'name' => ['en' => 'Toys & Games', 'ar' => 'ألعاب وترفيه', 'fr' => 'Jouets & Jeux'],
                'slug' => 'toys-games',
                'description' => ['en' => 'Fun toys and games for all ages', 'ar' => 'ألعاب ممتعة لجميع الأعمار', 'fr' => 'Jouets et jeux amusants pour tous les âges'],
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Products
        $products = [
            // Electronics
            [
                'category_id' => 1,
                'name' => ['en' => 'Wireless Headphones', 'ar' => 'سماعات لاسلكية', 'fr' => 'Casque sans fil'],
                'description' => ['en' => 'Premium wireless headphones with noise cancellation and long battery life', 'ar' => 'سماعات لاسلكية فاخرة مع إلغاء الضوضاء وعمر بطارية طويل', 'fr' => 'Casque sans fil haut de gamme avec réduction de bruit et longue autonomie'],
                'slug' => 'wireless-headphones',
                'sku' => 'ELEC-WH-001',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => ['en' => 'Smartphone Case', 'ar' => 'حافظة هاتف ذكي', 'fr' => 'Étui pour smartphone'],
                'description' => ['en' => 'Durable protective case for your smartphone', 'ar' => 'حافظة واقية متينة لهاتفك الذكي', 'fr' => 'Étui de protection durable pour votre smartphone'],
                'slug' => 'smartphone-case',
                'sku' => 'ELEC-SC-002',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => ['en' => 'Portable Charger', 'ar' => 'شاحن محمول', 'fr' => 'Chargeur portable'],
                'description' => ['en' => '20000mAh power bank with fast charging', 'ar' => 'بنك طاقة 20000 مللي أمبير مع شحن سريع', 'fr' => 'Batterie externe 20000mAh avec charge rapide'],
                'slug' => 'portable-charger',
                'sku' => 'ELEC-PC-003',
                'is_active' => true,
            ],
            // Fashion
            [
                'category_id' => 2,
                'name' => ['en' => 'Cotton T-Shirt', 'ar' => 'قميص قطني', 'fr' => 'T-shirt en coton'],
                'description' => ['en' => '100% organic cotton t-shirt, comfortable and stylish', 'ar' => 'قميص قطن عضوي 100٪، مريح وأنيق', 'fr' => 'T-shirt 100% coton biologique, confortable et élégant'],
                'slug' => 'cotton-tshirt',
                'sku' => 'FASH-TS-004',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => ['en' => 'Denim Jeans', 'ar' => 'جينز دنيم', 'fr' => 'Jean en denim'],
                'description' => ['en' => 'Classic blue denim jeans with perfect fit', 'ar' => 'جينز دنيم أزرق كلاسيكي بقصة مثالية', 'fr' => 'Jean en denim bleu classique avec coupe parfaite'],
                'slug' => 'denim-jeans',
                'sku' => 'FASH-DJ-005',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => ['en' => 'Leather Wallet', 'ar' => 'محفظة جلدية', 'fr' => 'Portefeuille en cuir'],
                'description' => ['en' => 'Genuine leather wallet with multiple card slots', 'ar' => 'محفظة جلد طبيعي مع فتحات متعددة للبطاقات', 'fr' => 'Portefeuille en cuir véritable avec plusieurs emplacements pour cartes'],
                'slug' => 'leather-wallet',
                'sku' => 'FASH-LW-006',
                'is_active' => true,
            ],
            // Home & Garden
            [
                'category_id' => 3,
                'name' => ['en' => 'Coffee Maker', 'ar' => 'صانعة قهوة', 'fr' => 'Cafetière'],
                'description' => ['en' => 'Automatic coffee maker with programmable timer', 'ar' => 'صانعة قهوة أوتوماتيكية مع مؤقت قابل للبرمجة', 'fr' => 'Cafetière automatique avec minuterie programmable'],
                'slug' => 'coffee-maker',
                'sku' => 'HOME-CM-007',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => ['en' => 'Indoor Plant Pot', 'ar' => 'أصيص نباتات داخلي', 'fr' => 'Pot de plante d\'intérieur'],
                'description' => ['en' => 'Modern ceramic plant pot for indoor plants', 'ar' => 'أصيص سيراميك عصري للنباتات الداخلية', 'fr' => 'Pot de plante en céramique moderne pour plantes d\'intérieur'],
                'slug' => 'indoor-plant-pot',
                'sku' => 'HOME-PP-008',
                'is_active' => true,
            ],
            // Sports
            [
                'category_id' => 4,
                'name' => ['en' => 'Yoga Mat', 'ar' => 'سجادة يوغا', 'fr' => 'Tapis de yoga'],
                'description' => ['en' => 'Non-slip yoga mat with carrying strap', 'ar' => 'سجادة يوغا غير قابلة للانزلاق مع حزام حمل', 'fr' => 'Tapis de yoga antidérapant avec sangle de transport'],
                'slug' => 'yoga-mat',
                'sku' => 'SPRT-YM-009',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => ['en' => 'Water Bottle', 'ar' => 'زجاجة ماء', 'fr' => 'Bouteille d\'eau'],
                'description' => ['en' => 'Insulated stainless steel water bottle, 750ml', 'ar' => 'زجاجة ماء من الفولاذ المقاوم للصدأ معزولة، 750 مل', 'fr' => 'Bouteille d\'eau en acier inoxydable isolée, 750ml'],
                'slug' => 'water-bottle',
                'sku' => 'SPRT-WB-010',
                'is_active' => true,
            ],
            // Books
            [
                'category_id' => 5,
                'name' => ['en' => 'Programming Book', 'ar' => 'كتاب برمجة', 'fr' => 'Livre de programmation'],
                'description' => ['en' => 'Comprehensive guide to modern web development', 'ar' => 'دليل شامل لتطوير الويب الحديث', 'fr' => 'Guide complet du développement web moderne'],
                'slug' => 'programming-book',
                'sku' => 'BOOK-PB-011',
                'is_active' => true,
            ],
            // Toys
            [
                'category_id' => 6,
                'name' => ['en' => 'Building Blocks Set', 'ar' => 'مجموعة مكعبات بناء', 'fr' => 'Ensemble de blocs de construction'],
                'description' => ['en' => 'Creative building blocks set with 500 pieces', 'ar' => 'مجموعة مكعبات بناء إبداعية مكونة من 500 قطعة', 'fr' => 'Ensemble de blocs de construction créatifs avec 500 pièces'],
                'slug' => 'building-blocks-set',
                'sku' => 'TOYS-BB-012',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Add product images from Picsum
            $imageCount = rand(1, 4); // Random 1-4 images per product
            for ($i = 1; $i <= $imageCount; $i++) {
                $imageId = 100 + ($product->id * 10) + $i; // Unique image ID
                $imageUrl = "https://picsum.photos/seed/{$imageId}/800/800";
                
                try {
                    $media = $product->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('products');
                    
                    // Also add to images table for reference
                    Image::create([
                        'product_id' => $product->id,
                        'variant_id' => null,
                        'name' => "Product Image {$i}",
                        'url' => $media->getUrl(),
                        'order' => $i,
                    ]);
                } catch (\Exception $e) {
                    // Silently continue if image download fails
                    continue;
                }
            }

            // Create variants for each product
            $variantPrices = [
                1 => [99.99, 149.99, 199.99],
                2 => [19.99, 24.99],
                3 => [45.99],
                4 => [29.99],
                5 => [59.99, 79.99],
                6 => [39.99],
                7 => [89.99],
                8 => [24.99, 34.99],
                9 => [35.99],
                10 => [22.99],
                11 => [34.99],
                12 => [49.99],
            ];

            $colors = ['Black', 'White', 'Blue', 'Red', 'Green'];
            $sizes = ['S', 'M', 'L', 'XL'];

            foreach ($variantPrices[$product->id] ?? [29.99] as $index => $price) {
                $variantName = $colors[$index % count($colors)] ?? 'Default';
                
                Variant::create([
                    'product_id' => $product->id,
                    'name' => ['en' => $variantName, 'ar' => $variantName, 'fr' => $variantName],
                    'sku' => 'SKU-' . strtoupper(substr($product->slug, 0, 3)) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'price' => $price,
                    'compare_at_price' => $index === 0 ? $price * 1.2 : null, // 20% off for first variant
                    'discount_amount' => $price * 0.1, // 10% discount
                    'quantity' => rand(10, 100),
                    'stock' => rand(0, 50), // Some products might be out of stock
                    'is_active' => true,
                ]);
            }
        }

        // Create Services
        $services = [
            [
                'title' => ['en' => 'Product Consultation', 'ar' => 'استشارة المنتج', 'fr' => 'Consultation produit'],
                'slug' => ['en' => 'product-consultation', 'ar' => 'استشارة-المنتج', 'fr' => 'consultation-produit'],
                'description' => ['en' => 'Get expert advice on choosing the right products for your needs', 'ar' => 'احصل على مشورة الخبراء في اختيار المنتجات المناسبة لاحتياجاتك', 'fr' => 'Obtenez des conseils d\'experts pour choisir les bons produits'],
                'price' => 50.00,
                'duration' => 30,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'Personal Shopping', 'ar' => 'تسوق شخصي', 'fr' => 'Shopping personnel'],
                'slug' => ['en' => 'personal-shopping', 'ar' => 'تسوق-شخصي', 'fr' => 'shopping-personnel'],
                'description' => ['en' => 'Let our experts curate a personalized shopping experience for you', 'ar' => 'دع خبرائنا يقومون بتنسيق تجربة تسوق شخصية لك', 'fr' => 'Laissez nos experts créer une expérience de shopping personnalisée'],
                'price' => 75.00,
                'duration' => 60,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'Tech Support', 'ar' => 'دعم تقني', 'fr' => 'Support technique'],
                'slug' => ['en' => 'tech-support', 'ar' => 'دعم-تقني', 'fr' => 'support-technique'],
                'description' => ['en' => 'Technical support and troubleshooting for your electronic devices', 'ar' => 'الدعم الفني وحل المشاكل لأجهزتك الإلكترونية', 'fr' => 'Support technique et dépannage pour vos appareils électroniques'],
                'price' => 40.00,
                'duration' => 45,
                'is_active' => true,
            ],
            [
                'title' => ['en' => 'Home Styling', 'ar' => 'تنسيق منزلي', 'fr' => 'Décoration d\'intérieur'],
                'slug' => ['en' => 'home-styling', 'ar' => 'تنسيق-منزلي', 'fr' => 'decoration-interieur'],
                'description' => ['en' => 'Professional home styling and decoration advice', 'ar' => 'نصائح احترافية لتنسيق وديكور المنزل', 'fr' => 'Conseils professionnels en décoration d\'intérieur'],
                'price' => 100.00,
                'duration' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        // Create Customers
        for ($i = 1; $i <= 10; $i++) {
            Customer::create([
                'first_name' => 'Customer' . $i,
                'last_name' => 'Test',
                'email' => 'customer' . $i . '@example.com',
                'phone_number' => '+1234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'address_line1' => $i . ' Test Street',
                'address_line2' => 'Apartment ' . $i,
                'city' => 'Test City',
                'country' => 'Test Country',
            ]);
        }

        // Create Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'discount_percentage' => 10,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE20',
            'discount_percentage' => 20,
            'start_date' => now(),
            'end_date' => now()->addMonths(2),
            'is_active' => true,
        ]);

        // Create Orders
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        for ($i = 1; $i <= 20; $i++) {
            $customer = Customer::inRandomOrder()->first();
            $status = $statuses[array_rand($statuses)];
            
            $order = Order::create([
                'customer_id' => $customer->id,
                'code' => 'ORD-' . strtoupper(uniqid()),
                'status' => $status,
                'total_amount' => 0,
                'discount_percentage' => rand(0, 20),
                'paid_at' => $status === 'delivered' ? now() : null,
            ]);

            // Create order items
            $itemCount = rand(1, 4);
            $totalAmount = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $variant = Variant::inRandomOrder()->first();
                $quantity = rand(1, 3);
                $itemTotal = $variant->price * $quantity;
                $totalAmount += $itemTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $variant->price,
                ]);
            }

            // Apply discount
            $discountedAmount = $totalAmount * (1 - ($order->discount_percentage / 100));

            $order->update([
                'total_amount' => $discountedAmount,
            ]);
        }

        // Add items to cart for testing payment methods
        // Set instance identifier to 'admin' user email for persistent cart
        Cart::instance('default');
        
        // Get some random variants to add to cart
        $testVariants = Variant::with('product')->inRandomOrder()->take(3)->get();
        
        foreach ($testVariants as $variant) {
            $product = $variant->product;
            
            Cart::add([
                'id' => $variant->id,
                'name' => $product->name['en'] ?? 'Product',
                'qty' => rand(1, 2),
                'price' => $variant->price,
                'weight' => 0,
                'options' => [
                    'product_id' => $product->id,
                    'product_slug' => $product->slug,
                    'image' => $product->images()->first()?->url ?? null,
                    'variant_name' => $variant->name['en'] ?? null,
                    'stock' => $variant->quantity ?? 10,
                    'category' => $product->category?->name['en'] ?? 'General',
                ]
            ]);
        }
        
        // Store cart to database with a test identifier
        Cart::store('test_user_cart');

        $this->command->info('Database seeded successfully with fake data!');
        $this->command->info('Admin credentials: admin@nouniestore.com / password');
        $this->command->info('Manager credentials: manager@nouniestore.com / password');
        $this->command->info('Cart seeded with ' . Cart::count() . ' items for testing payment methods!');
    }
}

