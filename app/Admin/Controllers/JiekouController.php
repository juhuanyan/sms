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

class JiekouController extends Controller
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
        return Admin::grid(Jiekou::class, function (Grid $grid) {
            $grid->filter(function($filter){

                // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
//                $filter->useModal();

                // 禁用id查询框
                $filter->disableIdFilter();

                $filter->like('name', '接口名称');

            });

            $grid->id('ID')->sortable();

            $grid->name('接口名称')->sortable();
            $grid->url('接口URL')->sortable();
            $grid->updated_at('最后更新时间')->sortable();
//            $grid->column('最后更新时间')->display(function () {
//                $sms = Smss::where(['jiekouid'=>$this->id])->orderBy('updated_at', 'desc')->first();
//                if ($sms->updated_at > $this->updated_at) {
//                    return $sms->updated_at;
//                } else {
//                    return $this->updated_at;
//                }
//            });
            $grid->column('手动更新')->display(function () {
                $xingji = '<a href="'.url('getSms/'.$this->name.'/'.$this->id).'"><i class="fa fa-refresh"></i></a>';
                return $xingji;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Jiekou::class, function (Form $form) {

            $form->text('name', '接口名称');
            $form->text('url', '接口URL');
            $form->radio('type', '返回数据类型')->values(['xml' => 'XML', 'json'=> 'JSON'])->default('xml');
            $form->radio('datatype', '返回数据格式')->values(['utf8' => 'UTF8', 'gbk'=> 'GBK'])->default('utf8');
            $form->text('jiangeshijian', '获取间隔时间')->placeholder('分钟');
            $form->divide();
            $form->text('shoujihaobiaoshi', '回复手机号标识');
            $form->text('neirongbiaoshi', '回复内容标识');
            $form->text('riqibiaoshi', '回复日期标识');
            $form->text('shijianbiaoshi', '回复时间标识');
            $form->divide();
            $form->text('fanhuizhibiaoshi', '返回值标识');
            $form->text('chenggongdaima', '返回值成功代码');

        });
    }
}
