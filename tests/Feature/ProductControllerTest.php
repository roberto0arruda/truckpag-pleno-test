<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_index_action_should_return_a_list_of_products(): void
    {
        Product::factory()->count(30)->create();

        $response = $this->getJson('/products');

        $response->assertOk();

        $response->assertJsonCount(15, 'data');
    }

    public function test_pagination_returns_correct_number_of_items_per_page(): void
    {
        Product::factory()->count(30)->create();

        $response = $this->getJson('/products?limit=10&page=1');

        $response->assertOk();

        $response->assertJsonCount(10, 'data');

        $response->assertJsonFragment([
            'current_page' => 1,
            'per_page' => 10,
            'total' => 30,
            'last_page' => 3
        ]);
    }

    public function test_pagination_navigates_correctly_between_pages(): void
    {
        Product::factory()->count(25)->create();

        $responsePage1 = $this->getJson('/products?limit=5&page=1');
        $responsePage1->assertStatus(200);
        $responsePage1->assertJsonCount(5, 'data');
        $responsePage1->assertJsonFragment(['current_page' => 1]);

        $responsePage2 = $this->getJson('/products?limit=5&page=2');
        $responsePage2->assertStatus(200);
        $responsePage2->assertJsonCount(5, 'data');
        $responsePage2->assertJsonFragment(['current_page' => 2]);

        $responsePage5 = $this->getJson('/products?limit=5&page=5');
        $responsePage5->assertStatus(200);
        $responsePage5->assertJsonCount(5, 'data');
        $responsePage5->assertJsonFragment(['current_page' => 5]);
    }

    public function test_the_show_action_should_return_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/products/' . $product->code);

        $response->assertJsonStructure([
            'id',
            'product_name',
            'brands',
        ])->assertJson([
            'id' => $product->id,
            'product_name' => $product->product_name,
            'code' => $product->code,
        ]);
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::factory()->create([
            'status' => 'published'
        ]);

        $this->assertEquals('published', $product->status);

        $response = $this->deleteJson("/products/{$product->code}");

        $response->assertStatus(204);

        $fromDatabase = Product::find($product->id);

        $this->assertEquals('trash', $fromDatabase->status);
    }
}
