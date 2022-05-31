<?php
/**
 * WT JoomShopping Favorites is an alternative wish list (favorite products) for JoomShopping based on coockies.
 * @package     WT JoomShopping Favorite
 * @version     1.3.1
 * @Author      Sergey Tolkachyov, https://web-tolk.ru
 * @copyright   Copyright (C) 2020 Sergey Tolkachyov
 * @license     GNU/GPL 3.0
 * @since       1.0.0
 * @link        https://web-tolk.ru/en/dev/joomshopping/wt-joomshopping-favorite.html
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Registry\Registry;

class plgSystemWt_modules_in_jshopping_positions extends CMSPlugin
{
	protected $autoloadlanguage = true;

	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}


	/**
	 * Вызывается перед $view->display()
	 *
	 * @param $view JoomShopping Product view object
	 *
	 *
	 * @since 1.0.0
	 */
	public function onBeforeDisplayProductView(&$view)
	{


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
				$view->$product_tmp_var .= ModuleHelper::renderModule($module);
			}
		}


	}

	public function onBeforeDisplaywtjshoppingfavoritesView(&$view)
	{

	}

	/**
	 * Вызывается перед $view->display. Изменение $productlist ничего не даёт.
	 *
	 * @param $view        JoomShopping category view
	 * @param $productlist List of products
	 *
	 * @see   \Joomla\Component\Jshopping\Site\Controller\ProductsController::display
	 * @see   \Joomla\Component\Jshopping\Site\Controller\ManufacturerController::view
	 * @see   \Joomla\Component\Jshopping\Site\Controller\CategoryController::view
	 * @see   \Joomla\Component\Jshopping\Site\Controller\VendorController::products
	 * @since 1.0.0
	 */
	public function onBeforeDisplayProductListView($view, &$productlist)
	{
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
					$view->$product_list_tmp_var_category .= ModuleHelper::renderModule($module);
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
							$product->$product_list_tmp_var_product .= ModuleHelper::renderModule($module);

						}
					}
				}
			}
		}
	}

	/**
	 * Вызывается перед $view->display. Изменение $productlist ничего не даёт.
	 *
	 * @param $view        JoomShopping category view
	 *
	 * @see   \Joomla\Component\Jshopping\Site\Controller\CategoryController::display
	 * @since 1.0.0
	 */
	public function onBeforeDisplayCategoryView($view)
	{
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
					$view->$product_list_tmp_var_category .= ModuleHelper::renderModule($module);
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
							$product->$product_list_tmp_var_product .= ModuleHelper::renderModule($module);

						}
					}
				}
			}
		}
	}

}
