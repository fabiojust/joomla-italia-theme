<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

// Create shortcuts to some parameters.
$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$user    = Factory::getUser();
$info    = $params->get('info_block_position', 0);
$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';

// Check if associations are implemented. If they are, define the parameter.
$assocParam        = (Associations::isEnabled() && $params->get('show_associations'));
$currentDate       = Factory::getDate()->format('d-m-Y');
$isNotPublishedYet = $this->item->publish_up > $currentDate;
$isExpired         = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;


$fullimg = json_decode($this->item->images);

$pubblicazione = $this->item->publish_up;
$pubblicazione = Factory::getDate()->format('d.m.Y');

$revisione = $this->item->modified;
$revisione = Factory::getDate()->format('d.m.Y');;

$urlcompleto = Uri::getInstance();

$config = JFactory::getConfig();

$nomesito = $config->get( 'sitename' );

$baseImagePath = Uri::root(false) . "media/templates/site/joomla-italia-theme/images/";

?>

<section class="section bg-white py-5 position-relative d-block d-lg-flex align-items-center overflow-hidden section-hero">
<?php if($fullimg->image_fulltext !=''): ?>
        <div class="title-img" style="background-image: url('<?php echo $fullimg->image_fulltext; ?>');"></div>
    <?php else: ?>
        <div class="title-img bg-greendark bg-greendarkgradient d-none d-md-block">
            <svg width="100%" height="100%" viewBox="0 0 726 360" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g id="Group" opacity="0.32"><path id="Rectangle" d="M627.751,245.625l-396.368,-193.321l-193.322,396.368l396.368,193.321l193.322,-396.368Z" style="fill:url(#_Linear1);"></path><path id="Rectangle1" serif:id="Rectangle" d="M583.359,-179.506l-264.865,159.147l159.147,264.865l264.865,-159.147l-159.147,-264.865Z" style="fill:url(#_Linear2);"></path><path id="Rectangle2" serif:id="Rectangle" d="M210.182,-54.565l-213.341,33.79l33.79,213.34l213.341,-33.79l-33.79,-213.34Z" style="fill:url(#_Linear3);"></path></g><defs><linearGradient id="_Linear1" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(203.046,589.69,-589.69,203.046,231.383,52.3035)"><stop offset="0" style="stop-color:#0f842e;stop-opacity:1"></stop><stop offset="1" style="stop-color:#00838f;stop-opacity:1"></stop></linearGradient><linearGradient id="_Linear2" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(344.438,-26.7144,26.7144,344.438,398.068,112.073)"><stop offset="0" style="stop-color:#0e8a5f;stop-opacity:1"></stop><stop offset="1" style="stop-color:#00838f;stop-opacity:1"></stop></linearGradient><linearGradient id="_Linear3" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(230.236,72.8805,-72.8805,230.236,13.7359,85.8949)"><stop offset="0" style="stop-color:#0e8a5f;stop-opacity:1"></stop><stop offset="1" style="stop-color:#00838f;stop-opacity:1"></stop></linearGradient></defs>
            </svg>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5">
                <div class="hero-title">
                    <h1><?php echo $this->escape($this->item->title); ?></h1>
                    <p><?php echo JHTML::_('string.truncate', $this->item->text, true, false, false) ; ?></p>
                </div>
                <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                    <div class="argomenti-section px-4 mt-5">
                        <h2 class="h6">Argomenti</h2>
                        <div class="badges greenlight">
                            <?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
                            <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<div class="pagina-eventi greendark">
    <div class="border-row">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-3 p-0 pt-lg-2 mb-3">
                    <aside class="greendark aside-main aside-sticky" id="page-index">
                        <div class="aside-title" id="page-index">
                            <a class="toggle-link-list" data-bs-toggle="collapse" href="#lista-paragrafi" role="button" aria-expanded="false" aria-controls="lista-paragrafi" aria-label="apri / chiudi indice della pagina">
                                <span>Indice della pagina</span>
                                <svg class="icon icon-toggle"><use href="<?= $baseImagePath ?>sprites.svg#it-expand"></use></svg>
                            </a>
                        </div>
                        <div id="lista-paragrafi" class="link-list-wrapper collapse show" role="region" aria-labelledby="page-index">
                            <ul class="link-list" data-element="page-index">
                            <?php
                                JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
                                JModelLegacy::addIncludePath(JPATH_SITE. '/components/com_content/models', 'ContentModel');

                                $id = JFactory::getApplication()->input->get('id');

                                $model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request'=>true));
                                $appParams = JFactory::getApplication()->getParams();
                                $model->setState('params', $appParams);
                                $item = $model->getItem($id);
                                $jcFields = FieldsHelper::getFields('com_content.article', $item, true);

                            ?>
                            <?php foreach($jcFields as $jcField) { ?>
                                <?php if ($jcField->value !=''){ ?>
                                    <li><a href="#art-par-<?php echo $jcField->id; ?>" aria-label="Vai al paragrafo <?php echo $jcField->title; ?>"  data-focus-mouse="false"><?php echo $jcField->title; ?></a></li>
                                <?php } ?>
                             <?php } ?>
                            </ul>
                        </div>
                    </aside>
                </div>
                <div class="col-12 col-lg-9 border-aside ps-lg-5 pt-0 py-lg-5">
                    <div class="row variable-gutters">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <div class="actions-wrapper actions-main">
                                <a class="toggle-actions" href="#" title="Vedi azioni" data-bs-toggle="modal" data-bs-target="#modalaction">
                                    <svg class="icon icon-xs">
                                        <use xlink:href="<?= $baseImagePath ?>sprites.svg#it-more-items"></use>
                                    </svg>
                                    <span>Stampa / Condividi</span>
                                </a>
                                <div class="modal modal-actions fade no-print" tabindex="-1" role="dialog" id="modalaction" aria-labelledby="modalCenterTitle">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Chiudi finestra modale">
                                                <svg class="icon">
                                                    <use href="<?= $baseImagePath ?>sprites.svg#it-close"></use>
                                                </svg>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="link-list-wrapper">
                                                    <ul class="link-list ps-0 ms-0">
                                                        <li>
                                                            <a href="javascript:window.print();" class="list-item left-icon" title="Stampa il contenuto" data-focus-mouse="false">
                                                                <svg class="icon"><use href="<?= $baseImagePath ?>sprites.svg#it-print"></use></svg>
                                                                <span>Stampa</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="mailto:?subject=<?php echo $this->escape($this->item->title); ?>&amp;body=<?php echo $urlcompleto ?>" class="list-item left-icon" title="Invia il contenuto">
                                                                <svg class="icon"><use href="<?= $baseImagePath ?>sprites.svg#it-mail"></use></svg>
                                                                <span>Invia</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="list-item collapsed link-toggle" title="Condividi" href="#social-share" data-bs-toggle="collapse" aria-expanded="false" aria-controls="social-share" role="button" id="share-control">
                                                                <svg class="icon"><use href="<?= $baseImagePath ?>sprites.svg#it-share"></use></svg>
                                                                <span>Condividi</span>
                                                                <svg class="icon icon-right"><use href="<?= $baseImagePath ?>sprites.svg#it-expand"></use></svg>
                                                            </a>
                                                            <ul class="ps-0 link-sublist collapse" id="social-share" role="region" aria-labelledby="share-control">
                                                                <li>
                                                                    <a class="list-item" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $urlcompleto ?>" title="Condividi su: Facebook" target="_blank">
                                                                        <svg class="icon"><use href="<?= $baseImagePath ?>sprites.svg#it-facebook"></use></svg>
                                                                        <span>Facebook</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="list-item" href="http://twitter.com/share?text=<?php echo $this->escape($this->item->title); ?>&amp;url=<?php echo $urlcompleto ?>" title="Condividi su: Twitter" target="_blank">
                                                                        <svg class="icon"><use href="<?= $baseImagePath ?>sprites.svg#it-twitter"></use></svg>
                                                                        <span>Twitter</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="list-item" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $urlcompleto ?>&amp;title=<?php echo $this->escape($this->item->title); ?>&amp;source=<?php echo $nomesito?>" title="Condividi su: Linkedin" target="_blank">
                                                                    <svg class="icon"><use href="<?= $baseImagePath ?>sprites.svg#it-linkedin"></use></svg>
                                                                        <span>Linkedin</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="py-1 px-3 btn btn-primary btn-sm" data-bs-dismiss="modal" type="button">Ok</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="article-wrapper redbrown <?php echo $this->pageclass_sfx; ?>" >
                        <?php

                        if (!empty($this->item->pagination) && !$this->item->paginationposition && $this->item->paginationrelative) {
                            echo $this->item->pagination;
                        }
                        ?>

                        <?php $useDefList = $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
                        || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam; ?>


                        <?php if ($canEdit) : ?>
                            <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]); ?>
                        <?php endif; ?>

                        <?php // Content is generated by content plugin event "onContentAfterTitle" ?>
                        <?php echo $this->item->event->afterDisplayTitle; ?>




                        <?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>


                        <?php if ((int) $params->get('urls_position', 0) === 0) : ?>
                            <?php echo $this->loadTemplate('links'); ?>
                        <?php endif; ?>
                        <?php if ($params->get('access-view')) : ?>

                            <?php
                            if (!empty($this->item->pagination) && !$this->item->paginationposition && !$this->item->paginationrelative) :
                                echo $this->item->pagination;
                            endif;
                            ?>
                            <?php if (isset($this->item->toc)) :
                                echo $this->item->toc;
                            endif; ?>


                            <?php if ($info == 1 || $info == 2) : ?>
                                <?php if ($useDefList) : ?>
                                    <?php echo LayoutHelper::render('joomla.content.info_block', ['item' => $this->item, 'params' => $params, 'position' => 'below']); ?>
                                <?php endif; ?>

                            <?php endif; ?>

                            <?php
                            if (!empty($this->item->pagination) && $this->item->paginationposition && !$this->item->paginationrelative) :
                                echo $this->item->pagination;
                                ?>
                            <?php endif; ?>
                            <?php if ((int) $params->get('urls_position', 0) === 1) : ?>
                                <?php echo $this->loadTemplate('links'); ?>
                            <?php endif; ?>
                            <?php // Optional teaser intro text for guests ?>
                        <?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
                            <?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
                            <?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
                            <?php // Optional link to let them register to see the whole article. ?>
                            <?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
                                <?php $menu = Factory::getApplication()->getMenu(); ?>
                                <?php $active = $menu->getActive(); ?>
                                <?php $itemId = $active->id; ?>
                                <?php $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
                                <?php $link->setVar('return', base64_encode(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
                                <?php echo LayoutHelper::render('joomla.content.readmore', ['item' => $this->item, 'params' => $params, 'link' => $link]); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                        if (!empty($this->item->pagination) && $this->item->paginationposition && $this->item->paginationrelative) :
                            echo $this->item->pagination;
                            ?>
                        <?php endif; ?>
                        <?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
                        <?php echo $this->item->event->afterDisplayContent; ?>
                        <div class="meta-articolo" data-element="metadata"><small><strong>Pubblicato:</strong> <?php echo $pubblicazione ?> - <strong>Revisione:</strong> <?php echo $revisione ?></small></div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>
