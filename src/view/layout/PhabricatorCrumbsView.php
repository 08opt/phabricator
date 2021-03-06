<?php

final class PhabricatorCrumbsView extends AphrontView {

  private $crumbs = array();
  private $actions = array();

  protected function canAppendChild() {
    return false;
  }

  public function addCrumb(PhabricatorCrumbView $crumb) {
    $this->crumbs[] = $crumb;
    return $this;
  }


  public function addAction(PhabricatorMenuItemView $action) {
    $this->actions[] = $action;

    return $this;
  }

  public function render() {
    require_celerity_resource('phabricator-crumbs-view-css');

    $action_view = null;
    if ($this->actions) {
      $actions = array();
      foreach ($this->actions as $action) {
        $icon = null;
        if ($action->getIcon()) {
          $icon = phutil_render_tag(
            'span',
            array(
              'class' => 'sprite-icon action-'.$action->getIcon(),
            ),
            '');
        }
        $actions[] = phutil_render_tag(
          'a',
          array(
            'href' => $action->getHref(),
            'class' => 'phabricator-crumbs-action',
          ),
          $icon.phutil_escape_html($action->getName()));
      }

      $action_view = phutil_render_tag(
        'div',
        array(
          'class' => 'phabricator-crumbs-actions',
        ),
        self::renderSingleView($actions));
    }

    if ($this->crumbs) {
      last($this->crumbs)->setIsLastCrumb(true);
    }

    return phutil_render_tag(
      'div',
      array(
        'class' => 'phabricator-crumbs-view '.
                   'sprite-gradient gradient-breadcrumbs',
      ),
      $action_view.
      self::renderSingleView($this->crumbs));
  }

}
