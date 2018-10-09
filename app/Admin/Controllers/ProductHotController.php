<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\ProductHot;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin as Admin;
class ProductHotController extends Controller
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
            ->header('最热口子管理')
            ->body($this->grid()->disableExport());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('详情')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {

        return $content
            ->header('编辑')
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
            ->header('新增最热口子')
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
       return Admin::grid(ProductHot::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->column('product.name','名称');
            $grid->product()->icon('图标')->display(function ($icon) {
                $url = env('app.APP_URL') . '/uploads/' . $icon;
                return "<img src='$url' width='30px' height='30px' />";
            });
            $grid->order('排序');
            $states = [
                'on' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
            ];

            $grid->status('上下架')->switch($states);
            $recStates = [
                'on' => ['value' => 1, 'text' => '推荐', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '正常', 'color' => 'default'],
            ];
            $grid->is_recommend('是否推荐')->switch($recStates);
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductHot::findOrFail($id));
        $show->id('Id');
        $show->product()->name('名称');
        $show->product()->icon('图标')->image();
        $show->order('排序');
        $show->field('is_recommend','是否推荐')->as(function ($recommend) {
            return $recommend ? '是' : '否';
        });
        $show->field('status','是否上架')->as(function ($recommend) {
            return $recommend ? '是' : '否';
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ProductHot::class,function (Form $form){
            $form->display('id');
            $ids = ProductHot::query()->pluck('pid')->toArray();
            $products = Product::query()->whereNotIn('id',$ids)->orderBy('id','asc')->pluck('name','id')->toArray();
            $form->select('pid','口子')->options($products)->rules('required',['必须选择一个口子']);
            $form->number('order', '排序')->min(0);
            $states = [
                'on' => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
            ];
            $form->switch('status', '是否上架')->options($states);
            $recStates = [
                'on' => ['value' => 1, 'text' => '推荐', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => '正常', 'color' => 'default'],
            ];
            $form->switch('is_recommend', '是否推荐')->options($recStates);
        });
    }
}
