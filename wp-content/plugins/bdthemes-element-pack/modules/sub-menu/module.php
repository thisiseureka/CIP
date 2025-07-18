<?php

namespace ElementPack\Modules\SubMenu;

use ElementPack\Base\Element_Pack_Module_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	public function get_name() {
		return 'sub-menu';
	}

	public function get_widgets() {
		$widgets = [
			'Sub_Menu',
		];

		return $widgets;
	}
}


class ep_sub_menu_menu_walker extends \Walker_Nav_Menu {
	var $has_child = false;
	public function start_lvl(&$output, $depth = 0, $args = array()) {
		$output .= '<span>';
	}

	public function end_lvl(&$output, $depth = 0, $args = array()) {
		$output .= '</span>';
	}

	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$data    = array();
		$class   = '';
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		if ($classes) {
			$class = trim(preg_replace('/menu-item(.+)/', '', implode(' ', $classes)));
		}
		//new class
		$classes = array();
		// $data['style'] = '';

		$classes[] = 'ep-item';
		if ($args->walker->has_children) {
			$classes[] = '';
		}

		if ($item->current || $item->current_item_parent || $item->current_item_ancestor) {
			$classes[] = ' active';
		}
		if ($item->dropdown_child && $depth > 0) {
			$classes[] = ' sub-dropdown';
		}
		// set id
		// $data['data-id'] = $item->ID;

		// is current item ?
		if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
			$data['data-menu-active'] = 2;

			// home/frontpage item
		} elseif (preg_replace('/#(.+)$/', '', $item->url) == 'index.php' && (is_home() || is_front_page())) {
			$data['data-menu-active'] = 2;
		}
		//
		$attributes = '';
		foreach ($data as $name => $value) {
			$attributes .= sprintf(' %s="%s"', $name, $value);
		}

		// create item output
		$id = apply_filters('nav_menu_item_id', '', $item, $args);

		if ($classes) {
			$class .= implode(' ', $classes);
		}
		if ($class) {
			$class = ' class="' . $class . '"';
		} else {
			$class = '';
		}

		// $output .= '<a class="bdt-menu-item"' . (strlen($id) ? sprintf(' id="%s"', esc_attr($id)) : '') . $attributes . $class . '>';

		// set link attributes
		$attributes = '';
		foreach (array('attr_title' => 'title', 'target' => 'target', 'xfn' => 'rel', 'url' => 'href') as $var => $attr) {
			if (!empty($item->$var)) {
				$attributes .= sprintf(' %s="%s"', $attr, $item->$var);
			}
		}

		// escape link title
		$item->title = $item->title; //htmlspecialchars($item->title, ENT_COMPAT, "UTF-8");
		$classes     = trim(preg_replace('/menu-item(.+)/', '', implode(' ', $classes)));

		// is separator ?
		if ($item->url == '#') {
			$format     = '%s<a href="#" %s><span class="ep-content"><span class="ep-title">%s</span></span><span class="ep-hover-icon ep-icon-arrow-right"></span></a>%s';

			$attributes = ' class="' . $classes . '"';
		} else {
			$attributes .= ' class="' . $classes . '"';
			$format = '%s<a%s><span class="ep-content"><span class="ep-title">%s</span></span><span class="ep-hover-icon ep-icon-arrow-right"></span></a>%s';
		}


		if (isset($item->icon)) {
			$icon = "<span class=\"bdt-margin-small-right\" bdt-icon=\"icon: {$item->icon}\"></span>";
		} else {
			$icon = '';
		}

		// create link output
		$item_output = sprintf($format, $args->before, $attributes, $icon . $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after, $args->after);

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		// $output .= '</a';
	}


	public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args = array(), &$output = '') {
		// attach to element so that it's available in start_el()
		$element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
}
