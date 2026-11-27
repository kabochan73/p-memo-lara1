# 詳細設計書 (Detailed Design)

## 4-1. アーキテクチャ設計

| No. | 項目 | 内容 |
|---|---|---|
| 48 | 技術バージョン | PHP 8.5 / Laravel 13 / MySQL 8系 / Node.js LTS版 |
| 49 | ディレクトリ構成 | Laravel標準のMVC構成をそのまま使用(`app/Http/Controllers`, `app/Models`, `resources/views` など) |
| 50 | 責務分離 | Controller: リクエスト受付・処理呼び出し・レスポンス返却(Service層は設けない) / Model(Eloquent): DB操作・リレーション定義 / FormRequest: 入力バリデーション / Policy: 認可(他人のメモへのアクセス制御) |
| 51 | ライブラリ選定 | 認証系パッケージ(Breeze/Fortify等)は使わず、Laravel標準のAuth機能(Hash, Authファサード, ミドルウェア)を使って認証を自作する |
| 52 | コーディング規約 | Laravel標準の命名規則に従う(Controllerは`〇〇Controller`、Modelは単数形パスカルケース、テーブル名は複数形スネークケース) / フォーマットはLaravel Pint(標準搭載)を使用 |
| 53 | 環境変数設計 | `.env` でAPP_NAME / APP_ENV / APP_KEY / APP_URL / DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD などLaravel標準の項目を管理する |

## 4-2. データベース設計

| No. | 項目 | 内容 |
|---|---|---|
| 54 | DB選定 | MySQL |
| 55 | エンティティ抽出 | ユーザー / メモ |
| 56 | ER図 | `users (1) ────< (多) memos`(1人のユーザーが複数のメモを持つ) |
| 57 | テーブル定義 | `users`: ユーザー情報を管理する(Laravel標準テーブル) / `memos`: メモ情報を管理する |
| 58 | カラム定義 | **users**(Laravel標準マイグレーションをそのまま使用): id(bigint, PK, auto increment) / name(varchar 255) / email(varchar 255, unique) / email_verified_at(timestamp, nullable, 今回未使用) / password(varchar 255) / remember_token(varchar 100, nullable) / created_at, updated_at<br>**memos**: id(bigint, PK, auto increment) / user_id(bigint, FK→users.id) / title(varchar 255, 必須) / body(text, nullable) / created_at, updated_at / deleted_at(timestamp, nullable, 論理削除用) |
| 59 | 主キー設計 | 各テーブルの `id` はbigintの自動増分(Laravel標準) |
| 60 | 外部キー設計 | `memos.user_id` → `users.id`。ユーザー削除時は関連メモもカスケード削除する |
| 61 | 制約設計 | `email`: UNIQUE制約 / `name`・`email`・`password`・`title`: NOT NULL |
| 62 | 正規化 | 現状の`users`/`memos`の2テーブル構成で重複データはなく、正規化済みとする |
| 63 | インデックス設計 | `memos.user_id`には外部キー制約により自動的にインデックスが付与される。検索はLIKEによる部分一致検索とし、FULLTEXTインデックスは導入しない |
| 64 | 削除方針 | メモの削除は論理削除(SoftDeletes、`deleted_at`カラムで管理)とする。ユーザー削除時のカスケード(No.60)は物理削除のまま |
| 65 | トランザクション設計 | 各操作は単一テーブルへの処理のみのため、明示的なトランザクション制御は不要とする |
| 66 | 排他制御設計 | 設けない(メモは本人のみ編集可能なため、他人との同時更新競合は発生しない想定) |

## 4-3. アプリケーション設計

| No. | 項目 | 内容 |
|---|---|---|
| 67 | ルーティング設計 | GET/POST `/register`(会員登録、認証不要) / GET/POST `/login`(ログイン、認証不要) / POST `/logout`(ログアウト、認証必要) / `/memos`系(一覧・作成・詳細・編集・更新・削除、`Route::resource`相当、すべて認証必要) |
| 68 | Controller設計 | `RegisteredUserController`: create(会員登録画面表示) / store(登録処理)<br>`AuthenticatedSessionController`: create(ログイン画面表示) / store(ログイン処理) / destroy(ログアウト処理)<br>`MemoController`: index(一覧+検索) / create(作成画面表示) / store(作成処理) / show(詳細表示) / edit(編集画面表示) / update(更新処理) / destroy(削除処理) |
| 69 | Model設計 | `User`: `memos()`で`hasMany(Memo::class)`、`password`はhashedキャスト(Laravel標準)<br>`Memo`: `user()`で`belongsTo(User::class)`、`SoftDeletes`トレイトを使用、`fillable`に`title`・`body`・`user_id`を指定 |
| 70 | バリデーション設計 | `RegisterUserRequest`: name(required/string/max:255) / email(required/email/unique:users/max:255) / password(required/confirmed/min:8)<br>`LoginRequest`: email(required/email) / password(required)<br>`MemoRequest`(作成・更新共通): title(required/string/max:255) / body(nullable/string) |
| 71 | 認証・認可設計 | Guard: Laravel標準の`web` Guard(セッションベース認証)を使用 / Middleware: `auth`ミドルウェアで認証必須ルートを保護 / Policy: `MemoPolicy`を作成し`update`・`delete`・`view`アクションで本人のメモかをチェック |
| 72 | 処理フロー設計 | 会員登録: バリデーション→パスワードハッシュ化→User作成→自動ログイン→一覧へ<br>ログイン: バリデーション→`Auth::attempt()`→成功時は一覧へ、失敗時はエラー表示<br>ログアウト: `Auth::logout()`→セッション無効化→ログイン画面へ<br>メモ作成: バリデーション→ログインユーザーに紐づけてMemo作成→一覧へ<br>メモ一覧: ログインユーザーのメモを取得(検索時はLIKE検索)→更新日時降順で表示<br>メモ詳細/編集/削除: Route Model Bindingで取得→`MemoPolicy`で本人確認→処理実行→リダイレクト |
| 73 | クラス・メソッド設計 | No.68(Controller設計)・No.69(Model設計)がベース。詳細な引数・戻り値は実装時に詰める |
| 74 | API設計 | 対象外(Bladeによる画面表示のみで、JSON APIは想定しない) |
| 75 | 例外処理設計 | Laravel標準の例外ハンドリング(`bootstrap/app.php`)を使用、カスタマイズは最小限 / `ModelNotFoundException`→404ページ / `AuthorizationException`→403ページ / エラーは`storage/logs/laravel.log`に記録 |
| 76 | キャッシュ設計 | 対象外(データ量・アクセス規模が小さく、キャッシュは不要) |
| 77 | 非同期処理設計 | 対象外(メール送信や重い処理がなく、Job/Queueは使用しない) |
| 78 | ファイル管理設計 | 対象外(画像やファイルの添付機能はスコープ外) |
| 79 | テスト設計 | Feature Testを中心に実施。会員登録(正常/異常) / ログイン(正常/異常) / ログアウト / メモ作成(正常/異常) / メモ一覧(本人のみ表示・検索) / メモ詳細・編集・削除(本人のみ操作可、他人のメモは403) / 未ログイン時はログイン画面へリダイレクト |
