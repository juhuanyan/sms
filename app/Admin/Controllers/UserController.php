<?php

namespace App\Admin\Controllers;

use App\Models\Jiekou;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

use Encore\Admin\Controllers\ModelForm;

class UserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Administrator::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->username(trans('admin::lang.username'));
            $grid->name(trans('admin::lang.name'));

            $grid->roles(trans('admin::lang.roles'))->value(function ($roles) {
                $roles = array_map(function ($role) {
                    return "<span class='label label-success'>{$role['name']}</span>";
                }, $roles);

                return implode('&nbsp;', $roles);
            });

            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));

            $grid->rows(function ($row) {
                if ($row->id == 1) {
                    $row->actions('edit');
                }
            });

            $grid->disableBatchDeletion();

            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Administrator::class, function (Form $form) {

            $form->text('username', trans('admin::lang.username'))->rules('required');
            $form->text('name', trans('admin::lang.name'))->rules('required');
            $form->password('password', trans('admin::lang.password'))->rules('required');

            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));
//            $form->multipleSelect('permissions', trans('admin::lang.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $jiekous = Jiekou::all();
            foreach ($jiekous as $jiekou){
                $jiekouArray[$jiekou->id] = $jiekou->name;
            }
            $form->divide();
            $form->html('<b>用户为管理员时,以下信息不需要填写!!</b>');
            $form->select('name', '接口名称')->options($jiekouArray);
            $form->text('jiangeshijian', '获取间隔时间')->placeholder('分钟');
            $form->text('fangwenip', '接口访问IP')->placeholder('*为空则不限制,建议填写。多IP ","分割');
            $form->text('dengluip', 'Web平台登录IP')->placeholder('*为空则不限制,多IP ","分割');
            $form->text('jiekouurl', '查询接口URL')->rules('required');

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}
