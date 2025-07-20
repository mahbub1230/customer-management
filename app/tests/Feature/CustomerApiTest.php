<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Contact;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers()
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function test_can_create_customer()
    {
        $data = [
            'name' => 'Customer A',
            'reference' => 'CUSTA',
            'category' => 'Gold',
            'start_date' => '2025-07-20',
            'description' => 'Test customer',
        ];

        $response = $this->postJson('/api/customers', $data);

        $response->assertStatus(201)->assertJsonFragment(['name' => 'Customer A']);
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_can_update_customer()
    {
        $customer = Customer::factory()->create();

        $data = [
            'name' => 'Updated Customer',
            'reference' => 'REF123',
            'category' => 'Gold'
        ];


        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(200)->assertJsonFragment($data);
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_can_list_contacts_for_customer()
    {
        $customer = Customer::factory()->create();
        Contact::factory()->count(2)->create(['customer_id' => $customer->id]);

        $response = $this->getJson("/api/customers/{$customer->id}/contacts");

        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_can_create_contact_for_customer()
    {
        $customer = Customer::factory()->create();

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        $response = $this->postJson("/api/customers/{$customer->id}/contacts", $data);

        $response->assertStatus(201)->assertJsonFragment(['first_name' => 'John']);
        $this->assertDatabaseHas('contacts', ['customer_id' => $customer->id, 'first_name' => 'John']);
    }

    public function test_can_update_contact()
    {
        $contact = Contact::factory()->create();

        $data = [
            'first_name' => 'John',
            'last_name' => 'Smith'
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $data);

        $response->assertStatus(200)->assertJsonFragment($data);
        $this->assertDatabaseHas('contacts', $data);
    }
}
