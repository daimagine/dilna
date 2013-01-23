<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jojo
 * Date: 9/16/12
 * Time: 12:56 AM
 * To change this template use File | Settings | File Templates.
 */

class Item_Controller extends Secure_Controller {

    public $restful = true;

    public function __construct() {
        parent::__construct();
        Session::put('active.main.nav', 'item@index');
    }

    private function define_asset(){
        Asset::add('style', 'css/styles.css');
        Asset::add('jquery', 'js/jquery.min.js');
        Asset::add('jquery-ui', 'js/jquery-ui.min.js', array('jquery'));
        Asset::add('jquery-uniform', 'js/plugins/forms/jquery.uniform.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.dataTables', 'js/plugins/tables/jquery.dataTables.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.sortable', 'js/plugins/tables/jquery.sortable.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.resizable', 'js/plugins/tables/jquery.resizable.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.collapsible', 'js/plugins/ui/jquery.collapsible.min.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.breadcrumbs', 'js/plugins/ui/jquery.breadcrumbs.js', array('jquery', 'jquery-ui'));
        Asset::add('jquery.tipsy', 'js/plugins/ui/jquery.tipsy.js', array('jquery', 'jquery-ui'));
        Asset::add('bootstrap-js', 'js/bootstrap.js', array('jquery-uniform'));
        Asset::add('application-js', 'js/application.js', array('jquery-uniform'));
    }

    public function get_index() {
        $all_item_category = ItemCategory::listAll(array());
        if ($all_item_category == null) {
            Session::flash('message_error', 'System Failed (List Item Category Null)');
            return Redirect::to('/');
        }
        return $this->layout->nest('content', 'item.index', array(
            'item_category' => $all_item_category,
        ));
//        $this->get_list();
    }

    public function get_list() {
        Asset::add('function_item', 'js/item/confirmation.js',  array('jquery', 'jquery-ui'));
        $category_id = Input::get('category'); //this id category
        $all_item_category = ItemCategory::listAll(array());
        if ($all_item_category == null) {
            Session::flash('message_error', 'System Failed (List Item Category Null)');
            return Redirect::to('/');
        }
        $item_category=null;
        if($category_id!=null) {
            foreach ($all_item_category as $c) {
                if ($c->id == $category_id ) {
                    $item_category=$c;
                }
            }
        } else {
            $item_category=$all_item_category[0];
        }
        if ($item_category==null) {
            Session::flash('message_error', 'System Failed get item category');
            return Redirect::to('/');
        }
        $criteria = array(
            'item_category_id' => $item_category->id
        );

        $this->get_items($criteria, $all_item_category ,$item_category);
    }

    public function get_items($criteria, $all_item_category, $item_category) {
        $item = Item::listAll($criteria);
        return $this->layout->nest('content', 'item.list', array(
            'item' => $item,
            'item_category' => $all_item_category,
            'category' => $item_category
        ));
    }

    public function get_add() {
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/item/application.js',  array('jquery', 'jquery-ui'));
        $itemdata = Session::get('item');
        $category_id = Input::get('category'); //this id category

        $all_item_category = ItemCategory::listAll(array());
        if ($all_item_category == null) {
            Session::flash('message_error', 'System Failed (List Item Category Null)');
            return Redirect::to('item/index');
        }
        $item_category=null;
        if($category_id!=null) {
            foreach ($all_item_category as $c) {
                if ($c->id == $category_id ) {
                    $item_category=$c;
                }
            }
        } else {
            $item_category=$all_item_category[0];
        }
        if ($item_category==null) {
            Session::flash('message_error', 'System Failed get item category');
            return Redirect::to('item/index');
        }
        $itemType=ItemType::listAll(array('item_category_id' => $item_category->id));
        $selectionType = array();
        foreach($itemType as $type) {
            $selectionType[$type->id] = $type->name;
        }
        $unitType=UnitType::listAll(array(
            'item_category_id' => $item_category->id
        ));
        $selectionUnit = array();
        foreach($unitType as $unit) {
            $selectionUnit[$unit->id] = $unit->name;
        }

        $lstSubAte=Item_Controller::get_lstSubAccountTrx(null);

        $selectionAte= array();
        if($lstSubAte!=null){
            foreach($lstSubAte as $ate) {
                $selectionAte[$ate->id] = ($ate->account_transaction->invoice_no).('-').($ate->id);
            }
        }

        $code = Item::code_new_item();
        return $this->layout->nest('content', 'item.add', array(
            'item' => $itemdata,
            'code' => $code,
            'itemType' => $selectionType,
            'itemCategory' => $item_category,
            'allItemCategory' => $all_item_category,
            'unitType'  => $selectionUnit,
            'accountTransaction' => $selectionAte
        ));
    }

    public function post_add() {
        $validation = Validator::make(Input::all(), $this->getRules());
        $itemdata = Input::all();
        if(!$validation->fails()) {
            $success = Item::create($itemdata);
            $storeItemPrice = ItemPrice::create(array(
                'item_id' => $success,
                'price' => $itemdata['price'],
                'prev_price' => 0,
                'purchase_price' => $itemdata['purchase_price']
            ));
            if($success and $storeItemPrice) {
                //success
                Session::flash('message', 'Success create');
                return Redirect::to('item/index'.'?category='.$itemdata['item_category_id']);
            } else {
                Session::flash('message_error', 'Failed create');
                return Redirect::to('item/add'.'?category='.$itemdata['item_category_id'])
                    ->with('item', $itemdata);
            }
        } else {
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
            return Redirect::to('item/add')
                ->with_errors($validation)
                ->with('item', $itemdata);
        }
    }

    public function get_edit($id=null) {
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery', 'jquery-ui'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery', 'jquery-ui'));
        Asset::add('function_item', 'js/item/application.js',  array('jquery', 'jquery-ui'));
        if($id===null) {
            return Redirect::to('item/index');
        }

        $item = Item::find($id);
        $itemType=ItemType::listAll(array('item_category_id' => $item->item_category->id));
        $selectionType = array();
        foreach($itemType as $type) {
            $selectionType[$type->id] = $type->name;
        }
        $unitType=UnitType::listAll(array(
            'item_category_id' => $item->item_category->id
        ));
        $selectionUnit = array();
        foreach($unitType as $unit) {
            $selectionUnit[$unit->id] = $unit->name;
        }

        $lstSubAte=Item_Controller::get_lstSubAccountTrx(null);
        $selectionAte= array();
        if($lstSubAte!=null){
            foreach($lstSubAte as $ate) {
                $selectionAte[$ate->id] = ($ate->account_transaction->invoice_no).('-').($ate->id);
            }
        }

        return $this->layout->nest('content', 'item.edit', array(
            'item' => $item,
            'itemType' => $selectionType,
            'unitType'  => $selectionUnit,
            'accountTransaction' => $selectionAte,
            'category' => $item->item_category
        ));
    }

    public function post_edit() {
        $id = Input::get('id');
        if($id===null) {
            return Redirect::to('item/index');
        }
        $item = Input::all();
        $prevPrice=Item::find($id)->price;
        if ($prevPrice !== $item['price']) {
            //get price active from DB
            $oldPrice = ItemPrice::getSingleResult(array(
                'item_id' => $id
            ));
            if ($oldPrice) {
                $updateItemPrice = ItemPrice::update($oldPrice->id, array(
                    'status' => statusType::INACTIVE
                ));
                $newPrice = ItemPrice::create(array(
                    'item_id' => $id,
                    'price' => $item['price'],
                    'prev_price' => $oldPrice->price,
                    'purchase_price' => $item['purchase_price']
                ));
                if ($updateItemPrice==null || $newPrice==null) {
                    Session::flash('message_error', 'Failed update item');
                }
            }
        }

        $updateItem = Item::update($id, $item);
        if ($updateItem) {
            Session::flash('message', 'Success update item');
        }
        return Redirect::to('item/index'.'?category='.$item['item_category_id']);
    }


    public function get_delete($id=null) {
        if($id===null) {
            return Redirect::to('item/index');
        }
        $success = Item::remove($id);
        if($success) {
            //success
            Session::flash('message', 'Remove success');
            return Redirect::to('item/index');
        } else {
            Session::flash('message_error', 'Remove failed');
            return Redirect::to('item/index');
        }
    }

    private function getRules($method='add') {
        $additional = array();
        $rules = array(
            'name' => 'required|max:50',
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

    //============================= START CONTROLLER FOR APPROVED HERE ====================

    private function get_lstSubAccountTrx($id){
        $lstAte=SubAccountTrx::getByCriteria(array(
            'id' => $id,
            'approved_status' => approvedStatus::NEW_ACCOUNT_INVOICE,
            'account_trx_id' => null,
            'account_trx_status' => accountTrxStatus::AWAITING_PAYMENT,
            'account_trx_type' => ACCOUNT_TYPE_CREDIT,
            'account_category' => AccountCategory::ITEM,
        ));
        return $lstAte;
    }

    public function get_list_approved(){
        Session::forget(ACCOUNT_TRX_ID);
        $lstSubAte=Item_Controller::get_lstSubAccountTrx(null);
        //dd(DB::last_query());
        return $this->layout->nest('content', 'item.approved.list', array(
            'lstSubAte' => $lstSubAte
        ));
    }

    public function get_detail_approved($id=null) {
        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
        Asset::add('jquery.validationEngine-en', 'js/plugins/forms/jquery.validationEngine-en.js',  array('jquery', 'jquery-ui'));
        Asset::add('jquery.ui.mousewheel', 'js/plugins/forms/jquery.mousewheel.js', array('jquery'));
        Asset::add('jquery.validate', 'js/plugins/wizards/jquery.validate.js',  array('jquery.validationEngine-en'));
        Asset::add('validationEngine.form', 'js/plugins/forms/jquery.validationEngine.js',  array('jquery.validationEngine-en'));
        Asset::add('function_item', 'js/item/application.js',  array('jquery.validationEngine-en'));
        Session::forget(ACCOUNT_TRX_ID);
        Session::put(ACCOUNT_TRX_ID, $id);
        if($id===null) {
            return Redirect::to('access/index');
        }
        $subAccountTrx = SubAccountTrx::find($id);
        $all_item_category = ItemCategory::listAll(array());

        return $this->layout->nest('content', 'item.approved.detail', array(
            'subAccountTrx' => $subAccountTrx,
            'itemCategory' => $all_item_category
        ));
    }

    public function get_lst_item() {
        //get list items
        Item_Controller::define_asset();
        $lstItemCategory = ItemCategory::listAll(null);
        $lstItems = Item::listAll(array());
        return View::make('item.approved.items', array(
            'lstItemCategory' => $lstItemCategory,
            'lstItems' => $lstItems
        ));
    }

    public function post_add_apporved_item(){
        $validation = Validator::make(Input::all(), $this->getRules());
        $subAccountTrxId = Session::get(ACCOUNT_TRX_ID, null);
        $data = Input::all();

        $success = ItemStockFlow::create($data);
        if($success) {
            return Redirect::to('item/detail_approved/'.$subAccountTrxId);
        } else {
            Session::flash('message_error', 'Failed create');
            return Redirect::to('item/list_approved');
        }
        return Redirect::to('item/detail_approved/'.$subAccountTrxId)
            ->with_errors($validation)
            ->with('access', $data);
    }

    public function get_putnewitem($id){
        Asset::add('jquery.ui.spinner','js/plugins/forms/ui.spinner.js', array('jquery'));
        Asset::add('function_item', 'js/item/application.js',  array('jquery.ui.spinner', 'jquery-ui'));
        $itemdata = Session::get('item');
        $subAccountTrxId = Session::get(ACCOUNT_TRX_ID, null);
        if($subAccountTrxId==null){
            Session::flash('message', 'System Failed');
            return Redirect::to('/');
        }
        //get price for this sub account transaction
        $actTrx=SubAccountTrx::find($subAccountTrxId);
//        dd($actTrx);

        $item_category = ItemCategory::find($id);
        if ($item_category==null) {
            Session::flash('message_error', 'System Failed get item category');
            return Redirect::to('item/index');
        }
        $itemType=ItemType::listAll(array('item_category_id' => $item_category->id));
        $selectionType = array();
        foreach($itemType as $type) {
            $selectionType[$type->id] = $type->name;
        }
        $unitType=UnitType::listAll(array(
            'item_category_id' => $item_category->id
        ));
        $selectionUnit = array();
        foreach($unitType as $unit) {
            $selectionUnit[$unit->id] = $unit->name;
        }

        $code = Item::code_new_item();
        return View::make('item.approved.additem', array(
            'item' => $itemdata,
            'code' => $code,
            'itemType' => $selectionType,
            'unitType'  => $selectionUnit,
            'itemCategory' => $item_category,
            'sub_account_trx_id' => $subAccountTrxId,
            'purchase_price' => $actTrx->unit_price,
        ));
    }

    public function post_putnewitem(){
        $validation = Validator::make(Input::all(), $this->getRules());
        $subAccountTrxId = Session::get(ACCOUNT_TRX_ID, null);
        $itemdata = Input::all();
        $stockOpname= $itemdata['stock'];
        if(!$validation->fails()) {
            $itemdata['stock']=0;
            $success = Item::create($itemdata);
            $insertStokFlow = ItemStockFlow::create(array(
                'item_id' => $success,
                'sub_account_trx_id' => $subAccountTrxId,
                'quantity' => $stockOpname
            ));
            if($success && $insertStokFlow) {
                Session::flash('message', 'Success Update');
            } else {
                Session::flash('message_error', 'Failed create item');
            }
        } else {
            Session::flash('message_error', 'Failed create');
            Log::info('Validation fails. error : ' + print_r($validation->errors, true));
        }
        return Redirect::to('item/detail_approved/'.$subAccountTrxId);
    }

    public function get_remove_opname_item($id=null){
        $subAccountTrxId = Session::get(ACCOUNT_TRX_ID, null);
        if($id===null){
            return Redirect::to('item/detail_approved/'.$subAccountTrxId);
        }

        $success = ItemStockFlow::deleteItemStockFlow($id);
        return Redirect::to('item/detail_approved/'.$subAccountTrxId);
    }

    public function post_approved_action() {
        $data = Input::all();
        $action = Input::get('action'); //this action type
        $remarks = Input::get('remarks');
        $subAccountTrxId = Session::get(ACCOUNT_TRX_ID, null);
        $item =null;
        $subAccountTrx = SubAccountTrx::find($subAccountTrxId);
            if ($action == 'confirm') {
                if (isset($data['item_id'])) {
                    if($data['item_id']==null || $data['item_id']=='' || $data['item_id']=='0') {
                        $item = Item::create($data);
                        $successAddStockFlow = ItemStockFlow::create(array(
                            'item_id' => $item,
                            'sub_account_trx_id' => $subAccountTrxId,
                            'quantity' => $data['stock_opname']
                        ));

                        $successAddPrice = ItemPrice::create(array(
                            'item_id' => $item,
                            'price' => $data['price'],
                            'prev_price' => 0,
                            'purchase_price' => $data['purchase_price']
                        ));
                        if($successAddStockFlow && $item && $successAddPrice) {
                            Session::flash('message', 'Success Closed');
                        } else {
                            Session::flash('message_error', 'Failed Closed approved invoice');
                            return Redirect::to('item/list_approved');
                        }
                    } else {
                        $item = Item::find($data['item_id']);
                        $success = ItemStockFlow::create(array(
                            'item_id' => $item->id,
                            'sub_account_trx_id' => $subAccountTrxId,
                            'quantity' => $data['stock_opname']
                        ));
                        $countStock = ($item->stock) + ((int)$data['stock_opname']);
                        $item = Item::update($item->id, array('stock' => $countStock));
                        if($success && $item) {
                            Session::flash('message', 'Success Closed');
                        } else {
                            Session::flash('message_error', 'Failed Closed approved invoice');
                            return Redirect::to('item/list_approved');
                        }
                    }
                }
                $subAccountTrx->approved_status = approvedStatus::CONFIRM_BY_WAREHOUSE;
            } else if ($action = 'reject') {
                $subAccountTrx->approved_status = approvedStatus::REVIEW_BY_WAREHOUSE;
            }
            if(SubAccountTrx::updateStatus($subAccountTrx->id, $subAccountTrx->approved_status, $remarks)) {
                //success update sub account trx
            }

        return Redirect::to('item/list_approved');
    }


    //============================= START CONTROLLER FOR MENU HISTORY ====================
    public function get_list_history() {
        $category_id = Input::get('category'); //this id category
        $all_item_category = ItemCategory::listAll(array());
        if ($all_item_category == null) {
            Session::flash('message_error', 'System Failed (List Item Category Null)');
            return Redirect::to('/');
        }
        $item_category=null;
        if($category_id!=null) {
            foreach ($all_item_category as $c) {
                if ($c->id == $category_id ) {
                    $item_category=$c;
                }
            }
        } else {
            $item_category=$all_item_category[0];
        }
        if ($item_category==null) {
            Session::flash('message_error', 'System Failed get item category');
            return Redirect::to('/');
        }
        $criteria = array(
            'item_category_id' => $item_category->id,
            'status' => array(statusType::ACTIVE, statusType::INACTIVE),
        );

        //----call query get list item_price-----//
        $listItemPrice = ItemPrice::listAll(array(
           'item_category_id' => $item_category->id,
            'status' => array(statusType::ACTIVE, statusType::INACTIVE)
        ));

        //----call query get item stock flow-----//
        $listItemStockFlow=ItemStockFlow::listAll(array(
            'item_category_id' => $item_category->id,
            'status' => array(statusType::ACTIVE, statusType::INACTIVE)
        ));
        Asset::add('function_item', 'js/item/application.list.history.js',  array('jquery', 'jquery-ui'));
        return $this->layout->nest('content', 'item.history', array(
            'listItemPrice' => $listItemPrice,
            'listItemStokFlow' => $listItemStockFlow,
            'item_category' => $all_item_category,
            'category' => $item_category
        ));
    }


}