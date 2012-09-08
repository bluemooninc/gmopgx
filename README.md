GMO Payment Gateway for XOOPS Cube
====

gmopgx は、 GMOペイメントゲートウェイ株式会社 ( http://www.gmo-pg.com/ )のクレジットカード決済サービスを利用する為の決済モジュールと、決済モジュールを利用するショッピングカートのアプリケーションがセットになったリポジトリで 、XOOPS Cube 2.1以降で動作します。

ライセンス： GPL V2.0 オープンソースライセンス

インストール方法

このリポジトリにある html/modules/配下の２つのフォルダを XOOPS Cube をインストールしたサーバの html/modules フォルダにアップロードします。

管理者にて XOOPS Cube にログインした後、管理者メニューのモジュール管理よりモジュールインストールを行います。

設定

管理者メニュー画面にあるクレジットカード決済( モジュール名 gmopgx )のリンクより、一般設定をクリックして GMO ペイメントゲートウェイより発行された利用IDを設定します。

PGCARD_SITE_ID　発行されたサイト用IDを登録します。
PGCARD_SITE_PASS　発行されたサイト用IDを登録します。
PGCARD_SHOP_ID 　発行されたショップ用IDを登録します。
PGCARD_SHOP_PASS 　発行されたショップ用IDを登録します。
決済後のジャンプURL　決済が完了した後にジャンプしたいURLを登録します。以下は、同梱のカートアプリ ( Myshop ) のURLです。

myshop/thankyou_gmopg.php?orderId= 

ショッピングカート（マイ・ショップ）の設定

一般設定より、GMO-PG の支払いに対応しますか？ に「はい」を選択します。
GMO-PG の支払いURL にモジュールの決済用URL( ./gmopgx/entryTran ) を設定します。
Paypal Email アドレス　にお店のメールアドレスを記載ください。（これはPaypalと設定を共有している為です。Paypalを利用しない場合でも設定します）

ご利用方法についてご質問等ございましたら、有限会社ブルームーン( http://www.bluemooninc.jp )へアクセスください。また、サイト構築や設定に関するサービスも同社が提供しています。
