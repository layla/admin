<?php

use Layla\API;

class Admin_Page_Page {

	public function read_multiple($view, $pages)
	{
		$templates = array(
			'listitem' => View::make('admin::pages.pages.listitem')
		);

		$view->notifications();

		$view->full_list(function($view) use ($pages, $templates)
		{
			$view->header(function($view)
			{
				$view->search();
				$view->tabs(function($tab)
				{
					$tab->add('<i class="icon-list"></i>');
					$tab->add('<i class="icon-tree"></i>');
				});
			});

			$view->items(function($view) use ($pages, $templates)
			{
				if(count($pages->results) > 0)
				{
					foreach ($pages->results as $page)
					{
						$view->add($templates['listitem']->with('page', $page)->render());
					}
				}
				else
				{
					if(Input::get('q'))
					{
						$view->no_results(__('admin::page.read_multiple.table.no_search_results'));
					}
					else
					{
						$view->no_results(__('admin::page.read_multiple.table.no_results'));
					}
				}
			});
		});

		$view->templates($templates);
	}

	public function update($view, $page)
	{
		$view->form(function($view) use ($page)
		{
			$view->page_header(function($view)
			{
				$view->title(__('admin::page.update.title'));
			});

			// Get Languages and put it in a nice array for the dropdown
			$languages = model_array_pluck(API::get(array('languages'))->get('results'), function($language)
			{
				return $language->name;
			}, 'id');

			$view->text('meta_title',  __('admin::page.update.form.lang.meta_title'), $page->lang->meta_title);
			$view->text('meta_keywords', __('admin::page.update.form.lang.meta_keywords'), $page->lang->meta_keywords);
			$view->textarea('meta_description', __('admin::page.update.form.lang.meta_description'), $page->lang->meta_description);

			/**
			 * @todo stop laravel from adding id's to the form fields
			 */
			$view->text('temp_menu', __('admin::page.update.form.lang.menu'), $page->lang->menu);
			$view->text('url', __('admin::page.update.form.lang.url'), $page->lang->url);


			$view->actions(function($view)
			{
				$view->submit(__('admin::page.update.buttons.edit'), 'primary');
			});
		}, 'PUT', prefix('admin').'page/edit/'.$page->id);
	}

}