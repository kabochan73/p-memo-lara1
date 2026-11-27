<?php

namespace Tests\Feature;

use App\Models\Memo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_メモを作成できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/memos', [
            'title' => '買い物リスト',
            'body' => '牛乳・パン',
        ]);

        $response->assertRedirect(route('memos.index'));
        $this->assertDatabaseHas('memos', [
            'user_id' => $user->id,
            'title' => '買い物リスト',
        ]);
    }

    public function test_タイトル未入力ではメモを作成できない(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/memos', [
            'title' => '',
            'body' => '本文のみ',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('memos', ['body' => '本文のみ']);
    }

    public function test_自分のメモのみ一覧に表示される(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Memo::factory()->for($user)->create(['title' => '自分のメモ']);
        Memo::factory()->for($other)->create(['title' => '他人のメモ']);

        $response = $this->actingAs($user)->get('/memos');

        $response->assertSee('自分のメモ');
        $response->assertDontSee('他人のメモ');
    }

    public function test_キーワードで検索できる(): void
    {
        $user = User::factory()->create();

        Memo::factory()->for($user)->create(['title' => '買い物リスト']);
        Memo::factory()->for($user)->create(['title' => '読書メモ']);

        $response = $this->actingAs($user)->get('/memos?keyword='.urlencode('買い物'));

        $response->assertSee('買い物リスト');
        $response->assertDontSee('読書メモ');
    }

    public function test_自分のメモの詳細を閲覧できる(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->for($user)->create(['title' => 'マイメモ']);

        $response = $this->actingAs($user)->get("/memos/{$memo->id}");

        $response->assertOk();
        $response->assertSee('マイメモ');
    }

    public function test_他人のメモの詳細は閲覧できず403になる(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $memo = Memo::factory()->for($other)->create();

        $response = $this->actingAs($user)->get("/memos/{$memo->id}");

        $response->assertForbidden();
    }

    public function test_自分のメモを編集できる(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->for($user)->create(['title' => '元のタイトル']);

        $response = $this->actingAs($user)->put("/memos/{$memo->id}", [
            'title' => '更新後のタイトル',
            'body' => '更新後の本文',
        ]);

        $response->assertRedirect(route('memos.show', $memo));
        $this->assertDatabaseHas('memos', [
            'id' => $memo->id,
            'title' => '更新後のタイトル',
        ]);
    }

    public function test_他人のメモは編集できず403になる(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $memo = Memo::factory()->for($other)->create(['title' => '元のタイトル']);

        $response = $this->actingAs($user)->put("/memos/{$memo->id}", [
            'title' => '不正な更新',
            'body' => '不正な本文',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('memos', ['id' => $memo->id, 'title' => '元のタイトル']);
    }

    public function test_自分のメモを削除できる(): void
    {
        $user = User::factory()->create();
        $memo = Memo::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete("/memos/{$memo->id}");

        $response->assertRedirect(route('memos.index'));
        $this->assertSoftDeleted('memos', ['id' => $memo->id]);
    }

    public function test_他人のメモは削除できず403になる(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $memo = Memo::factory()->for($other)->create();

        $response = $this->actingAs($user)->delete("/memos/{$memo->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('memos', ['id' => $memo->id, 'deleted_at' => null]);
    }
}
