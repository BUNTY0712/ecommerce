<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EcommerceFlowTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_product_listing_page_loads_with_products(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Our Products Catalog');
        $response->assertSee('Electronics');
    }

    public function test_product_detail_page_loads(): void
    {
        $product = DB::table('products')->first();

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_can_add_product_to_cart_and_view_cart(): void
    {
        $product = DB::table('products')->first();

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();

        $cartResponse = $this->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($product->name);
        $cartResponse->assertSee('Shopping Cart');
    }

    public function test_can_complete_shipping_details_form(): void
    {
        $product = DB::table('products')->first();
        $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->post('/checkout/shipping', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '9876543210',
            'address' => '123 Tech Park',
            'address_line_2' => 'Suite 404',
            'city' => 'Bangalore',
            'state' => 'Karnataka',
            'pincode' => '560001',
            'country' => 'India',
        ]);

        $response->assertRedirect(route('checkout.payment'));
        $this->assertEquals('Jane', session('checkout_shipping.first_name'));
    }

    public function test_can_place_cash_on_delivery_order_and_decrement_stock(): void
    {
        $product = DB::table('products')->where('stock', '>', 5)->first();
        $initialStock = $product->stock;

        // Add to cart
        $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);

        // Submit shipping
        $this->post('/checkout/shipping', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '9988776655',
            'address' => '456 Innovation Way',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
            'country' => 'India',
        ]);

        // Place COD order
        $response = $this->post('/checkout/payment', [
            'payment_method' => 'cod',
        ]);

        $order = DB::table('orders')->where('email', 'john.doe@example.com')->first();

        $this->assertNotNull($order);
        $this->assertEquals('cod', $order->payment_method);
        $this->assertEquals('cod', $order->payment_status);

        $response->assertRedirect(route('order.success', ['order' => $order->id]));

        // Verify order item created
        $orderItem = DB::table('order_items')->where('order_id', $order->id)->first();
        $this->assertEquals($product->id, $orderItem->product_id);
        $this->assertEquals(2, $orderItem->quantity);

        // Verify stock decremented
        $updatedProduct = DB::table('products')->where('id', $product->id)->first();
        $this->assertEquals($initialStock - 2, $updatedProduct->stock);
    }
}
