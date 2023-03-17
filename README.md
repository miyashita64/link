# 注意 #
本リポジトリは、開発に用いたリポジトリではなく、公開できるファイルだけを公開するために作られたものです。

# リンク #
リンクは、障害児童福祉施設における書類業務の軽減と施設間の情報共有を実現することを目的としたWebアプリケーションです。

# リンク環境構築 #

## 各種バージョン ##
* PHP 7.2.34
* Composer 2.0.8
* Laravel 7.30.4
* MySQL 5.7

## **プロジェクトをクローンする(git)** ##
以下のコマンドで、既存のプロジェクトをクローンする

```bash
$ git clone BitbucketのページでコピーできるURL

$ git clone https://Taki_Miyata@bitbucket.org/Taki_Miyata/link.git
```

設定ファイル(.env)を作成
```bash
$ cd link/link
$ cp .env.example .env
```

.envファイルを編集する
DB_DATABASE=データベース名
DB_USERNAME=mysqlのユーザ名
DB_PASSWORD=mysqlのパスワード

アプリケーションキーを生成する
```bash
$ php artisan key:generate
```

パッケージをインストールする
```bash
$ composer update
$ composer install
```

データベースにテーブルを作成する
```bash
$ php artisan migrate
```

サンプルデータを生成する
```bash
$ php artisan db:seed
```