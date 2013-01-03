<?php
if (!defined('XOOPS_ROOT_PATH')) exit();
$comdir = XOOPS_ROOT_PATH . "/modules/gmopgx/vendor/gpay_client/src/";
set_include_path($comdir);
require_once('com/gmo_pg/client/input/SearchCardInput.php');
require_once('com/gmo_pg/client/tran/SearchCard.php');

class Gmopgx_Service extends XCube_Service
{
    public $mServiceName = 'Gmopgx_Service';
    public $mNameSpace = 'Gmopgx';
    public $mClassName = 'Gmopgx_Service';

    public function prepare()
    {
        $this->addFunction(S_PUBLIC_FUNC('int checkOrderStatus(int orderId)'));
	    $this->addFunction(S_PUBLIC_FUNC('array getRegisteredCardList()'));
    }

    public function checkOrderStatus()
    {
        $ret = false;
        $root = XCube_Root::getSingleton();
        $orderId = $root->mContext->mRequest->getRequest('orderId');
        if ($root->mContext->mUser->isInRole('Site.RegisteredUser')) {
            $uid = $root->mContext->mXoopsUser->get('uid');
            $modHand = xoops_getmodulehandler('payment', 'gmopgx');
            $myrow = $modHand->getDataById($uid, $orderId);
            if ($myrow) {
                if ($myrow['status'] == 1) $ret = true;
            }
        }
        return $ret;
    }

	public function &getRegisteredCardList()
	{
		$root = XCube_Root::getSingleton();
		$MemberId = $root->mContext->mXoopsUser->get('uid');
		$root->mController->setupModuleContext('gmopgx');
		$myModuleConfig = $root->mContext->mModuleConfig;

		//入力パラメータクラスをインスタンス化します
		$input = new SearchCardInput();

		//サイトID・パスワード
		$input->setSiteId($myModuleConfig['PGCARD_SITE_ID']);
		$input->setSitePass($myModuleConfig['PGCARD_SITE_PASS']);

		//会員IDは必須です
		$input->setMemberId($MemberId);

		//カード登録連番が指定された場合、パラメータに設定します。
		if (isset($_POST['CardSeq'])){
			$cardSeq = xoops_getrequest('CardSeq');
			$seqMode = xoops_getrequest('SeqMode');
			if (0 < strlen($cardSeq)) {
				//登録カード連番
				$input->setCardSeq($cardSeq);
				$input->setSeqMode($seqMode);
			}
		}
		//API通信クラスをインスタンス化します
		$exe = new SearchCard();

		//パラメータオブジェクトを引数に、実行メソッドを呼びます。
		//正常に終了した場合、結果オブジェクトが返るはずです。
		$output = $exe->exec($input);

		//実行後、その結果を確認します。
		if ($exe->isExceptionOccured()) {
			//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。
			$this->error_message = "Connection ERROR!";
			return NULL;
		} else {
			//例外が発生していない場合、出力パラメータオブジェクトが戻ります。
			if ($output->isErrorOccurred()) { //出力パラメータにエラーコードが含まれていないか、チェックしています。
				$errorHandle = new ErrorHandler();
				$errorList = $output->getErrList();
				$this->error_message = "";
				foreach ($errorList as $errorInfo) {
					/* @var $errorInfo ErrHolder */
					$this->error_message .= '<li>'
						. $errorInfo->getErrCode()
						. ':' . $errorInfo->getErrInfo()
						. ':' . $errorHandle->getMessage($errorInfo->getErrInfo())
						. '</li>';
				}
				return NULL;
			}
			//例外発生せず、エラーの戻りもないので、正常とみなします。
			//このif文を抜けて、結果を表示します。
		}
		$cardList = $output->getCardList();
		return $cardList;
	}
}