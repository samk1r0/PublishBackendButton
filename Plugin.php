<?php namespace Depcore\PublishBackendButton;

use System\Classes\PluginBase;
use OFFLINE\Mall\Controllers\Products;
use OFFLINE\Mall\Models\Product;
use Redirect;
class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'PublishBackendButton',
            'description' => 'No description provided yet...',
            'author' => 'Depcore',
            'icon' => 'icon-leaf'
        ];
    }

    public function boot(){
        Products::extend(function(Products $controller) {

            list($author, $plugin) = explode('\\', strtolower(get_class()));
            $partials_path = sprintf('$/%s/%s/partials/products', $author, $plugin);
            $controller->addViewPath($partials_path);


            // Only for Product model
            if ($controller instanceof \OFFLINE\Mall\Controllers\Products) {
                $controller->addDynamicMethod('onUnpublishOption', static function () use ($controller) {
                    $checked = post('checked');
                    Product::whereIn('id', $checked)->update([
                        'published'=>0
                    ]);
                    return Redirect::refresh();
                });
                $controller->addDynamicMethod('onPublishOption', static function () use ($controller) {
                    $checked = post('checked');
                    Product::whereIn('id', $checked)->update([
                        'published'=>1
                    ]);
                    return Redirect::refresh();
                });
            }
        });

    }
}
