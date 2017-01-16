<?php

namespace App\Admin\Controllers;

use App\Models\Jiekou;
use App\Models\Smss;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SmsController extends Controller
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

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(Smss::class, function (Grid $grid) {

            $user = Admin::user();
            if (!$user->can('administrator') && $user->can('customer')){
                $grid->model()->where(['jiekouid'=>$user->jiekouid]);
            }

            $user = Admin::user();
            $grid->filter(function ($filter) use($user) {


                // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
//                $filter->useModal();

                // 禁用id查询框
                $filter->disableIdFilter();

                $filter->like('caller', '回复手机号码');

                if ($user->can('administrator') && $user->can('customer')){
                    $filter->is('jiekouid', '所属接口')->select(Jiekou::all()->pluck('name', 'id'));
                }

                $filter->between('deliverdate', '回复时间')->datetime();


            });

            if (!$user->can('administrator') && $user->can('customer')){
                $grid->disableActions();
                $grid->disableBatchDeletion();
                $grid->disableCreation();
            }

            $grid->id('ID')->sortable();
            $grid->caller('回复手机号码')->sortable();
            $grid->msg('回复短信内容')->sortable();
            $grid->deliverdate('回复时间')->sortable();
            $grid->jiekouid('所属接口')->sortable()->display(function ($jiekou) {
                $sms = Jiekou::where('id', $jiekou)->first();
                return $sms->name;
            });
            $grid->disableBatchDeletion();

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Smss::class, function (Form $form) {

            $jiekous = Jiekou::all();
            foreach ($jiekous as $jiekou) {
                $jiekouArray[$jiekou->id] = $jiekou->name;
            }

            $form->text('caller', '回复手机号码');
            $form->text('msg', '回复短信内容');
            $form->date('deliverdate', '回复时间');
            $form->select('jiekouid', '所属接口')->options($jiekouArray);

        });
    }
}
