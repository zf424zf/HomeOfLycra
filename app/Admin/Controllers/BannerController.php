<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BannerController extends Controller
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
            ->header('轮播图管理')
            ->description('轮播图列表')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
        $grid = new Grid(new Banner);

        $grid->id('Id');
        $grid->url('图片')->display(function ($image) {
            $url = env('app.APP_URL') . '/uploads/' . $image;
            return "<img width='30px' height='30px' src='$url'/>";
        });
        $grid->desc('备注');
        $states = [
            'on' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
        ];
        $grid->status('上下架')->switch($states);
//        $grid->status('是否显示')->display(function ($status){
//            if($status == 1){
//                return "显示";
//            }
//            return "不显示";
//        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(Banner::findOrFail($id));

        $show->id('Id');
        $show->url()->image();
        $show->desc('Desc');
        $show->status('Status');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Banner);
        $form->image('url', '图片')->move('images/banner')->uniqueName();
        $form->text('desc', '备注');
//        $form->number('status', '是否显示(1为显示)')->max(1)->min(0);
        $states = [
            '上架' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
            '下架' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
        ];
        $form->switch('status', '上下架')->options($states);
        return $form;
    }
}
