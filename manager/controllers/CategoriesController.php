<?php

/**
 * Categories Controller
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CategoriesController extends AdminBaseController
{

    /**
     * Initialize Categories
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewCategories();
    }

    /**
     * Render Search Form
     *
     * @param int $cateId
     */
    public function index($cateId = 0)
    {
        $frm = $this->getSearchForm();
        $frm->fill(['parent_id' => $cateId]);
        $this->sets([
            "frmSearch" => $frm,
            "canEdit" => $this->objPrivilege->canEditCategories(true),
            "parentId" => $cateId
        ]);
        $this->_template->render();
    }

    /**
     * Search & List Categories
     */
    public function search()
    {
        $form = $this->getSearchForm();
        if (!$post = $form->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($form->getValidationErrors()));
        }

        $srch = Category::getSearchObject();
        $srch->joinTable(
            Category::DB_LANG_TBL,
            'LEFT OUTER JOIN',
            'catg.cate_id = catg_l.catelang_cate_id AND catg_l.catelang_lang_id = ' . $this->siteLangId,
            'catg_l'
        );
        $srch->addCondition('cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        if (!empty($post['keyword'])) {
            $srch->addCondition('cate_name', 'LIKE', '%' . $post['keyword'] . '%');
        }
        if (isset($post['parent_id'])) {
            $srch->addCondition('cate_parent', '=', $post['parent_id']);
        }
        if (isset($post['cate_type']) && $post['cate_type'] > 0) {
            $srch->addCondition('catg.cate_type', '=', $post['cate_type']);
        }
        $srch->addMultipleFields(
            [
                'catg.cate_id',
                'catg.cate_type',
                'catg.cate_parent',
                'catg.cate_subcategories',
                'catg.cate_status',
                'catg.cate_created',
                'catg_l.cate_name',
                'catg_l.cate_details',
                'catg_l.catelang_lang_id',
            ]
        );
        $srch->setPageNumber($post['page']);
        $srch->setPageSize($post['pagesize']);
        $srch->addOrder('cate_order');
        $data = FatApp::getDb()->fetchAll($srch->getResultSet(), 'cate_id');

        $this->sets([
            'arrListing' => $data,
            'page' => $post['page'],
            'postedData' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
            'canEdit' => $this->objPrivilege->canEditCategories(true),
            'types' => Category::getCategoriesTypes()
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Render Categories Form
     *
     * @param int $categoryId
     * @param int $langId
     */
    public function form(int $categoryId, int $langId)
    {
        $this->objPrivilege->canEditCategories();
        $categoryId = FatUtility::int($categoryId);
        $langId = FatUtility::int($langId);
        $langId = ($langId < 1) ? $this->siteLangId : $langId;

        $data = [];
        if ($categoryId > 0) {
            $category = new Category($categoryId);
            $data = $category->getDataById($langId);

            if (count($data) < 1) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
            $data['catelang_lang_id'] = $langId;
        }
        $frm = $this->getForm($langId);
        $frm->fill($data);
        
        $this->sets([
            'frm' => $frm,
            'data' => $data,
            'categoryId' => $categoryId,
        ]);

        $this->_template->render(false, false);
    }

    /**
     * Setup Categories
     */
    public function setup()
    {
        $this->objPrivilege->canEditCategories();
        $frm = $this->getForm($this->siteLangId);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }

        $category = new Category($post['cate_id']);
        $parent = FatUtility::int($post['cate_parent']);
        if (!$category->checkUnique($post['cate_name'], $this->siteLangId, $parent)) {
            FatUtility::dieJsonError($category->getError());
        }

        if (!$category->addUpdateData($post)) {
            FatUtility::dieJsonError($category->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
    }

    /**
     * Delete category
     *
     * @param int $cateId
     */
    public function delete(int $cateId)
    {
        $this->objPrivilege->canEditCategories();
        $cateId = FatUtility::int($cateId);

        $category = new Category($cateId);
        if (!$category->delete()) {
            FatUtility::dieJsonError($category->getError());
        }
        
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_RECORD_DELETED_SUCCESSFULLY'));
    }

    /**
     * Get Form
     *
     * @return Form
     */
    private function getForm(int $langId): Form
    {

        $frm = new Form('frmCategory');

        $fld = $frm->addHiddenField('', 'cate_id');
        $fld->requirements()->setIntPositive();
        $fld = $frm->addHiddenField('', 'catelang_id');
        $fld->requirements()->setIntPositive();

        $fld = $frm->addSelectBox(
            Label::getLabel('LBL_LANGUAGE'),
            'catelang_lang_id',
            Language::getAllNames(),
            '',
            [],
            ''
        );
        $fld->requirements()->setRequired();

        $fld = $frm->addHiddenField('', 'cate_type', Category::TYPE_COURSE);
        $fld->requirements()->setIntPositive();
        /* This will be used in future. */
        /* $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'cate_type', Category::getCategoriesTypes(), '', [], '')
        ->requirements()
        ->setRequired(); */

        $fld = $frm->addTextBox(Label::getLabel('LBL_NAME'), 'cate_name')->requirements()->setRequired();
        $frm->addTextarea(Label::getLabel('LBL_DESCRIPTION'), 'cate_details')->requirements()->setRequired();

        $parentCategories = Category::getCategoriesByParentId($langId);
        $frm->addSelectBox(Label::getLabel('LBL_PARENT'), 'cate_parent', $parentCategories, '', []);

        $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'cate_status', AppConstant::getActiveArr(), '', [], '')
        ->requirements()
        ->setRequired();

        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        return $frm;
    }

    /**
     * Get Search Form
     *
     * @return Form
     */
    private function getSearchForm(): Form
    {
        $frm = new Form('categorySearch');
        $frm->addHiddenField('', 'parent_id', '');
        $frm->addTextBox(Label::getLabel('LBL_KEYWORD'), 'keyword', '');
        $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'cate_type', Category::getCategoriesTypes());
        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'pagesize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'));
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_CLEAR'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    /**
     * Update status
     *
     * @param int $cateId
     * @param int $status
     * @return bool
     */
    public function updateStatus(int $cateId, int $status)
    {
        $this->objPrivilege->canEditCategories();
        $cateId = FatUtility::int($cateId);
        $status = FatUtility::int($status);
        $status = ($status == AppConstant::YES) ? AppConstant::NO : AppConstant::YES;

        $category = new Category($cateId);
        $category->setFldValue('cate_status', $status);
        if (!$category->save()) {
            FatUtility::dieJsonError($category->getError());
        }
        
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_STATUS_UPDATED_SUCCESSFULLY'));
    }

    /**
     * Update Sort Order
     *
     * @param int $onDrag
     * @return json
     */
    public function updateOrder(int $onDrag = 1)
    {
        $this->objPrivilege->canEditCategories();
        $post = FatApp::getPostedData();
        if (!empty($post)) {
            $cateObj = new Category();
            if (!$cateObj->updateOrder($post['categoriesList'])) {
                FatUtility::dieJsonError($cateObj->getError());
            }
            if ($onDrag == 0) {
                FatUtility::dieJsonSuccess('');
            } else {
                FatUtility::dieJsonSuccess(Label::getLabel('LBL_Order_Updated_Successfully'));
            }
        }
    }

    public function getBreadcrumbNodes($action)
    {
        $nodes = [];
        $parameters = FatApp::getParameters();
        if (isset($parameters[0]) && $parameters[0] > 0) {
            $nodes = [
                [
                    'title' => Label::getLabel('LBL_ROOT_CATEGORIES'),
                    'href' => MyUtility::generateUrl('categories', 'index')
                ],
                [
                    'title' => Label::getLabel('LBL_SUB_CATEGORIES'),
                ]
            ];
        } else {
            $nodes = [['title' => Label::getLabel('LBL_ROOT_CATEGORIES')]];
        }
        return $nodes;
    }
}
