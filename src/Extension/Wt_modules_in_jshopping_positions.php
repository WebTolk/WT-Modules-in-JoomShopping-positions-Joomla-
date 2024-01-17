<?php
/**
 * @package       WT Modules in jshopping positions
 * @version       2.0.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2024 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Joomla\Plugin\System\Wt_modules_in_jshopping_positions\Extension;
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

class Wt_modules_in_jshopping_positions extends CMSPlugin implements SubscriberInterface
{
    protected $autoloadlanguage = true;

    protected $allowLegacyListeners = false;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   4.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeDisplayProductView' => 'onBeforeDisplayProductView',
            'onBeforeDisplaywtjshoppingfavoritesView' => 'onBeforeDisplaywtjshoppingfavoritesView',
            'onBeforeDisplayProductListView' => 'onBeforeDisplayProductListView',
            'onBeforeDisplayCategoryView' => 'onBeforeDisplayCategoryView',
            'onBeforeDisplayCartView' => 'onBeforeDisplayCartView',
            'onBeforeDisplayCheckoutStep2View' => 'onBeforeDisplayCheckoutStep2View',
            'onBeforeDisplayCheckoutStep3View' => 'onBeforeDisplayCheckoutStep3View',
            'onBeforeDisplayCheckoutStep4View' => 'onBeforeDisplayCheckoutStep4View',
            'onBeforeDisplayCheckoutStep5View' => 'onBeforeDisplayCheckoutStep5View',
            'onBeforeDisplayCheckoutFinish' => 'onBeforeDisplayCheckoutFinish'
        ];
    }

    /**
     * Динамически создает свойство в объекте или добавляет к нему значение
     *
     * @param $obj object Объект класса
     * @param $property string свойство
     * @param $value object значение
     *
     * @return void
     *
     * @since 2.0
     */
    private static function CreateOrAddToProperty($obj, $property, $value): void
    {
        if (property_exists($obj, $property))
        {
            $obj->$property .= $value;
        }
        else
        {
            $obj->$property = $value;
        }
    }

    /**
     * Вызывается перед $view->display()
     *
     * @param $event \Joomla\Event\Event before display product view
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function onBeforeDisplayProductView($event): void
    {
        /* @var $view object JoomShopping Product view */
        $view = $event->getArgument(0);
        $product_modules = $this->params->get('product_modules');

        foreach ($product_modules as $product_module)
        {
            $product_tmp_var = $product_module->product_tmp_var;
            if ($product_tmp_var == 'custom_position')
            {
                $product_tmp_var = $product_module->custom_position;
            }
            $modules = ModuleHelper::getModules($product_module->position);
            foreach ($modules as $module)
            {
                self::CreateOrAddToProperty($view, $product_tmp_var, ModuleHelper::renderModule($module));
            }
        }
    }

    /**
     * @param $event \Joomla\Event\Event before display WT jshopping favorites view
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function onBeforeDisplaywtjshoppingfavoritesView($event): void
    {
        /* @var $view object JoomShopping Product view */
        $view = $event->getArgument(0);
    }

    /**
     * Вызывается перед $view->display. Изменение $productlist ничего не даёт.
     *
     * @param $event Event before display product list view
     *
     * @return void
     *
     * @see   \Joomla\Component\Jshopping\Site\Controller\ProductsController::display
     * @see   \Joomla\Component\Jshopping\Site\Controller\ManufacturerController::view
     * @see   \Joomla\Component\Jshopping\Site\Controller\CategoryController::view
     * @see   \Joomla\Component\Jshopping\Site\Controller\VendorController::products
     * @since 1.0.0
     */
    public function onBeforeDisplayProductListView($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);
        /* @var $productlist object List of products */
        $productlist = $event->getArgument(1);

        $category_modules = $this->params->get('category_modules');

        foreach ($category_modules as $category_module)
        {
            if ($category_module->category_or_product == 'category')
            {
                $product_list_tmp_var_category = $category_module->product_list_tmp_var_category;
                if ($product_list_tmp_var_category == 'custom_position')
                {
                    $product_list_tmp_var_category = $category_module->custom_position_category;
                }
                $modules = ModuleHelper::getModules($category_module->position);
                foreach ($modules as $module)
                {
                    self::CreateOrAddToProperty($view, $product_list_tmp_var_category, ModuleHelper::renderModule($module));
                }
            }
            elseif ($category_module->category_or_product == 'product')
            {
                if (count((array) $view->rows) > 0)
                {
                    $product_list_tmp_var_product = $category_module->product_list_tmp_var_product;
                    if ($product_list_tmp_var_product == 'custom_position')
                    {
                        $product_list_tmp_var_product = $category_module->custom_position_product;
                    }

                    foreach ($view->rows as $product)
                    {
                        $modules = ModuleHelper::getModules($category_module->position);
                        foreach ($modules as $module)
                        {
                            self::CreateOrAddToProperty($product, $product_list_tmp_var_product, ModuleHelper::renderModule($module));
                        }
                    }
                }
            }
        }
    }

    /**
     * Вызывается перед $view->display. Изменение $productlist ничего не даёт.
     *
     * @param $event Event before display category view
     *
     * @return void
     *
     * @see   \Joomla\Component\Jshopping\Site\Controller\CategoryController::display
     * @since 1.0.0
     */
    public function onBeforeDisplayCategoryView($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);

        $category_modules = $this->params->get('category_modules');

        foreach ($category_modules as $category_module)
        {
            if ($category_module->category_or_product == 'category')
            {
                $product_list_tmp_var_category = $category_module->product_list_tmp_var_category;
                if ($product_list_tmp_var_category == 'custom_position')
                {
                    $product_list_tmp_var_category = $category_module->custom_position_category;
                }
                $modules = ModuleHelper::getModules($category_module->position);
                foreach ($modules as $module)
                {
                    self::CreateOrAddToProperty($view, $product_list_tmp_var_category, ModuleHelper::renderModule($module));
                }
            }
            elseif ($category_module->category_or_product == 'product')
            {
                if (!empty($view->rows))
                {
                    $product_list_tmp_var_product = $category_module->product_list_tmp_var_product;
                    if ($product_list_tmp_var_product == 'custom_position')
                    {
                        $product_list_tmp_var_product = $category_module->custom_position_product;
                    }

                    foreach ($view->rows as $product)
                    {
                        $modules = ModuleHelper::getModules($category_module->position);
                        foreach ($modules as $module)
                        {
                            self::CreateOrAddToProperty($product, $product_list_tmp_var_product, ModuleHelper::renderModule($module));
                        }
                    }
                }
            }
        }
    }

    /**
     * Вызывается в корзине товаров.
     *
     * @param $event Event before display cart view
     *
     * @return void
     *
     * @see \Joomla\Component\Jshopping\Site\Controller\CartController::view
     * @since 1.1.0
     */
    public function onBeforeDisplayCartView($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);

        $checkout_modules = $this->params->get('checkout_modules');
        foreach ($checkout_modules as $checkout_module)
        {
            if ($checkout_module->checkout_section == 'cart')
            {
                $checkout_cart_tmp_var = $checkout_module->checkout_cart_tmp_var_list;
                if ($checkout_cart_tmp_var == 'custom_position')
                {
                    $checkout_cart_tmp_var = $checkout_module->custom_position_checkout_cart;
                }
                $modules = ModuleHelper::getModules($checkout_module->position);
                foreach ($modules as $module)
                {
                    self::CreateOrAddToProperty($view, $checkout_cart_tmp_var, ModuleHelper::renderModule($module));
                }
            }
        }
    }

    /**
     * Вызывается при оформлении заказа на шаге заполнения адреса
     *
     * @param $event Event before display checkout step 2 view
     *
     * @return void
     *
     * @see \Joomla\Component\Jshopping\Site\Controller\CheckoutController::step2
     * @since 1.1.0
     */
    public function onBeforeDisplayCheckoutStep2View($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);

        $checkout_modules = $this->params->get('checkout_modules');
        foreach ($checkout_modules as $checkout_module)
        {
            if ($checkout_module->checkout_section == 'address')
            {
                $checkout_address_tmp_var = $checkout_module->checkout_address_tmp_var_list;
                if ($checkout_address_tmp_var == 'custom_position')
                {
                    $checkout_address_tmp_var = $checkout_module->custom_position_checkout_address;
                }
                $modules = ModuleHelper::getModules($checkout_module->position);
                foreach ($modules as $module)
                {
                    self::CreateOrAddToProperty($view, $checkout_address_tmp_var, ModuleHelper::renderModule($module));
                }
            }
        }
    }

    /**
     * Вызывается при оформлении заказа на шаге заполнения выбора способа оплаты
     *
     * @param $event Event before display checkout step 3 view
     *
     * @return void
     *
     * @see \Joomla\Component\Jshopping\Site\Controller\CheckoutController::step3
     * @since 1.1.0
     */
    public function onBeforeDisplayCheckoutStep3View($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);

        $checkout_modules = $this->params->get('checkout_modules');

        foreach ($checkout_modules as $checkout_module)
        {
            if ($checkout_module->checkout_section == 'payments')
            {
                $checkout_payments_tmp_var = $checkout_module->checkout_payments_tmp_var_list;
                if ($checkout_payments_tmp_var == 'custom_position')
                {
                    $checkout_payments_tmp_var = $checkout_module->custom_position_checkout_payments;
                }
                $modules = ModuleHelper::getModules($checkout_module->position);
                foreach ($modules as $module)
                {
                    CreateOrAddToProperty($view, $checkout_payments_tmp_var, ModuleHelper::renderModule($module));
                }
            }
        }
    }

    /**
     * Вызывается при оформлении заказа на шаге заполнения выбора способа доставки
     *
     * @param $event Event before display checkout step 4 view
     *
     * @return void
     *
     * @see \Joomla\Component\Jshopping\Site\Controller\CheckoutController::step4
     * @since 1.1.0
     */
    public function onBeforeDisplayCheckoutStep4View($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);

        $checkout_modules = $this->params->get('checkout_modules');

        foreach ($checkout_modules as $checkout_module)
        {
            if ($checkout_module->checkout_section == 'shippings')
            {
                $checkout_shippings_tmp_var = $checkout_module->checkout_shippings_tmp_var_list;
                if ($checkout_shippings_tmp_var == 'custom_position')
                {
                    $checkout_shippings_tmp_var = $checkout_module->custom_position_checkout_shippings;
                }
                $modules = ModuleHelper::getModules($checkout_module->position);
                foreach ($modules as $module)
                {
                    self::CreateOrAddToProperty($view, $checkout_shippings_tmp_var, ModuleHelper::renderModule($module));
                }
            }
        }
    }

    /**
     * Вызывается при оформлении заказа на шаге предпросмотра заказа и комментария к заказу
     *
     * @param $event Event before display checkout step 5 view
     *
     * @return void
     *
     * @see \Joomla\Component\Jshopping\Site\Controller\CheckoutController::step5
     * @since 1.1.0
     */
    public function onBeforeDisplayCheckoutStep5View($event): void
    {
        /* @var $view object JoomShopping category view */
        $view = $event->getArgument(0);

        $checkout_modules = $this->params->get('checkout_modules');

        foreach ($checkout_modules as $checkout_module)
        {
            if ($checkout_module->checkout_section == 'previewfinish')
            {
                $checkout_previewfinish_tmp_var = $checkout_module->checkout_previewfinish_tmp_var_list;
                if ($checkout_previewfinish_tmp_var == 'custom_position')
                {
                    $checkout_previewfinish_tmp_var = $checkout_module->custom_position_checkout_previewfinish;
                }
                $modules = ModuleHelper::getModules($checkout_module->position);
                foreach ($modules as $module)
                {
                    self::CreateOrAddToProperty($view, $checkout_previewfinish_tmp_var, ModuleHelper::renderModule($module));
                }
            }
        }
    }

    /**
     * Вызывается при оформлении заказа на шаге предпросмотра заказа и комментария к заказу
     *
     * @param $event Event before display checkout finish view
     *
     * @return void
     *
     * @see \Joomla\Component\Jshopping\Site\Controller\CheckoutController::step5
     * @since 1.1.0
     */
    public function onBeforeDisplayCheckoutFinish($event): void
    {
        /* @var $text object */
        $text = $event->getArgument(0);
        /* @var $order_id object */
        $order_id = $event->getArgument(1);

        $checkout_modules = $this->params->get('checkout_modules');

        foreach ($checkout_modules as $checkout_module)
        {
            if ($checkout_module->checkout_section == 'finish')
            {
                $modules = ModuleHelper::getModules($checkout_module->position);
                foreach ($modules as $module)
                {
                    $text .= ModuleHelper::renderModule($module);
                }
            }
        }
    }
}
