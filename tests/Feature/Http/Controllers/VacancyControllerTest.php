<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Vacancy;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index displays view', function (): void {
    $vacancies = Vacancy::factory()->count(3)->create();

    $response = get(route('vacancies.index'));

    $response->assertOk();
    $response->assertViewIs('vacancy.index');
    $response->assertViewHas('vacancies');
});


test('create displays view', function (): void {
    $response = get(route('vacancies.create'));

    $response->assertOk();
    $response->assertViewIs('vacancy.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\VacancyController::class,
        'store',
        \App\Http\Requests\VacancyStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $response = post(route('vacancies.store'));

    $response->assertRedirect(route('vacancies.index'));
    $response->assertSessionHas('vacancy.id', $vacancy->id);

    assertDatabaseHas(vacancies, [ /* ... */ ]);
});


test('show displays view', function (): void {
    $vacancy = Vacancy::factory()->create();

    $response = get(route('vacancies.show', $vacancy));

    $response->assertOk();
    $response->assertViewIs('vacancy.show');
    $response->assertViewHas('vacancy');
});


test('edit displays view', function (): void {
    $vacancy = Vacancy::factory()->create();

    $response = get(route('vacancies.edit', $vacancy));

    $response->assertOk();
    $response->assertViewIs('vacancy.edit');
    $response->assertViewHas('vacancy');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\VacancyController::class,
        'update',
        \App\Http\Requests\VacancyUpdateRequest::class
    );

test('update redirects', function (): void {
    $vacancy = Vacancy::factory()->create();

    $response = put(route('vacancies.update', $vacancy));

    $vacancy->refresh();

    $response->assertRedirect(route('vacancies.index'));
    $response->assertSessionHas('vacancy.id', $vacancy->id);
});


test('destroy deletes and redirects', function (): void {
    $vacancy = Vacancy::factory()->create();

    $response = delete(route('vacancies.destroy', $vacancy));

    $response->assertRedirect(route('vacancies.index'));

    assertSoftDeleted($vacancy);
});
