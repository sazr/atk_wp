<?php

namespace atk_wp\views;

class Wizard extends \Atk4\Ui\Wizard {

  protected function getUrl(int $step): string
  {
      return $this->url([$this->urlTrigger => $step, 'atk_wp_wizard'=>true, 'page' => $_GET['page']]);
  }
}