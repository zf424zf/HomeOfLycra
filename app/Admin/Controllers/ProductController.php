<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('产品列表')
            ->description('贷款产品列表')
            ->body($this->grid()->disableExport());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('编辑')
            ->description('编辑产品')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑')
            ->description('编辑产品')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);
        $grid->id('Id');
        $grid->name('名称');
        $grid->icon('图标')->display(function ($icon) {
            $url = env('app.APP_URL') . '/uploads/' . $icon;
            return "<img src='$url' width='30px' height='30px' />";
        });
        $grid->limit('额度');
        $grid->price('费用');
        $grid->desc('介绍');
        $grid->apply_num('申请人数');
//        $grid->url('网址');
        $grid->limit_date('借款期限');
        $grid->limit_age('年龄限制');
        $grid->apply_time('办理时间');
        $grid->check_type('审核方式');
        $grid->order('排序');
        $colors = ['danger', 'success', 'primary', 'info', 'warning'];
        $grid->keywords('标签')->display(function ($keywords) use ($colors) {
//            $keywordArr = explode(',', $keywords);
            $dom = '';
            foreach ($keywords as $key => $value) {
                $dom .= '<span style="margin:0 1px" class="label label-' . $colors[$key % count($colors)] . '">'.$value.'</span>';
            }
            return $dom;
        });
        $states = [
            'on' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
        ];
        $grid->status('上下架')->switch($states);
//        $grid->created_at('创建时间');
//        $grid->updated_at('编辑时间');

        $grid->filter(function ($filter) {
            // 在这里添加字段过滤器
            $filter->like('name', 'name');
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));
        $show->id('Id');
        $show->name('名称');
        $show->icon('图标')->image();
        $show->limit('额度');
        $show->price('费用');
        $show->desc('介绍');
        $show->apply_num('申请人数');
        $show->url('网址');
        $show->limit_date('借款期限');
        $show->limit_age('年龄限制');
        $show->apply_time('办理时间');
        $show->check_type('审核方式');
        $show->order('排序');
        $show->created_at('创建时间');
        $show->updated_at('编辑时间');
        $show->keywords('标签');
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);
        $form->text('name', '名称');
        $form->image('icon', '图标')->move('images/products')->uniqueName();
        $form->text('limit', '额度');
        $form->text('price', '费用');
        $form->text('desc', '介绍');
        $form->number('apply_num', '申请人数')->min(0);
        $form->url('url', '网址');
        $form->text('limit_date', '借款期限');
        $form->text('limit_age', '年龄限制');
        $form->text('apply_time', '办理时间');
        $form->text('check_type', '审核方式');
        $form->number('order', '排序')->min(0);
        $tags = Tag::all()->pluck('name')->toArray();
        $form->tags('keywords', '标签')->options($tags);
        $states = [
            'on' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
        ];
        $form->switch('status', '是否上架')->options($states);
        return $form;
    }
}
