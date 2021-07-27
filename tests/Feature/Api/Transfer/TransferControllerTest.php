<?php

namespace Tests\Feature\Matches;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
}