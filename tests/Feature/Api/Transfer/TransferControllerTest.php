<?php

namespace Tests\Feature\Api\Transfer;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Transfer\TransferMoneyJob;
use App\Jobs\Transfer\SendNotificationTransferJob;
use App\Models\Transfer;

class TransferControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * @dataProvider getRequiredFields
     *
     * @param string $field
     * @param string $fieldName
     *
    */
    public function test_required_fields($field, $fieldName)
    {
        $this->post(route('api.transfer'), [])
             ->assertJson([
                $field  => [__('validation.required', ['attribute' => $fieldName])],
            ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getRequiredFields() : array
    {
        return [
            ['amount'  , 'amount' ],
            ['payee_id', 'payee id'],
            ['payer_id', 'payer id'],
        ];
    }

    /**
     * @dataProvider getNumericFields
     *
     * @param string $field
     * @param string $fieldValue
     * @param string $fieldName
     *
    */
    public function test_numeric_fields($field, $fieldValue, $fieldName)
    {
        $this->post(route('api.transfer'), [
            $field => $fieldValue
        ])->assertJson([
            $field  => [__('validation.numeric', ['attribute' => $fieldName])],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getNumericFields() : array
    {
        return [
            ['payee_id', 'asd' , 'payee id'],
            ['payee_id', 'xxx' , 'payee id'],
            ['payer_id', 'asd' , 'payer id'],
            ['payer_id', 'xxx' , 'payer id'],
        ];
    }

    /**
     * @dataProvider getExistsFields
     *
     * @param string $field
     * @param string $fieldValue
     * @param string $fieldName
     *
    */
    public function test_exists_fields($field, $fieldValue, $fieldName)
    {
        $this->post(route('api.transfer'), [
            $field => $fieldValue
        ])->assertJson([
            $field  => [__('validation.exists', ['attribute' => $fieldName])],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getExistsFields() : array
    {
        return [
            ['payee_id', rand(1000,9999) , 'payee id'],
            ['payer_id', rand(1000,9999) , 'payer id'],
        ];
    }

    public function test_valid_amount_field()
    {
        $invalidAmount = User::first()->getAmount() + 10;
        $this->post(route('api.transfer'), [
            'amount' => $invalidAmount
        ])->assertJson([
            'amount'  => [__('Your balance is insufficient for this transaction')],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_payee_and_payer_different()
    {
        $user = User::first();
        $user->update(['shopkeeper' => false]);

        $this->post(route('api.transfer'), [
            'payer_id' => 1,
            'payee_id' => 1,
        ])->assertJson([
            'payee_id'  => [__('validation.different', ['attribute' => 'payee id', 'other' => 'payer id'])],
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_is_shopkeeper_middleware()
    {
        $user = User::first();
        $user->update(['shopkeeper' => true]);

        $this->post(route('api.transfer'), [
            'payer_id' => $user->id
        ])->assertJson([
            'message' => __('You are not allowed to access this route')
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_it_should_be_able_to_process_a_transfer()
    {
        Queue::fake();
        $user = User::first();
        $user->update(['shopkeeper' => false]);

        $this->post(route('api.transfer'), [
            'amount'   => 100,
            'payer_id' => 1,
            'payee_id' => 2
        ])->assertJson([
            'message' => __('Your transfer is being processed !')
        ])->assertStatus(Response::HTTP_OK);
        Queue::assertPushed(TransferMoneyJob::class);
        Queue::assertPushed(TransferMoneyJob::class, 1);
        Queue::assertPushedWithChain(TransferMoneyJob::class, [
            SendNotificationTransferJob::class,
        ]);
    }
}