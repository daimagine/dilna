<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 1/5/13
 * Time: 10:42 AM
 * To change this template use File | Settings | File Templates.
 */

class Asset_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'asset@index');
    }

    public function get_index() {
        $this->get_list();
    }

    public function get_list() {
       $assets = AssetActiva::listAll(array('status' => array(statusType::ACTIVE, statusType::INACTIVE)));
        return $this->layout->nest('content', 'asset.list', array(
            'assets' => $assets,
        ));
    }

    public function get_edit($id=null) {
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/asset/application.js',  array('jquery', 'jquery-ui'));
        if($id===null) {
            return Redirect::to('asset/index');
        }
        $asset = AssetActiva::find($id);

        $lstAssetType = AssetType::listAll(array('status' => array(statusType::ACTIVE, statusType::INACTIVE)));
        $assetTypes=array();
        foreach($lstAssetType as $type) {
            $assetTypes[$type->id] = $type->name;
        }

        return $this->layout->nest('content', 'asset.edit', array(
            'asset' => $asset,
            'assetTypes' => $assetTypes
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        $dataEdit = Input::all();
//        dd($dataEdit);
        if($id===null) {
            Session::flash('message_error', 'Failed update asset');
            return Redirect::to('asset/index');
        }
        $validation = Validator::make(Input::all(), $this->getRules());
        if(!$validation->fails()) {
            $success = AssetActiva::update($id, $dataEdit);
            if ($success) {
                Session::flash('message', 'Success update asset');
            } else {
                Session::flash('message_error', 'Failed update asset');
            }
        } else {
            return Redirect::to('asset/edit/'.$id)
                ->with('service', $dataEdit);
        }

        return Redirect::to('asset/index');
    }


    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('asset/index');
        }
        $success = AssetActiva::updateStatus($id, statusType::INACTIVE);
        if($success) {
            Session::flash('message', 'Inactive asset success');
            return Redirect::to('asset/index');
        } else {
            Session::flash('message_error', 'Inactive asset failed');
            return Redirect::to('asset/index');
        }
    }

    //============================ Asset Type Management ============================
    public function get_list_asset_type() {
        $lstAssetType = AssetType::listAll(array('status' => array(statusType::ACTIVE, statusType::INACTIVE)));
            return $this->layout->nest('content', 'asset.listassettype', array(
                'lstAssetType' => $lstAssetType,
            ));
    }

    private function inject_asset_assetType(){
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/asset/application.js',  array('jquery', 'jquery-ui'));
    }

    public function get_add_asset_type() {
        Asset_Controller::inject_asset_assetType();
        $asset_type = Session::get('asset_type');
        return $this->layout->nest('content', 'asset.addassettype', array(
            'asset_type' => $asset_type
        ));
    }

    public function post_add_asset_type() {
        $inputData=Input::all();
        $validation = Validator::make(Input::all(), array(
            'name' => 'required|min:5|max:50',
            'description' => 'required|min:5',
            'status' => 'required|min:1|max:1',
        ));

        if(!$validation->fails()) {
            $storeAssetType = AssetType::create($inputData);
            if ($storeAssetType) {
                Session::flash('message', 'Success add asset type');
                return Redirect::to('asset/list_asset_type');
            }
        }

        Asset_Controller::inject_asset_assetType();
        Session::flash('message_error', 'Failed add asset type');
        return Redirect::to('asset/add_asset_type')
            ->with_errors($validation)
            ->with('asset_type', $inputData);
    }


    public function get_edit_asset_type($id=null) {
        Asset_Controller::inject_asset_assetType();
        $asset_type = AssetType::find($id);
        if ($asset_type === null) {
            return Redirect::to('asset/list_asset_type');
        }
        return $this->layout->nest('content', 'asset.editassettype', array(
            'asset_type' => $asset_type
        ));
    }


    public function post_edit_asset_type(){
        $id = Input::get('id');
        if($id===null) {
            Session::flash('message_error', 'Failed update');
            return Redirect::to('asset/list_asset_type');
        }

        $inputData=Input::all();
//        dd($inputData);
        $validation = Validator::make(Input::all(), array(
            'name' => 'required|min:5|max:50',
            'description' => 'required|min:5',
            'status' => 'required|min:1|max:1',
        ));
        if(!$validation->fails()) {
            $storeAssetType = AssetType::update($id, $inputData);
            if ($storeAssetType) {
                Session::flash('message', 'Success edit asset type');
                return Redirect::to('asset/list_asset_type');
            }
        }

        Asset_Controller::inject_asset_assetType();
        Session::flash('message_error', 'Failed edit asset type');
        return Redirect::to('asset/add_asset_type')
            ->with_errors($validation)
            ->with('asset_type', $inputData);

    }

    public function get_delete_asset_type($id=null){
        if($id===null) {
            return Redirect::to('asset/list_asset_type');
        }
        $updated = AssetType::update($id, array('status' => statusType::INACTIVE));
        if ($updated) {
            Session::flash('message', 'Success Inactive');
        } else {
            Session::flash('message_error', 'Failed Inactive type');
        }
        return Redirect::to('asset/list_asset_type');
    }



    //============================= START CONTROLLER FOR APPROVED HERE ====================
    private function get_lstSubAccountTrx($id){
        $lstAte=SubAccountTrx::getByCriteria(array(
            'id' => $id,
            'approved_status' => approvedStatus::NEW_ACCOUNT_INVOICE,
            'account_trx_id' => null,
            'account_trx_status' => accountTrxStatus::AWAITING_PAYMENT,
            'account_trx_type' => ACCOUNT_TYPE_CREDIT,
            'account_category' => AccountCategory::ASSET,
        ));
        return $lstAte;
    }

    public function get_list_approved(){
        Session::forget(ACCOUNT_TRX_ID);
        $lstSubAte=$this::get_lstSubAccountTrx(null);
//        dd($lstSubAte);
        return $this->layout->nest('content', 'asset.listapproved', array(
            'lstSubAte' => $lstSubAte
        ));
    }

    public function get_detail_approved($id=null) {
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/asset/application.js',  array('jquery', 'jquery-ui'));
        Session::forget(ACCOUNT_TRX_ID);
        Session::put(ACCOUNT_TRX_ID, $id);
        if($id===null) {
            return Redirect::to('asset/index');
        }
        $subAccountTrx = SubAccountTrx::find($id);
        $lstAssetType = AssetType::listAll(array('status' => array(statusType::ACTIVE, statusType::INACTIVE)));

        $assetTypes=array();
        foreach($lstAssetType as $type) {
            $assetTypes[$type->id] = $type->name;
        }

        $code = AssetActiva::code_new_asset();
        return $this->layout->nest('content', 'asset.processapproved', array(
            'subAccountTrx' => $subAccountTrx,
            'assetTypes' => $assetTypes,
            'code' => $code
        ));
    }

    public function post_approved_action() {
        $data = Input::all();
        $action = Input::get('action');
        $remarks = Input::get('remarks');
        $subAccountTrxId = Session::get(ACCOUNT_TRX_ID, null);
        $item =null;
        $subAccountTrx = SubAccountTrx::find($subAccountTrxId);
//        dd($data);
        if ($action == 'confirm') {
            $success = AssetActiva::insertMultiData($data);
            $subAccountTrx->approved_status = approvedStatus::CONFIRM_BY_WAREHOUSE;
            if ($success) {
                Session::flash('message', 'Success add Asset');
            } else {
                Session::flash('message_error', 'Failed add Asset');
                return Redirect::to('asset/list_approved');
            }
        } else if ($action = 'reject') {
            $subAccountTrx->approved_status = approvedStatus::REVIEW_BY_WAREHOUSE;
            Session::flash('message', 'Success Reject approved invoice');
        }

        if(SubAccountTrx::updateStatus($subAccountTrx->id, $subAccountTrx->approved_status, $remarks)) {
            //success update sub account trx
        }

        return Redirect::to('asset/list_approved');
    }


    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'name' => 'required|max:50',
            'description' => 'required',
            'purchase_price' => 'required|match:/[0-9]+(\.[0-9][0-9]?)?/',
            'code' => 'required',
            'status' => 'required|min:1|max:1',
        );
        if($method == 'add') {
            $additional = array(
            );
        } elseif($method == 'edit') {
            $additional = array(
            );
        }
        return array_merge($rules, $additional);
    }
}