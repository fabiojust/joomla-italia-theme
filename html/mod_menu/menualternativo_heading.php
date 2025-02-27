<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2012 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 defined('_JEXEC') or die;

 use Joomla\CMS\Filter\OutputFilter;
 use Joomla\CMS\HTML\HTMLHelper;

 use Joomla\CMS\Uri\Uri;

 $baseImagePath = Uri::root(false) . "media/templates/site/joomla-italia-theme/images/";

 $item_active='';
 $attributes = [];

 if ($item->anchor_title) {
     $attributes['title'] = $item->anchor_title;
 }

 if ($item->anchor_rel) {
     $attributes['rel'] = $item->anchor_rel;
 }

 if ($item->id == $active_id) {
     $attributes['aria-current'] = 'location';

     if ($item->current) {
         $attributes['aria-current'] = 'page';
         $item_active = 'active';
     }
 }

 $linktype = $item->title;

if(
    ($item->title == "Panoramica")
)
{
    $attributes['data-element'] = 'overview';
}



 if ($item->deeper && $item->level == 1){
     $attributes['class'] = 'nav-link dropdown-toggle '.$item_active . ' ' .$item->anchor_css;
     $attributes['data-bs-toggle'] = 'dropdown';
     $attributes['aria-expanded'] = 'false';
     $attributes['id'] ='mainNavDropdown';
 } elseif ($item->level >= 2){
     $attributes['class'] =  'dropdown-item list-item '.$item_active;

 } else {
     $attributes['class'] = 'nav-link '.$item_active;
 }

 if ($item->menu_icon) {
     // The link is an icon
     if ($itemParams->get('menu_text', 1)) {
         // If the link text is to be displayed, the icon is added with aria-hidden
         $linktype = '<span class="p-2 ' . $item->menu_icon . '" aria-hidden="true"></span>' . $item->title;
     } else {
         // If the icon itself is the link, it needs a visually hidden text
         $linktype = '<span class="p-2 ' . $item->menu_icon . '" aria-hidden="true"></span><span class="visually-hidden">' . $item->title . '</span>';
     }
 } elseif ($item->menu_image) {
     // The link is an image, maybe with its own class
     $image_attributes = [];

     if ($item->menu_image_css) {
         $image_attributes['class'] = $item->menu_image_css;
     }

     $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $image_attributes);

     if ($itemParams->get('menu_text', 1)) {
         $linktype .= '<span class="image-title">' . $item->title . '</span>';
     }
 }

 if ($item->browserNav == 1) {
     $attributes['target'] = '_blank';
 } elseif ($item->browserNav == 2) {
     $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';

     $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
 }

 if ($item->deeper && $item->level == 1){
     echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), '<span>' . $linktype . '</span>
     <svg class="icon icon-xs">
         <use href="' . $baseImagePath . 'sprites.svg#it-expand"></use>
     </svg>
 ', $attributes);
 }elseif ($item->level >= 2){
     echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), '<span>' . $linktype . '</span>', $attributes);
 } else {
     echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
 }
