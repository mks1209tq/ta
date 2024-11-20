<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index displays view', function (): void {
    $documents = Document::factory()->count(3)->create();

    $response = get(route('documents.index'));

    $response->assertOk();
    $response->assertViewIs('document.index');
    $response->assertViewHas('documents');
});


test('create displays view', function (): void {
    $response = get(route('documents.create'));

    $response->assertOk();
    $response->assertViewIs('document.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\DocumentController::class,
        'store',
        \App\Http\Requests\DocumentStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $response = post(route('documents.store'));

    $response->assertRedirect(route('documents.index'));
    $response->assertSessionHas('document.id', $document->id);

    assertDatabaseHas(documents, [ /* ... */ ]);
});


test('show displays view', function (): void {
    $document = Document::factory()->create();

    $response = get(route('documents.show', $document));

    $response->assertOk();
    $response->assertViewIs('document.show');
    $response->assertViewHas('document');
});


test('edit displays view', function (): void {
    $document = Document::factory()->create();

    $response = get(route('documents.edit', $document));

    $response->assertOk();
    $response->assertViewIs('document.edit');
    $response->assertViewHas('document');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\DocumentController::class,
        'update',
        \App\Http\Requests\DocumentUpdateRequest::class
    );

test('update redirects', function (): void {
    $document = Document::factory()->create();

    $response = put(route('documents.update', $document));

    $document->refresh();

    $response->assertRedirect(route('documents.index'));
    $response->assertSessionHas('document.id', $document->id);
});


test('destroy deletes and redirects', function (): void {
    $document = Document::factory()->create();

    $response = delete(route('documents.destroy', $document));

    $response->assertRedirect(route('documents.index'));

    assertSoftDeleted($document);
});
