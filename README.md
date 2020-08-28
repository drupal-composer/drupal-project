# Composer template for Drupal projects

![Merge upstream branches](https://github.com/studioumi/drupal-project/workflows/Merge%20upstream%20branches/badge.svg)

composer create-projectによってDrupal9.xの初期構築を行うパッケージ。

## 使用方法

最初に [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) をインストールしてください。

下記のコマンドでプロジェクトを作成します。

```bash
# {some-dir} は Drupal をインストールするディレクトリ.
composer create-project studioumi/drupal-project:8.x-dev {some-dir} --no-interaction
```

プロジェクト作成後、インストールディレクトリへ移動しDrupalの初期インストールを実行します。

```bash
cd {some-dir}
drush site:install --account-name=admin --account-mail=foo@example.com --account-pass=pass --locale=ja --db-url=mysql://user:password@host:port/dbname
```

初期インストール後、 `settings.php` を変更しgitの初期化を行います。

```bash
git init
git commit -m "initial commit."
```

## Landoを実行

以下コマンドでLando環境が起動します。  
Landoのインストールは事前に行ってください。( https://docs.lando.dev/basics/installation.html )

```bash
lando start
```

## その他ライブラリ導入方法

コントリビュートモジュールやその他ライブラリをインストールする場合
`composer require ...` コマンドで導入できます。

```bash
cd some-dir
composer require drupal/devel:~1.0
```

## Drupal コアのアップデート

1. `composer update` を利用し、パッケージをアップデートします

```bash
composer update drupal/core-recommended drupal/core-dev --with-dependencies
```

2. `git diff` で差分の確認を行います。その際、 `.htaccess` や `robots.txt` 等のファイルも更新される為  
   必要に応じて差分の取り込みを行います。

### コア及びコントリビュートモジュールのパッチ適用

コア等の挙動に問題があり、パッチを当てる必要がある場合 `composer.json` へ適用するパッチを記載します。  
これは [composer-patches](https://github.com/cweagans/composer-patches) によって自動的にパッチが適用されます。

```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL or local path to patch"
        }
    }
}
```

### PHPのバージョンを固定する方法

以下コマンドで実行するPHPのバージョンを固定することが出来ます。

```bash
composer config platform.php 7.4
```

### その他

本プロジェクトは [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project) のフォークプロジェクトです。  
詳細な内容はそちらを参照ください。

#### 主な変更点

- `drupal/core` -> `drupal/core-recomended` への置き換え
- [drupal-composer/drupal-paranoia](https://packagist.org/packages/drupal-composer/drupal-paranoia) の利用
- [Lando](https://docs.lando.dev/) の実行環境
