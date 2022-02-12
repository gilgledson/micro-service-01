<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected $endpoint = "/companies";

    /**
     *  Get all categories
     *
     * @return void
     */
    public function test_get_all_companies()
    {
        Company::factory()->count(6)->create();
        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, "data");
        $response->assertStatus(200);
    }
    /**
     *  Error get single company
     *
     * @return void
     */
    public function test_error_get_single_company()
    {
        $category = "fake-url";
        $response = $this->getJson("{$this->endpoint}/{$category}");
        $response->assertStatus(404);
    }
    /**
     *  Get single company
     *
     * @return void
     */
    public function test_get_single_company()
    {
        $category = Company::factory()->create();
        $response = $this->getJson("{$this->endpoint}/{$category->uuid}");
        $response->assertStatus(200);
    }
    /**
     *  Validation store company
     *
     * @return void
     */
    public function test_validation_store_company()
    {

        $response = $this->postJson($this->endpoint, [
            "category_id" => null,
            "name"        => "",
            "email"       => "",
            "whatsapp"    => "",
        ]);
        $response->assertStatus(422);
    }
    /**
     *  Store company
     *
     * @return void
     */
    public function test_store_company()
    {
        $dataExample = [
            "category_id"  => Category::factory()->create()->id,
            "name"         => "Company name",
            "email"        => "email@email.com",
            "whatsapp"     => 99999999999
        ];

        $response = $this->postJson($this->endpoint,  $dataExample);
        $response->assertStatus(201);
    }
    /**
     *  Update company
     *
     * @return void
     */
    public function test_update_category()
    {
        $company =  Company::factory()->create();
        $dataExample = [
            "category_id"  => Category::factory()->create()->id,
            "name"         => "Company name",
            "email"        => "email@email.com",
            "whatsapp"     => 99999999999
        ];
        $response = $this->putJson("{$this->endpoint}/fake-company", $dataExample);
        $response->assertStatus(404);
        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", []);
        $response->assertStatus(422);
        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", $dataExample);
        $response->assertStatus(200);
    }
    /**
     *  Delete categories
     *
     * @return void
     */
    public function test_delete_category()
    {

        $category =  Company::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/fake-company");
        $response->assertStatus(404);
        $response = $this->deleteJson("{$this->endpoint}/{$category->uuid}");
        $response->assertStatus(204);
    }
}
