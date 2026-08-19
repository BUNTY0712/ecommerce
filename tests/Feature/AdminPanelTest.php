<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_dashboard_loads_successfully(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard &amp; Store Analytics', false) || $response->assertSee('Dashboard');
        $response->assertSee('TOTAL REVENUE');
    }

    public function test_admin_can_view_orders_list(): void
    {
        $response = $this->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertSee('Orders Management');
    }

    public function test_admin_can_update_order_status(): void
    {
        // Create an order via DB facade
        $orderId = DB::table('orders')->insertGetId([
            'order_number' => 'ORD-TEST1234',
            'customer_name' => 'Alice Admin',
            'email' => 'alice@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'pincode' => '110001',
            'country' => 'India',
            'subtotal' => 1000,
            'shipping_charge' => 0,
            'total_amount' => 1000,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post("/admin/orders/{$orderId}/status", [
            'order_status' => 'shipped',
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect();

        $updatedOrder = DB::table('orders')->where('id', $orderId)->first();
        $this->assertEquals('shipped', $updatedOrder->order_status);
        $this->assertEquals('paid', $updatedOrder->payment_status);
    }

    public function test_admin_can_create_new_product(): void
    {
        $category = DB::table('categories')->first();

        $response = $this->post('/admin/products', [
            'name' => 'Super Gaming Laptop',
            'category_id' => $category->id,
            'price' => 75999.00,
            'discount_price' => 69999.00,
            'stock' => 15,
            'short_description' => 'Ultra powerful gaming machine.',
            'description' => 'Detailed laptop specs...',
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = DB::table('products')->where('name', 'Super Gaming Laptop')->first();
        $this->assertNotNull($product);
        $this->assertEquals(75999.00, $product->price);
        $this->assertEquals(15, $product->stock);
    }

    public function test_admin_can_update_product(): void
    {
        $product = DB::table('products')->first();

        $response = $this->put("/admin/products/{$product->id}", [
            'name' => 'Updated Product Title',
            'category_id' => $product->category_id,
            'price' => 999.00,
            'stock' => 50,
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $updated = DB::table('products')->where('id', $product->id)->first();
        $this->assertEquals('Updated Product Title', $updated->name);
        $this->assertEquals(999.00, $updated->price);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = DB::table('products')->first();

        $response = $this->delete("/admin/products/{$product->id}");

        $response->assertRedirect(route('admin.products.index'));

        $deleted = DB::table('products')->where('id', $product->id)->first();
        $this->assertNull($deleted);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->post('/admin/categories', [
            'name' => 'Smart Home Gear',
            'description' => 'Connected home automation devices.',
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = DB::table('categories')->where('name', 'Smart Home Gear')->first();
        $this->assertNotNull($category);
        $this->assertEquals('smart-home-gear', $category->slug);
    }
}
